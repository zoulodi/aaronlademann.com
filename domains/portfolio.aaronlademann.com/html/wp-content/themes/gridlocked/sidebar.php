		<!--BEGIN #sidebar .aside-->
		<div id="sidebar" class="aside">
        
        	<!-- BEGIN #logo -->
			<div id="logo">
				<?php /*
				If "plain text logo" is set in theme options then use text
				if a logo url has been set in theme options then use that
				if none of the above then use the default logo.png */
				if (get_option('tz_plain_logo') == 'true') { ?>
				<a href="<?php echo home_url(); ?>"><?php bloginfo( 'name' ); ?></a>
				<?php } elseif (get_option('tz_logo')) { ?>
				<a href="<?php echo home_url(); ?>" rel="nofollow"><img id="logoimg" src="<?php echo get_option('tz_logo'); ?>" alt="<?php bloginfo( 'name' ); ?>"/></a>
				<?php } else { ?>
				<a href="<?php echo home_url(); ?>" rel="nofollow"><img id="logoimg" src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="<?php bloginfo( 'name' ); ?>" /></a>
				<?php } ?>
                
                <?php $tagline = get_option('tz_tagline'); ?>
                <?php if($tagline != '') : ?>
                
                <p id="tagline"><?php echo stripslashes($tagline); ?></p>
                
                <?php endif; ?>
                
			<!-- END #logo -->
			</div>
            
            <!--<div class="seperator clearfix">
            	<div class="line"></div>
            </div>-->
			
            <?php if(!is_page() && !is_page_template('template-portfolio.php') && !is_tax('skill-type') && get_post_type() != 'portfolio') : ?>
            
						<?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar() ) ?>
            
            
            <?php elseif(is_page() && !is_page_template('template-portfolio.php')) : ?>
            
            <?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar('Page Sidebar') ) ?>
            
            
            <?php elseif(is_page_template('template-portfolio.php') || is_tax('skill-type') || get_post_type() == 'portfolio') :?>
            
            <?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar('Portfolio Sidebar') ) ?> 
            
            
            <?php endif; ?>
            
            <?php if(is_page_template('template-portfolio.php') || is_tax('skill-type') || get_post_type() == 'portfolio') :?>
            <div class="widget">
                <span class="widget-title"><strong>View Options</strong></span>
                <ul id="filter" class="insetBox">
                	<li <?php if(is_page_template('template-portfolio.php')) : ?>class="current-menu-item"<?php endif; ?>>
                  	<?php $full_folio_rel = ( is_home() || is_front_page() ) ? 'rel="nofollow"' : ''; ?>
                    <a href="<?php echo get_permalink( get_option('tz_portfolio_page') ); ?>" <?php echo $full_folio_rel; ?>>Full Portfolio</a>
                  </li>
                  <?php wp_list_categories(array('title_li' => '', 'taxonomy' => 'skill-type', 'depth' => 1)); ?>
                </ul>
            </div>
            <?php endif; ?>
            
            <!-- BEGIN #back-to-top -->
            <div id="back-to-top" class="link insetBox">
            	<!--<a href="#" rel="nofollow">-->
                	<span class="icon"><span class="arrow"></span></span>
                    <span class="text"><?php _e('Back to Top', 'framework'); ?></span>
                <!--</a>--> 
            <!-- END #back-to-top -->
            </div>
		
		<!--END #sidebar .aside-->
		</div>