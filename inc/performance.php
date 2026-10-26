<?php
/** Prestazioni: immagini WebP, pulizia dell'head, script solo dove servono. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Vero se il file è il favicon: nome con "favicon", oppure il Site Icon impostato in
 * Personalizza → Identità del sito (che online diventa i vari <link rel="icon">/apple-touch-icon
 * in <head>). In entrambi i casi deve restare .png: alcuni browser e Google Search non
 * riconoscono un'icona .webp, un nome file non è garanzia sufficiente da solo.
 */
function starter_is_favicon_file( $filename ) {
	if ( stripos( (string) $filename, 'favicon' ) !== false ) {
		return true;
	}

	$site_icon_id = get_option( 'site_icon' );

	if ( ! $site_icon_id || ! $filename ) {
		return false;
	}

	$site_icon_file = get_attached_file( $site_icon_id );

	return $site_icon_file && wp_basename( $site_icon_file ) === wp_basename( (string) $filename );
}

/**
 * Vero se il file deve restare nel formato originale (niente WebP):
 * - il favicon (vedi sopra);
 * - il logo delle email di WooCommerce (Outlook desktop non mostra i WebP): è il file impostato in
 *   WooCommerce → Impostazioni → Email → Immagine dell'intestazione, oppure un file con "email" nel nome;
 * - qualsiasi file con "nowebp" nel nome.
 */
function starter_keep_original_format( $filename ) {
	$filename = (string) $filename;

	if ( starter_is_favicon_file( $filename ) || preg_match( '/email|nowebp/i', wp_basename( $filename ) ) ) {
		return true;
	}

	$email_logo = (string) get_option( 'woocommerce_email_header_image' );

	return $email_logo && $filename && wp_basename( wp_parse_url( $email_logo, PHP_URL_PATH ) ) === wp_basename( $filename );
}

/**
 * Quando WordPress crea le misure ridotte non passa il nome del file al filtro del formato: si ricorda qui se l'immagine in lavorazione
 * deve restare nel formato originale (favicon, logo email, "nowebp"), impostato da "intermediate_image_sizes_advanced" qui sotto.
 */
function starter_keep_format_flag( $set = null ) {
	static $keep = false;

	if ( null !== $set ) {
		$keep = (bool) $set;
	}

	return $keep;
}
add_filter(
	'intermediate_image_sizes_advanced',
	function ( $sizes, $meta = array(), $id = 0 ) {
		$file = $id ? get_attached_file( $id ) : '';

		starter_keep_format_flag( $file && starter_keep_original_format( $file ) );

		return $sizes;
	},
	5,
	3
);
add_filter(
	'wp_generate_attachment_metadata',
	function ( $meta ) {
		starter_keep_format_flag( false );

		return $meta;
	},
	99
);

/* Immagini: sottodimensioni in WebP, eccetto favicon e logo delle email (restano nel formato originale) */
add_filter(
	'image_editor_output_format',
	function ( $formats, $filename = '' ) {
		if ( starter_keep_original_format( $filename ) || ( '' === (string) $filename && starter_keep_format_flag() ) ) {
			return $formats;
		}
		$formats['image/jpeg'] = 'image/webp';
		$formats['image/png']  = 'image/webp';
		return $formats;
	},
	10,
	2
);
add_filter( 'wp_editor_set_quality', function () {
	return 80;
} );
/**
 * Converte in WebP (qualità 80) un file JPG/PNG, girandolo secondo l'EXIF delle foto da telefono.
 * Restituisce il percorso del .webp oppure '' se non si può (favicon, logo email, formato non supportato, errore).
 */
