=== Analytics Tracker ===
Plugin Name: Analytics Tracker
Plugin URI: https://stylishwp.com/product/wordpress-plugins/google-analytics-tracker/
Description: Analytics Tracker makes it super easy to add Google Analytics tracking code on your site
Contributors: valeriutihai
Author: Valeriu Tihai
Author URI: http://valeriu.tihai.ca
Text Domain: analytics-tracker
Tags: Google Analytics, Analytics Tracker, Tracking Code, UA Code, Universal Analytics, Visits statistics, Web Stats, WordPress Google Analytics
Donate link: https://paypal.me/valeriu/5
Requires at least: 4.6
Tested up to: 4.7.1
Stable tag: 1.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Analytics Tracker makes it super easy to add Google Analytics tracking code on your site

== Description ==

> If you like the plugin, feel free to [rate it](https://wordpress.org/support/view/plugin-reviews/analytics-tracker?rate=5#postform). Thank You!

Analytics Tracker utilizes the latest and greatest features of Universal Google Analytics and makes it super easy to add tracking code on your blog.

[youtube https://www.youtube.com/watch?v=emtS4LMBFI0]

This is one of the simplest to use WordPress plugins to insert Google Analytics code on your websites built in WordPress.

Google Analytics is now the most widely used web analytics service on the Internet.

= Features: =
* <strong>Support AMP</strong> - Insert code to Accelerated Mobile Pages, require [AMP](https://wordpress.org/plugins/amp/) plugin created by Automattic.
* <strong>Force SSL</strong> - Setting forceSSL to true will force http pages to also send all beacons using https.
* <strong>User ID</strong> - This is intended to be a known identifier for a user provided by the site owner/tracking library user.
* <strong>Anonymize IP</strong> - The IP address of the sender will be anonymized
* <strong>Display Features</strong> - The plugin works by sending an additional request to stats.g.doubleclick.net that is used to provide advertising features like remarketing and demographics and interest reporting in Google Analytics.
* <strong>Enhanced Link Attribution</strong> - Enhanced Link Attribution improves the accuracy of your In-Page Analytics report by automatically differentiating between multiple links to the same URL on a single page by using link element IDs.
* <strong>Custom Dimensions</strong> - You can use custom dimensions to track: Tags, Category, Archive, Author, Post Format, Post Type
* <strong>Event</strong> -  for Download, Email, Phone number, Outbound links, Error 404, Search, Add a comment, Scroll Depth


> <strong>Analytics Tracker on GitHub</strong><br>
> You can submit feature requests or bugs on [Analytics Tracker](https://github.com/valeriu/analytics-tracker) repository.


== Installation ==
In most cases you can install automatically from WordPress.org.

However, if you install this manually, follow these steps:
1. Create the directory \'auto-update\' in your \'/wp-content/plugins/\' directory
2. Upload all the plugin\'s file to the newly created directory
3. Activate the plugin through the \'Plugins\' menu in WordPress

If you don't have an Google Analytics ID, you need to go to [Google Analytics](http://www.google.com/analytics), create an account and [get the code](https://support.google.com/analytics/answer/1032385?rd=1), similar to UA-XXXXXXX-YY

== Frequently Asked Questions ==
= How can I suggest a new feature, contribute or report a bug? =
You can submit pull requests, feature requests and bug reports on [our GitHub repository](https://github.com/valeriu/analytics-tracker).

= What are the requirements to use this plugin? =
You need an active Google Analytics account and obviously a WordPress blog.

= Where can I see the analytics report? =
You can see your detailed analytics report in your [Google Analytics](http://www.google.com/analytics) account.

= What data does the tracking snippet capture? =

When you add either of these tracking snippets to your website, you send a pageview for each page your users visit. Google Analytics processes this data and can infer a great deal of information including:

* The total time a user spends on your site.
* The time a user spends on each page and in what order those pages were visited.
* What internal links were clicked (based on the URL of the next pageview).

In addition, the IP address, user agent string, and initial page inspection analytics.js does when creating a new tracker is used to determine things like the following:

* The geographic location of the user.
* What browser and operating system are being used.
* Screen size and whether Flash or Java is installed.
* The referring site.

== Localization ==

You can translate Analytics Tracker on [translate.wordpress.org](https://translate.wordpress.org/projects/wp-plugins/analytics-tracker).

== Screenshots ==
1. Google Analytics Settings

== Changelog ==
= 1.1.0 =
* Added tracking code for Accelerated Mobile Pages (Require AMP plugin https://wordpress.org/plugins/amp/)

= 1.0.5 =
* Fixed Event for Download, Email, Phone number and Outbound links

= 1.0.4 =
* Added event for Add a Comment
* Added event for Scroll Depth
* Fixed Options for Custom dimension

= 1.0.3 =
* Added Enhanced Link Attribution
* Added Events for Download, Email, Phone number, Outbound links, Error 404, Search

= 1.0.2 =
* Added Force SSL
* Added User ID
* Added Anonymize IP
* Added Display Features
* Added Custom Dimensions

= 1.0.1 =
* Added my WordPress Themes
* Fixed Regex for UA Code

= 1.0.0 =
* First Release
