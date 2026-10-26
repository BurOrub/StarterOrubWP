<?php
/**
 * Pulsanti "Condividi" di un articolo: Facebook, X, LinkedIn e WhatsApp. Solo link di condivisione
 * (nessun plugin, nessuno script di terze parti). Si attivano da Opzioni → Blog.
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$starter_url    = rawurlencode( get_permalink() );
$starter_title  = rawurlencode( html_entity_decode( get_the_title(), ENT_QUOTES, 'UTF-8' ) );
$starter_shares = array(
	'Facebook' => 'https://www.facebook.com/sharer/sharer.php?u=' . $starter_url,
	'X'        => 'https://twitter.com/intent/tweet?url=' . $starter_url . '&text=' . $starter_title,
	'LinkedIn' => 'https://www.linkedin.com/sharing/share-offsite/?url=' . $starter_url,
	'WhatsApp' => 'https://wa.me/?text=' . $starter_title . '%20' . $starter_url,
);
?>
<div class="share">
	<p class="share__label eyebrow"><?php esc_html_e( 'Condividi', 'slug-theme' ); ?></p>
	<ul class="share__list">
		<?php foreach ( $starter_shares as $starter_label => $starter_href ) : ?>
			<li>
				<a class="button button--outline share__link" href="<?php echo esc_url( $starter_href ); ?>" target="_blank" rel="noopener noreferrer">
					<?php echo starter_flip_text( $starter_label ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
