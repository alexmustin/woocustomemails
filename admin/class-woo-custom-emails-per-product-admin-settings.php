<?php

class Woo_Custom_Emails_Per_Product_Admin_Settings {

	/*
	 * For easier overriding we declared the keys
	 * here as well as our tabs array which is populated
	 * when registering settings
	 */
	private $display_settings_key = 'woocustomemails_settings_name';
	private $admin_options_key = 'wcepp_admin_options';
	private $plugin_settingspage_key = 'woocustomemails_settings_page';
	private $plugin_settings_tabs = array();

	public function __construct() {

		// Init settings.
		add_action( 'init', array( &$this, 'load_settings' ) );

		// Register Settings Sections.
		add_action( 'admin_init', array( &$this, 'register_display_settings' ) );
		add_action( 'admin_init', array( &$this, 'register_admin_options' ) );

		// Add the 'Settings' page under the WCE menu.
		add_action( 'admin_menu', array( &$this, 'add_woocustomemails_settings_menu' ) );

	}

	/*
	 * Loads both the Display Settings and Admin Options from
	 * the database into their respective arrays. Uses
	 * array_merge to merge with default values if they're
	 * missing.
	 */
	public function load_settings() {
		$this->display_settings = (array) get_option( $this->display_settings_key );
		$this->admin_options = (array) get_option( $this->admin_options_key );

		// Merge with defaults
		$this->display_settings = array_merge( array(
			'show_in_admin_email' => '',
			'display_classes' => '',
		), $this->display_settings );

		$this->admin_options = array_merge( array(
			// 'advanced_option' => 'Advanced value',
		), $this->admin_options );
	}

	/*
	 * Registers the Display Settings via the Settings API,
	 * appends the setting to the tabs array of the object.
	 */
	public function register_display_settings() {
		$this->plugin_settings_tabs[$this->display_settings_key] = 'Display Settings';

		// Register the setting.
		register_setting(
			'woocustomemails_settings_group',
			$this->display_settings_key
		);

		// Add the section.
		add_settings_section(
			'wce_display_settings_section',
			'Display Settings',
			array( &$this, 'section_display_settings_desc' ),
			$this->display_settings_key
		);

		// Add 'Show In Admin Emails' field.
		add_settings_field(
			'show_in_admin_email',
			__('Include Custom Messages in Admin Emails', 'woo_custom_emails_domain'),
			array( &$this, 'field_showinadminemails' ),
			$this->display_settings_key,
			'wce_display_settings_section'
		);

		// hr Separator.
		add_settings_field(
			'hr-1',
			'<hr>',
			array( &$this, 'output_hr' ),
			$this->display_settings_key,
			'wce_display_settings_section'
		);

		// Add 'Display Classes' field.
		add_settings_field(
			'display_classes',
			__('Display for Other Product Types', 'woo_custom_emails_domain'),
			array( &$this, 'field_displayclasses' ),
			$this->display_settings_key,
			'wce_display_settings_section'
		);
	}

	// Outputs a horizontal rule.
	public function output_hr() {
		echo '<hr>';
	}

	/*
	 * Registers the Admin Options and appends the
	 * key to the plugin settings tabs array.
	 */
	public function register_admin_options() {
		$this->plugin_settings_tabs[$this->admin_options_key] = 'Admin Options';

		// Register the setting.
		register_setting(
			$this->admin_options_key,
			$this->admin_options_key
		);

		// Add a section.
		add_settings_section(
			'section_admin',
			'Admin Options',
			array( &$this, 'section_admin_desc' ),
			$this->admin_options_key
		);

		// // Add a field.
		// add_settings_field(
		// 	'advanced_option',
		// 	'An Advanced Option',
		// 	array( &$this, 'field_advanced_option' ),
		// 	$this->admin_options_key,
		// 	'section_admin'
		// );
	}

	/*
	 * The following methods provide descriptions
	 * for their respective sections, used as callbacks
	 * with add_settings_section
	 */
	public function section_display_settings_desc() {
		echo __( 'Configure the display settings for Woo Custom Emails custom messages.', 'woo_custom_emails_domain' );
	}

	public function section_admin_desc() {
		echo __( 'More Admin Options &mdash; including the "Assigned Messages" feature &mdash; will be coming soon in a future release!', 'woo_custom_emails_domain' );
	}

