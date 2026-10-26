<?php
/**
 * Opzioni del Customizer aggiunte oltre a quelle di functions.php (si registrano dopo, priorità 20):
 * - Header: versione a tutta larghezza, sottomenu col click, icone shop (account, preferiti, carrello) solo con Woo;
 * - Generale: briciole di pane;
 * - Shop (solo con WooCommerce attivo): filtri, card prodotto, preferiti, dati per la fattura;
 * - Blog: aside, colonne, cosa mostrare di data/autore/categorie/tag, riquadro autore, articoli correlati.
 *
 * Tutti i valori sono theme_mod "starter_*": i default stanno accanto all'uso (inc/blog.php, inc/woocommerce.php).
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Registra un'opzione con casella di spunta. */
function starter_customizer_checkbox( $wp_customize, $id, $section, $label, $default = false, $description = '', $priority = 10 ) {
	$wp_customize->add_setting(
		$id,
		array(
			'default'           => $default,
			'sanitize_callback' => 'starter_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		$id,
		array(
			'type'        => 'checkbox',
			'section'     => $section,
			'label'       => $label,
			'description' => $description,
			'priority'    => $priority,
		)
	);
}

/* Registra un menu a tendina; i valori ammessi sono le chiavi di $choices (il resto torna al predefinito). */
function starter_customizer_select( $wp_customize, $id, $section, $label, $choices, $default, $description = '', $priority = 10 ) {
	$wp_customize->add_setting(
		$id,
		array(
			'default'           => $default,
			'sanitize_callback' => function ( $value ) use ( $choices, $default ) {
				return array_key_exists( $value, $choices ) ? $value : $default;
			},
		)
	);

	$wp_customize->add_control(
		$id,
		array(
			'type'        => 'select',
			'section'     => $section,
			'label'       => $label,
			'description' => $description,
			'choices'     => $choices,
			'priority'    => $priority,
		)
	);
}

/* Titoletto che divide i gruppi di opzioni di una sezione. */
function starter_customizer_heading( $wp_customize, $id, $section, $label, $priority ) {
	$wp_customize->add_setting( $id, array( 'sanitize_callback' => '__return_false' ) );

	$wp_customize->add_control(
		new Starter_Customize_Heading_Control(
			$wp_customize,
			$id,
			array(
				'section'  => $section,
				'label'    => $label,
				'priority' => $priority,
			)
		)
	);
}

/* Registra le opzioni del Customizer (Header, Footer, Prestazioni, Blog…) */
function starter_customize_register_extra( $wp_customize ) {

	/* ---------------------------------------------------------------- Header */
	starter_customizer_checkbox(
		$wp_customize,
		'starter_header_fullwidth',
		'starter_options_header',
		__( 'Header a tutta larghezza', 'slug-theme' ),
		false,
		__( 'Fino a 1920px. Con "Flottante" anche la card si allarga.', 'slug-theme' ),
		20
	);

	starter_customizer_checkbox(
		$wp_customize,
		'starter_header_submenu_click',
		'starter_options_header',
		__( 'Le voci con sottomenu si aprono col click', 'slug-theme' ),
		true,
		__( 'Sottomenu chiusi, si aprono col click. La voce madre non è più un link.', 'slug-theme' ),
		21
	);

	/* Social e icone: stile, divisore, ordine */
	starter_customizer_heading( $wp_customize, 'starter_heading_header_tools', 'starter_options_header', __( 'Social e icone', 'slug-theme' ), 25 );

	starter_customizer_select(
		$wp_customize,
		'starter_header_icon_style',
		'starter_options_header',
		__( 'Stile delle icone', 'slug-theme' ),
		array(
			'boxed' => __( 'Con bordo e animazione (come i social)', 'slug-theme' ),
			'plain' => __( 'Solo icona, con sfondo al passaggio', 'slug-theme' ),
			'tight' => __( 'Semplici (solo icona, piccola)', 'slug-theme' ),
		),
		'boxed',
		__( 'Vale per social e icone dell\'header.', 'slug-theme' ),
		26
	);

	starter_customizer_select(
		$wp_customize,
		'starter_header_tools_order',
		'starter_options_header',
		__( 'Ordine: social e icone', 'slug-theme' ),
		array(
			'social-first' => __( 'Prima i social, poi le icone (ricerca, carrello…)', 'slug-theme' ),
			'icons-first'  => __( 'Prima le icone, poi i social', 'slug-theme' ),
		),
		'social-first',
		__( 'Quale gruppo sta a sinistra. Col menu ridotto i social sono solo nel pannello.', 'slug-theme' ),
		27
	);

	starter_customizer_checkbox(
		$wp_customize,
		'starter_header_divider',
		'starter_options_header',
		__( 'Divisore tra social e icone', 'slug-theme' ),
		false,
		__( 'Linea tra social e icone.', 'slug-theme' ),
		28
	);

	/* Icone */
	starter_customizer_heading( $wp_customize, 'starter_heading_header_icons', 'starter_options_header', __( 'Icone nell\'header', 'slug-theme' ), 30 );

	starter_customizer_checkbox(
		$wp_customize,
		'starter_header_show_search',
		'starter_options_header',
		__( 'Mostra la ricerca', 'slug-theme' ),
		true,
		__( 'Ricerca in prodotti (con Woo), pagine e articoli.', 'slug-theme' ),
		31
	);

	if ( starter_has_woocommerce() ) {
		starter_customizer_checkbox(
			$wp_customize,
			'starter_header_show_account',
			'starter_options_header',
			__( 'Mostra l\'icona Account', 'slug-theme' ),
			false,
			__( 'Con tooltip. Solo con WooCommerce.', 'slug-theme' ),
			32
		);

		starter_customizer_checkbox(
			$wp_customize,
			'starter_header_show_wishlist',
			'starter_options_header',
			__( 'Mostra l\'icona Preferiti', 'slug-theme' ),
			false,
			__( 'Con numero e tooltip. Pagina in Opzioni → Shop.', 'slug-theme' ),
			33
		);

		starter_customizer_checkbox(
			$wp_customize,
			'starter_header_show_cart',
			'starter_options_header',
			__( 'Mostra l\'icona Carrello', 'slug-theme' ),
			false,
			__( 'Con numero e tooltip.', 'slug-theme' ),
			34
		);
	}

	/* Logo che cambia scrollando */
	starter_customizer_heading( $wp_customize, 'starter_heading_header_logo', 'starter_options_header', __( 'Logo scrollando', 'slug-theme' ), 40 );

	starter_customizer_select(
		$wp_customize,
		'starter_header_scroll_logo',
		'starter_options_header',
		__( 'Cosa fa il logo quando scorri la pagina', 'slug-theme' ),
		array(
			'none'  => __( 'Resta uguale', 'slug-theme' ),
			'small' => __( 'Diventa più piccolo', 'slug-theme' ),
			'icon'  => __( 'Diventa un\'icona che carico io', 'slug-theme' ),
		),
		'none',
		__( 'Dopo lo scroll il logo si rimpicciolisce o diventa l\'icona qui sotto.', 'slug-theme' ),
		41
	);

	$wp_customize->add_setting(
		'starter_header_logo_icon',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'starter_header_logo_icon',
			array(
				'label'       => __( 'Icona del logo (scroll)', 'slug-theme' ),
				'description' => __( 'PNG o SVG quadrata, alta 40px.', 'slug-theme' ),
				'section'     => 'starter_options_header',
				'priority'    => 42,
				'settings'    => 'starter_header_logo_icon',
			)
		)
	);

	starter_customizer_heading( $wp_customize, 'starter_heading_corners', 'starter_options_general', __( 'Bordi del sito', 'slug-theme' ), 4.05 );

	/* Angoli: un solo raggio per card, tab, immagini e riquadri (i pulsanti hanno il loro in Opzioni → Pulsanti). In cima a Generale, subito dopo la larghezza del contenuto: è una delle prime cose da impostare. */
	starter_customizer_select(
		$wp_customize,
		'starter_corner_radius',
		'starter_options_general',
		__( 'Angoli di card, tab, immagini e riquadri', 'slug-theme' ),
		array(
			'none'   => __( 'Nessuno (angoli vivi)', 'slug-theme' ),
			'sm'     => __( 'Piccoli (sm)', 'slug-theme' ),
			'md'     => __( 'Medi (md)', 'slug-theme' ),
			'lg'     => __( 'Grandi (lg)', 'slug-theme' ),
			'xl'     => __( 'Molto grandi (xl)', 'slug-theme' ),
			'custom' => __( 'Valore mio (px)', 'slug-theme' ),
		),
		'md',
		__( 'Vale per tutto il sito: tab di immobili, portfolio e shop, card, immagini, riquadri e campi dei moduli, anche dentro i blocchi con campi personalizzati. Se i pulsanti non hanno un arrotondamento loro, lo seguono.', 'slug-theme' ),
		4.1
	);

	$wp_customize->add_setting(
		'starter_corner_radius_custom',
		array(
			'default'           => 12,
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'starter_corner_radius_custom',
		array(
			'type'            => 'number',
			'section'         => 'starter_options_general',
			'label'           => __( 'Il tuo valore (px)', 'slug-theme' ),
			'description'     => __( 'Si usa con "Valore mio (px)". Da 0 a 80.', 'slug-theme' ),
			'input_attrs'     => array(
				'min' => 0,
				'max' => 80,
			),
			'priority'        => 4.2,
		)
	);

	/* --------------------------------------------------------------- Generale */
	starter_customizer_checkbox(
		$wp_customize,
		'starter_breadcrumbs',
		'starter_options_general',
		__( 'Mostra le briciole di pane', 'slug-theme' ),
		false,
		__( 'Percorso Home / pagina, tranne in home.', 'slug-theme' ),
		211
	);

	starter_customizer_heading( $wp_customize, 'starter_heading_breadcrumbs', 'starter_options_general', __( 'Briciole di pane', 'slug-theme' ), 210 );

	starter_customizer_select(
		$wp_customize,
		'starter_breadcrumbs_style',
		'starter_options_general',
		__( 'Stile delle briciole', 'slug-theme' ),
		array(
			'inline' => __( 'Nel titolo della pagina (come Chi siamo, Servizi)', 'slug-theme' ),
			'bar'    => __( 'Fascia con bordo sotto l\'header', 'slug-theme' ),
		),
		'inline',
		__( 'Vale per tutte le pagine, blog e shop compresi.', 'slug-theme' ),
		212
	);

	starter_customizer_heading( $wp_customize, 'starter_heading_whatsapp', 'starter_options_general', __( 'WhatsApp', 'slug-theme' ), 220 );

	$wp_customize->add_setting(
		'starter_whatsapp_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'starter_whatsapp_url',
		array(
			'type'        => 'url',
			'section'     => 'starter_options_general',
			'label'       => __( 'Link WhatsApp', 'slug-theme' ),
			'description' => __( 'Es. https://wa.me/393331234567. Vuoto = nessun pulsante.', 'slug-theme' ),
			'priority'    => 221,
		)
	);

	$wp_customize->add_setting(
		'starter_whatsapp_text',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'starter_whatsapp_text',
		array(
			'type'        => 'text',
			'section'     => 'starter_options_general',
			'label'       => __( 'Testo del pulsante', 'slug-theme' ),
			'description' => __( 'Vuoto = "Scrivici su WhatsApp".', 'slug-theme' ),
			'priority'    => 222,
		)
	);

	/* ------------------------------------------------------------------- Shop */
	if ( starter_has_woocommerce() ) {
		$wp_customize->add_section(
			'starter_options_shop',
			array(
				'title'       => __( 'Shop', 'slug-theme' ),
				'description' => __( 'Negozio WooCommerce.', 'slug-theme' ),
				'panel'       => 'starter_options',
				'priority'    => 12,
			)
		);

		starter_customizer_heading( $wp_customize, 'starter_heading_shop_filters', 'starter_options_shop', __( 'Filtri', 'slug-theme' ), 1 );

		starter_customizer_checkbox(
			$wp_customize,
			'starter_shop_filters',
			'starter_options_shop',
			__( 'Attiva i filtri nella pagina Shop', 'slug-theme' ),
			false,
			__( 'Filtri sopra la griglia; su mobile in un pannello.', 'slug-theme' ),
			2
		);

		starter_customizer_select(
			$wp_customize,
			'starter_shop_columns',
			'starter_options_shop',
			__( 'Colonne dei prodotti', 'slug-theme' ),
			array(
				2 => __( '2 colonne', 'slug-theme' ),
				3 => __( '3 colonne', 'slug-theme' ),
				4 => __( '4 colonne', 'slug-theme' ),
			),
			4,
			__( 'Su desktop; su tablet e telefono si riducono da sole.', 'slug-theme' ),
			8
		);

		starter_customizer_checkbox( $wp_customize, 'starter_shop_test_mode', 'starter_options_shop', __( 'Negozio in test', 'slug-theme' ), false, __( 'Avviso giallo in fondo a tutte le pagine, sotto il footer. Da spegnere online.', 'slug-theme' ), 30 );

		$wp_customize->add_setting(
			'starter_shop_test_text',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'starter_shop_test_text',
			array(
				'type'        => 'text',
				'section'     => 'starter_options_shop',
				'label'       => __( 'Testo dell\'avviso', 'slug-theme' ),
				'description' => __( 'Vuoto = testo standard.', 'slug-theme' ),
				'priority'    => 31,
			)
		);

		starter_customizer_checkbox( $wp_customize, 'starter_shop_show_count', 'starter_options_shop', __( 'Mostra il numero di prodotti', 'slug-theme' ), false, __( 'Es. "12 prodotti" sopra la griglia.', 'slug-theme' ), 9 );
		starter_customizer_checkbox( $wp_customize, 'starter_shop_filters_in_categories', 'starter_options_shop', __( 'Filtri anche nelle pagine delle categorie', 'slug-theme' ), false, '', 3 );
		starter_customizer_checkbox( $wp_customize, 'starter_shop_filter_search', 'starter_options_shop', __( 'Filtro: ricerca con suggerimenti', 'slug-theme' ), true, '', 4 );
		starter_customizer_checkbox( $wp_customize, 'starter_shop_filter_category', 'starter_options_shop', __( 'Filtro: categoria', 'slug-theme' ), true, '', 5 );
		starter_customizer_checkbox( $wp_customize, 'starter_shop_filter_price', 'starter_options_shop', __( 'Filtro: prezzo (da / a)', 'slug-theme' ), true, '', 6 );
		starter_customizer_checkbox(
			$wp_customize,
			'starter_shop_filter_attributes',
			'starter_options_shop',
			__( 'Filtri: attributi (Colore, Materiale…)', 'slug-theme' ),
			true,
			__( 'Un filtro per attributo di WooCommerce.', 'slug-theme' ),
			7
		);

		starter_customizer_heading( $wp_customize, 'starter_heading_shop_card', 'starter_options_shop', __( 'Card prodotto', 'slug-theme' ), 10 );

		starter_customizer_checkbox( $wp_customize, 'starter_shop_card_wishlist', 'starter_options_shop', __( 'Cuore "preferiti" sulla card', 'slug-theme' ), true, '', 11 );
		starter_customizer_checkbox( $wp_customize, 'starter_shop_card_add_to_cart', 'starter_options_shop', __( 'Pulsante "Aggiungi al carrello" all\'hover', 'slug-theme' ), true, '', 12 );
		starter_customizer_checkbox( $wp_customize, 'starter_shop_card_hover_image', 'starter_options_shop', __( 'Seconda foto all\'hover', 'slug-theme' ), true, __( 'Mostra la 2ª foto della galleria.', 'slug-theme' ), 13 );
		starter_customizer_checkbox( $wp_customize, 'starter_shop_card_categories', 'starter_options_shop', __( 'Mostra le categorie', 'slug-theme' ), true, '', 14 );
		starter_customizer_checkbox( $wp_customize, 'starter_shop_card_description', 'starter_options_shop', __( 'Mostra la descrizione breve', 'slug-theme' ), false, '', 15 );

		starter_customizer_heading( $wp_customize, 'starter_heading_shop_pages', 'starter_options_shop', __( 'Pagine e cassa', 'slug-theme' ), 20 );

		$wp_customize->add_setting(
			'starter_shop_wishlist_page',
			array(
				'default'           => 0,
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			'starter_shop_wishlist_page',
			array(
				'type'        => 'dropdown-pages',
				'section'     => 'starter_options_shop',
				'label'       => __( 'Pagina dei preferiti', 'slug-theme' ),
				'description' => __( 'Pagina con [starter_preferiti]. Vuoto = /preferiti/.', 'slug-theme' ),
				'priority'    => 21,
			)
		);

		starter_customizer_checkbox(
			$wp_customize,
			'starter_shop_invoice_fields',
			'starter_options_shop',
			__( 'Cassa: campi per la fattura', 'slug-theme' ),
			true,
			__( 'P. IVA, Codice fiscale, SDI e PEC (facoltativi).', 'slug-theme' ),
			22
		);
	}

	/* ------------------------------------------------------ Footer: spazio libero */
	starter_customizer_heading( $wp_customize, 'starter_heading_footer_free', 'starter_options_footer', __( 'Spazio libero', 'slug-theme' ), 90 );

	$wp_customize->add_setting(
		'starter_footer_newsletter_title',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'starter_footer_newsletter_title',
		array(
			'type'        => 'text',
			'section'     => 'starter_options_footer',
			'label'       => __( 'Titolo dello spazio libero', 'slug-theme' ),
			'description' => __( 'Facoltativo.', 'slug-theme' ),
			'priority'    => 91,
		)
	);

	$wp_customize->add_setting(
		'starter_footer_newsletter_subtitle',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'starter_footer_newsletter_subtitle',
		array(
			'type'        => 'text',
			'section'     => 'starter_options_footer',
			'label'       => __( 'Sottotitolo', 'slug-theme' ),
			'description' => __( 'Facoltativo, sotto il titolo.', 'slug-theme' ),
			'priority'    => 92,
		)
	);

	$wp_customize->add_setting(
		'starter_footer_newsletter_shortcode',
		array(
			'default'           => '',
			'sanitize_callback' => 'wp_kses_post',
		)
	);

	$wp_customize->add_control(
		'starter_footer_newsletter_shortcode',
		array(
			'type'        => 'textarea',
			'section'     => 'starter_options_footer',
			'label'       => __( 'Contenuto dello spazio libero', 'slug-theme' ),
			'description' => __( 'Shortcode (es. un form), testo o HTML. Vuoto = nessuno spazio.', 'slug-theme' ),
			'priority'    => 93,
		)
	);

	starter_customizer_heading( $wp_customize, 'starter_heading_footer_style4', 'starter_options_footer', __( 'Stile 4: pagamenti', 'slug-theme' ), 95 );

	$wp_customize->add_setting(
		'starter_footer_payments_image',
		array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'starter_footer_payments_image',
			array(
				'section'     => 'starter_options_footer',
				'label'       => __( 'Immagine dei metodi di pagamento', 'slug-theme' ),
				'description' => __( 'Solo Stile 4: loghi dei pagamenti, sotto i social.', 'slug-theme' ),
				'mime_type'   => 'image',
				'priority'    => 96,
			)
		)
	);

	/* ------------------------------------------------------------ Prestazioni */
	$wp_customize->add_section(
		'starter_options_perf',
		array(
			'title'       => __( 'Prestazioni', 'slug-theme' ),
			'description' => sprintf(
				/* translators: %s: link alla pagina Media → Ottimizza immagini. */
				__( 'Velocità del sito per visitatori e motori di ricerca. Le immagini diventano WebP in automatico e gli script non bloccano il caricamento: qui scegli il resto. Le immagini già in Libreria media si ottimizzano da %s.', 'slug-theme' ),
				'<a href="' . esc_url( admin_url( 'upload.php?page=starter-media-optimize' ) ) . '" target="_blank">' . esc_html__( 'Media → Ottimizza immagini', 'slug-theme' ) . '</a>'
			),
			'panel'       => 'starter_options',
			'priority'    => 14,
		)
	);

	starter_customizer_checkbox( $wp_customize, 'starter_perf_htaccess', 'starter_options_perf', __( 'Compressione e cache del browser (file .htaccess)', 'slug-theme' ), true, __( 'Scrive nel .htaccess le regole per comprimere i file (Brotli/Gzip) e farli ricordare al browser per un anno. Serve un hosting Apache o LiteSpeed; spegnendo la casella le regole vengono tolte.', 'slug-theme' ), 1 );
	starter_customizer_checkbox( $wp_customize, 'starter_perf_local_fonts', 'starter_options_perf', __( 'Google Fonts serviti dal tuo sito', 'slug-theme' ), true, __( 'Scarica i font scelti in Tipografia sul tuo sito (cartella uploads/starter-fonts): niente richieste a Google, più veloce e più in regola con la privacy. Si aggiorna quando salvi il Personalizza; se il download fallisce restano i Google Fonts.', 'slug-theme' ), 1.5 );
	starter_customizer_checkbox( $wp_customize, 'starter_perf_preload_hero', 'starter_options_perf', __( 'Precarica la foto di apertura (hero)', 'slug-theme' ), true, __( 'Se la pagina comincia con una hero con foto, il browser la scarica subito: la pagina sembra più veloce.', 'slug-theme' ), 2 );
	starter_customizer_checkbox( $wp_customize, 'starter_perf_cf7_lazy', 'starter_options_perf', __( 'Carica Contact Form 7 solo dove c\'è un modulo', 'slug-theme' ), true, __( 'Evita di caricare script e stile dei moduli nelle pagine che non ne hanno (con il form newsletter nel footer restano in tutte le pagine).', 'slug-theme' ), 3 );
	starter_customizer_checkbox( $wp_customize, 'starter_perf_avif', 'starter_options_perf', __( 'Immagini AVIF (con ripiego WebP)', 'slug-theme' ), true, __( 'Accanto a ogni immagine crea una versione AVIF più leggera (sempre di ottima qualità) e la propone ai browser che la leggono; gli altri vedono il WebP. Non tocca SVG, favicon e logo delle email. Richiede PHP con AVIF: se il server non lo ha, resta il WebP.', 'slug-theme' ), 2.2 );

	$wp_customize->add_setting(
		'starter_perf_avif_quality',
		array(
			'default'           => 70,
			'sanitize_callback' => function ( $value ) {
				return max( 40, min( 90, (int) $value ) );
			},
		)
	);

	$wp_customize->add_control(
		'starter_perf_avif_quality',
		array(
			'type'        => 'number',
			'section'     => 'starter_options_perf',
			'label'       => __( 'Qualità AVIF (40–90)', 'slug-theme' ),
			'description' => __( '70 è un buon equilibrio. Più alto = file più pesante. Vale per le immagini create da ora; per le altre usa Media → Ottimizza immagini.', 'slug-theme' ),
			'input_attrs' => array( 'min' => 40, 'max' => 90, 'step' => 1 ),
			'priority'    => 2.3,
		)
	);

	starter_customizer_checkbox( $wp_customize, 'starter_perf_webm', 'starter_options_perf', __( 'Video WebM (con ripiego mp4)', 'slug-theme' ), true, __( 'Se accanto a un video mp4 carichi in Libreria media un .webm con lo stesso nome (es. film.mp4 e film.webm), il browser usa il WebM e gli altri l\'mp4. Vale per i blocchi del tema e per il blocco Video di Gutenberg.', 'slug-theme' ), 2.4 );
	starter_customizer_checkbox( $wp_customize, 'starter_perf_favicon', 'starter_options_perf', __( 'Favicon .ico e icone per Google, Android e Apple', 'slug-theme' ), true, __( 'Dall\'icona del sito (Identità del sito, 512×512) crea in automatico favicon.ico, le icone PNG 192 e 512, l\'icona Apple 180 e il manifest. Il file originale resta PNG.', 'slug-theme' ), 2.5 );
	starter_customizer_checkbox( $wp_customize, 'starter_perf_bundle', 'starter_options_perf', __( 'Unisci CSS e JS in un solo file', 'slug-theme' ), false, __( 'Per ogni tipo di pagina crea un solo file CSS e uno JS (nello stesso ordine di prima, compressi) al posto di molti. Esclusi i file esterni (es. Google Fonts). Se qualcosa si rompe, spegni la casella.', 'slug-theme' ), 5 );
	starter_customizer_checkbox( $wp_customize, 'starter_perf_minify_html', 'starter_options_perf', __( 'Comprimi il codice HTML', 'slug-theme' ), false, __( 'Toglie spazi, a capo e commenti dal codice della pagina (che diventa praticamente su una riga). Non cambia ciò che si vede.', 'slug-theme' ), 6 );
	starter_customizer_checkbox( $wp_customize, 'starter_perf_no_migrate', 'starter_options_perf', __( 'Togli jQuery Migrate', 'slug-theme' ), true, __( 'Piccolo script per vecchi plugin: se un plugin datato smette di funzionare, riaccendilo.', 'slug-theme' ), 4 );

	/* ------------------------------------------------------------------- Blog */
	$wp_customize->add_section(
		'starter_options_blog',
		array(
			'title'       => __( 'Blog', 'slug-theme' ),
			'description' => __( 'Pagina Blog, categorie, tag, autori, ricerca e articoli.', 'slug-theme' ),
			'panel'       => 'starter_options',
			'priority'    => 13,
		)
	);

	$blog = 'starter_options_blog';
	$def  = starter_blog_defaults();

	starter_customizer_heading( $wp_customize, 'starter_heading_blog_layout', $blog, __( 'Struttura', 'slug-theme' ), 1 );

	starter_customizer_checkbox( $wp_customize, 'starter_blog_aside_list', $blog, __( 'Aside (filtri e ricerca) nella pagina Blog, nelle categorie e nella ricerca', 'slug-theme' ), $def['aside_list'], __( 'Colonna a destra: ricerca, categorie, ultimi articoli, tag.', 'slug-theme' ), 2 );
	starter_customizer_checkbox( $wp_customize, 'starter_blog_aside_single', $blog, __( 'Aside negli articoli', 'slug-theme' ), $def['aside_single'], '', 3 );

	starter_customizer_checkbox( $wp_customize, 'starter_blog_aside_sticky', $blog, __( 'Aside fissa', 'slug-theme' ), $def['aside_sticky'], __( 'Resta in vista mentre scorri (a 20px dall\'alto). Solo desktop.', 'slug-theme' ), 3.5 );

	$wp_customize->add_setting(
		'starter_blog_columns',
		array(
			'default'           => 3,
			'sanitize_callback' => function ( $value ) {
				return in_array( (int) $value, array( 1, 2, 3, 4 ), true ) ? (int) $value : 3;
			},
		)
	);

	$wp_customize->add_control(
		'starter_blog_columns',
		array(
			'type'        => 'select',
			'section'     => $blog,
			'label'       => __( 'Colonne della griglia articoli', 'slug-theme' ),
			'description' => __( 'Su desktop. "1 colonna": foto a sinistra, testo a destra.', 'slug-theme' ),
			'choices'     => array(
				1 => __( '1 colonna (immagine a sinistra, testo a destra)', 'slug-theme' ),
				2 => __( '2 colonne', 'slug-theme' ),
				3 => __( '3 colonne', 'slug-theme' ),
				4 => __( '4 colonne', 'slug-theme' ),
			),
			'priority'    => 4,
		)
	);

	// Articoli per pagina: è l'opzione di WordPress (Impostazioni → Lettura), modificata da qui.
	$wp_customize->add_setting(
		'posts_per_page',
		array(
			'type'              => 'option',
			'default'           => 9,
			'capability'        => 'manage_options',
			'sanitize_callback' => function ( $value ) {
				return max( 1, min( 50, (int) $value ) );
			},
		)
	);

	$wp_customize->add_control(
		'posts_per_page',
		array(
			'type'        => 'number',
			'section'     => $blog,
			'label'       => __( 'Articoli per pagina', 'slug-theme' ),
			'description' => __( 'È la stessa impostazione di WordPress (Impostazioni → Lettura). Con 3 colonne conviene un multiplo di 3.', 'slug-theme' ),
			'input_attrs' => array( 'min' => 1, 'max' => 50 ),
			'priority'    => 4.5,
		)
	);

	starter_customizer_checkbox(
		$wp_customize,
		'starter_blog_featured',
		$blog,
		__( 'Ultimo articolo in evidenza', 'slug-theme' ),
		$def['featured'],
		__( 'Il più recente a tutta larghezza, con foto a sinistra. Solo prima pagina, non nella ricerca.', 'slug-theme' ),
		5
	);

	starter_customizer_heading( $wp_customize, 'starter_heading_blog_list', $blog, __( 'Card dell\'elenco', 'slug-theme' ), 10 );

	starter_customizer_checkbox( $wp_customize, 'starter_blog_list_date', $blog, __( 'Mostra la data', 'slug-theme' ), $def['list_date'], '', 11 );
	starter_customizer_checkbox( $wp_customize, 'starter_blog_list_author', $blog, __( 'Mostra l\'autore', 'slug-theme' ), $def['list_author'], '', 12 );
	starter_customizer_checkbox( $wp_customize, 'starter_blog_author_link', $blog, __( 'Nome dell\'autore cliccabile', 'slug-theme' ), $def['author_link'], __( 'Porta all\'elenco degli articoli di quell\'autore. Vale per le card e per l\'articolo.', 'slug-theme' ), 12.5 );
	starter_customizer_checkbox( $wp_customize, 'starter_blog_list_cats', $blog, __( 'Mostra le categorie', 'slug-theme' ), $def['list_cats'], '', 13 );
	starter_customizer_checkbox( $wp_customize, 'starter_blog_list_tags', $blog, __( 'Mostra i tag', 'slug-theme' ), $def['list_tags'], __( 'Sotto il testo, prima di "Leggi tutto".', 'slug-theme' ), 14 );

	starter_customizer_heading( $wp_customize, 'starter_heading_blog_single', $blog, __( 'Articolo', 'slug-theme' ), 20 );

	starter_customizer_checkbox( $wp_customize, 'starter_blog_single_date', $blog, __( 'Mostra la data', 'slug-theme' ), $def['single_date'], '', 21 );
	starter_customizer_checkbox( $wp_customize, 'starter_blog_single_author', $blog, __( 'Mostra l\'autore', 'slug-theme' ), $def['single_author'], '', 22 );
	starter_customizer_checkbox( $wp_customize, 'starter_blog_single_cats', $blog, __( 'Mostra le categorie', 'slug-theme' ), $def['single_cats'], '', 23 );
	starter_customizer_checkbox( $wp_customize, 'starter_blog_toc', $blog, __( 'Sommario', 'slug-theme' ), $def['toc'], __( 'Indice dei titoli (H2 e H3) prima del primo H2, con almeno due titoli.', 'slug-theme' ), 28 );
	starter_customizer_checkbox( $wp_customize, 'starter_blog_single_tags', $blog, __( 'Mostra i tag (a fine articolo)', 'slug-theme' ), $def['single_tags'], '', 24 );
	starter_customizer_checkbox( $wp_customize, 'starter_blog_author_box', $blog, __( 'Riquadro autore a fine articolo', 'slug-theme' ), $def['author_box'], __( 'Avatar, nome e biografia presi dal profilo dell\'utente (Utenti → Profilo).', 'slug-theme' ), 25 );
	starter_customizer_checkbox( $wp_customize, 'starter_blog_related', $blog, __( 'Articoli correlati ("Continua a leggere")', 'slug-theme' ), $def['related'], '', 26 );
	starter_customizer_checkbox( $wp_customize, 'starter_blog_share', $blog, __( 'Pulsanti "Condividi"', 'slug-theme' ), $def['share'], __( 'Facebook, X, LinkedIn e WhatsApp: solo link, nessuno script di terze parti.', 'slug-theme' ), 27 );
}
add_action( 'customize_register', 'starter_customize_register_extra', 20 );
