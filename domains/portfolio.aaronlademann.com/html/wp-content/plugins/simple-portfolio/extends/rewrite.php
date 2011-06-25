<?php

/**
 * Define the rewrite rules.
 * [slug]/ redirect to index.php?post_type=portfolio
 * [slug].xml redirect to index.php?post_type=portfolio&portfolio_data=xml
 */
add_filter('rewrite_rules_array','portfolio_rewrite_rules_array');

function portfolio_rewrite_rules_array($rules) {
	$info = get_post_type_object('portfolio');

	$newrules = array();
	$newrules[$info->rewrite['slug'].'/?$'] = 'index.php?post_type='.$info->name;
	$newrules[$info->rewrite['slug'].'/page/?([0-9]{1,})/?$'] = 'index.php?post_type='.$info->name.'&paged=$matches[1]';
	
	if (get_option('use-xml') != '0')
		$newrules[$info->rewrite['slug'].'.xml/?$'] =  'index.php?post_type='.$info->name.'&portfolio_data=xml';
	
	return $newrules + $rules;
}


/**
 * Enable the portfolio_data query variable for xml data
 */
add_filter('query_vars','portfolio_query_vars');

function portfolio_query_vars($vars) {
	array_push($vars, 'portfolio_data');
	return $vars;
}


/**
 * Redirect the template to portfolio.php or show the xml data
 * @see http://codex.wordpress.org/Template_Hierarchy
 */
add_action( 'template_redirect', 'portfolio_template_redirect' );

function portfolio_template_redirect() {
	global $wp_query;
	
	// intercept xml data to display
	if ($wp_query->get('portfolio_data') == 'xml'):
		include('xml.php');
		die();
	endif;
	
	$info = get_post_type_object('portfolio');
	
	if (isset($wp_query->query_vars)):
		$post_type = isset($wp_query->query_vars) ? $wp_query->query_vars['post_type'] : '';
		if (!is_robots() && !is_feed() && !is_trackback() && !is_single() && $post_type == 'portfolio'):
			$wp_query->is_home = false;
			$wp_query->is_custom_post_type_archive = true;
			locate_template( array( "portfolio.php", "index.php" ), true );
			die();
		endif;
	endif;
}


/**
 * Flush the rewrite rules
 * Refresh them in htaccess
 */
add_action('admin_init', 'portfolio_flush_rewrite');
register_activation_hook( __FILE__, 'portfolio_flush_rewrite' );
register_deactivation_hook( __FILE__, 'portfolio_flush_rewrite' );

function portfolio_flush_rewrite() {
	global $wp_rewrite;
	$wp_rewrite->flush_rules();
}


?>