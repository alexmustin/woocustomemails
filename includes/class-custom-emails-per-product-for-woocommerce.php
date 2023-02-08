<?php
/**
 * Main constructor file.
 *
 * @package cepp4wc_domain
 */

/**
 * The Custom_Emails_Per_Product_For_WooCommerce class handles the structure of the plugin.
 */
class Custom_Emails_Per_Product_For_WooCommerce {

	/**
	 * Loader var.
	 *
	 * @var object $loader Object to track what to load.
	 */
	protected $loader;

	/**
	 * Slug.
	 *
	 * @var string $plugin_slug The slug of this plugin.
	 */
	protected $plugin_slug;

	/**
	 * Plugin version.
	 *
	 * @var string $version The version of this plugin.
	 */
	protected $version;

	/**
	 * The Class constructor.
	 */
	public function __construct() {

		$this->plugin_slug = 'cepp4wc_domain';
		$this->version     = CEPP4WC_PLUGIN_VERSION;

		$this->cepp4wc_load_dependencies();
		$this->cepp4wc_define_admin_hooks();

	}

	/**
	 * Loads the required files.
	 *
	 * @return void
	 */
	private function cepp4wc_load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-custom-emails-per-product-for-wc-product-data-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-custom-emails-per-product-for-wc-loader.php';
		$this->loader = new Custom_Emails_Per_Product_For_WC_Loader();

	}

	/**
	 * Setup the Admin Hooks
	 *
	 * @return void
	 */
	private function cepp4wc_define_admin_hooks() {
		$cepp4wp_product_data_admin = new Custom_Emails_Per_Product_For_WC_Product_Data_Admin( $this->get_version() );

		$this->loader->add_action( 'admin_head-post.php', $cepp4wp_product_data_admin, 'cepp4wp_custom_admin_style' );
		$this->loader->add_action( 'admin_head-post-new.php', $cepp4wp_product_data_admin, 'cepp4wp_custom_admin_style' );
		$this->loader->add_action( 'admin_enqueue_scripts', $cepp4wp_product_data_admin, 'cepp4wp_enqueue_custom_admin_style' );
		$this->loader->add_action( 'woocommerce_product_data_tabs', $cepp4wp_product_data_admin, 'add_cepp4wc_tab' );
		$this->loader->add_action( 'woocommerce_product_data_panels', $cepp4wp_product_data_admin, 'add_cepp4wc_tab_fields' );
		$this->loader->add_action( 'woocommerce_process_product_meta', $cepp4wp_product_data_admin, 'save_cepp4wc_tab_fields' );

		// Add AJAX Fetch JS to footer.
		$this->loader->add_action( 'admin_footer', $cepp4wp_product_data_admin, 'ajax_cepp4wp_fetch_script' );

		// Add AJAX Fetch Function.
		$this->loader->add_action( 'wp_ajax_cepp4wp_data_fetch', $cepp4wp_product_data_admin, 'cepp4wp_data_fetch' );
		$this->loader->add_action( 'wp_ajax_nopriv_cepp4wp_data_fetch', $cepp4wp_product_data_admin, 'cepp4wp_data_fetch' );

	}

	/**
	 * Get the plugin version.
	 *
	 * @return string $version The plugin version.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Run everything.
	 *
	 * @return void
	 */
	public function run() {
		$this->loader->run();
	}

}
