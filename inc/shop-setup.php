<?php
/**
 * Impostazioni di partenza del negozio: spedizione in Italia e avviso "negozio in test".
 *
 * - Spedizione Italia: 5 € a ordine, gratis da 99 € di prodotti. Quando la spedizione gratuita è disponibile
 *   il costo fisso non viene proposto (starter_shop_hide_flat_rate). Si crea da Strumenti → Contenuti di prova
 *   (o con starter_shop_setup_shipping()); poi si cambia da WooCommerce → Impostazioni → Spedizione.
 * - Negozio in test: pagamento alla consegna attivo per provare un ordine intero e una barra con l'avviso su tutte le
 *   pagine (Opzioni → Shop → "Negozio in test"). Da spegnere prima di andare online.
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Zona "Italia" con spedizione a 5 € e spedizione gratuita sopra i 99 €. Non tocca una zona Italia già esistente. */
function starter_shop_setup_shipping( $flat = '5', $free_from = '99' ) {
	if ( ! class_exists( 'WC_Shipping_Zone' ) ) {
		return false;
	}

	foreach ( WC_Shipping_Zones::get_zones() as $zone_data ) {
		if ( 'Italia' === $zone_data['zone_name'] ) {
			return false;
		}
	}

	$zone = new WC_Shipping_Zone();
	$zone->set_zone_name( 'Italia' );
	$zone->add_location( 'IT', 'country' );
	$zone->save();

	$flat_id = $zone->add_shipping_method( 'flat_rate' );
	$free_id = $zone->add_shipping_method( 'free_shipping' );

	update_option(
		'woocommerce_flat_rate_' . $flat_id . '_settings',
		array(
			'title'      => __( 'Spedizione in Italia', 'slug-theme' ),
			'tax_status' => 'none',
			'cost'       => $flat,
		)
	);
	update_option(
		'woocommerce_free_shipping_' . $free_id . '_settings',
		array(
			'title'      => __( 'Spedizione gratuita', 'slug-theme' ),
			'requires'   => 'min_amount',
			'min_amount' => $free_from,
		)
	);

	return true;
}

/* Con la spedizione gratuita disponibile si propone solo quella (niente "5 €" accanto a "Gratis"). */
add_filter(
	'woocommerce_package_rates',
	function ( $rates ) {
		$free = array();

		foreach ( $rates as $rate_id => $rate ) {
			if ( 'free_shipping' === $rate->method_id ) {
				$free[ $rate_id ] = $rate;
			}
		}

		return $free ? $free : $rates;
	},
	100
);

/* Negozio in test: attiva il pagamento alla consegna (per completare un ordine di prova) e l'avviso su tutte le pagine. */
function starter_shop_setup_test() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	$cod            = (array) get_option( 'woocommerce_cod_settings', array() );
	$cod['enabled'] = 'yes';
	update_option( 'woocommerce_cod_settings', $cod );

	set_theme_mod( 'starter_shop_test_mode', true );

	if ( '' === trim( (string) get_theme_mod( 'starter_shop_test_text', '' ) ) ) {
		set_theme_mod( 'starter_shop_test_text', __( 'Negozio in modalità test: gli ordini non vengono evasi e nessun pagamento è reale.', 'slug-theme' ) );
	}
}

/* Avviso "negozio in test" (Opzioni → Shop): in fondo alla pagina, sotto il footer, ben evidente. Colore fisso (giallo) di proposito: deve vedersi
   su qualunque palette ed è un avviso da spegnere online. */
add_action(
	'wp_footer',
	function () {
		if ( ! starter_has_woocommerce() || ! get_theme_mod( 'starter_shop_test_mode', false ) ) {
			return;
		}

		$text = trim( (string) get_theme_mod( 'starter_shop_test_text', '' ) );

		if ( '' === $text ) {
			$text = __( 'Negozio in modalità test.', 'slug-theme' );
		}

		echo '<div class="shop-test-notice" role="status">' . esc_html( $text ) . '</div>';
	},
	1
);

/* Testo sotto il titolo della pagina Shop: se la pagina Shop non ha testo ne mette uno di esempio (si cambia dalla pagina). */
function starter_shop_page_intro() {
	if ( ! function_exists( 'wc_get_page_id' ) ) {
		return;
	}

	$id = (int) wc_get_page_id( 'shop' );

	if ( $id > 0 && '' === trim( (string) get_post_field( 'post_content', $id ) ) ) {
		wp_update_post(
			array(
				'ID'           => $id,
				'post_content' => "<!-- wp:paragraph -->\n<p>Una selezione dei nostri prodotti: scegli quello che fa per te, aggiungilo al carrello e ricevilo a casa.</p>\n<!-- /wp:paragraph -->",
			)
		);
	}
}
