<?php
/**
 * Opzioni comuni dei blocchi: colori di sfondo della sezione e classi personalizzate (scheda "Avanzate" di ogni blocco).
 * I blocchi stessi e i loro campi stanno nel plugin Starter Blocks (blocks/<nome>/).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Sfondi della sezione (campo "Sfondo della sezione"): chiave => etichetta. Nessuno, "Chiaro" (grigio leggero) e tutti i colori della palette di Opzioni → Colori (slot_N, con il nome che hanno lì). */
function starter_section_bg_choices() {
	$choices = array(
		''       => __( 'Nessuno', 'slug-theme' ),
		'chiaro' => __( 'Chiaro (grigio leggero)', 'slug-theme' ),
	);

	foreach ( starter_get_named_colors() as $slot => $data ) {
		$choices[ $slot ] = $data['name'];
	}

	return $choices;
}

/* Colori salvati con le vecchie chiavi (prima dell'elenco completo) => slot della palette. */
function starter_section_bg_legacy_map() {
	return array(
		'bianco'  => 'slot_5',
		'primary' => 'slot_1',
		'scuro'   => 'slot_3',
	);
}

/* Vero se un colore esadecimale è scuro (testo chiaro sopra). Luminanza relativa sRGB < 0,45. */
function starter_color_is_dark( $hex ) {
	$hex = ltrim( (string) $hex, '#' );

	if ( 3 === strlen( $hex ) ) {
		$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
	}

	if ( ! preg_match( '/^[0-9a-fA-F]{6}$/', $hex ) ) {
		return false;
	}

	$lum = 0;

	foreach ( array( array( 0.2126, 0 ), array( 0.7152, 2 ), array( 0.0722, 4 ) ) as $part ) {
		$channel = hexdec( substr( $hex, $part[1], 2 ) ) / 255;
		$lum    += $part[0] * ( $channel <= 0.03928 ? $channel / 12.92 : pow( ( $channel + 0.055 ) / 1.055, 2.4 ) );
	}

	return $lum < 0.45;
}


/* Applica "Sfondo della sezione" e "Classi CSS personalizzate" al primo elemento del blocco. */
add_filter(
	'render_block',
	function ( $html, $block ) {
		if ( empty( $block['attrs']['data'] ) || ! is_array( $block['attrs']['data'] ) || '' === trim( (string) $html ) ) {
			return $html;
		}

		$data    = $block['attrs']['data'];
		$classes = array();
		$bg      = isset( $data['sfondo_sezione'] ) ? (string) $data['sfondo_sezione'] : '';
		$bg      = starter_section_bg_legacy_map()[ $bg ] ?? $bg;
		$style   = '';
		$colors  = starter_get_named_colors();

		if ( 'chiaro' === $bg ) {
			$classes[] = 'bg-sezione';
			$classes[] = 'bg-sezione--chiaro';
		} elseif ( isset( $colors[ $bg ] ) ) {
			$classes[] = 'bg-sezione';
			$classes[] = starter_color_is_dark( $colors[ $bg ]['value'] ) ? 'bg-sezione--scuro' : 'bg-sezione--chiaro-testo';
			$style     = 'background-color:' . esc_attr( $colors[ $bg ]['value'] ) . ';';
		}

		if ( ! empty( $data['classi_custom'] ) && is_string( $data['classi_custom'] ) ) {
			foreach ( preg_split( '/\s+/', trim( $data['classi_custom'] ) ) as $class ) {
				$class = preg_replace( '/[^A-Za-z0-9_:\-]/', '', $class );

				if ( '' !== $class ) {
					$classes[] = $class;
				}
			}
		}

		if ( ! $classes ) {
			return $html;
		}

		$add   = implode( ' ', array_unique( $classes ) );
		$first = '/^(\s*(?:<!--.*?-->\s*)*<[a-zA-Z][a-zA-Z0-9]*)([^>]*)>/s';

		if ( ! preg_match( $first, $html, $m ) ) {
			return $html;
		}

		$attrs = $m[2];

		// class="..." esistente: si aggiunge lì; altrimenti si crea.
		$attrs = preg_match( '/\sclass="([^"]*)"/', $attrs ) ? preg_replace( '/\sclass="([^"]*)"/', ' class="$1 ' . $add . '"', $attrs, 1 ) : $attrs . ' class="' . $add . '"';

		if ( $style ) {
			$attrs = preg_match( '/\sstyle="([^"]*)"/', $attrs ) ? preg_replace( '/\sstyle="([^"]*)"/', ' style="$1;' . $style . '"', $attrs, 1 ) : $attrs . ' style="' . $style . '"';
		}

		return preg_replace( $first, '$1' . addcslashes( $attrs, '\\$' ) . '>', $html, 1 );
	},
	20,
	2
);
