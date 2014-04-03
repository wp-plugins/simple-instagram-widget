<?php

/**
 * Plugin Name: Simple Instagram  Widget
 * Description: A widget that displays Instagram photos
 * Version: 1.0.1
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
