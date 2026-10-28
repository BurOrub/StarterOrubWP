<?php
/** Funzioni di supporto condivise dai blocchi del tema. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Legge un campo di un contenuto (articolo o termine) o delle opzioni; senza il plugin Starter Blocks ripiega sul meta grezzo. */
function starter_field( $name, $post_id = null, $option = false ) {
	$target = $option ? 'option' : ( $post_id ? $post_id : get_the_ID() );

	if ( function_exists( 'starter_meta_get' ) ) {
		return starter_meta_get( $name, $target );
	}

	if ( $option ) {
		return get_option( 'options_' . $name, '' );
	}

	return is_string( $target ) && 0 === strpos( $target, 'term_' ) ? get_term_meta( (int) substr( $target, 5 ), $name, true ) : get_post_meta( (int) $target, $name, true );
}

/* Campo del blocco che si sta disegnando (plugin Starter Blocks). $raw = valore grezzo salvato. */
function starter_block_field( $name, $raw = false ) {
	return function_exists( 'starter_bf' ) ? starter_bf( $name, $raw ) : '';
}

/* Attributi HTML di un campo "Link" (array url/title/target). */
function starter_link_attrs( $link, $class = '' ) {
	if ( ! is_array( $link ) || empty( $link['url'] ) ) {
		return '';
	}

	$attrs = 'href="' . esc_url( $link['url'] ) . '"';

	if ( ! empty( $link['target'] ) ) {
		$attrs .= ' target="' . esc_attr( $link['target'] ) . '" rel="noopener"';
	}

	if ( $class ) {
		$attrs .= ' class="' . esc_attr( $class ) . '"';
	}

	return $attrs;
}

/* Stili di pulsante tra cui scegliere nei blocchi: sono le 4 varianti di Opzioni → Pulsanti (Pieno 1 e Contorno 1 = i pulsanti "principale" e "secondario" del sito). */
function starter_button_style_choices( $with_light = false ) {
	$choices = array(
		''          => __( 'Predefinito del blocco', 'slug-theme' ),
		'pieno-1'   => __( 'Pieno 1', 'slug-theme' ),
		'contorno-1' => __( 'Contorno 1', 'slug-theme' ),
		'pieno-2'   => __( 'Pieno 2', 'slug-theme' ),
		'contorno-2' => __( 'Contorno 2', 'slug-theme' ),
	);

	if ( $with_light ) {
		$choices['chiaro'] = __( 'Chiaro (sopra le foto)', 'slug-theme' );
	}

	return $choices;
}

/* Classi del pulsante di un blocco: lo stile scelto nel menu "Stile del pulsante", oppure quelle predefinite del blocco se è su "Predefinito". $extra = classi in più (es. split__button). */
function starter_button_class( $style, $default_class, $extra = '' ) {
	$map = array(
		'pieno-1'    => 'button',
		'contorno-1' => 'button button--outline',
		'pieno-2'    => 'button button--fill-2',
		'contorno-2' => 'button button--outline-2',
		'chiaro'     => 'button button--light',
	);

	if ( ! is_string( $style ) || ! isset( $map[ $style ] ) ) {
		return $default_class;
	}

	return trim( $map[ $style ] . ' ' . $extra );
}

/* Testo di un campo "Link", o un valore di ripiego se il titolo è vuoto. */
function starter_link_title( $link, $fallback = '' ) {
	return ( is_array( $link ) && ! empty( $link['title'] ) ) ? $link['title'] : $fallback;
}

/* Id di allegati da un valore salvato: un id, un elenco di id, o (vecchi contenuti) array con 'ID'. */
function starter_attachment_ids( $value ) {
	$ids = array();

	if ( is_array( $value ) ) {
		if ( isset( $value['ID'] ) ) {
			return array( (int) $value['ID'] );
		}

		foreach ( $value as $item ) {
			$ids = array_merge( $ids, starter_attachment_ids( $item ) );
		}

		return $ids;
	}

	return is_numeric( $value ) && (int) $value > 0 ? array( (int) $value ) : array();
}

