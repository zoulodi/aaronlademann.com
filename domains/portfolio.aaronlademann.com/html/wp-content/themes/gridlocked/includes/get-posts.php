<?php 
	
	if(file_exists('../../../../wp-load.php')) :
		include '../../../../wp-load.php';
	else:
		include '../../../../../wp-load.php';
	endif; 
	
	ob_flush(); 

	$offset 	= 		htmlspecialchars(trim($_POST['offset']));
	$cat 		= 		htmlspecialchars(trim($_POST['category']));
	$author 	= 		htmlspecialchars(trim($_POST['author']));
	$tag 		= 		htmlspecialchars(trim($_POST['tag']));
	$date 		= 		htmlspecialchars(trim($_POST['date']));
	$search 	= 		htmlspecialchars(trim($_POST['searchQ']));
   
    query_posts(array(
					'offset' => $offset,
					'cat' => $cat,
					'author' => $author,
					'tag' => $tag,
					'monthnum' => $date,
					's' => $search
				)
			);
			
?>
 
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
<?php
	// The following determines what the post format is and shows the correct file accordingly
	$format = get_post_format();
	get_template_part( 'includes/'.$format );
	
	if($format == '')
	get_template_part( 'includes/standard' );

	
?>
		
<?php endwhile; endif; ?>

<?php ob_end_flush(); ?>