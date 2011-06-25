				<!--BEGIN .hentry -->
				<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">	
                
                	<?php /* if the post has a WP 2.9+ Thumbnail */
					if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ): ?>
                    
                    <?php 
					$lightbox = get_post_meta(get_the_ID(), 'tz_image_lightbox', TRUE); 
					
					if($lightbox == 'yes') {
						$lightbox = TRUE;
					} else {
						$lightbox = FALSE;
					}
						
					?>
                    <?php  $src = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), array( '9999','9999' ), false, '' ); ?>
                    
					<div class="post-thumb clearfix">
                    
						<?php if($lightbox) : ?>
                            <a class="lightbox" title="<?php the_title(); ?>" href="<?php echo $src[0]; ?>">
                                <span class="overlay">
                                    <span class="icon"></span>
                                </span>
                                
                                <?php if(!is_singular()) : ?>
                                <?php the_post_thumbnail('archive-thumb'); ?>
                                <?php else: ?>
                                <?php the_post_thumbnail('single-thumb'); ?>
                                <?php endif; ?>
                            </a>
                        <?php else: ?>
                        
							<?php if(!is_singular()) : ?>
                            <?php the_post_thumbnail('archive-thumb'); ?>
                            <?php else: ?>
                            <?php the_post_thumbnail('single-thumb'); ?>
                            <?php endif; ?>
                            
                        <?php endif; ?>
                        
					</div>
                    
                    <div class="arrow"></div>
					<?php endif;  ?>

                    <h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php _e('Permalink to:', 'framework');?> <?php the_title(); ?>"><?php the_title(); ?></a></h2>

					<!--BEGIN .entry-content -->
					<div class="entry-content">
						<?php the_content(''); ?>
					<!--END .entry-content -->
					</div>
                    
                    <?php if(!is_singular()) : get_template_part('includes/post-meta'); endif; ?>
                
				<!--END .hentry-->  
				</div>