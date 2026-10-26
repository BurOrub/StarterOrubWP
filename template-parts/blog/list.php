<?php
/**
 * Elenco articoli condiviso da pagina Blog (home.php), archivi (archive.php) e ricerca (search.php):
 * intestazione della sezione, griglia di card, paginazione e aside. Opzioni in Opzioni → Blog.
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<main class="site-content site-content--blog" id="site-content">

	<?php starter_blog_render_header(); ?>

	<div class="blog-layout">

		<div class="blog-main">

			<?php starter_blog_aside_toggle(); ?>

			<?php if ( have_posts() ) : ?>

				<div class="blog-grid">
					<?php
					$starter_first = starter_blog_featured_active();

					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/content/card', null, array( 'featured' => $starter_first ) );
						$starter_first = false; // Solo il primo articolo è in evidenza.
					endwhile;
					?>
				</div>

				<?php starter_blog_pagination(); ?>

			<?php else : ?>

				<div class="blog-empty">
					<p class="txt"><?php echo esc_html( is_search() ? __( 'Nessun risultato per questa ricerca. Prova con altre parole.', 'slug-theme' ) : __( 'Nessun contenuto trovato.', 'slug-theme' ) ); ?></p>
					<?php get_search_form(); ?>
				</div>

			<?php endif; ?>

		</div>

		<?php
		if ( starter_blog_has_aside() ) {
			get_sidebar( 'blog' );
		}
		?>

	</div>

</main>
