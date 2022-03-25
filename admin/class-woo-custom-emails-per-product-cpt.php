<?php
/**
 * Creates the Custom Post Type required for the plugin.
 *
 * @package WooCustomEmails
 */

/**
 * Woo_Custom_Emails_Per_Product_CPT is a class for creating the 'woocustomemails' Custom Post Type.
 */
class Woo_Custom_Emails_Per_Product_CPT {

	/**
	 * Creates a new custom post type.
	 *
	 * @since 2.0.0
	 * @access public
	 * @uses register_post_type()
	 */
	public static function new_cpt_wcemails() {

		$cap_type = 'post';
		$plural   = 'WC Custom Messages';
		$single   = 'WC Custom Message';
		$cpt_name = 'woocustomemails';

		$opts['can_export']           = true;
		$opts['capability_type']      = $cap_type;
		$opts['description']          = '';
		$opts['exclude_from_search']  = true;
		$opts['has_archive']          = true;
		$opts['hierarchical']         = false;
		$opts['map_meta_cap']         = true;
		$opts['menu_icon']            = 'dashicons-email-alt';
		$opts['menu_position']        = 50;
		$opts['public']               = false;
		$opts['publicly_querable']    = false;
		$opts['register_meta_box_cb'] = '';
		$opts['rewrite']              = false;
		$opts['show_in_admin_bar']    = true;
		$opts['show_in_menu']         = true;
		$opts['show_in_nav_menu']     = true;
		$opts['show_ui']              = true;
		$opts['supports']             = array( 'title', 'editor', 'revisions' );
		$opts['taxonomies']           = array();

		$opts['capabilities']['delete_others_posts']    = "delete_others_{$cap_type}s";
		$opts['capabilities']['delete_post']            = "delete_{$cap_type}";
		$opts['capabilities']['delete_posts']           = "delete_{$cap_type}s";
		$opts['capabilities']['delete_private_posts']   = "delete_private_{$cap_type}s";
		$opts['capabilities']['delete_published_posts'] = "delete_published_{$cap_type}s";
		$opts['capabilities']['edit_others_posts']      = "edit_others_{$cap_type}s";
		$opts['capabilities']['edit_post']              = "edit_{$cap_type}";
		$opts['capabilities']['edit_posts']             = "edit_{$cap_type}s";
		$opts['capabilities']['edit_private_posts']     = "edit_private_{$cap_type}s";
		$opts['capabilities']['edit_published_posts']   = "edit_published_{$cap_type}s";
		$opts['capabilities']['publish_posts']          = "publish_{$cap_type}s";
		$opts['capabilities']['read_post']              = "read_{$cap_type}";
		$opts['capabilities']['read_private_posts']     = "read_private_{$cap_type}s";

		$opts['labels']['add_new']            = esc_html__( 'Add New Custom Message', 'woo_custom_emails_domain' );
		$opts['labels']['add_new_item']       = esc_html__( 'Add New Custom Message', 'woo_custom_emails_domain' );
		$opts['labels']['all_items']          = esc_html__( 'WC Custom Messages', 'woo_custom_emails_domain' );
		$opts['labels']['edit_item']          = esc_html__( 'Edit WC Custom Message', 'woo_custom_emails_domain' );
		$opts['labels']['menu_name']          = esc_html__( 'Custom Emails', 'woo_custom_emails_domain' );
		$opts['labels']['name']               = esc_html__( 'WC Custom Messages', 'woo_custom_emails_domain' );
		$opts['labels']['name_admin_bar']     = esc_html__( 'WC Custom Message', 'woo_custom_emails_domain' );
		$opts['labels']['new_item']           = esc_html__( 'New WC Custom Message', 'woo_custom_emails_domain' );
		$opts['labels']['not_found']          = esc_html__( 'No WC Custom Messages Found', 'woo_custom_emails_domain' );
		$opts['labels']['not_found_in_trash'] = esc_html__( 'No WC Custom Messages Found in Trash', 'woo_custom_emails_domain' );
		$opts['labels']['parent_item_colon']  = esc_html__( 'Parent WC Custom Messages :', 'woo_custom_emails_domain' );
		$opts['labels']['search_items']       = esc_html__( 'Search WC Custom Messages', 'woo_custom_emails_domain' );
		$opts['labels']['singular_name']      = esc_html__( 'WC Custom Message', 'woo_custom_emails_domain' );
		$opts['labels']['view_item']          = esc_html__( 'View WC Custom Message', 'woo_custom_emails_domain' );

		$opts = apply_filters( 'woocustomemails-cpt-options', $opts );

		register_post_type( strtolower( $cpt_name ), $opts );

	}

}

// Initialize the Class.
add_action(
	'plugins_loaded',
	function() {
		$wcepp_cpt = new Woo_Custom_Emails_Per_Product_CPT();
		$wcepp_cpt->new_cpt_wcemails();
	}
);
