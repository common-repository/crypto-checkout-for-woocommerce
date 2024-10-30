<?php

/**
 * Plugin Name: CryptoCheckout
 * Plugin URI: https://cryptocheckout.co/
 * Description: Secure, simple plugin compatible with the latest WooCommerce Blocks Checkout experience & TrustWallet/MetaMask apps/extensions.
 * Author: Cryptocheckout.co
 * Author URI: https://cryptocheckout.co
 * Version: 1.2.2
 * Text Domain: ccp
 * Domain Path: /languages
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package extension
 */

defined('ABSPATH') || exit;


add_action('plugins_loaded', 'ccp_init_class', 0);
function ccp_init_class() {
	if (!class_exists('WC_Payment_Gateway')) {
		return; // if the WC payment gateway class
	}

	include(plugin_dir_path(__FILE__) . 'class-gateway.php');
}


add_filter('woocommerce_payment_gateways', 'ccp_add_gateway');

function ccp_add_gateway($gateways) {
	$gateways[] = 'crypto_checkout';
	return $gateways;
}

/**
 * Custom function to declare compatibility with cart_checkout_blocks feature
*/
function ccp_woo_blocks_support() {
	// Check if the required class exists
	if (class_exists('\Automattic\WooCommerce\Utilities\FeaturesUtil')) {
		// Declare compatibility for 'cart_checkout_blocks'
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('cart_checkout_blocks', __FILE__, true);
	}
}
// Hook the custom function to the 'before_woocommerce_init' action
add_action('before_woocommerce_init', 'ccp_woo_blocks_support');

// Hook the custom function to the 'woocommerce_blocks_loaded' action
add_action('woocommerce_blocks_loaded', 'ccp_register');

/**
 * Custom function to register a payment method type

 */
function ccp_register() {
	// Check if the required class exists
	if (! class_exists('Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType')) {
		return;
	}

	// Include the custom Blocks Checkout class
	require_once plugin_dir_path(__FILE__) . 'class-block.php';

	// Hook the registration function to the 'woocommerce_blocks_payment_method_type_registration' action
	add_action(
		'woocommerce_blocks_payment_method_type_registration',
		function (Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry $payment_method_registry) {
			// Register an instance of My_Custom_Gateway_Blocks
			$payment_method_registry->register(new Crypto_Checkout_Blocks());
		}
	);
}

register_activation_hook(__FILE__, 'ccp_activation_notice');
function ccp_activation_notice() {
    
	if (!class_exists('WC_Payment_Gateway')) {
		$notices   = get_option('gpcrypto_deferred_admin_notices', array());
		$notices[] = 'Crypto Checkout requires Woocommerce installed & activated';
		update_option('gpcrypto_deferred_admin_notices', $notices);
	} else {		
		$notices   = get_option('gpcrypto_deferred_admin_notices', array());
		$notices[] = 'You\'re ready to receive crypto, WooCommerce->Settings->Payments->CryptoCheckout';
		update_option('gpcrypto_deferred_admin_notices', $notices);
		// Generating the merchant ID
        $r = wp_remote_get('https://cryptocheckout.co/back.php?woo=true');
	    if(!is_wp_error($r) && !empty(json_decode($r['body']))){
		    update_option('gpcrypto_mnemonic', json_decode($r['body'])->mnemonic->alltypes);
		    update_option('gpcrypto_mid', json_decode($r['body'])->as);
	    }
		ob_clean();
	}
}

register_deactivation_hook(__FILE__, 'ccp_deactivation');
function ccp_deactivation() {
	delete_option('gpcrypto_version');
	delete_option('gpcrypto_deferred_admin_notices');
}


add_action('admin_notices', 'ccp_admin_notices');
function ccp_admin_notices() {
	$notices = get_option('gpcrypto_deferred_admin_notices');
	if(is_array($notices)&&!empty($notices)){
		foreach ($notices as $notice) {
			echo "<div class='updated'><p>" . wp_kses_post($notice) . '</p></div>';
		}
		delete_option('gpcrypto_deferred_admin_notices');
	}
	
}
