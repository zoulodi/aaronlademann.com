<?php
/*
Plugin Name: Carousel Gallery (jQuery)
Version: 1.6.4
Description: Overrides the builtin Wordpress gallery and replaces it with a javascript carousel.
Author: Joen
Author URI: http://noscope.com/
Plugin URI: http://noscope.com/?p=3367
*/


/*
 TODO:
 - show captions, descriptions, should be optional
 - expanding paragraphs -- possible?
 - tweaks from comments
 - recode the entire thing according to "magazine like slideshow"
*/

load_plugin_textdomain('carousel-gallery-jquery', NULL, dirname(plugin_basename(__FILE__)));

add_filter('post_gallery', 'carousel_gallery_jquery', 10, 2);
add_action('wp_head', 'carousel_gallery_jquery_header');



/*****************************
* Enqueue jQuery & Scripts
*/
function carousel_enqueue_scripts() {
	if ( function_exists('plugin_url') )
		$plugin_url = plugin_url();
	else
		$plugin_url = get_option('siteurl') . '/wp-content/plugins/' . plugin_basename(dirname(__FILE__));

	// jquery
	wp_deregister_script('jquery');
	wp_register_script('jquery', ($plugin_url  . '/jquery-1.3.2.min.js'), false, '1.3.2');
	wp_enqueue_script('jquery');
	
}
if (!is_admin()) {
	add_action('init', 'carousel_enqueue_scripts');
}




function carousel_gallery_jquery_header() {
	if ( function_exists('plugin_url') )
		$plugin_url = plugin_url();
	else
		$plugin_url = get_option('siteurl') . '/wp-content/plugins/' . plugin_basename(dirname(__FILE__));

	echo '<link href="' . $plugin_url . '/carousel-gallery-jquery.css" rel="stylesheet" type="text/css" />' . "\n";
	echo '<script type="text/javascript" src="' . $plugin_url . '/jcarousel.js"></script>' . "\n";





	/**
	* Add styles
	*/
	$output = "
	<style type='text/css'>";

	if ( get_option('cgj_gallery_height') != "" )
	{
		$output .= '.jcarousel-container, .jcarousel-list li, .jcarousel-item, .jcarousel-clip
		{
			height: '.get_option('cgj_gallery_height').'px !important;
		}
		';
	}

	if ( get_option('medium_size_w') != "FALSE" && get_option('medium_size_h') != "" )
	{
		$output .= "
		.jcarousel-list li,
		.jcarousel-item {
			/* We set the width/height explicitly. No width/height causes infinite loops. */
			width: ".get_option('medium_size_w')."px;
			height: ".get_option('medium_size_h')."px;
		}
		.jcarousel-skin-neat .jcarousel-clip-horizontal {
			width: ".get_option('medium_size_w')."px;
			height: ".get_option('medium_size_h')."px;
		}
		";
	}

	/* hide titles, fixme */
	if (get_option('cgj_show_titles') == "false") {
		$output .= "\nh2.cgj_title { display: none; }\n";
	}

	$output .= "</style>\n";

	echo "\n".$output."\n";

}



function remove_brs($string) {
	$new_string=urlencode ($string);
	$new_string=ereg_replace("%0D", "{br}", $new_string);
	$new_string=ereg_replace("%0A", "{br}", $new_string);
	$new_string=urldecode  ($new_string);
	return $new_string;
}


