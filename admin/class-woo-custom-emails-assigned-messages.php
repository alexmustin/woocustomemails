<?php
/**
 * The WCE Assigned Messages settings page.
 *
 * @since 2.2.6
*/

// Load table class from WP core.
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

// Create reusable Class.
class WCE_AssignedMessagesTable extends WP_List_Table {

    // Define found_data array.
    public $found_data = array();

    /*
     * Function to get WCE Message Statuses.
     */
    public function get_wcemessage_statuses( $this_id ) {

        $assignedmsgs = '';

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

        // Determine Location string for On-Hold status.
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

        // Determine Location string for Processing status.
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

        // Determine Location string for Completed status.
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

        $messages = array();
        $statuses = array();
        $locations = array();

        /* MESSAGES
        ----------------------------------- */
        // ON-HOLD.
        if ( $this_wcemessage_id_onhold ) {
            $messages[] = '<div class="on-hold"><span><a href="' . $wce_onhold_message_editURL . '" target="_blank">' . $wce_onhold_message_title . '</a></span></div>';
        }

        // PROCESSING.
        if ( $this_wcemessage_id_processing ) {
            $messages[] = '<div class="processing"><span><a href="' . $wce_processing_message_editURL . '" target="_blank">' . $wce_processing_message_title . '</a></span></div>';
        }

        // COMPLETED.
        if ( $this_wcemessage_id_completed ) {
            $messages[] = '<div class="completed"><span><a href="' . $wce_completed_message_editURL . '" target="_blank">' . $wce_completed_message_title . '</a></span></div>';
        }

        /* STATUSES
        ----------------------------------- */
        // ON-HOLD.
        if ( $this_wcemessage_id_onhold ) {
            $statuses[] = '<div class="on-hold">' . $this_orderstatus_onhold . '</div>';
        }

        // PROCESSING.
        if ( $this_wcemessage_id_processing ) {
            $statuses[] = '<div class="processing">' . $this_orderstatus_processing . '</div>';
        }

        // COMPLETED.
        if ( $this_wcemessage_id_completed ) {
            $statuses[] = '<div class="completed">' . $this_orderstatus_completed . '</div>';
        }

        /* LOCATIONS
        ----------------------------------- */
        // ON-HOLD.
        if ( $this_wcemessage_id_onhold ) {
            $locations[] = '<div class="on-hold">' . $this_location_onhold . '</div>';
        }

        // PROCESSING.
        if ( $this_wcemessage_id_processing ) {
            $locations[] = '<div class="processing">' . $this_location_processing . '</div>';
        }

        // COMPLETED.
        if ( $this_wcemessage_id_completed ) {
            $locations[] = '<div class="completed">' . $this_location_completed . '</div>';
        }

        return array (
            'messages' => $messages,
            'statuses' => $statuses,
            'locations' => $locations,
        );

    }

