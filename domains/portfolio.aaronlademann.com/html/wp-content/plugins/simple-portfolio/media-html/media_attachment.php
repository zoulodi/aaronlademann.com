<?php
	error_reporting(0);
	require( dirname(__FILE__) . '/../../../../wp-config.php' );
	
	/**
	* Depricated..
	* //echo wp_get_attachment_url($_GET['id']); 
	* 
	* Return image url in medium size instead of the full image
	*/
	$img = wp_get_attachment_image_src($_GET['id'], 'medium');
	echo $img[0];
?>