<?php
/** Custom Post Type "Immobile" */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Registra il CPT "immobile". */
function starter_imm_register_immobile_cpt() {
	register_post_type(
		'immobile',
		array(
			'labels'        => array(
				'name'               => __( 'Immobili', 'slug-theme' ),
				'singular_name'      => __( 'Immobile', 'slug-theme' ),
				'add_new'            => __( 'Aggiungi immobile', 'slug-theme' ),
				'add_new_item'       => __( 'Aggiungi nuovo immobile', 'slug-theme' ),
				'edit_item'          => __( 'Modifica immobile', 'slug-theme' ),
				'new_item'           => __( 'Nuovo immobile', 'slug-theme' ),
				'view_item'          => __( 'Vedi immobile', 'slug-theme' ),
				'all_items'          => __( 'Tutti gli immobili', 'slug-theme' ),
				'search_items'       => __( 'Cerca immobili', 'slug-theme' ),
				'not_found'          => __( 'Nessun immobile trovato', 'slug-theme' ),
				'not_found_in_trash' => __( 'Nessun immobile nel cestino', 'slug-theme' ),
				'featured_image'     => __( 'Immagine per card e hero', 'slug-theme' ),
				'set_featured_image' => __( 'Imposta immagine', 'slug-theme' ),
			),
			'public'        => true,
			'show_in_rest'  => false, // L'editor a blocchi serve solo al riquadro dei campi: il contenuto è fatto di campi, non di blocchi.
			'menu_icon'     => 'dashicons-building',
			'menu_position' => 20,
			'has_archive'   => false,
			'rewrite'       => array(
				'slug'       => 'immobili',
				'with_front' => false,
			),
			'supports'      => array( 'title', 'thumbnail', 'page-attributes' ), // page-attributes: campo "Ordine" per riordinare a mano senza cambiare la data.
		)
	);
}
add_action( 'init', 'starter_imm_register_immobile_cpt' );

/* Colonne dell'elenco admin */
function starter_imm_immobile_admin_columns( $columns ) {
	return array(
		'cb'                => $columns['cb'],
		'starter_imm_image'     => __( 'Immagine', 'slug-theme' ),
		'title'             => $columns['title'],
		'starter_imm_prezzo'    => __( 'Prezzo', 'slug-theme' ),
		'starter_imm_contratto' => __( 'Contratto', 'slug-theme' ),
		'starter_imm_zona'      => __( 'Zona', 'slug-theme' ),
		'starter_imm_ordine'    => __( 'Ordine', 'slug-theme' ),
		'taxonomy-evidenza' => __( 'Evidenza', 'slug-theme' ), // Colonna creata da show_admin_column.
		'date'              => $columns['date'],
	);
}
add_filter( 'manage_immobile_posts_columns', 'starter_imm_immobile_admin_columns' );

/* Contenuto delle colonne personalizzate. */
function starter_imm_immobile_admin_column_content( $column, $post_id ) {
	switch ( $column ) {
		case 'starter_imm_image':
			echo get_the_post_thumbnail( $post_id, array( 60, 60 ) ) ?: '&mdash;'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			break;

		case 'starter_imm_prezzo':
			$prezzo = starter_field( 'prezzo', $post_id );
			echo '' !== (string) $prezzo ? esc_html( starter_imm_format_price( $prezzo ) ) : '&mdash;';
			break;

		case 'starter_imm_ordine':
			echo (int) get_post_field( 'menu_order', $post_id );
			break;

		case 'starter_imm_contratto':
		case 'starter_imm_zona':
			$terms = get_the_terms( $post_id, 'starter_imm_contratto' === $column ? 'contratto' : 'zona' );
			echo $terms && ! is_wp_error( $terms ) ? esc_html( implode( ', ', wp_list_pluck( $terms, 'name' ) ) ) : '&mdash;';
			break;
	}
}
add_action( 'manage_immobile_posts_custom_column', 'starter_imm_immobile_admin_column_content', 10, 2 );

/* Filtro a tendina "Evidenza" sopra l'elenco admin degli immobili. */
function starter_imm_immobile_admin_evidenza_filter( $post_type ) {
	if ( 'immobile' !== $post_type ) {
		return;
	}

	$selected = isset( $_GET['starter_imm_evidenza'] ) ? sanitize_title( wp_unslash( $_GET['starter_imm_evidenza'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	wp_dropdown_categories(
		array(
			'taxonomy'        => 'evidenza',
			'name'            => 'starter_imm_evidenza',
			'value_field'     => 'slug',
			'selected'        => $selected,
			'show_option_all' => __( 'Tutte le evidenze', 'slug-theme' ),
			'hierarchical'    => true,
			'hide_empty'      => false,
			'orderby'         => 'name',
		)
	);
}
add_action( 'restrict_manage_posts', 'starter_imm_immobile_admin_evidenza_filter' );

/* Applica il filtro Evidenza alla query dell'elenco admin (query_var disattivato sulla */
function starter_imm_immobile_admin_evidenza_query( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() || 'immobile' !== $query->get( 'post_type' ) ) {
		return;
	}

	$term = isset( $_GET['starter_imm_evidenza'] ) ? sanitize_title( wp_unslash( $_GET['starter_imm_evidenza'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	if ( '' === $term || '0' === $term ) {
		return;
	}

	$query->set(
		'tax_query',
		array(
			array(
				'taxonomy' => 'evidenza',
				'field'    => 'slug',
				'terms'    => $term,
			),
		)
	);
}
add_action( 'pre_get_posts', 'starter_imm_immobile_admin_evidenza_query' );

/* Elenco admin: ordinato come il sito (campo "Ordine", poi data) finché non si clicca su un'altra colonna. */
add_action(
	'pre_get_posts',
	function ( $query ) {
		if ( ! is_admin() || ! $query->is_main_query() || 'immobile' !== $query->get( 'post_type' ) || $query->get( 'orderby' ) ) {
			return;
		}

		$query->set(
			'orderby',
			array(
				'menu_order' => 'ASC',
				'date'       => 'DESC',
			)
		);
	}
);

/* Colonna "Ordine" ordinabile nell'elenco admin. */
add_filter(
	'manage_edit-immobile_sortable_columns',
	function ( $columns ) {
		$columns['starter_imm_ordine'] = 'menu_order';

		return $columns;
	}
);
