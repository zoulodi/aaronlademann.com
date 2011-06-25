				<!--BEGIN .hentry -->
				<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
                
                	<?php $quote =  get_post_meta(get_the_ID(), 'tz_quote', true); ?>
                	
                    <!--BEGIN .quote-wrap -->
                    <div class="quote-wrap clearfix">
                    	
                        <span class="icon"></span>
                        
                        <blockquote>
                            <?php echo $quote; ?>
                        </blockquote>
                        
                    <!--END .quote-wrap -->
                    </div>
                    
                    <span class="arrow"></span>

					<!--BEGIN .entry-content -->
					<div class="entry-content">
						<?php the_content(''); ?>
					<!--END .entry-content -->
					</div>
                    
                    <?php if(!is_singular()) : get_template_part('includes/post-meta'); endif; ?>
                
				<!--END .hentry-->  
				</div>