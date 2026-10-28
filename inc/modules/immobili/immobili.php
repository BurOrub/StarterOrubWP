<?php
/**
 * Modulo Immobili (si accende da Personalizza → Opzioni → Moduli (CPT); spento, nessun file di questa cartella viene caricato).
 *
 * Portato da Di Guida Real Estate (ultima versione) con lo stile del tema Starter: colori, font e dimensioni sono quelli delle
 * opzioni del tema, nessun colore o font del sito di origine.
 *
 * Contiene:
 * - il tipo di contenuto Immobile (solo titolo e immagine; il resto sono campi) e le sue tassonomie (Tipologia, Contratto, Zona, Consulente, Evidenza);
 * - la scheda dell'immobile (templates/single-immobile.php): galleria con lightbox, dati, planimetria, tour virtuale 3D, video, mappa, consulente e modulo;
 * - la scheda in PDF (immobili-pdf.php + libreria Dompdf in dompdf/, caricata solo quando si crea un PDF);
 * - i filtri dell'elenco (immobili-filters.php) e i blocchi Elenco immobili, Immobili in evidenza e Hero home (template-parts/);
 * - la pagina "Immobili" (creata all'accensione) e un immobile di esempio (sample/).
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/immobili-helpers.php';
require_once __DIR__ . '/immobili-cpt.php';
require_once __DIR__ . '/immobili-taxonomies.php';
require_once __DIR__ . '/immobili-filters.php';
require_once __DIR__ . '/immobili-pdf.php';

/* ------------------------------------------------------------------ Icone */

/* Icone dell'immobiliare (superficie, locali, bagni, punti d'interesse…): si aggiungono a quelle del tema, che hanno la precedenza. */
function starter_imm_icon_paths() {
	return array(
		'area'          => '<rect x="3" y="3" width="18" height="18" rx="4"/><path d="M3 9h18"/><path d="M9 21V9"/>',
		'home'          => '<path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V21h14V9.5"/><path d="M10 21v-6h4v6"/>',
		'bed'           => '<path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/><path d="M6 8v9"/>',
		'bath'          => '<path d="M9 5 7.6 3.6A2.1 2.1 0 0 0 4 5v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5"/><path d="M2 12h20"/><path d="M7 19v2"/><path d="M17 19v2"/>',
		'layers'        => '<path d="M12.8 2.2a2 2 0 0 0-1.6 0L2.6 6.1a1 1 0 0 0 0 1.8l8.6 3.9a2 2 0 0 0 1.6 0l8.6-3.9a1 1 0 0 0 0-1.8Z"/><path d="m22 12.6-9.2 4.2a2 2 0 0 1-1.6 0L2 12.6"/><path d="m22 17.6-9.2 4.2a2 2 0 0 1-1.6 0L2 17.6"/>',
		'zap'           => '<path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"/>',
		'download'      => '<path d="M12 3v12"/><path d="m7 10 5 5 5-5"/><path d="M5 21h14"/>',
		'images'        => '<rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.1-3.1a2 2 0 0 0-2.8 0L6 21"/>',
		'armchair'      => '<path d="M20 9V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v3"/><path d="M2 16a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-5a2 2 0 0 0-4 0v1.5a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5V11a2 2 0 0 0-4 0z"/><path d="M4 18v2"/><path d="M20 18v2"/>',
		'camera'        => '<path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/>',
		'train'         => '<rect width="16" height="16" x="4" y="3" rx="2"/><path d="M4 11h16"/><path d="M12 3v8"/><path d="m8 19-2 3"/><path d="m18 22-2-3"/><path d="M8 15h.01"/><path d="M16 15h.01"/>',
		'bus'           => '<path d="M8 6v6"/><path d="M15 6v6"/><path d="M2 12h19.6"/><path d="M18 18h3s.5-1.7.8-2.8c.1-.4.2-.8.2-1.2 0-.4-.1-.8-.2-1.2l-1.4-5C20.1 6.8 19.1 6 18 6H4a2 2 0 0 0-2 2v10h3"/><circle cx="7" cy="18" r="2"/><path d="M9 18h5"/><circle cx="16" cy="18" r="2"/>',
		'tree'          => '<path d="M10 10v.2A3 3 0 0 1 8.9 16H5a3 3 0 0 1-1-5.8V10a3 3 0 0 1 6 0Z"/><path d="M7 16v6"/><path d="M13 19v3"/><path d="M12 19h8.3a1 1 0 0 0 .7-1.7L18 14h.3a1 1 0 0 0 .7-1.7L16 9h.2a1 1 0 0 0 .8-1.7L13 3l-1.4 1.5"/>',
		'school'        => '<path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"/><path d="M22 10v6"/><path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"/>',
		'bag'           => '<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>',
		'utensils'      => '<path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/>',
		'cross'         => '<path d="M12 8v8"/><path d="M8 12h8"/><rect width="18" height="18" x="3" y="3" rx="2"/>',
		'cart'          => '<circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>',
		'hospital'      => '<path d="M12 6v4"/><path d="M14 14h-4"/><path d="M14 18h-4"/><path d="M14 8h-4"/><path d="M18 12h2a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-9a2 2 0 0 1 2-2h2"/><path d="M18 22V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v18"/>',
		'pill'          => '<path d="m10.5 20.5 10-10a4.95 4.95 0 1 0-7-7l-10 10a4.95 4.95 0 1 0 7 7Z"/><path d="m8.5 8.5 7 7"/>',
		'file-down'     => '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M12 18v-6"/><path d="m9 15 3 3 3-3"/>',
		'ruler'         => '<path d="M21.3 15.3a2.4 2.4 0 0 1 0 3.4l-2.6 2.6a2.4 2.4 0 0 1-3.4 0L2.7 8.7a2.41 2.41 0 0 1 0-3.4l2.6-2.6a2.41 2.41 0 0 1 3.4 0Z"/><path d="m14.5 12.5 2-2"/><path d="m11.5 9.5 2-2"/><path d="m8.5 6.5 2-2"/><path d="m17.5 15.5 2-2"/>',
		'cube'          => '<path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/>',
		'play'          => '<rect width="18" height="14" x="3" y="5" rx="3"/><path d="m10 9 5 3-5 3z"/>',
		'sliders'       => '<path d="M21 4h-7"/><path d="M10 4H3"/><path d="M21 12h-9"/><path d="M8 12H3"/><path d="M21 20h-5"/><path d="M12 20H3"/><path d="M14 2v4"/><path d="M8 10v4"/><path d="M16 18v4"/>',
	);
}

