				<!--BEGIN .hentry -->
				<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
                	
                    <?php tz_gallery(get_the_ID()); ?>
                    
                	<!--BEGIN .slider -->
					<div id="slider-<?php the_ID(); ?>" class="slider" data-loader="<?php echo  get_template_directory_uri(); ?>/images/<?php if(get_option('tz_alt_stylesheet') == 'dark.css'):?>dark<?php else: ?>light<?php endif; ?>/ajax-loader.gif">
                    
                    <?php 
						$args = array(
							'orderby'		 => 'menu_order',
							'post_type'      => 'attachment',
							'post_parent'    => get_the_ID(),
							'post_mime_type' => 'image',
							'post_status'    => null,
							'numberposts'    => -1,
						);
						$attachments = get_posts($args);
					?>
                        
                        <?php if ($attachments) : ?>
                        
                        <div class="slides_container clearfix">
                        
                        <?php foreach ($attachments as $attachment) : ?>
                        	
                            <?php 
								$src = wp_get_attachment_image_src( $attachment->ID, 'gallery-format-thumb'); 
								if(is_singular())
									$src = wp_get_attachment_image_src( $attachment->ID, 'single-thumb'); 
							?>
                            
                        	<div>
                            <img 
                            height="<?php echo $src[2]; ?>"
                            width="<?php echo $src[1]; ?>"
                            alt="<?php echo apply_filters('the_title', $attachment->post_title); ?>" 
                            src="<?php echo $src[0]; ?>" 
                            />
                            </div>
                        
                        <?php endforeach; ?>
                        
                        </div>
                        <?php endif; ?>

                    <!--END .slider -->
					</div>
                    
                    <div class="arrow"></div>
                    
                    <h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php _e('Permalink to:', 'framework');?> <?php the_title(); ?>"><?php the_title(); ?></a></h2>

					<!--BEGIN .entry-content -->
					<div class="entry-content">
						<?php the_content(''); ?>
					<!--END .entry-content -->
					</div>
                    
                    <?php if(!is_singular()) : get_template_part('includes/post-meta'); endif; ?>
                
				<!--END .hentry-->  
				</div>