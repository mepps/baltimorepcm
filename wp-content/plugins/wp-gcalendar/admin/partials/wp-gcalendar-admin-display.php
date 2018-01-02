<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Wp_Gcalendar
 * @subpackage Wp_Gcalendar/admin/partials
 */


$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$actual_link_ssl = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$url = admin_url( 'admin.php?page=google-calendar-plg');
$url2 = admin_url( 'admin.php?page=google-calendar-event');
$url3 = admin_url( 'admin.php?page=google-calendar-settings');



require_once plugin_dir_path( __FILE__ ) . 'settings.php';

require_once plugin_dir_path( __FILE__ ) . 'events.php';

require_once plugin_dir_path( __FILE__ ) . 'functions.php';

require_once plugin_dir_path( __FILE__ ) . 'ajax-callback.php';

require_once plugin_dir_path( __FILE__ ) . 'shortcodes.php';

require_once plugin_dir_path( __FILE__ ) . 'widgets.php';

require_once plugin_dir_path( __FILE__ ) . 'documentation.php';

require_once plugin_dir_path( __FILE__ ) . 'trans.php';