add_filter(
	'starter_icon_paths',
	static function ( $paths ) {
		return array_merge( $paths, starter_imm_icon_paths() );
	}
);

/* ------------------------------------------------------------------ Immagini */

add_action(
	'after_setup_theme',
	static function () {
		add_image_size( 'starter-immobile-card', 720, 828, true );
		add_image_size( 'starter-immobile-card-sm', 480, 552, true );
		add_image_size( 'starter-hero-thumb', 128, 128, true );
	},
	11
);


/* ------------------------------------------------------------------ Scheda e stili */

add_filter(
	'single_template',
	static function ( $template ) {
		$own = __DIR__ . '/templates/single-immobile.php';

		return ( is_singular( 'immobile' ) && is_readable( $own ) ) ? $own : $template;
	}
);

/* Vero se la pagina ha bisogno degli stili e degli script degli immobili. */
function starter_imm_needs_assets() {
	return is_singular( 'immobile' ) || starter_page_has_block( 'elenco-immobili' ) || starter_page_has_block( 'immobili-in-evidenza' ) || starter_page_has_block( 'immobili-hero-home' );
}

/* Il blocco Elenco immobili usa i filtri comuni del tema (CSS/filters.css). */
add_filter(
	'starter_needs_filters_css',
	static function ( $needed ) {
		return $needed || starter_page_has_block( 'elenco-immobili' );
	}
);

/* File compilato (CSS/dist, JS/dist) se aggiornato rispetto al sorgente, altrimenti il sorgente. */
function starter_imm_asset( $source, $dist ) {
	$src_file  = get_theme_file_path( $source );
	$dist_file = get_theme_file_path( $dist );

	return ( file_exists( $dist_file ) && filemtime( $dist_file ) >= filemtime( $src_file ) ) ? $dist : $source;
}

