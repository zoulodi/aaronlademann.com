<?php 
if ( !is_file('wp-load.php') )
{
	preg_match('|^(.*?/)(wp-content)/|i', str_replace('\\', '/', __FILE__), $_m);
	require_once $_m[1].'wp-load.php';
}
else
	require_once 'wp-load.php';

require_once ABSPATH.'wp-admin/admin.php';
if ( !current_user_can('install_plugins') )
	wp_die(__('You do not have sufficient permissions to install plugins on this blog.'));


$res = $wpdb->query("ALTER TABLE `{$wpdb->prefix}pageflip` AUTO_INCREMENT = 1") !== false;
$res = $res && $wpdb->query("ALTER TABLE `{$wpdb->prefix}pageflip_gallery` AUTO_INCREMENT = 1") !== false;
$res = $res && $wpdb->query("ALTER TABLE `{$wpdb->prefix}pageflip_img` AUTO_INCREMENT = 1") !== false;

if ($res)
	echo "OK";


?>