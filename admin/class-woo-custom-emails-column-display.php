<?php
/**
 * Adds custom columns to the Manage Posts page for WCCEPP.
 *
 * @package WooCustomEmails
 */

/**
 * Woo_Custom_Emails_Column_Display is a class for displaying extra table columns on the Manage Posts page for WCCEPP.
 */
class Woo_Custom_Emails_Column_Display {

	/**
	 * Adds new columns to the page.
	 */
	public function __construct() {
		add_filter( 'manage_woocustomemails_posts_columns', array( &$this, 'set_custom_edit_woocustomemails_columns' ) );
		add_action( 'manage_woocustomemails_posts_custom_column', array( &$this, 'custom_woocustomemails_column' ), 10, 2 );
	}

	/**
	 * Adds an "ID" column to the WCE Messages list.
	 *
	 * @param object $columns The column object to be modified.
	 *
	 * @since  2.0.0
	 */
	public function set_custom_edit_woocustomemails_columns( $columns ) {

		// Remove the Title and Date columns now, add them back later.
		unset( $columns['title'] );
		unset( $columns['date'] );

		// Add new "ID" column.
		$columns['messageid'] = __( 'ID', 'woo_custom_emails_domain' );

		// Add the Title and Date columns back in.
		$columns['title'] = __( 'Title', 'woo_custom_emails_domain' );
		$columns['date']  = __( 'Date', 'woo_custom_emails_domain' );

		return $columns;
	}

	/**
	 * Displays the "ID" column data.
	 *
	 * @param object $column The column object to be modified.
	 * @param string $post_id The ID of the post to be output.
	 *
	 * @since  2.0.0
	 */
	public function custom_woocustomemails_column( $column, $post_id ) {
		if ( 'messageid' === $column ) {
			$id = $post_id;
			echo esc_attr( $id );
		}
	}

}

// Initialize the Class.
add_action(
	'plugins_loaded',
	function() {
		new Woo_Custom_Emails_Column_Display();
	}
);
