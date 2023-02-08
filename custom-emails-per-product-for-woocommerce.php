<?php
/**
 * Plugin Name: Custom Emails Per Product for WooCommerce
 * Description: Add custom content per product into the default WooCommerce customer receipt email template.
 * Version: 1.0.0
 * Author: Alex Mustin
 * Author URI: https://alexmustin.com
 * Text Domain: cepp4wc_domain
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * WC requires at least: 6.3.0
 * WC tested up to: 7.3.0
 *
 * @package cepp4wc_domain
 */

// Exit if not WordPress.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define Globals.
define( 'CEPP4WC_PLUGIN_VERSION', '1.0.0' );

// Add a check for WooCommerce on plugin activation.
register_activation_hook( __FILE__, 'cepp4wc_activate_check_for_woo' );

/**
 * Checks for WooCommerce on plugin activation.
 */
function cepp4wc_activate_check_for_woo() {
	if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
		$woo_plugin_url = esc_url( 'https://wordpress.org/plugins/woocommerce/' );
		$text_string  = '';
		$text_string .= sprintf( '%1$s%2$s%3$s', '<h2>', esc_html__( 'Oops!', 'cepp4wc_domain' ), '</h2>' );
		$text_string .= sprintf( '%1$s%2$sWooCommerce%3$s%4$s%5$s', '<p>', '<a href="' . $woo_plugin_url . '" target="_blank">', '</a>', esc_html__( ' is required for this plugin.', 'cepp4wc_domain' ), '</p>' );
		$text_string .= sprintf( '%1$s%2$s%3$s', '<p>', esc_html__( 'Please install and activate WooCommerce and try again.', 'cepp4wc_domain' ), '</p>' );
		wp_die( $text_string ); // phpcs:ignore
	}
}

// Include required files.
require_once plugin_dir_path( __FILE__ ) . 'includes/class-custom-emails-per-product-for-woocommerce.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/class-custom-emails-per-product-for-wc-cpt.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/class-custom-emails-per-product-for-wc-column-display.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/class-custom-emails-per-product-for-wc-admin-settings.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-custom-emails-per-product-for-wc-output.php';

/**
 * Runs main plugin functions.
 */
function run_cepp4wc() {
	// Create a new object.
	$cepp4wc_obj = new Custom_Emails_Per_Product_For_WooCommerce();

	// Do the 'run' function inside our object.
	$cepp4wc_obj->run();

	// Create a new output object.
	new Custom_Emails_Per_Product_For_WC_Output();
}

// Go!
run_cepp4wc();
