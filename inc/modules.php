<?php
/**
 * Moduli (CPT): funzioni facoltative del tema che si accendono e si spengono da Personalizza → Opzioni → Moduli (CPT).
 *
 * Ogni modulo è una cartella/un file a parte (CPT, tassonomie, campi, blocchi, template, CSS). Se il modulo è spento il suo codice
 * non viene nemmeno caricato: niente CPT, campi, blocchi, CSS, JS né query in più. L'interruttore sta nei theme_mod, che WordPress
 * legge comunque una volta per richiesta con le altre opzioni del tema.
 *
 * Aggiungere un modulo = una voce in starter_modules() (+ i suoi file). L'interruttore compare da solo nel Personalizza.
 *
 * Quando un modulo si accende o si spegne (alla richiesta dopo il salvataggio):
 * - gli indirizzi del sito si aggiornano (una volta sola);
 * - se il modulo ha una pagina (es. "Portfolio", "Immobili") la si crea, o si ripubblica se l'aveva messa in bozza il tema;
 * - spegnendo, le pagine create dal tema per quel modulo tornano in bozza (il link smette di funzionare); i contenuti restano nel database.
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Elenco dei moduli.
 *
 * - label, description: testo dell'interruttore (stringhe semplici, tradotte quando si mostrano: il registro si legge prima di init);
 * - file: file da caricare quando il modulo è acceso (relativo al tema);
 * - blocks: cartelle di template-parts/ dei blocchi del modulo (si registrano solo a modulo acceso);
 * - page: pagina creata all'attivazione (slug, title, block = blocco che la identifica, content = funzione che ne scrive il contenuto);
 * - demo: funzione dei contenuti di prova (lanciata da Strumenti → Contenuti di prova);
 * - activate: funzione lanciata quando il modulo si accende (es. crea un modulo di contatto);
 * - default: acceso di partenza (no: ogni sito accende solo ciò che gli serve).
 */
function starter_modules() {
	return apply_filters(
		'starter_modules',
		array(
			'portfolio' => array(
				'label'       => 'Portfolio',
				'description' => 'Progetti con categorie, scheda del progetto e blocco "Elenco progetti". Crea la pagina Portfolio.',
				'file'        => 'inc/portfolio.php',
				'blocks'      => array( 'progetti', 'progetto-testo', 'progetto-media', 'progetto-crediti', 'progetto-testata' ),
				'page'        => array(
					'slug'    => 'portfolio',
					'title'   => 'Portfolio',
					'block'   => 'progetti',
					'content' => 'starter_portfolio_page_content',
				),
				'demo'        => 'starter_portfolio_demo',
				'default'     => false,
			),
			'immobili'  => array(
				'label'       => 'Immobili',
				'description' => 'Immobili con filtri, scheda con galleria, planimetria, tour virtuale e PDF, e i blocchi Immobili — Hero home, In evidenza ed Elenco. Crea la pagina Immobili.',
				'file'        => 'inc/modules/immobili/immobili.php',
				'blocks'      => array( 'immobili-hero-home', 'immobili-in-evidenza', 'elenco-immobili' ),
				'page'        => array(
					'slug'    => 'immobili',
					'title'   => 'Immobili',
					'block'   => 'elenco-immobili',
					'content' => 'starter_immobili_page_content',
				),
				'demo'        => 'starter_immobili_demo',
				'activate'    => 'starter_immobili_activate',
				'default'     => false,
			),
		)
	);
}

/* Il modulo è acceso? */
function starter_module_active( $id ) {
	$modules = starter_modules();

	if ( ! isset( $modules[ $id ] ) ) {
		return false;
	}

	return (bool) get_theme_mod( 'starter_module_' . $id, ! empty( $modules[ $id ]['default'] ) );
}

/* Moduli accesi: id => definizione. */
function starter_modules_active() {
	return array_filter(
		starter_modules(),
		static function ( $module, $id ) {
			return starter_module_active( $id );
		},
		ARRAY_FILTER_USE_BOTH
	);
}

/* Cartelle dei blocchi dei moduli spenti (starter_theme_register_acf_blocks le salta). */
function starter_modules_inactive_blocks() {
	$blocks = array();

	foreach ( starter_modules() as $id => $module ) {
		if ( ! starter_module_active( $id ) ) {
			$blocks = array_merge( $blocks, (array) $module['blocks'] );
		}
	}

	return $blocks;
}

/* Carica i file dei moduli accesi, e solo quelli. */
foreach ( starter_modules_active() as $starter_module ) {
	$starter_module_file = get_template_directory() . '/' . $starter_module['file'];

	if ( is_readable( $starter_module_file ) ) {
		require_once $starter_module_file;
	}
}

