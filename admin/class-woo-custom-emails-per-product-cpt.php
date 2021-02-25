<?php

class Woo_Custom_Emails_Per_Product_CPT {

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

}

// Initialize the Class.
add_action(
    'plugins_loaded',
    function(){
        $wcepp_cpt = new Woo_Custom_Emails_Per_Product_CPT;
        $wcepp_cpt->new_cpt_wcemails();
    }
);
