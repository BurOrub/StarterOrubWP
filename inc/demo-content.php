<?php
/**
 * Contenuti di prova per collaudare il tema (Strumenti → Contenuti di prova).
 *
 * Crea, senza duplicare niente se lo rilanci:
 * - pagine di base: Privacy Policy e Cookie Policy (bozza da completare, con tabella di CookieYes), Preferiti, Blog;
 * - menu "Header" di prova con un sottomenu (per provare l'apertura col click nel pannello mobile);
 * - articoli di prova con categorie, tag e immagini (per Blog, archivi, ricerca, articolo);
 * - con WooCommerce attivo: pagine Woo (shop, carrello, cassa, account) se mancano, categorie, attributi e 12 prodotti
 *   finti con immagini segnaposto (uno in offerta, uno esaurito, due con galleria per la seconda foto all'hover).
 *
 * Tutto ciò che crea è marcato con il meta "_starter_demo": "Elimina i contenuti di prova" toglie solo quello
 * (le pagine Privacy e Cookie, utili davvero, restano). Nessun testo è definitivo: sono segnaposto da sostituire.
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ---------------------------------------------------------------- Pagina admin */
add_action(
	'admin_menu',
	function () {
		add_management_page(
			__( 'Contenuti di prova', 'slug-theme' ),
			__( 'Contenuti di prova', 'slug-theme' ),
			'manage_options',
			'starter-demo',
			'starter_demo_admin_page'
		);
	}
);

/* Pagina Strumenti → Contenuti di prova: pulsanti Crea/Elimina ed esito */
function starter_demo_admin_page() {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- messaggio di esito.
	$done = isset( $_GET['starter_demo_done'] ) ? sanitize_text_field( wp_unslash( $_GET['starter_demo_done'] ) ) : '';
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Contenuti di prova', 'slug-theme' ); ?></h1>
		<?php if ( $done ) : ?>
			<div class="notice notice-success"><p><?php echo esc_html( $done ); ?></p></div>
		<?php endif; ?>
		<p><?php esc_html_e( 'Crea le pagine standard (Home, Chi siamo, Servizi, Contatti), Privacy e Cookie, un menu, il modulo newsletter del footer, 7 articoli e — con WooCommerce — 12 prodotti finti. Si può rilanciare senza duplicare.', 'slug-theme' ); ?></p>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline-block;margin-right:12px">
			<input type="hidden" name="action" value="starter_demo_create">
			<?php wp_nonce_field( 'starter_demo' ); ?>
			<?php submit_button( __( 'Crea i contenuti di prova', 'slug-theme' ), 'primary', 'submit', false ); ?>
		</form>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline-block;margin-right:12px">
			<input type="hidden" name="action" value="starter_cookieyes_it">
			<?php wp_nonce_field( 'starter_demo' ); ?>
			<?php submit_button( __( 'Imposta CookieYes in italiano', 'slug-theme' ), 'secondary', 'submit', false ); ?>
		</form>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline-block" onsubmit="return confirm('<?php echo esc_js( __( 'Eliminare prodotti, articoli, menu e immagini di prova?', 'slug-theme' ) ); ?>');">
			<input type="hidden" name="action" value="starter_demo_delete">
			<?php wp_nonce_field( 'starter_demo' ); ?>
			<?php submit_button( __( 'Elimina i contenuti di prova', 'slug-theme' ), 'delete', 'submit', false ); ?>
		</form>
	</div>
	<?php
}

add_action(
	'admin_post_starter_demo_create',
	function () {
		check_admin_referer( 'starter_demo' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Non hai i permessi per farlo.', 'slug-theme' ) );
		}

		$report = starter_demo_create();
		wp_safe_redirect( add_query_arg( 'starter_demo_done', rawurlencode( $report ), admin_url( 'tools.php?page=starter-demo' ) ) );
		exit;
	}
);

add_action(
	'admin_post_starter_demo_delete',
	function () {
		check_admin_referer( 'starter_demo' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Non hai i permessi per farlo.', 'slug-theme' ) );
		}

		$count = starter_demo_delete();
		/* translators: %d: numero di elementi eliminati. */
		wp_safe_redirect( add_query_arg( 'starter_demo_done', rawurlencode( sprintf( __( 'Eliminati %d elementi di prova.', 'slug-theme' ), $count ) ), admin_url( 'tools.php?page=starter-demo' ) ) );
		exit;
	}
);

