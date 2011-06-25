<?php
/**
Plugin Name: Simple Portfolio
Plugin URI: http://projects.inlet.nl/simple-portfolio-wordpress3/
Description: Manage your portfolio projects easily and use them everywhere you like. This plugin is very simple to use, it doesn't bother you with a complex user interface. Add project specific information, for example what your role was and the team you've worked in, etc. Add any media you like: YouTube, code snippets or any media from your wordpress built-in Media Library. Wonder how it works? Watch the <a href="http://www.inlet.nl">screencast</a>.
Version: 1.7.1
Author: Patrick Brouwer (Inlet)
Author URI: http://www.inlet.nl

    Copyright 2010 Patrick Brouwer  (email : patrick@inlet.nl)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

include('extends/init.php');
include('extends/settings-panel.php');
include('extends/dashboard.php');
include('extends/admin-head.php');
include('extends/admin-menu.php');
include('extends/save-post.php');
include('extends/rewrite.php');
include('extends/wp-head.php');
include('extends/columns.php');

/**
* Retrieves all portfolio media for provided project post. 
* @param $post_id int (optional) the project post id, if null pull from global $post
* @param $type string (optional) filter on type. possible values are: 'image', 'youtube', 'snippet' or 'text'
* @return array
*
* Example retrieve all project media:
* (usage in the loop @see http://codex.wordpress.org/The_Loop)
*
*		<?php while (have_posts()) : the_post(); ?>
*			<h1>Media</h1>
*			<ul>
*			<?php foreach (simple_portfolio_media() as $media): ?>
*				<li><?php print_r($media); ?></li>
*			<?php endforeach; ?>
*			</ul>
*		<?php endwhile; ?>
*
* Example retrieve typed media like Images and display them:
* (usage in the loop @see http://codex.wordpress.org/The_Loop)
*
*		<?php while (have_posts()) : the_post(); ?>
*			<?php foreach (simple_portfolio_media('image') as $image): ?>
*				<?php 
*					$src = wp_get_attachment_image_src($image['value'], 'full');
*					echo "<a href=\"$src[0]\">" . wp_get_attachment_image($image['value']) . "</a>";
*				?>
*			<?php endforeach; ?>
*		<?php endwhile; ?>
*
*/
function simple_portfolio_media(  $post_id = null, $type = null  ) {
	global $post;
	if ($post_id == null) $post_id = $post->ID;
	
	$media = get_post_meta( $post_id, 'portfolio_media', false );
	$media = (is_array($media) && count($media) > 0) ? unserialize(base64_decode($media[0])) : array();
	
	if ($type != null):
		$new_media = array();
		foreach ($media as $value):
			if ($value['type'] == $type) 
				$new_media[] = $value;
		endforeach;
		$media = $new_media;
	endif;
	
	return $media;
}

/**
* Retrieves all portfolio general information for provided project post
* @param $post_id int (optional) the project post id, if null pull from global $post
* @return array
*
* Example: (usage in the loop @see http://codex.wordpress.org/The_Loop)
*		<?php while (have_posts()) : the_post(); ?>
*			<h1>Project Information</h1>
*			<ul>
*			<?php foreach (simple_portfolio_info() as $info): ?>
*				<li><?php echo $info; ?></li>
*			<?php endforeach; ?>
*			</ul>
*		<?php endwhile; ?>
*/
function simple_portfolio_info( $post_id = null ) {
	global $post;
	if ($post_id == null) $post_id = $post->ID;
	
	$custom = get_post_custom( $post_id );
	$post_portfolio_info = array();
	
	foreach (get_option_preformatted() as $key=>$value):
		$post_portfolio_info['portfolio_' . $key] = isset($custom['portfolio_' . $key]) ? $custom['portfolio_' . $key][0] : '';
	endforeach;
	
	return $post_portfolio_info;
}

