<?php

/**
 * Plugin Name: Covenant Health Network Notifications
 * Plugin URI: https://github.com/WebDevStudios/custom-post-type-ui/
 * Description: Create and display site-wide notifications from the General Settings page
 * Author: John Galyon
 * Version: 1.1
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

add_action( 'wp_enqueue_scripts', 'cov_network_notifications_enqueue' );
function cov_network_notifications_enqueue() {
	$css_path = plugin_dir_url( __FILE__ ) . 'assets/covenant-network-notifications.min.css';

	wp_enqueue_style(
		'covenant_network_notifications',
		plugin_dir_url( __FILE__ ) . 'assets/covenant-network-notifications.min.css',
		'main',
		'1.0.3',
		'screen'
	);

	wp_enqueue_script(
		'covenant_network_notifications',
		plugin_dir_url( __FILE__ ) . 'assets/covenant-network-notifications.min.js',
		'jquery',
		'1.0.3',
		true
	);
}

// Register settings and stuff
add_action( 'admin_init', 'cov_notification_settings' );

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

	/*if( ! empty( get_option( 'approval_mode' ) ) && current_user_can( 'administrator' )) {
		echo $struct;
	} else {
		echo $struct;
	}*/

	echo $struct;
}

/**
 * ACF logic for grabbing custom content in covenanthealth.com ACF
 * options page, then outputting that content
 */

if ( function_exists( 'get_sites' ) && class_exists( 'WP_Site_Query' ) ) {
	add_action( 'wp_body_open', 'cov_front_page_notification' );

	function cov_front_page_notification() {
		$site      = 1;
		$chosen    = array();
		$all_sites = get_sites( array(
			'archived' => 0
		) );
		$cov_sites = get_sites( array(
			'site__in' => array(
				1, 2, 3, 4, 6, 8, 9, 10, 12, 13, 14, 17, 18, 19, 22, 32, 34, 43, 51, 56, 57, 62, 76, 91, 93
			)
		) );
		$cmg_sites = get_sites( array(
			'site__in' => array(
				9, 11, 15, 16, 20, 21, 23, 24, 25, 26, 27, 29, 30, 31, 33, 36, 37, 39, 40, 42, 44, 46, 47, 48, 49, 51,
				52, 53, 54, 58, 59, 61, 63, 65, 66, 67, 68, 69, 70, 72, 73, 74, 77, 78, 80, 81, 82, 85, 88, 94
			)
		) );

		// Get the field data from the options page on covenanthealth.com
		switch_to_blog( $site );
		$fields = array(
			'content'   => get_field( 'cov_network_front_page_content', 'option' ),
			'reach'     => get_field( 'content_reach', 'option' ),
			'cov_blogs' => get_field( 'covenant_sites_check', 'option' ),
			'cmg_blogs' => get_field( 'cmg_sites_check', 'option' ),
		);

		if ( $fields['reach']['value'] == 'other' ) {
			foreach ( $fields['cov_blogs']['value'] as $val ) {
				array_push( $chosen, intval( $val ) );
			}
			foreach ( $fields['cmg_blogs']['value'] as $val ) {
				array_push( $chosen, intval( $val ) );
			}
		}

		$selected_sites = get_sites( $chosen );

		restore_current_blog();

		// What sites need this hot data injection?
		if ( $fields['reach']['value'] == 'all' ) {
			foreach ( $all_sites as $site ) {
				switch_to_blog( $site->blog_id );

				if( is_front_page() ) {

					?>
					<script type="text/javascript">
						(function($) {
							$('main').prepend('<div class="row options_content_row"><div class="col-xs-12"><?php echo $fields['content']; ?></div></div>');
						})(jQuery);
					</script>
					<?php

				}

				restore_current_blog();
			}
		} else if ( $fields['reach']['value'] == 'cov' ) {
			foreach ( $cov_sites as $site ) {
				switch_to_blog( $site->blog_id );

				if ( is_front_page() ) {

					?>
					<script type="text/javascript">
						( function( $ ) {
							$( 'main' ).prepend( '<div class="row options_content_row"><div class="col-xs-12"><?php echo $fields['content']; ?></div></div>' );
						} )( jQuery );
					</script>
					<?php

				}

				restore_current_blog();
			}
		} else if( $fields['reach']['value'] == 'cmg' ) {
			foreach ( $cmg_sites as $site ) {
				switch_to_blog( $site->blog_id );

				if( is_front_page() ) {

					?>
					<script type="text/javascript">
						(function($) {
							$('main').prepend('<div class="row options_content_row"><div class="col-xs-12"><?php echo $fields['content']; ?></div></div>');
						})(jQuery);
					</script>
					<?php

				}

				restore_current_blog();
			}
		} else {
			foreach ( $selected_sites as $site ) {
				switch_to_blog( $site->blog_id );

				if( is_front_page() ) {

					?>
					<script type="text/javascript">
						(function($) {
							$('main').prepend('<div class="row options_content_row"><div class="col-xs-12"><?php echo $fields['content']; ?></div></div>');
						})(jQuery);
					</script>
					<?php

				}

				restore_current_blog();
			}
		}
	}
}
