<?php
/**
 * Blocco "Sezione affiancata": foto (1 o 2) accanto a un testo scritto direttamente in Gutenberg con i blocchi nativi
 * (titolo, paragrafi, elenchi, pulsanti): più libero di "Testo affiancato", che ha campi fissi. Con 2 foto la seconda,
 * più piccola, si sovrappone in basso a sinistra. Ripreso da Symbolikamente.
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$starter_photos = starter_photo_pair( 'foto', 'foto_2' ); // foto principale + seconda foto facoltativa (campi immagine singoli)
$starter_format = get_field( 'formato' ) ? get_field( 'formato' ) : 'verticale';
$starter_side   = get_field( 'lato' ) ? get_field( 'lato' ) : 'sinistra';
$starter_bg     = get_field( 'sfondo' ) ? get_field( 'sfondo' ) : 'nessuno';
$starter_eyebrow = get_field( 'occhiello' );

// Testo di partenza: titolo, paragrafo, pulsante. Si modifica e si riordina liberamente.
$starter_template = array(
	array( 'core/heading', array( 'level' => 2, 'placeholder' => __( 'Titolo della sezione', 'slug-theme' ) ) ),
	array( 'core/paragraph', array( 'placeholder' => __( 'Testo…', 'slug-theme' ) ) ),
	array( 'core/buttons', array(), array( array( 'core/button', array( 'className' => 'is-style-outline' ) ) ) ),
);

$starter_classes = sprintf( 'alignfull content-block split split--affiancata split--%1$s split--foto-%2$s%3$s', esc_attr( $starter_side ), esc_attr( $starter_format ), 'muted' === $starter_bg ? ' panel panel--muted' : '' );
$starter_wrapper = get_block_wrapper_attributes( array( 'class' => $starter_classes ) );
$starter_inner   = 'block-wrap split__grid';

if ( 'muted' === $starter_bg ) {
	$starter_inner = 'panel__inner split__grid';
}
?>
<section <?php echo $starter_wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php if ( 'muted' === $starter_bg ) : ?>
	<div class="panel__box">
	<?php endif; ?>
	<div class="<?php echo esc_attr( $starter_inner ); ?>">
		<div class="split__figure<?php echo count( $starter_photos ) > 1 ? ' split__figure--pair' : ''; ?>" data-reveal>
			<?php if ( $starter_photos ) : ?>
				<div class="split__figure-main">
					<?php echo wp_get_attachment_image( $starter_photos[0], 'large', false, array( 'loading' => 'lazy', 'sizes' => '(max-width: 900px) 100vw, 50vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				<?php if ( ! empty( $starter_photos[1] ) ) : ?>
					<div class="split__figure-small">
						<?php echo wp_get_attachment_image( $starter_photos[1], 'medium_large', false, array( 'loading' => 'lazy', 'sizes' => '(max-width: 900px) 42vw, 20vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				<?php endif; ?>
			<?php elseif ( ! empty( $is_preview ) ) : ?>
				<p class="block-placeholder"><?php esc_html_e( 'Aggiungi 1 o 2 foto dalla barra laterale.', 'slug-theme' ); ?></p>
			<?php endif; ?>
		</div>

		<div class="split__text split__text--free" data-reveal>
			<?php if ( $starter_eyebrow ) : ?>
				<?php // Occhiello come semplice testo: il titolo H2 lo scrive chi compila il blocco, qui sotto. ?>
				<?php $starter_eyebrow_tag = starter_heading_tag( 'occhiello', 'p' ); ?>
				<<?php echo esc_html( $starter_eyebrow_tag ); ?> class="eyebrow"><?php echo starter_inline( $starter_eyebrow ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></<?php echo esc_html( $starter_eyebrow_tag ); ?>>
			<?php endif; ?>
			<InnerBlocks template="<?php echo esc_attr( wp_json_encode( $starter_template ) ); ?>" />
		</div>
	</div>
	<?php if ( 'muted' === $starter_bg ) : ?>
	</div>
	<?php endif; ?>
</section>
