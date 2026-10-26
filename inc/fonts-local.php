<?php
/**
 * Google Fonts in locale: i font scelti in Opzioni → Tipografia (Font 1 e Font 2) vengono scaricati una volta
 * dai server di Google e serviti dal tuo sito (cartella uploads/starter-fonts), come nei siti Di Guida e Symbolikamente.
 *
 * Perché: niente richiesta a un sito esterno (più veloce, niente collegamento a Google, meglio per la privacy/GDPR),
 * `font-display: swap`, file WOFF2 con lo stesso nome per sempre e cache lunga, precaricamento del file "latin".
 * Se il download non riesce, il tema continua a usare i Google Fonts come prima: il sito non resta mai senza font.
 * Solo i sottoinsiemi "latin" e "latin-ext" (italiano, lingue europee); gli altri alfabeti restano su Google se servono.
 *
 * Opzione: Opzioni → Prestazioni → "Google Fonts serviti dal tuo sito" (accesa di base).
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Vero se l'opzione è accesa. */
function starter_local_fonts_enabled() {
	return (bool) get_theme_mod( 'starter_perf_local_fonts', true );
}

/* Famiglie Google attive (Font 1 e Font 2), senza doppioni, in ordine alfabetico. */
function starter_google_families() {
	$families = array();

	if ( function_exists( 'starter_get_active_typography_fonts' ) ) {
		foreach ( starter_get_active_typography_fonts() as $font ) {
			if ( 'google' === $font['type'] ) {
				$families[ $font['family'] ] = $font['family'];
			}
		}
	}

	// Font serif della scritta grande del footer (Stile 1 senza logo chiaro).
	if ( function_exists( 'starter_footer_wordmark_font_needed' ) && starter_footer_wordmark_font_needed() ) {
		$families['Fraunces'] = 'Fraunces';
	}

	ksort( $families );

	return array_values( $families );
}

/* Cartella e indirizzo dei file scaricati. */
function starter_local_fonts_paths() {
	$uploads = wp_upload_dir();

	return array(
		'dir' => trailingslashit( $uploads['basedir'] ) . 'starter-fonts',
		'url' => trailingslashit( $uploads['baseurl'] ) . 'starter-fonts',
	);
}

/**
 * Scarica i font attivi. Restituisce true se tutto è andato a buon fine (e salva CSS e file da precaricare nell'opzione
 * "starter_local_fonts"), false se qualcosa non è riuscito (in quel caso non cambia nulla e restano i Google Fonts).
 */
