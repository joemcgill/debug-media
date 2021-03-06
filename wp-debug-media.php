<?php
/**
 * Plugin Name: Debug Media
 * Version: 0.3.0
 * Description: Adds media debugging information to the Debug Bar.
 * Author: Joe McGill
 * Author URI: joemcgill.net
 * Plugin URI: https://github.com/joemcgill/debug-media
 * Text Domain: debug-media
 * Domain Path: /languages
 * @package Debug_Media
 */

add_filter( 'debug_bar_panels', 'debug_bar_media_panel' );
function debug_bar_media_panel( $panels ) {
	require_once 'class-debug-bar-media.php';
	$panels[] = new Debug_Bar_Media();
	return $panels;
}
