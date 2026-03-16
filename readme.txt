=== Analytics Tracker ===
Plugin Name: Analytics Tracker
Plugin URI: https://stylishwp.com/product/wordpress-plugins/google-analytics-tracker/
Description: Analytics Tracker makes it super easy to add Google Analytics (gtag.js) tracking code on your site
Contributors: valeriutihai
Author: Valeriu Tihai
Author URI: http://valeriu.tihai.ca
Text Domain: analytics-tracker
Tags: google analytics, ga4, measurement id, analytics, google tag
Donate link: https://paypal.me/valeriu/25
Requires at least: 4.6
Tested up to: 6.9
Stable tag: 3.0.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Analytics Tracker makes it super easy to add Google Analytics tracking code on your site

== Description ==

> If you like the plugin, feel free to [rate it](https://wordpress.org/support/view/plugin-reviews/analytics-tracker?rate=5#postform). Thank You!

Analytics Tracker utilizes the latest and greatest features of Google global site tag (gtag.js) and makes it super easy to add tracking code on your blog.

Note: Google ended Universal Analytics (UA-) processing on July 1, 2023. We keep UA tracking IDs for legacy compatibility, but for active reporting we strongly recommend using Google Analytics 4 with a G-XXXXXXXX Measurement ID.

This is one of the simplest to use WordPress plugins to insert Google Analytics code on your websites built in WordPress.

Google Analytics is now the most widely used web analytics service on the Internet.

= Features: =
* <strong>GA4 Measurement ID setup</strong> - Configure your Google tag with a valid GA4 Measurement ID (for example: G-XXXXXXXXXX).
* <strong>User ID (optional)</strong> - Send an anonymous hashed user identifier for logged-in users.
* <strong>Enriched page_view event</strong> - Adds contextual parameters for home, singular, taxonomy, author, search, and 404 pages.
* <strong>Comment Event (optional)</strong> - Enable or disable the event sent when a comment becomes visible on your site.
* <strong>Minimal compatibility mode for non-GA4 IDs</strong> - Loads basic gtag config without custom events.


> <strong>Analytics Tracker on GitHub</strong><br>
> You can submit feature requests or bugs on [Analytics Tracker](https://github.com/valeriu/analytics-tracker) repository.


== Installation ==
In most cases you can install automatically from WordPress.org.

However, if you install this manually, follow these steps:
1. Upload the Analytics Tracker plugin to your site;
2. Activate the plugin through the \'Plugins\' menu in WordPress;
3. Then enter your GA4 Measurement ID (G-XXXXXXXXXX);
You’re done!

If you don't have a GA4 Measurement ID yet, create a property in [Google Analytics](http://www.google.com/analytics) and get your Measurement ID (for example: G-XXXXXXXXXX).

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

In addition, the IP address, user agent string, and initial page inspection performed by the tracking library can be used to determine things like the following:

* The geographic location of the user.
* What browser and operating system are being used.
* Screen size and whether Flash or Java is installed.
* The referring site.

== Localization ==

You can translate Analytics Tracker on [translate.wordpress.org](https://translate.wordpress.org/projects/wp-plugins/analytics-tracker).

== Screenshots ==
1. Google Analytics Settings

== Changelog ==
= 3.0.1 =
* Added a WordPress Playground blueprint with a preconfigured GA4 Measurement ID for demo use.
* Added a Playground admin notice to clarify that tracking runs in the background and should be verified with browser developer tools.
* Removed unused legacy Scroll Depth vendor files from the plugin package.
* Updated README wording to use tracking-library terminology instead of referencing analytics.js directly.

= 3.0.0 =
* Updated settings and documentation for GA4 Measurement ID.
* Added settings sanitization callback for plugin options.
* Added an optional setting to enable or disable the event sent when a comment becomes visible on your site.
* Improved uninstall routine for multisite installations.
* Refined admin asset versioning and loading.

= 2.0.1 =
* Added compatibility with PHP 7.2

= 2.0.0 =
* Use Global Site Tag (gtag.js)
* Deleted Force SSL

= 1.1.1 =
* Fixed XSS vulnerability on search event, thanks to Arjan Snaterse - https://www.uprise.nl

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
