<?php
/**
 * Campi ACF dei blocchi in template-parts/ (registrati via codice, nessun acf-json da sincronizzare).
 * Un blocco in più: duplica una delle sezioni sotto, cambia "key"/block name, aggiorna anche block.json.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registra il gruppo di campi di un blocco aggiungendo, subito dopo "Occhiello" e "Titolo", i selettori "Tag HTML" (Automatico, H1-H6, span, p):
 * servono a scegliere come marcare titoli e occhielli per la SEO. "Automatico" lascia il tag che il tema calcola da solo.
 * Nei repeater si applica solo dove l'elenco è indicato in $repeater_groups (Missione e visione, che ha occhiello e titolo per riga).
 */
function starter_add_block_group( array $group ) {
	$repeater_groups = array( 'group_starter_missione_visione' );

	$group['fields'] = starter_insert_heading_tag_fields( $group['fields'], $group['key'], in_array( $group['key'], $repeater_groups, true ) );
	$group['fields'] = starter_insert_button_style_fields( $group['fields'], $group['key'] );
	$group['fields'] = array_merge( $group['fields'], starter_advanced_fields( $group['key'] ) );

	acf_add_local_field_group( $group );
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

/* Blocchi che hanno già una foto/video di sfondo: lì non ha senso lo sfondo colorato, restano solo le classi. */
function starter_blocks_without_section_bg() {
	return array( 'group_starter_hero_media', 'group_starter_hero_split', 'group_starter_hero_slider', 'group_starter_hero_video', 'group_starter_hero_home', 'group_starter_mappa' );
}

/* Scheda "Avanzate" in fondo a ogni blocco: sfondo colorato a tutta larghezza + classi CSS personalizzate (margini, padding, ecc.). Applicati da starter_apply_block_advanced(). */
function starter_advanced_fields( $scope ) {
	$fields = array(
		array(
			'key'       => 'field_' . $scope . '_adv_tab',
			'label'     => __( 'Avanzate', 'slug-theme' ),
			'type'      => 'tab',
			'placement' => 'top',
		),
	);

	if ( ! in_array( $scope, starter_blocks_without_section_bg(), true ) ) {
		$fields[] = array(
			'key'           => 'field_' . $scope . '_adv_bg',
			'label'         => __( 'Sfondo della sezione', 'slug-theme' ),
			'name'          => 'sfondo_sezione',
			'type'          => 'select',
			'choices'       => starter_section_bg_choices(),
			'default_value' => '',
			'instructions'  => __( 'Fascia colorata a tutta larghezza dietro il blocco. Sui colori scuri il testo diventa chiaro da solo.', 'slug-theme' ),
		);
	}

	$fields[] = array(
		'key'          => 'field_' . $scope . '_adv_classes',
		'label'        => __( 'Classi CSS personalizzate', 'slug-theme' ),
		'name'         => 'classi_custom',
		'type'         => 'text',
		'placeholder'  => 'mt2 pb4 pb2-sma',
		'instructions' => __( 'Separate da uno spazio. Spazi pronti: mt, mb (margine sopra/sotto), pt, pb (padding sopra/sotto) + numero 0-10 (1 = 1rem; 05 = 0,5rem; 20 = 20px). Per tablet aggiungi -tab, per mobile -sma (es. pb4 pb2-sma). Vale qualsiasi altra classe del tema o tua.', 'slug-theme' ),
	);

	return $fields;
}

/* Applica "Sfondo della sezione" e "Classi CSS personalizzate" al primo elemento del blocco ACF. */
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

/* Campi link che sono pulsanti, per gruppo: sotto ognuno si aggiunge il menu "Stile del pulsante" (Pieno 1/2, Contorno 1/2). true = c'è anche "Chiaro" (blocchi sopra le foto).
   Un nuovo blocco con un pulsante si aggiunge qui e legge lo stile con starter_button_class( get_field( 'nome_stile' ), 'classi predefinite' ). */
function starter_button_link_fields() {
	return array(
		'group_starter_testo_affiancato'     => array( 'link' => false ),
		'group_starter_sezione_voci'         => array( 'link' => false ),
		'group_starter_hero_media'           => array( 'cta_primaria' => false, 'cta_secondaria' => false ),
		'group_starter_hero_split'           => array( 'cta_primaria' => false, 'cta_secondaria' => false ),
		'group_starter_hero_home'            => array( 'cta_primaria' => false, 'cta_secondaria' => false ),
		'group_starter_hero_slider'          => array( 'link' => true, 'link_2' => true ),
		'group_starter_hero_video'           => array( 'link' => true, 'link_2' => true ),
		'group_starter_ultimi_articoli'      => array( 'link' => false ),
		'group_starter_prodotti'             => array( 'link' => false ),
		'group_starter_instagram'            => array( 'link' => false ),
		'group_starter_immobili_in_evidenza' => array( 'link' => false ),
		'group_starter_elenco_servizi'       => array( 'voci_cta' => false ),
	);
}

/* Aggiunge ai campi di un blocco le scelte di stile dei pulsanti */
function starter_insert_button_style_fields( array $fields, $scope ) {
	$buttons = starter_button_link_fields();

	if ( empty( $buttons[ $scope ] ) ) {
		return $fields;
	}

	$out = array();

	foreach ( $fields as $field ) {
		// Link dentro un repeater (solo i gruppi elencati qui, es. il pulsante di ogni servizio): il menu va nella stessa riga.
		if ( ! empty( $field['sub_fields'] ) && in_array( $scope, array( 'group_starter_elenco_servizi' ), true ) ) {
			$field['sub_fields'] = starter_insert_button_style_fields( $field['sub_fields'], $scope );
		}

		$out[] = $field;

		if ( isset( $field['name'], $field['type'] ) && 'link' === $field['type'] && isset( $buttons[ $scope ][ $field['name'] ] ) ) {
			$out[] = array(
				'key'           => 'field_btn_' . substr( md5( $scope . '|' . $field['name'] ), 0, 12 ),
				'label'         => __( 'Stile del pulsante', 'slug-theme' ),
				'name'          => $field['name'] . '_stile',
				'type'          => 'select',
				'choices'       => starter_button_style_choices( $buttons[ $scope ][ $field['name'] ] ),
				'default_value' => '',
				'instructions'  => __( 'Le 4 varianti di Opzioni → Pulsanti. "Predefinito" lascia lo stile pensato per questo blocco.', 'slug-theme' ),
			);
		}
	}

	return $out;
}

/* Aggiunge ai campi di un blocco la scelta del tag del titolo (H2, H3…), anche nei ripetitori */
function starter_insert_heading_tag_fields( array $fields, $scope, $into_repeaters ) {
	$out = array();

	foreach ( $fields as $field ) {
		if ( $into_repeaters && ! empty( $field['sub_fields'] ) ) {
			$field['sub_fields'] = starter_insert_heading_tag_fields( $field['sub_fields'], $field['key'], true );
		}

		$out[] = $field;

		// Solo nel livello dove il gruppo (o la riga del repeater) ha davvero questi campi di testo.
		if ( isset( $field['name'], $field['type'] ) && in_array( $field['type'], array( 'text', 'textarea' ), true ) && in_array( $field['name'], array( 'occhiello', 'titolo' ), true ) && ( 0 === strpos( $scope, 'group_' ) || $into_repeaters ) ) {
			$is_eyebrow = 'occhiello' === $field['name'];

			$out[] = array(
				'key'           => 'field_tag_' . substr( md5( $scope . '|' . $field['name'] ), 0, 12 ),
				'label'         => $is_eyebrow ? __( 'Tag HTML dell\'occhiello', 'slug-theme' ) : __( 'Tag HTML del titolo', 'slug-theme' ),
				'name'          => $field['name'] . '_tag',
				'type'          => 'select',
				'choices'       => starter_heading_tag_choices(),
				'default_value' => 'auto',
				'instructions'  => $is_eyebrow ? __( 'Per la SEO: scegli H1-H6, span o p. "Automatico" lascia la gerarchia dei titoli calcolata dal tema.', 'slug-theme' ) : '',
			);
		}
	}

	return $out;
}

/* Registra i campi ACF di tutti i blocchi del tema */
function starter_register_acf_blocks_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	starter_add_block_group(
		array(
			'key'      => 'group_starter_testo_affiancato',
			'title'    => __( 'Testo affiancato', 'slug-theme' ),
			'fields'   => array(
				array(
					'key'     => 'field_sta_lato_media',
					'label'   => __( 'Lato della foto', 'slug-theme' ),
					'name'    => 'lato_media',
					'type'    => 'select',
					'choices' => array(
						'sinistra' => __( 'Sinistra', 'slug-theme' ),
						'destra'   => __( 'Destra', 'slug-theme' ),
					),
					'default_value' => 'sinistra',
				),
				array(
					'key'     => 'field_sta_sfondo',
					'label'   => __( 'Sfondo', 'slug-theme' ),
					'name'    => 'sfondo',
					'type'    => 'select',
					'choices' => array(
						'nessuno' => __( 'Nessuno', 'slug-theme' ),
						'muted'   => __( 'Riquadro chiaro', 'slug-theme' ),
					),
					'default_value' => 'nessuno',
				),
				array(
					'key'   => 'field_sta_livello',
					'label' => __( 'Livello titolo', 'slug-theme' ),
					'name'  => 'livello',
					'type'  => 'select',
					'choices' => array( 'h2' => 'H2', 'h1' => 'H1' ),
					'default_value' => 'h2',
				),
				array(
					'key'   => 'field_sta_occhiello',
					'label' => __( 'Occhiello', 'slug-theme' ),
					'name'  => 'occhiello',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_sta_titolo',
					'label' => __( 'Titolo', 'slug-theme' ),
					'name'  => 'titolo',
					'type'  => 'text',
					'instructions' => __( 'Puoi usare <em>parola</em> per una parola in corsivo evidenziato.', 'slug-theme' ),
				),
				array(
					'key'   => 'field_sta_testo',
					'label' => __( 'Testo', 'slug-theme' ),
					'name'  => 'testo',
					'type'  => 'textarea',
				),
				array(
					'key'        => 'field_sta_checklist',
					'label'      => __( 'Checklist', 'slug-theme' ),
					'name'       => 'checklist',
					'type'       => 'repeater',
					'layout'     => 'table',
					'button_label' => __( 'Aggiungi voce', 'slug-theme' ),
					'sub_fields' => array(
						array(
							'key'   => 'field_sta_checklist_voce',
							'label' => __( 'Voce', 'slug-theme' ),
							'name'  => 'voce',
							'type'  => 'text',
						),
					),
				),
				array(
					'key'   => 'field_sta_citazione',
					'label' => __( 'Citazione (facoltativa)', 'slug-theme' ),
					'name'  => 'citazione',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_sta_link',
					'label' => __( 'Link', 'slug-theme' ),
					'name'  => 'link',
					'type'  => 'link',
				),
				array(
					'key'           => 'field_sta_immagine',
					'label'         => __( 'Foto principale', 'slug-theme' ),
					'name'          => 'immagine',
					'type'          => 'image',
					'return_format' => 'id',
					'preview_size'  => 'medium',
				),
				array(
					'key'           => 'field_sta_immagine_2',
					'label'         => __( 'Seconda foto (facoltativa)', 'slug-theme' ),
					'name'          => 'immagine_2',
					'type'          => 'image',
					'return_format' => 'id',
					'preview_size'  => 'medium',
					'instructions'  => __( 'Compare come foto rotonda sovrapposta alla principale.', 'slug-theme' ),
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'block',
						'operator' => '==',
						'value'    => 'starter-theme/testo-affiancato',
					),
				),
			),
		)
	);

	starter_add_block_group(
		array(
			'key'      => 'group_starter_sezione_voci',
			'title'    => __( 'Sezione voci', 'slug-theme' ),
			'fields'   => array(
				array(
					'key'     => 'field_sv_layout',
					'label'   => __( 'Layout', 'slug-theme' ),
					'name'    => 'layout',
					'type'    => 'select',
					'choices' => array(
						'numerato' => __( 'Elenco numerato', 'slug-theme' ),
						'card'     => __( 'Card classiche con testo', 'slug-theme' ),
						'icone'    => __( 'Card strette con icona', 'slug-theme' ),
						'passi'    => __( 'Card numerate', 'slug-theme' ),
					),
					'default_value' => 'numerato',
				),
				array(
					'key'     => 'field_sv_sfondo',
					'label'   => __( 'Sfondo (solo per le card)', 'slug-theme' ),
					'name'    => 'sfondo',
					'type'    => 'select',
					'choices' => array(
						'accent' => __( 'Colore d\'accento', 'slug-theme' ),
						'muted'  => __( 'Riquadro chiaro', 'slug-theme' ),
					),
					'default_value' => 'accent',
					'conditional_logic' => array(
						array(
							array( 'field' => 'field_sv_layout', 'operator' => '!=', 'value' => 'numerato' ),
						),
					),
				),
				array(
					'key'   => 'field_sv_livello',
					'label' => __( 'Livello titolo', 'slug-theme' ),
					'name'  => 'livello',
					'type'  => 'select',
					'choices' => array( 'h2' => 'H2', 'h1' => 'H1' ),
					'default_value' => 'h2',
				),
				array(
					'key'   => 'field_sv_occhiello',
					'label' => __( 'Occhiello', 'slug-theme' ),
					'name'  => 'occhiello',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_sv_titolo',
					'label' => __( 'Titolo', 'slug-theme' ),
					'name'  => 'titolo',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_sv_testo',
					'label' => __( 'Testo introduttivo (solo per l\'elenco numerato)', 'slug-theme' ),
					'name'  => 'testo',
					'type'  => 'textarea',
					'conditional_logic' => array(
						array(
							array( 'field' => 'field_sv_layout', 'operator' => '==', 'value' => 'numerato' ),
						),
					),
				),
				array(
					'key'   => 'field_sv_link',
					'label' => __( 'Link (facoltativo)', 'slug-theme' ),
					'name'  => 'link',
					'type'  => 'link',
				),
				array(
					'key'        => 'field_sv_voci',
					'label'      => __( 'Voci', 'slug-theme' ),
					'name'       => 'voci',
					'type'       => 'repeater',
					'layout'     => 'block',
					'button_label' => __( 'Aggiungi voce', 'slug-theme' ),
					'sub_fields' => array(
						array(
							'key'   => 'field_sv_voci_titolo',
							'label' => __( 'Titolo', 'slug-theme' ),
							'name'  => 'titolo',
							'type'  => 'text',
						),
						array(
							'key'   => 'field_sv_voci_testo',
							'label' => __( 'Testo', 'slug-theme' ),
							'name'  => 'testo',
							'type'  => 'textarea',
						),
						array(
							'key'     => 'field_sv_voci_icona',
							'label'   => __( 'Icona (solo per le card strette con icona)', 'slug-theme' ),
							'name'    => 'icona',
							'type'    => 'starter_icon',
							'default_value' => 'key', // è l'icona che il render mostra quando il campo è vuoto
						),
						array(
							'key'   => 'field_sv_voci_link',
							'label' => __( 'Link (solo per le card)', 'slug-theme' ),
							'name'  => 'link',
							'type'  => 'link',
						),
					),
				),
				array(
					'key'   => 'field_sv_cta_testo',
					'label' => __( 'CTA finale — testo (solo per le card classiche)', 'slug-theme' ),
					'name'  => 'cta_testo',
					'type'  => 'text',
					'conditional_logic' => array(
						array(
							array( 'field' => 'field_sv_layout', 'operator' => '==', 'value' => 'card' ),
						),
					),
				),
				array(
					'key'   => 'field_sv_cta_link',
					'label' => __( 'CTA finale — link', 'slug-theme' ),
					'name'  => 'cta_link',
					'type'  => 'link',
					'conditional_logic' => array(
						array(
							array( 'field' => 'field_sv_layout', 'operator' => '==', 'value' => 'card' ),
						),
					),
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'block',
						'operator' => '==',
						'value'    => 'starter-theme/sezione-voci',
					),
				),
			),
		)
	);

	starter_add_block_group(
		array(
			'key'      => 'group_starter_recensioni',
			'title'    => __( 'Recensioni', 'slug-theme' ),
			'fields'   => array(
				array(
					'key'   => 'field_rec_occhiello',
					'label' => __( 'Occhiello', 'slug-theme' ),
					'name'  => 'occhiello',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_rec_titolo',
					'label' => __( 'Titolo', 'slug-theme' ),
					'name'  => 'titolo',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_rec_link',
					'label' => __( 'Link "Vedi tutte" (facoltativo)', 'slug-theme' ),
					'name'  => 'link',
					'type'  => 'link',
				),
				array(
					'key'        => 'field_rec_voci',
					'label'      => __( 'Recensioni', 'slug-theme' ),
					'name'       => 'voci',
					'type'       => 'repeater',
					'layout'     => 'block',
					'button_label' => __( 'Aggiungi recensione', 'slug-theme' ),
					'sub_fields' => array(
						array(
							'key'   => 'field_rec_voci_nome',
							'label' => __( 'Nome', 'slug-theme' ),
							'name'  => 'nome',
							'type'  => 'text',
						),
						array(
							'key'   => 'field_rec_voci_testo',
							'label' => __( 'Testo', 'slug-theme' ),
							'name'  => 'testo',
							'type'  => 'textarea',
						),
						array(
							'key'   => 'field_rec_voci_data',
							'label' => __( 'Data (facoltativa)', 'slug-theme' ),
							'name'  => 'data',
							'type'  => 'text',
						),
					),
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'block',
						'operator' => '==',
						'value'    => 'starter-theme/recensioni',
					),
				),
			),
		)
	);

	starter_add_block_group(
		array(
			'key'      => 'group_starter_mappa',
			'title'    => __( 'Mappa', 'slug-theme' ),
			'fields'   => array(
				array(
					'key'   => 'field_map_indirizzo',
					'label' => __( 'Indirizzo', 'slug-theme' ),
					'name'  => 'indirizzo',
					'type'  => 'text',
					'required' => 1,
				),
				array(
					'key'   => 'field_map_pulsante',
					'label' => __( 'Testo pulsante', 'slug-theme' ),
					'name'  => 'pulsante',
					'type'  => 'text',
					'placeholder' => __( 'Indicazioni stradali', 'slug-theme' ),
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'block',
						'operator' => '==',
						'value'    => 'starter-theme/mappa',
					),
				),
			),
		)
	);

	starter_add_block_group(
		array(
			'key'      => 'group_starter_intestazione_pagina',
			'title'    => __( 'Intestazione pagina', 'slug-theme' ),
			'fields'   => array(
				array(
					'key'   => 'field_ip_occhiello',
					'label' => __( 'Occhiello', 'slug-theme' ),
					'name'  => 'occhiello',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_ip_titolo',
					'label' => __( 'Titolo (H1)', 'slug-theme' ),
					'name'  => 'titolo',
					'type'  => 'text',
					'required' => 1,
					'instructions' => __( 'Puoi usare <em>parola</em> per una parola in corsivo evidenziato.', 'slug-theme' ),
				),
				array(
					'key'   => 'field_ip_testo',
					'label' => __( 'Testo', 'slug-theme' ),
					'name'  => 'testo',
					'type'  => 'textarea',
				),
				array(
					'key'          => 'field_ip_posizione',
					'label'        => __( 'Posizione del testo', 'slug-theme' ),
					'name'         => 'posizione_testo',
					'type'         => 'select',
					'instructions' => __( 'Dove sta il testo: sotto il titolo oppure a destra, accanto al titolo (su schermi stretti va comunque sotto).', 'slug-theme' ),
					'choices'      => array(
						'sotto'  => __( 'Sotto il titolo', 'slug-theme' ),
						'destra' => __( 'A destra del titolo', 'slug-theme' ),
					),
					'default_value' => 'sotto',
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'block',
						'operator' => '==',
						'value'    => 'starter-theme/intestazione-pagina',
					),
				),
			),
		)
	);

	starter_add_block_group(
		array(
			'key'      => 'group_starter_missione_visione',
			'title'    => __( 'Due riquadri', 'slug-theme' ),
			'fields'   => array(
				array(
					'key'        => 'field_mv_voci',
					'label'      => __( 'Riquadri', 'slug-theme' ),
					'name'       => 'voci',
					'type'       => 'repeater',
					'layout'     => 'block',
					'min'        => 1,
					'button_label' => __( 'Aggiungi riquadro', 'slug-theme' ),
					'instructions' => __( 'Il secondo riquadro viene evidenziato automaticamente con il colore d\'accento.', 'slug-theme' ),
					'sub_fields' => array(
						array(
							'key'   => 'field_mv_voci_occhiello',
							'label' => __( 'Occhiello', 'slug-theme' ),
							'name'  => 'occhiello',
							'type'  => 'text',
						),
						array(
							'key'   => 'field_mv_voci_titolo',
							'label' => __( 'Titolo', 'slug-theme' ),
							'name'  => 'titolo',
							'type'  => 'text',
						),
						array(
							'key'   => 'field_mv_voci_testo',
							'label' => __( 'Testo', 'slug-theme' ),
							'name'  => 'testo',
							'type'  => 'textarea',
						),
					),
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'block',
						'operator' => '==',
						'value'    => 'starter-theme/missione-visione',
					),
				),
			),
		)
	);
}
add_action( 'acf/init', 'starter_register_acf_blocks_fields' );
