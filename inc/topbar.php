<?php
/**
 * Barra annunci sopra l'header: una fascia con testi che scorrono in continuazione (marquee), accesa da Opzioni → Barra annunci.
 *
 * Markup:
 *   .topbar                      fascia (in cima alla pagina, scorre via con la pagina; l'header fisso la segue)
 *     .topbar__inner             <a> se c'è un link, altrimenti <div>
 *       .topbar__track           due gruppi identici uno dopo l'altro: l'animazione sposta la pista di un gruppo
 *         .topbar__group > .topbar__item (+ .topbar__sep)
 *
 * Altezza, scorrimento dell'header e animazione: CSS/starter.css (sezione "Barra annunci") e initTopBar() in JS/scripts.js.
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Simboli separatori: li usano la barra annunci (tra un annuncio e il successivo) e le categorie delle card del Portfolio. */
function starter_topbar_separators() {
	return array(
		'dot'      => '·',
		'bullet'   => '•',
		'slash'    => '/',
		'dslash'   => '//',
		'pipe'     => '|',
		'dash'     => '—',
		'plus'     => '+',
		'star'     => '✦',
		'asterisk' => '✱',
		'diamond'  => '◆',
		'arrow'    => '→',
	);
}

/* Barra accesa e con almeno un annuncio. */
function starter_topbar_active() {
	return (bool) get_theme_mod( 'starter_topbar_enabled', false ) && starter_topbar_messages();
}

/* Annunci: uno per riga. */
function starter_topbar_messages() {
	$lines = preg_split( '/\r\n|\r|\n/', (string) get_theme_mod( 'starter_topbar_text', '' ) );

	return array_values( array_filter( array_map( 'trim', $lines ), 'strlen' ) );
}

/* Opzioni → Barra annunci. */
add_action(
	'customize_register',
	function ( $wp_customize ) {
		$section = 'starter_options_topbar';

		$wp_customize->add_section(
			$section,
			array(
				'title'       => __( 'Barra annunci', 'slug-theme' ),
				'description' => __( 'Una fascia sopra l\'header con testi che scorrono in continuazione, per annunci, eventi o promozioni. Scorre via con la pagina.', 'slug-theme' ),
				'panel'       => 'starter_options',
				'priority'    => 11, // Subito dopo Header (10).
			)
		);

		starter_customizer_checkbox( $wp_customize, 'starter_topbar_enabled', $section, __( 'Mostra la barra annunci', 'slug-theme' ), false, '', 1 );

		$wp_customize->add_setting(
			'starter_topbar_label',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'starter_topbar_label',
			array(
				'type'        => 'text',
				'section'     => $section,
				'label'       => __( 'Etichetta in grassetto', 'slug-theme' ),
				'description' => __( 'Facoltativa: compare in grassetto prima di ogni annuncio (es. OPEN DAY).', 'slug-theme' ),
				'priority'    => 2,
			)
		);

		$wp_customize->add_setting(
			'starter_topbar_text',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_textarea_field',
			)
		);

		$wp_customize->add_control(
			'starter_topbar_text',
			array(
				'type'        => 'textarea',
				'section'     => $section,
				'label'       => __( 'Annunci', 'slug-theme' ),
				'description' => __( 'Uno per riga. Scorrono uno dopo l\'altro e ricominciano.', 'slug-theme' ),
				'priority'    => 3,
			)
		);

		$wp_customize->add_setting(
			'starter_topbar_link',
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$wp_customize->add_control(
			'starter_topbar_link',
			array(
				'type'        => 'url',
				'section'     => $section,
				'label'       => __( 'Link (facoltativo)', 'slug-theme' ),
				'description' => __( 'Se lo metti, tutta la fascia diventa cliccabile.', 'slug-theme' ),
				'priority'    => 4,
			)
		);

		starter_customizer_select(
			$wp_customize,
			'starter_topbar_separator',
			$section,
			__( 'Separatore tra gli annunci', 'slug-theme' ),
			starter_topbar_separators(),
			'dot',
			'',
			5
		);

		starter_customizer_select(
			$wp_customize,
			'starter_topbar_speed',
			$section,
			__( 'Velocità', 'slug-theme' ),
			array(
				'30'  => __( 'Lenta', 'slug-theme' ),
				'60'  => __( 'Media', 'slug-theme' ),
				'100' => __( 'Veloce', 'slug-theme' ),
			),
			'60',
			'',
			6
		);

		starter_customizer_checkbox( $wp_customize, 'starter_topbar_pause', $section, __( 'Ferma lo scorrimento al passaggio del mouse', 'slug-theme' ), false, '', 7 );

		// Colori dalla Tavolozza (vuoto = scuro con testo chiaro).
		$previous = starter_colors_section( $section );
		$priority = 8;

		starter_register_color_control( $wp_customize, 'starter_topbar_bg', __( 'Colore di sfondo', 'slug-theme' ), $priority, __( 'Vuoto = colore del testo del sito.', 'slug-theme' ) );
		starter_register_color_control( $wp_customize, 'starter_topbar_color', __( 'Colore del testo', 'slug-theme' ), $priority, __( 'Vuoto = colore di sfondo del sito.', 'slug-theme' ) );

		starter_colors_section( $previous );
	},
	20
);

