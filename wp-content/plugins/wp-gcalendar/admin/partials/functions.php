<?php 

add_action( 'admin_footer', 'my_action_javascript' );
function my_action_javascript() { ?>
	<script type="text/javascript" >
    var CLIENT_ID=jQuery("div#settings").attr("clientID");var calendarId=jQuery("div#settings").attr("calendarId");var rooturl=jQuery("div#settings").attr("root");var priority=jQuery("div#settings").attr("priority");var SCOPES=["https://www.googleapis.com/auth/calendar"];var checkAuth=function(){gapi.auth.authorize({'client_id':CLIENT_ID,'scope':SCOPES.join(' '),'immediate':true},handleAuthResult);}
	function handleAuthResult(authResult){var authorizeDiv=document.getElementById('authorize-div');if(authResult&&!authResult.error){loadCalendarApi();}else{}}
	function handleAuthClick(event){gapi.auth.authorize({client_id:CLIENT_ID,scope:SCOPES,immediate:false},handleAuthResult);return false;}
	function loadCalendarApi(){gapi.client.load('calendar','v3',listUpcomingEvents);}
	function listUpcomingEvents(){jQuery('#res').html('<img src="'+rooturl+'/img/loading.gif" />');window.setTimeout(evlist,3000);}
	var evlist=function(){var request=gapi.client.calendar.events.list({'calendarId':calendarId,'timeMin':(new Date()).toISOString(),'showDeleted':false,'singleEvents':true,/*'maxResults':250,*/'orderBy':'startTime'});request.execute(function(resp){var events=resp.items;console.log(events);var ajaxURL=rooturl+'includes/result.php';var data={events}; var events = JSON.stringify(events); var data={'action':'wpgc_events_action','result':events};jQuery.post(ajaxurl,data,function(response){jQuery('#res').html(response);});});}
	</script> 
<?php
}


if(isset($_GET['d'])){
	function wpgc_calendar_delete_event()
	{
		global $wpdb;
		$table_name = $wpdb->prefix . 'events';
		$d = intval($_GET['d']);
		$wpdb->delete( $table_name, array( 'localID' => $d ), array( '%d' ) );
		$url = admin_url( 'admin.php?page=google-calendar-plg');
		?>
		<meta http-equiv="refresh" content="0;URL='<?php echo $url; ?>'" />
		<?php
	}

	add_action( 'wpgc_delete_gc', 'wpgc_calendar_delete_event');
	do_action( 'wpgc_delete_gc');
}