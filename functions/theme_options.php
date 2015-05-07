<?php
/**
 * This file loads custom css and js for our theme
 *
 * @package WordPress
 * @subpackage CoreOne
 * @since CoreOne 1.0
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Default options values
$theme_options = array(
	'products' => true,
	'albums' => true,
	'category_images' => true,
	'referencess' => true,
	'analytics' => ''
);

if ( is_admin() ) :

	function bs_register_theme_settings() {
		register_setting( 'bs_theme_options', 'theme_options', 'bs_validate_theme_options' );
	}
	add_action( 'admin_init', 'bs_register_theme_settings' );

	function bs_theme_options() {
		add_menu_page( 'Tema Ayarları', 'Tema Ayarları', 'edit_theme_options', 'theme_options', 'bs_theme_options_page', 'dashicons-visibility', 45);
	}
	add_action( 'admin_menu', 'bs_theme_options' );

	function bs_theme_options_page() {
		global $theme_options;

		if ( ! isset( $_REQUEST['updated'] ) )
			$_REQUEST['updated'] = false; // This checks whether the form has just been submitted. ?>

		<div class="wrap">

			<h2>Tema Ayarları</h2>

			<?php if ( false !== $_REQUEST['updated'] ) : ?>
			<div class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
			<?php endif; ?>

			<form method="post" action="options.php">

			<?php $settings = get_option( 'theme_options', $theme_options ); ?>
			<?php settings_fields( 'bs_theme_options' ); ?>

				<div id="standard" class="postbox ">
					<div class="inside">						
						<br>
						<h3>İçerik Türleri</h3>
						<table>
							<tr>
								<td style="vertical-align:top;"><b>Ürünleri Etkinleştir</b></td>
								<td>
									<input type="checkbox" id="products" name="theme_options[products]" value="1" <?php checked( true, $settings['products'] ); ?> />
								</td>
							</tr>
							<tr>
								<td style="vertical-align:top;"><b>Ürün Kategori Resimlerini Etkinleştir</b></td>
								<td>
									<input type="checkbox" id="category_images" name="theme_options[category_images]" value="1" <?php checked( true, $settings['category_images'] ); ?> />
								</td>
							</tr>
							<tr>
								<td style="vertical-align:top;"><b>Albümleri Etkinleştir</b></td>
								<td>
									<input type="checkbox" id="albums" name="theme_options[albums]" value="1" <?php checked( true, $settings['albums'] ); ?> />
								</td>
							</tr>
							<tr>
								<td style="vertical-align:top;"><b>Referansları Etkinleştir</b></td>
								<td>
									<input type="checkbox" id="referencess" name="theme_options[referencess]" value="1" <?php checked( true, $settings['referencess'] ); ?> />
								</td>
							</tr>							
							<tr>
								<td style="vertical-align:top;"><b>Google Analytics Kodu</b></td>
								<td><textarea id="theme_options[analytics]" name="theme_options[analytics]" rows="5" cols="36"><?php esc_attr_e( $settings['analytics'] ); ?></textarea></td>
							</tr>
						</table>					
						<br/>
						<input name="submit" id="submit" value="Bilgileri Kaydet" type="submit">
					</div>
				</div>
			</form>
		</div>

		<?php
	}

	function bs_validate_theme_options( $input ) {

		global $theme_options;
		$settings = get_option( 'theme_options', $theme_options );
		
		//Analytics
		$input['analytics'] = wp_filter_post_kses( $input['analytics'] );
		
		//Content Types
		if ( ! isset( $input['products'] ) )
			$input['products'] = null;
		$input['products'] = ( $input['products'] == 1 ? 1 : 0 );		

		if ( ! isset( $input['albums'] ) )
			$input['albums'] = null;
		$input['albums'] = ( $input['albums'] == 1 ? 1 : 0 );

		if ( ! isset( $input['category_images'] ) )
			$input['category_images'] = null;
		$input['category_images'] = ( $input['category_images'] == 1 ? 1 : 0 );

		if ( ! isset( $input['referencess'] ) )
			$input['referencess'] = null;
		$input['referencess'] = ( $input['referencess'] == 1 ? 1 : 0 );		
		
		return $input;
	}

endif;  // EndIf is_admin()