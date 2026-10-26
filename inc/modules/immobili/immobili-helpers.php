<?php
/** Funzioni di supporto per gli immobili */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* "1300000" → "€ 1.300.000". */
function starter_imm_format_price( $price ) {
	return '€ ' . number_format_i18n( (float) $price, 0 );
}

/* Prezzo dell'immobile formattato, oppure "Trattativa riservata" se il campo è vuoto. */
function starter_imm_get_price_label( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$price   = starter_imm_field( 'prezzo', $post_id );

	if ( '' === (string) $price || null === $price || false === $price ) {
		return __( 'Trattativa riservata', 'slug-theme' );
	}

	$label = starter_imm_format_price( $price );

	// Affitto
	if ( 'affitto' === sanitize_title( starter_imm_get_term_name( 'contratto', $post_id ) ) ) {
		$label .= ' ' . __( '/mese', 'slug-theme' );
	}

	return $label;
}

/* Legge un campo ACF; se ACF non è attivo ripiega sul meta grezzo, così il tema non va in */
function starter_imm_field( $name, $post_id = null, $option = false ) {
	$target = $option ? 'option' : ( $post_id ? $post_id : get_the_ID() );

	if ( function_exists( 'get_field' ) ) {
		return get_field( $name, $target );
	}

	return $option ? get_option( 'options_' . $name, '' ) : get_post_meta( $target, $name, true );
}

/* Titolo mostrato nella scheda */
function starter_imm_get_page_title_html( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$custom  = starter_imm_field( 'titolo_pagina', $post_id );

	if ( is_string( $custom ) && '' !== trim( $custom ) ) {
		return wp_kses_post( $custom );
	}

	return esc_html( get_the_title( $post_id ) );
}

/* Etichetta zona per card e scheda */
function starter_imm_get_zone_label( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$terms   = get_the_terms( $post_id, 'zona' );

	if ( ! $terms || is_wp_error( $terms ) ) {
		return '';
	}

	// Il termine più profondo (quartiere) prima, poi il suo comune.
	usort(
		$terms,
		function ( $a, $b ) {
			return count( get_ancestors( $b->term_id, 'zona' ) ) - count( get_ancestors( $a->term_id, 'zona' ) );
		}
	);

	$term  = $terms[0];
	$names = array( $term->name );

	foreach ( get_ancestors( $term->term_id, 'zona', 'taxonomy' ) as $ancestor_id ) {
		$ancestor = get_term( $ancestor_id, 'zona' );

		if ( $ancestor && ! is_wp_error( $ancestor ) ) {
			$names[] = $ancestor->name;
		}
	}

	return implode( ' · ', $names );
}

/* Nome del primo termine di una tassonomia dell'immobile ('' se assente). */
function starter_imm_get_term_name( $taxonomy, $post_id = null ) {
	$terms = get_the_terms( $post_id ? $post_id : get_the_ID(), $taxonomy );

	return ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
}

/* Badge dell'immobile */
function starter_imm_get_badges( $post_id = null ) {
	$rows   = starter_imm_field( 'etichette', $post_id );
	$badges = array();

	if ( is_array( $rows ) ) {
		foreach ( $rows as $row ) {
			$label = ! empty( $row['etichette_elenco'] ) ? $row['etichette_scelta'] : $row['etichette_testo'];

			if ( ! empty( $label ) ) {
				$badges[] = $label;
			}
		}
	}

	return $badges;
}

/* Testo del contratto per i badge */
function starter_imm_get_contract_badge( $post_id = null ) {
	$name = starter_imm_get_term_name( 'contratto', $post_id );

	return $name ? sprintf( __( 'In %s', 'slug-theme' ), mb_strtolower( $name ) ) : '';
}

/* "5 locali", "1 bagno" */
function starter_imm_count_label( $n, $singular, $plural, $plus = false ) {
	$n = (int) $n;

	return $n . ( $plus ? '+' : '' ) . ' ' . ( 1 === $n && ! $plus ? $singular : $plural );
}

