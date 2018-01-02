<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Wp_Gcalendar
 * @subpackage Wp_Gcalendar/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Gcalendar
 * @subpackage Wp_Gcalendar/public
 * @author     Your Name <email@example.com>
 */
class Wp_Gcalendar_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $wp_gcalendar    The ID of this plugin.
	 */
	private $wp_gcalendar;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $wp_gcalendar       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $wp_gcalendar, $version ) {

		$this->wp_gcalendar = $wp_gcalendar;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Gcalendar_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Gcalendar_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->wp_gcalendar, plugin_dir_url( __FILE__ ) . 'css/wp-gcalendar-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'wp-gc-style', plugins_url('css/wp-gc-style.css', __FILE__));
	    wp_enqueue_style( 'fullcalendar', plugins_url('css/fullcalendar.css', __FILE__));
	    wp_enqueue_style( 'jquery.qtip', plugins_url('css/jquery.qtip.css', __FILE__));
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Gcalendar_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Gcalendar_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->wp_gcalendar, plugin_dir_url( __FILE__ ) . 'js/wp-gcalendar-public.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script('jquery');
	    wp_enqueue_script( 'moment.min', plugins_url('js/moment.min.js', __FILE__) );
	    wp_enqueue_script( 'fullcalendar.min', plugins_url('js/fullcalendar.min.js', __FILE__) );
	    wp_enqueue_script( 'lang-all', plugins_url('js/lang-all.js', __FILE__) );
	    wp_enqueue_script( 'jquery.qtip', plugins_url('js/jquery.qtip.js', __FILE__) );
	}

	

}
