<?php
/**
 * Pagine standard di partenza: Home, Chi siamo, Servizi, Contatti.
 *
 * Costruite con i blocchi del tema (template-parts/) e con blocchi di Gutenberg: ogni sezione è un blocco
 * oppure un Gruppo (blocco "Gruppo", tag "section"), così la pagina si legge e si riordina per sezioni. I testi e le
 * foto sono segnaposto da sostituire. Si creano da Strumenti → Contenuti di prova → "Crea le pagine standard" e non
 * si cancellano con "Elimina i contenuti di prova" (sono pagine vere, da riscrivere).
 *
 * La Home diventa la pagina iniziale del sito; Contatti usa il primo modulo di Contact Form 7 che trova.
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ------------------------------------------------------- Pezzi di Gutenberg */

/* Titolo. */
function starter_pg_h( $text, $level = 2, $class = '', $center = false ) {
	$attrs = array();

	if ( 2 !== $level ) {
		$attrs['level'] = $level;
	}

	if ( $center ) {
		$attrs['textAlign'] = 'center';
	}

	if ( $class ) {
		$attrs['className'] = $class;
	}

	$classes = trim( 'wp-block-heading' . ( $center ? ' has-text-align-center' : '' ) . ( $class ? ' ' . $class : '' ) );

	return '<!-- wp:heading' . ( $attrs ? ' ' . wp_json_encode( $attrs ) : '' ) . " -->\n<h{$level} class=\"{$classes}\">{$text}</h{$level}>\n<!-- /wp:heading -->\n\n";
}

/* Paragrafo. */
function starter_pg_p( $text, $class = '', $center = false ) {
	$attrs   = array();
	$classes = array();

	if ( $center ) {
		$attrs['align'] = 'center';
		$classes[]      = 'has-text-align-center';
	}

	if ( $class ) {
		$attrs['className'] = $class;
		$classes[]          = $class;
	}

	return '<!-- wp:paragraph' . ( $attrs ? ' ' . wp_json_encode( $attrs ) : '' ) . " -->\n<p" . ( $classes ? ' class="' . implode( ' ', $classes ) . '"' : '' ) . ">{$text}</p>\n<!-- /wp:paragraph -->\n\n";
}

/* Pulsanti: $items = [ [ testo, url, 'outline' (facoltativo) ], ... ]. */
function starter_pg_buttons( $items, $center = false ) {
	$out = '<!-- wp:buttons' . ( $center ? ' {"layout":{"type":"flex","justifyContent":"center"}}' : '' ) . " -->\n<div class=\"wp-block-buttons\">";

	foreach ( $items as $item ) {
		$outline = ! empty( $item[2] );
		$out    .= '<!-- wp:button' . ( $outline ? ' {"className":"is-style-outline"}' : '' ) . " -->\n<div class=\"wp-block-button" . ( $outline ? ' is-style-outline' : '' ) . '"><a class="wp-block-button__link wp-element-button" href="' . esc_url( $item[1] ) . '">' . esc_html( $item[0] ) . "</a></div>\n<!-- /wp:button -->";
	}

	return $out . "</div>\n<!-- /wp:buttons -->\n\n";
}

/**
 * Sezione = blocco Gruppo (tag section, a tutta larghezza, contenuto nella larghezza del sito).
 *
 * @param string $inner Blocchi dentro il gruppo.
 * @param string $class Classi in più (spaziature: pt5, pb5…).
 * @param string $bg    Slug del colore di sfondo ('' = nessuno).
 * @param string $text  Slug del colore del testo ('' = predefinito).
 */
function starter_pg_group( $inner, $class = '', $bg = '', $text = '' ) {
	$attrs   = array(
		'tagName' => 'section',
		'align'   => 'full',
	);
	$classes = array( 'wp-block-group', 'alignfull' );

	if ( $class ) {
		$attrs['className'] = $class;
		$classes[]          = $class;
	}

	if ( $bg ) {
		$attrs['backgroundColor'] = $bg;
	}

	if ( $text ) {
		$attrs['textColor'] = $text;
		$classes[]          = "has-{$text}-color";
	}

	if ( $bg ) {
		$classes[] = "has-{$bg}-background-color";
	}

	if ( $text ) {
		$classes[] = 'has-text-color';
	}

	if ( $bg ) {
		$classes[] = 'has-background';
	}

	$attrs['layout'] = array( 'type' => 'constrained' );

	return '<!-- wp:group ' . wp_json_encode( $attrs ) . " -->\n<section class=\"" . implode( ' ', $classes ) . "\">" . trim( $inner ) . "</section>\n<!-- /wp:group -->\n\n";
}

/* Link nel formato dei campi "Link" dei blocchi (titolo, url, target). */
function starter_pg_link( $title, $path ) {
	return array(
		'title'  => $title,
		'url'    => home_url( $path ),
		'target' => '',
	);
}

/* Blocco con dei blocchi di Gutenberg dentro (es. "Sezione affiancata"). */
function starter_pg_block_inner( $name, $data, $keys, $inner ) {
	$block = starter_demo_block( $name, $data, $keys );

	return str_replace( ' /-->', ' -->', rtrim( $block ) ) . "\n" . $inner . '<!-- /wp:starter-theme/' . $name . " -->\n\n";
}

