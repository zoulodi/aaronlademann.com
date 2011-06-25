<?php

add_action('admin_menu', 'portfolio_admin_menu');

/**
* Creates the meta boxes for this plugin
* Load the correct stylesheet/javascript and metaboxes for the right admin page..
*/
function portfolio_admin_menu() {
	/** on portfolio edit/add page */
	if ((isset($_GET['post']) && get_post_type($_GET['post']) == 'portfolio') || ( isset($_GET['post_type']) && $_GET['post_type'] == 'portfolio' )) :
		wp_enqueue_style('simple-portfolio', WP_PLUGIN_URL . '/simple-portfolio/css/simple-portfolio.css');
		wp_enqueue_script('simple-portfolio', WP_PLUGIN_URL . '/simple-portfolio/js/simple-portfolio.js');
	
		add_meta_box('portfolio_meta_information', 'General Information', 'portfolio_meta_information_html', 'portfolio', 'normal', 'high');
		add_meta_box('portfolio_meta_media', 'Media', 'portfolio_meta_media_html', 'portfolio', 'side', 'low');
	endif;
	
	/** on media upload page */
	if (isset($_GET['simpleportfolio_media'])) :
		wp_enqueue_style('simple-portfolio', WP_PLUGIN_URL . '/simple-portfolio/css/simple-portfolio-media-upload.css');
		wp_enqueue_script('simple-portfolio', WP_PLUGIN_URL . '/simple-portfolio/js/simple-portfolio-media-upload.js');
	endif;
	
	/** on settings page */
	if (isset($_GET['page']) && $_GET['page'] == 'portfolio-settings'):
		wp_enqueue_style('simple-portfolio', WP_PLUGIN_URL . '/simple-portfolio/css/simple-portfolio.css');
	endif;
}

/**
* Returns the portfolio information html for the meta box
*/
function portfolio_meta_information_html() {
	global $post;
	$info = simple_portfolio_info( $post->ID );
	$fields = split(",", get_option('info-fields'));

	echo "<div id='portfolio_options'>";
	echo "<input type=\"hidden\" name=\"simple_portfolio_nonce\" id=\"simple_portfolio_nonce\" value=\"" .wp_create_nonce( plugin_basename(WP_PLUGIN_URL . '/simple-portfolio/simple-portfolio.php') ) . "\" />";
	if (count(get_option_preformatted()) == 0):
		$settings_url =  get_admin_url() . "options-general.php?page=portfolio-settings";
		_e("No fields are set yet. Click <a href=\"$settings_url\">here</a> to set the information fields.", "portfolio");
	endif;
	
	foreach (get_option_preformatted() as $key=>$value) :
		add_portfolio_option($key, $value, isset($info['portfolio_' . $key]) ? $info['portfolio_' . $key] : '');
	endforeach;
	echo "</div>";
}

/**
* Add a single portfolio information
* @param String $option The unique id / title
* @param String $value
*/
function add_portfolio_option( $key, $option, $value ) {
	?>
		<p>
			<label for="<?php echo 'portfolio_info_' . $option; ?>" style="display:block"><?php echo ucfirst($option) ?></label>
			<input type="text" value="<?php echo htmlentities($value); ?>" class="code" id="<?php echo 'portfolio_info_' . $key; ?>" name="<?php echo 'portfolio_info_' . $key; ?>">
		</p>
	<?php
}

/**
* Returns the portfolio media html for the meta box
*/
function portfolio_meta_media_html() {
	global $post;
	$media = simple_portfolio_media( $post->ID );
	
	if (!strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE')) : 
	?>
	<div id="add_buttons">
		<a title="Add Media" class="thickbox" id="add_media" href="media-upload.php?post_id=<?php echo $post->ID; ?>&simpleportfolio_media=true&TB_iframe=1&width=640&height=644" style="border:none;text-decoration:none;">
			<input type="button" value="Add an Image" class="button tagadd" />
		</a>
		<input type="button" value="Add a YouTube video" class="button tagadd" onclick="add_youtube();" />
		<input type="button" value="Add a Code Snippet" class="button tagadd" onclick="add_snippet();" />
		<input type="button" value="Add a Text Paragraph" class="button tagadd" onclick="add_text();" />
	</div>
	<div id="portfolio-media-add"></div>
	<div id="portfolio-media-items">
		<ul>
		<?php if (is_array($media) && count($media) > 0): foreach ($media as $key=>$media_item) : $index = $key + 1; ?>	
			
			<li>
				<?php
					$url_to_load =  WP_PLUGIN_URL . '/simple-portfolio/media-html/' . strtolower($media_item['type']) . '.html';
					
					$f = wp_remote_fopen($url_to_load);
					$f = preg_replace(	array(	"/{title}/",
												"/{index}/",
												"/{value}/"), 
										array(	ucfirst($media_item['type']),
												$index,
												htmlentities($media_item['value'])), $f);
													
					if (strtolower($media_item['type']) == 'image'):
						$img = wp_get_attachment_image_src($media_item['value'], 'medium');
						$f = preg_replace(	array("/{value\[0\]}/", "/{value\[1\]}/"), 
											array($img[0], $media_item['value']), 
											$f);
					endif;
					print_r($f);
				?>
			</li>
			
		<?php endforeach; endif; ?>
		</ul>
	</div>
	
	<?php else: ?>
		<div style="display:block;margin:10px;padding:10px;background-color: #fffeeb; color: #555555; border: 1px solid #aaaaaa; text-align: center;">
			<span style="color:#ff0000;">Media disabled!</span><p>Internet Explorer is not compatible with this plugin. Please update your browser to Firefox</p>
		</div>
	<?php endif; ?>
	<script type="text/javascript">
		// check browser version
		if (!jQuery.browser.msie)  {
			close_all_media_snippets();
		}
	</script>
	
	<?php
}

?>