/**
 * Foto principale + seconda foto facoltativa di un blocco (max 2 id). Ogni foto è un campo immagine singolo.
 * Compatibile con i contenuti salvati quando erano una gallery: l'elenco vecchio ($main come array, oppure $legacy) vale ancora.
 */
function starter_photo_pair( $main, $second, $legacy = '' ) {
	$ids = array_merge(
		starter_attachment_ids( starter_block_field( $main, true ) ),
		starter_attachment_ids( starter_block_field( $second, true ) )
	);

	if ( ! $ids && $legacy ) {
		$ids = starter_attachment_ids( starter_block_field( $legacy, true ) );
	}

	return array_slice( array_values( array_unique( $ids ) ), 0, 2 );
}

/* Testo con HTML limitato consentito (titoli con <em>, ecc.). */
function starter_kses( $html ) {
	return wp_kses_post( (string) $html );
}

/* Testo scritto in un blocco (titolo, occhiello, testo, voce di un elenco…): permette solo la formattazione in linea (grassetto, corsivo, sottolineato, a capo, link), niente altro HTML. */
function starter_inline( $text ) {
	return wp_kses(
		(string) $text,
		array(
			'a'      => array(
				'href'   => true,
				'target' => true,
				'rel'    => true,
			),
			'strong' => array(),
			'b'      => array(),
			'em'     => array(),
			'i'      => array(),
			'u'      => array(),
			'br'     => array(),
			'sub'    => array(),
			'sup'    => array(),
			'mark'   => array( 'class' => true ),
			'span'   => array( 'style' => true ),
		)
	);
}

/* Testo di un pulsante con l'animazione "flip" già usata sul blocco Pulsante nativo (vedi starter_wp_block_button_flip_animation() in functions.php): stessa struttura HTML, riusa lo stesso CSS. */
function starter_flip_text( $text ) {
	$text = esc_html( $text );

	return '<span class="button__text-wrap"><span class="button__text">' . $text . '</span><span class="button__text button__text--clone" aria-hidden="true">' . $text . '</span></span>';
}

/* Tag ammessi per occhiello e titolo dei moduli (campi "Tag HTML" dei blocchi): da H1 a H6, span e paragrafo. */
function starter_heading_tag_choices() {
	return array(
		'auto' => __( 'Automatico (consigliato)', 'slug-theme' ),
		'h1'   => 'H1',
		'h2'   => 'H2',
		'h3'   => 'H3',
		'h4'   => 'H4',
		'h5'   => 'H5',
		'h6'   => 'H6',
		'span' => 'span',
		'p'    => 'p',
	);
}

/* Tag scelto: $value se è uno di quelli ammessi, altrimenti quello automatico ($auto). */
function starter_pick_tag( $value, $auto ) {
	$value = is_string( $value ) ? strtolower( trim( $value ) ) : '';

	return ( '' !== $value && 'auto' !== $value && isset( starter_heading_tag_choices()[ $value ] ) ) ? $value : $auto;
}

/**
 * Tag HTML scelto nel blocco per l'occhiello ($part = 'occhiello') o per il titolo ($part = 'titolo'): campi "Tag HTML" del modulo.
 * "Automatico" (o nessuna scelta, come nei contenuti vecchi) lascia il tag che il tema calcola per la gerarchia SEO ($auto).
 * Dentro una riga di un repeater legge la riga, altrimenti il campo del blocco; fuori da un blocco restituisce sempre $auto.
 */
function starter_heading_tag( $part, $auto ) {
	$name = $part . '_tag';

	if ( function_exists( 'starter_bf_active' ) && starter_bf_active() ) {
		return starter_pick_tag( starter_bf( $name ), $auto );
	}

	return $auto;
}

