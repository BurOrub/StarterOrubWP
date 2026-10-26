<?php
/**
 * Filtri del negozio, sullo schema dei filtri di Di Guida Real Estate: form GET che ricarica la pagina (niente
 * AJAX, URL condivisibili), parametri con prefisso f_, su tablet/mobile un pannello che entra da sinistra
 * aperto dal pulsante "Filtri".
 *
 * Si attivano da Opzioni → Shop → Filtri (solo con WooCommerce). Di base compaiono nella pagina Shop; con
 * l'opzione dedicata anche nelle pagine delle categorie (che hanno sempre l'ordinamento). I filtri nascono dai
 * dati dei prodotti, niente di inventato, e ognuno si accende o spegne dalle opzioni:
 * - Cerca: ricerca sui soli prodotti, con suggerimenti mentre scrivi (Store API di Woo);
 * - Categoria: categorie prodotto (nelle pagine di una categoria, le sue sottocategorie);
 * - Prezzo: da / a, sul prezzo del prodotto;
 * - Attributi: un filtro per ogni attributo globale di WooCommerce (Prodotti → Attributi) che ha almeno un
 *   prodotto assegnato (es. Materiale, Colore). Senza prodotti non compare.
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const STARTER_SHOP_FILTER_PREFIX = 'f_';

/* Nome del parametro URL di un filtro. */
function starter_shop_filter_param( $key, $suffix = '' ) {
	return STARTER_SHOP_FILTER_PREFIX . $key . ( $suffix ? '_' . $suffix : '' );
}

/* Pagina del negozio in cui ci sono i filtri: lo Shop, e (se scelto nelle opzioni) le pagine delle categorie. */
function starter_shop_filters_enabled() {
	if ( ! starter_has_woocommerce() || ! get_theme_mod( 'starter_shop_filters', false ) ) {
		return false;
	}

	if ( is_shop() ) {
		return true;
	}

	return is_product_category() && get_theme_mod( 'starter_shop_filters_in_categories', false );
}

/* Configurazione dei filtri attivi in questa pagina, nell'ordine del form. */
function starter_shop_filters() {
	static $filters = null;

	if ( null !== $filters ) {
		return $filters;
	}

	$filters = array();

	if ( ! starter_shop_filters_enabled() ) {
		return $filters;
	}

	if ( get_theme_mod( 'starter_shop_filter_search', true ) ) {
		$filters['cerca'] = array(
			'type'  => 'search',
			'label' => __( 'Cerca', 'slug-theme' ),
		);
	}

	// Categoria: nella pagina di una categoria solo le sue sottocategorie.
	$parent = is_product_category() ? (int) get_queried_object_id() : 0;
	$terms  = get_theme_mod( 'starter_shop_filter_category', true ) ? starter_shop_filter_term_options( 'product_cat', $parent ) : array();

	if ( $terms ) {
		$filters['categoria'] = array(
			'type'     => 'tax_select',
			'label'    => $parent ? __( 'Sottocategoria', 'slug-theme' ) : __( 'Categoria', 'slug-theme' ),
			'taxonomy' => 'product_cat',
			'options'  => $terms,
			'all'      => __( 'Tutte', 'slug-theme' ),
		);
	}

	if ( get_theme_mod( 'starter_shop_filter_price', true ) ) {
		$filters['prezzo'] = array(
			'type'  => 'range',
			/* translators: %s: simbolo della valuta. */
			'label' => sprintf( __( 'Prezzo (%s)', 'slug-theme' ), html_entity_decode( get_woocommerce_currency_symbol(), ENT_QUOTES, 'UTF-8' ) ),
			'step'  => 5,
		);
	}

	// Un filtro per ogni attributo globale con almeno un prodotto.
	if ( get_theme_mod( 'starter_shop_filter_attributes', true ) && function_exists( 'wc_get_attribute_taxonomies' ) ) {
		foreach ( wc_get_attribute_taxonomies() as $attribute ) {
			$taxonomy = wc_attribute_taxonomy_name( $attribute->attribute_name );

			if ( ! taxonomy_exists( $taxonomy ) ) {
				continue;
			}

			$options = starter_shop_filter_term_options( $taxonomy );

			if ( ! $options ) {
				continue;
			}

			$filters[ $attribute->attribute_name ] = array(
				'type'     => 'tax_checkbox',
				'label'    => $attribute->attribute_label,
				'taxonomy' => $taxonomy,
				'options'  => $options,
				'any'      => __( 'Qualsiasi', 'slug-theme' ),
			);
		}
	}

	/* Filtro per cambiare o riordinare i filtri nel progetto. */
	$filters = apply_filters( 'starter_shop_filters', $filters );

	return $filters;
}

