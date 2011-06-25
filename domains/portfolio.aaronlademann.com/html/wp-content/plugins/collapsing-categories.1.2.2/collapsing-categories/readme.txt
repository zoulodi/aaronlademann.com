=== Collapsing Categories ===
Contributors: robfelty
Donate link: http://blog.robfelty.com/plugins
Plugin URI: http://blog.robfelty.com/plugins
Tags: categories, sidebar, widget
Requires at least: 2.8
Tested up to: 3.0
Stable tag: 1.2.2

This plugin uses Javascript to dynamically expand or collapsable the set of
posts for each category.

== Description ==

This is a very simple plugin that uses Javascript to form a collapsable set of
links in the sidebar for the categories. Every post corresponding to a given
category will be expanded.

= What's New?=

* 1.2.2 (2010.08.05)
    * fixed self class for category archive pages (error pointed out by ltache)

* 1.2.1 (2010.07.25)
    * Fixed bug where top-level categories did not show up if only had posts in
      sub-sub categories.
    * Fixed post counting errors

* 1.2 (2010.06.18)
    * Added option to automatically expand categories to which a post is
      assigned on single post pages
    * Storing posts in a javascript array and only adding to the DOM when
      requested (speeds page load)
    * fixed bug where empty subcategory with non-empty subsubcategory doesn't
      show up

= CSS Class changes = 
Version 1.1 introduces different css classes to the collapsing categories and
posts, which should make it easier to style in the future, and more consistent
across my other collapsing plugins
Please see below for an explanation of the css classes

== Installation ==

IMPORTANT!
Please deactivate before upgrading, then re-activate the plugin. 

Unpackage contents to wp-content/plugins/ so that the files are in a
collapsing-categories directory.

= Widget installation = 

 Activate the plugin, then simply go the
Presentation > Widgets section and drag over the Collapsing Categories Widget.


= Manual installation = 

 Activate the plugin, then insert the following into your template: (probably
in sidebar.php). See the Options section for more information on specifying
options.
`
<?php 
echo "<ul class='collapsCatList'>\n";
if (function_exists('collapsCat')) {
  collapsCat();
} else {
  wp_get_categories('your_options_here');
}
echo "</ul>\n";
?>
`

== Frequently Asked Questions ==


=  What is the option about the ID of the sidebar? =

Here is the deal. If you have a rule in your theme like:
`#sidebar ul li ul li {color:blue}`
it will override a rule like
`li.collapsArch {color:red}`
because it uses an ID, instead of a class. That is the way CSS works. So if
you change our rule to:
`#sidebar li.collapsArch {color:red}`
then this alleviates that problem. 
The option for the ID of the sidebar does this automatically for you.

= How do I use different symbols for collapsing and expanding? =

If you want to use images, you can upload your own images to
http://yourblogaddress/wp-content/plugins/collapsing-categories/img/collapse.gif
and expand.gif

There is an option for this.

= I have selected a category to expand by default, but it doesn't seem to work =

If you select a sub-category to expand by default, but not the parent
category, you will not see the sub-category expanded until you expand the
parent category.  You probably want to add both the parent and the
sub-category into the expand by default list.

= I can't get including or excluding to work = 

Make sure you specify category names, not ids.

= There seems to be a newline between the collapsing/expanding symbol and the
category name. How do I fix this? =

If your theme has some css that says something like

#sidebar li a {display:block}

that is the problem. 
You probably want to add a float:left to the .sym class

= No categories are showing up! What's wrong?" =

Are you using categories or tags? By default, collapsing categories only lists
categories. Please check the options in the settings page (or in the widget if
you are using the widget)

=  How do I change the style of the collapsing categories lists? =

As of version 0.9, there are several default styles that come with
collapsing-categories. You can choose from these in the settings panel, or you
can create your own custom style. A good strategy is to choose a default, then
modify it slightly to your needs. 

The following classes are used:
* collapsing - applied to all ul and li elements
* categories - applied to all ul and li elements
* list - applied to the top-level ul
* item - applied to each li which has no sub-elements
* expand - applied to a category which can be expanded (is currently
  collapsed)
* collapse - applied to a category which can be collapsed (is currently
  expanded)
* sym - class for the expanding / collapsing symbol

