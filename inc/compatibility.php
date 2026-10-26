<?php
/**
 * Compatibilità con gli aggiornamenti di WordPress, WooCommerce e ACF.
 *
 * Il tema dichiara con quali versioni (maggiore.minore) è stato provato. Se un sito monta una versione più nuova, compare
 * un avviso in Bacheca, Aggiornamenti, Temi e Plugin, e un controllo in Strumenti → Salute del sito. Non blocca nulla:
 * serve a ricordare di provare in locale (tools/smoke-test.js) prima di aggiornare un sito vero.
 *
 * Dopo aver provato una versione nuova, si alza il numero in starter_compat_tested().
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Versioni con cui il tema è stato provato (maggiore.minore). */
function starter_compat_tested() {
	return array(
		'WordPress'   => '7.1',
		'WooCommerce' => '11.2',
		'ACF'         => '6.8',
	);
}

/* Versioni installate sul sito (vuote se il software non c'è). */
function starter_compat_current() {
	global $wp_version;

	return array(
		'WordPress'   => (string) $wp_version,
		'WooCommerce' => defined( 'WC_VERSION' ) ? (string) WC_VERSION : '',
		'ACF'         => defined( 'ACF_VERSION' ) ? (string) ACF_VERSION : '',
	);
}

/* Elenco dei software con una versione più nuova di quella provata: nome => array( installata, provata ). */
function starter_compat_ahead() {
	$tested = starter_compat_tested();
	$ahead  = array();

	foreach ( starter_compat_current() as $name => $version ) {
		if ( '' === $version ) {
			continue;
		}

		$short = implode( '.', array_slice( explode( '.', $version ), 0, 2 ) );

		if ( version_compare( $short, $tested[ $name ], '>' ) ) {
			$ahead[ $name ] = array( $version, $tested[ $name ] );
		}
	}

	return $ahead;
}

/* Testo dell'avviso: software più nuovo di quello provato con il tema */
function starter_compat_message( $ahead ) {
	$parts = array();

	foreach ( $ahead as $name => $versions ) {
		/* translators: 1: software, 2: versione installata, 3: versione provata. */
		$parts[] = sprintf( __( '%1$s %2$s (provata fino alla %3$s)', 'slug-theme' ), $name, $versions[0], $versions[1] );
	}

	return implode( ', ', $parts );
}

add_action(
	'admin_notices',
	function () {
		$screen = get_current_screen();

		if ( ! $screen || ! in_array( $screen->id, array( 'dashboard', 'update-core', 'themes', 'plugins' ), true ) || ! current_user_can( 'update_core' ) ) {
			return;
		}

		$ahead = starter_compat_ahead();

		if ( ! $ahead ) {
			return;
		}

		printf(
			'<div class="notice notice-warning"><p><strong>%1$s</strong> %2$s %3$s</p></div>',
			esc_html__( 'Tema Starter:', 'slug-theme' ),
			esc_html( sprintf( /* translators: %s: elenco versioni. */ __( 'questo sito usa versioni più recenti di quelle con cui il tema è stato provato: %s.', 'slug-theme' ), starter_compat_message( $ahead ) ) ),
			esc_html__( 'Controlla il sito (pagine, negozio, moduli) dopo l\'aggiornamento.', 'slug-theme' )
		);
	}
);

add_filter(
	'site_status_tests',
	function ( $tests ) {
		$tests['direct']['starter_compat'] = array(
			'label' => __( 'Compatibilità del Tema Starter', 'slug-theme' ),
			'test'  => function () {
				$ahead  = starter_compat_ahead();
				$result = array(
					'label'       => __( 'Il tema è stato provato con le versioni installate', 'slug-theme' ),
					'status'      => 'good',
					'badge'       => array(
						'label' => __( 'Tema', 'slug-theme' ),
						'color' => 'blue',
					),
					'description' => '<p>' . esc_html__( 'WordPress, WooCommerce e ACF non superano le versioni con cui il tema è stato provato.', 'slug-theme' ) . '</p>',
					'actions'     => '',
					'test'        => 'starter_compat',
				);

				if ( $ahead ) {
					$result['label']       = __( 'Il tema non è stato ancora provato con alcune versioni installate', 'slug-theme' );
					$result['status']      = 'recommended';
					$result['description'] = '<p>' . esc_html( starter_compat_message( $ahead ) ) . '</p><p>' . esc_html__( 'Non è un errore: serve controllare il sito dopo l\'aggiornamento.', 'slug-theme' ) . '</p>';
				}

				return $result;
			},
		);

		return $tests;
	}
);