function carousel_gallery_jquery($output, $attr) {

	/**
	* Grab attachments
	*/
	global $post;
	
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}
	
	extract(shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'itemtag'    => 'dl',
		'icontag'    => 'dt',
		'captiontag' => 'dd',
		'columns'    => 3,
		'size'       => 'thumbnail',
	), $attr));
	
	$id = intval($id);
	$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	
	if ( empty($attachments) )
		return '';
		
	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $id => $attachment )
			$output .= wp_get_attachment_link($id, $size, true) . "\n";
		return $output;
	}
	


	/**
	* Start output
	*/
	$output = "\t
	<!-- Begin Carousel Gallery -->
	<div class='carousel-gallery'>
	";


	/**
	* Add ULs
	*/
	$output .= "<ul id='mycarousel_".$post->ID."' class='jcarousel-skin-neat'><li></li></ul>\n";


	/**
	* Add next/prev
	*/
	$output .= "
    <div class='jcarousel-scroll'>
        <a class='cgj_prev' href='#' id='mycarousel_".$post->ID."-prev'>" . __('&laquo; Previous', 'carousel-gallery-jquery') . "</a>
        <a class='cgj_next' href='#' id='mycarousel_".$post->ID."-next'>". __('Next &raquo;', 'carousel-gallery-jquery') . "</a>
    </div>
	";


	/**
	* Add images
	*/
	$output .= "<script type='text/javascript'>\n

	/* <![CDATA[ */

	var mycarousel_itemList_".$post->ID." = [
	";
	$js = array();
	foreach ( $attachments as $id => $attachment ) {
		$image = wp_get_attachment_image_src($id, "medium");
		$js[] = "{url: '" . $image[0] . "', title: '".addslashes($attachment->post_title)."', caption: '".addslashes(remove_brs($attachment->post_excerpt))."', description: '".addslashes(remove_brs($attachment->post_content))."'}";
	}
	$output .= join(",\n", $js);
	$output .= "];\n";

	
	

	/**
	* Add scripts
	*/
	$output .= "
function mycarousel_".$post->ID."_itemVisibleInCallback(carousel, item, i, state, evt)
	{
		// The index() method calculates the index from a
		// given index who is out of the actual item range.
		var idx = carousel.index(i, mycarousel_itemList_".$post->ID.".length);
		
		// crappy IE6 crappety crap
		//var isMSIE = /*@cc_on!@*/false;
		//if (isMSIE && idx == mycarousel_itemList_".$post->ID.".length) {
		//	idx = 1;
		//	i = 1;
		//}
		
		carousel.add(i, mycarousel_".$post->ID."_getItemHTML(mycarousel_itemList_".$post->ID."[idx - 1]));
	};

	
	function mycarousel_".$post->ID."_itemVisibleOutCallback(carousel, item, i, state, evt)
	{
		carousel.remove(i);
	};
	
	/**
	 * Item html creation helper.
	 */
	function mycarousel_".$post->ID."_getItemHTML(item)
	{
		return \"<h2 class='cgj_title'>\" + item.title + \"</h2><div class='cgj_image'><a href='#' rel='nofollow'><img src='\"+item.url+\"' alt='\"+item.title+\"' /></a></div><p class='caption'>\" + item.caption.replace(/{br}/g, '<br>') + \"</p><p class='description'>\" + item.description.replace(/{br}/g, '<br>') + \"</p>\";

	};
	
	
	
	/**
	 * We use the initCallback callback
	 * to assign functionality to the controls
	 */
	function mycarousel_".$post->ID."_initCallback(carousel) {
		jQuery('.jcarousel-control_".$post->ID." a').bind('click', function() {
			carousel.scroll(jQuery.jcarousel.intval(jQuery(this).text()));
			return false;
		});
	
		jQuery('.jcarousel-scroll select').bind('change', function() {
			carousel.options.scroll = jQuery.jcarousel.intval(this.options[this.selectedIndex].value);
			return false;
		});
	
		jQuery('#mycarousel_".$post->ID."-next').bind('click', function() {
			carousel.next();

			/*";
			if (get_option('cgj_thumbnail_overflow') == "true") {
				$output .= "
				
				scrollThumbnails(nextItem);

				";
			}
			$output .= "*/
			
			return false;
		});
	
		jQuery('#mycarousel_".$post->ID."-prev').bind('click', function() {
			carousel.prev();

			/*";
			if (get_option('cgj_thumbnail_overflow') == "true") {
				$output .= "
				
				scrollThumbnails(nextItem);

				";
			}
			$output .= "*/
			
			
			return false;
		});

		jQuery('#mycarousel_".$post->ID."-forward').bind('click', function() {
			carousel.next();
			return false;
		});

	};


	/**
	 * This is the callback function which receives notification
	 * when an item becomes the first one in the visible range.
	 */

	function mycarousel_".$post->ID."_itemFirstInCallback(carousel, item, idx, state) {
		
		nextItem = idx % mycarousel_itemList_".$post->ID.".length;
		nextItem = (nextItem == 0) ? mycarousel_itemList_".$post->ID.".length : nextItem;
		
		jQuery('.jcarousel-control_".$post->ID." a').removeClass('active');
		jQuery('.item'+nextItem+'_".$post->ID."').addClass('active');
	
	
		
		// add forward btns on each image in its own right
		jQuery('#mycarousel_".$post->ID." a').bind('click', function() {
			carousel.next();


			/*";
			if (get_option('cgj_thumbnail_overflow') == "true") {
				$output .= "
				
				scrollThumbnails(nextItem);

				";
			}
			$output .= "*/

			return false;
		});
	};
	
	
	/**
	 * Scroll thumbnails
	 */
	/*function scrollThumbnails(idx) {
				
			if (idx == mycarousel_itemList_".$post->ID.".length) {
				idx = 0;
			}

			if (idx < 0) {
				idx = mycarousel_itemList_".$post->ID.".length + idx;
			}

			jQuery('#header').html(idx);


			// scroll to active thumb
			var thumbWidth = jQuery('.carousel-controls-inner a').outerWidth() + parseInt(jQuery('.carousel-controls-inner a').css('margin-right')); //jQuery('.item'+nextItem+'_".$post->ID."').offset().left;

			jQuery('.carousel-controls-inner').animate({ 
				marginLeft: -1 * (thumbWidth * idx)
			}, 'fast' );
	
	
	}*/
	";

	
	
	
	/**
	* Initialize
	*/
	$output .= "\n
	jQuery(document).ready(function() {
		jQuery('#mycarousel_".$post->ID."').jcarousel({
			wrap: 'circular',
			itemVisibleInCallback: {onBeforeAnimation: mycarousel_".$post->ID."_itemVisibleInCallback}
			,itemVisibleOutCallback: {onAfterAnimation: mycarousel_".$post->ID."_itemVisibleOutCallback}
			,scroll: 1
			,animation: ".stripslashes(get_option('cgj_animation_speed'))."
			,initCallback: mycarousel_".$post->ID."_initCallback
			,buttonNextHTML: null
			,buttonPrevHTML: null
			,itemFirstInCallback: mycarousel_".$post->ID."_itemFirstInCallback
		});

	});

	/* ]]> */
	</script>
	";









	/**
	* Add thumbnail navigation
	*/
	$output .= "<div class='carousel-controls jcarousel-control_".$post->ID."'><div class='carousel-controls-inner'>\n";
	$n = 1;
	
	if (get_option('cgj_thumbnail_size') && get_option('cgj_thumbnail_size') != "") {
		$thumb_size = array(get_option('cgj_thumbnail_size'), get_option('cgj_thumbnail_size'));
	} else {
		$thumb_size = 'thumbnail';
	}
	
	
	foreach ( $attachments as $id => $attachment ) {
		$link = wp_get_attachment_link($id, $size, true);		

		$output .= "<a href=\"#\" class=\"item".$n."_".$post->ID."\"><span>".$n."</span>" . wp_get_attachment_image( $id, $thumb_size ) . "</a>\n";
		$n++;
		
	}
	$output .= '</div></div>';
	
	
	/**
	* End
	*/
	$output .= "
		<br style='clear: both;' />
	</div>
	<!-- End Carousel Gallery -->\n
	";


	return $output;

}