/* Opzioni slug => nome di una tassonomia (solo termini con prodotti), con rientro per i livelli. */
function starter_shop_filter_term_options( $taxonomy, $parent = 0 ) {
	$args = array(
		'taxonomy'   => $taxonomy,
		'hide_empty' => true,
	);

	if ( 'product_cat' === $taxonomy ) {
		$args['exclude'] = array( (int) get_option( 'default_product_cat' ) );
		$args['orderby'] = 'menu_order';
	} else {
		$args['orderby'] = 'name';
	}

	if ( $parent ) {
		$args['child_of'] = $parent;
	}

	$terms = get_terms( $args );

	if ( is_wp_error( $terms ) || ! $terms ) {
		return array();
	}

	$by_parent = array();

	foreach ( $terms as $term ) {
		$by_parent[ (int) $term->parent ][] = $term;
	}

	$options = array();
	$walk    = function ( $id, $depth ) use ( &$walk, &$options, $by_parent ) {
		foreach ( isset( $by_parent[ $id ] ) ? $by_parent[ $id ] : array() as $term ) {
			$options[ $term->slug ] = str_repeat( '— ', $depth ) . $term->name;
			$walk( (int) $term->term_id, $depth + 1 );
		}
	};

	$walk( $parent, 0 );

	// Termini il cui genitore non ha prodotti (escluso da hide_empty).
	foreach ( $terms as $term ) {
		if ( ! isset( $options[ $term->slug ] ) ) {
			$options[ $term->slug ] = $term->name;
		}
	}

	return $options;
}

/* Numero da un parametro, '' se vuoto o non valido. */
function starter_shop_filter_number( $raw ) {
	$raw = is_string( $raw ) ? trim( str_replace( ',', '.', $raw ) ) : '';

	return ( '' !== $raw && is_numeric( $raw ) && (float) $raw >= 0 ) ? $raw + 0 : '';
}

