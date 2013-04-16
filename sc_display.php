<?php



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


?>