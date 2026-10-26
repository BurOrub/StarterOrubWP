<?php
/**
 * Footer del tema: carica lo stile scelto in Personalizza → Opzioni →
 * Footer → Versione footer (vedi template-parts/footer/footer-{slug}.php
 * e starter_footer_style_choices() in functions.php).
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$starter_footer_style = get_theme_mod( 'starter_footer_style', 'style-1' );

get_template_part( 'template-parts/footer/footer', $starter_footer_style );

get_template_part( 'template-parts/footer/whatsapp-button' );
?>

<div class="lightbox" id="lightbox" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Galleria immagini', 'slug-theme' ); ?>" hidden>
	<div class="lightbox__bar">
		<span class="lightbox__caption" id="lightbox-caption"></span>
		<button type="button" class="round-button round-button--light" data-lightbox-close aria-label="<?php esc_attr_e( 'Chiudi', 'slug-theme' ); ?>"><?php echo starter_icon( 'x', 22, 2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
	</div>
	<div class="lightbox__stage">
		<button type="button" class="round-button round-button--light" data-lightbox-step="-1" aria-label="<?php esc_attr_e( 'Precedente', 'slug-theme' ); ?>"><?php echo starter_icon( 'chevron-left', 22, 2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
		<img class="lightbox__img" id="lightbox-img" src="" alt="">
		<button type="button" class="round-button round-button--light" data-lightbox-step="1" aria-label="<?php esc_attr_e( 'Successiva', 'slug-theme' ); ?>"><?php echo starter_icon( 'chevron-right', 22, 2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
	</div>
</div>

	<?php wp_footer(); ?>
</body>
</html>