function starter_convert_to_webp( $file ) {
	if ( ! $file || ! file_exists( $file ) || starter_keep_original_format( $file ) ) {
		return '';
	}

	$type = wp_check_filetype( $file );

	if ( ! in_array( $type['type'], array( 'image/jpeg', 'image/png' ), true ) ) {
		return '';
	}

	$editor = wp_get_image_editor( $file );

	if ( is_wp_error( $editor ) || ! $editor->supports_mime_type( 'image/webp' ) ) {
		return '';
	}

	$editor->maybe_exif_rotate();
	$editor->set_quality( 80 );

	$dir   = dirname( $file );
	$name  = pathinfo( $file, PATHINFO_FILENAME ) . '.webp';
	$name  = file_exists( $dir . '/' . $name ) ? wp_unique_filename( $dir, $name ) : $name;
	$saved = $editor->save( $dir . '/' . $name, 'image/webp' );

	return is_wp_error( $saved ) || empty( $saved['path'] ) ? '' : $saved['path'];
}

/* Anche l'originale caricato diventa WebP (non solo le misure ridotte): ogni immagine del sito è WebP. Il JPG/PNG caricato viene sostituito. */
add_filter(
	'wp_handle_upload',
	function ( $upload ) {
		if ( empty( $upload['file'] ) || empty( $upload['type'] ) || ! in_array( $upload['type'], array( 'image/jpeg', 'image/png' ), true ) ) {
			return $upload;
		}

		$webp = starter_convert_to_webp( $upload['file'] );

		if ( ! $webp ) {
			return $upload;
		}

		wp_delete_file( $upload['file'] );

		$upload['url']  = trailingslashit( dirname( $upload['url'] ) ) . wp_basename( $webp );
		$upload['file'] = $webp;
		$upload['type'] = 'image/webp';

		return $upload;
	}
);

add_filter( 'big_image_size_threshold', function () {
	return 2000;
} );
add_filter(
	'intermediate_image_sizes_advanced',
	function ( $sizes ) {
		unset( $sizes['2048x2048'] );
		return $sizes;
	}
);

/* decoding="async" su tutte le immagini */
add_filter(
	'wp_get_attachment_image_attributes',
	function ( $attr ) {
		if ( empty( $attr['decoding'] ) ) {
			$attr['decoding'] = 'async';
		}
		return $attr;
	}
);

/* Precarica i font del tema dichiarati in theme.json (se locali): niente flash di testo non stilizzato. */
function starter_preload() {
	$fonts = wp_get_global_settings( array( 'typography', 'fontFamilies' ) );

	foreach ( ( $fonts['theme'] ?? array() ) as $font ) {
		foreach ( $font['fontFace'] ?? array() as $face ) {
			foreach ( (array) ( $face['src'] ?? array() ) as $src ) {
				if ( 0 !== strpos( $src, 'file:./' ) ) {
					continue;
				}
				printf(
					'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
					esc_url( get_theme_file_uri( substr( $src, 7 ) ) )
				);
			}
		}
	}
}
add_action( 'wp_head', 'starter_preload', 2 );

/* Pulizia dell'head e degli asset di WordPress non usati (wp_generator/wlwmanifest/rsd già rimossi da starter_theme_cleanup_head in functions.php). */
function starter_clean_head() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	remove_action( 'wp_head', 'rest_output_link_wp_head' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	remove_action( 'wp_head', 'feed_links', 2 );
	remove_action( 'wp_head', 'feed_links_extra', 3 );
	add_filter( 'emoji_svg_url', '__return_false' );
	add_filter( 'should_load_separate_core_block_assets', '__return_true' );
}
add_action( 'init', 'starter_clean_head', 5 );

/* Toglie stili e script di WordPress che il tema non usa */
function starter_dequeue_assets() {
	wp_dequeue_style( 'classic-theme-styles' );
	wp_deregister_script( 'wp-embed' );
}
add_action( 'wp_enqueue_scripts', 'starter_dequeue_assets', 100 );

/* ==========================================================================
   Prestazioni avanzate: script in defer, collegamenti anticipati, Contact Form 7 solo dove serve,
   immagine della hero precaricata, compressione e cache del browser (.htaccess).
   Opzioni in Personalizza → Opzioni → Prestazioni.
   ========================================================================== */

