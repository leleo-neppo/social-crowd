<?php


/**
 * Gets Social Stats From Requested Social Networks
 *
 * @since 0.1
 * @author randall@macnative.com
 */
function SocialCrowd_GetCounts()
{
	$sc_options = get_option('Social_Crowd_Options');
	
	if( !get_option('Social_Crowd_Key') || get_option('Social_Crowd_Key') == 0 ) {
		$str_req = SocialCrowd_RandString(3, false);	
		$str_req .= SocialCrowd_RandString();
		$key = SocialCrowd_Get("http://api.macnative.com/sc/?reqStr=".$str_req);
		if(strlen($key) == 32){
			update_option('Social_Crowd_Key', $key);
		}
	}
	
    if ($sc_options["interval"] < mktime() - get_option('Social_Crowd_Timer') && get_option('Social_Crowd_Key') != 0 ) 
	{
		$scKey = get_option('Social_Crowd_Key');
		$SCreqURI = "http://api.macnative.com/sc/?reqKey=".$scKey;
		
		//Get Facebook Stats
		if($sc_options["get_facebook"]){
			$SCreqURI .= "&fb=".$sc_options["facebook_token"];
		}
		
		//Get Twitter Stats
		if($sc_options["get_twitter"]){
			$SCreqURI .= "&tw=".$sc_options["twitter_token"];
		}
		
		//Get Youtube Stats
		if($sc_options["get_youtube"]){
			$SCreqURI .= "&yt=".$sc_options["youtube_token"];
		}
		
		//Get Vimeo Stats
		if($sc_options["get_vimeo"]){
			$SCreqURI .= "&vm=".$sc_options["vimeo_token"];
		}
		
		$results = json_decode(SocialCrowd_Get($SCreqURI));
		
		if($results->response == "Success"){
			if( !get_option('Social_Crowd_Stats') ) {
				unset($results->response);
				add_option('Social_Crowd_Stats', $results);
			}else{
				$currStats = get_option('Social_Crowd_Stats');
				foreach($results AS $key => $val){
					if($key != "response"){
						foreach($val AS $key2 => $val2){
							if($sc_options["update"] == "max"){
								//only update if larger value
								if($val2 > $currStats[$key][$key2]){
									$currStats[$key][$key2] = $results->$key->$key2;
								}
							}else{
								//always update unless zero is returned
								if($val2 != 0){
									$currStats[$key][$key2] = $results->$key->$key2;
								}
							}//end update method
						}//end internal foreach loop
					}
				}//end external foreach loop	
			}
			update_option('Social_Crowd_Stats', $currStats);
			update_option('Social_Crowd_Timer', mktime());
		}
		//Mailchimp api call = http://us1.api.mailchimp.com/1.3/?method=lists&apikey=1fa32d83fc746903f28067258f2e70d6-us1		
	}   
}

?>