<?php
/** Scheda dell'immobile in PDF (libreria Dompdf in inc/modules/immobili/dompdf, caricata solo quando si crea il PDF). Colori dalle opzioni del tema. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Indirizzo del PDF di un immobile */
function starter_imm_pdf_url( $post_id = null ) {
	return add_query_arg( 'scheda-pdf', '1', get_permalink( $post_id ? $post_id : get_the_ID() ) );
}

/* Immagine come data URI (JPEG ridimensionato, con ritaglio opzionale), così il PDF non dipende da percorsi o permessi dei file. */
function starter_imm_pdf_image( $attachment_id, $width, $height = 0, $crop = false ) {
	$file = $attachment_id ? get_attached_file( $attachment_id ) : '';

	if ( ! $file || ! file_exists( $file ) ) {
		return '';
	}

	if ( 'image/svg+xml' === get_post_mime_type( $attachment_id ) ) {
		return 'data:image/svg+xml;base64,' . base64_encode( (string) file_get_contents( $file ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	}

	require_once ABSPATH . 'wp-admin/includes/file.php'; // wp_tempnam() non c'è sul sito pubblico.

	$editor = wp_get_image_editor( $file );

	if ( is_wp_error( $editor ) ) {
		return '';
	}

	$editor->resize( $width, $height ? $height : null, $crop );
	$editor->set_quality( 82 );
	$tmp   = wp_tempnam( 'scheda-pdf' );
	$saved = $editor->save( $tmp . '.jpg', 'image/jpeg' );
	@unlink( $tmp ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged

	if ( is_wp_error( $saved ) || empty( $saved['path'] ) ) {
		return '';
	}

	$data = (string) file_get_contents( $saved['path'] ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	@unlink( $saved['path'] ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged

	return $data ? 'data:image/jpeg;base64,' . base64_encode( $data ) : '';
}

/* Colore delle opzioni del tema (Opzioni → Colori) come #rrggbb, oppure il valore di ripiego. */
function starter_imm_pdf_hex( $field, $fallback ) {
	$value = function_exists( 'starter_resolve_named_color' ) ? trim( (string) starter_resolve_named_color( starter_color_ref( $field ) ) ) : '';

	return preg_match( '/^#([0-9a-f]{6})$/i', $value ) ? strtolower( $value ) : $fallback;
}

/* Mescola due colori #rrggbb: $amount = quota del primo (0-1). */
function starter_imm_pdf_mix( $a, $b, $amount ) {
	$out = '#';

	foreach ( array( 1, 3, 5 ) as $i ) {
		$out .= str_pad( dechex( (int) round( hexdec( substr( $a, $i, 2 ) ) * $amount + hexdec( substr( $b, $i, 2 ) ) * ( 1 - $amount ) ) ), 2, '0', STR_PAD_LEFT );
	}

	return $out;
}

/* Colori del PDF: derivati da testo e colore principale del tema. */
function starter_imm_pdf_palette() {
	static $palette = null;

	if ( null === $palette ) {
		$text    = starter_imm_pdf_hex( 'starter_color_text', '#1a1a1a' );
		$primary = starter_imm_pdf_hex( 'starter_color_btn_primary', '#0073aa' );

		$palette = array(
			'text'    => $text,
			'muted'   => starter_imm_pdf_mix( $text, '#ffffff', 0.68 ),
			'primary' => $primary,
			'line'    => starter_imm_pdf_mix( $primary, '#ffffff', 0.35 ),
			'line_lt' => starter_imm_pdf_mix( $primary, '#ffffff', 0.18 ),
			'tint'    => starter_imm_pdf_mix( $primary, '#ffffff', 0.07 ),
			'tint_lt' => starter_imm_pdf_mix( $primary, '#ffffff', 0.025 ),
		);
	}

	return $palette;
}

/* Icona del tema come immagine SVG per il PDF */
function starter_imm_pdf_icon( $name, $size = 14, $color = '' ) {
	$paths = starter_imm_icon_paths();
	$color = '' !== $color ? $color : starter_imm_pdf_palette()['primary'];

	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}

	$svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="' . $color . '" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">' . $paths[ $name ] . '</svg>';

	return '<img class="ic" src="data:image/svg+xml;base64,' . base64_encode( $svg ) . '" width="' . (int) $size . '" height="' . (int) $size . '" alt="">';
}

/* HTML del PDF */
function starter_imm_pdf_html( $post_id ) {
	$post = get_post( $post_id );

	if ( ! $post ) {
		return '';
	}

	$pal     = starter_imm_pdf_palette();
	$e       = 'esc_html';
	$title   = trim( wp_strip_all_tags( (string) starter_imm_field( 'titolo_pagina', $post_id ) ) );
	$title   = '' !== $title ? $title : get_the_title( $post_id );
	$address = trim( (string) starter_imm_field( 'indirizzo', $post_id ) );
	$zone    = starter_imm_get_zone_label( $post_id );
	$badges  = array_filter( array_merge( array_slice( starter_imm_get_badges( $post_id ), 0, 1 ), array( starter_imm_get_contract_badge( $post_id ) ), array_slice( starter_imm_get_badges( $post_id ), 1 ) ) );
	$opening = trim( (string) starter_imm_field( 'apertura', $post_id ) );
	$desc_h  = trim( (string) starter_imm_field( 'titolo_descrizione', $post_id ) );
	$desc    = starter_imm_field( 'descrizione', $post_id );
	$facts   = starter_imm_immobile_facts( $post_id );
	$rows    = starter_imm_immobile_data_rows( $post_id );
	$feats   = starter_imm_immobile_highlights( $post_id );
	$strengs = starter_imm_field( 'punti_di_forza', $post_id );
	$blocks  = starter_imm_field( 'blocchi_piano', $post_id );
	$zone_h  = trim( (string) starter_imm_field( 'titolo_zona', $post_id ) );
	$zone_t  = trim( (string) starter_imm_field( 'testo_zona', $post_id ) );
	$poi     = starter_imm_field( 'punti_interesse', $post_id );
	$types   = starter_imm_poi_types();
	$plans   = starter_imm_field( 'planimetria', $post_id );
	$adv     = starter_imm_get_consultant( $post_id );

	// Posizione: indirizzo + zona senza ripetizioni.
	$zone_parts = array_filter(
		explode( ' · ', (string) $zone ),
		function ( $part ) use ( $address ) {
			return '' === $part || false === stripos( $address, $part );
		}
	);
	$where      = implode( ' · ', array_filter( array_merge( array( $address ), $zone_parts ) ) );

	// Immagine in evidenza (orizzontale 16:9); in mancanza, la prima foto della galleria.
	$cover_id = (int) get_post_thumbnail_id( $post_id );
	$gallery  = starter_imm_field( 'galleria', $post_id );

	if ( ! $cover_id && is_array( $gallery ) && $gallery ) {
		$first    = reset( $gallery );
		$cover_id = isset( $first['ID'] ) ? (int) $first['ID'] : 0;
	}

	$cover = starter_imm_pdf_image( $cover_id, 1600, 800, true );
	$logo  = starter_imm_pdf_image( (int) get_theme_mod( 'custom_logo' ), 500 );

	ob_start();
	?>
<!doctype html>
<html lang="it">
<head>
<meta charset="utf-8">
<title><?php echo $e( $title ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></title>
<style>
	@page { margin: 16mm 16mm 20mm 16mm; }
	* { box-sizing: border-box; }
	body { margin: 0; color: <?php echo $pal['text']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>; font-family: Helvetica, sans-serif; font-size: 10pt; line-height: 1.5; }
	h1, h2, h3 { margin: 0; font-weight: bold; color: <?php echo $pal['text']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>; font-family: Helvetica, sans-serif; }
	h1 { font-size: 27pt; line-height: 1.12; }
	h2 { margin-top: 5px; font-size: 18pt; line-height: 1.2; }
	p { margin: 0 0 8px; }
	img.ic { vertical-align: -2px; }
	.eyebrow { margin: 0 0 4px; color: <?php echo $pal['primary']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>; font-size: 8pt; font-weight: bold; letter-spacing: 1.6px; text-transform: uppercase; }
	.block { margin-top: 24px; }
	.top { width: 100%; margin-bottom: 18px; border-bottom: 1px solid <?php echo $pal['line']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>; }
	.top td { padding-bottom: 10px; vertical-align: bottom; }
	.top .right { color: <?php echo $pal['primary']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>; font-size: 8pt; font-weight: bold; letter-spacing: 1.6px; text-align: right; text-transform: uppercase; }
	.badge { display: inline-block; margin: 0 4px 6px 0; padding: 3px 10px; border: 1px solid <?php echo $pal['line']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>; border-radius: 12px; color: <?php echo $pal['text']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>; font-size: 8pt; font-weight: bold; }
	.badge.solid { border-color: <?php echo $pal['text']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>; background: <?php echo $pal['text']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>; color: <?php echo $pal['tint_lt']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>; }
	.head { width: 100%; }
	.head td { vertical-align: top; }
	.head .price { width: 150px; text-align: right; }
	.price-label { color: <?php echo $pal['muted']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>; font-size: 8pt; letter-spacing: 1.6px; text-transform: uppercase; }
	.price-value { color: <?php echo $pal['text']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>; font-family: Helvetica, sans-serif; font-size: 18pt; line-height: 1.2; white-space: nowrap; }
	.where { margin-top: 8px; color: <?php echo $pal['muted']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>; font-size: 10pt; }
	.cover { display: block; width: 100%; margin: 18px 0 0; border-radius: 6px; }
	.lead { margin-top: 16px; color: <?php echo $pal['text']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>; font-family: Helvetica, sans-serif; font-size: 13.5pt; line-height: 1.4; }
	.facts { width: 100%; border-collapse: separate; border-spacing: 8px 0; margin: 0 -8px; }
	.facts td { padding: 12px 10px; border: 1px solid <?php echo $pal['line_lt']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>; border-radius: 6px; background: <?php echo $pal['tint']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>; vertical-align: top; }
	.fact-value { color: <?php echo $pal['text']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>; font-family: Helvetica, sans-serif; font-size: 14pt; line-height: 1.2; }
	.fact-note { color: <?php echo $pal['muted']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>; font-size: 8pt; }
	.text p, .text li { color: <?php echo $pal['text']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>; }
	.text ul, .text ol { margin: 0 0 8px; padding-left: 18px; }
	.text h2, .text h3 { margin: 12px 0 4px; font-size: 13pt; }
	.cols { width: 100%; border-collapse: separate; border-spacing: 10px 10px; margin: 0 -10px; }
	.cols td { width: 50%; padding: 12px 14px; border: 1px solid <?php echo $pal['line_lt']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>; border-radius: 6px; vertical-align: top; }
	.cols h3 { font-size: 13pt; }
	.cols p { margin: 4px 0 0; font-size: 9pt; }
	.chips { margin: 0 0 12px; }
	.chip { display: inline-block; margin: 0 4px 5px 0; padding: 3px 10px; border: 1px solid <?php echo $pal['line']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>; border-radius: 12px; color: <?php echo $pal['text']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>; font-size: 9pt; }
	.data { width: 100%; border-collapse: collapse; }
	.data td { width: 50%; padding: 6px 0; border-bottom: 1px solid <?php echo $pal['line_lt']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>; vertical-align: top; }
	.data .k { padding-right: 10px; color: <?php echo $pal['muted']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>; }
	.data .v { color: <?php echo $pal['text']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>; font-weight: bold; }
	.poi { width: 100%; border-collapse: collapse; margin-top: 10px; }
	.poi td { padding: 6px 0; border-bottom: 1px solid <?php echo $pal['line_lt']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>; vertical-align: top; }
	.poi .d { width: 90px; color: <?php echo $pal['primary']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>; font-weight: bold; text-align: right; }
	.advisor { width: 100%; margin-top: 26px; border: 1px solid <?php echo $pal['line']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>; border-radius: 6px; background: <?php echo $pal['tint']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>; }
	.advisor td { padding: 14px 16px; vertical-align: middle; }
	.advisor .photo { width: 70px; padding-right: 0; }
	.advisor .photo img { width: 56px; height: 56px; border-radius: 28px; }
	.advisor .name { color: <?php echo $pal['text']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>; font-family: Helvetica, sans-serif; font-size: 15pt; }
	.advisor .role { color: <?php echo $pal['muted']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>; font-size: 9pt; }
	.advisor .links { margin-top: 4px; color: <?php echo $pal['text']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>; }
	.advisor .links span { margin-right: 14px; }
	.plan { page-break-before: always; }
	.plan-img { display: block; max-width: 100%; max-height: 215mm; margin: 14px auto 0; }
	.footer { position: fixed; right: 0; bottom: -12mm; left: 0; padding-top: 6px; border-top: 1px solid <?php echo $pal['line']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>; color: <?php echo $pal['muted']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>; font-size: 8pt; }
</style>
</head>
<body>

<div class="footer"><?php echo $e( get_bloginfo( 'name' ) . ' · ' . preg_replace( '#^https?://#', '', home_url() ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>

<table class="top"><tr>
	<td><?php echo $logo ? '<img src="' . $logo . '" style="height:34px" alt="">' : '<b>' . $e( get_bloginfo( 'name' ) ) . '</b>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
	<td class="right"><?php esc_html_e( 'Scheda immobile', 'slug-theme' ); ?></td>
</tr></table>

<table class="head"><tr>
	<td>
		<?php foreach ( array_values( $badges ) as $i => $badge ) : ?>
			<span class="badge<?php echo 0 === $i ? ' solid' : ''; ?>"><?php echo $e( $badge ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
		<?php endforeach; ?>
		<h1><?php echo $e( $title ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h1>
		<?php if ( $where ) : ?>
			<div class="where"><?php echo starter_imm_pdf_icon( 'map-pin', 13 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php echo $e( $where ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		<?php endif; ?>
	</td>
	<td class="price">
		<div class="price-label"><?php esc_html_e( 'Prezzo', 'slug-theme' ); ?></div>
		<div class="price-value"><?php echo $e( starter_imm_get_price_label( $post_id ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
	</td>
</tr></table>

<?php if ( $cover ) : ?>
	<img class="cover" src="<?php echo $cover; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" alt="">
<?php endif; ?>

<?php if ( '' !== $opening ) : ?>
	<p class="lead"><?php echo $e( $opening ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
<?php endif; ?>

<?php if ( $facts ) : ?>
	<div class="block">
		<div class="eyebrow"><?php esc_html_e( 'In sintesi', 'slug-theme' ); ?></div>
		<table class="facts"><tr>
			<?php foreach ( $facts as $fact ) : ?>
				<td>
					<?php echo starter_imm_pdf_icon( $fact[0], 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<div class="fact-value"><?php echo $e( $fact[1] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					<?php if ( $fact[2] ) : ?>
						<div class="fact-note"><?php echo $e( $fact[2] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					<?php endif; ?>
				</td>
			<?php endforeach; ?>
		</tr></table>
	</div>
<?php endif; ?>

<?php if ( $desc ) : ?>
	<div class="block">
		<div class="eyebrow"><?php esc_html_e( 'Descrizione', 'slug-theme' ); ?></div>
		<?php if ( '' !== $desc_h ) : ?>
			<h2><?php echo $e( $desc_h ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
		<?php endif; ?>
		<div class="text"><?php echo wp_kses( $desc, wp_kses_allowed_html( 'post' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
	</div>
<?php endif; ?>

<?php if ( is_array( $strengs ) && $strengs ) : ?>
	<div class="block">
		<div class="eyebrow"><?php esc_html_e( 'Punti di forza', 'slug-theme' ); ?></div>
		<table class="cols">
			<?php foreach ( array_chunk( $strengs, 2 ) as $pair ) : ?>
				<tr>
					<?php foreach ( $pair as $strength ) : ?>
						<td>
							<h3><?php echo $e( $strength['punti_di_forza_titolo'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
							<?php if ( $strength['punti_di_forza_testo'] ) : ?>
								<p><?php echo $e( $strength['punti_di_forza_testo'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
							<?php endif; ?>
						</td>
					<?php endforeach; ?>
					<?php if ( 1 === count( $pair ) ) : ?>
						<td style="border:0"></td>
					<?php endif; ?>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>
<?php endif; ?>

<?php if ( is_array( $blocks ) && $blocks ) : ?>
	<div class="block">
		<table class="cols">
			<?php foreach ( array_chunk( $blocks, 2 ) as $pair ) : ?>
				<tr>
					<?php foreach ( $pair as $box ) : ?>
						<td>
							<?php if ( $box['blocchi_piano_occhiello'] ) : ?>
								<div class="eyebrow"><?php echo $e( $box['blocchi_piano_occhiello'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
							<?php endif; ?>
							<h3><?php echo $e( $box['blocchi_piano_titolo'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
							<p><?php echo $e( $box['blocchi_piano_testo'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
						</td>
					<?php endforeach; ?>
					<?php if ( 1 === count( $pair ) ) : ?>
						<td style="border:0"></td>
					<?php endif; ?>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>
<?php endif; ?>

<?php if ( $feats || $rows ) : ?>
	<div class="block">
		<div class="eyebrow"><?php esc_html_e( 'Caratteristiche', 'slug-theme' ); ?></div>
		<?php if ( $feats ) : ?>
			<div class="chips">
				<?php foreach ( $feats as $feat ) : ?>
					<span class="chip"><?php echo $e( $feat ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<?php if ( $rows ) : ?>
			<table class="data">
				<?php foreach ( array_chunk( $rows, 2 ) as $pair ) : ?>
					<tr>
						<?php foreach ( $pair as $row ) : ?>
							<td><span class="k"><?php echo $e( $row[0] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><br><span class="v"><?php echo $e( $row[1] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span></td>
						<?php endforeach; ?>
						<?php if ( 1 === count( $pair ) ) : ?>
							<td style="border:0"></td>
						<?php endif; ?>
					</tr>
				<?php endforeach; ?>
			</table>
		<?php endif; ?>
	</div>
<?php endif; ?>

<?php if ( $zone_h || $zone_t || ( is_array( $poi ) && $poi ) ) : ?>
	<div class="block">
		<div class="eyebrow"><?php esc_html_e( 'La zona', 'slug-theme' ); ?></div>
		<?php if ( $zone_h ) : ?>
			<h2><?php echo $e( $zone_h ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
		<?php endif; ?>
		<?php if ( $zone_t ) : ?>
			<div class="text"><?php echo wp_kses_post( wpautop( esc_html( $zone_t ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		<?php endif; ?>
		<?php if ( is_array( $poi ) && $poi ) : ?>
			<table class="poi">
				<?php foreach ( $poi as $place ) : ?>
					<?php $type = isset( $types[ $place['punti_interesse_tipo'] ] ) ? $types[ $place['punti_interesse_tipo'] ] : array( '', 'map-pin' ); ?>
					<tr>
						<td style="width:24px"><?php echo starter_imm_pdf_icon( $type[1], 15 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
						<td><?php echo $e( $place['punti_interesse_nome'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
						<td class="d"><?php echo $e( $place['punti_interesse_distanza'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
					</tr>
				<?php endforeach; ?>
			</table>
		<?php endif; ?>
	</div>
<?php endif; ?>

<?php if ( '' !== trim( (string) $adv['name'] ) ) : ?>
	<?php $photo = ( ! empty( $adv['photo'] ) && is_array( $adv['photo'] ) && ! empty( $adv['photo']['ID'] ) ) ? starter_imm_pdf_image( (int) $adv['photo']['ID'], 240, 240, true ) : ''; ?>
	<table class="advisor"><tr>
		<?php if ( $photo ) : ?>
			<td class="photo"><img src="<?php echo $photo; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" alt=""></td>
		<?php endif; ?>
		<td>
			<div class="eyebrow"><?php esc_html_e( 'Il tuo consulente', 'slug-theme' ); ?></div>
			<div class="name"><?php echo $e( $adv['name'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<?php if ( ! empty( $adv['role'] ) ) : ?>
				<div class="role"><?php echo $e( $adv['role'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<?php endif; ?>
			<div class="links">
				<?php if ( ! empty( $adv['phone'] ) ) : ?>
					<span><?php echo starter_imm_pdf_icon( 'phone', 12, $pal['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php echo $e( $adv['phone'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<?php endif; ?>
				<?php if ( ! empty( $adv['mail'] ) ) : ?>
					<span><?php echo starter_imm_pdf_icon( 'mail', 12, $pal['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php echo $e( $adv['mail'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<?php endif; ?>
			</div>
		</td>
	</tr></table>
<?php endif; ?>

<?php if ( is_array( $plans ) && $plans ) : ?>
	<?php foreach ( $plans as $plan ) : ?>
		<?php $plan_src = starter_imm_pdf_image( isset( $plan['ID'] ) ? (int) $plan['ID'] : 0, 1800 ); ?>
		<?php if ( $plan_src ) : ?>
			<div class="plan">
				<div class="eyebrow"><?php esc_html_e( 'Planimetria', 'slug-theme' ); ?></div>
				<h2><?php echo $e( $title ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
				<img class="plan-img" src="<?php echo $plan_src; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" alt="">
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
<?php endif; ?>

</body>
</html>
	<?php

	return (string) ob_get_clean();
}

/* Genera (o riusa dalla cache) il PDF e restituisce il percorso del file. */
function starter_imm_pdf_build( $post_id ) {
	$autoload = get_template_directory() . '/inc/modules/immobili/dompdf/autoload.inc.php';

	if ( ! file_exists( $autoload ) ) {
		return '';
	}

	$html    = starter_imm_pdf_html( $post_id );
	$uploads = wp_upload_dir();
	$dir     = trailingslashit( $uploads['basedir'] ) . 'schede-immobili';
	$key     = md5( $html );
	$file    = $dir . '/' . (int) $post_id . '-' . $key . '.pdf';

	if ( file_exists( $file ) ) {
		return $file;
	}

	wp_mkdir_p( $dir );

	if ( ! file_exists( $dir . '/index.php' ) ) {
		file_put_contents( $dir . '/index.php', "<?php\n// Silence is golden.\n" ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
	}

	if ( function_exists( 'wp_raise_memory_limit' ) ) {
		wp_raise_memory_limit( 'admin' );
	}

	@set_time_limit( 120 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged

	require_once $autoload;

	$options = new \Dompdf\Options();
	$options->set( 'isRemoteEnabled', false );
	$options->set( 'isHtml5ParserEnabled', true );
	$options->set( 'defaultFont', 'Helvetica' );
	$options->set( 'fontCache', $dir );
	$options->set( 'tempDir', $dir );
	$options->set( 'chroot', array( get_template_directory() . '/inc/modules/immobili/dompdf', $uploads['basedir'] ) );

	$dompdf = new \Dompdf\Dompdf( $options );
	$dompdf->setPaper( 'A4', 'portrait' );
	$dompdf->loadHtml( $html, 'UTF-8' );
	$dompdf->render();

	// Numero di pagina in basso a destra.
	$canvas = $dompdf->getCanvas();
	$font   = $dompdf->getFontMetrics()->getFont( 'Helvetica' );
	$canvas->page_text( $canvas->get_width() - 60, $canvas->get_height() - 36, '{PAGE_NUM} / {PAGE_COUNT}', $font, 8, array( 0.35, 0.42, 0.48 ) );

	$output = $dompdf->output();

	foreach ( (array) glob( $dir . '/' . (int) $post_id . '-*.pdf' ) as $old ) {
		@unlink( $old ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
	}

	file_put_contents( $file, $output ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents

	return $file;
}

/* Scarica il PDF all'indirizzo della scheda con ?scheda-pdf=1 */
function starter_imm_pdf_download() {
	if ( ! is_singular( 'immobile' ) || ! isset( $_GET['scheda-pdf'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return;
	}

	$post_id = get_queried_object_id();
	$file    = starter_imm_pdf_build( $post_id );

	if ( ! $file || ! file_exists( $file ) ) {
		wp_die( esc_html__( 'Non è stato possibile creare la scheda in PDF. Riprova tra qualche istante.', 'slug-theme' ), '', array( 'response' => 500 ) );
	}

	nocache_headers();
	header( 'Content-Type: application/pdf' );
	header( 'Content-Disposition: attachment; filename="' . sanitize_file_name( get_post_field( 'post_name', $post_id ) . '-scheda.pdf' ) . '"' );
	header( 'Content-Length: ' . filesize( $file ) );
	header( 'X-Robots-Tag: noindex, nofollow' );
	readfile( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_readfile
	exit;
}
add_action( 'template_redirect', 'starter_imm_pdf_download' );
