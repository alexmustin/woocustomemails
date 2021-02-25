<?php
/**
 * Woo_Custom_Emails_Output is a class used to output the custom email message.
 */
class Woo_Custom_Emails_Output {

	// Define vars.

	/**
	 * Assigns the current version of this plugin.
	 *
	 * @since 1.0.0
	 * @var string $version The current version of this plugin.
	 */
	protected $version;

	/**
	 * Assigns the text domain of this plugin.
	 *
	 * @since 1.0.0
	 * @var string $textdomain The text domain of this plugin.
	 */
	protected $textdomain;

	/**
	 * Assigns an array of messages which have already been added to the email.
	 *
	 * @since 1.0.0
	 * @var array $shown_messages An array of messages which have already been added to the email.
	 */
	public $shown_messages;

	/**
	 * Class constructor.
	 */
	public function __construct() {

		$this->version = WCE_PLUGIN_VERSION;

		$shown_messages = array();

		// ON-HOLD STATUS.
		add_action( 'woocommerce_order_status_pending_to_on-hold_notification', array( $this, 'status_action_onhold' ), 10, 2 );
		add_action( 'woocommerce_order_status_pending_to_on-hold_notification', array( $this, 'woo_custom_emails_insert_content' ), 10, 2 );
		add_action( 'woocommerce_order_status_failed_to_on-hold_notification', array( $this, 'status_action_onhold' ), 10, 2 );
		add_action( 'woocommerce_order_status_failed_to_on-hold_notification', array( $this, 'woo_custom_emails_insert_content' ), 10, 2 );
		add_action( 'woocommerce_order_status_cancelled_to_on-hold_notification', array( $this, 'status_action_onhold' ), 10, 2 );
		add_action( 'woocommerce_order_status_cancelled_to_on-hold_notification', array( $this, 'woo_custom_emails_insert_content' ), 10, 2 );

		// PROCESSING STATUS.
		add_action( 'woocommerce_order_status_cancelled_to_processing_notification', array( $this, 'status_action_processing' ), 10, 2 );
		add_action( 'woocommerce_order_status_cancelled_to_processing_notification', array( $this, 'woo_custom_emails_insert_content' ), 10, 2 );
		add_action( 'woocommerce_order_status_failed_to_processing_notification', array( $this, 'status_action_processing' ), 10, 2 );
		add_action( 'woocommerce_order_status_failed_to_processing_notification', array( $this, 'woo_custom_emails_insert_content' ), 10, 2 );
		add_action( 'woocommerce_order_status_on-hold_to_processing_notification', array( $this, 'status_action_processing' ), 10, 2 );
		add_action( 'woocommerce_order_status_on-hold_to_processing_notification', array( $this, 'woo_custom_emails_insert_content' ), 10, 2 );
		add_action( 'woocommerce_order_status_pending_to_processing_notification', array( $this, 'status_action_processing' ), 10, 2 );
		add_action( 'woocommerce_order_status_pending_to_processing_notification', array( $this, 'woo_custom_emails_insert_content' ), 10, 2 );

		// COMPLETED STATUS.
		add_action( 'woocommerce_order_status_completed_notification', array( $this, 'status_action_completed' ), 10, 2 );
		add_action( 'woocommerce_order_status_completed_notification', array( $this, 'woo_custom_emails_insert_content' ), 10, 2 );

		/*
		// FAILED STATUS
		// woocommerce_order_status_pending_to_failed_notification
		// woocommerce_order_status_on-hold_to_failed_notification

		// REFUNDED
		// woocommerce_order_fully_refunded_notification
		// woocommerce_order_partially_refunded_notification

		// CANCELLED
		// woocommerce_order_status_processing_to_cancelled_notification
		// woocommerce_order_status_on-hold_to_cancelled_notification
		*/

	}

	/**
	 * Adds a flag for when the current status is set to 'on-hold'.
	 */
	public function status_action_onhold() {
		global $this_order_status_action;
		$this_order_status_action = 'woocommerce_order_status_on-hold';
	}

	/**
	 * Adds a flag for when the current status is set to 'processing'.
	 */
	public function status_action_processing() {
		global $this_order_status_action;
		$this_order_status_action = 'woocommerce_order_status_processing';
	}

	/**
	 * Adds a flag for when the current status is set to 'completed'.
	 */
	public function status_action_completed() {
		global $this_order_status_action;
		$this_order_status_action = 'woocommerce_order_status_completed';
	}

