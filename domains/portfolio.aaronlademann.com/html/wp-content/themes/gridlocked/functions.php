<?php

/*-----------------------------------------------------------------------------------

	Here we have all the custom functions for the theme
	Please be extremely cautious editing this file,
	When things go wrong, they tend to go wrong in a big way.
	You have been warned!

-----------------------------------------------------------------------------------*/

/**
 * This theme uses wp_nav_menu() in two locations.
 */
register_nav_menus( array(
	'primary' => __( 'Header Menu', 'gridlocked' ),
	'secondary' => __( 'Footer Menu', 'gridlocked' ),
) );

/*-----------------------------------------------------------------------------------*/
/*	Exclude pages from search
/*-----------------------------------------------------------------------------------*/


function tz_exclude_pages($query) {
        if ($query->is_search) {
        $query->set('post_type', 'post');
                                }
        return $query;
}
add_filter('pre_get_posts','tz_exclude_pages');


/*-----------------------------------------------------------------------------------*/
/*	Load Translation Text Domain
/*-----------------------------------------------------------------------------------*/

load_theme_textdomain ('framework');


/*-----------------------------------------------------------------------------------*/
/*	Register Sidebars
/*-----------------------------------------------------------------------------------*/

if ( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'name' => 'Main Sidebar',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div><div class="seperator clearfix"><div class="line"></div></div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	));
	register_sidebar(array(
		'name' => 'Page Sidebar',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div><div class="seperator clearfix"><div class="line"></div></div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	));
	register_sidebar(array(
		'name' => 'Portfolio Sidebar',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div><div class="seperator clearfix"><div class="line"></div></div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	));
	register_sidebar(array(
		'name' => 'Overlay Column 1',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	));
	register_sidebar(array(
		'name' => 'Overlay Column 2',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	));
	register_sidebar(array(
		'name' => 'Overlay Column 3',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	));
	register_sidebar(array(
		'name' => 'Overlay Column 4',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	));
}


/*-----------------------------------------------------------------------------------*/
/*	Post Formats
/*-----------------------------------------------------------------------------------*/

$formats = array( 
			'aside', 
			'gallery', 
			'link', 
			'image', 
			'quote', 
			'audio',
			'video');

add_theme_support( 'post-formats', $formats ); 

add_post_type_support( 'post', 'post-formats' );


/*-----------------------------------------------------------------------------------*/
/*	Configure WP2.9+ Thumbnails
/*-----------------------------------------------------------------------------------*/

if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 50, 50, true ); // Normal post thumbnails
	add_image_size( 'large', 680, '', true ); // Large thumbnails
	add_image_size( 'medium', 250, '', true ); // Medium thumbnails
	add_image_size( 'small', 125, '', true ); // Small thumbnails
	add_image_size( 'archive-thumb', 360, '', true ); // Thumbnails that appear on any archive like page
	add_image_size( 'single-thumb', 550, '', true ); // Thumbnails that appear on any single page
	add_image_size( 'portfolio-thumb', 230, 170, true ); // Thumbnails that appear on any single page
	add_image_size( 'gallery-format-thumb', 360, 270, true ); // Thumbnails that appear on gallery formats
	add_image_size( 'fullsize', '', '', true ); // Fullsize
}


/*-----------------------------------------------------------------------------------*/
/*	Custom Gravatar Support
/*-----------------------------------------------------------------------------------*/

function tz_custom_gravatar( $avatar_defaults ) {
    $tz_avatar = get_template_directory_uri() . '/images/gravatar.png';
    $avatar_defaults[$tz_avatar] = 'Custom Gravatar (/images/gravatar.png)';
    return $avatar_defaults;
}
add_filter( 'avatar_defaults', 'tz_custom_gravatar' );


/*-----------------------------------------------------------------------------------*/
/*	Change Default Excerpt Length
/*-----------------------------------------------------------------------------------*/

function tz_excerpt_length($length) {
return 17; }
add_filter('excerpt_length', 'tz_excerpt_length');


/*-----------------------------------------------------------------------------------*/
/*	Configure Excerpt String
/*-----------------------------------------------------------------------------------*/

function tz_excerpt_more($excerpt) {
return str_replace('[...]', '...', $excerpt); }
add_filter('wp_trim_excerpt', 'tz_excerpt_more');


/*-----------------------------------------------------------------------------------*/
/*	Register and load common JS
/*-----------------------------------------------------------------------------------*/

function tz_register_js() {
	if (!is_admin()) {
		// comment out the next two lines to load the local copy of jQuery
		//wp_deregister_script('jquery');
		//wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js');
		wp_register_script('tz_custom', get_template_directory_uri() . '/js/jquery.custom.js', 'jquery', '1.0', TRUE); 
		wp_register_script('jquery-ui-custom', get_template_directory_uri() . '/js/jquery-ui-1.8.5.custom.min.js', 'jquery');
		wp_register_script('tz_shortcodes', get_template_directory_uri() . '/js/jquery.shortcodes.js', 'jquery');
// aaronl: custom
		wp_register_script('galleria', '/wp-content/plugins/galleria/galleria-1.2.4.min.js', 'jquery');	 	

		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-custom');
		wp_enqueue_script('tz_shortcodes');
		wp_enqueue_script('tz_custom');
// aaronl: custom		
		wp_enqueue_script('galleria');		
		
	}
}
add_action('init', 'tz_register_js');