/**
* Query projects. @see http://codex.wordpress.org/Function_Reference/query_posts
* You can use the loop from wordpress @see http://codex.wordpress.org/The_Loop after calling this method.
* @param $taxonomy string/array name of the taxonomy, possible values are: 'portfolio-clients', 'portfolio-categories' or 'portfolio-tags' (or combine multiple combinations in an array)
* @param $slug string slug of the taxonomy for filtering projects on taxonomy slug name.
* @return array
*
* Example:
* 		<?php simple_portfolio_query_projects(); ?>
*		<?php while (have_posts()) : the_post(); ?>
*			<h1><?php echo the_title(); ?></h1>
*			<?php the_content('Read more &raquo;'); ?>
*		<?php endwhile; ?>
*/
function simple_portfolio_query_projects( $taxonomy = null, $slug = null, $args = null ) {
	$args = is_array($args) ? $args : array();
	$args['post_type'] = isset($args['post_type']) ? $args['post_type'] : 'portfolio';
	$args['post_status'] = isset($args['post_status']) ? $args['post_status'] : 'publish';
	$args['numberposts'] = isset($args['numberposts']) ? $args['numberposts'] : -1;
	
	if ($taxonomy && $slug) $args[$taxonomy] = $slug;
	if ($taxonomy && !$slug) $args['taxonomy'] = $taxonomy;
	return query_posts($args); 
}

/**
* Retrieve projects in an array. When no arguments are applied, all projects will be returned. @see http://codex.wordpress.org/Function_Reference/get_posts
* @param $taxonomy string/array name of the taxonomy, possible values are: 'portfolio-clients', 'portfolio-categories' or 'portfolio-tags' (or combine multiple combinations in an array)
* @param $slug string slug of the taxonomy for filtering projects on taxonomy slug name.
* @return array
*
* Example:
*		<?php $commercial_projects = simple_portfolio_get_projects('portfolio-categories', 'commercial'); ?>
*
*		<ul>
*		<?php foreach ($commercial_projects as $post): ?>
*			<?php setup_postdata($post); ?>
*			<li>
*				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
*				<?php the_content(); ?>
*			</li>
*		<?php endforeach; ?>
*		</ul>
*/
function simple_portfolio_get_projects( $taxonomy = null, $slug = null, $args = null ) {
	$args = is_array($args) ? $args : array();
	$args['post_type'] = isset($args['post_type']) ? $args['post_type'] : 'portfolio';
	$args['post_status'] = isset($args['post_status']) ? $args['post_status'] : 'publish';
	$args['numberposts'] = isset($args['numberposts']) ? $args['numberposts'] : -1;
	
	if ($taxonomy && $slug) $args[$taxonomy] = $slug;
	if ($taxonomy && !$slug) $args['taxonomy'] = $taxonomy; 
	return get_posts($args);
}

/**
* Is xml enabled? This way you can check easily if xml is enabled
* @return Boolean
* 
* Example check and show link to xml:
*		<?php if (simple_portfolio_xml_enabled()): ?>
*			<h1>You have enabled XML output</h1>
*			<?php $xml_url = get_site_url() . '/' . get_post_type_object('portfolio')->rewrite['slug'] . '.xml';
*			<a href="<?php echo $xml_url; ?>">View XML</a>
*		<?php endif; ?>
*/
function simple_portfolio_xml_enabled() {
	return get_option('use-xml') == '0' ? false : true;
}

/**
* List clients. Echo the output directly.
* @param $post_id int (optional) the project post id. (default null which list all clients unrelated to a project post)
* @param $args array @see http://codex.wordpress.org/Template_Tags/wp_list_categories
*
* Example list all clients (even those who have no project posts):
*		<h1>Clients</h1>
*		<?php simple_portfolio_list_clients(null, array('hide_empty' => 0)); ?>
*
* Example usage in the loop @see http://codex.wordpress.org/The_Loop 
*		<?php while (have_posts()) : the_post(); ?>
*			<h1>Client(s)</h1>
*			<?php simple_portfolio_list_clients($post->ID); ?>
*		<?php endwhile; ?>	
*/
function simple_portfolio_list_clients( $post_id = null, $args = null ) {
	$args = is_array($args) ? $args : array();
	$args['taxonomy'] = 'portfolio-clients';
	$args['title_li'] = '';
	
	if ($post_id) {
		echo "<ul>";
		echo get_the_term_list($post_id, $args['taxonomy'], '<li>', '</li><li>', '</li>' );  
		echo "</ul>";
		return;
	}
	
	return wp_list_categories($args);
}

