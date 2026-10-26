<?php
/** Filtri dell'elenco immobili (blocco "Elenco immobili"). */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Prefisso dei parametri URL e numero di immobili per pagina. */
const STARTER_IMM_FILTER_PREFIX = 'f_';
const STARTER_IMM_PER_PAGE      = 9;

/* Configurazione dei filtri, nell'ordine in cui compaiono nel form. */
function starter_imm_immobili_filters() {
	return array(
		'ricerca'         => array(
			'type'        => 'search',
			'label'       => __( 'Cerca', 'slug-theme' ),
			'placeholder' => __( 'Zona, via, caratteristica…', 'slug-theme' ),
		),
		'tipologia'       => array(
			'type'     => 'tax_select',
			'label'    => __( 'Tipologia', 'slug-theme' ),
			'taxonomy' => 'tipologia',
			'all'      => __( 'Tutte', 'slug-theme' ),
		),
		'contratto'       => array(
			'type'     => 'tax_select',
			'label'    => __( 'Contratto', 'slug-theme' ),
			'taxonomy' => 'contratto',
			'all'      => __( 'Tutti', 'slug-theme' ),
		),
		'zona'            => array(
			'type'     => 'tax_select',
			'label'    => __( 'Zona', 'slug-theme' ),
			'taxonomy' => 'zona',
			'all'      => __( 'Tutte', 'slug-theme' ),
		),
		'prezzo'          => array(
			'type'  => 'range',
			'label' => __( 'Prezzo (€)', 'slug-theme' ),
			'meta'  => 'prezzo',
			'step'  => 10000,
		),
		'superficie'      => array(
			'type'  => 'range',
			'label' => __( 'Superficie (mq)', 'slug-theme' ),
			'meta'  => 'superficie',
			'step'  => 10,
		),
		'locali'          => array(
			'type'    => 'min',
			'label'   => __( 'Locali', 'slug-theme' ),
			'meta'    => 'locali',
			'options' => array( 1, 2, 3, 4, 5 ),
			'any'     => __( 'Qualsiasi', 'slug-theme' ),
		),
		'bagni'           => array(
			'type'    => 'min',
			'label'   => __( 'Bagni', 'slug-theme' ),
			'meta'    => 'bagni',
			'options' => array( 1, 2, 3 ),
			'any'     => __( 'Qualsiasi', 'slug-theme' ),
		),
	);
}

/* Nome del parametro URL */
function starter_imm_filter_param( $key, $suffix = '' ) {
	return STARTER_IMM_FILTER_PREFIX . $key . ( $suffix ? '_' . $suffix : '' );
}

/* Valore numerico dal parametro, o '' se vuoto o non valido. */
function starter_imm_filter_number( $raw ) {
	$raw = is_string( $raw ) ? trim( $raw ) : '';

	return ( '' !== $raw && is_numeric( $raw ) && (float) $raw >= 0 ) ? $raw + 0 : '';
}

