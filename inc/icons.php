<?php
/** Set di icone SVG lineari (stile Lucide) usate da template e blocchi. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Contenuto interno degli SVG, per nome. */
function starter_icon_paths() {
	// I moduli (inc/modules.php) aggiungono le loro icone con il filtro; quelle del tema hanno sempre la precedenza.
	return array_merge( apply_filters( 'starter_icon_paths', array() ), starter_icon_paths_base() );
}

/* Icone di linea del tema (nome → tracciati SVG) */
function starter_icon_paths_base() {
	return array(
		'check'          => '<path d="M20 6 9 17l-5-5"/>',
		'arrow-right'    => '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
		'arrow-up-right' => '<path d="M7 7h10v10"/><path d="M7 17 17 7"/>',
		'chevron-left'   => '<path d="m15 18-6-6 6-6"/>',
		'chevron-right'  => '<path d="m9 18 6-6-6-6"/>',
		'x'              => '<path d="M18 6 6 18"/><path d="m6 6 12 12"/>',
		'map-pin'        => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>',
		'phone'          => '<path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.7a2 2 0 0 1-.5 2.1L8 9.8a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.5c.9.3 1.8.6 2.7.7a2 2 0 0 1 1.7 2z"/>',
		'mail'           => '<rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>',
		'clock'          => '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>',
		'users'          => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.9"/><path d="M16 3.1a4 4 0 0 1 0 7.8"/>',
		'shield'         => '<path d="M20 13c0 5-3.5 7.5-7.7 8.9a1 1 0 0 1-.6 0C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.2-2.7a1.2 1.2 0 0 1 1.6 0C14.5 3.8 17 5 19 5a1 1 0 0 1 1 1z"/>',
		'sparkles'       => '<path d="M9.9 15.5a2 2 0 0 0-1.4-1.4l-6.1-1.6a.5.5 0 0 1 0-1l6.1-1.6a2 2 0 0 0 1.4-1.4l1.6-6.1a.5.5 0 0 1 1 0l1.6 6.1a2 2 0 0 0 1.4 1.4l6.1 1.6a.5.5 0 0 1 0 1l-6.1 1.6a2 2 0 0 0-1.4 1.4l-1.6 6.1a.5.5 0 0 1-1 0z"/>',
		'handshake'      => '<path d="m11 17 2 2a1 1 0 1 0 3-3"/><path d="m14 14 2.5 2.5a1 1 0 1 0 3-3l-3.9-3.9a3 3 0 0 0-4.2 0l-.9.9a1 1 0 0 1-1.4 0L7 8.5"/><path d="m21 3 1 11h-2"/><path d="M3 3 2 14l6.5 6.5a1 1 0 1 0 3-3"/><path d="M3 4h8"/>',
		'chart'          => '<path d="M3 3v16a2 2 0 0 0 2 2h16"/><path d="m19 9-5 5-4-4-3 3"/>',
		'building'       => '<path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/>',
		// Header shop (ricerca, account, preferiti, carrello) e filtri del negozio.
		'search'         => '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>',
		'user'           => '<circle cx="12" cy="8" r="4"/><path d="M4 21c1.5-4 4.5-6 8-6s6.5 2 8 6"/>',
		'heart'          => '<path d="M12 20s-7-4.5-7-10a4 4 0 0 1 7-2.5A4 4 0 0 1 19 10c0 5.5-7 10-7 10z"/>',
		'bag'            => '<path d="M5 8h14l-1.2 12H6.2z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/>',
		'menu'           => '<path d="M4 7h16"/><path d="M4 12h16"/><path d="M4 17h16"/>',
		'sliders'        => '<path d="M4 6h10"/><path d="M18 6h2"/><circle cx="16" cy="6" r="2"/><path d="M4 12h4"/><path d="M12 12h8"/><circle cx="10" cy="12" r="2"/><path d="M4 18h12"/><path d="M20 18h0"/><circle cx="18" cy="18" r="2"/>',
		'star'           => '<path d="M12 2.25 15 8.25l6.75.75-4.875 4.5 1.275 6.75L12 17.1 5.85 20.25l1.275-6.75L2.25 9l6.75-.75z"/>',
		'truck'          => '<path d="M3 6h11v10H3z"/><path d="M14 9h4l3 3v4h-7"/><circle cx="7" cy="17.5" r="1.8"/><circle cx="17" cy="17.5" r="1.8"/>',
		'gift'           => '<rect x="3" y="8" width="18" height="4" rx="1"/><path d="M12 8v13"/><path d="M19 12v9H5v-9"/><path d="M7.5 8a2.5 2.5 0 0 1 0-5C11 3 12 8 12 8s1-5 4.5-5a2.5 2.5 0 0 1 0 5"/>',
		'shield-check'   => '<path d="M12 2.25 3.75 5.25v6c0 5.25 3.75 9 8.25 10.5 4.5-1.5 8.25-5.25 8.25-10.5v-6z"/><path d="m8.25 12 3 3 5.25-6"/>',
		'key'            => '<path d="M2.6 17.4A2 2 0 0 0 2 18.8V21a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1h1a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1h.2a2 2 0 0 0 1.4-.6l.8-.8a6.5 6.5 0 1 0-4-4z"/><circle cx="16.5" cy="7.5" r=".5"/>',
	);
}

