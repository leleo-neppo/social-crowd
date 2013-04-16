<?php


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

		
		if(update_option("Social_Crowd_Options", $options_string)){
			$update_success = "Social Crowd Options Updated Successfully";
			update_option('Social_Crowd_Timer', '0');
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

#adminbox .radio2 label, #adminbox .radio2 label + a, label, #your-profile label + a { 
	border-bottom: none;
}

</style>

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
			$curl_msg = "Congratulations the PHP Curl Module is loaded, Social Crowd should Function Properly";
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