/* ------------------------------------------------------------------ Utilità */

/* Pagina con questo slug, se esiste (qualunque stato tranne cestino). */
function starter_demo_page_by_slug( $slug ) {
	$found = get_posts(
		array(
			'name'           => $slug,
			'post_type'      => 'page',
			'post_status'    => array( 'publish', 'draft', 'private', 'pending' ),
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);

	return $found ? (int) $found[0] : 0;
}

/* Crea una pagina se manca; $demo = true la marca come contenuto di prova (si elimina con il pulsante). */
function starter_demo_page( $slug, $title, $content, $demo = true ) {
	$id = starter_demo_page_by_slug( $slug );

	if ( $id ) {
		return $id;
	}

	$id = wp_insert_post(
		array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_name'    => $slug,
			'post_title'   => $title,
			'post_content' => $content,
		)
	);

	if ( $id && ! is_wp_error( $id ) && $demo ) {
		update_post_meta( $id, '_starter_demo', 1 );
	}

	return (int) $id;
}

/* Immagine segnaposto (PNG con sfondo a tinta unita e il testo) nella libreria media; 0 se GD non c'è. */
function starter_demo_image( $label, $color_index = 0, $width = 1200, $height = 1200, $meta = '_starter_demo' ) {
	if ( ! function_exists( 'imagecreatetruecolor' ) ) {
		return 0;
	}

	require_once ABSPATH . 'wp-admin/includes/image.php';

	$palette = array( array( 120, 160, 190 ), array( 196, 150, 110 ), array( 118, 170, 140 ), array( 192, 118, 118 ), array( 130, 130, 190 ), array( 190, 172, 96 ) );
	$rgb     = $palette[ $color_index % count( $palette ) ];
	$img     = imagecreatetruecolor( $width, $height );
	$bg      = imagecolorallocate( $img, $rgb[0], $rgb[1], $rgb[2] );
	$fg      = imagecolorallocate( $img, 255, 255, 255 );

	imagefilledrectangle( $img, 0, 0, $width, $height, $bg );
	imagefilledellipse( $img, (int) ( $width * 0.5 ), (int) ( $height * 0.46 ), (int) ( $width * 0.42 ), (int) ( $width * 0.42 ), imagecolorallocate( $img, min( 255, $rgb[0] + 55 ), min( 255, $rgb[1] + 55 ), min( 255, $rgb[2] + 55 ) ) );
	imagestring( $img, 5, (int) ( $width / 2 - strlen( $label ) * 4.5 ), (int) ( $height * 0.82 ), $label, $fg );

	ob_start();
	imagepng( $img );
	$png = ob_get_clean();
	imagedestroy( $img );

	$upload = wp_upload_bits( 'demo-' . sanitize_title( $label ) . '-' . wp_generate_password( 4, false ) . '.png', null, $png );

	if ( ! empty( $upload['error'] ) ) {
		return 0;
	}

	$attachment_id = wp_insert_attachment(
		array(
			'post_mime_type' => 'image/png',
			'post_title'     => $label,
			'post_status'    => 'inherit',
		),
		$upload['file']
	);

	if ( ! $attachment_id || is_wp_error( $attachment_id ) ) {
		return 0;
	}

	wp_update_attachment_metadata( $attachment_id, wp_generate_attachment_metadata( $attachment_id, $upload['file'] ) );
	update_post_meta( $attachment_id, $meta, 1 );
	update_post_meta( $attachment_id, '_wp_attachment_image_alt', $label );

	return (int) $attachment_id;
}

