<?php

/**
 * Plugin Name: Covenant Health Network Notifications
 * Plugin URI: https://github.com/WebDevStudios/custom-post-type-ui/
 * Description: Create and display site-wide notifications from the General Settings page
 * Author: John Galyon
 * Version: 1.0.2
 * Author URI: https://www.covenanthealth.com/
 * Text Domain: covenant-network-notifications
 * Domain Path: /languages
 * License: GPL-2.0+
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CovNetworkNotifications
 *
 * Includes common methods accessed throughout this plugin
 * Why am I doing this? I don't even know how to use a class.
 */
/*class Cov_Network_Notifications {

	// Return the path of the plugin directory
	public static function cov_plugin_path() {
		return dirname( __FILE__ );
	}

	//Returns the url of the plugin's root folder
	public static function cov_base_url() {
		return plugins_url( '', __FILE__ );
	}
}*/

add_action( 'wp_enqueue_scripts', 'cov_network_notifications_enqueue');
function cov_network_notifications_enqueue() {
	$css_path = plugin_dir_url( __FILE__ ) . '/assets/covenant-network-notifications.min.css';

	wp_enqueue_style(
		'covenant_network_notifications',
		plugin_dir_url( __FILE__ ) . '/assets/covenant-network-notifications.min.css',
		'main',
		'1.0.3',
		'screen'
	);

	wp_enqueue_script(
		'covenant_network_notifications',
		plugin_dir_url( __FILE__ ) . '/assets/covenant-network-notifications.min.js',
		'jquery',
		'1.0.3',
		false
	);
}

// Register settings and stuff
add_action('admin_init', 'cov_notification_settings');

function cov_notification_settings() {
	register_setting(
		'general',
		'notification_message',
		array(
			'type'              => 'string',
			'sanitize_callback' => 'wp_kses_post',
			'show_in_rest'      => true
		)
	);

	add_settings_section(
		'notification-msg',
		'Site-Wide Notification',
		'__return_false',
		'general'
	);

	add_settings_field(
		'notification_message',
		'Enter notification text to be shown site-wide',
		'cov_print_notification_editor',
		'general',
		'notification-msg'
	);
}

function cov_print_notification_editor() {
	$the_guides = html_entity_decode( get_option( 'notification_message' ) );

	echo wp_editor(
		get_option( 'notification_message' ),
		'notificationmessage',
		array(
			'textarea_name' => 'notification_message'
		)
	);
}

add_action( 'wp_body_open', 'cov_output_notification' );
function cov_output_notification() {
	$msg    = ! empty( get_option( 'notification_message' ) ) ? get_option( 'notification_message' ) : '<p style="text-align: center;"><i class="fa fa-info-circle" aria-hidden="true"></i><a href="https://www.covenanthealth.com/coronavirus/?utm_source=notification_bar&utm_medium=banner&utm_campaign=coronavirus"> Novel Coronavirus (COVID-19) Information and Updates</a></p>';
	$struct = '';
	$struct .= '<div class="system-notification-wrapper"><div class="container"><div class="row"><div class="col-xs-12">' . $msg . '</div></div></div></div>';

	echo $struct;
}
