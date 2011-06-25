=== Carousel Gallery (jQuery) ===
Contributors: Joen, eTiger13
Tags: gallery, images, javascript, jquery, pictures, photos
Requires at least: 2.6
Tested up to: 2.9.1
Stable tag: trunk

Carousel Gallery tweaks Wordpress' built-in gallery function by simply replacing it with a really neat looking javascript flippable gallery (a carousel).

As such, it's a really simple and minimalistic plugin; simply activate, and your galleries look a bit nicer, and are a bit more interactive and fast loading. No need to flip through several HTML pages, when you can quickly brush through the entire gallery in one place. You can <a href="http://noscope.com/journal/2009/03/carousel-gallery-jquery-for-wordpress">see a demo</a>.

The plugin uses jQuery, and if your site doesn't already use jQuery, it'll add the script for you.

== Description ==

This plugin tweaks the gallery tag ([gallery]) in Wordpress 2.6 thusly by replacing it with a javascript carousel.

== Installation ==

1. Unpack the plugin, put it in your "plugins" folder (`/wp-content/plugins/`).
2. Activate the plugin from the Plugins section.

== F.A.Q. ==

= How do I change the layout =
Most of the design, you have to do using CSS. Fortunately there are lots of classes to hook into.

= How do I change the image and thumbnail sizes =
Carousel Gallery uses the Wordpress Media settings for this (Settings -> Media).

Since version 1.5, you can also add the width/height of thumbnails in the Carousel Gallery options page. These are, however, scaled down using CSS, and could suffer in quality.

== Screenshots ==

1. This shows the default look of the gallery. When you click the "Next" button, the large image slides to the left and the next thumbnail is shown large.

== Changelog ==

* 1.0: First release.
* 1.1: Made clicking the current picture show the next.
* 1.2: Tweaked the "next" link, to not be a dummy.
* 1.3: Most importantly, improved IE6 compatability. Previously, the thing wouldn't loop. Now it will, not perfectly, but at least it won't crap out either. Made the CSS less !important. Made it hard to style or unstyle. Also made "next" and "prev" translatable. Also added a hidden feature, which allows you to remove the first image in the gallery, should that be the one you're using as thumbnail. You have to hack the plugin for this.
* 1.4: Fixed a problem where on some setups, thumbnails wouldn't show up. Also changed the way JQuery is registered, so that it's more compatible with other JQuery plugins.
* 1.4.1: Renamed the function that enqueues jquery to not conflict with other plugins.
* 1.5: Added an options page that allows you to configure a number of things. Additionally, tweaked the gallery so that titles and descriptions are now shown. Finally, made it translatable.
* 1.6: Added some tweaks thanks to Mayid (rel="nofollow"). Also now uses "jQuery" instead of "$", which should make it easier in turn to make it more compatible.
* 1.6.1: Ouch! The jQuery library got truncated in the last SVN commit! If nothing worked for you, try now!
* 1.6.2: Doh, another minuscule bugfix.
* 1.6.3: Added a fix to an IE "loop" bug. Thanks a lot to Luqman Amjad from http://www.kudoswebsolutions.com/
* 1.6.4: Fixed validation errors with having Style tag in body of the page and unenclosed JS cdata