/* ------------------------------------------------------------------- Crea */
function starter_demo_create() {
	$lines = array();

	// 1) Pagine di base.
	$privacy_id = starter_demo_page( 'privacy-policy', __( 'Privacy Policy', 'slug-theme' ), starter_demo_privacy_content(), false );
	$cookie_id  = starter_demo_page( 'cookie-policy', __( 'Cookie Policy', 'slug-theme' ), starter_demo_cookie_content(), false );

	if ( $privacy_id && ! (int) get_option( 'wp_page_for_privacy_policy' ) ) {
		update_option( 'wp_page_for_privacy_policy', $privacy_id );
	}

	$blog_id = starter_demo_page_by_slug( 'blog' ) ?: starter_demo_page( 'blog', __( 'Blog', 'slug-theme' ), '', false );
	update_option( 'page_for_posts', $blog_id );

	if ( starter_has_woocommerce() ) {
		starter_wishlist_page_create();
	}

	$lines[] = __( 'pagine di base', 'slug-theme' );

	// Pagine standard (Home, Chi siamo, Servizi, Contatti) e modulo newsletter del footer.
	starter_pages_create();
	starter_newsletter_form_create();
	$lines[] = __( 'pagine standard e modulo newsletter', 'slug-theme' );

	// 2) Articoli.
	$posts = starter_demo_posts();
	/* translators: %d: numero di articoli. */
	$lines[] = sprintf( __( '%d articoli', 'slug-theme' ), $posts );

	// Moduli accesi (Opzioni → Moduli): ognuno porta i suoi contenuti di prova.
	foreach ( starter_modules_active() as $starter_module ) {
		if ( ! empty( $starter_module['demo'] ) && is_callable( $starter_module['demo'] ) ) {
			/* translators: 1: nome del modulo, 2: numero di elementi creati. */
			$lines[] = sprintf( __( '%1$s: %2$d elementi', 'slug-theme' ), $starter_module['label'], (int) call_user_func( $starter_module['demo'] ) );
		}
	}

	// 3) Woo.
	$products = 0;

	if ( starter_has_woocommerce() ) {
		$products = starter_demo_products();
		starter_shop_setup_shipping();
		starter_shop_page_intro();
		starter_shop_setup_test();
		/* translators: %d: numero di prodotti. */
		$lines[] = sprintf( __( '%d prodotti', 'slug-theme' ), $products );
	}

	// 4) Menu.
	starter_demo_menu( $blog_id );
	$lines[] = __( 'menu Header con sottomenu', 'slug-theme' );

	/* translators: %s: elenco di ciò che è stato creato. */
	return sprintf( __( 'Creati: %s.', 'slug-theme' ), implode( ', ', $lines ) );
}

/* Testo di esempio della Privacy Policy */
function starter_demo_privacy_content() {
	$text  = '<!-- wp:paragraph --><p><strong>Bozza da completare e far verificare al titolare del trattamento.</strong> Sostituisci i testi tra [parentesi quadre] con i dati reali.</p><!-- /wp:paragraph -->';
	$text .= '<!-- wp:heading --><h2>Titolare del trattamento</h2><!-- /wp:heading -->';
	$text .= '<!-- wp:paragraph --><p>[Ragione sociale], con sede in [indirizzo], P.IVA [partita IVA], email [indirizzo email], è titolare del trattamento dei dati personali raccolti tramite questo sito.</p><!-- /wp:paragraph -->';
	$text .= '<!-- wp:heading --><h2>Dati raccolti e finalità</h2><!-- /wp:heading -->';
	$text .= '<!-- wp:paragraph --><p>Raccogliamo i dati che ci invii compilando i moduli di contatto (nome, email, telefono, messaggio), i dati necessari a gestire eventuali ordini e acquisti, e dati di navigazione e analitici nei limiti del consenso espresso tramite il banner dei cookie.</p><!-- /wp:paragraph -->';
	$text .= '<!-- wp:heading --><h2>Base giuridica e conservazione</h2><!-- /wp:heading -->';
	$text .= '<!-- wp:paragraph --><p>Il trattamento si basa sul tuo consenso, sull\'esecuzione di misure precontrattuali o di un contratto e, per gli obblighi di legge, sulla normativa vigente. I dati sono conservati per il tempo necessario alle finalità indicate: [indicare i tempi].</p><!-- /wp:paragraph -->';
	$text .= '<!-- wp:heading --><h2>I tuoi diritti</h2><!-- /wp:heading -->';
	$text .= '<!-- wp:paragraph --><p>Puoi chiedere accesso, rettifica, cancellazione, limitazione, portabilità e opposizione scrivendo a [indirizzo email]. Hai inoltre il diritto di proporre reclamo al Garante per la protezione dei dati personali.</p><!-- /wp:paragraph -->';
	$text .= '<!-- wp:heading --><h2>Cookie</h2><!-- /wp:heading -->';
	$text .= '<!-- wp:paragraph --><p>Per i dettagli sui cookie utilizzati consulta la <a href="/cookie-policy/">Cookie Policy</a>.</p><!-- /wp:paragraph -->';

	return $text;
}