An example:
`
<ul id='widget-collapscat-15-top ' class='collapsing categories list'>
  <li class='collapsing categories post'><a
    href='http://mysite.com/your-website/about-your-own-site/'
    title='About your own site'>About your own site</a>
  </li>
  <li class='collapsing categories'><span class='collapsing categories expand'
    onclick='expandCollapse(event, "▶","▼", 1, "collapsing categories"); return
    false'><span class='sym'>▶</span>Web hosting</span>
    <ul id='collapsCat-176-15' style="display:none">
      <li class='collapsing categories post'><a 
        href='http://mysite.com/your-website/web-hosting/about-webhosting/'
        title='About webhosting'>About webhosting</a>
      </li>
      <li class='collapsing categories post'><a 
        href='http://mysite.com/products/webhosting-1/'
        title='Webhosting #1'>Webhosting #1</a>
      </li>
      <li class='collapsing categories post'><a 
        href='http://mysite.com/products/webhosting-2/'
        title='Webhosting #2'>Webhosting #2</a>
      </li>
    </ul>
  </li> <!-- ending subcategory -->
`

== Screenshots ==

1. a few expanded categories with default theme, showing nested categories
2. available options 

== Options ==
Style options can be set via the settings panel. All other options can be set
from the widget panel. If you wish to insert the code into your theme manually
instead of using a widget, you can use the following options. These options
can be given to the `collapsCat()` function either as an array or in query
style, in the same manner as the `wp_list_categories` function.

`$defaults=array(
   'showPostCount' => true,
   'inExclude' => 'exclude',
   'inExcludeCats' => '',
   'showPosts' => true, 
   'showPages' => false,
   'linkToCat' => true,
   'olderThan' => 0,
   'excludeAll' => '0',
   'catSortOrder' => 'ASC',
   'catSort' => 'catName',
   'postSortOrder' => 'ASC',
   'postSort' => 'postTitle',
   'expand' => '0',
   'defaultExpand' => '',
   'postTitleLength' => 0,
   'animate' => 0,
   'catfeed' => 'none',
   'catTag' => 'cat',
   'showPostDate' => false,
   'postDateAppend' => 'after',
   'postDateFormat' => 'm/d',
   'useCookies' => true,
   'showTopLevel' => true,
   'postsBeforeCats' => false,
   'expandCatPost' => true,
   'debug'=>'0'
);
`

* inExclude
    * Whether to include or exclude certain categories 
        * 'exclude' (default) 
        * 'include'
* inExcludeCats
    * The categories which should be included or excluded
* showPages
    * Whether or not to include pages as well as posts. Default if false
* linkToCat
    * 1 (true), clicking on a category title will link to the category archive (default)
    * 0 (false), clicking on a category title expands and collapses 
* catSort
    * How to sort the categorys. Possible values:
        * 'catName' the title of the category (default)
        * 'catId' the Id of the category
        * 'catSlug' the url of the category
        * 'catCount' the number of posts in the category
        * 'catOrder' custom order specified in the categorys settings
* catSortOrder
    * Whether categories should be sorted in normal or reverse
      order. Possible values:
        * 'ASC' normal order (a-z 0-9) (default)
        * 'DESC' reverse order (z-a 9-0)  
* postSort
    * How to sort the posts. Possible values:
        * 'postDate' the date of the post (default)
        * 'postId' the Id of the post
        * 'postTitle' the title of the post
        * 'postComment' the number of comments on the post
* postSortOrder
    * Whether post should be sorted in normal or reverse
      order. Possible values:
        * 'ASC' normal order (a-z 0-9) (default)
        * 'DESC' reverse order (z-a 9-0)  
* expand
    * The symbols to be used to mark expanding and collapsing. Possible values:
        * '0' Triangles (default)
        * '1' + -
        * '2' [+] [-]
        * '3' images (you can upload your own if you wish)
        * '4' custom symbols
* customExpand
    * If you have selected '4' for the expand option, this character will be
      used to mark expandable link categories
* customCollapse
    * If you have selected '4' for the expand option, this character will be
      used to mark collapsible link categories
* postTitleLength
    * Truncate post titles to this number of characters (default: 0 = don't
      truncate)
* animate
    * When set to true, collapsing and expanding will be animated
* catfeed
    * Whether to add a link to the rss feed for a category. Possible values:
        * 'none' (default)
        * 'text' shows RSS
        * 'image' shows an RSS icon
* catTag
    * Whether to include categories, tags, or both. Possible values:
        * 'cat' (default)
        * 'tag'
        * 'both'