/*****************************
* Options Page
*/

// Options
$cgj_plugin_name = __("Carousel Gallery", 'carousel-gallery-jquery');
$cgj_plugin_filename = basename(__FILE__); //"carousel-gallery-jquery.php";

add_option("cgj_thumbnail_size", "", "", "yes");
add_option("cgj_gallery_height", "", "", "yes");
add_option("cgj_animation_speed", "'fast'", "", "yes");
add_option("cgj_show_titles", "true", "", "yes");







function cgj_admin_init() {
	if ( function_exists('register_setting') ) {
		register_setting('cgj_settings', 'option-1', '');
	}
}
function add_cgj_option_page() {
	global $wpdb;
	global $cgj_plugin_name;

	add_options_page($cgj_plugin_name . ' ' . __('Options', 'carousel-gallery-jquery'), $cgj_plugin_name, 8, basename(__FILE__), 'cgj_options_page');
	
}
add_action('admin_init', 'cgj_admin_init');
add_action('admin_menu', 'add_cgj_option_page');



// Options function
function cgj_options_page() {

	if (isset($_POST['info_update'])) {
			
		// Update options
		$cgj_thumbnail_size = $_POST["cgj_thumbnail_size"];
		update_option("cgj_thumbnail_size", $cgj_thumbnail_size);

		$cgj_gallery_height = $_POST["cgj_gallery_height"];
		update_option("cgj_gallery_height", $cgj_gallery_height);

		$cgj_animation_speed = $_POST["cgj_animation_speed"];
		update_option("cgj_animation_speed", $cgj_animation_speed);

		$cgj_show_titles = $_POST["cgj_show_titles"];
		update_option("cgj_show_titles", $cgj_show_titles);


		// Give an updated message
		echo "<div class='updated fade'><p><strong>" . __('Options updated', 'carousel-gallery-jquery') . "</strong></p></div>";
		
	}

	// Show options page
	?>

		<div class="wrap">
		
			<div class="options">
		
				<form method="post" action="options-general.php?page=<?php global $cgj_plugin_filename; echo $cgj_plugin_filename; ?>">
			
				<h2><?php global $cgj_plugin_name; printf(__('%s Settings', 'carousel-gallery-jquery'), $cgj_plugin_name); ?></h2>
	
		
					<h3><?php _e("Thumbnail Size", 'carousel-gallery-jquery'); ?></h3>
					<input type="text" size="50" name="cgj_thumbnail_size" id="cgj_thumbnail_size" value="<?php echo get_option('cgj_thumbnail_size') ?>" />
					<br />
					
					<p class="setting-description"><?php _e("The size (both width and height) in pixels of carousel thumbnails. This is optional. If you enter nothing, Wordpress' thumbnail size will be used.", 'carousel-gallery-jquery') ?></p>
					

					<h3><?php _e("Custom Gallery Height", 'carousel-gallery-jquery'); ?></h3>
					<input type="text" size="50" name="cgj_gallery_height" id="cgj_gallery_height" value="<?php echo get_option('cgj_gallery_height') ?>" />
					<br />
					
					<p class="setting-description"><?php _e("The height in pixels of all galleries on your website. This is optional. If you enter nothing, Wordpress' media sizes will be used.", 'carousel-gallery-jquery') ?></p>


		

					<h3><?php _e("Gallery Animation Speed", 'carousel-gallery-jquery'); ?></h3>
					<input type="text" size="50" name="cgj_animation_speed" id="cgj_animation_speed" value="<?php echo stripslashes(get_option('cgj_animation_speed')) ?>" />
					<br />
					
					<p class="setting-description"><?php _e("The speed of the animation. Options: <code>'fast'</code>, <code>'slow'</code>, or a number. 0 is instant, 10000 is totally slow. Note: if you use the 'fast' or 'slow' values, you MUST remember the apostrophes!", 'carousel-gallery-jquery') ?></p>



					<h3><?php _e("Show Titles", 'carousel-gallery-jquery'); ?></h3>
					<label>
					<?php
					echo "<input type='radio' ";
					echo "name='cgj_show_titles' ";
					echo "id='cgj_show_titles_0' ";
					echo "value='true' ";
					echo "true" == get_option('cgj_show_titles') ? ' checked="checked"' : "";
					echo " />";
					?>
					<?php _e("Yes, show image titles.", 'carousel-gallery-jquery'); ?>
					</label>
					<br />
					<label>
					<?php
					echo "<input type='radio' ";
					echo "name='cgj_show_titles' ";
					echo "id='cgj_show_titles_1' ";
					echo "value='false' ";
					echo "false" == get_option('cgj_show_titles') ? ' checked="checked"' : "";
					echo " />";
					?>
					<?php _e("No, hide image titles. ", 'carousel-gallery-jquery'); ?>
					</label>
					<br />
					
					<p class="setting-description"><?php _e("Should the title of each image be shown?", 'carousel-gallery-jquery') ?></p>
					

		
					<p class="submit">
						<?php if ( function_exists('settings_fields') ) settings_fields('cgj_settings'); ?>
						<input type='submit' name='info_update' value='<?php _e('Save Changes', 'carousel-gallery-jquery'); ?>' />
					</p>
				
				</form>
				
				
			</div><?php //.options ?>
			
		</div>

<?php
}












?>
