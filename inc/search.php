<?php
/**
 * Ricerca del sito (lente nell'header): pannello a tutta larghezza con anteprima dei risultati mentre si scrive,
 * divisi per tipo: Prodotti (solo con WooCommerce, anche per SKU), Pagine, Articoli e gli eventuali altri contenuti
 * pubblici del sito. Niente plugin: un endpoint REST pubblico (/wp-json/starter/v1/search?q=…) e initSearchPanel()
 * in JS/scripts.js. Invio porta alla normale pagina dei risultati (search.php), quindi funziona anche senza JS.
 *
 * Si attiva da Opzioni → Header → "Mostra la ricerca". Funziona con o senza WooCommerce.
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Un risultato nel formato letto dal JS. */
function starter_search_item( $post_id, $type ) {
	$post_id = (int) $post_id;
	$item    = array(
		'title' => html_entity_decode( get_the_title( $post_id ), ENT_QUOTES, 'UTF-8' ),
		'url'   => get_permalink( $post_id ),
		'image' => (string) get_the_post_thumbnail_url( $post_id, 'medium' ),
		'sub'   => '',
	);

	if ( 'product' === $type && function_exists( 'wc_get_product' ) ) {
		$product = wc_get_product( $post_id );

		if ( $product ) {
			$price       = $product->get_price();
			$item['sub'] = '' !== $price ? html_entity_decode( wp_strip_all_tags( wc_price( $price ) ), ENT_QUOTES, 'UTF-8' ) : '';
		}
	}

	return $item;
}

/* Gli altri contenuti pubblici cercabili (tipi di contenuto personalizzati), oltre a prodotti, pagine e articoli. */
function starter_search_extra_types() {
	$types = get_post_types(
		array(
			'public'              => true,
			'exclude_from_search' => false,
		),
		'objects'
	);

	unset( $types['post'], $types['page'], $types['product'], $types['attachment'] );

	return apply_filters( 'starter_search_extra_types', $types );
}

/* Ricerca per un tipo di contenuto: ID dei risultati. */
function starter_search_ids( $post_type, $q, $limit, $extra = array() ) {
	return get_posts(
		array_merge(
			array(
				'post_type'           => $post_type,
				'post_status'         => 'publish',
				's'                   => $q,
				'posts_per_page'      => $limit,
				'fields'              => 'ids',
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
				'suppress_filters'    => false, // get_posts() li salta di default: serve perché WPML limiti i risultati alla lingua corrente.
			),
			$extra
		)
	);
}

/**
 * Risultati raggruppati per tipo, per l'anteprima (poche voci) e per un eventuale elenco completo ($full = true).
 *
 * @param string $q    Testo cercato.
 * @param bool   $full true per l'elenco completo.
 * @return array[] Ogni gruppo: type, label, items[title, url, image, sub].
 */
function starter_search_groups( $q, $full = false ) {
	$groups = array();
	$add    = function ( $type, $label, $ids ) use ( &$groups ) {
		if ( $ids ) {
			$groups[] = array(
				'type'  => $type,
				'label' => $label,
				'items' => array_map(
					function ( $id ) use ( $type ) {
						return starter_search_item( $id, $type );
					},
					$ids
				),
			);
		}
	};

	/* Prodotti: titolo/testo e SKU; fuori quelli nascosti dalla ricerca. */
	if ( function_exists( 'wc_get_product' ) ) {
		$hidden  = array(
			'tax_query' => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				array(
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => array( 'exclude-from-search' ),
					'operator' => 'NOT IN',
				),
			),
		);
		$by_text = starter_search_ids( 'product', $q, $full ? 60 : 12, $hidden );
		$by_sku  = starter_search_ids(
			'product',
			'',
			$full ? 60 : 12,
			$hidden + array(
				's'          => '',
				'meta_query' => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					array(
						'key'     => '_sku',
						'value'   => $q,
						'compare' => 'LIKE',
					),
				),
			)
		);
		$ids     = array_values( array_unique( array_merge( $by_sku, $by_text ) ) );

		// In testa: SKU uguale a quanto scritto, poi la parola nel titolo, poi il resto.
		$rank = function ( $id ) use ( $q ) {
			if ( 0 === strcasecmp( (string) get_post_meta( $id, '_sku', true ), $q ) ) {
				return 0;
			}

			return false !== mb_stripos( get_the_title( $id ), $q ) ? 1 : 2;
		};
		usort(
			$ids,
			function ( $a, $b ) use ( $rank ) {
				return $rank( $a ) <=> $rank( $b );
			}
		);

		$add( 'product', __( 'Prodotti', 'slug-theme' ), array_slice( $ids, 0, $full ? 48 : 6 ) );
	}

	/* Pagine (escluse home, carrello, checkout e account, che non hanno senso come risultato). */
	$excluded = array( (int) get_option( 'page_on_front' ) );

	if ( function_exists( 'wc_get_page_id' ) ) {
		foreach ( array( 'cart', 'checkout', 'myaccount' ) as $wc_page ) {
			$excluded[] = (int) wc_get_page_id( $wc_page );
		}
	}

	$add(
		'page',
		__( 'Pagine', 'slug-theme' ),
		starter_search_ids( 'page', $q, $full ? 48 : 6, array( 'post__not_in' => array_filter( $excluded ) ) ) // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_post__not_in
	);

	$add( 'post', __( 'Articoli', 'slug-theme' ), starter_search_ids( 'post', $q, $full ? 24 : 4 ) );

	foreach ( starter_search_extra_types() as $type => $object ) {
		$add( $type, $object->labels->name, starter_search_ids( $type, $q, $full ? 24 : 4 ) );
	}

	return $groups;
}

