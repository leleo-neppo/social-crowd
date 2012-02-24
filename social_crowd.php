<?php
/**
 * @package Social_Crowd
 * @author Randall Hinton
 * @version 0.7.4
 */
/*
Plugin Name: Social Crowd
Plugin URI: http://www.macnative.com/socialCrowd
Description: This plugin retrieves the raw number of Friends/Followers/Fans etc from your favorite social networks and allows you to show that raw number on any page of your wordpress blog using a simple php function **Requires PHP Curl Module**
Author: Randall Hinton
Version: 0.7.4
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
		add_submenu_page('options-general.php', 'Social Crowd Options', 'Social Crowd Options', 8, __FILE__, 'SocialCrowd_Options_Page');
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
	if( !get_option('Social_Crowd_Feedburner_Count') ) {
		add_option('Social_Crowd_Feedburner_Count', '0');
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
	if( !get_option('Social_Crowd_Gplus_circled') ) {
		add_option('Social_Crowd_Gplus_circled', '0');
	}
	if( !get_option('Social_Crowd_Gplus_in_circles') ) {
		add_option('Social_Crowd_Gplus_in_circles', '0');
	}
	if( !get_option('Social_Crowd_Linked_In_Connections') ) {
		add_option('Social_Crowd_Linked_In_Connections', '0');
	}

	if( !get_option('Social_Crowd_Options') ) {
		add_option('Social_Crowd_Options', 'interval:7200~get_feedburner:0~feedburner_token:0~get_facebook:0~facebook_token:0~get_twitter:0~twitter_token:0~get_youtube:0~youtube_token:0~get_vimeo:0~vimeo_token:0~get_gplus:0~gplus_token:0get_linkedin:0~linkedin_token:0');
	}
}

/**
 * Gets Social Stats From Requested Social Networks
 *
 * @since 0.1
 * @author randall@macnative.com
 */
