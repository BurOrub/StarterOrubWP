<?php
/**
 * CookieYes in italiano.
 *
 * CookieYes (plugin "cookie-law-info") nasce in inglese: banner, categorie e pulsanti escono in inglese anche su un sito
 * italiano. Qui si usano i testi italiani che il plugin stesso porta con sé (con qualche ritocco alle traduzioni più
 * rigide, es. "Accettare tutto" → "Accetta tutto") e si imposta l'italiano come lingua del banner.
 *
 * - parte da sola una volta, la prima volta che si apre l'amministrazione dopo aver installato il plugin;
 * - si può rilanciare da Strumenti → Contenuti di prova → "Imposta CookieYes in italiano" (utile se qualcuno
 *   ha rimesso l'inglese o dopo aver ricreato il banner).
 * Non tocca colori, posizione, tipo di banner né i cookie che hai già descritto a mano.
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Cartella con i testi per lingua del plugin ('' se il plugin non c'è o ha un'altra struttura). */
function starter_cookieyes_dir() {
	$dir = WP_PLUGIN_DIR . '/cookie-law-info/lite/admin/modules/';

	return file_exists( $dir . 'banners/includes/contents/it.json' ) ? $dir : '';
}

/* True se CookieYes è installato e caricato. */
function starter_cookieyes_active() {
	return function_exists( 'cky_default_language' ) && '' !== starter_cookieyes_dir();
}

/* Ritocchi alle traduzioni del plugin (più naturali per un sito italiano). */
function starter_cookieyes_polish( $json ) {
	return str_replace(
		array(
			'"Accettare tutto"',
			'"Vicino"',
			'"Abilitare"',
			'"Disattivare"',
			'"Politica sui cookie"',
			'"descrizione"',
			'categorizzatati',
			'Si prega di accettare il consenso ai cookie',
		),
		array(
			'"Accetta tutto"',
			'"Chiudi"',
			'"Abilita"',
			'"Disabilita"',
			'"Informativa privacy"',
			'"Descrizione"',
			'categorizzati',
			'Accetta i cookie per vedere questo contenuto',
		),
		$json
	);
}

/**
 * Imposta l'italiano come lingua di CookieYes: impostazioni, banner (testi) e categorie.
 *
 * @return bool true se è stato fatto qualcosa.
 */
function starter_cookieyes_set_italian() {
	if ( ! starter_cookieyes_active() ) {
		return false;
	}

	global $wpdb;

	$dir     = starter_cookieyes_dir();
	$banners = json_decode( starter_cookieyes_polish( (string) file_get_contents( $dir . 'banners/includes/contents/it.json' ) ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- file del plugin, in locale.
	$cats    = json_decode( starter_cookieyes_polish( (string) file_get_contents( $dir . 'cookies/includes/contents/categories/it.json' ) ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- idem.

	if ( ! is_array( $banners ) || ! is_array( $cats ) ) {
		return false;
	}

	// 1. Lingua del plugin.
	$settings                          = (array) get_option( 'cky_settings', array() );
	$settings['languages']['default']  = 'it';
	$settings['languages']['selected'] = array( 'it' );
	update_option( 'cky_settings', $settings );

	// 2. Banner: testi italiani; il pulsante "Informativa privacy" attivo (porta alla pagina scelta in Impostazioni → Privacy).
	$table = $wpdb->prefix . 'cky_banners';
	$rows  = $wpdb->get_results( "SELECT banner_id, settings FROM {$wpdb->prefix}cky_banners" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery,WordPress.DB.PreparedSQL.NotPrepared

	foreach ( (array) $rows as $row ) {
		$config = json_decode( (string) $row->settings, true );

		if ( ! is_array( $config ) ) {
			continue;
		}

		$law = isset( $config['settings']['applicableLaw'] ) ? $config['settings']['applicableLaw'] : 'gdpr';

		if ( empty( $banners[ $law ] ) ) {
			continue;
		}

		$config['settings']['languages'] = array(
			'default'  => 'it',
			'selected' => array( 'it' ),
		);

		if ( isset( $config['config']['notice']['elements']['buttons']['elements']['readMore'] ) ) {
			$config['config']['notice']['elements']['buttons']['elements']['readMore']['status'] = true;
		}

		$wpdb->update( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			$table,
			array(
				'settings' => wp_json_encode( $config ),
				'contents' => wp_json_encode( array( 'it' => $banners[ $law ] ) ),
			),
			array( 'banner_id' => (int) $row->banner_id )
		);
	}

	// 3. Categorie (Necessari, Funzionali…): nome e descrizione in italiano.
	$cat_table = $wpdb->prefix . 'cky_cookie_categories';
	$cat_rows  = $wpdb->get_results( "SELECT category_id, slug FROM {$wpdb->prefix}cky_cookie_categories" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery,WordPress.DB.PreparedSQL.NotPrepared

	foreach ( (array) $cat_rows as $row ) {
		if ( empty( $cats[ $row->slug ] ) ) {
			continue;
		}

		$wpdb->update( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			$cat_table,
			array(
				'name'        => wp_json_encode( array( 'it' => $cats[ $row->slug ]['name'] ) ),
				'description' => wp_json_encode( array( 'it' => $cats[ $row->slug ]['description'] ) ),
			),
			array( 'category_id' => (int) $row->category_id )
		);
	}

	// 4. Svuota le copie già pronte (il banner si rigenera nella lingua nuova).
	update_option( 'cky_banner_template', '' );

	$transients = $wpdb->get_col( "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE '\\_transient\\_cky\\_%' OR option_name LIKE '\\_transient\\_timeout\\_cky\\_%'" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery,WordPress.DB.PreparedSQL.NotPrepared

	foreach ( (array) $transients as $name ) {
		delete_option( $name );
	}

	wp_cache_flush();
	update_option( 'starter_cookieyes_it', 1 );

	return true;
}

/* La prima volta che si entra in amministrazione con CookieYes ancora in inglese, lo passa in italiano. */
add_action(
	'admin_init',
	function () {
		if ( get_option( 'starter_cookieyes_it' ) || ! starter_cookieyes_active() || ! str_starts_with( (string) get_locale(), 'it' ) ) {
			return;
		}

		if ( 'en' === cky_default_language() ) {
			starter_cookieyes_set_italian();
		}
	}
);

add_action(
	'admin_post_starter_cookieyes_it',
	function () {
		check_admin_referer( 'starter_demo' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Non hai i permessi per farlo.', 'slug-theme' ) );
		}

		$ok = starter_cookieyes_set_italian();

		wp_safe_redirect(
			add_query_arg(
				'starter_demo_done',
				rawurlencode( $ok ? __( 'CookieYes impostato in italiano.', 'slug-theme' ) : __( 'CookieYes non è installato o non è attivo.', 'slug-theme' ) ),
				admin_url( 'tools.php?page=starter-demo' )
			)
		);
		exit;
	}
);
