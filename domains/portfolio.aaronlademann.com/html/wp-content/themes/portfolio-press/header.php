<?php
/**
 * @package WordPress
 * @subpackage Portfolio Press
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />

<?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/html5.js"></script>
<![endif]-->

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="wrapper">
	<header id="branding">
    	<div id="masthead" class="col-width">
      
      <?php $heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'div'; ?>
				<<?php echo $heading_tag; ?> class="heading">
					<?php echo bloginfo( 'name' ) ?>
        </<?php echo $heading_tag; ?>>  
        <?php if ( !of_get_option('logo', false) ) { ?>
          <h2 id="site-description"><?php bloginfo( 'description' ); ?></h2>
        <?php } ?>
        
			<hgroup id="logo">
      	<?php if ( of_get_option('logo', false) ) { ?>
					<a id="mastlogo" href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
          	<img src="<?php echo of_get_option('logo'); ?>" alt="<?php echo bloginfo( 'name' ) ?>" />
          </a>
				<?php } else {
					bloginfo( 'name' );
				}?>
			</hgroup>
      
      <nav id="topNav" class="nav">

        <div class="skip-link screen-reader-text"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'portfoliopress' ); ?>"><?php _e( 'Skip to content', 'portfoliopress' ); ?></a></div>
    		
        <?php wp_nav_menu( array('theme_location' => 'primary', 'container' => '')); ?>
        <!--<nav class="subnav"><php wp_nav_menu( array('theme_location' => 'tertiary', 'container' => '')); ?></nav>-->
      </nav><!-- #access -->
    
    </div>
    
	</header><!-- #branding -->

	<div id="main">
    	<div class="col-width">