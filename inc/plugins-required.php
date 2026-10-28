<?php
/**
 * Plugin richiesti e consigliati: schermata "Installa plugin" in Aspetto (libreria TGM Plugin Activation, in inc/tgmpa/).
 * Un solo elenco, qui sotto: da lì si installano e si attivano tutti i plugin in un colpo solo.
 * - Plugin del catalogo di WordPress: si scaricano da soli (basta lo slug).
 * - Starter Blocks (obbligatorio), Starter Live e Starter Security: pacchetto allegato all'ultima release del loro repository GitHub.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( is_admin() ) {
	require_once get_template_directory() . '/inc/tgmpa/class-tgm-plugin-activation.php';
}

/* Elenco dei plugin. "required" => true: il tema avvisa finché mancano; false: solo consigliato. */
function starter_register_required_plugins() {
	$plugins = array();

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
				'name'     => 'Starter Blocks (blocchi e campi del tema)',
				'slug'     => 'starter-blocks',
				'source'   => 'https://github.com/BurOrub/StarterBlocksWP/releases/latest/download/starter-blocks.zip',
				'required' => true,
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


/* I blocchi e i campi dei contenuti stanno nel plugin Starter Blocks: senza, il tema avvisa. */
function starter_blocks_plugin_notice() {
	if ( function_exists( 'starter_bf' ) || ! current_user_can( 'install_plugins' ) ) {
		return;
	}

	printf(
		'<div class="notice notice-warning"><p>%s</p></div>',
		esc_html__( 'Il plugin Starter Blocks non è attivo: senza di lui mancano i blocchi del tema e i campi di Portfolio e Immobili.', 'slug-theme' )
	);
}
add_action( 'admin_notices', 'starter_blocks_plugin_notice' );
