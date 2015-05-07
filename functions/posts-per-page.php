<?php
/**
 * This file filters the default WP pagination where needed
 *
 * @package WordPress
 * @subpackage CoreOne
 * @since CoreOne 1.0
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$bs_option_posts_per_page = get_option( 'posts_per_page' );
add_action( 'init', 'bs_modify_posts_per_page', 0);

if ( ! function_exists( 'bs_modify_posts_per_page' ) ) {
	function bs_modify_posts_per_page() {
		add_filter( 'option_posts_per_page', 'bs_option_posts_per_page' );
	}
}

if ( ! function_exists ( 'bs_option_posts_per_page' ) ) {
	function bs_option_posts_per_page( $value ) {
		global $bs_option_posts_per_page;
		if( is_tax('portfolio_category') || is_tax('portfolio_tag') || is_post_type_archive('portfolio') ) {
			return bs_get_data('portfolio_pagination','12');
		}
		if( is_search() ) {
			return bs_get_data('search_pagination','10');
		}
		else {
			return $bs_option_posts_per_page;
		}
	}
}