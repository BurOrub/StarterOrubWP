<?php
/**
 * Card di un elemento nella griglia (".blog-grid"/".blog-card"): usata da pagina Blog, archivi, ricerca e dal
 * "Continua a leggere" a fine articolo. Cosa mostrare (data, autore, categorie, tag) si sceglie in Opzioni → Blog.
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// $args['featured'] = true: card in evidenza (primo articolo, opzione Blog → "Primo articolo in evidenza").
$starter_featured = ! empty( $args['featured'] );
?>

<article <?php post_class( $starter_featured ? 'blog-card blog-card--featured' : 'blog-card' ); ?> id="post-<?php the_ID(); ?>">

	<a class="blog-card__thumbnail" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( $starter_featured ? 'large' : 'starter-theme-card' ); ?>
		<?php else : ?>
			<span class="blog-card__placeholder"></span>
		<?php endif; ?>
	</a>

	<?php starter_blog_meta( 'list', 'main' ); // Data e autore: etichetta sopra l'immagine. ?>

	<div class="blog-card__body">
		<?php starter_blog_meta( 'list', 'cats' ); ?>

		<h2 class="entry-title <?php echo $starter_featured ? 'title-h4' : 'title-h5'; ?>">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h2>

		<div class="entry-summary">
			<p class="has-small-font-size"><?php echo esc_html( starter_excerpt_chars( get_the_ID(), $starter_featured ? 260 : 140 ) ); ?></p>
		</div>

		<?php starter_blog_card_tags(); ?>

		<a class="button button--outline read-more" href="<?php the_permalink(); ?>">
			<?php echo starter_flip_text( __( 'Leggi tutto', 'slug-theme' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php echo starter_icon( 'arrow-right', 16, 2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<span class="screen-reader-text"><?php the_title(); ?></span>
		</a>
	</div>
</article>
