<?php
/** Card di un immobile */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$starter_card_id     = get_the_ID();
$starter_card_tag    = ( isset( $args['heading'] ) && in_array( $args['heading'], array( 'h2', 'h3', 'h4', 'h5' ), true ) ) ? $args['heading'] : 'h3';
$starter_card_badges = starter_imm_get_badges( $starter_card_id );
$starter_card_badge  = $starter_card_badges ? $starter_card_badges[0] : starter_imm_get_term_name( 'contratto', $starter_card_id );
$starter_card_zone   = starter_imm_get_zone_label( $starter_card_id );
$starter_card_thumb  = get_post_thumbnail_id( $starter_card_id );
?>

<a class="immobile-card" href="<?php the_permalink(); ?>" data-reveal>
	<div class="immobile-card__media">
		<?php
		if ( $starter_card_thumb ) {
			echo wp_get_attachment_image(
				$starter_card_thumb,
				'starter-immobile-card',
				false,
				array(
					'alt'     => starter_imm_immobile_image_alt( $starter_card_id, $starter_card_thumb ),
					'loading' => 'lazy',
					'sizes'   => '(max-width: 640px) 100vw, (max-width: 1100px) 50vw, 400px',
				)
			);
		}
		?>
		<?php if ( $starter_card_badge ) : ?>
			<span class="immobile-card__badge"><?php echo esc_html( $starter_card_badge ); ?></span>
		<?php endif; ?>
	</div>

	<div class="immobile-card__body">
		<?php if ( $starter_card_zone ) : ?>
			<span class="immobile-card__zone"><?php echo esc_html( $starter_card_zone ); ?></span>
		<?php endif; ?>
		<<?php echo esc_html( $starter_card_tag ); ?> class="immobile-card__title title-h5"><?php the_title(); ?></<?php echo esc_html( $starter_card_tag ); ?>>
		<span class="immobile-card__price"><?php echo esc_html( starter_imm_get_price_label( $starter_card_id ) ); ?></span>
	</div>

	<?php $starter_card_facts = starter_imm_get_card_facts( $starter_card_id ); ?>
	<?php if ( $starter_card_facts ) : ?>
		<div class="immobile-card__facts">
			<?php foreach ( $starter_card_facts as $starter_card_fact ) : ?>
				<span><?php echo starter_icon( $starter_card_fact[0] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $starter_card_fact[1] ); ?></span>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</a>