/* Tag heading di una sezione: occhiello (h1/h2) → titolo (un livello sotto) → eventuale figlio (un altro livello sotto). Il modulo può sostituire occhiello e titolo con il tag scelto. */
function starter_section_heading_tags( $has_eyebrow, $eyebrow_level = 2 ) {
	$eyebrow_level = in_array( (int) $eyebrow_level, array( 1, 2 ), true ) ? (int) $eyebrow_level : 2;
	$title_level   = $has_eyebrow ? $eyebrow_level + 1 : $eyebrow_level;

	$tags = array(
		'eyebrow' => starter_heading_tag( 'occhiello', 'h' . $eyebrow_level ),
		'title'   => starter_heading_tag( 'titolo', 'h' . $title_level ),
		'child'   => 'h' . ( $title_level + 1 ),
	);

	// Se il titolo è un H1-H5 scelto a mano, i titoli delle voci scendono di un livello rispetto a lui.
	if ( preg_match( '/^h([1-6])$/', $tags['title'], $m ) ) {
		$tags['child'] = 'h' . min( 6, (int) $m[1] + 1 );
	}

	return $tags;
}

/* Briciole di pane: $items = [ [ etichetta, url ], ... ], l'ultima senza url. */
function starter_breadcrumb( $items ) {
	echo '<nav class="breadcrumb" aria-label="' . esc_attr__( 'Percorso', 'slug-theme' ) . '">';

	foreach ( $items as $i => $item ) {
		if ( $i ) {
			echo '<span aria-hidden="true">/</span>';
		}

		if ( ! empty( $item[1] ) ) {
			echo '<a href="' . esc_url( $item[1] ) . '">' . esc_html( $item[0] ) . '</a>';
		} else {
			echo '<span aria-current="page">' . esc_html( $item[0] ) . '</span>';
		}
	}

	echo '</nav>';
}

/* Briciole di una pagina: Home, pagine genitore, pagina corrente. */
function starter_page_breadcrumb() {
	$items = array( array( __( 'Home', 'slug-theme' ), home_url( '/' ) ) );

	foreach ( array_reverse( get_post_ancestors( get_the_ID() ) ) as $ancestor ) {
		$items[] = array( get_the_title( $ancestor ), get_permalink( $ancestor ) );
	}

	$items[] = array( get_the_title(), '' );
	starter_breadcrumb( $items );
}

/* Contact Form 7: niente <p> automatici intorno ai campi (li gestisce già il markup del tema). */
add_filter( 'wpcf7_autop_or_not', '__return_false' );

/* Messaggio in editor per un blocco ancora vuoto (sul sito non stampa niente). */
function starter_block_placeholder( $is_preview, $message ) {
	if ( $is_preview ) {
		echo '<p class="block-placeholder">' . esc_html( $message ) . '</p>';
	}
}

