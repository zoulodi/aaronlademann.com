<?php
/**
 * @package Portfolio
 * @version 1
 */
/*
Plugin Name: Portfolio
Plugin URI:  http://bestwebsoft.com/plugin/
Description: Plugin for portfolio.
Author: BestWebSoft
Version: 1.05
Author URI: http://bestwebsoft.com/
License: GPLv2 or later
*/

/*  Copyright 2011  BestWebSoft  ( admin@bestwebsoft.com )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


$prtf_boxes = array (
	'Portfolio-Info' => array (
		array( '_prtf_date_compl', 'Date of completion', 'Date, when a task was executed', '', '' ),
		array( '_prtf_link', 'Link', 'Link on the site', '', '' ),
		array( '_prtf_short_descr', 'Short description', 'Short description for display in portfolio page', '', '' ),
		array( '_prtf_descr', 'Description', 'Description for the task', 'textarea', '' ),
		array( '_prtf_svn', 'SVN', 'URL of the SVN', '', '' ),
		array( '_prtf_images', 'Image', 'Image for portfolio. One of the image sed as featured image', 'gallery', '' ),
		)
);

if( ! function_exists( 'portfolio_plugin_install' ) ) {
	function portfolio_plugin_install() {
		if ( ! copy(WP_PLUGIN_DIR .'/portfolio/template/portfolio.php', TEMPLATEPATH .'/portfolio.php'))
		{
			add_action( 'admin_notices', create_function( '', "echo 'Error copy template file';" ) );
		}
	}
}

if( ! function_exists( 'portfolio_plugin_uninstall' ) ) {
	function portfolio_plugin_uninstall() {
		if ( ! unlink(TEMPLATEPATH .'/portfolio.php'))
		{
			add_action( 'admin_notices', create_function( '', "echo 'Error delete template file';" ) );
		}
	}
}

// Create post type for portfolio
if( ! function_exists( 'post_type_portfolio' ) ) {
	function post_type_portfolio() {
		register_post_type( 
			'portfolio',
			array( 
				'labels' => array(
					'name' => __( 'Portfolio' ),
					'singular_name' => __( 'Portfolio' ),
					'add_new'				=> __( 'Add New' ),
					'add_new_item'	=> __( 'Add New Portfolio' ),
					'edit'					=> __( 'Edit' ),
					'edit_item'			=> __( 'Edit Portfolio' ),
					'new_item'			=> __( 'New Portfolio' ),
					'view'					=> __( 'View Portfolio' ),
					'view_item'			=> __( 'View Portfolio' ),
					'search_items'	=> __( 'Search Portfolio' ),
					'not_found'			=> __( 'No portfolio found' ),
					'not_found_in_trash' => __( 'No portfolio found in Trash' ),
					'parent'				=> __( 'Parent Portfolio' ),
				),
				'description' => __('Create a Portfolio.'), 
				'public'	=> true,
				'show_ui' => true,
				'publicly_queryable'	=> true,
				'exclude_from_search' => true,
				'menu_position' => 6,
				'hierarchical'	=> FALSE,
				'query_var'			=> true,
				'register_meta_box_cb' => 'init_metaboxes_portfolio',
				'supports' => array (
					'title', //Text input field to create a post title.
					'custom-fields',
					'comments', //Ability to turn on/off comments.
					'revisions', //Allows revisions to be made for your post.
					'author', //Displays a select box for changing the post author.
				)
			)
		);

		// Register style and script files
		wp_register_style( 'portfolioStylesheet', WP_PLUGIN_URL . '/portfolio/css/stylesheet.css' );
		wp_enqueue_style( 'portfolioStylesheet' );
		wp_register_script( 'portfolioScript', WP_PLUGIN_URL . '/portfolio/js/script.js' );
		wp_enqueue_script( 'portfolioScript' );
		wp_enqueue_script( 'datepicker', WP_PLUGIN_URL .'/portfolio/datepicker/datepicker.js', array( 'jquery' ) );  
		wp_enqueue_style( 'datepicker', WP_PLUGIN_URL .'/portfolio/datepicker/datepicker.css' );
		wp_enqueue_script( 'lightbox', WP_PLUGIN_URL .'/portfolio//colorbox/jquery.colorbox-min.js', array( 'jquery' ) ); 
		wp_enqueue_style( 'lightbox', WP_PLUGIN_URL .'/portfolio/colorbox/colorbox.css' );
	}
}



// Create taxonomy for portfolio - Technologies and Executors Profile
if( ! function_exists( 'taxonomy_portfolio' ) ) {
	function taxonomy_portfolio() {		
		register_taxonomy(
			'portfolio_executor_profile',
			'portfolio',
			array(
				'label' => __('Executors Profile'),
				'sort'	=> true,
				'args'	=> array('orderby' => 'term_order'),
				'rewrite' => array('slug' => 'executor_profile'),
				'show_tagcloud' => false
			)
		);

		register_taxonomy( 
			'post_tag', 
			'portfolio', 
			array(
				'hierarchical' => false,
				'update_count_callback' => '_update_post_term_count',
				'label'			=> 'Technologies',
				'query_var' => 'tag',
				'rewrite'		=> did_action( 'init' ) ? 
					array(
						'slug'				=> 'technologies',
						'with_front'	=> false ) : 
					false,
				'public'		=> true,
				'show_ui'		=> true,
				'_builtin'	=> true,
			) 
		);
	}
}

// Create custom permalinks for portfolio post type
if( ! function_exists( 'portfolio_custom_permalinks' ) ) {
	function portfolio_custom_permalinks() {
		global $wp_rewrite;
		$wp_rewrite->add_rule( 'portfolio/page/([^/]+)/?$', 'index.php?pagename=portfolio&page=$matches[1]', 'top' );
		$wp_rewrite->add_rule( 'technologies/([^/]*)/?$', 'index.php?post_type=portfolio&tag=$matches[1]', 'top' );
		$wp_rewrite->add_rule( 'technologies/([^/]+)/page/([0-9]*)/?$', 'index.php?post_type=portfolio&tag=$matches[1]&paged=$matches[2]', 'top' );
    $wp_rewrite->flush_rules();
	}
}

// Initialization of all metaboxes on the 'Add Portfolio' and Edit Portfolio pages
if ( ! function_exists( 'init_metaboxes_portfolio' ) ) {
	function init_metaboxes_portfolio() {
		add_meta_box( 'Portfolio-Info', 'Portfolio Info', 'prtf_post_custom_box', 'portfolio', 'normal', 'high' ); // Description metaboxe
	}
}

// Create custom meta box for portfolio post type
if ( ! function_exists( 'prtf_post_custom_box' ) ) {
	function prtf_post_custom_box( $obj = '', $box = '' ) {
		global $prtf_boxes;
		static $sp_nonce_flag = false;
		// Run once
		if( ! $sp_nonce_flag ) {
			echo_prtf_nonce();
			$sp_nonce_flag = true;
		}
		// Generate box contents
		foreach( $prtf_boxes[ $box[ 'id' ] ] as $prtf_box ) {
			echo field_html( $prtf_box );
		}
		echo "<script type=\"text/javascript\">
			var form = document.getElementById('post');
			form.encoding = 'multipart/form-data';
			form.setAttribute('enctype', 'multipart/form-data');
		</script>";
	}
}

// This switch statement specifies different types of meta boxes
if( ! function_exists( 'field_html' ) ) {
	function field_html ( $args ) {
		switch ( $args[ 3 ] ) {
			case 'textarea':
				return text_area( $args );
			case 'gallery':
				return images_gallery( $args );
			default:
				return text_field( $args );
		}
	}
}

// This is the default text field meta box
if( ! function_exists( 'text_field' ) ) {
	function text_field( $args ) {
		global $post;

		$description	= $args[2];
		$args[ 2 ]		= esc_html ( get_post_meta( $post->ID, $args[0], true ) );
		$label_format =
			'<div class="portfolio_admin_box">'.
			'<p><label for="%1$s"><strong>%2$s</strong></label></p>'.
			'<p><input style="width: 80%%;" type="text" name="%1$s" id="%1$s" value="%3$s" /></p>'.
			'<p><em>'. $description .'</em></p>'.
			'</div>';
		if( '_prtf_date_compl' == $args[0] ) {
			echo '<script type="text/javascript">jQuery(document).ready(function(){jQuery("#_prtf_date_compl").simpleDatepicker({ startdate: 2008, enddate: new Date().getFullYear()+3 });});</script>';
		}
		return vsprintf( $label_format, $args );
	}
}

// This is the text area meta box
if( ! function_exists( 'text_area' ) ) {
	function text_area( $args ) {
		global $post;

		$description	= $args[2];
		$args[2]		= esc_html( get_post_meta( $post->ID, $args[0], true ) );
		$label_format =
			'<div class="portfolio_admin_box">'.
			'<p><label for="%1$s"><strong>%2$s</strong></label></p>'.
			'<p><textarea class="theEditor" style="width: 90%%;" name="%1$s">%3$s</textarea></p>'.
			'<p><em>'. $description .'</em></p>'.
			'</div>';
		return vsprintf( $label_format, $args );
	}
}

// This is the images/media uploader meta box
if( ! function_exists( 'images_gallery' ) ) {
	function images_gallery( $args ) {
		global $post;
		$description	= $args[2];
		$args[2]		= esc_html( get_post_meta( $post->ID, $args[0], true ) );
		$post_ID			= $post->ID;
		$label_format = '<div class="portfolio_admin_box">'.
			'<p><label for="'.$args[0].'"><strong>'.$args[1].'</strong></label></p>'.
			'<iframe frameborder="0" src=" %s " style="width: 100%%; height: 400px;"> </iframe>'.
			'<p><em>'. $description .'</em></p>'.
			'</div>';
		return vsprintf( $label_format, get_upload_iframe_src('media') );
	}
}

// Use nonce for verification ...
if( ! function_exists ( 'echo_prtf_nonce' ) ) {
	function echo_prtf_nonce () {
		echo sprintf(
			'<input type="hidden" name="%1$s" id="%1$s" value="%2$s" />',
			'prtf_nonce_name',
			wp_create_nonce( plugin_basename(__FILE__) )
		);
	}
}

/* When the post is saved, saves our custom data */
if ( ! function_exists ( 'prtf_save_postdata' ) ) {
	function prtf_save_postdata( $post_id, $post ) {
		global $prtf_boxes;
		global $post;

		if( "portfolio" == $post->post_type ) {
			// verify this came from the our screen and with proper authorization,
			// because save_post can be triggered at other times
			if( ! current_user_can ( 'edit_page', $post->ID ) ) {
				return $post->ID;
			} 
			// We'll put it into an array to make it easier to loop though.
			// The data is already in $prtf_boxes, but we need to flatten it out.
			foreach( $prtf_boxes as $prtf_boxe ) {
				foreach( $prtf_boxe as $prtf_fields ) {
					$my_data[ $prtf_fields[0] ] = $_POST[ $prtf_fields[0] ];
				}
			}

			// Add values of $my_data as custom fields
			// Let's cycle through the $my_data array!
			foreach( $my_data as $key => $value ) {
				if( 'revision' == $post->post_type  ) {
					// don't store custom data twice
					return;
				}

				// if $value is an array, make it a CSV (unlikely)
				$value = implode( ',', (array)$value );
				if( get_post_meta( $post->ID, $key, FALSE ) && $value ) {
					// Custom field has a value and this custom field exists in database
					update_post_meta( $post->ID, $key, $value );
				} 
				elseif($value) {
					// Custom field has a value, but this custom field does not exist in database
					add_post_meta( $post->ID, $key, $value );
				}
				else {
					// Custom field does not have a value, but this custom field exists in database
					update_post_meta( $post->ID, $key, $value );
				}
			}
		}
	}
}

