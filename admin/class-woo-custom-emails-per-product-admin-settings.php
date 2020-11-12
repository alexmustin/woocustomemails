<?php

class Woo_Custom_Emails_Per_Product_Admin_Settings {

	function __construct() {

        add_filter( 'manage_woocustomemails_posts_columns', array( $this, 'set_custom_edit_woocustomemails_columns' ) );
		add_action( 'manage_woocustomemails_posts_custom_column' , array( $this, 'custom_woocustomemails_column' ), 10, 2 );

		// Register Settings
		add_action( 'admin_init', array( $this, 'woocustomemails_settings_init' ) );

		// Add 'Assigned Messages' page under WCE menu
		add_action( 'admin_menu', array( $this, 'add_woocustomemails_assignedmessages_menu' ) );

		// Add 'Settings' page under WCE menu
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

	public function add_woocustomemails_assignedmessages_menu() {

		$wce_assignedmessages_menu = add_submenu_page(
            'edit.php?post_type=woocustomemails',
            __('Assigned Messages','woo_custom_emails_domain'), // page title
            __('Assigned Messages','woo_custom_emails_domain'), // menu title
            'manage_options', // capability
            'woocustomemails_assigned', // menu slug
            array( $this, 'display_wce_assigned_page' )
        );

		add_action( 'admin_print_styles-' . $wce_assignedmessages_menu, 'wce_custom_admin_css' );

		function wce_custom_admin_css() {
			wp_enqueue_style( 'wce-admin-styles', plugins_url( '/woocustomemails-admin-styles.css', __FILE__ ) );
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
    * Show the WCE Assigned Messages page content
    *
    * @since 2.2.6
    */
	public function display_wce_assigned_page() {
		?>
		<div class="wrap">

			<h1 class="wp-heading-inline">Woo Custom Emails - Assigned Messages</h1>

			<?php
			// Setup query arguments.
			$args = array(
                'post_type'            => 'product',
                'ignore_sticky_posts'  => 1,
                'no_found_rows'        => 1,
                // 'posts_per_page'       => -1,
                'orderby'              => 'name', // (string) - Order posts by: name, date, rand.
                'order'                => 'asc', // (string) - Post order: asc, desc.
                'meta_query'           => array(
					'relation' => 'OR',
                    array(
                        'key' => 'wcemessage_id_onhold',
						'value' => '',
                        'compare' => '!=',
                    ),
					array(
                        'key' => 'wcemessage_id_processing',
						'value' => '',
                        'compare' => '!=',
                    ),
					array(
                        'key' => 'wcemessage_id_completed',
						'value' => '',
                        'compare' => '!=',
                    ),
                ),
            );

	        // Create query object.
	        $query = new WP_Query( $args );

			$total_posts = $query->post_count;

			$output .= '<div class="post-count">';
			$output .= '<p>You have <b>' . $total_posts . '</b> products with WCE Messages assigned.';

			// Message for 0 found products.
			if ( $total_posts < 1 ) {
				$output .= ' You can assign a WCE Message under the "Messages" tab when editing a product. <a href="' . get_bloginfo('url') . '/wp-admin/edit.php?post_type=product">See All Products &rarr;</a>';
			}

			$output .= '</p></div>';

			// Open HTML output.
        	$output .= '<div class="assigned-wce-messages">';

			// If query object has posts...
        	if ( $query->have_posts() ) {

				// Open data table.
				$output .= '<table>';
				$output .= '<thead class="header">';

				// Product Count column.
				$output .= '<td class="product-count">#</td>';

				// Product column.
				$output .= '<td class="product-title">Product</td>';

				// Assigned Message Title column.
				$output .= '<td class="assigned-message-title">Assigned WCE Messages</td>';

				// Assigned Message Order Status column.
				$output .= '<td class="assigned-message-orderstatus">Order Status</td>';

				// Assigned Message Template Location column.
				$output .= '<td class="assigned-message-templatelocation">Location</td>';

				$output .= '</thead>';
				$output .= '<tbody>';

				$count = 1;

				// While query has posts...
            	while( $query->have_posts() ) {

					// Assign query object.
                	$query->the_post();

					// Assign global vars.
					global $product, $post;

					// Assign total count.
					$total_posts = $query->post_count;

					// Assign vars.
	                $this_id = get_the_ID();
					$this_thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $this_id ), 'thumbnail' );
	                $this_title = $query->post->post_title;
	                $this_productlink = get_edit_post_link( $this_id );

					$wcemessage_id_onhold =
						$wcemessage_id_processing =
						$wcemessage_id_completed =
						$wcemessage_location_onhold =
						$wcemessage_location_processing =
						$wcemessage_location_completed =
						$wce_onhold_message_title =
						$wce_onhold_message_editURL =
						$wce_processing_message_title =
						$wce_processing_message_editURL =
						$wce_completed_message_title =
						$wce_completed_message_editURL =
						$this_wcemessage_id_onhold =
						$this_wcemessage_id_processing =
						$this_wcemessage_id_completed =
						$this_orderstatus_onhold =
						$this_orderstatus_processing =
						$this_orderstatus_completed =
						$this_location_onhold =
						$this_location_processing =
						$this_location_completed = '';


					// Get WCE Message meta.
					$wcemessage_id_onhold = (int) get_post_meta( $this_id, 'wcemessage_id_onhold', true );
					$wcemessage_location_onhold = get_post_meta( $this_id, 'location_onhold', true );
					$wcemessage_id_processing = (int) get_post_meta( $this_id, 'wcemessage_id_processing', true );
					$wcemessage_location_processing = get_post_meta( $this_id, 'location_processing', true );
					$wcemessage_id_completed = (int) get_post_meta( $this_id, 'wcemessage_id_completed', true );
					$wcemessage_location_completed = get_post_meta( $this_id, 'location_completed', true );

					// Check for On-Hold content.
					if ( $wcemessage_id_onhold > 0 ) {
						$this_orderstatus_onhold = '<span class="on-hold">On Hold</span>';
						$this_wcemessage_id_onhold = $wcemessage_id_onhold;
						$wce_onhold_message_title = get_the_title( $this_wcemessage_id_onhold );
						$wce_onhold_message_editURL = get_edit_post_link( $this_wcemessage_id_onhold );
					}

					// Check for Processing content.
					if ( $wcemessage_id_processing > 0 ) {
						$this_orderstatus_processing = '<span class="processing">Processing</span>';
						$this_wcemessage_id_processing = $wcemessage_id_processing;
						$wce_processing_message_title = get_the_title( $this_wcemessage_id_processing );
						$wce_processing_message_editURL = get_edit_post_link( $this_wcemessage_id_processing );
					}

					// Check for Completed content.
					if ( $wcemessage_id_completed > 0 ) {
						$this_orderstatus_completed = '<span class="completed">Completed</span>';
						$this_wcemessage_id_completed = $wcemessage_id_completed;
						$wce_completed_message_title = get_the_title( $this_wcemessage_id_completed );
						$wce_completed_message_editURL = get_edit_post_link( $this_wcemessage_id_completed );
					}

					switch ( $wcemessage_location_onhold ) {
		                case 'woocommerce_email_before_order_table':
							$this_location_onhold = 'Before Order Table';
		                    break;
						case 'woocommerce_email_after_order_table':
							$this_location_onhold = 'After Order Table';
		                    break;
						case 'woocommerce_email_order_meta':
							$this_location_onhold = 'After Order Meta';
		                    break;
						case 'woocommerce_email_customer_details':
							$this_location_onhold = 'After Customer Details';
							break;
		            }

					switch ( $wcemessage_location_processing ) {
		                case 'woocommerce_email_before_order_table':
							$this_location_processing = 'Before Order Table';
		                    break;
						case 'woocommerce_email_after_order_table':
							$this_location_processing = 'After Order Table';
		                    break;
						case 'woocommerce_email_order_meta':
							$this_location_processing = 'After Order Meta';
		                    break;
						case 'woocommerce_email_customer_details':
							$this_location_processing = 'After Customer Details';
							break;
		            }

					switch ( $wcemessage_location_completed ) {
		                case 'woocommerce_email_before_order_table':
							$this_location_completed = 'Before Order Table';
		                    break;
						case 'woocommerce_email_after_order_table':
							$this_location_completed = 'After Order Table';
		                    break;
						case 'woocommerce_email_order_meta':
							$this_location_completed = 'After Order Meta';
		                    break;
						case 'woocommerce_email_customer_details':
							$this_location_completed = 'After Customer Details';
							break;
		            }

					// Open table row.
					$output .= '<tr class="product">';

					// Product Count column.
					$output .= '<td class="product-count"> '. $count . '</td>';

					// Product Title column.
					$output .= '<td class="product-title"><a href="' . $this_productlink . '" target="_blank"><img src="' . $this_thumb[0] . '" class="thumb" />' . $this_title . '</a></td>';

					// Assigned Messages column.
					$output .= '<td class="assigned-message-title">';

					// ON-HOLD.
					if ( $this_wcemessage_id_onhold ) {
						$output .= '<div class="on-hold">';
						$output .= '<a href="' . $wce_onhold_message_editURL . '" target="_blank">' . $wce_onhold_message_title . '</a>';
						$output .= '</div>';
					}

					// PROCESSING.
					if ( $this_wcemessage_id_processing ) {
						$output .= '<div class="processing">';
						$output .= '<a href="' . $wce_processing_message_editURL . '" target="_blank">' . $wce_processing_message_title . '</a>';
						$output .= '</div>';
					}

					// COMPLETED.
					if ( $this_wcemessage_id_completed ) {
						$output .= '<div class="completed">';
						$output .= '<a href="' . $wce_completed_message_editURL . '" target="_blank">' . $wce_completed_message_title . '</a>';
						$output .= '</div>';
					}

					$output .= '</td>';

					// Order Status column.
					$output .= '<td class="assigned-message-orderstatus">';

					// ON-HOLD.
					if ( $this_wcemessage_id_onhold ) {
						$output .= '<div class="on-hold">' . $this_orderstatus_onhold . '</div>';
					}

					// PROCESSING.
					if ( $this_wcemessage_id_processing ) {
						$output .= '<div class="processing">' . $this_orderstatus_processing . '</div>';
					}

					// COMPLETED.
					if ( $this_wcemessage_id_completed ) {
						$output .= '<div class="completed">' . $this_orderstatus_completed . '</div>';
					}

					$output .= '</td>';

					// Location column.
					$output .= '<td class="assigned-message-templatelocation">';

					// $output .= $this_templatelocation;

					// ON-HOLD.
					if ( $this_wcemessage_id_onhold ) {
						$output .= '<div class="on-hold">' . $this_location_onhold . '</div>';
					}

					// PROCESSING.
					if ( $this_wcemessage_id_processing ) {
						$output .= '<div class="processing">' . $this_location_processing . '</div>';
					}

					// COMPLETED.
					if ( $this_wcemessage_id_completed ) {
						$output .= '<div class="completed">' . $this_location_completed . '</div>';
					}

					$output .= '</td>';

					// Close table row.
					$output .= '</tr>';

					$count++;

				}

				// Close table body.
				$output .= '</tbody>';

				// Close data table.
				$output .= '</table>';

				// Reset wp query.
            	wp_reset_postdata();

			} else {

				$output .= '<p class="alert"><b>Sorry, no Products found with assigned WCE Messages.</b></p>';

			}

			$output .= '</div><!-- // end .assigned-wce-messages // -->';

			echo $output;
			?>

		</div><!-- // end .wrap // -->
		<?php
	}

	/**
	* Register and add Settings
	*
	* @since 2.1.0
	*/
	public function woocustomemails_settings_init() {

		// Register Settings
		register_setting(
	        'woocustomemails_settings_group', // Option group
	        'woocustomemails_settings_name', // Option name
	        array( $this, 'sanitize' ) // Sanitize
	    );

		// Register Section -- Display Settings
	    add_settings_section(
	        'wce_display_settings_section', // ID
	        __('Display Settings', 'woo_custom_emails_domain'), // Title
	        array( $this, 'print_section_info' ), // Callback
	        'woocustomemails_settings_page' // Page
        );

		// Field - Include Custom Message in Admin Emails
		add_settings_field(
	        'show_in_admin_email', // ID
	        __('Include Custom Message in Admin Emails', 'woo_custom_emails_domain'), // Title
	        array( $this, 'show_in_admin_email_callback' ), // Callback
	        'woocustomemails_settings_page', // Page
	        'wce_display_settings_section' // Section
        );

		// Field - Display Classes
		add_settings_field(
	        'display_classes', // ID
	        __('Extra Product Display Classes', 'woo_custom_emails_domain'), // Title
	        array( $this, 'display_classes_callback' ), // Callback
	        'woocustomemails_settings_page', // Page
	        'wce_display_settings_section' // Section
        );

		// Field - Old 1.x Legacy Content
	    add_settings_field(
	        'show_old_customcontent', // ID
	        __('Show Old 1.x Legacy Content', 'woo_custom_emails_domain'), // Title
	        array( $this, 'show_old_customcontent_callback' ), // Callback
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

		// Save 'Include Custom Message in Admin Emails'
		if( isset( $input['show_in_admin_emails'] ) ) {
		    $new_input['show_in_admin_emails'] = sanitize_text_field( $input['show_in_admin_emails'] );
		}

		// Save 'Display Classes'
		if( isset( $input['display_classes'] ) && !empty( $input['display_classes'] ) ) {
		    $new_input['display_classes'] = sanitize_text_field( $input['display_classes'] );
		} else {
			$new_input['display_classes'] = '';
		}

		// Save 'Show Old 1.x Content'
		if( isset( $input['show_old_customcontent'] ) ) {
		    $new_input['show_old_customcontent'] = sanitize_text_field( $input['show_old_customcontent'] );
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
	* Display the 'Include Custom Message in Admin Emails' field
	*/
	public function show_in_admin_email_callback() {
		$checked = '';
		$show_in_admin_emails = '';
		if( ! isset( $this->options['show_in_admin_emails'] ) ) {
			// Data not set
			$this->options['show_in_admin_emails'] = false;
		} else {
			// Data is set
			$checked = 'checked';
			$show_in_admin_emails = $this->options['show_in_admin_emails'];
		}
		printf( '<input type="checkbox" id="show_in_admin_emails" name="woocustomemails_settings_name[show_in_admin_emails]" value="1" %s />', $checked );
		echo '<br><span class="description">' . __( 'Add the Custom Messages to Admin emails.', 'woo_custom_emails_domain' ) . '</span>';
	}

	/**
	* Display the 'Extra Display Classes' field
	*/
	public function display_classes_callback() {
		$display_classes_setting = '';

		if( isset( $this->options['display_classes'] ) && !empty( $this->options['display_classes'] ) ) {
			// Data is set
			$display_classes_setting = $this->options['display_classes'];
		}
		printf( '<textarea id="display_classes" name="woocustomemails_settings_name[display_classes]" class="fullwidth-text">%s</textarea>', $display_classes_setting );
		echo '<br><span class="description">' . __( 'Allows you to specify other non-default product types which can be used with WCEPP.<br>Should be a comma-separated list.<br><b>Example:</b><pre>show_if_booking, show_if_grouped</pre>', 'woo_custom_emails_domain' ) . '</span>';
	}

	/**
	* Display the 'Show Old 1.x Legacy Content' checkbox
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
		echo '<br><span class="description">' . __( 'Adds a section in the Product Data "Custom Emails" Tab to display content from older 1.x versions of the plugin. You can use this tool to Copy/Paste older email content into a new WCE Message.', 'woo_custom_emails_domain' ) . '</span>';
	}

}
