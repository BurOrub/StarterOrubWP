<?php
/**
 * Footer — Stile 4 (4 colonne), ripreso da Symbolikamente.
 *
 * Riga logo (versione chiara/negativa) + frase, poi 4 colonne: Contatti e social, menu "Link", menu
 * "Riferimenti", spazio libero (es. form di Contact Form 7); riga finale con copyright e Credits.
 *
 * Campi già presenti in Opzioni → Footer e riusati qui: Descrizione, Titolo contatti, Contatti (telefono, email,
 * indirizzo), Copyright (con {anno} e {sito}), Credits (testo e link), Mostra i social nel footer, Logo chiaro
 * (Identità del sito / Opzioni → Header). Campi nuovi, solo per questo stile (Opzioni → Footer):
 *   - Immagine metodi di pagamento: opzionale, sotto i social (utile per i negozi).
 * L'ultima colonna è lo "Spazio libero" (Opzioni → Footer, comune a tutti gli stili): titolo facoltativo e contenuto
 * a piacere, di solito uno shortcode come [contact-form-7 id="12"]. Se è vuoto la colonna non compare.
 * Le due colonne menu usano le posizioni "Footer: colonna Link" e "Footer: colonna Riferimenti" (Aspetto → Menu):
 * il titolo della colonna è il nome del menu assegnato.
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$starter_footer_socials    = get_theme_mod( 'starter_social_show_footer', true ) ? starter_get_social_links() : array();
$starter_footer_logo_light = get_theme_mod( 'starter_dark_logo', '' );

$starter_footer_description    = starter_wpml_translate_string(
	'footer_description',
	get_theme_mod( 'starter_footer_description', __( 'Un tema WordPress classico e leggero, pensato come base pronta da personalizzare: header e footer configurabili dal Customizer, Gutenberg-first fin dall\'inizio.', 'slug-theme' ) )
);
$starter_footer_contacts_title = starter_wpml_translate_string( 'footer_contacts_title', get_theme_mod( 'starter_footer_contacts_title', __( 'Contatti', 'slug-theme' ) ) );
$starter_footer_contacts       = starter_get_footer_contacts();

$starter_footer_copyright = str_replace(
	array( '{anno}', '{sito}' ),
	array( gmdate( 'Y' ), get_bloginfo( 'name' ) ),
	starter_wpml_translate_string( 'footer_copyright', get_theme_mod( 'starter_footer_copyright', __( '© {anno} {sito}. Tutti i diritti riservati.', 'slug-theme' ) ) )
);

// Link legali (Opzioni → Footer): Privacy, Cookie, Termini e Credits.
$starter_legal = array();

foreach ( array( 'privacy', 'cookie', 'terms' ) as $starter_key ) {
	$starter_label = starter_wpml_translate_string( "footer_{$starter_key}_label", get_theme_mod( "starter_footer_{$starter_key}_label", '' ) );

	if ( $starter_label ) {
		$starter_legal[] = array(
			'label' => $starter_label,
			'url'   => get_theme_mod( "starter_footer_{$starter_key}_url", '' ),
			'class' => get_theme_mod( "starter_footer_{$starter_key}_class", '' ),
		);
	}
}

$starter_footer_credits_label = starter_wpml_translate_string( 'footer_credits_label', get_theme_mod( 'starter_footer_credits_label', '' ) );
$starter_footer_credits_url   = get_theme_mod( 'starter_footer_credits_url', '' );

$starter_payments_id      = (int) get_theme_mod( 'starter_footer_payments_image', 0 );

$starter_footer_menus = array(
	'footer-link'        => __( 'Link', 'slug-theme' ),
	'footer-riferimenti' => __( 'Riferimenti', 'slug-theme' ),
);
?>

<footer class="site-footer site-footer--4" id="site-footer">
	<div class="site-footer__inner">

		<div class="footer-row footer-row--brand">
			<a class="footer-brand-row__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<?php if ( $starter_footer_logo_light ) : ?>
					<?php echo starter_picturefy( '<img src="' . esc_url( $starter_footer_logo_light ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" loading="lazy">' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php else : ?>
					<span class="footer-brand-row__name"><?php bloginfo( 'name' ); ?></span>
				<?php endif; ?>
			</a>

			<?php if ( $starter_footer_description ) : ?>
				<p class="footer-brand-row__description title-h5"><?php echo esc_html( $starter_footer_description ); ?></p>
			<?php endif; ?>
		</div>

		<div class="footer-row footer-row--columns">

			<div class="footer-col footer-info">
				<h2 class="footer-nav-col__title eyebrow"><?php echo esc_html( $starter_footer_contacts_title ); ?></h2>

				<?php if ( $starter_footer_contacts ) : ?>
					<ul class="footer-list">
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
				<?php endif; ?>

				<?php if ( $starter_footer_socials ) : ?>
					<ul class="footer-social-icons">
						<?php foreach ( $starter_footer_socials as $starter_social ) : ?>
							<li>
								<a href="<?php echo esc_url( $starter_social['url'] ); ?>" aria-label="<?php echo esc_attr( $starter_social['label'] ); ?>" target="_blank" rel="noopener noreferrer">
									<?php echo $starter_social['svg']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitizzato al salvataggio. ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<?php if ( $starter_payments_id ) : ?>
					<?php echo wp_get_attachment_image( $starter_payments_id, 'medium', false, array( 'class' => 'footer-payments', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php endif; ?>
			</div>

			<?php foreach ( $starter_footer_menus as $starter_location => $starter_title ) : ?>
				<?php if ( has_nav_menu( $starter_location ) ) : ?>
					<?php $starter_menu_name = starter_footer_menu_title( $starter_location, $starter_title ); ?>
					<nav class="footer-col" aria-label="<?php echo esc_attr( $starter_menu_name ); ?>">
						<h2 class="footer-nav-col__title eyebrow"><?php echo esc_html( $starter_menu_name ); ?></h2>
						<?php
						wp_nav_menu(
							array(
								'theme_location' => $starter_location,
								'container'      => false,
								'menu_class'     => 'footer-list',
								'fallback_cb'    => false,
								'depth'          => 1,
							)
						);
						?>
					</nav>
				<?php elseif ( 'footer-riferimenti' === $starter_location && starter_has_woocommerce() ) : ?>
					<?php // Nessun secondo menu assegnato: con WooCommerce si mostrano le pagine interne dello shop. ?>
					<?php $starter_shop_links = starter_footer_shop_links(); ?>
					<?php if ( $starter_shop_links ) : ?>
						<nav class="footer-col" aria-label="<?php esc_attr_e( 'Shop', 'slug-theme' ); ?>">
							<h2 class="footer-nav-col__title eyebrow"><?php esc_html_e( 'Shop', 'slug-theme' ); ?></h2>
							<ul class="footer-list">
								<?php foreach ( $starter_shop_links as $starter_shop_link ) : ?>
									<li><a href="<?php echo esc_url( $starter_shop_link[1] ); ?>"><?php echo esc_html( $starter_shop_link[0] ); ?></a></li>
								<?php endforeach; ?>
							</ul>
						</nav>
					<?php endif; ?>
				<?php endif; ?>
			<?php endforeach; ?>

			<?php $starter_free = starter_footer_free_html(); ?>
			<?php if ( $starter_free ) : ?>
				<div class="footer-col footer-form-col"><?php echo $starter_free; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitizzato in starter_footer_free_html(). ?></div>
			<?php endif; ?>

		</div>

		<div class="footer-row footer-row--bottom">
			<p class="footer-copyright"><?php echo esc_html( $starter_footer_copyright ); ?></p>

			<?php if ( $starter_legal || $starter_footer_credits_label ) : ?>
				<nav class="footer-legal" aria-label="<?php esc_attr_e( 'Link legali', 'slug-theme' ); ?>">
					<?php foreach ( $starter_legal as $starter_item ) : ?>
						<a href="<?php echo esc_url( $starter_item['url'] ? $starter_item['url'] : '#' ); ?>"<?php echo $starter_item['class'] ? ' class="' . esc_attr( $starter_item['class'] ) . '"' : ''; ?>><?php echo esc_html( $starter_item['label'] ); ?></a>
					<?php endforeach; ?>
					<?php if ( $starter_footer_credits_label ) : ?>
						<a href="<?php echo esc_url( $starter_footer_credits_url ? $starter_footer_credits_url : '#' ); ?>"><?php echo esc_html( $starter_footer_credits_label ); ?></a>
					<?php endif; ?>
				</nav>
			<?php endif; ?>
		</div>

	</div>
</footer>