/* Chiavi dei campi dei blocchi, per scrivere i valori nei blocchi (vedi blocks/<nome>/fields.json del plugin Starter Blocks). */
function starter_pg_keys( $block ) {
	$all = array(
		'hero-media'           => array(
			'occhiello'       => 'field_sthm_occhiello',
			'titolo'          => 'field_sthm_titolo',
			'testo'           => 'field_sthm_testo',
			'cta_primaria'    => 'field_sthm_cta1',
			'cta_secondaria'  => 'field_sthm_cta2',
			'clienti'         => 'field_sthm_clienti',
			'clienti_testo'   => 'field_sthm_clienti_testo',
			'foto'            => 'field_sthm_foto',
			'etichetta_foto'  => 'field_sthm_etichetta_foto',
			'colore_etichetta_foto'  => 'field_sthm_colore_foto',
			'colore_etichetta_video' => 'field_sthm_colore_video',
			'video'           => 'field_sthm_video',
			'copertina_video' => 'field_sthm_copertina',
			'etichetta_video' => 'field_sthm_etichetta_video',
		),
		'hero-slider'          => array( 'occhiello' => 'field_sthe_occhiello', 'immagini' => 'field_sthe_immagini', 'titolo' => 'field_sthe_titolo', 'testo' => 'field_sthe_testo', 'link' => 'field_sthe_link', 'link_2' => 'field_sthe_link2', 'durata' => 'field_sthe_durata' ),
		'hero-video'           => array( 'occhiello' => 'field_sthv_occhiello', 'video' => 'field_sthv_video', 'copertina' => 'field_sthv_copertina', 'titolo' => 'field_sthv_titolo', 'testo' => 'field_sthv_testo', 'link' => 'field_sthv_link', 'link_2' => 'field_sthv_link2' ),
		'striscia-valori'      => array( 'voci' => array( 'field_stsv_voci', array( 'icona' => 'field_stsv_icona', 'titolo' => 'field_stsv_titolo' ) ) ),
		'sezione-affiancata'   => array( 'occhiello' => 'field_stsa_occhiello', 'foto' => 'field_stsa_foto', 'foto_2' => 'field_stsa_foto_2', 'formato' => 'field_stsa_formato', 'lato' => 'field_stsa_lato', 'sfondo' => 'field_stsa_sfondo' ),
		'sezione-voci'         => array(
			'layout'    => 'field_sv_layout',
			'sfondo'    => 'field_sv_sfondo',
			'occhiello' => 'field_sv_occhiello',
			'titolo'    => 'field_sv_titolo',
			'testo'     => 'field_sv_testo',
			'link'      => 'field_sv_link',
			'voci'      => array( 'field_sv_voci', array( 'titolo' => 'field_sv_voci_titolo', 'testo' => 'field_sv_voci_testo', 'icona' => 'field_sv_voci_icona', 'link' => 'field_sv_voci_link' ) ),
		),
		'elenco-servizi'       => array( 'voci' => array( 'field_ses_voci', array( 'voci_id' => 'field_ses_id', 'voci_icona' => 'field_ses_icona', 'voci_occhiello' => 'field_ses_occhiello', 'voci_titolo' => 'field_ses_titolo', 'voci_etichetta' => 'field_ses_etichetta', 'voci_testo' => 'field_ses_testo', 'voci_immagine' => 'field_ses_immagine', 'voci_elenco' => 'field_ses_elenco', 'voci_cta' => 'field_ses_cta' ) ) ),
		'testo-affiancato'     => array(
			'lato_media' => 'field_sta_lato_media',
			'sfondo'     => 'field_sta_sfondo',
			'occhiello'  => 'field_sta_occhiello',
			'titolo'     => 'field_sta_titolo',
			'testo'      => 'field_sta_testo',
			'checklist'  => array( 'field_sta_checklist', array( 'voce' => 'field_sta_checklist_voce' ) ),
			'link'       => 'field_sta_link',
			'immagine'   => 'field_sta_immagine',
			'immagine_2' => 'field_sta_immagine_2',
		),
		'prodotti-in-evidenza' => array( 'occhiello' => 'field_stpr_occhiello', 'titolo' => 'field_stpr_titolo', 'testo' => 'field_stpr_testo', 'link' => 'field_stpr_link', 'sorgente' => 'field_stpr_sorgente', 'numero' => 'field_stpr_numero' ),
		'ultimi-articoli'      => array( 'occhiello' => 'field_stua_occhiello', 'titolo' => 'field_stua_titolo', 'testo' => 'field_stua_testo', 'link' => 'field_stua_link', 'numero' => 'field_stua_numero' ),
		'recensioni'           => array(
			'occhiello' => 'field_rec_occhiello',
			'titolo'    => 'field_rec_titolo',
			'voci'      => array( 'field_rec_voci', array( 'nome' => 'field_rec_voci_nome', 'testo' => 'field_rec_voci_testo', 'data' => 'field_rec_voci_data' ) ),
		),
		'intestazione-pagina'  => array( 'occhiello' => 'field_ip_occhiello', 'titolo' => 'field_ip_titolo', 'testo' => 'field_ip_testo' ),
		'missione-visione'     => array( 'voci' => array( 'field_mv_voci', array( 'occhiello' => 'field_mv_voci_occhiello', 'titolo' => 'field_mv_voci_titolo', 'testo' => 'field_mv_voci_testo' ) ) ),
		'team'                 => array(
			'occhiello' => 'field_stte_occhiello',
			'titolo'    => 'field_stte_titolo',
			'testo'     => 'field_stte_testo',
			'voci'      => array( 'field_stte_voci', array( 'voci_foto' => 'field_stte_foto', 'voci_nome' => 'field_stte_nome', 'voci_ruolo' => 'field_stte_ruolo', 'voci_bio' => 'field_stte_bio' ) ),
		),
		'contatti'             => array( 'occhiello' => 'field_stco_occhiello', 'titolo' => 'field_stco_titolo', 'recapiti' => array( 'field_stco_recapiti', array( 'icona' => 'field_stco_recapiti_icona', 'testo' => 'field_stco_recapiti_testo' ) ), 'shortcode_form' => 'field_stco_form' ),
		'mappa'                => array( 'indirizzo' => 'field_map_indirizzo', 'pulsante' => 'field_map_pulsante' ),
		'elenco-immobili'      => array( 'occhiello' => 'field_ei_occhiello', 'titolo' => 'field_ei_titolo', 'testo' => 'field_ei_testo' ),
		'immobili-in-evidenza' => array( 'occhiello' => 'field_ie_occhiello', 'titolo' => 'field_ie_titolo', 'testo' => 'field_ie_testo', 'link' => 'field_ie_link' ),
		'immobili-hero-home'  => array( 'occhiello' => 'field_hh_occhiello', 'titolo' => 'field_hh_titolo', 'testo' => 'field_hh_testo', 'cta_primaria' => 'field_hh_cta_primaria', 'cta_secondaria' => 'field_hh_cta_secondaria' ),
		'progetti'             => array( 'colonne' => 'field_stpj_colonne', 'per_pagina' => 'field_stpj_per_pagina', 'schede' => 'field_stpj_schede', 'conteggi' => 'field_stpj_conteggi', 'numeri' => 'field_stpj_numeri', 'categorie' => 'field_stpj_categorie' ),
		'progetto-testo'       => array( 'occhiello' => 'field_stpt_occhiello', 'titolo' => 'field_stpt_titolo', 'testo' => 'field_stpt_testo', 'disposizione' => 'field_stpt_layout' ),
		'progetto-media'       => array(
			'colonne'     => 'field_stpm_colonne',
			'proporzione' => 'field_stpm_proporzione',
			'elementi'    => array( 'field_stpm_elementi', array( 'elementi_tipo' => 'field_stpm_tipo', 'elementi_immagine' => 'field_stpm_immagine', 'elementi_video' => 'field_stpm_video', 'elementi_copertina' => 'field_stpm_copertina', 'elementi_didascalia' => 'field_stpm_didascalia' ) ),
		),
		'slider'               => array(
			'modalita'         => 'field_stsl_modalita',
			'larghezza'        => 'field_stsl_larghezza',
			'colonne_desktop'  => 'field_stsl_colonne_desktop',
			'colonne_tablet'   => 'field_stsl_colonne_tablet',
			'colonne_mobile'   => 'field_stsl_colonne_mobile',
			'gap'              => 'field_stsl_gap',
			'proporzione'      => 'field_stsl_proporzione',
			'anteprima'        => 'field_stsl_anteprima',
			'anteprima_px'     => 'field_stsl_anteprima_px',
			'frecce'           => 'field_stsl_frecce',
			'frecce_stile'     => 'field_stsl_frecce_stile',
			'paginazione'      => 'field_stsl_paginazione',
			'autoplay'         => 'field_stsl_autoplay',
			'autoplay_secondi' => 'field_stsl_autoplay_secondi',
			'ricomincia'       => 'field_stsl_ricomincia',
			'didascalie'       => 'field_stsl_didascalie',
			'angoli'           => 'field_stsl_angoli',
			'slide'            => 'field_stsl_slide',
		),
		'progetto-crediti'     => array( 'titolo' => 'field_stpc_titolo', 'voci' => array( 'field_stpc_voci', array( 'voci_ruolo' => 'field_stpc_ruolo', 'voci_nome' => 'field_stpc_nome' ) ) ),
	);

	return $all[ $block ];
}

