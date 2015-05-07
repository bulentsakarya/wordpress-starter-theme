<?php
/**
 * Registers the Albümler Custom Post Type
 *
 * @package WordPress
 * @subpackage CoreOne
 * @since CoreOne 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'BS_Albumler_Post_Type' ) ) {

	class BS_Albumler_Post_Type {

		function __construct() {

			// Adds the portfolio post type and taxonomies
			add_action( 'init', array( &$this, 'albumler_init' ), 0 );

			// Adds columns in the admin view for thumbnail and taxonomies
			add_filter( 'manage_edit-albumler_columns', array( &$this, 'albumler_edit_columns' ) );
			add_action( 'manage_posts_custom_column', array( &$this, 'albumler_column_display' ), 10, 2 );

		}
		

		function albumler_init() {

			/**
			 * Enable the Portfolio custom post type
			 * http://codex.wordpress.org/Function_Reference/register_post_type
			 */

			$labels = array(
				'name'					=> 'Albümler',
				'singular_name'			=> 'Albüm',
				'add_new'				=> 'Albüm Ekle',
				'add_new_item'			=> 'Yeni Albüm',
				'edit_item'				=> 'Bilgileri Düzenle',
				'new_item'				=> 'Yeni Albüm',
				'view_item'				=> 'Görüntüle',
				'search_items'			=> 'albumlerde Ara',
				'not_found'				=> 'Sonuç Bulunamadı',
				'not_found_in_trash'	=> 'Silinmiş Öğe Bulunmamaktadır'
			);
			
			$args = array(
				'labels'			=> $labels,
				'public'			=> true,
				'supports'			=> array( 'title', 'editor', 'thumbnail'),
				'capability_type'	=> 'post',
				'rewrite'			=> array("slug" => "albumler"), // Permalinks format
				'has_archive'		=> true,
				'menu_icon'			=> 'dashicons-format-gallery',
			); 
			
			$args = apply_filters( 'bs_albumler_args', $args);
			
			register_post_type( 'albums', $args );

		}

		/**
		 * Add Columns to Portfolio Edit Screen
		 * http://wptheming.com/2010/07/column-edit-pages/
		 */

		function albumler_edit_columns( $columns ) {
			$columns['albumler_thumbnail'] = 'Resim';
			$columns['title'] = 'Albüm Adı';
			unset($columns['date']);
			return $columns;
		}

		function albumler_column_display( $albumler_columns, $post_id ) {

			// Code from: http://wpengineer.com/display-post-thumbnail-post-page-overview

			switch ( $albumler_columns ) {

				// Display the thumbnail in the column view
				case "albumler_thumbnail":
					$width = (int) 80;
					$height = (int) 80;
					$thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );

					// Display the featured image in the column view if possible
					if ( $thumbnail_id ) {
						$thumb = wp_get_attachment_image( $thumbnail_id, array($width, $height), true );
					}
					if ( isset( $thumb ) ) {
						echo $thumb;
					} else {
						echo __( 'None', 'wpex' );
					}
					break;	
		
			}
		}

	}

	new BS_Albumler_Post_Type;

}