/* Risposta REST della ricerca: gruppi di risultati per tipo di contenuto */
function starter_search_rest( WP_REST_Request $request ) {
	// Massimo 80 caratteri: oltre non è una ricerca vera ma un tentativo di appesantire il database.
	$q = trim( mb_substr( sanitize_text_field( (string) $request->get_param( 'q' ) ), 0, 80 ) );

	if ( mb_strlen( $q ) < 2 ) {
		return rest_ensure_response(
			array(
				'query'  => $q,
				'groups' => array(),
			)
		);
	}

	// Risultati in cache per 45 secondi; la chiave cambia appena un contenuto viene modificato, quindi niente dati vecchi.
	$cache_key = 'starter_search_' . md5( $q . '|' . get_locale() . '|' . wp_cache_get_last_changed( 'posts' ) );
	$groups    = get_transient( $cache_key );

	if ( ! is_array( $groups ) ) {
		$groups = starter_search_groups( $q );
		set_transient( $cache_key, $groups, 45 );
	}

	return rest_ensure_response(
		array(
			'query'  => $q,
			'groups' => $groups,
		)
	);
}

add_action(
	'rest_api_init',
	function () {
		register_rest_route(
			'starter/v1',
			'/search',
			array(
				'methods'             => 'GET',
				'callback'            => 'starter_search_rest',
				'permission_callback' => '__return_true',
				'args'                => array(
					'q' => array(
						'type'     => 'string',
						'required' => true,
					),
				),
			)
		);
	}
);

/* Indirizzo e testi letti dal JS (le stringhe passano da qui per la traduzione). */
add_action(
	'wp_enqueue_scripts',
	function () {
		if ( ! starter_header_search_enabled() ) {
			return;
		}

		$data = array(
			'endpoint' => esc_url_raw( rest_url( 'starter/v1/search' ) ),
			'homeUrl'  => esc_url_raw( home_url( '/' ) ),
			'i18n'     => array(
				'empty' => __( 'Nessun risultato per', 'slug-theme' ),
				'all'   => __( 'Vedi tutti i risultati', 'slug-theme' ),
				'error' => __( 'La ricerca non è disponibile in questo momento. Premi Invio per cercare nella pagina dei risultati.', 'slug-theme' ),
			),
		);

		wp_add_inline_script( 'starter-theme-scripts', 'window.starterSearch = ' . wp_json_encode( $data ) . ';', 'before' );
	},
	20
);

/* Frase del campo: elenca solo ciò che si può davvero cercare. */
function starter_search_tip() {
	return function_exists( 'wc_get_product' )
		? __( 'Cerca un prodotto, una pagina o un articolo', 'slug-theme' )
		: __( 'Cerca una pagina o un articolo', 'slug-theme' );
}

/* Pannello di ricerca: si apre dalla lente dell'header (initSearchPanel in JS/scripts.js). */
function starter_search_panel() {
	if ( ! starter_header_search_enabled() ) {
		return;
	}
	?>
	<div class="search-panel" id="search-panel" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Cerca nel sito', 'slug-theme' ); ?>" hidden>
		<div class="search-panel__shade" data-search-close></div>
		<div class="search-panel__sheet">
			<div class="search-panel__head starter-container">
				<p class="search-panel__tip"><?php echo esc_html( starter_search_tip() ); ?></p>
				<form role="search" method="get" class="search-panel__form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<label class="screen-reader-text" for="search-panel-input"><?php esc_html_e( 'Cerca nel sito', 'slug-theme' ); ?></label>
					<?php echo starter_icon( 'search', 26, 1.5 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<input type="search" id="search-panel-input" class="search-panel__input" name="s" placeholder="<?php esc_attr_e( 'Inizia a digitare…', 'slug-theme' ); ?>" autocomplete="off" aria-controls="search-results">
					<button type="button" class="search-panel__clear" data-search-clear hidden><?php esc_html_e( 'Cancella', 'slug-theme' ); ?></button>
				</form>
				<button type="button" class="icon-btn search-panel__close" data-search-close aria-label="<?php esc_attr_e( 'Chiudi la ricerca', 'slug-theme' ); ?>"><?php echo starter_icon( 'x', 22, 1.5 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
			</div>
			<div class="search-panel__results starter-container" id="search-results" aria-live="polite"></div>
		</div>
	</div>
	<?php
}
add_action( 'wp_footer', 'starter_search_panel', 5 );
