<?php
/**
 * Render del blocco ACF "starter-theme/example-block".
 *
 * Variabili disponibili (passate da ACF): $block, $content, $is_preview,
 * $block_id, $context.
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$heading = function_exists( 'get_field' ) ? get_field( 'heading' ) : '';
$text    = function_exists( 'get_field' ) ? get_field( 'text' ) : '';

$wrapper_attributes = get_block_wrapper_attributes();
?>

<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>

	<?php if ( $heading ) : ?>
		<h2 class="example-block__heading"><?php echo esc_html( $heading ); ?></h2>
	<?php endif; ?>

	<?php if ( $text ) : ?>
		<div class="example-block__text"><?php echo wp_kses_post( $text ); ?></div>
	<?php endif; ?>

	<?php if ( ! $heading && ! $text && ! empty( $is_preview ) ) : ?>
		<p class="example-block__placeholder">
			<?php esc_html_e( 'Example Block: aggiungi un gruppo di campi ACF con location "Block: Example Block" per personalizzarlo.', 'starter-theme' ); ?>
		</p>
	<?php endif; ?>

</div>