/* Blocco "semplice" (senza blocchi dentro) con le sue chiavi. */
function starter_pg_block( $name, $data ) {
	return starter_demo_block( $name, $data, starter_pg_keys( $name ) );
}

/**
 * Sezione finale "contattaci": Gruppo che contiene una Copertina con l'immagine di sfondo in parallasse (fissa mentre si
 * scorre), velo scuro, titolo, testo e pulsante, con molto spazio sopra e sotto.
 */
function starter_pg_cta( $title, $text, $label, $path ) {
	$image = starter_demo_image( 'Sfondo', 1, 2000, 1200, '_starter_page' );
	$url   = $image ? wp_get_attachment_url( $image ) : '';
	$inner = starter_pg_h( $title, 2, '', true ) . starter_pg_p( $text, '', true ) . starter_pg_buttons( array( array( $label, home_url( $path ) ) ), true );

	$attrs = array(
		'url'               => $url,
		'id'                => $image,
		'hasParallax'       => true,
		'dimRatio'          => 70,
		'overlayColor'      => 'secondario',
		'isUserOverlayColor' => true,
		'minHeight'         => 420,
		'align'             => 'full',
		'className'         => 'pt8 pb8 cta-cover',
		'layout'            => array( 'type' => 'constrained' ),
	);

	$cover = '<!-- wp:cover ' . wp_json_encode( $attrs, JSON_UNESCAPED_SLASHES ) . " -->\n" .
		'<div class="wp-block-cover alignfull has-parallax pt8 pb8 cta-cover" style="min-height:420px">' .
		'<div class="wp-block-cover__image-background wp-image-' . (int) $image . ' has-parallax" style="background-position:50% 50%;background-image:url(' . esc_url( $url ) . ')"></div>' .
		'<span aria-hidden="true" class="wp-block-cover__background has-secondario-background-color has-background-dim-70 has-background-dim"></span>' .
		'<div class="wp-block-cover__inner-container">' . "\n" . trim( $inner ) . "</div></div>\n<!-- /wp:cover -->";

	return starter_pg_group( $cover, '' );
}

/* ------------------------------------------------------------------- Pagine */

/* Testo di accettazione privacy per i moduli creati dal tema: con link alla pagina Privacy se c'è. */
function starter_cf7_privacy_line() {
	$privacy = get_privacy_policy_url();
	$link    = $privacy ? '<a href="' . esc_url( $privacy ) . '" target="_blank" rel="noopener">Privacy Policy</a>' : 'Privacy Policy';

	return '[acceptance privacy] Ho letto la ' . $link . ' e accetto il trattamento dei dati [/acceptance]';
}

/**
 * Modulo "Contatti" di Contact Form 7 (nome, email, oggetto, messaggio e accettazione privacy), per la pagina Contatti.
 * Se manca lo crea; il modulo di esempio di Contact Form 7 non ha l'accettazione della privacy.
 *
 * @return int ID del modulo (0 se Contact Form 7 non c'è).
 */
