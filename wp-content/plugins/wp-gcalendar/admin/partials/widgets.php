<?php 
class WPGCWidget extends WP_Widget
{
	function WPGCWidget() {
		$widget_options = array(
		'classname'		=>		'wpgc-widget',
		'description' 	=>		'WP GCalendar widget'
		);
		
		parent::WP_Widget('WPGC_widget', 'WP GCalendar Widget', $widget_options);
	}
	
	function widget( $args, $instance ) {
		extract ( $args, EXTR_SKIP );
		$title = ( $instance['title'] ) ? $instance['title'] : 'List of events';
		global $wpdb;
		$table_name = $wpdb->prefix . 'events';
		$events = $wpdb->get_results('select * from ' . $table_name . ' where start_Date >= "'. date('Y-m-d') . '" or end_Date >= "'.date('Y-m-d').'" limit 5' );
		?>
		<?php echo $before_widget ?>
		<?php echo $before_title . $title . $after_title ?>
		<ul>
			<?php foreach ($events as $event) { ?>
			<li>
				<?php echo $event->start_Date; ?>
				<?php $start = date("g:i a", strtotime($event->start_Time)); echo $start; ?> - 
				<?php $end = date("g:i a", strtotime($event->end_Time)); echo $end; ?> 
				<?php echo esc_attr($event->summary); ?>
			</li>
			<?php } ?>
		</ul>
		<?php 
	}
	
	function form( $instance ) {
		?>
		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>">
		Title: 
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
				name="<?php echo $this->get_field_name('title'); ?>"
				value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</label>
		</p>
		<?php 
	}
	
}
	
function WPGC_widget_init() {
	register_widget("WPGCWidget");
}
add_action('widgets_init','WPGC_widget_init');