	/**
	 * Insert the custom content into the email template at the chosen location.
	 *
	 * @param array  $shown_messages An array of which messages have already been added to the email.
	 * @param object $order An object containing all the order info.
	 */
	public function woo_custom_emails_insert_content( $shown_messages, $order ) {

		global $woocommerce;

		if ( ! is_array( $shown_messages ) ) {
			$shown_messages = array();
		}

		global $this_order_status_action;

		if ( ! function_exists( 'log_message' ) ) {
			/**
			 * Debug logging.
			 *
			 * @param string $message The message to be logged.
			 * @param string $log A string to determine the log type.
			 */
			function log_message( $message, $log = 'default' ) {
				if ( WP_DEBUG_LOG ) {
					$wc_logger = wc_get_logger();
					$context = array( 'source' => $log );
					if ( is_array( $message ) || is_object( $message ) ) {
						$wc_logger->debug( print_r( $message, true ), $context );
					} else {
						$wc_logger->debug( $message, $context );
					}
				}
			}
		}

		// log_message( $recipient ); // log the recipient.

		// Function to output the custom message.
		if ( ! function_exists( 'woo_custom_emails_output_message' ) ) {

			/**
			 * Inserts the custom content into the email template at the chosen location.
			 *
			 * @param object $order An object containing all the Order information.
			 * @param array  $shown_messages An array of which messages have already been added to the email.
			 * @param boolean $sent_to_admin A boolean value if this email is sent to admin or not.
			 */
			function woo_custom_emails_output_message( $order, $sent_to_admin, $shown_messages ) {

				// Show content on Admin emails if setting is enabled.

				$options = get_option( 'woocustomemails_settings_name' );
				$show_in_admin_email_setting = isset( $options['show_in_admin_email'] ) ? $options['show_in_admin_email'] : false;

				if ( false !== $show_in_admin_email_setting ) {
					// Setting is enabled - show the message in the Admin email.
					// The parent function will return the message content.
				} else {
					// Setting is disabled - do not show the message in the Admin email.
					// Exit out of the parent function and return nothing.
					if ( $sent_to_admin ) {
						return;
					}
				}

				if ( ! is_array( $shown_messages ) ) {
					$shown_messages = array();
				}

				global $this_order_status_action;

				// Get items in this order.
				$items = (array) $order->get_items();

				// Loop through all items in this order.
				foreach ( $items as $item ) {

					// Get this product ID.
					$this_product_id = $item['product_id'];

					// Get this meta.
					$orderstatus_meta = (string) get_post_meta( $this_product_id, 'order_status', true );
					$wcemessage_id = get_post_meta( $this_product_id, 'wcemessage_id', true );
					$templatelocation_meta = get_post_meta( $this_product_id, 'location', true );

					/**
					 * New data as of 2.2.0+
					 *
					 * @since 2.2.0
					 */
					$wcemessage_id_onhold = (int) get_post_meta( $this_product_id, 'wcemessage_id_onhold', true );
					$wcemessage_location_onhold = get_post_meta( $this_product_id, 'location_onhold', true );
					$wcemessage_id_processing = (int) get_post_meta( $this_product_id, 'wcemessage_id_processing', true );
					$wcemessage_location_processing = get_post_meta( $this_product_id, 'location_processing', true );
					$wcemessage_id_completed = (int) get_post_meta( $this_product_id, 'wcemessage_id_completed', true );
					$wcemessage_location_completed = get_post_meta( $this_product_id, 'location_completed', true );

					// Set a var to track the current email template location.
					$this_email_template_location = (string) current_action();

					// If there is NEW data assigned...
					if ( ! empty( $wcemessage_id_onhold ) || ! empty( $wcemessage_id_processing ) || ! empty( $wcemessage_id_completed ) ) {

						// ON-HOLD Status.
						if ( 'woocommerce_order_status_on-hold' === $this_order_status_action ) {

							// If there is an email assigned for 'On hold' status...
							if ( ! empty( $wcemessage_id_onhold ) ) {

								// If this message has not already been shown in this email...
								if ( ! in_array( $wcemessage_id_onhold, $shown_messages, true ) ) {

									if ( ! is_array( $shown_messages ) ) {
										$shown_messages = array();
									}

									// If message location setting is equal to the current email template location...
									if ( $wcemessage_location_onhold === $this_email_template_location ) {

										// Show the message!

										// Define output var.
										$output = '';

										if ( 'woocommerce_email_order_meta' === $this_email_template_location || 'woocommerce_email_customer_details' === $this_email_template_location ) {
											// Extra line breaks at the beginning to separate Message content from Email content.
											$output .= '<br><br>';
										}

										// Output the_content of the saved WCE Message ID.
										$output .= nl2br( get_post_field( 'post_content', $wcemessage_id_onhold ) );

										// Extra line breaks at the end to separate Message content from Email content.
										$output .= '<br><br>';

										// Output everything!
										echo $output;

										// Update 'shown_emails' var.
										$shown_messages[] = $wcemessage_id_onhold;

									}
								}
							}
						}

						// PROCESSING Status.
						if ( 'woocommerce_order_status_processing' === $this_order_status_action ) {

							// If there is an email assigned for 'Processing' status...
							if ( ! empty( $wcemessage_id_processing ) ) {

								// If this message has not already been shown in this email...
								if ( ! in_array( $wcemessage_id_processing, $shown_messages ) ) {

									if ( ! is_array( $shown_messages ) ) {
										$shown_messages = array();
									}

									// If message location setting is equal to the current email template location...
									if ( $wcemessage_location_processing === $this_email_template_location ) {

										// Show the message!

										// Define output var.
										$output = '';

										if ( 'woocommerce_email_order_meta' === $this_email_template_location || 'woocommerce_email_customer_details' === $this_email_template_location ) {
											// Extra line breaks at the beginning to separate Message content from Email content.
											$output .= '<br><br>';
										}

										// Output the_content of the saved WCE Message ID.
										$output .= nl2br( get_post_field( 'post_content', $wcemessage_id_processing ) );

										// Extra line breaks at the end to separate Message content from Email content.
										$output .= '<br><br>';

										// Output everything!
										echo $output;

										// Update 'shown_emails' var.
										$shown_messages[] = $wcemessage_id_processing;

									}
								}
							}
						}

						// COMPLETED Status.
						if ( 'woocommerce_order_status_completed' === $this_order_status_action ) {

							// If there is an email assigned for 'Completed' status...
							if ( ! empty( $wcemessage_id_completed ) ) {

								// If this message has not already been shown in this email...
								if ( ! in_array( $wcemessage_id_completed, $shown_messages ) ) {

									if ( ! is_array( $shown_messages ) ) {
										$shown_messages = array();
									}

									// If message location setting is equal to the current email template location...
									if ( $wcemessage_location_completed === $this_email_template_location ) {

										// Show the message!

										// Define output var.
										$output = '';

										if ( 'woocommerce_email_order_meta' === $this_email_template_location || 'woocommerce_email_customer_details' === $this_email_template_location ) {
											// Extra line breaks at the beginning to separate Message content from Email content.
											$output .= '<br><br>';
										}

										// Output the_content of the saved WCE Message ID.
										$output .= nl2br( get_post_field( 'post_content', $wcemessage_id_completed ) );

										// Extra line breaks at the end to separate Message content from Email content.
										$output .= '<br><br>';

										// Output everything!
										echo $output;

										// Update 'shown_emails' var.
										$shown_messages[] = $wcemessage_id_completed;

									}
								}
							}
						}
					} else {

						// If there is a legacy WCE Message assigned...
						if ( $wcemessage_id ) {

							// If order status setting is equal to the current order status action...
							if ( $orderstatus_meta == $this_order_status_action ) {

								// If this message has not already been shown in this email...
								if ( ! in_array( $wcemessage_id, $shown_messages ) ) {

									// If template location setting is equal to the current email template location...
									if ( $templatelocation_meta == $this_email_template_location ) {

										// Show the message!

										// Define output var.
										$output = '';

										if ( 'woocommerce_email_order_meta' === $this_email_template_location || 'woocommerce_email_customer_details' === $this_email_template_location ) {
											// Extra line breaks at the beginning to separate Message content from Email content.
											$output .= '<br><br>';
										}

										// Output the_content of the saved WCE Message ID.
										$output .= nl2br( get_post_field( 'post_content', $wcemessage_id ) );

										// Extra line breaks at the end to separate Message content from Email content.
										$output .= '<br><br>';

										// Output everything!
										echo $output;

										// Update 'shown_emails' var.
										$shown_messages[] = $wcemessage_id;

									}
								}
							}
						}
					}
				}

			} // woo_custom_emails_output_message()

		}

		// Add an action for each email template location to insert our custom message.
		add_action( 'woocommerce_email_before_order_table', 'woo_custom_emails_output_message', 10, 3 );
		add_action( 'woocommerce_email_after_order_table', 'woo_custom_emails_output_message', 10, 3 );
		add_action( 'woocommerce_email_order_meta', 'woo_custom_emails_output_message', 10, 3 );
		add_action( 'woocommerce_email_customer_details', 'woo_custom_emails_output_message', 10, 3 );

	} // woo_custom_emails_insert_content()

}
