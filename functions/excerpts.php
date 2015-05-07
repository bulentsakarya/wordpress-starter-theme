<?php
/**
 * Custom excerpts based on wp_trim_words
 * Created for child-theming purposes
 * 
 * Learn more at http://codex.wordpress.org/Function_Reference/wp_trim_words
 *
 * @since 1.0
 *
 * @package WordPress
 * @subpackage CoreOne
 * @since CoreOne 1.0 
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( !function_exists( 'bs_excerpt' ) ) :
	function bs_excerpt($length=30, $readmore=false ) {
		global $post;
		$id = $post->ID;
		$meta_excerpt = get_post_meta( $id, 'bs_excerpt_length', true );
		$length = $meta_excerpt ? $meta_excerpt : $length;	
		if ( has_excerpt( $id ) ) {
			$output = $post->post_excerpt;
		} else {
			$output = wp_trim_words( strip_shortcodes( get_the_content( $id ) ), $length);
			if ( $readmore == true ) {
				$readmore_link = '<a href="'. get_permalink( $id ) .'" title="" rel="bookmark" class="readmore">Devamı &rarr;</a>';
				$output .= apply_filters( 'bs_readmore_link', $readmore_link );
			}
		}
		echo $output;
	}
endif;



/**
* Change default read more style
* @since 1.0
*/
add_filter('excerpt_more', 'bs_excerpt_more');
if ( !function_exists( 'bs_excerpt_more' ) ) :
	function bs_excerpt_more($more) {
		global $post;
		return '...';
	}
endif;



/**
* Change default excerpt length
* @since 1.0
*/
add_filter( 'excerpt_length', 'bs_custom_excerpt_length', 999 );
if ( !function_exists( 'bs_custom_excerpt_length' ) ) :
	function bs_custom_excerpt_length( $length ) {
		return bs_get_data('excerpt_length','40');
	}
endif;