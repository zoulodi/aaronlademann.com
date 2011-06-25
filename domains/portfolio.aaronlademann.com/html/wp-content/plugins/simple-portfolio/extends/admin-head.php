<?php

add_action('admin_head', 'portfolio_admin_head');

/**
* Change the icon on every page where post type is portfolio.
* Also save template paths to vars
*/
function portfolio_admin_head() {
	global $post_type;
	
	$post_type = isset($post_type) ? $post_type : '';
	$_GET['post_type'] = isset($_GET['post_type']) ? $_GET['post_type'] : '';
	$_GET['post'] = isset($_GET['post']) ? $_GET['post'] : '';
	?>
	<style>
	<?php if (($_GET['post_type'] == 'portfolio') || ($post_type == 'portfolio') || (get_post_type($_GET['post']) == 'portfolio')) : ?>
	#icon-edit, #icon-post { 
		background:transparent url('<?php echo WP_PLUGIN_URL . '/simple-portfolio/images/icon.png'; ?>') no-repeat; 
		background-position: -4px -7px;
		height: 45px;
		width: 45px;
	}
	<?php endif; ?>
	</style>
	
	<script type="text/javascript">
		var media_html_path = "<?php echo WP_PLUGIN_URL . '/simple-portfolio/media-html/'; ?>";
	</script>
	<?php
}

?>