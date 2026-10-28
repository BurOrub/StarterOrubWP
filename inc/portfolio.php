<?php
/**
 * Portfolio: progetti (CPT "portfolio"), categorie, blocco "Elenco progetti" e pagina del singolo progetto.
 *
 * Modulo del tema (vedi inc/modules.php): si accende e si spegne da Opzioni → Moduli (CPT). Spento, questo file non viene caricato.
 *
 * - Nessun archivio: l'elenco dei progetti è il blocco "Elenco progetti" (template-parts/progetti), da mettere in una pagina
 *   normale creata da te (titolo, testi e altri blocchi sono liberi). Filtro per categoria e pagine con ?categoria= e ?pagina=.
 * - Singolo progetto: single-portfolio.php (testata + copertina + contenuto con i blocchi "Progetto — …" + altri progetti)
 * - Blocchi dei contenuti: Progetto — testo, Progetto — media (immagini e video in 1/2/3 colonne), Progetto — crediti
 * - Contenuti di prova: starter_portfolio_demo() (lanciata da Strumenti → Contenuti di prova)
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ---------------------------------------------------------------- Opzioni */

/* Valori predefiniti (le chiavi sono i nomi dei theme_mod senza prefisso "starter_portfolio_"). L'aspetto dell'elenco (colonne, schede…) è nei campi del blocco. */
function starter_portfolio_defaults() {
	return array(
		'related' => true, // "Altri progetti" a fine scheda.
		'meta'    => true, // Servizi (categorie), agenzie, partner e link nella testata del progetto.
	);
}

/* Valore di un'opzione del Portfolio (o il suo predefinito) */
function starter_portfolio_opt( $key ) {
	$defaults = starter_portfolio_defaults();
	$default  = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';
	$value    = get_theme_mod( "starter_portfolio_{$key}", $default );

	return is_bool( $default ) ? (bool) $value : $value;
}

/* Vero se il modulo Portfolio è acceso (Opzioni → Moduli) */
function starter_portfolio_enabled() {
	return starter_module_active( 'portfolio' );
}

/* Pagina che contiene il blocco "Elenco progetti" (la prima pubblicata): serve per le briciole e per "Tutti i progetti". 0 se non c'è. */
function starter_portfolio_page_id() {
	$id = get_transient( 'starter_portfolio_page' );

	if ( false === $id ) {
		$found = get_posts(
			array(
				'post_type'      => 'page',
				'post_status'    => 'publish',
				's'              => 'wp:starter-theme/progetti',
				'search_columns' => array( 'post_content' ),
				'orderby'        => 'ID',
				'order'          => 'ASC',
				'posts_per_page' => 1,
				'fields'         => 'ids',
			)
		);
		$id    = $found ? (int) $found[0] : 0;

		set_transient( 'starter_portfolio_page', $id, DAY_IN_SECONDS );
	}

	// La cache tiene la pagina di base: con WPML si passa alla sua traduzione nella lingua corrente (senza WPML il filtro non fa nulla).
	return (int) apply_filters( 'wpml_object_id', (int) $id, 'page', true );
}

/* Indirizzo della pagina Portfolio ('' se non esiste) */
function starter_portfolio_url() {
	$id = starter_portfolio_page_id();

	return $id ? get_permalink( $id ) : '';
}

/**
 * Pagine figlie della pagina Portfolio (es. varianti di prova dell'elenco): il loro indirizzo /portfolio/nome/ coincide con quello dei
 * progetti, quindi WordPress lo cerca solo tra i progetti. Se non c'è nessun progetto con quel nome, si serve la pagina figlia.
 */
