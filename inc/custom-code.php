<?php
/**
 * Codici e script del sito (Tag Manager, Analytics, pixel, Clarity…): tre campi in Personalizza → Opzioni → Codici e script.
 *
 *  - Head: dentro <head>, il più in alto possibile.
 *  - Inizio body: subito dopo <body> (qui va la parte <noscript> di Google Tag Manager).
 *  - Fine body: prima di </body>.
 *
 * Sono salvati come opzioni del sito (wp_options), non come opzioni del tema: restano anche cambiando tema o passando dal padre al figlio, e un
 * aggiornamento del tema non li tocca. Il codice si stampa tale e quale: il compressore dell'HTML (Prestazioni) lo lascia intatto.
 * Solo chi può inserire codice libero (capacità "unfiltered_html": amministratori; su multisito il super admin) vede e salva questi campi.
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Campi: posizione => nome dell'opzione. */
function starter_custom_code_fields() {
	return array(
		'head'   => 'starter_code_head',
		'body'   => 'starter_code_body',
		'footer' => 'starter_code_footer',
	);
}

/* Salva il codice così com'è, solo per chi può inserire codice libero; per gli altri resta il valore già salvato. */
function starter_custom_code_sanitize( $value, $setting ) {
	if ( ! current_user_can( 'unfiltered_html' ) ) {
		return (string) get_option( $setting->id, '' );
	}

	return trim( (string) $value );
}

/* Stampa il codice di una posizione; mai nell'anteprima di Personalizza (non deve contare visite). */
function starter_custom_code_print( $position ) {
	$fields = starter_custom_code_fields();

	if ( empty( $fields[ $position ] ) || is_customize_preview() ) {
		return;
	}

	$code = trim( (string) get_option( $fields[ $position ], '' ) );

	if ( '' === $code ) {
		return;
	}

	// I segnaposto dicono al compressore dell'HTML (inc/assets-optimizer.php) di non toccare quello che sta in mezzo.
	echo "\n<!--starter-code-->\n" . $code . "\n<!--/starter-code-->\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- codice libero inserito da chi ha "unfiltered_html".
}

add_action(
	'wp_head',
	function () {
		starter_custom_code_print( 'head' );
	},
	2 // Subito dopo charset e viewport (che stanno già in header.php), prima di CSS e JS del tema.
);

add_action(
	'wp_body_open',
	function () {
		starter_custom_code_print( 'body' );
	},
	1
);

add_action(
	'wp_footer',
	function () {
		starter_custom_code_print( 'footer' );
	},
	99
);

/* Opzioni → Codici e script. */
add_action(
	'customize_register',
	function ( $wp_customize ) {
		$section = 'starter_options_code';

		$wp_customize->add_section(
			$section,
			array(
				'title'       => __( 'Codici e script', 'slug-theme' ),
				'description' => __( 'Incolla qui i codici di Tag Manager, Analytics, pixel e simili, completi dei tag &lt;script&gt;. Restano anche aggiornando il tema.', 'slug-theme' ),
				'panel'       => 'starter_options',
				'priority'    => 30,
				'capability'  => 'unfiltered_html',
			)
		);

		$controls = array(
			'head'   => array( 'starter_code_head', __( 'Nell\'head', 'slug-theme' ), __( 'Tag Manager (parte script), Analytics, pixel, Clarity.', 'slug-theme' ) ),
			'body'   => array( 'starter_code_body', __( 'Inizio del body', 'slug-theme' ), __( 'Subito dopo &lt;body&gt;: la parte &lt;noscript&gt; di Tag Manager.', 'slug-theme' ) ),
			'footer' => array( 'starter_code_footer', __( 'Fine del body', 'slug-theme' ), __( 'Prima di &lt;/body&gt;: chat, widget e script da caricare per ultimi.', 'slug-theme' ) ),
		);

		$priority = 1;

		foreach ( $controls as $control ) {
			$wp_customize->add_setting(
				$control[0],
				array(
					'type'              => 'option',
					'default'           => '',
					'capability'        => 'unfiltered_html',
					'sanitize_callback' => 'starter_custom_code_sanitize',
				)
			);

			$wp_customize->add_control(
				$control[0],
				array(
					'type'        => 'textarea',
					'section'     => $section,
					'label'       => $control[1],
					'description' => $control[2],
					'priority'    => $priority++,
					'input_attrs' => array(
						'rows'       => 8,
						'spellcheck' => 'false',
						'style'      => 'font-family:monospace;font-size:12px;white-space:pre;overflow:auto',
					),
				)
			);
		}
	}
);
