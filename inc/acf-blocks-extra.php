<?php
/**
 * Campi ACF dei blocchi aggiuntivi (hero con slider, striscia valori, ultimi articoli, prodotti in evidenza,
 * Instagram, team, contatti), importati da Symbolikamente e Di Guida Real Estate e resi generici.
 * Registrati via codice come gli altri (inc/acf-blocks.php): ogni blocco ha block.json + render.php in template-parts/.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Campi comuni "intestazione di sezione": titolo, testo, pulsante. */
function starter_extra_head_fields( $prefix, $with_link = true ) {
	$fields = array(
		array(
			'key'          => "field_{$prefix}_occhiello",
			'label'        => __( 'Occhiello', 'slug-theme' ),
			'name'         => 'occhiello',
			'type'         => 'text',
			'instructions' => __( 'Piccola scritta sopra il titolo, con la parola chiave. Facoltativa.', 'slug-theme' ),
		),
		array(
			'key'   => "field_{$prefix}_titolo",
			'label' => __( 'Titolo', 'slug-theme' ),
			'name'  => 'titolo',
			'type'  => 'text',
		),
		array(
			'key'       => "field_{$prefix}_testo",
			'label'     => __( 'Testo', 'slug-theme' ),
			'name'      => 'testo',
			'type'      => 'textarea',
			'rows'      => 3,
			'new_lines' => 'br',
		),
	);

	if ( $with_link ) {
		$fields[] = array(
			'key'           => "field_{$prefix}_link",
			'label'         => __( 'Pulsante', 'slug-theme' ),
			'name'          => 'link',
			'type'          => 'link',
			'return_format' => 'array',
		);
	}

	return $fields;
}

/* Posizione "Blocco = starter-theme/{nome}". */
function starter_extra_block_location( $name ) {
	return array(
		array(
			array(
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'starter-theme/' . $name,
			),
		),
	);
}

/* Slider: il campo gallery di ACF accetta per sé solo immagini ("File must be a valid image"). Qui si rifà il controllo ammettendo anche i video: ogni elemento deve essere un'immagine o un video, e ne serve almeno uno. */
add_filter(
	'acf/validate_value/key=field_stsl_slide',
	function ( $valid, $value ) {
		$ids = is_array( $value ) ? array_filter( $value, 'is_numeric' ) : array();

		foreach ( $ids as $id ) {
			if ( ! wp_attachment_is_image( (int) $id ) && ! wp_attachment_is( 'video', (int) $id ) ) {
				return __( 'Il file deve essere un\'immagine o un video.', 'slug-theme' );
			}
		}

		return $ids ? true : __( 'Aggiungi almeno un\'immagine o un video.', 'slug-theme' );
	},
	10,
	2
);

/* Stesso motivo, ma per il controllo che ACF fa già nella finestra della libreria media (etichetta rossa "Limitato" sul file): ai video dello slider si toglie l'errore "non è un'immagine". */
add_filter(
	'acf/validate_attachment/key=field_stsl_slide',
	function ( $errors, $file, $attachment ) {
		$id    = (int) ( $attachment['id'] ?? 0 );
		$mime  = (string) ( $attachment['mime'] ?? $attachment['type'] ?? '' );
		$ext   = strtolower( (string) ( $file['type'] ?? '' ) );
		$video = ( $id && wp_attachment_is( 'video', $id ) ) || str_starts_with( $mime, 'video' ) || in_array( $ext, array( 'mp4', 'webm' ), true );

		if ( $video && is_array( $errors ) ) {
			unset( $errors['invalid_image'] );
		}

		return $errors;
	},
	10,
	3
);

