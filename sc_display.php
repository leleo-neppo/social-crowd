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
	
	$currStats = get_option('Social_Crowd_Stats');
	
	if($which == "all"){
		$stats = array();
		$stats["feedburner"] = $currStats["feedBurner"]["subscribers"];
		$stats["facebook"] = $currStats["faceBook"]["likes"];
		$stats["twitter"] = $currStats["twitter"]["followers"];
		$stats["twitterFriends"] = $currStats["twitter"]["friends"];
		$stats["twitterStatuses"] = $currStats["twitter"]["statuses"];
		$stats["twitterListed"] = $currStats["twitter"]["listed"];
		$stats["youtube"] = $currStats["youTube"]["contacts"];
		$stats["youtubeSubscribers"] = $currStats["youTube"]["subscribers"];
		$stats["youtubeViews"] = $currStats["youTube"]["views"];
		$stats["youtubeUploadViews"] = $currStats["youTube"]["uploadViews"];
		$stats["vimeo"] = $currStats["vimeo"]["contacts"];
		$stats["vimeoUploads"] = $currStats["vimeo"]["uploaded"];
		$stats["vimeoAppearsIn"] = $currStats["vimeo"]["appearsIn"];
		$stats["vimeoLikes"] = $currStats["vimeo"]["liked"];
		$stats["gplusCircles"] = $currStats["googlePlus"]["circled"];
		$stats["gplusInCircles"] = $currStats["googlePlus"]["inCircles"];
		$stats["linkedIn"] = $currStats["linkedIn"]["connections"];
		return $stats;
	}else{
		switch($which){
			case feedburner:
				echo $currStats["feedBurner"]["subscribers"];
			break;
			case facebook:
				echo $currStats["faceBook"]["likes"];
			break;
			case twitter:
				echo $currStats["twitter"]["followers"];
			break;
			case twitterFriends:
				echo $currStats["twitter"]["friends"];
			break;
			case twitterStatuses:
				echo $currStats["twitter"]["statuses"];
			break;
			case twitterListed:
				echo $currStats["twitter"]["listed"];
			break;
			case youtube:
				echo $currStats["youTube"]["contacts"];
			break;
			case youtubeSubscribers:
				echo $currStats["youTube"]["subscribers"];
			break;
			case youtubeViews:
				echo $currStats["youTube"]["views"];
			break;
			case youtubeUploadViews:
				echo $currStats["youTube"]["uploadViews"];
			break;
			case vimeo:
				echo $currStats["vimeo"]["contacts"];
			break;
			case vimeoUploads:
				echo $currStats["vimeo"]["uploaded"];
			break;
			case vimeoAppearsIn:
				echo $currStats["vimeo"]["appearsIn"];
			break;
			case vimeoLikes:
				echo $currStats["vimeo"]["liked"];
			break;
			case gplusCircles:
				echo $currStats["googlePlus"]["circled"];
			break;
			case gplusInCircles:
				echo $currStats["googlePlus"]["inCircles"];
			break;
			case linkedIn:
				echo $currStats["linkedIn"]["connections"];
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