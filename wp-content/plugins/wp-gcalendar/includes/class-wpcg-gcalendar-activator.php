<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Wp_Gcalendar
 * @subpackage Wp_Gcalendar/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Gcalendar
 * @subpackage Wp_Gcalendar/includes
 * @author     Your Name <email@example.com>
 */
class Wpcg_Gcalendar_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;

		$table_name = $wpdb->prefix . "events";
		
		if ( $wpdb->get_var('SHOW TABLES LIKE ' . $table_name) != $table_name )
		{
			$sql = 'CREATE TABLE ' . $table_name . '( 
					kind VARCHAR (255),
					etag VARCHAR (255),
					id VARCHAR (255) NOT NULL,
					status VARCHAR (255),
					htmlLink VARCHAR (255),
					created DATETIME,
					updated DATETIME,
					summary VARCHAR (255),
					description TEXT,
					location VARCHAR (255),
					colorId VARCHAR (255),
					start_Date DATE,
					start_Time TIME,
					start_TimeZone VARCHAR (255),
					end_Date DATE,
					end_Time TIME,
					attendees TEXT,
					notification VARCHAR (255),
					time INTEGER(10),
					end_TimeZone VARCHAR (255),
					localID INTEGER(10) UNSIGNED AUTO_INCREMENT,
					CalenderID VARCHAR (255),
					PRIMARY KEY  (localID))';

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
			
			add_option('events_database_version','1.0');
		}

		if($wpdb->query("SHOW TABLES LIKE '$table_name'")){
			if (($tablefields = $wpdb->get_results( 'SHOW COLUMNS FROM '.$table_name, OBJECT )) == TRUE) {
	            $columns = count($tablefields);
	            $field_array = array();
	            for ($i = 0; $i < $columns; $i++) {
	                $fieldname = $tablefields[$i]->Field;
	                $field_array[] = $fieldname;
	            }
	        }


			if (!in_array('attendees', $field_array))
			{
			$wpdb->query('ALTER TABLE '. $table_name .' ADD attendees TEXT');
			}
			if (!in_array('notification', $field_array))
			{
			$wpdb->query('ALTER TABLE '. $table_name .' ADD notification VARCHAR(250)');
			}


			$wpdb->query('ALTER TABLE '. $table_name .' MODIFY description TEXT;');
		}

		$table_setting = $wpdb->prefix . "api_setting";
		if ( $wpdb->get_var('SHOW TABLES LIKE ' . $table_setting) != $table_setting )
		{
			$sql = 'CREATE TABLE ' . $table_setting . '( 
					id_setting INTEGER(10) UNSIGNED AUTO_INCREMENT,
					clientID VARCHAR (255),
					calendarID VARCHAR (255),
					defaultDate DATE,
					priority VARCHAR (255),
					PRIMARY KEY  (id_setting) )';
			
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
			add_option('api_setting_database_version','1.0');
		}

		if($wpdb->query("SHOW TABLES LIKE '$table_setting'")){
			if (($tablefields = $wpdb->get_results( 'SHOW COLUMNS FROM '.$table_setting, OBJECT )) == TRUE) {
	            $columns = count($tablefields);
	            $field_array = array();
	            for ($i = 0; $i < $columns; $i++) {
	                $fieldname = $tablefields[$i]->Field;
	                $field_array[] = $fieldname;
	            }
	        }


			if (!in_array('notification', $field_array))
			{
			$wpdb->query('ALTER TABLE '. $table_setting .' ADD lang VARCHAR(250)');
			}

		}

	}

}
