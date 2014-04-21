<?php

/**
 * Plugin Name: Simple Instagram  Widget
 * Description: A widget that displays Instagram photos
 * Version: 1.1.1
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
		plugins_url( 'lib/widget/js/admin-instagram.js', __FILE__),
		array('jquery')
	);

}
add_action('admin_enqueue_scripts', 'tc_simple_instagram_admin_enqueue_scripts');