*   showPostDate 
    * When true, show the date of each post
*   postDateAppend
    * Show the date before or after the post title. Possible values:
        * 'after' (default)
        * 'before'
*   postDateFormat
    * What format the post date is in. This uses the standard php date
      formatting codes
*   useCookies
    * When true, expanding and collapsing of categories is remembered for each
      visitor. When false, categories are always display collapsed (unless
      explicitly set to auto-expand). Possible values:
         * 1 (true) (default)
         * 0 (false)
* showTopLevel
    * When set to false, the top level category will not be shown. This could
      be useful if you only want to show subcategories from one particular
      top-level category
         * 1 (true) (default)
         * 0 (false)
* postsBeforeCats
    * When set to true, posts in category X will be ordered before
      subcategories of category X
         * 1 (true)
         * 0 (false) (default)
* expandCatPost
    * When set to true, any category to which a post is assigned will
      automatically be expanded on a single post page.
         * 1 (true) (default)
         * 0 (false)
* debug
    * When set to true, extra debugging information will be displayed in the
      underlying code of your page (but not visible from the browser). Use
      this option if you are having problems


= Examples =

`collapsCat('animate=1&catSort=ASC&expand=3&inExcludeCats=general,uncategorized')`
This will produce a list with:
* animation on
* categories shown in alphabetical order
* using images to mark collapsing and expanding
* exclude posts from  the categories general and uncategorized


== Demo ==

I use this plugin in my blog at http://blog.robfelty.com


== CAVEAT ==

Currently this plugin relies on Javascript to expand and collapse the links.
If a user's browser doesn't support javascript they won't see the links to the
posts, but the links to the categories will still work (which is the default
behavior in wordpress anyways)

== CHANGELOG ==

= 1.2.2 (2010.08.05) =
* fixed self class for category archive pages (error pointed out by ltache)

= 1.2.1 (2010.07.25) =
* Fixed bug where top-level categories did not show up if only had posts in
  sub-sub categories.
* Fixed post counting errors

= 1.2 (2010.06.18) =
* Added option to automatically expand categories to which a post is
  assigned on single post pages
* Storing posts in a javascript array and only adding to the DOM when
  requested (speeds page load)
* fixed bug where empty subcategory with non-empty subsubcategory doesn't
  show up

= 1.1.1 (2010.01.28) =
* Added option to display posts before categories
* Fixed bug with assigning self class to posts in sub-categories 
* Refactored code to reduce number of database reads when using multiple
  instances of the widget
* Switched from scriptaculous to jquery. No longer conflicts with plugins
  which use mootools (e.g. featured content gallery)
* Changed css class called "post" to "item" to avoid conflicts with other
  commonly used css classes

= 1.1 (2010.01.03) =
* Bug fixes
    * Fixed xhtml validation error (thanks Mathie)
    * Fixed incorrect link bug (thanks andydv)
    * Fixed some css issues 
    * Manual version works even if no options are given
    * Fixed include option
    * Fixed self class problems
    * Fixed link to settings page from widget options (thanks wp.Man)
    * Fixed rss options (thanks wp.Man)
* New options and features
    * Added option to hide top level category names
    * Changed css classes to make them more consistent with other collapsing
      plugins (thanks Bernhard Reiter)
* Internationalization and localization
    * Added Russian localization (thanks fatcow.com)
    * Added German localization (thanks Bernhard Reiter)

= 1.0.2 (2009.07.19) =
* Fixed older than option
* Added advanced options section in configuration
* Added advanced option to remember expanding and collapsing for each
  visitor (using cookies)
* Now issuing a correct id for each ul when using widgets 
* Small change in manual installation
* TODO: Added advanced option to expand category when viewing the category
  archive page
* Permalinks which use author now work correctly
=  1.0.1 (2009.06.22) =
* Fixed some problems with cookies on page load

=  1.0.beta (2009.06.08) =
* Added option to show post date
* Fixed some options that were broken in 1.0.alpha

=  1.0.alpha (2009.05.01) =
* Compatible with wordpress 2.8 widget api (incompatible with 2.7.1 and
  earlier)
* Can now add options manually when using manually instead of widget
* When using tags, link now points to correct location

=  0.9.8 (2009.04.17) =
* Fixed triangle problem

=  0.9.7 (2009.04.16) =
* fixed a few bugs introduced in 0.9.6

