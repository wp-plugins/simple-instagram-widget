<?php

class TC_Simple_Instagram_Widget extends WP_Widget {

	private $instance_count = 0;

	function __construct() {
		$widget_ops = array( 'classname' => 'simple-instagram-widget', 'description' => 'A widget that displays a set of Instagram photos' );
		parent::__construct( 'simple-instagram-widget', 'Simple Instagram Widget', $widget_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		wp_enqueue_style(
			'simple-instagram-style',
			plugin_dir_path( __FILE__ ) . '/lib/css/simple-instagram-widget.css'
		);

		wp_enqueue_script(
			'simple-instagram-script',
			plugin_dir_path( __FILE__ ) . '/lib/js/instagram.js', 
			array('jquery')
		);

		//Our variables from the widget settings.
		if ( ! empty( $instance['userID_converted'] ) ) {
			$userID = $instance['userID_converted'];
		} else if ( ! empty( $instance['userID'] ) ) {
			$userID = $instance['userID'];
		}
		if ( ! empty( $instance['hashtag'] ) ) {
			$hashtag = $instance['hashtag'];
		}
		$count = $instance['count'];
		$this->instance_count++;

		$settings = get_option('tc_ig_settings');
		$client_id = $settings['tc_ig_client_id'];

		echo $before_widget;

		?>
			<script>
				jQuery(function($) {

					$('.simple-instagram-widget-wrapper-<?php echo $this->instance_count; ?>').on('didLoadInstagram', function(event, response) {

						var data = response.data;

						for( var key in data ) {
							var image_src = data[key]['images']['standard_resolution']['url'],
								image_caption = data[key]['caption']['text'],
								image_link = data[key]['link'],
								output;

							output = '<div class="simple-instagram-widget-image"><a href="'+image_link+'" target="_blank"><img src="'+image_src+'" alt="'+image_caption+'" ></a></div>';

							$('.simple-instagram-widget-wrapper-<?php echo $this->instance_count; ?>').append(output);
						}

					});

					$('.simple-instagram-widget-wrapper-<?php echo $this->instance_count; ?>').instagram({
						clientId: '<?php echo $client_id; ?>',
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
			<div class="simple-instagram-widget-wrapper simple-instagram-widget-wrapper-<?php echo $this->instance_count; ?> clearfix">

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
		
		$instance['userID_converted'] = '';
		
		foreach ( $username_response_data['data'] as $data ) {
			if ( $data['username'] == $instance['userID'] ) {
				$instance['userID_converted'] = $data['id'];
			}
		}

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
