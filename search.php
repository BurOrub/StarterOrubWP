<?php
/**
 * Risultati di ricerca.
 * Struttura condivisa in template-parts/blog/list.php: intestazione col nome della sezione in cui siamo,
 * griglia di card, aside. Si regola da Opzioni → Blog.
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

get_template_part( 'template-parts/blog/list' );

get_footer();
