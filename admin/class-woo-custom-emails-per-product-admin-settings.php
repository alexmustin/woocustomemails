<?php

class Woo_Custom_Emails_Per_Product_Admin_Settings {

	function __construct() {

        add_filter( 'manage_woocustomemails_posts_columns', array( $this, 'set_custom_edit_woocustomemails_columns' ) );
		add_action( 'manage_woocustomemails_posts_custom_column' , array( $this, 'custom_woocustomemails_column' ), 10, 2 );

		// Register Settings
		add_action( 'admin_init', array( $this, 'woocustomemails_settings_init' ) );

		// Add Settings page under WCE menu
		add_action( 'admin_menu', array( $this, 'add_woocustomemails_settings_menu' ) );

		// Save Settings
		add_action( 'load-woocustomemails_page_woocustomemails-settings', array( $this, 'woocustomemails_save_options' ) );

	}

    /**
	 * Creates a new custom post type
	 *
	 * @since 	2.0.0
	 * @access 	public
	 * @uses 	register_post_type()
	 */
	public static function new_cpt_wcemails() {

		$cap_type 	= 'post';
		$plural 	= 'Woo Custom Email Messages';
		$single 	= 'WCE Message';
		$cpt_name 	= 'woocustomemails';

		$opts['can_export']								= TRUE;
		$opts['capability_type']						= $cap_type;
		$opts['description']							= '';
		$opts['exclude_from_search']					= TRUE;
		$opts['has_archive']							= TRUE;
		$opts['hierarchical']							= FALSE;
		$opts['map_meta_cap']							= TRUE;
		$opts['menu_icon']								= 'dashicons-email-alt';
		$opts['menu_position']							= 50;
		$opts['public']									= FALSE;
		$opts['publicly_querable']						= FALSE;
		$opts['register_meta_box_cb']					= '';
		$opts['rewrite']								= FALSE;
		$opts['show_in_admin_bar']						= TRUE;
		$opts['show_in_menu']							= TRUE;
		$opts['show_in_nav_menu']						= TRUE;
		$opts['show_ui']								= TRUE;
		$opts['supports']								= array( 'title', 'editor', 'revisions' );
		$opts['taxonomies']								= array();

		$opts['capabilities']['delete_others_posts']	= "delete_others_{$cap_type}s";
		$opts['capabilities']['delete_post']			= "delete_{$cap_type}";
		$opts['capabilities']['delete_posts']			= "delete_{$cap_type}s";
		$opts['capabilities']['delete_private_posts']	= "delete_private_{$cap_type}s";
		$opts['capabilities']['delete_published_posts']	= "delete_published_{$cap_type}s";
		$opts['capabilities']['edit_others_posts']		= "edit_others_{$cap_type}s";
		$opts['capabilities']['edit_post']				= "edit_{$cap_type}";
		$opts['capabilities']['edit_posts']				= "edit_{$cap_type}s";
		$opts['capabilities']['edit_private_posts']		= "edit_private_{$cap_type}s";
		$opts['capabilities']['edit_published_posts']	= "edit_published_{$cap_type}s";
		$opts['capabilities']['publish_posts']			= "publish_{$cap_type}s";
		$opts['capabilities']['read_post']				= "read_{$cap_type}";
		$opts['capabilities']['read_private_posts']		= "read_private_{$cap_type}s";

		$opts['labels']['add_new']						= esc_html__( "Add New {$single}", 'woo_custom_emails_domain' );
		$opts['labels']['add_new_item']					= esc_html__( "Add New {$single}", 'woo_custom_emails_domain' );
		$opts['labels']['all_items']					= esc_html__( $plural, 'woo_custom_emails_domain' );
		$opts['labels']['edit_item']					= esc_html__( "Edit {$single}" , 'woo_custom_emails_domain' );
        $opts['labels']['menu_name']					= esc_html__( 'Custom Emails', 'woo_custom_emails_domain' );
		$opts['labels']['name']							= esc_html__( $plural, 'woo_custom_emails_domain' );
		$opts['labels']['name_admin_bar']				= esc_html__( $single, 'woo_custom_emails_domain' );
		$opts['labels']['new_item']						= esc_html__( "New {$single}", 'woo_custom_emails_domain' );
		$opts['labels']['not_found']					= esc_html__( "No {$plural} Found", 'woo_custom_emails_domain' );
		$opts['labels']['not_found_in_trash']			= esc_html__( "No {$plural} Found in Trash", 'woo_custom_emails_domain' );
		$opts['labels']['parent_item_colon']			= esc_html__( "Parent {$plural} :", 'woo_custom_emails_domain' );
		$opts['labels']['search_items']					= esc_html__( "Search {$plural}", 'woo_custom_emails_domain' );
		$opts['labels']['singular_name']				= esc_html__( $single, 'woo_custom_emails_domain' );
		$opts['labels']['view_item']					= esc_html__( "View {$single}", 'woo_custom_emails_domain' );

		$opts = apply_filters( 'woocustomemails-cpt-options', $opts );

		register_post_type( strtolower( $cpt_name ), $opts );

	} // new_cpt_wcemails()

