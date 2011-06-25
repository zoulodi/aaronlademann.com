<?php
add_action('init', 'portfolio_init');

/**
* Initialize simple-portfolio plugin
*/
function portfolio_init() {
	$custom_slug = get_option('slug') != '' ? get_option('slug') : 'portfolio';
	
	$args = array(
		'labels'			=> array(
			'name'					=> __('Portfolio'),
			'singular_name' 		=> __('Portfolio Project'),
			'add_new'				=> __('Add Project'),
			'add_new_item'			=> __('Add Project'),
			'new_item'				=> __('Add Project'),
			'view_item'				=> __('View Project'),
			'search_items' 			=> __('Search Portfolio'), 
			'edit_item' 			=> __('Edit Project'),
			'all_items'				=> __('Complete Portfolio'),
			'not_found'				=> __('No Projects found'),
			'not_found_in_trash'	=> __('No Projects found in Trash')
		),
		'taxonomies'		=> array('portfolio-categories', 'portfolio-clients', 'portfolio-tags'),
		'public'			=> true,
		'show_ui'			=> true,
		'_builtin'			=> false,
		'_edit_link'		=> 'post.php?post=%d',
		'capability_type'	=> 'post',
		'rewrite'			=> array('slug' => __($custom_slug)),
		'hierarchical'		=> false,
		'menu_position'		=> 20,
		'menu_icon'			=> WP_PLUGIN_URL . '/simple-portfolio/images/icon.jpg',
		'supports'			=> array('title', 'editor', 'comments', 'thumbnail')
	);
	
	/** create portfolio categories (taxonomy) */
	register_taxonomy('portfolio-categories', 'project', array(
			'hierarchical'		=> true,
			'show_ui'			=> true,
			'rewrite'			=> array('slug' => __($custom_slug . '/category')),
			'labels'			=> array(
					'name' 							=> __('Portfolio Categories'),
					'singular_name'					=> __('Portfolio Category'),
					'search_items' 					=> __('Search Portfolio Categories'),
					'popular_items'					=> __('Popular Portfolio Categories'),
					'all_items'						=> __('All Portfolio Categories' ),
					'parent_item'					=> __('Parent Portfolio Category'),
					'parent_item_colon'				=> __('Parent Portfolio Category'),
					'edit_item'						=> __('Edit Portfolio Category'), 
					'update_item'					=> __('Update Portfolio Category'),
					'add_new_item'					=> __('Add New Portfolio Category'),
					'new_item_name'					=> __('New Portfolio Category'),
					'separate_items_with_commas'	=> __('Separate Portfolio Categories with commas'),
					'add_or_remove_items' 			=> __('Add or remove Portfolio Categories'),
					'choose_from_most_used' 		=> __('Choose from the most used Portfolio Categories')
		)
	));
	
	/** create portfolio clients (taxonomy) */
	register_taxonomy('portfolio-clients', 'project', array(
			'hierarchical'		=> true,
			'show_ui'			=> true,
			'query_var' 		=> true,
			'rewrite'			=> array('slug' => __($custom_slug . '/client')),
			'labels'			=> array(
					'name' 							=> __('Clients'),
					'singular_name'					=> __('Client'),
					'search_items' 					=> __('Search Clients'),
					'popular_items'					=> __('Popular Clients'),
					'all_items'						=> __('All Clients' ),
					'parent_item'					=> __('Parent Client'),
					'parent_item_colon'				=> __('Parent Client'),
					'edit_item'						=> __('Edit Client'), 
					'update_item'					=> __('Update Client'),
					'add_new_item'					=> __('Add New Client'),
					'new_item_name'					=> __('New Client'),
					'separate_items_with_commas'	=> __('Separate Clients with commas'),
					'add_or_remove_items' 			=> __('Add or remove Clients'),
					'choose_from_most_used' 		=> __('Choose from the most used Clients')
		)
	));
	
	/** create portfolio tags (taxonomy) */
	register_taxonomy('portfolio-tags', 'project', array(
			'hierarchical'		=> false,
			'show_ui'			=> true,
			'query_var' 		=> true,
			'public'			=> true,
			'rewrite'			=> array('slug' => __($custom_slug . '/tag')),
			'labels'			=> array(
					'name' 							=> __('Tags'),
					'singular_name'					=> __('Tag'),
					'search_items' 					=> __('Search Tags'),
					'popular_items'					=> __('Popular Tags'),
					'all_items'						=> __('All Tags' ),
					'parent_item'					=> __('Parent Tag'),
					'parent_item_colon'				=> __('Parent Tag'),
					'edit_item'						=> __('Edit Tag'), 
					'update_item'					=> __('Update Tag'),
					'add_new_item'					=> __('Add New Tag'),
					'new_item_name'					=> __('New Tag'),
					'separate_items_with_commas'	=> __('Separate Tags with commas'),
					'add_or_remove_items' 			=> __('Add or remove Tags'),
					'choose_from_most_used' 		=> __('Choose from the most used Tags')
		)
	));
	
	/** create new custom post type */
	register_post_type('portfolio', $args);
}

?>