/* Script del tema e librerie delle animazioni in "defer": non bloccano il disegno della pagina. */
add_filter(
	'script_loader_tag',
	function ( $tag, $handle, $src ) {
		if ( is_admin() || false !== strpos( $tag, ' defer' ) || false !== strpos( $tag, ' async' ) ) {
			return $tag;
		}

		if ( in_array( $handle, array( 'starter-theme-scripts', 'gsap', 'lenis', 'starter-animations' ), true ) ) {
			return str_replace( ' src=', ' defer src=', $tag );
		}

		return $tag;
	},
	10,
	3
);

/* Collegamento anticipato ai servizi esterni davvero usati (Google Fonts, librerie delle animazioni). */
add_filter(
	'wp_resource_hints',
	function ( $urls, $relation ) {
		if ( 'preconnect' !== $relation || is_admin() ) {
			return $urls;
		}

		if ( function_exists( 'starter_get_active_typography_fonts' ) ) {
			foreach ( starter_get_active_typography_fonts() as $font ) {
				if ( 'google' === $font['type'] ) {
					$urls[] = 'https://fonts.googleapis.com';
					$urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' => 'anonymous' );
					break;
				}
			}
		}

		return $urls;
	},
	10,
	2
);

/* jQuery Migrate serve solo a codice molto vecchio: fuori dal sito pubblico. */
add_action(
	'wp_default_scripts',
	function ( $scripts ) {
		if ( ! is_admin() && isset( $scripts->registered['jquery'] ) && get_theme_mod( 'starter_perf_no_migrate', true ) ) {
			$scripts->registered['jquery']->deps = array_diff( $scripts->registered['jquery']->deps, array( 'jquery-migrate' ) );
		}
	}
);

/**
 * Contact Form 7: CSS e JS solo nelle pagine che hanno un modulo (nel testo, in un blocco del tema o nel footer).
 * Spegnibile da Opzioni → Prestazioni.
 */
function starter_page_has_form() {
	static $cache = null;

	if ( null !== $cache ) {
		return $cache;
	}

	$cache = false;

	// Il form newsletter nello "Spazio libero" del footer compare in tutte le pagine.
	if ( false !== strpos( (string) get_theme_mod( 'starter_footer_newsletter_shortcode', '' ), '[contact-form-7' ) ) {
		return $cache = true;
	}

	if ( ! is_singular() ) {
		return $cache;
	}

	$post = get_post();

	if ( $post ) {
		$content = (string) $post->post_content;
		$cache   = false !== strpos( $content, '[contact-form-7' ) || false !== strpos( $content, 'wp:starter-theme/contatti' ) || false !== strpos( $content, 'wp:contact-form-7' );
	}

	return $cache;
}

add_filter(
	'wpcf7_load_js',
	function ( $load ) {
		return get_theme_mod( 'starter_perf_cf7_lazy', true ) ? starter_page_has_form() : $load;
	}
);
add_filter(
	'wpcf7_load_css',
	function ( $load ) {
		return get_theme_mod( 'starter_perf_cf7_lazy', true ) ? starter_page_has_form() : $load;
	}
);

/**
 * Immagine di apertura (LCP) precaricata: se la pagina comincia con una hero con foto, il browser la scarica subito
 * (con la misura giusta per lo schermo) invece di scoprirla a pagina disegnata.
 */
