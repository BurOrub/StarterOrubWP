<?php
/**
 * Archivio prodotti (override di WooCommerce): pagina Shop e pagine delle categorie.
 *
 * Testata, filtri, ordinamento e griglia nella larghezza dei contenuti (Opzioni → Generale). I filtri sono quelli attivi in Opzioni → Shop (inc/filters.php).
 *
 * @package Starter_Theme
 * @version 8.6.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

$starter_title = woocommerce_page_title( false );
?>

<main class="site-content shop-archive" id="site-content">

	<?php
	// Pagina Shop: testo introduttivo = contenuto della pagina (blocchi), così si può modificare dal frontend con Starter Live.
	$starter_shop_id = ( is_shop() && function_exists( 'wc_get_page_id' ) ) ? (int) wc_get_page_id( 'shop' ) : 0;

	if ( $starter_shop_id > 0 && in_array( absint( get_query_var( 'paged' ) ), array( 0, 1 ), true ) ) {
		$starter_lead = (string) apply_filters( 'the_content', get_post_field( 'post_content', $starter_shop_id ) );
	} else {
		ob_start();
		do_action( 'woocommerce_archive_description' );
		$starter_lead = ob_get_clean();
	}

	$starter_shop_eyebrow = ( $starter_shop_id > 0 && metadata_exists( 'post', $starter_shop_id, 'starter_eyebrow' ) )
		? (string) get_post_meta( $starter_shop_id, 'starter_eyebrow', true )
		: (string) apply_filters( 'starter_shop_eyebrow', __( 'Negozio online: prodotti selezionati', 'slug-theme' ) );

	// Categoria/tag prodotto: occhiello (campo extra del termine, altrimenti "Categoria") e titolo modificabili dal frontend.
	$starter_term     = is_product_taxonomy() ? get_queried_object() : null;
	$starter_term_eyebrow = '';
	$starter_live_args    = $starter_shop_id > 0 ? array( 'post' => $starter_shop_id, 'title' => true, 'lead' => true ) : array();

	if ( $starter_term instanceof WP_Term ) {
		$starter_term_eyebrow = metadata_exists( 'term', $starter_term->term_id, 'starter_eyebrow' ) ? (string) get_term_meta( $starter_term->term_id, 'starter_eyebrow', true ) : __( 'Categoria', 'slug-theme' );
		$starter_live_args    = array( 'term' => $starter_term->term_id, 'tax' => $starter_term->taxonomy, 'title' => true, 'lead' => true );
	}

	starter_page_header(
		array(
			// Pagina Shop: occhiello = H1 e titolo = H2. Categorie: occhiello "Categoria" (span) e titolo = H1.
			'eyebrow'    => is_product_category() ? $starter_term_eyebrow : $starter_shop_eyebrow,
			'title'      => $starter_title,
			'lead_html'  => $starter_lead,
			'eyebrow_h1' => ! is_product_taxonomy(),
			'live'       => $starter_live_args,
		)
	);
	?>

	<section class="shop-products" aria-label="<?php esc_attr_e( 'Prodotti', 'slug-theme' ); ?>">
		<div class="starter-container shop-products__inner">
			<?php woocommerce_output_all_notices(); ?>

			<div class="filters-wrap<?php echo starter_shop_filters() ? ' has-filters' : ''; ?>">
				<?php starter_shop_filters_render(); ?>

				<div class="filters-toolbar">
					<?php starter_shop_filters_toggle(); ?>
					<?php starter_shop_result_count(); ?>
					<?php
					if ( woocommerce_product_loop() ) {
						woocommerce_catalog_ordering();
					}
					?>
				</div>
			</div>

			<?php if ( woocommerce_product_loop() ) : ?>
				<?php
				woocommerce_product_loop_start();

				if ( wc_get_loop_prop( 'total' ) ) {
					while ( have_posts() ) {
						the_post();
						do_action( 'woocommerce_shop_loop' );
						wc_get_template_part( 'content', 'product' );
					}
				}

				woocommerce_product_loop_end();
				woocommerce_pagination();
				?>
			<?php else : ?>
				<div class="shop-empty">
					<p class="txt"><?php esc_html_e( 'Nessun prodotto corrisponde ai filtri scelti.', 'slug-theme' ); ?></p>
					<?php if ( starter_shop_filters_active_count() ) : ?>
						<a class="button button--outline" href="<?php echo esc_url( starter_shop_filters_reset_url() ); ?>"><?php echo starter_flip_text( __( 'Azzera i filtri', 'slug-theme' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>

</main>

<?php
get_footer( 'shop' );