add_filter(
	'request',
	static function ( $vars ) {
		if ( empty( $vars['portfolio'] ) || ! is_string( $vars['portfolio'] ) || ! starter_portfolio_page_id() ) {
			return $vars;
		}

		if ( get_page_by_path( $vars['portfolio'], OBJECT, 'portfolio' ) ) {
			return $vars;
		}

		$path = get_page_uri( starter_portfolio_page_id() ) . '/' . $vars['portfolio'];

		if ( get_page_by_path( $path, OBJECT, 'page' ) ) {
			return array( 'pagename' => $path );
		}

		// Pagina figlia rinominata: il vecchio indirizzo porta a quello nuovo.
		$moved = get_posts(
			array(
				'post_type'      => 'page',
				'post_parent'    => starter_portfolio_page_id(),
				'post_status'    => 'publish',
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'meta_key'       => '_wp_old_slug', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'meta_value'     => $vars['portfolio'], // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			)
		);

		if ( $moved && ! is_admin() ) {
			wp_safe_redirect( get_permalink( $moved[0] ), 301 );
			exit;
		}

		return $vars;
	}
);

/* Briciole di pane e menu: i progetti stanno sotto la pagina Portfolio (vedi starter_cpt_list_page in inc/breadcrumbs.php). */
add_filter(
	'starter_cpt_list_page',
	static function ( $page_id, $post_type ) {
		return 'portfolio' === $post_type ? starter_portfolio_page_id() : $page_id;
	},
	10,
	2
);

/* Se si salva o si cancella una pagina, la ricerca della pagina Portfolio si rifà. */
foreach ( array( 'save_post_page', 'trashed_post', 'deleted_post', 'untrashed_post' ) as $starter_portfolio_hook ) {
	add_action(
		$starter_portfolio_hook,
		function () {
			delete_transient( 'starter_portfolio_page' );
		}
	);
}

/* ------------------------------------------------- Tipo di contenuto e tassonomie */

function starter_register_portfolio_cpt() {
	if ( ! starter_portfolio_enabled() ) {
		return;
	}

	register_post_type(
		'portfolio',
		array(
			'labels'        => array(
				'name'          => __( 'Portfolio', 'slug-theme' ),
				'singular_name' => __( 'Progetto', 'slug-theme' ),
				'add_new_item'  => __( 'Aggiungi nuovo progetto', 'slug-theme' ),
				'edit_item'     => __( 'Modifica progetto', 'slug-theme' ),
				'all_items'     => __( 'Tutti i progetti', 'slug-theme' ),
				'search_items'  => __( 'Cerca progetti', 'slug-theme' ),
				'not_found'     => __( 'Nessun progetto trovato', 'slug-theme' ),
			),
			'public'        => true,
			'show_in_rest'  => true, // Necessario per l'editor a blocchi.
			'menu_icon'     => 'dashicons-portfolio',
			'menu_position' => 20,
			'has_archive'   => false, // L'elenco è una pagina normale con il blocco "Elenco progetti".
			'rewrite'       => array( 'slug' => 'portfolio' ),
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		)
	);

	// Le categorie servono a organizzare e a filtrare l'elenco: non hanno pagine proprie.
	register_taxonomy(
		'portfolio_category',
		'portfolio',
		array(
			'labels'             => array(
				'name'          => __( 'Categorie portfolio', 'slug-theme' ),
				'singular_name' => __( 'Categoria portfolio', 'slug-theme' ),
			),
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_rest'       => true,
			'show_admin_column'  => true,
			'hierarchical'       => true, // Si comporta come le categorie (con eventuali sotto-categorie).
			'rewrite'            => false,
		)
	);

	register_taxonomy(
		'portfolio_client_type',
		'portfolio',
		array(
			'labels'             => array(
				'name'          => __( 'Tipo di cliente', 'slug-theme' ),
				'singular_name' => __( 'Tipo di cliente', 'slug-theme' ),
			),
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_rest'       => true,
			'hierarchical'       => true,
			'rewrite'            => false,
		)
	);
}
add_action( 'init', 'starter_register_portfolio_cpt' );

/* Starter Live: i dati del progetto (cliente, anno, servizi, agenzie, link, video) si modificano dal frontend come i campi dei blocchi. */
add_filter(
	'starter_live_meta_post_types',
	static function ( $types ) {
		$types[] = 'portfolio';

		return $types;
	}
);

/* Campi che la testata non mostra come testo: non vanno agganciati ad altri elementi. */
add_filter(
	'starter_live_meta_post_skip',
	static function ( $skip, $post_type ) {
		return 'portfolio' === $post_type ? array_merge( (array) $skip, array( 'progetto_logo', 'progetto_copertina_full', 'progetto_correlati' ) ) : $skip;
	},
	10,
	2
);


/* ------------------------------------------------- Funzioni per i template */

/* Margini delle categorie: le classi già presenti nel CSS (.mt05, .mt1, … e .mb05, .mb1, …). '' = nessuna classe (margine 0). */
function starter_portfolio_margin_choices( $prefix ) {
	$choices = array( '' => __( '0 (nessuno)', 'slug-theme' ) );

	foreach ( array( '05' => '0,5 rem', '1' => '1 rem', '2' => '2 rem', '3' => '3 rem', '4' => '4 rem', '5' => '5 rem', '6' => '6 rem', '7' => '7 rem', '8' => '8 rem', '9' => '9 rem', '10' => '10 rem', '20' => '20 px' ) as $key => $label ) {
		$choices[ $prefix . $key ] = strtoupper( $prefix . $key ) . ' — ' . $label;
	}

	return $choices;
}

/**
 * Opzioni delle card (categorie, riassunto e cliente, video, animazione logo, colori) lette da una sorgente: i campi del blocco "Elenco progetti"
 * ('get_field') oppure le opzioni del tema per gli "Altri progetti" (vedi starter_portfolio_related_get()). $get riceve il nome del campo
 * (gli stessi nomi dei campi del blocco) e restituisce il valore, o null se non è mai stato impostato: allora valgono i predefiniti.
 * Restituisce: cats (stile), cats_opts, titles, video, reveal, style (variabili CSS dei colori delle categorie).
 */
function starter_portfolio_card_config( $get ) {
	// Interruttori: se il campo non è mai stato salvato valgono "acceso".
	$flag = static function ( $name ) use ( $get ) {
		$value = $get( $name );

		return null === $value || '' === $value ? true : (bool) $value;
	};

	// Categorie sulle card. I blocchi salvati prima di "Stile categorie" avevano solo l'interruttore "categorie".
	$cat_style = (string) $get( 'stile_categorie' );

	if ( ! in_array( $cat_style, array( 'testo', 'tab_sotto', 'tab_sopra', 'nessuna' ), true ) ) {
		$cat_style = $flag( 'categorie' ) ? 'testo' : 'nessuna';
	}

	$cat_opts = array(
		'separator' => (string) $get( 'separatore_categorie' ) ?: 'dot',
		'fill'      => (string) $get( 'riempimento_categorie' ) ?: 'piene',
		'shape'     => (string) $get( 'forma_categorie' ) ?: 'singole',
		'upper'     => (bool) $get( 'maiuscole_categorie' ),
		'mt'        => (string) $get( 'margine_sopra_categorie' ),
		'mb'        => (string) $get( 'margine_sotto_categorie' ),
		'position'  => 'prima' === $get( 'posizione_categorie' ) ? 'prima' : 'dopo',
	);

	// Riassunto e nome del cliente (scheda "Titolo").
	$titles = array(
		'order'    => 'cliente' === $get( 'ordine_titoli' ) ? 'cliente' : 'riassunto',
		'sum_show' => $flag( 'riassunto_mostra' ),
		'sum_tag'  => (string) $get( 'riassunto_tag' ) ?: 'h2',
		'sum_size' => (string) $get( 'riassunto_dimensione' ) ?: 'title-5',
		'sum_col'  => (string) $get( 'riassunto_colore' ),
		'sum_hov'  => (string) $get( 'riassunto_colore_hover' ),
		'sum_pos'  => 'sopra' === $get( 'riassunto_posizione' ) ? 'sopra' : 'sotto',
		'cli_show' => $flag( 'cliente_mostra' ),
		'cli_tag'  => (string) $get( 'cliente_tag' ) ?: 'p',
		'cli_size' => (string) $get( 'cliente_dimensione' ) ?: 'small',
		'cli_col'  => (string) $get( 'cliente_colore' ),
		'cli_hov'  => (string) $get( 'cliente_colore_hover' ),
		'cli_pos'  => 'sopra' === $get( 'cliente_posizione' ) ? 'sopra' : 'sotto',
	);

	// Animazione logo: il nome del cliente diventa la scritta animata sull'immagine (niente riga sotto) e il riassunto sta sempre sotto.
	$reveal = array();

	if ( $get( 'animazione_logo' ) ) {
		$titles['cli_show'] = false;
		$titles['sum_pos']  = 'sotto';
		$reveal             = array(
			'tint'  => $flag( 'logo_tinta' ),
			'color' => starter_resolve_named_color( (string) $get( 'cliente_colore' ) ),
		);
	}

	// Colore delle categorie (menu "Colori con nome"): --cat-color, e --cat-color-text per il testo sopra le tab piene (chiaro o scuro in base al colore).
	$style   = '';
	$cat_hex = starter_resolve_named_color( (string) $get( 'colore_categorie' ) );

	if ( $cat_hex ) {
		$style = '--cat-color:' . $cat_hex . ';--cat-color-text:' . ( starter_color_is_dark( $cat_hex ) ? '#fff' : '#111' ) . ';';
	}

	$sep_hex = starter_resolve_named_color( (string) $get( 'colore_simbolo_categorie' ) );

	if ( $sep_hex ) {
		$style .= '--cat-sep:' . $sep_hex . ';--cat-sep-op:1;';
	}

	return array(
		'cats'      => $cat_style,
		'cats_opts' => $cat_opts,
		'titles'    => $titles,
		'video'     => $flag( 'video_hover' ),
		'reveal'    => $reveal,
		'style'     => $style,
	);
}

/* Video mostrato sopra l'immagine al passaggio del mouse: il video di copertina del progetto. Restituisce l'URL o ''. */
function starter_portfolio_hover_video( $post_id = 0 ) {
	$file = starter_field( 'progetto_video', $post_id ? $post_id : get_the_ID() );

	return is_array( $file ) && ! empty( $file['url'] ) ? $file['url'] : '';
}

/* Proporzione (larghezza / altezza) di un logo. Gli SVG non hanno dimensioni nella Libreria media: si leggono dal file (width e height, oppure viewBox). 0 se non si riesce. */
function starter_portfolio_logo_ratio( $logo ) {
	if ( ! empty( $logo['width'] ) && ! empty( $logo['height'] ) ) {
		return round( $logo['width'] / $logo['height'], 4 );
	}

	$id    = (int) $logo['ID'];
	$cache = get_post_meta( $id, '_starter_logo_ratio', true );

	if ( '' !== $cache ) {
		return (float) $cache;
	}

	$ratio = 0;
	$file  = get_attached_file( $id );
	$svg   = $file && is_readable( $file ) && 'svg' === strtolower( pathinfo( $file, PATHINFO_EXTENSION ) ) ? (string) file_get_contents( $file, false, null, 0, 4096 ) : ''; // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- file locale della Libreria media.

	if ( $svg ) {
		if ( preg_match( '/viewbox\s*=\s*["\']\s*[-\d.]+[\s,]+[-\d.]+[\s,]+([\d.]+)[\s,]+([\d.]+)/i', $svg, $m ) && (float) $m[2] > 0 ) {
			$ratio = round( (float) $m[1] / (float) $m[2], 4 );
		} elseif ( preg_match( '/<svg[^>]*\swidth\s*=\s*["\']([\d.]+)/i', $svg, $w ) && preg_match( '/<svg[^>]*\sheight\s*=\s*["\']([\d.]+)/i', $svg, $h ) && (float) $h[1] > 0 ) {
			$ratio = round( (float) $w[1] / (float) $h[1], 4 );
		}
	}

	update_post_meta( $id, '_starter_logo_ratio', $ratio );

	return $ratio;
}

/**
 * Animazione logo di una card: al centro dell'immagine il logo del cliente (se manca, il nome in testo); passando il mouse il logo sparisce
 * e il nome del cliente, in grande, scorre da destra a sinistra (senso di lettura). $opts: tint (logo nel colore dello sfondo del sito), color (colore esadecimale facoltativo).
 */
function starter_portfolio_reveal_html( $opts = array(), $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$name    = get_the_title( $post_id ); // Il nome del cliente è il titolo del progetto.

	if ( '' === $name ) {
		return '';
	}

	$logo  = starter_field( 'progetto_logo', $post_id );
	$tint  = ! isset( $opts['tint'] ) || $opts['tint'];
	$style = ! empty( $opts['color'] ) ? ' style="--reveal-c:' . esc_attr( $opts['color'] ) . '"' : '';
	$url   = is_array( $logo ) && ! empty( $logo['ID'] ) ? wp_get_attachment_image_url( (int) $logo['ID'], 'medium' ) : '';

	$ratio = is_array( $logo ) && ! empty( $logo['ID'] ) ? starter_portfolio_logo_ratio( $logo ) : 0;

	if ( $url && $tint && $ratio ) {
		$mark = '<span class="project-card__logo project-card__logo--mask" style="--logo:url(' . esc_url( $url ) . ');--logo-ratio:' . $ratio . '" role="img" aria-label="' . esc_attr( $name ) . '"></span>';
	} elseif ( $url ) {
		$mark = starter_picturefy( '<img class="project-card__logo" src="' . esc_url( $url ) . '" alt="' . esc_attr( $name ) . '" loading="lazy" decoding="async">' );
	} else {
		$mark = '<span class="project-card__logo project-card__logo--text">' . esc_html( $name ) . '</span>';
	}

	// Il gruppo si ripete fino a coprire la larghezza e poi si duplica: lo scorrimento è continuo. Più è lungo il testo, più dura il giro.
	$length = max( 1, mb_strlen( $name ) );
	$times  = max( 1, (int) ceil( 14 / $length ) );
	$group  = '<span class="project-card__marquee-group">' . str_repeat( '<span>' . esc_html( $name ) . '</span>', $times ) . '</span>';
	$secs   = max( 6, round( $length * $times * 0.5 ) );

	return '<span class="project-card__reveal"' . $style . '>' . $mark . '<span class="project-card__marquee" aria-hidden="true"><span class="project-card__marquee-track" style="--marquee-dur:' . (int) $secs . 's">' . $group . $group . '</span></span></span>'; // Valori già scappati sopra.
}

/**
 * Griglia dei progetti nella visualizzazione scelta. $cards: HTML delle card (già scappato). $s: cols, cols_t, cols_m, gap_x, gap_y, ratio, fourth.
 * classica = colonne con immagini della stessa altezza; masonry = colonne CSS con il formato originale; blocchi = gruppi da 3 (2 orizzontali + 1 verticale, poi
 * invertiti) con un quarto elemento facoltativo a tutta larghezza; sfalsata = 2 colonne con offset e larghezze diverse.
 */
function starter_portfolio_grid_html( $cards, $layout, $s ) {
	$vars = sprintf(
		'--pc-d:%d;--pc-t:%d;--pc-m:%d;--gap-x:%dpx;--gap-y:%dpx;--project-ratio:%s;',
		$s['cols'],
		$s['cols_t'],
		$s['cols_m'],
		$s['gap_x'],
		$s['gap_y'],
		str_replace( '-', ' / ', $s['ratio'] )
	);

	if ( 'blocchi' === $layout ) {
		$keys   = $s['fourth'] ? array( 'h1', 'h2', 'v', 'f' ) : array( 'h1', 'h2', 'v' );
		$html   = '<div class="project-blocks" style="' . esc_attr( $vars ) . '">';
		$groups = array_chunk( $cards, count( $keys ) );

		foreach ( $groups as $g => $group ) {
			$html .= '<div class="project-group' . ( $g % 2 ? ' is-flip' : '' ) . '">';

			foreach ( $group as $k => $card ) {
				$html .= '<div class="project-slot project-slot--' . $keys[ $k ] . '">' . $card . '</div>';
			}

			$html .= '</div>';
		}

		return $html . '</div>';
	}

	if ( 'sfalsata' === $layout ) {
		$cols = array( '', '' );

		foreach ( $cards as $i => $card ) {
			$col           = $i % 2;
			$wide          = 0 === ( (int) floor( $i / 2 ) + $col ) % 2;
			$cols[ $col ] .= '<div class="project-stagger__item ' . ( $wide ? 'is-wide' : 'is-narrow' ) . '">' . $card . '</div>';
		}

		return '<div class="project-stagger" style="' . esc_attr( $vars ) . '"><div class="project-stagger__col">' . $cols[0] . '</div><div class="project-stagger__col project-stagger__col--b">' . $cols[1] . '</div></div>';
	}

	return '<div class="project-grid project-grid--set project-grid--' . ( 'masonry' === $layout ? 'masonry' : 'classica' ) . '" style="' . esc_attr( $vars ) . '">' . implode( '', $cards ) . '</div>';
}

/**
 * Transizione tra elenco e progetto (scheda Animazioni del blocco "Elenco progetti"): nessuna | cover | dissolvenza | tendina.
 * Sulla pagina dell'elenco vale la scelta del blocco; sulla scheda del progetto, quella del blocco nella pagina Portfolio.
 * Usa le View Transitions tra pagine (Chrome, Edge, Safari recenti): dove non ci sono, si naviga normalmente.
 */
function starter_portfolio_transition_config() {
	static $config = null;

	if ( null !== $config ) {
		return $config;
	}

	$config = array( 'mode' => 'nessuna', 'text' => '', 'counter' => false, 'bg' => '', 'fg' => '' );
	$modes  = array( 'cover', 'dissolvenza', 'tendina' );

	// Tra un progetto e l'altro (correlati): una sola scelta, in Opzioni → Portfolio.
	if ( is_singular( 'portfolio' ) ) {
		$mode = (string) get_theme_mod( 'starter_portfolio_single_transition', 'nessuna' );

		if ( in_array( $mode, $modes, true ) ) {
			$config = array(
				'mode'    => $mode,
				'text'    => (string) starter_wpml_translate_string( 'portfolio_single_text', (string) get_theme_mod( 'starter_portfolio_single_text', __( 'Caricamento', 'slug-theme' ) ) ),
				'counter' => (bool) get_theme_mod( 'starter_portfolio_single_counter', false ),
				'bg'      => starter_resolve_named_color( (string) get_theme_mod( 'starter_portfolio_single_bg', '' ) ),
				'fg'      => starter_resolve_named_color( (string) get_theme_mod( 'starter_portfolio_single_fg', '' ) ),
			);
		}

		return $config;
	}

	// Dall'elenco al progetto: la scelta del blocco "Elenco progetti" di questa pagina.
	$post = is_singular() ? get_queried_object() : null;

	if ( ! $post || ! isset( $post->post_content ) || false === strpos( $post->post_content, 'wp:starter-theme/progetti' ) ) {
		return $config;
	}

	$find = static function ( $blocks ) use ( &$find ) {
		foreach ( $blocks as $block ) {
			if ( 'starter-theme/progetti' === $block['blockName'] ) {
				return isset( $block['attrs']['data'] ) ? (array) $block['attrs']['data'] : array();
			}

			if ( ! empty( $block['innerBlocks'] ) && null !== ( $found = $find( $block['innerBlocks'] ) ) ) { // phpcs:ignore WordPress.CodeAnalysis.AssignmentInCondition.Found
				return $found;
			}
		}

		return null;
	};
	$data = $find( parse_blocks( $post->post_content ) );

	if ( is_array( $data ) && isset( $data['transizione'] ) && in_array( $data['transizione'], $modes, true ) ) {
		$config = array(
			'mode'    => $data['transizione'],
			'text'    => isset( $data['transizione_testo'] ) ? (string) $data['transizione_testo'] : __( 'Caricamento', 'slug-theme' ),
			'counter' => ! empty( $data['transizione_contatore'] ),
			'bg'      => isset( $data['transizione_sfondo'] ) ? starter_resolve_named_color( (string) $data['transizione_sfondo'] ) : '',
			'fg'      => isset( $data['transizione_colore_testo'] ) ? starter_resolve_named_color( (string) $data['transizione_colore_testo'] ) : '',
		);
	}

	return $config;
}

/* Solo il tipo di transizione (nessuna | cover | tendina | dissolvenza). */
function starter_portfolio_transition() {
	$config = starter_portfolio_transition_config();

	return $config['mode'];
}

add_action(
	'wp_head',
	static function () {
		$config = starter_portfolio_transition_config();

		// Sul progetto lo script d'arrivo serve sempre (la transizione può essere partita da qualunque elenco); la meta solo dove la transizione è attiva.
		if ( 'nessuna' === $config['mode'] && ! is_singular( 'portfolio' ) ) {
			return;
		}

		if ( 'nessuna' !== $config['mode'] ) {
			printf(
				'<meta name="starter-project-transition" content="%1$s" data-text="%2$s" data-counter="%3$s" data-bg="%4$s" data-fg="%5$s">' . "\n",
				esc_attr( $config['mode'] ),
				esc_attr( $config['text'] ),
				$config['counter'] ? '1' : '0',
				esc_attr( $config['bg'] ),
				esc_attr( $config['fg'] )
			);
		}

		// Pagina di arrivo: se l'animazione è partita dall'elenco, il velo (immagine a tutto schermo, tendina o caricamento) c'è già al primo disegno.
		// Lo toglie JS/scripts.js (initProjectTransition) quando la pagina è pronta.
		echo '<script id="starter-project-transition-in">(function(){try{var d=JSON.parse(sessionStorage.getItem("starterPt")||"null");if(!d||Date.now()-d.t>10000||!/^(cover|tendina|dissolvenza)$/.test(d.mode)){return;}var r=document.documentElement,s=r.style,q=function(v){return JSON.stringify(String(v).replace(/[\\\\\n\r]/g," "));};s.setProperty("--pt-img","url("+q(d.src)+")");s.setProperty("--pt-name",q(d.name));s.setProperty("--pt-text",q(d.text||""));s.setProperty("--pt-n","0");if(d.bg){s.setProperty("--pt-bg",d.bg);}if(d.fg){s.setProperty("--pt-fg",d.fg);}if(d.mode==="cover"&&d.ar>0){var W=r.clientWidth||innerWidth,H=innerHeight,w=W,h=W/d.ar;if(h<H){h=H;w=H*d.ar;}s.setProperty("--pt-bs1",w+"px "+h+"px");}r.className+=" pt-"+d.mode+(d.counter?" pt-counter":"")+" pt-go pt-in";}catch(e){}})();</script>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- script fisso, senza dati di input.
	},
	1
);

/* Menu colori dei campi del blocco: la prima voce è "Predefinito" (breve, entra nel pannello stretto). */
function starter_portfolio_color_choices() {
	$choices = function_exists( 'starter_named_color_choices' ) ? starter_named_color_choices() : array( '' => '' );
	$choices[''] = __( 'Predefinito', 'slug-theme' );

	return $choices;
}

/* Progetto con la copertina a tutto schermo in cima: l'header parte trasparente sopra l'immagine (classe has-hero) e la testata scende sotto. */
add_filter(
	'body_class',
	static function ( $classes ) {
		if ( is_singular( 'portfolio' ) && starter_field( 'progetto_copertina_full' ) && has_post_thumbnail() ) {
			$classes[] = 'has-hero';
			$classes[] = 'project-cover-full';
		}

		return $classes;
	}
);

/* Nome del cliente di un progetto: è il titolo del progetto (non c'è un campo a parte). Lo usano le animazioni e la riga "cliente" delle card. */
function starter_portfolio_client_name( $post_id = 0 ) {
	return get_the_title( $post_id ? $post_id : get_the_ID() );
}

/* Colori con nome per l'hover: "Nessuno" = il colore non cambia (si potrà usare un effetto grafico al suo posto). */
function starter_portfolio_hover_color_choices() {
	$choices = function_exists( 'starter_named_color_choices' ) ? starter_named_color_choices() : array( '' => '' );
	$choices[''] = __( 'Nessuno', 'slug-theme' );

	return $choices;
}

/* Tag HTML scelti per riassunto e cliente: tutti i titoli, paragrafo e span (si sceglie in base a dove sta il blocco nella pagina). */
function starter_portfolio_tag_choices() {
	return array(
		'h1'   => 'H1',
		'h2'   => 'H2',
		'h3'   => 'H3',
		'h4'   => 'H4',
		'h5'   => 'H5',
		'h6'   => 'H6',
		'p'    => __( 'Paragrafo', 'slug-theme' ),
		'span' => 'Span',
	);
}

/* Misure per i menu di dimensione: titoli del tema e misure di Gutenberg, con i px (minimo–massimo) letti da theme.json. */
function starter_portfolio_font_size_choices() {
	$sizes = function_exists( 'wp_get_global_settings' ) ? wp_get_global_settings( array( 'typography', 'fontSizes', 'theme' ) ) : array();
	$by    = array();

	foreach ( (array) $sizes as $size ) {
		if ( isset( $size['slug'], $size['name'], $size['size'] ) ) {
			$by[ $size['slug'] ] = $size;
		}
	}

	$label = static function ( $slug, $fallback ) use ( $by ) {
		if ( ! isset( $by[ $slug ] ) ) {
			return $fallback;
		}

		preg_match_all( '/([\d.]+)rem/', $by[ $slug ]['size'], $m );
		$px = array_map(
			static function ( $rem ) {
				return (int) round( (float) $rem * 16 );
			},
			$m[1]
		);
		$px = $px ? ( count( $px ) > 1 && reset( $px ) !== end( $px ) ? reset( $px ) . '–' . end( $px ) : (string) reset( $px ) ) . ' px' : '';

		return trim( $by[ $slug ]['name'] . ' — ' . $px, ' —' );
	};

	$groups = array(
		__( 'Titoli del tema', 'slug-theme' )   => array(
			'display' => 'Titolo grande (hero)',
			'title-1' => 'Titolo 1 (H1)',
			'title-2' => 'Titolo 2 (H2)',
			'title-3' => 'Titolo 3 (H3)',
			'title-4' => 'Titolo 4 (H4)',
			'title-5' => 'Titolo 5 (H5)',
			'eyebrow' => 'Titolo 6 / etichetta (H6)',
		),
		__( 'Misure di Gutenberg', 'slug-theme' ) => array(
			'xx-large' => 'XXL',
			'x-large'  => 'XL',
			'large'    => 'Grande',
			'medium'   => 'Media',
			'small'    => 'Piccola',
		),
	);

	foreach ( $groups as $group => $items ) {
		foreach ( $items as $slug => $fallback ) {
			$groups[ $group ][ $slug ] = $label( $slug, $fallback );
		}
	}

	return $groups;
}

/* Classe CSS di una misura: i titoli usano le classi .title-hN del tema, le misure di Gutenberg .has-…-font-size. */
function starter_portfolio_size_class( $slug ) {
	if ( 'display' === $slug ) {
		return 'title-display';
	}

	if ( 'eyebrow' === $slug ) {
		return 'title-h6';
	}

	if ( preg_match( '/^title-([1-5])$/', (string) $slug, $m ) ) {
		return 'title-h' . $m[1];
	}

	return in_array( $slug, array( 'xx-large', 'x-large', 'large', 'medium', 'small' ), true ) ? 'has-' . $slug . '-font-size' : 'title-h5';
}

/**
 * Riassunto (tipo di lavoro) e nome del cliente di una card, nell'ordine, tag, misura e colore scelti nel blocco "Elenco progetti".
 * Il riassunto è l'estratto del progetto; il cliente è il campo "Cliente" (se vuoto, il titolo). Se si spengono entrambi resta il titolo
 * solo per chi usa lo screen reader, così il link ha sempre un nome. $where: 'sotto' o 'sopra' l'immagine (la chiamata "sotto" stampa anche il titolo nascosto).
 */
function starter_portfolio_titles_html( $opts = array(), $post_id = 0, $where = 'sotto' ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$opts    = wp_parse_args(
		$opts,
		array(
			'order'    => 'riassunto',
			'sum_show' => true,
			'sum_tag'  => 'h2',
			'sum_size' => 'title-5',
			'sum_col'  => '',
			'sum_hov'  => '',
			'sum_pos'  => 'sotto',
			'cli_show' => true,
			'cli_tag'  => 'p',
			'cli_size' => 'small',
			'cli_col'  => '',
			'cli_hov'  => '',
			'cli_pos'  => 'sotto',
		)
	);

	$parts = array();
	$item  = static function ( $class, $tag, $allowed, $default, $size, $color, $hover, $text ) {
		$tag    = in_array( $tag, $allowed, true ) ? $tag : $default;
		$hex    = $color ? starter_resolve_named_color( $color ) : '';
		$hex_h  = $hover ? starter_resolve_named_color( $hover ) : '';
		$style  = ( $hex ? '--text-c:' . $hex . ';' : '' ) . ( $hex_h ? '--text-h:' . $hex_h . ';' : '' );
		$style  = $style ? ' style="' . esc_attr( $style ) . '"' : '';
		$class .= $hex_h ? ' has-hover-color' : '';

		return '<' . $tag . ' class="' . esc_attr( $class . ' ' . starter_portfolio_size_class( $size ) ) . '"' . $style . '>' . esc_html( $text ) . '</' . $tag . '>';
	};

	$summary = has_excerpt( $post_id ) ? wp_strip_all_tags( get_the_excerpt( $post_id ) ) : '';
	$client  = get_the_title( $post_id );

	if ( $opts['sum_show'] && '' !== $summary ) {
		$parts['riassunto'] = $item( 'project-card__summary', $opts['sum_tag'], array_keys( starter_portfolio_tag_choices() ), 'h2', $opts['sum_size'], $opts['sum_col'], $opts['sum_hov'], $summary );
	}

	if ( $opts['cli_show'] && '' !== $client ) {
		$parts['cliente'] = $item( 'project-card__client', $opts['cli_tag'], array_keys( starter_portfolio_tag_choices() ), 'p', $opts['cli_size'], $opts['cli_col'], $opts['cli_hov'], $client );
	}

	if ( ! $parts ) {
		return 'sotto' !== $where ? '' : '<span class="project-card__titles"><span class="screen-reader-text">' . esc_html( get_the_title( $post_id ) ) . '</span></span>';
	}

	if ( 'cliente' === $opts['order'] ) {
		$parts = array_reverse( $parts );
	}

	// Ogni voce sta sotto o sopra l'immagine (in basso a sinistra): si stampa il gruppo richiesto da $where.
	$pos = array(
		'riassunto' => 'sopra' === $opts['sum_pos'] ? 'sopra' : 'sotto',
		'cliente'   => 'sopra' === $opts['cli_pos'] ? 'sopra' : 'sotto',
	);
	$parts = array_filter(
		$parts,
		static function ( $key ) use ( $pos, $where ) {
			return $pos[ $key ] === $where;
		},
		ARRAY_FILTER_USE_KEY
	);

	if ( ! $parts ) {
		return '';
	}

	return '<span class="project-card__titles' . ( 'sopra' === $where ? ' project-card__titles--over' : '' ) . '">' . implode( '', $parts ) . '</span>'; // Valori già scappati sopra.
}

/* Simboli separatori per i campi del blocco (stesso set della barra annunci). */
function starter_portfolio_separator_choices() {
	return function_exists( 'starter_topbar_separators' ) ? starter_topbar_separators() : array( 'dot' => '·' );
}

/* Categorie di un progetto (nomi). */
function starter_portfolio_cats( $post_id = 0 ) {
	$terms = get_the_terms( $post_id ? $post_id : get_the_ID(), 'portfolio_category' );

	return ( $terms && ! is_wp_error( $terms ) ) ? wp_list_pluck( $terms, 'name' ) : array();
}

/**
 * Categorie di una card, nello stile scelto nel blocco "Elenco progetti".
 * $style: testo | tab_sotto | tab_sopra; $opts: separator (chiave del set), fill (piene|vuote), shape (singole|unica), upper (maiuscolo), mt/mb (classi .mt…/.mb… sotto l'immagine).
 * Restituisce l'HTML (vuoto se non ci sono categorie). Le categorie non sono link: la card intera è già un link.
 */
function starter_portfolio_cats_html( $names, $style, $opts = array() ) {
	if ( ! $names || 'nessuna' === $style ) {
		return '';
	}

	$opts       = wp_parse_args(
		$opts,
		array(
			'separator' => 'dot',
			'fill'      => 'piene',
			'shape'     => 'singole',
			'upper'     => false,
			'mt'        => '',
			'mb'        => '',
		)
	);
	$separators = starter_portfolio_separator_choices();
	$symbol     = isset( $separators[ $opts['separator'] ] ) ? $separators[ $opts['separator'] ] : '·';
	$sep        = ' <span class="project-card__sep" aria-hidden="true">' . esc_html( $symbol ) . '</span> ';
	$names      = array_map( 'esc_html', $names );
	$fill       = 'vuote' === $opts['fill'] ? 'outline' : 'fill';
	$upper      = $opts['upper'] ? ' is-upper' : '';
	if ( 'tab_sopra' === $style ) {
		$opts['mt'] = '';
		$opts['mb'] = '';
	}

	$margins    = array_keys( starter_portfolio_margin_choices( 'mt' ) + starter_portfolio_margin_choices( 'mb' ) );
	$upper     .= ( $opts['mt'] && in_array( $opts['mt'], $margins, true ) ? ' ' . $opts['mt'] : '' ) . ( $opts['mb'] && in_array( $opts['mb'], $margins, true ) ? ' ' . $opts['mb'] : '' );

	if ( 'tab_sotto' === $style ) {
		return '<span class="project-card__cats project-card__cats--tabs is-' . $fill . $upper . '"><span class="project-card__tab">' . implode( '</span><span class="project-card__tab">', $names ) . '</span></span>';
	}

	if ( 'tab_sopra' === $style ) {
		if ( 'unica' === $opts['shape'] ) {
			return '<span class="project-card__cats project-card__cats--over is-single' . $upper . '"><span class="project-card__tab">' . implode( $sep, $names ) . '</span></span>';
		}

		return '<span class="project-card__cats project-card__cats--over is-' . $fill . $upper . '"><span class="project-card__tab">' . implode( '</span><span class="project-card__tab">', $names ) . '</span></span>';
	}

	return '<span class="project-card__cats project-card__cats--text' . $upper . '">' . implode( $sep, $names ) . '</span>';
}

/**
 * Testata dell'elenco: occhiello (H1 con la parola chiave), titolo e introduzione, come la pagina Blog. Nella pagina Portfolio
 * titolo, occhiello e testo introduttivo sono quelli della pagina (modificabili anche dal frontend con Starter Live);
 * nelle pagine delle categorie, il nome della categoria.
 */
/* Schede delle categorie del blocco "Elenco progetti": "Tutti" + una per categoria. Sono link alla stessa pagina con ?categoria=, funzionano senza JavaScript. */
function starter_portfolio_tabs( $terms, $base, $active_slug, $total, $counts, $style = 'linea', $sentence = '' ) {
	$style  = in_array( $style, array( 'linea', 'testo', 'pillole', 'frase' ), true ) ? $style : 'linea';
	$counts = $counts && 'frase' !== $style;
	$items  = 'frase' === $style ? array() : array( array( $base, __( 'Tutti', 'slug-theme' ), $total, '' === $active_slug ) );

	foreach ( $terms as $term ) {
		// Nella frase non c'è "Tutti": cliccando il filtro attivo si disattiva (link alla pagina senza categoria).
		$is_active = $active_slug === $term->slug;
		$items[]   = array( $is_active && 'frase' === $style ? $base : add_query_arg( 'categoria', $term->slug, $base ), $term->name, $term->count, $is_active );
	}

	$link = static function ( $item ) use ( $counts ) {
		return sprintf(
			'<a class="project-tabs__link%1$s" href="%2$s"%3$s>%4$s%5$s</a>',
			$item[3] ? ' is-active' : '',
			esc_url( $item[0] ),
			$item[3] ? ' aria-current="page"' : '',
			esc_html( $item[1] ),
			$counts ? ' <span class="project-tabs__count">' . (int) $item[2] . '</span>' : ''
		);
	};

	$label = esc_attr__( 'Categorie del portfolio', 'slug-theme' );

	// Frase: i filtri sono parole sottolineate dentro una frase ("Realizziamo progetti di Branding, Web design.").
	if ( 'frase' === $style ) {
		echo '<nav class="project-tabs project-tabs--frase" aria-label="' . $label . '"><p class="project-tabs__sentence">' . ( $sentence ? esc_html( $sentence ) . ' ' : '' ) . implode( ', ', array_map( $link, $items ) ) . '.</p></nav>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- scappato in $link e sopra.

		return;
	}

	echo '<nav class="project-tabs project-tabs--' . esc_attr( $style ) . '" aria-label="' . $label . '"><ul class="project-tabs__list">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	foreach ( $items as $item ) {
		echo '<li>' . $link( $item ) . '</li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- scappato in $link.
	}

	echo '</ul></nav>';
}