function starter_preload_hero_image() {
	if ( ! is_singular() || ! get_theme_mod( 'starter_perf_preload_hero', true ) ) {
		return;
	}

	$post = get_post();

	if ( ! $post ) {
		return;
	}

	$blocks = array_values(
		array_filter(
			parse_blocks( (string) $post->post_content ),
			function ( $block ) {
				return ! empty( $block['blockName'] );
			}
		)
	);

	if ( ! $blocks ) {
		return;
	}

	$first = $blocks[0];
	$data  = $first['attrs']['data'] ?? array();
	$id    = 0;

	switch ( $first['blockName'] ) {
		case 'starter-theme/hero-slider':
			$images = $data['immagini'] ?? array();
			$id     = is_array( $images ) ? (int) reset( $images ) : 0;
			break;
		case 'starter-theme/hero-video':
			$id = (int) ( $data['copertina'] ?? 0 );
			break;
		case 'starter-theme/hero-media':
			$id = (int) ( $data['foto'] ?? 0 );
			break;
		case 'starter-theme/hero-split':
			$id = (int) ( $data['foto'] ?? 0 );
			break;
	}

	if ( ! $id ) {
		return;
	}

	$size   = 'starter-theme/hero-slider' === $first['blockName'] || 'starter-theme/hero-video' === $first['blockName'] ? 'starter-hero' : 'large';
	$src    = wp_get_attachment_image_url( $id, $size );
	$srcset = wp_get_attachment_image_srcset( $id, $size );

	if ( $src ) {
		$type = '';

		// Se la foto ha la versione AVIF (e la pagina la userà in <picture>) si precarica quella: chi non legge AVIF salta il preload e scarica il WebP normalmente.
		if ( get_theme_mod( 'starter_perf_avif', true ) && starter_avif_url( $src ) ) {
			$avif_set = array();

			foreach ( explode( ',', (string) $srcset ) as $part ) {
				$bits = preg_split( '/\s+/', trim( $part ), 2 );
				$avif = $bits[0] ? starter_avif_url( $bits[0] ) : '';

				if ( $avif ) {
					$avif_set[] = $avif . ( isset( $bits[1] ) ? ' ' . $bits[1] : '' );
				}
			}

			$src    = starter_avif_url( $src );
			$srcset = implode( ', ', $avif_set );
			$type   = ' type="image/avif"';
		}

		printf(
			'<link rel="preload" as="image" href="%s"%s%s imagesizes="100vw" fetchpriority="high">' . "\n",
			esc_url( $src ),
			$type, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- costante.
			$srcset ? ' imagesrcset="' . esc_attr( $srcset ) . '"' : ''
		);
	}
}
add_action( 'wp_head', 'starter_preload_hero_image', 3 );

/* ----------------------------------------------------------------- .htaccess */

/**
 * Righe da scrivere nel .htaccess: compressione (Brotli e Gzip), cache lunga del browser per file statici e immagini,
 * tipi dei file moderni. L'HTML non viene messo in cache. Funziona su Apache e LiteSpeed (es. Hostinger); su nginx
 * (es. Local by Flywheel) il file è ignorato.
 */
