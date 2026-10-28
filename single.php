<?php
/**
 * Template per il singolo articolo (post_type "post"): titolo, data/autore/categorie, immagine, testo, tag,
 * riquadro autore, "Continua a leggere" e aside. Tutto si accende o spegne da Opzioni → Blog.
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<?php // Articolo singolo: titolo, foto, testo, condivisione, tag, autore, commenti e correlati ?>
<main class="site-content site-content--blog" id="site-content">

	<?php
	while ( have_posts() ) :
		the_post();
		?>

		<?php starter_breadcrumb_header(); ?>

		<?php // Intestazione: titolo e dati dell'articolo ?>
		<header class="blog-header">
			<h1<?php echo starter_live_attrs( array( 'post' => get_the_ID(), 'src' => 'post', 'key' => 'post_title', 'label' => __( 'Titolo', 'slug-theme' ), 'value' => get_post_field( 'post_title', get_the_ID() ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="blog-header__title title-h2"><?php the_title(); ?></h1>
			<?php starter_blog_meta( 'single', has_post_thumbnail() ? 'cats' : 'all' ); // Con la foto, data e autore vanno sopra l'immagine. ?>
		</header>

		<?php // Layout a due colonne: articolo + aside (se attivo) ?>
		<div class="blog-layout">

			<div class="blog-main">

				<?php starter_blog_aside_toggle(); ?>

				<?php // Articolo ?>
				<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

					<?php if ( has_post_thumbnail() ) : ?>
						<div class="entry-thumbnail">
							<?php starter_blog_meta( 'single', 'main' ); ?>
							<?php the_post_thumbnail( 'large', array( 'sizes' => '(max-width: 1100px) 100vw, 1200px' ) ); // Occupa tutta la colonna: il browser sceglie il file giusto. ?>
						</div>
					<?php endif; ?>

					<?php // Testo dell'articolo ?>
					<div class="entry-content">
						<?php
						the_content();

						wp_link_pages(
							array(
								'before' => '<nav class="page-links">' . esc_html__( 'Pagine:', 'slug-theme' ),
								'after'  => '</nav>',
							)
						);
						?>
					</div>

					<?php // Pulsanti "Condividi" ?>
					<?php if ( starter_blog_opt( 'share' ) ) : ?>
						<?php get_template_part( 'template-parts/blog/share' ); ?>
					<?php endif; ?>

					<?php // Tag a fine articolo ?>
					<?php if ( starter_blog_opt( 'single_tags' ) && has_tag() ) : ?>
						<footer class="entry-footer">
							<?php the_tags( '<div class="tags-links">', '<span class="tags-links__sep" aria-hidden="true">&middot;</span>', '</div>' ); ?>
						</footer>
					<?php endif; ?>

					<?php // Riquadro autore ?>
					<?php starter_blog_author_box(); ?>
				</article>

				<?php // Commenti (se attivi) ?>
				<?php if ( ! starter_comments_disabled() && ( comments_open() || get_comments_number() ) ) : ?>
					<?php comments_template(); ?>
				<?php endif; ?>

			</div>

			<?php
			if ( starter_blog_has_aside() ) {
				get_sidebar( 'blog' );
			}
			?>

		</div>

		<?php // Articoli correlati ("Continua a leggere") ?>
		<?php starter_blog_related(); ?>

	<?php endwhile; ?>

</main>

<?php
get_footer();
