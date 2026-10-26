<?php
/**
 * Card prodotto: foto quadrata (seconda foto della galleria all'hover), cuore "preferiti", "Aggiungi al
 * carrello" che compare all'hover, categorie, nome, descrizione breve, prezzo. Usa il $product globale.
 * Cosa mostrare si sceglie in Opzioni → Shop → Card prodotto.
 *
 * @package Starter_Theme
 */

defined( 'ABSPATH' ) || exit;

global $product;

$starter_link     = get_permalink( $product->get_id() );
$starter_name     = $product->get_name();
$starter_image_id = (int) $product->get_image_id();
$starter_hover_id = get_theme_mod( 'starter_shop_card_hover_image', true ) ? starter_product_hover_image_id( $product ) : 0;
$starter_cats     = get_theme_mod( 'starter_shop_card_categories', true ) ? starter_product_categories( $product->get_id() ) : array();
$starter_text     = get_theme_mod( 'starter_shop_card_description', false ) ? starter_product_card_text( $product ) : '';
?>
<article class="product-card">
	<div class="product-card__media">
		<a class="product-card__img" href="<?php echo esc_url( $starter_link ); ?>" tabindex="-1" aria-hidden="true">
			<?php
			if ( $starter_image_id ) {
				echo wp_get_attachment_image(
					$starter_image_id,
					'woocommerce_thumbnail',
					false,
					array(
						'class'   => 'product-card__base' . ( $starter_hover_id ? ' has-alt' : '' ),
						'alt'     => $starter_name,
						'loading' => 'lazy',
						'sizes'   => '(max-width: 600px) 50vw, (max-width: 1280px) 33vw, 25vw',
					)
				);
			} else {
				echo wc_placeholder_img( 'woocommerce_thumbnail', array( 'class' => 'product-card__base' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			if ( $starter_hover_id ) {
				echo wp_get_attachment_image(
					$starter_hover_id,
					'woocommerce_thumbnail',
					false,
					array(
						'class'   => 'product-card__alt',
						'alt'     => '',
						'loading' => 'lazy',
						'sizes'   => '(max-width: 600px) 50vw, (max-width: 1280px) 33vw, 25vw',
					)
				);
			}
			?>
		</a>

		<?php if ( get_theme_mod( 'starter_shop_card_wishlist', true ) ) : ?>
			<button type="button" class="product-card__wish" aria-pressed="false" data-wishlist="<?php echo esc_attr( $product->get_id() ); ?>">
				<span class="screen-reader-text">
					<?php
					/* translators: %s: nome del prodotto. */
					echo esc_html( sprintf( __( 'Aggiungi %s ai preferiti', 'slug-theme' ), $starter_name ) );
					?>
				</span>
				<?php echo starter_icon( 'heart', 19, 1.5 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</button>
		<?php endif; ?>

		<?php if ( get_theme_mod( 'starter_shop_card_add_to_cart', true ) ) : ?>
			<div class="product-card__add">
				<?php woocommerce_template_loop_add_to_cart(); ?>
			</div>
		<?php endif; ?>
	</div>

	<?php if ( $starter_cats ) : ?>
		<div class="product-card__cat has-small-font-size">
			<?php foreach ( $starter_cats as $starter_term ) : ?>
				<a href="<?php echo esc_url( get_term_link( $starter_term ) ); ?>"><?php echo esc_html( $starter_term->name ); ?></a>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<h3 class="product-card__name title-h5"><a href="<?php echo esc_url( $starter_link ); ?>"><?php echo esc_html( $starter_name ); ?></a></h3>

	<?php if ( $starter_text ) : ?>
		<p class="product-card__desc has-small-font-size"><?php echo esc_html( $starter_text ); ?></p>
	<?php endif; ?>

	<span class="product-card__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
</article>
