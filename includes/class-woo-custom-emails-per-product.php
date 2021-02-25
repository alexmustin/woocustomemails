<?php

class Woo_Custom_Emails_Per_Product {

	// Define vars
	protected $loader;
	protected $plugin_slug;
	protected $version;

	// Class constructor
	public function __construct() {

		$this->plugin_slug = 'woo_custom_emails_domain';
		$this->version = WCE_PLUGIN_VERSION;

		$this->woo_custom_emails_load_dependencies();
		$this->woo_custom_emails_define_admin_hooks();

	}

	// Load required files
	private function woo_custom_emails_load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-woo-product-data-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woo-custom-emails-per-product-loader.php';
		$this->loader = new Woo_Custom_Emails_Per_Product_Loader();

	}

	// Setup Admin Hooks
	private function woo_custom_emails_define_admin_hooks() {
		$woo_product_data_admin = new Woo_Product_Data_Admin( $this->get_version() );

		$this->loader->add_action( 'admin_head-post.php', $woo_product_data_admin, 'wce_custom_admin_style' );
		$this->loader->add_action( 'admin_head-post-new.php', $woo_product_data_admin, 'wce_custom_admin_style' );
		$this->loader->add_action( 'admin_enqueue_scripts', $woo_product_data_admin, 'wce_enqueue_custom_admin_style' );
		$this->loader->add_action( 'woocommerce_product_data_tabs', $woo_product_data_admin, 'add_woo_custom_emails_tab' );
		$this->loader->add_action( 'woocommerce_product_data_panels', $woo_product_data_admin, 'add_woo_custom_emails_tab_fields' );
		$this->loader->add_action( 'woocommerce_process_product_meta', $woo_product_data_admin, 'save_woo_custom_emails_tab_fields' );

		//* Add AJAX Fetch JS to footer
		$this->loader->add_action( 'admin_footer', $woo_product_data_admin, 'ajax_wce_fetch_script' );

		//* Add AJAX Fetch Function
		$this->loader->add_action( 'wp_ajax_wce_data_fetch' , $woo_product_data_admin, 'wce_data_fetch' );
		$this->loader->add_action( 'wp_ajax_nopriv_wce_data_fetch', $woo_product_data_admin, 'wce_data_fetch' );

	}

	// Function to get plugin version
	public function get_version() {
		return $this->version;
	}

	// Run everything
	public function run() {
		$this->loader->run();
	}

}
