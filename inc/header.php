<?php
/**
 * Header: opzioni e pezzi di markup condivisi (icone, ordine di social e icone, logo che cambia allo scroll).
 *
 * Struttura dell'header (header.php), da sinistra a destra:
 *   .site-branding                      logo (con eventuale icona che compare dopo lo scroll)
 *   .site-header__nav-wrap              menu + CTA (su desktop sono figli diretti della riga; su mobile/Ridotto è il pannello a tendina)
 *   .site-header__tools                 social, divisore, icone (ricerca, account, preferiti, carrello): l'ordine si sceglie in Opzioni → Header
 *   .mobile-menu-toggle                 pulsante "Menu" con hamburger (solo mobile/Ridotto)
 *
 * Opzioni (Opzioni → Header): stile delle icone (con bordo e animazione come i social, oppure semplici), divisore tra social
 * e icone, quale gruppo viene prima, ricerca, logo piccolo o icona allo scroll. Le classi sul body si scrivono qui sotto.
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Stile delle icone dell'header: 'boxed' (bordo + animazione, come i social), 'plain' (solo l'icona) o 'tight' (solo l'icona, senza spazio intorno). */
function starter_header_icon_style() {
	$style = get_theme_mod( 'starter_header_icon_style', 'boxed' );

	return in_array( $style, array( 'plain', 'tight' ), true ) ? $style : 'boxed';
}

/* Gruppo che sta per primo (a sinistra) tra social e icone: 'social-first' o 'icons-first'. */
function starter_header_tools_order() {
	return 'icons-first' === get_theme_mod( 'starter_header_tools_order', 'social-first' ) ? 'icons-first' : 'social-first';
}

/* Cosa fa il logo scrollando: 'none', 'small' (stesso logo, più piccolo) o 'icon' (icona caricata da te; ripiego su 'small' se manca). */
function starter_header_scroll_logo() {
	$mode = get_theme_mod( 'starter_header_scroll_logo', 'none' );

	if ( 'icon' === $mode && ! get_theme_mod( 'starter_header_logo_icon', '' ) ) {
		return 'small';
	}

	return in_array( $mode, array( 'small', 'icon' ), true ) ? $mode : 'none';
}

/* Ricerca nell'header: funziona sempre (articoli e pagine; i prodotti solo con WooCommerce). */
function starter_header_search_enabled() {
	return (bool) get_theme_mod( 'starter_header_show_search', true );
}

/* Classi sul body che comandano l'aspetto dell'header (vedi CSS/starter.css, sezione "Header"). */
add_filter(
	'body_class',
	function ( $classes ) {
		$style     = starter_header_icon_style();
		$classes[] = 'icons-' . $style;

		if ( 'tight' === $style ) {
			$classes[] = 'icons-plain'; // Stesse regole delle icone semplici, più strette.
		}

		if ( 'icons-first' === starter_header_tools_order() ) {
			$classes[] = 'tools-icons-first';
		}

		if ( get_theme_mod( 'starter_header_divider', false ) ) {
			$classes[] = 'has-header-divider';
		}

		$logo_mode = starter_header_scroll_logo();

		if ( 'none' !== $logo_mode ) {
			$classes[] = 'logo-scroll-' . $logo_mode;
		}

		if ( starter_header_search_enabled() ) {
			$classes[] = 'has-header-search';
		}

		// Pulsante WhatsApp attivo (c'è un link in Opzioni → Generale → WhatsApp): serve a ciò che sta fisso in basso per non sovrapporsi.
		if ( function_exists( 'starter_whatsapp_opt' ) && '' !== starter_whatsapp_opt( 'url' ) ) {
			$classes[] = 'has-whatsapp';
		}

		return $classes;
	}
);

/**
 * Pulsante-icona dell'header (ricerca, account, preferiti, carrello). Stessa icona sdoppiata dei social: con lo stile
 * "con bordo" al passaggio del mouse l'icona ruota e lascia il posto al duplicato (vedi CSS ".icon-btn").
 *
 * @param array $args icon (nome in inc/icons.php), text (testo al posto dell'icona), href (vuoto = <button>), label (aria-label; vuoto se il testo sta in 'after'),
 *                    class (classi in più), attrs (attributi HTML già escapati), after (HTML dopo l'icona, es. il badge).
 * @return string HTML.
 */
