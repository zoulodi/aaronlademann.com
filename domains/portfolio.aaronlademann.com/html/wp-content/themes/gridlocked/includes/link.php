				<!--BEGIN .hentry -->
				<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
                
                	<?php $url =  get_post_meta(get_the_ID(), 'tz_link_url', true); ?>
                
                    <h2 class="entry-title">
                    	<span class="icon"></span>
                    	<a target="_blank" href="<?php echo $url; ?>" title="<?php _e('Permalink to:', 'framework');?> <?php echo $url; ?>">
							<?php the_title(); ?>
                        </a>
                    </h2>
                    
                    <span class="arrow"></span>

					<!--BEGIN .entry-content -->
					<div class="entry-content">
						<?php the_content(''); ?>
					<!--END .entry-content -->
					</div>
                    
                    <?php if(!is_singular()) : get_template_part('includes/post-meta'); endif; ?>
                
				<!--END .hentry-->  
				</div>