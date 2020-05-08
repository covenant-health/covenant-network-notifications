<?php

/**
 * Plugin Name: Covenant Health Network Notifications
 * Plugin URI: https://github.com/WebDevStudios/custom-post-type-ui/
 * Description: Create and display site-wide notifications from the General Settings page
 * Author: John Galyon
 * Version: 1.3.1
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

add_action( 'wp_body_open', 'add_medchat' );
function add_medchat() {
	echo '<style>.medchat-widget button.mc-expand {padding: 6px 6px 10px;#medchat-trigger .outreach-disabled {font-size: 0 !important;width: 240px !important;}#medchat-trigger .outreach-disabled:after {font-size: 16px;content: "Coronavirus Assessment Tool"}.mc-expand {background-color: #00549f !important}</style><script type="text/javascript" src="https://medchatapp.com/widget/widget.js?api-key=z_a91c_250qOb5muOZLhnA"></script><script>jQuery(document).ready(function (){if (window.location.href.indexOf("?q=chatinit") > -1) {console.log("Chat initiated from browser ...");window.MedChatApp.launch();}});</script>';
}

add_action( 'wp_enqueue_scripts', 'cov_network_notifications_enqueue' );
function cov_network_notifications_enqueue() {
	$css_path = plugin_dir_url( __FILE__ ) . 'assets/covenant-network-notifications.min.css';

	wp_enqueue_style(
	'covenant_network_notifications',
	plugin_dir_url( __FILE__ ) . 'assets/covenant-network-notifications.min.css',
	'main',
	'1.3.1',
	'screen'
	);

	wp_enqueue_script(
	'covenant_network_notifications',
	plugin_dir_url( __FILE__ ) . 'assets/covenant-network-notifications.min.js',
	'jquery',
	'1.3.1',
	true
	);
}

''

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
		'archived' => 0,
		'deleted'  => 0,
		'public'   => 1
		) );
		$cov_sites = get_sites( array(
		'site__in' => array(
		1,
		2,
		3,
		4,
		6,
		8,
		9,
		10,
		12,
		13,
		14,
		17,
		18,
		19,
		22,
		32,
		34,
		43,
		51,
		56,
		57,
		62,
		76,
		91,
		93
		)
		) );
		$cmg_sites = get_sites( array(
		'site__in' => array(
		9,
		11,
		15,
		16,
		20,
		21,
		23,
		24,
		25,
		26,
		27,
		29,
		30,
		31,
		33,
		36,
		37,
		39,
		40,
		42,
		44,
		46,
		47,
		48,
		49,
		51,
		52,
		53,
		54,
		58,
		59,
		61,
		63,
		65,
		66,
		67,
		68,
		69,
		70,
		72,
		73,
		74,
		77,
		78,
		80,
		81,
		82,
		85,
		88,
		94
		)
		) );

		// Get the field data from the options page on covenanthealth.com
		switch_to_blog( $site );
		$fields = array(
		'content'   => rtrim( preg_replace( '~>\\s+<~m', '><', get_field( 'cov_network_front_page_content', 'options' ) ) ),
		'reach'     => get_field( 'content_reach', 'options' ),
		'cov_blogs' => get_field( 'covenant_sites_check', 'options' ),
		'cmg_blogs' => get_field( 'cmg_sites_check', 'options' ),
		);

		$cov_sites_console = get_field( 'covenant_sites_check', 'options' );
		$cmg_sites_console = get_field( 'cmg_sites_check', 'options' );

		if ( $fields['reach']['value'] === 'other' ) {
			foreach ( $cov_sites_console as $val ) {
				array_push( $chosen, intval( $val ) );
			}
			foreach ( $cmg_sites_console as $val ) {
				array_push( $chosen, intval( $val ) );
			}
		}

		restore_current_blog();

		// What sites need this hot data injection?
		if ( $fields['reach']['value'] === 'all' ) {
			foreach ( $all_sites as $site ) {
				$id        = $site->blog_id;
				$blog_name = get_bloginfo( 'name' );
				switch_to_blog( $id ); ?>
				<?php
				if ( is_front_page() && $blog_name === get_bloginfo( 'name' ) ) {

					?>
					<script type="text/javascript">
						( function( $ ) {
							let cContent = '<?php echo $fields['content']; ?>';
							console.log( '<?php echo $site->blog_id; ?>' = '<?php echo $site->domain ?>' = '<?php echo get_current_blog_id(); ?>' = '<?php echo $id; ?>' = '<?php echo get_bloginfo( 'name' ); ?>' = '<?php echo $blog_name; ?>' );
							console.log( 'all sites' );
							console.log( cContent );
							$( document ).ready( function() {
								$( 'main' ).prepend( '<div class="row options_content_row selected_sites"><div class="col-xs-12">' + cContent + '</div></div>' );
							} );
						} )( jQuery );
					</script>
					<?php

				}

				restore_current_blog();
			}
		} else if ( $fields['reach']['value'] === 'cov' ) {
			foreach ( $cov_sites as $site ) {
				$id        = $site->blog_id;
				$blog_name = get_bloginfo( 'name' );
				switch_to_blog( $id ); ?>
				<?php
				if ( is_front_page() && $blog_name === get_bloginfo( 'name' ) ) {

					?>
					<script type="text/javascript">
						( function( $ ) {
							let cContent = '<?php echo $fields['content']; ?>';
							console.log( '<?php echo $site->blog_id; ?>' = '<?php echo $site->domain ?>' = '<?php echo get_current_blog_id(); ?>' = '<?php echo $id; ?>' = '<?php echo get_bloginfo( 'name' ); ?>' = '<?php echo $blog_name; ?>' );
							console.log( 'cov sites' );
							console.log( cContent );
							$( document ).ready( function() {
								$( 'main' ).prepend( '<div class="row options_content_row selected_sites"><div class="col-xs-12">' + cContent + '</div></div>' );
							} );
						} )( jQuery );
					</script>
					<?php

				}

				restore_current_blog();
			}
		} else if ( $fields['reach']['value'] === 'cmg' ) {
			foreach ( $cmg_sites as $site ) {
				$id        = $site->blog_id;
				$blog_name = get_bloginfo( 'name' );
				switch_to_blog( $id ); ?>
				<?php
				if ( is_front_page() && $blog_name === get_bloginfo( 'name' ) ) {

					?>
					<script type="text/javascript">
						( function( $ ) {
							let cContent = '<?php echo $fields['content']; ?>';
							console.log( '<?php echo $site->blog_id; ?>' = '<?php echo $site->domain ?>' = '<?php echo get_current_blog_id(); ?>' = '<?php echo $id; ?>' = '<?php echo get_bloginfo( 'name' ); ?>' = '<?php echo $blog_name; ?>' );
							console.log( 'cmg sites' );
							console.log( cContent );
							$( document ).ready( function() {
								$( 'main' ).prepend( '<div class="row options_content_row selected_sites"><div class="col-xs-12">' + cContent + '</div></div>' );
							} );
						} )( jQuery );
					</script>
					<?php

				}

				restore_current_blog();
			}
		} else {
			foreach ( $chosen as $siteVar ) {
				$site = get_sites( array( 'ID' => $siteVar ) );
				$id   = $site[0]->blog_id;
				console_log( $chosen );
				?>
				<?php
				$blog_name = get_bloginfo( 'name' );
				switch_to_blog( $id );
				?>
				<?php
				if ( $blog_name === get_bloginfo( 'name' ) && is_front_page() ) {
					?>
					<script type="text/javascript">
						( function( $ ) {
							let cContent = '<?php echo $fields['content']; ?>';
							console.log( '<?php echo $site[0]->blog_id; ?>' = '<?php echo $site[0]->domain ?>' = '<?php echo get_current_blog_id(); ?>' = '<?php echo $id; ?>' = '<?php echo get_bloginfo( 'name' ); ?>' = '<?php echo $blog_name; ?>' );
							console.log( 'selected sites' );
							console.log( cContent );
							$( document ).ready( function() {
								$( 'main' ).prepend( '<div class="row options_content_row selected_sites"><div class="col-xs-12">' + cContent + '</div></div>' );
							} );
						} )( jQuery );
					</script>
					<?php

				}

				restore_current_blog();
			}
		}
	}
}

function console_log( $data ) {
	echo '<script>';
	echo 'console.log(' . json_encode( $data ) . ')';
	echo '</script>';
}

function blogLoopTest( $content ) {
	$sites = get_sites(
	array(
	'archived' => 0,
	'deleted'  => 0,
	'public'   => 1
	)
	);

	if ( is_page( 15308 ) ) {
		$cmg = array();
		$non_cmg = array();

		foreach ( $sites as $site ) {
			switch_to_blog( $site->blog_id );
			$opt = get_option( 'cov_cmg_check' );

			if( $opt === 1) {
				array_push( $cmg, $site->blog_id );
			} else {
				array_push( $non_cmg, $site->blog_id );
			}
			restore_current_blog();
		}

		$content .= print_r($cmg) . '<br><br>' . print_r($non_cmg);
	}

	return $content;
}
add_filter( 'the_content', 'blogLoopTest' );

add_action( 'admin_init', 'cov_cmg_settings' );
function cov_cmg_settings() {

	$sites = get_sites();

	foreach ( $sites as $site ) {
		// Register setting that allows administrators to return
		// the affiliation type of the site.
		register_setting(
		'general',
		'cov_cmg_check',
		array(
		'type'         => 'string',
		'show_in_rest' => true,
		'description'  => 'Check whether site is a Covenant affiliate or a Covenant Medical Group affiliate'
		)
		);

		// Create the settings field for cov_cmg_check
		add_settings_field(
		'cov_cmg_check',
		'Is this a CMG site?',
		'cov_cmg_check_callback',
		'general',
		'default'
		);
	}
}

function cov_cmg_check_callback() {
	$opt = get_option( 'cov_cmg_check', 0 );

	$checked = ( isset( $opt ) && $opt == 1 ) ? 1 : 0;
	// Yes button
	$html = '<fieldset>';
	$html = '<input type="checkbox" id="cov_cmg_check" name="cov_cmg_check" value="1" ' . checked( 1, $opt, false ) . '/>';
	$html .= '<label for="cov_cmg_check">Yes, this is a CMG site</label>';
	$html .= '</fieldset>';

	echo $html;
}

add_filter( 'act/load_field/key=field_5eb4ae4943aa4', 'cmg_sites_check_2_load_field' );
function cmg_sites_check_2_load_field( $field ) {
	$sites = get_sites(
	array(
	'archived' => 0,
	'deleted'  => 0,
	'public'   => 1
	)
	);

	$field['choices'] = array();
	foreach ( $sites as $site ) {
		switch_to_blog( $site->blog_id );
		$opt = get_option( 'cov_cmg_check' );
		if ( $opt === 1 ) {
			$field['choices'][ $site->blog_id ] = $site->blogname;
		}
		restore_current_blog();
	}
	return $field;
}
