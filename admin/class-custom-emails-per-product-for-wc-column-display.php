<?php
/**
 * Custom_Emails_Per_Product_For_WC_Column_Display is a class for displaying extra table columns on the Manage Posts page for WCCEPP.
 */
class Custom_Emails_Per_Product_For_WC_Column_Display {

	/**
	 * Adds new columns to the page.
	 */
	public function __construct() {
		add_filter( 'manage_cepp4wp_message_posts_columns', array( &$this, 'set_custom_edit_cepp4wp_message_columns' ) );
		add_action( 'manage_cepp4wp_message_posts_custom_column', array( &$this, 'custom_cepp4wp_message_column' ), 10, 2 );
	}

	/**
	 * Adds an "ID" column to the WCE Messages list.
	 *
	 * @param object $columns The column object to be modified.
	 */
	public function set_custom_edit_cepp4wp_message_columns( $columns ) {

		// Remove the Title and Date columns now, add them back later.
		unset( $columns['title'] );
		unset( $columns['date'] );

		// Add new "ID" column.
		$columns['messageid'] = __( 'ID', 'cepp4wc_domain' );

		// Add the Title and Date columns back in.
		$columns['title'] = __( 'Title', 'cepp4wc_domain' );
		$columns['date']  = __( 'Date', 'cepp4wc_domain' );

		return $columns;
	}

	/**
	 * Displays the "ID" column data.
	 *
	 * @param object $column The column object to be modified.
	 * @param string $post_id The ID of the post to be output.
	 */
	public function custom_cepp4wp_message_column( $column, $post_id ) {
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
		new Custom_Emails_Per_Product_For_WC_Column_Display();
	}
);