/* Progetti correlati in fondo alla scheda di un progetto */
function starter_portfolio_related( $count = 3 ) {
	if ( ! starter_portfolio_opt( 'related' ) ) {
		return;
	}

	$id  = get_the_ID();
	$ids = array();

	// Prima quelli scelti a mano (mai il progetto stesso, solo pubblicati, nessun doppione).
	foreach ( (array) starter_field( 'progetto_correlati', $id ) as $chosen ) {
		$chosen = (int) ( is_object( $chosen ) ? $chosen->ID : $chosen );
		$chosen = (int) apply_filters( 'wpml_object_id', $chosen, 'portfolio', false ); // Con WPML: la traduzione nella lingua corrente (0 se manca, così non si mostrano progetti di un'altra lingua).

		if ( $chosen && $chosen !== $id && 'portfolio' === get_post_type( $chosen ) && 'publish' === get_post_status( $chosen ) && ! in_array( $chosen, $ids, true ) ) {
			$ids[] = $chosen;
		}
	}

	$ids = array_slice( $ids, 0, $count );

	// Quelli che mancano: gli ultimi progetti, escludendo questo e i già scelti.
	if ( count( $ids ) < $count ) {
		$more = get_posts(
			array(
				'post_type'        => 'portfolio',
				'post__not_in'     => array_merge( array( $id ), $ids ), // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_post__not_in
				'posts_per_page'   => $count - count( $ids ),
				'orderby'          => 'date',
				'order'            => 'DESC',
				'fields'           => 'ids',
				'suppress_filters' => false, // Solo progetti della lingua corrente (WPML).
			)
		);
		$ids  = array_merge( $ids, $more );
	}

	if ( ! $ids ) {
		return;
	}

	$starter_tags = starter_section_heading_tags( false );
	$starter_card = starter_portfolio_card_config( 'starter_portfolio_related_get' ); // Aspetto delle card: Opzioni → Portfolio.
	$starter_ratio = (string) starter_portfolio_related_get( 'proporzione' );
	$starter_ratio = in_array( $starter_ratio, array( '16-9', '3-2', '4-3', '1-1', '4-5' ), true ) ? $starter_ratio : '4-3';
	?>
	<section class="alignfull content-block project-related">
		<div class="block-wrap">
			<div class="section-head" data-reveal>
				<div class="section-head__text">
					<<?php echo esc_html( $starter_tags['title'] ); ?> class="text-balance"><?php esc_html_e( 'Altri progetti', 'slug-theme' ); ?></<?php echo esc_html( $starter_tags['title'] ); ?>>
				</div>
				<?php if ( starter_portfolio_url() ) : ?>
					<a class="button button--outline" href="<?php echo esc_url( starter_portfolio_url() ); ?>"><?php echo starter_flip_text( __( 'Tutti i progetti', 'slug-theme' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
				<?php endif; ?>
			</div>
			<div class="project-grid project-grid--related" style="<?php echo esc_attr( $starter_card['style'] . '--project-ratio:' . str_replace( '-', ' / ', $starter_ratio ) . ';' ); ?>">
				<?php
				foreach ( $ids as $related_id ) {
					$GLOBALS['post'] = get_post( $related_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
					setup_postdata( $GLOBALS['post'] );
					get_template_part(
						'template-parts/portfolio/card',
						null,
						array(
							'cats'      => $starter_card['cats'],
							'cats_opts' => $starter_card['cats_opts'],
							'titles'    => $starter_card['titles'],
							'video'     => $starter_card['video'],
							'reveal'    => $starter_card['reveal'],
						)
					);
				}

				wp_reset_postdata();
				?>
			</div>
		</div>
	</section>
	<?php
}

/* ------------------------------------------------- Opzioni nel Personalizza */

add_action(
	'customize_register',
	function ( $wp_customize ) {
		$section = 'starter_options_portfolio';
		$def     = starter_portfolio_defaults();

		$wp_customize->add_section(
			$section,
			array(
				'title'       => __( 'Portfolio', 'slug-theme' ),
				'description' => __( 'Progetti del portfolio. L\'elenco si crea in una pagina normale con il blocco "Elenco progetti" (colonne, schede e card si scelgono nel blocco).', 'slug-theme' ),
				'panel'       => 'starter_options',
				'priority'    => 13,
			)
		);

		starter_customizer_heading( $wp_customize, 'starter_heading_portfolio_single', $section, __( 'Scheda del progetto', 'slug-theme' ), 20 );

		starter_customizer_checkbox( $wp_customize, 'starter_portfolio_meta', $section, __( 'Dati del progetto nella testata', 'slug-theme' ), $def['meta'], __( 'Servizi (le categorie del progetto), agenzie, partner e link al progetto online.', 'slug-theme' ), 21 );
		starter_customizer_checkbox( $wp_customize, 'starter_portfolio_related', $section, __( '"Altri progetti" a fine scheda', 'slug-theme' ), $def['related'], '', 22 );

		// Animazione tra una scheda e l'altra (es. cliccando "Altri progetti"). Dall'elenco al progetto vale la scelta del blocco "Elenco progetti".
		starter_customizer_heading( $wp_customize, 'starter_heading_portfolio_transition', $section, __( 'Passaggio tra i progetti', 'slug-theme' ), 80 );

		$colors = function_exists( 'starter_named_color_choices' ) ? starter_named_color_choices() : array( '' => '' );

		starter_customizer_select(
			$wp_customize,
			'starter_portfolio_single_transition',
			$section,
			__( 'Animazione', 'slug-theme' ),
			array(
				'nessuna'     => __( 'Nessuna animazione', 'slug-theme' ),
				'cover'       => __( 'La copertina si ingrandisce a tutto schermo', 'slug-theme' ),
				'tendina'     => __( 'Tendina dal basso', 'slug-theme' ),
				'dissolvenza' => __( 'Caricamento con dissolvenza', 'slug-theme' ),
			),
			'nessuna',
			'',
			81
		);

		$wp_customize->add_setting( 'starter_portfolio_single_text', array( 'default' => __( 'Caricamento', 'slug-theme' ), 'sanitize_callback' => 'sanitize_text_field' ) );
		$wp_customize->add_control(
			'starter_portfolio_single_text',
			array(
				'type'     => 'text',
				'section'  => $section,
				'label'    => __( 'Scritta del caricamento', 'slug-theme' ),
				'priority' => 82,
			)
		);

		starter_customizer_checkbox( $wp_customize, 'starter_portfolio_single_counter', $section, __( 'Percentuale da 0 a 100% al posto della scritta', 'slug-theme' ), false, '', 83 );
		starter_customizer_select( $wp_customize, 'starter_portfolio_single_bg', $section, __( 'Sfondo (dissolvenza e tendina)', 'slug-theme' ), $colors, '', '', 84 );
		starter_customizer_select( $wp_customize, 'starter_portfolio_single_fg', $section, __( 'Testo (dissolvenza e tendina)', 'slug-theme' ), $colors, '', '', 85 );
	},
	20
);

/* "Altri progetti" a fine scheda: le card si scelgono qui, con gli stessi nomi e valori dei campi del blocco "Elenco progetti" (tranne il layout, sempre a 3 colonne). */
function starter_portfolio_related_defaults() {
	return array(
		'stile_categorie'         => 'tab_sopra',
		'forma_categorie'         => 'singole',
		'riempimento_categorie'   => 'piene',
		'separatore_categorie'    => 'dot',
		'maiuscole_categorie'     => false,
		'colore_categorie'        => '',
		'colore_simbolo_categorie' => '',
		'posizione_categorie'     => 'dopo',
		'ordine_titoli'           => 'riassunto',
		'riassunto_mostra'        => true,
		'riassunto_tag'           => 'h3',
		'riassunto_dimensione'    => 'title-5',
		'riassunto_colore'        => '',
		'riassunto_colore_hover'  => '',
		'cliente_mostra'          => true,
		'cliente_tag'             => 'h4',
		'cliente_dimensione'      => 'small',
		'cliente_posizione'       => 'sotto',
		'cliente_colore'          => '',
		'cliente_colore_hover'    => '',
		'proporzione'             => '4-3',
		'animazione_logo'         => false,
		'logo_tinta'              => true,
		'video_hover'             => true,
	);
}

/* Legge un'opzione delle card degli "Altri progetti" (stessa firma di get_field, da passare a starter_portfolio_card_config()). */
function starter_portfolio_related_get( $name ) {
	$defaults = starter_portfolio_related_defaults();

	return get_theme_mod( 'starter_portfolio_rel_' . $name, array_key_exists( $name, $defaults ) ? $defaults[ $name ] : null );
}

add_action(
	'customize_register',
	function ( $wp_customize ) {
		$section = 'starter_options_portfolio';
		$def     = starter_portfolio_related_defaults();
		$colors  = starter_portfolio_color_choices();
		$hover   = starter_portfolio_hover_color_choices();
		$tags    = starter_portfolio_tag_choices();
		$sizes   = array();

		foreach ( starter_portfolio_font_size_choices() as $group ) {
			$sizes = array_merge( $sizes, $group );
		}

		$val = static function ( $name ) use ( $wp_customize, $def ) {
			$setting = $wp_customize->get_setting( 'starter_portfolio_rel_' . $name );

			return $setting ? $setting->value() : $def[ $name ];
		};
		$sel = static function ( $name, $label, $choices, $priority ) use ( $wp_customize, $section, $def ) {
			starter_customizer_select( $wp_customize, 'starter_portfolio_rel_' . $name, $section, $label, $choices, $def[ $name ], '', $priority );
		};
		$chk = static function ( $name, $label, $priority ) use ( $wp_customize, $section, $def ) {
			starter_customizer_checkbox( $wp_customize, 'starter_portfolio_rel_' . $name, $section, $label, $def[ $name ], '', $priority );
		};
		$when = static function ( $name, $callback ) use ( $wp_customize ) {
			$control = $wp_customize->get_control( 'starter_portfolio_rel_' . $name );

			if ( $control ) {
				$control->active_callback = $callback;
			}
		};
		$related_on = static function () {
			return (bool) starter_portfolio_opt( 'related' );
		};

		// Categorie.
		starter_customizer_heading( $wp_customize, 'starter_heading_portfolio_rel_cats', $section, __( 'Altri progetti: categorie', 'slug-theme' ), 40 );
		$sel(
			'stile_categorie',
			__( 'Categorie sulle card', 'slug-theme' ),
			array(
				'testo'     => __( 'Testo con simbolo', 'slug-theme' ),
				'tab_sotto' => __( 'Tab sotto l\'immagine', 'slug-theme' ),
				'tab_sopra' => __( 'Tab sopra l\'immagine', 'slug-theme' ),
				'nessuna'   => __( 'Nessuna', 'slug-theme' ),
			),
			41
		);
		$sel( 'forma_categorie', __( 'Tab sopra l\'immagine', 'slug-theme' ), array( 'singole' => __( 'Una tab per categoria', 'slug-theme' ), 'unica' => __( 'Un\'unica tab con il simbolo tra le categorie', 'slug-theme' ) ), 42 );
		$sel( 'riempimento_categorie', __( 'Tab piene o vuote', 'slug-theme' ), array( 'piene' => __( 'Piene', 'slug-theme' ), 'vuote' => __( 'Vuote (solo contorno)', 'slug-theme' ) ), 43 );
		$sel( 'separatore_categorie', __( 'Simbolo tra le categorie', 'slug-theme' ), starter_portfolio_separator_choices(), 44 );
		$sel( 'colore_simbolo_categorie', __( 'Colore del simbolo', 'slug-theme' ), $colors, 45 );
		$sel( 'posizione_categorie', __( 'Posizione rispetto al titolo', 'slug-theme' ), array( 'dopo' => __( 'Dopo il titolo', 'slug-theme' ), 'prima' => __( 'Prima del titolo', 'slug-theme' ) ), 46 );
		$chk( 'maiuscole_categorie', __( 'Categorie in maiuscolo', 'slug-theme' ), 47 );
		$sel( 'colore_categorie', __( 'Colore delle categorie', 'slug-theme' ), $colors, 48 );

		$when( 'stile_categorie', $related_on );
		$when( 'forma_categorie', static function () use ( $val, $related_on ) { return $related_on() && 'tab_sopra' === $val( 'stile_categorie' ); } );
		$when( 'riempimento_categorie', static function () use ( $val, $related_on ) { return $related_on() && in_array( $val( 'stile_categorie' ), array( 'tab_sopra', 'tab_sotto' ), true ); } );
		$sep_on = static function () use ( $val, $related_on ) {
			return $related_on() && ( 'testo' === $val( 'stile_categorie' ) || ( 'tab_sopra' === $val( 'stile_categorie' ) && 'unica' === $val( 'forma_categorie' ) ) );
		};
		$when( 'separatore_categorie', $sep_on );
		$when( 'colore_simbolo_categorie', $sep_on );
		$when( 'posizione_categorie', static function () use ( $val, $related_on ) { return $related_on() && in_array( $val( 'stile_categorie' ), array( 'testo', 'tab_sotto' ), true ); } );
		$when( 'maiuscole_categorie', static function () use ( $val, $related_on ) { return $related_on() && 'nessuna' !== $val( 'stile_categorie' ); } );
		$when( 'colore_categorie', static function () use ( $val, $related_on ) { return $related_on() && 'nessuna' !== $val( 'stile_categorie' ); } );

		// Riassunto e cliente.
		starter_customizer_heading( $wp_customize, 'starter_heading_portfolio_rel_titles', $section, __( 'Altri progetti: riassunto e cliente', 'slug-theme' ), 50 );
		$sel( 'ordine_titoli', __( 'Ordine', 'slug-theme' ), array( 'riassunto' => __( 'Prima il riassunto, poi il cliente', 'slug-theme' ), 'cliente' => __( 'Prima il cliente, poi il riassunto', 'slug-theme' ) ), 51 );
		$chk( 'riassunto_mostra', __( 'Mostra il riassunto', 'slug-theme' ), 52 );
		$sel( 'riassunto_tag', __( 'Riassunto: tag HTML', 'slug-theme' ), $tags, 53 );
		$sel( 'riassunto_dimensione', __( 'Riassunto: dimensione', 'slug-theme' ), $sizes, 54 );
		$sel( 'riassunto_colore', __( 'Riassunto: colore', 'slug-theme' ), $colors, 56 );
		$sel( 'riassunto_colore_hover', __( 'Riassunto: colore hover', 'slug-theme' ), $hover, 57 );
		$chk( 'cliente_mostra', __( 'Mostra il nome del cliente', 'slug-theme' ), 58 );
		$sel( 'cliente_tag', __( 'Cliente: tag HTML', 'slug-theme' ), $tags, 59 );
		$sel( 'cliente_dimensione', __( 'Cliente: dimensione', 'slug-theme' ), $sizes, 60 );
		$sel( 'cliente_posizione', __( 'Cliente: posizione', 'slug-theme' ), array( 'sotto' => __( 'Sotto l\'immagine', 'slug-theme' ), 'sopra' => __( 'Sopra l\'immagine', 'slug-theme' ) ), 61 );
		$sel( 'cliente_colore', __( 'Cliente: colore', 'slug-theme' ), $colors, 62 );
		$sel( 'cliente_colore_hover', __( 'Cliente: colore hover', 'slug-theme' ), $hover, 63 );

		$sum_on = static function () use ( $val, $related_on ) { return $related_on() && $val( 'riassunto_mostra' ); };
		$cli_on = static function () use ( $val, $related_on ) { return $related_on() && $val( 'cliente_mostra' ) && ! $val( 'animazione_logo' ); };
		$when( 'ordine_titoli', $related_on );
		$when( 'riassunto_mostra', $related_on );
		foreach ( array( 'riassunto_tag', 'riassunto_dimensione', 'riassunto_colore', 'riassunto_colore_hover' ) as $name ) {
			$when( $name, $sum_on );
		}
		$when( 'cliente_mostra', static function () use ( $val, $related_on ) { return $related_on() && ! $val( 'animazione_logo' ); } );
		foreach ( array( 'cliente_tag', 'cliente_dimensione', 'cliente_posizione', 'cliente_colore_hover' ) as $name ) {
			$when( $name, $cli_on );
		}
		$when( 'cliente_colore', static function () use ( $val, $related_on ) { return $related_on() && ( $val( 'animazione_logo' ) || $val( 'cliente_mostra' ) ); } );

		// Immagine.
		starter_customizer_heading( $wp_customize, 'starter_heading_portfolio_rel_media', $section, __( 'Altri progetti: immagine', 'slug-theme' ), 70 );
		$sel( 'proporzione', __( 'Proporzione delle immagini', 'slug-theme' ), array( '16-9' => '16:9', '3-2' => '3:2', '4-3' => '4:3', '1-1' => '1:1', '4-5' => __( '4:5 (verticale)', 'slug-theme' ) ), 71 );
		$chk( 'animazione_logo', __( 'Animazione logo (al passaggio il nome del cliente scorre sull\'immagine)', 'slug-theme' ), 72 );
		$chk( 'logo_tinta', __( 'Logo colore sfondo', 'slug-theme' ), 73 );
		$chk( 'video_hover', __( 'Video al passaggio del mouse', 'slug-theme' ), 74 );
		$when( 'proporzione', $related_on );
		$when( 'animazione_logo', $related_on );
		$when( 'logo_tinta', static function () use ( $val, $related_on ) { return $related_on() && $val( 'animazione_logo' ); } );
		$when( 'video_hover', $related_on );
	},
	21
);

/* Contenuto della pagina Portfolio creata dal modulo: intestazione e blocco "Elenco progetti". */
function starter_portfolio_page_content() {
	return starter_pg_block(
		'intestazione-pagina',
		array(
			'occhiello' => 'Portfolio: progetti e casi studio',
			'titolo'    => 'Portfolio',
			'testo'     => 'Una selezione dei nostri lavori: identità, siti web e progetti grafici. Scegli una categoria per filtrarli.',
		)
	) . starter_pg_block(
		'progetti',
		array(
			'colonne'    => '3',
			'per_pagina' => 12,
			'schede'     => '1',
			'conteggi'   => '1',
			'numeri'     => '1',
			'categorie'  => '1',
		)
	);
}

/* ------------------------------------------------- Contenuti di prova */

/**
 * Categorie e progetti di esempio: 3 schede complete con strutture diverse (identità visiva, sito web con video,
 * logo e grafica) e 6 più brevi per riempire l'archivio. Immagini segnaposto; i video sono quelli di esempio del tema.
 * Si può rilanciare senza duplicare; "Elimina i contenuti di prova" li toglie.
 *
 * @return int Numero di progetti creati.
 */
function starter_portfolio_demo() {
	if ( ! post_type_exists( 'portfolio' ) ) {
		return 0;
	}

	foreach ( array( 'Branding', 'Web design', 'Graphic design', 'Logo design' ) as $name ) {
		if ( ! term_exists( $name, 'portfolio_category' ) ) {
			$term = wp_insert_term( $name, 'portfolio_category' );

			if ( ! is_wp_error( $term ) ) {
				update_term_meta( $term['term_id'], '_starter_demo', 1 );
			}
		}
	}

	starter_module_ensure_page( 'portfolio' );

	$video_wide = starter_pg_video( 'demo-video-hero.mp4', 'Video orizzontale di esempio' );
	$video_tall = starter_pg_video( 'demo-video-verticale.mp4', 'Video verticale di esempio' );
	$n          = 0;

	/* Immagine segnaposto: $w/$h scelgono formato orizzontale, verticale o quadrato. */
	$img = static function ( $label, $color, $w = 1600, $h = 1000 ) {
		return (int) starter_demo_image( $label, $color, $w, $h );
	};

	$text   = static function ( $eyebrow, $title, $body, $layout = 'affiancato' ) {
		return starter_demo_block( 'progetto-testo', array( 'occhiello' => $eyebrow, 'titolo' => $title, 'testo' => $body, 'disposizione' => $layout ), starter_pg_keys( 'progetto-testo' ) );
	};
	$media  = static function ( $cols, $ratio, $items ) {
		$rows = array();

		foreach ( $items as $item ) {
			$rows[] = isset( $item['v'] )
				? array( 'elementi_tipo' => 'video', 'elementi_immagine' => '', 'elementi_video' => (int) $item['v'], 'elementi_copertina' => (int) ( isset( $item['p'] ) ? $item['p'] : 0 ), 'elementi_didascalia' => isset( $item['c'] ) ? $item['c'] : '' )
				: array( 'elementi_tipo' => 'immagine', 'elementi_immagine' => (int) $item['i'], 'elementi_video' => '', 'elementi_copertina' => '', 'elementi_didascalia' => isset( $item['c'] ) ? $item['c'] : '' );
		}

		return starter_demo_block( 'progetto-media', array( 'colonne' => (string) $cols, 'proporzione' => $ratio, 'elementi' => $rows ), starter_pg_keys( 'progetto-media' ) );
	};
	$credit = static function ( $rows ) {
		$voci = array();

		foreach ( $rows as $role => $name ) {
			$voci[] = array( 'voci_ruolo' => $role, 'voci_nome' => $name );
		}

		return starter_demo_block( 'progetto-crediti', array( 'titolo' => __( 'Collaboratori', 'slug-theme' ), 'voci' => $voci ), starter_pg_keys( 'progetto-crediti' ) );
	};

	$lorem_a = 'Il cliente cercava un\'immagine più chiara e coerente su ogni canale. Abbiamo ascoltato il team, studiato il mercato e definito insieme una direzione semplice da usare ogni giorno.' . "\n\n" . 'Questo testo è un segnaposto: sostituiscilo con la storia del progetto, il problema di partenza e come l\'avete affrontato.';
	$lorem_b = 'Ogni scelta nasce da un principio: meno elementi, più cura. Forme essenziali, una palette ristretta e un sistema di regole che permette di crescere senza perdere riconoscibilità.';
	$lorem_c = 'Il risultato è un sistema flessibile, applicato su stampa, digitale e spazi. I numeri confermano il cambio di passo: più riconoscibilità, più contatti, un team che lavora con strumenti chiari.';

	$projects = array();

	// 1) Identità visiva: ritmo a immagini larghe, coppie e terne (stile caso studio).
	$projects['lumen-identita-visiva'] = array(
		'title'   => 'Lumen Studio',
		'cats'    => array( 'Branding', 'Logo design' ),
		'excerpt' => 'Identità visiva per uno studio di illuminazione',
		'cover'   => array( 'Lumen', 0, 1600, 1000 ), // Le copertine hanno proporzioni diverse, così si vedono bene masonry, blocchi e sfalsata.
		'content' => function () use ( $img, $text, $media, $credit, $lorem_a, $lorem_b, $lorem_c ) {
			return $text( 'Contesto', 'Una luce nuova per un marchio storico', $lorem_a )
				. $media( 1, 'originale', array( array( 'i' => $img( 'Lumen 1', 1 ) ) ) )
				. $media( 3, '4-5', array( array( 'i' => $img( 'Lumen 2', 2, 1000, 1250 ) ), array( 'i' => $img( 'Lumen 3', 3, 1000, 1250 ) ), array( 'i' => $img( 'Lumen 4', 4, 1000, 1250 ) ) ) )
				. $text( 'Idea', 'Equilibrio e precisione', $lorem_b )
				. $media( 2, '1-1', array( array( 'i' => $img( 'Lumen 5', 5, 1200, 1200 ), 'c' => 'Marchio e variante compatta' ), array( 'i' => $img( 'Lumen 6', 0, 1200, 1200 ), 'c' => 'Palette e tipografia' ) ) )
				. $text( 'Risultato', 'Un\'identità che cambia insieme all\'azienda', $lorem_c )
				. $media( 1, 'originale', array( array( 'i' => $img( 'Lumen 7', 1 ) ) ) )
				. $media( 2, 'originale', array( array( 'i' => $img( 'Lumen 8', 2, 1200, 900 ) ), array( 'i' => $img( 'Lumen 9', 3, 1200, 900 ) ) ) )
				. $media( 1, 'originale', array( array( 'i' => $img( 'Lumen 10', 4 ) ) ) )
				. $media( 2, 'originale', array( array( 'i' => $img( 'Lumen 11', 5, 1200, 900 ) ), array( 'i' => $img( 'Lumen 12', 0, 1200, 900 ) ) ) )
				. $credit( array( 'Direzione creativa' => 'Anna Rossi', 'Art direction' => 'Marco Bianchi', 'Strategia e testi' => 'Giulia Verdi', 'Illustrazioni' => 'Luca Neri', 'Fotografia' => 'Sara Gialli' ) );
		},
	);

	// 2) Sito web: copertina con video, video e immagini mescolati.
	$projects['orbita-sito-web'] = array(
		'title'   => 'Orbita Software',
		'cats'    => array( 'Web design' ),
		'excerpt' => 'Sito web per un\'azienda di software',
		'cover'   => array( 'Orbita', 2, 1000, 1400 ),
		'video'   => $video_wide,
		'content' => function () use ( $img, $text, $media, $credit, $video_wide, $video_tall, $lorem_a, $lorem_b ) {
			return $text( 'Contesto', 'Un sito che racconta un prodotto complesso', $lorem_a, 'centrato' )
				. $media( 1, '16-9', array( array( 'v' => $video_wide, 'p' => $img( 'Orbita video', 1 ), 'c' => 'Home page: animazioni in scroll' ) ) )
				. $text( 'Approccio', 'Chiarezza prima di tutto', $lorem_b )
				. $media( 2, '4-5', array( array( 'v' => $video_tall, 'p' => $img( 'Orbita mobile', 3, 1000, 1250 ), 'c' => 'Versione mobile' ), array( 'i' => $img( 'Orbita 2', 4, 1000, 1250 ), 'c' => 'Pagina prodotto' ) ) )
				. $media( 3, '16-9', array( array( 'i' => $img( 'Orbita 3', 5 ) ), array( 'i' => $img( 'Orbita 4', 0 ) ), array( 'i' => $img( 'Orbita 5', 1 ) ) ) )
				. $media( 1, 'originale', array( array( 'i' => $img( 'Orbita 6', 2 ) ) ) )
				. $credit( array( 'Web design' => 'Anna Rossi', 'Sviluppo' => 'Davide Blu', 'Copy' => 'Giulia Verdi', 'Animazioni' => 'Luca Neri' ) );
		},
	);

	// 3) Logo e grafica: griglie quadrate e un video verticale.
	$projects['nord-logo-e-grafica'] = array(
		'title'   => 'Nord Caffè',
		'cats'    => array( 'Logo design', 'Graphic design' ),
		'excerpt' => 'Logo e grafica per una caffetteria',
		'cover'   => array( 'Nord', 3, 1200, 1200 ),
		'content' => function () use ( $img, $text, $media, $credit, $video_tall, $lorem_a, $lorem_c ) {
			return $text( 'Obiettivo', 'Un marchio che si legge anche da lontano', $lorem_a )
				. $media( 2, '1-1', array( array( 'i' => $img( 'Nord 1', 0, 1200, 1200 ) ), array( 'i' => $img( 'Nord 2', 1, 1200, 1200 ) ), array( 'i' => $img( 'Nord 3', 2, 1200, 1200 ) ), array( 'i' => $img( 'Nord 4', 3, 1200, 1200 ) ) ) )
				. $media( 3, '1-1', array( array( 'i' => $img( 'Nord 5', 4, 1200, 1200 ) ), array( 'i' => $img( 'Nord 6', 5, 1200, 1200 ) ), array( 'i' => $img( 'Nord 7', 0, 1200, 1200 ) ) ) )
				. $text( 'Risultato', 'Dal bancone all\'insegna', $lorem_c, 'centrato' )
				. $media( 2, '4-5', array( array( 'v' => $video_tall, 'p' => $img( 'Nord video', 1, 1000, 1250 ) ), array( 'i' => $img( 'Nord 8', 2, 1000, 1250 ) ) ) )
				. $credit( array( 'Logo e grafica' => 'Anna Rossi', 'Illustrazioni' => 'Luca Neri', 'Insegna' => 'Officina Verde' ) );
		},
	);

	// Altri 6, più brevi, per riempire archivio e filtri.
	$short = array(
		'aurora-naming-e-logo'    => array( 'Aurora — Naming e logo', array( 'Logo design', 'Branding' ), 'Naming, Logo' ),
		'casa-verde-e-commerce'   => array( 'Casa Verde — E-commerce', array( 'Web design' ), 'Negozio online, UX' ),
		'rete-campagna-social'    => array( 'Rete — Campagna social', array( 'Graphic design' ), 'Campagna, Social kit' ),
		'mare-identita-ristorante' => array( 'Mare — Identità ristorante', array( 'Branding', 'Graphic design' ), 'Identità, Menu' ),
		'pixel-landing-page'      => array( 'Pixel — Landing page', array( 'Web design' ), 'Landing page, Animazioni' ),
		'sigma-marchio'           => array( 'Sigma — Marchio', array( 'Logo design' ), 'Marchio, Linee guida' ),
	);
	$short_sizes = array( array( 1500, 1000 ), array( 1100, 1500 ), array( 1600, 900 ), array( 1000, 1250 ), array( 1600, 1100 ), array( 1000, 1500 ) );
	$i           = 0;

	foreach ( $short as $slug => $data ) {
		$ci                = $i % 6;
		$projects[ $slug ] = array(
			'title'   => explode( ' — ', $data[0] )[0],
			'cats'    => $data[1],
			'excerpt' => $data[2] . ' per ' . explode( ' — ', $data[0] )[0],
			'cover'   => array_merge( array( explode( ' — ', $data[0] )[0], $ci ), $short_sizes[ $i % count( $short_sizes ) ] ),
			'content' => function () use ( $img, $text, $media, $lorem_b, $ci ) {
				return $text( 'Progetto', 'Una soluzione su misura', $lorem_b, 'centrato' )
					. $media( 2, '4-3', array( array( 'i' => $img( 'Dettaglio 1', $ci, 1200, 900 ) ), array( 'i' => $img( 'Dettaglio 2', $ci + 1, 1200, 900 ) ) ) );
			},
		);
		++$i;
	}

	$offset = 0;

	foreach ( $projects as $slug => $project ) {
		$existing = get_posts(
			array(
				'name'           => $slug,
				'post_type'      => 'portfolio',
				'post_status'    => 'any',
				'posts_per_page' => 1,
				'fields'         => 'ids',
			)
		);

		if ( $existing ) {
			continue;
		}

		$cover = $img( $project['cover'][0], $project['cover'][1], $project['cover'][2], $project['cover'][3] );
		$id    = wp_insert_post(
			array(
				'post_type'    => 'portfolio',
				'post_status'  => 'publish',
				'post_name'    => $slug,
				'post_title'   => $project['title'],
				'post_excerpt' => $project['excerpt'],
				'post_content' => wp_slash( call_user_func( $project['content'] ) ),
				// Il primo dell'elenco è il più recente: l'archivio parte da lì.
				'post_date'    => gmdate( 'Y-m-d H:i:s', current_time( 'timestamp' ) - ( $offset * MINUTE_IN_SECONDS ) ), // phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested
			)
		);
		++$offset;

		if ( ! $id || is_wp_error( $id ) ) {
			continue;
		}

		update_post_meta( $id, '_starter_demo', 1 );
		wp_set_object_terms( $id, $project['cats'], 'portfolio_category' );

		if ( $cover ) {
			set_post_thumbnail( $id, $cover );
		}

		if ( ! empty( $project['video'] ) ) {
			update_field( 'field_stpd_video', (int) $project['video'], $id );
		}

		// Testo sotto il titolo (contesto editoriale): un breve esempio con il nome del cliente.
		update_field( 'field_stpd_testo', sprintf( "%s ci ha chiesto un progetto chiaro e coerente su ogni canale.\nAbbiamo lavorato insieme per definire una direzione semplice da usare ogni giorno: ecco come è andata.", $project['title'] ), $id );

		++$n;
	}

	return $n;
}

/* ------------------------------------------------- Anteprima della testata nell'editor */

/* Marcatore del blocco anteprima: sta solo nell'editor. Si aggiunge all'apertura del progetto e si toglie al salvataggio, così il contenuto nel database resta pulito. */
function starter_portfolio_preview_marker() {
	return '<!-- wp:starter-theme/progetto-testata {"lock":{"move":true,"remove":true}} /-->';
}

add_filter(
	'rest_prepare_portfolio',
	static function ( $response, $post, $request ) {
		if ( 'edit' !== $request->get_param( 'context' ) ) {
			return $response;
		}
		$data = $response->get_data();
		if ( isset( $data['content']['raw'] ) && false === strpos( $data['content']['raw'], 'wp:starter-theme/progetto-testata' ) ) {
			$data['content']['raw'] = starter_portfolio_preview_marker() . "\n\n" . $data['content']['raw'];
			$response->set_data( $data );
		}
		return $response;
	},
	10,
	3
);

add_filter(
	'wp_insert_post_data',
	static function ( $data ) {
		if ( 'portfolio' === $data['post_type'] && false !== strpos( $data['post_content'], 'wp:starter-theme/progetto-testata' ) ) {
			$data['post_content'] = ltrim( preg_replace( '#<!-- wp:starter-theme/progetto-testata\b.*?/-->\s*#s', '', $data['post_content'] ) );
		}
		return $data;
	}
);
