<?php
/**
 * Plugin richiesti e consigliati: schermata "Installa plugin" in Aspetto (libreria TGM Plugin Activation, in inc/tgmpa/).
 * Un solo elenco, qui sotto: da lì si installano e si attivano tutti i plugin in un colpo solo.
 * - Plugin del catalogo di WordPress: si scaricano da soli (basta lo slug).
 * - Starter Live e Starter Security: pacchetto allegato all'ultima release del loro repository GitHub.
 * - ACF Pro (a pagamento): si scarica con la chiave di licenza, da definire in wp-config.php come ACF_PRO_LICENSE
 *   (la stessa costante serve a ACF per attivare la licenza da solo). Senza la chiave compare un avviso.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( is_admin() ) {
	require_once get_template_directory() . '/inc/tgmpa/class-tgm-plugin-activation.php';
}

/* Indirizzo di download di ACF Pro con la chiave del sito; stringa vuota se la chiave non è definita. */
function starter_acf_pro_package() {
	if ( ! defined( 'ACF_PRO_LICENSE' ) || ! is_string( ACF_PRO_LICENSE ) || '' === ACF_PRO_LICENSE ) {
		return '';
	}

	return add_query_arg(
		array(
			'p' => 'pro',
			'k' => rawurlencode( ACF_PRO_LICENSE ),
		),
		'https://connect.advancedcustomfields.com/v2/plugins/download'
	);
}

/* Elenco dei plugin. "required" => true: il tema avvisa finché mancano; false: solo consigliato. */
function starter_register_required_plugins() {
	$plugins = array();

	// ACF Pro: nel catalogo non c'è, quindi solo con la chiave. Senza, l'avviso è in starter_acf_pro_notice().
	if ( '' !== starter_acf_pro_package() ) {
		$plugins[] = array(
			'name'     => 'ACF Pro',
			'slug'     => 'advanced-custom-fields-pro',
			'source'   => starter_acf_pro_package(),
			'required' => true,
		);
	}

	$plugins = array_merge(
		$plugins,
		array(
			array(
				'name'     => 'Contact Form 7',
				'slug'     => 'contact-form-7',
				'required' => true,
			),
			array(
				'name'     => 'Flamingo (archivio dei messaggi dei moduli)',
				'slug'     => 'flamingo',
				'required' => false,
			),
			array(
				'name'     => 'Yoast SEO',
				'slug'     => 'wordpress-seo',
				'required' => false,
			),
			array(
				'name'     => 'WooCommerce (solo per i siti con negozio)',
				'slug'     => 'woocommerce',
				'required' => false,
			),
			array(
				'name'     => 'CookieYes (avviso cookie)',
				'slug'     => 'cookie-law-info',
				'required' => false,
			),
			array(
				'name'     => 'LiteSpeed Cache (per hosting LiteSpeed, es. Hostinger)',
				'slug'     => 'litespeed-cache',
				'required' => false,
			),
			array(
				'name'     => 'Starter Live (modifica dal frontend)',
				'slug'     => 'starter-live',
				'source'   => 'https://github.com/BurOrub/StarterLiveWP/releases/latest/download/starter-live.zip',
				'required' => false,
			),
			array(
				'name'     => 'Starter Security (sicurezza)',
				'slug'     => 'starter-security',
				'source'   => 'https://github.com/BurOrub/StarterSecurityWP/releases/latest/download/starter-security.zip',
				'required' => false,
			),
		)
	);

	tgmpa(
		$plugins,
		array(
			'id'           => 'starter',
			'menu'         => 'starter-install-plugins',
			'parent_slug'  => 'themes.php',
			'capability'   => 'install_plugins',
			'has_notices'  => true,
			'dismissable'  => true,
			'is_automatic' => false,
			'strings'      => array(
				'page_title' => __( 'Installa i plugin del tema', 'slug-theme' ),
				'menu_title' => __( 'Installa plugin', 'slug-theme' ),
			),
		)
	);
}
add_action( 'tgmpa_register', 'starter_register_required_plugins' );

/* Senza la chiave di licenza ACF Pro non si può scaricare da qui: se manca, un avviso dice cosa fare. */
function starter_acf_pro_notice() {
	if ( ! current_user_can( 'install_plugins' ) || '' !== starter_acf_pro_package() || ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) ) {
		return;
	}

	if ( function_exists( 'acf_get_setting' ) || class_exists( 'ACF' ) ) {
		return;
	}

	printf(
		'<div class="notice notice-warning"><p>%s</p></div>',
		wp_kses(
			__( '<strong>ACF Pro</strong> è necessario al tema e non è attivo. Aggiungi la chiave di licenza in <code>wp-config.php</code> (<code>define( \'ACF_PRO_LICENSE\', \'la-tua-chiave\' );</code>) per scaricarlo da <em>Aspetto → Installa plugin</em>, oppure installalo a mano.', 'slug-theme' ),
			array(
				'strong' => array(),
				'code'   => array(),
				'em'     => array(),
			)
		)
	);
}
add_action( 'admin_notices', 'starter_acf_pro_notice' );
