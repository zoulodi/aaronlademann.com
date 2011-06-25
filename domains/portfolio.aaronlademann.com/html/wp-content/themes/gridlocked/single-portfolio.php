<?php get_header(); ?>
<!--BEGIN #primary .hfeed-->
        
        <div id="primary" class="hfeed">
          <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
          <?php 
                  $image1 = get_post_meta(get_the_ID(), 'tz_portfolio_image', TRUE); 
                  $image2 = get_post_meta(get_the_ID(), 'tz_portfolio_image2', TRUE); 
                  $image3 = get_post_meta(get_the_ID(), 'tz_portfolio_image3', TRUE); 
                  $image4 = get_post_meta(get_the_ID(), 'tz_portfolio_image4', TRUE); 
                  $image5 = get_post_meta(get_the_ID(), 'tz_portfolio_image5', TRUE);
                  $height = get_post_meta(get_the_ID(), 'tz_portfolio_image_height', TRUE);
                  
									
										$lightbox = get_post_meta(get_the_ID(), 'tz_portfolio_lightbox', TRUE); 
                    $embed = get_post_meta(get_the_ID(), 'tz_portfolio_embed_code', TRUE);
                    $large_image =  wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'fullsize', false, '' );
										$thumb = get_post_meta(get_the_ID(), 'tz_portfolio_thumb', TRUE); 
										$lightbox = TRUE;
										 
                  $custom_gallery = FALSE;
                  
                  //echo $height;
                ?> 
          <!--BEGIN .hentry -->
          <div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
            <?php if($image1 != '') : ?>
            <!-- its an image gallery -->
            	<!-- aaronl: custom -->
            	<script type="text/javascript">
							// break down all the different sizes i have for each shot
									<?php if($image1 != '') : ?>raw_src1 = "<?php echo $image1; ?>";<?php endif; ?>
									<?php if($image2 != '') : ?>raw_src2 = "<?php echo $image2; ?>";<?php endif; ?>
									<?php if($image3 != '') : ?>raw_src3 = "<?php echo $image3; ?>";<?php endif; ?>
									<?php if($image4 != '') : ?>raw_src4 = "<?php echo $image4; ?>";<?php endif; ?>
									<?php if($image5 != '') : ?>raw_src5 = "<?php echo $image5; ?>";<?php endif; ?>

									function get_image_src(index,size){
										// break down the raw src info pieces
										var raw_src;
										var img_size;
										//console.info("index: " + index);
										switch(index)
										{
											case '1':
												raw_src = raw_src1;		
											break;
											case '2':
												raw_src = raw_src2;
											break;
											case '3':
												raw_src = raw_src3;
											break;
											case '4':
												raw_src = raw_src4;
											break;
											case '5':
												raw_src = raw_src5;
											break;
											default:
											// something went wrong
											//console.warn("no index " + index + " is defined here.");
										} 
										switch(size)
										{
											case 'thumb':
												img_size = "_thumb";
											break;
											case 'image':
												img_size = "_550";
											break;
											case 'full':
												img_size = "_full";
											break;
											case 'big':
												img_size = "_1920";
											break;
											default:
											// something went wrong
											//console.warn("no size " + size + " is defined here.");
										} 
										var img_dir =  raw_src.slice(0,raw_src.lastIndexOf("/") + 1);
										var img_name = raw_src.slice(raw_src.lastIndexOf("/") + 1,raw_src.lastIndexOf("_"));    
										var img_mime = raw_src.slice(raw_src.lastIndexOf("."),raw_src.length);
										
										var image = img_dir + img_name + img_size + img_mime;
										//console.info("raw_src: " + raw_src + "\nimage: " + image);
										return image;
										
									}
									
									$(document).ready(function(){
									
										var win_width = $(window).width();
										var large_image_size;
										if(win_width > 1024) {
											large_image_size = 'big';
										} else {
											large_image_size = 'full';
										}
										var lightImages = $(".slider").find("a.lightbox");
										$(lightImages).each(function(index){

											var strIndex = (index + 1)+'';
											var lightLink = get_image_src(strIndex,large_image_size);
											$(this).attr("href",lightLink);
											//console.info("link #1 href=" + lightLink);
											
										});
									});
							</script>
              <!-- aaronl: custom -->
              <?php if($custom_gallery) : ?>
              <!-- aaronl: custom - use the fullscreen custom gallery -->
              
              <div id="custom-gallery">
              
              <div class="galleria-bar" style="bottom: 0px; "><div class="galleria-fullscreen"></div><div class="galleria-play"></div><div class="galleria-popout"></div><div class="galleria-thumblink"></div><div class="galleria-info"><div class="galleria-counter" style="opacity: 1; "><span class="galleria-current">1</span> / <span class="galleria-total">13</span></div><div class="galleria-info-text"><div class="galleria-info-title">Manzanar birds on wire</div><div class="galleria-info-description">Birds on wire, evening, Manzanar Relocation Center / photograph by Ansel Adams.</div></div></div><div class="galleria-s1"></div><div class="galleria-s2"></div><div class="galleria-s3"></div><div class="galleria-s4"></div></div> 
              </div>
              <script type="text/javscript">

									var data = [
											<?php if($image1 != '') : ?>
											{
													thumb: get_image_src('1','thumb'),
													image: get_image_src('1','image'), 
													big: get_image_src('1','big'),
													original: get_image_src('1','big')
													/*title: 'My title',
													description: 'My description',
													link: 'http://my.destination.com'*/
											}
											<?php endif; ?>
											<?php if($image2 != '') : ?>
											,{
													thumb: get_image_src('2','thumb'),
													image: get_image_src('2','image'), 
													big: get_image_src('2','big'),
													title: 'My title',
													description: 'My description',
													link: 'http://my.destination.com'
											}
											<?php endif; ?>
											<?php if($image3 != '') : ?>
											,{
													thumb: get_image_src('3','thumb'),
													image: get_image_src('3','image'), 
													big: get_image_src('3','big'),
													title: 'My title', 
													description: 'My description',
													link: 'http://my.destination.com'
											}
											<?php endif; ?>
											<?php if($image4 != '') : ?>
											,{
													thumb: get_image_src('4','thumb'),
													image: get_image_src('4','image'), 
													big: get_image_src('4','big'),
													title: 'My title',
													description: 'My description',
													link: 'http://my.destination.com'
											}
											<?php endif; ?>
											<?php if($image5 != '') : ?>
											,{
													thumb: get_image_src('5','thumb'),
													image: get_image_src('5','image'), 
													big: get_image_src('5','big'),
													title: 'My title',
													description: 'My description',
													link: 'http://my.destination.com'
											}
											<?php endif; ?>
									];
								
									Galleria.loadTheme('/wp-content/plugins/galleria/themes/classic/galleria.classic.min.js');
									size: 'big',
									$("#custom-gallery").galleria({
											width: 550,
											dataSource: data,
											showImageNav: false,
											height: <?php echo $height; ?>,
											extend: function(){
												var gallery = this;
												$(".galleria-image").click(function(){
													gallery.enterFullscreen();
												});
											}
									});
							</script>
              
              <?php else: ?>
              <!-- aaronl: custom - use the standard fancybox "slider" gallery -->
              
								<?php tz_gallery(get_the_ID()); ?>
                <!--BEGIN .slider -->
                <div id="slider-<?php the_ID(); ?>" class="slider" data-loader="<?php echo  get_template_directory_uri(); ?>/images/<?php if(get_option('tz_alt_stylesheet') == 'dark.css'):?>dark<?php else: ?>light<?php endif; ?>/ajax-loader.gif">
                  <div id="" class="slides_container clearfix">
                    <?php if($image1 != '') : ?>
                    <div><a class="lightbox" title="<?php the_title(); ?>" href="#" rel="gallery_<?php the_ID(); ?>"><img height="<?php echo $height; ?>" width="550" src="<?php echo $image1; ?>" alt="<?php the_title(); ?>" /></a></div>   
                    <?php endif; ?>
                    <?php if($image2 != '') : ?>
                    <div><a class="lightbox" title="<?php the_title(); ?>" href="#" rel="gallery_<?php the_ID(); ?>"><img width="550" src="<?php echo $image2; ?>" alt="<?php the_title(); ?>" /></a></div> 
                    <?php endif; ?>
                    <?php if($image3 != '') : ?>
                    <div><a class="lightbox" title="<?php the_title(); ?>" href="#" rel="gallery_<?php the_ID(); ?>"><img width="550" src="<?php echo $image3; ?>" alt="<?php the_title(); ?>" /></a></div>
                    <?php endif; ?>
                    <?php if($image4 != '') : ?>
                    <div><a class="lightbox" title="<?php the_title(); ?>" href="#" rel="gallery_<?php the_ID(); ?>"><img width="550" src="<?php echo $image4; ?>" alt="<?php the_title(); ?>" /></a></div>
                    <?php endif; ?>
                    <?php if($image5 != '') : ?>
                    <div><a class="lightbox" title="<?php the_title(); ?>" href="#" rel="gallery_<?php the_ID(); ?>"><img width="550" src="<?php echo $image5; ?>" alt="<?php the_title(); ?>" /></a></div> 
                    <?php endif; ?>
                  </div>
                  <!--END .slider -->
                </div>
                <?php if($image2 != '') : ?>
                <div class="arrow"></div>
                <?php else: ?>
                <div class="arrow noslider"></div>
                <?php endif; ?>
              
              <?php endif; ?>
              <!-- END if(custom_gallery) -->

            <?php else: ?>
            <!-- its a video gallery -->
            
							<?php $embed = get_post_meta(get_the_ID(), 'tz_portfolio_embed_code', TRUE); ?>
              <?php if($embed == '') : ?>
              <?php tz_video(get_the_ID()); ?>
              <?php $heightSingle = get_post_meta(get_the_ID(), 'tz_video_height_single', TRUE); ?>
              <style type="text/css">
                                          .single .jp-video-play,
                                          .single div.jp-jplayer.jp-jplayer-video {
                                              height: <?php echo $heightSingle; ?>px;
                                          }
                                      </style>
              <!-- BEGIN jquery_jplayer -->
              <div id="jquery_jplayer_<?php the_ID(); ?>" class="jp-jplayer jp-jplayer-video"></div>
              <div class="jp-video-container">
                <div class="jp-video">
                  <div class="jp-type-single">
                    <div id="jp_interface_<?php the_ID(); ?>" class="jp-interface">
                      <ul class="jp-controls">
                        <li>
                          <div class="seperator-first"></div>
                        </li>
                        <li>
                          <div class="seperator-second"></div>
                        </li>
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
              <!-- END jquery_jplayer -->
              <?php else: ?>
              <?php echo stripslashes(htmlspecialchars_decode($embed)); ?>
              <?php endif; ?>
              
            <?php endif; ?>
            <!-- END IF(image or video) -->
            
            <h1 class="entry-title"><?php the_title(); ?></h1>
            
            <!--BEGIN .entry-content -->
            <div class="entry-content">
              <?php the_content(''); ?>
              <!--END .entry-content -->
            </div>
            <!--END .hentry-->
          </div>
          <?php comments_template('', true); ?>
          <?php endwhile; else: ?>
          <!--BEGIN #post-0-->
          <div id="post-0" <?php post_class() ?>>
            <h1 class="entry-title">
              <?php _e('Error 404 - Not Found', 'framework') ?>
            </h1>
            <!--BEGIN .entry-content-->
            <div class="entry-content">
              <p>
                <?php _e("Sorry, but you are looking for something that isn't here.", "framework") ?>
              </p>
              <!--END .entry-content-->
            </div>
            <!--END #post-0-->
          </div>
          <?php endif; ?>
          <!--END #primary .hfeed-->
        </div>
        <!--BEGIN #single-sidebar-->
        <div id="single-sidebar">
          <?php 
                    $caption = get_post_meta(get_the_ID(), 'tz_portfolio_caption', TRUE); 
                    $link = get_post_meta(get_the_ID(), 'tz_portfolio_link', TRUE); 
                  ?>
          <!--BEGIN .entry-meta .entry-header-->
          <ul class="entry-meta entry-header clearfix">
            <?php edit_post_link( __('[Edit]', 'framework'), '<li class="edit-post">', '</li>' ); ?>
            <?php if($caption != '') : ?>
            <li class="caption"> <?php echo stripslashes(htmlspecialchars_decode($caption)); ?> </li>
            <?php endif; ?>
            <?php if($link != '') : ?>
            <li class="link"> <a target="_blank" href="<?php echo $link; ?>"><span class="icon"></span>
              <?php _e('View Project', 'framework'); ?>
              </a> </li>
            <?php endif; ?>
            <li class="terms">
              <ul>
                <?php wp_list_categories(array('title_li' => '<h3 class="widget-title first">Skills Used</h3>', 'show_option_none' => '[ empty ]', 'taxonomy' => 'skill-type')); ?>
              </ul>
              <ul>
                <?php wp_list_categories(array('title_li' => '<h3 class="widget-title">Media Type</h3>', 'show_option_none' => '[ empty ]', 'taxonomy' => 'media-type')); ?>
              </ul>
              <ul>
                <?php wp_list_categories(array('title_li' => '<h3 class="widget-title">Tools Used</h3>', 'show_option_none' => '[ empty ]', 'taxonomy' => 'tools-used')); ?>
              </ul>
            </li>
            <!--END .entry-meta entry-header -->
          </ul>
          <div class="seperator clearfix">
            <div class="line"></div>
          </div>
          <?php if(is_single()) : ?>
          <!--BEGIN .navigation .single-page-navigation -->
          <div class="navigation single-page-navigation clearfix">
            <div class="nav-previous">
              <?php next_post_link(__('%link', 'framework'), '<span class="arrow">%title</span>') ?>
            </div>
            <div class="portfolio-link"> <a href="<?php echo get_permalink( get_option('tz_portfolio_page') ); ?>"> <span class="icon">
              <?php _e('Back to Portfolio', 'framework'); ?>
              </span> </a> </div>
            <div class="nav-next">
              <?php previous_post_link(__('%link', 'framework'), '<span class="arrow">%title</span>') ?>
            </div>
            <!--END .navigation .single-page-navigation -->
          </div>
          <?php endif; ?>
          <!--END #single-sidebar-->
        </div>
<?php get_footer(); ?>
