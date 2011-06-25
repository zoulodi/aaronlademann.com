<?php

/*-----------------------------------------------------------------------------------

	Add Portfolio Post Type

-----------------------------------------------------------------------------------*/


function tz_create_post_type_portfolio() 
{
	$labels = array(
		'name' => __( 'Portfolio','framework'),
		'singular_name' => __( 'Portfolio','framework' ),
		'add_new' => __('Add New','framework'),
		'add_new_item' => __('Add New Portfolio','framework'),
		'edit_item' => __('Edit Portfolio','framework'),
		'new_item' => __('New Portfolio','framework'),
		'view_item' => __('View Portfolio','framework'),
		'search_items' => __('Search Portfolio','framework'),
		'not_found' =>  __('No portfolio found','framework'),
		'not_found_in_trash' => __('No portfolio found in Trash','framework'), 
		'parent_item_colon' => ''
	  );
	  
	  $args = array(
		'labels' => $labels,
		'public' => true,
		'exclude_from_search' => true,
		'publicly_queryable' => true,
		'show_ui' => true, 
		'query_var' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => null,
		"rewrite" => array('slug' => 'portfolio', 'hierarchical' => true), 
		'supports' => array('title','editor','thumbnail','custom-fields','excerpt','comments')
	  ); 
	  
	  register_post_type(__( 'portfolio' ),$args);
}



function tz_build_taxonomies(){
	$skill_args = array(
		"hierarchical" => true, 
		"show_option_all" => __( "[ empty ]" ),
		"show_option_none" => __( "[ empty ]" ),
		"label" => __( "Skills" ), 
		"singular_label" => __( "Skill" ), 
		"rewrite" => array('slug' => 'portfolio/skill', 'hierarchical' => true), 
		"public" => true
	);
	register_taxonomy(__( "skill-type" ), array(__( "portfolio" )), $skill_args); 
	
	$media_args = array(
		"hierarchical" => true, 
		"show_option_all" => __( "[ empty ]" ),
		"show_option_none" => __( "[ empty ]" ),
		"label" => __( "Media Types" ), 
		"singular_label" => __( "Media" ), 
		"rewrite" => array('slug' => 'portfolio/media', 'hierarchical' => true), 
		"public" => true
	);
	register_taxonomy(__( "media-type" ), array(__( "portfolio" )), $media_args); 	
	
	$tool_args = array(
		"hierarchical" => true, 
		"show_option_all" => __( "[ empty ]" ),
		"show_option_none" => __( "[ empty ]" ),
		"label" => __( "Tools Used" ), 
		"singular_label" => __( "Tool" ), 
		"rewrite" => array('slug' => 'portfolio/tool', 'hierarchical' => true), 
		"public" => true
	);
	register_taxonomy(__( "tools-used" ), array(__( "portfolio" )), $tool_args); 	
}


function tz_portfolio_edit_columns($columns){  

        $columns = array(  
            "cb" => "<input type=\"checkbox\" />",  
            "title" => __( 'Portfolio Item Title' ),
            "type" => __( 'type' )
        );  
  
        return $columns;  
}  
  
function tz_portfolio_custom_columns($column){  
        global $post;  
        switch ($column)  
        {    
            case __( 'type' ):  
                echo get_the_term_list($post->ID, __( 'skill-type' ), '', ', ','');  
                break;
        }  
}  

add_action( 'init', 'tz_create_post_type_portfolio' );
add_action( 'init', 'tz_build_taxonomies', 0 );
add_filter("manage_edit-portfolio_columns", "tz_portfolio_edit_columns");  
add_action("manage_posts_custom_column",  "tz_portfolio_custom_columns");  

?>