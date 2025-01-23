<?php
/**
 * Plugin Name:     WP Schema
 * Plugin URI:      https://plugins.wp-cli.org/demo-plugin
 * Description:     This is a wp-cli demo plugin
 * Author:          wp-cli
 * Author URI:      https://wp-cli.org
 * Text Domain:     wp-schema
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         WP_Schema
 */

defined('ABSPATH') || exit;

// Define constants.
define( 'SCHEMA_VERSION', '0.1.0' );
define( 'SCHEMA_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'SCHEMA_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
define( 'SCHEMA_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );


require_once dirname( __FILE__ ) . '/includes/class-wp-schema.php';


/**
 * Main instance of WP_Schema.
 *
 * Returns the main instance of Formnx to prevent the need to use globals.
 *
 * @since  1.26
 * @return Formnx
 */
function WPSCHEMA() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
	return WP_Schema::instance();
}

$GLOBALS['wp_schema'] = WPSCHEMA();