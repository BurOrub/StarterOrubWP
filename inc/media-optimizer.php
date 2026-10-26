<?php
/** Ottimizzazione delle immagini già in Libreria media: crea gli AVIF mancanti e converte JPG/PNG rimasti in WebP (pagina Media → Ottimizza immagini e comandi WP-CLI). */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ============================================================ Conteggi */

/**
 * Fotografia della libreria: quali immagini si possono ottimizzare e a che punto sono.
 * Restituisce id da fare per l'AVIF ('avif_ids'), id JPG/PNG da convertire ('webp_ids'), tutti gli id ottimizzabili ('all_ids') e i video.
 */
function starter_media_stats() {
	global $wpdb;

	$rows = $wpdb->get_results( "SELECT p.ID, pm.meta_value AS file, p.post_mime_type AS mime FROM {$wpdb->posts} p INNER JOIN {$wpdb->postmeta} pm ON pm.post_id = p.ID AND pm.meta_key = '_wp_attached_file' WHERE p.post_type = 'attachment' AND ( p.post_mime_type LIKE 'image/%' OR p.post_mime_type LIKE 'video/%' ) ORDER BY p.ID ASC" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching

	$flag = function ( $key ) use ( $wpdb ) {
		return array_map( 'intval', (array) $wpdb->get_col( $wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = %s", $key ) ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
	};

	$avif_done  = array_flip( $flag( '_starter_avif' ) );
	$webp_fail  = array_flip( $flag( '_starter_webp_failed' ) );
	$site_icons = array_flip( $flag( '_wp_attachment_context' ) );
	$icon_id    = (int) get_option( 'site_icon' );
	$upload     = wp_get_upload_dir();

	$out = array(
		'avif_ids'   => array(),
		'webp_ids'   => array(),
		'all_ids'    => array(),
		'mime_ids'   => array(),
		'with_avif'  => 0,
		'videos'     => 0,
		'videos_alt' => 0,
	);

	foreach ( $rows as $row ) {
		$id  = (int) $row->ID;
		$ext = strtolower( pathinfo( (string) $row->file, PATHINFO_EXTENSION ) );

		if ( 0 === strpos( $row->mime, 'video/' ) ) {
			if ( in_array( $ext, array( 'mp4', 'webm' ), true ) ) {
				++$out['videos'];
				$alt = preg_replace( '/\.(?:mp4|webm)$/i', 'mp4' === $ext ? '.webm' : '.mp4', $row->file );

				if ( is_file( $upload['basedir'] . '/' . $alt ) ) {
					++$out['videos_alt'];
				}
			}
			continue;
		}

		if ( ! in_array( $ext, array( 'webp', 'jpg', 'jpeg', 'png' ), true ) || $id === $icon_id || isset( $site_icons[ $id ] ) || starter_keep_original_format( (string) $row->file ) ) {
			continue;
		}

		$out['all_ids'][] = $id;

		if ( isset( $avif_done[ $id ] ) ) {
			++$out['with_avif'];
		} else {
			$out['avif_ids'][] = $id;
		}

		if ( 'webp' !== $ext && ! isset( $webp_fail[ $id ] ) ) {
			$out['webp_ids'][] = $id;
		}

		if ( 'webp' === $ext && 'image/webp' !== $row->mime ) {
			$out['mime_ids'][] = $id;
		}
	}

	return $out;
}

/* ============================================================ Sostituzione degli indirizzi nel database */

/* Sostituisce $map (vecchio => nuovo) dentro un valore, anche se è un array serializzato (le lunghezze si ricalcolano). */
function starter_replace_in_value( $value, array $map ) {
	if ( is_array( $value ) ) {
		foreach ( $value as $k => $v ) {
			$value[ $k ] = starter_replace_in_value( $v, $map );
		}

		return $value;
	}

	if ( ! is_string( $value ) || '' === $value ) {
		return $value;
	}

	if ( is_serialized( $value ) ) {
		// Gli oggetti serializzati non si toccano.
		if ( preg_match( '/(?:^|[;{}])O:\d+:"/', $value ) ) {
			return $value;
		}

		$data = @unserialize( $value, array( 'allowed_classes' => false ) ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged, WordPress.PHP.DiscouragedPHPFunctions.serialize_unserialize

		return false === $data && 'b:0;' !== $value ? $value : serialize( starter_replace_in_value( $data, $map ) ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize
	}

	return strtr( $value, $map );
}

/* Cambia nel database (articoli, campi personalizzati, opzioni, meta dei termini) gli indirizzi dei file convertiti. $stem: parte iniziale comune, es. "/2026/08/foto". */
function starter_replace_urls_in_db( $stem, array $map ) {
	global $wpdb;

	if ( ! $map ) {
		return;
	}

	// Gli stessi indirizzi compaiono anche con le barre "\/" dei dati JSON.
	foreach ( $map as $old => $new ) {
		$map[ str_replace( '/', '\\/', $old ) ] = str_replace( '/', '\\/', $new );
	}

	$likes = array( '%' . $wpdb->esc_like( $stem ) . '%', '%' . $wpdb->esc_like( str_replace( '/', '\\/', $stem ) ) . '%' );

	// Articoli e pagine (anche revisioni e bozze).
	foreach ( array( 'post_content', 'post_excerpt', 'post_content_filtered' ) as $column ) {
		$ids = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE {$column} LIKE %s OR {$column} LIKE %s", $likes ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		foreach ( $ids as $post_id ) {
			$value = (string) $wpdb->get_var( $wpdb->prepare( "SELECT {$column} FROM {$wpdb->posts} WHERE ID = %d", $post_id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$new   = strtr( $value, $map );

			if ( $new !== $value ) {
				$wpdb->update( $wpdb->posts, array( $column => $new ), array( 'ID' => (int) $post_id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
				clean_post_cache( (int) $post_id );
			}
		}
	}

	// Campi personalizzati, opzioni (anche del Personalizza) e meta dei termini: qui i valori possono essere serializzati.
	$tables = array(
		array( $wpdb->postmeta, 'meta_id', 'meta_value', '' ),
		array( $wpdb->termmeta, 'meta_id', 'meta_value', '' ),
		array( $wpdb->options, 'option_id', 'option_value', " AND option_name NOT LIKE '\\_transient%' AND option_name NOT LIKE '\\_site\\_transient%'" ),
	);

	foreach ( $tables as list( $table, $key, $column, $extra ) ) {
		$rows = $wpdb->get_results( $wpdb->prepare( "SELECT {$key} AS id, {$column} AS value FROM {$table} WHERE ( {$column} LIKE %s OR {$column} LIKE %s )", $likes ) . $extra ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		foreach ( $rows as $row ) {
			$new = starter_replace_in_value( $row->value, $map );

			if ( $new !== $row->value ) {
				$wpdb->update( $table, array( $column => $new ), array( $key => (int) $row->id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			}
		}
	}

	wp_cache_flush();
}

/* ============================================================ Conversione JPG/PNG → WebP di un allegato già presente */

/**
 * Converte un allegato JPG/PNG in WebP: nuovo file e nuove misure, aggiorna la libreria, sostituisce i vecchi indirizzi nei contenuti
 * e solo alla fine cancella i vecchi file. Se qualcosa va storto l'originale resta com'era. Restituisce true se convertito.
 */
function starter_convert_attachment_to_webp( $id ) {
	$id   = (int) $id;
	$file = get_attached_file( $id );

	if ( ! $file || ! is_file( $file ) || ! preg_match( '/\.(?:jpe?g|png)$/i', $file ) || $id === (int) get_option( 'site_icon' ) || 'site-icon' === get_post_meta( $id, '_wp_attachment_context', true ) || starter_keep_original_format( $file ) ) {
		return false;
	}

	if ( function_exists( 'set_time_limit' ) ) {
		@set_time_limit( 120 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
	}

	wp_raise_memory_limit( 'image' );

	$webp = starter_convert_to_webp( $file );

	if ( ! $webp ) {
		return false;
	}

	$old_meta  = (array) wp_get_attachment_metadata( $id );
	$old_mime  = (string) get_post_mime_type( $id );
	$dir       = dirname( $file );
	$old_files = array( $file );

	foreach ( (array) ( $old_meta['sizes'] ?? array() ) as $size ) {
		if ( ! empty( $size['file'] ) ) {
			$old_files[] = $dir . '/' . $size['file'];
		}
	}

	update_attached_file( $id, $webp );
	wp_update_post(
		array(
			'ID'             => $id,
			'post_mime_type' => 'image/webp',
		)
	);

	$new_meta = wp_generate_attachment_metadata( $id, $webp );

	if ( empty( $new_meta ) || ! is_array( $new_meta ) || empty( $new_meta['file'] ) ) {
		// Marcia indietro: l'originale non è stato toccato.
		update_attached_file( $id, $file );
		wp_update_post(
			array(
				'ID'             => $id,
				'post_mime_type' => $old_mime,
			)
		);
		wp_delete_file( $webp );

		return false;
	}

	foreach ( array( 'image_meta', 'original_image' ) as $keep ) {
		if ( isset( $old_meta[ $keep ] ) ) {
			$new_meta[ $keep ] = $old_meta[ $keep ];
		}
	}

	// Coppie vecchio → nuovo indirizzo (relativo a uploads), dalla più lunga alla più corta ci pensa strtr().
	$old_rel = _wp_relative_upload_path( $file );
	$new_rel = _wp_relative_upload_path( $webp );
	$subdir  = dirname( $old_rel );
	$subdir  = '.' === $subdir ? '' : $subdir . '/';
	$map     = array( '/' . $old_rel => '/' . $new_rel );

	foreach ( (array) ( $old_meta['sizes'] ?? array() ) as $name => $size ) {
		if ( empty( $size['file'] ) ) {
			continue;
		}

		$target = ! empty( $new_meta['sizes'][ $name ]['file'] ) ? $new_meta['sizes'][ $name ]['file'] : wp_basename( $webp );

		$map[ '/' . $subdir . $size['file'] ] = '/' . $subdir . $target;
	}

	// Gli .avif creati dalla vecchia immagine non valgono più: si rifanno dal nuovo WebP.
	foreach ( $old_files as $old ) {
		$stale = preg_replace( '/\.(?:jpe?g|png)$/i', '.avif', $old );

		if ( is_file( $stale ) ) {
			@unlink( $stale ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged, WordPress.WP.AlternativeFunctions.unlink_unlink
		}
	}

	wp_update_attachment_metadata( $id, $new_meta );

	starter_replace_urls_in_db( '/' . $subdir . pathinfo( $old_rel, PATHINFO_FILENAME ), $map );

	// Solo ora i vecchi file possono sparire (senza passare da wp_delete_file, che toglierebbe anche l'.avif appena creato).
	foreach ( array_unique( $old_files ) as $old ) {
		if ( is_file( $old ) && $old !== $webp ) {
			@unlink( $old ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged, WordPress.WP.AlternativeFunctions.unlink_unlink
		}
	}

	update_post_meta( $id, '_starter_converted_from', wp_basename( $file ) );
	starter_generate_avif_for_attachment( $id );

	return true;
}

/* Allegati con estensione .webp ma tipo MIME vecchio (es. image/png): si riallinea il tipo. Restituisce quanti ne ha sistemati. */
function starter_media_fix_mimes( array $ids ) {
	foreach ( $ids as $id ) {
		wp_update_post(
			array(
				'ID'             => (int) $id,
				'post_mime_type' => 'image/webp',
			)
		);
	}

	return count( $ids );
}

/* ============================================================ Lavoro a lotti (pagina admin e WP-CLI) */

/**
 * Esegue un lotto del compito 'avif' o 'webp' entro $budget secondi. Con $force (solo avif) rifà anche gli .avif già creati, partendo da $offset.
 * Restituisce array( fatti, rimasti, prossimo offset ).
 */
function starter_media_run_batch( $task, $budget = 8, $force = false, $offset = 0 ) {
	$start = microtime( true );
	$stats = starter_media_stats();
	$done  = 0;

	if ( 'webp' === $task ) {
		starter_media_fix_mimes( $stats['mime_ids'] );
		$ids = $stats['webp_ids'];

		foreach ( $ids as $id ) {
			if ( microtime( true ) - $start > $budget ) {
				break;
			}

			if ( ! starter_convert_attachment_to_webp( $id ) ) {
				update_post_meta( $id, '_starter_webp_failed', 1 );
			}

			++$done;
		}

		return array( $done, max( 0, count( $ids ) - $done ), 0 );
	}

	$ids = $force ? array_slice( $stats['all_ids'], (int) $offset ) : $stats['avif_ids'];

	foreach ( $ids as $id ) {
		if ( microtime( true ) - $start > $budget ) {
			break;
		}

		starter_generate_avif_for_attachment( $id, $force );
		++$done;
	}

	return array( $done, max( 0, count( $ids ) - $done ), (int) $offset + $done );
}

add_action(
	'wp_ajax_starter_media_batch',
	function () {
		check_ajax_referer( 'starter_media', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'Permessi insufficienti.', 'slug-theme' ), 403 );
		}

		$task = 'webp' === ( $_POST['task'] ?? '' ) ? 'webp' : 'avif';

		list( $done, $left, $next ) = starter_media_run_batch( $task, 8, ! empty( $_POST['force'] ), (int) ( $_POST['offset'] ?? 0 ) );

		wp_send_json_success(
			array(
				'done'   => $done,
				'left'   => $left,
				'offset' => $next,
			)
		);
	}
);

/* ============================================================ Pagina Media → Ottimizza immagini */

add_action(
	'admin_menu',
	function () {
		add_submenu_page(
			'upload.php',
			__( 'Ottimizza immagini', 'slug-theme' ),
			__( 'Ottimizza immagini', 'slug-theme' ),
			'manage_options',
			'starter-media-optimize',
			'starter_media_optimize_page'
		);
	}
);

/* Disegna la pagina Media → Ottimizza immagini (conteggi, pulsanti e barra di avanzamento) */
function starter_media_optimize_page() {
	$stats = starter_media_stats();
	$total = count( $stats['all_ids'] );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Ottimizza immagini', 'slug-theme' ); ?></h1>

		<table class="widefat striped" style="max-width:640px">
			<tbody>
				<tr><td><?php esc_html_e( 'Server in grado di creare AVIF', 'slug-theme' ); ?></td><td><strong><?php echo starter_avif_supported() ? esc_html__( 'Sì', 'slug-theme' ) : esc_html__( 'No (restano i WebP)', 'slug-theme' ); ?></strong></td></tr>
				<tr><td><?php esc_html_e( 'Immagini ottimizzabili (esclusi SVG, favicon, logo email)', 'slug-theme' ); ?></td><td id="sm-total"><?php echo (int) $total; ?></td></tr>
				<tr><td><?php esc_html_e( 'Con versione AVIF già elaborata', 'slug-theme' ); ?></td><td id="sm-avif"><?php echo (int) $stats['with_avif']; ?></td></tr>
				<tr><td><?php esc_html_e( 'JPG/PNG ancora da convertire in WebP', 'slug-theme' ); ?></td><td id="sm-webp"><?php echo count( $stats['webp_ids'] ); ?></td></tr>
				<tr><td><?php esc_html_e( 'Video mp4/webm con il file gemello', 'slug-theme' ); ?></td><td><?php echo (int) $stats['videos_alt']; ?> / <?php echo (int) $stats['videos']; ?></td></tr>
			</tbody>
		</table>

		<h2><?php esc_html_e( '1. Crea gli AVIF', 'slug-theme' ); ?></h2>
		<p>
			<button type="button" class="button button-primary" data-task="avif" <?php disabled( ! starter_avif_supported() ); ?>><?php esc_html_e( 'Crea gli AVIF mancanti', 'slug-theme' ); ?></button>
			<label style="margin-left:12px"><input type="checkbox" id="sm-force"> <?php esc_html_e( 'Rifai anche quelli già creati (dopo aver cambiato la qualità)', 'slug-theme' ); ?></label>
		</p>

		<h2><?php esc_html_e( '2. Converti i JPG e PNG rimasti in WebP', 'slug-theme' ); ?></h2>
		<p>
			<label><input type="checkbox" id="sm-backup"> <?php esc_html_e( 'Ho fatto un backup di database e cartella uploads', 'slug-theme' ); ?></label>
		</p>
		<p><button type="button" class="button button-primary" data-task="webp" disabled><?php esc_html_e( 'Converti in WebP', 'slug-theme' ); ?></button></p>

		<progress id="sm-bar" value="0" max="1" style="width:100%;max-width:640px;display:none"></progress>
		<p id="sm-log" style="max-width:640px"></p>
	</div>

	<script>
	( function () {
		var buttons = document.querySelectorAll( '[data-task]' ), bar = document.getElementById( 'sm-bar' ), log = document.getElementById( 'sm-log' ), backup = document.getElementById( 'sm-backup' ), force = document.getElementById( 'sm-force' ), busy = false;
		var nonce = <?php echo wp_json_encode( wp_create_nonce( 'starter_media' ) ); ?>, ajax = <?php echo wp_json_encode( admin_url( 'admin-ajax.php' ) ); ?>;

		backup.addEventListener( 'change', function () {
			buttons.forEach( function ( b ) { if ( 'webp' === b.dataset.task ) { b.disabled = ! backup.checked; } } );
		} );

		function run( task ) {
			var offset = 0, total = 0, finished = 0;
			busy = true;
			buttons.forEach( function ( b ) { b.disabled = true; } );
			bar.style.display = 'block';
			bar.value = 0;
			log.textContent = <?php echo wp_json_encode( __( 'Lavoro in corso… non chiudere la pagina.', 'slug-theme' ) ); ?>;

			( function step() {
				var body = new URLSearchParams( { action: 'starter_media_batch', nonce: nonce, task: task, offset: offset } );
				if ( force.checked && 'avif' === task ) { body.set( 'force', '1' ); }

				fetch( ajax, { method: 'POST', body: body, credentials: 'same-origin' } )
					.then( function ( r ) { return r.json(); } )
					.then( function ( res ) {
						if ( ! res || ! res.success ) { throw new Error( res && res.data ? res.data : 'errore' ); }
						var d = res.data;
						finished += d.done;
						total = Math.max( total, finished + d.left );
						offset = d.offset;
						bar.max = total || 1;
						bar.value = finished;
						log.textContent = finished + ' / ' + total;
						if ( d.left > 0 && d.done > 0 ) { step(); return; }
						log.textContent = <?php echo wp_json_encode( __( 'Fatto: ', 'slug-theme' ) ); ?> + finished + ' / ' + total + <?php echo wp_json_encode( __( '. Ricarica la pagina per i nuovi conteggi.', 'slug-theme' ) ); ?>;
						busy = false;
					} )
					.catch( function ( e ) {
						log.textContent = <?php echo wp_json_encode( __( 'Interrotto: ', 'slug-theme' ) ); ?> + e.message + <?php echo wp_json_encode( __( '. Puoi rilanciare: riparte da dove era arrivato.', 'slug-theme' ) ); ?>;
						busy = false;
					} );
			} )();
		}

		buttons.forEach( function ( b ) {
			b.addEventListener( 'click', function () { if ( ! busy ) { run( b.dataset.task ); } } );
		} );
	} )();
	</script>
	<?php
}

/* ============================================================ WP-CLI */

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	/**
	 * Ottimizzazione immagini del tema.
	 */
	class Starter_Media_CLI {
		/**
		 * Mostra a che punto è la libreria.
		 *
		 * @when after_wp_load
		 */
		public function status() {
			$stats = starter_media_stats();

			WP_CLI::log( 'AVIF sul server: ' . ( starter_avif_supported() ? 'sì' : 'no' ) );
			WP_CLI::log( 'Immagini ottimizzabili: ' . count( $stats['all_ids'] ) );
			WP_CLI::log( 'Con AVIF elaborato: ' . $stats['with_avif'] );
			WP_CLI::log( 'JPG/PNG da convertire: ' . count( $stats['webp_ids'] ) );
			WP_CLI::log( 'Video con gemello: ' . $stats['videos_alt'] . ' / ' . $stats['videos'] );
		}

		/**
		 * Crea gli AVIF mancanti.
		 *
		 * ## OPTIONS
		 *
		 * [--force]
		 * : Rifà anche gli AVIF già creati.
		 *
		 * @when after_wp_load
		 */
		public function avif( $args, $assoc ) {
			$this->loop( 'avif', ! empty( $assoc['force'] ) );
		}

		/**
		 * Converte in WebP i JPG e PNG rimasti (fai prima un backup).
		 *
		 * @when after_wp_load
		 */
		public function webp() {
			$this->loop( 'webp', false );
		}

		/* Ripete i lotti finché non resta nulla da fare */
		private function loop( $task, $force ) {
			$offset = 0;
			$total  = 0;

			do {
				list( $done, $left, $offset ) = starter_media_run_batch( $task, 20, $force, $offset );
				$total += $done;
				WP_CLI::log( "{$total} fatti, {$left} da fare" );
			} while ( $left > 0 && $done > 0 );

			WP_CLI::success( "{$total} immagini elaborate." );
		}
	}

	WP_CLI::add_command( 'starter media', 'Starter_Media_CLI' );
}