add_action(
	'wp_enqueue_scripts',
	static function () {
		if ( ! starter_imm_needs_assets() ) {
			return;
		}

		$css = starter_imm_asset( 'CSS/immobili.css', 'CSS/dist/immobili.min.css' );
		$js  = starter_imm_asset( 'JS/immobili.js', 'JS/dist/immobili.min.js' );

		wp_enqueue_style( 'starter-immobili', get_template_directory_uri() . '/' . $css, array( 'starter-theme-style' ), starter_asset_version( $css ) );
		wp_enqueue_script( 'starter-immobili', get_template_directory_uri() . '/' . $js, array(), starter_asset_version( $js ), true );
	},
	20
);

/* Anche l'editor a blocchi mostra i blocchi del modulo con il loro stile. */
add_action(
	'after_setup_theme',
	static function () {
		add_editor_style( 'CSS/immobili.css' );
	},
	11
);

/* Starter Live: i campi della scheda dell'immobile (campi, non blocchi) si modificano dal frontend come quelli dei blocchi. */
add_filter(
	'starter_live_meta_post_types',
	static function ( $types ) {
		$types[] = 'immobile';

		return $types;
	}
);

/* L'immagine "hero" dell'immobile compare solo nel blocco Hero home, non nella scheda: lì non va agganciata a una delle foto. */
add_filter(
	'starter_live_meta_post_skip',
	static function ( $skip, $post_type ) {
		return 'immobile' === $post_type ? array_merge( (array) $skip, array( 'immagine_hero' ) ) : $skip;
	},
	10,
	2
);

/* I blocchi Elenco immobili e Hero home aprono la pagina con la loro intestazione: page.php non aggiunge il titolo. */
add_filter(
	'starter_page_header_blocks',
	static function ( $blocks ) {
		return array_merge( $blocks, array( 'elenco-immobili', 'immobili-hero-home' ) );
	}
);

/* Pagina "Immobili" e pagine con i blocchi: breadcrumbs e menu (vedi starter_cpt_list_page in inc/breadcrumbs.php). */
add_filter(
	'starter_cpt_list_page',
	static function ( $page_id, $post_type ) {
		return 'immobile' === $post_type ? starter_imm_page_id() : $page_id;
	},
	10,
	2
);

/* ------------------------------------------------------------------ Opzioni (Personalizza → Opzioni → Immobili) */

add_action(
	'customize_register',
	static function ( $wp_customize ) {
		$wp_customize->add_section(
			'starter_options_immobili',
			array(
				'title'       => __( 'Immobili', 'slug-theme' ),
				'description' => __( 'Scheda dell\'immobile. L\'elenco si crea nella pagina Immobili con il blocco "Elenco immobili".', 'slug-theme' ),
				'panel'       => 'starter_options',
				'priority'    => 13,
			)
		);

		$wp_customize->add_setting(
			'starter_imm_form_request',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'starter_imm_form_request',
			array(
				'type'        => 'text',
				'section'     => 'starter_options_immobili',
				'label'       => __( 'Shortcode del modulo "Richiedi informazioni"', 'slug-theme' ),
				'description' => __( 'Compare nella scheda dell\'immobile, accanto al consulente. Es. [contact-form-7 id="12"]. Il modulo di esempio è creato da solo quando accendi il modulo.', 'slug-theme' ),
			)
		);
	},
	20
);

/* ------------------------------------------------------------------ Accensione del modulo */

