<?php
/**
 * Aggiornamenti del tema da GitHub: WordPress mostra la notifica classica (Bacheca → Aggiornamenti, pagina Temi) e aggiorna con un clic.
 * Il sito guarda l'ultima "Release" del repository: il tag (es. v1.2.0) è la versione, il file .zip allegato alla release è il pacchetto.
 * Funziona anche con un tema figlio attivo: si aggiorna sempre il padre (cartella starter-theme), il figlio non viene toccato.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Repository GitHub nel formato "utente/repo". Vuoto = nessun controllo (il tema funziona normalmente, senza notifiche).
 * Si può definire anche in wp-config.php (prima di wp-settings.php) per un sito che usa un altro repository. */
if ( ! defined( 'STARTER_UPDATE_REPO' ) ) {
	define( 'STARTER_UPDATE_REPO', 'BurOrub/StarterOrubWP' );
}

/* Facoltativo, solo per repository privati: token GitHub di sola lettura, da definire in wp-config.php come STARTER_GITHUB_TOKEN. */

/* Repository da controllare, già ripulito: solo "utente/repo" valido, altrimenti stringa vuota. */
function starter_update_repo() {
	$repo = trim( (string) apply_filters( 'starter_update_repo', STARTER_UPDATE_REPO ) );

	return preg_match( '#^[A-Za-z0-9_.-]+/[A-Za-z0-9_.-]+$#', $repo ) ? $repo : '';
}

/* Ultima release pubblicata: array( version, package, url ) oppure false. Il risultato resta in cache 6 ore (30 minuti se GitHub non risponde). */
function starter_update_latest_release( $force = false ) {
	$repo = starter_update_repo();

	if ( '' === $repo ) {
		return false;
	}

	$key = 'starter_update_release';

	if ( $force ) {
		delete_transient( $key );
	}

	$cached = get_transient( $key );

	if ( false !== $cached ) {
		return is_array( $cached ) ? $cached : false;
	}

	$args = array(
		'timeout' => 10,
		'headers' => array(
			'Accept'     => 'application/vnd.github+json',
			'User-Agent' => 'starter-theme-updater',
		),
	);

	if ( defined( 'STARTER_GITHUB_TOKEN' ) && STARTER_GITHUB_TOKEN ) {
		$args['headers']['Authorization'] = 'Bearer ' . STARTER_GITHUB_TOKEN;
	}

	$response = wp_remote_get( 'https://api.github.com/repos/' . $repo . '/releases/latest', $args );
	$data     = is_wp_error( $response ) || 200 !== (int) wp_remote_retrieve_response_code( $response )
		? null
		: json_decode( wp_remote_retrieve_body( $response ), true );

	if ( ! is_array( $data ) || empty( $data['tag_name'] ) ) {
		set_transient( $key, 'errore', 30 * MINUTE_IN_SECONDS );

		return false;
	}

	// Pacchetto: il .zip allegato alla release (struttura pulita, creato dalla build); se manca, lo zip automatico di GitHub.
	$package = '';

	foreach ( (array) ( $data['assets'] ?? array() ) as $asset ) {
		if ( isset( $asset['name'], $asset['browser_download_url'] ) && preg_match( '/^starter-theme.*\.zip$/i', $asset['name'] ) ) {
			$package = defined( 'STARTER_GITHUB_TOKEN' ) && STARTER_GITHUB_TOKEN && ! empty( $asset['url'] ) ? $asset['url'] : $asset['browser_download_url'];
			break;
		}
	}

	if ( '' === $package && ! empty( $data['zipball_url'] ) ) {
		$package = $data['zipball_url'];
	}

	// Il pacchetto deve stare su GitHub: niente indirizzi di altri siti, qualunque cosa risponda l'API.
	$host = wp_parse_url( $package, PHP_URL_HOST );

	if ( ! in_array( $host, array( 'github.com', 'api.github.com', 'codeload.github.com' ), true ) ) {
		$package = '';
	}

	$release = array(
		'version' => ltrim( (string) $data['tag_name'], 'vV' ),
		'package' => $package,
		'url'     => ! empty( $data['html_url'] ) ? esc_url_raw( $data['html_url'] ) : 'https://github.com/' . $repo . '/releases',
	);

	set_transient( $key, $release, 6 * HOUR_IN_SECONDS );

	return $release;
}

