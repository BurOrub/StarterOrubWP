<?php
/**
 * Footer — Stile 1.
 *
 * Struttura e stile ispirati al footer di GoCanopy (via footer.design):
 * riga intro + colonne menu, riga social + spazio libero (shortcode o contenuto), logo enorme,
 * riga finale con copyright e link legali. Il layout cambia ordine tra
 * mobile e desktop (vedi CSS/starter.css, ".footer-*").
 *
 * Tutti i testi passano da Personalizza → Opzioni → Footer e da
 * starter_wpml_translate_string(), così risultano già pronti per WPML
 * (String Translation) quando verrà installato — vedi functions.php.
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Stessi social dell'header (Personalizza -> Opzioni -> Generale ->
// Social), ma con un checkbox "Mostra i social" separato per il footer.
$starter_footer_socials = get_theme_mod( 'starter_social_show_footer', true ) ? starter_get_social_links() : array();
$starter_footer_logo_light = get_theme_mod( 'starter_dark_logo', '' );

$starter_footer_intro_title = starter_wpml_translate_string(
	'footer_intro_title',
	get_theme_mod(
		'starter_footer_intro_title',
		get_bloginfo( 'description' ) ? get_bloginfo( 'description' ) : __( 'Tema base di lavoro', 'slug-theme' )
	)
);
$starter_footer_description = starter_wpml_translate_string(
	'footer_description',
	get_theme_mod(
		'starter_footer_description',
		__( 'Un tema WordPress classico e leggero, pensato come base pronta da personalizzare: header e footer configurabili dal Customizer, Gutenberg-first fin dall\'inizio.', 'slug-theme' )
	)
);
$starter_footer_contacts_title = starter_wpml_translate_string(
	'footer_contacts_title',
	get_theme_mod( 'starter_footer_contacts_title', __( 'Contatti', 'slug-theme' ) )
);
$starter_footer_contacts = starter_get_footer_contacts();
$starter_footer_menu_title = starter_wpml_translate_string(
	'footer_menu_title',
	get_theme_mod( 'starter_footer_menu_title', get_bloginfo( 'name' ) )
);
$starter_footer_copyright = starter_wpml_translate_string(
	'footer_copyright',
	get_theme_mod( 'starter_footer_copyright', __( '© {anno} {sito}. Tutti i diritti riservati.', 'slug-theme' ) )
);
$starter_footer_copyright = str_replace(
	array( '{anno}', '{sito}' ),
	array( gmdate( 'Y' ), get_bloginfo( 'name' ) ),
	$starter_footer_copyright
);
$starter_footer_privacy_label = starter_wpml_translate_string(
	'footer_privacy_label',
	get_theme_mod( 'starter_footer_privacy_label', '' )
);
$starter_footer_privacy_url   = get_theme_mod( 'starter_footer_privacy_url', '' );
$starter_footer_privacy_class = get_theme_mod( 'starter_footer_privacy_class', '' );
$starter_footer_cookie_label = starter_wpml_translate_string(
	'footer_cookie_label',
	get_theme_mod( 'starter_footer_cookie_label', '' )
);
$starter_footer_cookie_url   = get_theme_mod( 'starter_footer_cookie_url', '' );
$starter_footer_cookie_class = get_theme_mod( 'starter_footer_cookie_class', '' );
$starter_footer_terms_label = starter_wpml_translate_string(
	'footer_terms_label',
	get_theme_mod( 'starter_footer_terms_label', '' )
);
$starter_footer_terms_url   = get_theme_mod( 'starter_footer_terms_url', '' );
$starter_footer_terms_class = get_theme_mod( 'starter_footer_terms_class', '' );
$starter_footer_credits_label = starter_wpml_translate_string(
	'footer_credits_label',
	get_theme_mod( 'starter_footer_credits_label', '' )
);
$starter_footer_credits_url = get_theme_mod( 'starter_footer_credits_url', '' );
?>

<footer class="site-footer" id="site-footer">
	<div class="site-footer__inner">

		<?php // Le due righe condividono le stesse colonne: la newsletter occupa lo spazio di Contatti + Menu. ?>
		<div class="footer-grid">
		<div class="footer-row footer-row--top">
			<div class="footer-intro">
				<h3 class="footer-nav-col__title"><?php echo esc_html( $starter_footer_intro_title ); ?></h3>
				<?php if ( $starter_footer_description ) : ?>
					<p class="footer-intro__description"><?php echo esc_html( $starter_footer_description ); ?></p>
				<?php endif; ?>
			</div>

			<?php if ( $starter_footer_contacts ) : ?>
				<div class="footer-info">
					<h3 class="footer-nav-col__title"><?php echo esc_html( $starter_footer_contacts_title ); ?></h3>
					<ul class="footer-info__list">
						<?php foreach ( $starter_footer_contacts as $starter_contact ) : ?>
							<li>
								<?php if ( $starter_contact['href'] ) : ?>
									<a href="<?php echo esc_attr( $starter_contact['href'] ); ?>"><?php echo esc_html( $starter_contact['text'] ); ?></a>
								<?php else : ?>
									<?php echo wp_kses( $starter_contact['text'], starter_footer_consent_allowed_html() ); ?>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>

			<div class="footer-nav-col">
				<h3 class="footer-nav-col__title"><?php echo esc_html( $starter_footer_menu_title ); ?></h3>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'header',
						'container'      => false,
						'fallback_cb'    => false,
						'depth'          => 1,
					)
				);
				?>
			</div>
		</div>

		<div class="footer-row footer-row--middle">
			<?php if ( $starter_footer_socials ) : ?>
				<div class="footer-social">
					<?php foreach ( $starter_footer_socials as $starter_social ) : ?>
						<div class="footer-social__item">
							<a href="<?php echo esc_url( $starter_social['url'] ); ?>" class="footer-social__icon" target="_blank" rel="noopener noreferrer">
								<?php echo $starter_social['svg']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitizzato in starter_sanitize_svg() al salvataggio. ?>
							</a>
							<a href="<?php echo esc_url( $starter_social['url'] ); ?>" class="footer-social__label" target="_blank" rel="noopener noreferrer">
								<?php
								/* translators: %s: nome del social, es. "Instagram". */
								echo esc_html( sprintf( __( 'Seguici su %s', 'slug-theme' ), $starter_social['label'] ) );
								?>
							</a>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php $starter_free = starter_footer_free_html(); ?>
			<?php if ( $starter_free ) : ?>
				<div class="footer-cta-wrap footer-free-wrap"><?php echo $starter_free; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitizzato in starter_footer_free_html(). ?></div>
			<?php endif; ?>
		</div>
		</div>

		<div class="footer-wordmark" aria-hidden="true">
			<?php if ( $starter_footer_logo_light ) : ?>
				<?php echo starter_picturefy( '<img src="' . esc_url( $starter_footer_logo_light ) . '" class="footer-wordmark__logo" alt="" loading="lazy" decoding="async">' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php else : ?>
				<span><?php bloginfo( 'name' ); ?></span>
			<?php endif; ?>
		</div>

		<div class="footer-row footer-row--bottom">
			<p class="footer-copyright"><?php echo esc_html( $starter_footer_copyright ); ?></p>

			<nav class="footer-legal" aria-label="<?php esc_attr_e( 'Link legali', 'slug-theme' ); ?>">
				<?php if ( $starter_footer_privacy_label ) : ?>
					<a href="<?php echo esc_url( $starter_footer_privacy_url ? $starter_footer_privacy_url : '#' ); ?>"<?php echo $starter_footer_privacy_class ? ' class="' . esc_attr( $starter_footer_privacy_class ) . '"' : ''; ?>><?php echo esc_html( $starter_footer_privacy_label ); ?></a>
				<?php endif; ?>
				<?php if ( $starter_footer_cookie_label ) : ?>
					<a href="<?php echo esc_url( $starter_footer_cookie_url ? $starter_footer_cookie_url : '#' ); ?>"<?php echo $starter_footer_cookie_class ? ' class="' . esc_attr( $starter_footer_cookie_class ) . '"' : ''; ?>><?php echo esc_html( $starter_footer_cookie_label ); ?></a>
				<?php endif; ?>
				<?php if ( $starter_footer_terms_label ) : ?>
					<a href="<?php echo esc_url( $starter_footer_terms_url ? $starter_footer_terms_url : '#' ); ?>"<?php echo $starter_footer_terms_class ? ' class="' . esc_attr( $starter_footer_terms_class ) . '"' : ''; ?>><?php echo esc_html( $starter_footer_terms_label ); ?></a>
				<?php endif; ?>
				<?php if ( $starter_footer_credits_label ) : ?>
					<a href="<?php echo esc_url( $starter_footer_credits_url ? $starter_footer_credits_url : '#' ); ?>"><?php echo esc_html( $starter_footer_credits_label ); ?></a>
				<?php endif; ?>
			</nav>
		</div>

	</div>
</footer>