//Function for displaying data portfolio post on the frontend
if( ! function_exists ( 'display_portfolio' ) ) {
	function display_portfolio ( $content ) {
		global $post;
		global $display_script;

		global $wp_query;
		if( 'portfolio' == $post->post_type && ! $wp_query->query_vars['tag'] ) {
			$content = "";
			if( is_null( $display_script ) ) {
				$content .= "<script type='text/javascript'>".
				"var base_url = '". WP_PLUGIN_URL ."/portfolio';".
				"jQuery(document).ready(function(){".
						"jQuery('a[rel=\"lightbox\"]').colorbox({transition:'fade'});".
					"});".
				"</script>";
				$display_script = true;
			}
			
			// Get meta value for post
			$meta_values = get_post_custom( $post->ID );		
			
			$thumb			= array();
			$images			= array();
			$upload_dir = wp_upload_dir();
			$image_alt	= "";
			$thumb_url	=	"";
			$featured_image_url = "";
			
			// If isset featured images, display this value 
			if( array_key_exists( '_thumbnail_id', $meta_values ) ) {
				$thumb			= wp_get_attachment_metadata( $meta_values['_thumbnail_id'][0] );
				$thumb_url	= $upload_dir["baseurl"] ."/". substr($thumb['file'], 0, 8) . $thumb['sizes']['medium']['file'];
				$featured_image_url = $upload_dir["baseurl"] ."/". $thumb["file"];
			}
			
			// Display all images from post gallery
			$post_attachments = get_posts( 'post_type=attachment&post_parent='. $post->ID );
			$count = 0;
			foreach($post_attachments as $attachment) {
				$images[$count]['metadata'] = wp_get_attachment_metadata( $attachment->ID );
				$images[$count]['alldata']	= $attachment;
				$count++;
			}
			
			// If not isset featured images, display one image from the gallery
			if( 0 == count( $thumb ) ) {
				if( 0 < count( $images ) ) {
					$thumb_url					= ( isset( $images[0]['metadata']['sizes']["medium"]['file'] ) ? $upload_dir["url"] ."/". $images[0]['metadata']['sizes']["medium"]['file'] : $images[0]['alldata']->guid );
					$featured_image_url = $upload_dir["baseurl"] ."/". $images[0]['metadata']["file"];
					$image_alt					= get_post_custom( $images[0]['alldata']->ID );
					$image_alt					= $image_alt["_wp_attachment_image_alt"][0];
				}
				else {
					$thumb_url					= "";
					$featured_image_url = "";
					$image_alt					= "";
				}
			}
			
			// Create html for display portfolio post
			$content .= '<p><a class="lightbox" rel="lightbox" href="'. $featured_image_url .'"><img src="'. $thumb_url .'" width="240" /></a></p>';
			$content .= '<p><span class="lable">Date of completion</span>: '. $meta_values["_prtf_date_compl"][0] .'</p>';
			$user_id = get_current_user_id();
			if ( 0 == $user_id ) {
				$content .= '<p><span class="lable">Link</span>: '. $meta_values["_prtf_link"][0] .'</p>';
			}
			else {
				if( false !== parse_url( $meta_values["_prtf_link"][0] ) )
					$content .= '<p><span class="lable">Link</span>: <a href="'. $meta_values["_prtf_link"][0] .'">'. $meta_values["_prtf_link"][0] .'</a></p>';
				else
					$content .= '<p><span class="lable">Link</span>: '. $meta_values["_prtf_link"][0] .'</p>';
			}
			$content .= '<p><span class="lable">Description</span>: '. $meta_values["_prtf_descr"][0] .'</p>';
			if ( 0 != $user_id ) {
				$executors_profile = wp_get_object_terms( $post->ID, 'portfolio_executor_profile' );

				$content .= '<p><span class="lable">SVN</span>: '. $meta_values["_prtf_svn"][0] .'</p>';
				$content .= '<p><span class="lable">Executors Profile</span>: ';

				$count = 0;
				foreach($executors_profile as $profile) {
					if($count > 0)
						$content .= ', ';
					$content .= '<a href="'. $profile->description .'" title="'. $profile->name .' profile" target="_blank">'. $profile->name .'</a>';
					$count++;
				}
				$content .= '</p>';
			}
			$content .= '<p class="portfolio_images_block">';
			
			$count = 0;
			// Display images from gallery
			for( $i = 0; $i < count( $images ); $i++ ) {
				$thumb_url = $upload_dir["baseurl"] ."/". substr($thumb['file'], 0, 8) . $images[$i]['metadata']['sizes']["medium"]['file'];
				$image_url = $images[$i]['alldata']->guid;

				if( ! isset( $images[$i]['metadata']['sizes']["medium"]['file'] ) )
					$thumb_url = $image_url;

				$images_alt = get_post_custom( $images[$i]['alldata']->ID );
				$images_alt = $images_alt["_wp_attachment_image_alt"][0];

				if( 0 == $count )
					$content .= "<span class=\"lable\">More screnshots</span>: <div class=\"portfolio_images_rows\">";

				$content .= '<div class="portfolio_images_gallery"><a class="lightbox" rel="lightbox" href="'. $image_url .'" title="'. $images[$i]['alldata']->post_title .'"><img src="'. $thumb_url .'" width="240" alt="'. $images_alt .'" /></a><br />'. $images[$i]['alldata']->post_content .'</div>';
				$count++;

				if( 0 == $count % 3 && 0 != $count ) {
					$content .= '</div><div class="portfolio_images_rows">';
				}
			}
			if( 0 < $count )
				$content .= '</div>';
			$content .= '</p>';
			
			// Display post tag - technologies
			$tags = wp_get_object_terms( $post->ID, 'post_tag' ) ;
			
			if ( $tags ) {
				if( 0 < count( $tags ) )
					$content .= '<div class="portfolio_terms">Technologies: ';
				foreach ( $tags as $tag ) {
					$content .= '<a href="'. get_tag_link( $tag->term_id ). '" title="' . sprintf( __( "View all posts in %s" ), $tag->name ) . '" ' . '>' . $tag->name.'</a>, ';
				}
				$content = substr( $content, 0, strlen( $content ) -2 );
				if( 0 < count ( $tags ) )
					$content .= '</div>';
			}
		}
		if( 'portfolio' == $post->post_type && $wp_query->query_vars['tag'] )
		{
			display_portfolio_term();
		}
		return $content;
	}
}