/* Dice a WordPress qual è l'ultima versione del padre: se è più nuova compare la notifica di aggiornamento, altrimenti il tema risulta aggiornato. */
add_filter(
	'pre_set_site_transient_update_themes',
	function ( $transient ) {
		if ( ! is_object( $transient ) ) {
			return $transient;
		}

		$release = starter_update_latest_release();

		if ( ! $release || '' === $release['version'] ) {
			return $transient;
		}

		$slug    = get_template();
		$current = wp_get_theme( $slug )->get( 'Version' );
		$item    = array(
			'theme'        => $slug,
			'new_version'  => $release['version'],
			'url'          => $release['url'],
			'package'      => $release['package'],
			'requires'     => '',
			'requires_php' => '',
		);

		if ( $current && version_compare( $release['version'], $current, '>' ) ) {
			$transient->response[ $slug ] = $item;
			unset( $transient->no_update[ $slug ] );
		} else {
			$transient->no_update[ $slug ] = $item;
			unset( $transient->response[ $slug ] );
		}

		return $transient;
	}
);

/* Lo zip automatico di GitHub si estrae in una cartella "utente-repo-hash": la rinomina in starter-theme, altrimenti WordPress installerebbe un tema nuovo. */
add_filter(
	'upgrader_source_selection',
	function ( $source, $remote_source, $upgrader, $hook_extra ) {
		$slug = get_template();

		if ( empty( $hook_extra['theme'] ) || $slug !== $hook_extra['theme'] || basename( untrailingslashit( $source ) ) === $slug ) {
			return $source;
		}

		global $wp_filesystem;

		$target = trailingslashit( $remote_source ) . $slug . '/';

		if ( $wp_filesystem && $wp_filesystem->move( untrailingslashit( $source ), untrailingslashit( $target ), true ) ) {
			return $target;
		}

		return new WP_Error( 'starter_update_rename', __( 'Impossibile preparare la cartella del tema aggiornato.', 'slug-theme' ) );
	},
	10,
	4
);

/* Repository privato: il download del pacchetto da GitHub porta il token (e chiede il file, non la pagina della release). */
add_filter(
	'http_request_args',
	function ( $args, $url ) {
		$repo = starter_update_repo();

		if ( '' === $repo || ! defined( 'STARTER_GITHUB_TOKEN' ) || ! STARTER_GITHUB_TOKEN || 0 !== strpos( $url, 'https://api.github.com/repos/' . $repo . '/' ) ) {
			return $args;
		}

		$args['headers']['Authorization'] = 'Bearer ' . STARTER_GITHUB_TOKEN;

		if ( false !== strpos( $url, '/releases/assets/' ) || false !== strpos( $url, '/zipball/' ) ) {
			$args['headers']['Accept'] = 'application/octet-stream';
		}

		return $args;
	},
	10,
	2
);

/* "Controlla di nuovo" in Bacheca → Aggiornamenti ignora la cache e interroga subito GitHub. */
add_action(
	'load-update-core.php',
	function () {
		if ( isset( $_GET['force-check'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			delete_transient( 'starter_update_release' );
		}
	}
);

/* Dopo un aggiornamento del tema si scarta la cache, così la notifica sparisce subito. */
add_action(
	'upgrader_process_complete',
	function ( $upgrader, $hook_extra ) {
		if ( isset( $hook_extra['type'] ) && 'theme' === $hook_extra['type'] ) {
			delete_transient( 'starter_update_release' );
		}
	},
	10,
	2
);
