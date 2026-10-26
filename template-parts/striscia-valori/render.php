<?php
/**
 * Blocco "Striscia valori": riga di voci con icona sopra e titolo (4 su desktop, 2 su tablet, 1 su mobile).
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$starter_items = (array) get_field( 'voci' );

if ( ! $starter_items ) {
	starter_block_placeholder( ! empty( $is_preview ), __( 'Striscia valori: aggiungi le voci.', 'slug-theme' ) );
	return;
}

$starter_wrapper = get_block_wrapper_attributes( array( 'class' => 'alignfull values-strip' ) );
?>
<div <?php echo $starter_wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<ul class="values-strip__list starter-container">
		<?php foreach ( $starter_items as $starter_item ) : ?>
			<li class="values-strip__item">
				<?php echo starter_icon( $starter_item['icona'], 34, 1.3 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<span class="values-strip__title title-h6"><?php echo starter_inline( $starter_item['titolo'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
