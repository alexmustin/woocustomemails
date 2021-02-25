<?php

class Woo_Custom_Emails_Output_Column_Display {

    public function __construct() {

        add_filter( 'manage_woocustomemails_posts_columns', array( &$this, 'set_custom_edit_woocustomemails_columns' ) );
		add_action( 'manage_woocustomemails_posts_custom_column' , array( &$this, 'custom_woocustomemails_column' ), 10, 2 );

    }

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

}

// Initialize the Class.
add_action(
    'plugins_loaded',
    function(){
        new Woo_Custom_Emails_Output_Column_Display();
    }
);
