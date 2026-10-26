<?php
/**
 * Aside del blog (a destra su desktop; su tablet/mobile pannello aperto dal pulsante "Filtri e ricerca").
 *
 * Se in Aspetto → Widget l'area "Sidebar Blog" ha dei widget, si usano quelli (l'ordine lo decidi tu).
 * Altrimenti il tema stampa un aside di base: cerca, categorie (col numero di articoli), ultimi articoli, tag.
 * Si spegne da Opzioni → Blog (aside nell'elenco / negli articoli).
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<aside class="sidebar sidebar--blog" id="blog-sidebar" aria-label="<?php esc_attr_e( 'Filtri e ricerca', 'slug-theme' ); ?>">
	<?php // Pulsante di chiusura del pannello (tablet e mobile) ?>
	<button type="button" class="sidebar-close" id="sidebar-close" aria-label="<?php esc_attr_e( 'Chiudi', 'slug-theme' ); ?>">
		&times;
	</button>

	<?php // Widget scelti in Aspetto → Widget, se ci sono ?>
	<?php if ( is_active_sidebar( 'sidebar-blog' ) ) : ?>

		<?php dynamic_sidebar( 'sidebar-blog' ); ?>

	<?php else : ?>

		<?php // Aside di base — cerca ?>
		<section class="sidebar-block sidebar-block--search">
			<h2 class="sidebar-block__title title-h5"><?php esc_html_e( 'Cerca', 'slug-theme' ); ?></h2>
			<?php get_search_form(); ?>
		</section>

		<?php // Aside di base — categorie col numero di articoli ?>
		<?php
		$starter_categories = get_categories(
			array(
				'hide_empty' => true,
				'exclude'    => array( (int) get_option( 'default_category' ) ),
			)
		);
		?>
		<?php if ( $starter_categories ) : ?>
			<section class="sidebar-block">
				<h2 class="sidebar-block__title title-h5"><?php esc_html_e( 'Categorie', 'slug-theme' ); ?></h2>
				<ul class="sidebar-block__list">
					<?php foreach ( $starter_categories as $starter_cat ) : ?>
						<li<?php echo is_category( $starter_cat->term_id ) ? ' class="is-current"' : ''; ?>>
							<a href="<?php echo esc_url( get_category_link( $starter_cat ) ); ?>"><?php echo esc_html( $starter_cat->name ); ?></a>
							<span class="sidebar-block__count"><?php echo (int) $starter_cat->count; ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
			</section>
		<?php endif; ?>

		<?php // Aside di base — ultimi articoli (esclude quello aperto) ?>
		<?php
		$starter_latest = new WP_Query(
			array(
				'post_type'           => 'post',
				'posts_per_page'      => 5,
				'post__not_in'        => is_singular( 'post' ) ? array( get_queried_object_id() ) : array(),
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
			)
		);
		?>
		<?php if ( $starter_latest->have_posts() ) : ?>
			<section class="sidebar-block">
				<h2 class="sidebar-block__title title-h5"><?php esc_html_e( 'Ultimi articoli', 'slug-theme' ); ?></h2>
				<ul class="sidebar-block__list">
					<?php
					while ( $starter_latest->have_posts() ) :
						$starter_latest->the_post();
						?>
						<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
					<?php endwhile; ?>
				</ul>
			</section>
			<?php wp_reset_postdata(); ?>
		<?php endif; ?>

		<?php // Aside di base — tag più usati ?>
		<?php $starter_tags = get_tags( array( 'orderby' => 'count', 'order' => 'DESC', 'number' => 20 ) ); ?>
		<?php if ( $starter_tags ) : ?>
			<section class="sidebar-block">
				<h2 class="sidebar-block__title title-h5"><?php esc_html_e( 'Tag', 'slug-theme' ); ?></h2>
				<div class="tags-links">
					<?php foreach ( $starter_tags as $starter_i => $starter_tag ) : ?>
						<?php echo $starter_i ? '<span class="tags-links__sep" aria-hidden="true">&middot;</span>' : ''; ?>
						<a href="<?php echo esc_url( get_tag_link( $starter_tag ) ); ?>"><?php echo esc_html( $starter_tag->name ); ?></a>
					<?php endforeach; ?>
				</div>
			</section>
		<?php endif; ?>

	<?php endif; ?>
</aside>
