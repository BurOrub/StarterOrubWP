<?php
/**
 * Clona: voce "Clona" negli elenchi di pagine, articoli e CPT (Portfolio, Immobili…).
 * Crea una copia in bozza con stesso titolo, contenuto, campi (ACF compresi),
 * immagine in evidenza, categorie e genitore. Lo slug non si copia: in bozza non c'è.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Tipi di contenuto in cui compare "Clona".
 * Esclusi allegati e prodotti (varianti e SKU univoco non si copiano così).
 */
function starter_clone_post_types() {
	static $cache = null; // Si chiama a ogni riga dell'elenco: si calcola una volta sola.

	if ( null === $cache ) {
		$types = get_post_types( array( 'show_ui' => true ), 'names' );
		unset( $types['attachment'], $types['product'], $types['shop_order'], $types['shop_coupon'] );
		$cache = array_values( apply_filters( 'starter_clone_post_types', $types ) );
	}

	return $cache;
}

/**
 * Crea la copia. Restituisce l'ID nuovo, o 0 se non riesce.
 */
function starter_clone_post( $post_id ) {
	$post = get_post( $post_id );
	if ( ! $post || ! in_array( $post->post_type, starter_clone_post_types(), true ) ) {
		return 0;
	}

	$new_id = wp_insert_post(
		wp_slash(
			array(
				'post_type'      => $post->post_type,
				'post_status'    => 'draft',
				'post_title'     => $post->post_title,
				'post_content'   => $post->post_content,
				'post_excerpt'   => $post->post_excerpt,
				'post_parent'    => $post->post_parent,
				'menu_order'     => $post->menu_order,
				'comment_status' => $post->comment_status,
				'ping_status'    => $post->ping_status,
				'post_password'  => $post->post_password,
				'post_author'    => get_current_user_id(),
			)
		),
		true
	);
	if ( is_wp_error( $new_id ) || ! $new_id ) {
		return 0;
	}

	// Campi personalizzati (ACF, immagine in evidenza, modello di pagina, SEO…).
	$skip = array( '_edit_lock', '_edit_last', '_wp_old_slug', '_wp_trash_meta_status', '_wp_trash_meta_time', '_wp_desired_post_slug' );
	foreach ( get_post_meta( $post->ID ) as $key => $values ) {
		if ( in_array( $key, $skip, true ) ) {
			continue;
		}
		foreach ( $values as $value ) {
			add_post_meta( $new_id, $key, wp_slash( maybe_unserialize( $value ) ) );
		}
	}

	// Categorie, tag e altre tassonomie.
	foreach ( get_object_taxonomies( $post->post_type ) as $taxonomy ) {
		$terms = wp_get_object_terms( $post->ID, $taxonomy, array( 'fields' => 'ids' ) );
		if ( ! is_wp_error( $terms ) && $terms ) {
			wp_set_object_terms( $new_id, $terms, $taxonomy );
		}
	}

	return (int) $new_id;
}

/**
 * Voce "Clona" accanto a Modifica / Modifica rapida / Cestina / Visualizza.
 */
function starter_clone_row_action( $actions, $post ) {
	$type = get_post_type_object( $post->post_type );
	if (
		! $type
		|| ! in_array( $post->post_type, starter_clone_post_types(), true )
		|| ! current_user_can( 'edit_post', $post->ID )
		|| ! current_user_can( $type->cap->create_posts )
	) {
		return $actions;
	}

	$url = wp_nonce_url(
		admin_url( 'admin.php?action=starter_clone&post=' . $post->ID ),
		'starter_clone_' . $post->ID
	);

	$actions['starter_clone'] = sprintf(
		'<a href="%s" aria-label="%s">%s</a>',
		esc_url( $url ),
		esc_attr( sprintf( 'Clona “%s”', get_the_title( $post ) ) ),
		'Clona'
	);

	return $actions;
}
add_filter( 'post_row_actions', 'starter_clone_row_action', 20, 2 );
add_filter( 'page_row_actions', 'starter_clone_row_action', 20, 2 );

/**
 * Esegue la copia e torna all'elenco.
 */
function starter_clone_handle() {
	$post_id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0;
	check_admin_referer( 'starter_clone_' . $post_id );

	$post = get_post( $post_id );
	$type = $post ? get_post_type_object( $post->post_type ) : null;
	if ( ! $post || ! $type || ! current_user_can( 'edit_post', $post_id ) || ! current_user_can( $type->cap->create_posts ) ) {
		wp_die( 'Non puoi clonare questo contenuto.', '', array( 'response' => 403 ) );
	}

	$new_id = starter_clone_post( $post_id );
	if ( ! $new_id ) {
		wp_die( 'Non sono riuscito a clonare il contenuto.' );
	}

	wp_safe_redirect(
		add_query_arg(
			array(
				'post_type'      => $post->post_type,
				'starter_cloned' => $new_id,
			),
			admin_url( 'edit.php' )
		)
	);
	exit;
}
add_action( 'admin_action_starter_clone', 'starter_clone_handle' );

/**
 * Avviso dopo la copia, con il collegamento per modificarla.
 */
function starter_clone_notice() {
	if ( empty( $_GET['starter_cloned'] ) ) {
		return;
	}
	$new_id = absint( $_GET['starter_cloned'] );
	if ( ! $new_id || ! current_user_can( 'edit_post', $new_id ) ) {
		return;
	}
	printf(
		'<div class="notice notice-success is-dismissible"><p>Contenuto clonato come bozza. <a href="%s">Modifica la copia</a></p></div>',
		esc_url( get_edit_post_link( $new_id, 'raw' ) )
	);
}
add_action( 'admin_notices', 'starter_clone_notice' );
