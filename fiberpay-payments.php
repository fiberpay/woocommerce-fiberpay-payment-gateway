<?php
/*
 * Plugin Name: Fiberpay Payment Plugin
 * Plugin URI: https://fiberpay.pl
 * Description: Take instant payments on your store.
 * Author: Fiberpay
 * Author URI: https://fiberpay.pl
 * Text Domain: fiberpay-payments
 * Domain Path: /languages
 * Version: 0.1.0
 */

if (!defined( 'ABSPATH')) {
	exit; // Exit if accessed directly.
}

if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
	return;
}

require __DIR__ . '/vendor/autoload.php';

function fiberpay_add_gateway_class($gateways) {
	$gateways[] = 'Fiberpay_WC_Payment_Gateway';
	return $gateways;
}
add_filter('woocommerce_payment_gateways', 'fiberpay_add_gateway_class');

add_action('plugins_loaded', 'fiberpay_init_gateway_class', 11);

function fiberpay_init_gateway_class() {
	load_plugin_textdomain( 'fiberpay-payments', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );

	if ( is_admin() ) {
		require_once dirname( __FILE__ ) . '/includes/class-wc-fiberpay-admin-notices.php';
	}

	if(class_exists('WC_Payment_Gateway')) {
		static $plugin;

		if ( ! isset( $plugin ) ) {
			include_once plugin_dir_path(__FILE__) . '/vendor/fiberpay/fiberpay-php/lib/FiberPayClient.php';
			include_once plugin_dir_path(__FILE__) . '/includes/class-wc-gateway-fiberpay.php';
		}

		return $plugin;
	}
}
