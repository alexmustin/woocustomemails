<?php
/**
 * Responsible for coordinating actions and filters
 * between the core plugin and the administration class.
 *
 * @package woo_custom_emails_domain\includes
 */
class Woo_Custom_Emails_Per_Product_Loader {

	// Define protected vars
	protected $actions;
	protected $filters;

	// Class constructor
	public function __construct() {

		// Define vars as arrays
		$this->actions = array();
		$this->filters = array();

	}

	// Function to add ACTIONS to array above
	public function add_action( $hook, $component, $callback ) {
		$this->actions = $this->add( $this->actions, $hook, $component, $callback );
	}

	// Function to add FILTERS to array above
	public function add_filter( $hook, $component, $callback ) {
		$this->filters = $this->add( $this->filters, $hook, $component, $callback );
	}

	// Function to add items into an array
	private function add( $hooks, $hook, $component, $callback ) {

		$hooks[] = array(
			'hook'      => $hook,
			'component' => $component,
			'callback'  => $callback
		);

		return $hooks;

	}

	public function run() {

		foreach ( $this->filters as $hook ) {
			add_filter( $hook['hook'], array( $hook['component'], $hook['callback'] ) );
		}

		foreach ( $this->actions as $hook ) {
			add_action( $hook['hook'], array( $hook['component'], $hook['callback'] ) );
		}

	}

}

