<?php

/**
 *
 * @link              https://www.getlisten2it.com
 * @since             1.0.0
 * @package           Listen2it
 *
 * @wordpress-plugin
 * Plugin Name:       Listen2It - Text-to-speech audio articles
 * Plugin URI:        https://www.getlisten2it.com
 * Description:       Listen2It helps you convert your articles and blog posts into audio versions instantly and without any effort using lifelike voices in 75+ languages
 * Version:           1.0.2
 * Author:            Listen2It
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       listen2it
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'LISTEN2IT_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-listen2it-activator.php
 */
function activate_listen2it() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-listen2it-activator.php';
	Listen2it_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-listen2it-deactivator.php
 */
function deactivate_listen2it() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-listen2it-deactivator.php';
	Listen2it_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_listen2it' );
register_deactivation_hook( __FILE__, 'deactivate_listen2it' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-listen2it.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_listen2it() {

	$plugin = new Listen2it();
	$plugin->run();

}
run_listen2it();
