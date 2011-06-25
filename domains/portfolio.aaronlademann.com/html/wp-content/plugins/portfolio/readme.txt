=== Portfolio ===
Contributors: bestwebsoft
Donate link: http://bestwebsoft.com/
Tags: portfolio, images gallery, custom fields
Requires at least: 3.1
Tested up to: 3.1
Stable tag: 2011.1.05

Portfolio allows you to create a page with information about your past projects.

== Description ==

Portfolio allows to create a unique page for displaying portfolio items with screenshots and additional information such as description, short description, URL, date of completion, etc.
Also it allows to add additional screenhots (many additional screenshots per 1 portfolio item).

== Installation ==

1. Upload `portfolio` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. If you have a 'loop.php' file in your WordPress template then place this strings `<?php global $wp_query; if ( $wp_query->query_vars['tag'] ) { 
if(function_exists('display_term')) { display_term(); return; } } ?>` in your templates before string `<?php if ( ! have_posts() ) : ?>`or `<?php if ( have_posts() ) : ?>`.
4. Please check if you have the 'portfolio.php' template file in your templates directory. If you are not able to find this file, then just copy it from '/wp-content/plugins/portfolio/template/' directory to your templates directory.

== Frequently Asked Questions ==

= I cannot view my Portfolio page =

1. First of all, you need to create your first Portfolio page and choose 'Portfolio Template' from a list of available templates (which will be used for displaying our portfolio).
2. If you cannot find 'Portfolio Template' from a list of available templates, then just copy it from '/wp-content/plugins/portfolio/template/' directory to your templates directory.

= How to use plugin? =

1. Create necessary technologies using this page http://example.com/wp-admin/edit-tags.php?taxonomy=post_tag&post_type=portfolio
2. This is optional. Fill in this page http://example.com/wp-admin/edit-tags.php?taxonomy=portfolio_executor_profile&post_type=portfolio - create a profile of executor. Fill in 'Name' and 'Description' fields. 'Description' field contains link to a personal executor's page.
3. Choose 'Add New' from the 'Portfolio' menu and fill out your page. Set necessary values for the  'Technologies' and 'Executors Profile' widgets.

= How to add an image? =

Use Wordpress meta box to upload images from URL or your local storage. Note that one image needs to be selected as 'Featured' - it will be main image of your Portfolio item.

== Screenshots ==

1. This screen shot for Add New Portfolio items.
2. Add technologies page.
3. Add executors profile page.
4. Portfolio frontend page.

== Changelog ==

= 1.05 =
* In this version fixes a display image bug.

= 1.04 =
* In this version added image for portfolio to the admin page.

= 1.03 =
* In this version the image uploading by means of custom fields is substituted with the Wordpress standard meta box for the media files uploading.

== Upgrade Notice ==

= 1.05 =
This version fixes a display image bug. Upgrade immediately.

= 1.03 =
This version fixes a security related bug. Upgrade immediately.