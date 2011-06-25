				<!--BEGIN .hentry -->
				<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">				

					<!--BEGIN .entry-content -->
					<div class="entry-content clearfix">
						<?php the_content(''); ?>
					<!--END .entry-content -->
					</div>
                    
                    <?php if(!is_singular()) : get_template_part('includes/post-meta'); endif; ?>
                
				<!--END .hentry-->  
				</div>