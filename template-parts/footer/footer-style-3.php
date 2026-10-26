<?php
/**
 * Footer — Stile 3 (compatto).
 *
 * Una sola riga (logo + menu inline + social, con le stesse icone a
 * bottone dell'header: riusa ".social-links") più la riga finale
 * copyright/link legali, identica a quella di "Stile 1"
 * (".footer-row--bottom", vedi CSS/starter.css). Pensato per pagine o
 * siti che non hanno bisogno delle colonne di "Stile 1"/"Stile 2"; non ha lo spazio libero (newsletter).
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
// Social), con il checkbox "Mostra i social" del footer.
$starter_footer_socials    = get_theme_mod( 'starter_social_show_footer', true ) ? starter_get_social_links() : array();
$starter_footer_logo_light = get_theme_mod( 'starter_dark_logo', '' );

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

<footer class="site-footer site-footer--minimal" id="site-footer">
	<div class="site-footer__inner">

		<div class="footer-row footer-row--minimal">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer-minimal__brand">
				<?php if ( $starter_footer_logo_light ) : ?>
					<?php echo starter_picturefy( '<img src="' . esc_url( $starter_footer_logo_light ) . '" class="footer-minimal__logo" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" loading="lazy" decoding="async">' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php else : ?>
					<span><?php bloginfo( 'name' ); ?></span>
				<?php endif; ?>
			</a>

			<nav class="footer-minimal__nav" aria-label="<?php esc_attr_e( 'Menu del footer', 'slug-theme' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'header',
						'container'      => false,
						'fallback_cb'    => false,
						'depth'          => 1,
						'items_wrap'     => '<ul class="footer-minimal__nav-list">%3$s</ul>',
					)
				);
				?>
			</nav>

			<?php if ( $starter_footer_socials ) : ?>
				<ul class="social-links footer-minimal__social">
					<?php starter_render_social_links_items( $starter_footer_socials ); ?>
				</ul>
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
