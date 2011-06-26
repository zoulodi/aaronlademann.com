<!DOCTYPE html>

<!-- BEGIN html -->
<html <?php language_attributes(); ?>>

<!-- BEGIN head -->
<head>
  <!-- aaronl: custom | IE Compliance Mode Control -->
  <meta http-equiv="X-UA-Compatible" content="IE=9" />
  <!-- Meta Tags -->
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	
	<!-- Title -->
	<title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>
	
	<!-- Stylesheets -->
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/<?php echo get_option('tz_alt_stylesheet'); ?>" type="text/css" media="screen" />
  <link rel="stylesheet" type="text/css" media="screen" href="http://aaronlademann.com/_includes/_css/template.css" />
	<!-- RSS & Pingbacks -->
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo( 'name' ); ?> RSS Feed" href="<?php if (get_option('tz_feedburner')) { echo get_option('tz_feedburner'); } else { bloginfo( 'rss2_url' ); } ?>" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />  
  <?php
  $browserAsString = $_SERVER['HTTP_USER_AGENT'];
  if (strstr($browserAsString, " AppleWebKit/") && strstr($browserAsString, " Mobile/")) { 
		// set false for now
		//$is_ios = false;
		$is_ios = true; 
		if ( !is_single() ) {
		?>
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; minimum-scale=1.0;">
  <link rel="stylesheet" type="text/css" media="screen" href="http://aaronlademann.com/_includes/_css/iOS.css" />    
	<?php }} ?>
	<!-- Theme Hook -->
	<?php wp_head(); ?>

<!-- END head -->
</head>

<!-- BEGIN body -->
<body <?php body_class(); ?>>
    
	<!--BEGIN #bg-line-->
    <div id="bg-line"></div>

    <!--BEGIN aaronlademann.com primary navigation-->
      <div id="masthead">
          
        <div id="topNav" class="nav">
  
          <?php wp_nav_menu( array('theme_location' => 'primary', 'container' => '')); ?>
          
        </div>
          
        <div id="logoContainer">
            <a id="mastlogo" href="http://aaronlademann.com/" title="AaronLademann.com" rel="nofollow">
              <img src="http://aaronlademann.com/_images/_template/masthead-aaronlademann.com-logo.png" width="294" height="52" alt="<?php echo bloginfo( 'name' ) ?>" />
            </a> 
  
        </div>
      
      </div>
    <!--END aaronlademann.com primary navigation--> 

        <!-- BEGIN #container -->
        <div id="container" class="clearfix js-disabled">
    
            <!--BEGIN #content -->
            <div id="content">
            	
                <?php if(get_option('tz_widget_overlay') == 'true') : ?>
                
            	<!--BEGIN #widget-overlay -->
                 <div id="widget-overlay-container">
            
                     <!--BEGIN #widget-overlay -->
                     <div id="widget-overlay">
                        
                         <!--BEGIN #overlay-inner -->
                         <div id="overlay-inner" class="clearfix">
                         
                         	<div class="column">
                            <?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar('Overlay Column 1') ) ?>
                            </div>
                            <div class="column">
                            <?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar('Overlay Column 2') ) ?>
                            </div>
                            <div class="column">
                            <?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar('Overlay Column 3') ) ?>
                            </div>
                            <div class="column">
                            <?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar('Overlay Column 4') ) ?>
                            </div>
                         
                         <!--END #overlay-inner -->
                         </div>
    
                      <!--END #widget-overlay -->
                     </div>
                     
                     <div id="overlay-open"><a href="#"><?php _e('Open Widget Area', 'framework'); ?></a></div>
                 
                 <!--END #widget-overlay-container -->
                 </div>
                 
                 <?php endif; ?>
        
<?php get_sidebar(); ?>
		