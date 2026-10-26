<?php
/**
 * WooCommerce: supporto del tema, CSS di Woo solo dove serve, icone shop nell'header
 * (account, preferiti, carrello con tooltip), card prodotto unica, scheda prodotto.
 *
 * Tutto quello che segue è inerte se WooCommerce non è installato: le opzioni "Shop" e le icone
 * dell'header compaiono nel Customizer solo con Woo attivo (vedi inc/customizer-shop.php).
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Vero se WooCommerce è attivo. */
function starter_has_woocommerce() {
	return class_exists( 'WooCommerce' );
}

/* Vero nelle pagine del negozio (shop, categorie, prodotto, carrello, cassa, account). */
function starter_is_woocommerce_page() {
	if ( ! starter_has_woocommerce() ) {
		return false;
	}

	return is_woocommerce() || is_cart() || is_checkout() || is_account_page();
}

/* Vero se in questa pagina servono gli stili dello shop (CSS/shop.css): pagine Woo, pagina Preferiti, card prodotto. */
function starter_needs_shop_css() {
	if ( ! starter_has_woocommerce() ) {
		return false;
	}

	if ( starter_is_woocommerce_page() ) {
		return true;
	}

	// Pagine col blocco "Prodotti in evidenza" o lo shortcode dei preferiti: stesse card del negozio.
	if ( is_singular() ) {
		$post = get_post();

		return $post && ( has_shortcode( $post->post_content, 'starter_preferiti' ) || has_block( 'starter-theme/prodotti-in-evidenza', $post ) );
	}

	return false;
}

/* Supporto del tema: galleria prodotto (zoom, lightbox, slider) e dimensioni immagini delle card (quadrate 600). */
function starter_woocommerce_setup() {
	add_theme_support(
		'woocommerce',
		array(
			'thumbnail_image_width' => 600,
			'single_image_width'    => 1000,
			'product_grid'          => array(
				'default_columns' => 4,
				'min_columns'     => 2,
				'max_columns'     => 4,
			),
		)
	);
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'starter_woocommerce_setup' );

/* Ritaglio quadrato delle miniature prodotto. */
add_filter(
	'woocommerce_get_image_size_thumbnail',
	function () {
		return array(
			'width'  => 600,
			'height' => 600,
			'crop'   => 1,
		);
	}
);

/**
 * Fogli di stile di Woo: "layout" e "smallscreen" sostituiti dal CSS del tema (griglia e card sono nostre);
 * "general" resta solo nelle pagine del negozio, non in home.
 */
function starter_woocommerce_styles( $styles ) {
	unset( $styles['woocommerce-layout'], $styles['woocommerce-smallscreen'] );

	if ( ! starter_is_woocommerce_page() ) {
		unset( $styles['woocommerce-general'] );
	}

	return $styles;
}
add_filter( 'woocommerce_enqueue_styles', 'starter_woocommerce_styles' );

/* Blocchi Woo (carrello/cassa a blocchi): i loro CSS restano, ma solo dove ci sono. */
add_filter( 'should_load_separate_core_block_assets', '__return_true' );

/**
 * Script di Woo che aggiornano il carrello nell'header:
 * - "wc-add-to-cart": aggiunta AJAX dal pulsante della card (evento "added_to_cart");
 * - "wc-cart-fragments": dopo l'aggiunta (e al cambio pagina) sostituisce il badge e il tooltip del carrello.
 * Da WooCommerce 7.8 "wc-cart-fragments" non si carica più da solo su tutte le pagine.
 */
function starter_woocommerce_scripts() {
	if ( ! starter_has_woocommerce() ) {
		return;
	}

	if ( starter_header_shop_enabled( 'cart' ) ) {
		wp_enqueue_script( 'wc-cart-fragments' );
	}

	if ( 'yes' === get_option( 'woocommerce_enable_ajax_add_to_cart' ) ) {
		wp_enqueue_script( 'wc-add-to-cart' );
	}
}
add_action( 'wp_enqueue_scripts', 'starter_woocommerce_scripts', 20 );

/*
 * Icone shop nell'header, attivabili una per una da Opzioni → Header (solo con Woo attivo).
 * $which: 'account' | 'wishlist' | 'cart'.
 */
