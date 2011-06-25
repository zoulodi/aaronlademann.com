    
            <!-- END #content -->
            </div>
            
        <!-- END #container -->
        </div> 
        
        <!-- BEGIN #footer -->       
        <div id="footer" class="clearfix">
        		
            <nav id="footerNav" class="nav">
							<?php wp_nav_menu( array( 'theme_location' => 'secondary') ); ?>
            </nav>
            
            <p class="copyright" id="copyright">&copy; Copyright <?php echo date( 'Y' ); ?> <a href="http://aaronlademann.com">Aaron Lademann</a></p>
            

            
            <p class="credit"><?php echo get_option('tz_footer_copy'); ?></p>

        
        <!-- END #footer -->
        </div>
		
	<!-- Theme Hook -->
	<?php wp_footer(); ?>
			
<!--END body-->
</body>
<!--END html-->
</html>