/* Testo di esempio della Cookie Policy */
function starter_demo_cookie_content() {
	$text  = '<!-- wp:paragraph --><p><strong>Bozza da completare.</strong> La tabella qui sotto è generata da CookieYes (scansione del sito dal pannello del plugin).</p><!-- /wp:paragraph -->';
	$text .= '<!-- wp:heading --><h2>Cosa sono i cookie</h2><!-- /wp:heading -->';
	$text .= '<!-- wp:paragraph --><p>I cookie sono piccoli file di testo che i siti salvano sul tuo dispositivo per farli funzionare, ricordare le tue preferenze e, se acconsenti, misurare l\'uso del sito o mostrarti contenuti pertinenti.</p><!-- /wp:paragraph -->';
	$text .= '<!-- wp:heading --><h2>Categorie di cookie</h2><!-- /wp:heading -->';
	$text .= '<!-- wp:paragraph --><p><strong>Necessari</strong>: indispensabili al funzionamento del sito, sempre attivi. <strong>Analitici</strong>, <strong>funzionali</strong> e <strong>di marketing</strong>: si attivano solo dopo il tuo consenso.</p><!-- /wp:paragraph -->';
	$text .= '<!-- wp:heading --><h2>Elenco dei cookie</h2><!-- /wp:heading -->';
	$text .= "<!-- wp:shortcode -->\n[cookie_audit]\n<!-- /wp:shortcode -->";
	$text .= '<!-- wp:heading --><h2>Come gestire le tue preferenze</h2><!-- /wp:heading -->';
	$text .= '<!-- wp:paragraph --><p>Puoi modificare il consenso in qualsiasi momento dal pulsante di gestione dei cookie del sito, oppure dalle impostazioni del tuo browser.</p><!-- /wp:paragraph -->';

	return $text;
}

/*
 * Scrive un blocco nel contenuto di una pagina, con testi e immagini segnaposto.
 * $data: nome campo => valore; $keys: nome campo => chiave ACF (serve a ACF per leggere i valori dal blocco).
 */
function starter_demo_block( $name, $data, $keys ) {
	$payload = array(
		'name' => 'starter-theme/' . $name,
		'data' => array(),
		'mode' => 'preview',
	);

	foreach ( $data as $field => $value ) {
		// Repeater: $keys[campo] = array( chiave del repeater, array( sottocampo => chiave ) ); ACF vuole i valori "appiattiti" (campo_0_sottocampo).
		if ( is_array( $keys[ $field ] ) ) {
			$payload['data'][ $field ]        = count( $value );
			$payload['data'][ '_' . $field ] = $keys[ $field ][0];

			foreach ( array_values( $value ) as $i => $row ) {
				foreach ( $row as $sub => $sub_value ) {
					$payload['data'][ "{$field}_{$i}_{$sub}" ]        = $sub_value;
					$payload['data'][ "_{$field}_{$i}_{$sub}" ] = $keys[ $field ][1][ $sub ];
				}
			}

			continue;
		}

		$payload['data'][ $field ]        = $value;
		$payload['data'][ '_' . $field ] = $keys[ $field ];
	}

	return '<!-- wp:starter-theme/' . $name . ' ' . wp_json_encode( $payload ) . " /-->\n\n";
}

