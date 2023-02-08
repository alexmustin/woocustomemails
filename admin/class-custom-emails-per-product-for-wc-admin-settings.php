<?php
/**
 * Custom_Emails_Per_Product_For_WC_Admin_Settings is a class for adding the Admin Settings for the plugin.
 */
class Custom_Emails_Per_Product_For_WC_Admin_Settings {

	/**
	 * Tracks the plugin settings.
	 *
	 * @var object $cepp4wc_settings_options Object to track the settings for the plugin.
	 */
	private $cepp4wc_settings_options;

	/**
	 * Setup the plugin settings object.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( &$this, 'cepp4wc_settings_add_plugin_page' ) );
		add_action( 'admin_init', array( &$this, 'cepp4wc_settings_page_init' ) );
	}

	/**
	 * Adds the plugin settings page to the menu.
	 */
	public function cepp4wc_settings_add_plugin_page() {
		add_submenu_page(
			'edit.php?post_type=cepp4wp_message', // parent slug.
			__( 'Custom Emails Per Product for WooCommerce Settings', 'cepp4wc_domain' ), // page title.
			__( 'Settings', 'cepp4wc_domain' ), // menu title.
			'manage_options', // capability.
			'cepp4wc-settings', // menu slug.
			array( $this, 'cepp4wc_settings_create_admin_page' ) // callback function.
		);
	}