    /*
     * Function to get WCE Messages.
     */
    public function get_wcemessages() {

        // Get paged var.
        $paged = get_query_var( 'paged', 1 );

        // Get Screen Options setting for number of posts to show.
        $user = get_current_user_id();
        $screen = get_current_screen();
        $option = $screen->get_option( 'per_page', 'option' );
        $per_page = get_user_meta($user, $option, true);
        if ( empty ( $per_page) || $per_page < 1 ) {
            $per_page = $screen->get_option( 'per_page', 'default' );
        }

        // Create query args.
        $wce_queryargs = array(
            'post_type'      => 'product',
            'posts_per_page' => -1, // TODO: improve query!
            'paged'          => $paged,
            'meta_query'     => array(
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
            'update_post_term_cache' => false // false when taxonomy terms will not be utilized
        );

        // Create query.
        $wce_query = new WP_Query( $wce_queryargs );

        // Begin setting up array.
        if ( $wce_query->have_posts() ) {

            while ( $wce_query->have_posts() ) {
                $wce_query->the_post();
                $id = get_the_ID();
                $thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'thumbnail' );
                $imgsrc = '<img src="' . $thumbnail[0] . '" class="thumb" />';
                $title = '<div class="product-title">' . html_entity_decode( get_the_title() ) . '</div>';

                // Get array of all message statuses.
                $msgstatuses = $this->get_wcemessage_statuses( $id );

                // Create empty vars.
                $messages_content =
                    $statuses_content =
                    $locations_content = '';

                // List out all Messages.
                for ( $m = 0; $m < count( $msgstatuses['messages'] ); $m++ ) {
                    $messages_content .= $msgstatuses['messages'][$m];
                }

                // List out all Statuses.
                for ( $s = 0; $s < count( $msgstatuses['statuses'] ); $s++ ) {
                    $statuses_content .= $msgstatuses['statuses'][$s];
                }

                // List out all Locations.
                for ( $l = 0; $l < count( $msgstatuses['locations'] ); $l++ ) {
                    $locations_content .= $msgstatuses['locations'][$l];
                }

                // Create array of items to export.
                $wce_msg_array[] = array(
                    'ID'           => $id,
                    'thumb'        => $imgsrc,
                    'producttitle' => $title,
                    'assignedmsgs' => $messages_content,
                    'orderstatus'  => $statuses_content,
                    'msglocation'  => $locations_content,
                );
            }

            wp_reset_postdata();

        }

        // Return array.
        return $wce_msg_array;

    }

    // Constructor.
    function __construct() {

        global $status, $page;

        parent::__construct( array(
            'singular' => __( 'product', 'woocustomemails' ),   //singular name of the listed records
            'plural'   => __( 'products', 'woocustomemails' ),  //plural name of the listed records
            'ajax'     => false                                 //does this table support ajax?
        ));

    }

    // Function to display Not Found message.
    function no_items() {
        _e( 'No products with assigned WCE Messages were found.' );
    }

    // Create all columns.
    function column_default( $item, $column_name ) {
        switch( $column_name ) {
            case 'ID':
            case 'thumb':
            case 'producttitle':
            case 'assignedmsgs':
            case 'orderstatus':
            case 'msglocation':
            return $item[ $column_name ];
            default:
            return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
        }
    }

    // Get the sortable columns.
    function get_sortable_columns() {
        $sortable_columns = array(
            'ID'           => array( 'ID', false ),
            'producttitle' => array( 'producttitle', false ),
        );
        return $sortable_columns;
    }

    // Get the columns.
    function get_columns(){
        $columns = array(
            'cb'           => '<input type="checkbox" />',
            'ID'           => __( 'ID', 'woocustomemails' ),
            'thumb'        => '<span class="dashicons dashicons-format-image"></span>',
            'producttitle' => __( 'Product Name', 'woocustomemails' ),
            'assignedmsgs' => __( 'Assigned WCE Messages', 'woocustomemails' ),
            'orderstatus'  => __( 'Order Status', 'woocustomemails' ),
            'msglocation'  => __( 'Message Location', 'woocustomemails' ),
        );
         return $columns;
    }

    // Sorting.
    function usort_reorder( $a, $b ) {
        // If no sort, default to ID.
        $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'ID';
        // If no order, default to asc.
        $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
        // Determine sort order.
        $result = strcmp( $a[$orderby], $b[$orderby] );
        // Send final sort direction to usort.
        return ( $order === 'asc' ) ? $result : -$result;
    }

    // Display titles.
    function column_producttitle( $item ){

        $actions = array (
            'edit'   => sprintf (
                '<a href="%s">Edit</a>',
                get_edit_post_link( $item['ID'] )
            ),
            // 'delete' => sprintf (
            //     '<a href="%s">Delete</a>',
            //     get_delete_post_link( $item['ID'] )
            // ),
        );

        return sprintf (
            '%1$s %2$s',
            $item['producttitle'],
            $this->row_actions( $actions )
        );
    }

