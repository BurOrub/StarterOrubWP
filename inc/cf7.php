<?php
/**
 * Contact Form 7: anti-spam senza plugin né servizi esterni + campi nascosti per Flamingo.
 *
 * 1) Honeypot automatico in TUTTI i moduli (niente da incollare nel form):
 *    - un campo "trappola" invisibile (classe .form-hp): le persone non lo vedono e non lo compilano, i bot sì;
 *    - un orario firmato (starter_ts): inviare il modulo in meno di 3 secondi dal caricamento, o senza un orario
 *      valido, è considerato spam.
 *    Le richieste scartate finiscono nel registro spam di CF7 (e Flamingo) con il motivo; il bot non vede errori.
 *
 * 2) Campi nascosti per sapere DA DOVE arriva ogni contatto. A ogni modulo il tema aggiunge da solo (non serve
 *    toccare il form) i tag [hidden pagina] e [hidden pagina-url]: titolo e indirizzo della pagina in cui si trova il
 *    modulo. Flamingo li registra con il messaggio e il tema li aggiunge in fondo alla mail ("Pagina: …").
 *    Se in un modulo li scrivi a mano, il tema non li duplica.
 *    Nota: `default:post_title` NON funziona sui campi hidden di CF7, per questo il valore lo scrive il tema.
 *
 * 3) Auto-paragrafo di CF7 disattivato: il markup dei moduli resta quello scritto (niente <p> e <br> inseriti da soli).
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Secondi minimi tra caricamento della pagina e invio del modulo. */
const STARTER_HONEYPOT_MIN_SECONDS = 3;

/* Firma (HMAC) dell'orario di caricamento: riconosce gli invii troppo rapidi dei bot */
function starter_honeypot_signature( $time ) {
	return substr( hash_hmac( 'sha256', (string) $time, wp_salt( 'nonce' ) ), 0, 20 );
}

/* Inietta trappola e orario firmato in ogni form CF7 renderizzato, senza toccare il contenuto dei moduli. */
add_filter(
	'wpcf7_form_elements',
	function ( $html ) {
		$time = time();

		$honeypot = sprintf(
			'<span class="form-hp" aria-hidden="true"><label>%1$s<input type="text" name="starter_hp_check" tabindex="-1" autocomplete="off" value=""></label><input type="hidden" name="starter_ts" value="%2$s"></span>',
			esc_html__( 'Lascia questo campo vuoto', 'slug-theme' ),
			esc_attr( $time . '.' . starter_honeypot_signature( $time ) )
		);

		return $honeypot . $html;
	}
);

/* Spam se la trappola è compilata o l'orario firmato manca, non è valido o è troppo recente. */
add_filter(
	'wpcf7_spam',
	function ( $spam, $submission = null ) {
		if ( $spam ) {
			return $spam;
		}

		$reason = '';

		// phpcs:disable WordPress.Security.NonceVerification.Missing -- moduli pubblici, il controllo è anti-bot.
		if ( ! empty( $_POST['starter_hp_check'] ) ) {
			$reason = 'campo trappola compilato';
		} else {
			$parts = explode( '.', isset( $_POST['starter_ts'] ) ? sanitize_text_field( wp_unslash( $_POST['starter_ts'] ) ) : '' );
			$time  = isset( $parts[0] ) ? (int) $parts[0] : 0;
			$sign  = isset( $parts[1] ) ? $parts[1] : '';

			if ( ! $time || ! hash_equals( starter_honeypot_signature( $time ), $sign ) ) {
				$reason = 'orario mancante o non valido';
			} elseif ( time() - $time < STARTER_HONEYPOT_MIN_SECONDS ) {
				$reason = 'inviato troppo in fretta';
			}
		}
		// phpcs:enable

		if ( ! $reason ) {
			return $spam;
		}

		if ( $submission && method_exists( $submission, 'add_spam_log' ) ) {
			$submission->add_spam_log(
				array(
					'agent'  => 'starter_honeypot',
					'reason' => $reason,
				)
			);
		}

		return true;
	},
	10,
	2
);

/* Valore dei campi nascosti [hidden pagina] e [hidden pagina-url]: titolo e indirizzo della pagina del modulo. */
add_filter(
	'wpcf7_form_tag',
	function ( $tag ) {
		if ( 'hidden' !== $tag['basetype'] || ! in_array( $tag['name'], array( 'pagina', 'pagina-url' ), true ) || ! get_the_ID() ) {
			return $tag;
		}

		$tag['values']     = array( 'pagina' === $tag['name'] ? get_the_title() : get_permalink() );
		$tag['raw_values'] = $tag['values'];

		return $tag;
	}
);

/* Aggiunge i due campi nascosti a ogni modulo (e li stampa in fondo alla mail) se non ci sono già. Non nell'editor di CF7. */
add_filter(
	'wpcf7_contact_form_properties',
	function ( $properties ) {
		if ( is_admin() && ! wp_doing_ajax() && ! ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
			return $properties;
		}

		if ( ! empty( $properties['form'] ) && false === strpos( $properties['form'], '[hidden pagina' ) ) {
			$properties['form'] .= "\n[hidden pagina][hidden pagina-url]";

			if ( ! empty( $properties['mail']['body'] ) ) {
				$properties['mail']['body'] .= "\n\n--\nPagina: [pagina]\nURL: [pagina-url]";
			}
		}

		return $properties;
	}
);

/* Niente <p> e <br> automatici nei moduli: il markup resta quello scritto. */
add_filter( 'wpcf7_autop_or_not', '__return_false' );
