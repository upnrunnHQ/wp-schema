<?php
/**
 * File containing the class WP_Schema.
 *
 * @package WP_Schema
 * @since   1.33.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles core plugin hooks and action setup.
 *
 * @since 1.0.0
 */
class WP_Schema {
	/**
	 * The single instance of the class.
	 *
	 * @var self
	 * @since  1.26.0
	 */
	private static $instance = null;

	/**
	 * Main WP_Schema Instance.
	 *
	 * Ensures only one instance of WP_Schema is loaded or can be loaded.
	 *
	 * @since  0.0.1
	 * @static
	 * @see WPSCHEMA()
	 * @return self Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Includes.
		include_once SCHEMA_PLUGIN_DIR . '/includes/model.php';
		include_once SCHEMA_PLUGIN_DIR . '/includes/class-wp-model-type.php';
		include_once SCHEMA_PLUGIN_DIR . '/includes/class-wp-create-schema.php';
		include_once SCHEMA_PLUGIN_DIR . '/includes/class-wp-update-schema.php';
		include_once SCHEMA_PLUGIN_DIR . '/includes/class-wp-model-query.php';

		// Actions.
		add_action( 'after_setup_theme', [ $this, 'load_plugin_textdomain' ] );
	}

	/**
	 * Loads textdomain for plugin.
	 */
	public function load_plugin_textdomain() {
		load_textdomain( 'wp-schema', WP_LANG_DIR . '/wp-schema/wp-schema-' . apply_filters( 'plugin_locale', get_locale(), 'wp-schema' ) . '.mo' );
		load_plugin_textdomain( 'wp-schema', false, SCHEMA_PLUGIN_DIR . '/languages/' );
	}
}