	/*
	 * Display Setting field callback, renders a
	 * text input, note the name and value.
	 */
	public function field_showinadminemails() {
		$checked = '';
		$show_in_admin_emails = '';

		if ( empty ( $this->display_settings['show_in_admin_email'] ) ) {
			// Data not set
			// $show_in_admin_emails = false;
		} else {
			// Data is set
			$checked = 'checked';
			// $show_in_admin_emails = $this->display_settings['show_in_admin_email'];
		}
		// var_dump($show_in_admin_emails);
		?>
		<input type="checkbox" id="show_in_admin_email" name="<?php echo $this->display_settings_key; ?>[show_in_admin_email]" value="1" <?php echo $checked; ?> /> <b><?php echo __( 'Include in Admin Emails', 'woo_custom_emails_domain' ); ?></b><br>
		<?php
		echo '<p>' . __( 'Show the Custom Messages content inside Admin order notification emails.', 'woo_custom_emails_domain' ) . '</p>';

		// $options = get_option( 'woocustomemails_settings_name' );
		// $old_show_in_admin_email_setting = $options['show_in_admin_email'];
		// echo '<p>'.$old_show_in_admin_email_setting.'</p>';

	}

	/*
	 * Display Setting field callback, renders a
	 * text input, note the name and value.
	 */
	public function field_displayclasses() {
		$dclasses = $this->display_settings['display_classes'];
		// var_dump($dclasses);
		?>
		<textarea id="display_classes" name="<?php echo $this->display_settings_key; ?>[display_classes]" class="fullwidth-text"><?php echo esc_attr( $this->display_settings['display_classes'] ); ?></textarea>
		<?php
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

		// $options = get_option( 'woocustomemails_settings_name' );
		// $old_classes_setting = $options['display_classes'];
		// echo '<p>'.$old_classes_setting.'</p>';
	}

	// public function field_advanced_option() {
		// // settings go here!
	// }

    /**
    * Create a new custom link for the WCE Settings page
    *
    * @since 2.1.0
    */
	public function add_woocustomemails_settings_menu() {

        add_submenu_page(
            'edit.php?post_type=woocustomemails',
            __('WCE Settings','woo_custom_emails_domain'), // page title
            __('WCE Settings','woo_custom_emails_domain'), // menu title
            'manage_options', // capability
            'woocustomemails_settings_page', // menu slug
            array( $this, 'display_wce_settings_page' )
        );

	}

    /**
    * Show the WCE Settings page content
    *
    * @since 2.1.0
    */
	public function display_wce_settings_page() {
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/woocustomemails-admin-settings-page-form.php';
	}

	/*
	 * Renders our tabs in the plugin options page,
	 * walks through the object's tabs array and prints
	 * them one by one. Provides the heading for the
	 * plugin_options_page method.
	 */
	public function plugin_options_tabs() {
		$current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $this->display_settings_key;

		// screen_icon();
		echo '<h2 class="nav-tab-wrapper">';
		foreach ( $this->plugin_settings_tabs as $tab_key => $tab_caption ) {
			$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
			echo '<a class="nav-tab ' . $active . '" href="?post_type=woocustomemails&page=' . $this->plugin_settingspage_key . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';
		}
		echo '</h2>';
	}

	/**
	* Sanitize each setting field as needed
	*
	* @param array $input Contains all settings fields as array keys
	*/
	public function sanitize( $input ) {
		$new_input = array();

		// Save 'Include Custom Message in Admin Emails'
		if ( isset( $input['show_in_admin_email'] ) ) {
		    $new_input['show_in_admin_email'] = sanitize_text_field( $input['show_in_admin_email'] );
		} else {
			$new_input['show_in_admin_email'] = '';
		}

		// Save 'Display Classes'
		if ( isset( $input['display_classes'] ) ) {
		    $new_input['display_classes'] = sanitize_text_field( $input['display_classes'] );
		} else {
			$new_input['display_classes'] = '';
		}

		return $new_input;
	}

// -------------------------------------------------------------------------- //
// -------------------------------------------------------------------------- //
// -------------------------------------------------------------------------- //