/* Dati sintetici per card e riga "fatti" */
function starter_imm_get_card_facts( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$facts   = array();

	$mq     = starter_imm_field( 'superficie', $post_id );
	$locali = starter_imm_field( 'locali', $post_id );
	$bagni  = starter_imm_field( 'bagni', $post_id );
	$piano  = starter_imm_field( 'piano', $post_id );

	if ( '' !== (string) $mq ) {
		$facts[] = array( 'area', number_format_i18n( (float) $mq, 0 ) . ' m²' );
	}
	if ( '' !== (string) $locali ) {
		$facts[] = array( 'bed', starter_imm_count_label( $locali, __( 'locale', 'slug-theme' ), __( 'locali', 'slug-theme' ), (bool) starter_imm_field( 'locali_plus', $post_id ) ) );
	}
	if ( '' !== (string) $bagni ) {
		$facts[] = array( 'bath', starter_imm_count_label( $bagni, __( 'bagno', 'slug-theme' ), __( 'bagni', 'slug-theme' ) ) );
	}
	if ( $piano ) {
		$facts[] = array( 'layers', $piano );
	}

	return $facts;
}

/* Immobili in evidenza per una posizione ("hero-home", "in-evidenza-home") */
function starter_imm_get_featured_immobili( $term_slug, $limit, $exclude = array() ) {
	return get_posts(
		array(
			'post_type'      => 'immobile',
			'post_status'    => 'publish',
			'posts_per_page' => $limit,
			'orderby'        => array(
				'menu_order' => 'ASC',
				'date'       => 'DESC',
			),
			'post__not_in'   => $exclude,
			'tax_query'      => array(
				array(
					'taxonomy' => 'evidenza',
					'field'    => 'slug',
					'terms'    => $term_slug,
				),
			),
		)
	);
}

/* Pagina dell'elenco: quella creata dal modulo (Opzioni → Moduli) o una qualunque che contiene il blocco "Elenco immobili". 0 se non c'è. */
function starter_imm_page_id() {
	$id = get_transient( 'starter_imm_page' );

	if ( false === $id ) {
		$found = get_posts(
			array(
				'post_type'      => 'page',
				'post_status'    => 'publish',
				's'              => 'wp:starter-theme/elenco-immobili',
				'search_columns' => array( 'post_content' ),
				'orderby'        => 'ID',
				'order'          => 'ASC',
				'posts_per_page' => 1,
				'fields'         => 'ids',
			)
		);
		$id    = $found ? (int) $found[0] : 0;

		set_transient( 'starter_imm_page', $id, DAY_IN_SECONDS );
	}

	return (int) $id;
}

/* Se si salva o si cancella una pagina, la ricerca della pagina dell'elenco si rifà. */
foreach ( array( 'save_post_page', 'trashed_post', 'deleted_post', 'untrashed_post' ) as $starter_imm_hook ) {
	add_action(
		$starter_imm_hook,
		function () {
			delete_transient( 'starter_imm_page' );
		}
	);
}

/* URL della pagina dell'elenco, o della home se non esiste. */
function starter_imm_get_immobili_page_url() {
	$id = starter_imm_page_id();

	return $id ? get_permalink( $id ) : home_url( '/' );
}

/** Alt SEO per le foto di un immobile */
function starter_imm_immobile_image_alt( $post_id, $attachment_id = 0, $label = '' ) {
	$base = $attachment_id ? trim( (string) get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) : '';
	$base = '' !== $label ? $label : ( '' !== $base ? $base : get_the_title( $post_id ) );

	$tipologia = starter_imm_get_term_name( 'tipologia', $post_id );
	$contratto = mb_strtolower( starter_imm_get_term_name( 'contratto', $post_id ) );
	$zona      = str_replace( ' · ', ', ', starter_imm_get_zone_label( $post_id ) );

	$context = ( '' !== $tipologia ? $tipologia : __( 'Immobile', 'slug-theme' ) )
		. ( '' !== $contratto ? ' ' . sprintf( /* translators: %s: vendita o affitto. */ __( 'in %s', 'slug-theme' ), $contratto ) : '' )
		. ( '' !== $zona ? ' ' . sprintf( /* translators: %s: zona. */ __( 'a %s', 'slug-theme' ), $zona ) : '' );

	return sprintf( '%s – %s | %s', $base, $context, get_bloginfo( 'name' ) );
}

