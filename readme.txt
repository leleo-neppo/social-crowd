=== Social Crowd ===
Contributors: bdoga
Plugin URI: http://www.macnative.com/development/socialCrowd
Author URI: http://www.macnative.com
Donate link: http://www.macnative.com/development/donate 
Tags: social, network, count, friends, contacts, stats, statistics, followers, readers, facebook, twitter, feedburner, youtube, vimeo, number, raw
Requires at least: 3.0
Tested up to: 3.1.2
Stable tag: 1.0

This plugin retrieves the number of Friends/Followers/Fans from your favorite social networks and displays those on any page of your wordpress blog.

== Description ==

The Social Crowd Plugin grabs the latest counts of your Friends/Fans/Followers etc from your Favorite Social Networks and then displays them on your Blog. The counts that are reported come raw and without styling, so you can make them look and feel like your website. It is the perfect solution to encourage more users to join your network.

**Supported Networks:**

* Facebook
* Twitter
* Youtube
* Vimeo
* Feedburner

I hope to expand this list to include your favorites ( just leave me some comments on the plugin homepage http://www.macnative.com/development/socialCrowd ).

A big thanks to DeviantArt's jwloh for creating the Social.me Icon Set that is used in the plugin's Administrator, You can check out his work at http://jwloh.deviantart.com/

== Installation ==

1. Unzip and upload the Social Crowd plugin folder to wp-content/plugins/
1. Activate the plugin from your WordPress admin panel.
1. Installation finished.

== Frequently Asked Questions ==

= How do I call the function =

The function that you will call is: 

	SocialCrowd_Stats();

You have two options: 

* Calling the function with a specific network:
	1. Place the function wherever you want the data to be displayed. 
	1. Call the function with a specific network name (all lowercase):
		* SocialCrowd_Stats('facebook')
		* SocialCrowd_Stats('twitter')
		* SocialCrowd_Stats('youtube')
		* etc...
	1. Function will echo out the requested Network Stats.

* Calling the function without a specific network:
	1. Place the function anywhere you want.
	1. Call the function with no options.
		$stats = SocialCrowd_Stats()
	1. The function will return an array with the stats for all your networks.
	1. the array is an associative array that you can you can access like so:
		* $stats['facebook']
		* $stats['twitter']
		* $stats['youtube']
		* etc...

= What if my favorite network isn't supported? =

Drop me a line and I will work on getting it added.

== Screenshots == 

1. Admin Interface
2. Example Usage

== Changelog ==

= 1.0 [2011-05-17] = 
* Initial Release



