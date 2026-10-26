<?php
/**
 * Template per la pagina 404 (contenuto non trovato): stesso titolo di pagina del resto del sito, campo di ricerca e
 * qualche link per ripartire.
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main class="site-content site-content--page" id="site-content">

	<?php
	starter_page_header(
		array(
			'eyebrow'   => __( 'Errore 404', 'slug-theme' ),
			'title'     => __( 'Pagina non trovata', 'slug-theme' ),
			'lead_html' => '<p>' . esc_html__( 'La pagina che stai cercando non esiste o è stata spostata. Prova a cercare quello che ti serve.', 'slug-theme' ) . '</p>',
		)
	);
	?>

	<div class="error-404 starter-container">
		<?php get_search_form(); ?>

		<div class="error-404__links">
			<a class="button button--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo starter_flip_text( __( 'Torna alla home', 'slug-theme' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>

			<?php if ( (int) get_option( 'page_for_posts' ) ) : ?>
				<a class="button button--outline" href="<?php echo esc_url( get_permalink( (int) get_option( 'page_for_posts' ) ) ); ?>"><?php echo starter_flip_text( __( 'Vai al blog', 'slug-theme' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
			<?php endif; ?>

			<?php if ( starter_has_woocommerce() ) : ?>
				<a class="button button--outline" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php echo starter_flip_text( __( 'Scopri il negozio', 'slug-theme' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
			<?php endif; ?>
		</div>
	</div>

</main>

<?php
get_footer();