function starter_contact_form_create() {
	if ( ! class_exists( 'WPCF7_ContactForm' ) ) {
		return 0;
	}

	$existing = get_posts(
		array(
			'post_type'      => 'wpcf7_contact_form',
			'title'          => 'Contatti',
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);

	if ( $existing ) {
		return (int) $existing[0];
	}

	$form = '<div class="form-grid">' . "\n" .
		'<label>Nome [text* your-name autocomplete:given-name placeholder "Mario"]</label>' . "\n" .
		'<label>Cognome [text* your-surname autocomplete:family-name placeholder "Rossi"]</label>' . "\n" .
		'<label>Telefono [tel your-phone autocomplete:tel placeholder "+39"]</label>' . "\n" .
		'<label>Email [email* your-email autocomplete:email placeholder "nome@email.it"]</label>' . "\n" .
		'<div class="form-grid__full form-grid__choices"><span class="form-grid__legend">Come posso aiutarti?</span>[checkbox your-topic use_label_element "Consulenza" "Preventivo" "Assistenza" "Altro"]</div>' . "\n" .
		'<label class="form-grid__full">Note [textarea your-message placeholder "Raccontaci qualcosa sul tuo progetto"]</label>' . "\n" .
		'<div class="form-grid__full">' . starter_cf7_privacy_line() . '</div>' . "\n" .
		'</div>' . "\n\n" .
		'[hidden your-subject "Richiesta dal modulo contatti"]' . "\n\n" .
		'[submit "Invia la richiesta"]';

	$cf = WPCF7_ContactForm::get_template();
	$cf->set_title( 'Contatti' );
	$cf->set_properties(
		array(
			'form' => $form,
			'mail' => array(
				'active'             => true,
				'subject'            => '[_site_title] Nuovo messaggio dal modulo contatti',
				'sender'             => '[_site_title] <wordpress@' . wp_parse_url( home_url(), PHP_URL_HOST ) . '>',
				'recipient'          => '[_site_admin_email]',
				'body'               => "Da: [your-name] [your-surname]\nEmail: [your-email]\nTelefono: [your-phone]\nCome posso aiutarti: [your-topic]\n\nNote:\n[your-message]\n\n-- \nMessaggio inviato dal modulo contatti del sito [_site_url]",
				'additional_headers' => 'Reply-To: [your-email]',
				'attachments'        => '',
				'use_html'           => false,
				'exclude_blank'      => false,
			),
		)
	);

	return (int) $cf->save();
}

/* Shortcode del modulo Contatti (lo crea se serve), '' se il plugin non c'è. */
function starter_pg_form_shortcode() {
	$id = starter_contact_form_create();

	return $id ? '[contact-form-7 id="' . $id . '" title="Contatti"]' : '';
}

/* Video di esempio (Mixkit, licenza gratuita anche commerciale) dalla cartella images del tema (verticale per Chi siamo, orizzontale per la hero con video): lo importa nella libreria media una volta sola. */
function starter_pg_video( $file = 'demo-video-verticale.mp4', $title = 'Video verticale di esempio' ) {
	$existing = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'meta_key'       => '_starter_demo_video', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_value'     => $file, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);

	if ( $existing ) {
		return (int) $existing[0];
	}

	$src = get_theme_file_path( 'images/' . $file );

	if ( ! file_exists( $src ) ) {
		return 0;
	}

	$upload = wp_upload_bits( $file, null, (string) file_get_contents( $src ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- file del tema.

	if ( ! empty( $upload['error'] ) ) {
		return 0;
	}

	$id = wp_insert_attachment(
		array(
			'post_mime_type' => 'video/mp4',
			'post_title'     => $title,
			'post_status'    => 'inherit',
		),
		$upload['file']
	);

	if ( $id && ! is_wp_error( $id ) ) {
		update_post_meta( $id, '_starter_demo_video', $file );
		update_post_meta( $id, '_starter_page', 1 );

		return (int) $id;
	}

	return 0;
}

/* Foto segnaposto per le pagine (restano anche eliminando i contenuti di prova). */
function starter_pg_images( $count, $width = 1600, $height = 1000, $offset = 0 ) {
	$ids = array();

	for ( $i = 0; $i < $count; $i++ ) {
		$id = starter_demo_image( 'Foto ' . ( $offset + $i + 1 ), $offset + $i, $width, $height, '_starter_page' );

		if ( $id ) {
			$ids[] = $id;
		}
	}

	return $ids;
}

/* Contenuto della pagina Home standard */
function starter_pg_home() {
	$name    = get_bloginfo( 'name' );
	$hero    = starter_pg_images( 3, 2000, 1100 );
	$photo   = starter_pg_images( 1, 1200, 1800, 3 );
	$content = '';

	$content .= starter_pg_block(
		'hero-slider',
		array(
			'immagini'  => $hero,
			'occhiello' => 'Servizi professionali per la tua attività',
			'titolo'   => 'Il tuo progetto, <em>fatto bene</em>',
			'testo'    => 'Una frase che spiega in due righe cosa fai e per chi: chiara, concreta, senza giri di parole.',
			'link'     => starter_pg_link( 'Scopri i servizi', '/servizi/' ),
			'durata'   => 6,
		)
	);

	$content .= starter_pg_block(
		'striscia-valori',
		array(
			'voci' => array(
				array( 'icona' => 'shield-check', 'titolo' => 'Qualità garantita' ),
				array( 'icona' => 'truck', 'titolo' => 'Consegne puntuali' ),
				array( 'icona' => 'users', 'titolo' => 'Assistenza dedicata' ),
				array( 'icona' => 'star', 'titolo' => 'Clienti soddisfatti' ),
			),
		)
	);

	// Sezione affiancata: foto + testo scritto con i blocchi di Gutenberg.
	$content .= starter_pg_block_inner(
		'sezione-affiancata',
		array( 'occhiello' => 'Chi siamo', 'foto' => $photo[0] ?? 0, 'formato' => 'verticale', 'lato' => 'sinistra', 'sfondo' => 'nessuno' ),
		starter_pg_keys( 'sezione-affiancata' ),
		starter_pg_h( 'Persone prima di tutto' ) .
		starter_pg_p( "{$name} nasce per aiutare le persone a ottenere risultati concreti. Lavoriamo con metodo, ascoltiamo prima di proporre e restiamo accanto ai clienti anche dopo la consegna.", 'has-large-font-size' ) .
		starter_pg_p( 'Questo è un testo di esempio: sostituiscilo con la storia, i valori e le competenze della tua attività.' ) .
		starter_pg_buttons( array( array( 'Scopri chi siamo', home_url( '/chi-siamo/' ), true ) ) )
	);

	$content .= starter_pg_block(
		'sezione-voci',
		array(
			'layout'    => 'card',
			'sfondo'    => 'muted',
			'occhiello' => 'Servizi',
			'titolo'    => 'Cosa possiamo fare per te',
			'link'      => starter_pg_link( 'Tutti i servizi', '/servizi/' ),
			'voci'      => array(
				array( 'titolo' => 'Consulenza', 'testo' => 'Analizziamo la tua situazione e ti indichiamo la strada più semplice per arrivare all\'obiettivo.', 'link' => starter_pg_link( 'Scopri di più', '/servizi/' ) ),
				array( 'titolo' => 'Progetto', 'testo' => 'Trasformiamo l\'idea in un progetto chiaro, con tempi e costi definiti fin dall\'inizio.', 'link' => starter_pg_link( 'Scopri di più', '/servizi/' ) ),
				array( 'titolo' => 'Assistenza', 'testo' => 'Dopo la consegna restiamo a disposizione per manutenzione, aggiornamenti e dubbi.', 'link' => starter_pg_link( 'Scopri di più', '/servizi/' ) ),
			),
		)
	);

	// Seconda hero, con video: alternativa a quella iniziale, tra i servizi e i prodotti.
	$hero_video = starter_pg_video( 'demo-video-hero.mp4', 'Video hero di esempio' );

	if ( $hero_video ) {
		$content .= starter_pg_block(
			'hero-video',
			array(
				'occhiello' => 'Il nostro metodo di lavoro',
				'video'     => $hero_video,
				'copertina' => $hero[0] ?? 0,
				'titolo'    => 'Lavoriamo insieme, <em>passo dopo passo</em>',
				'testo'     => 'Un secondo spazio per raccontare il tuo metodo o una promozione, con un video al posto delle foto.',
				'link'      => starter_pg_link( 'Contattaci', '/contatti/' ),
				'link_2'    => starter_pg_link( 'Scopri i servizi', '/servizi/' ),
			)
		);
	}

	if ( starter_has_woocommerce() ) {
		$content .= starter_pg_block(
			'prodotti-in-evidenza',
			array(
				'occhiello' => 'Il negozio',
				'titolo'   => 'Prodotti in evidenza',
				'testo'    => 'Una selezione dal nostro negozio.',
				'link'     => array( 'title' => 'Vai al negozio', 'url' => wc_get_page_permalink( 'shop' ), 'target' => '' ),
				'sorgente' => 'recenti',
				'numero'   => 4,
			)
		);
	}

	$content .= starter_pg_block(
		'testo-affiancato',
		array(
			'lato_media' => 'destra',
			'sfondo'     => 'muted',
			'occhiello'  => 'Perché sceglierci',
			'titolo'     => 'Un metodo semplice, risultati che si vedono',
			'testo'      => 'Ci occupiamo di tutto, dall\'idea alla consegna, con un unico referente e un prezzo chiaro.',
			'checklist'  => array(
				array( 'voce' => 'Preventivo chiaro e senza sorprese' ),
				array( 'voce' => 'Un unico referente per tutto il progetto' ),
				array( 'voce' => 'Assistenza anche dopo la consegna' ),
			),
			'link'       => starter_pg_link( 'Parlaci del tuo progetto', '/contatti/' ),
			'immagine'   => ( $starter_ta_imgs = starter_pg_images( 2, 1200, 1200, 4 ) )[0] ?? 0,
			'immagine_2' => $starter_ta_imgs[1] ?? 0,
		)
	);

	$content .= starter_pg_block(
		'ultimi-articoli',
		array( 'occhiello' => 'Il blog', 'titolo' => 'Dal blog', 'testo' => 'Novità, consigli e approfondimenti.', 'numero' => 3 )
	);

	$content .= starter_pg_block(
		'recensioni',
		array(
			'occhiello' => 'Dicono di noi',
			'titolo'    => 'Le parole di chi ha lavorato con noi',
			'voci'      => array(
				array( 'nome' => 'Anna R.', 'testo' => 'Professionali e disponibili: ci hanno seguito in ogni fase e il risultato è andato oltre le aspettative.', 'data' => '2 mesi fa' ),
				array( 'nome' => 'Marco B.', 'testo' => 'Tempi rispettati, comunicazione sempre chiara. Li consiglio senza esitazioni.', 'data' => '4 mesi fa' ),
				array( 'nome' => 'Giulia T.', 'testo' => 'Ci siamo sentiti ascoltati fin dal primo incontro. Un team che fa davvero la differenza.', 'data' => '6 mesi fa' ),
			),
		)
	);

	$content .= starter_pg_cta( 'Hai un progetto in mente?', 'Raccontacelo: ti rispondiamo entro un giorno lavorativo.', 'Contattaci', '/contatti/' );

	return $content;
}

/* Contenuto della pagina Chi siamo */
function starter_pg_about() {
	$photos  = starter_pg_images( 2, 1200, 1500, 1 );
	$content = '';

	$clients = starter_pg_images( 6, 300, 300, 0 );
	$side    = starter_pg_images( 2, 900, 1200, 2 );

	$content .= starter_pg_block(
		'hero-media',
		array(
			'occhiello'       => 'Chi siamo: il nostro team e il nostro metodo',
			'titolo'          => 'Persone, metodo e <em>passione</em>',
			'testo'           => 'Una breve introduzione alla tua attività: chi sei, da quanto tempo lavori e che cosa ti distingue.',
			'cta_primaria'    => starter_pg_link( 'Parlaci del tuo progetto', '/contatti/' ),
			'cta_secondaria'  => starter_pg_link( 'Scopri i servizi', '/servizi/' ),
			'clienti'         => $clients,
			'clienti_testo'   => 'Scelti da oltre 100 clienti',
			'foto'            => $side[0] ?? 0,
			'etichetta_foto'  => 'Strategia e identità',
			'colore_etichetta_foto'  => 'cta',
			'colore_etichetta_video' => 'social',
			'video'           => starter_pg_video(),
			'copertina_video' => $side[1] ?? 0,
			'etichetta_video' => 'Produzione e contenuti',
		)
	);

	$content .= starter_pg_block_inner(
		'sezione-affiancata',
		array( 'occhiello' => 'Come siamo nati', 'foto' => $photos[0] ?? 0, 'foto_2' => $photos[1] ?? 0, 'formato' => 'verticale', 'lato' => 'destra', 'sfondo' => 'nessuno' ),
		starter_pg_keys( 'sezione-affiancata' ),
		starter_pg_h( 'La nostra storia' ) .
		starter_pg_p( 'Racconta come è nata l\'attività, quali problemi risolve e che cosa la rende diversa dalle altre. Poche righe, scritte come parleresti a un cliente.', 'has-large-font-size' ) .
		starter_pg_p( 'Puoi aggiungere altri paragrafi, un elenco o un pulsante: questo blocco è scritto con i blocchi di Gutenberg, quindi si modifica come una pagina normale.' )
	);

	$content .= starter_pg_block(
		'missione-visione',
		array(
			'voci' => array(
				array( 'occhiello' => 'Missione', 'titolo' => 'Che cosa facciamo ogni giorno', 'testo' => 'Descrivi in due o tre righe lo scopo dell\'attività e il valore che porta ai clienti.' ),
				array( 'occhiello' => 'Visione', 'titolo' => 'Dove vogliamo arrivare', 'testo' => 'Descrivi in due o tre righe il futuro che vuoi costruire e i principi che ti guidano.' ),
			),
		)
	);

	$members = starter_pg_images( 3, 800, 800, 2 );
	$content .= starter_pg_block(
		'team',
		array(
			'occhiello' => 'Il team',
			'titolo'    => 'Le persone dietro al progetto',
			'testo'     => 'Un piccolo gruppo di persone che si occupa di tutto con cura.',
			'voci'      => array(
				array( 'voci_foto' => $members[0] ?? 0, 'voci_nome' => 'Mario Rossi', 'voci_ruolo' => 'Fondatore', 'voci_bio' => 'Breve presentazione: da quanto lavora nel settore e di che cosa si occupa.' ),
				array( 'voci_foto' => $members[1] ?? 0, 'voci_nome' => 'Laura Bianchi', 'voci_ruolo' => 'Progetti', 'voci_bio' => 'Breve presentazione: da quanto lavora nel settore e di che cosa si occupa.' ),
				array( 'voci_foto' => $members[2] ?? 0, 'voci_nome' => 'Paolo Verdi', 'voci_ruolo' => 'Assistenza', 'voci_bio' => 'Breve presentazione: da quanto lavora nel settore e di che cosa si occupa.' ),
			),
		)
	);

	$content .= starter_pg_cta( 'Lavoriamo insieme?', 'Scrivici: ci piace conoscere i progetti prima ancora di fare un preventivo.', 'Contattaci', '/contatti/' );

	return $content;
}

/* Blocco "Elenco servizi" di esempio (5 servizi con menu laterale, foto, elenco con spunte e pulsante). */
function starter_pg_services_list() {
	$photos = starter_pg_images( 5, 1000, 1200, 10 );
	$link   = starter_pg_link( 'Scopri di più', '/contatti/' );
	$lines  = "Primo punto che descrive cosa è incluso\nSecondo punto, scritto in modo semplice\nTerzo punto con il risultato per il cliente";
	$items  = array(
		array( 'voci_id' => 'consulenza', 'voci_icona' => 'sparkles', 'voci_occhiello' => 'Per iniziare', 'voci_titolo' => 'Consulenza iniziale', 'voci_etichetta' => 'Consulenza', 'voci_testo' => 'Due o tre righe che spiegano a chi serve il servizio e che problema risolve.' ),
		array( 'voci_id' => 'progetto', 'voci_icona' => 'chart', 'voci_occhiello' => 'Il piano', 'voci_titolo' => 'Progetto su misura', 'voci_etichetta' => 'Progetto', 'voci_testo' => 'Due o tre righe che spiegano come lavorate e cosa riceve il cliente.' ),
		array( 'voci_id' => 'realizzazione', 'voci_icona' => 'building', 'voci_occhiello' => 'Il lavoro', 'voci_titolo' => 'Realizzazione', 'voci_etichetta' => 'Realizzazione', 'voci_testo' => 'Due o tre righe su tempi, aggiornamenti e modo di lavorare.' ),
		array( 'voci_id' => 'assistenza', 'voci_icona' => 'handshake', 'voci_occhiello' => 'Dopo la consegna', 'voci_titolo' => 'Assistenza continua', 'voci_etichetta' => 'Assistenza', 'voci_testo' => 'Due o tre righe su come restate accanto al cliente anche dopo.' ),
		array( 'voci_id' => 'extra', 'voci_icona' => 'camera', 'voci_occhiello' => 'In più', 'voci_titolo' => 'Servizi aggiuntivi', 'voci_etichetta' => 'Extra', 'voci_testo' => 'Due o tre righe su ciò che si può aggiungere al percorso.' ),
	);

	foreach ( $items as $i => $item ) {
		$items[ $i ]['voci_elenco']   = $lines;
		$items[ $i ]['voci_cta']      = $link;
		$items[ $i ]['voci_immagine'] = isset( $photos[ $i ] ) ? $photos[ $i ] : 0;
	}

	return starter_pg_block( 'elenco-servizi', array( 'voci' => $items ) );
}

/* Blocco "Sezione voci" con layout "Elenco numerato": 5 ragioni con testo introduttivo e pulsante a fianco. */
function starter_pg_numbered_reasons() {
	return starter_pg_block(
		'sezione-voci',
		array(
			'layout'    => 'numerato',
			'occhiello' => 'Perché sceglierci',
			'titolo'    => 'Cinque ragioni per affidarci il tuo progetto',
			'testo'     => 'Una frase che riassume il metodo: professionalità, trasparenza, risultati.',
			'link'      => starter_pg_link( 'Parla con noi', '/contatti/' ),
			'voci'      => array(
				array( 'titolo' => 'Prima ragione', 'testo' => 'Due righe che spiegano perché è un vantaggio per chi ti sceglie.' ),
				array( 'titolo' => 'Seconda ragione', 'testo' => 'Due righe che spiegano perché è un vantaggio per chi ti sceglie.' ),
				array( 'titolo' => 'Terza ragione', 'testo' => 'Due righe che spiegano perché è un vantaggio per chi ti sceglie.' ),
				array( 'titolo' => 'Quarta ragione', 'testo' => 'Due righe che spiegano perché è un vantaggio per chi ti sceglie.' ),
				array( 'titolo' => 'Quinta ragione', 'testo' => 'Due righe che spiegano perché è un vantaggio per chi ti sceglie.' ),
			),
		)
	);
}

/* Contenuto della pagina Servizi */
function starter_pg_services() {
	$content = '';

	$content .= starter_pg_block(
		'intestazione-pagina',
		array(
			'occhiello' => 'Servizi di consulenza, progetto e assistenza',
			'titolo'    => 'Come possiamo aiutarti',
			'testo'     => 'Un elenco chiaro di ciò che offri, scritto pensando a chi lo legge per la prima volta.',
		)
	);

	$content .= starter_pg_services_list();

	$content .= starter_pg_block(
		'sezione-voci',
		array(
			'layout' => 'icone',
			'sfondo' => 'muted',
			'titolo' => 'I nostri servizi',
			'voci'   => array(
				array( 'icona' => 'sparkles', 'titolo' => 'Servizio uno', 'testo' => 'Due righe che spiegano di che cosa si tratta e a chi serve.' ),
				array( 'icona' => 'chart', 'titolo' => 'Servizio due', 'testo' => 'Due righe che spiegano di che cosa si tratta e a chi serve.' ),
				array( 'icona' => 'handshake', 'titolo' => 'Servizio tre', 'testo' => 'Due righe che spiegano di che cosa si tratta e a chi serve.' ),
				array( 'icona' => 'shield-check', 'titolo' => 'Servizio quattro', 'testo' => 'Due righe che spiegano di che cosa si tratta e a chi serve.' ),
				array( 'icona' => 'building', 'titolo' => 'Servizio cinque', 'testo' => 'Due righe che spiegano di che cosa si tratta e a chi serve.' ),
				array( 'icona' => 'users', 'titolo' => 'Servizio sei', 'testo' => 'Due righe che spiegano di che cosa si tratta e a chi serve.' ),
			),
		)
	);

	$content .= starter_pg_block(
		'sezione-voci',
		array(
			'layout'    => 'passi',
			'sfondo'    => 'accent',
			'occhiello' => 'Come lavoriamo',
			'titolo'    => 'Quattro passi, dall\'idea alla consegna',
			'voci'      => array(
				array( 'titolo' => 'Ascolto', 'testo' => 'Ci raccontiamo: obiettivi, tempi e budget.' ),
				array( 'titolo' => 'Proposta', 'testo' => 'Ti mostriamo un piano chiaro con costi definiti.' ),
				array( 'titolo' => 'Realizzazione', 'testo' => 'Lavoriamo con aggiornamenti regolari lungo il percorso.' ),
				array( 'titolo' => 'Consegna', 'testo' => 'Ti affianchiamo anche dopo, per qualsiasi dubbio.' ),
			),
		)
	);

	$content .= starter_pg_numbered_reasons();

	$content .= starter_pg_block(
		'testo-affiancato',
		array(
			'lato_media' => 'sinistra',
			'sfondo'     => 'nessuno',
			'occhiello'  => 'Perché noi',
			'titolo'     => 'Quello che ci distingue',
			'testo'      => 'Un paragrafo che spiega il tuo punto di forza: esperienza, rapidità, attenzione al dettaglio.',
			'checklist'  => array(
				array( 'voce' => 'Esperienza maturata su progetti simili' ),
				array( 'voce' => 'Comunicazione semplice e trasparente' ),
				array( 'voce' => 'Risultati misurabili' ),
			),
			'immagine'   => starter_pg_images( 1, 1200, 1200, 5 )[0] ?? 0,
		)
	);

	$content .= starter_pg_cta( 'Ti serve un preventivo?', 'Scrivici due righe sul tuo progetto: ti rispondiamo con una proposta chiara.', 'Richiedi un preventivo', '/contatti/' );

	return $content;
}

/* Pagina Credits: titolo, frase e un elenco con il ruolo di chi ha realizzato il sito. */
function starter_pg_credits() {
	$name = get_bloginfo( 'name' );
	$list = '<!-- wp:list -->' . "\n" . '<ul class="wp-block-list"><!-- wp:list-item -->' . "\n" .
		'<li><strong>Art Direction, Web Design e Sviluppo WordPress:</strong> <a href="https://buranidenis.com" target="_blank" rel="noopener">Denis Burani</a></li>' . "\n" .
		'<!-- /wp:list-item --></ul>' . "\n" . '<!-- /wp:list -->' . "\n\n";

	return starter_pg_group(
		starter_pg_h( 'Credits', 1, 'mb2' ) . starter_pg_p( 'Il sito di ' . esc_html( $name ) . ' è stato realizzato da:', 'mb1' ) . $list,
		'pt3 pb5'
	);
}

/* Contenuto della pagina Contatti */
function starter_pg_contacts() {
	$content  = '';
	$content .= starter_pg_block(
		'intestazione-pagina',
		array(
			'occhiello' => 'Contatti: richiedi un preventivo gratuito',
			'titolo'    => 'Parliamone',
			'testo'     => 'Compila il modulo o scrivici direttamente: ti rispondiamo il prima possibile.',
		)
	);

	// I recapiti partono da quelli di Opzioni → Footer ma vivono nel blocco: da qui si modificano liberamente.
	$recapiti = array();

	foreach ( starter_get_footer_contacts() as $contact ) {
		$recapiti[] = array(
			'icona' => starter_contact_icon( $contact['href'] ),
			'testo' => $contact['text'],
		);
	}

	$content .= starter_pg_block(
		'contatti',
		array(
			'occhiello'      => 'Scrivici',
			'titolo'         => 'Dove e come trovarci',
			'recapiti'       => $recapiti,
			'shortcode_form' => starter_pg_form_shortcode(),
		)
	);

	$content .= starter_pg_block(
		'mappa',
		array(
			'indirizzo' => 'Piazza del Duomo, Milano',
			'pulsante'  => 'Indicazioni stradali',
		)
	);

	return $content;
}

/**
 * Crea (o aggiorna se ancora vuote) le pagine standard e imposta la Home come pagina iniziale.
 *
 * @param bool $force true = riscrive il contenuto delle pagine già esistenti.
 * @return int[] ID delle pagine: home, chi-siamo, servizi, contatti.
 */
function starter_pages_create( $force = false ) {
	$defs = array(
		'home'      => array( __( 'Home', 'slug-theme' ), 'starter_pg_home' ),
		'chi-siamo' => array( __( 'Chi siamo', 'slug-theme' ), 'starter_pg_about' ),
		'servizi'   => array( __( 'Servizi', 'slug-theme' ), 'starter_pg_services' ),
		'contatti'  => array( __( 'Contatti', 'slug-theme' ), 'starter_pg_contacts' ),
		'credits'   => array( __( 'Credits', 'slug-theme' ), 'starter_pg_credits' ),
	);
	$ids  = array();

	foreach ( $defs as $slug => $def ) {
		$id = starter_demo_page_by_slug( $slug );

		if ( $id && ! $force && '' !== trim( (string) get_post_field( 'post_content', $id ) ) ) {
			$ids[ $slug ] = $id;
			continue;
		}

		$content = call_user_func( $def[1] );

		if ( $id ) {
			wp_update_post(
				array(
					'ID'           => $id,
					'post_content' => wp_slash( $content ),
					'post_status'  => 'publish',
				)
			);
		} else {
			$id = wp_insert_post(
				array(
					'post_type'    => 'page',
					'post_status'  => 'publish',
					'post_name'    => $slug,
					'post_title'   => $def[0],
					'post_content' => wp_slash( $content ),
				)
			);
		}

		if ( $id && ! is_wp_error( $id ) ) {
			update_post_meta( $id, '_starter_page', 1 );
			$ids[ $slug ] = (int) $id;
		}
	}

	starter_pages_link_footer();

	if ( starter_has_woocommerce() ) {
		starter_wishlist_page_create();
	}

	if ( ! empty( $ids['home'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $ids['home'] );
	}

	return $ids;
}

/**
 * Modulo "Newsletter" di Contact Form 7 (nome, email, accettazione privacy) per lo spazio libero del footer.
 * Se manca lo crea e, se lo spazio libero del footer è vuoto, ci mette il suo shortcode con titolo e sottotitolo.
 * Le iscrizioni arrivano per email e in Flamingo (con la pagina di provenienza, vedi inc/cf7.php).
 *
 * @return int ID del modulo (0 se Contact Form 7 non c'è).
 */
function starter_newsletter_form_create() {
	if ( ! class_exists( 'WPCF7_ContactForm' ) ) {
		return 0;
	}

	$existing = get_posts(
		array(
			'post_type'      => 'wpcf7_contact_form',
			'title'          => 'Newsletter',
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);
	$id       = $existing ? (int) $existing[0] : 0;

	if ( ! $id ) {
		$form    = '[hidden your-subject "Iscrizione alla newsletter"]' . "\n\n" .
			'<label>Nome [text* nome autocomplete:name placeholder "Il tuo nome"]</label>' . "\n\n" .
			'<label>Email [email* email autocomplete:email placeholder "La tua email"]</label>' . "\n\n" .
			starter_cf7_privacy_line() . "\n\n" .
			'[submit "Iscriviti"]';

		$cf = WPCF7_ContactForm::get_template();
		$cf->set_title( 'Newsletter' );
		$cf->set_properties(
			array(
				'form' => $form,
				'mail' => array(
					'active'             => true,
					'subject'            => '[_site_title] Nuova iscrizione alla newsletter',
					'sender'             => '[_site_title] <wordpress@' . wp_parse_url( home_url(), PHP_URL_HOST ) . '>',
					'recipient'          => '[_site_admin_email]',
					'body'               => "Nome: [nome]\nEmail: [email]\n\n-- \nIscrizione dal modulo newsletter del sito [_site_url]",
					'additional_headers' => 'Reply-To: [email]',
					'attachments'        => '',
					'use_html'           => false,
					'exclude_blank'      => false,
				),
			)
		);
		$id = (int) $cf->save();
	}

	// Spazio libero del footer: solo se è ancora vuoto.
	if ( $id && '' === trim( (string) get_theme_mod( 'starter_footer_newsletter_shortcode', '' ) ) ) {
		set_theme_mod( 'starter_footer_newsletter_shortcode', '[contact-form-7 id="' . $id . '" title="Newsletter"]' );
		set_theme_mod( 'starter_footer_newsletter_title', 'Newsletter' );
		set_theme_mod( 'starter_footer_newsletter_subtitle', 'Novità e consigli, senza spam.' );
	}

	return $id;
}

/* Collega nel footer i link Privacy Policy e Cookie Policy alle pagine create dal tema (solo se i campi sono vuoti). */
function starter_pages_link_footer() {
	$credits = starter_demo_page_by_slug( 'credits' );

	if ( $credits ) {
		if ( '' === trim( (string) get_theme_mod( 'starter_footer_credits_label', '' ) ) ) {
			set_theme_mod( 'starter_footer_credits_label', 'Credits' );
		}

		if ( in_array( trim( (string) get_theme_mod( 'starter_footer_credits_url', '' ) ), array( '', '#' ), true ) ) {
			set_theme_mod( 'starter_footer_credits_url', get_permalink( $credits ) );
		}
	}

	foreach ( array( 'privacy' => array( 'privacy-policy', 'Privacy Policy' ), 'cookie' => array( 'cookie-policy', 'Cookie Policy' ) ) as $key => $page ) {
		$id = starter_demo_page_by_slug( $page[0] );

		if ( ! $id ) {
			continue;
		}

		if ( '' === trim( (string) get_theme_mod( "starter_footer_{$key}_label", '' ) ) ) {
			set_theme_mod( "starter_footer_{$key}_label", $page[1] );
		}

		if ( in_array( trim( (string) get_theme_mod( "starter_footer_{$key}_url", '' ) ), array( '', '#' ), true ) ) {
			set_theme_mod( "starter_footer_{$key}_url", get_permalink( $id ) );
		}
	}
}

/* Contenuto della pagina Preferiti: titolo come Chi siamo (occhiello, titolo, testo), l'elenco e il banner finale. */
function starter_wishlist_page_content() {
	$content  = starter_pg_block(
		'intestazione-pagina',
		array(
			'occhiello' => 'Prodotti preferiti salvati',
			'titolo'    => 'Preferiti',
			'testo'     => 'I prodotti che hai salvato con il cuore, per ritrovarli quando vuoi.',
		)
	);
	$content .= "<!-- wp:shortcode -->\n[starter_preferiti]\n<!-- /wp:shortcode -->\n\n";
	$content .= starter_pg_cta( 'Cerchi qualcos\'altro?', 'Dai un\'occhiata a tutti i prodotti del negozio.', 'Vai al negozio', '/negozio/' );

	return $content;
}

/* Pagina "Preferiti" (shortcode [starter_preferiti]): la crea se manca (o se ha solo lo shortcode) e la collega in Opzioni → Shop. */
function starter_wishlist_page_create() {
	$id = starter_demo_page_by_slug( 'preferiti' );

	if ( ! $id ) {
		$id = (int) wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_name'    => 'preferiti',
				'post_title'   => __( 'Preferiti', 'slug-theme' ),
				'post_content' => wp_slash( starter_wishlist_page_content() ),
			)
		);
	} elseif ( false === strpos( (string) get_post_field( 'post_content', $id ), 'intestazione-pagina' ) ) {
		wp_update_post(
			array(
				'ID'           => $id,
				'post_content' => wp_slash( starter_wishlist_page_content() ),
			)
		);
	}

	if ( $id ) {
		update_post_meta( $id, '_starter_page', 1 );
		set_theme_mod( 'starter_shop_wishlist_page', $id );
	}

	return $id;
}
