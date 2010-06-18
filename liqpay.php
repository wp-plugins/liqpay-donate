<?php
/*
Plugin Name: Liqpay Donate
Plugin URI: http://www.icom.kiev.ua/index.php?page=liqpay
Description: Liqpay Donate - plugin for to get donations and accept payments. Adds a sidebar widget to display Liqpay Donate.
Author: Icom Organization (Bordyzhan Sergey)
Version: 1.0
Author URI: http://sergey.icom.kiev.ua/
*/

/*
Copyright (c) 2009-2010, Icom Organization.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/
add_action("widgets_init", array('Liqpay_donate', 'register'));

class Liqpay_donate {

  function liqpay_control() {
  	$liqpay_option = get_option('liqpay_donate');
	
	if (!is_array($liqpay_option)) {
		$liqpay_option = array('graphic_button'=>'"checked="checked"');
	}?>
  	
	<p>
		<strong><?php _e('Title Liqpay Donate', 'liqpay') ?></strong>
	</p>
    <p>
	   	<label for="liqpay_title"><?php _e('Title text:', 'liqpay') ?></label><br />
	    <input type="text" name="title" id="liqpay_title" value="<?php echo $liqpay_option['title'] ?>" class="widefat" />
	</p>
	<p>
		<strong><?php _e('Options Liqpay Donate', 'liqpay') ?></strong>
	</p>
	<p>
		<label for="liqpay_merchant_id"><?php _e('Merchant ID:', 'liqpay') ?></label><br />
		<input type="text" name="merchant_id" id="liqpay_merchant_id" value="<?php echo $liqpay_option['merchant'] ?>" class="widefat" /><br />
		
		<label for="liqpay_result_url"><?php _e('Redirect after payment:', 'liqpay') ?></label><br />
		<input type="text" name="result_url" id="liqpay_result_url" value="<?php echo $liqpay_option['result_url'] ?>" class="widefat" /><br />
		
		<label for="liqpay_description"><?php _e('Payment description:', 'liqpay') ?></label><br />
		<input type="text" name="description" id="liqpay_description" value="<?php echo $liqpay_option['description'] ?>" class="widefat" /><br />
		
		<label for="liqpay_amount"><?php _e('Amount:', 'liqpay') ?></label><br />
		<input type="text" name="amount" id="liqpay_amount" value="<?php echo $liqpay_option['amount'] ?>" class="widefat" /><br />
    	<input type="hidden" name="liqpay_submit" value="1" /> 
	</p>
	<p>
		<strong><?php _e('Appearance Liqpay Donate', 'liqpay') ?></strong>
	</p>
	<p>
		<label for="graphic_button">
			<input type="radio" name="button" <?php echo $liqpay_option['graphic_button'] ?> id="graphic_button" value="1" />
			<?php _e('Graphics button', 'liqpay') ?>
		</label><br />
		
		<label for="usual_button">
			<input type="radio" name="button" <?php echo $liqpay_option['usual_button'] ?> id="usual_button" value="2" />
			<?php _e('Standart button', 'liqpay') ?>
		</label><br />

	</p><?php
   		
	if (isset($_POST['liqpay_submit'])){
    	$liqpay_option['title'] = attribute_escape($_POST['title']);
    	$liqpay_option['merchant'] = attribute_escape($_POST['merchant_id']);
		$liqpay_option['result_url'] = attribute_escape($_POST['result_url']);
		$liqpay_option['description'] = attribute_escape($_POST['description']);
		$liqpay_option['amount'] = attribute_escape($_POST['amount']);
		$liqpay_option['button'] = $_POST['button'];
		if ($liqpay_option['button'] == 1) {
			$liqpay_option['graphic_button'] = '"checked="checked"';
			$liqpay_option['usual_button'] = '';
		}
		if ($liqpay_option['button'] == 2) {
			$liqpay_option['usual_button'] = '"checked="checked"';
			$liqpay_option['graphic_button'] = '';
		}
	
    	update_option('liqpay_donate', $liqpay_option);
	}
  }

  function liqpay_widget($args) {
  	$liqpay_option = get_option('liqpay_donate');
	
    echo $args['before_widget'];
    echo $args['before_title'].$liqpay_option['title'].$args['after_title'];?>
	
	<p> 
		<form action="https://liqpay.com/?do=clickNbuy" method="post" accept-charset="utf-8" target="_blank">
			<input type="hidden" name="version" value="1.1" />
			<input type="hidden" name="merchant_id" value="<?php echo $liqpay_option['merchant']; ?>" />
			<input type="hidden" name="result_url" value="<?php echo $liqpay_option['result_url']; ?>" />
			<input type="hidden" name="undefined_quantity" value="0" />
			<input type="hidden" name="description" value="<?php echo $liqpay_option['description']; ?>" />
			<?php if ($liqpay_option['button'] == 2) {
				_e('Donate', 'liqpay'); echo('<br />');
			}?>
			<input type="text" size="4" name="amount" value="<?php echo $liqpay_option['amount']; ?>" />
			<select style="width: 55px;" name="currency">
				<option value="EUR">EUR</option>
				<option selected="selected" value="USD">USD</option>
				<option value="RUR">RUR</option>
				<option value="UAH">UAH</option>
			</select><br />
			<input type="hidden" name="charset" value="utf-8" />
			<input type="hidden" name="no_shipping" value="1" />
			<input type="hidden" name="no_note" value="0" />
			<?php if ($liqpay_option['button'] == 2) {
				echo '&nbsp;';_e('on site development', 'liqpay');
				echo '<br/><input type="submit" name="pay" value="'.__('Pay', 'liqpay').'" />';
			}
			else {
				echo '<input type="image" src="'.WP_PLUGIN_URL."/".basename(dirname(__FILE__)).'/images/donate_liqpay.png" width="90" height="60" border="0" name="submit" alt="'.__('To pay by means of LiqPAY', 'liqpay').'" title="'.__('To pay by means of LiqPAY', 'liqpay').'" style="border: none; background: none;" />';
			}?>
		</form>
	</p><?php
    
	echo $args['after_widget'];
  }
  
  function register() {
    if (function_exists('load_plugin_textdomain')) {
        load_plugin_textdomain('liqpay', 'wp-content/plugins/liqpay/languages');
    }
    register_sidebar_widget('Liqpay Donate', array('Liqpay_donate', 'liqpay_widget'));
    register_widget_control('Liqpay Donate', array('Liqpay_donate', 'liqpay_control'));
  }

}