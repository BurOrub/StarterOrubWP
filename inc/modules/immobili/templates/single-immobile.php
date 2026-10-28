<?php
/** Scheda singolo immobile */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$starter_id        = get_the_ID();
	$starter_gallery   = starter_imm_field( 'galleria', $starter_id );
	$starter_plan      = starter_imm_field( 'planimetria', $starter_id );
	$starter_address   = trim( (string) starter_imm_field( 'indirizzo', $starter_id ) );
	$starter_zone      = starter_imm_get_zone_label( $starter_id );
	$starter_badges    = array_filter( array_merge( array_slice( starter_imm_get_badges( $starter_id ), 0, 1 ), array( starter_imm_get_contract_badge( $starter_id ) ), array_slice( starter_imm_get_badges( $starter_id ), 1 ) ) );
	$starter_price     = starter_imm_field( 'prezzo', $starter_id );
	$starter_mq        = starter_imm_field( 'superficie', $starter_id );
	$starter_locali    = starter_imm_field( 'locali', $starter_id );
	$starter_locali_p  = (bool) starter_imm_field( 'locali_plus', $starter_id );
	$starter_camere    = starter_imm_field( 'camere', $starter_id );
	$starter_bagni     = starter_imm_field( 'bagni', $starter_id );
	$starter_piano     = starter_imm_field( 'piano', $starter_id );
	$starter_classe    = starter_imm_field( 'classe_energetica', $starter_id );
	$starter_epi       = starter_imm_field( 'epi', $starter_id );
	$starter_features  = starter_imm_immobile_highlights( $starter_id );
	$starter_blocks    = starter_imm_field( 'blocchi_piano', $starter_id );
	$starter_desc      = starter_imm_field( 'descrizione', $starter_id );
	$starter_desc_h    = starter_imm_field( 'titolo_descrizione', $starter_id );
	$starter_opening   = trim( (string) starter_imm_field( 'apertura', $starter_id ) );
	$starter_zone_h    = starter_imm_field( 'titolo_zona', $starter_id );
	$starter_zone_t    = starter_imm_field( 'testo_zona', $starter_id );
	$starter_form      = trim( (string) starter_wpml_translate_string( 'imm_form_request', (string) get_theme_mod( 'starter_imm_form_request', '' ) ) ); // Con WPML si può mettere un form diverso per lingua (String Translation).
	$starter_list_url  = starter_imm_get_immobili_page_url();
	$starter_strengths = starter_imm_field( 'punti_di_forza', $starter_id );
	$starter_video     = starter_imm_field( 'video_tour', $starter_id );
	$starter_poi       = starter_imm_field( 'punti_interesse', $starter_id );
	$starter_poi_types = starter_imm_poi_types();
	$starter_adv       = starter_imm_get_consultant( $starter_id );
	$starter_adv_name  = trim( (string) $starter_adv['name'] );
	$starter_adv_role  = isset( $starter_adv['role'] ) ? $starter_adv['role'] : '';
	$starter_adv_photo = isset( $starter_adv['photo'] ) ? $starter_adv['photo'] : '';
	$starter_adv_phone = isset( $starter_adv['phone'] ) ? $starter_adv['phone'] : '';
	$starter_adv_mail  = isset( $starter_adv['mail'] ) ? $starter_adv['mail'] : '';

	// Foto
	$starter_photos = array();

	if ( is_array( $starter_gallery ) && $starter_gallery ) {
		foreach ( $starter_gallery as $starter_photo ) {
			$starter_photos[] = array(
				'id'   => $starter_photo['ID'],
				'full' => isset( $starter_photo['sizes']['1536x1536'] ) ? $starter_photo['sizes']['1536x1536'] : ( isset( $starter_photo['sizes']['large'] ) ? $starter_photo['sizes']['large'] : $starter_photo['url'] ),
				'alt'  => starter_imm_immobile_image_alt( $starter_id, $starter_photo['ID'] ),
				'cap'  => $starter_photo['alt'] ? $starter_photo['alt'] : get_the_title(),
			);
		}
	} elseif ( has_post_thumbnail() ) {
		$starter_thumb_id = get_post_thumbnail_id();
		$starter_photos[] = array(
			'id'   => $starter_thumb_id,
			'full' => wp_get_attachment_image_url( $starter_thumb_id, 'large' ),
			'alt'  => starter_imm_immobile_image_alt( $starter_id, $starter_thumb_id ),
			'cap'  => get_the_title(),
		);
	}

	$starter_3d_html = starter_imm_get_virtual_tour_embed( starter_imm_field( 'tour_3d', $starter_id ), sprintf( /* translators: %s: titolo dell'immobile. */ __( 'Tour virtuale 3D: %s', 'slug-theme' ), get_the_title() ) );
	$starter_rows  = starter_imm_immobile_data_rows( $starter_id );
	$starter_facts = starter_imm_immobile_facts( $starter_id );
	?>

