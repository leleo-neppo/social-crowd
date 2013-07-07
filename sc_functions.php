<?php
/**
 * Core functions for Plugin
 */

/**
 * Old Get Options function Gets options string from the DB and converts it into an array (left for upgrade purpouses)
 *
 * @since 0.1
 * @author randall@macnative.com
 */
function SocialCrowd_GetOptions_Orig()
{
	$testOptions = get_option('Social_Crowd_Options');
	if(strpos($testOptions, "~")){
		$options = array();
		$suboptions = explode("~",get_option('Social_Crowd_Options'));
		for($x=0; $x < count($suboptions); $x++){
			$temp = explode(":",$suboptions[$x]);
			$options[$temp[0]] = $temp[1];
		}
		return $options;
	}else{
		return false;
	}
	
}

/**
 * Gets options string from the DB and converts it into an array (left for compatibility and convenience in upgrading)
 *
 * @since 0.9.6
 * @author randall@macnative.com
 */
function SocialCrowd_GetOptions()
{
	return get_option('Social_Crowd_Options');
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
 * Grab Results from Social Crowd Web Service
 * 
 * @since 0.9.6
 * @author randall@macnative.com
 */
function SocialCrowd_Get($url) 
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_USERAGENT, "SocialCrowd v0.9.6");
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

/**
 * Upgrade Social Crowd Options to be stored as a regular serialized array
 * 
 * @since 0.9.6
 * @author randall@macnative.com
 */
