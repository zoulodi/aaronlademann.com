<?php
header("HTTP/1.1 404 Not Found");
header("Status: 404 Not Found");
?>
<?php get_header(); ?>

			<!--BEGIN #primary .hfeed-->
			<div id="primary" class="hfeed">
			
				<!--BEGIN #post-0-->
				<div id="post-0" <?php post_class() ?>>
					
					<h1 class="entry-title"><?php _e('Error 404 - Not Found', 'framework') ?></h1>
					
					<!--BEGIN .entry-content-->
					<div class="entry-content">
						<p><?php _e("Sorry, but you are looking for something that isn't here.", "framework") ?></p>
					<!--END .entry-content-->
					</div>
					
				<!--END #post-0-->
				</div>
				
			<!--END #primary .hfeed-->
			</div>
 
<?php get_sidebar(); ?>

<?php get_footer(); ?>