<main class="immobile" id="site-content">
<?php ob_start(); // Il contenuto della scheda passa dal filtro di Starter Live, che rende modificabili i campi (solo per chi può modificare). ?>

	<section class="immobile-head">
		<?php starter_breadcrumb_inline(); ?>

		<div class="immobile-head__row">
			<div class="immobile-head__main" data-reveal>
				<?php if ( $starter_badges ) : ?>
					<div class="immobile-badges">
						<?php foreach ( array_values( $starter_badges ) as $starter_i => $starter_badge ) : ?>
							<span class="tag<?php echo 0 === $starter_i ? ' tag--solid' : ''; ?>"><?php echo esc_html( $starter_badge ); ?></span>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<h1 class="immobile-title title-h1"<?php echo '' === trim( (string) starter_imm_field( 'titolo_pagina', $starter_id ) ) ? starter_live_attrs( array( 'post' => $starter_id, 'src' => 'post', 'key' => 'post_title', 'label' => __( 'Titolo', 'slug-theme' ), 'value' => get_post_field( 'post_title', $starter_id ) ) ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo starter_imm_get_page_title_html( $starter_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h1>

				<?php if ( $starter_address || $starter_zone ) : ?>
					<span class="immobile-address">
						<?php echo starter_icon( 'map-pin', 18, 1.9 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php $starter_zone_parts = array_filter(
							explode( ' · ', $starter_zone ),
							function ( $part ) use ( $starter_address ) {
								return '' === $part || false === stripos( $starter_address, $part );
							}
						);
						// L'indirizzo è un elemento a sé (modificabile con Starter Live, cambia anche la mappa); la zona resta accanto.
						if ( '' !== $starter_address ) {
							echo '<span' . starter_live_attrs( array( 'src' => 'fieldnum', 'key' => 'indirizzo' ) ) . '>' . esc_html( $starter_address ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
						echo esc_html( ( '' !== $starter_address && $starter_zone_parts ? ' · ' : '' ) . implode( ' · ', array_filter( $starter_zone_parts ) ) ); ?>
					</span>
				<?php endif; ?>

				<?php if ( '' !== $starter_opening ) : ?>
					<p class="immobile-opening has-large-font-size"><?php echo esc_html( $starter_opening ); ?></p>
				<?php endif; ?>
			</div>

			<div class="immobile-head__price" data-reveal>
				<span class="immobile-eyebrow"><?php esc_html_e( 'Prezzo', 'slug-theme' ); ?></span>
				<span class="immobile-price"<?php echo starter_live_attrs( array( 'src' => 'fieldnum', 'key' => 'prezzo' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( starter_imm_get_price_label( $starter_id ) ); ?></span>
			</div>
		</div>
	</section>

	<?php if ( $starter_photos ) : ?>
		<section class="immobile-gallery" aria-label="<?php esc_attr_e( 'Foto dell\'immobile', 'slug-theme' ); ?>">
			<div class="immobile-gallery__track" id="gallery-track" data-drag-scroll data-reveal>
				<?php foreach ( $starter_photos as $starter_i => $starter_photo ) : ?>
					<button type="button" class="immobile-gallery__slide" data-lightbox-index="<?php echo (int) $starter_i; ?>" data-full="<?php echo esc_url( $starter_photo['full'] ); ?>" data-alt="<?php echo esc_attr( $starter_photo['alt'] ); ?>" data-caption="<?php echo esc_attr( $starter_photo['cap'] ); ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: descrizione della foto. */ __( 'Ingrandisci: %s', 'slug-theme' ), $starter_photo['cap'] ) ); ?>">
						<?php
						echo wp_get_attachment_image(
							$starter_photo['id'],
							'large',
							false,
							array(
								'alt'      => $starter_photo['alt'],
								'loading'  => $starter_i < 2 ? 'eager' : 'lazy',
								'sizes'    => '(max-width: 782px) 86vw, (max-width: 1023px) 62vw, 44vw',
							)
						);
						?>
					</button>
				<?php endforeach; ?>
			</div>

			<div class="immobile-gallery__bar">
				<div class="immobile-gallery__actions">
					<a class="button button immobile-pdf" href="<?php echo esc_url( starter_imm_pdf_url( $starter_id ) ); ?>" rel="nofollow">
						<?php echo starter_icon( 'file-down', 17, 2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php echo starter_flip_text( __( 'Scarica scheda immobile', 'slug-theme' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</a>
					<button type="button" class="button button--outline" data-lightbox-index="0">
						<?php echo starter_icon( 'images', 16, 1.9 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php echo starter_flip_text( sprintf( /* translators: %d: numero di foto. */ _n( 'Vedi la foto', 'Vedi tutte le %d foto', count( $starter_photos ), 'slug-theme' ), count( $starter_photos ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</button>
					<?php if ( is_array( $starter_plan ) && $starter_plan ) : ?>
						<a class="button button--outline" href="#planimetria">
							<?php echo starter_icon( 'ruler', 16, 1.9 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<?php echo starter_flip_text( __( 'Planimetria', 'slug-theme' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</a>
					<?php endif; ?>
					<?php if ( $starter_3d_html ) : ?>
						<a class="button button--outline" href="#tour-virtuale">
							<?php echo starter_icon( 'cube', 16, 1.9 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<?php echo starter_flip_text( __( 'Virtual tour', 'slug-theme' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</a>
					<?php endif; ?>
				</div>

				<?php if ( count( $starter_photos ) > 1 ) : ?>
					<div class="immobile-gallery__arrows">
						<button type="button" class="round-button" data-scroll-for="gallery-track" data-scroll-dir="-1" aria-label="<?php esc_attr_e( 'Foto precedenti', 'slug-theme' ); ?>"><?php echo starter_icon( 'chevron-left', 20, 2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
						<button type="button" class="round-button" data-scroll-for="gallery-track" data-scroll-dir="1" aria-label="<?php esc_attr_e( 'Foto successive', 'slug-theme' ); ?>"><?php echo starter_icon( 'chevron-right', 20, 2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
					</div>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>

	<section class="immobile-body">
		<div class="immobile-body__main">

			<?php if ( $starter_desc || $starter_desc_h ) : ?>
				<div class="immobile-block" data-reveal>
					<span class="eyebrow"><?php esc_html_e( 'Descrizione', 'slug-theme' ); ?></span>
					<?php if ( $starter_desc_h ) : ?>
						<h2 class="title-h3 immobile-h2 text-balance"><?php echo esc_html( $starter_desc_h ); ?></h2>
					<?php endif; ?>
					<?php if ( $starter_desc ) : ?>
						<div class="immobile-text"><?php echo wp_kses_post( $starter_desc ); ?></div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( $starter_facts ) : ?>
				<div class="immobile-block" data-reveal>
					<span class="eyebrow"><?php esc_html_e( 'In sintesi', 'slug-theme' ); ?></span>
					<div class="immobile-facts">
						<?php foreach ( $starter_facts as $starter_fact ) : ?>
							<div class="immobile-fact">
								<span class="icon-circle"><?php echo starter_icon( $starter_fact[0], 22, 1.9 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
								<p class="immobile-fact__value"<?php echo ! empty( $starter_fact[3] ) ? starter_live_attrs( array( 'src' => 'fieldnum', 'key' => $starter_fact[3] ) ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $starter_fact[1] ); ?></p>
								<?php if ( $starter_fact[2] ) : ?>
									<p><?php echo esc_html( $starter_fact[2] ); ?></p>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( is_array( $starter_strengths ) && $starter_strengths ) : ?>
				<div class="immobile-block" data-reveal>
					<span class="eyebrow"><?php esc_html_e( 'Punti di forza', 'slug-theme' ); ?></span>
					<ul class="immobile-highlights<?php echo in_array( count( $starter_strengths ), array( 2, 4 ), true ) ? ' immobile-highlights--pairs' : ''; ?>">
						<?php foreach ( $starter_strengths as $starter_strength ) : ?>
							<li>
								<span class="icon-circle"><?php echo starter_icon( 'check', 22, 2.2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
								<h2><?php echo esc_html( $starter_strength['punti_di_forza_titolo'] ); ?></h2>
								<?php if ( $starter_strength['punti_di_forza_testo'] ) : ?>
									<p><?php echo esc_html( $starter_strength['punti_di_forza_testo'] ); ?></p>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>

			<?php if ( is_array( $starter_blocks ) && $starter_blocks ) : ?>
				<div class="immobile-boxes" data-reveal>
					<?php foreach ( $starter_blocks as $starter_box ) : ?>
						<div class="immobile-box">
							<?php if ( $starter_box['blocchi_piano_occhiello'] ) : ?>
								<span class="immobile-eyebrow immobile-eyebrow--small"><?php echo esc_html( $starter_box['blocchi_piano_occhiello'] ); ?></span>
							<?php endif; ?>
							<h2><?php echo esc_html( $starter_box['blocchi_piano_titolo'] ); ?></h2>
							<p><?php echo esc_html( $starter_box['blocchi_piano_testo'] ); ?></p>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php if ( $starter_features || $starter_rows ) : ?>
				<div class="immobile-block" data-reveal>
					<span class="eyebrow"><?php esc_html_e( 'Caratteristiche', 'slug-theme' ); ?></span>

					<?php if ( $starter_features ) : ?>
						<ul class="immobile-chips">
							<?php foreach ( $starter_features as $starter_feature ) : ?>
								<li><?php echo starter_icon( 'check', 14, 2.5 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $starter_feature ); ?></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>

					<?php if ( $starter_rows ) : ?>
						<dl class="immobile-data">
							<?php foreach ( $starter_rows as $starter_row ) : ?>
								<div>
									<dt><?php echo esc_html( $starter_row[0] ); ?></dt>
									<dd<?php echo ! empty( $starter_row[2] ) ? starter_live_attrs( array( 'src' => 'fieldnum', 'key' => $starter_row[2] ) ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $starter_row[1] ); ?></dd>
								</div>
							<?php endforeach; ?>
						</dl>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( is_array( $starter_plan ) && $starter_plan ) : ?>
				<div class="immobile-block immobile-plan" id="planimetria" data-reveal>
					<span class="eyebrow"><?php esc_html_e( 'Planimetria', 'slug-theme' ); ?></span>
					<?php $starter_plan_multi = count( $starter_plan ) > 1; ?>
					<div class="immobile-plan__slider<?php echo $starter_plan_multi ? ' is-multi' : ''; ?>">
						<div class="immobile-plan__track" id="plan-track">
						<?php foreach ( $starter_plan as $starter_plan_img ) : ?>
							<div class="immobile-plan__frame">
								<?php
								echo wp_get_attachment_image(
									$starter_plan_img['ID'],
									'large',
									false,
									array(
										'alt'     => starter_imm_immobile_image_alt( $starter_id, $starter_plan_img['ID'], __( 'Planimetria', 'slug-theme' ) ),
										'loading' => 'lazy',
									)
								);
								?>
							</div>
						<?php endforeach; ?>
						</div>
						<div class="immobile-plan__bar">
							<a class="button button--outline" href="<?php echo esc_url( $starter_plan[0]['url'] ); ?>" download data-plan-files="<?php echo esc_attr( wp_json_encode( wp_list_pluck( $starter_plan, 'url' ) ) ); ?>">
								<?php echo starter_icon( 'download', 16, 1.9 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php echo starter_flip_text( $starter_plan_multi ? __( 'Scarica le planimetrie', 'slug-theme' ) : __( 'Scarica la planimetria', 'slug-theme' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</a>
						<?php if ( $starter_plan_multi ) : ?>
							<div class="immobile-plan__arrows">
								<button type="button" class="round-button" data-scroll-for="plan-track" data-scroll-dir="-1" aria-label="<?php esc_attr_e( 'Planimetria precedente', 'slug-theme' ); ?>"><?php echo starter_icon( 'chevron-left', 20, 2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
								<button type="button" class="round-button" data-scroll-for="plan-track" data-scroll-dir="1" aria-label="<?php esc_attr_e( 'Planimetria successiva', 'slug-theme' ); ?>"><?php echo starter_icon( 'chevron-right', 20, 2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
							</div>
						<?php endif; ?>
						</div>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( $starter_3d_html ) : ?>
				<div class="immobile-block" id="tour-virtuale" data-reveal>
					<span class="eyebrow"><?php esc_html_e( 'Tour virtuale 3D', 'slug-theme' ); ?></span>
					<div class="immobile-map immobile-video">
						<?php echo $starter_3d_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				</div>
			<?php endif; ?>

			<?php $starter_tour_html = starter_imm_get_tour_embed( $starter_video, sprintf( /* translators: %s: titolo dell'immobile. */ __( 'Video: %s', 'slug-theme' ), get_the_title() ) ); ?>
			<?php if ( $starter_tour_html ) : ?>
				<div class="immobile-block" data-reveal>
					<span class="eyebrow"><?php esc_html_e( 'Video', 'slug-theme' ); ?></span>
					<div class="immobile-map immobile-video">
						<?php echo $starter_tour_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( $starter_address || $starter_zone_h || $starter_zone_t || $starter_poi ) : ?>
				<div class="immobile-block" data-reveal>
					<span class="eyebrow"><?php esc_html_e( 'La zona', 'slug-theme' ); ?></span>
					<?php if ( $starter_zone_h ) : ?>
						<h2 class="title-h3 immobile-h2 text-balance"><?php echo esc_html( $starter_zone_h ); ?></h2>
					<?php endif; ?>
					<?php if ( $starter_zone_t ) : ?>
						<div class="immobile-text"><?php echo wp_kses_post( wpautop( esc_html( $starter_zone_t ) ) ); ?></div>
					<?php endif; ?>
					<?php if ( is_array( $starter_poi ) && $starter_poi ) : ?>
						<ul class="immobile-poi" aria-label="<?php esc_attr_e( 'Nelle vicinanze', 'slug-theme' ); ?>">
							<?php foreach ( $starter_poi as $starter_place ) : ?>
								<?php
								$starter_place_type = isset( $starter_poi_types[ $starter_place['punti_interesse_tipo'] ] ) ? $starter_poi_types[ $starter_place['punti_interesse_tipo'] ] : array( '', 'map-pin' );
								?>
								<li>
									<span class="icon-circle icon-circle--small"><?php echo starter_icon( $starter_place_type[1], 20, 1.9 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
									<span class="immobile-poi__text">
										<span class="immobile-poi__name"><?php echo esc_html( $starter_place['punti_interesse_nome'] ); ?></span>
										<?php if ( $starter_place['punti_interesse_distanza'] ) : ?>
											<span class="immobile-poi__type"><?php echo esc_html( $starter_place['punti_interesse_distanza'] ); ?></span>
										<?php endif; ?>
									</span>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
					<?php if ( $starter_address ) : ?>
						<div class="immobile-map">
							<iframe src="<?php echo esc_url( 'https://maps.google.com/maps?q=' . rawurlencode( $starter_address ) . '&output=embed' ); ?>" title="<?php echo esc_attr( sprintf( /* translators: %s: indirizzo. */ __( 'Mappa: %s', 'slug-theme' ), $starter_address ) ); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

		</div>

		<aside class="immobile-aside" id="contatta" data-reveal>
			<div class="immobile-contact">
				<div class="immobile-contact__head">
					<span class="eyebrow"><?php esc_html_e( 'Ti interessa questo immobile?', 'slug-theme' ); ?></span>
					<span class="immobile-contact__title"><?php esc_html_e( 'Richiedi informazioni', 'slug-theme' ); ?></span>
				</div>
				<?php if ( '' !== $starter_form ) : ?>
					<div class="immobile-form">
						<?php echo do_shortcode( $starter_form ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( '' !== $starter_adv_name ) : ?>
				<div class="immobile-advisor">
					<?php if ( is_array( $starter_adv_photo ) && ! empty( $starter_adv_photo['ID'] ) ) : ?>
						<?php
						echo wp_get_attachment_image(
							$starter_adv_photo['ID'],
							'thumbnail',
							false,
							array(
								'alt'     => $starter_adv_photo['alt'] ? $starter_adv_photo['alt'] : trim( $starter_adv_name . ( $starter_adv_role ? ', ' . $starter_adv_role : '' ) . ' – ' . get_bloginfo( 'name' ) ),
								'loading' => 'lazy',
							)
						);
						?>
					<?php endif; ?>
					<div class="immobile-advisor__body">
						<span class="immobile-eyebrow immobile-eyebrow--small"><?php esc_html_e( 'Il tuo consulente', 'slug-theme' ); ?></span>
						<span class="immobile-advisor__name"><?php echo esc_html( $starter_adv_name ); ?></span>
						<?php if ( $starter_adv_role ) : ?>
							<span class="immobile-advisor__role"><?php echo esc_html( $starter_adv_role ); ?></span>
						<?php endif; ?>
						<span class="immobile-advisor__links">
							<?php if ( $starter_adv_phone ) : ?>
								<a href="<?php echo esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $starter_adv_phone ) ); ?>"><?php echo starter_icon( 'phone', 16, 2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $starter_adv_phone ); ?></a>
							<?php endif; ?>
							<?php if ( $starter_adv_mail ) : ?>
								<a href="<?php echo esc_url( 'mailto:' . $starter_adv_mail ); ?>"><?php echo starter_icon( 'mail', 16, 2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $starter_adv_mail ); ?></a>
							<?php endif; ?>
						</span>
					</div>
				</div>
			<?php endif; ?>
		</aside>
	</section>

	<?php
	$starter_related = starter_imm_get_featured_immobili( 'in-evidenza-home', 3, array( $starter_id ) );

	// Se gli immobili in evidenza (escluso quello aperto) sono meno di 3, si completa con i successivi nello stesso ordine del sito.
	if ( count( $starter_related ) < 3 ) {
		$starter_related = array_merge(
			$starter_related,
			get_posts(
				array(
					'post_type'        => 'immobile',
					'post_status'      => 'publish',
					'posts_per_page'   => 3 - count( $starter_related ),
					'suppress_filters' => false, // Solo immobili della lingua corrente (WPML).
					'post__not_in'     => array_merge( array( $starter_id ), wp_list_pluck( $starter_related, 'ID' ) ),
					'orderby'          => array(
						'menu_order' => 'ASC',
						'date'       => 'DESC',
					),
				)
			)
		);
	}
	?>

	<?php
	// Le schede in fondo sono di altri immobili: non fanno parte di quello che si sta modificando.
	echo apply_filters( 'starter_live_post_html', ob_get_clean(), $starter_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	?>

	<?php if ( $starter_related ) : ?>
		<section class="immobile-related">
			<div class="section-head" data-reveal>
				<div class="section-head__text">
					<span class="eyebrow"><?php esc_html_e( 'Potrebbero interessarti', 'slug-theme' ); ?></span>
					<h2 class="title-h3 text-balance"><?php esc_html_e( 'Altri immobili in evidenza', 'slug-theme' ); ?></h2>
				</div>
				<a class="button button--outline" href="<?php echo esc_url( $starter_list_url ); ?>">
					<?php echo starter_flip_text( __( 'Scopri gli immobili', 'slug-theme' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php echo starter_icon( 'arrow-right', 16, 2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</a>
			</div>
			<div class="immobile-grid">
				<?php
				foreach ( $starter_related as $starter_related_post ) {
					setup_postdata( $GLOBALS['post'] = $starter_related_post ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
					get_template_part( 'template-parts/content/card-immobile', null, array( 'heading' => 'h3' ) );
				}
				wp_reset_postdata();
				?>
			</div>
		</section>
	<?php endif; ?>

</main>

	<?php
endwhile;

get_footer();
