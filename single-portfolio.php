<?php
/**
 * Singolo progetto del Portfolio: testata (categorie, titolo, dati del progetto), copertina (video o immagine),
 * contenuto fatto con i blocchi "Progetto — testo / media / crediti" e "Altri progetti".
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main class="site-content site-content--page site-content--project" id="site-content">

	<?php
	while ( have_posts() ) :
		the_post();

		$starter_id = get_the_ID();
		ob_start();
		get_template_part( 'template-parts/portfolio/project-head', null, array( 'post_id' => $starter_id ) );
		echo apply_filters( 'starter_live_post_html', ob_get_clean(), $starter_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- HTML già scappato nella testata; il filtro (Starter Live) aggiunge solo i marcatori di modifica.
		?>

		<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
		</article>

		<?php starter_portfolio_related(); ?>

	<?php endwhile; ?>

</main>

<?php
get_footer();