function SocialCrowd_GetCounts()
{
	$sc_options = SocialCrowd_GetOptions();
	
    if ($sc_options["interval"] < mktime() - get_option('Social_Crowd_Timer')) 
	{
		
		//Get Feedburner Subscribers
		if($sc_options["get_feedburner"]){
			$xml = SocialCrowd_Load_XML('https://feedburner.google.com/api/awareness/1.0/GetFeedData?uri=http://feeds.feedburner.com/'.$sc_options['feedburner_token']);

			if($sc_options["update"]){
				if ($xml->feed->entry['circulation'] != '' && $xml->feed->entry['circulation'] > get_option('Social_Crowd_Feedburner_Count')) 
				{ 
					update_option('Social_Crowd_Feedburner_Count', (string) $xml->feed->entry['circulation']); 
				}
			}else{
				if ($xml->feed->entry['circulation'] != '' && $xml->feed->entry['circulation'] > 0) 
				{ 
					update_option('Social_Crowd_Feedburner_Count', (string) $xml->feed->entry['circulation']); 
				}
			}
		}
		
		//Get Facebook Fans/Friends
		if($sc_options["get_facebook"]){
			$json = json_decode(SocialCrowd_Load_JSON('https://graph.facebook.com/'.$sc_options['facebook_token']));
			
			if($sc_options["update"]){
				if ($json->likes != '' && $json->likes > get_option('Social_Crowd_Facebook_Count')) 
				{ 
					update_option('Social_Crowd_Facebook_Count', (string) $json->likes); 
				}
			}else{
				if ($json->likes != '' && $json->likes > 0) 
				{ 
					update_option('Social_Crowd_Facebook_Count', (string) $json->likes); 
				}
			}
		}
		
		//Get Twitter Followers
		if($sc_options["get_twitter"]){
			$xml = SocialCrowd_Load_XML("http://twitter.com/users/show.xml?screen_name=".$sc_options['twitter_token']);
			
			if($sc_options["update"]){
				if ($xml->followers_count != '' && $xml->followers_count > get_option('Social_Crowd_Twitter_Count')) 
		        { 
	                update_option('Social_Crowd_Twitter_Count',  (string) $xml->followers_count); 
	            }
				if ($xml->friends_count != '' && $xml->friends_count > get_option('Social_Crowd_Twitter_friendsCount')) 
		        { 
	                update_option('Social_Crowd_Twitter_friendsCount',  (string) $xml->friends_count); 
	            }
				if ($xml->statuses_count != '' && $xml->statuses_count > get_option('Social_Crowd_Twitter_statusesCount')) 
		        { 
	                update_option('Social_Crowd_Twitter_statusesCount',  (string) $xml->statuses_count); 
	            }
				if ($xml->listed_count != '' && $xml->listed_count > get_option('Social_Crowd_Twitter_listedCount')) 
		        { 
	                update_option('Social_Crowd_Twitter_listedCount',  (string) $xml->listed_count); 
	            }
			}else{
				if ($xml->followers_count != '' && $xml->followers_count > 0) 
		        { 
	                update_option('Social_Crowd_Twitter_Count',  (string) $xml->followers_count); 
	            }
				if ($xml->friends_count != '' && $xml->friends_count > 0) 
		        { 
	                update_option('Social_Crowd_Twitter_friendsCount',  (string) $xml->friends_count); 
	            }
				if ($xml->statuses_count != '' && $xml->statuses_count > 0) 
		        { 
	                update_option('Social_Crowd_Twitter_statusesCount',  (string) $xml->statuses_count); 
	            }
				if ($xml->listed_count != '' && $xml->listed_count > 0) 
		        { 
	                update_option('Social_Crowd_Twitter_listedCount',  (string) $xml->listed_count); 
	            }
			}
		}
		
		//Get Youtube Followers
		if($sc_options["get_youtube"]){
				$xml = SocialCrowd_Load_XML('http://gdata.youtube.com/feeds/api/users/'.$sc_options['youtube_token']);
				$gd = $xml->children('http://schemas.google.com/g/2005');
			
			if($sc_options["update"]){
				foreach($gd->feedLink AS $links){
					$temp = $links->attributes();
					if(strpos($temp['rel'],"contacts") && $temp['countHint'] > get_option('Social_Crowd_Youtube_Count')){
						update_option('Social_Crowd_Youtube_Count', (string) $temp['countHint']);
					}
				}
			}else{
				foreach($gd->feedLink AS $links){
					$temp = $links->attributes();
					if(strpos($temp['rel'],"contacts") && $temp['countHint'] > 0){
						update_option('Social_Crowd_Youtube_Count', (string) $temp['countHint']);
					}
				}
			}
				
			//Get Youtube Statistics
			$yt = $xml->children('http://gdata.youtube.com/schemas/2007');
			
			$stats = $yt->statistics->attributes();
			if($sc_options["update"]){
				if($stats["subscriberCount"] != '' && $stats["subscriberCount"] > get_option('Social_Crowd_Youtube_subscriberCount')){
					update_option('Social_Crowd_Youtube_subscriberCount', (string) $stats['subscriberCount']);
				}
				if($stats["viewCount"] != '' && $stats["viewCount"] > get_option('Social_Crowd_Youtube_viewCount')){
					update_option('Social_Crowd_Youtube_viewCount', (string) $stats['viewCount']);
				}
				if($stats["totalUploadViews"] != '' && $stats["totalUploadViews"] > get_option('Social_Crowd_Youtube_uploadViewCount')){
					update_option('Social_Crowd_Youtube_uploadViewCount', (string) $stats['totalUploadViews']);
				}
			}else{
				if($stats["subscriberCount"] != '' && $stats["subscriberCount"] > 0){
					update_option('Social_Crowd_Youtube_subscriberCount', (string) $stats['subscriberCount']);
				}
				if($stats["viewCount"] != '' && $stats["viewCount"] > 0){
					update_option('Social_Crowd_Youtube_viewCount', (string) $stats['viewCount']);
				}
				if($stats["totalUploadViews"] != '' && $stats["totalUploadViews"] > 0){
					update_option('Social_Crowd_Youtube_uploadViewCount', (string) $stats['totalUploadViews']);
				}
			}
		}
		
		//Get Vimeo Contacts
		if($sc_options["get_vimeo"]){
			$xml = SocialCrowd_Load_XML("http://vimeo.com/api/v2/".$sc_options['vimeo_token']."/info.xml");
			if($sc_options["update"]){
				if ($xml->user->total_contacts != '' && $xml->user->total_contacts > get_option('Social_Crowd_Vimeo_Count')) 
		        { 
	                update_option('Social_Crowd_Vimeo_Count',  (string) $xml->user->total_contacts); 
	            }
				if ($xml->user->total_videos_uploaded != '' && $xml->user->total_videos_uploaded > get_option('Social_Crowd_Vimeo_uploadedCount')) 
		        { 
	                update_option('Social_Crowd_Vimeo_uploadedCount',  (string) $xml->user->total_videos_uploaded); 
	            }
				if ($xml->user->total_videos_appears_in != '' && $xml->user->total_videos_appears_in > get_option('Social_Crowd_Vimeo_appearsInCount')) 
		        { 
	                update_option('Social_Crowd_Vimeo_appearsInCount',  (string) $xml->user->total_videos_appears_in); 
	            }
				if ($xml->user->total_videos_liked != '' && $xml->user->total_videos_liked > get_option('Social_Crowd_Vimeo_likedCount')) 
		        { 
	                update_option('Social_Crowd_Vimeo_likedCount',  (string) $xml->user->total_videos_liked); 
	            }
			}else{
				if ($xml->user->total_contacts != '' && $xml->user->total_contacts > 0) 
		        { 
	                update_option('Social_Crowd_Vimeo_Count',  (string) $xml->user->total_contacts); 
	            }
				if ($xml->user->total_videos_uploaded != '' && $xml->user->total_videos_uploaded > 0) 
		        { 
	                update_option('Social_Crowd_Vimeo_uploadedCount',  (string) $xml->user->total_videos_uploaded); 
	            }
				if ($xml->user->total_videos_appears_in != '' && $xml->user->total_videos_appears_in > 0) 
		        { 
	                update_option('Social_Crowd_Vimeo_appearsInCount',  (string) $xml->user->total_videos_appears_in); 
	            }
				if ($xml->user->total_videos_liked != '' && $xml->user->total_videos_liked > 0) 
		        { 
	                update_option('Social_Crowd_Vimeo_likedCount',  (string) $xml->user->total_videos_liked); 
	            }
			}
		}
		
		//Get Google Plus Circles added 0.5
		if($sc_options["get_gplus"]){
			$scrape = SocialCrowd_Load_JSON('https://plus.google.com/'.$sc_options['gplus_token'].'/posts');

			$pattern = "|(?<=circles \()(.*?)(?=\)<)|";
			$pattern2 = "|(?<=in circles \()(.*?)(?=\)<)|";
			$temp1 = preg_match($pattern, $scrape, $matches);
			$temp2 = preg_match($pattern2, $scrape, $matches2);
			if(is_numeric($matches[0]) && is_numeric($matches2[0])){
				if($sc_options["update"]){
					if ($matches[0] != '' && $matches[0] > get_option('Social_Crowd_Gplus_circled')) 
					{ 
						update_option('Social_Crowd_Gplus_circled', (string) $matches[0]); 
					}
					if ($matches2[0] != '' && $matches2[0] > get_option('Social_Crowd_Gplus_in_circles')) 
					{ 
						update_option('Social_Crowd_Gplus_in_circles', (string) $matches2[0]); 
					}
				}else{
					if ($matches[0] != '' && $matches[0] > 0) 
					{ 
						update_option('Social_Crowd_Gplus_circled', (string) $matches[0]); 
					}
					if ($matches2[0] != '' && $matches2[0] > 0) 
					{ 
						update_option('Social_Crowd_Gplus_in_circles', (string) $matches2[0]); 
					}
				}
			}
		}
		
		//Get LinkedIn Connections added 0.6
		if($sc_options["get_linkedin"]){
			//$scrape = SocialCrowd_Load_JSON('http://www.linkedin.com/in/'.$sc_options['linkedin_token']);
			if(stristr($sc_options["linkedin_token"],"//")){
				$scrape = SocialCrowd_Load_JSON('http:'.$sc_options['linkedin_token']);
			}else{
				$scrape = SocialCrowd_Load_JSON('http://www.linkedin.com/in/'.$sc_options['linkedin_token']);
			}

			$temp1 = explode('overview-connections', $scrape);
			$temp2 = explode('<strong>',$temp1[1]);
			$self = explode('</strong>',$temp2[1]);
			if(is_numeric($self[0])){
				if($sc_options["update"]){
					if ($self[0] != '' && $self[0] > get_option('Social_Crowd_Linked_In_Connections')) 
					{ 
						update_option('Social_Crowd_Linked_In_Connections', (string) $self[0]); 
					}
				}else{
					if ($self[0] != '' && $self[0] > 0) 
					{ 
						update_option('Social_Crowd_Linked_In_Connections', (string) $self[0]); 
					}
				}
			}
		}
		
		//Mailchimp api call = http://us1.api.mailchimp.com/1.3/?method=lists&apikey=1fa32d83fc746903f28067258f2e70d6-us1
		
		update_option('Social_Crowd_Timer', mktime());		
	}   
}