function starter_header_shop_enabled( $which ) {
	if ( ! starter_has_woocommerce() ) {
		return false;
	}

	return (bool) get_theme_mod( "starter_header_show_{$which}", false );
}

/* Vero se almeno una delle icone shop è attiva (per sapere se stampare il contenitore). */
function starter_header_shop_any() {
	return starter_header_shop_enabled( 'account' ) || starter_header_shop_enabled( 'wishlist' ) || starter_header_shop_enabled( 'cart' );
}

/* Numero di articoli nel carrello (0 se Woo non è attivo o la sessione non c'è). */
function starter_cart_count() {
	if ( ! starter_has_woocommerce() || ! WC()->cart ) {
		return 0;
	}

	return (int) WC()->cart->get_cart_contents_count();
}

/* Badge del conteggio: nascosto a 0; stesso markup nel primo caricamento e nel frammento AJAX. */
function starter_cart_count_html() {
	$count = starter_cart_count();

	return sprintf(
		'<span class="header-cart__count%1$s" aria-hidden="true">%2$s</span>',
		$count ? '' : ' is-empty',
		$count ? esc_html( $count ) : ''
	);
}

/* Etichetta accessibile del link carrello, aggiornata insieme al numero. */
function starter_cart_label() {
	$count = starter_cart_count();

	/* translators: %d: numero di articoli nel carrello. */
	return sprintf( _n( 'Carrello, %d articolo', 'Carrello, %d articoli', $count, 'slug-theme' ), $count );
}

/* Tooltip del carrello nell'header: gli articoli dentro (max 5), con foto, quantità e prezzo, più il totale. */
function starter_cart_tip_html() {
	$cart  = starter_has_woocommerce() ? WC()->cart : null;
	$items = $cart ? $cart->get_cart() : array();
	$html  = '<div class="header-tip__panel header-tip__panel--cart" role="tooltip">';

	if ( ! $items ) {
		return $html . '<p class="header-tip__empty">' . esc_html__( 'Il carrello è vuoto.', 'slug-theme' ) . '</p></div>';
	}

	$html .= '<ul class="header-tip__list">';

	foreach ( array_slice( $items, 0, 5 ) as $item ) {
		$product = $item['data'];

		if ( ! $product instanceof WC_Product ) {
			continue;
		}

		$html .= sprintf(
			'<li class="header-tip__item"><span class="header-tip__thumb">%1$s</span><span class="header-tip__name">%2$s</span><span class="header-tip__meta">%3$s &times; %4$s</span></li>',
			$product->get_image( 'thumbnail' ),
			esc_html( $product->get_name() ),
			esc_html( $item['quantity'] ),
			$cart->get_product_price( $product )
		);
	}

	$html .= '</ul>';

	if ( count( $items ) > 5 ) {
		/* translators: %d: numero di altri prodotti nel carrello. */
		$html .= '<p class="header-tip__more">' . esc_html( sprintf( _n( 'e altro %d prodotto', 'e altri %d prodotti', count( $items ) - 5, 'slug-theme' ), count( $items ) - 5 ) ) . '</p>';
	}

	$html .= '<p class="header-tip__total"><span>' . esc_html__( 'Subtotale', 'slug-theme' ) . '</span> <strong>' . $cart->get_cart_subtotal() . '</strong></p></div>';

	return $html;
}

/* Frammenti AJAX: badge, etichetta e tooltip del carrello nell'header. */
function starter_cart_fragments( $fragments ) {
	$fragments['div.header-tip__panel--cart'] = starter_cart_tip_html();
	$fragments['span.header-cart__count']     = starter_cart_count_html();
	$fragments['span.header-cart__label']     = '<span class="header-cart__label screen-reader-text">' . esc_html( starter_cart_label() ) . '</span>';

	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'starter_cart_fragments' );

/* URL utili all'header (vuoti se Woo non è attivo). */
function starter_shop_urls() {
	if ( ! starter_has_woocommerce() ) {
		return array(
			'cart'    => '',
			'account' => '',
			'shop'    => '',
		);
	}

	return array(
		'cart'    => wc_get_cart_url(),
		'account' => wc_get_page_permalink( 'myaccount' ),
		'shop'    => wc_get_page_permalink( 'shop' ),
	);
}

/* Contenitore del negozio: stesso <main> del resto del tema (header, footer e larghezze coerenti). */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

