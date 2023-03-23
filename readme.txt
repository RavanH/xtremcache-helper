=== XtremCache Helper ===
Contributors: RavanH
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=ravanhagen%40gmail%2ecom&item_name=XML%20Sitemap%20Feed
Tags: sitemap, xml sitemap, news sitemap, sitemap.xml, robots.txt, Google, Google News, Yahoo, Bing, Yandex, Baidu, seo, feed, Polylang, WPML, image sitemap
Requires at least: 4.4
Requires PHP: 5.6
Tested up to: 6.1
Stable tag: 5.3.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Helper for o2switch's XtremCache. Provides Varnish cache purge options in the admin menu as well as automatic purging and cache exclusions options.

== Description ==


= Privacy / GDPR =

This plugin does not collect any user or visitor data nor set browser cookies. Using this plugin should not impact your site privacy policy in any way.

**Data that is published or transmitted **

The plugin does not publish or transmit any data.

= Contribute =

If you're happy with this plugin as it is, please consider writing a quick [rating](https://wordpress.org/support/plugin/xtremcache-helper/reviews/#new-post) or helping other users out on the [support forum](https://wordpress.org/support/plugin/xtremcache-helper).

If you wish to help build this plugin, you're very welcome to [translate it into your language](https://translate.wordpress.org/projects/wp-plugins/xtremcache-helper/) or contribute code on [Github](https://github.com/RavanH/xtremcache-helper/).


== Installation ==

= Wordpress =

**I.** If you have been using another XML Sitemap plugin before, check your site root and remove any created sitemap.xml, sitemap-news.xml and (if you're not managing this one manually) robots.txt files that remained there.

**II.** Install plugin by:

Quick installation via **[Covered Web Services](http://coveredwebservices.com/wp-plugin-install/?plugin=xtremcache-helper)** !

 &hellip; OR &hellip;

Search for "xtremcache helper" and install with that slick **Plugins > Add New** admin page.

 &hellip; OR &hellip;

Follow these steps:

1. Download archive.

2. Upload the zip file via the Plugins > Add New > Upload page &hellip; OR &hellip; unpack and upload with your favourite FTP client to the /plugins/ folder.

**III.** Activate the plugin on the Plugins page.

Done!

= WordPress 3+ in Multi Site mode =

Same as above but do a **Network Activate** to make a the cache helper available for each site on your network.

= Uninstallation =

Upon uninstalling the plugin from the Admin > Plugins page, plugin options and meta data will be cleared from the database. See notes in the uninstall.php file.

On multisite, the uninstall.php *can* loop through all sites in the network to perform the uninstalltion process for each site. However, this does not scale for large networks so it *only* does a per-site uninstallation when `define('XMLSF_MULTISITE_UNINSTALL', true);` is explicitly set in wp-config.php.

== Frequently Asked Questions ==

= Where are the options? =




== Screenshots ==



== Upgrade Notice ==

= 0.x =



== Changelog ==

= 0.6 =
* more regex purge options: theme, plugins, home/blog pages
* improved autmatic purging
* purging on block theme template modification

= 0.5 =
* admin notices

= 0.4 =
* autoated purging
* async purging

= 0.3 =
* plugin de/activation routines
* dedicated Cache class

= 0.2 =
* autoload
* more regex purge options: media, js & css

= 0.1 =
* admin bar purge button for all and this page