function starter_htaccess_rules() {
	$types = 'text/html text/plain text/css text/javascript application/javascript application/x-javascript application/json application/xml application/rss+xml application/atom+xml application/xhtml+xml image/svg+xml image/x-icon font/ttf font/otf font/woff font/woff2 application/vnd.ms-fontobject application/x-font-ttf application/x-font-opentype application/manifest+json';

	return array(
		'# Compressione dei file di testo (Brotli se disponibile, altrimenti Gzip).',
		'<IfModule mod_brotli.c>',
		"\tAddOutputFilterByType BROTLI_COMPRESS " . $types,
		'</IfModule>',
		'<IfModule mod_deflate.c>',
		"\tAddOutputFilterByType DEFLATE " . $types,
		"\tBrowserMatch ^Mozilla/4 gzip-only-text/html",
		"\tBrowserMatch ^Mozilla/4\\.0[678] no-gzip",
		"\tBrowserMatch \\bMSIE !no-gzip !gzip-only-text/html",
		'</IfModule>',
		'<IfModule mod_headers.c>',
		"\tHeader append Vary Accept-Encoding",
		'</IfModule>',
		'',
		'# Tipi dei file moderni.',
		'<IfModule mod_mime.c>',
		"\tAddType image/webp .webp",
		"\tAddType image/avif .avif",
		"\tAddType font/woff2 .woff2",
		"\tAddType video/mp4 .mp4",
		"\tAddType video/webm .webm",
		"\tAddType image/x-icon .ico",
		"\tAddType application/manifest+json .webmanifest",
		'</IfModule>',
		'',
		'# Cache lunga nel browser: i file del tema hanno la versione nell\'indirizzo (?ver=), le immagini hanno nomi unici.',
		'<IfModule mod_expires.c>',
		"\tExpiresActive On",
		"\tExpiresByType text/css \"access plus 1 year\"",
		"\tExpiresByType text/javascript \"access plus 1 year\"",
		"\tExpiresByType application/javascript \"access plus 1 year\"",
		"\tExpiresByType image/webp \"access plus 1 year\"",
		"\tExpiresByType image/avif \"access plus 1 year\"",
		"\tExpiresByType image/jpeg \"access plus 1 year\"",
		"\tExpiresByType image/png \"access plus 1 year\"",
		"\tExpiresByType image/gif \"access plus 1 year\"",
		"\tExpiresByType image/svg+xml \"access plus 1 year\"",
		"\tExpiresByType image/x-icon \"access plus 1 week\"",
		"\tExpiresByType application/manifest+json \"access plus 1 week\"",
		"\tExpiresByType font/woff2 \"access plus 1 year\"",
		"\tExpiresByType font/woff \"access plus 1 year\"",
		"\tExpiresByType font/ttf \"access plus 1 year\"",
		"\tExpiresByType video/mp4 \"access plus 1 month\"",
		"\tExpiresByType video/webm \"access plus 1 month\"",
		"\tExpiresByType application/json \"access plus 0 seconds\"",
		"\tExpiresByType text/html \"access plus 0 seconds\"",
		'</IfModule>',
		'<IfModule mod_headers.c>',
		"\t<FilesMatch \"\\.(css|js|webp|avif|jpe?g|png|gif|svg|woff2?|ttf|mp4|webm)$\">",
		"\t\tHeader set Cache-Control \"public, max-age=31536000\"",
		"\t</FilesMatch>",
		"\t<FilesMatch \"\\.(ico)$\">",
		"\t\tHeader set Cache-Control \"public, max-age=604800\"",
		"\t</FilesMatch>",
		'</IfModule>',
	);
}

/* Scrive (o toglie) il blocco nel .htaccess del sito, senza toccare le regole di WordPress o di altri plugin. Restituisce true se il file è stato aggiornato. */
function starter_apply_htaccess( $enable = null ) {
	global $is_apache;

	$enable = null === $enable ? (bool) get_theme_mod( 'starter_perf_htaccess', true ) : (bool) $enable;

	if ( ! function_exists( 'insert_with_markers' ) ) {
		require_once ABSPATH . 'wp-admin/includes/misc.php';
	}

	if ( ! function_exists( 'get_home_path' ) ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
	}

	$file = trailingslashit( get_home_path() ) . '.htaccess';

	// Solo su server che leggono il .htaccess (Apache, LiteSpeed) e se il file è scrivibile (o può essere creato).
	if ( ! $is_apache || ( file_exists( $file ) ? ! wp_is_writable( $file ) : ! wp_is_writable( dirname( $file ) ) ) ) {
		return false;
	}

	return (bool) insert_with_markers( $file, 'STARTER_PERFORMANCE', $enable ? starter_htaccess_rules() : array() );
}

/* Applica le regole quando si salva il Personalizza, quando si attiva il tema e, una volta per versione delle regole, in amministrazione. */
add_action(
	'customize_save_after',
	function () {
		starter_apply_htaccess();
	}
);
add_action(
	'after_switch_theme',
	function () {
		starter_apply_htaccess();
	}
);
add_action(
	'admin_init',
	function () {
		$signature = md5( wp_json_encode( starter_htaccess_rules() ) . (int) get_theme_mod( 'starter_perf_htaccess', true ) );

		if ( get_option( 'starter_htaccess_signature' ) === $signature ) {
			return;
		}

		if ( starter_apply_htaccess() ) {
			update_option( 'starter_htaccess_signature', $signature );
		}
	}
);