/**
 * Add XML Loading Function
 * 
 * @since 0.1
 * @author randall@macnative.com
 */
function SocialCrowd_Load_XML($url) 
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $count = curl_exec($curl);
    curl_close($curl);
    return @simplexml_load_string($count);
}

/**
 * Add JSON Loading Function
 * 
 * @since 0.1
 * @author randall@macnative.com
 */
function SocialCrowd_Load_JSON($url) 
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $count = curl_exec($curl);
    curl_close($curl);
    return $count;
 }

/**
 * Outputs Count Variables individually or as an array
 *
 * @since 0.1
 * @author randall@macnative.com
 */
function SocialCrowd_Stats($which = "all")
{
	SocialCrowd_GetCounts();
	if($which == "all"){
		$stats = array();
		$stats["feedburner"] = get_option('Social_Crowd_Feedburner_Count');
		$stats["facebook"] = get_option('Social_Crowd_Facebook_Count');
		$stats["twitter"] = get_option('Social_Crowd_Twitter_Count');
		$stats["twitterFriends"] = get_option('Social_Crowd_Twitter_friendsCount');
		$stats["twitterStatuses"] = get_option('Social_Crowd_Twitter_statusesCount');
		$stats["twitterListed"] = get_option('Social_Crowd_Twitter_listedCount');
		$stats["youtube"] = get_option('Social_Crowd_Youtube_Count');
		$stats["youtubeSubscribers"] = get_option('Social_Crowd_Youtube_subscriberCount');
		$stats["youtubeViews"] = get_option('Social_Crowd_Youtube_viewCount');
		$stats["youtubeUploadViews"] = get_option('Social_Crowd_Youtube_uploadViewCount');
		$stats["vimeo"] = get_option('Social_Crowd_Vimeo_Count');
		$stats["vimeoUploads"] = get_option('Social_Crowd_Vimeo_uploadedCount');
		$stats["vimeoAppearsIn"] = get_option('Social_Crowd_Vimeo_appearsInCount');
		$stats["vimeoLikes"] = get_option('Social_Crowd_Vimeo_likedCount');
		$stats["gplusCircles"] = get_option('Social_Crowd_Gplus_circled');
		$stats["gplusInCircles"] = get_option('Social_Crowd_Gplus_in_circles');
		$stats["linkedIn"] = get_option('Social_Crowd_Linked_In_Connections');
		return $stats;
	}else{
		switch($which){
			case feedburner:
				echo get_option('Social_Crowd_Feedburner_Count');
			break;
			case facebook:
				echo get_option('Social_Crowd_Facebook_Count');
			break;
			case twitter:
				echo get_option('Social_Crowd_Twitter_Count');
			break;
			case twitterFriends:
				echo get_option('Social_Crowd_Twitter_friendsCount');
			break;
			case twitterStatuses:
				echo get_option('Social_Crowd_Twitter_statusesCount');
			break;
			case twitterListed:
				echo get_option('Social_Crowd_Twitter_listedCount');
			break;
			case youtube:
				echo get_option('Social_Crowd_Youtube_Count');
			break;
			case youtubeSubscribers:
				echo get_option('Social_Crowd_Youtube_subscriberCount');
			break;
			case youtubeViews:
				echo get_option('Social_Crowd_Youtube_viewCount');
			break;
			case youtubeUploadViews:
				echo get_option('Social_Crowd_Youtube_uploadViewCount');
			break;
			case vimeo:
				echo get_option('Social_Crowd_Vimeo_Count');
			break;
			case vimeoUploads:
				echo get_option('Social_Crowd_Vimeo_uploadedCount');
			break;
			case vimeoAppearsIn:
				echo get_option('Social_Crowd_Vimeo_appearsInCount');
			break;
			case vimeoLikes:
				echo get_option('Social_Crowd_Vimeo_likedCount');
			break;
			case gplusCircles:
				echo get_option('Social_Crowd_Gplus_circled');
			break;
			case gplusInCircles:
				echo get_option('Social_Crowd_Gplus_in_circles');
			break;
			case linkedIn:
				echo get_option('Social_Crowd_Linked_In_Connections');
			break;
		}
	}
}