function display_portfolio_term()
{ 
	global $post;
	?>
	<div class="portfolio_content">
		<div class="entry">
			<?php 
			$meta_values	= get_post_custom( $post->ID );
			
			// Get value for featured images if is set
			$thumb			= array();
			$images			= array();
			$upload_dir = wp_upload_dir();
			$image_alt	= "";
			$thumb_url	=	"";
			$featured_image_url = "";

			if( array_key_exists( '_thumbnail_id', $meta_values ) ) {
				$thumb			= wp_get_attachment_metadata( $meta_values['_thumbnail_id'][0] );
				$thumb_url	= $upload_dir["url"] ."/". $thumb['sizes']['medium']['file'];
				$featured_image_url = $upload_dir["baseurl"] ."/". $thumb["file"];
			}
			
			// If featured images not set, display one image from post's gallery
			$post_attachments = get_posts( 'post_type=attachment&post_parent='. $post->ID .'&numberposts=1' );
			if( 0 == count( $thumb ) ) {
				if( 0 < count( $post_attachments ) ) {
					$metadata = wp_get_attachment_metadata( $post_attachments[0]->ID );
					$thumb_url					= ( isset( $metadata['sizes']["medium"]['file'] ) ? $upload_dir["url"] ."/". $metadata['sizes']["medium"]['file'] : $post_attachments[0]->guid );
					$featured_image_url = $upload_dir["baseurl"] ."/". $metadata["file"];
					$image_alt					= get_post_custom( $post_attachments[0]->ID );
					$image_alt					= $image_alt["_wp_attachment_image_alt"][0];
				}
				else {
					$thumb_url					= "";
					$featured_image_url = "";
					$image_alt					= "";
				}
			}
			
			// Display content
			echo '<p><a class="lightbox" rel="lightbox" href="'. $featured_image_url .'"><img src="'. $thumb_url .'" width="240" alt="'. $image_alt .'" /></a></p>';
			echo '<p><span class="lable">Date of completion</span>: '. $meta_values["_prtf_date_compl"][0] .'</p>';
			$user_id = get_current_user_id();
			if ( 0 == $user_id ) {
				echo '<p><span class="lable">Link</span>: '. $meta_values["_prtf_link"][0] .'</p>';
			}
			else {
				if( false !== parse_url ( $meta_values["_prtf_link"][0] ) )
					echo '<p><span class="lable">Link</span>: <a href="'. $meta_values["_prtf_link"][0] .'">'. $meta_values["_prtf_link"][0] .'</a></p>';
				else
					echo '<p><span class="lable">Link</span>: '. $meta_values["_prtf_link"][0] .'</p>';
			}
			echo '<p><span class="lable">Short description</span>: '. $meta_values["_prtf_short_descr"][0] .'</p>'; ?>
		</div>
		<div class="read_more"><a href="<?php the_permalink() ?>" rel="bookmark">Read more >></a></div>
	</div>
	
	<?php $tags = wp_get_object_terms( $post->ID, 'post_tag' ) ;
	// Add post's tag in page
	if ( $tags ) {
		if( 0 < count( $tags ) ) {
			$content = "";
			$content .= '<div class="portfolio_terms">Technologies: ';
			foreach ( $tags as $tag ) {
				$content .= '<a href="'. get_tag_link( $tag->term_id ). '" title="' . sprintf( __( "View all posts in %s" ), $tag->name ) . '" ' . '>' . $tag->name.'</a>, ';
			}
			$content = substr( $content, 0, strlen( $content ) -2 );
			$content .= '</div>';
		}
	}

	$content .= "<script type='text/javascript'>".
		"var base_url = '". WP_PLUGIN_URL ."/portfolio';".
		"jQuery(document).ready(function(){".
				"jQuery('a[rel=\"lightbox\"]').colorbox({transition:'fade'});".
			"});".
		"</script>";
	echo $content;

}

