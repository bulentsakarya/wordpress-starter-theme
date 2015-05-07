<?php
/**
 * Registers the Ürünler Custom Post Type
 *
 * @package WordPress
 * @subpackage CoreOne
 * @since CoreOne 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'BS_Urunler_Post_Type' ) ) {

	class BS_Urunler_Post_Type {

		function __construct() {

			// Adds the portfolio post type and taxonomies
			add_action( 'init', array( &$this, 'products_init' ), 0 );

			// Adds columns in the admin view for thumbnail and taxonomies
			add_filter( 'manage_edit-products_columns', array( &$this, 'products_edit_columns' ) );
			add_action( 'manage_products_posts_custom_column', array( &$this, 'products_column_display' ), 10, 2 );
			add_filter( 'manage_edit-products_sortable_columns', array( &$this, 'products_sortable_columns'));

			// Allows filtering of posts by taxonomy in the admin view
			add_action( 'restrict_manage_posts', array( &$this, 'products_add_taxonomy_filters' ) );

		}
		

		function products_init() {

			/**
			 * Enable the Portfolio custom post type
			 * http://codex.wordpress.org/Function_Reference/register_post_type
			 */

			$labels = array(
				'name'					=> 'Ürünler',
				'singular_name'			=> 'Ürün',
				'add_new'				=> 'Ürün Ekle',
				'add_new_item'			=> 'Yeni Ürün',
				'edit_item'				=> 'Bilgileri Düzenle',
				'new_item'				=> 'Yeni Ürün',
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
				'rewrite'			=> array("slug" => "urunler"), // Permalinks format
				'has_archive'		=> true,
				'menu_icon'			=> 'dashicons-cart',
			); 
			
			$args = apply_filters( 'bs_products_args', $args);
			
			register_post_type( 'products', $args );


			/**
			 * Register a taxonomy for Ürünler Categories
			 * http://codex.wordpress.org/Function_Reference/register_taxonomy
			 */

			$taxonomy_product_categories_labels = array(
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

			$taxonomy_product_categories_args = array(
				'labels'			=> $taxonomy_product_categories_labels,
				'public'			=> true,
				'show_in_nav_menus'	=> true,
				'show_ui'			=> true,
				'show_tagcloud'		=> true,
				'hierarchical'		=> true,
				'rewrite'			=> array( 'slug' => 'urun-kategori' ),
				'query_var'			=> true
			);

			$taxonomy_product_categories_args = apply_filters( 'bs_taxonomy_product_categories_args', $taxonomy_product_categories_args);
			
			register_taxonomy( 'product_categories', array( 'products' ), $taxonomy_product_categories_args );		

		}

		/**
		 * Add Columns to Portfolio Edit Screen
		 * http://wptheming.com/2010/07/column-edit-pages/
		 */

		function products_edit_columns( $columns ) {	
			$columns['products_thumbnail'] = 'Resim';
			$columns['title'] = 'Ürün Adı';
			$columns['product_categories'] = 'Kategori';
			unset($columns['date']);
			return $columns;
		}

		function products_column_display( $products_columns, $post_id ) {

			// Code from: http://wpengineer.com/display-post-thumbnail-post-page-overview

			switch ( $products_columns ) {

				// Display the thumbnail in the column view
				case "products_thumbnail":
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
				case "product_categories":

					if ( $category_list = get_the_term_list( $post_id, 'product_categories', '', ', ', '' ) ) {
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
		function products_sortable_columns( $columns ) {-
			$columns['product_categories'] = 'product_categories';
			return $columns;
		}		

		/**
		 * Adds taxonomy filters to the urunler admin page
		 * Code artfully lifed from http://pippinsplugins.com
		 */

		function products_add_taxonomy_filters() {
			global $typenow;

			// An array of all the taxonomyies you want to display. Use the taxonomy name or slug
			$taxonomies = array( 'product_categories' );

			// must set this to the post type you want the filter(s) displayed on
			if ( $typenow == 'products' ) {

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

	new BS_Urunler_Post_Type;

}