/* Modulo CF7 "Richiedi informazioni immobile" (nome, email, telefono, note, privacy), creato una volta e collegato nelle opzioni. */
function starter_imm_request_form_create() {
	if ( ! class_exists( 'WPCF7_ContactForm' ) ) {
		return '';
	}

	$existing = get_posts(
		array(
			'post_type'      => 'wpcf7_contact_form',
			'title'          => 'Richiedi informazioni immobile',
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);

	if ( $existing ) {
		$id = (int) $existing[0];
	} else {
		$form = '<label class="screen-reader-text" for="imm-nome">Nome e cognome</label>' . "\n" .
			'[text* your-name id:imm-nome autocomplete:name placeholder "Nome e cognome"]' . "\n\n" .
			'<label class="screen-reader-text" for="imm-email">Email</label>' . "\n" .
			'[email* your-email id:imm-email autocomplete:email placeholder "Email"]' . "\n\n" .
			'<label class="screen-reader-text" for="imm-tel">Telefono</label>' . "\n" .
			'[tel your-phone id:imm-tel autocomplete:tel placeholder "Telefono"]' . "\n\n" .
			'<label class="screen-reader-text" for="imm-note">Note</label>' . "\n" .
			'[textarea your-message id:imm-note x3 placeholder "Il tuo messaggio"]' . "\n\n" .
			starter_cf7_privacy_line() . "\n\n" .
			'[submit "Invia richiesta"]';

		$cf = WPCF7_ContactForm::get_template();
		$cf->set_title( 'Richiedi informazioni immobile' );
		$cf->set_properties(
			array(
				'form' => $form,
				'mail' => array(
					'active'             => true,
					'subject'            => 'Richiesta informazioni: [_post_title]',
					'sender'             => '[_site_title] <wordpress@' . wp_parse_url( home_url(), PHP_URL_HOST ) . '>',
					'recipient'          => '[_site_admin_email]',
					'body'               => "Nuova richiesta di informazioni per l'immobile:\n[_post_title]\n[_post_url]\n\nNome: [your-name]\nEmail: [your-email]\nTelefono: [your-phone]\n\nMessaggio:\n[your-message]\n\n-- \nRichiesta inviata dal sito [_site_title] ([_site_url])",
					'additional_headers' => 'Reply-To: [your-name] <[your-email]>',
					'use_html'           => false,
					'exclude_blank'      => false,
				),
				'additional_settings' => 'flamingo_subject: "[_post_title]"',
			)
		);
		$id = (int) $cf->save();
	}

	return $id ? '[contact-form-7 id="' . $id . '" title="Richiedi informazioni immobile"]' : '';
}

/* Accensione: crea il modulo "Richiedi informazioni" se manca (la pagina Immobili la crea il registro dei moduli). */
function starter_immobili_activate() {
	if ( '' === trim( (string) get_theme_mod( 'starter_imm_form_request', '' ) ) ) {
		$shortcode = starter_imm_request_form_create();

		if ( $shortcode ) {
			set_theme_mod( 'starter_imm_form_request', $shortcode );
		}
	}
}

/* Contenuto della pagina Immobili: il blocco "Elenco immobili" (ha la sua intestazione). */
function starter_immobili_page_content() {
	return starter_pg_block(
		'elenco-immobili',
		array(
			'occhiello' => 'Immobili in vendita e in affitto',
			'titolo'    => 'Immobili',
			'testo'     => 'Una selezione di immobili con tutti i dettagli. Usa i filtri per trovare quello che cerchi.',
		)
	);
}

/* ------------------------------------------------------------------ Contenuti di prova */

/**
 * Immobile di esempio (da Di Guida, con 4 foto e una planimetria): si importa una volta sola, con i suoi campi e le sue categorie.
 *
 * @return int Numero di immobili creati.
 */
function starter_immobili_demo() {
	$data = json_decode( (string) file_get_contents( __DIR__ . '/sample/immobile.json' ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

	if ( ! is_array( $data ) || ! post_type_exists( 'immobile' ) ) {
		return 0;
	}

	starter_imm_seed_initial_terms();
	starter_module_ensure_page( 'immobili' );

	$exists = get_posts(
		array(
			'post_type'      => 'immobile',
			'post_status'    => 'any',
			'name'           => sanitize_title( $data['title'] ),
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);

	if ( $exists ) {
		return starter_imm_demo_variants( (int) $exists[0] );
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$post_id = wp_insert_post(
		array(
			'post_type'   => 'immobile',
			'post_status' => 'publish',
			'post_title'  => $data['title'],
			'menu_order'  => (int) $data['menu_order'],
		)
	);

	if ( ! $post_id || is_wp_error( $post_id ) ) {
		return 0;
	}

	update_post_meta( $post_id, '_starter_demo', 1 );

	// Foto: ogni file di sample/ si importa una sola volta e si riusa nei vari campi.
	$cache  = array();
	$import = static function ( $file ) use ( &$cache, $post_id ) {
		if ( isset( $cache[ $file ] ) ) {
			return $cache[ $file ];
		}

		$source = __DIR__ . '/sample/' . $file;
		$tmp    = wp_tempnam( $file );

		if ( ! is_readable( $source ) || ! copy( $source, $tmp ) ) {
			return 0;
		}

		$id = media_handle_sideload(
			array(
				'name'     => 'immobile-di-esempio-' . $file,
				'tmp_name' => $tmp,
			),
			$post_id
		);

		if ( is_wp_error( $id ) ) {
			wp_delete_file( $tmp );

			return 0;
		}

		update_post_meta( $id, '_starter_demo', 1 );
		update_post_meta( $id, '_wp_attachment_image_alt', 'Immobile di esempio' );
		$cache[ $file ] = (int) $id;

		return (int) $id;
	};

	foreach ( $data['meta'] as $key => $value ) {
		update_post_meta( $post_id, $key, wp_slash( maybe_unserialize( $value ) ) );
	}

	$images = $data['images'];
	$thumb  = $import( $images['featured'] );

	if ( $thumb ) {
		set_post_thumbnail( $post_id, $thumb );
	}

	$hero = $import( $images['hero'] );
	$ids  = array_values( array_filter( array_map( $import, $images['gallery'] ) ) );
	$plan = array_values( array_filter( array_map( $import, $images['plan'] ) ) );

	update_post_meta( $post_id, 'immagine_hero', $hero );
	update_post_meta( $post_id, '_immagine_hero', $data['refs']['immagine_hero'] );
	update_post_meta( $post_id, 'galleria', $ids );
	update_post_meta( $post_id, '_galleria', $data['refs']['galleria'] );
	update_post_meta( $post_id, 'planimetria', $plan );
	update_post_meta( $post_id, '_planimetria', $data['refs']['planimetria'] );

	// Categorie: i termini mancanti si creano (Zona ha il comune e può averne altri sotto).
	foreach ( $data['terms'] as $taxonomy => $names ) {
		$term_ids = array();

		foreach ( $names as $name ) {
			$term = term_exists( $name, $taxonomy );
			$term = $term ? $term : wp_insert_term( $name, $taxonomy );

			if ( ! is_wp_error( $term ) ) {
				$term_ids[] = (int) ( is_array( $term ) ? $term['term_id'] : $term );
			}
		}

		wp_set_object_terms( $post_id, $term_ids, $taxonomy );
	}

	return 1 + starter_imm_demo_variants( $post_id );
}

/**
 * Altri due immobili di esempio, copie dell'immobile base con dati diversi (contratto, tipologia, zona, locali, bagni, prezzo…):
 * servono a mostrare cosa fanno i filtri dell'elenco. Riusano le stesse foto, quindi non pesano.
 *
 * @param int $base_id ID dell'immobile di esempio.
 * @return int Numero di immobili creati.
 */
function starter_imm_demo_variants( $base_id ) {
	$variants = array(
		'bilocale-luminoso-in-affitto' => array(
			'title'  => 'Bilocale luminoso in affitto',
			'order'  => 1,
			'meta'   => array(
				'titolo_pagina'     => 'Bilocale luminoso vicino alla metropolitana',
				'apertura'          => 'Un bilocale arredato con gusto, ideale per chi cerca comodità e collegamenti. Testo di esempio da sostituire.',
				'prezzo'            => '1450',
				'superficie'        => '62',
				'locali'            => '2',
				'locali_plus'       => '0',
				'camere'            => '1',
				'bagni'             => '1',
				'nota_locali'       => 'con angolo cottura',
				'nota_bagni'        => 'finestrato',
				'piano'             => '2° piano',
				'nota_piano'        => 'Con ascensore',
				'classe_energetica' => 'C',
				'epi'               => '95,00 kWh/m² anno',
				'indirizzo'         => 'Via dell\'Esempio 12',
				'titolo_zona'       => 'Un quartiere vivace',
			),
			'terms'  => array(
				'tipologia' => array( 'Appartamento' ),
				'contratto' => array( 'Affitto' ),
				'zona'      => array( 'Milano', 'Isola' ),
				'evidenza'  => array( 'In evidenza home' ),
			),
		),
		'villa-all-asta-bergamo'       => array(
			'title'  => 'Villa all\'asta con giardino',
			'order'  => 2,
			'meta'   => array(
				'titolo_pagina'     => 'Villa indipendente con ampio giardino',
				'apertura'          => 'Villa su due livelli con giardino e box, in vendita all\'asta. Testo di esempio da sostituire.',
				'prezzo'            => '480000',
				'superficie'        => '320',
				'locali'            => '7',
				'locali_plus'       => '0',
				'camere'            => '4',
				'bagni'             => '3',
				'nota_locali'       => 'più taverna',
				'nota_bagni'        => 'due con vasca',
				'piano'             => 'Su due livelli',
				'nota_piano'        => 'Con giardino privato',
				'classe_energetica' => 'G',
				'epi'               => '210,00 kWh/m² anno',
				'indirizzo'         => 'Via del Campo 3',
				'titolo_zona'       => 'Tra verde e servizi',
			),
			'terms'  => array(
				'tipologia' => array( 'Villa' ),
				'contratto' => array( 'Asta' ),
				'zona'      => array( 'Bergamo' ),
				'evidenza'  => array( 'In evidenza home' ),
			),
		),
	);

	$base = get_post( $base_id );

	if ( ! $base ) {
		return 0;
	}

	$created = 0;

	foreach ( $variants as $slug => $variant ) {
		$exists = get_posts(
			array(
				'post_type'      => 'immobile',
				'post_status'    => 'any',
				'name'           => $slug,
				'posts_per_page' => 1,
				'fields'         => 'ids',
			)
		);

		if ( $exists ) {
			continue;
		}

		$new_id = wp_insert_post(
			array(
				'post_type'   => 'immobile',
				'post_status' => 'publish',
				'post_name'   => $slug,
				'post_title'  => $variant['title'],
				'menu_order'  => (int) $variant['order'],
			)
		);

		if ( ! $new_id || is_wp_error( $new_id ) ) {
			continue;
		}

		// Tutti i campi dell'immobile base (stesse foto, stesse voci), poi si cambiano quelli indicati.
		foreach ( get_post_meta( $base_id ) as $key => $values ) {
			if ( in_array( $key, array( '_edit_lock', '_edit_last', '_wp_old_slug' ), true ) || ! isset( $values[0] ) ) {
				continue;
			}

			update_post_meta( $new_id, $key, wp_slash( maybe_unserialize( $values[0] ) ) );
		}

		foreach ( $variant['meta'] as $key => $value ) {
			update_post_meta( $new_id, $key, $value );
		}

		update_post_meta( $new_id, '_starter_demo', 1 );

		// Zona: il comune è il genitore del quartiere (Milano › Isola).
		foreach ( $variant['terms'] as $taxonomy => $names ) {
			$term_ids = array();
			$parent   = 0;

			foreach ( $names as $name ) {
				$term = term_exists( $name, $taxonomy, $parent );
				$term = $term ? $term : wp_insert_term( $name, $taxonomy, array( 'parent' => $parent ) );

				if ( is_wp_error( $term ) ) {
					continue;
				}

				$parent     = 'zona' === $taxonomy ? (int) ( is_array( $term ) ? $term['term_id'] : $term ) : 0;
				$term_ids[] = (int) ( is_array( $term ) ? $term['term_id'] : $term );
			}

			// Per la zona basta il termine più profondo (quartiere).
			wp_set_object_terms( $new_id, 'zona' === $taxonomy ? array( end( $term_ids ) ) : $term_ids, $taxonomy );
		}

		++$created;
	}

	return $created;
}

/*
 * Le scelte dei menu a tendina della scheda (badge come "Esclusiva", "Riscaldamento: Autonomo", tipi di punti di interesse…)
 * si salvano nel campo in italiano: all'uscita passano dalla traduzione del tema, così in un'altra lingua (languages/en_US.po) cambiano.
 */
add_filter(
	'starter_meta_value',
	function ( $value, $field, $target ) {
		if ( 'select' !== ( $field['type'] ?? '' ) || 'immobile' !== ( $target[2] ?? '' ) ) {
			return $value;
		}

		$translate = static function ( $text ) {
			return is_string( $text ) && '' !== $text ? translate( $text, 'slug-theme' ) : $text; // phpcs:ignore WordPress.WP.I18n
		};

		return is_array( $value ) ? array_map( $translate, $value ) : $translate( $value );
	},
	10,
	3
);