// Function for display page after choice tag in cloude tags
if( ! function_exists ( 'display_term' ) ) {
	function display_term()	{
		global $wp_query;
		global $paged;
		$paged	= ( $wp_query->query_vars['page'] ) ? $wp_query->query_vars['page'] : 1;
		$args		= array(
			'post_type'					=> 'portfolio',
			'post_status'				=> 'publish',
			'orderby'						=> 'menu_order',
			'caller_get_posts'  => 1,
			'posts_per_page'		=> 5,
			'paged'							=> $paged 
			);
		$args = array_merge( $wp_query->query, $args );
		query_posts( $args );

		//assigning variables to the loop		 
		while ( have_posts() ) : the_post(); 
		global $post;?>
		<div class="portfolio_content">
			<div class="item_title"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></div>
			<div class="entry">
			<?php 
			$meta_values	= get_post_custom( $post->ID );
			
			// Get value for featured images if is set
			$thumb			= array();
			$images			= array();
			$upload_dir = wp_upload_dir();
			$image_alt	= "";
			$thumb_url	=	"";
			$featured_image_url = "";

			if( array_key_exists( '_thumbnail_id', $meta_values ) ) {
				$thumb			= wp_get_attachment_metadata( $meta_values['_thumbnail_id'][0] );
				$thumb_url	= $upload_dir["url"] ."/". $thumb['sizes']['medium']['file'];
				$featured_image_url = $upload_dir["baseurl"] ."/". $thumb["file"];
			}
			
			// If featured images not set, display one image from post's gallery
			$post_attachments = get_posts( 'post_type=attachment&post_parent='. $post->ID .'&numberposts=1' );
			if( 0 == count( $thumb ) ) {
				if( 0 < count( $post_attachments ) ) {
					$metadata = wp_get_attachment_metadata( $post_attachments[0]->ID );
					$thumb_url					= ( isset( $metadata['sizes']["medium"]['file'] ) ? $upload_dir["url"] ."/". $metadata['sizes']["medium"]['file'] : $post_attachments[0]->guid );
					$featured_image_url = $upload_dir["baseurl"] ."/". $metadata["file"];
					$image_alt					= get_post_custom( $post_attachments[0]->ID );
					$image_alt					= $image_alt["_wp_attachment_image_alt"][0];
				}
				else {
					$thumb_url					= "";
					$featured_image_url = "";
					$image_alt					= "";
				}
			}
			
			// Display content
			echo '<p><a class="lightbox" rel="lightbox" href="'. $featured_image_url .'"><img src="'. $thumb_url .'" width="240" alt="'. $image_alt .'" /></a></p>';
			echo '<p><span class="lable">Date of completion</span>: '. $meta_values["_prtf_date_compl"][0] .'</p>';
			$user_id = get_current_user_id();
			if ( 0 == $user_id ) {
				echo '<p><span class="lable">Link</span>: '. $meta_values["_prtf_link"][0] .'</p>';
			}
			else {
				if( false !== parse_url ( $meta_values["_prtf_link"][0] ) )
					echo '<p><span class="lable">Link</span>: <a href="'. $meta_values["_prtf_link"][0] .'">'. $meta_values["_prtf_link"][0] .'</a></p>';
				else
					echo '<p><span class="lable">Link</span>: '. $meta_values["_prtf_link"][0] .'</p>';
			}
			echo '<p><span class="lable">Short description</span>: '. $meta_values["_prtf_short_descr"][0] .'</p>'; ?>
			</div>
			<div class="read_more"><a href="<?php the_permalink() ?>" rel="bookmark">Read more >></a></div>
		</div>
		
		<?php $tags = wp_get_object_terms( $post->ID, 'post_tag' ) ;
		// Add post's tag in page
		if ( $tags ) {
			if( 0 < count( $tags ) ) {
				$content = "";
				$content .= '<div class="portfolio_terms">Technologies: ';
				foreach ( $tags as $tag ) {
					$content .= '<a href="'. get_tag_link( $tag->term_id ). '" title="' . sprintf( __( "View all posts in %s" ), $tag->name ) . '" ' . '>' . $tag->name.'</a>, ';
				}
				$content = substr( $content, 0, strlen( $content ) -2 );
				$content .= '</div>';
				echo $content;
			}
		}
		endwhile; ?>
		<script type="text/javascript">
		var base_url = "<?php echo WP_PLUGIN_URL .'/portfolio'; ?>";
		jQuery(document).ready(function(){
				jQuery('a[rel="lightbox"]').colorbox({transition:'fade'});
			});
		</script>
		<?php 
		// Add pagination
		portfolio_pagination();
	}
}