/*-----------------------------------------------------------------------------------*/
/*	Register and load admin javascript
/*-----------------------------------------------------------------------------------*/

function tz_admin_js($hook) {
	if ($hook == 'post.php' || $hook == 'post-new.php') {
		wp_register_script('tz-admin', get_template_directory_uri() . '/js/jquery.custom.admin.js', 'jquery');
		wp_enqueue_script('tz-admin');
	}
}
add_action('admin_enqueue_scripts','tz_admin_js',10,1);


/*-----------------------------------------------------------------------------------*/
/*	Load contact template javascript
/*-----------------------------------------------------------------------------------*/

function tz_contact_js() {
	if (is_page_template('template-contact.php') ) 
		wp_register_script('validation', 'http://ajax.microsoft.com/ajax/jquery.validate/1.7/jquery.validate.min.js', 'jquery');
		wp_enqueue_script('validation');
}
add_action('wp_print_scripts', 'tz_contact_js');


/*-----------------------------------------------------------------------------------*/
/* Load scripts for single pages
/*-----------------------------------------------------------------------------------*/

function tz_single_scripts() {
	
	if(is_singular()) 
		
		wp_register_script('easing', get_template_directory_uri().'/js/jquery.easing.1.3.js', 'jquery');
		wp_enqueue_script( 'comment-reply' );
		wp_enqueue_script( 'easing' );
		
	if(is_singular() && has_post_format('image'))
	
		wp_register_script('fancybox', get_template_directory_uri().'/js/jquery.fancybox-1.3.4.pack.js', 'jquery');
		wp_enqueue_script( 'fancybox' );
		
	if(is_singular() && has_post_format('gallery') || get_post_type() == 'portfolio')
	
		wp_register_script('slidesjs', get_template_directory_uri().'/js/slides.min.jquery.js', 'jquery');
		wp_enqueue_script( 'slidesjs' );
		
		wp_register_script('jPlayer', get_template_directory_uri().'/js/jquery.jplayer.min.js', 'jquery');
		wp_enqueue_script( 'jPlayer' );
		
	if(is_singular() && has_post_format('video') || has_post_format('audio') )
	
		wp_register_script('jPlayer', get_template_directory_uri().'/js/jquery.jplayer.min.js', 'jquery');
		wp_enqueue_script( 'jPlayer' );
		
	// aaronl: custom
  $browserAsString = $_SERVER['HTTP_USER_AGENT'];
  if (strstr($browserAsString, " AppleWebKit/") && strstr($browserAsString, " Mobile/")) { 
			wp_register_script('iOS', 'http://aaronlademann.com/_includes/_js/iOSfixedPosition.js', 'jquery');
			wp_enqueue_script( 'iOS' );
	}
		
	
}
add_action('wp_print_scripts', 'tz_single_scripts');


/*-----------------------------------------------------------------------------------*/
/*	Scripts for blog pages
/*-----------------------------------------------------------------------------------*/

function tz_non_singular_scripts() {
	if(!is_singular())
	
		wp_register_script('slidesjs', get_template_directory_uri().'/js/slides.min.jquery.js', 'jquery');
		wp_register_script('masonry', get_template_directory_uri().'/js/jquery.masonry.min.js', 'jquery');
		wp_register_script('fancybox', get_template_directory_uri().'/js/jquery.fancybox-1.3.4.pack.js', 'jquery');
		wp_register_script('easing', get_template_directory_uri().'/js/jquery.easing.1.3.js', 'jquery');
		wp_register_script('jPlayer', get_template_directory_uri().'/js/jquery.jplayer.min.js', 'jquery');
		
		wp_enqueue_script( 'masonry' );
		wp_enqueue_script( 'slidesjs' );
		wp_enqueue_script( 'fancybox' );
		wp_enqueue_script( 'jPlayer' );
		wp_enqueue_script( 'easing' );
		
	if(is_page_template('template-portfolio.php'))
		wp_register_script('masonry', get_template_directory_uri().'/js/jquery.masonry.min.js', 'jquery');
		wp_enqueue_script( 'masonry' );
		
	// aaronl: custom
	$browserAsString = $_SERVER['HTTP_USER_AGENT'];
  if (strstr($browserAsString, " AppleWebKit/") && strstr($browserAsString, " Mobile/")) { 
			wp_register_script('iOS', 'http://aaronlademann.com/_includes/_js/iOSfixedPosition.js', 'jquery');
			wp_enqueue_script( 'iOS' );
	}
		
}
add_action('wp_print_scripts', 'tz_non_singular_scripts');


