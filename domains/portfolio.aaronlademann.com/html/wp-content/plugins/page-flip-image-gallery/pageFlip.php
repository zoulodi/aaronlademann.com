<?php
/*
Plugin Name: WordPress FlippingBook
Plugin URI: http://pageflipgallery.com/
Description: FlippingBook photo gallery plugin with page flip effects.
Author: FlippingBook WP Team
Author URI: http://pageflipgallery.com/
Version: 0.5.11.5
*/


if ( preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF']) ) { die('You are not allowed to call this page directly.'); }

define( 'PAGEFLIP_VERSION', '0.5.11.5' );

define( 'PAGEFLIP_DIR', str_replace("\\", '/', dirname(__FILE__)) );
$path = explode('/', PAGEFLIP_DIR);
define( 'PAGEFLIP_DIRNAME', $path[count($path)-1] );
define( 'PAGEFLIP_URL', WP_PLUGIN_URL.'/'.str_replace('/'.basename(__FILE__), '', plugin_basename(__FILE__)) );

global $wpdb;

$pageFlip_class_php = 'pageFlip.class.php';
if ( !file_exists($pageFlip_class_php) ) $pageFlip_class_php = dirname(__FILE__) . '/pageFlip.class.php';
include_once $pageFlip_class_php;


$pageFlip = new pageFlip_plugin_base;

$path_to_php_file_plugin = $pageFlip->plugin_dir . '/pageFlip.php';


add_action( 'init', array($pageFlip, 'init_textdomain') );


add_action( 'init', array($pageFlip, 'pageFlipWidgetRegister') );

add_action( 'admin_init', 'session_start' );
add_action( 'admin_print_scripts', array($pageFlip, 'adminScripts') );

$pageFlip->init();
$pageFlip->html->main = &$pageFlip;
$pageFlip->functions->main = &$pageFlip;


@set_error_handler( array( $pageFlip->functions, 'myErrorHandler' ), 2039 ); 
add_action('admin_menu', array($pageFlip, 'add_admin_menu'));

add_action('deactivate_' . $path_to_php_file_plugin, array($pageFlip, 'deactivate'));
add_action('activate_' . $path_to_php_file_plugin, array($pageFlip, 'activate'));

register_activation_hook(__FILE__, array( $pageFlip, 'activate' ));

add_shortcode('book', array($pageFlip, 'replaceBooks'));

remove_filter('the_content', 'do_shortcode', 11);
add_filter('the_content', 'do_shortcode', 13);		



if( !empty($_POST['feAction']) )
	$pageFlip->flashEditor( $_POST['feAction'], false );


add_action( 'wp_ajax_pages_list', array( $pageFlip, 'pagesList' ) );
add_action( 'wp_ajax_drop_pages_list', array( $pageFlip, 'replacePages' ) );
add_action( 'wp_ajax_delete_page', array( $pageFlip, 'delete_page' ) );
add_action( 'wp_ajax_refresh_book_preview', array( $pageFlip, 'bookPreview' ) );
add_action( 'wp_ajax_upload_form', array( $pageFlip, 'uploadForm' ) );
add_action( 'wp_ajax_go_to_page', array( $pageFlip, 'pagingImages' ) );
add_action( 'wp_ajax_img_per_page', array( $pageFlip, 'setImgPerPage' ) );
add_action( 'wp_ajax_delete_image', array( $pageFlip, 'delete_image' ) );
add_action( 'wp_ajax_split_image', array( $pageFlip, 'splitImage' ) );
add_action( 'wp_ajax_merge_image', array( $pageFlip, 'mergeImage' ) );
add_action( 'wp_ajax_delete_images', array( $pageFlip, 'deleteImages' ) );
add_action( 'wp_ajax_add_page_form', array( $pageFlip, 'addPageForm' ) );
add_action( 'wp_ajax_add_page_menu', array( $pageFlip, 'addPageMenu' ) );
add_action( 'wp_ajax_add_gallery', array( $pageFlip, 'addGallery' ) );
add_action( 'wp_ajax_view_galleries', array( $pageFlip, 'galleriesList' ) );
add_action( 'wp_ajax_images_list', array( $pageFlip, 'images_list' ) );
add_action( 'wp_ajax_move_image_to', array( $pageFlip, 'moveImgTo' ) );
add_action( 'wp_ajax_move_images_to', array( $pageFlip, 'moveImgsTo' ) );
add_action( 'wp_ajax_delete_gallery', array( $pageFlip, 'deleteGallery' ) );
add_action( 'wp_ajax_sort_book', array( $pageFlip, 'sortBook' ) );


add_filter( 'mce_buttons_3', array($pageFlip, 'mce_buttons') );
add_filter( 'mce_external_plugins', array($pageFlip, 'mce_external_plugins') );


?>