/**
 * Shortcode for displaying the Social Crowd Stats.
 *
 * @since 0.3
 * @author randall@macnative.com
 */

add_shortcode('SC_Stats', 'SocialCrowd_Stats_SC');

//define shortcode to display Social Crowd Stats on your wordpress site
//
//shortcode options:
// type -> code for the stats to display ie: type=facebook
//
function SocialCrowd_Stats_SC( $atts ) {
	extract( shortcode_atts( array(
			'type' => 'facebook'
		), $atts ) );

ob_start();

SocialCrowd_Stats($type);

$output = ob_get_contents();
ob_end_clean();

return $output;

}

/**
 * Gets options string from the DB and converts it into an array
 *
 * @since 0.1
 * @author randall@macnative.com
 */
function SocialCrowd_GetOptions()
{
	$options = array();
	$suboptions = explode("~",get_option('Social_Crowd_Options'));
	for($x=0; $x < count($suboptions); $x++){
		$temp = explode(":",$suboptions[$x]);
		$options[$temp[0]] = $temp[1];
	}
	return $options;
}

/**
 * Return Select Form Element
 *
 * @since 0.1
 * @author randall@macnative.com
 */
function SocialCrowd_Make_Select($x = "", $fields, $class="select", $id="select", $name="select") {
	echo '<select name="'.$name.'" id="'.$id.'" class="'.$class.'">';
		foreach ($fields as $shown => $value) {
			if($x == $value){
				echo '<option value="'.$value.'" selected="selected" >'.$shown.'</option>';
			}else{
				echo '<option value="'.$value.'" >'.$shown.'</option>';
			}
		}
	echo '</select>';
}

