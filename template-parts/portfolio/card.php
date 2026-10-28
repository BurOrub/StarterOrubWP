<?php
/**
 * Card di un progetto: immagine, titolo e categorie. Usata dal blocco "Elenco progetti" e da "Altri progetti".
 * $args['cats']: stile delle categorie (testo | tab_sotto | tab_sopra | nessuna, predefinito testo);
 * $args['reveal']: animazione logo (vedi starter_portfolio_reveal_html()); $args['video']: video al passaggio del mouse (predefinito sì); $args['titles']: riassunto e cliente (ordine, tag, misura, colore), vedi starter_portfolio_titles_html(); $args['cats_opts']: simbolo, tab piene/vuote, forma, maiuscole e posizione (prima/dopo il titolo), vedi starter_portfolio_cats_html(). Si sceglie nel blocco "Elenco progetti". $args['img_mb']: margine sotto l'immagine.
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$starter_style  = isset( $args['cats'] ) && is_string( $args['cats'] ) ? $args['cats'] : 'testo';
$starter_cats   = starter_portfolio_cats_html( starter_portfolio_cats(), $starter_style, isset( $args['cats_opts'] ) ? (array) $args['cats_opts'] : array() );
$starter_video  = ! isset( $args['video'] ) || $args['video'] ? starter_portfolio_hover_video() : '';
$starter_reveal = ! empty( $args['reveal'] ) ? starter_portfolio_reveal_html( (array) $args['reveal'] ) : '';
$starter_titles = isset( $args['titles'] ) ? (array) $args['titles'] : array();
$starter_titles_under = starter_portfolio_titles_html( $starter_titles, 0, 'sotto' );
$starter_title_hidden = false !== strpos( $starter_titles_under, 'screen-reader-text' ); // Voci spente: resta solo il titolo per gli screen reader.
$starter_cats_below   = $starter_cats && 'tab_sopra' !== $starter_style;
$starter_has_info     = $starter_cats_below || ( $starter_titles_under && ! $starter_title_hidden );
$starter_img_mb = isset( $args['img_mb'] ) && in_array( $args['img_mb'], array_keys( starter_portfolio_margin_choices( 'mb' ) ), true ) ? (string) $args['img_mb'] : ''; // Margine sotto l'immagine (mb05, mb1, mb2…).
$starter_cats_first = 'tab_sopra' !== $starter_style && isset( $args['cats_opts']['position'] ) && 'prima' === $args['cats_opts']['position'];
?>
<article <?php post_class( 'project-card' ); ?> id="post-<?php the_ID(); ?>">
	<a class="project-card__link" href="<?php the_permalink(); ?>" data-name="<?php echo esc_attr( starter_portfolio_client_name() ); ?>">
		<span class="project-card__media<?php echo $starter_img_mb ? ' ' . esc_attr( $starter_img_mb ) : ''; ?>">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'large', array( 'sizes' => '(max-width: 640px) 100vw, (max-width: 1100px) 50vw, 33vw' ) ); ?>
			<?php else : ?>
				<span class="project-card__placeholder"></span>
			<?php endif; ?>
			<?php if ( $starter_video ) : ?>
				<video class="project-card__video" muted loop playsinline disablepictureinpicture preload="none" aria-hidden="true" tabindex="-1">
					<?php echo starter_video_sources( $starter_video ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- starter_video_sources() scappa gli URL. ?>
				</video>
			<?php endif; ?>
			<?php echo $starter_reveal; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- HTML scappato da starter_portfolio_reveal_html(). ?>
			<?php
			if ( 'tab_sopra' === $starter_style ) {
				echo $starter_cats; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- HTML costruito e scappato da starter_portfolio_cats_html().
			}

			echo starter_portfolio_titles_html( $starter_titles, 0, 'sopra' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- HTML scappato da starter_portfolio_titles_html().
			?>
		</span>
		<?php if ( $starter_title_hidden ) : ?>
			<?php echo $starter_titles_under; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- HTML scappato da starter_portfolio_titles_html(). ?>
		<?php endif; ?>
		<?php if ( $starter_has_info ) : // Il blocco sotto l'immagine esiste solo se ha contenuto: vuoto lascerebbe uno spazio in più nella griglia. ?>
		<span class="project-card__info<?php echo $starter_cats_first && $starter_cats ? ' project-card__info--cats-first' : ''; ?>">
			<?php if ( $starter_cats && $starter_cats_first ) : ?>
				<?php echo $starter_cats; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- HTML costruito e scappato da starter_portfolio_cats_html(). ?>
			<?php endif; ?>
			<?php if ( ! $starter_title_hidden ) : ?>
				<?php echo $starter_titles_under; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- HTML scappato da starter_portfolio_titles_html(). ?>
			<?php endif; ?>
			<?php if ( $starter_cats_below && ! $starter_cats_first ) : ?>
				<?php echo $starter_cats; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- HTML costruito e scappato da starter_portfolio_cats_html(). ?>
			<?php endif; ?>
		</span>
		<?php endif; ?>
	</a>
</article>