// This is pagenation functionality for portfolio post type
if( ! function_exists ( 'portfolio_pagination' ) ) {
	function portfolio_pagination( $pages = '', $range = 2 ) {  
		 $showitems = ( $range * 2 )+1;  

		 global $paged;
		 if( empty ( $paged ) ) 
			 $paged = 1;

		 if( '' == $pages ) {
			 global $wp_query;
			 $pages = $wp_query->max_num_pages;
			 if( ! $pages ) {
				 $pages = 1;
			 }
		 }   

		 if( 1 != $pages ) {
			 echo "<div class='pagination'>";
			 if( 2 < $paged && $paged > $range + 1 && $showitems < $pages ) 
				 echo "<a href='". get_pagenum_link( 1 ) ."'>&laquo;</a>";
			 if( 1 < $paged && $showitems < $pages ) 
				 echo "<a href='". get_pagenum_link( $paged - 1 ) ."'>&lsaquo;</a>";

			 for ( $i = 1; $i <= $pages; $i++ ) {
				 if ( 1 != $pages && ( !( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showitems ) ) {
						 echo ( $paged == $i ) ? "<span class='current'>". $i ."</span>":"<a href='". get_pagenum_link($i) ."' class='inactive' >". $i ."</a>";
				 }
			 }

			 if ( $paged < $pages && $showitems < $pages ) 
				 echo "<a href='". get_pagenum_link( $paged + 1 ) ."'>&rsaquo;</a>";  
			 if ( $paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages ) 
				 echo "<a href='". get_pagenum_link( $pages ) ."'>&raquo;</a>";
			 echo "</div>\n";
		 }
	}
}

function portfolio_register_plugin_links($links, $file) {
	$base = plugin_basename(__FILE__);
	if ($file == $base) {
		$links[] = '<a href="http://wordpress.org/extend/plugins/portfolio/faq/" target="_blank">' . __('FAQ','captcha') . '</a>';
		$links[] = '<a href="Mailto:plugin@bestwebsoft.com">' . __('Support','captcha') . '</a>';
	}
	return $links;
}


register_activation_hook( __FILE__, 'portfolio_plugin_install'); // activate plugin
register_deactivation_hook( __FILE__, 'portfolio_plugin_uninstall'); // deactivate plugin

add_action( 'init', 'post_type_portfolio', 1 ); // register post type
add_action( 'init', 'taxonomy_portfolio', 0 ); // register taxonomy for portfolio
add_action( 'init', 'portfolio_custom_permalinks' ); // add custom permalink for portfolio
add_action( 'save_post', 'prtf_save_postdata', 1, 2 ); // save custom data from admin 

//Additional links on the plugin page
add_filter('plugin_row_meta', 'portfolio_register_plugin_links',10,2);

add_filter( 'the_content', 'display_portfolio' );	// display portfolio single post
?>