    // Get the bulk actions.
    function get_bulk_actions() {
        $actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }

    // Add column checkboxes.
    function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="book[]" value="%s" />', $item['ID']
        );
    }

    // Function to prepare items for display in the Table.
    function prepare_items() {
        $columns  = $this->get_columns();
        $hidden   = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array( $columns, $hidden, $sortable );
        $wce_querydata = $this->get_wcemessages();

        usort( $wce_querydata, array( &$this, 'usort_reorder' ) );

        $user = get_current_user_id();
        $screen = get_current_screen();
        $option = $screen->get_option( 'per_page', 'option' );
        $per_page = get_user_meta($user, $option, true);
        if ( empty ( $per_page) || $per_page < 1 ) {
            $per_page = $screen->get_option( 'per_page', 'default' );
        }
        $current_page = $this->get_pagenum();
        $total_items = count( $wce_querydata );

        // Assign found data var.
        $this->found_data = array_slice( $wce_querydata,( ( $current_page-1 )* $per_page ), $per_page );

        // Set pagination.
        $this->set_pagination_args( array(
            'total_items' => $total_items, // Calculate the total number of items
            'per_page'    => $per_page     // Determine how many items to show on a page
        ));

        // Set data to read.
        $this->items = $this->found_data;
    }

} // End WCE_AssignedMessagesTable class.

// function wce_add_assignedmsgs_settings_page() {
//
//     $wce_assignedmessages_menu = add_submenu_page(
//         'edit.php?post_type=woocustomemails',
//         __('Assigned Messages','woo_custom_emails_domain'), // page title
//         __('Assigned Messages','woo_custom_emails_domain'), // menu title
//         'manage_options', // capability
//         'woocustomemails_assigned', // menu slug
//         'wcemessages_render_list_page'
//     );
//     add_action( "load-$wce_assignedmessages_menu", 'add_options' );
//
//     add_action( 'admin_print_styles-' . $wce_assignedmessages_menu, 'wce_custom_admin_css' );
//
//     function wce_custom_admin_css() {
//         wp_enqueue_style( 'wce-admin-styles', plugins_url( '/woocustomemails-admin-styles.css', __FILE__ ) );
//     }
//
// }
// add_action( 'admin_menu', 'wce_add_assignedmsgs_settings_page' );

// Add the Screen Options settings.
function add_options() {
    global $woocustomemails;
    $option = 'per_page';
    $args = array(
        'label' => 'Products',
        'default' => 10,
        'option' => 'products_per_page'
    );
    add_screen_option( $option, $args );
    $woocustomemails = new WCE_AssignedMessagesTable();
}

// Save the user's Screen Options setting for "per page" value.
add_filter( 'set-screen-option', 'wce_save_screen_options', 10, 3 );
function wce_save_screen_options( $status, $option, $value ) {
    if ( 'products_per_page' == $option ) {
        return $value;
    }
    return $status;
}

// Render the List Table.
function wcemessages_render_list_page(){
    global $woocustomemails;
    echo '<div class="wrap"><h2>Woo Custom Emails - Assigned Messages</h2>';
    $woocustomemails->prepare_items();
    ?>
    <form method="post">
        <input type="hidden" name="page" value="wcemessages_list_table">
    <?php
    $woocustomemails->search_box( 'search', 'search_id' );
    $woocustomemails->display();
    echo '</form></div>';
}

// -------------------------------------------------------------------------- //
// -------------------------------------------------------------------------- //

// class Woo_Custom_Emails_Assigned_Products() {
//
//     public function __construct(){
//
//          // Add 'Assigned Messages' page under WCE menu
//          add_action( 'admin_menu', array( $this, 'add_woocustomemails_assignedmessages_menu' ) );
//
//     }
//
// }