/*-----------------------------------------------------------------------------------*/
/*	Load stylesheets if needed
/*-----------------------------------------------------------------------------------*/

function tz_styles() {
	// aaronl: custom
		wp_register_style( 'fancybox', get_template_directory_uri() . '/css/fancybox/jquery.fancybox-1.3.4.css' );
		
		wp_enqueue_style( 'fancybox' );
		wp_enqueue_style( 'jPlayer' );
		
}
add_action('wp_print_styles', 'tz_styles');


/*-----------------------------------------------------------------------------------*/
/*	Add Browser Detection Body Class
/*-----------------------------------------------------------------------------------*/

add_filter('body_class','tz_browser_body_class');
function tz_browser_body_class($classes) {
	global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;

	if($is_lynx) $classes[] = 'lynx';
	elseif($is_gecko) $classes[] = 'gecko';
	elseif($is_opera) $classes[] = 'opera';
	elseif($is_NS4) $classes[] = 'ns4';
	elseif($is_safari) $classes[] = 'safari';
	elseif($is_chrome) $classes[] = 'chrome';
	elseif($is_IE) $classes[] = 'ie';
	else $classes[] = 'unknown';

	if($is_iphone) $classes[] = 'iphone';
	
	$classes[] = 'aafolio';
	return $classes;
}


/*-----------------------------------------------------------------------------------*/
/*	Comment Styling
/*-----------------------------------------------------------------------------------*/

function tz_comment($comment, $args, $depth) {

    $isByAuthor = false;

    if($comment->comment_author_email == get_the_author_meta('email')) {
        $isByAuthor = true;
    }

    $GLOBALS['comment'] = $comment; ?>
   <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
     
     <div id="comment-<?php comment_ID(); ?>" class="comment-body clearfix">
     
      <?php echo get_avatar($comment,$size='35'); ?>
      
      <div class="comment-author vcard">
         <?php printf(__('%s'), get_comment_author_link()) ?> 
		 <?php if($isByAuthor) { ?><span class="author-tag"><?php _e('(Author)','framework') ?></span><?php } ?>
      </div>

      <div class="comment-meta commentmetadata">
	  	<?php printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?>
		<?php edit_comment_link(__('(Edit)'),'  ','') ?> &middot; 
		<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
      </div>
      
      <div class="comment-inner">
      
	  	<?php if ($comment->comment_approved == '0') : ?>
         <em class="moderation"><?php _e('Your comment is awaiting moderation.') ?></em>
         <br />
      	<?php endif; ?>
  
   		<?php comment_text() ?>
        
      </div>
      
     </div>

<?php
}


/*-----------------------------------------------------------------------------------*/
/*	Seperated Pings Styling
/*-----------------------------------------------------------------------------------*/

function tz_list_pings($comment, $args, $depth) {
       $GLOBALS['comment'] = $comment; ?>
<li id="comment-<?php comment_ID(); ?>"><?php comment_author_link(); ?>
<?php }


/*-----------------------------------------------------------------------------------*/
/*	Custom Login Logo Support
/*-----------------------------------------------------------------------------------*/

function tz_custom_login_logo() {
    echo '<style type="text/css">
        h1 a { background-image:url('.get_template_directory_uri().'/images/custom-login-logo.png) !important; }
    </style>';
}
function tz_wp_login_url() {
echo home_url();
}
function tz_wp_login_title() {
echo get_option('blogname');
}

add_action('login_head', 'tz_custom_login_logo');
add_filter('login_headerurl', 'tz_wp_login_url');
add_filter('login_headertitle', 'tz_wp_login_title');


/*-----------------------------------------------------------------------------------*/
/*	Load Widgets & Shortcodes
/*-----------------------------------------------------------------------------------*/

// Add the Theme Shortcodes
include("functions/theme-shortcodes.php");

// Add the Flickr Widget
include("functions/widget-flickr.php");

// Add the Twitter Widget
include("functions/widget-tweets.php");

// Add the tinymce button
include("functions/tinymce/tinymce.php");

// Add the post meta
include("functions/theme-postmeta.php");

// Add the post meta
include("functions/theme-portfoliometa.php");

// Add the post types
include("functions/theme-posttypes.php");

// Add the post types
include("functions/theme-likethis.php");


/*-----------------------------------------------------------------------------------*/
/*	Filters that allow shortcodes in Text Widgets
/*-----------------------------------------------------------------------------------*/

add_filter('widget_text', 'shortcode_unautop');
add_filter('widget_text', 'do_shortcode');


/*-----------------------------------------------------------------------------------*/
/*	Load Theme Options
/*-----------------------------------------------------------------------------------*/

define('TZ_FILEPATH', TEMPLATEPATH);
define('TZ_DIRECTORY', get_template_directory_uri());

require_once (TZ_FILEPATH . '/admin/admin-functions.php');
require_once (TZ_FILEPATH . '/admin/admin-interface.php');
require_once (TZ_FILEPATH . '/admin/theme-options.php');
require_once (TZ_FILEPATH . '/admin/theme-functions.php');

?>