add_action(
	'woocommerce_before_main_content',
	function () {
		echo '<main class="site-content shop-content" id="site-content">';
	},
	10
);

add_action(
	'woocommerce_after_main_content',
	function () {
		echo '</main>';
	},
	10
);

/* Categorie del prodotto per la card (prima la sottocategoria, poi la madre), massimo 2; esclusa "Uncategorized". */
function starter_product_categories( $product_id ) {
	$terms = get_the_terms( $product_id, 'product_cat' );

	if ( ! $terms || is_wp_error( $terms ) ) {
		return array();
	}

	$default = (int) get_option( 'default_product_cat' );
	$terms   = array_filter(
		$terms,
		function ( $term ) use ( $default ) {
			return (int) $term->term_id !== $default;
		}
	);

	usort(
		$terms,
		function ( $a, $b ) {
			return (int) ( 0 === (int) $a->parent ) - (int) ( 0 === (int) $b->parent );
		}
	);

	return array_slice( $terms, 0, 2 );
}

/* Seconda immagine (prima della galleria) per il cambio foto all'hover della card. */
function starter_product_hover_image_id( $product ) {
	$gallery = $product->get_gallery_image_ids();

	return $gallery ? (int) $gallery[0] : 0;
}

/* Testo breve della card: descrizione breve senza HTML, max ~110 caratteri. */
function starter_product_card_text( $product ) {
	$text = wp_strip_all_tags( $product->get_short_description() );

	return wp_html_excerpt( $text, 110, '…' );
}

