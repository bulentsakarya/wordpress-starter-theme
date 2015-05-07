<?php
/**
 * This file loads custom css and js for our theme
 *
 * @package WordPress
 * @subpackage CoreOne
 * @since CoreOne 1.0
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//Address Widget
class address_widget extends WP_Widget { 
 
    /** constructor -- name this the same as the class above */
    function address_widget() {
        parent::WP_Widget(false, $name = 'Address Widget');	
    }
 
    /** @see WP_Widget::widget -- do not rename this */
    function widget($args, $instance) {
		extract( $args );
		
		global $firm_options;
		$settings = get_option( 'firm_options', $firm_options );
        ?>
				<?php if ($settings['firma_adi'] !='') { ?>
				<address style="margin-top:-3px;">
					<span style="font-size:16px;font-weight:normal;color:#EEEEEE;margin-top: -3px;margin-bottom: 10px;"><?php echo $settings['firma_adi'] . "</span><br><div class=\"height10\"></div>"; ?>
					<?php if ($settings['adres'] !='') { echo $settings['adres'] . "<br>"; } ?>
					<?php if ($settings['ilce'] !='') { echo $settings['ilce'] . " / "; } ?><?php if ($settings['sehir'] !='') { echo $settings['sehir'] . "<br>"; } ?>
					<?php if ($settings['telefon_bir'] !='') { echo "<abbr title=\"Telefon\">T:</abbr> " . $settings['telefon_bir'] . "<br>"; } ?>
					<?php if ($settings['telefon_iki'] !='') { echo "<abbr title=\"Telefon\">T:</abbr> " . $settings['telefon_iki'] . "<br>"; } ?>
					<?php if ($settings['cep_telefonu'] !='') { echo "<abbr title=\"Cep Telefonu\">M:</abbr> " . $settings['cep_telefonu'] . "<br>"; } ?>
					<?php if ($settings['cep_telefonu_iki'] !='') { echo "<abbr title=\"Cep Telefonu\">M:</abbr> " . $settings['cep_telefonu_iki']; } ?>
				</address>
				<?php } else { ?>
				<p>Lütfen "Firma Bilgileri" alanından firma adını giriniz.</p>
				<?php } ?>
				<div class="clearfix"></div>
        <?php
    }

} // end class address_widget

add_action('widgets_init', create_function('', 'return register_widget("address_widget");'));