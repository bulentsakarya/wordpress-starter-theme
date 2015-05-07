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
$firm_options = array(
	'firma_adi' => '',
	'slogan' => '',
	'adres' => '',
	'ilce' => '',
	'sehir' => '',
	'telefon_bir' => '',	
	'telefon_iki' => '',
	'cep_telefonu' => '',
	'cep_telefonu_iki' => '',
	'facebook' => '',
	'twitter' => '',
	'googleplus' => ''
);

if ( is_admin() ) :

	function bs_register_firm_settings() {
		register_setting( 'bs_firm_options', 'firm_options', 'bs_validate_firm_options' );
	}
	add_action( 'admin_init', 'bs_register_firm_settings' );

	function bs_firm_options() {
		add_menu_page( 'Firma Bilgileri', 'Firma Bilgileri', 'edit_theme_options', 'firm_options', 'bs_firm_options_page', 'dashicons-id-alt', 40);
	}
	add_action( 'admin_menu', 'bs_firm_options' );

	function bs_firm_options_page() {
		global $firm_options;

		if ( ! isset( $_REQUEST['updated'] ) )
			$_REQUEST['updated'] = false; // This checks whether the form has just been submitted. ?>

		<div class="wrap">

			<h2>Firma Bilgileri</h2>

			<?php if ( false !== $_REQUEST['updated'] ) : ?>
			<div class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
			<?php endif; ?>

			<form method="post" action="options.php">

			<?php $settings = get_option( 'firm_options', $firm_options ); ?>
			<?php settings_fields( 'bs_firm_options' ); ?>

				<div id="standard" class="postbox ">
					<div class="inside">
						<h3>Genel Bilgiler</h3>
						<table>
							<tr>
								<td><b>Firma Adı</b></td>
								<td><input id="firma_adi" type="text" size="36" name="firm_options[firma_adi]" value="<?php esc_attr_e( $settings['firma_adi'] ); ?>" /></td>
							</tr>
							<tr>
								<td style="vertical-align:top;"><b>Slogan</b></td>
								<td>
									<input id="slogan" type="text" size="36" name="firm_options[slogan]" value="<?php esc_attr_e( $settings['slogan'] ); ?>" /><br>
									<p style="margin-top:0;">İç sayfalarda sağ üst köşede görülecektir.</p>
								</td>
							</tr>						
							<tr>
								<td style="vertical-align:top;"><b>Adres</b></td>
								<td><textarea id="adres" name="firm_options[adres]" rows="5" cols="30"><?php echo stripslashes($settings['adres']); ?></textarea></td>
							</tr>
							<tr>
								<td><b>İlçe</b></td>
								<td><input id="firm_options[ilce]" type="text" size="20" name="firm_options[ilce]" value="<?php esc_attr_e( $settings['ilce'] ); ?>" /></td>
							</tr>
							<tr>
								<td><b>İl</b></td>
								<td><input id="firm_options[sehir]" type="text" size="20" name="firm_options[sehir]" value="<?php esc_attr_e( $settings['sehir'] ); ?>" /></td>
							</tr>
							<tr>
								<td><b>Sabit Telefon 1</b></td>
								<td><input id="firm_options[telefon_bir]" type="text" size="20" name="firm_options[telefon_bir]" value="<?php esc_attr_e( $settings['telefon_bir'] ); ?>" /></td>
							</tr>
							<tr>
								<td><b>Sabit Telefon 2</b></td>
								<td><input id="firm_options[telefon_iki]" type="text" size="20" name="firm_options[telefon_iki]" value="<?php esc_attr_e( $settings['telefon_iki'] ); ?>" /></td>
							</tr>
							<tr>
								<td><b>Cep Telefonu 1</b></td>
								<td><input id="firm_options[cep_telefonu]" type="text" size="20" name="firm_options[cep_telefonu]" value="<?php esc_attr_e( $settings['cep_telefonu'] ); ?>" /></td>
							</tr>
							<tr>
								<td><b>Cep Telefonu 2</b></td>
								<td><input id="firm_options[cep_telefonu_iki]" type="text" size="20" name="firm_options[cep_telefonu_iki]" value="<?php esc_attr_e( $settings['cep_telefonu_iki'] ); ?>" /></td>
							</tr>						
						</table>
						<br>
						<h3>Sosyal Medya Bilgileri</h3>
						<table>
							<tr>
								<td><b>Facebook Adresi</b></td>
								<td><input id="firm_options[facebook]" type="text" size="36" name="firm_options[facebook]" value="<?php esc_attr_e( $settings['facebook'] ); ?>" /></td>
							</tr>
							<tr>
								<td><b>Twitter Adresi</b></td>
								<td><input id="firm_options[twitter]" type="text" size="36" name="firm_options[twitter]" value="<?php esc_attr_e( $settings['twitter'] ); ?>" /></td>
							</tr>
							<tr>
								<td><b>Google+ Adresi</b></td>
								<td><input id="firm_options[googleplus]" type="text" size="36" name="firm_options[googleplus]" value="<?php esc_attr_e( $settings['googleplus'] ); ?>" /></td>
							</tr>
						</table>
						<input name="submit" id="submit" value="Bilgileri Kaydet" type="submit">
					</div>
				</div>
			</form>
		</div>

		<?php
	}

	function bs_validate_firm_options( $input ) {

		global $firm_options;
		$settings = get_option( 'firm_options', $firm_options );
		
		//Text
		$input['firma_adi'] = wp_filter_nohtml_kses( $input['firma_adi'] );
		$input['slogan'] = wp_filter_nohtml_kses( $input['slogan'] );
		$input['ilce'] = wp_filter_nohtml_kses( $input['ilce'] );
		$input['sehir'] = wp_filter_nohtml_kses( $input['sehir'] );
		$input['telefon_bir'] = wp_filter_nohtml_kses( $input['telefon_bir'] );
		$input['telefon_iki'] = wp_filter_nohtml_kses( $input['telefon_iki'] );
		$input['cep_telefonu'] = wp_filter_nohtml_kses( $input['cep_telefonu'] );
		$input['cep_telefonu_iki'] = wp_filter_nohtml_kses( $input['cep_telefonu_iki'] );
		$input['facebook'] = wp_filter_nohtml_kses( $input['facebook'] );
		$input['twitter'] = wp_filter_nohtml_kses( $input['twitter'] );
		$input['googleplus'] = wp_filter_nohtml_kses( $input['googleplus'] );
		
		//Textarea
		$input['adres'] = wp_filter_post_kses( $input['adres'] );
		
		return $input;
	}

endif;  // EndIf is_admin()