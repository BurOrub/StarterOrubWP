<?php
/* Starter Theme - functions.php */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Evita accesso diretto al file.
}

define( 'STARTER_THEME_VERSION', '1.0.4' );

/* Modulo prestazioni (WebP, preload, pulizia head): un file dedicato in inc/. */
require_once get_template_directory() . '/inc/performance.php';
require_once get_template_directory() . '/inc/media-modern.php';
require_once get_template_directory() . '/inc/assets-optimizer.php';

/* Ottimizzazione della Libreria media (pagina in Media, AJAX, WP-CLI): serve solo in amministrazione e da riga di comando. */
if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
	require_once get_template_directory() . '/inc/media-optimizer.php';
}
require_once get_template_directory() . '/inc/fonts-local.php';

/* Icone SVG, funzioni condivise dai blocchi, pulsante WhatsApp e opzioni comuni dei blocchi (i blocchi sono nel plugin Starter Blocks). */
require_once get_template_directory() . '/inc/icons.php';
require_once get_template_directory() . '/inc/block-helpers.php';
require_once get_template_directory() . '/inc/whatsapp.php';
require_once get_template_directory() . '/inc/block-options.php';

/* Moduli del tema: shop (WooCommerce, preferiti, filtri), blog, breadcrumb, CF7, pagine di base. Ognuno è inerte se il plugin relativo non c'è. */
require_once get_template_directory() . '/inc/woocommerce.php';
require_once get_template_directory() . '/inc/wishlist.php';
require_once get_template_directory() . '/inc/shop-filters.php';
require_once get_template_directory() . '/inc/menu.php';
require_once get_template_directory() . '/inc/header.php';
require_once get_template_directory() . '/inc/topbar.php';
require_once get_template_directory() . '/inc/custom-code.php';
require_once get_template_directory() . '/inc/search.php';
require_once get_template_directory() . '/inc/blog.php';
require_once get_template_directory() . '/inc/breadcrumbs.php';
require_once get_template_directory() . '/inc/cf7.php';
require_once get_template_directory() . '/inc/customizer-extra.php';
require_once get_template_directory() . '/inc/modules.php';
require_once get_template_directory() . '/inc/shop-setup.php';
require_once get_template_directory() . '/inc/demo-content.php';
require_once get_template_directory() . '/inc/starter-pages.php';
require_once get_template_directory() . '/inc/cookieyes.php';
require_once get_template_directory() . '/inc/compatibility.php';
require_once get_template_directory() . '/inc/duplicate.php';

/* Aggiornamenti del tema da GitHub (notifica classica di WordPress): inerte finché non è indicato il repository. */
require_once get_template_directory() . '/inc/updater.php';

/* Schermata "Aspetto → Installa plugin": Contact Form 7, Yoast e gli altri plugin del sistema, da installare in un colpo solo. */
require_once get_template_directory() . '/inc/plugins-required.php';

/* Setup tema: supporti, menu, textdomain. */
function starter_setup() {
	load_theme_textdomain( 'slug-theme', get_template_directory() . '/languages' );

	add_theme_support( 'custom-logo' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'align-wide' );
	add_post_type_support( 'page', 'excerpt' );
	add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script' ) );

	// editor-styles: stesso CSS del frontend + override solo-editor, blocchi fedeli al sito.
	add_theme_support( 'editor-styles' );
	add_editor_style( array( 'CSS/starter.css', 'CSS/shop.css', 'CSS/filters.css', 'CSS/gutenberg.css' ) );

	register_nav_menus( array( 'header' => esc_html__( 'Header', 'slug-theme' ) ) );
	register_nav_menus( array( 'privacy' => esc_html__( 'Privacy', 'slug-theme' ) ) );

	// Colonne menu del footer "Stile 4" (il titolo della colonna è il nome del menu assegnato).
	register_nav_menus(
		array(
			'footer-link'        => esc_html__( 'Footer: colonna Link (Stile 4)', 'slug-theme' ),
			'footer-riferimenti' => esc_html__( 'Footer: colonna Riferimenti (Stile 4)', 'slug-theme' ),
		)
	);

	// Facoltativa: se non assegnata, il pannello mobile usa comunque il menu "Header".
	register_nav_menus( array( 'mobile' => esc_html__( 'Mobile (opzionale, sostituisce "Header" solo nel pannello mobile)', 'slug-theme' ) ) );
}
add_action( 'after_setup_theme', 'starter_setup' );

/* Classe "has-hero" sul body: permette all'header fixed di partire trasparente solo sopra la hero, senza JS. */
function starter_body_class_has_hero( $classes ) {
	$content = (string) get_post_field( 'post_content', get_queried_object_id() );

	if ( is_singular() && ( str_contains( $content, 'hero-section' ) || str_contains( $content, 'wp:starter-theme/hero-slider' ) || str_starts_with( ltrim( $content ), '<!-- wp:starter-theme/hero-video' ) || str_contains( $content, 'fullbleed' ) || str_contains( $content, 'cover-hero' ) ) ) {
		$classes[] = 'has-hero';
	}

	if ( starter_is_minimal_header() ) {
		$classes[] = 'header-minimal';
	}

	if ( starter_is_standard_header() ) {
		$classes[] = 'header-standard';
	}

	if ( starter_is_pills_header() ) {
		$classes[] = 'header-pills';
	}

	if ( starter_is_floating_header() ) {
		$classes[] = 'header-floating';
	}

	if ( has_nav_menu( 'mobile' ) ) {
		$classes[] = 'has-mobile-menu';
	}

	// Header a tutta larghezza (Opzioni → Header): il contenuto dell'header arriva fino a 1920px invece di fermarsi a --content-width.
	if ( get_theme_mod( 'starter_header_fullwidth', false ) ) {
		$classes[] = 'header-fullwidth';
	}

	// Voci con sottomenu: il click le apre (pulsante) invece di portare alla pagina; vedi inc/menu.php.
	if ( get_theme_mod( 'starter_header_submenu_click', true ) ) {
		$classes[] = 'submenu-click';
	}

	if ( starter_has_woocommerce() && starter_header_shop_any() ) {
		$classes[] = 'has-header-shop';
	}

	return $classes;
}
add_filter( 'body_class', 'starter_body_class_has_hero' );

/* Estratto troncato a caratteri (non parole), per altezza prevedibile nelle card blog. */
function starter_excerpt_chars( $post_id = null, $limit = 240 ) {
	$excerpt = get_the_excerpt( $post_id );
	$excerpt = wp_strip_all_tags( $excerpt );

	if ( mb_strlen( $excerpt ) <= $limit ) {
		return $excerpt;
	}

	$excerpt = mb_substr( $excerpt, 0, $limit );
	$excerpt = preg_replace( '/\s+?(\S+)?$/u', '', $excerpt );

	return $excerpt . '…';
}

/* Sidebar del blog (categorie, articoli, tag, ricerca). */
function starter_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Sidebar Blog', 'slug-theme' ),
			'id'            => 'sidebar-blog',
			'description'   => __( 'Widget visibili nella colonna laterale della pagina blog.', 'slug-theme' ),
			'before_widget' => '<div class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'starter_widgets_init' );