    /**
	 * Add an "ID" column to the WCE Messages list
	 *
	 * @since  2.0.0
	 */
	public function set_custom_edit_woocustomemails_columns($columns) {

		// Remove the Title and Date columns now, add them back later
		unset($columns['title']);
		unset($columns['date']);

		// Add new "ID" column
		$columns['messageid'] = __( 'ID', 'woo_custom_emails_domain' );

		// Add the Title and Date columns back in
		$columns['title'] = __( 'Title', 'woo_custom_emails_domain' );
		$columns['date'] = __( 'Date', 'woo_custom_emails_domain' );

		return $columns;
	}

	/**
	 * Display the "ID" column data
	 *
	 * @since  2.0.0
	 */
	public function custom_woocustomemails_column( $column, $post_id ) {
		if ( $column == 'messageid') {
			$id = $post_id;
			echo $id;
		}
	}

    /**
    * Create a new custom link for the WCE Settings page
    *
    * @since 2.1.0
    */
	public function add_woocustomemails_settings_menu() {

        add_submenu_page(
            'edit.php?post_type=woocustomemails',
            __('WCE Settings','woo_custom_emails_domain'),
            __('WCE Settings','woo_custom_emails_domain'),
            'manage_options',
            'woocustomemails_settings_page',	// slug
            array(
				$this,
				'display_wce_settings_page'
			)
        );

	}

    /**
    * Show the WCE Settings page content
    *
    * @since 2.1.0
    */
	public function display_wce_settings_page() {
		$this->options = get_option( 'woocustomemails_settings_name' );
		?>
		<div class="wrap">

			<h1 class="wp-heading-inline">Woo Custom Emails Settings</h1>

			<form action="options.php" method="post">
	            <?php
	            settings_fields('woocustomemails_settings_group');
	            do_settings_sections('woocustomemails_settings_page');
	            submit_button('Save Settings');
	            ?>
	        </form>

		</div>

		<?php

	}

	/**
	* Register and add Settings
	*
	* @since 2.1.0
	*/
	public function woocustomemails_settings_init() {

		// Register settings
		register_setting(
	        'woocustomemails_settings_group', // Option group
	        'woocustomemails_settings_name', // Option name
	        array( $this, 'sanitize' ) // Sanitize
	    );

		// Display Settings section
	    add_settings_section(
	        'wce_display_settings_section', // ID
	        __('Display Settings', 'woo_custom_emails_domain'), // Title
	        array( $this, 'print_section_info' ), // Callback
	        'woocustomemails_settings_page' // Page
        );

		// Custom Content checkbox
	    add_settings_field(
	        'show_old_customcontent', // ID
	        __('Show Legacy Content', 'woo_custom_emails_domain'), // Title
	        array( $this, 'show_old_customcontent_callback' ), // Callback
	        'woocustomemails_settings_page', // Page
	        'wce_display_settings_section' // Section
        );

		// Display Classes field
		add_settings_field(
	        'display_classes', // ID
	        __('Extra Display Classes', 'woo_custom_emails_domain'), // Title
	        array( $this, 'display_classes_callback' ), // Callback
	        'woocustomemails_settings_page', // Page
	        'wce_display_settings_section' // Section
        );

	}

	/**
	* Sanitize each setting field as needed
	*
	* @param array $input Contains all settings fields as array keys
	*/
	public function sanitize( $input ) {
		$new_input = array();

		// Save 'Show Old Content'
		if( isset( $input['show_old_customcontent'] ) ) {
		    $new_input['show_old_customcontent'] = sanitize_text_field( $input['show_old_customcontent'] );
		}

		// Save 'Display Classes'
		if( isset( $input['display_classes'] ) && !empty( $input['display_classes'] ) ) {
		    $new_input['display_classes'] = sanitize_text_field( $input['display_classes'] );
		} else {
			$new_input['display_classes'] = '';
		}

		return $new_input;
	}

	/**
	* Print the Section text
	*/
	public function print_section_info() {
		echo __( 'Select the Woo Custom Emails Display settings.', 'woo_custom_emails_domain' );
	}

	/**
	* Display the 'Show Version 1x Content' checkbox
	*/
	public function show_old_customcontent_callback() {
		$checked = '';
		$show_old_content = '';
		if( ! isset( $this->options['show_old_customcontent'] ) ) {
			// Data not set
			$this->options['show_old_customcontent'] = false;
		} else {
			// Data is set
			$checked = 'checked';
			$show_old_content = $this->options['show_old_customcontent'];
		}
	    printf( '<input type="checkbox" id="show_old_customcontent" name="woocustomemails_settings_name[show_old_customcontent]" value="1" %s />', $checked );
		echo '<br><span class="description">' . __( 'Adds a section in the Product Data "Custom Emails" Tab to display your legacy Custom Content from previous versions. You can use this tool to Copy/Paste older email content into a new WCE Message.', 'woo_custom_emails_domain' ) . '</span>';
	}

	/**
	* Display the 'Display Classes' field
	*/
	public function display_classes_callback() {
		$display_classes_setting = '';

		if( isset( $this->options['display_classes'] ) && !empty( $this->options['display_classes'] ) ) {
			// Data is set
			$display_classes_setting = $this->options['display_classes'];
		}
		printf(
			'<textarea id="display_classes" name="woocustomemails_settings_name[display_classes]" class="fullwidth-text">%s</textarea>', $display_classes_setting );
		echo '<br><span class="description">' . __( 'Allows you to specify which add-on product types WCE can be used with. Should be a comma-separated list. Example: show_if_booking, show_if_grouped', 'woo_custom_emails_domain' ) . '</span>';
	}

}
