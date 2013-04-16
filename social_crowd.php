<?php
/**
 * @package Social_Crowd
 * @author Randall Hinton
 * @version 0.9.1
 */
/*
Plugin Name: Social Crowd
Plugin URI: http://www.macnative.com/socialCrowd
Description: This plugin retrieves the raw number of Friends/Followers/Fans etc from your favorite social networks and allows you to show that raw number on any page of your wordpress blog using a simple php function **Requires PHP Curl Module**
Author: Randall Hinton
Version: 0.9.1
Author URI: http://www.macnative.com/
*/

register_activation_hook( __FILE__, 'SocialCrowd_Activate' );

/**
 * Check for the former plugin version and deactivates it, otherwise set default settings
 *
 * @since 0.1
 * @author randall@macnative.com
 */
function SocialCrowd_Activate() {
		SocialCrowd_DefaultSettings();
		SocialCrowd_Add_Option_Menu();
}

if ( is_admin() ) {
	add_action('admin_menu', 'SocialCrowd_Add_Option_Menu');
	add_action('admin_menu', 'SocialCrowd_DefaultSettings');
}

/**
 * Adds the plugin's options page
 * 
 * @since 0.1
 * @author randall@macnative.com
 */
function SocialCrowd_Add_Option_Menu() {
		add_submenu_page('options-general.php', 'Social Crowd', 'Social Crowd', 8, __FILE__, 'SocialCrowd_Options_Page');
}

/**
 * Adds settings link on the plugin administration page
 * 
 * @since 0.6
 * @author randall@macnative.com
 */
function SocialCrowd_Add_Settings_Link($links) {
		$settings_link = '<a href="options-general.php?page=social-crowd/social_crowd.php">Settings</a>'; 
		array_unshift($links, $settings_link); 
		return $links;
}

$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'SocialCrowd_Add_Settings_Link' );


/**
 * Adds the plugin's default settings
 *
 * @since 0.1
 * @author randall@macnative.com
 */
function SocialCrowd_DefaultSettings() {
	if( !get_option('Social_Crowd_Timer') ) {
		add_option('Social_Crowd_Timer', '0');
	}
	if( !get_option('Social_Crowd_Facebook_Count') ) {
		add_option('Social_Crowd_Facebook_Count', '0');
	}
	if( !get_option('Social_Crowd_Twitter_Count') ) {
		add_option('Social_Crowd_Twitter_Count', '0');
	}
	if( !get_option('Social_Crowd_Twitter_friendsCount') ) {
		add_option('Social_Crowd_Twitter_friendsCount', '0');
	}
	if( !get_option('Social_Crowd_Twitter_statusesCount') ) {
		add_option('Social_Crowd_Twitter_statusesCount', '0');
	}
	if( !get_option('Social_Crowd_Twitter_listedCount') ) {
		add_option('Social_Crowd_Twitter_listedCount', '0');
	}
	if( !get_option('Social_Crowd_Youtube_Count') ) {
		add_option('Social_Crowd_Youtube_Count', '0');
	}
	if( !get_option('Social_Crowd_Youtube_subscriberCount') ) {
		add_option('Social_Crowd_Youtube_subscriberCount', '0');
	}
	if( !get_option('Social_Crowd_Youtube_viewCount') ) {
		add_option('Social_Crowd_Youtube_viewCount', '0');
	}
	if( !get_option('Social_Crowd_Youtube_uploadViewCount') ) {
		add_option('Social_Crowd_Youtube_uploadViewCount', '0');
	}
	if( !get_option('Social_Crowd_Vimeo_Count') ) {
		add_option('Social_Crowd_Vimeo_Count', '0');
	}
	if( !get_option('Social_Crowd_Vimeo_uploadedCount') ) {
		add_option('Social_Crowd_Vimeo_uploadedCount', '0');
	}
	if( !get_option('Social_Crowd_Vimeo_appearsInCount') ) {
		add_option('Social_Crowd_Vimeo_appearsInCount', '0');
	}
	if( !get_option('Social_Crowd_Vimeo_likedCount') ) {
		add_option('Social_Crowd_Vimeo_likedCount', '0');
	}

	if( !get_option('Social_Crowd_Options') ) {
		add_option('Social_Crowd_Options', 'interval:7200~get_feedburner:0~feedburner_token:0~get_facebook:0~facebook_token:0~get_twitter:0~twitter_token:0~get_youtube:0~youtube_token:0~get_vimeo:0~vimeo_token:0~get_gplus:0~gplus_token:0get_linkedin:0~linkedin_token:0');
	}
}

require_once('sc_functions.php');
require_once('sc_grab_stats.php');
require_once('sc_display.php');
require_once('sc_display_horiz.php');
require_once('sc_options.php');
require_once('sc_widget.php');
require_once('sc_widget_advanced.php');
?>