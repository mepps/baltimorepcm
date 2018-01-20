<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function contact_form_shortcode_function( $atts ){
	///css
	wp_enqueue_style( 'cfw-bootstrap-css', plugin_dir_url( __FILE__ ).'css/cfw-bootstrap.css' );
	wp_enqueue_style( 'cfw-font-awesome-css', plugin_dir_url( __FILE__ ).'css/font-awesome.min.css' );
		
	//js
	wp_enqueue_script( 'jquery');
	wp_enqueue_script( 'cfw-bootstrap-js', plugin_dir_url( __FILE__ ) . 'js/bootstrap.js', array('jquery'), '3.3.6', false );
	wp_enqueue_script( 'cfw-ajax', plugin_dir_url( __FILE__ ) . 'js/cfw-ajax.js', array( 'jquery' ), '', true );
	wp_localize_script( 'cfw-ajax', 'cfw_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	wp_enqueue_style( 'wp-color-picker' ); 
	wp_enqueue_script( 'cfw-color-picker-js',  plugin_dir_url( __FILE__ ).'js/cfw-color-picker.js', array( 'jquery', 'wp-color-picker' ), '', true  );
	
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
			$show_query = 10;
		
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
		$show_query = 10;
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
		   color: <?php echo $ph_text_color; ?>!important;
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
		.form-group {
			padding-top:15px;
			padding-bottom:15px;		
		}
		.cfw-error {
			display: none;
			padding: 7px !important;
		}
			<?php echo $cus_css; ?>
		</style>
		<!--gogle captcha script-->
		
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
				<button type="button" class="btn <?php echo $sb_button_color; ?>"  onclick="return ValidateForm('<?php echo wp_create_nonce( "cfw_query_nonce" ); ?>');"><?php echo ucwords($sb_button_text); ?></button>
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
}
add_shortcode( 'CFW', 'contact_form_shortcode_function' );
?>