<?php

add_filter("manage_edit-portfolio_columns", "portfolio_edit_columns");  

function portfolio_edit_columns( $columns ) {
	$columns = array(  
		"cb" 			=> "<input type=\"checkbox\" />",  
		"title" 		=> "Project",
		"permalink"		=> "Permalink",
		"media"			=> "Number of media",
		"clients"		=> "Client(s)",
		"pcategories"	=> "Categories",
		"ptags"			=> "Tags",
	);  

	return $columns;  
}


add_action("manage_posts_custom_column",  "prod_custom_columns");

function prod_custom_columns($column){  
	global $post;  
	
	switch ($column) {   
		case "permalink":  
 			echo (trim(get_option('slug')) != '' ? get_option('slug') : 'portfolio') . '/' . $post->post_name;  
 			break;  
		case "media":  
			$media = simple_portfolio_media($post->ID);
			echo count($media);
 			break;  
		case "clients":  
			echo "<ul>";
			foreach (wp_get_post_terms($post->ID, 'portfolio-clients') as $term):
				echo "<li>";
				echo "<a href=\"" . get_term_link($term->slug, $term->taxonomy) . "\">$term->name</a>";
				echo "</li>";
			endforeach;
			echo "</ul>";
 			break;
		case "pcategories":  
			echo "<ul>";
			foreach (wp_get_post_terms($post->ID, 'portfolio-categories') as $term):
				echo "<li>";
				echo "<a href=\"" . get_term_link($term->slug, $term->taxonomy) . "\">$term->name</a>";
				echo "</li>";
			endforeach;
			echo "</ul>";
 			break;
		case "ptags":  
			echo get_the_term_list($post->ID, 'portfolio-tags', '', ', ');			
 			break;
	}  
}
?>