/** Legge e sanitizza i valori dei filtri dall'URL ($_GET). */
function starter_imm_filters_get_values() {
	$values = array();

	foreach ( starter_imm_immobili_filters() as $key => $filter ) {
		switch ( $filter['type'] ) {
			case 'search':
				$name           = starter_imm_filter_param( $key );
				$values[ $key ] = isset( $_GET[ $name ] ) ? mb_substr( trim( sanitize_text_field( wp_unslash( $_GET[ $name ] ) ) ), 0, 80 ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				break;

			case 'tax_select':
				$raw    = isset( $_GET[ starter_imm_filter_param( $key ) ] ) ? sanitize_title( wp_unslash( $_GET[ starter_imm_filter_param( $key ) ] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$values[ $key ] = ( '' !== $raw && term_exists( $raw, $filter['taxonomy'] ) ) ? $raw : '';
				break;

			case 'tax_checkbox':
				$raw  = isset( $_GET[ starter_imm_filter_param( $key ) ] ) ? (array) wp_unslash( $_GET[ starter_imm_filter_param( $key ) ] ) : array(); // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitizzato subito sotto.
				$slugs = array_filter( array_map( 'sanitize_title', array_filter( $raw, 'is_string' ) ) );
				$values[ $key ] = array_values( array_filter( $slugs, function ( $slug ) use ( $filter ) {
					return term_exists( $slug, $filter['taxonomy'] );
				} ) );
				break;

			case 'range':
				foreach ( array( 'min', 'max' ) as $edge ) {
					$name = starter_imm_filter_param( $key, $edge );
					$values[ $key ][ $edge ] = isset( $_GET[ $name ] ) ? starter_imm_filter_number( sanitize_text_field( wp_unslash( $_GET[ $name ] ) ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				}
				break;

			case 'min':
				$name = starter_imm_filter_param( $key );
				$values[ $key ] = isset( $_GET[ $name ] ) ? starter_imm_filter_number( sanitize_text_field( wp_unslash( $_GET[ $name ] ) ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				break;
		}
	}

	return $values;
}

/* True se il filtro ha un valore. */
function starter_imm_filter_is_active( $filter, $value ) {
	switch ( $filter['type'] ) {
		case 'range':
			return '' !== $value['min'] || '' !== $value['max'];
		case 'tax_checkbox':
			return ! empty( $value );
		case 'search':
			return '' !== trim( (string) $value );
		default:
			return '' !== $value;
	}
}

/* Numero di filtri attivi. */
function starter_imm_filters_active_count( $values ) {
	$count = 0;

	foreach ( starter_imm_immobili_filters() as $key => $filter ) {
		if ( starter_imm_filter_is_active( $filter, $values[ $key ] ) ) {
			++$count;
		}
	}

	return $count;
}

/** Argomenti WP_Query per i valori dei filtri. */
function starter_imm_filters_query_args( $values, $paged = 1 ) {
	$tax_query  = array( 'relation' => 'AND' );
	$meta_query = array( 'relation' => 'AND' );
	$search     = '';

	foreach ( starter_imm_immobili_filters() as $key => $filter ) {
		$value = $values[ $key ];

		if ( ! starter_imm_filter_is_active( $filter, $value ) ) {
			continue;
		}

		switch ( $filter['type'] ) {
			case 'search':
				$search = $value; // Cerca in titolo, campi di testo e nomi delle categorie dell'immobile (vedi starter_imm_search_where).
				break;

			case 'tax_select':
				$tax_query[] = array(
					'taxonomy' => $filter['taxonomy'],
					'field'    => 'slug',
					'terms'    => $value,
				);
				break;

			case 'tax_checkbox':
				$tax_query[] = array(
					'taxonomy' => $filter['taxonomy'],
					'field'    => 'slug',
					'terms'    => $value,
					'operator' => 'AND', // L'immobile deve avere tutte le caratteristiche scelte.
				);
				break;

			case 'range':
				$meta_query[] = array(
					'key'     => $filter['meta'],
					'value'   => '',
					'compare' => '!=', // Esclude gli immobili con campo vuoto.
				);

				if ( '' !== $value['min'] ) {
					$meta_query[] = array(
						'key'     => $filter['meta'],
						'value'   => $value['min'],
						'type'    => 'NUMERIC',
						'compare' => '>=',
					);
				}

				if ( '' !== $value['max'] ) {
					$meta_query[] = array(
						'key'     => $filter['meta'],
						'value'   => $value['max'],
						'type'    => 'NUMERIC',
						'compare' => '<=',
					);
				}
				break;

			case 'min':
				$meta_query[] = array(
					'key'     => $filter['meta'],
					'value'   => '',
					'compare' => '!=',
				);
				$meta_query[] = array(
					'key'     => $filter['meta'],
					'value'   => $value,
					'type'    => 'NUMERIC',
					'compare' => '>=',
				);
				break;
		}
	}

	$args = array(
		'post_type'      => 'immobile',
		'post_status'    => 'publish',
		'posts_per_page' => STARTER_IMM_PER_PAGE,
		'paged'          => max( 1, (int) $paged ),
		'orderby'        => array(
			'menu_order' => 'ASC',
			'date'       => 'DESC',
		),
	);

	if ( '' !== $search ) {
		$args['starter_imm_q'] = $search;
	}

	if ( count( $tax_query ) > 1 ) {
		$args['tax_query'] = $tax_query; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
	}

	if ( count( $meta_query ) > 1 ) {
		$args['meta_query'] = $meta_query; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
	}

	return $args;
}

/**
 * Ricerca per parola chiave solo sugli immobili: ogni parola deve comparire nel titolo, in un campo di testo della scheda
 * (indirizzo, descrizione, zona, etichette, altri dati, punti di interesse…) o nel nome di una categoria (tipologia, contratto, zona).
 */
add_filter(
	'posts_where',
	static function ( $where, $query ) {
		$term = $query->get( 'starter_imm_q' );

		if ( ! is_string( $term ) || '' === trim( $term ) ) {
			return $where;
		}

		global $wpdb;

		$fields = '^(titolo_pagina|apertura|indirizzo|titolo_descrizione|descrizione|titolo_zona|testo_zona|nota_locali|nota_bagni|nota_piano|piano|classe_energetica|etichette_[0-9]+_etichette_(scelta|testo)|altri_dati_[0-9]+_altri_dati_(scelta|etichetta|valore)|punti_di_forza_[0-9]+_punti_di_forza_(titolo|testo)|blocchi_piano_[0-9]+_blocchi_piano_(occhiello|titolo|testo)|punti_interesse_[0-9]+_punti_interesse_nome)$';
		$words  = array_slice( array_filter( preg_split( '/\s+/u', trim( $term ) ) ), 0, 5 );

		foreach ( $words as $word ) {
			$like   = '%' . $wpdb->esc_like( $word ) . '%';
			$where .= $wpdb->prepare(
				" AND ( {$wpdb->posts}.post_title LIKE %s
					OR EXISTS ( SELECT 1 FROM {$wpdb->postmeta} sm WHERE sm.post_id = {$wpdb->posts}.ID AND sm.meta_key REGEXP %s AND sm.meta_value LIKE %s )
					OR EXISTS ( SELECT 1 FROM {$wpdb->term_relationships} sr
						INNER JOIN {$wpdb->term_taxonomy} st ON st.term_taxonomy_id = sr.term_taxonomy_id
						INNER JOIN {$wpdb->terms} sx ON sx.term_id = st.term_id
						WHERE sr.object_id = {$wpdb->posts}.ID AND st.taxonomy IN ( 'tipologia', 'contratto', 'zona' ) AND sx.name LIKE %s ) )",
				$like,
				$fields,
				$like,
				$like
			);
		}

		return $where;
	},
	10,
	2
);

/* Pagina corrente dell'elenco (parametro f_pagina). */
function starter_imm_filters_current_page() {
	return isset( $_GET[ starter_imm_filter_param( 'pagina' ) ] ) ? max( 1, absint( wp_unslash( $_GET[ starter_imm_filter_param( 'pagina' ) ] ) ) ) : 1; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
}

/* Opzioni di una tassonomia per una select */
function starter_imm_filter_term_options( $taxonomy ) {
	$terms = get_terms(
		array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => true,
			'orderby'    => 'name',
		)
	);

	if ( is_wp_error( $terms ) || ! $terms ) {
		return array();
	}

	$by_parent = array();

	foreach ( $terms as $term ) {
		$by_parent[ (int) $term->parent ][] = $term;
	}

	$options = array();
	$walk    = function ( $parent, $depth ) use ( &$walk, &$options, $by_parent ) {
		if ( empty( $by_parent[ $parent ] ) ) {
			return;
		}

		foreach ( $by_parent[ $parent ] as $term ) {
			$options[ $term->slug ] = str_repeat( '— ', $depth ) . $term->name;
			$walk( $term->term_id, $depth + 1 );
		}
	};

	$walk( 0, 0 );

	// Termini il cui genitore non ha immobili (nascosto da hide_empty)
	foreach ( $terms as $term ) {
		if ( ! isset( $options[ $term->slug ] ) ) {
			$options[ $term->slug ] = $term->name;
		}
	}

	return $options;
}

/* Stampa il form dei filtri (GET, ricarica la pagina, nessun AJAX). */
function starter_imm_filters_render_form( $values, $action_url, $found = null ) {
	$active = starter_imm_filters_active_count( $values );
	?>
	<div class="filters-wrap">
	<div class="filters__overlay" data-filters-close></div>
	<form class="filters" id="filters-form" method="get" action="<?php echo esc_url( $action_url ); ?>" aria-label="<?php esc_attr_e( 'Filtra gli immobili', 'slug-theme' ); ?>">
		<div class="filters__head">
			<p class="filters__title title-h4"><?php esc_html_e( 'Filtra gli immobili', 'slug-theme' ); ?></p>
			<button type="button" class="icon-btn" data-filters-close aria-label="<?php esc_attr_e( 'Chiudi i filtri', 'slug-theme' ); ?>"><?php echo starter_icon( 'x', 22, 1.5 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
		</div>
		<div class="filters__grid">
			<?php foreach ( starter_imm_immobili_filters() as $key => $filter ) : ?>
				<?php
				$value = $values[ $key ];
				$id    = 'filter-' . $key;
				$name  = starter_imm_filter_param( $key );

				switch ( $filter['type'] ) :
					case 'search':
						?>
						<div class="filters__field filters__field--search">
							<label class="filters__label" for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $filter['label'] ); ?></label>
							<input type="search" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" maxlength="80" placeholder="<?php echo esc_attr( $filter['placeholder'] ); ?>" autocomplete="off">
						</div>
						<?php
						break;

					case 'tax_select':
						$options = starter_imm_filter_term_options( $filter['taxonomy'] );
						?>
						<div class="filters__field">
							<label class="filters__label" for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $filter['label'] ); ?></label>
							<select id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>">
								<option value=""><?php echo esc_html( $filter['all'] ); ?></option>
								<?php foreach ( $options as $slug => $label ) : ?>
									<option value="<?php echo esc_attr( $slug ); ?>" <?php selected( $value, $slug ); ?>><?php echo esc_html( $label ); ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<?php
						break;

					case 'range':
						?>
						<fieldset class="filters__field">
							<legend><?php echo esc_html( $filter['label'] ); ?></legend>
							<div class="filters__range">
								<label class="screen-reader-text" for="<?php echo esc_attr( $id . '-min' ); ?>"><?php echo esc_html( sprintf( /* translators: %s: nome del filtro. */ __( '%s: minimo', 'slug-theme' ), $filter['label'] ) ); ?></label>
								<input type="number" inputmode="numeric" min="0" step="<?php echo esc_attr( $filter['step'] ); ?>" id="<?php echo esc_attr( $id . '-min' ); ?>" name="<?php echo esc_attr( starter_imm_filter_param( $key, 'min' ) ); ?>" value="<?php echo esc_attr( $value['min'] ); ?>" placeholder="<?php esc_attr_e( 'Da', 'slug-theme' ); ?>">
								<label class="screen-reader-text" for="<?php echo esc_attr( $id . '-max' ); ?>"><?php echo esc_html( sprintf( /* translators: %s: nome del filtro. */ __( '%s: massimo', 'slug-theme' ), $filter['label'] ) ); ?></label>
								<input type="number" inputmode="numeric" min="0" step="<?php echo esc_attr( $filter['step'] ); ?>" id="<?php echo esc_attr( $id . '-max' ); ?>" name="<?php echo esc_attr( starter_imm_filter_param( $key, 'max' ) ); ?>" value="<?php echo esc_attr( $value['max'] ); ?>" placeholder="<?php esc_attr_e( 'A', 'slug-theme' ); ?>">
							</div>
						</fieldset>
						<?php
						break;

					case 'min':
						?>
						<div class="filters__field">
							<label class="filters__label" for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $filter['label'] ); ?></label>
							<select id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>">
								<option value=""><?php echo esc_html( $filter['any'] ); ?></option>
								<?php foreach ( $filter['options'] as $option ) : ?>
									<option value="<?php echo esc_attr( $option ); ?>" <?php selected( (string) $value, (string) $option ); ?>><?php echo esc_html( $option . ( end( $filter['options'] ) === $option ? '+' : '' ) ); ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<?php
						break;

					case 'tax_checkbox':
						$options = starter_imm_filter_term_options( $filter['taxonomy'] );

						if ( ! $options ) {
							break;
						}

						$chosen_names = array();

						foreach ( $value as $chosen_slug ) {
							if ( isset( $options[ $chosen_slug ] ) ) {
								$chosen_names[] = $options[ $chosen_slug ];
							}
						}

						$summary = ! $chosen_names ? $filter['any'] : ( count( $chosen_names ) > 2 ? sprintf( /* translators: %d: numero di caratteristiche scelte. */ __( '%d selezionate', 'slug-theme' ), count( $chosen_names ) ) : implode( ', ', $chosen_names ) );
						?>
						<div class="filters__field">
							<span class="filters__label" id="<?php echo esc_attr( $id . '-label' ); ?>"><?php echo esc_html( $filter['label'] ); ?></span>
							<details class="multiselect" data-any="<?php echo esc_attr( $filter['any'] ); ?>" data-many="<?php esc_attr_e( '%d selezionate', 'slug-theme' ); ?>">
								<summary class="multiselect__summary" aria-labelledby="<?php echo esc_attr( $id . '-label' ); ?>">
									<span class="multiselect__value"><?php echo esc_html( $summary ); ?></span>
								</summary>
								<div class="multiselect__panel" role="group" aria-labelledby="<?php echo esc_attr( $id . '-label' ); ?>">
									<?php foreach ( $options as $slug => $label ) : ?>
										<label class="multiselect__option">
											<input type="checkbox" name="<?php echo esc_attr( $name ); ?>[]" value="<?php echo esc_attr( $slug ); ?>" <?php checked( in_array( $slug, $value, true ) ); ?>>
											<span><?php echo esc_html( $label ); ?></span>
										</label>
									<?php endforeach; ?>
								</div>
							</details>
						</div>
						<?php
						break;
				endswitch;
				?>
			<?php endforeach; ?>

			<div class="filters__actions">
				<button type="submit" class="button button--primary"><?php echo starter_flip_text( __( 'Cerca immobili', 'slug-theme' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
				<a class="button button--outline filters__reset<?php echo $active ? '' : ' is-disabled'; ?>" href="<?php echo esc_url( $action_url ); ?>" <?php echo $active ? '' : 'aria-disabled="true" tabindex="-1"'; ?>><?php echo starter_flip_text( __( 'Azzera i filtri', 'slug-theme' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
			</div>
		</div>
	</form>
	<div class="filters-toolbar">
		<button type="button" class="button button--primary filters-toggle" aria-expanded="false" aria-controls="filters-form">
			<?php echo starter_icon( 'sliders', 18, 1.5 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php echo starter_flip_text( __( 'Filtri', 'slug-theme' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php if ( $active ) : ?>
				<span class="filters-toggle__count"><?php echo (int) $active; ?></span>
			<?php endif; ?>
		</button>
		<?php if ( null !== $found ) : ?>
			<p class="filters-toolbar__count" role="status">
				<?php
				echo esc_html(
					sprintf(
						/* translators: %d: numero di immobili trovati. */
						_n( '%d immobile trovato', '%d immobili trovati', (int) $found, 'slug-theme' ),
						(int) $found
					)
				);
				?>
			</p>
		<?php endif; ?>
	</div>
	</div>
	<?php
}