/* Tipi di punto d'interesse "Nelle vicinanze" */
function starter_imm_poi_types() {
	return array(
		'metro'      => array( __( 'Metropolitana', 'slug-theme' ), 'train' ),
		'treno'      => array( __( 'Stazione ferroviaria', 'slug-theme' ), 'train' ),
		'bus'        => array( __( 'Bus e tram', 'slug-theme' ), 'bus' ),
		'parco'      => array( __( 'Parco', 'slug-theme' ), 'tree' ),
		'scuola'     => array( __( 'Scuola', 'slug-theme' ), 'school' ),
		'shopping'   => array( __( 'Shopping', 'slug-theme' ), 'bag' ),
		'supermercato' => array( __( 'Supermercati', 'slug-theme' ), 'cart' ),
		'ristoranti' => array( __( 'Ristoranti', 'slug-theme' ), 'utensils' ),
		'ospedale'   => array( __( 'Ospedale', 'slug-theme' ), 'hospital' ),
		'farmacia'   => array( __( 'Farmacia', 'slug-theme' ), 'pill' ),
		'salute'     => array( __( 'Salute e servizi', 'slug-theme' ), 'cross' ),
	);
}

/* Codice HTML del video o tour virtuale */
function starter_imm_get_tour_embed( $url, $title = '' ) {
	$url = esc_url_raw( trim( (string) $url ) );

	if ( '' === $url ) {
		return '';
	}

	$embed = wp_oembed_get( $url, array( 'width' => 1280 ) );

	if ( $embed ) {
		return $embed;
	}

	return sprintf(
		'<iframe src="%s" title="%s" loading="lazy" allowfullscreen allow="fullscreen; xr-spatial-tracking"></iframe>',
		esc_url( $url ),
		esc_attr( $title ? $title : __( 'Video e tour virtuale', 'slug-theme' ) )
	);
}

/* Tour virtuale 3D (Matterport e simili): accetta il link oppure il codice iframe incollato, e ne ricava l'indirizzo. */
function starter_imm_get_virtual_tour_embed( $value, $title = '' ) {
	$value = trim( (string) $value );

	if ( '' === $value ) {
		return '';
	}

	if ( preg_match( '/<iframe[^>]+src\s*=\s*["\']([^"\']+)["\']/i', $value, $match ) ) {
		$value = html_entity_decode( $match[1] );
	}

	$url = esc_url_raw( $value );

	if ( '' === $url || 0 !== strpos( $url, 'https://' ) ) {
		return '';
	}

	return sprintf(
		'<iframe src="%s" title="%s" loading="lazy" allowfullscreen allow="fullscreen; encrypted-media; xr-spatial-tracking"></iframe>',
		esc_url( $url ),
		esc_attr( $title ? $title : __( 'Tour virtuale 3D', 'slug-theme' ) )
	);
}

/* Nel menu, nella scheda di un immobile resta evidenziata la voce della pagina "Immobili". */
function starter_imm_menu_highlight_immobili( $classes, $item ) {
	if ( is_singular( 'immobile' ) && 'page' === $item->object ) {
		if ( starter_imm_page_id() && (int) $item->object_id === starter_imm_page_id() ) {
			$classes[] = 'current-menu-ancestor';
		}
	}

	return $classes;
}
add_filter( 'nav_menu_css_class', 'starter_imm_menu_highlight_immobili', 10, 2 );

