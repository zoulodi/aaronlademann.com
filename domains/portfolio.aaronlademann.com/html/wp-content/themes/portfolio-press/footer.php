<?php
/**
 * @package WordPress
 * @subpackage Portfolio Press
 */
?>
	</div>
	</div><!-- #main -->

	<footer id="colophon">
    
    <?php if ( is_active_sidebar('footer-1') ||
			   	is_active_sidebar('footer-2') || 
			    is_active_sidebar('footer-3') || 
			    is_active_sidebar('footer-4') ) : ?>
                
           <div id="footer-widgets" class="col-width">

		<?php $i = 0; while ( $i <= 4 ) : $i++; ?>			
			<?php if ( is_active_sidebar('footer-'.$i) ) { ?>

		<div class="block footer-widget-<?php echo $i; ?>">
        	<?php dynamic_sidebar('footer-'.$i); ?>    
		</div>
		        
	        <?php } ?>
		<?php endwhile; ?>
        		        
		<div class="clear"></div>

	</div><!-- /#footer-widgets  -->
    
    <?php endif; ?>
    
    	<div id="footer" class="col-width">
        <nav id="footerNav" class="nav">
	        <?php wp_nav_menu( array( 'theme_location' => 'secondary') ); ?>
				</nav>
        	<p id="copyright"><small>&copy; <?php echo date("Y"); ?></small></p>
       </div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>