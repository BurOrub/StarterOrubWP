<?php
/** Formati moderni: immagini AVIF (ripiego WebP), video WebM (ripiego mp4), favicon .ico e icone per Google, Android e Apple. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ============================================================ Percorsi */

/* Da URL di un file in uploads al percorso sul disco ('' se l'URL è di un altro sito o fuori da uploads). */
function starter_upload_path_from_url( $url ) {
	static $base = null;

	if ( null === $base ) {
		$upload = wp_get_upload_dir();
		$base   = array(
			'url' => preg_replace( '#^https?:#i', '', $upload['baseurl'] ),
			'dir' => $upload['basedir'],
		);
	}

	$url = preg_replace( '#^https?:#i', '', (string) strtok( (string) $url, '?#' ) );

	if ( 0 !== strpos( $url, $base['url'] . '/' ) ) {
		return '';
	}

	$rel = rawurldecode( substr( $url, strlen( $base['url'] ) ) );

	return false !== strpos( $rel, '..' ) ? '' : $base['dir'] . $rel;
}

/* ============================================================ Immagini AVIF */

/* Qualità AVIF (Opzioni → Prestazioni): 70 dà un peso ben più basso del WebP a 80 senza differenze visibili. */
function starter_avif_quality() {
	return max( 40, min( 90, (int) get_theme_mod( 'starter_perf_avif_quality', 70 ) ) );
}

/* Vero se il server sa creare AVIF (GD con libavif). */
function starter_avif_supported() {
	return function_exists( 'imageavif' );
}

/* URL dell'.avif che sta accanto a un'immagine (stesso nome), solo se il file esiste davvero; altrimenti ''. */
function starter_avif_url( $url ) {
	static $cache = array();

	$url = (string) $url;

	if ( isset( $cache[ $url ] ) ) {
		return $cache[ $url ];
	}

	$cache[ $url ] = '';

	if ( ! preg_match( '/\.(?:webp|jpe?g|png)(?:[?#].*)?$/i', $url ) ) {
		return '';
	}

	$file = starter_upload_path_from_url( $url );
	$avif = $file ? preg_replace( '/\.(?:webp|jpe?g|png)$/i', '.avif', $file ) : '';

	if ( $avif && is_file( $avif ) && filesize( $avif ) ) {
		$cache[ $url ] = preg_replace( '/\.(?:webp|jpe?g|png)(?=[?#]|$)/i', '.avif', $url );
	}

	return $cache[ $url ];
}

/**
 * Crea l'.avif accanto a un file immagine (stesso nome). Lo tiene solo se pesa almeno il 3% meno del file di partenza.
 * Non tocca favicon, logo delle email, file "nowebp" e SVG/GIF. Restituisce true se l'.avif esiste a fine lavoro.
 */