/* Immobile in hero e relativa immagine (campo "immagine_hero" o copertina) */
function starter_imm_get_hero() {
	static $hero = null;

	if ( null !== $hero ) {
		return $hero;
	}

	$posts = starter_imm_get_featured_immobili( 'hero-home', 1 );
	$post  = $posts ? $posts[0] : null;
	$image = 0;

	if ( $post ) {
		$field = starter_imm_field( 'immagine_hero', $post->ID );
		$image = ( is_array( $field ) && ! empty( $field['ID'] ) ) ? (int) $field['ID'] : (int) get_post_thumbnail_id( $post );
	}

	$hero = array( 'post' => $post, 'image_id' => $image );

	return $hero;
}


/* Elenco di scelte multiple in testo: "Centralizzato, a radiatori, a metano" */
function starter_imm_join_choices( $values ) {
	$values = array_values( array_filter( (array) $values ) );

	foreach ( $values as $i => $value ) {
		$values[ $i ] = $i ? mb_strtolower( mb_substr( $value, 0, 1 ) ) . mb_substr( $value, 1 ) : $value;
	}

	return implode( ', ', $values );
}


/* Consulente dell'immobile: il primo scelto, altrimenti il termine "Team". */
function starter_imm_get_consultant( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$terms   = get_the_terms( $post_id, 'consulente' );
	$term    = ( is_array( $terms ) && $terms ) ? reset( $terms ) : get_term_by( 'slug', 'team', 'consulente' );

	if ( ! $term || is_wp_error( $term ) ) {
		return array( 'name' => '' );
	}

	$ref = 'consulente_' . $term->term_id;

	return array(
		'name'  => $term->name,
		'role'  => starter_imm_field_of( 'ruolo', $ref ),
		'photo' => starter_imm_field_of( 'foto', $ref ),
		'phone' => trim( (string) starter_imm_field_of( 'telefono', $ref ) ),
		'mail'  => trim( (string) starter_imm_field_of( 'email', $ref ) ),
	);
}

/* Legge un campo ACF ('' se ACF non c'è) */
function starter_imm_field_of( $name, $ref ) {
	return function_exists( 'get_field' ) ? get_field( $name, $ref ) : '';
}

/* Righe della tabella dati di un immobile: array di array( etichetta, valore ). Usata dalla scheda e dal PDF. */
function starter_imm_immobile_data_rows( $post_id ) {
	$starter_id       = (int) $post_id;
	$starter_price    = starter_imm_field( 'prezzo', $starter_id );
	$starter_mq       = starter_imm_field( 'superficie', $starter_id );
	$starter_locali   = starter_imm_field( 'locali', $starter_id );
	$starter_locali_p = (bool) starter_imm_field( 'locali_plus', $starter_id );
	$starter_camere   = starter_imm_field( 'camere', $starter_id );
	$starter_bagni    = starter_imm_field( 'bagni', $starter_id );
	$starter_piano    = starter_imm_field( 'piano', $starter_id );
	$starter_classe   = starter_imm_field( 'classe_energetica', $starter_id );
	$starter_epi      = starter_imm_field( 'epi', $starter_id );

	$starter_rows = array();
	$starter_add  = function ( $label, $value, $field = '' ) use ( &$starter_rows ) {
		if ( '' !== trim( (string) $value ) ) {
			$starter_rows[] = array( $label, $value, $field );
		}
	};

	$starter_add( __( 'Tipologia', 'slug-theme' ), starter_imm_get_term_name( 'tipologia', $starter_id ) );
	$starter_add( __( 'Contratto', 'slug-theme' ), starter_imm_get_term_name( 'contratto', $starter_id ) );
	$starter_add( __( 'Prezzo', 'slug-theme' ), starter_imm_get_price_label( $starter_id ), 'prezzo' );
	// Solo per le vendite e le aste: in affitto il prezzo è il canone, quindi dividerlo per i m² non ha senso.
	if ( $starter_price && $starter_mq && ! has_term( 'affitto', 'contratto', $starter_id ) ) {
		$starter_add( __( 'Prezzo al m²', 'slug-theme' ), number_format_i18n( round( $starter_price / $starter_mq ), 0 ) . ' €/m²' );
	}
	$starter_add( __( 'Superficie', 'slug-theme' ), '' !== (string) $starter_mq ? number_format_i18n( (float) $starter_mq, 0 ) . ' m²' : '', 'superficie' );
	$starter_add( __( 'Locali', 'slug-theme' ), '' !== (string) $starter_locali ? $starter_locali . ( $starter_locali_p ? '+' : '' ) : '', 'locali' );
	$starter_add( __( 'Camere da letto', 'slug-theme' ), $starter_camere, 'camere' );
	$starter_add( __( 'Bagni', 'slug-theme' ), $starter_bagni, 'bagni' );
	$starter_add( __( 'Piano', 'slug-theme' ), $starter_piano, 'piano' );
	$starter_add( __( 'Classe energetica', 'slug-theme' ), $starter_classe ? $starter_classe . ( $starter_epi ? ' · ' . $starter_epi : '' ) : '' );

	// "Altri dati": voci da elenco (Ascensore, Balcone…) o scritte a mano.
	foreach ( starter_imm_immobile_extra_items( $starter_id ) as $starter_item ) {
		$starter_add( $starter_item['label'], $starter_item['value'], $starter_item['field'] );
	}

	return $starter_rows;
}

