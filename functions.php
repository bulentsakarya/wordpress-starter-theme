<?php
/**
 * @package WordPress
 * @subpackage CoreOne
 * @since CoreOne 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*--------------------------------------*/
/* Define Constants
/*--------------------------------------*/
define( 'MAIN_JS_DIR', get_template_directory_uri().'/js' );
define( 'MAIN_CSS_DIR', get_template_directory_uri().'/css' );


/*--------------------------------------*/
/* Include functions
/*--------------------------------------*/
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
add_filter('show_admin_bar', '__return_false');
show_admin_bar(false);

require_once( get_template_directory() .'/functions/scripts.php' );
require_once( get_template_directory() .'/functions/breadcrumbs.php' );
require_once( get_template_directory() .'/functions/excerpts.php' );
require_once( get_template_directory() .'/functions/posts-per-page.php' );
require_once( get_template_directory() .'/functions/pagination.php');
require_once( get_template_directory() .'/functions/firm_options.php' );
require_once( get_template_directory() .'/functions/theme_options.php' );
require_once( get_template_directory() .'/functions/address_widget.php' );

/*--------------------------------------*/
/* Custom Post Types
/*--------------------------------------*/
global $theme_options;
$settings = get_option( 'theme_options', $theme_options );

if( $settings['products'] ) {
	require_once ( get_template_directory() .'/content/products.php' );
}

if( $settings['albums'] ) {
	require_once ( get_template_directory() .'/content/albums.php' );
}

if( $settings['products'] && $settings['category_images'] ) {
	require_once ( get_template_directory() .'/functions/category-images/categories-images.php' );
}

if( $settings['referencess'] ) {
	require_once ( get_template_directory() .'/content/referencess.php' );
}

if( $settings['products'] || $settings['albums'] ) {
	if( is_admin() ) {
		require_once( get_template_directory() .'/functions/gallery-metabox/gmb-admin.php' );
	} else {
		require_once( get_template_directory() .'/functions/gallery-metabox/gmb-display.php' );
	}
}


/*--------------------------------------*/
/* Menu Management
/*--------------------------------------*/
register_nav_menus( array(
	'primary_menu' => 'MainMenu',
));


/*--------------------------------------*/
/* Thumbnails and Image Support
/*--------------------------------------*/
add_theme_support('post-thumbnails');

//Responsive Image
function add_image_responsive_class($content) {
   global $post;
   $pattern ="/<img(.*?)class=\"(.*?)\"(.*?)>/i";
   $replacement = '<img$1class="$2 img-responsive marginbottom"$3>';
   $content = preg_replace($pattern, $replacement, $content);
   return $content;
}
add_filter('the_content', 'add_image_responsive_class');

//First Image
function catch_that_image() {
	global $post, $posts;
	$first_img = '';
	ob_start();
	ob_end_clean();
	$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
	$first_img = $matches[1][0];

	if(empty($first_img)) {
		$first_img = "";
	}
	return $first_img;
}

/*--------------------------------------*/
/* Sidebar
/*--------------------------------------*/
if ( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'name' => 'Sidebar',
		'id' => 'sidebar',
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '',
		'after_title' => '',
	));		
}