/**
 * Adds content to the plugin's options page
 *
 * @since 0.1
 * @author randall@macnative.com
 */
function SocialCrowd_Options_Page() {
	if (isset($_POST['action']) === true) {
		$options_string = "";
		if(isset($_POST["sc_interval"])){
			$options_string .= "interval:".$_POST["sc_interval"];
		}
		
		if(isset($_POST["sc_update"])){
			$options_string .= "~update:".$_POST["sc_update"];
		}
		
		if(isset($_POST["sc_feedburner_enabled"])){
			$options_string .= "~get_feedburner:1";
		}else{
			$options_string .= "~get_feedburner:0";
		}
		
		if(isset($_POST["sc_feedburner"]) && $_POST["sc_feedburner"] != ""){
			if(stristr($_POST["sc_feedburner"],"http")){
				$temp = explode("/",$_POST["sc_feedburner"]);
				$fb_token = $temp[3];
			}else{
				$fb_token = $_POST["sc_feedburner"];
			}
			$options_string .= "~feedburner_token:".$fb_token;
		}else{
			$options_string .= "~feedburner_token:0";
		}
		
		if(isset($_POST["sc_facebook_enabled"])){
			$options_string .= "~get_facebook:1";
		}else{
			$options_string .= "~get_facebook:0";
		}
		
		if(isset($_POST["sc_facebook"]) && $_POST["sc_facebook"] != ""){
			if(stristr($_POST["sc_facebook"],"http")){
				$temp = explode("/",$_POST["sc_facebook"]);
				$fb_token = $temp[3];
			}else{
				$fb_token = $_POST["sc_facebook"];
			}
			$options_string .= "~facebook_token:".$fb_token;
		}else{
			$options_string .= "~facebook_token:0";
		}
		
		if(isset($_POST["sc_twitter_enabled"])){
			$options_string .= "~get_twitter:1";
		}else{
			$options_string .= "~get_twitter:0";
		}
		
		if(isset($_POST["sc_twitter"]) && $_POST["sc_twitter"] != ""){
			if(stristr($_POST["sc_twitter"],"http")){
				$temp = explode("/",$_POST["sc_twitter"]);
				$t_token = $temp[3];
			}else{
				$t_token = $_POST["sc_twitter"];
			}
			$options_string .= "~twitter_token:".$t_token;
		}else{
			$options_string .= "~twitter_token:0";
		}
		
		if(isset($_POST["sc_youtube_enabled"])){
			$options_string .= "~get_youtube:1";
		}else{
			$options_string .= "~get_youtube:0";
		}
		
		if(isset($_POST["sc_youtube"]) && $_POST["sc_youtube"] != ""){
			if(stristr($_POST["sc_youtube"],"http")){
				$temp = explode("/",$_POST["sc_youtube"]);
				$yt_token = $temp[4];
			}else{
				$yt_token = $_POST["sc_youtube"];
			}
			$options_string .= "~youtube_token:".$yt_token;
		}else{
			$options_string .= "~youtube_token:0";
		}
		
		if(isset($_POST["sc_vimeo_enabled"])){
			$options_string .= "~get_vimeo:1";
		}else{
			$options_string .= "~get_vimeo:0";
		}
		
		if(isset($_POST["sc_vimeo"]) && $_POST["sc_vimeo"] != ""){
			if(stristr($_POST["sc_vimeo"],"http")){
				$temp = explode("/",$_POST["sc_vimeo"]);
				$v_token = $temp[3];
			}else{
				$v_token = $_POST["sc_vimeo"];
			}
			$options_string .= "~vimeo_token:".$v_token;
		}else{
			$options_string .= "~vimeo_token:0";
		}
		
		if(isset($_POST["sc_gplus_enabled"])){
			$options_string .= "~get_gplus:1";
		}else{
			$options_string .= "~get_gplus:0";
		}
		
		if(isset($_POST["sc_gplus"]) && $_POST["sc_gplus"] != ""){
			if(stristr($_POST["sc_gplus"],"http")){
				$temp = explode("/",$_POST["sc_gplus"]);
				$gp_token = $temp[3];
			}else{
				$gp_token = $_POST["sc_gplus"];
			}
			$options_string .= "~gplus_token:".$gp_token;
		}else{
			$options_string .= "~gplus_token:0";
		}
		
		if(isset($_POST["sc_linkedin_enabled"])){
			$options_string .= "~get_linkedin:1";
		}else{
			$options_string .= "~get_linkedin:0";
		}
		
		if(isset($_POST["sc_linkedin"]) && $_POST["sc_linkedin"] != ""){
			if(stristr($_POST["sc_linkedin"],"http")){
				$temp = explode(":",$_POST["sc_linkedin"]);
				$li_token = $temp[1];
			}else{
				$li_token = $_POST["sc_linkedin"];
			}
			$options_string .= "~linkedin_token:".$li_token;
		}else{
			$options_string .= "~linkedin_token:0";
		}
		
		
		if(update_option("Social_Crowd_Options", $options_string)){
			$update_success = "Social Crowd Options Updated Successfully";
		}else{
			$update_error = "Social Crowd Options Failed To Update";
		}
	
		echo '<script type="text/javascript">
		
		jQuery(document).ready(function($) {
			$(".fade").delay(4000).slideUp(1000);
		});
		
		</script>';
	}
		
	$sc_options = SocialCrowd_GetOptions();	
?>
<style type="text/css">
.sc_disabled{
	background: #EBEBEB;
}

#sc_ids_box {
	background: #F5F5F5;
	width: 759px;
}