	// /**
	// * Register and add Settings
	// *
	// * @since 2.1.0
	// */
	// public function woocustomemails_settings_init() {
	//
	// 	// Register Settings
	// 	register_setting(
	//         'woocustomemails_settings_group', // Option group
	//         'woocustomemails_settings_name', // Option name
	//         array( $this, 'sanitize' ) // Sanitize
	//     );
	//
	// 	// Show Section: Admin Options.
	// 	add_settings_section(
	// 		'wce_admin_options_section', // ID
	// 		'Admin Options', // Title
	// 		array( $this, 'print_admin_section_info' ), // Callback
	// 		'woocustomemails_settings_page' // Page
	// 	);
	//
	// 	// Show Section: Display Settings.
	// 	add_settings_section(
	// 		'wce_display_settings_section', // ID
	// 		'Display Settings', // Title
	// 		array( $this, 'print_display_section_info' ), // Callback
	// 		'woocustomemails_settings_page' // Page
	// 	);
	//
	// 	// Display the sections based on the active tab.
    //     if ( isset( $_GET["tab"] ) ) {
	//
    //         if ( $_GET["tab"] == "admin-options" ) {
	//
	// 			// // Field - Add Test Data.
	// 			// add_settings_field(
	// 		    //     'add_testdata_row', // ID
	// 		    //     __('Add Test Data', 'woo_custom_emails_domain'), // Title
	// 		    //     array( $this, 'add_testrow_callback' ), // Callback
	// 		    //     'woocustomemails_settings_page', // Page
	// 		    //     'wce_admin_options_section' // Section
	// 	        // );
	//
    //         } else {
	//
	// 			// Field - Include Custom Message in Admin Emails
	// 			add_settings_field(
	// 		        'show_in_admin_email', // ID
	// 		        __('Include Custom Message in Admin Emails', 'woo_custom_emails_domain'), // Title
	// 		        array( $this, 'show_in_admin_email_callback' ), // Callback
	// 		        'woocustomemails_settings_page', // Page
	// 		        'wce_display_settings_section' // Section
	// 	        );
	//
	// 			// SEPARATOR
	// 			add_settings_field(
	// 		        'separator-1', // ID
	// 		        '', // Title
	// 		        function(){ echo '<hr>'; }, // Callback
	// 		        'woocustomemails_settings_page', // Page
	// 		        'wce_display_settings_section' // Section
	// 	        );
	//
	// 			// Field - Display Classes
	// 			add_settings_field(
	// 		        'display_classes', // ID
	// 		        __('Display for Other Product Types', 'woo_custom_emails_domain'), // Title
	// 		        array( $this, 'display_classes_callback' ), // Callback
	// 		        'woocustomemails_settings_page', // Page
	// 		        'wce_display_settings_section' // Section
	// 	        );
	//
    //         }
	//
    //     } else {
	//
	// 		// Field - Include Custom Message in Admin Emails
	// 		add_settings_field(
	// 			'show_in_admin_email', // ID
	// 			__('Include Custom Message in Admin Emails', 'woo_custom_emails_domain'), // Title
	// 			array( $this, 'show_in_admin_email_callback' ), // Callback
	// 			'woocustomemails_settings_page', // Page
	// 			'wce_display_settings_section' // Section
	// 		);
	//
	// 		// SEPARATOR
	// 		add_settings_field(
	// 			'separator-1', // ID
	// 			'', // Title
	// 			function(){ echo '<hr>'; }, // Callback
	// 			'woocustomemails_settings_page', // Page
	// 			'wce_display_settings_section' // Section
	// 		);
	//
	// 		// Field - Display Classes
	// 		add_settings_field(
	// 			'display_classes', // ID
	// 			__('Display for Other Product Types', 'woo_custom_emails_domain'), // Title
	// 			array( $this, 'display_classes_callback' ), // Callback
	// 			'woocustomemails_settings_page', // Page
	// 			'wce_display_settings_section' // Section
	// 		);
	//
    //     }
	//
	// }
	//
	// /**
	// * Sanitize each setting field as needed
	// *
	// * @param array $input Contains all settings fields as array keys
	// */
	// public function sanitize( $input ) {
	// 	$new_input = array();
	//
	// 	// Save 'Include Custom Message in Admin Emails'
	// 	if ( isset( $input['show_in_admin_email'] ) ) {
	// 	    $new_input['show_in_admin_email'] = sanitize_text_field( $input['show_in_admin_email'] );
	// 	} else {
	// 		$new_input['show_in_admin_email'] = '';
	// 	}
	//
	// 	// Save 'Display Classes'
	// 	if ( isset( $input['display_classes'] ) ) {
	// 	    $new_input['display_classes'] = sanitize_text_field( $input['display_classes'] );
	// 	} else {
	// 		$new_input['display_classes'] = '';
	// 	}
	//
	// 	return $new_input;
	// }
	//
	// /**
	// * Print the Display Section text
	// */
	// public function print_display_section_info() {
	// 	echo '<p style="margin-top: 20px;">' . __( 'Configure the display settings for Woo Custom Emails custom messages.', 'woo_custom_emails_domain' ) . '</p>';
	// }
	//
	// // Display Admin Options section info.
	// public function print_admin_section_info() {
	// 	echo '<p style="margin-top: 20px;">Admin Options and the "Assigned Messages" feature will be coming soon in a future release!</p>';
	// }
	//
	// /**
	// * Display the 'Include Custom Message in Admin Emails' field
	// */
	// public function show_in_admin_email_callback() {
	// 	$checked = '';
	// 	$show_in_admin_emails = '';
	// 	if( ! isset( $this->options['show_in_admin_email'] ) ) {
	// 		// Data not set
	// 		$this->options['show_in_admin_email'] = false;
	// 	} else {
	// 		// Data is set
	// 		$checked = 'checked';
	// 		$show_in_admin_emails = $this->options['show_in_admin_email'];
	// 	}
	// 	printf( '<input type="checkbox" id="show_in_admin_email" name="woocustomemails_settings_name[show_in_admin_email]" value="1" %s /><b>%s</b>', $checked, __( 'Include in Admin Emails', 'woo_custom_emails_domain' ) );
	// 	echo '<br><span class="description">' . __( 'Show the Custom Messages content inside Admin order notification emails.', 'woo_custom_emails_domain' ) . '</span>';
	// }
	//
	// /**
	// * Display the 'Extra Display Classes' field
	// */
	// public function display_classes_callback() {
	// 	$display_classes_setting = '';
	//
	// 	if( isset( $this->options['display_classes'] ) && !empty( $this->options['display_classes'] ) ) {
	// 		// Data is set
	// 		$display_classes_setting = $this->options['display_classes'];
	// 	}
	// 	printf( '<textarea id="display_classes" name="woocustomemails_settings_name[display_classes]" class="fullwidth-text">%s</textarea>', $display_classes_setting );
	//
	// 	// Description.
	// 	echo '<div class="description" style="margin-top: 20px;">' . __( 'By default, WooCommerce <b>only</b> shows the \'Custom Emails\' tab for the standard \'product\' post type.<br>Use this option to specify other product post types which will show the Custom Emails tab. This should be a comma-separated list.', 'woo_custom_emails_domain' ) . '</div>';
	//
	// 	// Code example;
	// 	echo '<div class="description" style="margin-top: 20px; margin-left: 30px;"><b>Example:</b><pre>show_if_booking, show_if_grouped</pre></div>';
	//
	// 	// Instructions to find CSS Class.
	// 	echo '<div class="description" style="margin-top: 20px;">' . __( '<b>How to Find the CSS Class for a Custom Product</b><br>To find the class for your product type, do the following:', 'woo_custom_emails_domain' );
	// 	echo '<ol>';
	// 	echo __( '<li>Go to <b>Products</b>, and Edit a custom product</li>', 'woo_custom_emails_domain' );
	// 	echo __( '<li>Scroll down to the <b>Product Data</b> table</li>', 'woo_custom_emails_domain' );
	// 	echo __( '<li><b>Inspect the code</b> for a tab menu item, like <b>Inventory</b> (or any menu item which appears only for your product type)</li>', 'woo_custom_emails_domain' );
	// 	echo __( '<li>Find the custom CSS class for your product type which is assigned to that tab menu item. These classes usually start with <b>show_if_</b> -- ex: "show_if_booking"</li>', 'woo_custom_emails_domain' );
	// 	echo __( '<li>Copy and paste that CSS class into the field above</li>', 'woo_custom_emails_domain' );
	// 	echo __( '<li>Save your settings</li>', 'woo_custom_emails_domain' );
	// 	echo '</ol>';
	// 	echo '</div>';
	//
	// 	// Note text.
	// 	echo '<div style="margin-top: 20px; padding: 20px; border: 1px solid rgba(0,0,0,0.15);"><b style="color: #ff0000;">' . __( 'Please Note:</b> This feature is experimental and is not guaranteed to work.<br>Some WooCommerce add-ons use their own method of sending emails, outside of the normal WooCommerce process this plugin uses.', 'woo_custom_emails_domain' ) . '</div>';
	// }

}

add_action( 'plugins_loaded', function() { new Woo_Custom_Emails_Per_Product_Admin_Settings(); } );