/* Registra i campi ACF dei blocchi aggiuntivi */
function starter_register_extra_block_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	// Hero con foto e video.
	starter_add_block_group(
		array(
			'key'      => 'group_starter_hero_media',
			'title'    => __( 'Hero con foto e video', 'slug-theme' ),
			'fields'   => array(
				array( 'key' => 'field_sthm_occhiello', 'label' => __( 'Occhiello (H1 piccolo)', 'slug-theme' ), 'name' => 'occhiello', 'type' => 'text', 'instructions' => __( 'È l\'H1 della pagina: scrivi la parola chiave.', 'slug-theme' ) ),
				array( 'key' => 'field_sthm_titolo', 'label' => __( 'Titolo (H2 grande)', 'slug-theme' ), 'name' => 'titolo', 'type' => 'text', 'instructions' => __( 'Puoi usare <em>parola</em> per una parte in corsivo evidenziato.', 'slug-theme' ) ),
				array( 'key' => 'field_sthm_testo', 'label' => __( 'Testo', 'slug-theme' ), 'name' => 'testo', 'type' => 'textarea', 'rows' => 3, 'new_lines' => '' ),
				array( 'key' => 'field_sthm_cta1', 'label' => __( 'Pulsante principale', 'slug-theme' ), 'name' => 'cta_primaria', 'type' => 'link', 'return_format' => 'array' ),
				array( 'key' => 'field_sthm_cta2', 'label' => __( 'Pulsante secondario', 'slug-theme' ), 'name' => 'cta_secondaria', 'type' => 'link', 'return_format' => 'array' ),
				array( 'key' => 'field_sthm_clienti', 'label' => __( 'Foto dei clienti (max 6)', 'slug-theme' ), 'name' => 'clienti', 'type' => 'gallery', 'return_format' => 'id', 'max' => 6, 'instructions' => __( 'Foto quadrate piccole, mostrate tonde e sovrapposte.', 'slug-theme' ) ),
				array( 'key' => 'field_sthm_clienti_testo', 'label' => __( 'Frase accanto alle foto', 'slug-theme' ), 'name' => 'clienti_testo', 'type' => 'text' ),
				array( 'key' => 'field_sthm_foto', 'label' => __( 'Foto (a destra)', 'slug-theme' ), 'name' => 'foto', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium' ),
				array( 'key' => 'field_sthm_etichetta_foto', 'label' => __( 'Etichetta sotto la foto', 'slug-theme' ), 'name' => 'etichetta_foto', 'type' => 'text', 'instructions' => __( 'Parole chiave, es. "Strategia e identità".', 'slug-theme' ) ),
				array( 'key' => 'field_sthm_colore_foto', 'label' => __( 'Colore dell\'etichetta della foto', 'slug-theme' ), 'name' => 'colore_etichetta_foto', 'type' => 'select', 'choices' => array( 'cta' => __( 'Accento pulsanti (CTA)', 'slug-theme' ), 'social' => __( 'Accento social', 'slug-theme' ), 'menu' => __( 'Accento menu', 'slug-theme' ), 'principale' => __( 'Colore principale', 'slug-theme' ), 'chiaro' => __( 'Chiaro', 'slug-theme' ) ), 'default_value' => 'cta', 'instructions' => __( 'Colori scelti in Opzioni → Colori.', 'slug-theme' ) ),
				array( 'key' => 'field_sthm_video', 'label' => __( 'Video verticale', 'slug-theme' ), 'name' => 'video', 'type' => 'file', 'return_format' => 'array', 'mime_types' => 'mp4,webm', 'instructions' => __( 'Facoltativo, formato 9:16, leggero. Parte da solo, muto e in ciclo.', 'slug-theme' ) ),
				array( 'key' => 'field_sthm_copertina', 'label' => __( 'Copertina del video', 'slug-theme' ), 'name' => 'copertina_video', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium', 'instructions' => __( 'Immagine mostrata finché il video carica (o al suo posto).', 'slug-theme' ) ),
				array( 'key' => 'field_sthm_etichetta_video', 'label' => __( 'Etichetta sotto il video', 'slug-theme' ), 'name' => 'etichetta_video', 'type' => 'text' ),
				array( 'key' => 'field_sthm_colore_video', 'label' => __( 'Colore dell\'etichetta del video', 'slug-theme' ), 'name' => 'colore_etichetta_video', 'type' => 'select', 'choices' => array( 'cta' => __( 'Accento pulsanti (CTA)', 'slug-theme' ), 'social' => __( 'Accento social', 'slug-theme' ), 'menu' => __( 'Accento menu', 'slug-theme' ), 'principale' => __( 'Colore principale', 'slug-theme' ), 'chiaro' => __( 'Chiaro', 'slug-theme' ) ), 'default_value' => 'social' ),
			),
			'location' => starter_extra_block_location( 'hero-media' ),
		)
	);

	// Sezione affiancata: foto via ACF, testo con i blocchi nativi.
	starter_add_block_group(
		array(
			'key'      => 'group_starter_sezione_affiancata',
			'title'    => __( 'Sezione affiancata', 'slug-theme' ),
			'fields'   => array(
				array(
					'key'          => 'field_stsa_occhiello',
					'label'        => __( 'Occhiello', 'slug-theme' ),
					'name'         => 'occhiello',
					'type'         => 'text',
					'instructions' => __( 'Piccola scritta sopra il titolo, con la parola chiave. Facoltativa: sta sopra il titolo che scrivi nel testo accanto alla foto.', 'slug-theme' ),
				),
				array(
					'key'           => 'field_stsa_foto',
					'label'         => __( 'Foto principale', 'slug-theme' ),
					'name'          => 'foto',
					'type'          => 'image',
					'return_format' => 'id',
					'preview_size'  => 'medium',
				),
				array(
					'key'           => 'field_stsa_foto_2',
					'label'         => __( 'Seconda foto (facoltativa)', 'slug-theme' ),
					'name'          => 'foto_2',
					'type'          => 'image',
					'return_format' => 'id',
					'preview_size'  => 'medium',
					'instructions'  => __( 'Più piccola: si sovrappone in basso a sinistra alla foto principale.', 'slug-theme' ),
				),
				array(
					'key'           => 'field_stsa_formato',
					'label'         => __( 'Formato foto', 'slug-theme' ),
					'name'          => 'formato',
					'type'          => 'select',
					'choices'       => array(
						'verticale' => __( 'Verticale 2:3', 'slug-theme' ),
						'quadrata'  => __( 'Quadrata', 'slug-theme' ),
						'originale' => __( 'Originale, con ombra (es. copertina di un libro)', 'slug-theme' ),
					),
					'default_value' => 'verticale',
				),
				array(
					'key'           => 'field_stsa_lato',
					'label'         => __( 'Lato della foto', 'slug-theme' ),
					'name'          => 'lato',
					'type'          => 'button_group',
					'choices'       => array(
						'sinistra' => __( 'Sinistra', 'slug-theme' ),
						'destra'   => __( 'Destra', 'slug-theme' ),
					),
					'default_value' => 'sinistra',
				),
				array(
					'key'           => 'field_stsa_sfondo',
					'label'         => __( 'Sfondo', 'slug-theme' ),
					'name'          => 'sfondo',
					'type'          => 'button_group',
					'choices'       => array(
						'nessuno' => __( 'Nessuno', 'slug-theme' ),
						'muted'   => __( 'Riquadro chiaro', 'slug-theme' ),
					),
					'default_value' => 'nessuno',
				),
			),
			'location' => starter_extra_block_location( 'sezione-affiancata' ),
		)
	);

	// Hero testo + foto.
	starter_add_block_group(
		array(
			'key'      => 'group_starter_hero_split',
			'title'    => __( 'Hero testo + foto', 'slug-theme' ),
			'fields'   => array(
				array(
					'key'   => 'field_sths_occhiello',
					'label' => __( 'Occhiello', 'slug-theme' ),
					'name'  => 'occhiello',
					'type'  => 'text',
				),
				array(
					'key'          => 'field_sths_titolo',
					'label'        => __( 'Titolo (H1)', 'slug-theme' ),
					'name'         => 'titolo',
					'type'         => 'text',
					'instructions' => __( 'Puoi usare <em>parola</em> per una parte in corsivo evidenziato.', 'slug-theme' ),
				),
				array(
					'key'       => 'field_sths_testo',
					'label'     => __( 'Testo', 'slug-theme' ),
					'name'      => 'testo',
					'type'      => 'textarea',
					'rows'      => 3,
					'new_lines' => '',
				),
				array(
					'key'           => 'field_sths_cta1',
					'label'         => __( 'Pulsante principale', 'slug-theme' ),
					'name'          => 'cta_primaria',
					'type'          => 'link',
					'return_format' => 'array',
				),
				array(
					'key'           => 'field_sths_cta2',
					'label'         => __( 'Pulsante secondario', 'slug-theme' ),
					'name'          => 'cta_secondaria',
					'type'          => 'link',
					'return_format' => 'array',
				),
				array(
					'key'           => 'field_sths_foto',
					'label'         => __( 'Foto', 'slug-theme' ),
					'name'          => 'foto',
					'type'          => 'image',
					'return_format' => 'id',
					'preview_size'  => 'medium',
				),
				array(
					'key'           => 'field_sths_lato',
					'label'         => __( 'Lato della foto', 'slug-theme' ),
					'name'          => 'lato_foto',
					'type'          => 'button_group',
					'choices'       => array(
						'sinistra' => __( 'Sinistra', 'slug-theme' ),
						'destra'   => __( 'Destra', 'slug-theme' ),
					),
					'default_value' => 'destra',
				),
			),
			'location' => starter_extra_block_location( 'hero-split' ),
		)
	);

	starter_add_block_group(
		array(
			'key'      => 'group_starter_hero_slider',
			'title'    => __( 'Hero con slider', 'slug-theme' ),
			'fields'   => array(
				array(
					'key'           => 'field_sthe_immagini',
					'label'         => __( 'Foto dello slider', 'slug-theme' ),
					'name'          => 'immagini',
					'type'          => 'gallery',
					'return_format' => 'id',
					'min'           => 1,
					'max'           => 6,
					'instructions'  => __( 'Da 1 a 6 foto orizzontali, lato lungo 2000 px. Cambiano da sole in dissolvenza.', 'slug-theme' ),
				),
				array(
					'key'          => 'field_sthe_occhiello',
					'label'        => __( 'Occhiello', 'slug-theme' ),
					'name'         => 'occhiello',
					'type'         => 'text',
					'instructions' => __( 'Piccola scritta sopra il titolo, con la parola chiave. Facoltativa.', 'slug-theme' ),
				),
				array(
					'key'          => 'field_sthe_titolo',
					'label'        => __( 'Titolo (H1)', 'slug-theme' ),
					'name'         => 'titolo',
					'type'         => 'textarea',
					'rows'         => 2,
					'new_lines'    => 'br',
					'instructions' => __( 'Una riga per riga. La parte tra <em> e </em> va in corsivo con il colore in risalto.', 'slug-theme' ),
				),
				array(
					'key'          => 'field_sthe_testo',
					'label'        => __( 'Testo', 'slug-theme' ),
					'name'         => 'testo',
					'type'         => 'textarea',
					'rows'         => 3,
					'instructions' => __( 'Si può usare HTML (es. <br>, <strong>, <em>, <a>). Una riga vuota = nuovo paragrafo.', 'slug-theme' ),
				),
				array(
					'key'           => 'field_sthe_link',
					'label'         => __( 'Pulsante', 'slug-theme' ),
					'name'          => 'link',
					'type'          => 'link',
					'return_format' => 'array',
				),
				array(
					'key'           => 'field_sthe_link2',
					'label'         => __( 'Secondo pulsante', 'slug-theme' ),
					'name'          => 'link_2',
					'type'          => 'link',
					'return_format' => 'array',
					'instructions'  => __( 'Facoltativo: compare accanto al primo solo se lo compili.', 'slug-theme' ),
				),
				array(
					'key'           => 'field_sthe_durata',
					'label'         => __( 'Secondi per foto', 'slug-theme' ),
					'name'          => 'durata',
					'type'          => 'number',
					'default_value' => 6,
					'min'           => 3,
					'max'           => 15,
				),
			),
			'location' => starter_extra_block_location( 'hero-slider' ),
		)
	);

	starter_add_block_group(
		array(
			'key'      => 'group_starter_hero_video',
			'title'    => __( 'Hero con video', 'slug-theme' ),
			'fields'   => array(
				array(
					'key'           => 'field_sthv_video',
					'label'         => __( 'Video', 'slug-theme' ),
					'name'          => 'video',
					'type'          => 'file',
					'return_format' => 'array',
					'mime_types'    => 'mp4,webm',
					'instructions'  => __( 'Video orizzontale (16:9), leggero (meglio sotto i 5 MB). Parte da solo, muto e in ciclo.', 'slug-theme' ),
				),
				array(
					'key'           => 'field_sthv_copertina',
					'label'         => __( 'Copertina', 'slug-theme' ),
					'name'          => 'copertina',
					'type'          => 'image',
					'return_format' => 'id',
					'preview_size'  => 'medium',
					'instructions'  => __( 'Immagine mostrata finché il video carica (o al suo posto).', 'slug-theme' ),
				),
				array(
					'key'          => 'field_sthv_occhiello',
					'label'        => __( 'Occhiello', 'slug-theme' ),
					'name'         => 'occhiello',
					'type'         => 'text',
					'instructions' => __( 'Piccola scritta sopra il titolo, con la parola chiave. Facoltativa.', 'slug-theme' ),
				),
				array(
					'key'          => 'field_sthv_titolo',
					'label'        => __( 'Titolo', 'slug-theme' ),
					'name'         => 'titolo',
					'type'         => 'textarea',
					'rows'         => 2,
					'new_lines'    => 'br',
					'instructions' => __( 'Una riga per riga. La parte tra <em> e </em> va in corsivo con il colore in risalto.', 'slug-theme' ),
				),
				array(
					'key'          => 'field_sthv_testo',
					'label'        => __( 'Testo', 'slug-theme' ),
					'name'         => 'testo',
					'type'         => 'textarea',
					'rows'         => 3,
					'instructions' => __( 'Si può usare HTML (es. <br>, <strong>, <em>, <a>). Una riga vuota = nuovo paragrafo.', 'slug-theme' ),
				),
				array(
					'key'           => 'field_sthv_link',
					'label'         => __( 'Pulsante', 'slug-theme' ),
					'name'          => 'link',
					'type'          => 'link',
					'return_format' => 'array',
				),
				array(
					'key'           => 'field_sthv_link2',
					'label'         => __( 'Secondo pulsante', 'slug-theme' ),
					'name'          => 'link_2',
					'type'          => 'link',
					'return_format' => 'array',
					'instructions'  => __( 'Facoltativo: compare accanto al primo solo se lo compili.', 'slug-theme' ),
				),
			),
			'location' => starter_extra_block_location( 'hero-video' ),
		)
	);

	starter_add_block_group(
		array(
			'key'      => 'group_starter_striscia_valori',
			'title'    => __( 'Striscia valori', 'slug-theme' ),
			'fields'   => array(
				array(
					'key'          => 'field_stsv_voci',
					'label'        => __( 'Voci', 'slug-theme' ),
					'name'         => 'voci',
					'type'         => 'repeater',
					'layout'       => 'table',
					'min'          => 1,
					'max'          => 6,
					'button_label' => __( 'Aggiungi voce', 'slug-theme' ),
					'sub_fields'   => array(
						array(
							'key'     => 'field_stsv_icona',
							'label'   => __( 'Icona', 'slug-theme' ),
							'name'    => 'icona',
							'type'    => 'starter_icon',
							'default_value' => 'star',
						),
						array(
							'key'   => 'field_stsv_titolo',
							'label' => __( 'Titolo', 'slug-theme' ),
							'name'  => 'titolo',
							'type'  => 'text',
						),
					),
				),
			),
			'location' => starter_extra_block_location( 'striscia-valori' ),
		)
	);

	// Slider singolo o carosello di immagini.
	$stsl_carosello = array( array( array( 'field' => 'field_stsl_modalita', 'operator' => '==', 'value' => 'carosello' ) ) );

	starter_add_block_group(
		array(
			'key'      => 'group_starter_slider',
			'title'    => __( 'Slider', 'slug-theme' ),
			'fields'   => array(
				array(
					'key'           => 'field_stsl_modalita',
					'label'         => __( 'Tipo', 'slug-theme' ),
					'name'          => 'modalita',
					'type'          => 'button_group',
					'choices'       => array(
						'singolo'   => __( 'Slider singolo', 'slug-theme' ),
						'carosello' => __( 'Carosello', 'slug-theme' ),
					),
					'default_value' => 'carosello',
				),
				array(
					'key'           => 'field_stsl_larghezza',
					'label'         => __( 'Larghezza', 'slug-theme' ),
					'name'          => 'larghezza',
					'type'          => 'button_group',
					'choices'       => array(
						'contenuta' => __( 'Nella misura del sito', 'slug-theme' ),
						'piena'     => __( 'A tutto schermo', 'slug-theme' ),
					),
					'default_value' => 'contenuta',
				),
				array(
					'key'           => 'field_stsl_colonne_desktop',
					'label'         => __( 'Immagini visibili su desktop', 'slug-theme' ),
					'name'          => 'colonne_desktop',
					'type'          => 'select',
					'choices'       => array( '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6' ),
					'default_value' => '3',
					'wrapper'       => array( 'width' => '33' ),
					'conditional_logic' => $stsl_carosello,
				),
				array(
					'key'           => 'field_stsl_colonne_tablet',
					'label'         => __( 'Su tablet (fino a 1100px)', 'slug-theme' ),
					'name'          => 'colonne_tablet',
					'type'          => 'select',
					'choices'       => array( '1' => '1', '2' => '2', '3' => '3', '4' => '4' ),
					'default_value' => '2',
					'wrapper'       => array( 'width' => '33' ),
					'conditional_logic' => $stsl_carosello,
				),
				array(
					'key'           => 'field_stsl_colonne_mobile',
					'label'         => __( 'Su mobile (fino a 640px)', 'slug-theme' ),
					'name'          => 'colonne_mobile',
					'type'          => 'select',
					'choices'       => array( '1' => '1', '2' => '2' ),
					'default_value' => '1',
					'wrapper'       => array( 'width' => '33' ),
					'conditional_logic' => $stsl_carosello,
				),
				array(
					'key'           => 'field_stsl_gap',
					'label'         => __( 'Distanza tra le immagini (px)', 'slug-theme' ),
					'name'          => 'gap',
					'type'          => 'number',
					'default_value' => 16,
					'min'           => 0,
					'max'           => 100,
					'append'        => 'px',
					'wrapper'       => array( 'width' => '50' ),
				),
				array(
					'key'           => 'field_stsl_proporzione',
					'label'         => __( 'Proporzione delle immagini', 'slug-theme' ),
					'name'          => 'proporzione',
					'type'          => 'select',
					'choices'       => array(
						'16-9'      => '16:9',
						'3-2'       => '3:2',
						'4-3'       => '4:3',
						'1-1'       => '1:1',
						'4-5'       => '4:5 (verticale)',
						'21-9'      => '21:9 (panoramica)',
						'originale' => __( 'Originale (ognuna la sua)', 'slug-theme' ),
					),
					'default_value' => '16-9',
					'wrapper'       => array( 'width' => '50' ),
				),
				array(
					'key'           => 'field_stsl_anteprima',
					'label'         => __( 'Fai intravedere le immagini vicine', 'slug-theme' ),
					'name'          => 'anteprima',
					'type'          => 'true_false',
					'ui'            => 1,
					'default_value' => 0,
					'message'       => '',
					'instructions'  => __( 'Una striscia della slide precedente e della successiva compare ai lati.', 'slug-theme' ),
					'wrapper'       => array( 'width' => '50' ),
				),
				array(
					'key'               => 'field_stsl_anteprima_px',
					'label'             => __( 'Quanto si vede (px)', 'slug-theme' ),
					'name'              => 'anteprima_px',
					'type'              => 'number',
					'default_value'     => 50,
					'min'               => 10,
					'max'               => 200,
					'append'            => 'px',
					'wrapper'           => array( 'width' => '50' ),
					'conditional_logic' => array( array( array( 'field' => 'field_stsl_anteprima', 'operator' => '==', 'value' => '1' ) ) ),
				),
				array(
					'key'           => 'field_stsl_frecce',
					'label'         => __( 'Frecce', 'slug-theme' ),
					'name'          => 'frecce',
					'type'          => 'select',
					'choices'       => array(
						'immagine' => __( 'Sull\'immagine, ai lati', 'slug-theme' ),
						'sopra'    => __( 'Sopra lo slider', 'slug-theme' ),
						'sotto'    => __( 'Sotto lo slider', 'slug-theme' ),
						'nessuna'  => __( 'Nessuna', 'slug-theme' ),
					),
					'default_value' => 'immagine',
					'wrapper'       => array( 'width' => '50' ),
				),
				array(
					'key'               => 'field_stsl_frecce_stile',
					'label'             => __( 'Stile delle frecce', 'slug-theme' ),
					'name'              => 'frecce_stile',
					'type'              => 'select',
					'choices'           => starter_button_style_choices(),
					'default_value'     => '',
					'instructions'      => __( 'Uno dei 4 stili di Opzioni → Pulsanti. "Predefinito": pieno sull\'immagine, contorno sopra e sotto.', 'slug-theme' ),
					'wrapper'           => array( 'width' => '50' ),
					'conditional_logic' => array( array( array( 'field' => 'field_stsl_frecce', 'operator', 'value' => 'nessuna' ) ) ),
				),
				array(
					'key'           => 'field_stsl_paginazione',
					'label'         => __( 'Paginazione', 'slug-theme' ),
					'name'          => 'paginazione',
					'type'          => 'select',
					'choices'       => array(
						'puntini'   => __( 'Puntini', 'slug-theme' ),
						'contatore' => __( 'Contatore (1 / 5)', 'slug-theme' ),
						'barra'     => __( 'Barra di avanzamento', 'slug-theme' ),
						'nessuna'   => __( 'Nessuna', 'slug-theme' ),
					),
					'default_value' => 'puntini',
					'wrapper'       => array( 'width' => '50' ),
				),
				array(
					'key'           => 'field_stsl_autoplay',
					'label'         => __( 'Scorrimento automatico', 'slug-theme' ),
					'name'          => 'autoplay',
					'type'          => 'true_false',
					'ui'            => 1,
					'default_value' => 0,
					'instructions'  => __( 'Si ferma al passaggio del mouse, quando tocchi lo slider e con "riduci animazioni".', 'slug-theme' ),
					'wrapper'       => array( 'width' => '50' ),
				),
				array(
					'key'               => 'field_stsl_autoplay_secondi',
					'label'             => __( 'Secondi per slide', 'slug-theme' ),
					'name'              => 'autoplay_secondi',
					'type'              => 'number',
					'default_value'     => 5,
					'min'               => 1,
					'max'               => 30,
					'append'            => 's',
					'wrapper'           => array( 'width' => '50' ),
					'conditional_logic' => array( array( array( 'field' => 'field_stsl_autoplay', 'operator' => '==', 'value' => '1' ) ) ),
				),
				array(
					'key'           => 'field_stsl_ricomincia',
					'label'         => __( 'Ricomincia dall\'inizio', 'slug-theme' ),
					'name'          => 'ricomincia',
					'type'          => 'true_false',
					'ui'            => 1,
					'default_value' => 1,
					'instructions'  => __( 'Dopo l\'ultima slide la freccia (e l\'autoplay) tornano alla prima.', 'slug-theme' ),
					'wrapper'       => array( 'width' => '50' ),
				),
				array(
					'key'           => 'field_stsl_didascalie',
					'label'         => __( 'Mostra le didascalie', 'slug-theme' ),
					'name'          => 'didascalie',
					'type'          => 'true_false',
					'ui'            => 1,
					'default_value' => 0,
					'wrapper'       => array( 'width' => '50' ),
				),
				array(
					'key'           => 'field_stsl_angoli',
					'label'         => __( 'Angoli delle immagini', 'slug-theme' ),
					'name'          => 'angoli',
					'type'          => 'button_group',
					'choices'       => array(
						'sito'    => __( 'Come "Bordi del sito"', 'slug-theme' ),
						'nessuno' => __( 'Squadrati', 'slug-theme' ),
					),
					'default_value' => 'sito',
				),
				array(
					'key'           => 'field_stsl_slide',
					'label'         => __( 'Immagini', 'slug-theme' ),
					'name'          => 'slide',
					'type'          => 'gallery',
					'return_format' => 'id',
					'preview_size'  => 'medium',
					'min'           => 1,
					'mime_types'    => 'jpg,jpeg,png,gif,webp,avif,mp4,webm',
					'instructions'  => __( 'Immagini e video (mp4, webm: leggeri, partono da soli, muti e in ciclo). Scegli o trascina più file insieme; l\'ordine si cambia trascinandoli. La didascalia viene da quella del file nella Libreria media.', 'slug-theme' ),
				),
			),
			'location' => starter_extra_block_location( 'slider' ),
		)
	);

	starter_add_block_group(
		array(
			'key'      => 'group_starter_ultimi_articoli',
			'title'    => __( 'Ultimi articoli', 'slug-theme' ),
			'fields'   => array_merge(
				starter_extra_head_fields( 'stua' ),
				array(
					array(
						'key'           => 'field_stua_categoria',
						'label'         => __( 'Categoria', 'slug-theme' ),
						'name'          => 'categoria',
						'type'          => 'taxonomy',
						'taxonomy'      => 'category',
						'field_type'    => 'select',
						'allow_null'    => 1,
						'return_format' => 'id',
						'add_term'      => 0,
						'instructions'  => __( 'Vuoto = tutte.', 'slug-theme' ),
					),
					array(
						'key'           => 'field_stua_numero',
						'label'         => __( 'Quanti', 'slug-theme' ),
						'name'          => 'numero',
						'type'          => 'number',
						'default_value' => 3,
						'min'           => 1,
						'max'           => 9,
					),
				)
			),
			'location' => starter_extra_block_location( 'ultimi-articoli' ),
		)
	);

	starter_add_block_group(
		array(
			'key'      => 'group_starter_prodotti',
			'title'    => __( 'Prodotti in evidenza', 'slug-theme' ),
			'fields'   => array_merge(
				starter_extra_head_fields( 'stpr' ),
				array(
					array(
						'key'           => 'field_stpr_sorgente',
						'label'         => __( 'Quali prodotti', 'slug-theme' ),
						'name'          => 'sorgente',
						'type'          => 'select',
						'choices'       => array(
							'evidenza'  => __( 'In evidenza (stellina nell\'elenco prodotti)', 'slug-theme' ),
							'venduti'   => __( 'Più venduti', 'slug-theme' ),
							'recenti'   => __( 'Più recenti', 'slug-theme' ),
							'categoria' => __( 'Di una categoria', 'slug-theme' ),
							'scelti'    => __( 'Scelti a mano', 'slug-theme' ),
						),
						'default_value' => 'evidenza',
					),
					array(
						'key'               => 'field_stpr_categoria',
						'label'             => __( 'Categoria', 'slug-theme' ),
						'name'              => 'categoria',
						'type'              => 'taxonomy',
						'taxonomy'          => 'product_cat',
						'field_type'        => 'select',
						'return_format'     => 'id',
						'add_term'          => 0,
						'conditional_logic' => array( array( array( 'field' => 'field_stpr_sorgente', 'operator' => '==', 'value' => 'categoria' ) ) ),
					),
					array(
						'key'               => 'field_stpr_scelti',
						'label'             => __( 'Prodotti', 'slug-theme' ),
						'name'              => 'prodotti',
						'type'              => 'relationship',
						'post_type'         => array( 'product' ),
						'return_format'     => 'id',
						'filters'           => array( 'search', 'taxonomy' ),
						'conditional_logic' => array( array( array( 'field' => 'field_stpr_sorgente', 'operator' => '==', 'value' => 'scelti' ) ) ),
					),
					array(
						'key'           => 'field_stpr_numero',
						'label'         => __( 'Quanti', 'slug-theme' ),
						'name'          => 'numero',
						'type'          => 'number',
						'default_value' => 8,
						'min'           => 2,
						'max'           => 16,
					),
				)
			),
			'location' => starter_extra_block_location( 'prodotti-in-evidenza' ),
		)
	);

	starter_add_block_group(
		array(
			'key'      => 'group_starter_instagram',
			'title'    => __( 'Instagram', 'slug-theme' ),
			'fields'   => array(
				array(
					'key'          => 'field_stig_occhiello',
					'label'        => __( 'Occhiello', 'slug-theme' ),
					'name'         => 'occhiello',
					'type'         => 'text',
					'instructions' => __( 'Piccola scritta sopra il titolo, con la parola chiave. Facoltativa.', 'slug-theme' ),
				),
				array(
					'key'           => 'field_stig_titolo',
					'label'         => __( 'Titolo', 'slug-theme' ),
					'name'          => 'titolo',
					'type'          => 'text',
					'default_value' => 'Instagram',
				),
				array(
					'key'   => 'field_stig_account',
					'label' => __( 'Nome account', 'slug-theme' ),
					'name'  => 'account',
					'type'  => 'text',
				),
				array(
					'key'           => 'field_stig_link',
					'label'         => __( 'Pulsante (link al profilo)', 'slug-theme' ),
					'name'          => 'link',
					'type'          => 'link',
					'return_format' => 'array',
				),
				array(
					'key'           => 'field_stig_foto',
					'label'         => __( 'Foto', 'slug-theme' ),
					'name'          => 'foto',
					'type'          => 'gallery',
					'return_format' => 'id',
					'min'           => 3,
					'max'           => 6,
					'instructions'  => __( 'Foto quadrate dal profilo, da aggiornare a mano ogni tanto (nessun collegamento automatico: niente cookie di Instagram né rallentamenti).', 'slug-theme' ),
				),
			),
			'location' => starter_extra_block_location( 'instagram' ),
		)
	);

	starter_add_block_group(
		array(
			'key'      => 'group_starter_team',
			'title'    => __( 'Team', 'slug-theme' ),
			'fields'   => array(
				array(
					'key'   => 'field_stte_occhiello',
					'label' => __( 'Occhiello (titolo SEO, H2)', 'slug-theme' ),
					'name'  => 'occhiello',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_stte_titolo',
					'label' => __( 'Titolo grande (H3)', 'slug-theme' ),
					'name'  => 'titolo',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_stte_testo',
					'label' => __( 'Testo introduttivo', 'slug-theme' ),
					'name'  => 'testo',
					'type'  => 'textarea',
					'rows'  => 3,
				),
				array(
					'key'          => 'field_stte_voci',
					'label'        => __( 'Persone', 'slug-theme' ),
					'name'         => 'voci',
					'type'         => 'repeater',
					'layout'       => 'block',
					'button_label' => __( 'Aggiungi persona', 'slug-theme' ),
					'instructions' => __( 'Foto consigliata: verticale o quadrata con la persona al centro; viene ritagliata in quadrato.', 'slug-theme' ),
					'sub_fields'   => array(
						array(
							'key'           => 'field_stte_foto',
							'label'         => __( 'Foto', 'slug-theme' ),
							'name'          => 'voci_foto',
							'type'          => 'image',
							'return_format' => 'id',
							'preview_size'  => 'medium',
						),
						array(
							'key'   => 'field_stte_nome',
							'label' => __( 'Nome e cognome', 'slug-theme' ),
							'name'  => 'voci_nome',
							'type'  => 'text',
						),
						array(
							'key'   => 'field_stte_ruolo',
							'label' => __( 'Ruolo', 'slug-theme' ),
							'name'  => 'voci_ruolo',
							'type'  => 'text',
						),
						array(
							'key'   => 'field_stte_bio',
							'label' => __( 'Breve bio', 'slug-theme' ),
							'name'  => 'voci_bio',
							'type'  => 'textarea',
							'rows'  => 3,
						),
					),
				),
			),
			'location' => starter_extra_block_location( 'team' ),
		)
	);

	starter_add_block_group(
		array(
			'key'      => 'group_starter_contatti',
			'title'    => __( 'Contatti', 'slug-theme' ),
			'fields'   => array(
				array(
					'key'   => 'field_stco_occhiello',
					'label' => __( 'Occhiello', 'slug-theme' ),
					'name'  => 'occhiello',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_stco_titolo',
					'label' => __( 'Titolo', 'slug-theme' ),
					'name'  => 'titolo',
					'type'  => 'text',
				),
				array(
					'key'          => 'field_stco_recapiti',
					'label'        => __( 'Recapiti', 'slug-theme' ),
					'name'         => 'recapiti',
					'type'         => 'repeater',
					'layout'       => 'table',
					'button_label' => __( 'Aggiungi recapito', 'slug-theme' ),
					'instructions' => __( 'Telefono, email, indirizzo, orari… Email e numeri di telefono diventano link da soli. Se non ne aggiungi, il blocco usa i recapiti di Opzioni → Footer.', 'slug-theme' ),
					'sub_fields'   => array(
						array(
							'key'           => 'field_stco_recapiti_icona',
							'label'         => __( 'Icona', 'slug-theme' ),
							'name'          => 'icona',
							'type'          => 'starter_icon',
							'default_value' => 'phone',
						),
						array(
							'key'   => 'field_stco_recapiti_testo',
							'label' => __( 'Testo', 'slug-theme' ),
							'name'  => 'testo',
							'type'  => 'text',
						),
					),
				),
				array(
					'key'          => 'field_stco_form',
					'label'        => __( 'Shortcode del form', 'slug-theme' ),
					'name'         => 'shortcode_form',
					'type'         => 'text',
					'instructions' => __( 'Es. [contact-form-7 id="12"]. Mostrato a destra dei recapiti.', 'slug-theme' ),
				),
			),
			'location' => starter_extra_block_location( 'contatti' ),
		)
	);

	// Elenco servizi: menu laterale che segue lo scroll + una scheda per servizio (foto, testo, elenco con spunte, pulsante).
	starter_add_block_group(
		array(
			'key'      => 'group_starter_elenco_servizi',
			'title'    => __( 'Elenco servizi', 'slug-theme' ),
			'fields'   => array(
				array(
					'key'          => 'field_ses_voci',
					'label'        => __( 'Servizi', 'slug-theme' ),
					'name'         => 'voci',
					'type'         => 'repeater',
					'layout'       => 'block',
					'button_label' => __( 'Aggiungi servizio', 'slug-theme' ),
					'instructions' => __( 'Ogni servizio ha una voce nel menu laterale e una scheda. Il menu sta a sinistra su desktop; su telefono e tablet diventa una barra di icone fissa in basso.', 'slug-theme' ),
					'sub_fields'   => array(
						array( 'key' => 'field_ses_id', 'label' => __( 'Ancora', 'slug-theme' ), 'name' => 'voci_id', 'type' => 'text', 'instructions' => __( 'Una parola senza spazi (es. vendita): collega la voce del menu alla sua scheda.', 'slug-theme' ) ),
						array( 'key' => 'field_ses_icona', 'label' => __( 'Icona', 'slug-theme' ), 'name' => 'voci_icona', 'type' => 'starter_icon', 'default_value' => 'key' ),
						array( 'key' => 'field_ses_occhiello', 'label' => __( 'Occhiello', 'slug-theme' ), 'name' => 'voci_occhiello', 'type' => 'text' ),
						array( 'key' => 'field_ses_titolo', 'label' => __( 'Titolo', 'slug-theme' ), 'name' => 'voci_titolo', 'type' => 'text' ),
						array( 'key' => 'field_ses_etichetta', 'label' => __( 'Etichetta nel menu', 'slug-theme' ), 'name' => 'voci_etichetta', 'type' => 'text', 'instructions' => __( 'Facoltativa: se vuota, nel menu compare il titolo.', 'slug-theme' ) ),
						array( 'key' => 'field_ses_testo', 'label' => __( 'Testo', 'slug-theme' ), 'name' => 'voci_testo', 'type' => 'textarea', 'rows' => 3, 'new_lines' => '' ),
						array( 'key' => 'field_ses_immagine', 'label' => __( 'Foto', 'slug-theme' ), 'name' => 'voci_immagine', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium' ),
						array( 'key' => 'field_ses_elenco', 'label' => __( 'Elenco con spunte', 'slug-theme' ), 'name' => 'voci_elenco', 'type' => 'textarea', 'rows' => 5, 'new_lines' => '', 'instructions' => __( 'Una voce per riga.', 'slug-theme' ) ),
						array( 'key' => 'field_ses_cta', 'label' => __( 'Pulsante', 'slug-theme' ), 'name' => 'voci_cta', 'type' => 'link', 'return_format' => 'array' ),
					),
				),
			),
			'location' => starter_extra_block_location( 'elenco-servizi' ),
		)
	);
}
add_action( 'acf/init', 'starter_register_extra_block_fields' );

/* Contenuti salvati quando "Foto" era una gallery (1–2): nell'editor il campo mostra la prima foto; la seconda resta letta dal render finché non si salva. */
add_filter(
	'acf/load_value/key=field_stsa_foto',
	function ( $value ) {
		return is_array( $value ) ? (int) reset( $value ) : $value;
	}
);
