<?php
class Woo_Custom_Emails_Output {

	// Define vars
	protected $version;
	protected $textdomain;
	public $shown_messages;

	// Class constructor
	public function __construct() {

		$this->version = WCE_PLUGIN_VERSION;

		$shown_messages = array();

		// ON-HOLD
		add_action( 'woocommerce_order_status_pending_to_on-hold_notification',  array( $this, 'status_action_onhold' ), 10, 2 );
		add_action( 'woocommerce_order_status_pending_to_on-hold_notification',  array( $this, 'woo_custom_emails_insert_content' ), 10, 2 );
		add_action( 'woocommerce_order_status_failed_to_on-hold_notification',  array( $this, 'status_action_onhold' ), 10, 2 );
		add_action( 'woocommerce_order_status_failed_to_on-hold_notification',  array( $this, 'woo_custom_emails_insert_content' ), 10, 2 );
		add_action( 'woocommerce_order_status_cancelled_to_on-hold_notification',  array( $this, 'status_action_onhold' ), 10, 2 );
		add_action( 'woocommerce_order_status_cancelled_to_on-hold_notification',  array( $this, 'woo_custom_emails_insert_content' ), 10, 2 );

		// PROCESSING
		add_action( 'woocommerce_order_status_cancelled_to_processing_notification', array( $this, 'status_action_processing' ), 10, 2 );
		add_action( 'woocommerce_order_status_cancelled_to_processing_notification', array( $this, 'woo_custom_emails_insert_content' ), 10, 2 );
		add_action( 'woocommerce_order_status_failed_to_processing_notification', array( $this, 'status_action_processing' ), 10, 2 );
		add_action( 'woocommerce_order_status_failed_to_processing_notification', array( $this, 'woo_custom_emails_insert_content' ), 10, 2 );
		add_action( 'woocommerce_order_status_on-hold_to_processing_notification', array( $this, 'status_action_processing' ), 10, 2 );
		add_action( 'woocommerce_order_status_on-hold_to_processing_notification', array( $this, 'woo_custom_emails_insert_content' ), 10, 2 );
		add_action( 'woocommerce_order_status_pending_to_processing_notification', array( $this, 'status_action_processing' ), 10, 2 );
		add_action( 'woocommerce_order_status_pending_to_processing_notification', array( $this, 'woo_custom_emails_insert_content' ), 10, 2 );

		// COMPLETED
		add_action( 'woocommerce_order_status_completed_notification', array( $this, 'status_action_completed' ), 10, 2 );
		add_action( 'woocommerce_order_status_completed_notification',  array( $this, 'woo_custom_emails_insert_content' ), 10, 2 );

		// FAILED
		// woocommerce_order_status_pending_to_failed_notification
		// woocommerce_order_status_on-hold_to_failed_notification

		// REFUNDED
		// woocommerce_order_fully_refunded_notification
		// woocommerce_order_partially_refunded_notification

		// CANCELLED
		// woocommerce_order_status_processing_to_cancelled_notification
		// woocommerce_order_status_on-hold_to_cancelled_notification

	}

	public function status_action_onhold(){
		global $this_order_status_action;
		$this_order_status_action = "woocommerce_order_status_on-hold";
	}

	public function status_action_processing(){
		global $this_order_status_action;
		$this_order_status_action = "woocommerce_order_status_processing";
	}

	public function status_action_completed(){
		global $this_order_status_action;
		$this_order_status_action = "woocommerce_order_status_completed";
	}

