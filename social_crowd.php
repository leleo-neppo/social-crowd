<?php
/**
 * @package Social_Crowd
 * @author Randall Hinton
 * @version 0.9.6.1
 */
/*
Plugin Name: Social Crowd
Plugin URI: http://www.macnative.com/socialCrowd
Description: This plugin retrieves the raw number of Friends/Followers/Fans etc from your favorite social networks and allows you to show that raw number on any page of your wordpress blog using a simple php function **Requires PHP Curl Module**
Author: Randall Hinton
Version: 0.9.6.1
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
	//add_action('admin_menu', 'SocialCrowd_DefaultSettings');
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
	
	//Initiallize 
	if( !get_option('Social_Crowd_Options') ) {
		$socialCrowdOptions = array("interval" => "21600", "update" => "max", "get_facebook" => 0, "facebook_token" => "", "get_twitter" => 0, "twitter_token" => "", "get_youtube" => 0, "youtube_token" => "", "get_vimeo" => 0, "vimeo_token" => "", "get_gplus" => 0, "gplus_token" => "", "get_linkedin" => 0, "linkedin_token" => "", "get_feedburner" => 0, "feedburner_token" => "");
		add_option('Social_Crowd_Options', $socialCrowdOptions);
	}
	
	//Initialize Stats Array
	if( !get_option('Social_Crowd_Stats') ) {
		$scStats = array("faceBook" => array("likes" => 0, "talkingAbout" => 0), "twitter" => array("followers" => 0,"friends" => 0,"statuses" => 0,"listed" => 0), "youTube" => array("contacts"=> 0,"subscribers" => 0,"views" => 0,"uploadViews"=> 0), "vimeo" => array("contacts" => 0,"uploaded" => 0,"appearsIn" => 0,"liked" => 0), "googlePlus" => array("circled" => 0, "inCircles" => 0), "feedBurner" => array("subscribers" => 0), "linkedIn" => array("connections" => 0));
		
		add_option('Social_Crowd_Stats', $scStats);
	}
	
	//Mark Current Installed Version
	if( !get_option('Social_Crowd_Version') ) {
		add_option('Social_Crowd_Version', '0.9.6.1');
		if($scTimer = get_option('Social_Crowd_Timer')){
			Social_Crowd_Update();
		}
	}else{
		update_option('Social_Crowd_Version', '0.9.6.1');
	}
	
	//Create and reset the timer
	if( !get_option('Social_Crowd_Timer') ) {
		add_option('Social_Crowd_Timer', '0');
	}
	
	//Get Web Service Key
	if( !get_option('Social_Crowd_Key') ) {
		$str_req = SocialCrowd_RandString(3, false);	
		$str_req .= SocialCrowd_RandString();
		$key = SocialCrowd_Get("http://api.macnative.com/sc/?reqStr=".$str_req);
		if(strlen($key) == 32){
			add_option('Social_Crowd_Key', $key);
		}else{
			add_option('Social_Crowd_Key', '0');
		}
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