/* Colori della barra come variabili CSS (si aggiungono a quelle dei colori del tema). */
add_filter(
	'starter_colors_css_root_vars',
	function ( $vars ) {
		$vars['--topbar-bg']   = starter_resolve_named_color( get_theme_mod( 'starter_topbar_bg', '' ) );
		$vars['--topbar-text'] = starter_resolve_named_color( get_theme_mod( 'starter_topbar_color', '' ) );

		return $vars;
	}
);

/* La fascia, in cima alla pagina e sopra l'header. */
add_action(
	'wp_body_open',
	function () {
		if ( ! starter_topbar_active() ) {
			return;
		}

		$messages   = starter_topbar_messages();
		$label      = trim( (string) get_theme_mod( 'starter_topbar_label', '' ) );
		$link       = trim( (string) get_theme_mod( 'starter_topbar_link', '' ) );
		$separators = starter_topbar_separators();
		$sep        = isset( $separators[ get_theme_mod( 'starter_topbar_separator', 'dot' ) ] ) ? $separators[ get_theme_mod( 'starter_topbar_separator', 'dot' ) ] : '·';
		$speed      = max( 10, (int) get_theme_mod( 'starter_topbar_speed', 60 ) );
		$repeat     = max( 1, (int) ceil( 6 / count( $messages ) ) ); // Senza JS il gruppo è già abbastanza lungo; il JS lo completa in base alla larghezza dello schermo.

		// Un gruppo = tutti gli annunci (ripetuti $repeat volte), ognuno seguito dal separatore. Le ripetizioni extra sono "is-dup" (nascoste con "riduci animazioni").
		$group = '';

		for ( $i = 0; $i < $repeat; $i++ ) {
			foreach ( $messages as $message ) {
				$group .= '<span class="topbar__item' . ( $i ? ' is-dup' : '' ) . '">'
					. ( '' !== $label ? '<strong>' . esc_html( $label ) . '</strong> ' : '' )
					. esc_html( $message )
					. '</span><span class="topbar__sep' . ( $i ? ' is-dup' : '' ) . '" aria-hidden="true">' . esc_html( $sep ) . '</span>';
			}
		}

		$tag   = '' !== $link ? 'a' : 'div';
		$attrs = '' !== $link ? ' href="' . esc_url( $link ) . '"' : '';

		echo '<div class="topbar' . ( get_theme_mod( 'starter_topbar_pause', false ) ? ' topbar--pause' : '' ) . '" role="region" aria-label="' . esc_attr__( 'Annunci', 'slug-theme' ) . '" data-speed="' . esc_attr( $speed ) . '">';
		echo '<' . $tag . ' class="topbar__inner"' . $attrs . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<span class="topbar__track"><span class="topbar__group">' . $group . '</span><span class="topbar__group" aria-hidden="true">' . $group . '</span></span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</' . $tag . '></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	},
	5
);

add_filter(
	'body_class',
	function ( $classes ) {
		if ( starter_topbar_active() ) {
			$classes[] = 'has-topbar';
		}

		return $classes;
	}
);