function starter_local_fonts_download() {
	$families = starter_google_families();

	if ( ! $families ) {
		delete_option( 'starter_local_fonts' );
		return true;
	}

	$paths = starter_local_fonts_paths();

	if ( ! wp_mkdir_p( $paths['dir'] ) ) {
		return false;
	}

	$css_out   = '';
	$preload   = array();
	$preloaded = array();

	foreach ( $families as $family ) {
		// Font variabili: un solo file per tutti i pesi. Font statici (es. Ubuntu): si prova con i pesi più usati, dal più ricco al più semplice.
		$ua  = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0 Safari/537.36';
		$res = null;

		foreach ( array( ':wght@100..900', ':wght@300;400;500;600;700', ':wght@400;500;700', ':wght@400;700', '' ) as $axis ) {
			$res = wp_remote_get( 'https://fonts.googleapis.com/css2?family=' . rawurlencode( $family ) . $axis . '&display=swap', array( 'timeout' => 20, 'user-agent' => $ua ) );

			if ( ! is_wp_error( $res ) && 200 === (int) wp_remote_retrieve_response_code( $res ) ) {
				break;
			}
		}

		if ( is_wp_error( $res ) || 200 !== (int) wp_remote_retrieve_response_code( $res ) ) {
			return false;
		}

		$css = (string) wp_remote_retrieve_body( $res );

		if ( ! preg_match_all( '#/\*\s*([\w-]+)\s*\*/\s*@font-face\s*\{(.*?)\}#s', $css, $faces, PREG_SET_ORDER ) ) {
			return false;
		}

		$kept = 0;

		foreach ( $faces as $face ) {
			$subset = $face[1];

			if ( ! in_array( $subset, array( 'latin', 'latin-ext' ), true ) ) {
				continue;
			}

			if ( ! preg_match( '#url\((https://fonts\.gstatic\.com/[^)]+)\)#', $face[2], $src ) ) {
				continue;
			}

			$style = preg_match( '#font-style:\s*([a-z]+)#', $face[2], $m ) ? $m[1] : 'normal';
			$name  = sanitize_file_name( strtolower( $family ) . '-' . $style . '-' . $subset . '-' . substr( md5( $src[1] ), 0, 8 ) . '.woff2' );
			$file  = $paths['dir'] . '/' . $name;

			if ( ! file_exists( $file ) ) {
				$font = wp_remote_get( $src[1], array( 'timeout' => 30 ) );

				if ( is_wp_error( $font ) || 200 !== (int) wp_remote_retrieve_response_code( $font ) || '' === wp_remote_retrieve_body( $font ) ) {
					return false;
				}

				file_put_contents( $file, wp_remote_retrieve_body( $font ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
			}

			$block = str_replace( $src[1], $paths['url'] . '/' . $name, $face[2] );
			$block = preg_replace( '#font-display:\s*[a-z]+;?#', '', $block );

			$css_out .= '@font-face{' . preg_replace( '#\s+#', ' ', trim( $block ) ) . ' font-display:swap;}' . "\n";
			++$kept;

			// Si precarica un solo file latin per famiglia (il primo, in tondo).
			if ( 'latin' === $subset && 'normal' === $style && empty( $preloaded[ $family ] ) ) {
				$preload[]            = $paths['url'] . '/' . $name;
				$preloaded[ $family ] = true;
			}
		}

		if ( ! $kept ) {
			return false;
		}
	}

	update_option(
		'starter_local_fonts',
		array(
			'families' => $families,
			'css'      => $css_out,
			'preload'  => $preload,
		),
		false
	);

	return true;
}

/* Font locali pronti per le famiglie attive adesso? Restituisce l'array salvato oppure vuoto. */
function starter_local_fonts_ready() {
	if ( ! starter_local_fonts_enabled() ) {
		return array();
	}

	$saved = get_option( 'starter_local_fonts' );

	if ( ! is_array( $saved ) || empty( $saved['css'] ) || starter_google_families() !== ( $saved['families'] ?? array() ) ) {
		return array();
	}

	return $saved;
}

/* Scarica al salvataggio del Personalizza e, se serve, entrando in amministrazione (al massimo un tentativo ogni ora se fallisce). */
add_action(
	'customize_save_after',
	function () {
		if ( starter_local_fonts_enabled() ) {
			delete_transient( 'starter_local_fonts_retry' );
			starter_local_fonts_download();
		}
	}
);

add_action(
	'admin_init',
	function () {
		if ( ! starter_local_fonts_enabled() || ! starter_google_families() || starter_local_fonts_ready() || get_transient( 'starter_local_fonts_retry' ) ) {
			return;
		}

		set_transient( 'starter_local_fonts_retry', 1, HOUR_IN_SECONDS );
		starter_local_fonts_download();
	}
);

/* Sito pubblico: @font-face locali in linea (nessuna richiesta in più) e precaricamento del file latin. */
add_action(
	'wp_enqueue_scripts',
	function () {
		$local = starter_local_fonts_ready();

		if ( $local ) {
			wp_dequeue_style( 'starter-typography-google-fonts' );
			wp_deregister_style( 'starter-typography-google-fonts' );
			wp_add_inline_style( 'starter-theme-style', $local['css'] );
		}
	},
	12
);

add_action(
	'wp_head',
	function () {
		$local = starter_local_fonts_ready();

		foreach ( $local['preload'] ?? array() as $url ) {
			printf( '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n", esc_url( $url ) );
		}
	},
	2
);

/* Niente collegamento anticipato a Google quando i font sono in locale. */
add_filter(
	'wp_resource_hints',
	function ( $urls, $relation ) {
		if ( ! starter_local_fonts_ready() ) {
			return $urls;
		}

		return array_values(
			array_filter(
				$urls,
				function ( $url ) {
					$href = is_array( $url ) ? ( $url['href'] ?? '' ) : (string) $url;
					return false === strpos( $href, 'fonts.googleapis.com' ) && false === strpos( $href, 'fonts.gstatic.com' );
				}
			)
		);
	},
	20,
	2
);

/* Editor a blocchi: stessi font locali. */
add_filter(
	'block_editor_settings_all',
	function ( $settings ) {
		$local = starter_local_fonts_ready();

		if ( $local ) {
			$settings['styles'][] = array( 'css' => $local['css'] );
		}

		return $settings;
	},
	11
);
