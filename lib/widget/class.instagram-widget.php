<?php

class TC_Simple_Instagram_Widget extends WP_Widget {

	function tc_simple_instagram_widget() {
		$widget_ops = array(
			'classname' => 'simple-instagram-widget',
			'description' => 'A widget that displays a set of Instagram photos'
		);

		$control_ops = array(
			'id_base' => 'simple-instagram-widget'
		);

		$this->WP_Widget(
			'simple-instagram-widget',
			'Simple Instagram Widget', $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		wp_enqueue_style(
			'simple-instagram-style',
			plugins_url('css/simple-instagram-widget.css', __FILE__),
			array()
		);

		wp_enqueue_script(
			'simple-instagram-script',
			plugins_url('js/instagram.js', __FILE__),
			array('jquery')
		);

		//Our variables from the widget settings.
		$userID = $instance['userID'];
		$count = $instance['count'];
		$clientID = $instance['clientid'];

		echo $before_widget;

		?>
			<script>
				jQuery(function($) {

					$('.simple-instagram-widget-wrapper').on('didLoadInstagram', function(event, response) {

						var data = response.data;

						for( var key in data ) {
							var image_src = data[key]['images']['standard_resolution']['url'];
							var image_caption = data[key]['caption']['text'];
							var image_link = data[key]['link'];

							var output = '<div class="simple-instagram-widget-image"><a href="'+image_link+'" target="_blank"><img src="'+image_src+'" alt="'+image_caption+'" ></a></div>';

							$('.simple-instagram-widget-wrapper').append(output);
						}

					});

					$('.simple-instagram-widget-wrapper').instagram({
						userId: '<?php echo $userID; ?>',
						clientId: '<?php echo $clientID; ?>',
						count: '<?php echo $instance['count']; ?>'
					});
				});
			</script>
			<div class="simple-instagram-widget-wrapper clearfix">

			</div>

		<?php echo $after_widget;
	}


	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['clientid'] = strip_tags( $new_instance['clientid'] );
		$instance['userID'] = strip_tags( $new_instance['userID'] );
		$instance['count'] = strip_tags( $new_instance['count'] );

		return $instance;
	}


	function form( $instance ) {
		$defaults = array( );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p><label for="<?php echo $this->get_field_id( 'clientid' ); ?>">Instagram ClientID:</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'clientid' ); ?>" name="<?php echo $this->get_field_name( 'clientid' ); ?>" value="<?php echo $instance['clientid']; ?>" type="text"  /></p>

		<p><label for="<?php echo $this->get_field_id( 'userID' ); ?>">Instagram UserID:</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'userID' ); ?>" name="<?php echo $this->get_field_name( 'userID' ); ?>" value="<?php echo $instance['userID']; ?>" type="text"  /></p>

		<p><label for="<?php echo $this->get_field_id( 'count' ); ?>">Number of photos to show:</label>
		<input id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" value="<?php echo $instance['count']; ?>" type="text" value="5" size="3" /></p>

	<?php
	}
}

?>
