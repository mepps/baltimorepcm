<?php 
function wpgc_calendar_page()
{ 
	global $wpdb;
	$table_name = $wpdb->prefix . "api_setting";
	$settings = $wpdb->get_row('select * from ' . $table_name );
	if(!$settings){ ?>
	<h2><?php echo strip_tags( __( 'Settings required', 'wp-gcalendar' ) ); ?>! </h2>
	<p><a href="<?php echo admin_url('admin.php?page=google-calendar-settings'); ?>"><?php echo strip_tags( __( 'Go to settings', 'wp-gcalendar' ) ); ?></a></p>
	<?php exit(); } ?>

		
	    <script src="https://apis.google.com/js/client.js">
	    </script>
	<?php
		global $wpdb;
		$table_name = $wpdb->prefix . 'events';
		$events = $wpdb->get_results('select * from ' . $table_name . ' where start_Date >= "'.date('Y-m-d').'" or end_Date >= "'.date('Y-m-d').'" ORDER BY start_Date ASC' );
		$url = admin_url( 'admin.php?page=google-calendar-event');
		$url2 = admin_url( 'admin.php?page=google-calendar-plg');
		if(isset($_GET['id_e'])){
			$ev = $wpdb->get_row('select * from ' . $table_name . ' where id = '. $_GET['id_e'] );
			
		}
		?>
		<div class="wrap">
		<?php screen_icon(); 
		$table_settings = $wpdb->prefix . "api_setting";
		$settings = $wpdb->get_row('select * from ' . $table_settings );?>
		<div id="settings" priority = "<?php echo esc_attr($settings->priority); ?>" clientID = "<?php echo esc_attr($settings->clientID); ?>" calendarId = "<?php echo esc_attr($settings->calendarID); ?>" root="<?php echo plugins_url( '', __FILE__ ); ?>" hidden></div>

		<h2><?php echo strip_tags( __( 'List of upcoming events', 'wp-gcalendar' ) ); ?></h2>
			<p>
				
			 	<button onclick="handleAuthClick(event)" id="search-submit" class="button" /><?php echo strip_tags( __( 'Synchronize', 'wp-gcalendar' ) ); ?></button>
			</p>
			<div id="res">
				<table class="wp-list-table widefat fixed striped posts">
					<thead>
						<tr>
							<th id="columnname" class="manage-column column-columnname" scope="col"><?php echo strip_tags( __( 'Title', 'wp-gcalendar' ) ); ?></th>
							<th id="columnname" class="manage-column column-columnname" scope="col"><?php echo strip_tags( __( 'Location', 'wp-gcalendar' ) ); ?></th>
							<th id="columnname" class="manage-column column-columnname" scope="col"><?php echo strip_tags( __( 'Start date', 'wp-gcalendar' ) ); ?></th>
							<th id="columnname" class="manage-column column-columnname" scope="col"><?php echo strip_tags( __( 'End date', 'wp-gcalendar' ) ); ?></th>
							<th id="columnname" class="manage-column column-columnname" scope="col"><?php echo strip_tags( __( 'Description', 'wp-gcalendar' ) ); ?></th>
							<th id="columnname" class="manage-column column-columnname" scope="col"><?php echo strip_tags( __( 'Status', 'wp-gcalendar' ) ); ?></th>
							<th id="columnname" class="manage-column column-columnname" scope="col"><?php echo strip_tags( __( 'Action', 'wp-gcalendar' ) ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php $ev = array(); ?>
						<?php foreach ($events as $event) { ?>
						<tr>
							<td class="column-columnname" scope="row"><?php echo esc_attr($event->summary) ?></td>
							<td class="column-columnname"><?php echo esc_attr($event->location) ?></td>
							<td class="column-columnname"><?php echo esc_attr($event->start_Date).' '.esc_attr($event->start_Time) ?></td>
							<td class="column-columnname"><?php echo esc_attr($event->end_Date).' '.esc_attr($event->end_Time) ?></td>
							<td class="column-columnname"><?php echo esc_attr(substr($event->description, 0, 45)); if(strlen($event->description) > 45) echo ' ...'; ?></td>
							<td class="column-columnname"><?php echo esc_attr($event->status) ?></td>
							<td class="column-columnname">
								<?php if(empty($event->id)){ ?>
								<a href="<?php echo $url. '&id_e='. esc_attr($event->localID) ?>"><span class="dashicons dashicons-edit"></span></a>
								<?php } ?>
								<a href="<?php echo $url2. '&d='. esc_attr($event->localID) ?>" onclick="return confirm('Are you sure you want to delete this event?');"><span class="dashicons dashicons-trash"></span></a>
								
							</td>
						</tr>
						<?php 
						if($event->start_Date != '0000-00-00'){
						if(($event->start_Time != NULL) && ($event->end_Time != NULL)){
							$tooltip = __( 'Title', 'wp-gcalendar' ).": ".$event->summary."<br>";
							$tooltip .= __( 'start', 'wp-gcalendar' ).": ".$event->start_Date." ".__( 'at', 'wp-gcalendar' )." ".$event->start_Time." <br> ";
							$tooltip .= __( 'end', 'wp-gcalendar' ).": ".$event->end_Date." ".__( 'at', 'wp-gcalendar' )." ".$event->end_Time;
							if(!empty($event->location)) $tooltip .= "<br>".__( 'Location', 'wp-gcalendar' )." : ".$event->location;
							if(!empty($event->description)) $tooltip .= "<br>".__( 'Description', 'wp-gcalendar' )." : ".$event->description;
							$ev[] = array(
								'title' => "$event->summary",
								'start' => $event->start_Date.'T'.$event->start_Time,
								'end' => $event->end_Date.'T'.$event->end_Time,
								'allDay' => false,
								'tooltip' => $tooltip
								); 
						}else{
							$tooltip = __( 'Title', 'wp-gcalendar' ).": ".$event->summary."<br>";
							$tooltip .= __( 'start', 'wp-gcalendar' ).": ".$event->start_Date." <br> ";
							$tooltip .= __( 'end', 'wp-gcalendar' ).": ".$event->end_Date;
							if(!empty($event->location)) $tooltip .= "<br>".__( 'Location', 'wp-gcalendar' )." : ".$event->location;
							if(!empty($event->description)) $tooltip .= "<br>".__( 'Description', 'wp-gcalendar' )." : ".$event->description;
							$ev[] = array(
								'title' => "$event->summary",
								'start' => $event->start_Date,
								'end' => $event->end_Date,
								'tooltip' => $tooltip
								); 
						}
						
							} ?>
						<?php } ?>	
						<?php
							$e = json_encode($ev, JSON_HEX_APOS);
						 ?>
					</tbody>
				</table>
				<?php if($settings->lang == NULL){$lang = 'en';}else{$lang = $settings->lang;} ?>
				<div style="display:none" class="eventJson" defaultDate = "<?php echo date('Y-m-d'); ?>" data='<?php echo $e; ?>' lang="<?php echo $lang; ?>"></div>
			
			<script>

				jQuery(document).ready(function() {
					
					var ev = eval(jQuery("div.eventJson").attr("data"));
					var default_Date = jQuery("div.eventJson").attr("defaultDate");
					var language = jQuery("div.eventJson").attr("lang");
					jQuery('#calendar').fullCalendar({
						lang: language,
						header: {
						left: 'prev,next today',
						center: 'title',
						right: 'month,agendaWeek,agendaDay'
						},
						defaultDate: default_Date,
						selectable: false,
						selectHelper: true,
						select: function(start, end) {
							var title = prompt('Event Title:');
							var eventData;
							if (title) {
								eventData = {
									title: title,
									start: start,
									end: end
								};
								jQuery('#calendar').fullCalendar('renderEvent', eventData, true); // stick? = true
							}
							jQuery('#calendar').fullCalendar('unselect');
						},
						editable: false,
						eventLimit: true, // allow "more" link when too many events
						events: ev,
					    eventRender: function(event, element) {
					        element.qtip({ 
					            content: {    
					                text: event.tooltip 
					            },
				                position: {
								    my: 'top center',  
            						at: 'bottom center', 
							        target: element 
								}  
					        });
					    }
						});
						
					});

			</script>
			<div id='calendar'></div>
			</div>
		</div>
		<?php
	}