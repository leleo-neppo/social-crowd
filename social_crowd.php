<?php
/**
 * @package Social_Crowd
 * @author Randall Hinton
 * @version 1.0
 */
/*
Plugin Name: Social Crowd
Plugin URI: http://www.macnative.com/socialCrowd
Description: This plugin retrieves the raw number of Friends/Followers/Fans etc from your favorite social networks and allows you to show that raw number on any page of your wordpress blog using a simple php function **Requires PHP Curl Module**
Author: Randall Hinton
Version: 0.1
Author URI: http://www.macnative.com/
*/

register_activation_hook( __FILE__, 'SocialCrowd_Activate' );

/**
 * Check for the former plugin version and deactivates it, otherwise set default settings
 *
 * @since 1.0
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
 * Adds the plugin's default settings
 *
 * @since 1.0
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
	if( !get_option('Social_Crowd_Youtube_Count') ) {
		add_option('Social_Crowd_Youtube_Count', '0');
	}
	if( !get_option('Social_Crowd_Vimeo_Count') ) {
		add_option('Social_Crowd_Vimeo_Count', '0');
	}

	if( !get_option('Social_Crowd_Options') ) {
		add_option('Social_Crowd_Options', 'interval:7200~get_feedburner:0~feedburner_token:0~get_facebook:0~facebook_token:0~get_twitter:0~twitter_token:0~get_youtube:0~youtube_token:0~get_vimeo:0~vimeo_token:0');
	}
}

/**
 * Gets Social Stats From Requested Social Networks
 *
 * @since 1.0
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
			}else{
				if ($xml->followers_count != '' && $xml->followers_count > 0) 
		        { 
	                update_option('Social_Crowd_Twitter_Count',  (string) $xml->followers_count); 
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
		}
		
		//Get Vimeo Contacts
		if($sc_options["get_vimeo"]){
			$xml = SocialCrowd_Load_XML("http://vimeo.com/api/v2/".$sc_options['vimeo_token']."/info.xml");
			if($sc_options["update"]){
				if ($xml->user->total_contacts != '' && $xml->user->total_contacts > get_option('Social_Crowd_Vimeo_Count')) 
		        { 
	                update_option('Social_Crowd_Vimeo_Count',  (string) $xml->user->total_contacts); 
	            }
			}else{
				if ($xml->user->total_contacts != '' && $xml->user->total_contacts > 0) 
		        { 
	                update_option('Social_Crowd_Vimeo_Count',  (string) $xml->user->total_contacts); 
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
 * @since 1.0
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
 * @since 1.0
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
 * @since 1.0
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
		$stats["youtube"] = get_option('Social_Crowd_Youtube_Count');
		$stats["vimeo"] = get_option('Social_Crowd_Vimeo_Count');
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
			case youtube:
				echo get_option('Social_Crowd_Youtube_Count');
			break;
			case vimeo:
				echo get_option('Social_Crowd_Vimeo_Count');
			break;
		}
	}
}

/**
 * Gets options string from the DB and converts it into an array
 *
 * @since 1.0
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
 * @since 1.0
 * @author randall@macnative.com
 */
function SocialCrowd_Make_Select($x = "", $fields, $class="", $id="select", $name="select") {
	echo '<select name="'.$name.'" id="'.$id.'" class="'.$class.'">';
		foreach ($fields as $shown => $value) {
			if($x == $value){
				echo '<option value="'.$value.'" selected />'.$shown.'</option>';
			}else{
				echo '<option value="'.$value.'" />'.$shown.'</option>';
			}
		}
	echo '</select>';
}

/**
 * Adds the plugin's options page
 * 
 * @since 1.0
 * @author randall@macnative.com
 */
function SocialCrowd_Add_Option_Menu() {
		add_submenu_page('options-general.php', 'Social Crowd Options', 'Social Crowd Options', 8, __FILE__, 'SocialCrowd_Options_Page');
}