#sc_ids_box ul{
	margin-top: 15px;
	width: 750px;
}

#sc_ids_box li{
	padding: 5px 0px 5px 25px;
	background: #F5F5F5;
	margin-bottom: 0px;
	height: 59px;
}

#sc_ids_box li.disabled{
	background: #D5D5D5;
}

#sc_ids_box dl {
	padding-bottom: 5px;
	clear: both;
}

#sc_ids_box dt {
	float: left;
	width: 155px;
	padding: 10px 0px 0px 0px;
}

#sc_ids_box dd {
	float: left;
	width: 525px;
	padding: 5px 0px;
}

#sc_ids_box .labels {
	font-size: 12px;
	font-weight: bold;
	width: 225px;
	text-align: right;
	margin: 0px 15px 5px 0px;
}

#sc_ids_box label img {
	margin: 0px 5px -7px 0px;
}

#sc_ids_box .sc_nocheckbox {
	margin: 0px 5px -7px 27px;
}

#sc_ids_box .checkboxr{
	margin: 0px 15px 0px 0px;
}

.sc_example {
	font-size: 10px;
	font-style: italic;
	color: #999;
}

.sc_example2 {
	font-weight: bold;
	color: #888;
}

#curlMsg {
	width: 70%;
	border: 1px solid;
	padding: 10px 20px;
	margin: 10px auto;
	text-align: auto;
	font-size: 12px;
}

.loaded {
	border-color: #007E0C;
	color: #007E0C;
	background-color: #AEDB59;
}

.notloaded {
	border-color: #6C141A;
	color: #6C141A;
	background-color: #F6B9BB;
}

</style>

<script type="text/javascript">
function enable_options() {
	var elements = new Array();
	elements[0] = "feedburner";
	elements[1] = "facebook";
	elements[2] = "gplus";
	elements[3] = "twitter";
	elements[4] = "linkedin";
	elements[5] = "youtube";
	elements[6] = "vimeo";
	for (i=0; i < elements.length; i++) {
		if(eval('sc_' + elements[i] + '_enabled').checked){
			document.getElementById('sc_' + elements[i] + '_row').className = "";
		}else{
			document.getElementById('sc_' + elements[i] + '_row').className = "disabled";
		}
	}
}
</script>