function starter_header_icon_btn( $args ) {
	$args = wp_parse_args(
		$args,
		array(
			'text'  => '', // Testo al posto dell'icona (es. il codice lingua).
			'icon'  => 'search',
			'href'  => '',
			'label' => '',
			'class' => '',
			'attrs' => '',
			'after' => '',
		)
	);

	$svg   = '' !== $args['text'] ? esc_html( $args['text'] ) : starter_icon( $args['icon'], 21, 1.5 );
	$inner = sprintf(
		'<span class="icon-btn__wrap"><span class="icon-btn__icon">%1$s</span><span class="icon-btn__icon icon-btn__icon--clone" aria-hidden="true">%1$s</span></span>%2$s',
		$svg,
		$args['after']
	);

	if ( $args['href'] ) {
		return sprintf(
			'<a class="icon-btn %1$s" href="%2$s"%3$s %4$s>%5$s</a>',
			esc_attr( $args['class'] ),
			esc_url( $args['href'] ),
			$args['label'] ? ' aria-label="' . esc_attr( $args['label'] ) . '"' : '',
			$args['attrs'],
			$inner
		);
	}

	return sprintf(
		'<button type="button" class="icon-btn %1$s"%2$s %3$s>%4$s</button>',
		esc_attr( $args['class'] ),
		$args['label'] ? ' aria-label="' . esc_attr( $args['label'] ) . '"' : '',
		$args['attrs'],
		$inner
	);
}

/**
 * Lingue di WPML per il selettore dell'header (vuoto se WPML non c'è o c'è una sola lingua).
 * Ogni voce porta alla HOME della lingua scelta (non alla traduzione della pagina corrente).
 *
 * @return array[] code, name, active, url.
 */
function starter_wpml_languages() {
	global $sitepress;

	if ( ! is_object( $sitepress ) || ! method_exists( $sitepress, 'get_active_languages' ) ) {
		return array();
	}

	// Elenco delle lingue attive (non "wpml_active_languages": quello nasconde le lingue senza traduzione della pagina corrente).
	$langs = $sitepress->get_active_languages();

	if ( ! is_array( $langs ) || count( $langs ) < 2 ) {
		return array();
	}

	$current = (string) apply_filters( 'wpml_current_language', null );
	$out     = array();

	foreach ( $langs as $code => $lang ) {
		$out[] = array(
			'code'   => (string) $code,
			'name'   => ! empty( $lang['native_name'] ) ? $lang['native_name'] : strtoupper( $code ),
			'active' => (string) $code === $current,
			'url'    => (string) apply_filters( 'wpml_permalink', home_url( '/' ), $code ), // La home nella lingua $code ("wpml_home_url" non accetta la lingua: darebbe sempre quella corrente).
		);
	}

	return $out;
}

/**
 * Selettore di lingua: mostra solo le ALTRE lingue (mai quella in cui si è).
 * - una sola altra lingua: link diretto col suo codice (es. "EN");
 * - più lingue: icona che apre la tendina con i codici (es. "EN", "FR").
 * Senza WPML o con una sola lingua non stampa nulla.
 */
function starter_header_lang_html() {
	$others = array_values(
		array_filter(
			starter_wpml_languages(),
			static function ( $lang ) {
				return empty( $lang['active'] );
			}
		)
	);

	if ( ! $others ) {
		return '';
	}

	if ( 1 === count( $others ) ) {
		return '<div class="header-tip header-tip--lang header-tip--single">' .
			starter_header_icon_btn( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				array(
					'text'  => strtoupper( $others[0]['code'] ),
					'href'  => $others[0]['url'],
					'label' => $others[0]['name'],
					'class' => 'icon-btn--lang',
					'attrs' => 'hreflang="' . esc_attr( $others[0]['code'] ) . '" lang="' . esc_attr( $others[0]['code'] ) . '"',
				)
			) .
			'</div>';
	}

	$items = '';

	foreach ( $others as $lang ) {
		$items .= sprintf(
			'<li><a href="%1$s" hreflang="%2$s" lang="%2$s" title="%3$s"><span class="lang-list__code">%2$s</span></a></li>',
			esc_url( $lang['url'] ),
			esc_attr( $lang['code'] ),
			esc_attr( $lang['name'] )
		);
	}

	return '<div class="header-tip header-tip--lang">' .
		starter_header_icon_btn( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			array(
				'icon'  => 'languages',
				'label' => __( 'Cambia lingua', 'slug-theme' ),
				'class' => 'icon-btn--lang',
				'attrs' => 'aria-haspopup="true" aria-expanded="false"',
			)
		) .
		'<div class="header-tip__panel header-tip__panel--lang"><ul class="lang-list">' . $items . '</ul></div></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
