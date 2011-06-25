<?php

add_action('save_post', 'portfolio_save_post');


function portfolio_save_post($post_id) {
	if (!isset($_POST['simple_portfolio_nonce'])) return $post_id;
	if ( !wp_verify_nonce( $_POST['simple_portfolio_nonce'], plugin_basename(WP_PLUGIN_URL . '/simple-portfolio/simple-portfolio.php') )) return $post_id;
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $post_id;	

	// Check permissions
	if ( 'page' == $_POST['post_type'] ):
		if ( !current_user_can( 'edit_page', $post_id ) )
			return $post_id;
	else
		if ( !current_user_can( 'edit_post', $post_id ) )
			return $post_id;
	endif;
	
	
	// OK, WE'RE AUTHENTICATED !!!
	
	
	// Safe the General information
	foreach (get_option_preformatted() as $key=>$value):
		update_post_meta($post_id, 'portfolio_' . $key, $_POST['portfolio_info_' . $key]);
	endforeach;
	
	// Save media
	$media_count = 0;
	$media = array();
	
	$_POST = stripslashes_deep($_POST);
	foreach ($_POST as $key=>$value) {
		if (preg_match("/(portfolio_media_type)/", $key, $matches)) {
			$media_count++;
			$media[] = array(
				"type"		=> $_POST['portfolio_media_type_' . $media_count],
				"value"		=> $_POST['portfolio_media_value_' . $media_count]
			);
		}
	}

	// delete all post entries
	delete_post_meta( $post_id, 'portfolio_media' );
	
	// save serialized and encoded media data
	$serialized_media = base64_encode( serialize($media) );
	add_post_meta( $post_id, 'portfolio_media',  $serialized_media);
}

?>