/* Scelte per i campi "Icona" (es. voci della Sezione voci in stile "icone"). */
function starter_icon_choices() {
	return array(
		'key'       => __( 'Chiave', 'slug-theme' ),
		'chart'     => __( 'Grafico', 'slug-theme' ),
		'building'  => __( 'Edificio', 'slug-theme' ),
		'handshake' => __( 'Stretta di mano', 'slug-theme' ),
		'shield'    => __( 'Scudo', 'slug-theme' ),
		'sparkles'  => __( 'Stelle', 'slug-theme' ),
		'users'     => __( 'Persone', 'slug-theme' ),
		'star'         => __( 'Stella', 'slug-theme' ),
		'shield-check' => __( 'Scudo (garanzia)', 'slug-theme' ),
		'truck'        => __( 'Spedizione', 'slug-theme' ),
		'gift'         => __( 'Regalo', 'slug-theme' ),
		'heart'        => __( 'Cuore', 'slug-theme' ),
	);
}

/* ------------------------------------------------------------------------------------------------------------------
 * Libreria completa (inc/icons-data/, generata da tools/build-icons.js): ~1.900 icone Lucide + loghi dei brand.
 * Le icone qui sopra (starter_icon_paths) hanno la precedenza: così l'aspetto di quelle già usate nel tema non cambia.
 * I loghi si chiamano "brand-instagram", "brand-facebook"… e si disegnano pieni (fill) invece che a linea.
 * ---------------------------------------------------------------------------------------------------------------- */

/* Legge un blocco di dati (una lettera, "brands" o "index") una sola volta per richiesta. */
function starter_icon_data( $file ) {
	static $cache = array();

	if ( ! isset( $cache[ $file ] ) ) {
		$path          = get_template_directory() . '/inc/icons-data/' . $file . '.json';
		$data          = is_readable( $path ) ? json_decode( (string) file_get_contents( $path ), true ) : array();
		$cache[ $file ] = is_array( $data ) ? $data : array();
	}

	return $cache[ $file ];
}

/* Vero per le icone dei social (nome che inizia per brand-) */
function starter_icon_is_brand( $name ) {
	return 0 === strpos( (string) $name, 'brand-' );
}

/* Icone caricate dall'utente (Libreria media → SVG): si chiamano "svg-123" (123 = ID dell'allegato). */
function starter_icon_is_custom( $name ) {
	return 0 === strpos( (string) $name, 'svg-' ) && ctype_digit( substr( (string) $name, 4 ) );
}

/**
 * Un SVG della Libreria media ripulito e pronto per essere disegnato inline come icona: array( vb, paint (attributi), inner ), oppure null.
 * Il risultato è messo in cache nei meta dell'allegato (si rifà se il file cambia).
 */
