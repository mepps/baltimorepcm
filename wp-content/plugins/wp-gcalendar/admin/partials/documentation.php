<?php 
// Doc
function wpgc_documentation()
{ 
	$root = plugins_url( '../', __FILE__ );
	?>
	<div class="wrap wpgc-doc">
			<h1 class="wpgc-head">WP GCalndar Plugin</h1>
			<h3>Creating a Google Client ID</h3>
			<p>To read and add events from your Google Calendars you’ll need create a Google Client ID
			 and save within your plugin settings. Step-by-step instructions to create and save a Google
			  Client ID: Navigate to the <a href="https://console.developers.google.com/">Google Developers Console</a>. 
			  In the drop-down menu at the top right,
			   select Create a project…</p>
		    <img src="<?php echo $root ?>img/1.png">
			<p>Give your project a name, agree to the terms, then click <b>Create</b>.</p>
			<img src="<?php echo $root ?>img/2.png">
			<p>From the Google Developers Console Dashboard select <b>Enable and manage APIs</b>. 
				If you don’t see this, select <b>API Manager</b> from the top-left “hamburger” menu.</p>
			<img src="<?php echo $root ?>img/3.png">
			<p>You should now be in the API Manager section of the Google Developers Console. 
				Under <b>Google Apps APIs</b>, select <b>Calendar API</b>.</p>
			<img src="<?php echo $root ?>img/4.png">
			<p>Click <b>Enable API</b>.</p>
			<img src="<?php echo $root ?>img/5.png">
			<p>A message should pop up recommending to proceed with creating credentials. Do this now. 
				Alternatively select Credentials under API Manager in the left-hand menu.</p>
			<img src="<?php echo $root ?>img/6.png">
			<p>Go to "OAuth consent screen" section, enter "Product name shown users" and Save.</p>
			<img src="<?php echo $root ?>img/7.png">
			<p>Select <b>Add credentials</b> and click on <b>API key</b>.</p>
			<img src="<?php echo $root ?>img/8.png">
			<p>Click <b>Web application</b></p>
			<img src="<?php echo $root ?>img/9.png">
			<p>Give your Web application any name, Authorized JavaScript origins as you web site address, 
				and Authorized redirect URIs as the image shows then click <p>Create</p>.</p>
			<img src="<?php echo $root ?>img/10.png">
			<p>On the API key popup, select and copy (Cmd-C or Ctrl-C) your Client ID.</p>
			<img src="<?php echo $root ?>img/11.png">
			<p>Now back on your <b>WordPress dashboard</b>, go to <b>Calendar</b>, then <b>Settings</b> from the menu. 
				Enter your Google Client ID here, and your Calendar ID making sure you have pasted 
				the exact key without extra spaces. Then click <b>Save</b>.</p>
			<img src="<?php echo $root ?>img/12.png">
			<h3>Video Instructions</h3>
			<iframe width="100%" height="500" src="https://www.youtube.com/embed/AX9PtgKPnuM" frameborder="0" allowfullscreen></iframe>
			<h1>Google Calendar ID</h1>
			<p>For Your Calendar ID if you want to use your default calendar use primary as an ID, 
				for others go to your Calendar settings and copy your Calendar ID.</p>
			<img src="<?php echo $root ?>img/13.png">
			<img src="<?php echo $root ?>img/14.png">
			<p><i>In case of any error or problem, you may try obtaining a new Google Client ID by repeating the steps 
				above before contacting support. You can generate more than one Client ID under the same project on 
				Google Developers Console.</i></p>
			<h1>How to use the Admin Interface</h1>
			<p>Add an event your <b>Google Calendar</b></p>
			<img src="<?php echo $root ?>img/15.png">
			<p>Go to <b>Calendar > All Events</b> from the menu, then click <b>Synchronize</b></p>
			<img src="<?php echo $root ?>img/16.png">
			<p>The Google Calendar event will get imported to your Admin Calendar</p>
			<img src="<?php echo $root ?>img/17.png">
			<h3>Short code</h3>
			<p>Use the Shortcode <b>[wpgc-calendar]</b> to display your calendar in your posts or pages.</p>
			<p>To embed Shortcode into your theme:</p>
	<pre>
	&lt;?php
	$calendar = '[wpgc-calendar]';
	echo do_shortcode ( $calendar );
	?&gt;
	</pre>

			
	</div>
<?php
}