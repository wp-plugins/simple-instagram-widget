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
		if ( ! empty( $instance['userID_converted'] ) ) {
			$userID = $instance['userID_converted'];
		}
		if ( ! empty( $instance['hashtag'] ) ) {
			$hashtag = $instance['hashtag'];
		}
		$count = $instance['count'];
		

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
						clientId: '972fed4ff0d5444aa21645789adb0eb0',
						count: '<?php echo $instance['count']; ?>',
						<?php if ( ! empty( $userID ) ) { ?>
							userId: '<?php echo $userID; ?>',
						<?php } ?>
						<?php if ( ! empty( $hashtag ) ) { ?>
							hash: '<?php echo $hashtag; ?>',
						<?php } ?>
					});
				});
			</script>
			<div class="simple-instagram-widget-wrapper clearfix">

			</div>

		<?php echo $after_widget;
	}


	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		
		$instance['userID'] = strip_tags( $new_instance['userID'] );
		$instance['count'] = strip_tags( $new_instance['count'] );
		$instance['hashtag'] = strip_tags( $new_instance['hashtag'] );

		$username_response = wp_remote_get( 'https://api.instagram.com/v1/users/search?q=' . $instance['userID'] . '&client_id=972fed4ff0d5444aa21645789adb0eb0' );
		$username_response_data = json_decode( $username_response['body'], true );
		
		$instance['userID_converted'] = $username_response_data['data'][0]['id'];

		return $instance;
	}


	function form( $instance ) {
		?>
		
		<div class="item-wrapper">
			<p>
				<label>Display Images based on:</label><br />
				<label><input id="instagram_type_username" name="instagram_type_select" type="radio" value="username" <?php if ( ! empty( $instance['userID'] ) ) { echo 'checked'; } ?> />Username</label><br />
				<label><input id="instagram_type_radio" name="instagram_type_select" type="radio" value="hashtag" <?php if ( ! empty( $instance['hashtag'] ) ) { echo 'checked'; } ?> />Hashtag</label>
			</p>
		</div>

		<div class="item-wrapper instagram-switch" id="username" <?php if ( empty( $instance['userID'] ) ) { echo 'style="display:none;"'; } ?> >
			<p><label for="<?php echo $this->get_field_id( 'userID' ); ?>">Username:</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'userID' ); ?>" name="<?php echo $this->get_field_name( 'userID' ); ?>" value="<?php if ( isset( $instance['userID'] ) ) { echo $instance['userID']; } ?>" type="text"  /></p>
		</div>

		<div class="item-wrapper instagram-switch" id="hashtag" <?php if ( empty( $instance['hashtag'] ) ) { echo 'style="display:none;"'; } ?> >
			<p><label for="<?php echo $this->get_field_id( 'hashtag' ); ?>">Hashtag:</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'hashtag' ); ?>" name="<?php echo $this->get_field_name( 'hashtag' ); ?>" value="<?php if ( isset( $instance['hashtag'] ) ) { echo $instance['hashtag']; } ?>" type="text"  /></p>
		</div>
		
		<div class="item-wrapper">
			<p><label for="<?php echo $this->get_field_id( 'count' ); ?>">Number of photos to show:</label>
			<input id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" value="<?php if ( isset( $instance['count'] ) ) { echo $instance['count']; } ?>" type="text" size="3" /></p>
		</div>
	<?php
	}
}

?>
