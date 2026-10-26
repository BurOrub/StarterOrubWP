<?php
/**
 * Template di fallback principale.
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<?php // Elenco di ripiego (ultimi articoli) quando non c'è un template più specifico ?>
<main class="site-content" id="site-content">

	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : ?>
			<?php the_post(); ?>

			<?php // Scheda articolo: titolo, immagine ed estratto ?>
			<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
				<header class="entry-header">
					<?php
					if ( is_singular() ) {
						the_title( '<h1 class="entry-title up">', '</h1>' );
					} else {
						the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
					}
					?>
				</header>

				<?php if ( has_post_thumbnail() ) : ?>
					<div class="entry-thumbnail">
						<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'starter-theme-card' ); ?></a>
					</div>
				<?php endif; ?>

				<div class="entry-summary">
					<?php the_excerpt(); ?>
				</div>
			</article>

		<?php endwhile; ?>

		<?php
		the_posts_pagination(
			array(
				'prev_text' => __( 'Precedenti', 'starter-theme' ),
				'next_text' => __( 'Successivi', 'starter-theme' ),
			)
		);
		?>

	<?php else : ?>

		<p><?php esc_html_e( 'Nessun contenuto trovato.', 'starter-theme' ); ?></p>

	<?php endif; ?>

</main>

<?php
get_footer();
