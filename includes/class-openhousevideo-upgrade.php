<?php

/**
 * The class responsible for plugin upgrade procedures.
 *
 * @since        1.0.0
 * @package      OpenHouseVideo
 * @subpackage   OpenHouseVideo/includes
 */
final class OpenHouseVideo_Upgrade {

	/**
	 * The path to the main plugin file.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_file   The path to the main plugin file.
	 */
	private $plugin_file;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $current_version   The current version of the plugin.
	 */
	private $current_version;

	/**
	 * Name of the option which stores plugin version.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $option_name   Name of the option which stores plugin version.
	 */
	private $option_name;

	/**
	 * The previous saved version.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $saved_version   The previous saved version.
	 */
	private $saved_version;

	/**
	 * Define the functionality of the updater.
	 *
	 * @since   1.0.0
	 * @param string  $plugin_file    The path to the main plugin file.
	 * @param string  $plugin_version The current version of the plugin.
	 */
	public function __construct( $plugin_file, $plugin_version ) {

		$this->plugin_file     = $plugin_file;
		$this->current_version = $plugin_version;
		$this->option_name     = 'ohv_option_version';
		$this->saved_version   = get_option( $this->option_name, 0 );

	}

	/**
	 * Run upgrade procedures (if needed).
	 *
	 * @since  1.0.0
	 */
	public function maybe_upgrade() {

		if ( ! $this->is_version_changed() ) {
			return;
		}

		$this->save_current_version();

	}

	/**
	 * Conditional check if plugin was updated.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return boolean True if plugin was updated, False otherwise.
	 */
	private function is_version_changed() {
		return $this->is_previous_version_less_than( $this->current_version );
	}

	/**
	 * Conditional check if previous version of the plugin less than passed one.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return boolean True if previous version of the plugin less than passed one, False otherwise.
	 */
	private function is_previous_version_less_than( $version ) {
		return version_compare( $this->saved_version, $version, '<' );
	}

	/**
	 * Save current version number.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function save_current_version() {
		update_option( $this->option_name, $this->current_version, false );
	}
}
