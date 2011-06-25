				<!--BEGIN .hentry -->
				<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">	
					
                    <?php if(!is_singular()) : ?>
                    
                    <h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php _e('Permalink to:', 'framework');?> <?php the_title(); ?>"><?php the_title(); ?></a></h2>
                    
                    <?php else :?>
                    
                    <h2 class="entry-title"><?php the_title(); ?></h2>
                    
                    <?php endif; ?>

					<!--BEGIN .entry-content -->
					<div class="entry-content">
						<?php the_content(''); ?>
					<!--END .entry-content -->
					</div>
                    
                    <?php if(!is_singular()) : get_template_part('includes/post-meta'); endif; ?>
                
				<!--END .hentry-->  
				</div>