/* Riquadri principali di un immobile: array di array( icona, valore, nota ). Usata dalla scheda e dal PDF. */
function starter_imm_immobile_facts( $post_id ) {
	$starter_id       = (int) $post_id;
	$starter_price    = starter_imm_field( 'prezzo', $starter_id );
	$starter_mq       = starter_imm_field( 'superficie', $starter_id );
	$starter_locali   = starter_imm_field( 'locali', $starter_id );
	$starter_locali_p = (bool) starter_imm_field( 'locali_plus', $starter_id );
	$starter_camere   = starter_imm_field( 'camere', $starter_id );
	$starter_bagni    = starter_imm_field( 'bagni', $starter_id );
	$starter_piano    = starter_imm_field( 'piano', $starter_id );
	$starter_classe   = starter_imm_field( 'classe_energetica', $starter_id );
	$starter_epi      = starter_imm_field( 'epi', $starter_id );

	$starter_facts = array();

	if ( '' !== (string) $starter_mq ) {
		$starter_facts[] = array( 'area', number_format_i18n( (float) $starter_mq, 0 ) . ' m²', __( 'Superficie', 'slug-theme' ), 'superficie' );
	}
	if ( '' !== (string) $starter_locali ) {
		$starter_facts[] = array( 'home', starter_imm_count_label( $starter_locali, __( 'locale', 'slug-theme' ), __( 'locali', 'slug-theme' ), $starter_locali_p ), starter_imm_field( 'nota_locali', $starter_id ), 'locali' );
	}
	if ( '' !== (string) $starter_camere ) {
		$starter_facts[] = array( 'bed', starter_imm_count_label( $starter_camere, __( 'camera', 'slug-theme' ), __( 'camere', 'slug-theme' ) ), __( 'Da letto', 'slug-theme' ), 'camere' );
	}
	if ( '' !== (string) $starter_bagni ) {
		$starter_facts[] = array( 'bath', starter_imm_count_label( $starter_bagni, __( 'bagno', 'slug-theme' ), __( 'bagni', 'slug-theme' ) ), starter_imm_field( 'nota_bagni', $starter_id ), 'bagni' );
	}
	if ( $starter_piano ) {
		$starter_facts[] = array( 'layers', $starter_piano, starter_imm_field( 'nota_piano', $starter_id ), 'piano' );
	}
	if ( $starter_classe ) {
		/* translators: %s: lettera della classe energetica. */
		$starter_facts[] = array( 'zap', sprintf( __( 'Classe %s', 'slug-theme' ), $starter_classe ), $starter_epi, 'classe_energetica' );
	}

	return $starter_facts;
}

