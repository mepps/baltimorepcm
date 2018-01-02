<?php

/**
 * Plugin Name: WP GCalendar
 * Plugin URI: http://eurocizia.com/#products
 * Description: Wordpress plugin to manage your google calendar events.
 * Author: Eurocizia team
 * Version: 2.1
 * Author URI: http://www.eurocizia.com/
 * Text Domain: wp-gcalendar
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-gcalendar-activator.php
 */
function activate_wpcg_gcalendar() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpcg-gcalendar-activator.php';
	Wpcg_Gcalendar_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-gcalendar-deactivator.php
 */
function deactivate_wp_gcalendar() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-gcalendar-deactivator.php';
	Wp_Gcalendar_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wpcg_gcalendar' );
register_deactivation_hook( __FILE__, 'deactivate_wp_gcalendar' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wpgc-gcalendar.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/partials/wp-gcalendar-admin-display.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wpcg_gcalendar() {

	$plugin = new Wpcg_Gcalendar();
	$plugin->run();

}
run_wpcg_gcalendar();
function myplugin_update_db_check() {
	    //echo '<script> alert("adil") </script>';
	}
	add_action( 'plugins_loaded', 'myplugin_update_db_check' );