// public function add_woocustomemails_assignedmessages_menu() {
//
// 	$wce_assignedmessages_menu = add_submenu_page(
//         'edit.php?post_type=woocustomemails',
//         __('Assigned Messages','woo_custom_emails_domain'), // page title
//         __('Assigned Messages','woo_custom_emails_domain'), // menu title
//         'manage_options', // capability
//         'woocustomemails_assigned', // menu slug
//         array( $this, 'display_wce_assigned_page' )
//     );
//
// 	add_action( 'admin_print_styles-' . $wce_assignedmessages_menu, 'wce_custom_admin_css' );
//
// 	function wce_custom_admin_css() {
// 		wp_enqueue_style( 'wce-admin-styles', plugins_url( '/woocustomemails-admin-styles.css', __FILE__ ) );
// 	}
//
// }

// public function woo_custom_emails_insert_db_testrow() {
//
// 	// Global var for WP db.
// 	global $wpdb;
//
// 	// Show errors for development.
// 	$wpdb->suppress_errors(false);
// 	$wpdb->show_errors(true);
//
// 	// Custom Table name.
// 	$table_name = $wpdb->prefix . 'wcepp_messages';
//
// 	// Product ID.
// 	$product_id = 6;
//
// 	// Message ID for status: Processing.
// 	$msg_processing = '16';
//
// 	// Message Location for status: Processing.
// 	$msg_processing_loc = 'After Order Table';
//
// 	// Message ID for status: On-Hold.
// 	$msg_onhold = '16';
//
// 	// Message Location for status: On-Hold.
// 	$msg_onhold_loc = 'After Order Table';
//
// 	// Message ID for status: Completed.
// 	$msg_completed = '16';
//
// 	// Message Location for status: Completed.
// 	$msg_completed_loc = 'After Customer Details';
//
// 	// Create array of values to be entered.
// 	$data_array = array(
//         'product_id' => $product_id,
//         'msg_processing' => $msg_processing,
//         'msg_processing_loc' => $msg_processing_loc,
// 		'msg_onhold' => $msg_onhold,
//         'msg_onhold_loc' => $msg_onhold_loc,
// 		'msg_completed' => $msg_completed,
//         'msg_completed_loc' => $msg_completed_loc,
//     );
//
// 	// Start a new query to find existing rows.
// 	$existingrows_query = "SELECT * FROM $table_name WHERE product_id = '$product_id'";
//
// 	// Get the results.
// 	$query_results = $wpdb->get_results( $existingrows_query );
//
// 	// If the row does not exist...
// 	if ( count( $query_results ) == 0 ) {
// 		// ... insert the data.
// 		$rowResult = $wpdb->insert( $table_name, $data_array );
// 		$insert_latest_id = $wpdb->insert_id;
// 		if ( ! $rowResult ) {
// 			// Show Error message.
// 			echo '<div id="message" class="updated fade"><p>😞 <b>NOT ADDED:</b> FAILED TO ADD DATA.</p></div>';
// 		} else {
// 			// Show Success message.
// 			echo '<div id="message" class="updated fade"><p>😎 <b>ADDED:</b> ROW #' . $insert_latest_id . ' ADDED!</p></div>';
// 		}
// 	} else {
// 		// ...the row exists, attempt to update.
//
// 		// Get current row info.
// 		$therow = $query_results[0];
// 		$rowid = $therow->id;
// 		$product_id = $therow->product_id;
// 		$old_msg_processing = $therow->msg_processing;
// 		$old_msg_processing_loc = $therow->msg_processing_loc;
// 		$old_msg_onhold = $therow->msg_onhold;
// 		$old_msg_onhold_loc = $therow->msg_onhold_loc;
// 		$old_msg_completed = $therow->msg_completed;
// 		$old_msg_completed_loc = $therow->msg_completed_loc;
//
// 		// Tracking vars.
// 		$changed_item =
// 			$colname =
// 			$colval = '';
//
// 		// Assign vars.
// 		if ( $old_msg_processing !== $msg_processing ) {
// 			$changed_item = 'Processing Message';
// 			$colname = 'msg_processing';
// 			$colval = $msg_processing;
// 		} else if ( $old_msg_processing_loc !== $msg_processing_loc ) {
// 			$changed_item = 'Processing Message Location';
// 			$colname = 'msg_processing_loc';
// 			$colval = $msg_processing_loc;
// 		} else if ( $old_msg_onhold !== $msg_onhold ) {
// 			$changed_item = 'On-Hold Message';
// 			$colname = 'msg_onhold';
// 			$colval = $msg_onhold;
// 		} else if ( $old_msg_onhold_loc !== $msg_onhold_loc ) {
// 			$changed_item = 'On-Hold Message Location';
// 			$colname = 'msg_onhold_loc';
// 			$colval = $msg_onhold_loc;
// 		} else if ( $old_msg_completed !== $msg_completed ) {
// 			$changed_item = 'Completed Message';
// 			$colname = 'msg_completed';
// 			$colval = $msg_completed;
// 		} else if ( $old_msg_completed_loc !== $msg_completed_loc ) {
// 			$changed_item = 'Completed Message Location';
// 			$colname = 'msg_completed_loc';
// 			$colval = $msg_completed_loc;
// 		}
//
// 		// If nothing is changed...
// 		if ( '' === $changed_item ) {
// 			// ...show the Nothing Updated message.
// 			echo '<div id="message" class="updated fade"><p>🤷🏼‍♂️ <b>NOTHING UPDATED:</b> NO VALUES CHANGED IN ROW #' . $therow->id . '.</p></div>';
// 		} else {
// 			// ...changes are present, run the update.
// 			$wpdb->update(
// 				$table_name,
// 				array(
// 					$colname => $colval
// 				),
// 				array(
// 					'product_id' => $product_id,
// 				)
// 			);
//
// 			// Show Update message.
// 			echo '<div id="message" class="updated fade"><p>🔁 <b>UPDATED:</b> VALUE OF <b>' . $changed_item . '</b> IN ROW #' . $therow->id . '.</p></div>';
// 		}
// 	}
//
//
// }

