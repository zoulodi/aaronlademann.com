				<!--BEGIN .hentry -->
				<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
                    
                    
                    <?php 
					
					if(!is_singular()) {
						$embed = get_post_meta(get_the_ID(), 'tz_video_embed', TRUE); 
					} else {
						$embed = get_post_meta(get_the_ID(), 'tz_video_embed_single', TRUE); 
					}
					
					?>
                    
                    <?php if($embed == '') : ?>
                    
                    <?php tz_video(get_the_ID()); ?>
                    <?php $height = get_post_meta(get_the_ID(), 'tz_video_height', TRUE); ?>
                    <?php $heightSingle = get_post_meta(get_the_ID(), 'tz_video_height_single', TRUE); ?>
                    
                    <style type="text/css">
						.jp-video-play,
						div.jp-jplayer.jp-jplayer-video {
							height: <?php echo $height; ?>px;
						}
						.single .jp-video-play,
						.single div.jp-jplayer.jp-jplayer-video {
							height: <?php echo $heightSingle; ?>px;
						}
					</style>
                    
                    <div id="jquery_jplayer_<?php the_ID(); ?>" class="jp-jplayer jp-jplayer-video"></div>
                    
                    <div class="jp-video-container">
                        <div class="jp-video">
                            <div class="jp-type-single">
                                <div id="jp_interface_<?php the_ID(); ?>" class="jp-interface">
                                    <ul class="jp-controls">
                                    	<li><div class="seperator-first"></div></li>
                                        <li><div class="seperator-second"></div></li>
                                        <li><a href="#" class="jp-play" tabindex="1">play</a></li>
                                        <li><a href="#" class="jp-pause" tabindex="1">pause</a></li>
                                        <li><a href="#" class="jp-mute" tabindex="1">mute</a></li>
                                        <li><a href="#" class="jp-unmute" tabindex="1">unmute</a></li>
                                    </ul>
                                    <div class="jp-progress-container">
                                        <div class="jp-progress">
                                            <div class="jp-seek-bar">
                                                <div class="jp-play-bar"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="jp-volume-bar-container">
                                        <div class="jp-volume-bar">
                                            <div class="jp-volume-bar-value"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php else: ?>
                    
                    <?php echo stripslashes(htmlspecialchars_decode($embed)); ?>
                    
                    <?php endif; ?>

				
                    <h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php _e('Permalink to:', 'framework');?> <?php the_title(); ?>"><?php the_title(); ?></a></h2>

					<!--BEGIN .entry-content -->
					<div class="entry-content">
						<?php the_content(''); ?>
					<!--END .entry-content -->
					</div>
                    
                    <?php if(!is_singular()) : get_template_part('includes/post-meta'); endif; ?>
                
				<!--END .hentry-->  
				</div>