=  0.9.6 (2009.04.15) =
* Added option to group posts into misc category
* Switched role handling to proper API use (to control whether or not the
  settings page shows up)
* Added option for custom symbols
* No longer requires footer
* Updated javascript file
* Cleaned up code a bunch

=  0.9.5 (2009.03.22) =
* Fixed some more settings panel issues
* Truncate post title now working for posts in sub-categories
* Works even faster now for blogs with many posts
* Fixed option to show only tags
* Fixed previews for style settings
* Categories which do not have any posts due to date exclusion no longer
  show up
* Restored compatibility with my category order plugin
* Better internationalization support

=  0.9.4 (2009.03.09) =
* Fixed issue with multiple instances

=  0.9.3 (2009.03.08) =
* Fixed links to sub-categories

=  0.9.2 (2009.03.07) =
* Tweaked default style
* Fixed bug when not using permalinks

=  0.9.1 (2009.03.02) =
* Fixed bug where top level categories would not be displayed if they
  have no subcategories, and show only sub-categories is selected
* Can leave sidebar ID option blank if desired

=  0.9 (2009.03.01) =
* Added option to exclude posts older than certain number of days
* Widened widget options interface
* Updated text of widget options some
		* Categories no longer get nested if for some reasons there are no posts
		  showing up for a category 
		* Added option to exclude post X in categories A and B when either A or B
		  is excluded
* Post count is now more accurate
* Better internationalization for post and category titles
* Added truncate post title option
* Settings panel only available for admin
* fixed settings panel problems
* greatly increased speed for blogs with lots of posts and categories
* added new style selection method
* If current page is in category X, then category X will be expanded
  (thanks to Bernhard Reiter)

=  0.8.5 (2009.01.23) =
* fixed settings panel problems

=  0.8.4 (2009.01.15) =
* fixed sql queries, which seems to be working for most people now
* Got rid of empty quotes in query when no in/exclude is used
* Added option to list categories, tags, or both

=  0.8.3 (2009.01.08) =
* Refixed settings page for manual usage
* Changed category query in the hopes that it works for more people

