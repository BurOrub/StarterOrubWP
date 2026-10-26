<?php
/**
 * Menu principale: voci con sottomenu.
 *
 * Con l'opzione "Le voci con sottomenu si aprono col click" (Opzioni → Header, attiva di base) le voci
 * di primo livello che hanno sottovoci non sono link ma pulsanti con la freccia a sinistra:
 * - desktop: il sottomenu si apre all'hover e al click/tastiera, si richiude con un secondo click, Esc o clic fuori;
 * - pannello mobile (e header "Ridotto"): sottomenu CHIUSI, si aprono e chiudono toccando la voce.
 * La logica è in initSubmenus() di JS/scripts.js; l'aspetto in CSS/starter.css ("Menu principale").
 *
 * Nota: se la voce madre ha una pagina propria, aggiungila anche come prima sottovoce (es. "Vedi tutto"),
 * perché il click sulla madre apre il sottomenu e non porta alla pagina. Spegnendo l'opzione, le voci
 * tornano link normali con sottomenu all'hover (e sempre aperto nel pannello mobile).
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Aggiunge ai link con sottomenu il pulsante freccia (apre il sottomenu al clic) */
function starter_submenu_toggle_markup( $item_output, $item, $depth, $args ) {
	if ( ! get_theme_mod( 'starter_header_submenu_click', true ) ) {
		return $item_output;
	}

	// Menu a un solo livello (es. le voci del footer): niente sottomenu, quindi niente pulsante.
	if ( isset( $args->depth ) && 1 === (int) $args->depth ) {
		return $item_output;
	}

	// Qualunque livello: anche le voci di un sottomenu che hanno un altro sottomenu (si apre a destra su desktop).
	if ( empty( $args->theme_location ) || ! in_array( $args->theme_location, array( 'header', 'mobile' ), true ) ) {
		return $item_output;
	}

	if ( ! in_array( 'menu-item-has-children', (array) $item->classes, true ) ) {
		return $item_output;
	}

	$title = apply_filters( 'nav_menu_item_title', apply_filters( 'the_title', $item->title, $item->ID ), $item, $args, $depth );

	return sprintf(
		'<button type="button" class="submenu-toggle" aria-expanded="false"><span class="submenu-toggle__icon" aria-hidden="true"></span>%s</button>',
		wp_kses_post( $title )
	);
}
add_filter( 'walker_nav_menu_start_el', 'starter_submenu_toggle_markup', 10, 4 );

/* Titolo di una colonna menu del footer = nome del menu assegnato (Aspetto → Menu), tradotto da WPML; ripiego sul titolo predefinito. */
function starter_footer_menu_title( $location, $fallback ) {
	$locations = get_nav_menu_locations();

	if ( empty( $locations[ $location ] ) ) {
		return $fallback;
	}

	$menu = wp_get_nav_menu_object( $locations[ $location ] );

	return ( $menu && $menu->name ) ? starter_wpml_translate_string( 'footer_menu_' . $location, $menu->name ) : $fallback;
}

/**
 * Spazio libero del footer (al posto della vecchia newsletter finta), uguale in tutti gli stili: titolo facoltativo
 * sottotitolo facoltativo e contenuto scritto in Opzioni → Footer → "Spazio libero". Può essere uno shortcode (es. il form di Contact Form 7
 * o di un servizio di newsletter), ma anche testo, un link, un'immagine: qualunque HTML. Se il contenuto è vuoto
 * non si stampa nulla e il footer resta senza quella colonna.
 *
 * @return string HTML (vuoto se non c'è contenuto).
 */
function starter_footer_free_html() {
	$content = trim( (string) starter_wpml_translate_string( 'footer_free_content', (string) get_theme_mod( 'starter_footer_newsletter_shortcode', '' ) ) );

	if ( '' === $content ) {
		return '';
	}

	$title = trim( (string) starter_wpml_translate_string( 'footer_free_title', (string) get_theme_mod( 'starter_footer_newsletter_title', '' ) ) );
	$html  = '<div class="footer-free footer-form">';

	if ( '' !== $title ) {
		$html .= '<h2 class="footer-nav-col__title eyebrow">' . esc_html( $title ) . '</h2>';
	}

	$subtitle = trim( (string) starter_wpml_translate_string( 'footer_free_subtitle', (string) get_theme_mod( 'starter_footer_newsletter_subtitle', '' ) ) );

	if ( '' !== $subtitle ) {
		$html .= '<p class="footer-free__sub">' . esc_html( $subtitle ) . '</p>';
	}

	return $html . '<div class="footer-free__body">' . do_shortcode( wp_kses_post( $content ) ) . '</div></div>';
}

/* Pagine interne dello shop per il footer (Stile 4, quando non c'è un secondo menu): Negozio, Preferiti, Carrello, Account. */
function starter_footer_shop_links() {
	if ( ! starter_has_woocommerce() ) {
		return array();
	}

	$urls  = starter_shop_urls();
	$links = array( array( __( 'Negozio', 'slug-theme' ), $urls['shop'] ) );

	if ( starter_wishlist_url() ) {
		$links[] = array( __( 'Preferiti', 'slug-theme' ), starter_wishlist_url() );
	}

	$links[] = array( __( 'Carrello', 'slug-theme' ), $urls['cart'] );
	$links[] = array( __( 'Il mio account', 'slug-theme' ), $urls['account'] );

	return array_filter(
		$links,
		function ( $link ) {
			return ! empty( $link[1] );
		}
	);
}
