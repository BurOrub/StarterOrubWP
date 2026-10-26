<?php
/** Tassonomie dell'Immobile e termini iniziali. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Elenco delle tassonomie */
function starter_imm_immobile_taxonomies() {
	return array(
		'tipologia'       => array(
			'singular'     => __( 'Tipologia', 'slug-theme' ),
			'plural'       => __( 'Tipologie', 'slug-theme' ),
			'hierarchical' => true,
		),
		'contratto'       => array(
			'singular'     => __( 'Contratto', 'slug-theme' ),
			'plural'       => __( 'Contratti', 'slug-theme' ),
			'hierarchical' => true,
		),
		'zona'            => array(
			'singular'     => __( 'Zona', 'slug-theme' ),
			'plural'       => __( 'Zone', 'slug-theme' ),
			'hierarchical' => true, // Comune › quartiere.
		),
		'consulente'      => array(
			'singular'     => __( 'Consulente', 'slug-theme' ),
			'plural'       => __( 'Consulenti', 'slug-theme' ),
			'hierarchical' => true, // Casella da spuntare: nessuna scelta = "Team".
		),
		'evidenza'        => array(
			'singular'     => __( 'Evidenza', 'slug-theme' ),
			'plural'       => __( 'Evidenze', 'slug-theme' ),
			'hierarchical' => true,
			'admin_column' => true, // Solo backend: dice se l'immobile è in hero o in evidenza sulla home.
		),
	);
}

/* Termini creati quando il modulo si accende, solo se non esistono già. */
function starter_imm_immobile_initial_terms() {
	return array(
		'tipologia'       => array( 'Appartamento', 'Attico', 'Villa', 'Casa indipendente', 'Loft', 'Ufficio', 'Negozio' ),
		'contratto'       => array( 'Vendita', 'Affitto', 'Asta' ),
		'consulente'      => array( 'Team' ),
		'evidenza'        => array( 'Hero home', 'In evidenza home' ),
	);
}

/* Registra le tassonomie collegate al CPT "immobile". */
function starter_imm_register_immobile_taxonomies() {
	foreach ( starter_imm_immobile_taxonomies() as $slug => $config ) {
		$labels = array(
			'name'          => $config['plural'],
			'singular_name' => $config['singular'],
			'menu_name'     => isset( $config['menu_name'] ) ? $config['menu_name'] : $config['plural'],
			/* translators: %s: nome della tassonomia. */
			'search_items'  => sprintf( __( 'Cerca in %s', 'slug-theme' ), $config['plural'] ),
			/* translators: %s: nome della tassonomia. */
			'all_items'     => sprintf( __( 'Tutti: %s', 'slug-theme' ), $config['plural'] ),
			/* translators: %s: nome della tassonomia. */
			'edit_item'     => sprintf( __( 'Modifica: %s', 'slug-theme' ), $config['singular'] ),
			/* translators: %s: nome della tassonomia. */
			'add_new_item'  => sprintf( __( 'Aggiungi: %s', 'slug-theme' ), $config['singular'] ),
			'not_found'     => __( 'Nessun termine trovato', 'slug-theme' ),
		);

		register_taxonomy(
			$slug,
			'immobile',
			array(
				'labels'             => $labels,
				'hierarchical'       => $config['hierarchical'],
				'public'             => false,
				'publicly_queryable' => false,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'show_in_nav_menus'  => false,
				'show_in_rest'       => false,
				'show_admin_column'  => ! empty( $config['admin_column'] ),
				'rewrite'            => false,
				'query_var'          => false,
			)
		);
	}
}
add_action( 'init', 'starter_imm_register_immobile_taxonomies' );

/* Crea i termini iniziali mancanti, una volta sola (la prima volta che il modulo è acceso). */
function starter_imm_seed_initial_terms() {
	if ( get_option( 'starter_imm_terms_seeded' ) ) {
		return;
	}

	foreach ( starter_imm_immobile_initial_terms() as $taxonomy => $names ) {
		foreach ( $names as $name ) {
			if ( ! term_exists( $name, $taxonomy ) ) {
				wp_insert_term( $name, $taxonomy );
			}
		}
	}

	update_option( 'starter_imm_terms_seeded', 1 );
}
add_action( 'init', 'starter_imm_seed_initial_terms', 20 );