unset( $starter_module, $starter_module_file );

/* ------------------------------------------------------------------ Pagina del modulo */

/* Pagina del modulo creata dal tema (o già presente con il suo blocco): la crea se manca, la ripubblica se l'aveva messa in bozza il tema. */
function starter_module_ensure_page( $id ) {
	$modules = starter_modules();

	if ( empty( $modules[ $id ]['page'] ) ) {
		return 0;
	}

	$page = $modules[ $id ]['page'];

	// 1) Pagina creata dal tema per questo modulo.
	$flagged = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => array( 'publish', 'draft', 'pending', 'private' ),
			'meta_key'       => '_starter_module_page', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_value'     => $id, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);

	if ( $flagged ) {
		$page_id = (int) $flagged[0];

		if ( 'draft' === get_post_status( $page_id ) && get_post_meta( $page_id, '_starter_module_drafted', true ) ) {
			wp_update_post(
				array(
					'ID'          => $page_id,
					'post_status' => 'publish',
				)
			);
			delete_post_meta( $page_id, '_starter_module_drafted' );
		}

		return $page_id;
	}

	// 2) Pagina fatta a mano che contiene già il blocco principale del modulo: non se ne crea un'altra.
	$mine = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => array( 'publish', 'draft', 'pending', 'private' ),
			's'              => 'wp:starter-theme/' . $page['block'],
			'search_columns' => array( 'post_content' ),
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);

	if ( $mine ) {
		return (int) $mine[0];
	}

	// 3) Si crea.
	$content = is_callable( $page['content'] ) ? call_user_func( $page['content'] ) : '';
	$page_id = wp_insert_post(
		array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_name'    => $page['slug'],
			'post_title'   => __( $page['title'], 'slug-theme' ), // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText
			'post_content' => wp_slash( $content ),
		)
	);

	if ( $page_id && ! is_wp_error( $page_id ) ) {
		update_post_meta( $page_id, '_starter_module_page', $id );

		return (int) $page_id;
	}

	return 0;
}

/* Modulo spento: le sue pagine create dal tema tornano in bozza (il link smette di funzionare); i contenuti restano. */
function starter_module_draft_pages( $id ) {
	$pages = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'meta_key'       => '_starter_module_page', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_value'     => $id, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			'posts_per_page' => -1,
			'fields'         => 'ids',
		)
	);

	foreach ( $pages as $page_id ) {
		wp_update_post(
			array(
				'ID'          => $page_id,
				'post_status' => 'draft',
			)
		);
		update_post_meta( $page_id, '_starter_module_drafted', 1 );
	}
}

/* ------------------------------------------------------------------ Accensione e spegnimento */

add_action(
	'init',
	static function () {
		$active = array_keys( starter_modules_active() );
		sort( $active );
		$state = implode( ',', $active );
		$prev  = get_option( 'starter_modules_state', null );

		if ( $prev === $state ) {
			return;
		}

		update_option( 'starter_modules_state', $state );
		flush_rewrite_rules( false );

		$before = $prev ? explode( ',', (string) $prev ) : array();

		$modules = starter_modules();

		foreach ( array_diff( $active, $before ) as $id ) {
			starter_module_ensure_page( $id );

			if ( ! empty( $modules[ $id ]['activate'] ) && is_callable( $modules[ $id ]['activate'] ) ) {
				call_user_func( $modules[ $id ]['activate'] );
			}
		}

		foreach ( array_diff( $before, $active ) as $id ) {
			starter_module_draft_pages( $id );
		}
	},
	99
);

/* ------------------------------------------------------------------ Personalizza → Opzioni → Moduli (CPT) */

add_action(
	'customize_register',
	static function ( $wp_customize ) {
		$section = 'starter_options_modules';

		$wp_customize->add_section(
			$section,
			array(
				'title'       => __( 'Moduli (CPT)', 'slug-theme' ),
				'description' => __( 'Funzioni facoltative del tema: attiva solo quelle che servono al sito. Dopo aver pubblicato, ricarica la pagina: il modulo acceso aggiunge i suoi contenuti, i suoi blocchi e la sua pagina; spento, il suo codice non viene caricato (i contenuti già creati restano e tornano riaccendendolo).', 'slug-theme' ),
				'panel'       => 'starter_options',
				'priority'    => 12,
			)
		);

		$priority = 1;

		foreach ( starter_modules() as $id => $module ) {
			starter_customizer_checkbox( $wp_customize, 'starter_module_' . $id, $section, __( $module['label'], 'slug-theme' ), ! empty( $module['default'] ), __( $module['description'], 'slug-theme' ), $priority++ ); // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText
		}
	},
	20
);