/**
 * Adds content to the plugin's options page
 *
 * @since 1.0
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
			$options_string .= "~feedburner_token:".$_POST["sc_feedburner"];
		}else{
			$options_string .= "~feedburner_token:0";
		}
		
		if(isset($_POST["sc_facebook_enabled"])){
			$options_string .= "~get_facebook:1";
		}else{
			$options_string .= "~get_facebook:0";
		}
		
		if(isset($_POST["sc_facebook"]) && $_POST["sc_facebook"] != ""){
			$options_string .= "~facebook_token:".$_POST["sc_facebook"];
		}else{
			$options_string .= "~facebook_token:0";
		}
		
		if(isset($_POST["sc_twitter_enabled"])){
			$options_string .= "~get_twitter:1";
		}else{
			$options_string .= "~get_twitter:0";
		}
		
		if(isset($_POST["sc_twitter"]) && $_POST["sc_twitter"] != ""){
			$options_string .= "~twitter_token:".$_POST["sc_twitter"];
		}else{
			$options_string .= "~twitter_token:0";
		}
		
		if(isset($_POST["sc_youtube_enabled"])){
			$options_string .= "~get_youtube:1";
		}else{
			$options_string .= "~get_youtube:0";
		}
		
		if(isset($_POST["sc_youtube"]) && $_POST["sc_youtube"] != ""){
			$options_string .= "~youtube_token:".$_POST["sc_youtube"];
		}else{
			$options_string .= "~youtube_token:0";
		}
		
		if(isset($_POST["sc_vimeo_enabled"])){
			$options_string .= "~get_vimeo:1";
		}else{
			$options_string .= "~get_vimeo:0";
		}
		
		if(isset($_POST["sc_vimeo"]) && $_POST["sc_vimeo"] != ""){
			$options_string .= "~vimeo_token:".$_POST["sc_vimeo"];
		}else{
			$options_string .= "~vimeo_token:0";
		}
		
		
		if(update_option("Social_Crowd_Options", $options_string)){
			$successmessage = "Social Crowd Options Updated Successfully";
		}else{
			$successmessage = "Social Crowd Options Did Not Update";
		}

		echo '<div id="message0" class="updated fade">
			<p>
				<strong>
					' . $successmessage . '
				</strong>
			</p>
		</div><br />';
	
		echo '<script type="text/javascript">
		function OptionsUpdated() {
			window.location.href = "' . $_SERVER['REQUEST_URI'] . '";
		}

		window.setTimeout("OptionsUpdated()", 2000);
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

</style>

<script type="text/javascript">
function enable_options() {
	var elements = new Array();
	elements[0] = "feedburner";
	elements[1] = "facebook";
	elements[2] = "twitter";
	elements[3] = "youtube";
	elements[4] = "vimeo";
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
		<br class="clear" />
		<form name="sc_form" id="sc_form" action="" method="post">
		<input type="hidden" name="action" value="edit" />
			<div id="poststuff" class="ui-sortable">
			<div id="sc_ids_box" class="postbox if-js-open">
			<h3>Social Crowd Admin Options</h3>
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
							<input type="input" maxlength="64" size="25" name="sc_feedburner" id="sc_feedburner" value="<?php echo ( $sc_options['feedburner_token']!='0' ) ? $sc_options['feedburner_token'] : '' ?>">
							&nbsp;&nbsp;Your Feedburner ID <br /><span class="sc_example">ie: http://feeds.feedburner.com/</span><span class="sc_example sc_example2">feedname</span>
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
							<input type="input" maxlength="64" size="25" name="sc_facebook" id="sc_facebook" value="<?php echo ( $sc_options['facebook_token']!='0' ) ? $sc_options['facebook_token'] : '' ?>">
							&nbsp;&nbsp;Your Facebook ID <br /><span class="sc_example">ie: http://www.facebook.com/<span class="sc_example sc_example2">123456789012</span> or http://www.facebook.com/<span class="sc_example sc_example2">VanityID</span></span>
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
							<input type="input" maxlength="64" size="25" name="sc_twitter" id="sc_twitter" value="<?php echo ( $sc_options['twitter_token']!='0' ) ? $sc_options['twitter_token'] : '' ?>">
							&nbsp;&nbsp;Your Twitter ID <br /><span class="sc_example">ie: http://www.twitter.com/</span><span class="sc_example sc_example2">username</span> 
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
							<input type="input" maxlength="64" size="25" name="sc_youtube" id="sc_youtube" value="<?php echo ( $sc_options['youtube_token']!='0' ) ? $sc_options['youtube_token'] : '' ?>">
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
							<input type="input" maxlength="64" size="25" name="sc_vimeo" id="sc_vimeo" value="<?php echo ( $sc_options['vimeo_token']!='0' ) ? $sc_options['vimeo_token'] : '' ?>">
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
?>