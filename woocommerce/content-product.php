<?php
/**
 * Card prodotto del negozio (override di WooCommerce): la stessa card in shop, categorie, ricerca, preferiti e
 * nel blocco "Prodotti in evidenza". Il markup vero è in template-parts/content/card-product.php.
 *
 * @package Starter_Theme
 * @version 9.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! is_a( $product, WC_Product::class ) || ! $product->is_visible() ) {
	return;
}
?>
<li <?php wc_product_class( 'product-card-item', $product ); ?>>
	<?php get_template_part( 'template-parts/content/card-product' ); ?>
</li>
