<?php 
function wpgc_settings()
{ 
	global $wpdb; 
	$table_name = $wpdb->prefix . 'api_setting';
	if($_POST){
		$id_setting = intval($_POST['id_setting']);
		$clientID = sanitize_text_field($_POST['clientID']);
		$calendarID = sanitize_text_field($_POST['calendarID']);
		$lang = sanitize_text_field($_POST['lang']);
		if(isset($_POST['id_setting'])){
			$data = array(
				'id_setting' => $id_setting,
				'clientID' => $clientID,
				'calendarID' => $calendarID,
				'lang' => $lang,
				);
			$wpdb->update($table_name,$data,array( 'id_setting' => $_POST['id_setting'] )); ?>
<p class="wpgc-done">Settings updated !</p>
			<?php
		}else{
			$data = array(
				'clientID' => $clientID,
				'calendarID' => $calendarID,
				'lang' => $lang,
				);
			$wpdb->insert($table_name,$data); ?>
<p class="wpgc-done">Settings saved!</p>
			<?php
		}
		
	}
	$settings = $wpdb->get_row('select * from ' . $table_name );
	?>
	<div class="wrap">
	<form action="" method="post" id="insert-event">
	<?php if($settings){ ?>
	<input type="hidden" id="id_setting" name="id_setting" 
	value="<?php if($settings){ echo $settings->id_setting; } ?>" />
	<?php } ?>
	
	<table class="wpgc-setting-table">
		
		<tr>
			<td colspan="2" class="entry-view-field-name"><?php echo strip_tags( __( 'Settings', 'wp-gcalendar' ) ); ?></td>
		</tr>
		
		<tr>
			<td>
				<h3><label for="clientID"><?php echo strip_tags( __( 'Client ID', 'wp-gcalendar' ) ); ?> </label> </h3>
			</td>
			<td>
				<input type="text" id="clientID" class="wpgc-input" name="clientID" value="<?php if($settings){ echo esc_attr($settings->clientID); } ?>"/>
				
			</td>
		</tr>
		<tr>
			<td>
				<h3><label for="calendarID"><?php echo strip_tags( __( 'Calendar ID', 'wp-gcalendar' ) ); ?> </label> </h3>
			</td>
			<td>
				<input type="text" id="calendarID" class="wpgc-input" name="calendarID"
				value="<?php if($settings){ echo esc_attr($settings->calendarID); } ?>"/>
			</td>
		</tr>
		<tr>
			<td>
				<h3><label for="lang"><?php echo strip_tags( __( 'Calendar Language', 'wp-gcalendar' ) ); ?> </label> </h3>
			</td>
			<td>
				<select name="lang">
					<option value="en" <?php if($settings){ if($settings->lang == "en"){ echo 'selected'; } } ?>><?php echo strip_tags( __( 'English', 'wp-gcalendar' ) ); ?></option>
					<option value="ar" <?php if($settings){ if($settings->lang == "ar"){ echo 'selected'; } } ?>><?php echo strip_tags( __( 'Arabic', 'wp-gcalendar' ) ); ?></option>
					<option value="es" <?php if($settings){ if($settings->lang == "es"){ echo 'selected'; } } ?>><?php echo strip_tags( __( 'Spanish', 'wp-gcalendar' ) ); ?></option>
					<option value="fr" <?php if($settings){ if($settings->lang == "fr"){ echo 'selected'; } } ?>><?php echo strip_tags( __( 'French', 'wp-gcalendar' ) ); ?></option>
					<option value="nl" <?php if($settings){ if($settings->lang == "nl"){ echo 'selected'; } } ?>><?php echo strip_tags( __( 'Dutch', 'wp-gcalendar' ) ); ?></option>
					<option value="it" <?php if($settings){ if($settings->lang == "it"){ echo 'selected'; } } ?>><?php echo strip_tags( __( 'Italian', 'wp-gcalendar' ) ); ?></option>
					<option value="de" <?php if($settings){ if($settings->lang == "de"){ echo 'selected'; } } ?>><?php echo strip_tags( __( 'German', 'wp-gcalendar' ) ); ?></option>
					<option value="pt" <?php if($settings){ if($settings->lang == "pt"){ echo 'selected'; } } ?>><?php echo strip_tags( __( 'Portuguese', 'wp-gcalendar' ) ); ?></option>
					<option value="fa" <?php if($settings){ if($settings->lang == "fa"){ echo 'selected'; } } ?>><?php echo strip_tags( __( 'Persian', 'wp-gcalendar' ) ); ?></option>
					<option value="ja" <?php if($settings){ if($settings->lang == "ja"){ echo 'selected'; } } ?>><?php echo strip_tags( __( 'Japanese', 'wp-gcalendar' ) ); ?></option>
					<option value="hi" <?php if($settings){ if($settings->lang == "hi"){ echo 'selected'; } } ?>><?php echo strip_tags( __( 'Hindi', 'wp-gcalendar' ) ); ?></option>
					<option value="hu" <?php if($settings){ if($settings->lang == "hu"){ echo 'selected'; } } ?>><?php echo strip_tags( __( 'Hungarian', 'wp-gcalendar' ) ); ?></option>
					<option value="id" <?php if($settings){ if($settings->lang == "id"){ echo 'selected'; } } ?>><?php echo strip_tags( __( 'Indonesian', 'wp-gcalendar' ) ); ?></option>
					<option value="is" <?php if($settings){ if($settings->lang == "is"){ echo 'selected'; } } ?>><?php echo strip_tags( __( 'Icelandic', 'wp-gcalendar' ) ); ?></option>
					<option value="ko" <?php if($settings){ if($settings->lang == "ko"){ echo 'selected'; } } ?>><?php echo strip_tags( __( 'Korean', 'wp-gcalendar' ) ); ?></option>
					<option value="lv" <?php if($settings){ if($settings->lang == "lv"){ echo 'selected'; } } ?>><?php echo strip_tags( __( 'Latvian', 'wp-gcalendar' ) ); ?></option>
				</select>
			</td>
		</tr>
		
		<tr>
			
			<td colspan="2">
				<p>
					<input type="submit" name="submit" value="<?php echo strip_tags( __( 'Save', 'wp-gcalendar' ) ); ?>" id="search-submit" class="button" style="float:right; margin: 0 20px 10px 0" />
					<a href="<?php echo admin_url( 'admin.php?page=google-calendar-plg'); ?>" id="search-submit" class="button" style="float:right; margin: 0 20px 10px 0"/><?php echo strip_tags( __( 'Cancel', 'wp-gcalendar' ) ); ?></a>
				</p>

			</td>
		</tr>
	</table>
	
	<?php wp_nonce_field('calendar_event'); ?>
	</form>
	</div>
<?php
}