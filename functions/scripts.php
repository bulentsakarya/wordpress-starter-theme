<?php
/**
 * This file loads custom css and js for our theme
 *
 * @package WordPress
 * @subpackage CoreOne
 * @since CoreOne 1.0
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action('wp_enqueue_scripts','bs_load_scripts');
function bs_load_scripts() {
	
	/***** CSS *****/
	wp_enqueue_style( 'bootstrap', MAIN_CSS_DIR. '/bootstrap.min.css', 'style' );
	wp_enqueue_style( 'style', get_stylesheet_uri() );
	
	/***** jQuery *****/
	wp_enqueue_script( 'jqueryjs', MAIN_JS_DIR. '/jquery.min.js', '', '', false );
	wp_enqueue_script( 'bootstrapjs', MAIN_JS_DIR. '/bootstrap.min.js', '', '', false );
	wp_enqueue_script( 'site', MAIN_JS_DIR. '/site.js', '', '', false );
	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script('comment-reply');
	}
}


/**
* Browser Specific CSS
* @Since 1.0
*/
add_action('wp_head', 'bs_browser_dependencies');
if ( !function_exists('bs_browser_dependencies') ) {
	function bs_browser_dependencies() {
		
		echo '<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script src="'. MAIN_JS_DIR .'/html5shiv.js"></script>
			<script src="'. MAIN_JS_DIR .'/respond.min.js"></script>
		<![endif]-->';
	}
}