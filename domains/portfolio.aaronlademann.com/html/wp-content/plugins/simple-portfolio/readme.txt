=== Simple Portfolio ===

Contributors: patrick-brouwer
Donate link: http://www.inlet.nl
Tags: page, post, pages, posts, images, youtube, image, jpg, jpeg, picture, pictures, photos, portfolio, showcase, custom, clients, tags, categories.
Requires at least: 2.8
Tested up to: 3.1
Stable tag: 1.7.1

Simple Portfolio allows you to easily manage your portfolio. You can append snippets, youtube and media from the built-in Media Library to projects.

== Description ==

Manage your portfolio projects easily and use them everywhere you like. This plugin is very powerful and easy to use!  
You can create and manage your portfolio projects and add specific project information, for example your role and the team you've worked in.  
Add any media you like: YouTube, code snippets, text or any media from your wordpress built in Media Library. Besides it's easy to assign your projects to clients, categories or tags.  
This plugin provides an API which gives you the power to fetch all the data you want..

You can find more information about the plugin on the [plugin home page](http://projects.inlet.nl/simple-portfolio-wordpress3). 

Wonder how it works? [Watch the screencast](http://projects.inlet.nl/simple-portfolio-wordpress3/screencast).

For this plugin I created a sample theme, you can download it here: [plugin's homepage](http://projects.inlet.nl/simple-portfolio-wordpress3/wp-content/uploads/2010/07/simple-portfolio-theme.zip).

== Installation ==

1. Download the plugin and extract the files
1. Upload `simple-portfolio` to your `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Update your templates where applicable (see **Usage**)

== Frequently Asked Questions ==

= Where does this plugin save my media? =

The cool thing is that this plugin doesn't create extra tables in database or do other arbitrary stuff. Simple-portfolio only saves references of media to project meta data. This plugin uses the built in Media Library for uploads and storage.

= Can't show my projects? =

Simple-portfolio provides a small set of methods that can easily be implemented in your theme. Watch the [screencast](http://projects.inlet.nl/simple-portfolio-wordpress3/screencast/) for an example.

= Permalinks? =

You need to enable permalinks (see settings>permalinks). Change them to something different than 'default'. Now you can define your own slug, for example 'showcase' where you want to list your porfolio projects..

= Im totally stuck! What now? =

Have you checked the [screencast](http://projects.inlet.nl/simple-portfolio-wordpress3/screencast/) already? And have you studied the [API an Usage](http://wordpress.org/extend/plugins/simple-portfolio/other_notes/)? If not then don't hesitate to contact me!

= How can I add a custom taxonomy to portfolio ? =

[See function register_taxonomy_for_object_type on codex](http://codex.wordpress.org/Function_Reference/register_taxonomy_for_object_type)

All you have to do is create your own taxonomy and apply it on the simple portfolio post type like this:

<code>
<?php

add_action('init', 'theme_init');

function theme_init() {
	register_taxonomy('portfolio-brands', 'project', array( 'hierarchical' => true, 'show_ui' => true, 'label' => 'Portfolio Brands'));
	register_taxonomy_for_object_type('portfolio-brands', 'portfolio');
}

?>
</code>

== Screenshots ==

1. Overview of your projects
2. Add a project
3. Add general information to your project
4. Allow comments/trackbacks and pingbacks for specific projects
5. Assign your project to clients, categories and tags
6. Add images from the built-in Media Library
7. Image added to Media
8. Code snippet added to Media
9. Use the taxonomies and projects in your navigation menu
10. Settings panel (organize general information, slug and xml)
11. Example Theme implementation

== Changelog ==

= 1.7.1 =
Xml update, added more featured image formats.

= 1.7 =
The xml format is slightly changed. When navigating to site/slug.xml the menus are now recursive instead of listed under each other.

= 1.6 =

NOTE: I've changed the structure of the xml a bit, if you've used it in your site make sure you update the parsing logic

* added date 'created/modified' to portfolio xml
* added more xml features to settings>portfolio, you can now choose what type of data is included in the xml.
* Option to include all WP data in xml.
* Added featured image (post thumbnails for projects)

= 1.5 =

* File-access error caused by allow_fopen_url is set to 'Off' (failed to open stream) fixed. Uses the internal method 'wp_remote_fopen' instead.
* Added FAQ adding custom taxonomy

= 1.4 =

Dashboard fixed with new API

= 1.3 =

* Performance improvements in Wordpress CMS (media metabox/panel). Decrease load time of images.
* Taxonomies added, you can now assign your projects to categories and even tag them the way you like (thanks to Jankees van Woezik)
* Paginating fixed
* The option to enable/disable comments on projects
* Sorting settings>portfolio info fields the way you like
* XML update (clients, categories and tags added)
* New API added (huge improvement)

The next feature to be added will be the implementation of the sidebar widget, see [Dean Barrow's solutions](http://www.deanbarrow.co.uk/2010/08/customising-the-simple-portfolio-plugin-for-wordpress-3/)

= 1.2 =

Text type added

= 1.1 =

Bugfix on rewrite mechanism for paginating projects

= 1.0 = 

* First stable release. 

== Upgrade Notice == 

New features are added, make sure to update your template files in your theme (portfolio.php and single-portfolio.php). Also the API changed dramatically, simple_portfolio_all_projects() is removed and there are a bunch of new methods added.
Check the plugin simple-portfolio.php for more insight of the methods.

== Usage ==

After installing simple-portfolio, you will need to update your template files in your theme in order to pull the data to the front end. 
Since the update to version 1.3 there's changed a lot! You can now assign projects to clients, categories or tags. More features means more knowledge of theme development and therefore this update may excludes some newbies. 
One of the new features is allowing comments on your projects. You have to enable this in your theme See [Theme Development](http://codex.wordpress.org/Theme_Development) and [Comments Template](http://codex.wordpress.org/Function_Reference/comments_template)

Important is that you are aware of the API this plugin provides. 

= You have to create 2 extra template files to your theme =

* `portfolio.php`: this will list all your projects. Use the default [wordpress loop](http://codex.wordpress.org/The_Loop) to cycle through projects 
* `single-portfolio.php`: project detail information (single project)

== API ==

You can use these methods to pull the project(s) data in your theme

= Project Information =
<code>simple_portfolio_info($post_id = null)</code>

Retrieves all portfolio general information for provided project post  
@param $post_id int (optional) the project post id, if null pull from global $post  
@return array

Example: Usage in [the loop](http://codex.wordpress.org/The_Loop)
<code>
	<?php while (have_posts()) : the_post(); ?>
		<h1>Project Information</h1>
		<ul>
		<?php foreach (simple_portfolio_info() as $info): ?>
			<li><?php echo $info; ?></li>
		<?php endforeach; ?>
		</ul>
	<?php endwhile; ?>
</code>


= Project Media =
<code>simple_portfolio_media($post_id = null, $type = null)</code>

Retrieves all portfolio media for provided project post.  
@param $post_id int (optional) the project post id, if null pull from global $post  
@param $type string (optional) filter on type. possible values are: 'image', 'youtube', 'snippet' or 'text'  
@return array

Example retrieve all project media: [Usage in the loop](http://codex.wordpress.org/The_Loop)
<code>
	<?php while (have_posts()) : the_post(); ?>
		<h1>Media</h1>
		<ul>
		<?php foreach (simple_portfolio_media() as $media): ?>
			<li><?php print_r($media); ?></li>
		<?php endforeach; ?>
		</ul>
	<?php endwhile; ?>
</code>

= Query Projects = 
<code>simple_portfolio_query_projects($taxonomy = null, $slug = null)</code>

Query projects. see [wordpress query posts](http://codex.wordpress.org/Function_Reference/query_posts)  
You can use [the loop](http://codex.wordpress.org/The_Loop) from wordpress after calling this method.  
@param $taxonomy string/array name of the taxonomy, possible values are: 'portfolio-clients', 'portfolio-categories' or 'portfolio-tags' (or combine multiple combinations in an array)  
@param $slug string slug of the taxonomy for filtering projects on taxonomy slug name.  
@return array

Example:
<code>
	<?php simple_portfolio_query_projects(); ?>
	<?php while (have_posts()) : the_post(); ?>
		<h1><?php echo the_title(); ?></h1>
		<?php the_content('Read more &raquo;'); ?>
		<?php print_r(simple_portfolio_info($post->ID)); ?>
	<?php endwhile; ?>
</code>

= Get Projects = 
<code>simple_portfolio_get_projects($taxonomy = null, $slug = null)</code>

Retrieve projects in an array. When no arguments are applied, all projects will be returned. [See get posts](http://codex.wordpress.org/Function_Reference/get_posts)  
@param $taxonomy string/array name of the taxonomy, possible values are: 'portfolio-clients', 'portfolio-categories' or 'portfolio-tags' (or combine multiple combinations in an array)  
@param $slug string slug of the taxonomy for filtering projects on taxonomy slug name.  
@return array

Example:
<code>
	<?php $commercial_projects = simple_portfolio_get_projects('portfolio-categories', 'commercial'); ?>	
	<ul>
	<?php foreach ($commercial_projects as $post): ?>
		<?php setup_postdata($post); ?>
		<li>
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			<?php the_content(); ?>
		</li>
	<?php endforeach; ?>
	</ul>
</code>

= Check if XML is enabled =
<code>simple_portfolio_xml_enabled()</code>

Is xml enabled? This way you can check easily if xml is enabled  
@return Boolean 

Example check and show link to xml:
<code>
  	<?php if (simple_portfolio_xml_enabled()): ?>
  		<h1>You have enabled XML output</h1>
  		<?php $xml_url = get_site_url() . '/' . get_post_type_object('portfolio')->rewrite['slug'] . '.xml';
  		<a href="<?php echo $xml_url; ?>">View XML</a>
  	<?php endif; ?>
</code>

= List Clients =
<code>simple_portfolio_list_clients($post_id = null, $args = null)</code>

List clients. Echo the output directly.  
@param $post_id int (optional) the project post id. (default null which list all clients unrelated to a project post)  
@param $args array See [wp_list_categories](http://codex.wordpress.org/Template_Tags/wp_list_categories) 

Example list all clients (even those who have no project posts):
<code>
	<h1>Clients</h1>
	<?php simple_portfolio_list_clients(null, array('hide_empty' => 0)); ?>
</code>

Example usage in [the loop](http://codex.wordpress.org/The_Loop)
<code>
	<?php while (have_posts()) : the_post(); ?>
		<h1>Client(s)</h1>
		<?php simple_portfolio_list_clients($post->ID); ?>
	<?php endwhile; ?>
</code>

= Get Clients =
<code>simple_portfolio_get_clients($post_id = null, $args = null)</code>

Same as List Clients, excepts this retrieve the clients as an array.  
@param $post_id int (optional) the project post id. (default null which list all clients unrelated to a project post)  
@param $args array See [wp_list_categories](http://codex.wordpress.org/Template_Tags/wp_list_categories)  
@return array

Example:
<code>
	<?php $clients = simple_portfolio_get_clients($post->ID); ?>
	There are <?php echo count($clients); ?> clients
	<ul>
		<?php foreach ($clients as $client): ?>
			<li><a href="<?php echo $client->link; ?>"><?php echo $client->name; ?></a></li>
		<?php endforeach; ?>
	</ul>
</code>

= List Categories =
<code>simple_portfolio_list_categories($post_id = null, $args = null)</code>

List categories. Echo the output directly.  
@param $post_id int (optional) the project post id. (default null which list all categories unrelated to a project post)  
@param $args array See [wp_list_categories](http://codex.wordpress.org/Template_Tags/wp_list_categories) 

Usage see simple_portfolio_list_clients() 

= Get Categories =
<code>simple_portfolio_get_categories($post_id = null, $args = null)</code>

Retrieve the categories.  
@param $post_id int (optional) the project post id. (default null which list all categories unrelated to a project post)  
@param $args array See [wp_list_categories](http://codex.wordpress.org/Template_Tags/wp_list_categories)  
@return array

Usage see simple_portfolio_get_clients() 

= List Tags =
<code>simple_portfolio_list_tags($post_id = null, $args = null)</code>

List tags. Echo the output directly.  
@param $post_id int (optional) the project post id. (default null which list all tags unrelated to a project post)  
@param $args array See [wp_list_categories](http://codex.wordpress.org/Template_Tags/wp_list_categories) 

Usage see simple_portfolio_list_clients() 

= Get Tags =
<code>simple_portfolio_get_tags($post_id = null, $args = null)</code>

Retrieve the tags.  
@param $post_id int (optional) the project post id. (default null which list all tags unrelated to a project post)  
@param $args array See [wp_list_categories](http://codex.wordpress.org/Template_Tags/wp_list_categories)  
@return array

Usage see simple_portfolio_get_clients() 


== XML Output of all your projects ==

When you enable XML Output in **settings>portfolio** the location of your xml can be found here: `~/slugname.xml`

== Plugin Homepage ==

For more information, please visit the [plugin's homepage](http://projects.inlet.nl/simple-portfolio-wordpress3)