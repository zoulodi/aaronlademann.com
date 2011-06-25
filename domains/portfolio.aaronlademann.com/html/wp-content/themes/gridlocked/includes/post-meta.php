    <!--BEGIN .entry-meta .entry-header-->
    <ul class="entry-meta entry-header clearfix">
    
    <li class="published">
        <a href="<?php the_permalink(); ?>" title="<?php _e('Permalink to:', 'framework');?> <?php the_title(); ?>">
            <span class="icon"></span>
            <?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) .' '. __('ago', 'framework'); ?>
        </a>
    </li>
    
    <?php if(!is_singular()) : ?>
    <li class="like-count">
        <?php tz_printLikes(get_the_ID()); ?>
    </li>
    <?php endif; ?>
    
    <li class="comment-count">
        <?php comments_popup_link(__('<span class="icon"></span> 0', 'framework'), __('<span class="icon"></span> 1', 'framework'), __('<span class="icon"></span> %', 'framework')); ?>
    </li>
    
    <?php if(is_singular()) : ?>
    <li class="like-count">
        <?php tz_printLikes(get_the_ID()); ?>
    </li>
    <?php endif; ?>
    
    <?php edit_post_link( __('[Edit]', 'framework'), '<li class="edit-post">', '</li>' ); ?>
    
    <!--END .entry-meta entry-header -->
    </ul>
    
    <?php if(is_singular()) : ?>
    
    <div class="seperator clearfix">
        <div class="line"></div>
    </div>
	
    <?php if(has_tag()) : ?>
    
    <h3 class="widget-title"><?php _e('Tags', 'framework'); ?></h3>
    
    <?php the_tags('<ul><li>','</li><li>','</li></ul>'); ?>
    
    <div class="seperator clearfix">
        <div class="line"></div>
    </div>
    
	<?php endif; ?>
	
    <?php if(is_single()) : ?>
    <!--BEGIN .navigation .single-page-navigation -->
    <div class="navigation single-page-navigation clearfix">
        <div class="nav-previous"><?php next_post_link(__('%link', 'framework'), '<span class="arrow">%title</span>') ?></div>
        <div class="nav-next"><?php previous_post_link(__('%link', 'framework'), '<span class="arrow">%title</span>') ?></div>
    <!--END .navigation .single-page-navigation -->
    </div>
    <?php endif; ?>
    
    <?php endif; ?>