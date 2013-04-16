<?php


/**
 * Output Social Crowd Stats in a Horizontal Format
 *
 * @since 0.8
 * @author randall@macnative.com
 */
function SocialCrowd_GetHorizontal($icons='aquaticus', $networks='all', $desctext="true", $includecss="true", $newwindow="true", $facebookIcon='none', $facebookText='none', $twitterIcon='none', $twitterText='none', $googleIcon='none', $googleText='none', $linkedinIcon='none', $linkedinText='none', $youtubeIcon='none', $youtubeText='none', $vimeoIcon='none', $vimeoText='none', $feedburnerIcon='none', $feedburnerText='none')
{
	
	$siteurl = get_option('siteurl');
	$img_url = $siteurl . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/images/';
	
	$sc_options = SocialCrowd_GetOptions();	
	
	$display = explode(',',$networks);
	
	if($includecss == "true"){
	?>
	<style type="text/css">
		ul.scHoriz {
			margin: 10px;
		}
		.scHoriz li.crowdStat {
			list-style: none !important;
			background: none !important;
			padding: 10px !important;
			display: inline;
			text-align: center;
			float: left;
			border-radius: 5px;
		}
		.scHoriz li.crowdStat:hover {
			background: #EEE !important;
		}
		li.crowdStat img {
			width: 48px;
			height: 48px;
		}
	</style>
	<?php 
	}
	
	$stats = SocialCrowd_Stats(); 
	
	?>
	<ul class="scHoriz">
		<?php if(($networks=='all' && $sc_options["get_facebook"]=='1') || (in_array('facebook',$display) && $sc_options["get_facebook"]=='1')){ 
			if($facebookIcon=='none'){
				$icon = $img_url."large/".$icons."/facebook.png";
			}else{
				$icon = $facebookIcon;
			}
			?>
		<li class="crowdStat">
			<a href="http://www.facebook.com/<?php echo $sc_options['facebook_token'] ?>" <?php echo ($newwindow=='true') ? 'target="_blank"' : '' ?> ><img src="<?php echo $icon ?>" /></a><br />
			<?php if($desctext == 'true') { ?><span><?php echo ($facebookText!='none') ? $facebookText : 'Likes' ?></span><br /> <?php } ?>
			<?php echo $stats["facebook"] ?>
		</li>
		<?php } ?>
		
		<?php if(($networks=='all' && $sc_options["get_gplus"]=='1') || (in_array('google',$display) && $sc_options["get_gplus"]=='1')){ 
			if($googleIcon=='none'){
				$icon = $img_url."large/".$icons."/google.png";
			}else{
				$icon = $googleIcon;
			}
			?>
		<li class="crowdStat">
			<a href="http://plus.google.com/<?php echo $sc_options['gplus_token'] ?>" <?php echo ($newwindow=='true') ? 'target="_blank"' : '' ?> ><img src="<?php echo $icon ?>" /></a><br />
			<?php if($desctext == 'true') { ?><span><?php echo ($googleText!='none') ? $googleText : 'Circles' ?></span><br /><?php } ?>
			<?php echo $stats["gplusInCircles"] ?>
		</li>
		<?php } ?>
		
		<?php if(($networks=='all' && $sc_options["get_twitter"]=='1') || (in_array('twitter',$display) && $sc_options["get_twitter"]=='1')){ 
			if($twitterIcon=='none'){
				$icon = $img_url."large/".$icons."/twitter.png";
			}else{
				$icon = $twitterIcon;
			}
			?>
		<li class="crowdStat">
			<a href="http://www.twitter.com/<?php echo $sc_options['twitter_token'] ?>" <?php echo ($newwindow=='true') ? 'target="_blank"' : '' ?> ><img src="<?php echo $icon ?>" /></a><br />
			<?php if($desctext == 'true') { ?><span><?php echo ($twitterText!='none') ? $twitterText : 'Followers' ?></span><br /><?php } ?>
			<?php echo $stats["twitter"] ?>
		</li>
		<?php } ?>
		
		<?php if(($networks=='all' && $sc_options["get_linkedin"]=='1') || (in_array('linkedin',$display) && $sc_options["get_linkedin"]=='1')){ 
			if($linkedinIcon=='none'){
				$icon = $img_url."large/".$icons."/linkedin.png";
			}else{
				$icon = $linkedinIcon;
			}
			?>
		<li class="crowdStat">
			<a href="<?php echo (stristr($sc_options["linkedin_token"],"//")) ? 'http:' : 'http://www.linkedin.com/in/'; echo $sc_options['linkedin_token'] ?>" <?php echo ($newwindow=='true') ? 'target="_blank"' : '' ?> ><img src="<?php echo $icon ?>" /></a><br />
			<?php if($desctext == 'true') { ?><span><?php echo ($linkedinText!='none') ? $linkedinText : 'Links' ?></span><br /><?php } ?>
			<?php echo $stats["linkedIn"] ?>
		</li>
		<?php } ?>
		
		<?php if(($networks=='all' && $sc_options["get_youtube"]=='1') || (in_array('youtube',$display) && $sc_options["get_youtube"]=='1')){ 
			if($youtubeIcon=='none'){
				$icon = $img_url."large/".$icons."/youtube.png";
			}else{
				$icon = $youtubeIcon;
			}
			?>
		<li class="crowdStat">
			<a href="http://www.youtube.com/<?php echo $sc_options['youtube_token'] ?>" <?php echo ($newwindow=='true') ? 'target="_blank"' : '' ?> ><img src="<?php echo $icon ?>" /></a><br />
			<?php if($desctext == 'true') { ?><span><?php echo ($youtubeText!='none') ? $youtubeText : 'Scribers' ?></span><br /><?php } ?>
			<?php echo $stats["youtubeSubscribers"] ?>
		</li>
		<?php } ?>
		
		<?php if(($networks=='all' && $sc_options["get_vimeo"]=='1') || (in_array('vimeo',$display) && $sc_options["get_vimeo"]=='1')){ 
			if($vimeoIcon=='none'){
				$icon = $img_url."large/".$icons."/vimeo.png";
			}else{
				$icon = $vimeoIcon;
			}
			?>
		<li class="crowdStat">
			<a href="http://www.vimeo.com/<?php echo $sc_options['vimeo_token'] ?>" <?php echo ($newwindow=='true') ? 'target="_blank"' : '' ?> ><img src="<?php echo $icon ?>" /></a><br />
			<?php if($desctext == 'true') { ?><span><?php echo ($vimeoText!='none') ? $vimeoText : 'Contacts' ?></span><br /><?php } ?>
			<?php echo $stats["vimeo"] ?>
		</li>
		<?php } ?>
		
		<?php if(($networks=='all' && $sc_options["get_feedburner"]=='1') || (in_array('feedburner',$display) && $sc_options["get_feedburner"]=='1')){ 
			if($feedburnerIcon=='none'){
				$icon = $img_url."large/".$icons."/feed.png";
			}else{
				$icon = $feedburnerIcon;
			}
			?>
		<li class="crowdStat">
			<a href="http://feedburner.google.com/fb/a/mailverify?uri=<?php echo $sc_options['feedburner_token'] ?>&loc=en_US" <?php echo ($newwindow=='true') ? 'target="_blank"' : '' ?> ><img src="<?php echo $icon ?>" /></a><br />
			<?php if($desctext == 'true') { ?><span><?php echo ($feedburnerText!='none') ? $feedburnerText : 'Readers' ?></span><br /><?php } ?>
			<?php echo $stats["feedburner"] ?>
		</li>
		<?php } ?>
		
		
	</ul>
	<?php 
}