	// Insert the custom content into the email template at the chosen location
	public function woo_custom_emails_insert_content( $shown_messages ) {

		if ( ! is_array( $shown_messages ) ) {
			$shown_messages = array();
		}

		global $this_order_status_action;

		// Function to output the custom message
		if ( !function_exists( "woo_custom_emails_output_message" ) ) {

			function woo_custom_emails_output_message( $order, $shown_messages ){

				if ( ! is_array( $shown_messages ) ) {
					$shown_messages = array();
				}

				global $this_order_status_action;

				// Get items in this order
				$items = (array) $order->get_items();

				// Loop through all items in this order
				foreach ( $items as $item ) {

					// Get this product ID
					$this_product_id = $item['product_id'];

					// Get this meta
					$orderstatus_meta = (string) get_post_meta( $this_product_id, 'order_status', true );
					// $customcontent_meta = get_post_meta( $this_product_id, 'custom_content', true );	// OLD DATA FROM 1.x
					$wcemessage_id = get_post_meta( $this_product_id, 'wcemessage_id', true );
					$templatelocation_meta = get_post_meta( $this_product_id, 'location', true );

					/* 2.2.0 */
					$wcemessage_id_onhold = (int) get_post_meta( $this_product_id, 'wcemessage_id_onhold', true );
					$wcemessage_location_onhold = get_post_meta( $this_product_id, 'location_onhold', true );
					$wcemessage_id_processing = (int) get_post_meta( $this_product_id, 'wcemessage_id_processing', true );
					$wcemessage_location_processing = get_post_meta( $this_product_id, 'location_processing', true );
					$wcemessage_id_completed = (int) get_post_meta( $this_product_id, 'wcemessage_id_completed', true );
					$wcemessage_location_completed = get_post_meta( $this_product_id, 'location_completed', true );

					// Set a var to track the current email template location
					$this_email_template_location = (string) current_action();

					// If there is NEW data assigned
					if ( !empty( $wcemessage_id_onhold ) || !empty( $wcemessage_id_processing ) || !empty( $wcemessage_id_completed ) ) {

						// ON-HOLD ************************************
						if ( 'woocommerce_order_status_on-hold' == $this_order_status_action ) {

							// If there is an email assigned for 'On hold' status
							if ( !empty( $wcemessage_id_onhold ) ) {

								// If this message has not already been shown in this email
								if ( !in_array( $wcemessage_id_onhold, $shown_messages ) ) {

									if ( ! is_array( $shown_messages ) ) {
										$shown_messages = array();
									}

									// If message location setting is equal to the current email template location
									if ( $wcemessage_location_onhold == $this_email_template_location ) {

										// Show the message!

										// Define output var
										$output = '';

										if ( $this_email_template_location == 'woocommerce_email_order_meta' || $this_email_template_location == 'woocommerce_email_customer_details' ){
											// Extra line breaks at the beginning to separate Message content from Email content
											$output .= '<br><br>';
										}

										// Output the_content of the saved WCE Message ID
										$output .= nl2br( get_post_field( 'post_content', $wcemessage_id_onhold ) );

										// Extra line breaks at the end to separate Message content from Email content
										$output .= '<br><br>';

										// Output everything
										echo $output;

										// Update 'shown_emails' var
										$shown_messages[] = $wcemessage_id_onhold;

									}

								}

							}

						}

						// PROCESSING ************************************
						if ( 'woocommerce_order_status_processing' == $this_order_status_action ) {

							// If there is an email assigned for 'Processing' status
							if ( !empty( $wcemessage_id_processing ) ) {

								// If this message has not already been shown in this email
								if ( !in_array( $wcemessage_id_processing, $shown_messages ) ) {

									if ( ! is_array( $shown_messages ) ) {
										$shown_messages = array();
									}

									// If message location setting is equal to the current email template location
									if ( $wcemessage_location_processing == $this_email_template_location ) {

										// Show the message!

										// Define output var
										$output = '';

										if ( $this_email_template_location == 'woocommerce_email_order_meta' || $this_email_template_location == 'woocommerce_email_customer_details' ){
											// Extra line breaks at the beginning to separate Message content from Email content
											$output .= '<br><br>';
										}

										// Output the_content of the saved WCE Message ID
										$output .= nl2br( get_post_field( 'post_content', $wcemessage_id_processing ) );

										// Extra line breaks at the end to separate Message content from Email content
										$output .= '<br><br>';

										// Output everything
										echo $output;

										// Update 'shown_emails' var
										$shown_messages[] = $wcemessage_id_processing;

									}

								}

							}

						}

						// COMPLETED ************************************
						if ( 'woocommerce_order_status_completed' == $this_order_status_action ) {

							// If there is an email assigned for 'Completed' status
							if ( !empty( $wcemessage_id_completed ) ) {

								// If this message has not already been shown in this email
								if ( !in_array( $wcemessage_id_completed, $shown_messages ) ) {

									if ( ! is_array( $shown_messages ) ) {
										$shown_messages = array();
									}

									// If message location setting is equal to the current email template location
									if ( $wcemessage_location_completed == $this_email_template_location ) {

										// Show the message!

										// Define output var
										$output = '';

										if ( $this_email_template_location == 'woocommerce_email_order_meta' || $this_email_template_location == 'woocommerce_email_customer_details' ){
											// Extra line breaks at the beginning to separate Message content from Email content
											$output .= '<br><br>';
										}

										// Output the_content of the saved WCE Message ID
										$output .= nl2br( get_post_field( 'post_content', $wcemessage_id_completed ) );

										// Extra line breaks at the end to separate Message content from Email content
										$output .= '<br><br>';

										// Output everything
										echo $output;

										// Update 'shown_emails' var
										$shown_messages[] = $wcemessage_id_completed;

									}

								}

							}

						}

					} else {

						// If there is a legacy WCE Message assigned
						if ( $wcemessage_id ) {

							// If order status setting is equal to the current order status action
							if ( $orderstatus_meta == $this_order_status_action ) {

								// If this message has not already been shown in this email
								if ( !in_array( $wcemessage_id, $shown_messages ) ) {

									// If template location setting is equal to the current email template location
									if ( $templatelocation_meta == $this_email_template_location ) {

										// Show the message!

										// Define output var
										$output = '';

										if ( $this_email_template_location == 'woocommerce_email_order_meta' || $this_email_template_location == 'woocommerce_email_customer_details' ){
											// Extra line breaks at the beginning to separate Message content from Email content
											$output .= '<br><br>';
										}

										// Output the_content of the saved WCE Message ID
										$output .= nl2br( get_post_field( 'post_content', $wcemessage_id ) );

										// Extra line breaks at the end to separate Message content from Email content
										$output .= '<br><br>';

										// Output everything
										echo $output;

										// Update 'shown_emails' var
										$shown_messages[] = $wcemessage_id;

									}
								}

							}

						}

					}

				}

			} // woo_custom_emails_output_message()

		}

		// Add action for each email template location to inject our custom message
		add_action( 'woocommerce_email_before_order_table', 'woo_custom_emails_output_message', 10, 2 );
		add_action( 'woocommerce_email_after_order_table', 'woo_custom_emails_output_message', 10, 2 );
		add_action( 'woocommerce_email_order_meta', 'woo_custom_emails_output_message', 10, 2 );
		add_action( 'woocommerce_email_customer_details', 'woo_custom_emails_output_message', 10, 2 );

	} // woo_custom_emails_insert_content()

}
