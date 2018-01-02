<?php 
function wpgc_my_calendar()
	{	
		global $wpdb;
		$table_name = $wpdb->prefix . 'events';
		$events = $wpdb->get_results('select * from ' . $table_name . ' where start_Date >= "'.date('Y-m-d').'" or end_Date >= "'.date('Y-m-d').'" ORDER BY start_Date ASC' );
		$table_namee = $wpdb->prefix . "api_setting";
		$settings = $wpdb->get_row('select * from ' . $table_namee );
		$ev = array();
		foreach ($events as $event) {

		if($event->start_Date != '0000-00-00'){
			if(($event->start_Time != NULL) && ($event->end_Time != NULL)){
				$tooltip = __( 'Title', 'wp-gcalendar-pro' ).": ".$event->summary."<br>";
                $tooltip .= __( 'start', 'wp-gcalendar-pro' ).": ".$event->start_Date." ".__( 'at', 'wp-gcalendar-pro' )." ".$event->start_Time." <br> ";
                $tooltip .= __( 'end', 'wp-gcalendar-pro' ).": ".$event->end_Date." ".__( 'at', 'wp-gcalendar-pro' )." ".$event->end_Time;
                if(!empty($event->location)) $tooltip .= "<br>".__( 'Location', 'wp-gcalendar-pro' )." : ".$event->location;
                if(!empty($event->description)) $tooltip .= "<br>".__( 'Description', 'wp-gcalendar-pro' )." : ".$event->description;
				$ev[] = array(
					'title' => "$event->summary",
					'start' => $event->start_Date.'T'.$event->start_Time,
					'end' => $event->end_Date.'T'.$event->end_Time,
					'allDay' => false,
					'tooltip' => $tooltip
					); 
			}else{
				$tooltip = __( 'Title', 'wp-gcalendar-pro' ).": ".$event->summary."<br>";
                $tooltip .= __( 'start', 'wp-gcalendar-pro' ).": ".$event->start_Date." <br> ";
                $tooltip .= __( 'end', 'wp-gcalendar-pro' ).": ".$event->end_Date;
                if(!empty($event->location)) $tooltip .= "<br>".__( 'Location', 'wp-gcalendar-pro' )." : ".$event->location;
                if(!empty($event->description)) $tooltip .= "<br>".__( 'Description', 'wp-gcalendar-pro' )." : ".$event->description;
				$ev[] = array(
					'title' => "$event->summary",
					'start' => $event->start_Date,
					'end' => $event->end_Date,
					'tooltip' => $tooltip
					); 
			}
			
				} 
		} 	
		$e = json_encode($ev, JSON_HEX_APOS);
		?>
		<?php if($settings->lang == NULL){$lang = 'en';}else{$lang = $settings->lang;} ?>
		<div style="display:none" class="eventJson" defaultDate="<?php echo date('Y-m-d'); ?>" data='<?php echo $e; ?>' lang="<?php echo $lang; ?>"></div>
		<script>
		jQuery(document).ready(function() {
			
			var ev = eval(jQuery("div.eventJson").attr("data"));
			var defaultDate = jQuery("div.eventJson").attr("defaultDate");
			var language = jQuery("div.eventJson").attr("lang");
			jQuery('#calendar').fullCalendar({
				lang: language,
				header: {
	                    left: 'prev,next today',
	                    center: 'title',
	                    right: 'month,agendaWeek,agendaDay'
	                    },
	                    defaultDate: defaultDate,
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
	<?php
		return "<div id='calendar'></div>";	
	}	
	add_shortcode('wpgc-calendar','wpgc_my_calendar');