=  0.8.2: (2009.01.07) =
* Added nofollow option
* Added version to javascript
* not loading unnecessary code for admin pages (fixes interference with
  akismet stats page
* fixed settings page for manual usage

=  0.8.1 (2009/01/06) =
* Finally fixed disappearing widget problem when trying to add to sidebar
* Added debugging option to show the query used and the output
* Moved style option to options page
 
=  0.8 (2008/12/08) =
* fixed javascript bug where thisli.parentNode was null
* made javascript more flexible so that all collapsing X plugins can share
  more code
* Now adds default options to database upon activation for use manually
* styling now done through an option
* inline javascript moved to footer for faster page loading

=  0.7.1 (2008/12/01) =
* fixed javascript bug in IE7

=  0.7 (2008/11/22) =
* Cookie handling now affects categories that are expanded by default too
* Can now be used either as a widget or manually
* Got rid of the stupid float left from 0.6.6

=  0.6.6 (2008/11/21) =
* Added a float left to .sym css to make it compatible with more themes

=  0.6.5 (2008/11/18) =
* Now uses cookies to keep categories expanded if they have been clicked on

=  0.6.4 (2008/11/10) =
* Fixed a minor bug in with animation option not being properly set by
  default

=  0.6.3 (2008/10/03) =
* Added option to animate expanding and collapsing
* Added option to add rss feeds for each category

=  0.6.2 (2008/09/11) =
* Fixed display of expand and collapse symbols when using images
* Improved font handling and styling of text symbols

=  0.6.1 (2008/09/01) =
* Improved styling so that collapsing and expanding symbols use a
  fixed-width font, but category names do not
* When using the option to have category names trigger expansion, and not
  showing posts, categories with no subcategories now link to the category
* Added option to use images instead of html for collapse/expand characters
* +/- now uses UTF-8 encoding instead of html entities (may not work for
  pages not encoded in UTF-8

=  0.6 (2008/08/27) =
* Can have multiple instances of widgets, each with separate options
* No longer works as non-widget
* All options are stored in one database row
* Added more sorting options
* Added option to include or exclude certain categories
* Added option to expand certain categories by default
* Added option to have category names either link to category archive or to
  activate expanding and collapsing

=  0.5.10 (2008/08/20) =
* minor bug fix. Fixed option to optionally show pages

=  0.5.9 (2008/08/07) =
* minor bug fix - added space before category count
* Added option to sort by category (term) order
* Added option to sort by category (term) count (note that it sorts by the
  count of the parent category, so categories with many subcategories, but
  not many posts themselves will be out of order
* Added option to sort posts within categories

=  0.5.8 (2008/06/15) =
		* bug fix - category description now correctly appears in title attribute
		  if there is a description for a given category
* implemented a few more changes to work towards internationalization

=  0.5.7 (2008/05/23) =
* fixed misnamed class in javascript (collapsArch -> collapsCat)
* added font-family definition to css to make it monospace for +/- 
* added another option with brackets around the +/-

=  0.5.6 (2008/05/23) =
* fixed bug such that subcategories would not display the expand and
  collapse icons
* fixed bug that categories with subcategories that have posts, but do not
  have posts themselves will be displayed
* Thanks to [Andy] (http://www.onkelandy.com/blog) for both of these bug
  notices

=  0.5.5 (2008/05/19) =
* fixed bug - html now validates when not displaying posts
* new option - choose between arrows or +- for expanding and collapsing
* tweaked exclude option to function better with collapsing categories

=  0.5.4 =
* fixed bug - was using hard-coded wp_ prefix in one SQL query. 
  Now using $wpdb-> instead

=  0.5.3 =
* count is now correct for all subcategories

=  0.5.2 =
* Added option to exclude certain categories
* Added option to sort categories by slug

=  0.5.1 =
* options in widget seem to work now
* removed duplicate entries due to tag + category

=  0.5 =
* Added option to not show posts
* Added option to change title in widget
* Now is condensed into one plugin

=  0.4.4 =
* using unicode number codes in css stylesheet
* fixed bug with duplicate entries in subcategories

=  0.4.3 =
* nicer list indenting
* re-fixed permalink bug introduced sometime after version 0.3.5

=  0.4.2 =
* fixed bug with extraneous <ul>

=  0.4.1 =
		* fixed bug with get_sub_cat definition problem in WP 2.5. Looks like it
		  had something to do with nested functions maybe

=  0.4 =
* Verified to work with wordpress 2.5
* Now has custom styling option through the collapsCat.css stylesheet
* updated screenshots
* moved javascript into collapsCat.php and got rid of separate file

=  0.3.7 =
* strips html tags from post titles now

=  0.3.6 =
* Fixed bug introduced in version 0.3.5 where all links in a category
  pointed to the same post

=  0.3.5 =
* Now links should work with all sorts of permalink structures. Thanks to
  Krysthora http://krysthora.free.fr/ for finding this bug

=  0.3.4 =
* Added option to sort categories by id or name

=  0.3.3 =
* fixed bug in headers when collapsCat is not loaded
* fixed a few minor markup issues to make it valid xhtml

=  0.3.2 =
* posts now have the class "collapsCatPost" and can be styled with CSS.
  Some styling has been added in collapsCat.php
* removed list icons in front of triangles

=  0.3.1 =
* Added option to make post links to index.php, root, or archive.php, like
  collapsing-categories
* Fixed link to category listings

=  0.3 =
* Now uses only 2 database queries instead of 1 + 2*(count(categories))
* Now supports infinite levels of subcategories

=  0.2.2:  =
* Added option to show pages in list or not

=  0.2.1:  =
* Added collapsing class to <li>s with triangles for CSS styling
* Added style information to make triangles bigger and give a pointer
  cursor over them
* Added title tags to triangles to indicate functionality
		* Checking whether some of the same functionality from collapsing-categories
		  has already been loaded (for example the javascript file) in order to
		  avoid redundancy

=  0.2:  =
* Changed name from Fancy categories to Collapsing categories
* Changed author from Andrew Rader to Robert Felty
* Added triangles which mark the collapsing and expanding features
  That is, clicking on the triangle collapses or expands, while clicking
  on a category links to the category list for the said category.
  This uses html entities (dings) instead of images, for a variety of 
  reasons
* Lists the titles of posts, instead of just listing subcategories
* Removed the rel='hide' and rel='show' tags, because they are not xhtml
  1.0 compliant. Now uses the CSS classes instead
		* MOST IMPORTANTLY -- is compatible with both the pre 2.3 database which
		  uses categories, and the 2.3+ database structure which uses the tag
		  taxonomy

---------------------------------------------------------------------------

Fancy Categories Changelog

0.1:
	Initial Release
