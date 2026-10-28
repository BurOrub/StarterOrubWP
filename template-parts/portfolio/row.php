<?php
/**
 * Riga dell'elenco progetti (visualizzazione "Elenco" del blocco "Elenco progetti"): categorie e nome su una linea, al passaggio del mouse la linea
 * si colora e compare la copertina (o il video di copertina). Due versioni: "centro" (nome al centro, categoria a sinistra, un dato a destra) e
 * "sinistra" (nome a sinistra, categoria a destra, copertina grande e inclinata che esce dalla linea).
 *
 * $args['row']: align (centro|sinistra), right (riassunto|anno|freccia|nessuno), title_tag, title_size; $args['video']: video al passaggio (sì/no).
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$starter_row    = isset( $args['row'] ) ? (array) $args['row'] : array();
$starter_align  = 'sinistra' === ( $starter_row['align'] ?? '' ) ? 'sinistra' : 'centro';
$starter_right  = in_array( $starter_row['right'] ?? '', array( 'riassunto', 'anno', 'freccia', 'nessuno' ), true ) ? $starter_row['right'] : 'riassunto';
$starter_tags   = array_keys( starter_portfolio_tag_choices() );
$starter_tag    = in_array( $starter_row['title_tag'] ?? '', $starter_tags, true ) ? $starter_row['title_tag'] : 'h2';
$starter_size   = starter_portfolio_size_class( $starter_row['title_size'] ?? 'title-2' );
$starter_video  = ! isset( $args['video'] ) || $args['video'] ? starter_portfolio_hover_video() : '';
$starter_cats   = implode( ' / ', starter_portfolio_cats() );
$starter_sum    = has_excerpt() ? wp_strip_all_tags( get_the_excerpt() ) : '';
$starter_side   = '';

if ( 'riassunto' === $starter_right ) {
	$starter_side = $starter_sum;
} elseif ( 'anno' === $starter_right ) {
	$starter_side = get_the_date( 'Y' );
} elseif ( 'freccia' === $starter_right ) {
	$starter_side = starter_icon( 'arrow-up-right', 28, 1.6 );
}
?>
<article <?php post_class( 'project-row project-row--' . $starter_align ); ?> id="post-<?php the_ID(); ?>">
	<a class="project-card__link project-row__link" href="<?php the_permalink(); ?>" data-name="<?php echo esc_attr( starter_portfolio_client_name() ); ?>">
		<?php if ( 'centro' === $starter_align ) : ?>
			<span class="project-row__cats"><?php echo esc_html( $starter_cats ); ?></span>
		<?php endif; ?>

		<span class="project-row__name">
			<<?php echo esc_html( $starter_tag ); ?> class="project-row__title <?php echo esc_attr( $starter_size ); ?>"><?php the_title(); ?></<?php echo esc_html( $starter_tag ); ?>>
			<?php if ( has_post_thumbnail() ) : ?>
				<span class="project-row__preview project-card__media" aria-hidden="true">
					<?php the_post_thumbnail( 'large', array( 'sizes' => '(max-width: 900px) 40vw, 420px', 'loading' => 'lazy' ) ); ?>
					<?php if ( $starter_video ) : ?>
						<video class="project-card__video" muted loop playsinline disablepictureinpicture preload="none" tabindex="-1">
							<?php echo starter_video_sources( $starter_video ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- starter_video_sources() scappa gli URL. ?>
						</video>
					<?php endif; ?>
				</span>
			<?php endif; ?>
		</span>

		<?php if ( 'sinistra' === $starter_align ) : ?>
			<span class="project-row__cats"><?php echo esc_html( $starter_cats ); ?></span>
		<?php else : ?>
			<span class="project-row__side project-row__side--<?php echo esc_attr( $starter_right ); ?>"><?php echo 'freccia' === $starter_right ? $starter_side : esc_html( $starter_side ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- l'icona è già scappata da starter_icon(). ?></span>
		<?php endif; ?>
	</a>
</article>
