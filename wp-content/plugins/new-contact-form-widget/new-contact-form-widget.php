<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
/*
Plugin Name:  New Contact Form Widget & Shortcode [Standard] 
Plugin URI: http://awplife.com/product/contact-form-premium/
Description: Add Contact Form Widget and Shortcode On WordPress
Version: 0.4.3
Author: A WP Life
Author URI: http://awplife.com/product/contact-form-premium/
Text Domain: NCFWS
*/

// create table when pluign activate
register_activation_hook( __FILE__, 'cfw_install_script' );
function cfw_install_script() {
	//load create table file here
	global $wpdb;
	$table_name = $wpdb->prefix . "awp_contact_form";
	$create_contact_form_query = "CREATE TABLE IF NOT EXISTS `$table_name` (
	`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`name` varchar(256) NOT NULL,
	`email` varchar(256) NOT NULL,
	`subject` varchar(256) NOT NULL,
	`message` text NOT NULL,
	`date_time` datetime NOT NULL,
	`status` varchar(50) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
	$wpdb->query($create_contact_form_query);
}

// run when you de-activate this plugin
register_deactivation_hook( __FILE__, 'cfw_uninstall_script' );
function cfw_uninstall_script(){
	//load delete table file here
	// delete table when pluign deactivate
	//global $wpdb;
	//$table_name = $wpdb->prefix . "awp_contact_form";
	//$delete_contact_form_query = "DROP TABLE $table_name";
	//$wpdb->query($delete_contact_form_query);
}

// CFW Shortcode
require_once('shortcode.php');

// ajax action
add_action( 'wp_ajax_submit_user_query', 'submit_user_query_handle' );
add_action( 'wp_ajax_nopriv_submit_user_query', 'submit_user_query_handle' ); // need this to serve non logged in users

function submit_user_query_handle(){
	if(isset($_POST['action']) && $_POST['formsdata']) {
		$cfw_query_nonce_value = $_POST['security'];
		if(!wp_verify_nonce( $cfw_query_nonce_value, 'cfw_query_nonce' )) {
			$action = $_POST['action'];
			//convert sterilise forms data into array
			$cfw_data = array();
			parse_str($_POST['formsdata'], $cfw_data);
			global $wpdb;
			if($action == "submit_user_query") {
				$name = sanitize_text_field($cfw_data['name']);
				$email = sanitize_email($cfw_data['email']);
				$subject = sanitize_text_field($cfw_data['subject']);
				$message = sanitize_text_field($cfw_data['message']);
				
				// table name
				$cfw_table_name = $wpdb->prefix . 'awp_contact_form';

				//data array
				$cfw_columns_data = array(
					//column_name => field_value
					'id' => NULL,
					'name' => $name,
					'email' => $email,
					'subject' => $subject,
					'message' => $message,
					'date_time' => date("Y-m-d h:i:s"),
					'status' => 'pending'
				);

				//format array
				$cfw_data_format = array('%d', '%s', '%s', '%s', '%s', '%s', '%s');
				
				// load saved message
				$all_setttings = get_option('contact_form_settings');
				if(isset($all_setttings)){
					$qsm = $all_setttings['qsm'];
					$qfm = $all_setttings['qfm'];
				}
				
				if($wpdb->insert( $cfw_table_name, $cfw_columns_data, $cfw_data_format)) {
					if($qsm == "") echo "Thank you for submitting query. We will be back to you shortly."; else echo $qsm;
				} else {
					if($qfm == "") echo "Sorry! contact from not working properly. Please directly contact to site admin using this email: ".get_option( 'admin_email' ); else echo $qfm;
				}
			}
		}// verify query nonce value
	}// end of isset
}

add_action( 'widgets_init', function(){
	register_widget( 'cfw_Widget' );
});

class cfw_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array( 
			'classname' => 'contact_form',
			'description' => 'Display contact form to your visitors.',
		);
		parent::__construct( 'contact_form', 'Contact Form Widget', $widget_ops );
	}

	/**
	 * Outputs of the widget
	 */
	public function widget( $args, $instance ) {
		
		//css
		wp_enqueue_style( 'cfw-bootstrap-css', plugin_dir_url( __FILE__ ).'css/cfw-bootstrap.css' );
		wp_enqueue_style( 'cfw-font-awesome-css', plugin_dir_url( __FILE__ ).'css/font-awesome.min.css' );
		
		//js
		wp_enqueue_script( 'jquery');
		wp_enqueue_script( 'cfw-bootstrap-js', plugin_dir_url( __FILE__ ) . 'js/bootstrap.js', array('jquery'), '3.3.6', false );
		wp_enqueue_script( 'cfw-ajax', plugin_dir_url( __FILE__ ) . 'js/cfw-ajax.js', array( 'jquery' ), '', true );
		wp_localize_script( 'cfw-ajax', 'cfw_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );		
		
		echo $args['before_widget'];
		// widget title
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		// load saved setting from option table
		$all_setttings = get_option('contact_form_settings');
		//print_r($all_setttings);
		if(isset($all_setttings)){
			// Design Setting	
			if($all_setttings['title_field']) 
				$title_field = $all_setttings['title_field'];
			else 
				$title_field = "Contact Form";
			
			if($all_setttings['title_color']) 
				$title_color = $all_setttings['title_color'];
			else 
				$title_color = "#FAFAFA";
			
			if($all_setttings['description_field']) 
				$description_field = $all_setttings['description_field'];
			else 
				$description_field = "Please fill below form if you have any query with us.";
			
			if($all_setttings['name_field']) 
				$name_field = $all_setttings['name_field'];
			else 
				$name_field = "Type Your Name Here";
			
			if($all_setttings['email_field']) 
				$email_field = $all_setttings['email_field'];
			else 
				$email_field = "Type Your Email Here";
			
			if($all_setttings['subject_field']) 
				$subject_field = $all_setttings['subject_field'];
			else 
				$subject_field = "Type Your Query Subject Here";
			
			if($all_setttings['message_field']) 
				$message_field = $all_setttings['message_field'];
			else 
				$message_field = "Type Your Query Message Here";
		
		
			if($all_setttings['name_error_field']) 
				$name_error_field = $all_setttings['name_error_field'];
			else 
				$name_error_field = "Name cannot be blank.";
		
			if($all_setttings['email_error_field']) 
					$email_error_field = $all_setttings['email_error_field'];
				else 
					$email_error_field = "Email cannot be blank.";
			
			if($all_setttings['email_error_field_2']) 
					$email_error_field_2 = $all_setttings['email_error_field_2'];
				else 
					$email_error_field_2 = "Email is invalid.";
			
			if($all_setttings['subject_error_field']) 
					$subject_error_field = $all_setttings['subject_error_field'];
				else 
					$subject_error_field = "Subject cannot be blank.";
			
			if($all_setttings['message_error_field']) 
					$message_error_field = $all_setttings['message_error_field'];
				else 
					$message_error_field = "Message cannot be blank.";
			
			if($all_setttings['ph_text_color']) 
				$ph_text_color = $all_setttings['ph_text_color'];
			else 
				$ph_text_color = "black";
			
			if($all_setttings['lebal_icon_color']) 
				$lebal_icon_color = $all_setttings['lebal_icon_color'];
			else 
				$lebal_icon_color = "black";
			
			if($all_setttings['bg_color']) 
				$bg_color = $all_setttings['bg_color'];
			else 
				$bg_color = "#FFFFFF";
			
			if($all_setttings['show_query']) 
				$show_query = $all_setttings['show_query'];
			else 
				$show_query = "";
			
			if($all_setttings['sb_button_color']) 
				$sb_button_color = $all_setttings['sb_button_color'];
			else 
				$sb_button_color = "btn-info";
			
			if($all_setttings['sb_button_text']) 
				$sb_button_text = $all_setttings['sb_button_text'];
			else 
				$sb_button_text = "Submit";
			
			if($all_setttings['cus_css']) 
				$cus_css = $all_setttings['cus_css'];
			else 
				$cus_css = "";
		
			// Message Setting
			if($all_setttings['qsm']) 
				$qsm = $all_setttings['qsm'];
			else 
				$qsm = "Thank you for submitting query. We will be back to you shortly.";
			
			if($all_setttings['qfm'])
				$qfm = $all_setttings['qfm'];
			else
				$qfm = "Sorry! contact from not working properly. Please directly contact to site admin using this email: ".get_option( 'admin_email' );
		} else {
			
			$title_field = "Contact Form";
			$title_color = "#FAFAFA";
			$description_field = "Please fill below form if you have any query with us.";
			$name_field = "Type Your Name Here";
			$email_field = "Type Your Email Here";
			$subject_field = "Type Your Query Subject Here";
			$message_field = "Type Your Query Message Here";
			$name_error_field = "Name cannot be blank.";
			$email_error_field = "Email cannot be blank.";
			$email_error_field_2 = "Email is invalid.";
			$subject_error_field = "Subject cannot be blank.";
			$message_error_field = "Message cannot be blank.";
			$ph_text_color = "black";
			$lebal_icon_color = "black";
			$bg_color = "#FFFFFF";
			$show_query = "";
			$sb_button_color = "btn-info";
			$sb_button_text = "Submit";
			$cus_css= "";
			
			$qsm = "Thank you for submitting query. We will be back to you shortly.";
			$qfm = "Sorry! contact from not working properly. Please directly contact to site admin using this email: ".get_option( 'admin_email' );
		}
		?>
		<style>
		.cwf-title {
			color:<?php echo $title_color; ?> !important;
		}
		.cwf-desc {
			color:<?php echo $title_color; ?> !important;
		}
		.cfw-form {			
			background-color: <?php echo $bg_color; ?>!important;
			padding: 10px;
			border-radius:5px;
		}
		.cfw-form label{
			color: <?php echo $lebal_icon_color; ?>;
		}
		
		/* place holder css */
		.cfw-form ::-webkit-input-placeholder {
		   color: <?php echo $ph_text_color; ?> !important;
		}

		.cfw-form :-moz-placeholder { /* Firefox 18- */
		   color: <?php echo $ph_text_color; ?>!important;
		}

		.cfw-form ::-moz-placeholder {  /* Firefox 19+ */
		   color: <?php echo $ph_text_color; ?> !important;
		}

		.cfw-form :-ms-input-placeholder {  
		   color: <?php echo $ph_text_color; ?>!important;
		}
		.cfw-error {
			display: none;
			padding: 7px !important;
		}
			<?php echo $cus_css; ?>
			
		</style>
		<form id="user-contact-form" name="user-contact-form" class="cfw-form">
			<h2 class="text-center cwf-title"><?php echo esc_html($title_field); ?></h2>
			<p class="text-center cwf-desc"><?php echo esc_html($description_field); ?></p>			
			
			<div class="form-group">
				<label for="name"><i class="fa fa-user" aria-hidden="true"></i> Name</label>
				<input type="text" class="form-control" id="name" name="name" value="" placeholder="<?php echo esc_html($name_field); ?>">
				<span class="cfw-error name-error alert alert-warning"><strong><?php echo esc_html($name_error_field); ?></strong></span>
			</div>
			
			<div class="form-group">
				<label for="email"><i class="fa fa-envelope" aria-hidden="true"></i> Email</label>
				<input type="text" class="form-control" id="email" name="email" value="" placeholder="<?php echo esc_html($email_field); ?>">
				<span class="cfw-error email-error alert alert-warning"><strong><?php echo esc_html($email_error_field); ?></strong></span>
				<span class="cfw-error email-error-2 alert alert-warning"><strong><?php echo esc_html($email_error_field_2); ?></strong></span>
			</div>
			
			<div class="form-group">
				<label for="subject"><i class="fa fa-asterisk" aria-hidden="true"></i> Subject</label>
				<input type="text" class="form-control" id="subject" name="subject" value="" placeholder="<?php echo esc_html($subject_field); ?>">
				<span class="cfw-error subject-error alert alert-warning"><strong><?php echo esc_html($subject_error_field); ?></strong></span>
			</div>
			
			<div class="form-group">
				<label for="message"><i class="fa fa-comment" aria-hidden="true"></i> Message</label>
				<textarea class="form-control" id="message" name="message" placeholder="<?php echo esc_html($message_field); ?>"></textarea>
				<span class="cfw-error message-error alert alert-warning"><strong><?php echo esc_html($message_error_field); ?></strong></span>
			</div>
		
			<div class="form-group">
				<button type="button" class="btn <?php echo $sb_button_color; ?>"  onclick="return ValidateForm('<?php echo wp_create_nonce( 'cfw_query_nonce' ); ?>');"><?php echo ucwords($sb_button_text); ?></button>
			</div>
		</form>

		<!--loading icon-->
		<div id="awp-loading-icon" class="text-center" style="display: none;">
			<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><br>
			Please wait submitting your query.
		</div>
		
		<!--Ajax result-->
		<div id="contact-result" style="display: none;">
		</div>
		<?php
		echo $args['after_widget'];
	}

	/**
	 * Outputs Form For Admin
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( '', 'text_domain' );
		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( esc_attr( 'Title:' ) ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
		echo "<p><a href='admin.php?page=cfw-settings'>Configure Widget Settings</a></p>";
		echo "<p><strong>Important Note:</strong></p>";
		echo "<p>Don't use multiple shortcode on same Widget / Sidebar Area.</p>";
		echo "<p>Also, don't activate multiple Contact Form Widget into multiple Widget / Sidebar Area like (Sidebar Widgets / Footer Widgets / Header Widgets)</p>";
	}

	/**
	 * Processing widget options on save
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}
}

// Contact Form Widget Menu Page For Administrator
// For mange all contact queries & contact form widget settings
require_once('cfw-menu-pages.php');
?>