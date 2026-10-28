<?php
/**
 * Template per le pagine (post_type "page").
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
	while ( have_posts() ) :
		the_post();
		?>

		<?php
		/*
		 * Titolo di pagina sempre uguale: se il contenuto ha già il suo (blocco "Intestazione pagina", hero, un H1) o è la Home, il tema non aggiunge nulla; altrimenti
		 * (Privacy, Cookie, Preferiti, carrello, cassa, account…) lo stampa il tema nello stesso formato, con le briciole.
		 */
		$starter_content = (string) get_post_field( 'post_content', get_the_ID() );

		// Titolo e briciole: non nella home né se la pagina ha già il suo titolo
		if ( is_front_page() || starter_page_has_own_header() ) {
			// Home e pagine che aprono con la loro hero o intestazione: niente titolo aggiunto dal tema.
			$starter_title_done = true;
		} elseif ( str_contains( $starter_content, '<h1' ) || str_contains( $starter_content, '"level":1' ) ) {
			starter_breadcrumb_header();
		} else {
			starter_page_header( array( 'title' => get_the_title(), 'live' => array( 'post' => get_the_ID(), 'title' => true ) ) );
		}
		?>

		<?php // Contenuto della pagina ?>
		<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="entry-thumbnail">
					<?php the_post_thumbnail( 'large' ); ?>
				</div>
			<?php endif; ?>

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
		</article>

		<?php // Commenti (se attivi) ?>
		<?php if ( ! starter_comments_disabled() && ( comments_open() || get_comments_number() ) ) : ?>
			<?php comments_template(); ?>
		<?php endif; ?>

	<?php endwhile; ?>

</main>

<?php
get_footer();
