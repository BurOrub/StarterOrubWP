<?php
/**
 * Testata del progetto: copertina a tutto schermo (se scelta), titolo con categorie, dati del progetto, copertina.
 * Usata dalla scheda sul sito (single-portfolio.php) e dall'anteprima nell'editor (blocco "Progetto — testata").
 *
 * Argomenti: post_id, preview (true nell'editor: niente rivelazione allo scroll, briciole e modifica dal vivo).
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$starter_id      = ! empty( $args['post_id'] ) ? (int) $args['post_id'] : get_the_ID();
$starter_preview = ! empty( $args['preview'] );
$starter_reveal  = $starter_preview ? '' : ' data-reveal';

$starter_cats       = starter_portfolio_cats( $starter_id );
$starter_text       = trim( (string) starter_field( 'progetto_testo', $starter_id ) ); // Testo editoriale sotto il titolo.
$starter_summary    = trim( wp_strip_all_tags( (string) get_post_field( 'post_excerpt', $starter_id ) ) ); // Riassunto: è l'H1, il nome del progetto è l'H2.
$starter_link       = starter_field( 'progetto_link', $starter_id );
$starter_link_host  = is_array( $starter_link ) && ! empty( $starter_link['url'] ) ? preg_replace( '/^www\./i', '', (string) wp_parse_url( $starter_link['url'], PHP_URL_HOST ) ) : ''; // Il pulsante mostra il dominio del sito.
$starter_video      = starter_field( 'progetto_video', $starter_id );
$starter_video      = is_array( $starter_video ) && ! empty( $starter_video['url'] ) ? $starter_video['url'] : '';
$starter_cover_full = (bool) starter_field( 'progetto_copertina_full', $starter_id ) && has_post_thumbnail( $starter_id );
// Agenzie e partner: ogni riga ha un tipo; nella testata stanno in due voci affiancate (prima le agenzie, poi i partner). Le righe senza tipo sono agenzie.
$starter_collabs    = array( 'agenzia' => array(), 'partner' => array() );

foreach ( (array) starter_field( 'progetto_agenzie', $starter_id ) as $starter_row ) {
	if ( is_array( $starter_row ) && ! empty( $starter_row['link']['title'] ) ) {
		$starter_collabs[ 'partner' === ( $starter_row['tipo'] ?? '' ) ? 'partner' : 'agenzia' ][] = $starter_row['link'];
	}
}
$starter_agencies   = array_merge( $starter_collabs['agenzia'], $starter_collabs['partner'] );
$starter_meta       = starter_portfolio_opt( 'meta' ) && ( $starter_cats || $starter_agencies || ( is_array( $starter_link ) && ! empty( $starter_link['url'] ) ) );
$starter_title      = get_the_title( $starter_id );
$starter_thumb      = has_post_thumbnail( $starter_id );
$starter_img_attr   = $starter_preview ? array() : array( 'loading' => 'eager', 'fetchpriority' => 'high' );
$starter_media      = static function () use ( $starter_id, $starter_video, $starter_thumb, $starter_img_attr ) {
	if ( $starter_video ) {
		?>
		<video autoplay muted loop playsinline disablepictureinpicture controlslist="nodownload nofullscreen noremoteplayback" preload="auto"<?php echo $starter_thumb ? ' poster="' . esc_url( (string) get_the_post_thumbnail_url( $starter_id, 'large' ) ) . '"' : ''; ?>>
			<?php echo starter_video_sources( $starter_video ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- starter_video_sources() scappa gli URL. ?>
		</video>
		<?php
	} else {
		echo get_the_post_thumbnail( $starter_id, 'full', $starter_img_attr ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
};
?>

<?php if ( $starter_cover_full ) : ?>
	<figure class="alignfull project-cover project-cover--full">
		<?php $starter_media(); ?>
	</figure>
<?php endif; ?>

<section class="alignfull content-block page-header project-head">
	<div class="block-wrap project-head__grid">
		<div class="page-header__main"<?php echo $starter_reveal; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<?php
			if ( ! $starter_preview ) {
				starter_breadcrumb_inline();
			}
			?>
			<?php $starter_live = $starter_preview ? '' : starter_live_attrs( array( 'post' => $starter_id, 'src' => 'post', 'key' => 'post_title', 'label' => __( 'Titolo', 'slug-theme' ), 'value' => get_post_field( 'post_title', $starter_id ) ) ); ?>
			<?php if ( $starter_summary ) : ?>
				<?php $starter_live_sum = $starter_preview ? '' : starter_live_attrs( array( 'post' => $starter_id, 'src' => 'post', 'key' => 'post_excerpt', 'label' => __( 'Riassunto', 'slug-theme' ), 'value' => $starter_summary ) ); ?>
				<h1<?php echo $starter_live_sum; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="page-header__summary title-h5"><?php echo esc_html( $starter_summary ); ?></h1>
				<h2<?php echo $starter_live; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="page-header__title text-balance"><?php echo esc_html( $starter_title ); ?></h2>
			<?php else : ?>
				<h1<?php echo $starter_live; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="page-header__title text-balance"><?php echo esc_html( $starter_title ); ?></h1>
			<?php endif; ?>
			<?php if ( $starter_text ) : ?>
				<div class="project-intro txt"<?php echo $starter_preview ? '' : starter_live_attrs( array( 'src' => 'fieldnum', 'key' => 'progetto_testo' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $starter_text ); ?></div>
			<?php endif; ?>
		</div>

		<?php if ( $starter_meta ) : ?>
			<dl class="project-meta"<?php echo $starter_reveal; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<?php if ( $starter_cats ) : ?>
					<div class="project-meta__item project-meta__item--wide"><dt><?php esc_html_e( 'Servizi', 'slug-theme' ); ?></dt>
						<dd>
							<ul class="project-meta__list project-meta__chips">
								<?php foreach ( $starter_cats as $starter_out ) : ?>
									<li><?php echo esc_html( $starter_out ); ?></li>
								<?php endforeach; ?>
							</ul>
						</dd>
					</div>
				<?php endif; ?>
				<?php foreach ( $starter_collabs as $starter_kind => $starter_list ) : ?>
					<?php if ( $starter_list ) : ?>
						<div class="project-meta__item"><dt><?php echo esc_html( 'partner' === $starter_kind ? __( 'Partner', 'slug-theme' ) : _n( 'Agenzia', 'Agenzie', count( $starter_list ), 'slug-theme' ) ); ?></dt>
							<dd>
								<ul class="project-meta__list">
									<?php foreach ( $starter_list as $starter_agency ) : ?>
										<li class="title-h6 project-meta__name">
											<?php if ( ! empty( $starter_agency['url'] ) ) : ?>
												<a href="<?php echo esc_url( $starter_agency['url'] ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $starter_agency['title'] ); ?></a>
											<?php else : ?>
												<?php echo esc_html( $starter_agency['title'] ); ?>
											<?php endif; ?>
										</li>
									<?php endforeach; ?>
								</ul>
							</dd>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
				<?php if ( is_array( $starter_link ) && ! empty( $starter_link['url'] ) ) : ?>
					<div class="project-meta__item"><dt><?php esc_html_e( 'Visita il progetto', 'slug-theme' ); ?></dt>
						<dd>
							<ul class="project-meta__list">
								<li class="title-h6 project-meta__name"><a href="<?php echo esc_url( $starter_link['url'] ); ?>"<?php echo ! empty( $starter_link['target'] ) ? ' target="' . esc_attr( $starter_link['target'] ) . '" rel="noopener"' : ''; ?>><?php echo esc_html( $starter_link_host ? $starter_link_host : __( 'Visita il progetto', 'slug-theme' ) ); ?></a></li>
							</ul>
						</dd>
					</div>
				<?php endif; ?>
			</dl>
		<?php endif; ?>
	</div>
</section>

<?php if ( ! $starter_cover_full && ( $starter_video || $starter_thumb ) ) : ?>
	<figure class="alignfull project-cover">
		<div class="block-wrap">
			<?php $starter_media(); ?>
		</div>
	</figure>
<?php endif; ?>
