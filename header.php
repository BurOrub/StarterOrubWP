<?php
/**
 * Header del tema.
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#site-content"><?php esc_html_e( 'Vai al contenuto', 'slug-theme' ); ?></a>

<?php
// Icone dell'header: la ricerca funziona sempre; account, preferiti e carrello solo con WooCommerce attivo. Ognuna si attiva da Opzioni → Header → Icone.
$starter_shop_urls    = starter_shop_urls();
$starter_show_account = starter_header_shop_enabled( 'account' ) && $starter_shop_urls['account'];
$starter_show_cart    = starter_header_shop_enabled( 'cart' ) && $starter_shop_urls['cart'];
$starter_wishlist_url = starter_header_shop_enabled( 'wishlist' ) ? starter_wishlist_url() : '';
$starter_show_search  = starter_header_search_enabled();
$starter_has_icons    = $starter_show_search || $starter_show_account || $starter_wishlist_url || $starter_show_cart;
?>
<header class="site-header" id="site-header">
	<div class="site-header__inner">

		<div class="site-branding">
			<?php if ( has_custom_logo() ) : ?>
				<div class="custom-logo-wrap<?php echo starter_has_dark_logo() ? ' has-dark-logo' : ''; ?>">
					<?php the_custom_logo(); ?>

					<?php if ( starter_has_dark_logo() ) : ?>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="custom-logo-link custom-logo-link--dark" rel="home">
							<img
								src="<?php echo esc_url( get_theme_mod( 'starter_dark_logo' ) ); ?>"
								class="custom-logo custom-logo--dark"
								alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
							>
						</a>
					<?php endif; ?>

					<?php if ( 'icon' === starter_header_scroll_logo() ) : ?>
						<?php // Icona che sostituisce il logo dopo lo scroll (Opzioni → Header → Logo scrollando). ?>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="custom-logo-link custom-logo-link--icon" rel="home" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
							<?php echo starter_picturefy( '<img src="' . esc_url( get_theme_mod( 'starter_header_logo_icon' ) ) . '" class="custom-logo-icon" alt="">' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</a>
					<?php endif; ?>
				</div>
			<?php else : ?>
				<p class="site-title">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<?php bloginfo( 'name' ); ?>
					</a>
				</p>
			<?php endif; ?>
		</div>

		<?php
		// Icone social, configurabili da Personalizza -> Opzioni ->
		// Generale -> Social (condivise col footer, che ha il suo
		// checkbox separato). "Mostra i social" qui sotto le
		// attiva/disattiva solo per l'header. Calcolate qui perché
		// servono in due punti: la lista sempre visibile più sotto (fuori
		// dal pannello mobile) e la copia dentro il pannello stesso, che
		// la sostituisce quando la prima non ci starebbe più (vedi
		// ".social-links--panel" in CSS/starter.css).
		// Con l'header "Pillole" non ci sono i social: solo le icone dello shop (ricerca, account, preferiti, carrello).
		$starter_social_links = get_theme_mod( 'starter_social_show_header', true ) && ! starter_is_pills_header() ? starter_get_social_links() : array();
		?>

		<div class="site-header__nav-wrap" id="site-header-nav-wrap">

			<nav class="main-navigation main-navigation--desktop" id="main-navigation" aria-label="<?php esc_attr_e( 'Menu principale', 'slug-theme' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'header',
						'container'      => false,
						'fallback_cb'    => false,
					)
				);
				?>
			</nav>

			<?php if ( has_nav_menu( 'mobile' ) ) : ?>
				<?php
				/**
				 * Menu ad hoc solo per il pannello mobile/Ridotto (Aspetto ->
				 * Menu -> posizione "Mobile"), alternativo a quello desktop
				 * qui sopra. Stampato solo se è stato assegnato un menu a
				 * questa posizione; altrimenti il pannello mobile continua a
				 * mostrare lo stesso menu "Header" di sempre (vedi
				 * ".main-navigation--mobile" in CSS/starter.css, che scambia i
				 * due <nav> solo quando "body.has-mobile-menu" è presente).
				 */
				?>
				<nav class="main-navigation main-navigation--mobile" id="main-navigation-mobile" aria-label="<?php esc_attr_e( 'Menu principale (mobile)', 'slug-theme' ); ?>">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'mobile',
							'container'      => false,
							'fallback_cb'    => false,
						)
					);
					?>
				</nav>
			<?php endif; ?>

			<?php if ( $starter_show_account || $starter_wishlist_url ) : ?>
				<div class="site-header__panel-links">
					<?php if ( $starter_show_account ) : ?>
						<a class="nav-extra-link" href="<?php echo esc_url( $starter_shop_urls['account'] ); ?>"><?php echo starter_icon( 'user', 20, 1.5 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Il mio account', 'slug-theme' ); ?></a>
					<?php endif; ?>
					<?php if ( $starter_wishlist_url ) : ?>
						<a class="nav-extra-link" href="<?php echo esc_url( $starter_wishlist_url ); ?>"><?php echo starter_icon( 'heart', 20, 1.5 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Preferiti', 'slug-theme' ); ?></a>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php $starter_cta_text = starter_wpml_translate_string( 'cta_text', (string) get_theme_mod( 'starter_cta_text', __( 'Contattami', 'slug-theme' ) ) ); ?>
			<?php if ( $starter_cta_text || $starter_social_links ) : ?>
				<div class="site-header__actions">
					<?php if ( $starter_cta_text ) : ?>
						<a
							href="<?php echo esc_url( apply_filters( 'wpml_permalink', get_theme_mod( 'starter_cta_url', '#' ) ) ); // Link interno: va alla pagina nella lingua corrente (con WPML). ?>"
							class="button button--cta"
							<?php echo get_theme_mod( 'starter_cta_new_tab', false ) ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>
						>
							<span class="button__text-wrap">
								<span class="button__text"><?php echo esc_html( $starter_cta_text ); ?></span>
								<span class="button__text button__text--clone" aria-hidden="true"><?php echo esc_html( $starter_cta_text ); ?></span>
							</span>
						</a>
					<?php endif; ?>

					<?php if ( $starter_social_links ) : ?>
						<?php
						/**
						 * Copia delle icone social dentro il pannello mobile,
						 * affiancata alla CTA se c'è spazio (altrimenti va a
						 * capo sotto di lei — vedi ".site-header__actions" in
						 * CSS/starter.css, "flex-wrap"), al posto della lista
						 * qui sotto quando quella non ci sta più (vedi
						 * ".social-links--panel" nello stesso file).
						 */
						?>
						<ul class="social-links social-links--panel">
							<?php starter_render_social_links_items( $starter_social_links ); ?>
						</ul>
					<?php endif; ?>
				</div>
			<?php endif; ?>

		</div>

		<?php $starter_lang_html = starter_header_lang_html(); ?>
		<?php if ( $starter_social_links || $starter_has_icons || $starter_lang_html ) : ?>
			<?php
			/**
			 * Social e icone, in due gruppi: l'ordine (social prima o icone prima) e il divisore si scelgono in
			 * Opzioni → Header. Con il menu ridotto (mobile o versione "Ridotto") i social non stanno nella barra: li
			 * trovi solo dentro il pannello a tendina (la copia in ".social-links--panel").
			 */
			?>
			<div class="site-header__tools">
				<?php if ( $starter_social_links ) : ?>
					<ul class="social-links">
						<?php starter_render_social_links_items( $starter_social_links ); ?>
					</ul>
				<?php endif; ?>

				<?php if ( $starter_social_links && $starter_has_icons ) : ?>
					<span class="header-divider" aria-hidden="true"></span>
				<?php endif; ?>

				<?php if ( $starter_has_icons ) : ?>
					<?php // Icone con tooltip (Opzioni → Header). Ricerca, account, preferiti e carrello restano nella barra anche su mobile. ?>
					<div class="site-header__icons">
						<?php if ( $starter_show_search ) : ?>
							<?php
							echo starter_header_icon_btn( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								array(
									'icon'  => 'search',
									'label' => __( 'Cerca nel sito', 'slug-theme' ),
									'attrs' => 'data-search-open aria-controls="search-panel" aria-expanded="false"',
								)
							);
							?>
						<?php endif; ?>

						<?php if ( $starter_show_account ) : ?>
							<div class="header-tip header-tip--account">
								<?php
								echo starter_header_icon_btn( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									array(
										'icon'  => 'user',
										'href'  => $starter_shop_urls['account'],
										'label' => __( 'Il mio account', 'slug-theme' ),
									)
								);
								?>
								<div class="header-tip__panel header-tip__panel--label" role="tooltip">
									<p class="header-tip__empty"><?php echo esc_html( is_user_logged_in() ? __( 'Il mio account', 'slug-theme' ) : __( 'Accedi o registrati', 'slug-theme' ) ); ?></p>
								</div>
							</div>
						<?php endif; ?>

						<?php if ( $starter_wishlist_url ) : ?>
							<div class="header-tip header-tip--wish">
								<?php
								echo starter_header_icon_btn( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									array(
										'icon'  => 'heart',
										'href'  => $starter_wishlist_url,
										'label' => __( 'Preferiti', 'slug-theme' ),
										'after' => starter_wishlist_count_html(),
									)
								);
								?>
								<div class="header-tip__panel header-tip__panel--wish" data-wish-tip data-endpoint="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" aria-live="polite"></div>
							</div>
						<?php endif; ?>

						<?php if ( $starter_show_cart ) : ?>
							<div class="header-tip header-tip--cart">
								<?php
								echo starter_header_icon_btn( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									array(
										'icon'  => 'bag',
										'href'  => $starter_shop_urls['cart'],
										'class' => 'header-cart',
										'after' => '<span class="header-cart__label screen-reader-text">' . esc_html( starter_cart_label() ) . '</span>' . starter_cart_count_html(),
									)
								);
								?>
								<?php echo starter_cart_tip_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php if ( $starter_lang_html ) : ?>
					<?php // Lingua (WPML): sempre per ultima, dopo la sua linea di divisione. ?>
					<?php if ( $starter_social_links || $starter_has_icons ) : ?>
						<span class="header-divider header-divider--lang" aria-hidden="true"></span>
					<?php endif; ?>
					<?php echo $starter_lang_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<button type="button" class="mobile-menu-toggle" id="hamburger-toggle" aria-expanded="false" aria-controls="site-header-nav-wrap" aria-label="<?php esc_attr_e( 'Apri il menu', 'slug-theme' ); ?>">
			<span class="hamburger" aria-hidden="true"><i></i><i></i><i></i></span>
			<span class="mobile-menu-toggle__label">
				<span class="button__text-wrap">
					<span class="button__text"><?php esc_html_e( 'Menu', 'slug-theme' ); ?></span>
					<span class="button__text button__text--clone" aria-hidden="true"><?php esc_html_e( 'Menu', 'slug-theme' ); ?></span>
				</span>
			</span>
		</button>

	</div>
</header>

<?php starter_breadcrumb_bar(); ?>