/**
 * Shortcode for displaying the Social Crowd Stats Horizontal Format.
 *
 * @since 0.8
 * @author randall@macnative.com
 */

add_shortcode('SC_Horiz_Stats', 'SocialCrowd_GetHorizontal_SC');

//define shortcode to display Social Crowd Stats Horizontal Output
//
//shortcode options:
// icons -> Icon Set to Use (same options as the Widgets) ie: type=aquaticus (refer to documentation for full list)
// networks -> Comma Delimited List of Networks to display or (all) ie: networks=all networks=facebook,twitter,google
// desctext -> Show Description Text ie: desctext=true
// includecss -> Include Default CSS Style ie: includecss=true
// newwindow -> Open Links in New Window ie: newwindow=true
// facebookicon -> URL for Facebook Icon (if none given default will be used)
// facebooktext -> Text Under the Facebook Icon (if none given default will be used)
// twittericon -> URL for Twitter Icon (if none given default will be used)
// twittertext -> Text Under the Twitter Icon (if none given default will be used)
// googleicon -> URL for Google+ Icon (if none given default will be used)
// googletext -> Text Under the Facebook Icon (if none given default will be used)
// linkedinicon -> URL for LinkedIn Icon (if none given default will be used)
// linkedintext -> Text Under the Facebook Icon (if none given default will be used)
// youtubeicon -> URL for Youtube Icon (if none given default will be used)
// youtubetext -> Text Under the Facebook Icon (if none given default will be used)
// vimeoicon -> URL for Vimeo Icon (if none given default will be used)
// vimeotext -> Text Under the Facebook Icon (if none given default will be used)
// feedburnericon -> URL for Feedburner Icon (if none given default will be used)
// feedburnertext -> Text Under the Facebook Icon (if none given default will be used)
//
function SocialCrowd_GetHorizontal_SC( $atts ) {
	extract( shortcode_atts( array(
			'icons' => 'aquaticus',
			'networks' => 'all',
			'desctext' => 'true',
			'includecss' => 'true',
			'newwindow' => 'true',
			'facebookicon' => 'none',
			'facebooktext' => 'none',
			'twittericon' => 'none',
			'twittertext' => 'none',
			'googleicon' => 'none',
			'googletext' => 'none',
			'linkedinicon' => 'none',
			'linkedintext' => 'none',
			'youtubeicon' => 'none',
			'youtubetext' => 'none',
			'vimeoicon' => 'none',
			'vimeotext' => 'none',
			'feedburnericon' => 'none',
			'feedburnertext' => 'none'
		), $atts ) );

ob_start();

SocialCrowd_GetHorizontal($icons, $networks, $desctext, $includecss, $newwindow, $facebookicon, $facebooktext, $twittericon, $twittertext, $googleicon, $googletext, $linkedinicon, $linkedintext, $youtubeicon, $youtubetext, $vimeoicon, $vimeotext, $feedburnericon, $feedburnertext);

$output = ob_get_contents();
ob_end_clean();

return $output;

}



?>