function starter_generate_avif( $file, $force = false ) {
	if ( ! $file || ! is_file( $file ) || ! starter_avif_supported() || ! preg_match( '/\.(webp|jpe?g|png)$/i', $file, $m ) || starter_keep_original_format( $file ) ) {
		return false;
	}

	$dest = preg_replace( '/\.(?:webp|jpe?g|png)$/i', '.avif', $file );

	if ( is_file( $dest ) && ! $force ) {
		return true;
	}

	if ( ! wp_getimagesize( $file ) ) {
		return false;
	}

	wp_raise_memory_limit( 'image' );

	$ext = strtolower( $m[1] );
	$img = false;

	// GD segnala con un avviso i file che non sa leggere (es. WebP animati): si tace e si salta il file.
	if ( 'webp' === $ext ) {
		$img = function_exists( 'imagecreatefromwebp' ) ? @imagecreatefromwebp( $file ) : false; // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
	} elseif ( 'png' === $ext ) {
		$img = @imagecreatefrompng( $file ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
	} else {
		$img = @imagecreatefromjpeg( $file ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
	}

	if ( ! $img ) {
		return false;
	}

	imagepalettetotruecolor( $img );
	imagealphablending( $img, false );
	imagesavealpha( $img, true );

	$tmp = $dest . '.tmp';
	$ok  = @imageavif( $img, $tmp, starter_avif_quality(), 6 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
	unset( $img );

	if ( ! $ok || ! is_file( $tmp ) || ! filesize( $tmp ) || filesize( $tmp ) >= filesize( $file ) * 0.97 ) {
		@unlink( $tmp ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged, WordPress.WP.AlternativeFunctions.unlink_unlink
		if ( $force && is_file( $dest ) ) {
			@unlink( $dest ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged, WordPress.WP.AlternativeFunctions.unlink_unlink
		}
		return false;
	}

	return rename( $tmp, $dest ); // phpcs:ignore WordPress.WP.AlternativeFunctions.rename_rename
}

/* Crea gli .avif dell'originale e di tutte le misure di un allegato. Restituisce quanti ne ha creati. */
function starter_generate_avif_for_attachment( $id, $force = false, $meta = null ) {
	$file = get_attached_file( $id );

	// Le icone del sito (Site Icon) restano PNG: le gestisce la parte "favicon" qui sotto.
	if ( ! $file || ! starter_avif_supported() || 'site-icon' === get_post_meta( $id, '_wp_attachment_context', true ) || ! preg_match( '/\.(webp|jpe?g|png)$/i', $file ) ) {
		return 0;
	}

	if ( function_exists( 'set_time_limit' ) ) {
		@set_time_limit( 120 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
	}

	$meta  = is_array( $meta ) ? $meta : wp_get_attachment_metadata( $id );
	$files = array( $file );

	foreach ( (array) ( $meta['sizes'] ?? array() ) as $size ) {
		if ( ! empty( $size['file'] ) ) {
			$files[] = path_join( dirname( $file ), $size['file'] );
		}
	}

	$count = 0;

	foreach ( array_unique( $files ) as $path ) {
		if ( starter_generate_avif( $path, $force ) ) {
			++$count;
		}
	}

	update_post_meta( $id, '_starter_avif', time() );

	return $count;
}

/* Ogni volta che WordPress salva le misure di un'immagine (caricamento, rigenerazione, modifica) si creano anche gli .avif mancanti. */
add_filter(
	'wp_update_attachment_metadata',
	function ( $data, $id ) {
		if ( is_array( $data ) && get_theme_mod( 'starter_perf_avif', true ) ) {
			starter_generate_avif_for_attachment( (int) $id, false, $data );
		}

		return $data;
	},
	20,
	2
);

/* Quando WordPress cancella un'immagine cancella anche il suo .avif. */
add_filter(
	'wp_delete_file',
	function ( $file ) {
		if ( is_string( $file ) && preg_match( '/\.(?:webp|jpe?g|png)$/i', $file ) ) {
			$avif = preg_replace( '/\.(?:webp|jpe?g|png)$/i', '.avif', $file );

			if ( is_file( $avif ) ) {
				@unlink( $avif ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged, WordPress.WP.AlternativeFunctions.unlink_unlink
			}
		}

		return $file;
	}
);

/* Interruttore: nelle email di WooCommerce niente <picture> (i programmi di posta non lo capiscono). */
function starter_picture_off( $set = null ) {
	static $off = false;

	if ( null !== $set ) {
		$off = (bool) $set;
	}

	return $off;
}
add_action(
	'woocommerce_email_header',
	function () {
		starter_picture_off( true );
	},
	1
);
add_action(
	'woocommerce_email_footer',
	function () {
		starter_picture_off( false );
	},
	99
);

/* Vero per anteprime dell'editor, feed e REST: lì le immagini restano <img> semplici. */
function starter_picture_skip() {
	if ( starter_picture_off() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || is_feed() ) {
		return true;
	}

	return is_admin() && ( ! wp_doing_ajax() ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- sola lettura del nome dell'azione.
}

/**
 * Avvolge ogni <img> di uploads in <picture> con la sorgente AVIF: il browser sceglie da solo (AVIF se lo legge, altrimenti l'<img> WebP).
 * Ignora le immagini già dentro un <picture> e quelle senza .avif sul disco. Opzione: Prestazioni → Immagini AVIF.
 */
function starter_picturefy( $html ) {
	if ( ! is_string( $html ) || false === stripos( $html, '<img' ) || ! get_theme_mod( 'starter_perf_avif', true ) || starter_picture_skip() ) {
		return $html;
	}

	return preg_replace_callback(
		'~<picture\b.*?</picture>|<img\b(?:[^>"\']|"[^"]*"|\'[^\']*\')*>~is',
		function ( $m ) {
			$tag = $m[0];

			if ( 0 === stripos( $tag, '<picture' ) ) {
				return $tag;
			}

			$p = new WP_HTML_Tag_Processor( $tag );

			if ( ! $p->next_tag( 'img' ) || null !== $p->get_attribute( 'data-no-avif' ) ) {
				return $tag;
			}

			$src    = (string) $p->get_attribute( 'src' );
			$srcset = (string) $p->get_attribute( 'srcset' );
			$sizes  = (string) $p->get_attribute( 'sizes' );
			$found  = array();

			if ( '' !== $srcset ) {
				foreach ( explode( ',', $srcset ) as $part ) {
					$bits = preg_split( '/\s+/', trim( $part ), 2 );
					$avif = $bits[0] ? starter_avif_url( $bits[0] ) : '';

					if ( $avif ) {
						$found[] = $avif . ( isset( $bits[1] ) ? ' ' . $bits[1] : '' );
					}
				}
			} elseif ( '' !== $src && starter_avif_url( $src ) ) {
				$found[] = starter_avif_url( $src );
			}

			if ( ! $found ) {
				return $tag;
			}

			return '<picture><source type="image/avif" srcset="' . esc_attr( implode( ', ', $found ) ) . '"' . ( '' !== $srcset && '' !== $sizes ? ' sizes="' . esc_attr( $sizes ) . '"' : '' ) . '>' . $tag . '</picture>';
		},
		$html
	);
}
add_filter( 'wp_get_attachment_image', 'starter_picturefy', 20 );
add_filter( 'the_content', 'starter_picturefy', 20 );
add_filter( 'starter_field_content', 'starter_picturefy', 20 );
add_filter( 'widget_block_content', 'starter_picturefy', 20 );

/* ============================================================ Video WebM */

/**
 * Righe <source> di un video: se accanto all'mp4 c'è un .webm con lo stesso nome (o viceversa) mette prima il WebM, poi l'mp4.
 * Il browser usa il primo formato che sa leggere. Senza file gemello restituisce la sola sorgente originale. Opzione: Prestazioni → Video WebM.
 */
function starter_video_sources( $url ) {
	$url = (string) $url;

	if ( '' === $url ) {
		return '';
	}

	$list = array( $url );

	if ( get_theme_mod( 'starter_perf_webm', true ) && preg_match( '/\.(mp4|webm)(?:[?#].*)?$/i', $url, $m ) ) {
		$other = 'mp4' === strtolower( $m[1] ) ? 'webm' : 'mp4';
		$alt   = preg_replace( '/\.(?:mp4|webm)(?=[?#]|$)/i', '.' . $other, $url );
		$file  = starter_upload_path_from_url( $alt );

		if ( $file && is_file( $file ) ) {
			$list = 'webm' === $other ? array( $alt, $url ) : array( $url, $alt );
		}
	}

	$out = '';

	foreach ( $list as $item ) {
		$type = preg_match( '/\.webm(?:[?#].*)?$/i', $item ) ? 'video/webm' : ( preg_match( '/\.mp4(?:[?#].*)?$/i', $item ) ? 'video/mp4' : '' );
		$out .= '<source src="' . esc_url( $item ) . '"' . ( $type ? ' type="' . $type . '"' : '' ) . '>';
	}

	return $out;
}

/* Blocchi Video, Cover e Media e testo di Gutenberg: se esiste il file gemello, il <video src="…"> diventa <video><source webm><source mp4></video>. */
add_filter(
	'render_block',
	function ( $content, $block ) {
		if ( ! in_array( $block['blockName'] ?? '', array( 'core/video', 'core/cover', 'core/media-text' ), true ) || false === stripos( $content, '<video' ) || ! get_theme_mod( 'starter_perf_webm', true ) ) {
			return $content;
		}

		return preg_replace_callback(
			'~<video\b((?:[^>"\']|"[^"]*"|\'[^\']*\')*)>(.*?)</video>~is',
			function ( $m ) {
				if ( ! preg_match( '/\ssrc=(?:"([^"]*)"|\'([^\']*)\')/i', $m[1], $s ) ) {
					return $m[0];
				}

				$sources = starter_video_sources( html_entity_decode( '' !== $s[1] ? $s[1] : ( $s[2] ?? '' ) ) );

				if ( substr_count( $sources, '<source' ) < 2 ) {
					return $m[0];
				}

				return '<video' . str_replace( $s[0], '', $m[1] ) . '>' . $sources . $m[2] . '</video>';
			},
			$content
		);
	},
	10,
	2
);

/* ============================================================ Favicon e icone */

/* Cartella delle icone generate (uploads/starter-icons). */
function starter_icons_dir() {
	$upload = wp_get_upload_dir();

	return array(
		'path' => $upload['basedir'] . '/starter-icons',
		'url'  => $upload['baseurl'] . '/starter-icons',
	);
}

/* Immagine quadrata (centrata, ritagliata) di lato $side con sfondo trasparente, come dati PNG; '' se non riesce. */
function starter_icon_png( $source, $side ) {
	$dst = imagecreatetruecolor( $side, $side );
	imagealphablending( $dst, false );
	imagesavealpha( $dst, true );
	imagefill( $dst, 0, 0, imagecolorallocatealpha( $dst, 0, 0, 0, 127 ) );

	$w    = imagesx( $source );
	$h    = imagesy( $source );
	$edge = min( $w, $h );

	imagecopyresampled( $dst, $source, 0, 0, (int) ( ( $w - $edge ) / 2 ), (int) ( ( $h - $edge ) / 2 ), $side, $side, $edge, $edge );

	ob_start();
	imagepng( $dst, null, 9 );

	return (string) ob_get_clean();
}

/* Contenitore .ico con immagini PNG già pronte (array lato => dati PNG): lo leggono tutti i browser moderni. */
function starter_build_ico( array $pngs ) {
	$head  = pack( 'vvv', 0, 1, count( $pngs ) );
	$dirs  = '';
	$data  = '';
	$start = 6 + 16 * count( $pngs );

	foreach ( $pngs as $side => $png ) {
		$dirs .= pack( 'CCCCvvVV', $side >= 256 ? 0 : $side, $side >= 256 ? 0 : $side, 0, 0, 1, 32, strlen( $png ), $start + strlen( $data ) );
		$data .= $png;
	}

	return $head . $dirs . $data;
}

/**
 * Dal Site Icon (Aspetto → Personalizza → Identità del sito, 512×512) genera in uploads/starter-icons: favicon.ico (16, 32, 48, 256 px),
 * icon-192.png e icon-512.png (Google e Android), apple-touch-icon.png (180 px, Apple) e site.webmanifest. Sono sempre PNG/ICO,
 * qualunque sia il formato del file caricato. Rigenera solo se l'icona è cambiata.
 */
function starter_generate_icons( $force = false ) {
	$id = (int) get_option( 'site_icon' );

	if ( ! $id || ! function_exists( 'imagecreatetruecolor' ) ) {
		return false;
	}

	$file = get_attached_file( $id );
	$dir  = starter_icons_dir();

	if ( ! $file || ! is_file( $file ) ) {
		return false;
	}

	$signature = md5( $file . filemtime( $file ) . filesize( $file ) . get_bloginfo( 'name' ) . $dir['url'] );

	if ( ! $force && get_option( 'starter_icons_signature' ) === $signature && is_file( $dir['path'] . '/favicon.ico' ) && is_file( $dir['path'] . '/site.webmanifest' ) ) {
		return true;
	}

	if ( ! wp_mkdir_p( $dir['path'] ) ) {
		return false;
	}

	wp_raise_memory_limit( 'image' );

	$type   = wp_check_filetype( $file );
	$source = 'image/png' === $type['type'] ? @imagecreatefrompng( $file ) : ( 'image/webp' === $type['type'] ? @imagecreatefromwebp( $file ) : ( 'image/jpeg' === $type['type'] ? @imagecreatefromjpeg( $file ) : false ) ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged

	if ( ! $source ) {
		return false;
	}

	imagepalettetotruecolor( $source );

	$ico = array();

	foreach ( array( 16, 32, 48, 256 ) as $side ) {
		$ico[ $side ] = starter_icon_png( $source, $side );
	}

	$files = array(
		'favicon.ico'          => starter_build_ico( $ico ),
		'icon-192.png'         => starter_icon_png( $source, 192 ),
		'icon-512.png'         => starter_icon_png( $source, 512 ),
		'apple-touch-icon.png' => starter_icon_png( $source, 180 ),
		'site.webmanifest'     => wp_json_encode(
			array(
				'name'             => get_bloginfo( 'name' ),
				'short_name'       => get_bloginfo( 'name' ),
				'start_url'        => home_url( '/' ),
				'display'          => 'standalone',
				'background_color' => '#ffffff',
				'theme_color'      => '#ffffff',
				'icons'            => array(
					array( 'src' => $dir['url'] . '/icon-192.png', 'sizes' => '192x192', 'type' => 'image/png', 'purpose' => 'any' ),
					array( 'src' => $dir['url'] . '/icon-512.png', 'sizes' => '512x512', 'type' => 'image/png', 'purpose' => 'any' ),
				),
			),
			JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
		),
	);

	unset( $source );

	foreach ( $files as $name => $content ) {
		if ( '' === $content || false === file_put_contents( $dir['path'] . '/' . $name, $content ) ) { // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
			return false;
		}
	}

	update_option( 'starter_icons_signature', $signature );

	return true;
}

/* Vero se le icone sono pronte e l'opzione è accesa. */
function starter_icons_ready() {
	if ( ! get_theme_mod( 'starter_perf_favicon', true ) || ! get_option( 'site_icon' ) ) {
		return false;
	}

	$dir = starter_icons_dir();

	return is_file( $dir['path'] . '/favicon.ico' ) && is_file( $dir['path'] . '/site.webmanifest' );
}

/* Genera le icone quando cambia il Site Icon, al salvataggio del Personalizza e (se mancano) entrando in amministrazione. */
add_action(
	'customize_save_after',
	function () {
		if ( get_theme_mod( 'starter_perf_favicon', true ) ) {
			starter_generate_icons();
		}
	}
);
foreach ( array( 'add_option_site_icon', 'update_option_site_icon' ) as $starter_icon_hook ) {
	add_action(
		$starter_icon_hook,
		function () {
			if ( get_theme_mod( 'starter_perf_favicon', true ) ) {
				starter_generate_icons( true );
			}
		}
	);
}
unset( $starter_icon_hook );
add_action(
	'admin_init',
	function () {
		if ( get_theme_mod( 'starter_perf_favicon', true ) && get_option( 'site_icon' ) ) {
			starter_generate_icons();
		}
	}
);

/* Nell'head: al posto delle icone di WordPress, favicon.ico + PNG 192 (Google e Android), apple-touch-icon e manifest. */
add_filter(
	'site_icon_meta_tags',
	function ( $tags ) {
		if ( ! starter_icons_ready() ) {
			return $tags;
		}

		$dir = starter_icons_dir();
		$ver = '?v=' . substr( (string) get_option( 'starter_icons_signature' ), 0, 8 );

		return array(
			'<link rel="icon" href="' . esc_url( $dir['url'] . '/favicon.ico' . $ver ) . '" sizes="16x16 32x32 48x48 256x256">',
			'<link rel="icon" type="image/png" href="' . esc_url( $dir['url'] . '/icon-192.png' . $ver ) . '" sizes="192x192">',
			'<link rel="apple-touch-icon" href="' . esc_url( $dir['url'] . '/apple-touch-icon.png' . $ver ) . '" sizes="180x180">',
			'<link rel="manifest" href="' . esc_url( $dir['url'] . '/site.webmanifest' . $ver ) . '">',
		);
	}
);

/* Chi chiede /favicon.ico alla radice del sito (molti browser e robot lo fanno senza leggere l'head) riceve il file .ico generato. */
add_action(
	'do_faviconico',
	function () {
		if ( ! starter_icons_ready() ) {
			return;
		}

		$dir = starter_icons_dir();

		header( 'Content-Type: image/x-icon' );
		header( 'Cache-Control: public, max-age=604800' );
		readfile( $dir['path'] . '/favicon.ico' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_readfile
		exit;
	},
	1
);