function starter_icon_svg_parts( $attachment_id ) {
	static $cache = array();

	$attachment_id = absint( $attachment_id );

	if ( isset( $cache[ $attachment_id ] ) || array_key_exists( $attachment_id, $cache ) ) {
		return $cache[ $attachment_id ];
	}

	$cache[ $attachment_id ] = null;

	if ( ! $attachment_id || 'attachment' !== get_post_type( $attachment_id ) || 'image/svg+xml' !== get_post_mime_type( $attachment_id ) ) {
		return null;
	}

	$path = get_attached_file( $attachment_id );

	if ( ! $path || ! is_readable( $path ) || filesize( $path ) > 262144 ) {
		return null;
	}

	$mtime = (int) filemtime( $path );
	$saved = get_post_meta( $attachment_id, '_starter_svg_parts', true );

	if ( is_array( $saved ) && isset( $saved['mtime'], $saved['parts'] ) && (int) $saved['mtime'] === $mtime ) {
		$cache[ $attachment_id ] = $saved['parts'];

		return $saved['parts'];
	}

	$svg = starter_sanitize_svg( (string) file_get_contents( $path ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

	if ( ! $svg || ! preg_match( '~<svg\b([^>]*)>(.*)</svg>~is', $svg, $m ) || '' === trim( $m[2] ) ) {
		return null;
	}

	$root = $m[1];
	$vb   = '0 0 24 24';

	if ( preg_match( '/\bviewBox\s*=\s*"([^"]+)"/i', $root, $v ) ) {
		$vb = trim( $v[1] );
	} elseif ( preg_match( '/\bwidth\s*=\s*"([\d.]+)[a-z%]*"/i', $root, $w ) && preg_match( '/\bheight\s*=\s*"([\d.]+)[a-z%]*"/i', $root, $h ) ) {
		$vb = '0 0 ' . $w[1] . ' ' . $h[1];
	}

	// Si tengono i colori/tratti dichiarati sull'<svg>; se non ce ne sono si usa il colore del testo, così l'icona si può colorare dal CSS.
	$paint = array();

	foreach ( array( 'fill', 'stroke', 'stroke-width', 'stroke-linecap', 'stroke-linejoin' ) as $attr ) {
		if ( preg_match( '/\b' . preg_quote( $attr, '/' ) . '\s*=\s*"([^"]*)"/i', $root, $a ) ) {
			$paint[ $attr ] = $a[1];
		}
	}

	if ( ! isset( $paint['fill'] ) && ! isset( $paint['stroke'] ) ) {
		$paint['fill'] = 'currentColor';
	}

	$parts = array(
		'vb'    => $vb,
		'paint' => $paint,
		'inner' => trim( $m[2] ),
	);

	update_post_meta( $attachment_id, '_starter_svg_parts', array( 'mtime' => $mtime, 'parts' => $parts ) );
	$cache[ $attachment_id ] = $parts;

	return $parts;
}

/* Contenuto interno dell'SVG per nome, oppure '' se l'icona non esiste. */
function starter_icon_inner( $name ) {
	$name = (string) $name;

	if ( '' === $name || ! preg_match( '/^[a-z0-9-]+$/', $name ) ) {
		return '';
	}

	$custom = starter_icon_paths();

	if ( isset( $custom[ $name ] ) ) {
		return $custom[ $name ];
	}

	if ( starter_icon_is_custom( $name ) ) {
		$parts = starter_icon_svg_parts( (int) substr( $name, 4 ) );

		return $parts ? $parts['inner'] : '';
	}

	if ( starter_icon_is_brand( $name ) ) {
		$brands = starter_icon_data( 'brands' );
		$slug   = substr( $name, 6 );

		return isset( $brands[ $slug ] ) ? $brands[ $slug ] : '';
	}

	$shard = starter_icon_data( preg_match( '/^[a-z]/', $name ) ? $name[0] : '_' );

	return isset( $shard[ $name ] ) ? $shard[ $name ] : '';
}

/* Vero se l'icona esiste nella libreria */
function starter_icon_exists( $name ) {
	return '' !== starter_icon_inner( $name );
}

/** Restituisce l'icona come SVG inline (decorativo, aria-hidden). */
function starter_icon( $name, $size = 16, $stroke = 1.75 ) {
	$inner = starter_icon_inner( $name );

	if ( '' === $inner ) {
		return '';
	}

	$vb = '0 0 24 24';

	if ( starter_icon_is_custom( $name ) ) {
		// SVG caricato dall'utente: mantiene il suo viewBox e i suoi attributi di colore/tratto.
		$parts = starter_icon_svg_parts( (int) substr( $name, 4 ) );
		$vb    = $parts['vb'];
		$paint = '';

		foreach ( $parts['paint'] as $attr => $value ) {
			$paint .= sprintf( ' %s="%s"', esc_attr( $attr ), esc_attr( $value ) );
		}

		$paint = ltrim( $paint );
	} elseif ( starter_icon_is_brand( $name ) ) {
		// I loghi sono pieni, le altre icone a linea.
		$paint = 'fill="currentColor" stroke="none"';
	} else {
		$paint = sprintf( 'fill="none" stroke="currentColor" stroke-width="%s" stroke-linecap="round" stroke-linejoin="round"', esc_attr( $stroke ) );
	}

	return sprintf(
		'<svg class="icon icon--%1$s" width="%2$d" height="%2$d" viewBox="%5$s" %3$s aria-hidden="true" focusable="false">%4$s</svg>',
		esc_attr( $name ),
		(int) $size,
		$paint, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		$inner, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		esc_attr( $vb )
	);
}

/**
 * Tutta la libreria per i selettori (backend ACF e plugin Starter Live): { icons: { nome: markup }, keywords: { nome: "parole" } }.
 * Le icone personalizzate del tema sostituiscono quelle omonime della libreria.
 */
function starter_icon_library() {
	$icons = array();

	foreach ( glob( get_template_directory() . '/inc/icons-data/?.json' ) ?: array() as $file ) {
		$icons = array_merge( $icons, starter_icon_data( basename( $file, '.json' ) ) );
	}

	foreach ( starter_icon_paths() as $name => $markup ) {
		$icons[ $name ] = $markup;
	}

	foreach ( starter_icon_data( 'brands' ) as $slug => $markup ) {
		$icons[ 'brand-' . $slug ] = $markup;
	}

	ksort( $icons );

	$keywords = starter_icon_data( 'index' );
	$svgs     = array();

	// SVG caricati dall'utente (i più recenti per primi).
	$uploaded = array();

	foreach ( get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'post_mime_type' => 'image/svg+xml', 'posts_per_page' => 300, 'orderby' => 'date', 'order' => 'DESC', 'fields' => 'ids' ) ) as $att_id ) {
		$one = starter_icon_svg_entry( $att_id );

		if ( $one ) {
			$uploaded[ 'svg-' . $att_id ] = $one;
		}
	}

	foreach ( $uploaded as $key => $one ) {
		$icons[ $key ]    = $one['inner'];
		$svgs[ $key ]     = array( 'vb' => $one['vb'], 'paint' => $one['paint'] );
		$keywords[ $key ] = $one['keywords'];
	}

	return array(
		'icons'    => $icons,
		'keywords' => $keywords,
		'svgs'     => (object) $svgs,
		'custom'   => array_keys( $uploaded ), // nell'ordine "più recenti per primi"
	);
}