<?php 
	$siteurl = get_option('siteurl');
	$img_url = $siteurl . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/images/';
 ?>

	<div class="wrap">
		<div id="icon-plugins" class="icon32"></div><h2>Social Crowd Admin Options</h2>
		<?php
		if(isset($update_success)){	
			echo '<div id="message" class="updated fade">
				<p>
					<strong>
						' . $update_success . '
					</strong>
				</p>
			</div>';
		}
		
		if(isset($update_error)){	
			echo '<div id="message" class="error">
				<p>
					<strong>
						' . $update_error . '
					</strong>
				</p>
			</div>';
		}
		
		if(extension_loaded(curl)){
			$curl_class = "loaded";
			$curl_msg = "Congratulations the PHP Curl Module is loaded, Social Crowd will Function Properly";
		}else {
			$curl_class = "notloaded";
			$curl_msg = "Sorry but the PHP Curl Module is not loaded, it is required for Social Crowd to Function";
		}
		
		?>
		<form name="sc_form" id="sc_form" action="" method="post">
		<input type="hidden" name="action" value="edit" />
			<div id="poststuff" class="ui-sortable">
			<div id="sc_ids_box" class="postbox if-js-open">
			<h3>Social Crowd Admin Options</h3>
			<div id="curlMsg" class="<?php echo $curl_class ?>"><?php echo $curl_msg ?></div>
     		<ul>
				<li id="sc_interval_row">
					<dl>
						<dt><label for"sc_interval" class="labels"><img src="<?php echo $img_url."clock.png" ?>" title="Interval" class="sc_nocheckbox">&nbsp;Interval</label></dt>
						<dd><? 
								$interval_fields = array(
									'15 Minutes' => '900', '30 Minutes' => '1800', '1 Hour' => '3600', '2 Hours' => '7200', '6 Hours' => '21600', '12 Hours' => '43200', '1 Day' => '86400');
								SocialCrowd_Make_Select($sc_options['interval'], $interval_fields, "", "sc_interval", "sc_interval"); 
							?>
							&nbsp;&nbsp;How often do you want to update your Social Crowd Stats? <br /><span class="sc_example">ie: Once per Hour (Don't abuse your Favorite Social Networks)</span></dd>
					</dl>
				</li>
				<li id="sc_update_row">
					<dl>
						<dt><label for"sc_update" class="labels"><img src="<?php echo $img_url."update.png" ?>" title="Update" class="sc_nocheckbox">&nbsp;Update Type</label></dt>
						<dd><? 
								$update_fields = array(
									'Current' => '0', 'Maximum' => '1');
								SocialCrowd_Make_Select($sc_options['update'], $update_fields, "", "sc_update", "sc_update"); 
							?>
							&nbsp;&nbsp;What kind of updates do you want? <br /><span class="sc_example">ie: "Current" = Actual number reported, "Maximum" = Only update if current value is Higher.</span></dd>
					</dl>
				</li>
				<li id="sc_feedburner_row">
					<dl>
						<dt>
							<label for"sc_feedburner" class="labels">
								<input type="checkbox" name="sc_feedburner_enabled" id="sc_feedburner_enabled" class="checkboxr" <?php echo ( $sc_options['get_feedburner']=='1' ) ? ' checked="checked"' : '' ?> onchange="enable_options()" >
								<img src="<?php echo $img_url."feed.png" ?>" title="Feedburner">&nbsp;Feedburner
							</label>
						</dt>
						<dd>
							<input type="input" maxlength="128" size="25" name="sc_feedburner" id="sc_feedburner" value="<?php echo ( $sc_options['feedburner_token']!='0' ) ? $sc_options['feedburner_token'] : '' ?>">
							&nbsp;&nbsp;Your Feedburner ID <br /><span class="sc_example">ie: http://feeds.feedburner.com/</span><span class="sc_example sc_example2">feedname</span> <span class="sc_example">Also ensure that the "Awareness API" access is enabled in your feedburner settings under the Publicize tab.</span>
						</dd>
					</dl>
				</li>
				<li id="sc_facebook_row">
					<dl>
						<dt>
							<label for"sc_facebook" class="labels">
								<input type="checkbox" name="sc_facebook_enabled" id="sc_facebook_enabled" class="checkboxr" <?php echo ( $sc_options['get_facebook']=='1' ) ? ' checked="checked"' : '' ?> onchange="enable_options()" >
								<img src="<?php echo $img_url."facebook.png" ?>" title="Facebook">&nbsp;Facebook
							</label>
						</dt>
						<dd>
							<input type="input" maxlength="128" size="25" name="sc_facebook" id="sc_facebook" value="<?php echo ( $sc_options['facebook_token']!='0' ) ? $sc_options['facebook_token'] : '' ?>">
							&nbsp;&nbsp;Your Facebook ID <br /><span class="sc_example">ie: http://www.facebook.com/<span class="sc_example sc_example2">123456789012</span> or http://www.facebook.com/<span class="sc_example sc_example2">VanityID</span></span>
						</dd>
					</dl>
				</li>
				<li id="sc_gplus_row">
					<dl>
						<dt>
							<label for"sc_gplus" class="labels">
								<input type="checkbox" name="sc_gplus_enabled" id="sc_gplus_enabled" class="checkboxr" <?php echo ( $sc_options['get_gplus']=='1' ) ? ' checked="checked"' : '' ?> onchange="enable_options()" >
								<img src="<?php echo $img_url."google.png" ?>" title="Google+">&nbsp;Google+ (beta)
							</label>
						</dt>
						<dd>
							<input type="input" maxlength="128" size="25" name="sc_gplus" id="sc_gplus" value="<?php echo ( $sc_options['gplus_token']!='0' ) ? $sc_options['gplus_token'] : '' ?>">
							&nbsp;&nbsp;Your Google+ ID <br /><span class="sc_example">ie: http://plus.google.com/<span class="sc_example sc_example2">123456789012</span>/posts (search for yourself on google+ after signing out).</span>
						</dd>
					</dl>
				</li>
				<li id="sc_twitter_row">
					<dl>
						<dt>
							<label for"sc_twitter" class="labels">
								<input type="checkbox" name="sc_twitter_enabled" id="sc_twitter_enabled" class="checkboxr" <?php echo ( $sc_options['get_twitter']=='1' ) ? ' checked="checked"' : '' ?> onchange="enable_options()" >
								<img src="<?php echo $img_url."twitter.png" ?>" title="Twiter">&nbsp;Twitter
							</label>
						</dt>
						<dd>
							<input type="input" maxlength="128" size="25" name="sc_twitter" id="sc_twitter" value="<?php echo ( $sc_options['twitter_token']!='0' ) ? $sc_options['twitter_token'] : '' ?>">
							&nbsp;&nbsp;Your Twitter ID <br /><span class="sc_example">ie: http://www.twitter.com/</span><span class="sc_example sc_example2">username</span> 
						</dd>
					</dl>
				</li>
				<li id="sc_linkedin_row">
					<dl>
						<dt>
							<label for"sc_linkedin" class="labels">
								<input type="checkbox" name="sc_linkedin_enabled" id="sc_linkedin_enabled" class="checkboxr" <?php echo ( $sc_options['get_linkedin']=='1' ) ? ' checked="checked"' : '' ?> onchange="enable_options()" >
								<img src="<?php echo $img_url."linkedin.png" ?>" title="LinkedIn">&nbsp;Linked In (beta)
							</label>
						</dt>
						<dd>
							<input type="input" maxlength="128" size="25" name="sc_linkedin" id="sc_linkedin" value="<?php echo ( $sc_options['linkedin_token']!='0' ) ? $sc_options['linkedin_token'] : '' ?>">
							&nbsp;&nbsp;Your Linked In Public Profile URL <br /><span class="sc_example">ie: <span class="sc_example sc_example2">http://www.linkedin.com/in/johndoe</span> or <span class="sc_example sc_example2">http://www.linkedin.com/pub/janedoe/12/232/123</span></span>
						</dd>
					</dl>
				</li>
				<li id="sc_youtube_row">
					<dl>
						<dt>
							<label for"sc_youtube" class="labels">
								<input type="checkbox" name="sc_youtube_enabled" id="sc_youtube_enabled" class="checkboxr" <?php echo ( $sc_options['get_youtube']=='1' ) ? ' checked="checked"' : '' ?> onchange="enable_options()" >
								<img src="<?php echo $img_url."youtube.png" ?>" title="YouTube">&nbsp;YouTube
							</label>
						</dt>
						<dd>
							<input type="input" maxlength="128" size="25" name="sc_youtube" id="sc_youtube" value="<?php echo ( $sc_options['youtube_token']!='0' ) ? $sc_options['youtube_token'] : '' ?>">
							&nbsp;&nbsp;Your YouTube User ID <br /><span class="sc_example">ie: http://www.youtube.com/user/</span><span class="sc_example sc_example2">username</span>
						</dd>
					</dl>
				</li>
				<li id="sc_vimeo_row">
					<dl>
						<dt>
							<label for"sc_vimeo" class="labels">
								<input type="checkbox" name="sc_vimeo_enabled" id="sc_vimeo_enabled" class="checkboxr" <?php echo ( $sc_options['get_vimeo']=='1' ) ? ' checked="checked"' : '' ?> onchange="enable_options()" >
								<img src="<?php echo $img_url."vimeo.png" ?>" title="Vimeo">&nbsp;Vimeo
							</label>
						</dt>
						<dd>
							<input type="input" maxlength="128" size="25" name="sc_vimeo" id="sc_vimeo" value="<?php echo ( $sc_options['vimeo_token']!='0' ) ? $sc_options['vimeo_token'] : '' ?>">
							&nbsp;&nbsp;Your Vimeo User ID <br /><span class="sc_example">ie: http://www.vimeo.com/<span class="sc_example sc_example2">username</span>
						</dd>
					</dl>
				</li>
				
      	
			
      		<script type="text/javascript">
			enable_options();
			</script>
		
		
			<div class="inside">
			<p class="submit">
				<input type="submit" name="submit" value="Save Options &raquo;" class="button-primary" />
			</p>
			</div>
			</div>
			</form>
		</div>
 	</div>
<?php
}

require_once('sc_widget.php');
require_once('sc_widget_advanced.php');
?>