/* Pulsante "Aggiungi al carrello" della card con l'animazione del tema (testo che ruota). */
function starter_loop_add_to_cart_link( $html, $product, $args ) {
	$text = $product->add_to_cart_text();

	return sprintf(
		'<a href="%1$s" data-quantity="%2$s" class="%3$s" %4$s>%5$s</a>',
		esc_url( $product->add_to_cart_url() ),
		esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
		esc_attr( ( isset( $args['class'] ) ? $args['class'] : 'button' ) . ' button--primary' ),
		isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
		starter_flip_text( $text ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	);
}
add_filter( 'woocommerce_loop_add_to_cart_link', 'starter_loop_add_to_cart_link', 10, 3 );

/* Niente link "Visualizza carrello" sotto il pulsante della card: c'è il numero nell'header. */
add_filter(
	'woocommerce_loop_add_to_cart_args',
	function ( $args ) {
		$args['attributes']['data-no-view-cart'] = '1';

		return $args;
	}
);

/*
 * Scheda prodotto: foto e riepilogo in un contenitore a due colonne, così il riepilogo sticky si ferma
 * alla fine delle foto e le schede (descrizione, correlati) sotto occupano tutta la larghezza.
 */
add_action(
	'woocommerce_before_single_product_summary',
	function () {
		echo '<div class="product-main">';
	},
	1
);

add_action(
	'woocommerce_after_single_product_summary',
	function () {
		echo '</div>';
	},
	1
);

/* Tab della scheda prodotto: etichette brevi (restano traducibili con WPML/Loco). */
add_filter(
	'woocommerce_product_tabs',
	function ( $tabs ) {
		if ( isset( $tabs['reviews'] ) ) {
			global $product;

			/* translators: %d: numero di recensioni. */
			$tabs['reviews']['title'] = sprintf( __( 'Recensioni (%d)', 'slug-theme' ), $product ? (int) $product->get_review_count() : 0 );
		}

		return $tabs;
	},
	20
);

/* Briciole di Woo: separatore leggero e markup per la barra delle briciole del tema. */
add_filter(
	'woocommerce_breadcrumb_defaults',
	function ( $defaults ) {
		$defaults['delimiter']   = '<span class="crumbs-bar__sep" aria-hidden="true">/</span>';
		$defaults['wrap_before'] = '<nav class="woocommerce-breadcrumb" aria-label="' . esc_attr__( 'Briciole di pane', 'slug-theme' ) . '">';
		$defaults['wrap_after']  = '</nav>';

		return $defaults;
	}
);

// Le briciole le stampa il tema (barra sotto l'header, vedi inc/breadcrumbs.php), non Woo dentro il contenuto.
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

/*
 * Cassa a blocchi: dati per la fattura (partita IVA, codice fiscale, codice SDI, PEC), tutti facoltativi.
 * Woo li salva nell'ordine con i dati di fatturazione e li mostra nella scheda dell'ordine in admin (sezione
 * Fatturazione, modificabili), in "Ordine ricevuto" e nelle email. Attivi di base; si spengono da Opzioni → Shop.
 */
function starter_register_invoice_fields() {
	if ( ! get_theme_mod( 'starter_shop_invoice_fields', true ) || ! function_exists( 'woocommerce_register_additional_checkout_field' ) ) {
		return;
	}

	$fields = array(
		'partita-iva'    => array(
			'label'   => __( 'Partita IVA', 'slug-theme' ),
			'pattern' => '/^(?:IT)?[0-9]{11}$/',
			'message' => __( 'La partita IVA deve avere 11 cifre.', 'slug-theme' ),
			'upper'   => true,
		),
		'codice-fiscale' => array(
			'label'   => __( 'Codice fiscale', 'slug-theme' ),
			'pattern' => '/^(?:[A-Z0-9]{16}|[0-9]{11})$/',
			'message' => __( 'Il codice fiscale deve avere 16 caratteri (11 cifre per le società).', 'slug-theme' ),
			'upper'   => true,
		),
		'codice-sdi'     => array(
			'label'   => __( 'Codice SDI', 'slug-theme' ),
			'pattern' => '/^[A-Z0-9]{7}$/',
			'message' => __( 'Il codice SDI deve avere 7 caratteri.', 'slug-theme' ),
			'upper'   => true,
		),
		'pec'            => array(
			'label'   => __( 'PEC', 'slug-theme' ),
			'pattern' => '/^[^@\s]+@[^@\s]+\.[^@\s]+$/',
			'message' => __( 'Inserisci un indirizzo PEC valido.', 'slug-theme' ),
			'upper'   => false,
		),
	);

	foreach ( $fields as $key => $field ) {
		$pattern = $field['pattern'];
		$message = $field['message'];
		$upper   = $field['upper'];

		woocommerce_register_additional_checkout_field(
			array(
				'id'                => 'starter-theme/' . $key,
				'label'             => $field['label'],
				'location'          => 'address',
				'type'              => 'text',
				'required'          => false,
				'sanitize_callback' => function ( $value ) use ( $upper ) {
					$value = trim( preg_replace( '/\s+/', '', (string) $value ) );

					return $upper ? strtoupper( $value ) : $value;
				},
				'validate_callback' => function ( $value ) use ( $pattern, $message ) {
					if ( '' !== $value && ! preg_match( $pattern, $value ) ) {
						return new WP_Error( 'starter_invalid_invoice_field', $message );
					}

					return true;
				},
			)
		);
	}
}
add_action( 'woocommerce_init', 'starter_register_invoice_fields' );

/* Colonne della griglia prodotti (Opzioni → Shop → Colonne): classe sul body nelle pagine Shop e categorie. */
function starter_shop_columns() {
	$cols = (int) get_theme_mod( 'starter_shop_columns', 4 );

	return in_array( $cols, array( 2, 3, 4 ), true ) ? $cols : 4;
}

add_filter(
	'body_class',
	function ( $classes ) {
		if ( starter_has_woocommerce() && ( is_shop() || is_product_taxonomy() ) ) {
			$classes[] = 'shop-cols-' . starter_shop_columns();
		}

		return $classes;
	}
);


/* Campo quantità con i pulsanti − e + ai lati del numero (al posto delle frecce del browser): prodotto, set, carrello classico.
   Il clic lo gestisce initQuantityButtons() in JS/scripts.js. */
add_action(
	'woocommerce_before_quantity_input_field',
	function () {
		echo '<button type="button" class="qty-btn qty-btn--minus" data-qty-step="-1" aria-label="' . esc_attr__( 'Diminuisci la quantità', 'slug-theme' ) . '"><span aria-hidden="true">−</span></button>';
	}
);

add_action(
	'woocommerce_after_quantity_input_field',
	function () {
		echo '<button type="button" class="qty-btn qty-btn--plus" data-qty-step="1" aria-label="' . esc_attr__( 'Aumenta la quantità', 'slug-theme' ) . '"><span aria-hidden="true">+</span></button>';
	}
);