	/**
	 * Adds the plugin Admin settings page to the menu.
	 */
	public function cepp4wc_settings_create_admin_page() {
		$this->cepp4wc_settings_options = get_option( 'cepp4wc_settings_name' );
		?>

		<div class="wrap">
			<h2>Custom Emails Per Product for WooCommerce Settings</h2>

			<hr>

			<p class="howto"><?php esc_html( 'Settings for the Custom Emails Per Product For WooCommerce plugin.' ); ?></p>

			<?php settings_errors(); ?>

			<?php
			if ( isset( $_GET['tab'] ) ) {
				$active_tab = $_GET['tab'];
			} else {
				$active_tab = 'display';
			}
			?>

			<?php
			if ( isset( $_GET['page'] ) && wp_verify_nonce( sanitize_key( $_GET['page'] ) ) ) {
				$page = sanitize_text_field( wp_unslash( $_GET['page'] ) );
			} else {
				$page = 1;
			}
			?>
			<h2 class="nav-tab-wrapper">
				<a href="?post_type=cepp4wp_message&page=cepp4wc-settings" class="nav-tab <?php echo 'display' === $active_tab ? 'nav-tab-active' : ''; ?>">Display Settings</a>
				<a href="?post_type=cepp4wp_message&page=cepp4wc-settings&tab=more" class="nav-tab <?php echo 'more' === $active_tab ? 'nav-tab-active' : ''; ?>">More Settings</a>
			</h2>

			<form method="post" action="options.php">
				<?php
				if ( 'display' === $active_tab ) {
					settings_fields( 'cepp4wc_settings_option_group' );
					do_settings_sections( 'cepp4wp-settings-admin' );
					submit_button();
				}

				if ( 'more' === $active_tab ) {
					echo '<p>More options are coming soon!</p>';
				}
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Adds the 'Display Settings' section to the plugin settings page.
	 */
	public function cepp4wc_settings_page_init() {
		register_setting(
			'cepp4wc_settings_option_group', // option_group.
			'cepp4wc_settings_name', // option_name.
			array( $this, 'cepp4wc_settings_sanitize' ) // sanitize_callback.
		);

		add_settings_section(
			'cepp4wc_settings_setting_section', // id.
			'Display Settings', // title.
			array( $this, 'cepp4wc_settings_section_info' ), // callback.
			'cepp4wp-settings-admin' // page.
		);

		add_settings_field(
			'show_in_admin_email', // id.
			'Include in Admin Emails', // title.
			array( $this, 'show_in_admin_email_callback' ), // callback.
			'cepp4wp-settings-admin', // page.
			'cepp4wc_settings_setting_section' // section.
		);

		add_settings_field(
			'display_classes', // id.
			'Display for Other Product Types', // title.
			array( $this, 'display_classes_callback' ), // callback.
			'cepp4wp-settings-admin', // page.
			'cepp4wc_settings_setting_section' // section.
		);
	}

	/**
	 * Sanitizes field inputs.
	 *
	 * @param string $input The field data to be sanitized.
	 */
	public function cepp4wc_settings_sanitize( $input ) {
		$sanitary_values = array();
		if ( isset( $input['show_in_admin_email'] ) ) {
			$sanitary_values['show_in_admin_email'] = $input['show_in_admin_email'];
		}

		if ( isset( $input['display_classes'] ) ) {
			$sanitary_values['display_classes'] = sanitize_text_field( $input['display_classes'] );
		}

		return $sanitary_values;
	}

	/**
	 * Displays a line of description text.
	 */
	public function cepp4wc_settings_section_info() {
		echo esc_html( 'Configure the display settings for the Custom Messages.' );
	}

	/**
	 * Adds a checkbox field for the setting: Show messages in Admin emails.
	 */
	public function show_in_admin_email_callback() {
		printf(
			'<input type="checkbox" name="cepp4wc_settings_name[show_in_admin_email]" id="show_in_admin_email" value="show_in_admin_email" %s> <label for="show_in_admin_email">' . __( 'Show the Custom Messages content inside Admin order notification emails.', 'cepp4wc_domain' ) . '</label>',
			( isset( $this->cepp4wc_settings_options['show_in_admin_email'] ) && 'show_in_admin_email' === $this->cepp4wc_settings_options['show_in_admin_email'] ) ? 'checked' : ''
		);
	}

	/**
	 * Adds a textarea field for the setting: Display classes.
	 */
	public function display_classes_callback() {
		printf(
			'<textarea class="large-text" rows="5" name="cepp4wc_settings_name[display_classes]" id="display_classes">%s</textarea>',
			isset( $this->cepp4wc_settings_options['display_classes'] ) ? esc_attr( $this->cepp4wc_settings_options['display_classes'] ) : ''
		);
		// Description.
		echo '<div class="description" style="margin-top: 20px;">' . esc_html__( 'By default, WooCommerce only shows the "Custom Emails" tab for the standard "', 'cepp4wc_domain' ) . '<b>product</b>' . esc_html__( '" post type.', 'cepp4wc_domain' );
		echo '<br>' . esc_html__( 'Use this option to force other post types to show the Custom Emails tab. This should be a comma-separated list.', 'cepp4wc_domain' );
		echo '</div>';

		// Code example.
		echo '<div class="description" style="margin-top: 20px; margin-left: 30px;"><b>Example:</b><pre>show_if_booking, show_if_grouped</pre></div>';

		echo '<hr />';

		// Instructions to find CSS Class.
		echo '<div class="description" style="margin-top: 20px;"><h3>' . esc_html__( 'How to Find the CSS Class for a Custom Product', 'cepp4wc_domain' ) . '</h3>' . esc_html__( 'To find the class for your product type, do the following:', 'cepp4wc_domain' );
		echo '<ol>';
		echo '<li>' . esc_html__( 'Go to ', 'cepp4wc_domain' ) . '<b>' . esc_html__( 'Products', 'cepp4wc_domain' ) . '</b>' . esc_html__( ', and Edit a custom product', 'cepp4wc_domain' ) . '</li>';
		echo '<li>' . esc_html__( 'Scroll down to the ', 'cepp4wc_domain' ) . '<b>' . esc_html__( 'Product Data', 'cepp4wc_domain' ) . '</b>' . esc_html__( ' table', 'cepp4wc_domain' ) . '</li>';
		echo '<li><b>' . esc_html__( 'Inspect the code', 'cepp4wc_domain' ) . '</b>' . esc_html__( ' for a tab item, like ', 'cepp4wc_domain' ) . '<b>' . esc_html__( 'Inventory', 'cepp4wc_domain' ) . '</b>' . esc_html__( ' (or any menu item which appears only for your product type)', 'cepp4wc_domain' ) . '</li>';
		echo '<li>' . esc_html__( 'Find the custom CSS class for your product type which is assigned to that tab menu item. These classes usually start with ', 'cepp4wc_domain' ) . '<b>' . esc_html__( 'show_if_', 'cepp4wc_domain' ) . '</b>' . esc_html__( ' -- ex: "show_if_booking"', 'cepp4wc_domain' ) . '</li>';
		echo '<li>' . esc_html__( 'Copy and paste that CSS class into the field above', 'cepp4wc_domain' ) . '</li>';
		echo '<li>' . esc_html__( 'Save your settings', 'cepp4wc_domain' ) . '</li>';
		echo '</ol>';
		echo '</div>';

		// Note text.
		echo '<div style="margin-top: 20px; padding: 20px; border: 1px solid rgba(0,0,0,0.15);"><b style="color: #ff0000;">' . esc_html( 'Please Note: This feature is experimental and is not guaranteed to work.' ) . '</b><br>' . esc_html( 'Some WooCommerce add-ons use their own method of sending emails, outside of the normal WooCommerce process this plugin uses.' ) . '</div>';
	}

}
if ( is_admin() ) {
	$cepp4wc_settings = new Custom_Emails_Per_Product_For_WC_Admin_Settings();
}

/*
 * Retrieve values with the following functions:
 *
 * $cepp4wc_settings_options = get_option( 'cepp4wc_settings_name' ); // Array of All Options
 * $show_in_admin_email = $cepp4wc_settings_options['show_in_admin_email']; // Include in Admin Emails
 * $display_classes = $cepp4wc_settings_options['display_classes']; // Display for Other Product Types
 */