/**
* Show the WCE Assigned Messages page content
*
* @since 2.2.6
*/
// public function display_wce_assigned_page() {
// 	$output = '';
	// ? >
	// <div class="wrap">
	//
	// 	<h1 class="wp-heading-inline">Woo Custom Emails - Assigned Messages</h1>

		//<? php
		// // Setup query arguments.
		// $paged = get_query_var( 'paged', 1 );
		// $args = array(
		//     'post_type'      => 'product',
		//     'no_found_rows'  => true,
		//     'posts_per_page' => 10,
		// 	'paged'          => $paged,
		//     // 'orderby'              => 'name', // (string) - Order posts by: name, date, rand.
		//     // 'order'                => 'asc', // (string) - Post order: asc, desc.
		//     'meta_query'     => array(
		// 		'relation' => 'OR',
		//         array(
		//             'key' => 'wcemessage_id_onhold',
		// 			'value' => '',
		//             'compare' => '!=',
		//         ),
		// 		array(
		//             'key' => 'wcemessage_id_processing',
		// 			'value' => '',
		//             'compare' => '!=',
		//         ),
		// 		array(
		//             'key' => 'wcemessage_id_completed',
		// 			'value' => '',
		//             'compare' => '!=',
		//         ),
		//     ),
		// );
		//
		// // Create query object.
		// $assigned_query = new WP_Query( $args );
		//
		// $total_posts = $assigned_query->post_count;
		//
		// $output .= '<div class="post-count">';
		// // $output .= '<p>You have <b>' . $total_posts . '</b> products with WCE Messages assigned.';
		//
		// // Message for 0 found products.
		// if ( $total_posts < 1 ) {
		// 	$output .= ' You can assign a WCE Message under the "Messages" tab when editing a product. <a href="' . get_bloginfo('url') . '/wp-admin/edit.php?post_type=product">See All Products &rarr;</a>';
		// }
		//
		// $output .= '</p></div>';
		//
		// // Open HTML output.
		// $output .= '<div class="assigned-wce-messages">';
		//
		// // If query object has posts...
		// if ( $assigned_query->have_posts() ) {
		//
		// 	// Open data table.
		// 	$output .= '<table>';
		// 	$output .= '<thead class="header">';
		//
		// 	// Product Count column.
		// 	$output .= '<td class="product-count">#</td>';
		//
		// 	// Product column.
		// 	$output .= '<td class="product-title">Product</td>';
		//
		// 	// Assigned Message Title column.
		// 	$output .= '<td class="assigned-message-title">Assigned WCE Messages</td>';
		//
		// 	// Assigned Message Order Status column.
		// 	$output .= '<td class="assigned-message-orderstatus">Order Status</td>';
		//
		// 	// Assigned Message Template Location column.
		// 	$output .= '<td class="assigned-message-templatelocation">Location</td>';
		//
		// 	$output .= '</thead>';
		// 	$output .= '<tbody>';
		//
		// 	$count = 1;
		//
		// 	// While query has posts...
		// 	while( $assigned_query->have_posts() ) {
		//
		// 		// Assign query object.
		//     	$assigned_query->the_post();
		//
		// 		// Assign global vars.
		// 		global $product, $post;
		//
		// 		// Assign total count.
		// 		$total_posts = $assigned_query->post_count;
		//
		// 		// Assign vars.
		//         $this_id = get_the_ID();
		// 		$this_thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $this_id ), 'thumbnail' );
		//         $this_title = $assigned_query->post->post_title;
		//         $this_productlink = get_edit_post_link( $this_id );
		//
		// 		$wcemessage_id_onhold =
		// 			$wcemessage_id_processing =
		// 			$wcemessage_id_completed =
		// 			$wcemessage_location_onhold =
		// 			$wcemessage_location_processing =
		// 			$wcemessage_location_completed =
		// 			$wce_onhold_message_title =
		// 			$wce_onhold_message_editURL =
		// 			$wce_processing_message_title =
		// 			$wce_processing_message_editURL =
		// 			$wce_completed_message_title =
		// 			$wce_completed_message_editURL =
		// 			$this_wcemessage_id_onhold =
		// 			$this_wcemessage_id_processing =
		// 			$this_wcemessage_id_completed =
		// 			$this_orderstatus_onhold =
		// 			$this_orderstatus_processing =
		// 			$this_orderstatus_completed =
		// 			$this_location_onhold =
		// 			$this_location_processing =
		// 			$this_location_completed = '';
		//
		//
		// 		// Get WCE Message meta.
		// 		$wcemessage_id_onhold = (int) get_post_meta( $this_id, 'wcemessage_id_onhold', true );
		// 		$wcemessage_location_onhold = get_post_meta( $this_id, 'location_onhold', true );
		// 		$wcemessage_id_processing = (int) get_post_meta( $this_id, 'wcemessage_id_processing', true );
		// 		$wcemessage_location_processing = get_post_meta( $this_id, 'location_processing', true );
		// 		$wcemessage_id_completed = (int) get_post_meta( $this_id, 'wcemessage_id_completed', true );
		// 		$wcemessage_location_completed = get_post_meta( $this_id, 'location_completed', true );
		//
		// 		// Check for On-Hold content.
		// 		if ( $wcemessage_id_onhold > 0 ) {
		// 			$this_orderstatus_onhold = '<span class="on-hold">On Hold</span>';
		// 			$this_wcemessage_id_onhold = $wcemessage_id_onhold;
		// 			$wce_onhold_message_title = get_the_title( $this_wcemessage_id_onhold );
		// 			$wce_onhold_message_editURL = get_edit_post_link( $this_wcemessage_id_onhold );
		// 		}
		//
		// 		// Check for Processing content.
		// 		if ( $wcemessage_id_processing > 0 ) {
		// 			$this_orderstatus_processing = '<span class="processing">Processing</span>';
		// 			$this_wcemessage_id_processing = $wcemessage_id_processing;
		// 			$wce_processing_message_title = get_the_title( $this_wcemessage_id_processing );
		// 			$wce_processing_message_editURL = get_edit_post_link( $this_wcemessage_id_processing );
		// 		}
		//
		// 		// Check for Completed content.
		// 		if ( $wcemessage_id_completed > 0 ) {
		// 			$this_orderstatus_completed = '<span class="completed">Completed</span>';
		// 			$this_wcemessage_id_completed = $wcemessage_id_completed;
		// 			$wce_completed_message_title = get_the_title( $this_wcemessage_id_completed );
		// 			$wce_completed_message_editURL = get_edit_post_link( $this_wcemessage_id_completed );
		// 		}
		//
		// 		switch ( $wcemessage_location_onhold ) {
		//             case 'woocommerce_email_before_order_table':
		// 				$this_location_onhold = 'Before Order Table';
		//                 break;
		// 			case 'woocommerce_email_after_order_table':
		// 				$this_location_onhold = 'After Order Table';
		//                 break;
		// 			case 'woocommerce_email_order_meta':
		// 				$this_location_onhold = 'After Order Meta';
		//                 break;
		// 			case 'woocommerce_email_customer_details':
		// 				$this_location_onhold = 'After Customer Details';
		// 				break;
		//         }
		//
		// 		switch ( $wcemessage_location_processing ) {
		//             case 'woocommerce_email_before_order_table':
		// 				$this_location_processing = 'Before Order Table';
		//                 break;
		// 			case 'woocommerce_email_after_order_table':
		// 				$this_location_processing = 'After Order Table';
		//                 break;
		// 			case 'woocommerce_email_order_meta':
		// 				$this_location_processing = 'After Order Meta';
		//                 break;
		// 			case 'woocommerce_email_customer_details':
		// 				$this_location_processing = 'After Customer Details';
		// 				break;
		//         }
		//
		// 		switch ( $wcemessage_location_completed ) {
		//             case 'woocommerce_email_before_order_table':
		// 				$this_location_completed = 'Before Order Table';
		//                 break;
		// 			case 'woocommerce_email_after_order_table':
		// 				$this_location_completed = 'After Order Table';
		//                 break;
		// 			case 'woocommerce_email_order_meta':
		// 				$this_location_completed = 'After Order Meta';
		//                 break;
		// 			case 'woocommerce_email_customer_details':
		// 				$this_location_completed = 'After Customer Details';
		// 				break;
		//         }
		//
		// 		// Open table row.
		// 		$output .= '<tr class="product">';
		//
		// 		// Product Count column.
		// 		$output .= '<td class="product-count"> '. $count . '</td>';
		//
		// 		// Product Title column.
		// 		$output .= '<td class="product-title"><a href="' . $this_productlink . '" target="_blank"><img src="' . $this_thumb[0] . '" class="thumb" />' . $this_title . '</a></td>';
		//
		// 		// Assigned Messages column.
		// 		$output .= '<td class="assigned-message-title">';
		//
		// 		// ON-HOLD.
		// 		if ( $this_wcemessage_id_onhold ) {
		// 			$output .= '<div class="on-hold">';
		// 			$output .= '<a href="' . $wce_onhold_message_editURL . '" target="_blank">' . $wce_onhold_message_title . '</a>';
		// 			$output .= '</div>';
		// 		}
		//
		// 		// PROCESSING.
		// 		if ( $this_wcemessage_id_processing ) {
		// 			$output .= '<div class="processing">';
		// 			$output .= '<a href="' . $wce_processing_message_editURL . '" target="_blank">' . $wce_processing_message_title . '</a>';
		// 			$output .= '</div>';
		// 		}
		//
		// 		// COMPLETED.
		// 		if ( $this_wcemessage_id_completed ) {
		// 			$output .= '<div class="completed">';
		// 			$output .= '<a href="' . $wce_completed_message_editURL . '" target="_blank">' . $wce_completed_message_title . '</a>';
		// 			$output .= '</div>';
		// 		}
		//
		// 		$output .= '</td>';
		//
		// 		// Order Status column.
		// 		$output .= '<td class="assigned-message-orderstatus">';
		//
		// 		// ON-HOLD.
		// 		if ( $this_wcemessage_id_onhold ) {
		// 			$output .= '<div class="on-hold">' . $this_orderstatus_onhold . '</div>';
		// 		}
		//
		// 		// PROCESSING.
		// 		if ( $this_wcemessage_id_processing ) {
		// 			$output .= '<div class="processing">' . $this_orderstatus_processing . '</div>';
		// 		}
		//
		// 		// COMPLETED.
		// 		if ( $this_wcemessage_id_completed ) {
		// 			$output .= '<div class="completed">' . $this_orderstatus_completed . '</div>';
		// 		}
		//
		// 		$output .= '</td>';
		//
		// 		// Location column.
		// 		$output .= '<td class="assigned-message-templatelocation">';
		//
		// 		// $output .= $this_templatelocation;
		//
		// 		// ON-HOLD.
		// 		if ( $this_wcemessage_id_onhold ) {
		// 			$output .= '<div class="on-hold">' . $this_location_onhold . '</div>';
		// 		}
		//
		// 		// PROCESSING.
		// 		if ( $this_wcemessage_id_processing ) {
		// 			$output .= '<div class="processing">' . $this_location_processing . '</div>';
		// 		}
		//
		// 		// COMPLETED.
		// 		if ( $this_wcemessage_id_completed ) {
		// 			$output .= '<div class="completed">' . $this_location_completed . '</div>';
		// 		}
		//
		// 		$output .= '</td>';
		//
		// 		// Close table row.
		// 		$output .= '</tr>';
		//
		// 		$count++;
		//
		// 	}
		//
		// 	// Close table body.
		// 	$output .= '</tbody>';
		//
		// 	// Close data table.
		// 	$output .= '</table>';
		//
		// 	// Reset wp query.
		// 	wp_reset_postdata();
		// 	wp_reset_query();
		//
		// 	the_posts_pagination( array(
		// 		'mid_size' => 1,
		// 		'prev_text' => __('Prev', 'woo_custom_emails_domain'),
		// 		'next_text' => __('Next', 'woo_custom_emails_domain'),
		// 		'before_page_number' => '<span class="meta-nav screen-reader-text">' . __('Page', 'woo_custom_emails_domain') . ' </span>',
		// 	));
		//
		// } else {
		//
		// 	$output .= '<p class="alert"><b>Sorry, no Products found with assigned WCE Messages.</b></p>';
		//
		// }
		//
		// $output .= '</div><!-- // end .assigned-wce-messages // -->';
		//
		// echo $output;
		// ? >

	// </div><!-- // end .wrap // -->
	// <? php
// }

// /**
// * Display the 'Add Test Data' field/button.
// */
// public function add_testrow_callback() {
//
// 	// Create a nonce.
// 	wp_nonce_field('test_button_clicked');
//
// 	// Output a hidden field to track the value.
// 	echo '<input type="hidden" value="true" name="test_button" />';
//
// 	// Output the button to perform the action.
// 	submit_button('Add Test Data');
// 	echo '<span class="description">' . __( 'Add a row of Test Data to the \'wcepp_messages\' db table.', 'woo_custom_emails_domain' ) . '</span>';
// }

// // Check whether the 'Add Test Data' button has been submitted and also check its nonce.
// if ( isset( $_POST['test_button'] ) && check_admin_referer( 'test_button_clicked' ) ) {
// 	// Run function to add Test Data.
// 	$this->woo_custom_emails_insert_db_testrow();
// }