/** Valori dei filtri letti dall'URL e sanitizzati (solo opzioni esistenti). */
function starter_shop_filters_values() {
	static $values = null;

	if ( null !== $values ) {
		return $values;
	}

	$values = array();

	// phpcs:disable WordPress.Security.NonceVerification.Recommended -- filtri in GET, sola lettura.
	foreach ( starter_shop_filters() as $key => $filter ) {
		$name = starter_shop_filter_param( $key );

		switch ( $filter['type'] ) {
			case 'tax_select':
				$raw            = isset( $_GET[ $name ] ) ? sanitize_title( wp_unslash( $_GET[ $name ] ) ) : '';
				$values[ $key ] = isset( $filter['options'][ $raw ] ) ? $raw : '';
				break;

			case 'tax_checkbox':
				$raw            = isset( $_GET[ $name ] ) ? array_map( 'sanitize_title', array_filter( (array) wp_unslash( $_GET[ $name ] ), 'is_string' ) ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				$values[ $key ] = array_values( array_intersect( $raw, array_keys( $filter['options'] ) ) );
				break;

			case 'search':
				$values[ $key ] = isset( $_GET[ $name ] ) ? sanitize_text_field( wp_unslash( $_GET[ $name ] ) ) : '';
				break;

			case 'range':
				foreach ( array( 'min', 'max' ) as $edge ) {
					$param                   = starter_shop_filter_param( $key, $edge );
					$values[ $key ][ $edge ] = isset( $_GET[ $param ] ) ? starter_shop_filter_number( sanitize_text_field( wp_unslash( $_GET[ $param ] ) ) ) : '';
				}
				break;
		}
	}
	// phpcs:enable

	return $values;
}

/* True se il filtro ha un valore. */
function starter_shop_filter_is_active( $filter, $value ) {
	if ( 'range' === $filter['type'] ) {
		return '' !== $value['min'] || '' !== $value['max'];
	}

	return ! empty( $value );
}

/* Numero di filtri attivi (per il badge del pulsante "Filtri"). */
function starter_shop_filters_active_count() {
	$values = starter_shop_filters_values();
	$count  = 0;

	foreach ( starter_shop_filters() as $key => $filter ) {
		if ( starter_shop_filter_is_active( $filter, $values[ $key ] ) ) {
			++$count;
		}
	}

	return $count;
}

/* Filtri di tassonomia applicati alla query dei prodotti di WooCommerce. */
function starter_shop_filters_tax_query( $tax_query ) {
	if ( ! starter_shop_filters_enabled() ) {
		return $tax_query;
	}

	$values = starter_shop_filters_values();

	foreach ( starter_shop_filters() as $key => $filter ) {
		if ( ! in_array( $filter['type'], array( 'tax_select', 'tax_checkbox' ), true ) || ! starter_shop_filter_is_active( $filter, $values[ $key ] ) ) {
			continue;
		}

		$tax_query[] = array(
			'taxonomy'         => $filter['taxonomy'],
			'field'            => 'slug',
			'terms'            => (array) $values[ $key ],
			'operator'         => 'IN', // Attributi: basta uno dei valori scelti (es. Rosso o Blu).
			'include_children' => true,
		);
	}

	return $tax_query;
}
add_filter( 'woocommerce_product_query_tax_query', 'starter_shop_filters_tax_query' );

/* Filtro prezzo applicato alla query dei prodotti (prezzo attuale, anche in offerta). */
function starter_shop_filters_meta_query( $meta_query ) {
	if ( ! starter_shop_filters_enabled() || ! isset( starter_shop_filters()['prezzo'] ) ) {
		return $meta_query;
	}

	$price = starter_shop_filters_values()['prezzo'];

	if ( '' !== $price['min'] ) {
		$meta_query[] = array(
			'key'     => '_price',
			'value'   => $price['min'],
			'type'    => 'DECIMAL(10,2)',
			'compare' => '>=',
		);
	}

	if ( '' !== $price['max'] ) {
		$meta_query[] = array(
			'key'     => '_price',
			'value'   => $price['max'],
			'type'    => 'DECIMAL(10,2)',
			'compare' => '<=',
		);
	}

	return $meta_query;
}
add_filter( 'woocommerce_product_query_meta_query', 'starter_shop_filters_meta_query' );

/* Ricerca testuale sui soli prodotti (campo "Cerca" dei filtri). */
function starter_shop_filters_search( $query ) {
	if ( ! starter_shop_filters_enabled() || ! isset( starter_shop_filters()['cerca'] ) ) {
		return;
	}

	$term = starter_shop_filters_values()['cerca'];

	if ( '' !== $term ) {
		$query->set( 's', $term );
	}
}
add_action( 'woocommerce_product_query', 'starter_shop_filters_search' );

/* URL della pagina senza filtri (per "Azzera i filtri"), mantenendo l'ordinamento scelto. */
function starter_shop_filters_reset_url() {
	$url = is_product_taxonomy() ? get_term_link( get_queried_object() ) : wc_get_page_permalink( 'shop' );

	if ( ! empty( $_GET['orderby'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$url = add_query_arg( 'orderby', sanitize_key( wp_unslash( $_GET['orderby'] ) ), $url ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}

	return $url;
}

/** Stampa il form dei filtri (desktop: riquadro sopra la griglia; tablet/mobile: pannello a scomparsa). */
function starter_shop_filters_render() {
	$filters = starter_shop_filters();
	$values  = starter_shop_filters_values();
	$action  = is_product_taxonomy() ? get_term_link( get_queried_object() ) : wc_get_page_permalink( 'shop' );

	if ( ! $filters ) {
		return;
	}
	?>
	<div class="filters__overlay" data-filters-close></div>
	<form class="filters" id="filters-form" method="get" action="<?php echo esc_url( $action ); ?>" aria-label="<?php esc_attr_e( 'Filtra i prodotti', 'slug-theme' ); ?>">
		<div class="filters__head">
			<p class="filters__title title-h4"><?php esc_html_e( 'Filtra i prodotti', 'slug-theme' ); ?></p>
			<button type="button" class="icon-btn" data-filters-close aria-label="<?php esc_attr_e( 'Chiudi i filtri', 'slug-theme' ); ?>"><?php echo starter_icon( 'x', 22, 1.5 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
		</div>

		<div class="filters__grid">
			<?php foreach ( $filters as $key => $filter ) : ?>
				<?php
				$value = $values[ $key ];
				$id    = 'filtro-' . $key;
				$name  = starter_shop_filter_param( $key );

				switch ( $filter['type'] ) :
					case 'search':
						?>
						<div class="filters__field filters__field--search">
							<label class="filters__label" for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $filter['label'] ); ?></label>
							<div class="live-search" data-live-search="<?php echo esc_url( rest_url( 'wc/store/v1/products' ) ); ?>">
								<input type="search" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php esc_attr_e( 'Nome del prodotto…', 'slug-theme' ); ?>" autocomplete="off" role="combobox" aria-autocomplete="list" aria-expanded="false" aria-controls="<?php echo esc_attr( $id . '-risultati' ); ?>">
								<ul class="live-search__results" id="<?php echo esc_attr( $id . '-risultati' ); ?>" role="listbox" hidden></ul>
							</div>
						</div>
						<?php
						break;

					case 'tax_select':
						?>
						<div class="filters__field">
							<label class="filters__label" for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $filter['label'] ); ?></label>
							<select id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>">
								<option value=""><?php echo esc_html( $filter['all'] ); ?></option>
								<?php foreach ( $filter['options'] as $slug => $label ) : ?>
									<option value="<?php echo esc_attr( $slug ); ?>" <?php selected( $value, $slug ); ?>><?php echo esc_html( $label ); ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<?php
						break;

					case 'range':
						?>
						<fieldset class="filters__field">
							<legend class="filters__label"><?php echo esc_html( $filter['label'] ); ?></legend>
							<div class="filters__range">
								<label class="screen-reader-text" for="<?php echo esc_attr( $id . '-min' ); ?>"><?php esc_html_e( 'Prezzo minimo', 'slug-theme' ); ?></label>
								<input type="number" inputmode="decimal" min="0" step="<?php echo esc_attr( $filter['step'] ); ?>" id="<?php echo esc_attr( $id . '-min' ); ?>" name="<?php echo esc_attr( starter_shop_filter_param( $key, 'min' ) ); ?>" value="<?php echo esc_attr( $value['min'] ); ?>" placeholder="<?php esc_attr_e( 'Da', 'slug-theme' ); ?>">
								<label class="screen-reader-text" for="<?php echo esc_attr( $id . '-max' ); ?>"><?php esc_html_e( 'Prezzo massimo', 'slug-theme' ); ?></label>
								<input type="number" inputmode="decimal" min="0" step="<?php echo esc_attr( $filter['step'] ); ?>" id="<?php echo esc_attr( $id . '-max' ); ?>" name="<?php echo esc_attr( starter_shop_filter_param( $key, 'max' ) ); ?>" value="<?php echo esc_attr( $value['max'] ); ?>" placeholder="<?php esc_attr_e( 'A', 'slug-theme' ); ?>">
							</div>
						</fieldset>
						<?php
						break;

					case 'tax_checkbox':
						$chosen = array_intersect_key( $filter['options'], array_flip( $value ) );
						/* translators: %d: numero di valori scelti. */
						$many    = __( '%d selezionati', 'slug-theme' );
						$summary = ! $chosen ? $filter['any'] : ( count( $chosen ) > 2 ? sprintf( $many, count( $chosen ) ) : implode( ', ', $chosen ) );
						?>
						<div class="filters__field">
							<span class="filters__label" id="<?php echo esc_attr( $id . '-label' ); ?>"><?php echo esc_html( $filter['label'] ); ?></span>
							<details class="multiselect" data-any="<?php echo esc_attr( $filter['any'] ); ?>" data-many="<?php echo esc_attr( $many ); ?>">
								<summary class="multiselect__summary" aria-labelledby="<?php echo esc_attr( $id . '-label' ); ?>">
									<span class="multiselect__value"><?php echo esc_html( $summary ); ?></span>
								</summary>
								<div class="multiselect__panel" role="group" aria-labelledby="<?php echo esc_attr( $id . '-label' ); ?>">
									<?php foreach ( $filter['options'] as $slug => $label ) : ?>
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

			<?php // Ordinamento dentro ai filtri su desktop (su tablet/mobile c'è quello accanto al pulsante "Filtri"). ?>
			<div class="filters__field filters__field--orderby">
				<label class="filters__label" for="filtro-orderby"><?php esc_html_e( 'Ordina per', 'slug-theme' ); ?></label>
				<select id="filtro-orderby" name="orderby" data-default="<?php echo esc_attr( apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', 'menu_order' ) ) ); ?>">
					<?php
					$starter_orderby = isset( $_GET['orderby'] ) ? sanitize_key( wp_unslash( $_GET['orderby'] ) ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', 'menu_order' ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
					foreach ( starter_catalog_orderby_labels( apply_filters( 'woocommerce_catalog_orderby', array( 'menu_order' => '', 'popularity' => '', 'date' => '', 'price' => '', 'price-desc' => '' ) ) ) as $starter_key => $starter_label ) :
						?>
						<option value="<?php echo esc_attr( $starter_key ); ?>" <?php selected( $starter_orderby, $starter_key ); ?>><?php echo esc_html( $starter_label ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="filters__actions filters__actions--wide">
				<button type="submit" class="button button--primary"><?php echo starter_flip_text( __( 'Filtra prodotti', 'slug-theme' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
				<?php $starter_has_filters = (bool) starter_shop_filters_active_count(); ?>
				<a class="button button--outline filters__reset<?php echo $starter_has_filters ? '' : ' is-disabled'; ?>" href="<?php echo esc_url( starter_shop_filters_reset_url() ); ?>" data-filters-reset <?php echo $starter_has_filters ? '' : 'aria-disabled="true" tabindex="-1"'; ?>><?php echo starter_flip_text( __( 'Azzera i filtri', 'slug-theme' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
			</div>
		</div>
	</form>
	<?php
}

/* Pulsante "Filtri" (tablet/mobile), allineato all'ordinamento. */
function starter_shop_filters_toggle() {
	if ( ! starter_shop_filters() ) {
		return;
	}

	$active = starter_shop_filters_active_count();
	?>
	<button type="button" class="button button--primary filters-toggle" aria-expanded="false" aria-controls="filters-form">
		<?php echo starter_icon( 'sliders', 18, 1.5 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php echo starter_flip_text( __( 'Filtri', 'slug-theme' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php if ( $active ) : ?>
			<span class="filters-toggle__count"><?php echo (int) $active; ?></span>
		<?php endif; ?>
	</button>
	<?php
}

/* Ordinamento in italiano (le etichette di WooCommerce restano in inglese senza il pacchetto di traduzione). */
function starter_catalog_orderby_labels( $options ) {
	$labels = array(
		'menu_order' => __( 'Ordine predefinito', 'slug-theme' ),
		'popularity' => __( 'Più venduti', 'slug-theme' ),
		'rating'     => __( 'Più votati', 'slug-theme' ),
		'date'       => __( 'Più recenti', 'slug-theme' ),
		'price'      => __( 'Prezzo: dal più basso', 'slug-theme' ),
		'price-desc' => __( 'Prezzo: dal più alto', 'slug-theme' ),
	);

	foreach ( $options as $key => $label ) {
		if ( isset( $labels[ $key ] ) ) {
			$options[ $key ] = $labels[ $key ];
		}
	}

	return $options;
}
add_filter( 'woocommerce_catalog_orderby', 'starter_catalog_orderby_labels' );
add_filter( 'woocommerce_default_catalog_orderby_options', 'starter_catalog_orderby_labels' );

/* Numero di risultati, in italiano. */
function starter_shop_result_count() {
	global $wp_query;

	if ( ! get_theme_mod( 'starter_shop_show_count', false ) ) {
		return;
	}

	$total = (int) $wp_query->found_posts;

	/* translators: %d: numero di prodotti trovati. */
	echo '<p class="filters-toolbar__count">' . esc_html( sprintf( _n( '%d prodotto', '%d prodotti', $total, 'slug-theme' ), $total ) ) . '</p>';
}
