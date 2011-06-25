=== WP Smush.it ===
Plugin Name: WP Smush.it
Version: 1.3.4
Author: Dialect
Author URI: http://dialect.ca/?wp_smush_it
Contributors: alexdunae
Tags: images, image, attachments, attachment
Requires at least: 2.8
Tested up to: 3.0.4
Stable tag: 1.3.4

Reduce image file sizes and improve performance using the <a href="http://smush.it/">Smush.it</a> API within WordPress.

== Description ==

Yahoo's excellent <a href="http://developer.yahoo.com/performance/">Exceptional Performance series</a> recommends <a href="http://developer.yahoo.com/performance/rules.html#opt_images">optimizing images</a> in several lossless ways:

* stripping meta data from JPEGs
* optimizing JPEG compression
* converting certain GIFs to indexed PNGs
* stripping the un-used colours from indexed images

<a href="http://smush.it/">Smush.it</a> offers an API that performs these optimizations (except for stripping JPEG meta data) automatically, and this plugin seamlessly integrates Smush.it with WordPress.

= How does it work? =
Every image you add to a page or post will be automatically run through Smush.it behind the scenes.  You don&rsquo;t have to do anything different.

= Existing images =
You can also run your existing images through Smush.it via the WordPress `Media Library`.  Click on the `Smush.it now!` link for any image you'd like to smush.

= Privacy = 
Be sure you&rsquo;re comfortable with Smush.it&rsquo;s privacy policy (found on their <a href="http://info.yahoo.com/legal/us/yahoo/smush_it/smush_it-4378.html">FAQ</a>).

= Updates, etc... =

Plugin updates are announced on [http://www.twitter.com/TheCHANGELOG](http://www.twitter.com/TheCHANGELOG).

== Screenshots ==

1. See the savings from Smush.it in the Media Library.

== Installation ==

1. Upload the `wp-smushit` plugin to your `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Done!

== Changelog ==


= 1.3.3
* add debugging output on failure

= 1.3.2
* removed realpath() call
* IPv6 compat

= 1.3.1
* handle images stored on other domains -- props to [ka-ri-ne](http://wordpress.org/support/profile/ka-ri-ne) for the fix
* avoid time-out errors when working with larger files -- props to [Milan Dinić](http://wordpress.org/support/profile/dimadin) for the fix

= 1.2.10 = 
* removed testing link

= 1.2.9 = 
* updated Smush.it endpoint URL

= 1.2.8 =
* fixed path checking on Windows servers

= 1.2.7 =
* update to workaround WordPress's new JSON compat layer (see [trac ticket](http://core.trac.wordpress.org/ticket/11827))

= 1.2.6 =
* updated Smush.it endpoint URL
* fixed undefined constant

= 1.2.5 =
* updated Smush.it endpoint URL

= 1.2.4 =
* removed debugging code that was interfering with the Flash uploader

= 1.2.3 =
* bug fix

= 1.2.2 =
* updated to use Yahoo! hosted Smush.it service
* added security checks to files passed to `wp_smushit()`

= 1.2.1 =
* added support for PHP 4
* created admin action hook as workaround to WordPress 2.9's `$_registered_pages` security (see [changeset 11596](http://core.trac.wordpress.org/changeset/11596))
* add savings amount in bytes to Media Library (thx [Yoast](http://www.yoast.com/))

= 1.2 =
* added support for `WP_Http`

= 1.1.3 =
* fixed activation error when the PEAR JSON library is already loaded

= 1.1.2 =
* added test for `allow_url_fopen`

= 1.1.1 =
* added error message on PHP copy error

= 1.1 =
* improved handling of errors from Smush.it
* added ability to manually smush images from media library
* fixed inconsistent path handling from WP 2.5 -> WP 2.7

= 1.0.2 =
* added 'Not processed' status message when browsing media library

= 1.0.1 =
* added i10n functions

= 1.0 =
* first edition


== Contact and Credits ==

Written by Alex Dunae at Dialect ([dialect.ca](http://dialect.ca/?wp_smush_it), e-mail 'alex' at 'dialect dot ca'), 2008-11.

WP Smush.it includes a copy of the [PEAR JSON library](http://pear.php.net/pepr/pepr-proposal-show.php?id=198) written by Michal Migurski.

Smush.it was created by [Nicole Sullivan](http://www.stubbornella.org/content/) and [Stoyan Stefanov](http://phpied.com/).

