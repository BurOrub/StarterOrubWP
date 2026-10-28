<?php
/**
 * Pulsante WhatsApp fisso in basso a destra. I campi sono in Aspetto → Personalizza → Opzioni → Generale
 * (prima stavano in una pagina "Opzioni tema", ora tolta: chi aveva già compilato i campi li ritrova qui).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Valore di un campo WhatsApp ('url' o 'text'); ripiego sui vecchi campi delle opzioni se il sito li aveva compilati. */
function starter_whatsapp_opt( $which ) {
	$value = trim( (string) get_theme_mod( "starter_whatsapp_{$which}", '' ) );

	if ( '' === $value ) {
		$value = trim( (string) get_option( 'url' === $which ? 'options_whatsapp_url' : 'options_whatsapp_testo', '' ) );
	}

	return $value;
}

/* Classe "has-whatsapp" sul body quando il pulsante WhatsApp è attivo (spazio riservato in basso su mobile, vedi CSS/starter.css). */
function starter_whatsapp_body_class( $classes ) {
	if ( '' !== starter_whatsapp_opt( 'url' ) ) {
		$classes[] = 'has-whatsapp';
	}

	return $classes;
}
add_filter( 'body_class', 'starter_whatsapp_body_class' );