/**
* Retrieve the clients as an array.
* @param $post_id int (optional) the project post id. (default null which list all clients unrelated to a project post)
* @param $args array @see http://codex.wordpress.org/Template_Tags/wp_list_categories
* @return array
*
* Example:
*		<?php $clients = simple_portfolio_get_clients($post->ID); ?>
*		There are <?php echo count($clients); ?> clients
*		<ul>
*			<?php foreach ($clients as $client): ?>
*				<li><a href="<?php echo $client->link; ?>"><?php echo $client->name; ?></a></li>
*			<?php endforeach; ?>
*		</ul>
*/
function simple_portfolio_get_clients( $post_id = null, $args = null ) {
	$args = is_array($args) ? $args : array();
	$args['taxonomy'] = 'portfolio-clients';
	
	if ($post_id) {
		$result = wp_get_post_terms($post_id, $args['taxonomy'], $args);
		foreach ($result as &$item):
			$item->link = get_term_link($item->slug, $item->taxonomy);
		endforeach;
		return $result;
	}
	
	$result = get_terms($args['taxonomy'], $args);
	foreach ($result as &$item):
		$item->link = get_term_link($item->slug, $item->taxonomy);
	endforeach;
	return $result;
}

/**
* List categories. Echo the output directly.
* @param $post_id int (optional) the project post id. (default null which list all categories unrelated to a project post)
* @param $args array @see http://codex.wordpress.org/Template_Tags/wp_list_categories
*
* Usage @see simple_portfolio_list_clients()
*/
function simple_portfolio_list_categories( $post_id = null, $args = null ) {
	$args = is_array($args) ? $args : array();
	$args['taxonomy'] = 'portfolio-categories';
	$args['title_li'] = '';
	
	if ($post_id) {
		echo "<ul>";
		echo get_the_term_list($post_id, $args['taxonomy'], '<li>', '</li><li>', '</li>' );  
		echo "</ul>";
		return;
	}
	
	return wp_list_categories($args);
}

/**
* Retrieve the categories.
* @param $post_id int (optional) the project post id. (default null which list all categories unrelated to a project post)
* @param $args array @see http://codex.wordpress.org/Template_Tags/wp_list_categories
* @return array
*
* Usage @see simple_portfolio_get_clients()
*/
function simple_portfolio_get_categories( $post_id = null, $args = null ) {
	$args = is_array($args) ? $args : array();
	$args['taxonomy'] = 'portfolio-categories';
	
	if ($post_id) {
		$result = wp_get_post_terms($post_id, $args['taxonomy'], $args);
		foreach ($result as &$item):
			$item->link = get_term_link($item->slug, $item->taxonomy);
		endforeach;
		return $result;
	}
	
	$result = get_terms($args['taxonomy'], $args);
	foreach ($result as &$item):
		$item->link = get_term_link($item->slug, $item->taxonomy);
	endforeach;
	return $result;
}

/**
* List tags. Echo the output directly.
* @param $post_id int (optional) the project post id. (default null which list all tags unrelated to a project post)
* @param $args array @see http://codex.wordpress.org/Template_Tags/wp_list_categories
*
* Usage @see simple_portfolio_list_clients()
*/
function simple_portfolio_list_tags( $post_id = null, $args = null ) {
	$args = is_array($args) ? $args : array();
	$args['taxonomy'] = 'portfolio-tags';
	$args['title_li'] = '';
	
	if ($post_id) {
		echo "<ul>";
		echo get_the_term_list($post_id, $args['taxonomy'], '<li>', '</li><li>', '</li>' );  
		echo "</ul>";
		return;
	}
	
	return wp_list_categories($args);
}

/**
* Retrieve the tags.
* @param $post_id int (optional) the project post id. (default null which list all tags unrelated to a project post)
* @param $args array @see http://codex.wordpress.org/Template_Tags/wp_list_categories
* @return array
*
* Usage @see simple_portfolio_get_clients()
*/
function simple_portfolio_get_tags( $post_id = null, $args = null ) {
	$args = is_array($args) ? $args : array();
	$args['taxonomy'] = 'portfolio-tags';
	
	if ($post_id) {
		$result = wp_get_post_terms($post_id, $args['taxonomy'], $args);
		foreach ($result as &$item):
			$item->link = get_term_link($item->slug, $item->taxonomy);
		endforeach;
		return $result;
	}
	
	$result = get_terms($args['taxonomy'], $args);
	foreach ($result as &$item):
		$item->link = get_term_link($item->slug, $item->taxonomy);
	endforeach;
	return $result;
}
?>