<?php

/**
 * Plugin Name: Simple Instagram  Widget
 * Description: A widget that displays Instagram photos
 * Version: 1.2.6
 * Author: Ty Carlson
 * Author URI: http://www.tywayne.com
 */

/* register and build widget */
function tc_register_simple_instagram_widget() {
	register_widget( 'TC_Simple_Instagram_Widget' );
}
add_action( 'widgets_init', 'tc_register_simple_instagram_widget' );

/* Load the widget class */
require_once( plugin_dir_path( __FILE__ ) . '/lib/widget/class.instagram-widget.php' );


// Load admin script on widgets page
function tc_simple_instagram_admin_enqueue_scripts($hook) {

	if( $hook != 'widgets.php' )
		return;
	
	wp_enqueue_script(
		'admin-instagram',
		plugins_url( 'lib/js/admin-instagram.js', __FILE__),
		array('jquery')
	);

}
add_action('admin_enqueue_scripts', 'tc_simple_instagram_admin_enqueue_scripts');

// Register front end styles/scripts
function tc_simple_instagram_register_frontend_scripts() {
	wp_register_style(
		'simple-instagram-style',
		plugins_url( '/lib/css/simple-instagram-widget.css', __FILE__ )
	);

	wp_register_script(
		'simple-instagram-script',
		plugins_url( '/lib/js/instagram.js',  __FILE__ ),
		array('jquery')
	);
}

add_action( 'wp_enqueue_scripts', 'tc_simple_instagram_register_frontend_scripts' );


function tc_simple_instagram_shortcode($atts = '') {
	
	wp_enqueue_style('simple-instagram-style');
	wp_enqueue_script('simple-instagram-script');

	$atts = shortcode_atts( array(
		'hashtag' 	=> '',
		'username' 	=> '',
		'count' 	=> '5'
	), $atts );

	$instance_count = 0;
	$instance_count++;
	
	if ( $atts['username'] ) {
		$username_response = wp_remote_get( 'https://api.instagram.com/v1/users/search?q=' . $atts['username'] . '&client_id=972fed4ff0d5444aa21645789adb0eb0' );
		$username_response_data = json_decode( $username_response['body'], true );
		
		$atts['username_converted'] = '';
		foreach ( $username_response_data['data'] as $data ) {
			if ( $data['username'] == $atts['username'] ) {
				$atts['username_converted'] = $data['id'];
			}
		}
	}
	
	
	$return = '<script>' . "\n" ;
	$return .= 		'jQuery(function($) {' . "\n" ;

	$return .=			'$(".simple-instagram-shortcode-wrapper-' . $instance_count . '").on(\'didLoadInstagram\', function(event, response) {' . "\n" ;

	$return .=				'var data = response.data;' . "\n" ;

	$return .=				'for( var key in data ) {' . "\n" ;
	$return .=					'var image_src = data[key][\'images\'][\'standard_resolution\'][\'url\'];' . "\n" ;
	$return .=					'var image_link = data[key][\'link\'];' . "\n" ;

	$return .=					'var output = \'<div class="simple-instagram-shortcode-image"><a href="\'+image_link+\'" target="_blank"><img src="\'+image_src+\'" ></a></div>\';' . "\n" ;

	$return .=					'$(".simple-instagram-shortcode-wrapper-' . $instance_count . '").append(output);' . "\n" ;
	$return .=				'}' . "\n" ;

	$return .=			'});' . "\n" ;

	$return .=			'$(".simple-instagram-shortcode-wrapper-' . $instance_count . '").instagram({' . "\n" ;
	$return .=				'clientId: \'972fed4ff0d5444aa21645789adb0eb0\',' . "\n" ;
	$return .=				'count: \'' . $atts['count'] . '\',' . "\n" ;
	if ( $atts['username'] != '' ) {
		$return .=					'userId: \'' . $atts['username_converted'] . '\'' . "\n" ;
	} else if ( $atts['hashtag'] != '' ) {
		$return .= 					'hash: \'' . $atts['hashtag'] . '\'' . "\n" ;
	}
	$return .=			'});' . "\n" ;
	$return .=		'});' . "\n" ;
	$return .=	'</script>' . "\n" ;
	
	$return .= '<div class="simple-instagram-shortcode-wrapper simple-instagram-shortcode-wrapper-' . $instance_count . ' clearfix"></div>' . "\n" ;
	
	return $return;
}
add_shortcode('simple_instagram', 'tc_simple_instagram_shortcode');

