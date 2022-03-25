<?php
/**
 * Loads actions and filters for the Genesis framework.
 *
 * @package WooCustomEmails
 */

/**
 * The Woo_Custom_Emails_Per_Product_Loader class is responsible for
 * coordinating actions and filters between the core plugin and the
 * administration class.
 *
 * @package woo_custom_emails_domain\includes
 */
class Woo_Custom_Emails_Per_Product_Loader {

	// Define protected vars.

	/**
	 * Track all actions to run.
	 *
	 * @var array $actions An array of actions to run.
	 */
	protected $actions;

	/**
	 * Track all filters to run.
	 *
	 * @var array $filters An array of filters to run.
	 */
	protected $filters;

	/**
	 * Class constructor.
	 */
	public function __construct() {

		// Define vars as arrays.
		$this->actions = array();
		$this->filters = array();

	}

	/**
	 * Adds all the actions into the $actions array above.
	 *
	 * @param object $hook       The hook to run.
	 * @param object $component  The component to load in to this hook.
	 * @param object $callback   The function to run.
	 */
	public function add_action( $hook, $component, $callback ) {
		$this->actions = $this->add( $this->actions, $hook, $component, $callback );
	}

	/**
	 * Adds all the filters into the $filters array above.
	 *
	 * @param object $hook       The hook to run.
	 * @param object $component  The component to load in to this hook.
	 * @param object $callback   The function to run.
	 */
	public function add_filter( $hook, $component, $callback ) {
		$this->filters = $this->add( $this->filters, $hook, $component, $callback );
	}

	/**
	 * Adds items into an array
	 *
	 * @param object $hooks      An object of hooks to run.
	 * @param object $hook       A single hook to run.
	 * @param object $component  The action or filter to run during this hook.
	 * @param object $callback   The function to run.
	 */
	private function add( $hooks, $hook, $component, $callback ) {

		$hooks[] = array(
			'hook'      => $hook,
			'component' => $component,
			'callback'  => $callback,
		);

		return $hooks;

	}

	/**
	 * Runs all the actions and filters.
	 *
	 * @return void
	 */
	public function run() {

		foreach ( $this->filters as $hook ) {
			add_filter( $hook['hook'], array( $hook['component'], $hook['callback'] ) );
		}

		foreach ( $this->actions as $hook ) {
			add_action( $hook['hook'], array( $hook['component'], $hook['callback'] ) );
		}

	}

}