/* Intestazione di sezione dei blocchi: titolo (H2) e testo, centrati. Non stampa niente se sono vuoti. */
function starter_block_head( $title, $text, $eyebrow = '' ) {
	if ( ! $title && ! $text && ! $eyebrow ) {
		return;
	}

	// Struttura SEO come negli altri blocchi: con l'occhiello è lui l'H2 (con la parola chiave) e il titolo scende a H3.
	$tags = starter_section_heading_tags( (bool) $eyebrow );
	?>
	<div class="section-head section-head--center" data-reveal>
		<div class="section-head__text">
			<?php if ( $eyebrow ) : ?>
				<<?php echo esc_html( $tags['eyebrow'] ); ?> class="eyebrow"><?php echo starter_inline( $eyebrow ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></<?php echo esc_html( $tags['eyebrow'] ); ?>>
			<?php endif; ?>
			<?php if ( $title ) : ?>
				<<?php echo esc_html( $tags['title'] ); ?> class="text-balance"><?php echo starter_inline( $title ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></<?php echo esc_html( $tags['title'] ); ?>>
			<?php endif; ?>
			<?php if ( $text ) : ?>
				<p class="txt"><?php echo starter_inline( $text ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
			<?php endif; ?>
		</div>
	</div>
	<?php
}

/* Pulsante centrato sotto una griglia (campo "Link"). */
function starter_block_center_link( $link, $class = 'button button--outline', $style = '' ) {
	if ( ! is_array( $link ) || empty( $link['url'] ) ) {
		return;
	}

	$class = starter_button_class( $style, $class );
	?>
	<div class="section-foot">
		<a <?php echo starter_link_attrs( $link, $class ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo starter_flip_text( starter_link_title( $link ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
	</div>
	<?php
}

/**
 * Titolo di pagina uguale a quello del blocco "Intestazione pagina" (Chi siamo, Servizi…): briciole, occhiello, titolo
 * grande e testo. Lo usano Blog, archivi, ricerca e Shop, così le pagine hanno tutte lo stesso aspetto.
 *
 * Struttura dei titoli (SEO): con "eyebrow_h1" l'occhiello è l'H1 (piccolo, con la parola chiave) e il titolo grande è
 * un H2 con la misura dell'H1; altrimenti l'occhiello è un semplice <span> e il titolo è l'H1.
 *
 * @param array $args eyebrow, title, lead_html (testo sotto, può avere HTML), eyebrow_h1 (bool).
 */
/* Aggancio per Starter Live (plugin di modifica dal frontend): restituisce gli attributi che rendono modificabile un elemento che non è un blocco
   (titolo della pagina, occhiello salvato come meta…). Senza il plugin, o per chi non può modificare, restituisce una stringa vuota. */
function starter_live_attrs( $args ) {
	return (string) apply_filters( 'starter_live_attrs', '', $args );
}

/* Stampa l'intestazione di pagina: briciole e titolo H1 */
function starter_page_header( $args ) {
	$args = wp_parse_args(
		$args,
		array(
			'eyebrow'    => '',
			'title'      => '',
			'lead_html'  => '',
			'eyebrow_h1' => false,
			'live'       => array(), // array( 'post' => ID, 'title' => bool ): occhiello (meta) e titolo modificabili dal frontend con Starter Live.
		)
	);
	?>
	<section class="alignfull content-block page-header page-header--archive">
		<div class="block-wrap page-header__grid">
			<div class="page-header__main" data-reveal>
				<?php starter_breadcrumb_inline(); ?>
				<?php starter_page_header_titles( $args['eyebrow'], $args['title'], (bool) $args['eyebrow_h1'], false, (array) $args['live'] ); ?>
			</div>
			<?php if ( trim( wp_strip_all_tags( $args['lead_html'] ) ) ) : ?>
				<?php
				// Archivio di una categoria/tag: la descrizione del termine è modificabile dal frontend (Starter Live).
				$starter_lead_live = ( ! empty( $args['live']['term'] ) && ! empty( $args['live']['lead'] ) )
					? starter_live_attrs( array( 'src' => 'term', 'term' => (int) $args['live']['term'], 'tax' => (string) $args['live']['tax'], 'key' => 'description', 't' => 'rich', 'multiline' => true, 'label' => __( 'Testo introduttivo', 'slug-theme' ), 'value' => (string) get_term_field( 'description', (int) $args['live']['term'], (string) $args['live']['tax'], 'raw' ) ) )
					: '';
				?>
				<div class="page-header__lead" data-reveal<?php echo $starter_lead_live; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo wp_kses_post( $args['lead_html'] ); ?></div>
			<?php elseif ( ! empty( $args['live']['lead'] ) ) : ?>
				<?php
				// Nessun testo introduttivo: è facoltativo e ai visitatori non appare niente. Chi può modificare (con Starter Live) trova qui uno spazio vuoto
				// che compare solo in modalità modifica, per scriverlo; finché resta vuoto non si vede.
				if ( ! empty( $args['live']['term'] ) ) {
					$starter_lead_new = starter_live_attrs( array( 'src' => 'term', 'term' => (int) $args['live']['term'], 'tax' => (string) $args['live']['tax'], 'key' => 'description', 't' => 'rich', 'multiline' => true, 'label' => __( 'Testo introduttivo', 'slug-theme' ), 'value' => '' ) );
				} elseif ( ! empty( $args['live']['post'] ) ) {
					$starter_lead_new = starter_live_attrs( array( 'post' => (int) $args['live']['post'], 'src' => 'post', 'key' => 'post_intro', 't' => 'rich', 'multiline' => true, 'label' => __( 'Testo introduttivo', 'slug-theme' ), 'value' => '' ) );
				} else {
					$starter_lead_new = '';
				}
				?>
				<?php if ( $starter_lead_new ) : ?>
					<div class="page-header__lead" data-live-empty data-live-placeholder="<?php esc_attr_e( 'Aggiungi un testo introduttivo (facoltativo)', 'slug-theme' ); ?>"<?php echo $starter_lead_new; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>></div>
				<?php endif; ?>
			<?php endif; ?>
		</div>
	</section>
	<?php
}

/* Occhiello + titolo di un titolo di pagina (vedi starter_page_header). $title_html: ammesso <em>/<br>. */
function starter_page_header_titles( $eyebrow, $title, $eyebrow_h1 = true, $title_html = false, $live = array() ) {
	$title_out = $title_html ? starter_kses( $title ) : esc_html( $title );
	$eb_live   = '';
	$ti_live   = '';

	if ( ! empty( $live['post'] ) ) {
		$eb_live = starter_live_attrs( array( 'post' => (int) $live['post'], 'src' => 'meta', 'key' => 'starter_eyebrow', 'label' => __( 'Occhiello', 'slug-theme' ), 'value' => (string) $eyebrow ) );

		if ( ! empty( $live['title'] ) && ! $title_html ) {
			$ti_live = starter_live_attrs( array( 'post' => (int) $live['post'], 'src' => 'post', 'key' => 'post_title', 'label' => __( 'Titolo', 'slug-theme' ), 'value' => (string) get_post_field( 'post_title', (int) $live['post'] ) ) );
		}
	} elseif ( ! empty( $live['term'] ) ) {
		// Categoria / tag / categoria prodotto: occhiello = campo extra del termine, titolo = nome del termine.
		$eb_live = starter_live_attrs( array( 'term' => (int) $live['term'], 'tax' => (string) $live['tax'], 'src' => 'termmeta', 'key' => 'starter_eyebrow', 'label' => __( 'Occhiello', 'slug-theme' ), 'value' => (string) $eyebrow ) );

		if ( ! empty( $live['title'] ) && ! $title_html ) {
			$ti_live = starter_live_attrs( array( 'term' => (int) $live['term'], 'tax' => (string) $live['tax'], 'src' => 'term', 'key' => 'name', 'label' => __( 'Titolo', 'slug-theme' ), 'value' => (string) $title ) );
		}
	}

	// Tag di occhiello e titolo: quelli automatici (SEO) oppure quelli scelti nel blocco.
	if ( $eyebrow && $eyebrow_h1 ) {
		$eb_tag = starter_heading_tag( 'occhiello', 'h1' );
		$ti_tag = starter_heading_tag( 'titolo', 'h2' );

		echo '<' . $eb_tag . $eb_live . ' class="page-header__eyebrow eyebrow title-h6">' . starter_inline( $eyebrow ) . '</' . $eb_tag . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<' . $ti_tag . $ti_live . ' class="page-header__title text-balance">' . $title_out . '</' . $ti_tag . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		return;
	}

	if ( $eyebrow ) {
		$eb_tag = starter_heading_tag( 'occhiello', 'span' );

		echo '<' . $eb_tag . $eb_live . ' class="page-header__eyebrow eyebrow title-h6">' . starter_inline( $eyebrow ) . '</' . $eb_tag . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	$ti_tag = starter_heading_tag( 'titolo', 'h1' );

	echo '<' . $ti_tag . $ti_live . ' class="page-header__title text-balance">' . $title_out . '</' . $ti_tag . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Vero se la pagina apre già con la sua intestazione o hero (un blocco che la stampa): allora page.php non aggiunge il titolo.
 * I moduli (inc/modules.php) aggiungono i loro blocchi con il filtro starter_page_header_blocks.
 */
function starter_page_has_own_header() {
	$blocks = apply_filters(
		'starter_page_header_blocks',
		array( 'intestazione-pagina', 'hero-slider', 'hero-split', 'hero-media' )
	);

	foreach ( $blocks as $block ) {
		if ( has_block( 'starter-theme/' . $block ) ) {
			return true;
		}
	}

	return false;
}
