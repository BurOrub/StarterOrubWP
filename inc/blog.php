<?php
/**
 * Blog: opzioni (Opzioni → Blog), meta degli articoli, intestazioni di sezione, aside.
 *
 * Le stesse funzioni servono la pagina Blog (home.php), gli archivi di categoria/tag/autore/data (archive.php),
 * la ricerca (search.php) e l'articolo (single.php): cambiando un'opzione cambia ovunque.
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Valori predefiniti delle opzioni del blog (le chiavi sono i nomi dei theme_mod senza prefisso "starter_blog_"). */
function starter_blog_defaults() {
	return array(
		'aside_list'     => true,  // Aside sulla pagina Blog, gli archivi e la ricerca.
		'aside_single'   => true,  // Aside negli articoli.
		'columns'        => 3,     // Colonne della griglia (1 = elenco: immagine a sinistra, testo a destra).
		'featured'       => false, // Primo articolo in evidenza (a tutta larghezza) sulla prima pagina di Blog e archivi.
		'featured_post'  => 0,     // Articolo scelto a mano per l'evidenza della pagina Blog (0 = automatico).
		'filters'        => false, // Filtri per categoria (con il numero degli articoli) sopra l'elenco, solo nella pagina Blog.
		'list_date'      => true,  // Nelle card: data, autore, categorie, tag.
		'list_author'    => false,
		'author_link'    => false, // Nome dell'autore cliccabile (archivio dei suoi articoli), in card e articolo.
		'list_cats'      => true,
		'list_tags'      => false,
		'single_date'    => true,  // Nell'articolo.
		'single_author'  => true,
		'single_cats'    => true,
		'single_tags'    => true,
		'author_box'     => true,  // Riquadro autore a fine articolo.
		'related'        => true,  // "Continua a leggere" a fine articolo.
		'share'          => false, // Pulsanti Condividi.
		'aside_sticky'   => false, // Aside che resta ferma mentre si scorre (top 20px).
		'toc'            => false, // Sommario (indice dei titoli) a inizio articolo.
	);
}

/* Valore di un'opzione del blog. */
function starter_blog_opt( $key ) {
	$defaults = starter_blog_defaults();
	$default  = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';
	$value    = get_theme_mod( "starter_blog_{$key}", $default );

	return is_bool( $default ) ? (bool) $value : $value;
}

/* Vero se l'aside va mostrato in questa pagina (articolo oppure elenco). */
function starter_blog_has_aside() {
	return is_singular( 'post' ) ? starter_blog_opt( 'aside_single' ) : starter_blog_opt( 'aside_list' );
}

/* Classi sul body: layout senza aside e numero di colonne della griglia. */
add_filter(
	'body_class',
	function ( $classes ) {
		if ( is_home() || is_archive() || is_search() || is_singular( 'post' ) ) {
			$classes[] = 'blog-cols-' . (int) starter_blog_opt( 'columns' );

			if ( ! starter_blog_has_aside() ) {
				$classes[] = 'blog-no-aside';
			} elseif ( starter_blog_opt( 'aside_sticky' ) ) {
				$classes[] = 'blog-aside-sticky';
			}
		}

		return $classes;
	}
);

/**
 * Riga dei dati dell'articolo: a sinistra data e autore, a destra le categorie come etichette (solo quelli attivi
 * nelle opzioni). I tag non stanno qui: nelle card vanno sotto il testo, prima di "Leggi tutto" (starter_blog_card_tags);
 * nell'articolo a fine testo.
 *
 * @param string $context 'list' (card) o 'single' (articolo).
 * @param string $part    'all', 'main' (solo data e autore: etichetta sopra l'immagine della card) o 'cats' (solo categorie).
 */
