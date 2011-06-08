=== Social Crowd ===
Contributors: bdoga, tdawg2
Plugin URI: http://www.macnative.com/development/socialCrowd
Author URI: http://www.macnative.com
Donate link: http://www.macnative.com/development/donate 
Tags: social, network, count, friends, contacts, stats, statistics, followers, readers, facebook, twitter, feedburner, youtube, vimeo, number, raw
Requires at least: 3.0
Tested up to: 3.1.3
Stable tag: 0.2

Social Crowd retrieves the raw number of Friends/Followers from your favorite social networks and displays without formatting on any page of your blog

== Description ==

The Social Crowd Plugin grabs the latest counts of your Friends/Fans/Followers etc from your Favorite Social Networks and then displays them on your Blog. The counts that are reported come raw and without styling, so you can make them look and feel like your website. It is the perfect solution to encourage more users to join your network.

**Important:** This plugin **REQUIRES** the PHP Curl Module in order to function. Please make sure it is installed.

#### Supported Networks:

* Facebook
* Twitter
* Youtube
* Vimeo
* Feedburner


#### Plugin Usage

The Social Crowd function you will call is:

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


#### Available Stats

The available stats are listed in the following order: 
Type of statistic ('keyword'), use the keyowrd in the Social Crowd function to retrieve the desired content. 

* Feedburner subscriber count  (' **feedburner** ')  *Number of subscribers to your feed.*
* Facebook Friend/Like Count  (' **facebook** ')  *Number of friends or page likes.*
* Twitter Follower Count  (' **twitter** ')  *Number of followers.*
* Twitter Friend Count  (' **twitterFriends** ')  *Number of Friends you have.*
* Twitter Statuses Count  (' **twitterStatuses** ')  *Number of status updates you have sent.*
* Twitter Listed Count  (' **twitterListed** ')  *Number of lists you have been added to.*
* Youtube Friend Count  (' **youtube** ')  *Number of friends on Youtube.*
* Youtube Subscriber Count  (' **youtubeSubscribers** ')  *Number of Youtube subscribers.*
* Youtube Viewed Count  (' **youtubeViews** ')  *Number of videos you have viewed.*
* Youtube Uploaded Views Count  (' **youtubeUploadViews** ')  *Number of views your uploaded videos have had on Youtube.*
* Vimeo Friend Count  (' **vimeo** ')  *Number of friends you have on Vimeo.*
* Vimeo Uploads Count  (' **vimeoUploads** ')  *Number of videos you have uploaded to Vimeo.*
* Vimeo Appears In Count  (' **vimeoAppearsIn** ')  *Number of videos you appear in on Vimeo.*
* Vimeo Likes Count  (' **vimeoLikes** ')  *Number of videos that you have liked on Vimeo.*

I hope to expand this list to include your favorites ( just leave me some comments on the [plugin homepage][1] ). 

 [1]: http://www.macnative.com/development/social-crowd/

A big thanks to DeviantArt's jwloh for creating the Social.me Icon Set that is used in the plugin's Administrator, You can check out [his work][2].

 [2]: http://jwloh.deviantart.com/

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

= What stats can I grab? =

#### Available Stats

The available stats are listed in the following order: 
Type of statistic ('keyword'), use the keyowrd in the Social Crowd function to retrieve the desired content. 

* Feedburner subscriber count  (' **feedburner** ')  *Number of subscribers to your feed.*
* Facebook Friend/Like Count  (' **facebook** ')  *Number of friends or page likes.*
* Twitter Follower Count  (' **twitter** ')  *Number of followers.*
* Twitter Friend Count  (' **twitterFriends** ')  *Number of Friends you have.*
* Twitter Statuses Count  (' **twitterStatuses** ')  *Number of status updates you have sent.*
* Twitter Listed Count  (' **twitterListed** ')  *Number of lists you have been added to.*
* Youtube Friend Count  (' **youtube** ')  *Number of friends on Youtube.*
* Youtube Subscriber Count  (' **youtubeSubscribers** ')  *Number of Youtube subscribers.*
* Youtube Viewed Count  (' **youtubeViews** ')  *Number of videos you have viewed.*
* Youtube Uploaded Views Count  (' **youtubeUploadViews** ')  *Number of views your uploaded videos have had on Youtube.*
* Vimeo Friend Count  (' **vimeo** ')  *Number of friends you have on Vimeo.*
* Vimeo Uploads Count  (' **vimeoUploads** ')  *Number of videos you have uploaded to Vimeo.*
* Vimeo Appears In Count  (' **vimeoAppearsIn** ')  *Number of videos you appear in on Vimeo.*
* Vimeo Likes Count  (' **vimeoLikes** ')  *Number of videos that you have liked on Vimeo.*

I hope to expand this list to include your favorites ( just leave me some comments on the [plugin homepage][1] ).

= What if my favorite network isn't supported? =

[Drop me a line][3] and I will work on getting it added.
 [3]: http://www.macnative.com/contact-me/

== Screenshots == 

1. Admin Interface
2. Example Usage

== Changelog ==

= 0.2 [2011-06-00] =
* Added additional statistics gathering for Twitter, Youtube, and Vimeo.
* Added additional detail and information in the Readme file. 
* Small UI tweaks to the Admin.

= 0.1 [2011-05-17] = 
* Initial Release

== Upgrade Notice == 

= 0.2 =
Added additional statistics gathering for Twitter, Youtube, and Vimeo

