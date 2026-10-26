<?php
/**
 * Briciole di pane (Opzioni → Generale → "Mostra le briciole di pane").
 *
 * Usano le briciole di Yoast SEO se il plugin c'è (la barra visibile e il BreadcrumbList dei dati strutturati
 * hanno così la stessa fonte); senza Yoast ripiegano su quelle di WooCommerce nelle pagine dello shop, altrimenti
 * su un percorso semplice Home › pagina madre › titolo.
 *
 * Per un tipo di contenuto senza archivio ma con una pagina "elenco" (es. un CPT Immobili con la pagina
 * Immobili) filtra `wpseo_breadcrumb_links` nel progetto, così Yoast scrive Home › Immobili › Titolo.
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Voci delle briciole nel formato array( array( testo, url ), ... ); l'ultima ha l'url vuoto. */
function starter_breadcrumb_items() {
	if ( function_exists( 'YoastSEO' ) && ! is_admin() ) {
		$crumbs = YoastSEO()->meta->for_current_page()->breadcrumbs;

		if ( is_array( $crumbs ) && count( $crumbs ) > 1 ) {
			$items = array();

			foreach ( $crumbs as $crumb ) {
				$items[] = array( wp_strip_all_tags( $crumb['text'] ), $crumb['url'] );
			}

			$items[ count( $items ) - 1 ][1] = '';

			return $items;
		}
	}

	// Pagine dello shop: percorso di WooCommerce (conosce categorie e prodotti).
	if ( starter_has_woocommerce() && is_woocommerce() && function_exists( 'wc_get_breadcrumb' ) ) {
		$crumbs = wc_get_breadcrumb();

		if ( is_array( $crumbs ) && count( $crumbs ) > 1 ) {
			$items = array();

			foreach ( $crumbs as $crumb ) {
				$items[] = array( wp_strip_all_tags( $crumb[0] ), $crumb[1] );
			}

			$items[ count( $items ) - 1 ][1] = '';

			return $items;
		}
	}

	$items = array( array( __( 'Home', 'slug-theme' ), home_url( '/' ) ) );

	if ( is_singular() ) {
		$post = get_queried_object();

		foreach ( array_reverse( get_post_ancestors( $post ) ) as $ancestor_id ) {
			$items[] = array( get_the_title( $ancestor_id ), get_permalink( $ancestor_id ) );
		}

		// Tipo di contenuto senza archivio ma con una pagina elenco (Portfolio, Immobili): Home › pagina elenco › titolo.
		$list_id = (int) apply_filters( 'starter_cpt_list_page', 0, get_post_type( $post ) );

		if ( $list_id ) {
			$items[] = array( get_the_title( $list_id ), get_permalink( $list_id ) );
		}

		if ( is_singular( 'post' ) && (int) get_option( 'page_for_posts' ) ) {
			$items[] = array( get_the_title( (int) get_option( 'page_for_posts' ) ), get_permalink( (int) get_option( 'page_for_posts' ) ) );
		}

		$items[] = array( get_the_title( $post ), '' );
	} elseif ( is_home() ) {
		$items[] = array( get_the_title( (int) get_option( 'page_for_posts' ) ) ?: __( 'Blog', 'slug-theme' ), '' );
	} elseif ( is_search() ) {
		/* translators: %s: termine cercato. */
		$items[] = array( sprintf( __( 'Risultati per “%s”', 'slug-theme' ), get_search_query() ), '' );
	} elseif ( is_404() ) {
		$items[] = array( __( 'Pagina non trovata', 'slug-theme' ), '' );
	} elseif ( is_archive() ) {
		$items[] = array( wp_strip_all_tags( get_the_archive_title() ), '' );
	}

	return count( $items ) > 1 ? $items : array();
}

/* Stile delle briciole (Opzioni → Generale): 'inline' (nel titolo della pagina, come nelle pagine coi blocchi) o 'bar' (fascia con bordo sotto l'header). */
function starter_breadcrumbs_style() {
	return 'bar' === get_theme_mod( 'starter_breadcrumbs_style', 'inline' ) ? 'bar' : 'inline';
}

/* Vero se le briciole vanno mostrate nel titolo della pagina (opzione attiva, stile "inline", non in home). */
function starter_breadcrumbs_inline_on() {
	return get_theme_mod( 'starter_breadcrumbs', false ) && 'inline' === starter_breadcrumbs_style() && ! is_front_page();
}

