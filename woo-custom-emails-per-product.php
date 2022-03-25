<?php
/**
 * Plugin Name: WC Custom Emails Per Product
 * Description: Add custom content per product into the default WooCommerce customer receipt email template.
 * Version: 2.2.10
 * Author: Alex Mustin
 * Author URI: http://alexmustin.com
 * Text Domain: woo_custom_emails_domain
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * WC requires at least: 5.0.0
 * WC tested up to: 6.3.1
 *
 * @package woo_custom_emails_domain
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
define( 'WCE_PLUGIN_VERSION', '2.2.10' );

// Add a check for WooCommerce on plugin activation.
register_activation_hook( __FILE__, 'woo_custom_emails_activate_check_for_woo' );

/**
 * Checks for WooCommerce on plugin activation.
 */
function woo_custom_emails_activate_check_for_woo() {
	if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
		$woo_plugin_url = esc_url( 'https://wordpress.org/plugins/woocommerce/' );

		$text_string  = '';
		$text_string .= sprintf( '%1$s%2$s%3$s', '<h2>', esc_html__( 'Oops!', 'woo_custom_emails_domain' ), '</h2>' );
		$text_string .= sprintf( '%1$s%2$sWooCommerce%3$s%4$s%5$s', '<p>', '<a href="' . $woo_plugin_url . '" target="_blank">', '</a>', esc_html__( ' is required for this plugin.', 'woo_custom_emails_domain' ), '</p>' );
		$text_string .= sprintf( '%1$s%2$s%3$s', '<p>', esc_html__( 'Please install and activate WooCommerce and try again.', 'woo_custom_emails_domain' ), '</p>' );
		wp_die( $text_string ); // phpcs:ignore
	}
}

// Include required files.
require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-custom-emails-per-product.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/class-woo-custom-emails-per-product-cpt.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/class-woo-custom-emails-column-display.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/class-woo-custom-emails-per-product-admin-settings.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-custom-emails-output.php';

/**
 * Runs main plugin functions.
 */
function run_woo_custom_emails_per_product() {
	// Create a new object.
	$woo_custom_emails_domain = new Woo_Custom_Emails_Per_Product();

	// Do the 'run' function inside our object.
	$woo_custom_emails_domain->run();

	// Create a new output object.
	new Woo_Custom_Emails_Output();
}

// Go!
run_woo_custom_emails_per_product();