/* Dati di un SVG caricato per i selettori: markup, viewBox, attributi e parole chiave (titolo del file). */
function starter_icon_svg_entry( $attachment_id ) {
	$parts = starter_icon_svg_parts( $attachment_id );

	if ( ! $parts ) {
		return null;
	}

	return array(
		'inner'    => $parts['inner'],
		'vb'       => $parts['vb'],
		'paint'    => (object) $parts['paint'],
		'keywords' => 'svg caricato personalizzato ' . strtolower( get_the_title( $attachment_id ) ),
	);
}

/* Elenco per i selettori: solo per chi può modificare contenuti. */
add_action(
	'rest_api_init',
	function () {
		register_rest_route(
			'starter/v1',
			'/icons',
			array(
				'methods'             => 'GET',
				'callback'            => 'starter_icon_library',
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);

		// Un SVG appena caricato: stessi dati di una voce dell'elenco.
		register_rest_route(
			'starter/v1',
			'/icons/svg/(?P<id>\d+)',
			array(
				'methods'             => 'GET',
				'callback'            => function ( WP_REST_Request $request ) {
					$id  = absint( $request['id'] );
					$one = current_user_can( 'edit_posts' ) ? starter_icon_svg_entry( $id ) : null;

					return $one ? array( 'name' => 'svg-' . $id ) + $one : new WP_Error( 'starter_icon_svg', __( 'SVG non valido.', 'slug-theme' ), array( 'status' => 404 ) );
				},
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);
	}
);