/* Articoli di prova: ritorna il numero creato. */
function starter_demo_posts() {
	$existing = get_posts(
		array(
			'post_type'      => 'post',
			'post_status'    => 'any',
			'meta_key'       => '_starter_demo', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);

	if ( $existing ) {
		return 0;
	}

	$items = array(
		array( 'Come scegliere il prodotto giusto: una guida in 5 passi', 'Guide', array( 'guida', 'consigli' ) ),
		array( 'Le novità della stagione: cosa è arrivato in negozio', 'Novità', array( 'novità', 'stagione' ) ),
		array( 'Dietro le quinte: come lavoriamo ogni giorno', 'Novità', array( 'azienda' ) ),
		array( 'Cura e manutenzione: i consigli per far durare di più i tuoi acquisti', 'Guide', array( 'consigli', 'cura' ) ),
		array( 'Evento in negozio: ti aspettiamo sabato', 'Eventi', array( 'evento' ) ),
		array( 'Domande frequenti su spedizioni e resi', 'Guide', array( 'spedizioni', 'guida' ) ),
		array( 'Idee regalo per ogni occasione', 'Novità', array( 'regali', 'stagione' ) ),
	);

	$body  = '<!-- wp:paragraph --><p>Questo è un articolo di prova: serve a verificare la griglia del blog, le categorie, i tag, la ricerca e l\'impaginazione dell\'articolo. Sostituiscilo con contenuti veri.</p><!-- /wp:paragraph -->';
	$body .= '<!-- wp:heading --><h2>Un sottotitolo di esempio</h2><!-- /wp:heading -->';
	$body .= '<!-- wp:paragraph --><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio praesent libero, sed cursus ante dapibus diam. Sed nisi nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum, praesent mauris fusce nec tellus sed augue semper porta.</p><!-- /wp:paragraph -->';
	$body .= '<!-- wp:list --><ul><li>Primo punto dell\'elenco</li><li>Secondo punto dell\'elenco</li><li>Terzo punto dell\'elenco</li></ul><!-- /wp:list -->';
	$body .= '<!-- wp:heading {"level":3} --><h3>Un altro paragrafo</h3><!-- /wp:heading -->';
	$body .= '<!-- wp:paragraph --><p>Mauris massa. Vestibulum lacinia arcu eget nulla. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Curabitur sodales ligula in libero.</p><!-- /wp:paragraph -->';

	$author = get_current_user_id() ?: 1;

	if ( ! trim( (string) get_the_author_meta( 'description', $author ) ) ) {
		wp_update_user(
			array(
				'ID'          => $author,
				'description' => 'Biografia di prova dell\'autore: due righe che raccontano chi scrive e di cosa si occupa. Si modifica da Utenti → Profilo.',
			)
		);
	}

	$count = 0;

	foreach ( $items as $i => $item ) {
		$cat = term_exists( $item[1], 'category' );
		if ( $cat ) {
			$cat = (int) $cat['term_id'];
		} else {
			$cat = (int) wp_insert_term( $item[1], 'category' )['term_id'];
			add_term_meta( $cat, '_starter_demo', 1, true );
		}
		$id  = wp_insert_post(
			array(
				'post_type'     => 'post',
				'post_status'   => 'publish',
				'post_title'    => $item[0],
				'post_content'  => $body,
				'post_excerpt'  => 'Un breve riassunto di prova dell\'articolo, utile per vedere come si comporta la card nell\'elenco.',
				'post_author'   => $author,
				'post_category' => array( $cat ),
				'tags_input'    => $item[2],
				'post_date'     => gmdate( 'Y-m-d H:i:s', strtotime( '-' . ( $i * 9 + 2 ) . ' days' ) ),
			)
		);

		if ( $id && ! is_wp_error( $id ) ) {
			update_post_meta( $id, '_starter_demo', 1 );
			$image = starter_demo_image( 'Articolo ' . ( $i + 1 ), $i, 1600, 1000 );

			if ( $image ) {
				set_post_thumbnail( $id, $image );
			}

			++$count;
		}
	}

	return $count;
}

/* Prodotti di prova con categorie e attributi: ritorna il numero creato. */
function starter_demo_products() {
	// Pagine di WooCommerce (shop, carrello, cassa, account) se mancano.
	if ( ! wc_get_page_id( 'shop' ) || wc_get_page_id( 'shop' ) < 1 ) {
		WC_Install::create_pages();
	}

	$existing = get_posts(
		array(
			'post_type'      => 'product',
			'post_status'    => 'any',
			'meta_key'       => '_starter_demo', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);

	if ( $existing ) {
		return 0;
	}

	// Categorie (con una sottocategoria) e attributi globali.
	$term = function ( $name, $parent = 0 ) {
		$found = term_exists( $name, 'product_cat', $parent );

		if ( $found ) {
			return (int) $found['term_id'];
		}

		$new = wp_insert_term( $name, 'product_cat', array( 'parent' => $parent ) );

		if ( ! is_wp_error( $new ) ) {
			add_term_meta( $new['term_id'], '_starter_demo', 1, true );

			return (int) $new['term_id'];
		}

		return 0;
	};

	$casa      = $term( 'Casa' );
	$luci      = $term( 'Illuminazione', $casa );
	$tessili   = $term( 'Tessili', $casa );
	$accessori = $term( 'Accessori' );
	$ufficio   = $term( 'Ufficio' );

	$attributes = array(
		'colore'    => array( 'Colore', array( 'Bianco', 'Nero', 'Verde', 'Blu', 'Terracotta' ) ),
		'materiale' => array( 'Materiale', array( 'Cotone', 'Ceramica', 'Vetro', 'Lana', 'Cuoio' ) ),
	);

	foreach ( $attributes as $slug => $attr ) {
		if ( ! wc_attribute_taxonomy_id_by_name( $slug ) ) {
			wc_create_attribute(
				array(
					'name'         => $attr[0],
					'slug'         => $slug,
					'type'         => 'select',
					'order_by'     => 'menu_order',
					'has_archives' => false,
				)
			);
		}

		$taxonomy = wc_attribute_taxonomy_name( $slug );

		if ( ! taxonomy_exists( $taxonomy ) ) {
			register_taxonomy( $taxonomy, 'product', array( 'hierarchical' => false ) );
		}

		foreach ( $attr[1] as $value ) {
			if ( ! term_exists( $value, $taxonomy ) ) {
				wp_insert_term( $value, $taxonomy );
			}
		}
	}

	// Nome, prezzo, offerta, categorie, colore, materiale, foto nella galleria, esaurito.
	$items = array(
		array( 'Lampada da tavolo', 89, 69, array( $luci ), 'Nero', 'Vetro', 2, false ),
		array( 'Tazza in ceramica', 14, 0, array( $casa ), 'Bianco', 'Ceramica', 0, false ),
		array( 'Borsa in tela', 39, 0, array( $accessori ), 'Verde', 'Cotone', 2, false ),
		array( 'Cuscino in lino', 29, 0, array( $tessili ), 'Terracotta', 'Cotone', 0, false ),
		array( 'Candela profumata', 18, 0, array( $casa ), 'Bianco', 'Vetro', 0, false ),
		array( 'Quaderno rilegato', 12, 0, array( $ufficio ), 'Blu', 'Cuoio', 0, false ),
		array( 'Plaid in lana', 79, 0, array( $tessili ), 'Verde', 'Lana', 0, true ),
		array( 'Vaso in vetro', 34, 0, array( $casa ), 'Blu', 'Vetro', 0, false ),
		array( 'Portachiavi in cuoio', 9, 0, array( $accessori ), 'Nero', 'Cuoio', 0, false ),
		array( 'Orologio da parete', 54, 0, array( $casa ), 'Nero', 'Vetro', 0, false ),
		array( 'Set di piatti', 59, 0, array( $casa ), 'Bianco', 'Ceramica', 0, false ),
		array( 'Tappeto in cotone', 119, 0, array( $tessili ), 'Terracotta', 'Cotone', 0, false ),
	);

	$count = 0;

	foreach ( $items as $i => $item ) {
		$product = new WC_Product_Simple();
		$product->set_name( $item[0] );
		$product->set_status( 'publish' );
		$product->set_catalog_visibility( 'visible' );
		$product->set_regular_price( (string) $item[1] );

		if ( $item[2] ) {
			$product->set_sale_price( (string) $item[2] );
		}

		$product->set_short_description( 'Breve descrizione di prova del prodotto: due righe per vedere come si comporta la card.' );
		$product->set_description( 'Descrizione estesa di prova. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio praesent libero, sed cursus ante dapibus diam. Sed nisi nulla quis sem at nibh elementum imperdiet.' );
		$product->set_category_ids( array_filter( $item[3] ) );
		$product->set_manage_stock( false );
		$product->set_stock_status( $item[7] ? 'outofstock' : 'instock' );

		$attrs = array();

		foreach ( array( 'colore' => $item[4], 'materiale' => $item[5] ) as $slug => $value ) {
			$taxonomy = wc_attribute_taxonomy_name( $slug );
			$term_obj = get_term_by( 'name', $value, $taxonomy );
			$attr     = new WC_Product_Attribute();
			$attr->set_id( wc_attribute_taxonomy_id_by_name( $slug ) );
			$attr->set_name( $taxonomy );
			$attr->set_options( $term_obj ? array( (int) $term_obj->term_id ) : array() );
			$attr->set_visible( true );
			$attr->set_variation( false );
			$attrs[] = $attr;
		}

		$product->set_attributes( $attrs );

		$main = starter_demo_image( $item[0], $i );

		if ( $main ) {
			$product->set_image_id( $main );
		}

		// Ogni prodotto ha almeno una seconda foto: all'hover la card cambia immagine.
		if ( max( 1, $item[6] ) ) {
			$gallery = array();

			for ( $g = 1; $g <= max( 1, $item[6] ); $g++ ) {
				$gallery[] = starter_demo_image( $item[0] . ' ' . ( $g + 1 ), $i + $g );
			}

			$product->set_gallery_image_ids( array_filter( $gallery ) );
		}

		$id = $product->save();

		if ( $id ) {
			update_post_meta( $id, '_starter_demo', 1 );
			++$count;
		}
	}

	return $count;
}

/* Menu "Header di prova": Home, Chi siamo, Servizi, Shop (con sottomenu), Blog (voce singola), Contatti; assegnato a Header e Footer. */
function starter_demo_menu( $blog_id ) {
	$existing = wp_get_nav_menu_object( 'Header di prova' );

	if ( $existing ) {
		return;
	}

	$menu_id = wp_create_nav_menu( 'Header di prova' );

	if ( is_wp_error( $menu_id ) ) {
		return;
	}

	update_term_meta( $menu_id, '_starter_demo', 1 );

	$add = function ( $title, $url, $parent = 0, $object_id = 0 ) use ( $menu_id ) {
		$args = array(
			'menu-item-title'     => $title,
			'menu-item-status'    => 'publish',
			'menu-item-parent-id' => $parent,
		);

		if ( $object_id ) {
			$args['menu-item-object']    = 'page';
			$args['menu-item-object-id'] = $object_id;
			$args['menu-item-type']      = 'post_type';
		} else {
			$args['menu-item-url']  = $url;
			$args['menu-item-type'] = 'custom';
		}

		return (int) wp_update_nav_menu_item( $menu_id, 0, $args );
	};

	foreach ( array( 'home' => 'Home', 'chi-siamo' => 'Chi siamo', 'servizi' => 'Servizi' ) as $slug => $title ) {
		$page = starter_demo_page_by_slug( $slug );
		$add( $title, home_url( '/' . ( 'home' === $slug ? '' : $slug . '/' ) ), 0, $page );
	}

	if ( starter_has_woocommerce() ) {
		$shop = $add( __( 'Shop', 'slug-theme' ), wc_get_page_permalink( 'shop' ) );

		foreach ( array( 'Casa', 'Accessori', 'Ufficio' ) as $name ) {
			$term = get_term_by( 'name', $name, 'product_cat' );

			if ( $term ) {
				$add( $name, get_term_link( $term ), $shop );
			}
		}

		$add( __( 'Tutti i prodotti', 'slug-theme' ), wc_get_page_permalink( 'shop' ), $shop );
	}

	foreach ( array( 'portfolio' => 'starter_portfolio_page_id', 'immobili' => 'starter_immobili_page_id' ) as $starter_module_id => $starter_page_fn ) {
		if ( starter_module_active( $starter_module_id ) && function_exists( $starter_page_fn ) && call_user_func( $starter_page_fn ) ) {
			$add( get_the_title( call_user_func( $starter_page_fn ) ), get_permalink( call_user_func( $starter_page_fn ) ), 0, call_user_func( $starter_page_fn ) );
		}
	}

	// Blog: voce singola, senza sottomenu.
	$add( __( 'Blog', 'slug-theme' ), '', 0, $blog_id );
	$add( __( 'Contatti', 'slug-theme' ), home_url( '/contatti/' ), 0, starter_demo_page_by_slug( 'contatti' ) );

	$locations                   = get_theme_mod( 'nav_menu_locations', array() );
	$locations['header']         = $menu_id;
	$locations['footer-link']    = $menu_id;
	set_theme_mod( 'nav_menu_locations', $locations );
}

/* ----------------------------------------------------------------- Elimina */
function starter_demo_delete() {
	$count = 0;

	$posts = get_posts(
		array(
			'post_type'      => array( 'post', 'page', 'product', 'portfolio', 'immobile', 'attachment' ),
			'post_status'    => 'any',
			'meta_key'       => '_starter_demo', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'posts_per_page' => -1,
			'fields'         => 'ids',
		)
	);

	foreach ( $posts as $id ) {
		if ( wp_delete_post( $id, true ) ) {
			++$count;
		}
	}

	$menu = wp_get_nav_menu_object( 'Header di prova' );

	if ( $menu ) {
		wp_delete_nav_menu( $menu->term_id );
		++$count;
	}

	foreach ( array( 'product_cat', 'category', 'portfolio_category' ) as $taxonomy ) {
		foreach ( (array) get_terms( array( 'taxonomy' => $taxonomy, 'hide_empty' => false, 'meta_key' => '_starter_demo' ) ) as $term ) { // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			if ( $term instanceof WP_Term && wp_delete_term( $term->term_id, $taxonomy ) ) {
				++$count;
			}
		}
	}

	return $count;
}