function Social_Crowd_Update(){
	//fix Options
	if(!$options = SocialCrowd_GetOptions_Orig()){
		$options = array();
	}
	if($options["interval"] < "21600"){
		$options["interval"] = "21600";
	}
	if($options["update"] == 1){
		$options["update"] = "max";
	}else{
		$options["update"] = "curr";
	}
	update_option("Social_Crowd_Options", $options);
	
	//fix stats
	$scStats = get_option('Social_Crowd_Stats');
	
	if($scFacebook = get_option('Social_Crowd_Facebook_Count')){
		if($scFacebook > 0){
			$scStats["faceBook"]["likes"] = $scFacebook;
		}
		delete_option('Social_Crowd_Facebook_Count');
	}else{
		delete_option('Social_Crowd_Facebook_Count');
	}
	
	if($scTwitter = get_option('Social_Crowd_Twitter_Count')){
		if($scTwitter > 0){
			$scStats["twitter"]["followers"] = $scTwitter;
		}
		delete_option('Social_Crowd_Twitter_Count');
	}else{
		delete_option('Social_Crowd_Twitter_Count');
	}
	
	if($scTwitterFriends = get_option('Social_Crowd_Twitter_friendsCount')){
		if($scTwitterFriends > 0){
			$scStats["twitter"]["friends"] = $scTwitterFriends;
		}
		delete_option('Social_Crowd_Twitter_friendsCount');
	}else{
		delete_option('Social_Crowd_Twitter_friendsCount');
	}
	
	if($scTwitterStatus = get_option('Social_Crowd_Twitter_statusesCount')){
		if($scTwitterStatus > 0){
			$scStats["twitter"]["statuses"] = $scTwitterStatus;
		}
		delete_option('Social_Crowd_Twitter_statusesCount');
	}else{
		delete_option('Social_Crowd_Twitter_statusesCount');
	}
	
	if($scTwitterListed = get_option('Social_Crowd_Twitter_listedCount')){
		if($scTwitterListed > 0){
			$scStats["twitter"]["listed"] = $scTwitterListed;
		}
		delete_option('Social_Crowd_Twitter_listedCount');
	}else{
		delete_option('Social_Crowd_Twitter_listedCount');
	}
	
	if($scYoutube = get_option('Social_Crowd_Youtube_Count')){
		if($scYoutube > 0){
			$scStats["youTube"]["contacts"] = $scYoutube;
		}
		delete_option('Social_Crowd_Youtube_Count');
	}else{
		delete_option('Social_Crowd_Youtube_Count');
	}
	
	if($scYoutubeSub = get_option('Social_Crowd_Youtube_subscriberCount')){
		if($scYoutubeSub > 0){
			$scStats["youTube"]["subscribers"] = $scYoutubeSub;
		}
		delete_option('Social_Crowd_Youtube_subscriberCount');
	}else{
		delete_option('Social_Crowd_Youtube_subscriberCount');
	}
	
	if($scYoutubeViews = get_option('Social_Crowd_Youtube_viewCount')){
		if($scYoutubeViews > 0){
			$scStats["youTube"]["views"] = $scYoutubeViews;
		}
		delete_option('Social_Crowd_Youtube_viewCount');
	}else{
		delete_option('Social_Crowd_Youtube_viewCount');
	}
	
	if($scYoutubeUploadViews = get_option('Social_Crowd_Youtube_uploadViewCount')){
		if($scYoutubeUploadViews > 0){
			$scStats["youTube"]["uploadViews"] = $scYoutubeUploadViews;
		}
		delete_option('Social_Crowd_Youtube_uploadViewCount');
	}else{
		delete_option('Social_Crowd_Youtube_uploadViewCount');
	}
	
	if($scVimeo = get_option('Social_Crowd_Vimeo_Count')){
		if($scVimeo > 0){
			$scStats["vimeo"]["contacts"] = $scVimeo;
		}
		delete_option('Social_Crowd_Vimeo_Count');
	}else{
		delete_option('Social_Crowd_Vimeo_Count');
	}
	
	if($scVimeoUploaded = get_option('Social_Crowd_Vimeo_uploadedCount')){
		if($scVimeoUploaded > 0){
			$scStats["vimeo"]["uploaded"] = $scVimeoUploaded;
		}
		delete_option('Social_Crowd_Vimeo_uploadedCount');
	}else{
		delete_option('Social_Crowd_Vimeo_uploadedCount');
	}
	
	if($scVimeoAppearsIn = get_option('Social_Crowd_Vimeo_appearsInCount')){
		if($scVimeoAppearsIn > 0){
			$scStats["vimeo"]["appearsIn"] = $scVimeoAppearsIn;
		}
		delete_option('Social_Crowd_Vimeo_appearsInCount');
	}else{
		delete_option('Social_Crowd_Vimeo_appearsInCount');
	}
	
	if($scVimeoLiked = get_option('Social_Crowd_Vimeo_likedCount')){
		if($scVimeoLiked > 0){
			$scStats["vimeo"]["liked"] = $scVimeoLiked;
		}
		delete_option('Social_Crowd_Vimeo_likedCount');
	}else{
		delete_option('Social_Crowd_Vimeo_likedCount');
	}
	
	if($scGplusCircled = get_option('Social_Crowd_Gplus_circled')){
		if($scGplusCircled > 0){
			$scStats["googlePlus"]["circled"] = $scGplusCircled;
		}
		delete_option('Social_Crowd_Gplus_circled');
	}else{
		delete_option('Social_Crowd_Gplus_circled');
	}
	
	if($scGplusInCircles = get_option('Social_Crowd_Gplus_in_circles')){
		if($scGplusInCircles > 0){
			$scStats["googlePlus"]["inCircles"] = $scGplusInCircles;
		}
		delete_option('Social_Crowd_Gplus_in_circles');
	}else{
		delete_option('Social_Crowd_Gplus_in_circles');
	}
	
	if($scFeedburner = get_option('Social_Crowd_Feedburner_Count')){
		if($scFeedburner > 0){
			$scStats["feedBurner"]["subscribers"] = $scFeedburner;
		}
		delete_option('Social_Crowd_Feedburner_Count');
	}else{
		delete_option('Social_Crowd_Feedburner_Count');
	}
	
	if($scLinkedInConnections = get_option('Social_Crowd_Linked_In_Connections')){
		if($scLinkedInConnections > 0){
			$scStats["linkedIn"]["connections"] = $scLinkedInConnections;
		}
		delete_option('Social_Crowd_Linked_In_Connections');
	}else{
		delete_option('Social_Crowd_Linked_In_Connections');
	}
	
	update_option("Social_Crowd_Stats", $scStats);
}

/**
 * Build a Random String of Characters
 * 
 * @since 0.9.6
 * @author randall@macnative.com
 */
function SocialCrowd_RandString($length = 24, $full = true)
{   
	if($full){
		$chars = 'AaBbCcDdEeFfGHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz1234567890';
		for ($p = 0; $p < $length; $p++)
	    {
	        $result .= ($p%2) ? $chars[mt_rand(52, 60)] : $chars[mt_rand(0, 51)];
	    }
	}else{
		$chars = '24680';
		for ($p = 0; $p < $length; $p++)
	    {
	        $result .= $chars[mt_rand(0, 4)];
	    }
	}
    
    
    return $result;
}

?>