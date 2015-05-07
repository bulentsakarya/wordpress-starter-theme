<?php
/**
 * Registers the Ürünler Custom Post Type
 *
 * @package WordPress
 * @subpackage CoreOne
 * @since CoreOne 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'BS_Referanslar_Post_Type' ) ) {

	class BS_Referanslar_Post_Type {

		function __construct() {

			// Adds the portfolio post type and taxonomies
			add_action( 'init', array( &$this, 'referencess_init' ), 0 );

			// Adds columns in the admin view for thumbnail and taxonomies
			add_filter( 'manage_edit-referencess_columns', array( &$this, 'referencess_edit_columns' ) );
			add_action( 'manage_referencess_posts_custom_column', array( &$this, 'referencess_column_display' ), 10, 2 );
			add_filter( 'manage_edit-referencess_sortable_columns', array( &$this, 'referencess_sortable_columns'));

			// Allows filtering of posts by taxonomy in the admin view
			add_action( 'restrict_manage_posts', array( &$this, 'referencess_add_taxonomy_filters' ) );

		}
		

		function referencess_init() {

			/**
			 * Enable the Portfolio custom post type
			 * http://codex.wordpress.org/Function_Reference/register_post_type
			 */

			$labels = array(
				'name'					=> 'Referanslar',
				'singular_name'			=> 'Referans',
				'add_new'				=> 'Referans Ekle',
				'add_new_item'			=> 'Yeni Referans',
				'edit_item'				=> 'Bilgileri Düzenle',
				'new_item'				=> 'Yeni Referans',
				'view_item'				=> 'Görüntüle',
				'search_items'			=> 'Ürünlerde Ara',
				'not_found'				=> 'Sonuç Bulunamadı',
				'not_found_in_trash'	=> 'Silinmiş Öğe Bulunmamaktadır'
			);
			
			$args = array(
				'labels'			=> $labels,
				'public'			=> true,
				'supports'			=> array( 'title', 'editor', 'thumbnail'),
				'capability_type'	=> 'post',
				'rewrite'			=> array("slug" => "referanslar"), // Permalinks format
				'has_archive'		=> true,
				'menu_icon'			=> 'dashicons-portfolio',
			); 
			
			$args = apply_filters( 'bs_referencess_args', $args);
			
			register_post_type( 'referencess', $args );


			/**
			 * Register a taxonomy for Ürünler Categories
			 * http://codex.wordpress.org/Function_Reference/register_taxonomy
			 */

			$taxonomy_reference_categories_labels = array(
				'name'							=> 'Kategoriler',
				'singular_name'					=> 'Kategori',
				'search_items'					=> 'Kategori Ara',
				'popular_items'					=> 'Popüler Kategoriler',
				'all_items'						=> 'Tüm Kategoriler',
				'parent_item'					=> 'Ana Kategori',
				'parent_item_colon'				=> 'Ana Kategori',
				'edit_item'						=> 'Düzenle',
				'update_item'					=> 'Güncelle',
				'add_new_item'					=> 'Yeni Ekle',
				'new_item_name'					=> 'Yeni Kategori Ekle',
				'separate_items_with_commas'	=> 'Virgül kullanarak kategori giriniz',
				'add_or_remove_items'			=> 'Kategori Ekle veya Çıkar',
				'choose_from_most_used'			=> 'En çok kullanılanlardan seç',
				'menu_name'						=> 'Kategoriler',
			);

			$taxonomy_reference_categories_args = array(
				'labels'			=> $taxonomy_reference_categories_labels,
				'public'			=> true,
				'show_in_nav_menus'	=> true,
				'show_ui'			=> true,
				'show_tagcloud'		=> true,
				'hierarchical'		=> true,
				'rewrite'			=> array( 'slug' => 'referans-kategori' ),
				'query_var'			=> true
			);

			$taxonomy_reference_categories_args = apply_filters( 'bs_taxonomy_reference_categories_args', $taxonomy_reference_categories_args);
			
			register_taxonomy( 'reference_categories', array( 'referencess' ), $taxonomy_reference_categories_args );		

		}

		/**
		 * Add Columns to Portfolio Edit Screen
		 * http://wptheming.com/2010/07/column-edit-pages/
		 */

		function referencess_edit_columns( $columns ) {	
			$columns['referencess_thumbnail'] = 'Resim';
			$columns['title'] = 'Ürün Adı';
			$columns['reference_categories'] = 'Kategori';
			unset($columns['date']);
			return $columns;
		}

		function referencess_column_display( $referencess_columns, $post_id ) {

			// Code from: http://wpengineer.com/display-post-thumbnail-post-page-overview

			switch ( $referencess_columns ) {

				// Display the thumbnail in the column view
				case "referencess_thumbnail":
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
						echo 'Resim Yok';
					}
					break;	

				// Display the portfolio tags in the column view
				case "reference_categories":

					if ( $category_list = get_the_term_list( $post_id, 'reference_categories', '', ', ', '' ) ) {
						echo $category_list;
					} else {
						echo __( 'None', 'wpex' );
					}
					break;						
		
			}
		}
		
		/**
		 * Sortable Columns
		 */		
		function referencess_sortable_columns( $columns ) {-
			$columns['reference_categories'] = 'reference_categories';
			return $columns;
		}		

		/**
		 * Adds taxonomy filters to the urunler admin page
		 * Code artfully lifed from http://pippinsplugins.com
		 */

		function referencess_add_taxonomy_filters() {
			global $typenow;

			// An array of all the taxonomyies you want to display. Use the taxonomy name or slug
			$taxonomies = array( 'reference_categories' );

			// must set this to the post type you want the filter(s) displayed on
			if ( $typenow == 'referencess' ) {

				foreach ( $taxonomies as $tax_slug ) {
					$current_tax_slug = isset( $_GET[$tax_slug] ) ? $_GET[$tax_slug] : false;
					$tax_obj = get_taxonomy( $tax_slug );
					$tax_name = $tax_obj->labels->name;
					$terms = get_terms($tax_slug);
					if ( count( $terms ) > 0) {
						echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
						echo "<option value=''>$tax_name</option>";
						foreach ( $terms as $term ) {
							echo '<option value=' . $term->slug, $current_tax_slug == $term->slug ? ' selected="selected"' : '','>' . $term->name .' ( ' . $term->count .')</option>';
						}
						echo "</select>";
					}
				}
			}
		}

	}

	new BS_Referanslar_Post_Type;

}