/* Voci di "Altri dati" di un immobile: array di array( label, value, highlight ). Per le voci da elenco con valore a scelta (Ascensore, Arredamento, Balcone…) vale la scelta. */
function starter_imm_immobile_extra_items( $post_id ) {
	$rows  = starter_imm_field( 'altri_dati', (int) $post_id );
	$items = array();

	if ( ! is_array( $rows ) ) {
		return $items;
	}

	$value_keys = array(
		'Cucina'         => 'altri_dati_valore_cucina',
		'Riscaldamento'  => 'altri_dati_valore_riscaldamento',
		'Climatizzazione' => 'altri_dati_valore_clima',
		'Ascensore'     => 'altri_dati_valore_ascensore',
		'Arredamento'   => 'altri_dati_valore_arredamento',
		'Terrazzo'      => 'altri_dati_valore_sino',
		'Balcone'       => 'altri_dati_valore_sino',
		'Cantina'       => 'altri_dati_valore_sino',
		'Piscina'       => 'altri_dati_valore_sino',
		'Giardino'      => 'altri_dati_valore_sino',
		'Fibra ottica'  => 'altri_dati_valore_sino',
		'Porta blindata' => 'altri_dati_valore_sino',
	);

	foreach ( $rows as $row_idx => $row ) {
		$from_list = ! empty( $row['altri_dati_elenco'] );
		$label     = trim( (string) ( $from_list ? $row['altri_dati_scelta'] : $row['altri_dati_etichetta'] ) );
		$value     = isset( $row['altri_dati_valore'] ) ? $row['altri_dati_valore'] : '';
		$name      = 'altri_dati_valore';

		if ( $from_list && isset( $value_keys[ $label ] ) && ! empty( $row[ $value_keys[ $label ] ] ) ) {
			$value = $row[ $value_keys[ $label ] ];
			$name  = $value_keys[ $label ];
		}

		$value = trim( is_array( $value ) ? starter_imm_join_choices( $value ) : (string) $value );

		if ( '' === $label || '' === $value ) {
			continue;
		}

		$items[] = array(
			'label'     => $label,
			'value'     => $value,
			'highlight' => ! empty( $row['altri_dati_risalto'] ),
			'field'     => 'altri_dati_' . (int) $row_idx . '_' . $name, // nome del campo ACF (serve alla modifica dal frontend)
		);
	}

	return $items;
}

/* Etichette in risalto sopra l'elenco: le voci di "Altri dati" con "In risalto" attivo (non quelle con valore "No"). */
function starter_imm_immobile_highlights( $post_id ) {
	$chips = array();

	foreach ( starter_imm_immobile_extra_items( $post_id ) as $item ) {
		if ( ! $item['highlight'] || 0 === strcasecmp( $item['value'], __( 'No', 'slug-theme' ) ) ) {
			continue;
		}

		if ( 0 === strcasecmp( $item['value'], __( 'Sì', 'slug-theme' ) ) ) {
			$chips[] = $item['label'];
		} elseif ( false !== stripos( $item['value'], $item['label'] ) ) {
			$chips[] = $item['value'];
		} else {
			$chips[] = $item['label'] . ': ' . $item['value'];
		}
	}

	return $chips;
}

/* Righe predefinite di "Caratteristiche" per un nuovo immobile (Cucina, Riscaldamento, Climatizzazione, Spese condominio). */
function starter_imm_default_feature_rows( $value, $post_id, $field ) {
	if ( ! empty( $value ) || 'auto-draft' !== get_post_status( $post_id ) ) {
		return $value;
	}

	$rows = array();

	foreach ( array( 'Cucina', 'Riscaldamento', 'Climatizzazione', 'Spese condominio' ) as $label ) {
		$rows[] = array(
			'field_imm_altri_dati_elenco' => 1,
			'field_imm_altri_dati_scelta' => $label,
		);
	}

	return $rows;
}
add_filter( 'acf/load_value/key=field_imm_altri_dati', 'starter_imm_default_feature_rows', 10, 3 );
