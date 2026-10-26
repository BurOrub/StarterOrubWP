<?php
/**
 * Footer — Stile 5 (titolo grande).
 *
 * Nome del sito gigante a tutta larghezza (si adatta da solo), frase grande, contatti e social a sinistra, sedi a destra e riga finale
 * con crediti. Colori di sfondo e testo: quelli del footer (Opzioni → Colori). Testi: Opzioni → Footer (Testo = riga sopra il nome,
 * Frase grande, Contatti, Sedi).
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Dati del footer: social, frase, frase grande e contatti
$starter_footer_socials  = get_theme_mod( 'starter_social_show_footer', true ) ? starter_get_social_links() : array();
$starter_footer_tagline  = starter_wpml_translate_string( 'footer_description', get_theme_mod( 'starter_footer_description', '' ) );
$starter_footer_headline = starter_wpml_translate_string( 'footer_headline', get_theme_mod( 'starter_footer_headline', '' ) );
$starter_footer_contacts = starter_get_footer_contacts();
$starter_footer_places   = array();

// Sedi: ogni blocco separato da una riga vuota (la prima riga è il titolo)
foreach ( preg_split( '/(?:\r\n|\r|\n){2,}/', trim( (string) starter_wpml_translate_string( 'footer_locations', get_theme_mod( 'starter_footer_locations', '' ) ) ) ) as $starter_block ) {
	$starter_lines = array_values( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', $starter_block ) ), 'strlen' ) );

	if ( $starter_lines ) {
		$starter_footer_places[] = array(
			'title' => array_shift( $starter_lines ),
			'lines' => $starter_lines,
		);
	}
}

// Copyright (con {anno} e {sito}) e link legali
$starter_footer_copyright = str_replace(
	array( '{anno}', '{sito}' ),
	array( gmdate( 'Y' ), get_bloginfo( 'name' ) ),
	starter_wpml_translate_string( 'footer_copyright', get_theme_mod( 'starter_footer_copyright', __( '© {anno} {sito}. Tutti i diritti riservati.', 'slug-theme' ) ) )
);

$starter_footer_legal = array();

foreach ( array( 'privacy', 'cookie', 'terms' ) as $starter_legal ) {
	$starter_label = starter_wpml_translate_string( "footer_{$starter_legal}_label", get_theme_mod( "starter_footer_{$starter_legal}_label", '' ) );

	if ( $starter_label ) {
		$starter_footer_legal[] = array(
			'label' => $starter_label,
			'url'   => get_theme_mod( "starter_footer_{$starter_legal}_url", '' ) ? get_theme_mod( "starter_footer_{$starter_legal}_url", '' ) : '#',
		);
	}
}

$starter_credits_label = starter_wpml_translate_string( 'footer_credits_label', get_theme_mod( 'starter_footer_credits_label', '' ) );

if ( $starter_credits_label ) {
	$starter_footer_legal[] = array(
		'label' => $starter_credits_label,
		'url'   => get_theme_mod( 'starter_footer_credits_url', '' ) ? get_theme_mod( 'starter_footer_credits_url', '' ) : '#',
	);
}
?>

<?php // Footer Stile 5: nome gigante, frase, contatti, sedi e riga finale ?>
<footer class="site-footer site-footer--mega" id="site-footer">
	<div class="site-footer__inner">

		<?php // Riga sopra il nome e nome del sito a tutta larghezza ?>
		<div class="footer-mega__head">
			<?php if ( $starter_footer_tagline ) : ?>
				<p class="footer-mega__tagline"><?php echo esc_html( $starter_footer_tagline ); ?></p>
			<?php endif; ?>
			<p class="footer-mega__name"><span><?php bloginfo( 'name' ); ?></span></p>
		</div>

		<?php // Frase grande ?>
		<?php if ( $starter_footer_headline ) : ?>
			<p class="footer-mega__headline"><?php echo nl2br( esc_html( $starter_footer_headline ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- scappato sopra, nl2br aggiunge solo <br>. ?></p>
		<?php endif; ?>

		<?php // Colonne: contatti e social a sinistra, sedi a destra ?>
		<?php if ( $starter_footer_contacts || $starter_footer_socials || $starter_footer_places ) : ?>
			<div class="footer-mega__cols">

				<?php if ( $starter_footer_contacts || $starter_footer_socials ) : ?>
					<div class="footer-mega__contacts">
						<?php if ( $starter_footer_contacts ) : ?>
							<ul class="footer-mega__list">
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
							<ul class="footer-mega__social">
								<?php foreach ( $starter_footer_socials as $starter_social ) : ?>
									<li><a href="<?php echo esc_url( $starter_social['url'] ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $starter_social['label'] ); ?></a></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php if ( $starter_footer_places ) : ?>
					<div class="footer-mega__places">
						<?php foreach ( $starter_footer_places as $starter_place ) : ?>
							<div class="footer-mega__place">
								<h3 class="footer-mega__place-title"><?php echo esc_html( $starter_place['title'] ); ?></h3>
								<p><?php echo implode( '<br>', array_map( 'esc_html', $starter_place['lines'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- scappato riga per riga. ?></p>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

			</div>
		<?php endif; ?>

		<?php // Riga finale: copyright e link legali ?>
		<div class="footer-row footer-row--bottom">
			<p class="footer-copyright"><?php echo esc_html( $starter_footer_copyright ); ?></p>

			<?php if ( $starter_footer_legal ) : ?>
				<nav class="footer-legal" aria-label="<?php esc_attr_e( 'Link legali', 'slug-theme' ); ?>">
					<?php foreach ( $starter_footer_legal as $starter_item ) : ?>
						<a href="<?php echo esc_url( $starter_item['url'] ); ?>"><?php echo esc_html( $starter_item['label'] ); ?></a>
					<?php endforeach; ?>
				</nav>
			<?php endif; ?>
		</div>

	</div>
</footer>
