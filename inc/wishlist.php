<?php
/**
 * Preferiti (cuore sulle card prodotto). L'elenco sta nel browser del visitatore (localStorage "starter-wishlist",
 * gestito da initWishlist in JS/scripts.js): niente account, niente database. La pagina "Preferiti" contiene lo
 * shortcode [starter_preferiti]: il JS chiede qui (admin-ajax, azione starter_wishlist) le card dei prodotti
 * salvati, le stesse del negozio, e le toglie dalla pagina quando si spegne il cuore.
 *
 * L'icona cuore nell'header porta alla pagina scelta in Opzioni → Shop ("Pagina dei preferiti"); se non è scelta,
 * alla pagina con indirizzo /preferiti/ (creata dai contenuti di prova).
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Indirizzo della pagina Preferiti ('' se non esiste). */
function starter_wishlist_url() {
	$page_id = (int) get_theme_mod( 'starter_shop_wishlist_page', 0 );
	$page    = $page_id ? get_post( $page_id ) : null;

	// Pagina scelta assente (es. eliminata): ripiego sulla pagina con indirizzo /preferiti/.
	if ( ! $page ) {
		$page = get_page_by_path( 'preferiti' );
	}

	if ( ! $page || 'publish' !== $page->post_status ) {
		return '';
	}

	$id = (int) apply_filters( 'wpml_object_id', $page->ID, 'page', true ); // Pagina tradotta, se c'è (WPML).

	return (string) get_permalink( $id ? $id : $page->ID );
}

/* Badge con il numero di preferiti nell'header: il numero lo scrive il JS (il server non conosce l'elenco). */
function starter_wishlist_count_html() {
	return '<span class="header-wish__count is-empty" aria-hidden="true"></span>';
}

/* Contenitore della pagina: il JS lo riempie con le card dei prodotti salvati. */
add_shortcode(
	'starter_preferiti',
	function () {
		$shop = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );

		ob_start();
		?>
		<div class="shop-wishlist" data-wishlist-page data-endpoint="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>">
			<div class="shop-wishlist__grid woocommerce" data-wishlist-grid aria-live="polite"></div>
			<div class="shop-wishlist__empty" data-wishlist-empty hidden>
				<p class="title-h4"><?php esc_html_e( 'Non hai ancora prodotti tra i preferiti.', 'slug-theme' ); ?></p>
				<p class="txt"><?php esc_html_e( 'Tocca il cuore su un prodotto per salvarlo qui e ritrovarlo quando vuoi.', 'slug-theme' ); ?></p>
				<a class="button button--outline" href="<?php echo esc_url( $shop ); ?>"><?php echo starter_flip_text( __( 'Scopri i prodotti', 'slug-theme' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
);

/* Elenco di ID dal parametro "ids" (solo numeri, max 100). */
function starter_wishlist_ids_from_request() {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- elenco pubblico di prodotti, sola lettura.
	$raw = isset( $_GET['ids'] ) ? sanitize_text_field( wp_unslash( $_GET['ids'] ) ) : '';

	return array_slice( array_unique( array_filter( array_map( 'absint', explode( ',', $raw ) ) ) ), 0, 100 );
}

/* Card dei prodotti salvati (stesso markup del negozio: woocommerce/content-product.php). */
function starter_wishlist_ajax() {
	$ids = starter_wishlist_ids_from_request();

	if ( ! $ids || ! function_exists( 'wc_get_product' ) ) {
		wp_send_json( array( 'html' => '' ) );
	}

	global $product;

	ob_start();
	echo '<ul class="products columns-4">';

	foreach ( $ids as $id ) {
		$product = wc_get_product( $id );

		if ( $product && 'publish' === $product->get_status() && $product->is_visible() ) {
			wc_get_template_part( 'content', 'product' );
		}
	}

	echo '</ul>';

	wp_send_json( array( 'html' => ob_get_clean() ) );
}
add_action( 'wp_ajax_starter_wishlist', 'starter_wishlist_ajax' );
add_action( 'wp_ajax_nopriv_starter_wishlist', 'starter_wishlist_ajax' );

/* Tooltip dei preferiti nell'header: foto, nome e prezzo dei prodotti salvati (max 5). L'elenco arriva dal browser. */
function starter_wishlist_tip_ajax() {
	$ids   = starter_wishlist_ids_from_request();
	$items = array();
	$total = 0;

	if ( $ids && function_exists( 'wc_get_product' ) ) {
		foreach ( $ids as $id ) {
			$product = wc_get_product( $id );

			if ( ! $product || 'publish' !== $product->get_status() || ! $product->is_visible() ) {
				continue;
			}

			++$total;

			if ( count( $items ) < 5 ) {
				$items[] = sprintf(
					'<li class="header-tip__item"><span class="header-tip__thumb">%1$s</span><span class="header-tip__name">%2$s</span><span class="header-tip__meta">%3$s</span></li>',
					$product->get_image( 'thumbnail' ),
					esc_html( $product->get_name() ),
					$product->get_price_html()
				);
			}
		}
	}

	if ( ! $items ) {
		wp_send_json( array( 'html' => '<p class="header-tip__empty">' . esc_html__( 'Non hai ancora prodotti tra i preferiti.', 'slug-theme' ) . '</p>' ) );
	}

	$html = '<ul class="header-tip__list">' . implode( '', $items ) . '</ul>';

	if ( $total > count( $items ) ) {
		/* translators: %d: numero di altri prodotti nei preferiti. */
		$html .= '<p class="header-tip__more">' . esc_html( sprintf( _n( 'e altro %d prodotto', 'e altri %d prodotti', $total - count( $items ), 'slug-theme' ), $total - count( $items ) ) ) . '</p>';
	}

	wp_send_json( array( 'html' => $html ) );
}
add_action( 'wp_ajax_starter_wishlist_tip', 'starter_wishlist_tip_ajax' );
add_action( 'wp_ajax_nopriv_starter_wishlist_tip', 'starter_wishlist_tip_ajax' );

/*
 * Scheda prodotto: cuore dei preferiti accanto alla lente dello zoom. Stampato dopo la galleria e spostato
 * dentro dal JS (initProductWish), nascosto finché non è al suo posto. Solo se i preferiti sono attivi.
 */
add_action(
	'woocommerce_before_single_product_summary',
	function () {
		global $product;

		if ( ! $product || ! get_theme_mod( 'starter_shop_card_wishlist', true ) ) {
			return;
		}

		printf(
			'<button type="button" class="product-card__wish product-card__wish--single" aria-pressed="false" data-wishlist="%1$s" hidden><span class="screen-reader-text">%2$s</span>%3$s</button>',
			esc_attr( $product->get_id() ),
			/* translators: %s: nome del prodotto. */
			esc_html( sprintf( __( 'Aggiungi %s ai preferiti', 'slug-theme' ), $product->get_name() ) ),
			starter_icon( 'heart', 22, 2.2 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);
	},
	25
);
