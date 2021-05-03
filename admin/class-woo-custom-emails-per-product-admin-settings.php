<?php

class Woo_Custom_Emails_Per_Product_Admin_Settings {

	private $wce_settings_options;

	public function __construct() {
		add_action( 'admin_menu', array( &$this, 'wce_settings_add_plugin_page' ) );
		add_action( 'admin_init', array( &$this, 'wce_settings_page_init' ) );
	}

	public function wce_settings_add_plugin_page() {
        add_submenu_page(
            'edit.php?post_type=woocustomemails',				// parent slug
            __('WCE Settings','woo_custom_emails_domain'), 		// page title
            __('WCE Settings','woo_custom_emails_domain'), 		// menu title
            'manage_options', 									// capability
            'wce-settings', 									// menu slug
            array( $this, 'wce_settings_create_admin_page' )	// callback function
        );
	}

	public function wce_settings_create_admin_page() {
		$this->wce_settings_options = get_option( 'woocustomemails_settings_name' );
		// $this->plugin_settings_tabs['wce-settings'] = 'Display Settings';
		?>

		<div class="wrap">
			<h2>Woo Custom Emails Settings</h2>

			<hr>

		    <p class="howto"><?php _e('Settings for the Woo Custom Emails Per Product plugin.', 'woo_custom_emails_domain'); ?></p>

			<?php settings_errors(); ?>

			<?php
            if ( isset( $_GET ) ) {
				if ( empty( $_GET['tab'] ) ) {
					$active_tab = 'display_settings';
				} else {
	                $active_tab = $_GET['tab'];
				}
            }
            ?>

			<h2 class="nav-tab-wrapper">
                <a href="?post_type=woocustomemails&page=<?php echo $_GET['page']; ?>&tab=display_settings" class="nav-tab <?php echo $active_tab == 'display_settings' ? 'nav-tab-active' : ''; ?>">Display Settings</a>
                <a href="?post_type=woocustomemails&page=<?php echo $_GET['page']; ?>&tab=admin_settings" class="nav-tab <?php echo $active_tab == 'admin_settings' ? 'nav-tab-active' : ''; ?>">Admin Settings</a>
            </h2>

			<form method="post" action="options.php">
				<?php
					if ( $active_tab == 'display_settings' ) {
						settings_fields( 'wce_settings_option_group' );
						do_settings_sections( 'wce-settings-admin' );
						submit_button();
					} elseif ( $active_tab == 'admin_settings' ) {
						echo __( '<p>More Admin Options &mdash; including the "Assigned Messages" feature &mdash; will be coming soon in a future release!</p>', 'woo_custom_emails_domain' );
					}
				?>
			</form>
		</div>
	<?php }

	public function wce_settings_page_init() {
		register_setting(
			'wce_settings_option_group', // option_group
			'woocustomemails_settings_name', // option_name
			array( $this, 'wce_settings_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'wce_settings_setting_section', // id
			'Display Settings', // title
			array( $this, 'wce_settings_section_info' ), // callback
			'wce-settings-admin' // page
		);

		add_settings_field(
			'show_in_admin_email', // id
			'Include in Admin Emails', // title
			array( $this, 'show_in_admin_email_callback' ), // callback
			'wce-settings-admin', // page
			'wce_settings_setting_section' // section
		);

		add_settings_field(
			'display_classes', // id
			'Display for Other Product Types', // title
			array( $this, 'display_classes_callback' ), // callback
			'wce-settings-admin', // page
			'wce_settings_setting_section' // section
		);
	}

	public function wce_settings_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['show_in_admin_email'] ) ) {
			$sanitary_values['show_in_admin_email'] = $input['show_in_admin_email'];
		}

		if ( isset( $input['display_classes'] ) ) {
			$sanitary_values['display_classes'] = sanitize_text_field( $input['display_classes'] );
		}

		return $sanitary_values;
	}

	public function wce_settings_section_info() {
		echo __( 'Configure the display settings for Woo Custom Emails custom messages.', 'woo_custom_emails_domain' );
	}

	public function show_in_admin_email_callback() {
		printf(
			'<input type="checkbox" name="woocustomemails_settings_name[show_in_admin_email]" id="show_in_admin_email" value="show_in_admin_email" %s> <label for="show_in_admin_email">' . __( 'Show the Custom Messages content inside Admin order notification emails.', 'woo_custom_emails_domain' ) . '</label>',
			( isset( $this->wce_settings_options['show_in_admin_email'] ) && $this->wce_settings_options['show_in_admin_email'] === 'show_in_admin_email' ) ? 'checked' : ''
		);
	}

	public function display_classes_callback() {
		printf(
			'<textarea class="large-text" rows="5" name="woocustomemails_settings_name[display_classes]" id="display_classes">%s</textarea>',
			isset( $this->wce_settings_options['display_classes'] ) ? esc_attr( $this->wce_settings_options['display_classes']) : ''
		);
		// Description.
		echo '<div class="description" style="margin-top: 20px;">' . __( 'By default, WooCommerce only shows the \'Custom Emails\' tab for the standard \'<b>product</b>\' post type.<br>Use this option to specify other product post types which will show the Custom Emails tab. This should be a comma-separated list.', 'woo_custom_emails_domain' ) . '</div>';

		// Code example.
		echo '<div class="description" style="margin-top: 20px; margin-left: 30px;"><b>Example:</b><pre>show_if_booking, show_if_grouped</pre></div>';

		// Instructions to find CSS Class.
		echo '<div class="description" style="margin-top: 20px;">' . __( '<b>How to Find the CSS Class for a Custom Product</b><br>To find the class for your product type, do the following:', 'woo_custom_emails_domain' );
		echo '<ol>';
		echo __( '<li>Go to <b>Products</b>, and Edit a custom product</li>', 'woo_custom_emails_domain' );
		echo __( '<li>Scroll down to the <b>Product Data</b> table</li>', 'woo_custom_emails_domain' );
		echo __( '<li><b>Inspect the code</b> for a tab menu item, like <b>Inventory</b> (or any menu item which appears only for your product type)</li>', 'woo_custom_emails_domain' );
		echo __( '<li>Find the custom CSS class for your product type which is assigned to that tab menu item. These classes usually start with <b>show_if_</b> -- ex: "show_if_booking"</li>', 'woo_custom_emails_domain' );
		echo __( '<li>Copy and paste that CSS class into the field above</li>', 'woo_custom_emails_domain' );
		echo __( '<li>Save your settings</li>', 'woo_custom_emails_domain' );
		echo '</ol>';
		echo '</div>';

		// Note text.
		echo '<div style="margin-top: 20px; padding: 20px; border: 1px solid rgba(0,0,0,0.15);"><b style="color: #ff0000;">' . __( 'Please Note:</b> This feature is experimental and is not guaranteed to work.<br>Some WooCommerce add-ons use their own method of sending emails, outside of the normal WooCommerce process this plugin uses.', 'woo_custom_emails_domain' ) . '</div>';
	}

}
if ( is_admin() ) {
	$wce_settings = new Woo_Custom_Emails_Per_Product_Admin_Settings();
}

/*
 * Retrieve values with:
 * $wce_settings_options = get_option( 'woocustomemails_settings_name' ); // Array of All Options
 * $show_in_admin_email = $wce_settings_options['show_in_admin_email']; // Include in Admin Emails
 * $display_classes = $wce_settings_options['display_classes']; // Display for Other Product Types
 */