/* Customizer: logo header trasparente (in "Identità del sito") + pannello "Opzioni" per le impostazioni del tema. */
function starter_customize_register( $wp_customize ) {

	// Definiti qui (non globali): WP_Customize_Control esiste solo nel Customizer, non nel frontend.
	if ( ! class_exists( 'Starter_Add_Icon_Button_Control' ) ) {
		class Starter_Add_Icon_Button_Control extends WP_Customize_Control {
			public $type = 'starter_add_icon_button';

			/* Disegna il pulsante "+ Aggiungi icona personalizzata" */
			public function render_content() {
				?>
				<button type="button" class="button starter-add-icon-button">
					<?php esc_html_e( '+ Aggiungi icona personalizzata', 'slug-theme' ); ?>
				</button>
				<?php
			}
		}
	}

	// Sottotitolo per dividere gruppi di controlli in una sezione (WP non ha un tipo nativo per questo).
	if ( ! class_exists( 'Starter_Customize_Heading_Control' ) ) {
		class Starter_Customize_Heading_Control extends WP_Customize_Control {
			public $type = 'starter_heading';

			/* Disegna un titolo di sezione nel pannello */
			public function render_content() {
				?>
				<h3 class="starter-customize-heading"><?php echo esc_html( $this->label ); ?></h3>
				<?php
			}
		}
	}

	// Bottone CTA generico con testo/classe passati come argomenti (a differenza del bottone icona sopra, fisso).
	if ( ! class_exists( 'Starter_Customize_Button_Control' ) ) {
		class Starter_Customize_Button_Control extends WP_Customize_Control {
			public $type = 'starter_button';
			public $button_text = '';
			public $button_class = '';

			/* Disegna un pulsante con testo e classe scelti */
			public function render_content() {
				?>
				<button type="button" class="button <?php echo esc_attr( $this->button_class ); ?>">
					<?php echo esc_html( $this->button_text ); ?>
				</button>
				<?php
			}
		}
	}

	// Checkbox multipli in un solo controllo (uso: assegnazione font H1-H6+Body); un settings per checkbox, resta un theme_mod separato.
	if ( ! class_exists( 'Starter_Customize_Checkbox_Group_Control' ) ) {
		class Starter_Customize_Checkbox_Group_Control extends WP_Customize_Control {
			public $type = 'starter_checkbox_group';
			public $choices = array();

			/* Disegna un gruppo di caselle da spuntare */
			public function render_content() {
				?>
				<?php if ( $this->label ) : ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php endif; ?>
				<?php if ( $this->description ) : ?>
					<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
				<?php endif; ?>
				<div class="starter-checkbox-group">
					<?php foreach ( $this->choices as $starter_key => $starter_label ) : ?>
						<label>
							<input
								type="checkbox"
								value="1"
								<?php checked( $this->value( $starter_key ), true ); ?>
								<?php $this->link( $starter_key ); ?>
							>
							<?php echo esc_html( $starter_label ); ?>
						</label>
					<?php endforeach; ?>
				</div>
				<?php
			}
		}
	}

	// 3 campi testo (Desktop/Tablet/Mobile) per proprietà CSS responsive, valore libero (px/vw/rem); 3 settings indipendenti.
	if ( ! class_exists( 'Starter_Customize_Responsive_Text_Control' ) ) {
		class Starter_Customize_Responsive_Text_Control extends WP_Customize_Control {
			public $type = 'starter_responsive_text';

			/* Disegna i tre campi Desktop/Tablet/Mobile */
			public function render_content() {
				?>
				<?php if ( $this->label ) : ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php endif; ?>
				<?php if ( $this->description ) : ?>
					<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
				<?php endif; ?>
				<div class="starter-responsive-text-group">
					<?php foreach ( array( 'desktop' => __( 'Desktop', 'slug-theme' ), 'tablet' => __( 'Tablet', 'slug-theme' ), 'mobile' => __( 'Mobile', 'slug-theme' ) ) as $starter_bp_key => $starter_bp_label ) : ?>
						<label class="starter-responsive-text-group__field">
							<span class="starter-responsive-text-group__label"><?php echo esc_html( $starter_bp_label ); ?></span>
							<input
								type="text"
								value="<?php echo esc_attr( $this->value( $starter_bp_key ) ); ?>"
								<?php echo 'desktop' === $starter_bp_key ? '' : 'placeholder="' . esc_attr__( 'come Desktop', 'slug-theme' ) . '"'; ?>
								<?php $this->link( $starter_bp_key ); ?>
							>
						</label>
					<?php endforeach; ?>
				</div>
				<?php
			}
		}
	}

	// Select con optgroup (Font di sistema / Google Fonts), non supportato dal "select" nativo di WP.
	if ( ! class_exists( 'Starter_Customize_Font_Select_Control' ) ) {
		class Starter_Customize_Font_Select_Control extends WP_Customize_Control {
			public $type = 'starter_font_select';
			public $groups = array();

			/* Disegna un menu a tendina con i font, raggruppati */
			public function render_content() {
				?>
				<label>
					<?php if ( $this->label ) : ?>
						<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
					<?php endif; ?>
					<?php if ( $this->description ) : ?>
						<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
					<?php endif; ?>
					<select <?php $this->link(); ?>>
						<?php foreach ( $this->groups as $starter_group_label => $starter_group_choices ) : ?>
							<?php if ( '' === $starter_group_label ) : ?>
								<?php foreach ( $starter_group_choices as $starter_value => $starter_label ) : ?>
									<option value="<?php echo esc_attr( $starter_value ); ?>" <?php selected( $this->value(), $starter_value ); ?>><?php echo esc_html( $starter_label ); ?></option>
								<?php endforeach; ?>
							<?php else : ?>
								<optgroup label="<?php echo esc_attr( $starter_group_label ); ?>">
									<?php foreach ( $starter_group_choices as $starter_value => $starter_label ) : ?>
										<option value="<?php echo esc_attr( $starter_value ); ?>" <?php selected( $this->value(), $starter_value ); ?>><?php echo esc_html( $starter_label ); ?></option>
									<?php endforeach; ?>
								</optgroup>
							<?php endif; ?>
						<?php endforeach; ?>
					</select>
				</label>
				<?php
			}
		}
	}

	// Logo per l'header trasparente, dentro "Identità del sito".
	$wp_customize->add_setting(
		'starter_dark_logo_active',
		array(
			'default'           => false,
			'sanitize_callback' => 'starter_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'starter_dark_logo_active',
		array(
			'type'        => 'checkbox',
			'section'     => 'title_tagline',
			'priority'    => 9, // Subito dopo il controllo nativo "Logo".
			'label'       => __( 'Attiva il logo per l\'header trasparente sulla hero', 'slug-theme' ),
			'description' => __( 'Sostituisce il logo finché l\'header è trasparente sopra la hero.', 'slug-theme' ),
		)
	);

	$wp_customize->add_setting(
		'starter_dark_logo',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'starter_dark_logo',
			array(
				'label'    => __( 'Logo per l\'header trasparente', 'slug-theme' ),
				'section'  => 'title_tagline',
				'priority' => 10,
				'settings' => 'starter_dark_logo',
			)
		)
	);

	// Pannello "Opzioni": impostazioni del tema, non contenuto.
	$wp_customize->add_panel(
		'starter_options',
		array(
			'title'    => __( 'Opzioni', 'slug-theme' ),
			'priority' => 21, // Dopo "Denominazione del sito" (nativa, priorità 20).
		)
	);

	$wp_customize->add_section(
		'starter_options_general',
		array(
			'title'    => __( 'Generale', 'slug-theme' ),
			'panel'    => 'starter_options',
			'priority' => 5, // Prima della sezione Header.
		)
	);

	// --- Titolo "Commenti" ---------------------------------------------
	$wp_customize->add_setting(
		'starter_heading_comments',
		array(
			'sanitize_callback' => '__return_false',
		)
	);

	$wp_customize->add_control(
		new Starter_Customize_Heading_Control(
			$wp_customize,
			'starter_heading_comments',
			array(
				'section'  => 'starter_options_general',
				'label'    => __( 'Commenti', 'slug-theme' ),
				'priority' => 1,
			)
		)
	);

	$wp_customize->add_setting(
		'starter_disable_comments',
		array(
			'default'           => false,
			'sanitize_callback' => 'starter_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'starter_disable_comments',
		array(
			'type'        => 'checkbox',
			'section'     => 'starter_options_general',
			'label'       => __( 'Disattiva i commenti su tutto il sito', 'slug-theme' ),
			'description' => __( 'Toglie commenti e pingback da tutto il sito.', 'slug-theme' ),
			'priority'    => 2,
		)
	);

	// --- Titolo "Layout" -------------------------------------------------
	$wp_customize->add_setting(
		'starter_heading_layout',
		array(
			'sanitize_callback' => '__return_false',
		)
	);

	$wp_customize->add_control(
		new Starter_Customize_Heading_Control(
			$wp_customize,
			'starter_heading_layout',
			array(
				'section'  => 'starter_options_general',
				'label'    => __( 'Layout', 'slug-theme' ),
				'priority' => 3,
			)
		)
	);

	$wp_customize->add_setting(
		'starter_content_width',
		array(
			'default'           => 1280,
			'sanitize_callback' => 'starter_sanitize_content_width',
		)
	);

	$wp_customize->add_control(
		'starter_content_width',
		array(
			'type'        => 'number',
			'section'     => 'starter_options_general',
			'label'       => __( 'Larghezza contenuto (px)', 'slug-theme' ),
			'description' => __( 'Larghezza massima dei contenuti (default 1280). Le sezioni "wide" sono +200px.', 'slug-theme' ),
			'input_attrs' => array(
				'min' => 320,
				'max' => 3000,
				'step' => 10,
			),
			'priority'    => 4,
		)
	);

	// --- Titolo "Animazioni" --------------------------------------------
	$wp_customize->add_setting(
		'starter_heading_animations',
		array(
			'sanitize_callback' => '__return_false',
		)
	);

	$wp_customize->add_control(
		new Starter_Customize_Heading_Control(
			$wp_customize,
			'starter_heading_animations',
			array(
				'section'  => 'starter_options_general',
				'label'    => __( 'Animazioni', 'slug-theme' ),
				'priority' => 10,
			)
		)
	);

	// Caricamento delle pagine (preloader). Predefinito: nessuno.
	$wp_customize->add_setting(
		'starter_preloader',
		array(
			'default'           => 'none',
			'sanitize_callback' => static function ( $value ) {
				return in_array( $value, array( 'none', 'dissolvenza', 'tendina' ), true ) ? $value : 'none';
			},
		)
	);

	$wp_customize->add_control(
		'starter_preloader',
		array(
			'type'     => 'select',
			'section'  => 'starter_options_general',
			'label'    => __( 'Caricamento delle pagine', 'slug-theme' ),
			'choices'  => array(
				'none'        => __( 'Nessun caricamento', 'slug-theme' ),
				'dissolvenza' => __( 'Caricamento con dissolvenza', 'slug-theme' ),
				'tendina'     => __( 'Tenda dal basso', 'slug-theme' ),
			),
			'priority' => 11,
		)
	);

	$starter_preloader_on = static function ( $control ) {
		$setting = $control->manager->get_setting( 'starter_preloader' );

		return $setting && in_array( $setting->value(), array( 'dissolvenza', 'tendina' ), true );
	};

	$wp_customize->add_setting(
		'starter_preloader_content',
		array(
			'default'           => 'text',
			'sanitize_callback' => static function ( $value ) {
				return in_array( $value, array( 'text', 'percent', 'name', 'logo' ), true ) ? $value : 'text';
			},
		)
	);

	$wp_customize->add_control(
		'starter_preloader_content',
		array(
			'type'            => 'select',
			'section'         => 'starter_options_general',
			'label'           => __( 'Cosa mostrare', 'slug-theme' ),
			'choices'         => array(
				'text'    => __( 'Testo personalizzato', 'slug-theme' ),
				'percent' => __( 'Percentuale da 0 a 100%', 'slug-theme' ),
				'name'    => __( 'Nome del sito', 'slug-theme' ),
				'logo'    => __( 'Logo del sito', 'slug-theme' ),
			),
			'priority'        => 11.1,
			'active_callback' => $starter_preloader_on,
		)
	);

	$wp_customize->add_setting( 'starter_preloader_text', array( 'default' => __( 'Caricamento…', 'slug-theme' ), 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control(
		'starter_preloader_text',
		array(
			'type'            => 'text',
			'section'         => 'starter_options_general',
			'label'           => __( 'Testo', 'slug-theme' ),
			'priority'        => 11.2,
			'active_callback' => static function ( $control ) {
				$mode    = $control->manager->get_setting( 'starter_preloader' );
				$content = $control->manager->get_setting( 'starter_preloader_content' );

				return $mode && $content && in_array( $mode->value(), array( 'dissolvenza', 'tendina' ), true ) && 'text' === $content->value();
			},
		)
	);

	$wp_customize->add_setting( 'starter_preloader_logo_tint', array( 'default' => true, 'sanitize_callback' => 'starter_sanitize_checkbox' ) );
	$wp_customize->add_control(
		'starter_preloader_logo_tint',
		array(
			'type'            => 'checkbox',
			'section'         => 'starter_options_general',
			'label'           => __( 'Logo nel colore del testo', 'slug-theme' ),
			'priority'        => 11.5,
			'active_callback' => static function ( $control ) {
				$mode    = $control->manager->get_setting( 'starter_preloader' );
				$content = $control->manager->get_setting( 'starter_preloader_content' );

				return $mode && $content && in_array( $mode->value(), array( 'dissolvenza', 'tendina' ), true ) && 'logo' === $content->value();
			},
		)
	);

	$starter_pl_colors = function_exists( 'starter_named_color_choices' ) ? starter_named_color_choices() : array( '' => '' );

	foreach ( array( 'bg' => array( __( 'Sfondo', 'slug-theme' ), 11.3 ), 'fg' => array( __( 'Testo', 'slug-theme' ), 11.4 ) ) as $starter_pl_key => $starter_pl_data ) {
		$wp_customize->add_setting(
			"starter_preloader_{$starter_pl_key}",
			array(
				'default'           => '',
				'sanitize_callback' => static function ( $value ) use ( $starter_pl_colors ) {
					return array_key_exists( $value, $starter_pl_colors ) ? $value : '';
				},
			)
		);

		$wp_customize->add_control(
			"starter_preloader_{$starter_pl_key}",
			array(
				'type'            => 'select',
				'section'         => 'starter_options_general',
				'label'           => $starter_pl_data[0],
				'choices'         => $starter_pl_colors,
				'priority'        => $starter_pl_data[1],
				'active_callback' => $starter_preloader_on,
			)
		);
	}

	$wp_customize->add_setting(
		'starter_enable_smooth_scroll',
		array(
			'default'           => true,
			'sanitize_callback' => 'starter_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'starter_enable_smooth_scroll',
		array(
			'type'        => 'checkbox',
			'section'     => 'starter_options_general',
			'label'       => __( 'Scroll fluido', 'slug-theme' ),
			'description' => __( 'Scroll più morbido (Lenis). Si spegne con "riduci animazioni".', 'slug-theme' ),
			'priority'    => 12,
		)
	);

	// --- Titolo "Social" --------------------------------------------------
	// Qui una sola volta: condiviso da header e footer, ognuno con il proprio checkbox "Mostra i social".
	// --- Titolo "SVG" -----------------------------------------------
	$wp_customize->add_setting(
		'starter_heading_svg',
		array(
			'sanitize_callback' => '__return_false',
		)
	);

	$wp_customize->add_control(
		new Starter_Customize_Heading_Control(
			$wp_customize,
			'starter_heading_svg',
			array(
				'section'  => 'starter_options_general',
				'label'    => __( 'SVG', 'slug-theme' ),
				'priority' => 15, // Tra Animazioni (10-12) e Social (20).
			)
		)
	);

	$wp_customize->add_setting(
		'starter_svg_inline_mode',
		array(
			'default'           => false,
			'sanitize_callback' => 'starter_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'starter_svg_inline_mode',
		array(
			'type'        => 'checkbox',
			'section'     => 'starter_options_general',
			'label'       => __( 'Inserisci gli SVG come vettori', 'slug-theme' ),
			// WP non fa escaping di "description": i tag letterali diventano HTML vero, quindi uso &lt;img&gt;/&lt;svg&gt; (bug reale segnalato).
			'description' => __( 'Spento: un .svg è un\'immagine normale. Acceso: entra nell\'HTML ed è colorabile via CSS.', 'slug-theme' ),
			'priority'    => 16,
		)
	);

	// (URL e icone social sono le stesse ovunque compaiano)
	$wp_customize->add_setting(
		'starter_heading_social',
		array(
			'sanitize_callback' => '__return_false',
		)
	);

	$wp_customize->add_control(
		new Starter_Customize_Heading_Control(
			$wp_customize,
			'starter_heading_social',
			array(
				'section'  => 'starter_options_general',
				'label'    => __( 'Social', 'slug-theme' ),
				'priority' => 20,
			)
		)
	);

	// Social predefiniti: compila l'URL per farli comparire, vuoto = nascosto.
	$wp_customize->add_setting(
		'starter_pagination_align',
		array(
			'default'           => '',
			'sanitize_callback' => static function ( $value ) {
				return in_array( $value, array( 'left', 'center', 'right' ), true ) ? $value : '';
			},
		)
	);

	// Titoletto proprio: dopo SVG (15-16), prima di Social (20).
	$wp_customize->add_setting( 'starter_heading_pagination', array( 'sanitize_callback' => '__return_false' ) );

	$wp_customize->add_control(
		new Starter_Customize_Heading_Control(
			$wp_customize,
			'starter_heading_pagination',
			array(
				'section'  => 'starter_options_general',
				'label'    => __( 'Paginazione', 'slug-theme' ),
				'priority' => 17,
			)
		)
	);

	$wp_customize->add_control(
		'starter_pagination_align',
		array(
			'type'     => 'select',
			'section'  => 'starter_options_general',
			'label'    => __( 'Allineamento', 'slug-theme' ),
			'choices'  => array(
				''       => __( 'Predefinito', 'slug-theme' ),
				'left'   => __( 'A sinistra', 'slug-theme' ),
				'center' => __( 'Al centro', 'slug-theme' ),
				'right'  => __( 'A destra', 'slug-theme' ),
			),
			'priority' => 18,
		)
	);

	$starter_social_priority = 21;

	foreach ( starter_social_presets() as $key => $preset ) {
		$wp_customize->add_setting(
			"starter_social_{$key}",
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$wp_customize->add_control(
			"starter_social_{$key}",
			array(
				'type'        => 'url',
				'section'     => 'starter_options_general',
				/* translators: %s: nome del social, es. "Instagram". */
				'label'       => sprintf( __( 'Social: %s', 'slug-theme' ), $preset['label'] ),
				'description' => __( 'Lascia vuoto per non mostrare questa icona.', 'slug-theme' ),
				'priority'    => $starter_social_priority++,
			)
		);
	}

	// 5 slot icone social personalizzate (solo il primo visibile finché non si preme "+ Aggiungi"); SVG letto come codice, non <img>, per ereditare i colori.
	for ( $i = 1; $i <= 5; $i++ ) {
		$wp_customize->add_setting(
			"starter_social_custom_{$i}_svg",
			array(
				'default'           => 0,
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Media_Control(
				$wp_customize,
				"starter_social_custom_{$i}_svg",
				array(
					/* translators: %d: numero dello slot icona personalizzata. */
					'label'       => sprintf( __( 'Icona personalizzata %d — file SVG', 'slug-theme' ), $i ),
					'description' => __( 'Letto come codice: si colora via CSS.', 'slug-theme' ),
					'section'     => 'starter_options_general',
					'mime_type'   => 'image/svg+xml',
					'priority'    => 100 + ( $i * 10 ),
				)
			)
		);

		$wp_customize->add_setting(
			"starter_social_custom_{$i}_url",
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$wp_customize->add_control(
			"starter_social_custom_{$i}_url",
			array(
				'type'     => 'url',
				'section'  => 'starter_options_general',
				/* translators: %d: numero dello slot icona personalizzata. */
				'label'    => sprintf( __( 'Icona personalizzata %d — link', 'slug-theme' ), $i ),
				'priority' => 100 + ( $i * 10 ) + 1,
			)
		);
	}

	$wp_customize->add_setting(
		'starter_social_custom_add_button',
		array(
			'sanitize_callback' => '__return_false',
		)
	);

	$wp_customize->add_control(
		new Starter_Add_Icon_Button_Control(
			$wp_customize,
			'starter_social_custom_add_button',
			array(
				'section'  => 'starter_options_general',
				'priority' => 200, // Dopo tutti e 5 gli slot.
			)
		)
	);

	$wp_customize->add_section(
		'starter_options_header',
		array(
			'title'    => __( 'Header', 'slug-theme' ),
			'panel'    => 'starter_options',
			'priority' => 10, // Prima di Footer (15).
		)
	);

	// Versione dell'header.
	$wp_customize->add_setting(
		'starter_header_style',
		array(
			'default'           => 'extended',
			'sanitize_callback' => 'starter_sanitize_header_style',
		)
	);

	$wp_customize->add_control(
		'starter_header_style',
		array(
			'type'        => 'select',
			'section'     => 'starter_options_header',
			'label'       => __( 'Versione header', 'slug-theme' ),
			'description' => __( 'Standard: logo a sinistra, menu e resto a destra. Centrato: menu al centro. Ridotto: solo il pulsante "Menu". Pillole: senza sfondo, sempre visibile, con menu e pulsante in due pillole a destra.', 'slug-theme' ),
			'choices'     => array(
				'standard' => __( 'Standard', 'slug-theme' ),
				'extended' => __( 'Centrato', 'slug-theme' ),
				'minimal'  => __( 'Ridotto', 'slug-theme' ),
				'pills'    => __( 'Pillole', 'slug-theme' ),
			),
		)
	);

	// Header flottante: aspetto "staccato" sovrapposto a qualunque versione, senza cambiare la disposizione interna.
	$wp_customize->add_setting(
		'starter_header_floating',
		array(
			'default'           => false,
			'sanitize_callback' => 'starter_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'starter_header_floating',
		array(
			'type'        => 'checkbox',
			'section'     => 'starter_options_header',
			'label'       => __( 'Header Flottante', 'slug-theme' ),
			'description' => __( 'Card staccata dai bordi, con angoli arrotondati. Scrollando si attacca in alto.', 'slug-theme' ),
		)
	);

	// CTA header: testo vuoto = non stampata (come i social).
	$wp_customize->add_setting(
		'starter_cta_text',
		array(
			'default'           => __( 'Contattami', 'slug-theme' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'starter_cta_text',
		array(
			'type'        => 'text',
			'section'     => 'starter_options_header',
			'label'       => __( 'Testo pulsante CTA', 'slug-theme' ),
			'description' => __( 'Lascia vuoto per non mostrare la CTA nell\'header.', 'slug-theme' ),
		)
	);

	$wp_customize->add_setting(
		'starter_cta_url',
		array(
			'default'           => '#',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'starter_cta_url',
		array(
			'type'    => 'url',
			'section' => 'starter_options_header',
			'label'   => __( 'Link pulsante CTA', 'slug-theme' ),
		)
	);

	$wp_customize->add_setting(
		'starter_cta_new_tab',
		array(
			'default'           => false,
			'sanitize_callback' => 'starter_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'starter_cta_new_tab',
		array(
			'type'        => 'checkbox',
			'section'     => 'starter_options_header',
			'label'       => __( 'Apri la CTA in una nuova scheda', 'slug-theme' ),
			'description' => __( 'Le icone social si aprono sempre in una nuova scheda; per la CTA puoi scegliere.', 'slug-theme' ),
		)
	);

	// Mostra/nascondi social nell'header; dati configurati in Generale -> Social, condivisi col footer.
	$wp_customize->add_setting(
		'starter_social_show_header',
		array(
			'default'           => true,
			'sanitize_callback' => 'starter_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'starter_social_show_header',
		array(
			'type'        => 'checkbox',
			'section'     => 'starter_options_header',
			'label'       => __( 'Mostra i social', 'slug-theme' ),
			'description' => __( 'Social in Generale → Social. Qui: mostrarli o no.', 'slug-theme' ),
			'priority'    => 1,
		)
	);

	// --- Sezione "Footer" ------------------------------------------------
	$wp_customize->add_section(
		'starter_options_footer',
		array(
			'title'    => __( 'Footer', 'slug-theme' ),
			'panel'    => 'starter_options',
			'priority' => 15, // Dopo Header.
		)
	);

	// Versione footer: predisposto per più stili futuri (per ora uno solo).
	$wp_customize->add_setting(
		'starter_footer_style',
		array(
			'default'           => 'style-1',
			'sanitize_callback' => 'starter_sanitize_footer_style',
		)
	);

	$wp_customize->add_control(
		'starter_footer_style',
		array(
			'type'        => 'select',
			'section'     => 'starter_options_footer',
			'label'       => __( 'Versione footer', 'slug-theme' ),
			'description' => __( 'Campi comuni a tutti gli stili; "Spazio libero" e Stile 4 in fondo.', 'slug-theme' ),
			'choices'     => starter_footer_style_choices(),
			'priority'    => 1,
		)
	);

	// Come nell'Header: stesso elenco (Generale -> Social), checkbox indipendente.
	$wp_customize->add_setting(
		'starter_social_show_footer',
		array(
			'default'           => true,
			'sanitize_callback' => 'starter_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'starter_social_show_footer',
		array(
			'type'        => 'checkbox',
			'section'     => 'starter_options_footer',
			'label'       => __( 'Mostra i social', 'slug-theme' ),
			'description' => __( 'Social in Generale → Social. Qui: mostrarli o no.', 'slug-theme' ),
			'priority'    => 2,
		)
	);

	// --- Titolo "Colonna testo" ------------------------------------------
	$wp_customize->add_setting(
		'starter_heading_footer_intro',
		array(
			'sanitize_callback' => '__return_false',
		)
	);

	$wp_customize->add_control(
		new Starter_Customize_Heading_Control(
			$wp_customize,
			'starter_heading_footer_intro',
			array(
				'section'  => 'starter_options_footer',
				'label'    => __( 'Colonna testo', 'slug-theme' ),
				'priority' => 5,
			)
		)
	);

	$wp_customize->add_setting(
		'starter_footer_intro_title',
		array(
			'default'           => get_bloginfo( 'description' ) ? get_bloginfo( 'description' ) : __( 'Tema base di lavoro', 'slug-theme' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'starter_footer_intro_title',
		array(
			'type'     => 'text',
			'section'  => 'starter_options_footer',
			'label'    => __( 'Titolo', 'slug-theme' ),
			'priority' => 6,
		)
	);

	$wp_customize->add_setting(
		'starter_footer_description',
		array(
			'default'           => __( 'Un tema WordPress classico e leggero, pensato come base pronta da personalizzare: header e footer configurabili dal Customizer, Gutenberg-first fin dall\'inizio.', 'slug-theme' ),
			'sanitize_callback' => 'starter_sanitize_footer_description',
		)
	);

	$wp_customize->add_control(
		'starter_footer_description',
		array(
			'type'        => 'textarea',
			'section'     => 'starter_options_footer',
			'label'       => __( 'Testo', 'slug-theme' ),
			'description' => __( 'Riga di testo sotto al titolo, nella prima colonna del footer. Massimo 180 caratteri.', 'slug-theme' ),
			'priority'    => 7,
		)
	);

	// Stile 5: frase grande e sedi.
	$wp_customize->add_setting(
		'starter_footer_headline',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);

	$wp_customize->add_control(
		'starter_footer_headline',
		array(
			'type'     => 'textarea',
			'section'  => 'starter_options_footer',
			'label'    => __( 'Frase grande (Stile 5)', 'slug-theme' ),
			'priority' => 7,
		)
	);

	$wp_customize->add_setting(
		'starter_footer_locations',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);

	$wp_customize->add_control(
		'starter_footer_locations',
		array(
			'type'        => 'textarea',
			'section'     => 'starter_options_footer',
			'label'       => __( 'Sedi (Stile 5)', 'slug-theme' ),
			'description' => __( 'Una sede per blocco, separate da una riga vuota: la prima riga è il titolo.', 'slug-theme' ),
			'priority'    => 7,
		)
	);

	// --- Titolo "Colonna contatti" ---------------------------------------
	$wp_customize->add_setting(
		'starter_heading_footer_contacts',
		array(
			'sanitize_callback' => '__return_false',
		)
	);

	$wp_customize->add_control(
		new Starter_Customize_Heading_Control(
			$wp_customize,
			'starter_heading_footer_contacts',
			array(
				'section'  => 'starter_options_footer',
				'label'    => __( 'Colonna contatti', 'slug-theme' ),
				'priority' => 10,
			)
		)
	);

	$wp_customize->add_setting(
		'starter_footer_contacts_title',
		array(
			'default'           => __( 'Contatti', 'slug-theme' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'starter_footer_contacts_title',
		array(
			'type'     => 'text',
			'section'  => 'starter_options_footer',
			'label'    => __( 'Titolo', 'slug-theme' ),
			'priority' => 11,
		)
	);

	// Migrazione: pre-compila il nuovo campo unico dai vecchi Indirizzo/Telefono/Email, per non perdere dati.
	$starter_legacy_contacts = array_filter(
		array(
			get_theme_mod( 'starter_footer_address', '' ),
			get_theme_mod( 'starter_footer_phone', '' ),
			get_theme_mod( 'starter_footer_email', '' ),
		)
	);

	$wp_customize->add_setting(
		'starter_footer_contacts',
		array(
			'default'           => implode( "\n", $starter_legacy_contacts ),
			'sanitize_callback' => 'starter_sanitize_footer_contacts',
		)
	);

	$wp_customize->add_control(
		'starter_footer_contacts',
		array(
			'type'        => 'textarea',
			'section'     => 'starter_options_footer',
			'label'       => __( 'Voci', 'slug-theme' ),
			'description' => __( 'Una voce per riga. Telefono ed email diventano link. HTML ammesso.', 'slug-theme' ),
			'priority'    => 12,
		)
	);

	// --- Titolo "Colonna menu" -------------------------------------------
	$wp_customize->add_setting(
		'starter_heading_footer_menu',
		array(
			'sanitize_callback' => '__return_false',
		)
	);

	$wp_customize->add_control(
		new Starter_Customize_Heading_Control(
			$wp_customize,
			'starter_heading_footer_menu',
			array(
				'section'  => 'starter_options_footer',
				'label'    => __( 'Colonna menu', 'slug-theme' ),
				'priority' => 15,
			)
		)
	);

	$wp_customize->add_setting(
		'starter_footer_menu_title',
		array(
			'default'           => get_bloginfo( 'name' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'starter_footer_menu_title',
		array(
			'type'     => 'text',
			'section'  => 'starter_options_footer',
			'label'    => __( 'Titolo', 'slug-theme' ),
			'priority' => 16,
		)
	);


	// --- Titolo "Riga finale" ----------------------------------------------
	$wp_customize->add_setting(
		'starter_heading_footer_bottom',
		array(
			'sanitize_callback' => '__return_false',
		)
	);

	$wp_customize->add_control(
		new Starter_Customize_Heading_Control(
			$wp_customize,
			'starter_heading_footer_bottom',
			array(
				'section'  => 'starter_options_footer',
				'label'    => __( 'Riga finale', 'slug-theme' ),
				'priority' => 25,
			)
		)
	);

	$wp_customize->add_setting(
		'starter_footer_copyright',
		array(
			'default'           => __( '© {anno} {sito}. Tutti i diritti riservati.', 'slug-theme' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'starter_footer_copyright',
		array(
			'type'        => 'text',
			'section'     => 'starter_options_footer',
			'label'       => __( 'Testo copyright', 'slug-theme' ),
			/* translators: %1$s and %2$s are the literal placeholder tokens, not variables to translate. */
			'description' => sprintf( __( 'Usa %1$s per l\'anno corrente e %2$s per il nome del sito.', 'slug-theme' ), '<code>{anno}</code>', '<code>{sito}</code>' ),
			'priority'    => 26,
		)
	);

	// 4 link (Privacy/Cookie/Termini/Credits): vuoto di default = nascosto; non dipendono da Impostazioni WP; classi CSS per servizi esterni (es. Iubenda).
	$wp_customize->add_setting(
		'starter_heading_footer_privacy',
		array(
			'sanitize_callback' => '__return_false',
		)
	);

	$wp_customize->add_control(
		new Starter_Customize_Heading_Control(
			$wp_customize,
			'starter_heading_footer_privacy',
			array(
				'section'  => 'starter_options_footer',
				'label'    => __( 'Link "Privacy Policy"', 'slug-theme' ),
				'priority' => 27,
			)
		)
	);

	$wp_customize->add_setting(
		'starter_footer_privacy_label',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'starter_footer_privacy_label',
		array(
			'type'        => 'text',
			'section'     => 'starter_options_footer',
			'label'       => __( 'Testo', 'slug-theme' ),
			'description' => __( 'Vuoto di default: il link compare nel footer solo dopo aver scritto qui un testo.', 'slug-theme' ),
			'priority'    => 28,
		)
	);

	$wp_customize->add_setting(
		'starter_footer_privacy_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'starter_footer_privacy_url',
		array(
			'type'     => 'url',
			'section'  => 'starter_options_footer',
			'label'    => __( 'Indirizzo', 'slug-theme' ),
			'priority' => 29,
		)
	);

	$wp_customize->add_setting(
		'starter_footer_privacy_class',
		array(
			'default'           => '',
			'sanitize_callback' => 'starter_sanitize_css_classes',
		)
	);

	$wp_customize->add_control(
		'starter_footer_privacy_class',
		array(
			'type'        => 'text',
			'section'     => 'starter_options_footer',
			'label'       => __( 'Classi CSS (facoltative)', 'slug-theme' ),
			'description' => __( 'Classi aggiunte al link (es. Iubenda), separate da spazio.', 'slug-theme' ),
			'priority'    => 30,
		)
	);

	$wp_customize->add_setting(
		'starter_heading_footer_cookie',
		array(
			'sanitize_callback' => '__return_false',
		)
	);

	$wp_customize->add_control(
		new Starter_Customize_Heading_Control(
			$wp_customize,
			'starter_heading_footer_cookie',
			array(
				'section'  => 'starter_options_footer',
				'label'    => __( 'Link "Cookie Policy"', 'slug-theme' ),
				'priority' => 31,
			)
		)
	);

	$wp_customize->add_setting(
		'starter_footer_cookie_label',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'starter_footer_cookie_label',
		array(
			'type'        => 'text',
			'section'     => 'starter_options_footer',
			'label'       => __( 'Testo', 'slug-theme' ),
			'description' => __( 'Vuoto di default: il link compare nel footer solo dopo aver scritto qui un testo.', 'slug-theme' ),
			'priority'    => 32,
		)
	);

	$wp_customize->add_setting(
		'starter_footer_cookie_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'starter_footer_cookie_url',
		array(
			'type'     => 'url',
			'section'  => 'starter_options_footer',
			'label'    => __( 'Indirizzo', 'slug-theme' ),
			'priority' => 33,
		)
	);

	$wp_customize->add_setting(
		'starter_footer_cookie_class',
		array(
			'default'           => '',
			'sanitize_callback' => 'starter_sanitize_css_classes',
		)
	);

	$wp_customize->add_control(
		'starter_footer_cookie_class',
		array(
			'type'        => 'text',
			'section'     => 'starter_options_footer',
			'label'       => __( 'Classi CSS (facoltative)', 'slug-theme' ),
			'description' => __( 'Classi aggiunte al link (es. Iubenda), separate da spazio.', 'slug-theme' ),
			'priority'    => 34,
		)
	);

	$wp_customize->add_setting(
		'starter_heading_footer_terms',
		array(
			'sanitize_callback' => '__return_false',
		)
	);

	$wp_customize->add_control(
		new Starter_Customize_Heading_Control(
			$wp_customize,
			'starter_heading_footer_terms',
			array(
				'section'  => 'starter_options_footer',
				'label'    => __( 'Link "Termini di servizio"', 'slug-theme' ),
				'priority' => 35,
			)
		)
	);

	$wp_customize->add_setting(
		'starter_footer_terms_label',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'starter_footer_terms_label',
		array(
			'type'        => 'text',
			'section'     => 'starter_options_footer',
			'label'       => __( 'Testo', 'slug-theme' ),
			'description' => __( 'Vuoto di default: il link compare nel footer solo dopo aver scritto qui un testo.', 'slug-theme' ),
			'priority'    => 36,
		)
	);

	$wp_customize->add_setting(
		'starter_footer_terms_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'starter_footer_terms_url',
		array(
			'type'     => 'url',
			'section'  => 'starter_options_footer',
			'label'    => __( 'Indirizzo', 'slug-theme' ),
			'priority' => 37,
		)
	);

	$wp_customize->add_setting(
		'starter_footer_terms_class',
		array(
			'default'           => '',
			'sanitize_callback' => 'starter_sanitize_css_classes',
		)
	);

	$wp_customize->add_control(
		'starter_footer_terms_class',
		array(
			'type'        => 'text',
			'section'     => 'starter_options_footer',
			'label'       => __( 'Classi CSS (facoltative)', 'slug-theme' ),
			'description' => __( 'Classi aggiunte al link (es. Iubenda), separate da spazio.', 'slug-theme' ),
			'priority'    => 38,
		)
	);

	$wp_customize->add_setting(
		'starter_heading_footer_credits',
		array(
			'sanitize_callback' => '__return_false',
		)
	);

	$wp_customize->add_control(
		new Starter_Customize_Heading_Control(
			$wp_customize,
			'starter_heading_footer_credits',
			array(
				'section'  => 'starter_options_footer',
				'label'    => __( 'Link "Credits"', 'slug-theme' ),
				'priority' => 39,
			)
		)
	);

	$wp_customize->add_setting(
		'starter_footer_credits_label',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'starter_footer_credits_label',
		array(
			'type'        => 'text',
			'section'     => 'starter_options_footer',
			'label'       => __( 'Testo', 'slug-theme' ),
			'description' => __( 'Vuoto di default: il link compare nel footer solo dopo aver scritto qui un testo.', 'slug-theme' ),
			'priority'    => 40,
		)
	);

	$wp_customize->add_setting(
		'starter_footer_credits_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'starter_footer_credits_url',
		array(
			'type'     => 'url',
			'section'  => 'starter_options_footer',
			'label'    => __( 'Indirizzo', 'slug-theme' ),
			'priority' => 41,
		)
	);

	// --- Sezione "Tipografia": 4 slot font, ognuno assegnabile a h1-h6/Body; in caso di sovrapposizione vince l'ultimo slot.
	$wp_customize->add_section(
		'starter_options_typography',
		array(
			'title'    => __( 'Tipografia', 'slug-theme' ),
			'panel'    => 'starter_options',
			'priority' => 7, // Tra Generale (5) e Header (10).
		)
	);

	$starter_typography_checkbox_labels = array(
		'h1'   => __( 'H1', 'slug-theme' ),
		'h2'   => __( 'H2', 'slug-theme' ),
		'h3'   => __( 'H3', 'slug-theme' ),
		'h4'   => __( 'H4', 'slug-theme' ),
		'h5'   => __( 'H5', 'slug-theme' ),
		'h6'   => __( 'H6', 'slug-theme' ),
		'body' => __( 'Body (tutto il sito)', 'slug-theme' ),
	);

	$starter_typography_priority = 1;

	// --- Font 1 e Font 2: da Google Fonts o dai font di sistema --------
	foreach ( array( 1, 2 ) as $starter_font_n ) {
		$wp_customize->add_setting(
			"starter_heading_font{$starter_font_n}",
			array(
				'sanitize_callback' => '__return_false',
			)
		);

		$wp_customize->add_control(
			new Starter_Customize_Heading_Control(
				$wp_customize,
				"starter_heading_font{$starter_font_n}",
				array(
					'section'  => 'starter_options_typography',
					/* translators: %d: numero del font (1 o 2). */
					'label'    => sprintf( __( 'Font %d', 'slug-theme' ), $starter_font_n ),
					'priority' => $starter_typography_priority++,
				)
			)
		);

		$wp_customize->add_setting(
			"starter_font{$starter_font_n}_family",
			array(
				'default'           => '',
				'sanitize_callback' => 'starter_sanitize_typography_font_choice',
			)
		);

		$wp_customize->add_control(
			new Starter_Customize_Font_Select_Control(
				$wp_customize,
				"starter_font{$starter_font_n}_family",
				array(
					'section'     => 'starter_options_typography',
					'label'       => __( 'Font', 'slug-theme' ),
					'description' => __( 'Finché resta su "— Seleziona Font —" questo font non viene caricato sul sito.', 'slug-theme' ),
					'groups'      => array(
						'' => array( '' => __( '— Seleziona Font —', 'slug-theme' ) ),
						__( 'Font di sistema', 'slug-theme' ) => starter_system_fonts_choices(),
						__( 'Google Fonts', 'slug-theme' )    => starter_google_fonts_choices(),
					),
					'priority'    => $starter_typography_priority++,
				)
			)
		);

		foreach ( $starter_typography_checkbox_labels as $starter_key => $starter_label ) {
			$wp_customize->add_setting(
				"starter_font{$starter_font_n}_{$starter_key}",
				array(
					'default'           => false,
					'sanitize_callback' => 'starter_sanitize_checkbox',
				)
			);
		}

		$wp_customize->add_control(
			new Starter_Customize_Checkbox_Group_Control(
				$wp_customize,
				"starter_font{$starter_font_n}_elements",
				array(
					'section'  => 'starter_options_typography',
					'label'    => __( 'Applica a', 'slug-theme' ),
					'settings' => array_combine(
						array_keys( $starter_typography_checkbox_labels ),
						array_map(
							function ( $starter_key ) use ( $starter_font_n ) {
								return "starter_font{$starter_font_n}_{$starter_key}";
							},
							array_keys( $starter_typography_checkbox_labels )
						)
					),
					'choices'  => $starter_typography_checkbox_labels,
					'priority' => $starter_typography_priority++,
				)
			)
		);
	}

	// --- Font 3 e Font 4: caricati dall'utente, fino a 4 varianti peso/stile ciascuno, rivelati on-demand via bottoni.
	$starter_font_upload_formats = array(
		'ttf'   => array(
			'label'     => __( 'File TTF', 'slug-theme' ),
			'mime_type' => 'font/ttf',
		),
		'woff'  => array(
			'label'     => __( 'File WOFF', 'slug-theme' ),
			'mime_type' => 'font/woff',
		),
		'woff2' => array(
			'label'     => __( 'File WOFF2', 'slug-theme' ),
			'mime_type' => 'font/woff2',
		),
		'eot'   => array(
			'label'     => __( 'File EOT', 'slug-theme' ),
			'mime_type' => 'application/vnd.ms-fontobject',
		),
		'svg'   => array(
			'label'     => __( 'File SVG', 'slug-theme' ),
			'mime_type' => 'image/svg+xml',
		),
	);

	foreach ( array( 3, 4 ) as $starter_font_n ) {
		$wp_customize->add_setting(
			"starter_heading_font{$starter_font_n}",
			array(
				'sanitize_callback' => '__return_false',
			)
		);

		$wp_customize->add_control(
			new Starter_Customize_Heading_Control(
				$wp_customize,
				"starter_heading_font{$starter_font_n}",
				array(
					'section'  => 'starter_options_typography',
					/* translators: %d: numero del font (3 o 4). */
					'label'    => sprintf( __( 'Font %d — caricato da te', 'slug-theme' ), $starter_font_n ),
					'priority' => $starter_typography_priority++,
				)
			)
		);

		// Bottone dedicato a questo font: rivela nome/varianti solo per questo slot.
		$wp_customize->add_setting(
			"starter_add_custom_font{$starter_font_n}",
			array(
				'sanitize_callback' => '__return_false',
			)
		);

		$wp_customize->add_control(
			new Starter_Customize_Button_Control(
				$wp_customize,
				"starter_add_custom_font{$starter_font_n}",
				array(
					'section'      => 'starter_options_typography',
					/* translators: %d: numero del font (3 o 4). */
					'button_text'  => sprintf( __( '+ Aggiungi Font %d', 'slug-theme' ), $starter_font_n ),
					'button_class' => "starter-add-custom-font-button-{$starter_font_n}",
					'priority'     => $starter_typography_priority++,
				)
			)
		);

		$wp_customize->add_setting(
			"starter_font{$starter_font_n}_name",
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			"starter_font{$starter_font_n}_name",
			array(
				'type'        => 'text',
				'section'     => 'starter_options_typography',
				'label'       => __( 'Nome del font', 'slug-theme' ),
				'description' => __( 'Nome a tua scelta. Vuoto = font non caricato.', 'slug-theme' ),
				'priority'    => $starter_typography_priority++,
			)
		);

		foreach ( array( 1, 2, 3, 4 ) as $starter_variant_n ) {
			$wp_customize->add_setting(
				"starter_font{$starter_font_n}_v{$starter_variant_n}_weight",
				array(
					'default'           => '400',
					'sanitize_callback' => 'starter_sanitize_font_weight',
				)
			);

			$wp_customize->add_control(
				"starter_font{$starter_font_n}_v{$starter_variant_n}_weight",
				array(
					'type'        => 'select',
					'section'     => 'starter_options_typography',
					/* translators: %d: numero della variante (1-4). */
					'label'       => sprintf( __( 'Variante %d — peso', 'slug-theme' ), $starter_variant_n ),
					'choices'     => starter_font_weight_choices(),
					'priority'    => $starter_typography_priority++,
				)
			);

			$wp_customize->add_setting(
				"starter_font{$starter_font_n}_v{$starter_variant_n}_style",
				array(
					'default'           => 'normal',
					'sanitize_callback' => 'starter_sanitize_font_style',
				)
			);

			$wp_customize->add_control(
				"starter_font{$starter_font_n}_v{$starter_variant_n}_style",
				array(
					'type'        => 'select',
					'section'     => 'starter_options_typography',
					/* translators: %d: numero della variante (1-4). */
					'label'       => sprintf( __( 'Variante %d — stile', 'slug-theme' ), $starter_variant_n ),
					'choices'     => starter_font_style_choices(),
					'priority'    => $starter_typography_priority++,
				)
			);

			foreach ( $starter_font_upload_formats as $starter_format => $starter_format_data ) {
				$wp_customize->add_setting(
					"starter_font{$starter_font_n}_v{$starter_variant_n}_{$starter_format}",
					array(
						'default'           => 0,
						'sanitize_callback' => 'absint',
					)
				);

				$wp_customize->add_control(
					new WP_Customize_Media_Control(
						$wp_customize,
						"starter_font{$starter_font_n}_v{$starter_variant_n}_{$starter_format}",
						array(
							'label'     => $starter_format_data['label'],
							'section'   => 'starter_options_typography',
							'mime_type' => $starter_format_data['mime_type'],
							'priority'  => $starter_typography_priority++,
						)
					)
				);
			}

			// Bottone per rivelare la variante successiva (le altre partono nascoste).
			if ( 1 === $starter_variant_n ) {
				$wp_customize->add_setting(
					"starter_font{$starter_font_n}_add_variant",
					array(
						'sanitize_callback' => '__return_false',
					)
				);

				$wp_customize->add_control(
					new Starter_Customize_Button_Control(
						$wp_customize,
						"starter_font{$starter_font_n}_add_variant",
						array(
							'section'      => 'starter_options_typography',
							'button_text'  => __( '+ Aggiungi variante', 'slug-theme' ),
							'button_class' => "starter-add-font-variant-button starter-add-font-variant-button-{$starter_font_n}",
							'priority'     => $starter_typography_priority++,
						)
					)
				);
			} else {
				// Bottone per svuotare/nascondere questa variante (non la prima, base del font).
				$wp_customize->add_setting(
					"starter_font{$starter_font_n}_v{$starter_variant_n}_remove",
					array(
						'sanitize_callback' => '__return_false',
					)
				);

				$wp_customize->add_control(
					new Starter_Customize_Button_Control(
						$wp_customize,
						"starter_font{$starter_font_n}_v{$starter_variant_n}_remove",
						array(
							'section'      => 'starter_options_typography',
							'button_text'  => __( 'Rimuovi questa variante', 'slug-theme' ),
							'button_class' => 'starter-remove-button',
							'priority'     => $starter_typography_priority++,
						)
					)
				);
			}
		}

		foreach ( $starter_typography_checkbox_labels as $starter_key => $starter_label ) {
			$wp_customize->add_setting(
				"starter_font{$starter_font_n}_{$starter_key}",
				array(
					'default'           => false,
					'sanitize_callback' => 'starter_sanitize_checkbox',
				)
			);
		}

		$wp_customize->add_control(
			new Starter_Customize_Checkbox_Group_Control(
				$wp_customize,
				"starter_font{$starter_font_n}_elements",
				array(
					'section'  => 'starter_options_typography',
					'label'    => __( 'Applica a', 'slug-theme' ),
					'settings' => array_combine(
						array_keys( $starter_typography_checkbox_labels ),
						array_map(
							function ( $starter_key ) use ( $starter_font_n ) {
								return "starter_font{$starter_font_n}_{$starter_key}";
							},
							array_keys( $starter_typography_checkbox_labels )
						)
					),
					'choices'  => $starter_typography_checkbox_labels,
					'priority' => $starter_typography_priority++,
				)
			)
		);

		// Bottone per svuotare/nascondere tutto questo font.
		$wp_customize->add_setting(
			"starter_font{$starter_font_n}_remove",
			array(
				'sanitize_callback' => '__return_false',
			)
		);

		$wp_customize->add_control(
			new Starter_Customize_Button_Control(
				$wp_customize,
				"starter_font{$starter_font_n}_remove",
				array(
					'section'      => 'starter_options_typography',
					'button_text'  => __( 'Rimuovi questo font', 'slug-theme' ),
					'button_class' => 'starter-remove-button',
					'priority'     => $starter_typography_priority++,
				)
			)
		);
	}

	// --- Sezione "Stile testi": stile responsive per H1-H6, .txt e size native WP; solo tipografia, niente padding/bordi.
	$wp_customize->add_section(
		'starter_options_text_style',
		array(
			'title'       => __( 'Stile testi', 'slug-theme' ),
			'description' => __( 'Già compilate con clamp(): si adattano da sole a ogni schermo. Tablet e Mobile vuoti = come Desktop. Svuota un campo per tornare al valore standard.', 'slug-theme' ),
			'panel'       => 'starter_options',
			'priority'    => 7.5, // Tra Tipografia (7) e Colori (8).
		)
	);

	// Secondo valore = se l'elemento ha anche il campo margin-bottom.
	$starter_text_style_elements = array(
		'display'      => array( __( 'Titolo grande hero (.title-display)', 'slug-theme' ), false ),
		'h1'           => array( __( 'H1 (h1, .title-h1)', 'slug-theme' ), true ),
		'h2'           => array( __( 'H2 (h2, .title-h2)', 'slug-theme' ), true ),
		'h3'           => array( __( 'H3 (h3, .title-h3)', 'slug-theme' ), true ),
		'h4'           => array( __( 'H4 (h4, .title-h4)', 'slug-theme' ), true ),
		'h5'           => array( __( 'H5 (h5, .title-h5)', 'slug-theme' ), true ),
		'h6'           => array( __( 'H6 (h6, .title-h6)', 'slug-theme' ), true ),
		'txt'          => array( __( 'Paragrafo (.txt)', 'slug-theme' ), true ),
		'size_xx_large' => array( __( 'Dimensione XXL (.has-xx-large-font-size)', 'slug-theme' ), false ),
		'size_x_large' => array( __( 'Dimensione XL (.has-x-large-font-size)', 'slug-theme' ), false ),
		'size_large'   => array( __( 'Dimensione grande (.has-large-font-size)', 'slug-theme' ), false ),
		'size_medium'  => array( __( 'Dimensione media (.has-medium-font-size)', 'slug-theme' ), false ),
		'size_small'   => array( __( 'Dimensione piccola (.has-small-font-size)', 'slug-theme' ), false ),
		'btn'          => array( __( 'Pulsanti (.button, .wp-block-button__link)', 'slug-theme' ), false ),
	);

	$starter_text_style_priority = 1;

	foreach ( $starter_text_style_elements as $starter_el_key => $starter_el_data ) {
		list( $starter_el_label, $starter_el_has_margin ) = $starter_el_data;
		$starter_prefix = "starter_text_style_{$starter_el_key}";

		$wp_customize->add_setting(
			"{$starter_prefix}_heading",
			array(
				'sanitize_callback' => '__return_false',
			)
		);

		$wp_customize->add_control(
			new Starter_Customize_Heading_Control(
				$wp_customize,
				"{$starter_prefix}_heading",
				array(
					'section'  => 'starter_options_text_style',
					'label'    => $starter_el_label,
					'priority' => $starter_text_style_priority++,
				)
			)
		);

		foreach (
			array(
				'font_size'      => __( 'font-size', 'slug-theme' ),
				'line_height'    => __( 'line-height', 'slug-theme' ),
				'letter_spacing' => __( 'letter-spacing', 'slug-theme' ),
			) as $starter_prop => $starter_prop_label
		) {
			foreach ( array( 'desktop', 'tablet', 'mobile' ) as $starter_bp ) {
				$wp_customize->add_setting(
					"{$starter_prefix}_{$starter_prop}_{$starter_bp}",
					array(
						// Solo Desktop è compilato (clamp: si adatta da solo a ogni larghezza); Tablet e Mobile restano vuoti = come Desktop.
						'default'           => 'desktop' === $starter_bp ? starter_text_style_default( $starter_el_key, $starter_prop ) : '',
						'sanitize_callback' => 'starter_sanitize_css_length',
					)
				);
			}

			$wp_customize->add_control(
				new Starter_Customize_Responsive_Text_Control(
					$wp_customize,
					"{$starter_prefix}_{$starter_prop}",
					array(
						'section'  => 'starter_options_text_style',
						'label'    => $starter_prop_label,
						'settings' => array(
							'desktop' => "{$starter_prefix}_{$starter_prop}_desktop",
							'tablet'  => "{$starter_prefix}_{$starter_prop}_tablet",
							'mobile'  => "{$starter_prefix}_{$starter_prop}_mobile",
						),
						'priority' => $starter_text_style_priority++,
					)
				)
			);
		}

		$wp_customize->add_setting(
			"{$starter_prefix}_font_weight",
			array(
				'default'           => '',
				'sanitize_callback' => 'starter_sanitize_text_style_font_weight',
			)
		);

		$wp_customize->add_control(
			"{$starter_prefix}_font_weight",
			array(
				'type'     => 'select',
				'section'  => 'starter_options_text_style',
				'label'    => __( 'font-weight', 'slug-theme' ),
				'choices'  => array( '' => __( '— Non impostato —', 'slug-theme' ) ) + starter_font_weight_choices(),
				'priority' => $starter_text_style_priority++,
			)
		);

		$wp_customize->add_setting(
			"{$starter_prefix}_uppercase",
			array(
				'default'           => false,
				'sanitize_callback' => 'starter_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				"{$starter_prefix}_uppercase",
				array(
					'section'  => 'starter_options_text_style',
					'type'     => 'checkbox',
					'label'    => __( 'text-transform: uppercase', 'slug-theme' ),
					'priority' => $starter_text_style_priority++,
				)
			)
		);


		// Solo margin-bottom (niente margin-top, tolto su richiesta utente).
		if ( $starter_el_has_margin ) {
			foreach ( array( 'desktop', 'tablet', 'mobile' ) as $starter_bp ) {
				$wp_customize->add_setting(
					"{$starter_prefix}_margin_bottom_{$starter_bp}",
					array(
						'default'           => '',
						'sanitize_callback' => 'starter_sanitize_css_length',
					)
				);
			}

			$wp_customize->add_control(
				new Starter_Customize_Responsive_Text_Control(
					$wp_customize,
					"{$starter_prefix}_margin_bottom",
					array(
						'section'  => 'starter_options_text_style',
						'label'    => __( 'margin-bottom', 'slug-theme' ),
						'settings' => array(
							'desktop' => "{$starter_prefix}_margin_bottom_desktop",
							'tablet'  => "{$starter_prefix}_margin_bottom_tablet",
							'mobile'  => "{$starter_prefix}_margin_bottom_mobile",
						),
						'priority' => $starter_text_style_priority++,
					)
				)
			);
		}
	}

	// --- Colori: UNA sola sezione dentro "Opzioni" con 4 gruppi che si aprono e si chiudono (il Customizer non ha pannelli dentro un pannello):
	// Tavolozza, Sfondi/testi/link, Menu/footer/social, Tag e categorie. Tutti i colori sono facoltativi (vuoto = colore di base). I pulsanti sono in Opzioni → Pulsanti.
	// I gruppi sono titoli "starter_heading_cgroup_*" che JS/customize-controls.js trasforma in fisarmonica; l'ordine è quello delle priorità.
	$wp_customize->add_section(
		'starter_options_colors',
		array(
			'title'       => __( 'Colori', 'slug-theme' ),
			'description' => __( 'Tutti i colori del sito, in 4 gruppi che si aprono con un click. Parti dalla Tavolozza: i colori che definisci lì (con il loro nome) si scelgono poi negli altri gruppi e in Opzioni → Pulsanti; cambiando un colore nella Tavolozza cambia ovunque sia usato. Un colore scelto su un blocco in Gutenberg vince su questi.', 'slug-theme' ),
			'panel'       => 'starter_options',
			'priority'    => 8,
		)
	);

	// Sezione "Pulsanti" (Opzioni): le 4 varianti di pulsante (2 piene, 2 a contorno), arrotondamento e maiuscole. I colori si scelgono dalla Tavolozza.
	$wp_customize->add_section(
		'starter_options_buttons',
		array(
			'title'       => __( 'Pulsanti', 'slug-theme' ),
			'panel'       => 'starter_options',
			'priority'    => 9,
		)
	);

	starter_colors_section( 'starter_options_colors' );

	$starter_heading_checkbox_labels = array(
		'h1' => __( 'H1', 'slug-theme' ),
		'h2' => __( 'H2', 'slug-theme' ),
		'h3' => __( 'H3', 'slug-theme' ),
		'h4' => __( 'H4', 'slug-theme' ),
		'h5' => __( 'H5', 'slug-theme' ),
		'h6' => __( 'H6', 'slug-theme' ),
	);

	// Bersagli extra per "Applica a" (oltre ai titoli), riusabili anche per Colore link.
	$starter_link_target_labels = array(
		'current_page'      => __( 'Pagina corrente (menu header)', 'slug-theme' ),
		'menu_hover'         => __( 'Hover link menu', 'slug-theme' ),
		'footer_link'        => __( 'Link footer', 'slug-theme' ),
		'footer_link_hover'  => __( 'Hover link footer', 'slug-theme' ),
	);

	$starter_heading_and_link_targets = $starter_heading_checkbox_labels + $starter_link_target_labels;

	// --- Sottosezione "Colori con nome": 8 slot, i primi 5 visibili, altri 3 on-demand; riusati altrove via tendina.
	starter_customizer_heading( $wp_customize, 'starter_heading_cgroup_palette', 'starter_options_colors', __( 'Tavolozza', 'slug-theme' ), 0.5 );
	$starter_named_color_priority      = 1;
	$starter_named_color_default_names = array(
		1 => __( 'Principale', 'slug-theme' ),
		2 => __( 'Secondario', 'slug-theme' ),
		3 => __( 'Testo', 'slug-theme' ),
		4 => __( 'In risalto', 'slug-theme' ),
	);

	for ( $starter_i = 1; $starter_i <= STARTER_COLOR_SLOTS; $starter_i++ ) {
		$wp_customize->add_setting(
			"starter_named_color_{$starter_i}_name",
			array(
				'default'           => isset( $starter_named_color_default_names[ $starter_i ] ) ? $starter_named_color_default_names[ $starter_i ] : '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$starter_named_color_name_args = array(
			'type'     => 'text',
			'section'  => 'starter_options_colors',
			/* translators: %d: numero del colore. */
			'label'    => sprintf( __( 'Colore %d · nome', 'slug-theme' ), $starter_i ),
			'priority' => $starter_named_color_priority++,
		);

		$wp_customize->add_control( "starter_named_color_{$starter_i}_name", $starter_named_color_name_args );

		$wp_customize->add_setting(
			"starter_named_color_{$starter_i}_value",
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				"starter_named_color_{$starter_i}_value",
				array(
					'section'  => 'starter_options_colors',
					'label'    => __( 'Colore', 'slug-theme' ),
					'priority' => $starter_named_color_priority++,
				)
			)
		);

		if ( $starter_i > 5 ) {
			$wp_customize->add_setting(
				"starter_named_color_{$starter_i}_remove",
				array(
					'sanitize_callback' => '__return_false',
				)
			);

			$wp_customize->add_control(
				new Starter_Customize_Button_Control(
					$wp_customize,
					"starter_named_color_{$starter_i}_remove",
					array(
						'section'      => 'starter_options_colors',
						'button_text'  => __( 'Rimuovi', 'slug-theme' ),
						'button_class' => 'starter-remove-button starter-remove-named-color-' . $starter_i,
						'priority'     => $starter_named_color_priority++,
					)
				)
			);
		}
	}

	$wp_customize->add_setting(
		'starter_named_color_add_button',
		array(
			'sanitize_callback' => '__return_false',
		)
	);

	$wp_customize->add_control(
		new Starter_Customize_Button_Control(
			$wp_customize,
			'starter_named_color_add_button',
			array(
				'section'      => 'starter_options_colors',
				'button_text'  => __( '+ Aggiungi colore', 'slug-theme' ),
				'button_class' => 'starter-add-named-color-button',
				'priority'     => $starter_named_color_priority++,
			)
		)
	);

	starter_customizer_heading( $wp_customize, 'starter_heading_cgroup_site', 'starter_options_colors', __( 'Sfondi, testi e link', 'slug-theme' ), 99.5 );
	starter_colors_section( 'starter_options_colors' ); // Da qui: gruppo "Sfondi, testi e link".
	$starter_colors_priority = 100;

	starter_register_color_control(
		$wp_customize,
		'starter_color_background',
		__( 'Sfondo del sito', 'slug-theme' ),
		$starter_colors_priority,
		__( 'Vuoto = resta il colore di base del tema.', 'slug-theme' )
	);

	starter_register_color_control(
		$wp_customize,
		'starter_color_text',
		__( 'Testo', 'slug-theme' ),
		$starter_colors_priority,
		__( 'Vuoto = resta il colore di base del tema.', 'slug-theme' )
	);

	// Colore titoli: stesso meccanismo "Applica a" della Tipografia, ma solo H1-H6.
	foreach ( array( 'primary' => __( 'Colore principale', 'slug-theme' ), 'secondary' => __( 'Colore secondario', 'slug-theme' ) ) as $starter_heading_color_key => $starter_heading_color_label ) {
		$wp_customize->add_setting(
			"starter_heading_color_{$starter_heading_color_key}",
			array(
				'sanitize_callback' => '__return_false',
			)
		);

		$wp_customize->add_control(
			new Starter_Customize_Heading_Control(
				$wp_customize,
				"starter_heading_color_{$starter_heading_color_key}",
				array(
					'section'  => starter_colors_section(),
					'label'    => $starter_heading_color_label,
					'priority' => $starter_colors_priority++,
				)
			)
		);

		starter_register_color_control(
			$wp_customize,
			"starter_color_heading_{$starter_heading_color_key}",
			__( 'Colore', 'slug-theme' ),
			$starter_colors_priority
		);

		foreach ( $starter_heading_and_link_targets as $starter_key => $starter_label ) {
			$wp_customize->add_setting(
				"starter_color_heading_{$starter_heading_color_key}_{$starter_key}",
				array(
					'default'           => false,
					'sanitize_callback' => 'starter_sanitize_checkbox',
				)
			);
		}

		$wp_customize->add_control(
			new Starter_Customize_Checkbox_Group_Control(
				$wp_customize,
				"starter_color_heading_{$starter_heading_color_key}_elements",
				array(
					'section'  => starter_colors_section(),
					'label'    => __( 'Applica a', 'slug-theme' ),
					'settings' => array_combine(
						array_keys( $starter_heading_and_link_targets ),
						array_map(
							function ( $starter_key ) use ( $starter_heading_color_key ) {
								return "starter_color_heading_{$starter_heading_color_key}_{$starter_key}";
							},
							array_keys( $starter_heading_and_link_targets )
						)
					),
					'choices'  => $starter_heading_and_link_targets,
					'priority' => $starter_colors_priority++,
				)
			)
		);
	}

	// Colore link: si applica ai link "semplici" via --color-link/-hover, con lo stesso "Applica a".
	$wp_customize->add_setting(
		'starter_heading_color_link',
		array(
			'sanitize_callback' => '__return_false',
		)
	);

	$wp_customize->add_control(
		new Starter_Customize_Heading_Control(
			$wp_customize,
			'starter_heading_color_link',
			array(
				'section'     => starter_colors_section(),
				'label'       => __( 'Colore link', 'slug-theme' ),
				'description' => __( 'Tutti i link, tranne header, footer e pulsanti.', 'slug-theme' ),
				'priority'    => $starter_colors_priority++,
			)
		)
	);

	starter_register_color_control(
		$wp_customize,
		'starter_color_link',
		__( 'Colore', 'slug-theme' ),
		$starter_colors_priority,
		__( 'Vuoto = resta il colore principale del tema.', 'slug-theme' )
	);

	starter_register_color_control(
		$wp_customize,
		'starter_color_link_hover',
		__( 'Colore hover', 'slug-theme' ),
		$starter_colors_priority,
		__( 'Vuoto = nessun cambio colore al passaggio del mouse.', 'slug-theme' )
	);

	$wp_customize->add_setting(
		'starter_color_link_underline',
		array(
			'default'           => true,
			'sanitize_callback' => 'starter_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'starter_color_link_underline',
			array(
				'section'     => starter_colors_section(),
				'label'       => __( 'Sottolineatura', 'slug-theme' ),
				'type'        => 'checkbox',
				'priority'    => $starter_colors_priority++,
			)
		)
	);

	foreach ( $starter_link_target_labels as $starter_key => $starter_label ) {
		$wp_customize->add_setting(
			"starter_color_link_{$starter_key}",
			array(
				'default'           => false,
				'sanitize_callback' => 'starter_sanitize_checkbox',
			)
		);
	}

	$wp_customize->add_control(
		new Starter_Customize_Checkbox_Group_Control(
			$wp_customize,
			'starter_color_link_elements',
			array(
				'section'  => starter_colors_section(),
				'label'    => __( 'Applica a', 'slug-theme' ),
				'settings' => array_combine(
					array_keys( $starter_link_target_labels ),
					array_map(
						function ( $starter_key ) {
							return "starter_color_link_{$starter_key}";
						},
						array_keys( $starter_link_target_labels )
					)
				),
				'choices'  => $starter_link_target_labels,
				'priority' => $starter_colors_priority++,
			)
		)
	);

	starter_colors_section( 'starter_options_buttons' ); // Sezione "Pulsanti" (Opzioni).

	// Forma e testo, validi per tutte le varianti.
	starter_customizer_select(
		$wp_customize,
		'starter_button_radius',
		'starter_options_buttons',
		__( 'Arrotondamento dei pulsanti', 'slug-theme' ),
		array(
			''    => __( 'Come il resto del sito (Generale → Bordi del sito)', 'slug-theme' ),
			'0'   => __( 'Squadrato', 'slug-theme' ),
			'6'   => __( 'Leggero', 'slug-theme' ),
			'10'  => __( 'Medio', 'slug-theme' ),
			'16'  => __( 'Morbido', 'slug-theme' ),
			'999' => __( 'A pillola', 'slug-theme' ),
			'custom' => __( 'Valore mio (px)', 'slug-theme' ),
		),
		'',
		__( 'Vale per tutti i pulsanti del sito, anche quelli di Gutenberg, dei blocchi e dell\'header.', 'slug-theme' ),
		1
	);

	$wp_customize->add_setting(
		'starter_button_radius_custom',
		array(
			'default'           => 12,
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'starter_button_radius_custom',
		array(
			'type'        => 'number',
			'section'     => 'starter_options_buttons',
			'label'       => __( 'Il tuo valore (px)', 'slug-theme' ),
			'description' => __( 'Si usa con "Valore mio (px)". Da 0 a 999 (999 = a pillola).', 'slug-theme' ),
			'input_attrs' => array(
				'min' => 0,
				'max' => 999,
			),
			'priority'    => 1.5,
		)
	);

	starter_customizer_checkbox( $wp_customize, 'starter_button_uppercase', 'starter_options_buttons', __( 'Testo dei pulsanti in maiuscolo', 'slug-theme' ), false, '', 2 );

	// Dimensione e padding: valgono per tutti i pulsanti del sito (header, blocchi, Gutenberg).
	starter_customizer_select(
		$wp_customize,
		'starter_button_size',
		'starter_options_buttons',
		__( 'Dimensione dei pulsanti', 'slug-theme' ),
		array(
			''       => __( 'Normale', 'slug-theme' ),
			'small'  => __( 'Piccola', 'slug-theme' ),
			'xsmall' => __( 'Molto piccola', 'slug-theme' ),
		),
		'',
		'',
		2.2
	);

	foreach ( array( 'y' => array( __( 'Padding verticale (px)', 'slug-theme' ), 2.4 ), 'x' => array( __( 'Padding orizzontale (px)', 'slug-theme' ), 2.6 ) ) as $starter_pad_axis => $starter_pad_data ) {
		$wp_customize->add_setting(
			"starter_button_pad_{$starter_pad_axis}",
			array(
				'default'           => '',
				'sanitize_callback' => static function ( $value ) {
					return '' === $value || null === $value ? '' : (string) min( 200, absint( $value ) );
				},
			)
		);

		$wp_customize->add_control(
			"starter_button_pad_{$starter_pad_axis}",
			array(
				'type'        => 'number',
				'section'     => 'starter_options_buttons',
				'label'       => $starter_pad_data[0],
				'input_attrs' => array(
					'min'         => 0,
					'max'         => 200,
					'placeholder' => __( 'Automatico', 'slug-theme' ),
				),
				'priority'    => $starter_pad_data[1],
			)
		);
	}

	$starter_colors_priority = 10;

	// Le 4 varianti. Ogni variante ha gli stessi 6 campi; i primi 4 di "Pieno 1" e "Contorno 1" sono quelli già usati dal tema (--color-primary, --color-secondary…).
	foreach (
		array(
			'primary'   => array(
				'label'  => __( 'Pieno 1', 'slug-theme' ),
				'desc'   => __( 'Il pulsante principale: CTA dell\'header, pulsanti Gutenberg "Pieno" e quasi tutti i pulsanti del sito.', 'slug-theme' ),
				'fields' => array(
					''             => __( 'Sfondo', 'slug-theme' ),
					'_hover'       => __( 'Sfondo al passaggio del mouse', 'slug-theme' ),
					'_text'        => __( 'Testo', 'slug-theme' ),
					'_text_hover'  => __( 'Testo al passaggio del mouse', 'slug-theme' ),
					'_border'      => __( 'Bordo (vuoto = come lo sfondo)', 'slug-theme' ),
					'_border_hover' => __( 'Bordo al passaggio (vuoto = come lo sfondo hover)', 'slug-theme' ),
				),
			),
			'secondary' => array(
				'label'  => __( 'Contorno 1', 'slug-theme' ),
				'desc'   => __( 'Il pulsante a contorno: bordo e sfondo trasparente a riposo. Anche lo stile "Contorno" dei pulsanti Gutenberg.', 'slug-theme' ),
				'fields' => array(
					''             => __( 'Bordo', 'slug-theme' ),
					'_hover'       => __( 'Sfondo al passaggio del mouse', 'slug-theme' ),
					'_text'        => __( 'Testo', 'slug-theme' ),
					'_text_hover'  => __( 'Testo al passaggio del mouse', 'slug-theme' ),
					'_bg'          => __( 'Sfondo a riposo (vuoto = trasparente)', 'slug-theme' ),
					'_border_hover' => __( 'Bordo al passaggio (vuoto = come il testo hover)', 'slug-theme' ),
				),
			),
			'fill2'     => array(
				'label'  => __( 'Pieno 2', 'slug-theme' ),
				'desc'   => __( 'Un secondo pulsante pieno (classe .button--fill-2; in Gutenberg lo stile "Pieno 2"). Vuoto = scuro con testo chiaro.', 'slug-theme' ),
				'fields' => array(
					''             => __( 'Sfondo', 'slug-theme' ),
					'_hover'       => __( 'Sfondo al passaggio del mouse', 'slug-theme' ),
					'_text'        => __( 'Testo', 'slug-theme' ),
					'_text_hover'  => __( 'Testo al passaggio del mouse', 'slug-theme' ),
					'_border'      => __( 'Bordo (vuoto = come lo sfondo)', 'slug-theme' ),
					'_border_hover' => __( 'Bordo al passaggio (vuoto = come lo sfondo hover)', 'slug-theme' ),
				),
			),
			'outline2'  => array(
				'label'  => __( 'Contorno 2', 'slug-theme' ),
				'desc'   => __( 'Un secondo pulsante a contorno (classe .button--outline-2; in Gutenberg lo stile "Contorno 2"). Vuoto = bordo scuro che si riempie in hover.', 'slug-theme' ),
				'fields' => array(
					''             => __( 'Bordo', 'slug-theme' ),
					'_hover'       => __( 'Sfondo al passaggio del mouse', 'slug-theme' ),
					'_text'        => __( 'Testo', 'slug-theme' ),
					'_text_hover'  => __( 'Testo al passaggio del mouse', 'slug-theme' ),
					'_bg'          => __( 'Sfondo a riposo (vuoto = trasparente)', 'slug-theme' ),
					'_border_hover' => __( 'Bordo al passaggio (vuoto = come il testo hover)', 'slug-theme' ),
				),
			),
		) as $starter_btn_key => $starter_btn_data
	) {
		$wp_customize->add_setting(
			"starter_heading_color_btn_{$starter_btn_key}",
			array(
				'sanitize_callback' => '__return_false',
			)
		);

		$wp_customize->add_control(
			new Starter_Customize_Heading_Control(
				$wp_customize,
				"starter_heading_color_btn_{$starter_btn_key}",
				array(
					'section'     => starter_colors_section(),
					'label'       => $starter_btn_data['label'],
					'description' => $starter_btn_data['desc'],
					'priority'    => $starter_colors_priority++,
				)
			)
		);

		foreach ( $starter_btn_data['fields'] as $starter_btn_suffix => $starter_btn_label ) {
			starter_register_color_control( $wp_customize, "starter_color_btn_{$starter_btn_key}{$starter_btn_suffix}", $starter_btn_label, $starter_colors_priority );
		}
	}

	starter_colors_section( 'starter_options_colors' ); // Gruppo "Menu, footer e social" (dopo i pulsanti, che ora sono in un'altra sezione).
	$starter_colors_priority = 300;
	starter_customizer_heading( $wp_customize, 'starter_heading_cgroup_nav', 'starter_options_colors', __( 'Menu, footer e social', 'slug-theme' ), $starter_colors_priority++ );

	// Menu mobile e form newsletter footer: stesso schema 4 campi, variabili CSS separate per ciascuno.
	foreach (
		array(
			'menu'        => array(
				'prefix' => 'starter_color_menu',
				'label'  => __( 'Menu mobile', 'slug-theme' ),
			),
			'footer_form' => array(
				'prefix' => 'starter_color_footer_form',
				'label'  => __( 'Form newsletter (footer)', 'slug-theme' ),
			),
		) as $starter_group_data
	) {
		$starter_prefix = $starter_group_data['prefix'];

		$wp_customize->add_setting(
			"starter_heading_{$starter_prefix}",
			array(
				'sanitize_callback' => '__return_false',
			)
		);

		$wp_customize->add_control(
			new Starter_Customize_Heading_Control(
				$wp_customize,
				"starter_heading_{$starter_prefix}",
				array(
					'section'  => starter_colors_section(),
					'label'    => $starter_group_data['label'],
					'priority' => $starter_colors_priority++,
				)
			)
		);

		starter_register_color_control( $wp_customize, $starter_prefix, __( 'Colore', 'slug-theme' ), $starter_colors_priority );
		starter_register_color_control( $wp_customize, "{$starter_prefix}_hover", __( 'Colore hover', 'slug-theme' ), $starter_colors_priority );
		starter_register_color_control( $wp_customize, "{$starter_prefix}_text", __( 'Colore testo', 'slug-theme' ), $starter_colors_priority );
		starter_register_color_control( $wp_customize, "{$starter_prefix}_text_hover", __( 'Colore testo hover', 'slug-theme' ), $starter_colors_priority );
	}

	// Footer: colore testo condiviso da tutto ciò che non ha già un gruppo dedicato (form newsletter/icone social esclusi).
	$wp_customize->add_setting(
		'starter_heading_color_footer',
		array(
			'sanitize_callback' => '__return_false',
		)
	);

	$wp_customize->add_control(
		new Starter_Customize_Heading_Control(
			$wp_customize,
			'starter_heading_color_footer',
			array(
				'section'  => starter_colors_section(),
				'label'    => __( 'Footer', 'slug-theme' ),
				'priority' => $starter_colors_priority++,
			)
		)
	);

	starter_register_color_control(
		$wp_customize,
		'starter_color_footer_bg',
		__( 'Sfondo footer', 'slug-theme' ),
		$starter_colors_priority
	);
	starter_register_color_control(
		$wp_customize,
		'starter_color_footer_text',
		__( 'Colore testo footer', 'slug-theme' ),
		$starter_colors_priority,
		__( 'Si applica al testo generico, ai link legali (Privacy/Cookie/Termini/Credits), a eventuali link nei contatti e alle voci del menu di navigazione.', 'slug-theme' )
	);
	starter_register_color_control(
		$wp_customize,
		'starter_color_footer_link_hover',
		__( 'Colore hover dei link', 'slug-theme' ),
		$starter_colors_priority,
		__( 'Stesso gruppo di link del campo precedente, in hover.', 'slug-theme' )
	);
	starter_register_color_control(
		$wp_customize,
		'starter_color_footer_current_page',
		__( 'Colore pagina corrente', 'slug-theme' ),
		$starter_colors_priority
	);

	// Icone social: 3 campi, non 4 — "Colore" vuoto = segue il contesto (bianco su hero, scuro altrove), non forzare.
	$wp_customize->add_setting(
		'starter_heading_color_social',
		array(
			'sanitize_callback' => '__return_false',
		)
	);

	$wp_customize->add_control(
		new Starter_Customize_Heading_Control(
			$wp_customize,
			'starter_heading_color_social',
			array(
				'section'  => starter_colors_section(),
				'label'    => __( 'Icone social', 'slug-theme' ),
				'priority' => $starter_colors_priority++,
			)
		)
	);

	starter_register_color_control(
		$wp_customize,
		'starter_color_social',
		__( 'Colore', 'slug-theme' ),
		$starter_colors_priority,
		__( 'Vuoto = segue il colore del contesto (bianco sopra una hero trasparente, scuro altrove), come sempre.', 'slug-theme' )
	);
	starter_register_color_control( $wp_customize, 'starter_color_social_hover', __( 'Colore hover', 'slug-theme' ), $starter_colors_priority );
	starter_register_color_control( $wp_customize, 'starter_color_social_text_hover', __( 'Colore testo hover', 'slug-theme' ), $starter_colors_priority );

	starter_customizer_heading( $wp_customize, 'starter_heading_cgroup_tags', 'starter_options_colors', __( 'Tag e categorie', 'slug-theme' ), $starter_colors_priority++ );

	// Tag e categorie portfolio, stesso "pill": solo 2 campi (bordo+testo a riposo, sfondo pieno in hover).
	$wp_customize->add_setting(
		'starter_heading_color_tag',
		array(
			'sanitize_callback' => '__return_false',
		)
	);

	$wp_customize->add_control(
		new Starter_Customize_Heading_Control(
			$wp_customize,
			'starter_heading_color_tag',
			array(
				'section'  => starter_colors_section(),
				'label'    => __( 'Tag e categorie', 'slug-theme' ),
				'priority' => $starter_colors_priority++,
			)
		)
	);

	starter_register_color_control(
		$wp_customize,
		'starter_color_tag',
		__( 'Colore', 'slug-theme' ),
		$starter_colors_priority,
		__( 'Bordo e testo a riposo.', 'slug-theme' )
	);
	starter_register_color_control(
		$wp_customize,
		'starter_color_tag_hover',
		__( 'Colore hover', 'slug-theme' ),
		$starter_colors_priority,
		__( 'Sfondo pieno al passaggio del mouse.', 'slug-theme' )
	);

	$wp_customize->add_setting(
		'starter_color_tag_keep_border',
		array(
			'default'           => false,
			'sanitize_callback' => 'starter_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'starter_color_tag_keep_border',
			array(
				'section'     => starter_colors_section(),
				'type'        => 'checkbox',
				'label'       => __( 'Mantieni lo stesso colore del bordo anche in hover', 'slug-theme' ),
				'description' => __( 'Spento: in hover il bordo prende "Colore hover". Acceso: resta com\'è.', 'slug-theme' ),
				'priority'    => $starter_colors_priority++,
			)
		)
	);
}
add_action( 'customize_register', 'starter_customize_register' );

/* Versione asset da data modifica file, non da costante fissa: evita cache stale durante lo sviluppo. */
function starter_asset_version( $starter_relative_path ) {
	$starter_path = get_template_directory() . '/' . ltrim( $starter_relative_path, '/' );

	return file_exists( $starter_path ) ? filemtime( $starter_path ) : STARTER_THEME_VERSION;
}

/* Script admin Customizer: rivela uno slot icona personalizzata alla volta ad ogni click "+ Aggiungi". */
function starter_customize_controls_enqueue_scripts() {
	$starter_customize_controls_ver = starter_asset_version( 'JS/customize-controls.js' );

	wp_enqueue_script(
		'starter-customize-controls',
		get_template_directory_uri() . '/JS/customize-controls.js',
		array( 'jquery', 'customize-controls' ),
		$starter_customize_controls_ver,
		true
	);

	wp_add_inline_style(
		'customize-controls',
		'.starter-customize-heading {
			margin: 1.5em 0 0.5em;
			padding-top: 1em;
			border-top: 1px solid #dcdcde;
			font-size: 14px;
			text-transform: uppercase;
			letter-spacing: 0.04em;
			color: #50575e;
		}
		.starter-cgroup-head .starter-customize-heading {
			position: relative;
			cursor: pointer;
		}
		.starter-cgroup-head .starter-customize-heading::after {
			content: "";
			position: absolute;
			right: 4px;
			top: calc(1em + 4px);
			width: 7px;
			height: 7px;
			border-right: 2px solid currentColor;
			border-bottom: 2px solid currentColor;
			transform: rotate(45deg);
		}
		.starter-cgroup-head.is-open .starter-customize-heading::after {
			top: calc(1em + 8px);
			transform: rotate(-135deg);
		}
		.starter-cgroup-closed {
			display: none !important;
		}
		li:first-child > .customize-control .starter-customize-heading {
			margin-top: 0;
			padding-top: 0;
			border-top: 0;
		}
		.starter-checkbox-group {
			display: flex;
			flex-wrap: wrap;
			gap: 6px 14px;
			margin-top: 4px;
		}
		.starter-checkbox-group label {
			display: inline-flex;
			align-items: center;
			gap: 4px;
			font-size: 12px;
			white-space: nowrap;
		}
		.starter-checkbox-group input[type="checkbox"] {
			margin: 0;
		}
		/* Pulsanti "Aggiungi…" (solo contorno) e "Rimuovi…" (campitura piena): uguali in tutte le sezioni, così si distinguono subito. */
		.customize-control .button[class*="starter-add-"] {
			background: #fff;
			color: #2271b1;
			border-color: #2271b1;
			font-weight: 600;
		}
		.customize-control .button[class*="starter-add-"]:hover {
			background: #f0f6fc;
			color: #135e96;
			border-color: #135e96;
		}
		.customize-control .button.starter-remove-button {
			background: #b32d2e;
			color: #fff;
			border-color: #b32d2e;
			font-weight: 600;
		}
		.customize-control .button.starter-remove-button:hover {
			background: #8a2424;
			color: #fff;
			border-color: #8a2424;
		}
		/* Tavolozza: nome, campione di colore e "Rimuovi" sulla stessa riga. */
		#customize-theme-controls #sub-accordion-section-starter_options_colors.open {
			display: grid !important;
			grid-template-columns: minmax(0, 1fr) auto auto;
			column-gap: 8px;
			align-items: end;
		}
		#sub-accordion-section-starter_options_colors > li {
			grid-column: 1 / -1;
		}
		#sub-accordion-section-starter_options_colors > li[id^="customize-control-starter_named_color_"][id$="_name"] {
			grid-column: 1;
		}
		#sub-accordion-section-starter_options_colors > li[id^="customize-control-starter_named_color_"][id$="_value"] {
			grid-column: 2;
		}
		#sub-accordion-section-starter_options_colors > li[id^="customize-control-starter_named_color_"][id$="_remove"] {
			grid-column: 3;
		}
		#sub-accordion-section-starter_options_colors .wp-color-result-text {
			display: none;
		}
		#sub-accordion-section-starter_options_colors .wp-color-result.button {
			min-width: 44px;
			height: 30px;
			margin: 0;
			padding: 0 0 0 30px;
		}
		.starter-responsive-text-group {
			display: flex;
			gap: 8px;
			margin-top: 4px;
		}
		.starter-responsive-text-group__field {
			flex: 1;
			min-width: 0;
		}
		.starter-responsive-text-group__label {
			display: block;
			font-size: 11px;
			color: #50575e;
			margin-bottom: 2px;
		}
		.starter-responsive-text-group__field input[type="text"] {
			width: 100%;
		}'
	);
}
add_action( 'customize_controls_enqueue_scripts', 'starter_customize_controls_enqueue_scripts' );

/* Consente upload SVG solo a chi può modificare le opzioni tema, non a tutti gli utenti. */
function starter_allow_svg_upload( $mimes ) {
	if ( current_user_can( 'edit_theme_options' ) ) {
		$mimes['svg'] = 'image/svg+xml';
	}

	return $mimes;
}
add_filter( 'upload_mimes', 'starter_allow_svg_upload' );

/* wp_check_filetype_and_ext rifiuta comunque l'SVG: qui lo whitelista per chi può già caricarlo. */
function starter_fix_svg_filetype_check( $data, $file, $filename, $mimes ) {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return $data;
	}

	if ( ! $data['type'] ) {
		$filetype = wp_check_filetype( $filename, $mimes );

		if ( 'svg' === $filetype['ext'] ) {
			$data['ext']             = 'svg';
			$data['type']            = 'image/svg+xml';
			$data['proper_filename'] = $filename;
		}
	}

	return $data;
}
add_filter( 'wp_check_filetype_and_ext', 'starter_fix_svg_filetype_check', 10, 4 );

/* Sanifica anche il file SVG salvato su disco, non solo l'output in pagina. */
function starter_sanitize_uploaded_svg( $file ) {
	// 'type' può mancare (es. import da CLI via wp_handle_sideload_prefilter()).
	if ( 'image/svg+xml' !== ( $file['type'] ?? '' ) ) {
		return $file;
	}

	$contents = file_get_contents( $file['tmp_name'] ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- file appena caricato dall'admin, non un URL remoto.

	if ( $contents ) {
		file_put_contents( $file['tmp_name'], starter_sanitize_svg( $contents ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
	}

	return $file;
}
add_filter( 'wp_handle_upload_prefilter', 'starter_sanitize_uploaded_svg' );
add_filter( 'wp_handle_sideload_prefilter', 'starter_sanitize_uploaded_svg' );

/* Sanitizzazione checkbox del Customizer. */
function starter_sanitize_checkbox( $checked ) {
	return (bool) $checked;
}

/* "Larghezza contenuto": pixel in un range ragionevole, altrimenti default 1280. */
function starter_sanitize_content_width( $value ) {
	$value = absint( $value );

	return ( $value >= 320 && $value <= 3000 ) ? $value : 1280;
}

/* Valida una misura CSS libera (numero + unità opzionale, per line-height senza unità); altro = vuoto per prevenire iniezione CSS. */
function starter_sanitize_css_length( $value ) {
	$value = trim( (string) $value );

	if ( '' === $value ) {
		return '';
	}

	return preg_match( '/^-?[0-9]*\.?[0-9]+(px|em|rem|vw|vh|vmin|vmax|%|ch|ex)?$/', $value ) ? $value : '';
}

/* "font-weight" in Stile testi è facoltativo: a differenza di starter_sanitize_font_weight(), invalido torna vuoto non "400". */
function starter_sanitize_text_style_font_weight( $value ) {
	return array_key_exists( $value, starter_font_weight_choices() ) ? $value : '';
}

/* Registra un campo colore: ora è un menu che salva un riferimento a uno slot "Colori con nome" ("slot_1".."slot_8"), non un esadecimale diretto. */
function starter_register_color_control( $wp_customize, $id, $label, &$priority, $description = '' ) {
	$wp_customize->add_setting(
		$id,
		array(
			'default'           => '',
			'sanitize_callback' => 'starter_sanitize_named_color_ref',
		)
	);

	$starter_args = array(
		'type'     => 'select',
		'section'  => starter_colors_section(),
		'label'    => $label,
		'choices'  => starter_named_color_choices(),
		'priority' => $priority++,
	);

	if ( $description ) {
		$starter_args['description'] = $description;
	}

	$wp_customize->add_control( $id, $starter_args );
}

/* Palette del tema: slot => [ nome, esadecimale, slug del preset in theme.json ]. Stessi valori già in CSS/starter.css (:root), solo resi modificabili da "Colori con nome" invece che fissi. */
const STARTER_COLOR_SLOTS = 10; // I primi 5 sempre visibili, gli altri a comparsa con "+ Aggiungi colore" (vedi JS/customize-controls.js).

/* Pannello del Customizer in cui finiscono i controlli colore registrati da qui in poi (cambia a ogni gruppo: sfondo/testi, pulsanti, menu/footer, tag). */
function starter_colors_section( $set = null ) {
	static $current = 'starter_options_colors';

	if ( null !== $set ) {
		$current = $set;
	}

	return $current;
}

/* Palette colori di partenza (nome, esadecimale, slug) */
function starter_default_palette() {
	return array(
		1 => array( 'Principale', '#0073aa', 'principale' ),
		2 => array( 'Secondario', '#1a1a1a', 'secondario' ),
		3 => array( 'Testo', '#1a1a1a', 'testo' ),
		4 => array( 'In risalto', '#c6ff00', 'in-risalto' ),
		5 => array( 'Sfondo', '#ffffff', 'sfondo' ),
	);
}

/* Slug del preset di uno slot: fisso per i colori di base (li usa CSS/starter.css), "starter-named-N" per quelli aggiunti dall'utente. */
function starter_palette_slug( $slot ) {
	$palette = starter_default_palette();

	return isset( $palette[ $slot ] ) ? $palette[ $slot ][2] : 'starter-named-' . (int) $slot;
}

/* Campo colore del Customizer => slot di base che rappresenta (vuoto = nessun default, resta il valore fisso già in CSS). */
function starter_color_field_defaults() {
	return array(
		'starter_color_background'              => 'slot_5',
		'starter_color_text'                    => 'slot_3',
		'starter_color_btn_primary'             => 'slot_1',
		'starter_color_btn_primary_hover'       => 'slot_4',
		'starter_color_btn_primary_text'        => 'slot_5',
		'starter_color_btn_primary_text_hover'  => 'slot_3',
		'starter_color_btn_secondary'           => 'slot_2',
		'starter_color_btn_secondary_hover'     => 'slot_4',
		'starter_color_btn_secondary_text'      => 'slot_2',
		'starter_color_btn_secondary_text_hover' => 'slot_3',
		'starter_color_menu'                    => 'slot_3',
		'starter_color_menu_text'               => 'slot_5',
		'starter_color_menu_text_hover'         => 'slot_3',
		'starter_color_link'                    => 'slot_1',
		'starter_color_link_hover'              => 'slot_1',
	);
}

/* Riferimento (slot_N) salvato per un campo colore, o quello di base se non è mai stato toccato. */
function starter_color_ref( $field ) {
	$defaults = starter_color_field_defaults();

	return get_theme_mod( $field, isset( $defaults[ $field ] ) ? $defaults[ $field ] : '' );
}

/* Colori con nome attivi (16 slot possibili, i primi 5 già precompilati coi colori di base del tema): attivo solo chi ha un valore; nome vuoto → "Colore N" come ripiego. */
function starter_get_named_colors() {
	$starter_colors = array();
	$starter_palette = starter_default_palette();

	for ( $starter_i = 1; $starter_i <= STARTER_COLOR_SLOTS; $starter_i++ ) {
		$starter_value = get_theme_mod( "starter_named_color_{$starter_i}_value", isset( $starter_palette[ $starter_i ] ) ? $starter_palette[ $starter_i ][1] : '' );

		if ( ! $starter_value ) {
			continue;
		}

		$starter_name = get_theme_mod( "starter_named_color_{$starter_i}_name", isset( $starter_palette[ $starter_i ] ) ? $starter_palette[ $starter_i ][0] : '' );

		$starter_colors[ "slot_{$starter_i}" ] = array(
			/* translators: %d: numero del colore, solo come ripiego se il campo "nome" è vuoto. */
			'name'  => $starter_name ? $starter_name : sprintf( __( 'Colore %d', 'slug-theme' ), $starter_i ),
			'value' => $starter_value,
		);
	}

	return $starter_colors;
}

/* Scelte tendina campo colore: "— Non impostato —" (= colore base tema) più un'opzione per ogni colore con nome attivo. */
function starter_named_color_choices() {
	$starter_choices = array( '' => __( '— Non impostato —', 'slug-theme' ) );

	foreach ( starter_get_named_colors() as $starter_slot => $starter_data ) {
		$starter_choices[ $starter_slot ] = $starter_data['name'];
	}

	return $starter_choices;
}

/* Accetta solo riferimenti a slot "Colori con nome" attivi; altrimenti vuoto, per non lasciare riferimenti rotti. */
function starter_sanitize_named_color_ref( $value ) {
	return array_key_exists( $value, starter_named_color_choices() ) ? $value : '';
}

/* Risolve un riferimento "slot_N" nel colore esadecimale vero, usata da starter_colors_css(). */
function starter_resolve_named_color( $starter_ref ) {
	if ( ! $starter_ref ) {
		return '';
	}

	$starter_colors = starter_get_named_colors();

	return isset( $starter_colors[ $starter_ref ] ) ? $starter_colors[ $starter_ref ]['value'] : '';
}

/* Migrazione una tantum da esadecimali diretti a slot (dedup, un solo run: flag "starter_named_colors_migrated" evita di sovrascrivere modifiche successive). */
function starter_maybe_migrate_named_colors() {
	if ( get_theme_mod( 'starter_named_colors_migrated', false ) ) {
		return;
	}

	$starter_legacy_fields = array(
		'starter_color_background',
		'starter_color_text',
		'starter_color_heading_primary',
		'starter_color_heading_secondary',
		'starter_color_btn_primary',
		'starter_color_btn_primary_hover',
		'starter_color_btn_primary_text',
		'starter_color_btn_primary_text_hover',
		'starter_color_btn_secondary',
		'starter_color_btn_secondary_hover',
		'starter_color_btn_secondary_text',
		'starter_color_btn_secondary_text_hover',
		'starter_color_menu',
		'starter_color_menu_hover',
		'starter_color_menu_text',
		'starter_color_menu_text_hover',
		'starter_color_footer_form',
		'starter_color_footer_form_hover',
		'starter_color_footer_form_text',
		'starter_color_footer_form_text_hover',
		'starter_color_social',
		'starter_color_social_hover',
		'starter_color_social_text_hover',
		'starter_color_link',
		'starter_color_link_hover',
		'starter_color_tag',
		'starter_color_tag_hover',
	);

	// Stessi nomi default dei primi 4 slot in customize_register(), invece del generico "Colore migrato N".
	$starter_default_names = array(
		1 => __( 'Principale', 'slug-theme' ),
		2 => __( 'Secondario', 'slug-theme' ),
		3 => __( 'Testo', 'slug-theme' ),
		4 => __( 'In risalto', 'slug-theme' ),
	);

	$starter_used_slots = array();

	for ( $starter_i = 1; $starter_i <= STARTER_COLOR_SLOTS; $starter_i++ ) {
		if ( get_theme_mod( "starter_named_color_{$starter_i}_value", '' ) ) {
			$starter_used_slots[ $starter_i ] = true;
		}
	}

	$starter_hex_to_slot = array();
	$starter_next_slot   = 1;

	foreach ( $starter_legacy_fields as $starter_field ) {
		$starter_hex = get_theme_mod( $starter_field, '' );

		if ( ! $starter_hex || ! preg_match( '/^#[0-9a-f]{3,8}$/i', $starter_hex ) ) {
			continue;
		}

		if ( ! isset( $starter_hex_to_slot[ $starter_hex ] ) ) {
			while ( $starter_next_slot <= STARTER_COLOR_SLOTS && isset( $starter_used_slots[ $starter_next_slot ] ) ) {
				$starter_next_slot++;
			}

			if ( $starter_next_slot > STARTER_COLOR_SLOTS ) {
				// Oltre 8 colori diversi (caso limite): campo svuotato invece di un riferimento rotto.
				continue;
			}

			$starter_used_slots[ $starter_next_slot ] = true;
			$starter_hex_to_slot[ $starter_hex ]      = "slot_{$starter_next_slot}";

			set_theme_mod( "starter_named_color_{$starter_next_slot}_value", $starter_hex );

			if ( ! get_theme_mod( "starter_named_color_{$starter_next_slot}_name", '' ) ) {
				set_theme_mod(
					"starter_named_color_{$starter_next_slot}_name",
					isset( $starter_default_names[ $starter_next_slot ] )
						? $starter_default_names[ $starter_next_slot ]
						/* translators: %d: numero del colore. */
						: sprintf( __( 'Colore migrato %d', 'slug-theme' ), $starter_next_slot )
				);
			}

			$starter_next_slot++;
		}

		set_theme_mod( $starter_field, isset( $starter_hex_to_slot[ $starter_hex ] ) ? $starter_hex_to_slot[ $starter_hex ] : '' );
	}

	set_theme_mod( 'starter_named_colors_migrated', true );
}
add_action( 'after_setup_theme', 'starter_maybe_migrate_named_colors', 20 );

/* Versione header: valore ammesso o fallback a "extended". */
function starter_sanitize_header_style( $value ) {
	return in_array( $value, array( 'extended', 'standard', 'minimal', 'pills' ), true ) ? $value : 'extended';
}

/* Descrizione tema nel footer: testo semplice troncato a 180 caratteri. */
function starter_sanitize_footer_description( $value ) {
	return mb_substr( sanitize_textarea_field( $value ), 0, 180 );
}

/* Whitelist HTML ristretta per il testo privacy footer, condivisa da sanitizzazione e stampa per restare coerenti. */
function starter_footer_consent_allowed_html() {
	return array(
		'a'      => array(
			'href'   => true,
			'class'  => true,
			'target' => true,
			'rel'    => true,
			'title'  => true,
		),
		'strong' => array( 'class' => true ),
		'em'     => array( 'class' => true ),
		'span'   => array( 'class' => true ),
	);
}

/* Testo accettazione privacy: solo i tag/attributi whitelistati, resto rimosso da wp_kses(). */
function starter_sanitize_footer_consent_text( $value ) {
	return wp_kses( $value, starter_footer_consent_allowed_html() );
}

/* Voci contatti footer: stessa whitelist HTML del testo privacy, per link/classi oltre al testo semplice. */
function starter_sanitize_footer_contacts( $value ) {
	return wp_kses( $value, starter_footer_consent_allowed_html() );
}

/* Classi CSS dei link footer (per agganciare script esterni come Iubenda): sanitizzate una per una. */
function starter_sanitize_css_classes( $value ) {
	$classes = preg_split( '/\s+/', trim( (string) $value ) );
	$classes = array_map( 'sanitize_html_class', $classes );
	$classes = array_filter( $classes );

	return implode( ' ', $classes );
}

/* Elenco selezionato a mano (non tutti i 1400+ Google Fonts), non usato direttamente dal menu (serve il prefisso "google:"). */
function starter_google_fonts_list() {
	$fonts = array(
		'Abril Fatface',
		'Anton',
		'Archivo',
		'Arvo',
		'Assistant',
		'Barlow',
		'Bebas Neue',
		'Bitter',
		'Cabin',
		'Caveat',
		'Comfortaa',
		'Cormorant Garamond',
		'Crimson Text',
		'Dancing Script',
		'DM Sans',
		'Domine',
		'EB Garamond',
		'Exo 2',
		'Fira Sans',
		'Fjalla One',
		'Heebo',
		'Hind',
		'IBM Plex Sans',
		'IBM Plex Serif',
		'Inter',
		'JetBrains Mono',
		'Josefin Sans',
		'Karla',
		'Lato',
		'Libre Baskerville',
		'Lora',
		'Manrope',
		'Merriweather',
		'Montserrat',
		'Mukta',
		'Noto Sans',
		'Nunito',
		'Open Sans',
		'Oswald',
		'Outfit',
		'Overpass',
		'Pacifico',
		'Permanent Marker',
		'Playfair Display',
		'Plus Jakarta Sans',
		'Poppins',
		'PT Sans',
		'PT Serif',
		'Quicksand',
		'Raleway',
		'Righteous',
		'Roboto',
		'Roboto Mono',
		'Rubik',
		'Shadows Into Light',
		'Sora',
		'Source Code Pro',
		'Source Sans Pro',
		'Space Grotesk',
		'Space Mono',
		'Teko',
		'Titillium Web',
		'Ubuntu',
		'Vollkorn',
		'Work Sans',
		'Zilla Slab',
	);

	return array_combine( $fonts, $fonts );
}

/* Font di sistema: valore è già lo stack "font-family" con fallback, non solo il nome nudo. */
function starter_system_fonts_list() {
	return array(
		'Arial'              => "Arial, Helvetica, sans-serif",
		'Helvetica'          => "Helvetica, Arial, sans-serif",
		'Verdana'            => "Verdana, Geneva, sans-serif",
		'Tahoma'             => "Tahoma, Geneva, sans-serif",
		'Trebuchet MS'       => "'Trebuchet MS', Helvetica, sans-serif",
		'Segoe UI'           => "'Segoe UI', Tahoma, sans-serif",
		'System UI (nativo)' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
		'Georgia'            => 'Georgia, "Times New Roman", serif',
		'Times New Roman'    => "'Times New Roman', Times, serif",
		'Palatino'           => 'Palatino, "Palatino Linotype", serif',
		'Garamond'           => "Garamond, Baskerville, serif",
		'Courier New'        => "'Courier New', Courier, monospace",
		'Lucida Console'     => "'Lucida Console', Monaco, monospace",
	);
}

/* Scelte gruppo "Font di sistema": valore con prefisso "system:" => nome mostrato. */
function starter_system_fonts_choices() {
	$choices = array();

	foreach ( array_keys( starter_system_fonts_list() ) as $starter_name ) {
		$choices[ "system:{$starter_name}" ] = $starter_name;
	}

	return $choices;
}

/* Scelte gruppo "Google Fonts": valore con prefisso "google:" => nome mostrato. */
function starter_google_fonts_choices() {
	$choices = array();

	foreach ( array_keys( starter_google_fonts_list() ) as $starter_name ) {
		$choices[ "google:{$starter_name}" ] = $starter_name;
	}

	return $choices;
}

/* Font 1/2: valida "system:{nome}"/"google:{nome}" o vuoto; non ripiega su un default come altrove, invalido = vuoto. */
function starter_sanitize_typography_font_choice( $value ) {
	if ( '' === $value ) {
		return '';
	}

	if ( 0 === strpos( $value, 'system:' ) ) {
		$name = substr( $value, strlen( 'system:' ) );
		return array_key_exists( $name, starter_system_fonts_list() ) ? $value : '';
	}

	if ( 0 === strpos( $value, 'google:' ) ) {
		$name = substr( $value, strlen( 'google:' ) );
		return array_key_exists( $name, starter_google_fonts_list() ) ? $value : '';
	}

	return '';
}

/* Pesi per "Variante N — peso" di Font 3/4: valore = "font-weight" per la relativa @font-face. */
function starter_font_weight_choices() {
	return array(
		'100' => __( 'Thin (100)', 'slug-theme' ),
		'200' => __( 'Extra Light (200)', 'slug-theme' ),
		'300' => __( 'Light (300)', 'slug-theme' ),
		'400' => __( 'Regular (400)', 'slug-theme' ),
		'500' => __( 'Medium (500)', 'slug-theme' ),
		'600' => __( 'SemiBold (600)', 'slug-theme' ),
		'700' => __( 'Bold (700)', 'slug-theme' ),
		'800' => __( 'Extra Bold (800)', 'slug-theme' ),
		'900' => __( 'Black (900)', 'slug-theme' ),
	);
}

/* "Variante N — peso": valore valido o default "400" — qui non esiste "vuoto", una variante ha sempre un peso. */
function starter_sanitize_font_weight( $value ) {
	return array_key_exists( $value, starter_font_weight_choices() ) ? $value : '400';
}

/* Stili per "Variante N — stile": separato dal peso apposta, così il corsivo non si nasconde tra 16 combinazioni. */
function starter_font_style_choices() {
	return array(
		'normal' => __( 'Normale', 'slug-theme' ),
		'italic' => __( 'Corsivo', 'slug-theme' ),
	);
}

/* "Variante N — stile": "normal" o "italic", altrimenti default "normal". */
function starter_sanitize_font_style( $value ) {
	return array_key_exists( $value, starter_font_style_choices() ) ? $value : 'normal';
}

/* Consente upload font (TTF/WOFF/WOFF2/EOT) per Font 3/4, solo a chi può modificare le opzioni tema. */
function starter_allow_font_upload_mimes( $mimes ) {
	if ( current_user_can( 'edit_theme_options' ) ) {
		$mimes['ttf']   = 'font/ttf';
		$mimes['woff']  = 'font/woff';
		$mimes['woff2'] = 'font/woff2';
		$mimes['eot']   = 'application/vnd.ms-fontobject';
	}

	return $mimes;
}
add_filter( 'upload_mimes', 'starter_allow_font_upload_mimes' );

/* Stesso problema di starter_fix_svg_filetype_check() ma per i 4 formati font: whitelist esplicita in wp_check_filetype_and_ext. */
function starter_fix_font_filetype_check( $data, $file, $filename, $mimes ) {
	if ( ! current_user_can( 'edit_theme_options' ) || $data['type'] ) {
		return $data;
	}

	$font_mime_types = array(
		'ttf'   => 'font/ttf',
		'woff'  => 'font/woff',
		'woff2' => 'font/woff2',
		'eot'   => 'application/vnd.ms-fontobject',
	);

	$filetype = wp_check_filetype( $filename, $mimes );

	if ( isset( $font_mime_types[ $filetype['ext'] ] ) ) {
		$data['ext']             = $filetype['ext'];
		$data['type']            = $font_mime_types[ $filetype['ext'] ];
		$data['proper_filename'] = $filename;
	}

	return $data;
}
add_filter( 'wp_check_filetype_and_ext', 'starter_fix_font_filetype_check', 10, 4 );

/* Font attivi dei 4 slot tipografia, condiviso da CSS e enqueue Google Fonts per restare coerenti. */
function starter_get_active_typography_fonts() {
	$active = array();

	$selector_map = array(
		'h1'   => 'h1',
		'h2'   => 'h2',
		'h3'   => 'h3',
		'h4'   => 'h4',
		'h5'   => 'h5',
		'h6'   => 'h6',
		'body' => 'body',
	);

	// Font 1 e Font 2: Google Fonts o font di sistema.
	foreach ( array( 1, 2 ) as $n ) {
		$choice = get_theme_mod( "starter_font{$n}_family", '' );

		if ( ! $choice ) {
			continue;
		}

		if ( 0 === strpos( $choice, 'system:' ) ) {
			$type       = 'system';
			$name       = substr( $choice, strlen( 'system:' ) );
			$system     = starter_system_fonts_list();
			$family_css = $system[ $name ] ?? $name;
		} else {
			$type       = 'google';
			$name       = substr( $choice, strlen( 'google:' ) );
			$family_css = "'" . $name . "'";
		}

		$selectors = array();

		foreach ( $selector_map as $key => $selector ) {
			if ( get_theme_mod( "starter_font{$n}_{$key}", false ) ) {
				$selectors[] = $selector;
			}
		}

		$active[] = array(
			'type'       => $type,
			'family'     => $name,
			'family_css' => $family_css,
			'selectors'  => $selectors,
		);
	}

	// Font 3 e Font 4: caricati dall'amministratore, fino a 4 varianti (peso/stile) ciascuno.
	$formats = array(
		'woff2' => 'woff2',
		'woff'  => 'woff',
		'ttf'   => 'truetype',
		'eot'   => 'embedded-opentype',
		'svg'   => 'svg',
	);

	foreach ( array( 3, 4 ) as $n ) {
		$name = trim( (string) get_theme_mod( "starter_font{$n}_name", '' ) );

		if ( '' === $name ) {
			continue;
		}

		$variants = array();

		foreach ( array( 1, 2, 3, 4 ) as $v ) {
			$sources = array();

			foreach ( $formats as $ext => $format ) {
				$attachment_id = (int) get_theme_mod( "starter_font{$n}_v{$v}_{$ext}", 0 );

				if ( $attachment_id ) {
					$url = wp_get_attachment_url( $attachment_id );

					if ( $url ) {
						$sources[ $format ] = $url;
					}
				}
			}

			if ( ! $sources ) {
				continue;
			}

			$variants[] = array(
				'weight'  => get_theme_mod( "starter_font{$n}_v{$v}_weight", '400' ),
				'style'   => get_theme_mod( "starter_font{$n}_v{$v}_style", 'normal' ),
				'sources' => $sources,
			);
		}

		if ( ! $variants ) {
			continue;
		}

		$selectors = array();

		foreach ( $selector_map as $key => $selector ) {
			if ( get_theme_mod( "starter_font{$n}_{$key}", false ) ) {
				$selectors[] = $selector;
			}
		}

		$active[] = array(
			'type'       => 'upload',
			'family'     => $name,
			'family_css' => "'" . $name . "'",
			'selectors'  => $selectors,
			'variants'   => $variants,
		);
	}

	return $active;
}

/* CSS dei font attivi: fallback var(--font-body) salvo font di sistema; se un elemento è in più slot vince l'ultimo (Font 4 > 3 > 2 > 1) per ordine di cascade. */
function starter_typography_css() {
	$fonts = starter_get_active_typography_fonts();

	if ( ! $fonts ) {
		return '';
	}

	$css = '';

	foreach ( $fonts as $font ) {
		if ( 'upload' !== $font['type'] ) {
			continue;
		}

		foreach ( $font['variants'] as $variant ) {
			$src = array();

			foreach ( $variant['sources'] as $format => $url ) {
				$src[] = sprintf( "url('%s') format('%s')", esc_url_raw( $url ), $format );
			}

			$css .= sprintf(
				"@font-face{font-family:'%s';src:%s;font-weight:%s;font-style:%s;font-display:swap;}\n",
				esc_attr( $font['family'] ),
				implode( ',', $src ),
				esc_attr( $variant['weight'] ),
				esc_attr( $variant['style'] )
			);
		}
	}

	foreach ( $fonts as $font ) {
		if ( ! $font['selectors'] ) {
			continue;
		}

		$css .= sprintf(
			"%s{font-family:%s,var(--font-body);}\n",
			implode( ',', $font['selectors'] ),
			$font['family_css']
		);
	}

	return $css;
}

/* Inline il CSS tipografia (niente richiesta HTTP extra) e, se serve, un unico <link> Google Fonts per tutti gli slot attivi. */
function starter_enqueue_typography_assets() {
	$fonts = starter_get_active_typography_fonts();

	if ( ! $fonts ) {
		return;
	}

	$google_families = array();

	foreach ( $fonts as $font ) {
		if ( 'google' === $font['type'] ) {
			// "100..900" invece di elenco fisso: Google Fonts ignora da sé i pesi inesistenti, il browser scarica solo quelli usati.
			$google_families[] = 'family=' . rawurlencode( $font['family'] ) . ':wght@100..900';
		}
	}

	if ( $google_families ) {
		wp_enqueue_style(
			'starter-typography-google-fonts',
			'https://fonts.googleapis.com/css2?' . implode( '&', $google_families ) . '&display=swap',
			array(),
			null
		);
	}

	$css = starter_typography_css();

	if ( $css ) {
		wp_add_inline_style( 'starter-theme-style', $css );
	}
}
add_action( 'wp_enqueue_scripts', 'starter_enqueue_typography_assets', 11 );

/* Raggio unico degli angoli scelto in Opzioni → Generale → Bordi del sito (px). Di base: Medi (20). */
function starter_corner_radius() {
	$choice = get_theme_mod( 'starter_corner_radius', 'md' );
	$sizes  = array(
		'none' => 0,
		'sm'   => 14,
		'md'   => 20,
		'lg'   => 24,
		'xl'   => 28,
	);

	if ( isset( $sizes[ $choice ] ) ) {
		return $sizes[ $choice ];
	}

	if ( 'custom' === $choice ) {
		return max( 0, min( 80, (int) get_theme_mod( 'starter_corner_radius_custom', 12 ) ) );
	}

	return 20; // Medi (valore salvato vuoto o non più valido).
}

/* CSS Opzioni→Colori: campo vuoto = nessuna regola; H1-H6 usano selettore diretto a bassa specificità (non variabile) così i colori inline di Gutenberg vincono sempre. */
function starter_colors_css() {
	$css = '';

	// Ogni campo salva un riferimento a "Colori con nome" (slot_1..8); starter_resolve_named_color() lo risolve o torna vuoto.
	$starter_root_vars = array(
		'--color-background'        => starter_resolve_named_color( starter_color_ref( 'starter_color_background' ) ),
		'--color-text'               => starter_resolve_named_color( starter_color_ref( 'starter_color_text' ) ),
		'--color-primary'            => starter_resolve_named_color( starter_color_ref( 'starter_color_btn_primary' ) ),
		'--color-accent-cta'         => starter_resolve_named_color( starter_color_ref( 'starter_color_btn_primary_hover' ) ),
		'--color-primary-text'       => starter_resolve_named_color( starter_color_ref( 'starter_color_btn_primary_text' ) ),
		'--color-primary-text-hover' => starter_resolve_named_color( starter_color_ref( 'starter_color_btn_primary_text_hover' ) ),
		'--color-secondary'          => starter_resolve_named_color( starter_color_ref( 'starter_color_btn_secondary' ) ),
		'--color-accent-secondary'   => starter_resolve_named_color( starter_color_ref( 'starter_color_btn_secondary_hover' ) ),
		'--color-secondary-text'     => starter_resolve_named_color( starter_color_ref( 'starter_color_btn_secondary_text' ) ),
		'--color-secondary-text-hover' => starter_resolve_named_color( starter_color_ref( 'starter_color_btn_secondary_text_hover' ) ),
		// Pulsanti: i campi in più di "Pieno 1" e "Contorno 1" e le varianti 2 (sezione Opzioni → Pulsanti). Vuoto = valore di base in CSS/starter.css.
		'--btn-f1-border'            => starter_resolve_named_color( get_theme_mod( 'starter_color_btn_primary_border', '' ) ),
		'--btn-f1-border-hover'      => starter_resolve_named_color( get_theme_mod( 'starter_color_btn_primary_border_hover', '' ) ),
		'--btn-o1-bg'                => starter_resolve_named_color( get_theme_mod( 'starter_color_btn_secondary_bg', '' ) ),
		'--btn-o1-border-hover'      => starter_resolve_named_color( get_theme_mod( 'starter_color_btn_secondary_border_hover', '' ) ),
		'--btn-f2-bg'                => starter_resolve_named_color( get_theme_mod( 'starter_color_btn_fill2', '' ) ),
		'--btn-f2-bg-hover'          => starter_resolve_named_color( get_theme_mod( 'starter_color_btn_fill2_hover', '' ) ),
		'--btn-f2-text'              => starter_resolve_named_color( get_theme_mod( 'starter_color_btn_fill2_text', '' ) ),
		'--btn-f2-text-hover'        => starter_resolve_named_color( get_theme_mod( 'starter_color_btn_fill2_text_hover', '' ) ),
		'--btn-f2-border'            => starter_resolve_named_color( get_theme_mod( 'starter_color_btn_fill2_border', '' ) ),
		'--btn-f2-border-hover'      => starter_resolve_named_color( get_theme_mod( 'starter_color_btn_fill2_border_hover', '' ) ),
		'--btn-o2-border'            => starter_resolve_named_color( get_theme_mod( 'starter_color_btn_outline2', '' ) ),
		'--btn-o2-bg-hover'          => starter_resolve_named_color( get_theme_mod( 'starter_color_btn_outline2_hover', '' ) ),
		'--btn-o2-text'              => starter_resolve_named_color( get_theme_mod( 'starter_color_btn_outline2_text', '' ) ),
		'--btn-o2-text-hover'        => starter_resolve_named_color( get_theme_mod( 'starter_color_btn_outline2_text_hover', '' ) ),
		'--btn-o2-bg'                => starter_resolve_named_color( get_theme_mod( 'starter_color_btn_outline2_bg', '' ) ),
		'--btn-o2-border-hover'      => starter_resolve_named_color( get_theme_mod( 'starter_color_btn_outline2_border_hover', '' ) ),
		'--color-menu'               => starter_resolve_named_color( starter_color_ref( 'starter_color_menu' ) ),
		'--color-accent-menu'        => starter_resolve_named_color( get_theme_mod( 'starter_color_menu_hover', '' ) ),
		'--color-menu-text'          => starter_resolve_named_color( starter_color_ref( 'starter_color_menu_text' ) ),
		'--color-menu-text-hover'    => starter_resolve_named_color( starter_color_ref( 'starter_color_menu_text_hover' ) ),
		// "--color-social" non ha default: se vuoto, var(--color-social, currentColor) ripiega sul colore del contesto.
		'--color-social'             => starter_resolve_named_color( get_theme_mod( 'starter_color_social', '' ) ),
		'--color-accent-social'      => starter_resolve_named_color( get_theme_mod( 'starter_color_social_hover', '' ) ),
		'--color-social-text-hover'  => starter_resolve_named_color( get_theme_mod( 'starter_color_social_text_hover', '' ) ),
		'--color-link'               => starter_resolve_named_color( starter_color_ref( 'starter_color_link' ) ),
		'--color-link-hover'         => starter_resolve_named_color( starter_color_ref( 'starter_color_link_hover' ) ),
		'--color-tag'                => starter_resolve_named_color( get_theme_mod( 'starter_color_tag', '' ) ),
		'--color-tag-hover'          => starter_resolve_named_color( get_theme_mod( 'starter_color_tag_hover', '' ) ),
	);

	// I moduli (es. la barra annunci) aggiungono le proprie variabili.
	$starter_root_vars = apply_filters( 'starter_colors_css_root_vars', $starter_root_vars );

	$starter_root_css = '';

	foreach ( $starter_root_vars as $starter_var => $starter_value ) {
		if ( $starter_value ) {
			$starter_root_css .= $starter_var . ':' . esc_attr( $starter_value ) . ';';
		}
	}

	// Angoli: scelta unica per card, tab, immagini e riquadri (Opzioni → Generale → Bordi del sito) e arrotondamento dei pulsanti (Opzioni → Pulsanti).
	$starter_corner = starter_corner_radius();

	foreach ( array( '--radius-sm', '--radius-md', '--radius-lg', '--radius-xl', '--radius-section', '--radius-button' ) as $starter_radius_var ) {
		$starter_root_css .= $starter_radius_var . ':' . $starter_corner . 'px;';
	}

	$starter_btn_radius = get_theme_mod( 'starter_button_radius', '' );

	if ( 'custom' === $starter_btn_radius ) {
		$starter_btn_radius = max( 0, min( 999, (int) get_theme_mod( 'starter_button_radius_custom', 12 ) ) );
	}

	if ( '' !== (string) $starter_btn_radius && is_numeric( $starter_btn_radius ) ) {
		$starter_root_css .= '--radius-btn:' . (int) $starter_btn_radius . 'px;';
	}

	if ( get_theme_mod( 'starter_button_uppercase', false ) ) {
		$starter_root_css .= '--btn-transform:uppercase;';
	}

	// Dimensione e padding dei pulsanti (Opzioni → Pulsanti): ridefiniscono la misura "Pulsanti" del tema, quindi valgono ovunque.
	$starter_btn_vars = '';
	$starter_btn_size = get_theme_mod( 'starter_button_size', '' );
	$starter_btn_sets = array(
		'small'  => '--wp--preset--font-size--button:0.8125rem;--btn-h:40px;--btn-px:1.25rem;',
		'xsmall' => '--wp--preset--font-size--button:0.75rem;--btn-h:34px;--btn-px:1rem;',
	);

	if ( isset( $starter_btn_sets[ $starter_btn_size ] ) ) {
		$starter_btn_vars .= $starter_btn_sets[ $starter_btn_size ];
	}

	$starter_pad_y = get_theme_mod( 'starter_button_pad_y', '' );
	$starter_pad_x = get_theme_mod( 'starter_button_pad_x', '' );

	if ( '' !== (string) $starter_pad_y && is_numeric( $starter_pad_y ) ) {
		$starter_btn_vars .= '--btn-h:auto;--btn-py:' . (int) $starter_pad_y . 'px;';
	}

	if ( '' !== (string) $starter_pad_x && is_numeric( $starter_pad_x ) ) {
		$starter_btn_vars .= '--btn-px:' . (int) $starter_pad_x . 'px;';
	}

	if ( $starter_btn_vars ) {
		$css .= 'html:root{' . $starter_btn_vars . "}\n";
	}

	// Allineamento della paginazione (Opzioni → Generale): vale per blog, immobili, portfolio, shop e i prossimi elenchi. Vuoto = come prima.
	$starter_pagination = get_theme_mod( 'starter_pagination_align', '' );

	if ( in_array( $starter_pagination, array( 'left', 'center', 'right' ), true ) ) {
		$starter_justify   = array( 'left' => 'flex-start', 'center' => 'center', 'right' => 'flex-end' );
		$starter_root_css .= '--pagination-justify:' . $starter_justify[ $starter_pagination ] . ';--pagination-align:' . $starter_pagination . ';';
	}

	if ( $starter_root_css ) {
		$css .= ':root{' . $starter_root_css . "}\n";
	}

	// Checkbox "mantieni bordo tag uguale in hover": se spuntato, punta al colore a riposo invece che hover.
	if ( get_theme_mod( 'starter_color_tag_keep_border', false ) ) {
		$css .= ':root{--color-tag-hover-border:var(--color-tag);}' . "\n";
	}

	// Variabili footer (form + sfondo/testo/hover) scoped in ".site-footer", non nel :root globale.
	$starter_footer_form_vars = array(
		'--footer-form-accent'       => starter_resolve_named_color( get_theme_mod( 'starter_color_footer_form', '' ) ),
		'--footer-form-accent-hover' => starter_resolve_named_color( get_theme_mod( 'starter_color_footer_form_hover', '' ) ),
		'--footer-form-text'         => starter_resolve_named_color( get_theme_mod( 'starter_color_footer_form_text', '' ) ),
		'--footer-form-text-hover'   => starter_resolve_named_color( get_theme_mod( 'starter_color_footer_form_text_hover', '' ) ),
		'--footer-bg'                => starter_resolve_named_color( get_theme_mod( 'starter_color_footer_bg', '' ) ),
		'--footer-text'              => starter_resolve_named_color( get_theme_mod( 'starter_color_footer_text', '' ) ),
		'--footer-link-hover'        => starter_resolve_named_color( get_theme_mod( 'starter_color_footer_link_hover', '' ) ),
		'--footer-current-page'      => starter_resolve_named_color( get_theme_mod( 'starter_color_footer_current_page', '' ) ),
	);

	$starter_footer_form_css = '';

	foreach ( $starter_footer_form_vars as $starter_var => $starter_value ) {
		if ( $starter_value ) {
			$starter_footer_form_css .= $starter_var . ':' . esc_attr( $starter_value ) . ';';
		}
	}

	if ( $starter_footer_form_css ) {
		$css .= '.site-footer{' . $starter_footer_form_css . "}\n";
	}

	// Sottolineatura link "semplici": header/footer/pulsanti già hanno regole più specifiche che vincono; checkbox sempre stampato (no stato vuoto).
	$css .= 'a{text-decoration:' . ( get_theme_mod( 'starter_color_link_underline', true ) ? 'underline' : 'none' ) . ";}\n";

	// Bersagli condivisi da Colore principale/secondario/link: h1-h6 + pagina corrente/menu hover/footer link. Varianti "hover" usano il colore hover del gruppo se esiste, altrimenti l'unico colore.
	$starter_color_targets = array(
		'h1'                => array( 'selector' => 'h1' ),
		'h2'                => array( 'selector' => 'h2' ),
		'h3'                => array( 'selector' => 'h3' ),
		'h4'                => array( 'selector' => 'h4' ),
		'h5'                => array( 'selector' => 'h5' ),
		'h6'                => array( 'selector' => 'h6' ),
		// Solo header: il footer ha il proprio campo "--footer-current-page" per restare indipendente. hover=true perché la pagina corrente è più simile a uno stato hover permanente.
		'current_page'      => array(
			'selector' => '.main-navigation .current_page_item > a, .main-navigation .current-menu-item > a',
			'hover'    => true,
		),
		'menu_hover'        => array(
			'selector' => '.main-navigation a:hover, .main-navigation .sub-menu a:hover',
			'hover'    => true,
		),
		// Qualunque link nel footer tranne le icone social, che hanno un proprio sistema colori dedicato.
		'footer_link'       => array( 'selector' => '.site-footer a:not(.footer-social__icon, .social-links a)' ),
		'footer_link_hover' => array(
			'selector' => '.site-footer a:not(.footer-social__icon, .social-links a):hover',
			'hover'    => true,
		),
	);

	$starter_color_groups = array(
		'primary'   => array(
			'setting_prefix' => 'starter_color_heading_primary',
			'rest'           => starter_resolve_named_color( get_theme_mod( 'starter_color_heading_primary', '' ) ),
		),
		'secondary' => array(
			'setting_prefix' => 'starter_color_heading_secondary',
			'rest'           => starter_resolve_named_color( get_theme_mod( 'starter_color_heading_secondary', '' ) ),
		),
		'link'      => array(
			'setting_prefix' => 'starter_color_link',
			'rest'           => starter_resolve_named_color( starter_color_ref( 'starter_color_link' ) ),
			'hover'          => starter_resolve_named_color( starter_color_ref( 'starter_color_link_hover' ) ),
		),
	);

	foreach ( $starter_color_groups as $starter_group ) {
		// Riposo e hover si coprono a vicenda: se è compilato solo uno dei due, si applica anche ai bersagli dell'altro tipo.
		$starter_rest_color  = $starter_group['rest'] ? $starter_group['rest'] : ( $starter_group['hover'] ?? '' );
		$starter_hover_color = ! empty( $starter_group['hover'] ) ? $starter_group['hover'] : $starter_group['rest'];

		if ( ! $starter_rest_color && ! $starter_hover_color ) {
			continue;
		}

		foreach ( $starter_color_targets as $starter_field => $starter_target ) {
			if ( ! get_theme_mod( "{$starter_group['setting_prefix']}_{$starter_field}", false ) ) {
				continue;
			}

			$starter_target_color = ! empty( $starter_target['hover'] ) ? $starter_hover_color : $starter_rest_color;

			if ( ! $starter_target_color ) {
				continue;
			}

			$css .= $starter_target['selector'] . '{color:' . esc_attr( $starter_target_color ) . ";}\n";
		}
	}

	return $css;
}

/* Inline style sullo stesso foglio della tipografia, niente richiesta HTTP extra. */
function starter_enqueue_colors_assets() {
	$css = starter_colors_css();

	if ( $css ) {
		wp_add_inline_style( 'starter-theme-style', $css );
	}
}
add_action( 'wp_enqueue_scripts', 'starter_enqueue_colors_assets', 11 );

/* Mappa chiave campo -> selettore CSS per Opzioni→Stile testi, condivisa con starter_typography_style_css(). */
function starter_text_style_elements_map() {
	return array(
		'display'      => '.title-display',
		'h1'           => 'h1, .title-h1',
		'h2'           => 'h2, .title-h2',
		'h3'           => 'h3, .title-h3',
		'h4'           => 'h4, .title-h4',
		'h5'           => 'h5, .title-h5',
		'h6'           => 'h6, .title-h6',
		'txt'          => '.txt',
		'size_xx_large' => '.has-xx-large-font-size',
		'size_x_large' => '.has-x-large-font-size',
		'size_large'   => '.has-large-font-size',
		'size_medium'  => '.has-medium-font-size',
		'size_small'   => '.has-small-font-size',
		'btn'          => '.button, .wp-block-button__link',
	);
}

/*
 * Elemento di Stile testi => preset di dimensione in theme.json (--wp--preset--font-size--{slug}).
 * I componenti del tema (shop, blog, header...) usano SOLO queste variabili: cambiando una dimensione in
 * Opzioni → Stile testi cambia ovunque, per desktop, tablet e mobile. Mai font-size scritti a mano nei CSS.
 */
function starter_text_style_presets() {
	return array(
		'display'      => 'display',
		'h1'           => 'title-1',
		'h2'           => 'title-2',
		'h3'           => 'title-3',
		'h4'           => 'title-4',
		'h5'           => 'title-5',
		'h6'           => 'eyebrow',
		'txt'          => 'medium',
		'size_xx_large' => 'xx-large',
		'size_x_large' => 'x-large',
		'size_large'   => 'large',
		'size_small'   => 'small',
		'btn'          => 'button',
	);
}

/* Dimensione di un preset di theme.json (la stessa che usa CSS/starter.css), es. 'title-1' => 'clamp(2.75rem, …)'. */
function starter_theme_json_font_size( $slug ) {
	static $map = null;

	if ( null === $map ) {
		$map  = array();
		$json = wp_json_file_decode( get_theme_file_path( 'theme.json' ), array( 'associative' => true ) );

		foreach ( (array) ( $json['settings']['typography']['fontSizes'] ?? array() ) as $starter_size ) {
			$map[ $starter_size['slug'] ] = $starter_size['size'];
		}
	}

	return isset( $map[ $slug ] ) ? $map[ $slug ] : '';
}

/*
 * Valori standard di Stile testi: dimensione = preset di theme.json (clamp, si adatta a ogni larghezza) e interlinea.
 * Sono quelli che compaiono già compilati nelle opzioni (solo Desktop) e che CSS/starter.css applica di base.
 */
function starter_text_style_default( $key, $field ) {
	$sizes = array(
		'display'       => 'display',
		'h1'            => 'title-1',
		'h2'            => 'title-2',
		'h3'            => 'title-3',
		'h4'            => 'title-4',
		'h5'            => 'title-5',
		'h6'            => 'eyebrow',
		'txt'           => 'medium',
		'size_xx_large' => 'xx-large',
		'size_x_large'  => 'x-large',
		'size_large'    => 'large',
		'size_medium'   => 'medium',
		'size_small'    => 'small',
		'btn'           => 'button',
	);
	$lines = array(
		'display'       => '1.05',
		'h1'            => '1.05',
		'h2'            => '1.1',
		'h3'            => '1.15',
		'h4'            => '1.2',
		'h5'            => '1.25',
		'h6'            => '1.3',
		'txt'           => '1.65',
		'size_xx_large' => '1.1',
		'size_x_large'  => '1.2',
		'size_large'    => '1.4',
		'size_medium'   => '1.55',
		'size_small'    => '1.5',
		'btn'           => '1.2',
	);

	if ( 'font_size' === $field ) {
		return isset( $sizes[ $key ] ) ? starter_theme_json_font_size( $sizes[ $key ] ) : '';
	}

	if ( 'line_height' === $field ) {
		return isset( $lines[ $key ] ) ? $lines[ $key ] : '';
	}

	// Nessuna spaziatura tra le lettere di base.
	if ( 'letter_spacing' === $field ) {
		return '0';
	}

	return '';
}

/* Dichiarazioni CSS per un elemento a un breakpoint: proprietà responsive + (solo desktop) font-weight/uppercase. */
function starter_text_style_declarations( $prefix, $breakpoint, $starter_key = '' ) {
	$declarations = '';

	// Le classi size native di WP usano già !important in global-styles-inline.css: serve anche qui per vincere.
	$starter_wp_size_classes = array( 'size_xx_large', 'size_x_large', 'size_large', 'size_medium', 'size_small' );

	foreach (
		array(
			'font_size'      => 'font-size',
			'line_height'    => 'line-height',
			'letter_spacing' => 'letter-spacing',
			'margin_bottom'  => 'margin-bottom',
		) as $starter_field => $starter_css_prop
	) {
		$starter_value   = get_theme_mod( "{$prefix}_{$starter_field}_{$breakpoint}", '' );
		$starter_default = 'desktop' === $breakpoint ? starter_text_style_default( $starter_key, $starter_field ) : '';

		// Un valore uguale a quello standard non si ripete: lo stampa già CSS/starter.css con le dimensioni di theme.json.
		if ( $starter_value && $starter_value !== $starter_default ) {
			$starter_important = ( 'font_size' === $starter_field && in_array( $starter_key, $starter_wp_size_classes, true ) ) ? ' !important' : '';
			$declarations      .= $starter_css_prop . ':' . esc_attr( $starter_value ) . $starter_important . ';';
		}
	}

	if ( 'desktop' === $breakpoint ) {
		$starter_weight = get_theme_mod( "{$prefix}_font_weight", '' );

		if ( $starter_weight ) {
			$declarations .= 'font-weight:' . esc_attr( $starter_weight ) . ';';
		}

		if ( get_theme_mod( "{$prefix}_uppercase", false ) ) {
			$declarations .= 'text-transform:uppercase;';
		}
	}

	return $declarations;
}

/* CSS Stile testi: regola desktop + media query tablet/mobile solo se compilati; campo vuoto eredita dal breakpoint più largo (cascata max-width). */
function starter_typography_style_css() {
	$desktop_css = '';
	$tablet_css  = '';
	$mobile_css  = '';

	foreach ( starter_text_style_elements_map() as $starter_key => $starter_selector ) {
		$starter_prefix = "starter_text_style_{$starter_key}";

		$starter_desktop_decls = starter_text_style_declarations( $starter_prefix, 'desktop', $starter_key );
		$starter_tablet_decls  = starter_text_style_declarations( $starter_prefix, 'tablet', $starter_key );
		$starter_mobile_decls  = starter_text_style_declarations( $starter_prefix, 'mobile', $starter_key );

		if ( $starter_desktop_decls ) {
			$desktop_css .= $starter_selector . '{' . $starter_desktop_decls . "}\n";
		}

		if ( $starter_tablet_decls ) {
			$tablet_css .= $starter_selector . '{' . $starter_tablet_decls . "}\n";
		}

		if ( $starter_mobile_decls ) {
			$mobile_css .= $starter_selector . '{' . $starter_mobile_decls . "}\n";
		}
	}

	// Le dimensioni dei preset di theme.json seguono le opzioni (per breakpoint): i componenti usano var(--wp--preset--font-size--...).
	$starter_vars = array( 'desktop' => '', 'tablet' => '', 'mobile' => '' );

	foreach ( starter_text_style_presets() as $starter_key => $starter_slug ) {
		foreach ( array_keys( $starter_vars ) as $starter_bp ) {
			$starter_size = get_theme_mod( "starter_text_style_{$starter_key}_font_size_{$starter_bp}", '' );

			if ( $starter_size && $starter_size !== ( 'desktop' === $starter_bp ? starter_text_style_default( $starter_key, 'font_size' ) : '' ) ) {
				$starter_vars[ $starter_bp ] .= '--wp--preset--font-size--' . $starter_slug . ':' . esc_attr( $starter_size ) . ';';
			}
		}
	}

	$css = $starter_vars['desktop'] ? ':root{' . $starter_vars['desktop'] . "}\n" : '';

	if ( $starter_vars['tablet'] ) {
		$css .= '@media (max-width: 1023px){:root{' . $starter_vars['tablet'] . "}}\n";
	}

	if ( $starter_vars['mobile'] ) {
		$css .= '@media (max-width: 782px){:root{' . $starter_vars['mobile'] . "}}\n";
	}

	$css .= $desktop_css;

	if ( $tablet_css ) {
		$css .= '@media (max-width: 1023px){' . $tablet_css . "}\n";
	}

	if ( $mobile_css ) {
		$css .= '@media (max-width: 782px){' . $mobile_css . "}\n";
	}

	return $css;
}

/* Inline style sullo stesso foglio di tipografia/colori, niente richiesta HTTP extra. */
function starter_enqueue_text_style_assets() {
	$css = starter_typography_style_css();

	if ( $css ) {
		wp_add_inline_style( 'starter-theme-style', $css );
	}
}
add_action( 'wp_enqueue_scripts', 'starter_enqueue_text_style_assets', 12 );

/* --content-width da Opzioni→Generale; non stampato se è il default (1280); --wide-width è sempre content-width+200px. */
function starter_enqueue_layout_assets() {
	$content_width = get_theme_mod( 'starter_content_width', 1280 );

	if ( 1280 !== (int) $content_width ) {
		wp_add_inline_style( 'starter-theme-style', ':root{--content-width:' . (int) $content_width . "px;}\n" );
	}
}
add_action( 'wp_enqueue_scripts', 'starter_enqueue_layout_assets', 12 );

/* Editor a blocchi: stesse variabili dell'anteprima sul sito (colori, font, dimensioni dei testi, larghezza) scelte in Personalizza,
   altrimenti l'editor mostra i valori di base del CSS e le anteprime (pulsanti, prodotti, titoli) non corrispondono al sito. */
add_filter(
	'block_editor_settings_all',
	function ( $settings ) {
		$css           = starter_colors_css() . starter_typography_css() . starter_typography_style_css();
		$content_width = (int) get_theme_mod( 'starter_content_width', 1280 );

		if ( 1280 !== $content_width ) {
			$css .= ':root{--content-width:' . $content_width . "px;}\n";
		}

		if ( trim( $css ) ) {
			$settings['styles'][] = array( 'css' => $css );
		}

		return $settings;
	}
);

/* Google Fonts scelti in Personalizza → anche dentro l'editor a blocchi. */
/* Editor: sfondo della sezione e classi personalizzate dei blocchi (scheda Avanzate) visibili anche in Gutenberg. */
add_action(
	'enqueue_block_editor_assets',
	function () {
		$slots = array();

		foreach ( starter_get_named_colors() as $slot => $data ) {
			$slots[ $slot ] = array(
				'value' => $data['value'],
				'dark'  => starter_color_is_dark( $data['value'] ),
			);
		}

		wp_enqueue_script( 'starter-editor-blocks', get_template_directory_uri() . '/JS/editor-blocks.js', array( 'wp-hooks', 'wp-element', 'wp-compose', 'wp-block-editor' ), starter_asset_version( 'JS/editor-blocks.js' ), true );
		wp_add_inline_script( 'starter-editor-blocks', 'window.starterEditorBg = ' . wp_json_encode( array( 'slots' => (object) $slots, 'legacy' => (object) starter_section_bg_legacy_map() ) ) . ';', 'before' );
	}
);

add_action(
	'enqueue_block_assets',
	function () {
		if ( ! is_admin() || ! function_exists( 'starter_get_active_typography_fonts' ) ) {
			return;
		}

		$families = array();

		foreach ( starter_get_active_typography_fonts() as $font ) {
			if ( 'google' === $font['type'] ) {
				$families[] = 'family=' . rawurlencode( $font['family'] ) . ':wght@100..900';
			}
		}

		if ( $families && ! ( function_exists( 'starter_local_fonts_ready' ) && starter_local_fonts_ready() ) ) {
			wp_enqueue_style( 'starter-editor-google-fonts', 'https://fonts.googleapis.com/css2?' . implode( '&', $families ) . '&display=swap', array(), null );
		}
	}
);

/* Stili footer disponibili: per aggiungerne uno basta una voce qui + template-parts/footer/footer-{slug}.php. */
function starter_footer_style_choices() {
	return array(
		'style-1' => __( 'Stile 1', 'slug-theme' ),
		'style-2' => __( 'Stile 2 (editoriale)', 'slug-theme' ),
		'style-3' => __( 'Stile 3 (compatto)', 'slug-theme' ),
		'style-4' => __( 'Stile 4 (4 colonne)', 'slug-theme' ),
		'style-5' => __( 'Stile 5 (titolo grande)', 'slug-theme' ),
	);
}

/* Sanitizzazione versione footer: valore ammesso o fallback al primo disponibile. */
function starter_sanitize_footer_style( $value ) {
	$choices = starter_footer_style_choices();

	if ( array_key_exists( $value, $choices ) ) {
		return $value;
	}

	$keys = array_keys( $choices );

	return $keys[0];
}

/* Registra un campo Personalizza come stringa traducibile WPML; no-op se WPML non è attivo. */
function starter_wpml_register_string( $name, $value ) {
	if ( '' === $value ) {
		return;
	}

	// Si registra solo dalla lingua predefinita: in un'altra lingua il valore predefinito di un campo (un __()) arriva già tradotto e sovrascriverebbe l'originale.
	$default = apply_filters( 'wpml_default_language', null );

	if ( $default && apply_filters( 'wpml_current_language', null ) !== $default ) {
		return;
	}

	// Lingua d'origine = quella predefinita del sito (i testi si scrivono in Personalizza): senza, WPML userebbe quella della pagina visitata in quel momento.
	do_action( 'wpml_register_single_string', 'Starter Theme', $name, $value, false, apply_filters( 'wpml_default_language', null ) );
}

/* Ritorna la stringa tradotta via WPML, o il valore originale se WPML non è attivo. */
function starter_wpml_translate_string( $name, $value ) {
	if ( '' === $value ) {
		return $value;
	}

	starter_wpml_register_string( $name, $value );

	return apply_filters( 'wpml_translate_single_string', $value, 'Starter Theme', $name );
}

/* Legge le "Voci" di Opzioni → Footer → Contatti e rende cliccabili email/telefono; righe HTML vanno stampate con wp_kses(), non esc_html(). */
/* Link automatico di un recapito: email → mailto:, numero di telefono → tel:, altro (indirizzo, orari…) → nessun link. */
function starter_contact_href( $text ) {
	$text = trim( (string) $text );

	if ( is_email( $text ) ) {
		return 'mailto:' . $text;
	}

	if ( preg_match( '/^[+0-9][0-9\s().-]{5,}$/', $text ) ) {
		return 'tel:' . preg_replace( '/[^0-9+]/', '', $text );
	}

	return null;
}

/* Icona di ripiego di un recapito (quando il campo icona è vuoto): email, telefono o indirizzo. */
function starter_contact_icon( $href ) {
	if ( ! $href ) {
		return 'map-pin';
	}

	return 0 === strpos( $href, 'mailto:' ) ? 'mail' : 'phone';
}

/* Recapiti del footer (indirizzo, telefono, email…) pronti da stampare */
function starter_get_footer_contacts() {
	// Fallback per chi arriva dai vecchi campi separati Indirizzo/Telefono/Email non ancora migrati.
	$starter_legacy_contacts = array_filter(
		array(
			get_theme_mod( 'starter_footer_address', '' ),
			get_theme_mod( 'starter_footer_phone', '' ),
			get_theme_mod( 'starter_footer_email', '' ),
		)
	);

	$raw = get_theme_mod( 'starter_footer_contacts', implode( "\n", $starter_legacy_contacts ) );
	$raw = starter_wpml_translate_string( 'footer_contacts', $raw );

	if ( '' === trim( $raw ) ) {
		return array();
	}

	$lines   = preg_split( '/\r\n|\r|\n/', $raw );
	$entries = array();

	foreach ( $lines as $line ) {
		$line = trim( $line );

		if ( '' === $line ) {
			continue;
		}

		$entries[] = array(
			'text' => $line,
			'href' => starter_contact_href( $line ),
		);
	}

	return $entries;
}

/* Sanitizza l'SVG caricato: whitelist di tag/attributi per evitare markup pericoloso. */
function starter_sanitize_svg( $svg ) {
	// Rimuove prologo XML/DOCTYPE/commenti, che wp_kses() sotto non riconosce e tratterebbe come testo.
	$svg = preg_replace( '/<\?xml.*?\?>/s', '', $svg );
	$svg = preg_replace( '/<!DOCTYPE.*?>/s', '', $svg );
	$svg = preg_replace( '/<!--.*?-->/s', '', $svg );
	$svg = trim( $svg );

	// Solo attributi di disegno (nessun script, stile, evento o collegamento esterno). Servono anche sui singoli tratti, non solo sull'<svg>.
	$draw = array(
		'id'                => true,
		'class'             => true,
		'fill'              => true,
		'stroke'            => true,
		'stroke-width'      => true,
		'stroke-linecap'    => true,
		'stroke-linejoin'   => true,
		'stroke-miterlimit' => true,
		'fill-rule'         => true,
		'clip-rule'         => true,
		'opacity'           => true,
		'fill-opacity'      => true,
		'stroke-opacity'    => true,
		'transform'         => true,
		'clip-path'         => true,
	);

	$allowed_svg_tags = array(
		'svg'            => array(
			'xmlns'       => true,
			'viewbox'     => true,
			'width'       => true,
			'height'      => true,
			'aria-hidden' => true,
		) + $draw,
		'path'           => array( 'd' => true ) + $draw,
		'circle'         => array( 'cx' => true, 'cy' => true, 'r' => true ) + $draw,
		'rect'           => array( 'x' => true, 'y' => true, 'width' => true, 'height' => true, 'rx' => true, 'ry' => true ) + $draw,
		'line'           => array( 'x1' => true, 'y1' => true, 'x2' => true, 'y2' => true ) + $draw,
		'polyline'       => array( 'points' => true ) + $draw,
		'polygon'        => array( 'points' => true ) + $draw,
		'ellipse'        => array( 'cx' => true, 'cy' => true, 'rx' => true, 'ry' => true ) + $draw,
		'g'              => $draw,
		'defs'           => array(),
		'clippath'       => array( 'id' => true, 'clippathunits' => true ),
		'lineargradient' => array( 'id' => true, 'x1' => true, 'y1' => true, 'x2' => true, 'y2' => true, 'gradientunits' => true, 'gradienttransform' => true ),
		'radialgradient' => array( 'id' => true, 'cx' => true, 'cy' => true, 'r' => true, 'fx' => true, 'fy' => true, 'gradientunits' => true, 'gradienttransform' => true ),
		'stop'           => array( 'offset' => true, 'stop-color' => true, 'stop-opacity' => true ),
	);

	// Via anche il testo che resta tra i tag (es. il contenuto di uno <script> rimosso): in un SVG senza <text> non serve e non deve restare.
	return preg_replace( '~>[^<]*[^<\s][^<]*<~', '><', wp_kses( $svg, $allowed_svg_tags ) );
}

/* Opzioni → Generale → "SVG": se attivo, sostituisce ogni <img src=".svg"> con l'SVG inline (colorabile via CSS). Disattivo di default. */
function starter_inline_svg_images( $content ) {
	if ( ! get_theme_mod( 'starter_svg_inline_mode', false ) ) {
		return $content;
	}

	if ( false === stripos( $content, '.svg' ) ) {
		return $content;
	}

	return preg_replace_callback(
		'/<img\b[^>]*\ssrc=["\']([^"\']+\.svg(?:\?[^"\']*)?)["\'][^>]*>/i',
		'starter_replace_img_with_inline_svg',
		$content
	);
}
add_filter( 'the_content', 'starter_inline_svg_images', 20 );

/* Callback di starter_inline_svg_images(): sostituisce l'img con l'SVG, riportando classe/alt; in caso di errore lascia l'img invariato. */
function starter_replace_img_with_inline_svg( $matches ) {
	$img_tag = $matches[0];
	$path    = starter_svg_url_to_path( html_entity_decode( $matches[1] ) );

	if ( ! $path || ! file_exists( $path ) ) {
		return $img_tag;
	}

	$svg_contents = file_get_contents( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_file_get_contents

	if ( ! $svg_contents ) {
		return $img_tag;
	}

	$svg = starter_sanitize_svg( $svg_contents );

	if ( ! $svg ) {
		return $img_tag;
	}

	$svg_attrs = 'role="img"';

	if ( preg_match( '/\sclass=["\']([^"\']*)["\']/i', $img_tag, $class_match ) && trim( $class_match[1] ) ) {
		$svg_attrs .= ' class="' . esc_attr( trim( $class_match[1] ) ) . '"';
	}

	if ( preg_match( '/\salt=["\']([^"\']*)["\']/i', $img_tag, $alt_match ) && trim( $alt_match[1] ) ) {
		$svg_attrs .= ' aria-label="' . esc_attr( trim( $alt_match[1] ) ) . '"';
	} else {
		$svg_attrs .= ' aria-hidden="true"';
	}

	return preg_replace( '/<svg\b/i', '<svg ' . $svg_attrs, $svg, 1 );
}

/* Converte un URL della Libreria media nel percorso locale (vuoto se fuori dalla cartella upload). */
function starter_svg_url_to_path( $url ) {
	$upload_dir = wp_get_upload_dir();

	if ( empty( $upload_dir['baseurl'] ) || 0 !== strpos( $url, $upload_dir['baseurl'] ) ) {
		return '';
	}

	return $upload_dir['basedir'] . substr( $url, strlen( $upload_dir['baseurl'] ) );
}

/* True se il logo per l'header trasparente è attivo e caricato. */
function starter_has_dark_logo() {
	return (bool) get_theme_mod( 'starter_dark_logo_active', false ) && get_theme_mod( 'starter_dark_logo', '' );
}

/* True se Opzioni → Header ha l'header "Ridotto" selezionato. */
function starter_is_minimal_header() {
	return 'minimal' === get_theme_mod( 'starter_header_style', 'extended' );
}

/* True se Opzioni → Header ha l'header "Standard" selezionato. */
function starter_is_standard_header() {
	return in_array( get_theme_mod( 'starter_header_style', 'extended' ), array( 'standard', 'pills' ), true );
}

/* True se Opzioni → Header ha l'header "Pillole" selezionato: senza sfondo, sempre visibile, menu e pulsante in due pillole. Usa la disposizione "Standard". */
function starter_is_pills_header() {
	return 'pills' === get_theme_mod( 'starter_header_style', 'extended' );
}

/* True se "Header Flottante" (Opzioni → Header) è attivo; si applica sopra qualunque versione dell'header. */
function starter_is_floating_header() {
	return (bool) get_theme_mod( 'starter_header_floating', false );
}

/* Icone social predefinite (chiave => etichetta + SVG) mostrate come campi URL in Opzioni → Header. */
function starter_social_presets() {
	return array(
		'instagram' => array(
			'label' => 'Instagram',
			'svg'   => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="0.6" fill="currentColor" stroke="none"/></svg>',
		),
		'linkedin'  => array(
			'label' => 'LinkedIn',
			'svg'   => '<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" aria-hidden="true"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-4 0v7h-4V9h4v1.7A5.98 5.98 0 0 1 16 8z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>',
		),
		'facebook'  => array(
			'label' => 'Facebook',
			'svg'   => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>',
		),
		'twitter'   => array(
			'label' => 'X (Twitter)',
			'svg'   => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 4l16 16M20 4L4 20"/></svg>',
		),
		'youtube'   => array(
			'label' => 'YouTube',
			'svg'   => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="5" width="20" height="14" rx="4"/><path d="M10 9l5 3-5 3z" fill="currentColor" stroke="none"/></svg>',
		),
		'tiktok'    => array(
			'label' => 'TikTok',
			'svg'   => '<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" aria-hidden="true"><path d="M16.5 2c.3 2.1 1.8 3.8 4 4.1v3a7 7 0 0 1-4-1.3v6.7a6 6 0 1 1-6-6c.3 0 .7 0 1 .1v3.1a3 3 0 1 0 2 2.8V2h3z"/></svg>',
		),
		'substack'  => array(
			'label' => 'Substack',
			'svg'   => '<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" aria-hidden="true"><rect x="3" y="3" width="18" height="3"/><rect x="3" y="9" width="18" height="3"/><rect x="3" y="15" width="18" height="7"/></svg>',
		),
	);
}

/* Icone social da mostrare in header: preset con URL compilato + slot custom con URL e SVG entrambi compilati. */
function starter_get_social_links() {
	$links = array();

	foreach ( starter_social_presets() as $key => $preset ) {
		$url = get_theme_mod( "starter_social_{$key}", '' );

		if ( $url ) {
			$links[] = array(
				'label' => $preset['label'],
				'url'   => $url,
				'svg'   => $preset['svg'],
			);
		}
	}

	for ( $i = 1; $i <= 5; $i++ ) {
		$url    = get_theme_mod( "starter_social_custom_{$i}_url", '' );
		$svg_id = (int) get_theme_mod( "starter_social_custom_{$i}_svg", 0 );

		if ( ! $url || ! $svg_id ) {
			continue;
		}

		$svg_markup = starter_get_svg_markup_from_attachment( $svg_id );

		if ( ! $svg_markup ) {
			continue;
		}

		$attachment_title = get_the_title( $svg_id );

		$links[] = array(
			/* translators: %d: numero dello slot icona personalizzata. */
			'label' => $attachment_title ? $attachment_title : sprintf( __( 'Social personalizzato %d', 'slug-theme' ), $i ),
			'url'   => $url,
			'svg'   => $svg_markup,
		);
	}

	return $links;
}

/* Stampa gli <li> delle icone social col markup sdoppiato per l'animazione flip; usata sia nell'header che nel pannello mobile. */
function starter_render_social_links_items( $starter_links ) {
	foreach ( $starter_links as $starter_social_link ) {
		?>
		<li>
			<a href="<?php echo esc_url( $starter_social_link['url'] ); ?>" aria-label="<?php echo esc_attr( $starter_social_link['label'] ); ?>" target="_blank" rel="noopener noreferrer">
				<span class="social-links__icon-wrap">
					<span class="social-links__icon">
						<?php echo $starter_social_link['svg']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitizzato in starter_sanitize_svg() al salvataggio. ?>
					</span>
					<span class="social-links__icon social-links__icon--clone" aria-hidden="true">
						<?php echo $starter_social_link['svg']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitizzato in starter_sanitize_svg() al salvataggio. ?>
					</span>
				</span>
			</a>
		</li>
		<?php
	}
}

/* Legge l'SVG da Media Library e ritorna il markup sanificato, trattato come codice invece che come <img>. */
function starter_get_svg_markup_from_attachment( $attachment_id ) {
	$path = get_attached_file( $attachment_id );

	if ( ! $path || ! file_exists( $path ) ) {
		return '';
	}

	$contents = file_get_contents( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- file locale nella Media Library, non un URL remoto.

	if ( ! $contents ) {
		return '';
	}

	return starter_force_svg_currentcolor( starter_sanitize_svg( $contents ) );
}

/* Forza fill/stroke a "currentColor" (lascia "none") così le icone social caricate seguono i colori del tema; non usata per l'SVG generico nel contenuto. */
function starter_force_svg_currentcolor( $svg ) {
	return preg_replace_callback(
		'/\s(fill|stroke)=(["\'])(.*?)\2/i',
		function ( $matches ) {
			$value = trim( $matches[3] );

			if ( '' === $value || 'none' === strtolower( $value ) ) {
				return $matches[0];
			}

			return ' ' . $matches[1] . '="currentColor"';
		},
		$svg
	);
}

/* CSS/dist è valido solo se più recente dei sorgenti (altrimenti si usano i sorgenti: le modifiche si vedono subito). */
function starter_dist_ready() {
	$manifest = get_theme_file_path( 'CSS/dist/manifest.json' );

	if ( ! file_exists( $manifest ) || ! file_exists( get_theme_file_path( 'JS/dist/scripts.min.js' ) ) ) {
		return false;
	}

	$built = filemtime( $manifest );

	return $built >= filemtime( get_theme_file_path( 'CSS/starter.css' ) )
		&& $built >= filemtime( get_theme_file_path( 'CSS/preset-starter.css' ) )
		&& $built >= filemtime( get_theme_file_path( 'CSS/shop.css' ) )
		&& $built >= filemtime( get_theme_file_path( 'CSS/filters.css' ) )
		&& filemtime( get_theme_file_path( 'JS/dist/scripts.min.js' ) ) >= filemtime( get_theme_file_path( 'JS/scripts.js' ) );
}

/* Vero se in pagina c'è almeno uno dei blocchi in template-parts/ (vedi inc/acf-blocks.php). */
function starter_has_content_block() {
	if ( ! is_singular() ) {
		return false;
	}

	// Qualunque blocco del tema (template-parts/*): "<!-- wp:starter-theme/nome ... -->" nel contenuto.
	return str_contains( (string) get_post_field( 'post_content', get_queried_object_id() ), 'wp:starter-theme/' );
}

/* Vero se la pagina corrente contiene il blocco del tema indicato (es. 'ultimi-articoli'). */
function starter_page_has_block( $name ) {
	return is_singular() && str_contains( (string) get_post_field( 'post_content', get_queried_object_id() ), 'wp:starter-theme/' . $name );
}

/* Filtri degli elenchi (CSS/filters.css, uguale per tutti): archivio dello Shop e pagine dei moduli che li usano (filtro starter_needs_filters_css). */
function starter_needs_filters_css() {
	$shop = function_exists( 'is_shop' ) && ( is_shop() || is_product_taxonomy() );

	return (bool) apply_filters( 'starter_needs_filters_css', $shop );
}

/* Fogli CSS extra: bucket => serve in questa pagina? "base" (header, footer, bottoni...) si carica sempre, non è in elenco. */
function starter_css_buckets() {
	return array(
		'blog'      => is_home() || is_archive() || is_search() || is_singular( 'post' ) || is_404() || starter_page_has_block( 'ultimi-articoli' ),
		// Titoli di pagina e briciole condivisi (.page-header, .breadcrumb) stanno in questo foglio: serve su qualunque pagina con contenuto.
		'blocks'    => is_singular() || is_home() || is_archive() || is_search() || is_404() || starter_is_woocommerce_page(),
		'shop'      => starter_needs_shop_css(),
		'filters'   => starter_needs_filters_css(),
		'portfolio' => is_singular( 'portfolio' ) || starter_page_has_block( 'progett' ), // Blocchi "Elenco progetti" (progetti) e "Progetto — …" (progetto-…).
	);
}

/* Vero se il footer mostra la scritta grande in Fraunces (Stile 1, senza logo chiaro): solo allora serve quel font. */
function starter_footer_wordmark_font_needed() {
	return 'style-1' === get_theme_mod( 'starter_footer_style', 'style-1' ) && ! get_theme_mod( 'starter_dark_logo', '' );
}

/* Enqueue di stile e script del frontend. */
function starter_theme_enqueue_assets() {
	// Font serif solo per il wordmark del footer (Stile 1 senza logo chiaro), tenuto fuori da --font-body: si carica solo se serve
	// e, con "Google Fonts serviti dal tuo sito" acceso, viene scaricato in locale come gli altri.
	if ( starter_footer_wordmark_font_needed() && ! ( function_exists( 'starter_local_fonts_ready' ) && starter_local_fonts_ready() ) ) {
		wp_enqueue_style(
			'starter-footer-font',
			'https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;1,9..144,400&display=swap',
			array(),
			null
		);
	}

	// Con CSS/dist (npm run build) si carica il CSS minificato diviso per tipo di pagina; altrimenti il sorgente completo.
	$starter_dist = starter_dist_ready();

	if ( $starter_dist ) {
		wp_enqueue_style( 'starter-theme-style', get_template_directory_uri() . '/CSS/dist/base.min.css', array(), starter_asset_version( 'CSS/dist/base.min.css' ) );

		foreach ( starter_css_buckets() as $starter_bucket => $starter_needed ) {
			if ( $starter_needed && file_exists( get_theme_file_path( "CSS/dist/{$starter_bucket}.min.css" ) ) ) {
				wp_enqueue_style( "starter-{$starter_bucket}", get_template_directory_uri() . "/CSS/dist/{$starter_bucket}.min.css", array( 'starter-theme-style' ), starter_asset_version( "CSS/dist/{$starter_bucket}.min.css" ) );
			}
		}
	} else {
		// Stile di base per i blocchi Gutenberg non coperti da starter.css, caricato prima così starter.css vince sempre.
		wp_enqueue_style(
			'starter-theme-preset',
			get_template_directory_uri() . '/CSS/preset-starter.css',
			array(),
			starter_asset_version( 'CSS/preset-starter.css' )
		);

		// Versione da filemtime() invece di STARTER_THEME_VERSION: evita cache stale su file che cambiano spesso in sviluppo.
		wp_enqueue_style(
			'starter-theme-style',
			get_template_directory_uri() . '/CSS/starter.css',
			array( 'starter-theme-preset' ),
			starter_asset_version( 'CSS/starter.css' )
		);

		// Stili dello shop (card prodotto, filtri, scheda, carrello): solo dove servono. In produzione c'è CSS/dist/shop.min.css.
		if ( starter_needs_shop_css() ) {
			wp_enqueue_style( 'starter-shop', get_template_directory_uri() . '/CSS/shop.css', array( 'starter-theme-style' ), starter_asset_version( 'CSS/shop.css' ) );
		}

		if ( starter_needs_filters_css() ) {
			wp_enqueue_style( 'starter-filters', get_template_directory_uri() . '/CSS/filters.css', array( 'starter-theme-style' ), starter_asset_version( 'CSS/filters.css' ) );
		}
	}

	$starter_script = $starter_dist ? 'JS/dist/scripts.min.js' : 'JS/scripts.js';

	wp_enqueue_script(
		'starter-theme-scripts',
		get_template_directory_uri() . '/' . $starter_script,
		array(),
		starter_asset_version( $starter_script ),
		true
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'starter_theme_enqueue_assets' );

/* Versione di una libreria in JS/vendor, letta da JS/vendor/vendor.json (la aggiorna tools/update-vendor.js). */
function starter_vendor_version( $name ) {
	static $manifest = null;

	if ( null === $manifest ) {
		$file     = get_template_directory() . '/JS/vendor/vendor.json';
		$manifest = is_readable( $file ) ? json_decode( (string) file_get_contents( $file ), true ) : array(); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$manifest = is_array( $manifest ) ? $manifest : array();
	}

	return isset( $manifest[ $name ]['version'] ) ? (string) $manifest[ $name ]['version'] : STARTER_THEME_VERSION;
}

/* Caricamento delle pagine scelto in Opzioni → Generale: none | dissolvenza | tendina. */
function starter_preloader_mode() {
	$mode = get_theme_mod( 'starter_preloader', 'none' );

	return in_array( $mode, array( 'dissolvenza', 'tendina' ), true ) ? $mode : 'none';
}

/**
 * Caricamento a dissolvenza o a tenda: il velo (stesse classi "pt-…" della transizione tra i progetti, vedi CSS e initProjectTransition in
 * JS/scripts.js) è acceso al primo disegno della pagina e lo toglie JS quando la pagina è pronta. Se la pagina arriva da una transizione
 * tra progetti, vale quella.
 */
add_action(
	'wp_head',
	static function () {
		$mode = starter_preloader_mode();

		if ( ! in_array( $mode, array( 'dissolvenza', 'tendina' ), true ) ) {
			return;
		}

		$content = (string) get_theme_mod( 'starter_preloader_content', 'text' );
		$label   = in_array( $content, array( 'name', 'logo' ), true ) ? get_bloginfo( 'name' ) : (string) starter_wpml_translate_string( 'preloader_text', (string) get_theme_mod( 'starter_preloader_text', __( 'Caricamento…', 'slug-theme' ) ) );
		$bg      = starter_resolve_named_color( (string) get_theme_mod( 'starter_preloader_bg', '' ) );
		$fg      = starter_resolve_named_color( (string) get_theme_mod( 'starter_preloader_fg', '' ) );
		$classes = ' pt-' . $mode . ( 'percent' === $content ? ' pt-counter' : '' ) . ' pt-go pt-in';
		$logo_js = '';

		// Logo del sito: al posto della scritta (in basso a sinistra nella dissolvenza, al centro nella tenda).
		if ( 'logo' === $content ) {
			$logo = get_theme_mod( 'custom_logo' ) ? wp_get_attachment_image_src( (int) get_theme_mod( 'custom_logo' ), 'full' ) : false;

			if ( $logo && ! empty( $logo[0] ) ) {
				$ratio    = ! empty( $logo[1] ) && ! empty( $logo[2] ) ? round( $logo[1] / $logo[2], 4 ) : 3;
				$classes .= ' pt-logo' . ( get_theme_mod( 'starter_preloader_logo_tint', true ) ? '' : ' pt-logo-orig' );
				$logo_js  = 's.setProperty("--pt-logo","url("+JSON.stringify(' . wp_json_encode( $logo[0] ) . ')+")");s.setProperty("--pt-logo-ratio",' . wp_json_encode( (string) $ratio ) . ');';
			}
		}

		echo '<script id="starter-preloader-in">(function(){var r=document.documentElement;if(/\bpt-/.test(r.className)){return;}var s=r.style;s.setProperty("--pt-name",JSON.stringify(' . wp_json_encode( $label ) . '));s.setProperty("--pt-text",JSON.stringify(' . wp_json_encode( $label ) . '));s.setProperty("--pt-n","0");' . ( $bg ? 's.setProperty("--pt-bg",' . wp_json_encode( $bg ) . ');' : '' ) . ( $fg ? 's.setProperty("--pt-fg",' . wp_json_encode( $fg ) . ');' : '' ) . $logo_js . 'r.className+=' . wp_json_encode( $classes ) . ';setTimeout(function(){if(r.classList.contains("pt-in")){r.className=r.className.replace(/\bpt-[\w-]+/g,"");}},9000);})();</script>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- valori scappati con wp_json_encode.
		echo '<noscript><style>html.pt-in::before,html.pt-in::after{display:none!important}html.pt-in{overflow:auto!important}</style></noscript>' . "\n";
	},
	2
);

/* Scroll fluido (Lenis), solo se attivo in Opzioni → Generale. Niente GSAP: non serve. */
function starter_enqueue_animation_assets() {
	if ( ! (bool) get_theme_mod( 'starter_enable_smooth_scroll', true ) ) {
		return;
	}

	wp_enqueue_script(
		'lenis',
		get_template_directory_uri() . '/JS/vendor/lenis.min.js',
		array(),
		starter_vendor_version( 'lenis' ),
		true
	);

	wp_enqueue_script(
		'starter-animations',
		get_template_directory_uri() . '/JS/animations.js',
		array( 'lenis' ),
		STARTER_THEME_VERSION,
		true
	);

	wp_localize_script( 'starter-animations', 'starterAnimations', array( 'smoothScroll' => true ) );
}
add_action( 'wp_enqueue_scripts', 'starter_enqueue_animation_assets' );

/* CPT "Portfolio", categorie, archivio e scheda del progetto: vedi inc/portfolio.php (si spegne da Opzioni → Portfolio). */

/* Categoria "Starter" nell'inserimento blocchi, per i blocchi del tema (plugin Starter Blocks). */
function starter_block_categories( $categories ) {
	array_unshift(
		$categories,
		array(
			'slug'  => 'starter',
			'title' => __( 'Starter', 'slug-theme' ),
		)
	);

	return $categories;
}
add_filter( 'block_categories_all', 'starter_block_categories' );

/* Rimuove meta poco utili dall'head in produzione. */
function starter_theme_cleanup_head() {
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'rsd_link' );
}
add_action( 'init', 'starter_theme_cleanup_head' );

/* Larghezza default (px) per dimensionare immagini/oEmbed. */
if ( ! isset( $content_width ) ) {
	$content_width = 1280;
}

/* Dimensione custom per le immagini in evidenza, pronta all'uso nei template. */
function starter_theme_image_sizes() {
	add_image_size( 'starter-theme-card', 600, 400, true );
	// Foto dello slider hero (fino a 2000px di lato lungo).
	add_image_size( 'starter-hero', 2000, 2000, false );
}
add_action( 'after_setup_theme', 'starter_theme_image_sizes' );

/* Inserisce il markup duplicato per l'animazione flip del testo del blocco Pulsante (il core non lo genera da solo); solo frontend, mai nell'editor. */
function starter_wp_block_button_flip_animation( $block_content ) {
	if ( ! $block_content || false === strpos( $block_content, 'wp-block-button__link' ) ) {
		return $block_content;
	}

	$starter_matched = preg_match(
		'/(<a\b[^>]*\bclass="[^"]*\bwp-block-button__link\b[^"]*"[^>]*>)(.*?)(<\/a>)/s',
		$block_content,
		$starter_matches
	);

	if ( ! $starter_matched ) {
		return $block_content;
	}

	list( $starter_whole, $starter_open_tag, $starter_inner, $starter_close_tag ) = $starter_matches;

	$starter_new_inner = '<span class="button__text-wrap">'
		. '<span class="button__text">' . $starter_inner . '</span>'
		. '<span class="button__text button__text--clone" aria-hidden="true">' . $starter_inner . '</span>'
		. '</span>';

	return str_replace( $starter_whole, $starter_open_tag . $starter_new_inner . $starter_close_tag, $block_content );
}
add_filter( 'render_block_core/button', 'starter_wp_block_button_flip_animation' );

/* Stili del blocco Pulsante di Gutenberg: "Pieno" e "Contorno" sono già del core (qui prendono i colori di Pieno 1 e Contorno 1); questi due sono le varianti 2 di Opzioni → Pulsanti. */
function starter_register_button_block_styles() {
	register_block_style(
		'core/button',
		array(
			'name'  => 'fill-2',
			'label' => __( 'Pieno 2', 'slug-theme' ),
		)
	);

	register_block_style(
		'core/button',
		array(
			'name'  => 'outline-2',
			'label' => __( 'Contorno 2', 'slug-theme' ),
		)
	);
}
add_action( 'init', 'starter_register_button_block_styles' );


/* Palette editor Gutenberg: solo i "Colori con nome", per evitare duplicati con i vecchi campi di sistema (non deduplicati da Gutenberg). */
/* Palette di theme.json: i "Colori con nome" (Customizer) sostituiscono per slug i colori di base e aggiungono quelli nuovi. */
function starter_extend_editor_color_palette( $theme_json ) {
	$starter_data = $theme_json->get_data();
	$starter_base = isset( $starter_data['settings']['color']['palette']['theme'] ) ? $starter_data['settings']['color']['palette']['theme'] : array();
	$starter_map  = array();

	foreach ( $starter_base as $starter_entry ) {
		$starter_map[ $starter_entry['slug'] ] = $starter_entry;
	}

	foreach ( starter_get_named_colors() as $starter_slot => $starter_named ) {
		$starter_slug = starter_palette_slug( (int) substr( $starter_slot, 5 ) );

		$starter_map[ $starter_slug ] = array(
			'name'  => $starter_named['name'],
			'slug'  => $starter_slug,
			'color' => $starter_named['value'],
		);
	}

	return $theme_json->update_with(
		array(
			'version'  => 3,
			'settings' => array(
				'color' => array(
					'palette' => array_values( $starter_map ),
				),
			),
		)
	);
}
add_filter( 'wp_theme_json_data_theme', 'starter_extend_editor_color_palette' );

/* Salvando il Customizer si svuota la cache di theme.json, così i preset seguono subito. */
add_action(
	'customize_save_after',
	function () {
		if ( class_exists( 'WP_Theme_JSON_Resolver' ) ) {
			WP_Theme_JSON_Resolver::clean_cached_data();
		}
	}
);

/* Sostituisce il logo WordPress in login col logo del sito (variante normale, non quella per header trasparente). */
function starter_login_logo() {
	$starter_custom_logo_id = get_theme_mod( 'custom_logo' );

	if ( ! $starter_custom_logo_id ) {
		return;
	}

	$starter_logo = wp_get_attachment_image_src( $starter_custom_logo_id, 'full' );

	if ( ! $starter_logo ) {
		return;
	}

	list( $starter_logo_url, $starter_logo_width, $starter_logo_height ) = $starter_logo;

	// Scala il logo dentro uno spazio massimo senza deformarlo.
	$starter_max_width  = 320;
	$starter_max_height = 120;
	$starter_ratio      = min( $starter_max_width / $starter_logo_width, $starter_max_height / $starter_logo_height, 1 );
	$starter_width      = round( $starter_logo_width * $starter_ratio );
	$starter_height     = round( $starter_logo_height * $starter_ratio );
	?>
	<style type="text/css">
		#login h1 a {
			background-image: url(<?php echo esc_url( $starter_logo_url ); ?>);
			background-size: contain;
			background-position: center;
			background-repeat: no-repeat;
			width: <?php echo esc_attr( $starter_width ); ?>px;
			height: <?php echo esc_attr( $starter_height ); ?>px;
			margin-left: auto;
			margin-right: auto;
		}
	</style>
	<?php
}
add_action( 'login_enqueue_scripts', 'starter_login_logo' );

/* Logo di login: link all'homepage invece di wordpress.org, title col nome del sito. */
function starter_login_logo_url() {
	return home_url( '/' );
}
add_filter( 'login_headerurl', 'starter_login_logo_url' );

/* Title del logo di login: nome del sito */
function starter_login_logo_title() {
	return get_bloginfo( 'name' );
}
add_filter( 'login_headertext', 'starter_login_logo_title' );

/* True se "Disattiva i commenti su tutto il sito" (Opzioni → Generale) è attivo. */
function starter_comments_disabled() {
	return (bool) get_theme_mod( 'starter_disable_comments', false );
}

/* Disattiva completamente i commenti (hook attivi solo se l'opzione è spuntata): chiude commenti/pingback, li nasconde, rimuove tracce dall'admin. */
function starter_maybe_disable_comments() {
	if ( ! starter_comments_disabled() ) {
		return;
	}

	// Toglie il supporto commenti da ogni post type.
	add_action(
		'init',
		function () {
			foreach ( get_post_types() as $post_type ) {
				if ( post_type_supports( $post_type, 'comments' ) ) {
					remove_post_type_support( $post_type, 'comments' );
					remove_post_type_support( $post_type, 'trackbacks' );
				}
			}
		},
		100
	);

	// Chiude commenti/pingback ovunque senza toccare il database (reversibile disattivando l'opzione).
	add_filter( 'comments_open', '__return_false', 20 );
	add_filter( 'pings_open', '__return_false', 20 );

	// Nasconde i commenti già esistenti ovunque vengano interrogati.
	add_filter( 'comments_array', '__return_empty_array', 10 );

	// Rimuove "Commenti" dal menu di WordPress e dalla admin bar.
	add_action(
		'admin_menu',
		function () {
			remove_menu_page( 'edit-comments.php' );
		}
	);

	add_action(
		'init',
		function () {
			remove_action( 'admin_bar_menu', 'wp_admin_bar_comments_menu', 60 );
		}
	);

	// Chi apre direttamente la pagina commenti viene rimandato in bacheca.
	add_action(
		'admin_init',
		function () {
			global $pagenow;

			if ( 'edit-comments.php' === $pagenow ) {
				wp_safe_redirect( admin_url() );
				exit;
			}
		}
	);

	// Toglie il box "Discussione" dall'editor e il widget "Commenti recenti" dalla bacheca.
	add_action(
		'admin_init',
		function () {
			foreach ( get_post_types() as $post_type ) {
				remove_meta_box( 'commentstatusdiv', $post_type, 'normal' );
				remove_meta_box( 'commentsdiv', $post_type, 'normal' );
			}
		}
	);

	add_action(
		'wp_dashboard_setup',
		function () {
			remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
		}
	);
}
add_action( 'init', 'starter_maybe_disable_comments', 1 );

/* Aspetto → Font (Libreria dei font di WordPress) e Design (editor del sito): non servono, il tema ha già Tipografia e le altre opzioni in Personalizza. */
add_action(
	'admin_menu',
	function () {
		remove_submenu_page( 'themes.php', 'font-library.php' );
		remove_submenu_page( 'themes.php', 'site-editor.php' );
	},
	999
);
