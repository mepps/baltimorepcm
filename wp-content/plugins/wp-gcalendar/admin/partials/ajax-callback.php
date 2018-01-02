<?php 
add_action( 'wp_ajax_get_localevents', 'get_localevents_callback' );
function get_localevents_callback() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'events';
    $localEvents = $wpdb->get_results('select * from ' . $table_name . ' where id = ""  and start_Date >= "'.date('Y-m-d').'" or end_Date >= "'.date('Y-m-d').'" ORDER BY start_Date ASC' );
    echo json_encode($localEvents);
}

add_action( 'wp_ajax_wpgc_events_action', 'wpgc_events_action_callback' );
function wpgc_events_action_callback() {
    global $wpdb;
    $data = $_POST["result"]; 
    $data = json_decode(stripslashes_deep($_POST["result"]), true); 
    //var_dump($data);
    $table_name = $wpdb->prefix . 'events';
    $wpdb->delete( $table_name, array( 'id' => "" ) );
    $tablee_name = $wpdb->prefix . "api_setting";
    $settings = $wpdb->get_row('select * from ' . $tablee_name );
    foreach ($data as $key => $value) {
        
        $s = $wpdb->get_row('select * from ' . $table_name . ' where id = "' . $value['id'] . '"');
        if(empty($s)){
            if($value['start']['dateTime']){
                $date1 = explode('T', $value['start']['dateTime']);
                $time1 = explode('Z', $date1[1]); 
                $date2 = explode('T', $value['end']['dateTime']);
                $time2 = explode('Z', $date2[1]);                
                
                $event = array(
                'kind' => sanitize_text_field($value['kind']),
                'etag' => sanitize_text_field($value['etag']),
                'id' => sanitize_text_field($value['id']),
                'status' => sanitize_text_field($value['status']),
                'htmlLink' => sanitize_text_field($value['htmlLink']),
                'created' => sanitize_text_field($value['created']),
                'updated' => sanitize_text_field($value['updated']),
                'summary' => sanitize_text_field($value['summary']),
                'description' => sanitize_text_field($value['description']),
                'location' => sanitize_text_field($value['location']),
                'start_Date' => sanitize_text_field($date1[0]),
                'start_Time' => sanitize_text_field($time1[0]),
                'end_Date' => sanitize_text_field($date2[0]),
                'end_Time' => sanitize_text_field($time2[0]),
                'CalenderID' => sanitize_text_field($settings->calendarID)
                );
            }else{
                $event = array(
                'kind' => sanitize_text_field($value['kind']),
                'etag' => sanitize_text_field($value['etag']),
                'id' => sanitize_text_field($value['id']),
                'status' => sanitize_text_field($value['status']),
                'htmlLink' => sanitize_text_field($value['htmlLink']),
                'created' => sanitize_text_field($value['created']),
                'updated' => sanitize_text_field($value['updated']),
                'summary' => sanitize_text_field($value['summary']),
                'description' => sanitize_text_field($value['description']),
                'location' => sanitize_text_field($value['location']),
                'start_Date' => sanitize_text_field($value['start']['date']),
                'end_Date' => sanitize_text_field($value['end']['date']),
                'CalenderID' => sanitize_text_field($settings->calendarID)
                );
            }
            
            $add = $wpdb->insert($table_name,$event);
        }else{

            if($value['start']['dateTime']){
                $date1 = explode('T', $value['start']['dateTime']);
                $time1 = explode('Z', $date1[1]); 
                $date2 = explode('T', $value['end']['dateTime']);
                $time2 = explode('Z', $date2[1]);                
                
                $event = array(
                'kind' => sanitize_text_field($value['kind']),
                'etag' => sanitize_text_field($value['etag']),
                'status' => sanitize_text_field($value['status']),
                'htmlLink' => sanitize_text_field($value['htmlLink']),
                'created' => sanitize_text_field($value['created']),
                'updated' => sanitize_text_field($value['updated']),
                'summary' => sanitize_text_field($value['summary']),
                'description' => sanitize_text_field($value['description']),
                'location' => sanitize_text_field($value['location']),
                'start_Date' => sanitize_text_field($date1[0]),
                'start_Time' => sanitize_text_field($time1[0]),
                'end_Date' => sanitize_text_field($date2[0]),
                'end_Time' => sanitize_text_field($time2[0]),
                'CalenderID' => sanitize_text_field($settings->calendarID)
                );
            }else{
                $event = array(
                'kind' => sanitize_text_field($value['kind']),
                'etag' => sanitize_text_field($value['etag']),
                'status' => sanitize_text_field($value['status']),
                'htmlLink' => sanitize_text_field($value['htmlLink']),
                'created' => sanitize_text_field($value['created']),
                'updated' => sanitize_text_field($value['updated']),
                'summary' => sanitize_text_field($value['summary']),
                'description' => sanitize_text_field($value['description']),
                'location' => sanitize_text_field($value['location']),
                'start_Date' => sanitize_text_field($value['start']['date']),
                'end_Date' => sanitize_text_field($value['end']['date']),
                'CalenderID' => sanitize_text_field($settings->calendarID)
                );
            }

            $edit = $wpdb->update($table_name,$event,array( 'id' => $value['id'] ));
        }
        $events = $wpdb->get_results('select * from ' . $table_name . ' where start_Date >= "'.date('Y-m-d').'" or end_Date >= "'.date('Y-m-d').'" ORDER BY start_Date ASC' );
    }?>
        <div id="loading"></div>
        <p class="wpgc-done"><?php echo strip_tags( __( 'Your events have been synchronized with your google calendar', 'wp-gcalendar' ) ); ?>!</p>
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
                <?php $ev = array(); 
                $url = admin_url( 'admin.php?page=google-calendar-event');
                $url2 = admin_url( 'admin.php?page=google-calendar-plg');
                ?>
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
                        } ?>
                    <?php } ?>  
                    <?php
                        $e = json_encode($ev, JSON_HEX_APOS);
                     ?>
            </tbody>
        </table>
        <?php if($settings->lang == NULL){$lang = 'en';}else{$lang = $settings->lang;} ?>
        <div style="display:none" defaultDate="<?php echo date('Y-m-d'); ?>" class="eventJson" data='<?php echo $e; ?>' lang="<?php echo $lang; ?>"></div>
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
                    selectHelper: false,
                    select: function(start, end) {
                        var title = prompt('Event Title:');
                        var eventData;
                        if (title) {
                            eventData = {
                                title: title,
                                start: start,
                                end: end
                            };
                            jQuery('#calendar').fullCalendar('renderEvent', eventData, true);
                        }
                        jQuery('#calendar').fullCalendar('unselect');
                    },
                    editable: false,
                    eventLimit: true,
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
<?php
}