/* Briciole "nel titolo": stesso aspetto di quelle dei blocchi (".breadcrumb"). Non stampa niente se spente o senza percorso. */
function starter_breadcrumb_inline() {
	if ( ! starter_breadcrumbs_inline_on() ) {
		return;
	}

	$items = starter_breadcrumb_items();

	if ( $items ) {
		starter_breadcrumb( $items );
	}
}

/* Briciole "nel titolo" per pagine senza il blocco Intestazione pagina: stesso contenitore e stesse distanze (".page-header"). */
function starter_breadcrumb_header() {
	if ( ! starter_breadcrumbs_inline_on() || ! starter_breadcrumb_items() ) {
		return;
	}

	echo '<section class="alignfull content-block page-header page-header--crumbs"><div class="block-wrap">';
	starter_breadcrumb_inline();
	echo '</div></section>';
}

/* Barra delle briciole sotto l'header, in tutte le pagine tranne la home (se l'opzione è attiva e lo stile è "fascia"). */
function starter_breadcrumb_bar() {
	if ( ! get_theme_mod( 'starter_breadcrumbs', false ) || 'bar' !== starter_breadcrumbs_style() || is_front_page() ) {
		return;
	}

	// Il blocco "Intestazione pagina" ha già le sue briciole: niente doppione.
	if ( is_singular() && str_contains( (string) get_post_field( 'post_content', get_queried_object_id() ), 'wp:starter-theme/intestazione-pagina' ) ) {
		return;
	}

	// Nelle pagine dello shop senza Yoast: le briciole di WooCommerce, che conoscono categorie e prodotti.
	if ( ! function_exists( 'YoastSEO' ) && starter_has_woocommerce() && is_woocommerce() && function_exists( 'woocommerce_breadcrumb' ) ) {
		echo '<div class="crumbs-bar"><div class="starter-container">';
		woocommerce_breadcrumb();
		echo '</div></div>';

		return;
	}

	$items = starter_breadcrumb_items();

	if ( ! $items ) {
		return;
	}

	echo '<div class="crumbs-bar"><div class="starter-container"><nav class="woocommerce-breadcrumb" aria-label="' . esc_attr__( 'Briciole di pane', 'slug-theme' ) . '">';

	foreach ( $items as $i => $item ) {
		if ( $i ) {
			echo '<span class="crumbs-bar__sep" aria-hidden="true">/</span>';
		}

		if ( ! empty( $item[1] ) ) {
			echo '<a href="' . esc_url( $item[1] ) . '">' . esc_html( $item[0] ) . '</a>';
		} else {
			echo '<span aria-current="page">' . esc_html( $item[0] ) . '</span>';
		}
	}

	echo '</nav></div></div>';
}

/* Scheda prodotto: briciole nel titolo (stile "inline"). */
add_action(
	'woocommerce_before_single_product',
	function () {
		starter_breadcrumb_header();
	},
	5
);

/**
 * Yoast: nei tipi di contenuto con pagina elenco al posto dell'archivio (filtro starter_cpt_list_page) tra Home e il titolo
 * compare la pagina elenco, sia nella barra sia nei dati strutturati (BreadcrumbList).
 */
add_filter(
	'wpseo_breadcrumb_links',
	static function ( $crumbs ) {
		if ( ! is_singular() ) {
			return $crumbs;
		}

		$list_id = (int) apply_filters( 'starter_cpt_list_page', 0, get_post_type() );

		if ( ! $list_id ) {
			return $crumbs;
		}

		$list = array(
			'url'  => get_permalink( $list_id ),
			'text' => get_the_title( $list_id ),
			'id'   => $list_id,
		);

		// Si toglie un eventuale archivio già presente e si inserisce la pagina elenco dopo la Home.
		$crumbs = array_values(
			array_filter(
				$crumbs,
				static function ( $crumb ) use ( $list ) {
					return empty( $crumb['url'] ) || trailingslashit( $crumb['url'] ) !== trailingslashit( $list['url'] );
				}
			)
		);

		array_splice( $crumbs, 1, 0, array( $list ) );

		return $crumbs;
	}
);