function starter_blog_meta( $context = 'list', $part = 'all' ) {
	$main    = array();
	$cats    = '';
	$is_post = 'post' === get_post_type();

	if ( starter_blog_opt( "{$context}_date" ) ) {
		$main[] = '<span class="posted-on"><time datetime="' . esc_attr( get_the_date( 'c' ) ) . '">' . esc_html( get_the_date() ) . '</time></span>';
	}

	if ( $is_post && starter_blog_opt( "{$context}_author" ) ) {
		/* translators: %s: nome dell'autore. */
		$author = esc_html( get_the_author() );

		if ( starter_blog_opt( 'author_link' ) ) {
			$author = '<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" rel="author">' . $author . '</a>';
		}

		$main[] = '<span class="byline">' . sprintf( esc_html__( 'di %s', 'slug-theme' ), $author ) . '</span>';
	}

	if ( $is_post && starter_blog_opt( "{$context}_cats" ) && has_category() ) {
		foreach ( get_the_category() as $category ) {
			$cats .= '<a class="cat-chip" href="' . esc_url( get_category_link( $category ) ) . '" rel="category tag">' . esc_html( $category->name ) . '</a>';
		}
	}

	if ( 'main' === $part ) {
		$cats = '';
	} elseif ( 'cats' === $part ) {
		$main = array();
	}

	if ( ! $main && ! $cats ) {
		return;
	}

	echo '<div class="entry-meta' . ( 'main' === $part ? ' entry-meta--tab' : '' ) . '">';

	if ( $main ) {
		echo '<span class="entry-meta__main">' . implode( ' <span class="entry-meta__sep" aria-hidden="true">&middot;</span> ', $main ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	if ( $cats ) {
		echo '<span class="cat-links">' . $cats . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	echo '</div>';
}

/* Tag nelle card (se attivi in Opzioni → Blog): etichette sotto il testo, prima di "Leggi tutto". */
function starter_blog_card_tags() {
	if ( 'post' !== get_post_type() || ! starter_blog_opt( 'list_tags' ) || ! has_tag() ) {
		return;
	}

	the_tags( '<div class="tags-links">', '<span class="tags-links__sep" aria-hidden="true">&middot;</span>', '</div>' );
}

/* Vero se in questa pagina il primo articolo va in evidenza: opzione attiva, elenco (Blog o archivi, non la ricerca), prima pagina. */
function starter_blog_featured_active() {
	return starter_blog_opt( 'featured' ) && ( is_home() || is_archive() ) && ! is_search() && ! is_paged();
}

/* Pagina dove l'elenco ha i filtri e l'articolo in evidenza scelto: la pagina Blog e le pagine delle categorie. */
function starter_blog_is_filter_page() {
	return is_home() || is_category();
}

/* Categoria aperta (pagina di una categoria): id del termine, oppure 0 (pagina Blog). */
function starter_blog_filter_category() {
	return is_category() ? (int) get_queried_object_id() : 0;
}

/**
 * Articolo in evidenza della pagina Blog e delle categorie, nell'ordine: (1) quello scelto in Opzioni → Blog; (2) il più recente con categoria o tag
 * "in-evidenza"; (3) l'ultimo articolo. Se è attivo un filtro per categoria vale solo ciò che appartiene a quella categoria.
 *
 * @return WP_Post|null
 */
function starter_blog_featured_post() {
	static $cache = array();

	$cat = starter_blog_filter_category();

	if ( array_key_exists( $cat, $cache ) ) {
		return $cache[ $cat ];
	}

	$base = array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => 1,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	);

	if ( $cat ) {
		$base['cat'] = $cat;
	}

	$found  = null;
	$chosen = (int) starter_blog_opt( 'featured_post' );

	if ( $chosen ) {
		$posts = get_posts( array_merge( $base, array( 'p' => $chosen ) ) );
		$found = $posts ? $posts[0] : null;
	}

	if ( ! $found ) {
		$posts = get_posts(
			array_merge(
				$base,
				array(
					'tax_query' => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
						'relation' => 'OR',
						array( 'taxonomy' => 'category', 'field' => 'slug', 'terms' => 'in-evidenza' ),
						array( 'taxonomy' => 'post_tag', 'field' => 'slug', 'terms' => 'in-evidenza' ),
					),
				)
			)
		);
		$found = $posts ? $posts[0] : null;
	}

	if ( ! $found ) {
		$posts = get_posts( $base );
		$found = $posts ? $posts[0] : null;
	}

	$cache[ $cat ] = $found;

	return $found;
}

/* Pagina Blog e categorie: con l'evidenza attiva l'articolo in evidenza esce dalla griglia (si vede sopra, una volta sola). */
add_action(
	'pre_get_posts',
	function ( $query ) {
		if ( is_admin() || ! $query->is_main_query() || ! ( $query->is_home() || $query->is_category() ) ) {
			return;
		}

		if ( starter_blog_opt( 'featured' ) ) {
			$featured = starter_blog_featured_post();

			if ( $featured ) {
				$query->set( 'post__not_in', array( (int) $featured->ID ) );
			}
		}
	}
);

/* Indirizzo della pagina Blog (senza filtri). */
function starter_blog_page_url() {
	$page = (int) get_option( 'page_for_posts' );

	return $page ? get_permalink( $page ) : home_url( '/' );
}

/* Filtri per categoria sopra l'elenco: stesso aspetto dei filtri "classici" del Portfolio, con il numero degli articoli in un cerchio. Pagina Blog e pagine delle categorie: ogni voce porta alla pagina della categoria (che ha gli stessi filtri e lo stesso layout). */
function starter_blog_filters() {
	if ( ! starter_blog_is_filter_page() || ! starter_blog_opt( 'filters' ) ) {
		return;
	}

	$terms = get_categories( array( 'hide_empty' => true ) );

	if ( ! $terms ) {
		return;
	}

	$base   = starter_blog_page_url();
	$active = starter_blog_filter_category();
	$total  = (int) wp_count_posts( 'post' )->publish;
	$items  = array( array( $base, __( 'Tutti', 'slug-theme' ), $total, ! $active ) );

	foreach ( $terms as $term ) {
		$items[] = array( get_category_link( $term ), $term->name, (int) $term->count, (int) $term->term_id === $active );
	}

	echo '<nav class="blog-tabs" aria-label="' . esc_attr__( 'Categorie del blog', 'slug-theme' ) . '"><ul class="blog-tabs__list">';

	foreach ( $items as $item ) {
		printf(
			'<li><a class="blog-tabs__link%1$s" href="%2$s"%3$s>%4$s <span class="blog-tabs__count">%5$d</span></a></li>',
			$item[3] ? ' is-active' : '',
			esc_url( $item[0] ),
			$item[3] ? ' aria-current="page"' : '',
			esc_html( $item[1] ),
			(int) $item[2]
		);
	}

	echo '</ul></nav>';
}

/**
 * Intestazione della sezione corrente: occhiello (che sezione è), titolo (il nome della sezione) e introduzione.
 * Categoria "Notizie" → occhiello "Categoria", titolo "Notizie"; ricerca → "Risultati di ricerca" / “parola”.
 *
 * @return array{eyebrow:string,title:string,intro:string}
 */
function starter_blog_section_header() {
	$header = array(
		'eyebrow'    => '',
		'title'      => '',
		'intro'      => '',
		'eyebrow_h1' => false, // true = l'occhiello è l'H1 e il titolo un H2 (pagina Blog).
		'live'       => array(), // pagina Blog: occhiello e titolo modificabili dal frontend (plugin Starter Live).
	);

	if ( is_search() ) {
		$header['eyebrow'] = __( 'Risultati di ricerca', 'slug-theme' );
		/* translators: %s: termine cercato. */
		$header['title'] = sprintf( __( '“%s”', 'slug-theme' ), get_search_query() );
	} elseif ( is_category() ) {
		$header['eyebrow'] = __( 'Categoria', 'slug-theme' );
		$header['title']   = single_cat_title( '', false );
	} elseif ( is_tag() ) {
		$header['eyebrow'] = __( 'Tag', 'slug-theme' );
		$tag_name          = single_tag_title( '', false ); // Solo visivo: iniziale maiuscola, il nome salvato non cambia.
		$header['title']   = mb_strtoupper( mb_substr( $tag_name, 0, 1 ) ) . mb_substr( $tag_name, 1 );
	} elseif ( is_author() ) {
		$header['eyebrow'] = __( 'Autore', 'slug-theme' );
		$header['title']   = get_the_author_meta( 'display_name', (int) get_queried_object_id() );
	} elseif ( is_date() ) {
		$header['eyebrow'] = __( 'Archivio', 'slug-theme' );
		$header['title']   = is_day() ? get_the_date() : ( is_month() ? get_the_date( 'F Y' ) : get_the_date( 'Y' ) );
	} elseif ( is_tax() ) {
		$tax               = get_queried_object() ? get_taxonomy( get_queried_object()->taxonomy ) : null;
		$header['eyebrow'] = $tax ? $tax->labels->singular_name : '';
		$header['title']   = single_term_title( '', false );
	} elseif ( is_post_type_archive() ) {
		$header['title'] = post_type_archive_title( '', false );
	} elseif ( is_home() ) {
		$page_id         = (int) get_option( 'page_for_posts' );
		// L'occhiello si può cambiare dal frontend (Starter Live): è salvato come campo della pagina Blog, altrimenti vale il testo di default.
		$header['eyebrow']    = ( $page_id && metadata_exists( 'post', $page_id, 'starter_eyebrow' ) ) ? (string) get_post_meta( $page_id, 'starter_eyebrow', true ) : (string) apply_filters( 'starter_blog_home_eyebrow', __( 'Blog: guide, consigli e novità', 'slug-theme' ) );
		$header['live']       = $page_id ? array( 'post' => $page_id, 'title' => true, 'lead' => true ) : array();
		$header['eyebrow_h1'] = true;
		$header['title']      = $page_id ? get_the_title( $page_id ) : __( 'Blog', 'slug-theme' );
		$header['intro'] = $page_id ? (string) apply_filters( 'the_content', get_post_field( 'post_content', $page_id ) ) : '';
	}

	// Categoria, tag o altra tassonomia: occhiello (campo extra del termine, altrimenti il testo di default) e titolo modificabili dal frontend.
	$starter_term = ( is_category() || is_tag() || is_tax() ) ? get_queried_object() : null;

	if ( $starter_term instanceof WP_Term ) {
		if ( metadata_exists( 'term', $starter_term->term_id, 'starter_eyebrow' ) ) {
			$header['eyebrow'] = (string) get_term_meta( $starter_term->term_id, 'starter_eyebrow', true );
		}

		$header['live'] = array( 'term' => $starter_term->term_id, 'tax' => $starter_term->taxonomy, 'title' => true, 'lead' => true );
	}

	if ( ! $header['intro'] && ( is_category() || is_tag() || is_tax() || is_author() ) ) {
		$header['intro'] = (string) get_the_archive_description();
	}

	return $header;
}

/* Stampa l'intestazione della sezione (stessa su Blog, archivi e ricerca): lo stesso titolo delle pagine con i blocchi. */
function starter_blog_render_header() {
	$header = starter_blog_section_header();

	starter_page_header(
		array(
			'eyebrow'    => $header['eyebrow'],
			'title'      => $header['title'],
			'lead_html'  => $header['intro'],
			'eyebrow_h1' => $header['eyebrow_h1'],
			'live'       => $header['live'],
		)
	);
}

/* Pulsante che apre l'aside su tablet/mobile (non stampato se l'aside è spento). */
function starter_blog_aside_toggle() {
	if ( ! starter_blog_has_aside() ) {
		return;
	}
	?>
	<button type="button" class="sidebar-toggle button button--outline" id="sidebar-toggle" aria-expanded="false" aria-controls="blog-sidebar">
		<?php echo starter_flip_text( __( 'Filtri e ricerca', 'slug-theme' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</button>
	<?php
}

/* Paginazione dell'elenco. */
function starter_blog_pagination() {
	the_posts_pagination(
		array(
			'mid_size'  => 2,
			'prev_text' => __( '← Precedenti', 'slug-theme' ),
			'next_text' => __( 'Successivi →', 'slug-theme' ),
		)
	);
}

/* Riquadro autore a fine articolo (avatar, nome, biografia dal profilo dell'utente). */
function starter_blog_author_box() {
	if ( ! starter_blog_opt( 'author_box' ) ) {
		return;
	}

	$author_id = (int) get_the_author_meta( 'ID' );
	$bio       = trim( (string) get_the_author_meta( 'description' ) );
	?>
	<aside class="blog-author-box">
		<div class="blog-author-box__avatar"><?php echo get_avatar( $author_id, 96, '', '', array( 'class' => 'blog-author-box__img' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		<div class="blog-author-box__text">
			<p class="blog-author-box__label eyebrow"><?php esc_html_e( 'A cura di', 'slug-theme' ); ?></p>
			<p class="blog-author-box__name title-h4"><a href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>"><?php the_author(); ?></a></p>
			<?php if ( '' !== $bio ) : ?>
				<p class="blog-author-box__bio has-small-font-size"><?php echo esc_html( $bio ); ?></p>
			<?php endif; ?>
		</div>
	</aside>
	<?php
}

/* "Continua a leggere": 3 altri articoli (stessa categoria se ce ne sono, altrimenti i più recenti). */
function starter_blog_related() {
	if ( ! starter_blog_opt( 'related' ) ) {
		return;
	}

	$args = array(
		'post_type'           => 'post',
		'posts_per_page'      => 3,
		'post__not_in'        => array( get_the_ID() ),
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	);

	$cats = wp_get_post_categories( get_the_ID() );

	if ( $cats ) {
		$args['category__in'] = $cats;
	}

	$related = new WP_Query( $args );

	if ( ! $related->have_posts() && $cats ) {
		unset( $args['category__in'] );
		$related = new WP_Query( $args );
	}

	if ( ! $related->have_posts() ) {
		return;
	}
	?>
	<section class="blog-related">
		<h2 class="blog-related__title title-h3"><?php esc_html_e( 'Continua a leggere', 'slug-theme' ); ?></h2>
		<div class="blog-grid">
			<?php
			while ( $related->have_posts() ) :
				$related->the_post();
				get_template_part( 'template-parts/content/card' );
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</section>
	<?php
}

/**
 * Sommario dell'articolo (Opzioni → Blog → "Sommario"): indice dei titoli H2 (con gli H3 come sottovoci) in un riquadro
 * prima del primo H2, con link che scorrono al titolo. Si crea da solo dal contenuto; i titoli senza id ne ricevono uno.
 * Compare solo negli articoli e solo se ci sono almeno due titoli.
 */
function starter_blog_toc_filter( $content ) {
	if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() || ! starter_blog_opt( 'toc' ) ) {
		return $content;
	}

	if ( ! preg_match_all( '#<h([23])([^>]*)>(.*?)</h\1>#is', $content, $matches, PREG_SET_ORDER ) ) {
		return $content;
	}

	$used  = array();
	$items = array();
	$h2s   = 0;

	foreach ( $matches as $match ) {
		$label = trim( wp_strip_all_tags( $match[3] ) );

		if ( '' === $label ) {
			continue;
		}

		if ( preg_match( '#\sid=["\']([^"\']+)["\']#i', $match[2], $id_match ) ) {
			$id = $id_match[1];
		} else {
			$base = sanitize_title( $label ) ?: 'sezione';
			$id   = $base;
			$n    = 2;

			while ( in_array( $id, $used, true ) ) {
				$id = $base . '-' . $n++;
			}

			$replacement = '<h' . $match[1] . $match[2] . ' id="' . esc_attr( $id ) . '">' . $match[3] . '</h' . $match[1] . '>';
			$content     = preg_replace( '#' . preg_quote( $match[0], '#' ) . '#', $replacement, $content, 1 );
		}

		$used[] = $id;

		if ( '2' === $match[1] ) {
			++$h2s;
		}

		$items[] = array( (int) $match[1], $id, $label );
	}

	if ( $h2s < 1 || count( $items ) < 2 ) {
		return $content;
	}

	// Albero: ogni H2 con le sue sottovoci H3.
	$tree = array();

	foreach ( $items as $item ) {
		if ( 2 === $item[0] ) {
			$tree[] = array( $item, array() );
		} elseif ( $tree ) {
			$tree[ count( $tree ) - 1 ][1][] = $item;
		}
	}

	$link = function ( $item ) {
		return '<a href="#' . esc_attr( $item[1] ) . '">' . esc_html( $item[2] ) . '</a>';
	};

	// <details>: il titolo è anche il pulsante che apre e chiude il sommario (funziona senza JS e da tastiera).
	$html  = '<nav class="blog-toc" aria-label="' . esc_attr__( 'Sommario', 'slug-theme' ) . '"><details open>';
	$html .= '<summary class="blog-toc__title title-h5"><span>' . esc_html__( 'Sommario', 'slug-theme' ) . '</span><span class="blog-toc__toggle"><span class="blog-toc__label blog-toc__label--close">' . esc_html__( 'Chiudi', 'slug-theme' ) . '</span><span class="blog-toc__label blog-toc__label--open">' . esc_html__( 'Apri', 'slug-theme' ) . '</span><span class="blog-toc__chev" aria-hidden="true"></span></span></summary><ol class="blog-toc__list">';

	foreach ( $tree as $node ) {
		$html .= '<li>' . $link( $node[0] );

		if ( $node[1] ) {
			$html .= '<ol class="blog-toc__sub">';

			foreach ( $node[1] as $sub ) {
				$html .= '<li>' . $link( $sub ) . '</li>';
			}

			$html .= '</ol>';
		}

		$html .= '</li>';
	}

	$html .= '</ol></details></nav>';

	// Prima del primo H2.
	$pos = stripos( $content, '<h2' );

	return false === $pos ? $html . $content : substr( $content, 0, $pos ) . $html . substr( $content, $pos );
}
add_filter( 'the_content', 'starter_blog_toc_filter', 9 );
