<?php 
if ( preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF']) ) { die('You are not allowed to call this page directly.'); }

class pageFlip_plugin_base
{
	var
		$version = PAGEFLIP_VERSION,
		$page_title,
		$menu_title,
		$access_level = 5,
		$add_page_to = 1,
		$table_name,		
		$table_img_name,	
		$table_gal_name,	
		$siteURL,			
		$plugin_dir = PAGEFLIP_DIRNAME,	
		$pluginFilesDir = 'pageflip',	
		$plugin_path,	
		$plugin_url,	
		$component,		
		$editor,		
		$navigation,	
		$componentJS,	
		$jqueryJS,		
		$swfObjectJS,	
		$width = 800,	
		$height = 600,	
		$maxPageSize,	
		$bgFile,		
		$maxSoundSize,	
		$parent,		
		$booksDir = 'books',	
		$soundsDir = 'sounds',	
		$imagesDir = 'images',	
		$imagesPath,
		$uploadDir = 'upload',	
		$imgUrl,				
 		$jsDir = 'js',			
		$langDir = 'lang',		
		$imagesUrl,				
		$uploadPath,			
		$jsUrl,					
		$thumbWidth = 70,		
		$thumbHeight = 90,		
		$trial,		
		$functions,	
		$html,		
		$itemsPerPage,
		$layouts = array(),	
		$popup_php,			
		$usePageEditor = true;

	function pageFlip_plugin_base()
	{
		global $wpdb;

		if ( function_exists('wp_timezone_override_offset') )
			wp_timezone_override_offset();

		$this->get_options();

		$this->page_title = __('FlippingBook Gallery', 'pageFlip');
		$this->menu_title = __('FlippingBook', 'pageFlip');

		$this->table_name = $wpdb->prefix . 'pageflip';
		$this->table_img_name = $wpdb->prefix . 'pageflip_img';
		$this->table_gal_name = $wpdb->prefix . 'pageflip_gallery';

		$this->maxPageSize = 5 * 1024 * 1024;
		$this->maxSoundSize = 100 * 1024;

		$this->url = WP_PLUGIN_URL .'/'. $this->plugin_dir;
		$this->dir = WP_PLUGIN_DIR .'/'. $this->plugin_dir;

		$this->siteURL = get_option('siteurl');
		$this->plugin_path = ( defined('UPLOADS') ? ABSPATH . UPLOADS : WP_CONTENT_DIR ) .'/'. $this->pluginFilesDir .'/';
		$this->imagesPath = $this->plugin_path . $this->imagesDir.'/';
		$this->plugin_url = ( defined('UPLOADS') ? $this->siteURL.'/'. UPLOADS : WP_CONTENT_URL ) .'/'.  $this->pluginFilesDir . '/';
		$this->imagesUrl = $this->plugin_url . $this->imagesDir.'/';
		$this->jsUrl = WP_PLUGIN_URL .'/'. $this->plugin_dir.'/'. $this->jsDir .'/';
		$this->imgUrl = WP_PLUGIN_URL .'/'. $this->plugin_dir.'/img/';
		$this->bgFile = $this->imgUrl .'bg.jpg';
		$this->component = WP_PLUGIN_URL .'/'. $this->plugin_dir .'/flippingBook.swf';
		$this->editor = WP_PLUGIN_URL .'/'. $this->plugin_dir .'/albumEditor.swf';

		$this->navigation  = WP_PLUGIN_URL .'/'. $this->plugin_dir.'/';
		$this->uploadPath = $this->plugin_path . $this->uploadDir.'/';
		$this->componentJS = $this->jsUrl .'flippingbook.js';
		$this->swfObjectJS = $this->jsUrl .'swfobject.js';
		$this->jqueryJS = $this->jsUrl .'jquery.min.js';
		$this->trial = 10;

		$this->popup_php = WP_PLUGIN_URL.'/'.$this->plugin_dir.'/popup.php';
	}

	function init()
	{
		include_once PAGEFLIP_DIR.'/functions.class.php';	
		include_once PAGEFLIP_DIR.'/htmlPart.class.php';	
		include_once PAGEFLIP_DIR.'/book.class.php';		
		include_once PAGEFLIP_DIR.'/album.class.php';		

		$this->functions = new Functions();
		$this->html = new HTMLPart();

		
		$this->layouts[1] = new Layout( 1 );
		$this->layouts[1]->addArea( 0, 0, 0, 1, 1 );
		$this->layouts[2] = new Layout( 2 );
		$this->layouts[2]->addArea( 0, 0, 0, 1, 0.5 );
		$this->layouts[2]->addArea( 1, 0, 0.5, 1, 0.5 );
		$this->layouts[3] = new Layout( 3 );
		$this->layouts[3]->addArea( 0, 0, 0, 0.5, 0.5 );
		$this->layouts[3]->addArea( 1, 0.5, 0, 0.5, 0.25 );
		$this->layouts[3]->addArea( 2, 0.5, 0.25, 0.5, 0.25 );
		$this->layouts[3]->addArea( 3, 0, 0.5, 1, 0.5 );
		$this->layouts[4] = new Layout( 4 );
		$this->layouts[4]->addArea( 0, 0, 0, 1, 0.25 );
		$this->layouts[4]->addArea( 1, 0, 0.25, 0.33, 0.25 );
		$this->layouts[4]->addArea( 2, 0.33, 0.25, 0.67, 0.5 );
		$this->layouts[4]->addArea( 3, 0, 0.5, 0.33, 0.25 );
		$this->layouts[4]->addArea( 4, 0, 0.75, 0.33, 0.25 );
		$this->layouts[4]->addArea( 5, 0.33, 0.75, 0.33, 0.25 );
		$this->layouts[4]->addArea( 6, 0.66, 0.75, 0.323, 0.25 );

		
		$this->check_db();
		$this->check_dir();

		
		$this->itemsPerPage = array(
			 0 => array ( 'value' => 25, 'label' => __('25 per page', 'pageFlip') ),
			 1 => array ( 'value' => 50, 'label' => __('50 per page', 'pageFlip') ),
			 2 => array ( 'value' => 200, 'label' => __('200 per page', 'pageFlip') ),
			 3 => array ( 'value' => 0, 'label' => __('all', 'pageFlip') )
		);

		wp_enqueue_script('jquery');
		wp_enqueue_script('swfobject', $this->swfObjectJS, array(), '2.2');
		wp_enqueue_script('flippingbook', $this->componentJS, array('jquery', 'swfobject'), '0.5.10');
	}

	function admin_init()
	{
		session_start();
	}

	function adminScripts()
	{
		wp_enqueue_script('jquery');
		if ( !empty($_GET['page']) && preg_match('#^'.preg_quote(PAGEFLIP_DIRNAME).'/(books|images)#', $_GET['page']) )
			wp_enqueue_script('swfupload', $this->jsUrl.'swfupload.js', array(), '2.2.0.1');
	}

    function get_options()
	{
		if ( !defined('WP_PLUGIN_DIR') )
		{
			if ( !defined('WP_CONTENT_DIR') )
		  	  define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
		  	define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' ); 
		}

		if ( !defined('WP_PLUGIN_URL') )
		{
			if ( !defined('WP_CONTENT_URL') )
			  define( 'WP_CONTENT_URL', get_option('siteurl') . '/wp-content'); 
			define( 'WP_PLUGIN_URL', WP_CONTENT_URL . '/plugins' ); 
		}

		if ( !defined('PLUGINDIR') )
  			define( 'PLUGINDIR', 'wp-content/plugins' ); 
	}


	function add_admin_menu()
	{
		if ( $this->add_page_to == 1 )
			add_menu_page( $this->page_title,
				$this->menu_title, $this->access_level,
				$this->plugin_dir, array ( $this , 'main_page'), $this->imgUrl.'pageFlip.gif' );

		elseif ( $this->add_page_to == 2 )
			add_options_page( $this->page_title,
				$this->menu_title, $this->access_level,
				$this->plugin_dir, array ( $this , 'main_page'), $this->imgUrl.'pageFlip.gif' );

		elseif ( $this->add_page_to == 3 )
			add_management_page( $this->page_title,
				$this->menu_title, $this->access_level,
				$this->plugin_dir, array ( $this , 'main_page'), $this->imgUrl.'pageFlip.gif' );

		elseif ( $this->add_page_to == 4 )
			add_theme_page( $this->page_title,
				$this->menu_title, $this->access_level,
				$this->plugin_dir, array ( $this , 'main_page'), $this->imgUrl.'pageFlip.gif' );


        add_submenu_page( $this->plugin_dir, __('Main', 'pageFlip'),
								__('Main', 'pageFlip'), $this->access_level,
								$this->plugin_dir );
		add_submenu_page( $this->plugin_dir, __('Manage books and pages', 'pageFlip'),
								__('Manage books and pages', 'pageFlip'), $this->access_level,
								$this->plugin_dir . '/books', array( $this, 'manage_books' ) );
		add_submenu_page( $this->plugin_dir, __('Images', 'pageFlip'),
								__('Images', 'pageFlip'), $this->access_level,
								$this->plugin_dir . '/images', array( $this, 'images' ) );
		
	}

	function activate()
	{
		$this->check_dir();

		$dirname = PAGEFLIP_DIR.'/sounds';
		$dir = opendir($dirname);
		while ( $filename = readdir($dir) )
		{
			if ( is_file($dirname.'/'.$filename) )
				@copy($dirname.'/'.$filename, $this->plugin_path.'/sounds/'.$filename);
		}
		closedir($dir);
	}

	function deactivate()
	{
	}

	function book( $att, $echo = true )
	{
		$bookHTML = $this->replaceBooks($att, '');

		if ($echo)
			echo $bookHTML;

		return $bookHTML;
	}

	
	function replaceBooks( $att, $content = '' )
	{
		global $wpdb;

		
		if ( preg_match('/(\d+)/', $att['id'], $m) )
			$att['id'] = (int)$m[1];
		else
			return '';

		

		
        $sql = "SELECT `bgImage` FROM `{$this->table_name}` WHERE `id` = '{$att['id']}'";
        $bgImage = $wpdb->get_var( $sql );

        if ( empty($bgImage) )
        	$bgImage = $this->bgFile;
        else
        {
        	if ( $bgImage == "-1" )
        		$bgImage = '';
	        else
	        {
	        	$sql =
	        		"SELECT `filename`
	        		FROM `{$this->table_img_name}`
	        		WHERE
	        			`id` = '{$bgImage}' AND
	        			`type` = 'bg'";

	         	$bgImage = $this->plugin_url . $this->imagesDir . '/' . $wpdb->get_var( $sql );
	        }
        }

		$book = new Book( $att['id'] );		

		if( $book->state !== 1 ) return false;		
		if( $book->countPages == 0 ) return false;	

		
		if( empty( $att['width'] ) || empty( $att['height'] ) )
		{
			 if( empty( $att['width'] ) ) $att['width'] = $book->stageWidth;
			 if( empty( $att['height'] ) ) $att['height'] = $book->stageHeight;
		}

		if ( !empty($att['popup']) || (isset($book->popup) && $book->popup == 'true') )
		{
			
			if ( !empty($att['preview']) )
			{
				if ( preg_match('#(http://.*\.)(gif|jpg|jpeg|png)#', $att['preview'], $m) )
					$book->preview = $m[1].$m[2];
				else
					$book->preview = $att['preview'];
			}

			return $this->html->popupLink( $book, $att );
		}
		else
		{
			
			return $this->html->viewBook( $book, $att['width'], $att['height'], $bgImage );
		}
	}

	
	function main_page()
	{
        echo '<div class="wrap">';
		echo $this->functions->printHeader( '<a href="http://pageflipgallery.com/">' . $this->page_title . '</a>' );

		$this->functions->splitImage( WP_CONTENT_DIR . '/photo.jpg' );
		
		if( defined('PAGEFLIP_ERROR') ) echo PAGEFLIP_ERROR;
		echo $this->functions->check();

		echo $this->html->mainPage();
		echo '</div>';
	}

	function page_img($book, $page, $zoom = false, $echo = true)
	{
		global $pageFlip;

		$zoomURL = $page->zoomURL;
		$imageURL = $zoom ? $zoomURL : $page->image;

		if ( !$zoom )
		{
			if ( $book->autoReduce=='true' && $pageFlip->functions->getExt($imageURL) != 'swf' && $imageURL == $zoomURL )
			{
				list($width, $height) = $pageFlip->functions->getImageSize($imageURL);
				$scale1 = $width / $book->width;
				$scale2 = $height / $book->height;
				$scale = $scale1 > $scale2 ? $scale1 : $scale2;
				$f = $scale - intval($scale);
				if ($f > 0.15 || $scale >= 3)
				{
					$imageURL =
						$pageFlip->functions->getResized(
							$zoomURL,
							array(
								'max_width' => $book->width / 2,
								'max_height' => $book->height,
								'background' => $book->pageBack,
								'quality' => 90
							)
						);
				}
			}
			$url = $imageURL;

			$pageWidth = $book->width / 2;
			$pageHeight = $book->height;

			$size = "width='{$width}' height='{$height}'";
		}
		else
		{
			$url = $zoomURL;

			$pageWidth = $book->zoomImageWidth;
			$pageHeight = $book->zoomImageHeight;
		}

		if ($echo)
		{
			list($width, $height) = $pageFlip->functions->getImageSize($url);
			$s = $pageFlip->functions->imgSize($width, $height, $pageWidth, $pageHeight);

			$size = "width='{$s['width']}' height='{$s['height']}'";

			echo "<img src='{$url}' {$size} alt='' />";
		}

		return $url;
	}

	function edit_page()
	{
		$tPrev = '&laquo; '.__('Previous', 'pageFlip');
		$tNext = __('Next', 'pageFlip').' &raquo;';
		$tZoomIn = '+ '.__('Zoom In', 'pageFlip');
		$tZoomOut = '&minus; '.__('Zoom Out', 'pageFlip');

		global $pageFlip;

		$bookID = htmlspecialchars($_POST['id']);
		$pageID = htmlspecialchars($_POST['pageId']);
		$zoom = htmlspecialchars($_POST['zoom']) == $tZoomIn ? true : false;

		$book = new Book($bookID);
		$book->load();

		$pageBack = sprintf('#%06x', hexdec($book->pageBack));

		$pageWidth = $zoom ? $book->zoomImageWidth : $book->width / 2;
		$pageHeight = $zoom ? $book->zoomImageHeight : $book->height;

		$navPage = htmlentities($_POST['page'], ENT_COMPAT, 'utf-8');

		if ($navPage == 'Home')
			$pageID = 0;
		else if ($navPage == 'End')
			$pageID = count($book->pages)-1;
		else if ( $navPage == $tPrev && $pageID > 0 )
			$pageID--;
		else if ( $navPage == $tNext && $pageID < count($book->pages)-1 )
			$pageID++;

		$page = &$book->pages[$pageID];

		$prevAtt = $pageID == 0 ? 'disabled="disabled"' : '';
		$nextAtt = $pageID >= count($book->pages)-1 ? 'disabled="disabled"' : '';
;echo '		<div class="wrap">
			<h2>'; _e('Page Properties', 'pageFlip'); ;echo '</h2>

			<link rel="stylesheet" type="text/css" href="'; echo WP_PLUGIN_URL.'/'.$this->plugin_dir; ;echo '/css/pageflip-admin.css" />

			<form id="pageForm" action="#" method="post" style="margin:1em 0 3em 0;">
				<input type="hidden" name="action" value="'; _e('Page Properties', 'pageFlip'); ;echo '" />
				<input type="hidden" name="id" value="'; echo $bookID; ;echo '" />
				<input type="hidden" name="pageId" value="'; echo $pageID; ;echo '" />
				<input type="hidden" name="zoom" value="'; echo $zoom ? $tZoomIn : $tZoomOut; ;echo '" />
				<input type="hidden" name="pageWidth" value="'; echo $pageWidth; ;echo '" />
				<input type="hidden" name="pageHeight" value="'; echo $pageHeight; ;echo '" />

				<a name="1"></a>
				<table style="clear:both; margin:1em 0 1.5em 0; /*width:'; echo $pageWidth ; ;echo 'px;*/">
				<tr>
					<td align="left" width="10%">
						<label for="pageName">'; _e('Name', 'pageFlip'); ;echo '</label>
					</td>
					<td align="left" width="65%">
						<input type="text" id="pageName" name="pageName" value="'; echo $book->pages[$pageID]->name; ;echo '" size="40" style="width:90%;" />
					</td>
					<td align="center" width="25%" style="white-space:nowrap;">
						<input type="submit" class="button-primary" id="saveButton" name="save" value="'; _e('Update', 'pageFlip'); ;echo '" />
						<input type="submit" class="button" id="cancelButton" name="cancel" value="'; _e('Cancel', 'pageFlip'); ;echo '" />
					</td>
				</tr>
				</table>

			'; if ($this->functions->getExt($page->image) != 'swf') : ;echo '				<div style="height:2.5em; margin:0.5em 0;"><a class="button" href="#" style="font-weight:bold;" onclick="this.parentNode.style.display=\'none\'; jQuery(\'#writeText\').fadeIn(750); return false;">Write Text</a></div>
				<fieldset id="writeText" class="text widefat" style="display:none; float:left; width:auto; margin:0 0 1.5em 0; padding:0.5em 1em 1em; background:none;">
					<legend>Write Text</legend>

					'; echo $this->html->fontPanel('fontPanel', array('color'=>'CC0000')); ;echo '					<div>
						<textarea id="textToWrite" name="textToWrite" rows="3" style="width:100%;"></textarea>
					</div>
					<input type="hidden" id="textLeft" name="textLeft" value="" />
					<input type="hidden" id="textTop" name="textTop" value="" />
				</fieldset>
				<div class="clear"></div>

				';  ;echo '
				<div style="height:2em; /*margin:-2em 0 0 0;*/ padding-top:0.2em;">
					<input type="submit" class="button" name="zoom" value="'; echo !$zoom ? $tZoomIn : $tZoomOut; ;echo '" />
				</div>

				<div id="pageView" style="width:'; echo $pageWidth ; ;echo 'px; height:'; echo $pageHeight; ;echo 'px; padding:4px; background:'; echo $pageBack; ;echo '; border:2px outset '; echo $pageBack; ;echo ';">
';
					

					
					echo "<div class='active page' id='active_page' style='display:block; position:relative; float:left; width:{$pageWidth}px; height:{$pageHeight}px;'>";
					$this->page_img( $book, $book->pages[$pageID], $zoom );
					echo "<div id='text_001' style='float:left; cursor:move; font-family:Arial,Helvetica,sans-serif; position:absolute; left:0; top:0;'></div>";
					echo "</div>";

					
;echo '				</div>
				<div class="clear"></div>

				<div style="width:'; echo $pageWidth * 2 - 18; ;echo 'px; padding:0 15px; text-align:'; echo $pageID % 2 ? 'left' : 'right'; ;echo ';">
					'; if ($zoom) : ;echo '<big>'; endif; ;echo '						<a href="'; echo $url = $zoom ? $image['zoomURL'] : $image['imageURL']; ;echo '" target="_blank" style="text-decoration:none;">
							'; echo $pageFlip->functions->getExt($url); ;echo '						</a>
					'; if ($zoom) : ;echo '</big>'; endif; ;echo '				</div>
			</form>

			<script type="text/javascript">
			//<![CDATA[
				function editPage(id)
				{
					var form = document.getElementById(\'pageForm\');
					form.elements.pageId.value = id;
					form.submit();
				}

				function fontToFamily(font)
				{
					f = font.toLowerCase();
					switch (f)
					{
						case \'arial\':
							return "Arial, Helvetica, sans-serif";
						case \'times\':
							return "\'Times New Roman\', Times, serif";
						default:
							return font;
					}
				}


				var
					page = document.getElementById(\'active_page\'),
					text = document.getElementById(\'text_001\'),
					textToWrite = document.getElementById(\'textToWrite\'),
					fontFamily = document.getElementById(\'fontPanel_fontFamily\'),
					fontSize = document.getElementById(\'fontPanel_fontSize\'),
					color = document.getElementById(\'fontPanel_color\');

				textToWrite.onkeyup = function ()
				{
					text.innerHTML = textToWrite.value.replace(/\\n/g, \'<br />\');
				};
				textToWrite.onkeyup();

				fontFamily.onchange = function ()
				{
					text.style.fontFamily = fontToFamily(fontFamily.value);
				};
				fontFamily.onchange();

				fontSize.onchange = function ()
				{
					text.style.fontSize = fontSize.value + \'px\';
				};
				fontSize.onchange();

				color.onchange = function ()
				{
					text.style.color = \'#\' + color.value;
				};
				color.onchange();

				jQuery(document).ready(function($)
				{
					$(\'#text_001\').draggable(
					{
						containment: \'parent\',
						stop: function (event, ui)
						{
							document.getElementById(\'textLeft\').value = Math.round(ui.position.left);
							document.getElementById(\'textTop\').value = Math.round(ui.position.top);
						}
					});
				});

			//]]>
			</script>
		'; endif; ;echo '
		</div>
';
	}

	
    function manage_books()
    {
    	global $wpdb;

    	if ( !empty($_POST['action']) && $_POST['action'] == __('Page Properties', 'pageFlip') )
    	{
    		if ( !empty($_POST['save']) )
    		{
    			$book = new Book($_POST['id']);
    			$book->load();

    			$book->pages[$_POST['pageId']]->name = htmlspecialchars($_POST['pageName']);

    			if ( trim($_POST['textToWrite']) )
    			{
	    			$book->pages[$_POST['pageId']]->writeText(
	    				$_POST['textToWrite'],
	    				array(
	    					'pageWidth' => $_POST['pageWidth'],
	    					'pageHeight' => $_POST['pageHeight'],
	    					'left' => $_POST['textLeft'],
	    					'top' => $_POST['textTop'],
	    					'fontFamily' => $_POST['fontFamily'],
	    					'fontSize' => $_POST['fontSize'],
	    					'color' => $_POST['color']
	    				),
	    				strstr($_POST['zoom'], __('Zoom In', 'pageFlip')) ? true : false
	    			);
    			}

    			$book->save();
    		}
    		else if ( empty($_POST['cancel']) )
    		{
    			return $this->edit_page();
    		}
    	}

    	echo '<div class="wrap">';

    	if( defined( 'PAGEFLIP_ERROR' ) )
		{
			echo PAGEFLIP_ERROR . '</div>';
			return false;
		}

		echo '<noscript>'.$this->functions->errorMessage( 'JavaScript is disabled. Please, enable JavaScript for correctly work.' ).'</noscript>';

		
		if( !empty( $_POST['thisdo'] ) ) $_POST['do'] = $_POST['thisdo'];

    	if( isset( $_POST['actionButton'] ) )
			switch( $_POST['action'] )
	        {
	         	case 'addbook' : $this->add_book(); break;
	         	case 'editbook' : $this->edit_book(); break;
	         	case 'addpage' : $this->add_page( $_POST['imageId'], $_POST['type'] ); break;
	         	case 'Assign Selected Images to Page' :
	         	{
	         		if( count( $_POST['images'] ) > 0 )
					 foreach( $_POST['images'] as $imageId )
	         			if( !$this->add_page( $imageId, $_POST['type'] ) ) break;
	         		unset( $_POST['do'] );
	         	} break;
	         	case 'Assign Images from Gallery' :
	         	{
	         		$this->addPageFromGallery( $_POST['galleryId'], $_POST['type'] );
	         		unset( $_POST['do'] );
	         	} break;
	         	case 'uploadimage' :
	         		if( ( $_POST['do'] == 'New Page' ) )
	         		{
	         			$imagesId = $this->upload_image( 'New page' );
	         			if( count( $imagesId ) > 1 )
	         			{
	         				foreach( $imagesId as $imageId )
	         					if( !$this->add_page( $imageId, $_POST['type']  ) ) break;
	         				unset( $_POST['do'] );
	         			}
	         		}
	         	 	break;
         	 	case 'Delete Book' :
         	 		$this->delete_book( $_POST['id'] );
         	 		break;
	        }

        if( isset( $_POST['do'] ) )
         switch( $_POST['do'] )
         {
         	case __('Book Properties', 'pageFlip') :
         		$this->book_form( $_POST['id'] );
         		break;

         	case __('Add Page', 'pageFlip') :
         	case 'New Page' :
         		echo $this->functions->printHeader( 'New Page to book #' . $_POST['id'] );
         		if( isset( $_POST['imageId'] ) && $_POST['action'] == 'Assign Image to Page' && isset( $_POST['actionButton'] ) )
         			$this->add_page_form( $_POST['id'], $_POST['imageId'], $_POST['type'] );
         		elseif( ($_POST['action'] == 'uploadimage') && ( count( $imagesId ) == 1 ) )
         			$this->add_page_form( $_POST['id'], $imagesId[0] );
         		else
				{
					echo '<div id="addPageMenu">' . $this->html->addPageMenu() . '</div>';
			   	    $tButtonName = __('Create New Gallery', 'pageFlip');
			   	    $tUploadButton = __('Upload New Images', 'pageFlip');
			    	$tGalleryName = __('Gallery Name', 'pageFlip');
			    	$tAddGallery = __('Add Gallery', 'pageFlip');
					$text = "
						<form method=\"post\" name=\"addGalleryForm\" id=\"addGalleryForm\" action=\"\" style=\"margin:1em 0;\">
							<input type=\"hidden\" name=\"action\" value=\"add_gallery\">
							<input class=\"button\" id=\"createGalleryButton\" name=\"button\" value=\"{$tButtonName}\" type=\"button\" onclick=\"viewAddGalleryForm(); return false;\" style=\"margin:0.5em 0 1.5em 0;\" />
							<div id=\"addNewGallery\" style=\"margin:0 0 1.5em 0;\">
								<label for=\"galleryName\">{$tGalleryName}</label>
								<input name=\"galleryName\" id=\"galleryName\" size=\"40\" type=\"text\" />
								<input class=\"button\" name=\"actionButton\" value=\"{$tAddGallery}\" type=\"submit\" onclick=\"addGallery( this.form ); return false;\" />
							</div>
							<script type=\"text/javascript\">//<![CDATA[
								document.getElementById('addNewGallery').style.display = 'none';
							//]]></script>
						</form>";
					echo $text;
					$this->galleriesList( $_POST['id'] );
					
				}
         		break;

         	case 'Upload New Images' :
         		echo $this->html->uploadImageForm( $_POST['id'] );
         		break;

         	case 'Add New Book' :
         		$this->book_form();
         		break;
         }
        else
        {
        	echo $this->functions->printHeader( __( 'Manage books and pages', 'pageFlip' ) );
        	echo $this->html->operationBookPreview();

			$this->books_list();

			echo $this->html->operationBookPreview( 'bottom' );
        }
        echo "</div>";
    }

    
    function images()
    {
        echo '<div class="wrap">';

		if( defined( 'PAGEFLIP_ERROR' ) )
		{
			echo PAGEFLIP_ERROR . '</div>';
			return false;
		}

		echo '<noscript>'.$this->functions->errorMessage( 'JavaScript is disabled. Please, enable JavaScript for correctly work.' ).'</noscript>';

		if ( isset($_POST['actionButton']) )
		{
			switch( $_POST['action'] )
	        {
	        	case 'add_gallery' :
	        		$this->addGallery(false);
	        		break;
	        	case 'addbook' :
	        		$this->add_book();
	        		break;
	        	case 'uploadimage' :
				 	{
				 		$this->upload_image();
				 		unset( $_POST['do'] );
				 	} break;
	        }
		}

		$do = empty($_POST['do']) ? '' : $_POST['do'];
		switch( $do )
        {
         	case 'Upload New Images' : echo $this->html->uploadImageForm(); break;
         	case 'Upload Image' : echo $this->html->uploadImageForm( $_POST['bookId'] ); break;
         	case __('Create Book', 'pageFlip') :
         		global $wpdb;
         		$gallery = $wpdb->get_row("SELECT * FROM {$this->table_gal_name} WHERE id='{$_POST['galleryId']}' ");
         		$this->book_form('', $gallery->id);
         		break;
         	default :
         	    {
	         		
		     		
		     		$this->galleriesList();
	     		}
        }
        echo '</div>';

    }

	
	function books_list()
	{
		global $wpdb;

        $list = $this->html->ajaxPreviewBook();
        $list .= '<div id="bookList">';
        $list .= $this->html->headerPreviewBook();

	    $sql = "SELECT `id`, `name`, `date` FROM `".$this->table_name."` ORDER BY `id`";
	    $books = $wpdb->get_results( $sql, ARRAY_A );

	    if( count($books) == "0" ) $list .= $this->html->noBooksPreviewBook();
        else foreach( $books as $curBook )
        {
        	 $creationDate = date( "m/d/Y", $curBook['date'] );

        	 $book = new Book( $curBook['id'] );

        	 $bookPreview = $this->bookPreview( $book );

             $list .= $this->html->previewBook ( $book, $curBook['name'], $creationDate, $bookPreview['first'], $bookPreview['second'] );
		}

        $list .= $this->html->footerPreviewBook();

        if( isset($_POST['id']) )
        {
        	$id = (int)$_POST['id'];
			$list .=
				"<script type='text/javascript'>//<![CDATA[
					pageList({$id});
				//]]></script>";
        }

		$list .= '</div>';

        echo $list;
	}

	function bookPreview( $book = '' )
	{
		if ( empty($book) )
		{
			$book = new Book( (int)$_POST['bookId'] );
			$ajax = true;
		}
		else
			$ajax = false;

		
		
		if (
			( $book->alwaysOpened == 'false' && (int)$book->firstPage % 2 == 1 ) ||
			( $book->alwaysOpened == 'true' && (int)$book->firstPage % 2 == 0 )
		)
		{
			$firstPage = (int)$book->firstPage;
			$secondPage = (int)$book->firstPage + 1;
		}
		else
		{
			$firstPage = (int)$book->firstPage - 1;
			$secondPage = (int)$book->firstPage;
		}

		$result['first'] = !empty($book->pages[$firstPage]) ? $this->functions->printImg( $book->pages[$firstPage]->image ) : '';
		$result['second'] = !empty($book->pages[$secondPage]) ? $this->functions->printImg( $book->pages[$secondPage]->image ) : '';

		if ($ajax)
		{
			echo $result['first'] . '<split>' . $result['second'];
			exit;
		}
		else
			return $result;
	}

	function replacePages()
	{
		$book = new Book( (int)$_POST['bookId'] );

		switch( $_POST['op'] )
		{
			case 'up':
				if ( (int)$_POST['pageId'] > 0 )
				{
					$book->pages[((int)$_POST['pageId'] - 1)]->number++;
					$book->pages[(int)$_POST['pageId']]->number--;
				}
				break;
			case 'down':
				if ( (int)$_POST['pageId'] < $book->countPages )
				{
					$book->pages[(int)$_POST['pageId']]->number++;
					$book->pages[((int)$_POST['pageId'] + 1)]->number--;
				}
				break;
			default:
				$pages = split( ';', $_POST['pages'] );

				for ( $i = 0; $i < $book->countPages; $i++ )
					$book->pages[(int)$pages[$i]]->number = $i;
		}

		$book->refreshPages(); 
		$book->save();

		exit();
	}

	function pagesList()
	{
        global $wpdb;

        $list  = $this->html->headerPreviewPage( (int)$_POST['bookId'] );

        $book = new Book( (int)$_POST['bookId'] );

		if ( (int)$book->countPages === 0 )
			$list .= $this->html->noPagesPreviewPage();
		else
		{
			foreach( $book->pages as $id=>$page )
			{
				if ( trim($book->alwaysOpened) == 'false' )
					$side = ($id % 2 == 0) ? 'right' : 'left';
				else
					$side = ($id % 2 == 0) ? 'left' : 'right';

				$list .=
					$this->html->previewPage(
						(int)$_POST['bookId'], $page, $side,
						$this->functions->printImg($page->image, $page->number, $this->thumbWidth, $this->thumbHeight, true),
						$book->countPages
					);
			}
		}

		$list .= $this->html->footerPreviewPage();

        echo $list;
        exit();
	}

	
	function add_book()
	{
        global $wpdb;

        foreach( $_POST as $key=>$value )
        {
        	$_POST["$key"] = trim( $value ); 
        	$_POST["$key"] = stripslashes( $value );
			$_POST["$key"] = htmlspecialchars( $value );
			$_POST["$key"] = $wpdb->escape( $value ); 
        	
        }

        if ( empty($_POST['bookName']) )
        	$_POST['bookName'] = 'unnamed';

        
        if ( !empty($_FILES['image']['name'][0]) )
        	$imageId = $this->upload_image('bgImage');
        else
        	$imageId[0] = $_POST['bgImage'];

        
        $sql = "INSERT INTO `".$this->table_name."` (`name`, `date`, `bgImage`) VALUES ('".$_POST['bookName']."', '".date("U")."', '".$imageId[0]."')";
        $wpdb->query( $sql );

        $id = $wpdb->get_var( "SELECT LAST_INSERT_ID();", 0, 0 );

        
        $_POST['flipSound'] = $this->add_sound();

        
        $newBook = new Book();

		$newBook->id = $id;
		foreach( $newBook->properties as $property )
			if( !empty( $_POST[$property] ) || $property == 'flipSound' )
				$newBook->$property = $_POST[$property];

		
        if( !$newBook->save() )
        {
        	
        	$sql = "delete from `" . $this->table_name . "` where `id` = '" . $id . "'";
        	$wpdb->query( $sql );

        	echo __('Adding book error! Please setup permission to the books/ , images/ , sounds/ folders and include files to &quot;777&quot;', 'pageFlip');
		    return 0;
        }

		if ($galleryId = $_POST['galleryId'])
		{
			$images = $wpdb->get_results("SELECT * FROM `{$this->table_img_name}` WHERE `gallery`='{$galleryId}' ORDER BY `name` ASC");
			foreach ($images as $image)
			{
				$_POST['id'] = $id;
				$this->add_page($image->id, $image->type);
			}
			echo "<script type='text/javascript'>location.href='?page={$this->plugin_dir}/books';</script>";
		}
	}

	
	function add_page( $imageId, $type )
	{
        global $wpdb;

        $book = new Book( $_POST['id'] );

        $imageName = isset($_POST['name']) ? htmlspecialchars( stripslashes( $_POST['name'] ) ) : NULL;
        $zoomURL = '';

        switch( $type )
        {
        	case 'WPMedia' : {
        		$uploads = wp_upload_dir();
				$location = get_post_meta( $imageId, '_wp_attached_file', true );

				
				$image_path = $uploads['basedir'].'/'.$location;
				$new_url = $this->imagesUrl.basename($location);
				$new_path = $this->plugin_path.$this->imagesDir.'/'.basename($location);

				$_POST['galleryId'] = 0;
				$this->copyImage($new_path, $image_path, filesize($image_path), 'img', basename($location), 'copy');
        		$sql = "SELECT `filename` FROM `{$this->table_img_name}` WHERE `id` = '{$wpdb->insert_id}'";
	    		$img = $wpdb->get_row( $sql, ARRAY_A, 0 );
				$image = $this->functions->getImageUrl( $img['filename'] );
    			$filename = $img['filename'];
        	} break;
        	case 'NGGallery' : {
				$sql = "SELECT `filename`, `galleryid`, `alttext` FROM `{$wpdb->prefix}ngg_pictures` WHERE `pid` = '{$imageId}'";
				$img = $wpdb->get_row($sql, ARRAY_A);
    			$filename = $img['filename'];
				$sql = "select `path` from `{$wpdb->prefix}ngg_gallery` WHERE `gid` = '{$img['galleryid']}'";
				$path = $wpdb->get_var( $sql );

    			
    			$image_path = ABSPATH.$path.'/'.$img['filename'];
    			$new_url = $this->imagesUrl.$img['filename'];
    			$new_path = $this->plugin_path.$this->imagesDir.'/'.$img['filename'];

				$_POST['galleryId'] = 0;
				$this->copyImage($new_path, $image_path, filesize($image_path), 'img', $img['filename'], 'copy');
        		$sql = "SELECT `filename` FROM `{$this->table_img_name}` WHERE `id` = '{$wpdb->insert_id}'";
	    		$img = $wpdb->get_row( $sql, ARRAY_A, 0 );
				$image = $this->functions->getImageUrl( $img['filename'] );
        	} break;
        	default : {
        		$sql = "SELECT `filename`, `name` FROM `{$this->table_img_name}` WHERE `id` = '{$imageId}'";
	    		$img = $wpdb->get_row( $sql, ARRAY_A, 0 );
	    		$image = $this->functions->getImageUrl( $img['filename'] );
	    		if ( file_exists($this->imagesPath.'z_'.$img['filename']) )
	    			$zoomURL = $this->functions->getImageUrl( 'z_'.$img['filename'] );
    			$filename = $img['name'];
	    	}
        }

    	if (!$imageName)
    	{
    		preg_match('|(.*)\..*?|', $filename, $m);
    		$imageName = $m[1];
    	}


    	$book->pages[$book->countPages] = new Page( $image, $book->countPages, $imageName, $zoomURL );

        if( !$book->save() )
        {
        	echo __('Save file error! Please setup permission to the books/ , images/ , sounds/ folders and include files to &quot;777&quot;', 'pageFlip');
        	return false;
        }

        return true;
	}

	
	
	function addPageFromGallery( $galleryId, $type )
	{
        global $wpdb;

        switch( $type )
		{
			case 'NGGallery' :
				$sql = "SELECT `pid` AS id FROM `{$wpdb->prefix}ngg_pictures` WHERE `galleryId` = '{$galleryId}'";
			 break;
			default :
				$sql = "SELECT `id` FROM `{$this->table_img_name}` WHERE `type` = 'img' AND `gallery` = '{$galleryId}' ORDER BY `name` ASC";
		}

		$images = $wpdb->get_results( $sql, ARRAY_A );
		if( count( $images ) > 0 )
			foreach( $images as $img )
				$this->add_page( $img['id'], $type );
	}

	
	function addGallery($exit = true)
	{
		global $wpdb;

		$name = $wpdb->escape( $_POST['galleryName'] );

		$sql = "INSERT INTO `".$this->table_gal_name."` (`name`, `date`, `preview`) VALUES ('".$name."', '".date("U")."', 0)";
		$wpdb->query( $sql );

		if ($exit)
			exit;
	}

	
	function upload_image( $action='' )
	{
        global $wpdb;

        $imagesId = array();

	    
	    if( !empty($_POST['url']) && $_POST['uploadFormType']=='uploadFromUrlForm' )
        {
	       	
	       	if ( !$this->functions->isUrl($_POST['url']) )
	       	{
	       		$txt = '<strong>' . $_POST['url'] . '</strong> - <strong>' . __('Error', 'pageFlip') . '</strong>: ' . __('Incorrect url', 'pageFlip') . '<br />';
				echo $this->functions->errorMessage( $txt );
				return false;
	       	}

	       	if ( !$this->functions->checkImage($_POST['url']) )
	       		return false;

	       	$type = 'img';

			if ( empty($_POST['name']) )
				$_POST['name'] = basename( $_POST['url'] );

			$_POST['name'] = $wpdb->escape(htmlspecialchars(stripslashes( $_POST['name'] ))); 
			
		    $sql = "INSERT INTO `{$this->table_img_name}` (`name`, `filename`, `date`, `type`, `gallery`) VALUES ('{$_POST['name']}', '{$_POST['url']}', '".date("U")."', '{$type}', '{$_POST['galleryId']}')";
		    $res = $wpdb->query($sql);

	        if ( ($action == 'New page') || ($action == 'bgImage') )
	        {
	          	$sql = "SELECT LAST_INSERT_ID();";
	          	$imagesId[] = $wpdb->get_var( $sql, 0, 0 );
	        }

	        return true;
        }

        if ( $_POST['uploadFormType']=='uploadSwfForm' )
        {
        	$_POST['folder'] = str_replace(ABSPATH, '', $this->plugin_path.'upload/');
        }

		if( !empty( $_POST['folder'] ) && ($_POST['uploadFormType']=='uploadFromFolder' || $_POST['uploadFormType']=='uploadSwfForm') )
		{
			$curDir = ABSPATH . $_POST['folder'];
			if( is_dir($curDir) )
			{
				$dir = opendir($curDir); 

				for ($n_files = 0; $file = readdir($dir); )
					if ( is_file($curDir . $file) ) $n_files++;

				$dir = opendir($curDir); 

				ob_start();

				$i = 0;
				while ( $file = readdir($dir) )
				{
					if ( is_file( $curDir . $file ) )
					{
						$i++;
						$progress = "($i / $n_files)";
						echo "<p style='margin:0.5em 0;'>Loading <strong>". $curDir . $file ."</strong> {$progress} ";

						$size = filesize( $curDir . $file );
						$id = $this->copyImage( $file, $curDir . $file, $size, $action, '', 'rename' );
						if( $id ) $imagesId[] = $id;

						echo "</p>\n";
						ob_flush(); flush();
					}
				}
				closedir( $dir ); 
				echo '<script type="text/javascript">location.href=location.href;</script>';
			}
		}

		
		if( !empty( $_FILES['zip']['name'] ) && $_POST['uploadFormType']=='uploadZipForm' )
        {
        	@ini_set('memory_limit', '256M');

			
			if( $_FILES['zip']['type'] != 'application/zip'
				&& $_FILES['zip']['type'] != 'application/x-zip-compressed' )
				 {
				 	$txt = '<strong>' . $_FILES['zip']['name'] . '</strong> - <strong> ' . __('Error', 'pageFlip') . '</strong>: ' . __('This is not a zip file', 'pageFlip') . '<br />';
				    echo $this->functions->errorMessage( $txt );
					return false;
				 }

			if( ! class_exists( 'PclZip' ) )
			   require_once( ABSPATH . 'wp-admin/includes/class-pclzip.php' );

			
			$dir =  $this->plugin_path . $this->imagesDir . '/';
			$archiveName = $dir . basename( $_FILES['zip']['tmp_name'] );
			$folderName = $archiveName . '_folder/';
			copy( $_FILES['zip']['tmp_name'], $archiveName );

			
			$zip = new PclZip( $archiveName );

			
			$extractFiles = $zip->extract( PCLZIP_OPT_PATH, $folderName );

			
			if( $extractFiles == 0 )
			{
			 	$txt = '<strong>' . $_FILES['zip']['name'] . '</strong> - <strong>' . __('Extracting Error', 'pageFlip') . '</strong><br />';
			    echo $this->functions->errorMessage( $txt );
				return false;
			}

			
			foreach ( $extractFiles as $image )
			{
				$id = $this->copyImage( $image['stored_filename'], $image['filename'], $image['size'], $action, '', 'rename' );
	            if( $id ) $imagesId[] = $id;
			}

			
			@unlink( $archiveName );
			
			$this->functions->removeDir( $folderName );
        }

        
		if ( !empty($_FILES['image']['name']) && $_POST['uploadFormType']=='uploadImgForm' )
		{
			foreach ($_FILES['image']['name'] as $id => $imageName)
	        {
	            if ( !empty($imageName) )
	            {
		        	$zoomImageName = empty($_FILES['zoomImage']['tmp_name'][$id]) ? '' : $_FILES['zoomImage']['tmp_name'][$id];
		        	$id = $this->copyImage( $imageName, $_FILES['image']['tmp_name'][$id], $_FILES['image']['size'][$id], $action, $_POST['name'][$id], 'move_uploaded_file', $zoomImageName );
		            if ($id) $imagesId[] = $id;
	            }
	        }
		}

        unset( $_POST['name'] ); 

	    return $imagesId;
	}

	
	function copyImage( $imageName, $tmpName, $size, $action = 'img', $name = '', $functionName = 'move_uploaded_file', $zoomImageName = '' )
	{
	   global $wpdb;

	   
	   if( $size == 0 )
	   {
	   	
		
		return false;
	   } 

	   if( !$this->functions->checkImage( $imageName ) ) return false;

	   
	   switch( $action )
	   {
	   		case 'bgImage' :
	   			$type = 'bg';
	   			break;
	   		default :
	   			$type = 'img';
	   }

	   
	   preg_match('/.*\.(.*)$/', $imageName, $fileExt);

	   
       $dir =  $this->plugin_path . $this->imagesDir . '/';
       

	   
       
       

	   
		do
		{
			$filename = $this->functions->fileName( $type, $imageName );
			$new_filename = $dir . $filename;
		}
		while( file_exists( $new_filename ) );

		
	    $thumbName = $dir . 't_' . basename( $new_filename );
	    $imgSize = $this->functions->getImageSize( $tmpName );
	    $newSize = $this->functions->imgSize( $imgSize[0], $imgSize[1], $this->thumbWidth, $this->thumbHeight );

	    $zoomName = $dir. 'z_'.basename($new_filename);

		switch( strtolower( $fileExt['1'] ) )
        {
        	case 'swf' :
        		if( !$functionName( $tmpName, $new_filename ) )
        		{
	            	@unlink( $new_filename );
	            	$txt = '<strong>' . $imageName . '</strong> - <strong>' . __('Error', 'pageFlip') . ' [001]</strong>: ' . __('Write file error! Please setup permission to the books/ , images/ , sounds/ folders and include files to &quot;777&quot;', 'pageFlip') . '<br/>';
	            	echo $this->functions->errorMessage( $txt );
					return false;
	            } break;
        	default :
        		if( !$this->functions->img_resize( $tmpName, $thumbName, $newSize['width'], $newSize['height'] )
	                || !$functionName( $tmpName, $new_filename ) )
	            {
	            	@unlink( $new_filename ); @unlink( $thumbName );
	            	$txt = '<strong>' . $imageName . '</strong> - <strong>' . __('Error', 'pageFlip') . ' [002]</strong>: ' . __('Write file error! Please setup permission to the books/ , images/ , sounds/ folders and include files to &quot;777&quot;', 'pageFlip') . '<br/>';
	            	echo $this->functions->errorMessage( $txt );
					return false;
	            }
	            if ($zoomImageName)
	            {
	            	$functionName($zoomImageName, $zoomName);
	            }
        }

        
		
		if( empty( $name ) ) $name = $imageName;
	    else $name = $wpdb->escape( $name ); 

	    
	    if ( empty($_POST['galleryId']) )
	    	$_POST['galleryId'] = '0';

	    $sql = "insert into `".$this->table_img_name."` (`name`, `filename`, `date`, `type`, `gallery`) values ('".$name."', '".basename($new_filename)."', '".date("U")."', '".$type."', '".$_POST['galleryId']."')";
	    $wpdb->query( $sql );

        if( ($action == 'New page') || ($action == 'bgImage') )
        {
          	$sql = "SELECT LAST_INSERT_ID();";
          	return $wpdb->get_var( $sql, 0, 0 );
        }

		return false;
	}

	
	function uploadForm()
	{
		echo $this->html->uploadImageMenu();
		echo '<split>';

		switch( $_POST['type'] )
		{
			case 'swfUpload' : echo $this->html->uploadSwfForm(); break;
			case 'zip' : echo $this->html->uploadZipForm(); break;
			case 'fromUrl' : echo $this->html->uploadFromUrlForm(); break;
			case 'fromFolder' : echo $this->html->uploadFromFolder(); break;
			default : echo $this->html->uploadImgForm();
		}

		exit;
	}

	
	function edit_book()
	{
        global $wpdb;

        foreach($_POST as $key=>$value)
        {
        	$_POST[$key] = trim( $value );
        	$_POST[$key] = stripslashes( $value );
			$_POST[$key] = htmlspecialchars( $value );
			$_POST[$key] = $wpdb->escape($value); 
        	
        }

        if ( empty($_POST['bookName']) ) $_POST['bookName'] = 'unnamed';

        
        if( !empty($_FILES['image']['name'][0]) ) $imageId = $this->upload_image( "bgImage" );
        else $imageId[0] = $_POST['bgImage'];

        
        $sql = "UPDATE `".$this->table_name."` SET `name` = '".$_POST['bookName']."', `bgImage` = '".$imageId[0]."' WHERE `id` = '".$_POST['bookId']."'";
        $wpdb->query( $sql );

        
        $_POST['flipSound'] = $this->add_sound();;

        $book = new Book( $_POST['bookId'] );

        
        foreach( $book->properties as $property )
			if( isset($_POST[$property]) && (string)$_POST[$property] !== '' || $property == 'flipSound' )
				$book->$property = $_POST[$property];

		
        if( !$book->save() )
        {
        	$txt = __('Save file error! Please setup permission to the books/ , images/ , sounds/ folders and include files to &quot;777&quot;', 'pageFlip');
        	echo $this->functions->errorMessage( $txt );
			return false;
        }
	}

	
	function delete_book($bookId)
	{
        global $wpdb;

        @unlink($this->plugin_path . $this->booksDir . '/' . $bookId . '.xml');

        
        $sql = "DELETE FROM `".$this->table_name."` WHERE `id` = '".$bookId."'";
        
        $wpdb->query($sql);

        unset($_POST['do']);

        
        
	}

	
    function images_list( $bookId = 0, $gallery = 0 )
    {
    	global $wpdb;

    	if ( isset($_POST['bookId']) )
    		$bookId = $_POST['bookId'];

    	if ( isset($_POST['gallery']) )
    		$gallery = $_POST['gallery'];

    	if( (int)$_POST['page'] < 1 ) $_POST['page'] = 1;

		$navigation = $this->functions->navigationBar( $_POST['page'], get_option( 'pageFlip_imgPerPage' ), $_POST['type'], '', $gallery );

    	$start = ( $navigation['page'] - 1 ) * get_option( 'pageFlip_imgPerPage' );

    	switch( $_POST['type'] )
    	{
    		case 'NGGallery' : {
    			$sql = "SELECT `title` FROM `".$wpdb->prefix."ngg_gallery` WHERE `gid` = '".$gallery."'";
		    	$galleryName = $wpdb->get_var($sql);
    		} break;
    		case 'pageFlip' : {
    			if( (int)$gallery === 0 ) $galleryName = __('Unsorted', 'pageFlip');
		    	else
		    	{
		    		$sql = "SELECT `name` FROM `".$this->table_gal_name."` WHERE `id` = '".$gallery."'";
		    		$galleryName = $wpdb->get_var($sql);
		    	}
    		} break;
    	}

    	$list = '';

		if( $_POST['type'] === 'pageFlip' || $_POST['type'] === 'NGGallery' )
    	{
			$header = '<a href="#" onclick="return viewGalleries();">' . __('Galleries', 'pageFlip') . '</a> -> ' . __('Images from gallery', 'pageFlip') . ' &quot;'. $galleryName . '&quot;';

			if( (int)$bookId === 0 )
				$list .= $this->functions->printHeader( $header );
			else
				$list .= '<p style="font-size: medium;">' . $header . '</p>';
		}
    	
    	

 		

    	

		$list .= $this->html->operationPreviewImage( $bookId, 'top', $navigation, $_POST['type'], $gallery );
		$list .= $this->html->headerPreviewImage();
        $list .= $this->viewImagesList( $bookId, $start, get_option('pageFlip_imgPerPage'), $_POST['type'], $gallery );
		$list .= $this->html->footerPreviewImage();
		$list .= $this->html->operationPreviewImage( $bookId, 'bottom', $navigation, $_POST['type'], $gallery );

		$list .= '<div style="clear:both">&nbsp;</div>';

        echo $list;

        if( isset( $_POST['bookId'] ) )
		{
			echo '<split>' . $navigation['page'];
			exit;
		}
    }

    
    function galleriesList( $bookId = 0 )
    {
    	global $wpdb;

    	$list = '';

		if( isset( $_POST['bookId'] ) )
		{
			$bookId = $_POST['bookId'];
			$type = $_POST['type'];
		}
		else
		{
    	  	$list = $this->html->ajaxPreviewImage( $bookId );
    	  	$type = 'pageFlip';

    		$list .= '<div id="addPage">';
    	}

    	if( (int)$bookId === 0 )
			$list .= $this->functions->printHeader( __('Galleries', 'pageFlip') );

    	$list .= '<div id="pageFlipTop">';

		if( (int)$bookId === 0 )
			$list .= $this->html->operationPreviewGallery( $bookId );
		else $list .= '&nbsp;';

		$list .= '</div>';

    	$list .= '<div id="pageFlipList">';

 		
 		$list .= $this->html->headerPreviewGallery();

        $list .= $this->viewGalleriesList( $bookId, $type );

		$list .= $this->html->footerPreviewGallery();

		$list .= '</div>';

		if( isset( $_POST['bookId'] ) )
		{
			echo $list;
			exit;
		}
		else
		{
			$list .= '</div>';
        	echo $list;
  		}
    }

    function pagingImages()
    {
    	if( (int)$_POST['page'] < 1 ) $_POST['page'] = 1;

		$navigation = $this->functions->navigationBar( $_POST['page'], get_option( 'pageFlip_imgPerPage' ), $_POST['type'] );

		echo $navigation['bar'];

    	echo '<split>';

    	$start = ( $navigation['page'] - 1 ) * get_option( 'pageFlip_imgPerPage' );

    	echo $this->viewImagesList( $_POST['bookId'], $start, get_option( 'pageFlip_imgPerPage' ), $_POST['type'], $_POST['gallery'] );

		
		

    	

		

		

        

		

		

		
		

		echo '<split>';

		echo $navigation['page'];

		exit;
    }

	
	function viewImagesList( $bookId = 0, $start = 0, $count = 0, $type = 'pageFlip', $gallery = 0 )
	{
		if ( $start < 0 ) $start = 0;
		if ( $count > 0 ) $limit = "LIMIT ".$start.", ".$count;
		else $limit = '';

		switch( $type )
		{
			case 'WPMedia' : return $this->viewWPMediaImgList( $bookId, $limit ); break;
			
			case 'NGGallery' : return $this->viewNGGalleryImgList( $bookId, $limit, $gallery ); break;
			default : return $this->viewPageFlipImgList( $bookId, $gallery, $limit );
		}
	}

	
	function viewGalleriesList( $bookId = 0, $type = 'pageFlip' )
	{
		global $wpdb;

		$result = '';

		switch( $type )
		{
			case 'NGGallery' : {
				$sql = "SELECT `gid` AS id, `path`, `title` AS name, `previewpic` FROM `".$wpdb->prefix."ngg_gallery`";
				$galleries = $wpdb->get_results($sql, ARRAY_A);
			} break;
			default : {
				$sql = "SELECT `id`, `name`, `date`, `preview` FROM `".$this->table_gal_name."` ORDER BY `name` ASC";
				$galleries = $wpdb->get_results($sql, ARRAY_A);
			}
		}

        
		if( count( $galleries ) > 0 )
		    foreach( $galleries as $gallery )
		    {
		       	$sql = $this->functions->sqlImgList( 'count', $type, $gallery['id'] );
		    	$countImg = $wpdb->get_var( $sql );

		    	if( $countImg > 0 )
		    		$imageUrl = $this->functions->getGalleryPreview( $gallery['id'], $type );
		    	else $imageUrl = '';

				if( $type === 'pageFlip' ) $creationDate = date( "d/m/Y", $gallery['date'] );
				else $creationDate = '';

		        $result .= $this->html->previewGallery( $bookId, $gallery['id'], $gallery['name'], $countImg, $creationDate,
		        										 $this->functions->printImg( $imageUrl, '', '', '', true ), $type );
		    }

	    if ($type === 'pageFlip')
	    {
			$sql = $this->functions->sqlImgList('count', $type, 0);
			$count = $wpdb->get_var($sql);

		    if ( (int)$count > 0 )
		    {
				$sql = "SELECT `filename` FROM `".$this->table_img_name."` WHERE `type` = 'img' AND `gallery` = '0' ORDER BY RAND() LIMIT 1";
		    	$imageUrl = $this->functions->getImageUrl( $wpdb->get_var($sql) );

				$result .= $this->html->previewGallery( $bookId, 0, __('Unsorted', 'pageFlip'), $count, '',
		        										 $this->functions->printImg( $imageUrl, '', '', '', true ) );
		    }
	    }
	    elseif ( count($galleries) == 0 )
			$result = "<tr class=\"alternate author-self status-publish\" valign=\"top\">
				          <td colspan=\"5\" style=\"text-align: center;\"><strong>" . __('No galleries', 'pageFlip') ."</strong></td>
		        	   </tr>";

		return $result;
	}

	
	function viewAlbumsList( $bookId = 0, $start = 0, $count = 0, $type = 'pageFlip' )
	{
		if ($start < 0) $start = 0;
		if ($count > 0) $limit = "limit ".$start.", ".$count;
		else $limit = '';

		switch( $type )
		{
			case 'WPMedia' : return $this->viewWPMediaImgList( $bookId, $limit ); break;
			
			case 'NGGallery' : return $this->viewNGGalleryImgList( $bookId, $limit ); break;
			default : return $this->viewPageFlipImgList( $bookId, $limit );
		}
	}

	
	function viewPageFlipImgList( $bookId, $gallery, $limit )
	{
		global $wpdb;

		$result = '';

		$sql = $this->functions->sqlImgList( 'list', 'pageFlip', $gallery ).$limit;

		$images = $wpdb->get_results($sql, ARRAY_A);
        if( count($images) == "0" ) $result = "<tr class=\"alternate author-self status-publish\" valign=\"top\">
	                 					          <td colspan=\"5\" style=\"text-align: center;\"><strong>" . __('No images', 'pageFlip') ."</strong></td>
					  				            </tr>";
	    else foreach($images as $img)
	    {
	       	$imageUrl = $this->functions->getImageUrl( $img['filename'] );

			$uploadDate = date( "d/m/Y", $img['date'] );
	        $result .= $this->html->previewImage( $bookId, $img['id'], $img['name'], $uploadDate,
	        										 $this->functions->printImg( $imageUrl, $img['name'], '', '', true ), $gallery );
	    }

		return $result;
	}

	
	function viewWPMediaImgList( $bookId, $limit )
	{
		global $wpdb;

		$result = '';

    	$uploads = wp_upload_dir();

    	$sql = $this->functions->sqlImgList( 'list', 'WPMedia' ).$limit;
		$WPImages = $wpdb->get_results($sql, ARRAY_A);
		if ( count($WPImages) == 0 ) {
			$result =
				"<tr class=\"alternate author-self status-publish\" valign=\"top\">" .
				"<td colspan=\"5\" style=\"text-align: center;\"><strong>" . __('No images', 'pageFlip') ."</strong></td>" .
				"</tr>";
		}
		else {
			foreach ($WPImages as $img)
			{
				$location = get_post_meta( $img['post_id'], '_wp_attached_file', true );
	    		$filetype = wp_check_filetype( $location );

				if ( ( substr($filetype['type'], 0, 5) == 'image' ) && ( $thumb = wp_get_attachment_image( $img['post_id'], array(80, 60), true ) ) )
	    		{
					$att_title = wp_specialchars( _draft_or_post_title( $img['post_id'] ) );
	    			$result .= $this->html->previewImage( $bookId, $img['post_id'], $att_title, '', $thumb, 'WPMedia' );
	    		}
			}
		}
		return $result;
	}

	
	function viewNGGalleriesList( $bookId )
	{
		global $wpdb;

		$result = '';

    	$sql = "select `gid`, `path`, `title`, `previewpic` from `".$wpdb->prefix."ngg_gallery`";
		$NGGalleries = $wpdb->get_results($sql, ARRAY_A);
		if ( count($NGGalleries) == 0 ) {
			$result =
				"<tr class=\"alternate author-self status-publish\" valign=\"top\">" .
				"<td colspan=\"5\" style=\"text-align: center;\"><strong>" . __('No galleries', 'pageFlip') ."</strong></td>" .
				"</tr>";
		}
		else {
			foreach ($NGGalleries as $gallery) {
				$sql = "SELECT `filename` FROM `".$wpdb->prefix."ngg_pictures` where `pid` = '".$gallery['previewpic']."'";
				$imageUrl = $this->siteURL.'/' . $gallery['path'] . '/thumbs/thumbs_' . $wpdb->get_var( $sql );
				$result .= $this->html->previewImage( $bookId, $gallery['pid'], $gallery['title'], '',
				$this->functions->printImg( $imageUrl, $gallery['title'], '', '', true ) , 'NGGallery' );
			}
		}
		return $result;
	}

	
	function viewNGGalleryImgList( $bookId, $limit, $gallery )
	{
		global $wpdb;

		$result = '';

    	$sql = $this->functions->sqlImgList( 'list', 'NGGallery', $gallery ).$limit;
		$NGGImages = $wpdb->get_results($sql, ARRAY_A);
		if ( count($NGGImages) == 0 ) {
			$result =
				"<tr class=\"alternate author-self status-publish\" valign=\"top\">" .
				"<td colspan=\"5\" style=\"text-align: center;\"><strong>" . __('No images', 'pageFlip') ."</strong></td>" .
				"</tr>";
		}
		else {
			foreach ($NGGImages as $img) {
				$sql = "select `path` from `".$wpdb->prefix."ngg_gallery` where `gid` = '".$img['galleryid']."'";
				$imageUrl = $this->siteURL.'/' . $wpdb->get_var( $sql ) . '/thumbs/thumbs_' . $img['filename'];

				$result .= $this->html->previewImage( $bookId, $img['pid'], $img['alttext'], $img['imagedate'],
				$this->functions->printImg( $imageUrl, $img['alttext'], '', '', true ) , 'NGGallery' );
			}
		}
		return $result;
	}

	
	function addPageMenu()
	{
		echo $this->html->addPageMenu( $_POST['type'] );
		echo '<split>';
		echo $this->html->buttonsOpImages( $_POST['bookId'], $_POST['type'] );

		exit;
	}

	
	function setImgPerPage()
	{
		update_option( 'pageFlip_imgPerPage', (int)$_POST['count'] );
		exit;
	}

    
    function delete_page()
    {
		$book = new Book( $_POST['bookId'] );

        $book->deletePage( $_POST['pageId'] ); 

		
        $book->save();
    }

    
    function splitImage()
    {
		$book = new Book( $_POST['bookId'] );

		if( $this->functions->checkPic( $book->pages[(int)$_POST['pageId']]->image ) != 'pageFlip' ) exit;

        $image = $this->imagesPath . basename( $book->pages[(int)$_POST['pageId']]->image );
        $zoomImage = $this->imagesPath . basename( $book->pages[(int)$_POST['pageId']]->zoomURL );

		$newImages = $this->functions->splitImage( $image );
        if( !$newImages ) return false;

        $firstImage = $this->imagesUrl . basename( $newImages[0] );
        $secondImage = $this->imagesUrl . basename( $newImages[1] );

        if ( $zoomImage != $image )
        {
        	$newZoomImages = $this->functions->splitImage( $zoomImage );
	        $firstZoomImage = $this->imagesUrl . basename( $newZoomImages[0] );
	        $secondZoomImage = $this->imagesUrl . basename( $newZoomImages[1] );
        }
        else
        {
	        $firstZoomImage = '';
	        $secondZoomImage = '';
        }

        

        $book->pages[(int)$_POST['pageId']] = new Page( $firstImage, $_POST['pageId'], '', $firstZoomImage );

        for( $i = $book->countPages; $i > $_POST['pageId'] + 1; $i-- )
        {
        	$book->pages[$i] = $book->pages[($i - 1)];
        	$book->pages[$i]->number = $i;
        }

        $book->pages[($_POST['pageId'] + 1)] = new Page( $secondImage, ($_POST['pageId'] + 1), '', $secondZoomImage );

		$book->refreshPages(); 

		
        $book->save();

        exit;
    }

    
    function mergeImage()
    {
		$book = new Book( $_POST['bookId'] );

		$secondImage = substr( basename( $book->pages[(int)$_POST['pageId']]->image ), 2 );
		$mergeImage = $this->imagesUrl . $this->functions->getSplitImageName( $book->pages[(int)$_POST['pageId']]->image );

		@unlink( $this->imagesPath . basename( $book->pages[(int)$_POST['pageId']]->image ) ); 
		@unlink( $this->imagesPath . 't_' . basename( $book->pages[(int)$_POST['pageId']]->image ) ); 

		
		$book->pages[(int)$_POST['pageId']] = new Page( $mergeImage, $_POST['pageId'], '' );

        
        foreach( $book->pages as $page )
         	if( substr( basename( $page->image ), 2) == $secondImage )
        	{
				@unlink( $this->imagesPath . basename( $page->image ) ); 
				@unlink( $this->imagesPath . 't_' . basename( $page->image ) ); 

				$book->deletePage( $page->number );
				break;
        	}

		
        $book->save();

        exit;
    }

    
    function delete_image( $imageId = '' )
    {
        global $wpdb;

        if( $imageId === '' )
		{
			$imageId = $_POST['imageId'];
			$ajax = true;
		}

        $sql = "select `filename` from `".$this->table_img_name."` where `id` = '".$imageId."'";
	    $img = $wpdb->get_row($sql, ARRAY_A, 0);

        
        $sql = "delete from `".$this->table_img_name."` where `id` = '".$imageId."'";
        
        $wpdb->query($sql);

        if( !$this->functions->isUrl( $img['filename'] ) )
        {
			$page =  $this->plugin_path . $this->imagesDir . '/' . $img['filename'];

	        @unlink( $page );

	        
	        $fileExt = split( "\.", $img['filename'] );
	        if( $fileExt[1] != "swf" )
	        {
	        	 $thumb = $this->plugin_path . $this->imagesDir . '/t_' . $img['filename'];
	        	 @unlink( $thumb );

	        	 $zoom = $this->imagesPath. 'z_'.$img['filename'];
	        	 if (file_exists($zoom))
	        	 	@unlink($zoom);
	        }
        }

        if( $ajax ) exit;

        
    }

    
    function deleteImages()
    {
    	if( empty( $_POST['imageList'] ) ) return false;

		$images = split( ';', $_POST['imageList'] );

		foreach( $images as $imageId )
			$this->delete_image( $imageId );

		exit;
    }

	function deleteGallery()
	{
		global $wpdb;

		
		$sql = "select `id` from `".$this->table_img_name."` where `type` = 'img' and `gallery` = '".$_POST['gallery']."'";
		$images = $wpdb->get_results($sql, ARRAY_A);
		if( count( $images ) > 0 )
			foreach( $images as $img ) $this->delete_image( $img['id'] );

		
        $sql = "delete from `".$this->table_gal_name."` where `id` = '".$_POST['gallery']."'";
        
        $wpdb->query($sql);

		exit;
	}

    
    function moveImgTo( $galleryId = '', $imageId = '' )
    {
    	global $wpdb;

    	if( ( $galleryId === '' ) || ( $imageId === '' ) )
    	{
    		$galleryId = (int)$_POST['gallery'];
    		$imageId = (int)$_POST['imageId'];
    	}

		$sql = "update `".$this->table_img_name."` set `gallery` = '".$galleryId."' where `id` = '".$imageId."'";
    	$wpdb->query( $sql );

		if( ( $galleryId === '' ) || ( $imageId === '' ) ) exit;
    }

    
    function moveImgsTo()
    {
    	if( empty( $_POST['imageList'] ) ) return false;

		$images = split( ';', $_POST['imageList'] );

		foreach( $images as $imageId )
			$this->moveImgTo( $_POST['gallery'], $imageId );

		exit;
	}

    
	function add_sound()
	{
        if ($_FILES['sound']['name'])
        {
           if($_FILES["sound"]["size"] > $this->maxSoundSize) {echo __("This file is too big", 'pageFlip'); return 0;} 
           
	       $fileExt = split("\.", $_FILES['sound']['name']);
	       if(strtolower($fileExt['1']) != "mp3"){echo __("Wrong file type", 'pageFlip'); return 0;} 
	       
           $dirName = $this->plugin_path.$this->soundsDir."/";

           
           $maxNum = 0;
           $dir = opendir($dirName); 

	       while ($sound = readdir($dir))
	       {
	          if ($sound != '.' && $sound != '..')
	          {
	            $name = split("\.", $sound);
	            if((int)$name["0"] > (int)$maxNum) $maxNum = $name["0"];
	          }
	       }

	       closedir ($dir); 

	       
           $filename =  ( $maxNum + 1 ) . '.' . $fileExt['1'];

	       $new_filename = $dirName . $filename;

	       $_POST['flipSound'] = basename($new_filename);

	       if(!copy( $_FILES['sound']['tmp_name'], $new_filename ) ) {echo __("Write file error!", 'pageFlip'); return '';}
	    }

	    if( $_POST['flipSound'] !== '' ) $flipSound = $this->plugin_url . $this->soundsDir . '/' . $_POST['flipSound']; 
	    else $flipSound = '';

	    return $flipSound;
	}
	
    function check_db()
    {
         global $wpdb;

         $fieldsPageFlip = array( 'id' => 'BIGINT( 20 ) NOT NULL AUTO_INCREMENT PRIMARY KEY',
		 						  'name' => 'TEXT NOT NULL',
								  'date' => 'BIGINT( 11 ) NOT NULL DEFAULT \''.date("U").'\'',
								  'bgImage' => 'BIGINT( 11 ) NOT NULL'
								 );

		 $fieldsPageFlipImg = array( 'id' => 'BIGINT( 20 ) NOT NULL AUTO_INCREMENT PRIMARY KEY',
		 							 'name' => 'TEXT NOT NULL',
		 							 'filename' => 'TEXT NOT NULL',
		 							 'date' => 'BIGINT( 11 ) NOT NULL DEFAULT \''.date("U").'\'',
		 							 'type' => 'VARCHAR( 10 ) NOT NULL DEFAULT \'img\'',
		 							 'gallery' => 'BIGINT( 20 ) NOT NULL'
								   );

		$fieldsPageFlipGallery = array( 'id' => 'BIGINT( 20 ) NOT NULL AUTO_INCREMENT PRIMARY KEY',
		 							 	'name' => 'TEXT NOT NULL',
		 							 	'date' => 'BIGINT( 11 ) NOT NULL DEFAULT \''.date("U").'\'',
		 							 	'preview' => 'BIGINT( 20 ) NOT NULL'
								  	  );


		 
		 $this->functions->createTable( $this->table_name, $fieldsPageFlip );

		 
		 $this->functions->checkTable( $this->table_name, $fieldsPageFlip );

		 
		 $this->functions->createTable( $this->table_img_name, $fieldsPageFlipImg );

		 
		 $this->functions->checkTable( $this->table_img_name, $fieldsPageFlipImg );

		 
		 $this->functions->createTable( $this->table_gal_name, $fieldsPageFlipGallery );

		 
		 $this->functions->checkTable( $this->table_gal_name, $fieldsPageFlipGallery );
    }

	
    function check_dir()
    {
          global $pageFlipError;

		  

          $pageFlipError = '';

          if( $this->functions->createDir( $this->plugin_path ) )
          {
	          $this->functions->createDir( $this->plugin_path . $this->booksDir );
	          $this->functions->createDir( $this->plugin_path . $this->soundsDir );
	          $this->functions->createDir( $this->plugin_path . $this->imagesDir );
	          $this->functions->createDir( $this->plugin_path . $this->uploadDir );
          }

          if( $pageFlipError !== '' ) define( 'PAGEFLIP_ERROR', $pageFlipError );
    }

	
	function removeDir($dirName)
	{
	      
	      if(!is_dir($dirName)) return true;
	      
	      $delete_dir = opendir($dirName);
	      chdir($dirName);
	      while ($delete = readdir($delete_dir))
	      {
	             if(is_dir($delete) && ($delete !== ".") && ($delete !== "..")) $del_dir_names[] = $delete;
	             if(is_file($delete)) $del_file_names[] = $delete;
	      }
	      
	      if( is_dir("0/") ) $del_dir_names[] = "0/";

	      if(isset($del_file_names))
	       foreach($del_file_names as $delete_this_file) unlink($dirName.$delete_this_file);

	      if(isset($del_dir_names))
	       foreach($del_dir_names as $delete_this_dir) $this->removeDir($dirName.$delete_this_dir."/");

	      closedir($delete_dir);
	      if(rmdir($dirName)) return true;
	      else return false;
	}

	
	function addPageForm()
	{
		echo $this->add_page_form( $_POST['bookId'], $_POST['imageId'], $_POST['type'] );
		exit;
	}


	
	function add_page_form($id, $imageId, $type='pageFlip' )
	{
        global $wpdb;

        switch( $type )
        {
        	case 'WPMedia' : {
    			$image = wp_get_attachment_image( $imageId, array(80, 60), true );
    			$name = wp_specialchars( _draft_or_post_title( $imageId ) );
        	} break;
        	case 'NGGallery' : {
				$sql = "SELECT `filename`, `galleryid`, `alttext` FROM `{$wpdb->prefix}ngg_pictures` WHERE `pid` = '{$imageId}'";
				$img = $wpdb->get_row($sql, ARRAY_A);
				$sql = "SELECT `path` FROM `{$wpdb->prefix}ngg_gallery` WHERE `gid` = '{$img['galleryid']}'";

    			$image = $this->siteURL.'/' . $wpdb->get_var( $sql ) . '/thumbs/thumbs_' . $img['filename'];
				$image = $this->functions->printImg( $image, $img['alttext'] );
				$name = $img['alttext'];
        	} break;
        	default : {
        		$sql = "SELECT `name`, `filename` FROM `{$this->table_img_name}` WHERE `id` = '{$imageId}'";
			    $img = $wpdb->get_row($sql, ARRAY_A, 0);

			    $imageUrl = $this->functions->getImageUrl( $img['filename'] );
			    $image = $this->functions->printImg( $imageUrl, $img['name'] );
    			$name = $img['name'];
        	}
        }

        echo $this->html->addPageForm( $id, $imageId, $image, $name, $type );
	}

	
	function flashEditor( $do, $exit = true )
	{
		switch( $do )
		{
			case 'loadalbumxml' : echo $this->functions->loadAlbumXml( (int)$_POST['bookId'] ); break;
			case 'savealbumxml' : $this->functions->saveAlbumXml( (int)$_POST['bookId'] ); break;
			case 'loadlayouts' : echo $this->functions->loadLayouts( ); break;
		}
		if ( $exit )
			exit();
	}

    
	function book_form( $bookId = '', $galleryId = '' )
	{
		global $wpdb;
		

		$thisBook = new Book( $bookId );

		if( $bookId == '' )
        {
            $book['name'] = '';
            $book['button'] = __('Add Book', 'pageFlip');
            $book['title'] = __('Add Book', 'pageFlip');
            $book['action'] = 'addbook';
            $book['bgImage'] = '0';
        	if ($galleryId)
        	{
        		$gallery = $wpdb->get_row("SELECT * FROM `{$this->table_gal_name}` WHERE `id`='{$galleryId}'");
        		$book['name'] = $gallery->name;
        	}
        }
        else 
        {
            global $wpdb;
            $sql = "SELECT `name`, `bgImage` FROM `".$this->table_name."` WHERE `id` = '".$bookId."'";

            
            $book['name'] = $wpdb->get_var($sql, 0, 0);
            $book['button'] = __('Save Changes', 'pageFlip');
            $book['title'] = __('Book properties', 'pageFlip');
            $book['action'] = 'editbook';
            $book['bgImage'] = $wpdb->get_var($sql, 1, 0);
        }

        
        $dir_name = $this->plugin_path . $this->soundsDir . '/';
        $dir = opendir( $dir_name ); 

        $flipSound = '<select size="1" name="flipSound" id="flipSound">';
        $flipSound .= '<option value="">' . __('No sound', 'pageFlip') . '</option>';
        while ( $sound = readdir( $dir ) )
        {
          if ( $sound != '.' && $sound != '..' )
          {
            $flipSound .= '<option value="' . $sound . '"';
            if( basename( $thisBook->flipSound ) == $sound )   $flipSound .= ' selected="selected"';
            $flipSound .= '>' . $sound . '</option>';
          }
        }
        $flipSound .= '</select>';

        closedir ( $dir ); 

        
        $sql = "SELECT `id`, `name`, `filename` FROM `{$this->table_img_name}` WHERE `type` = 'bg' ORDER BY `id`";
	    $bgrounds = $wpdb->get_results( $sql, ARRAY_A );

	    $bgImageUrl = '';

        $bgImageList = '<select size="1" name="bgImage" id="bgImage" onchange="viewBackground(this);">';
        $bgImageList .= '<option value="-1"';
        if($book['bgImage'] == "-1")   $bgImageList .= ' selected="selected"';
        $bgImageList .= '>' . __('No Background', 'pageFlip') . '</option>' .
						'<option value="0"';

		if( $book['bgImage'] == "0" )
        {
        	$bgImageList .= ' selected="selected"';
        	$bgImageUrl = $this->bgFile;
        }

        $bgImageList .= '>' . __('default', 'pageFlip') . '</option>';

        $bgImagesAr = 'case \'0\' : preview = \'' . str_replace( "/", "\\/", $this->functions->printImg( $this->bgFile, 'default' ) ) . "'; break;\n";

        $bgImageName = '';
        if( count( $bgrounds ) > 0 )
         foreach ( $bgrounds as $bground )
         {
             $bgImageList .= '<option value="' . $bground['id'] . '"';
             if( $book['bgImage'] == $bground['id'] )
             {
             	$bgImageList .= ' selected="selected"';
             	$bgImageUrl = $this->plugin_url . $this->imagesDir . '/' . $bground['filename'];
             	$bgImageName = $bground['name'];
             }
             $bgImageList .= '>' . $bground['name'] . '</option>';

             $bgImagesAr .= 'case \'' . $bground['id'] . '\' : preview = \'' . str_replace( "/", "\\/", $this->functions->printImg( $this->plugin_url.$this->imagesDir . '/' . $bground['filename'], $bground['name'] ) ) . "'; break;\n";
         }
        $bgImageList .= '</select>';

        
        echo $this->html->bookForm( $book['title'], $book['name'], $thisBook,
									  $this->functions->printImg( $bgImageUrl, $bgImageName ),
        						  	   $bgImagesAr, $flipSound, $bgImageList,
        						         $book['action'], $book['button'], $galleryId );
	}


    
	function mce_external_plugins( $plugin_array )
	{
		$plugin_array['pageFlip'] = $this->jsUrl . 'editor_plugin.js';
	    return $plugin_array;
	}

	
	function mce_buttons( $buttons )
	{
	    array_push( $buttons, "pageFlip" );
	    return $buttons;
	}

	
	function init_textdomain()
	{
    	if ( function_exists('load_plugin_textdomain') )
        	load_plugin_textdomain( 'pageFlip', PLUGINDIR.'/'.$this->plugin_dir.'/'.$this->langDir.'/' );
	}


	function sortBook()
	{
		$book = new Book($_POST['bookId']);
		$sortBy = $_POST['sortBy'];
		$sortOrder = $_POST['sortOrder'] == 'desc' ? SORT_DESC : SORT_ASC;

		foreach ($book->pages as $id => $page)
		{
			$sort[$id] = strtolower($page->$sortBy);
		}
		array_multisort($sort, $sortOrder, $book->pages);

        if( !$book->save() )
        {
        	echo __('Save file error! Please setup permission to the books/ , images/ , sounds/ folders and include files to &quot;777&quot;', 'pageFlip');
        	return false;
        }
	}


	
	function pageFlipWidget($args, $widget_args = 1) {
		extract( $args, EXTR_SKIP );
		if ( is_numeric($widget_args) )
			$widget_args = array( 'number' => $widget_args );
		$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );
		extract( $widget_args, EXTR_SKIP );

		$options = get_option('widget_pageflip');
		if ( !isset($options[$number]) )
			return;

		$book_id = $options[$number]['book_id'];
		$title = apply_filters('widget_title', $options[$number]['title']);
		$link_type = $options[$number]['link_type'];
		$link_text = $options[$number]['link_text'];
		$from = $options[$number]['from'];
		$to = $options[$number]['to'];
		$preview_width = $options[$number]['preview_width'];
		$preview_height = $options[$number]['preview_height'];
		$text = apply_filters('widget_text', $options[$number]['text']);

		if (!$this->html)
		{
			$this->init();
			$this->html->main = &$this;
			$this->functions->main = &$this;
		}

		$book = new Book($book_id);
		$load = $book->load();
		if ($load === false)
			return false;

		echo $before_widget;
		if ( !empty( $title ) )
			echo $before_title . $title . $after_title;

		switch ($link_type)
		{
			case 'preview':
				$a = array('from'=>$from, 'to'=>$to, 'preview_width'=>$preview_width, 'preview_height'=>$preview_height);
				break;
			case 'text':
				$a = array('text'=>$link_text);
				break;
		}
;echo '		<div class="pageflip_widget"><div class="textwidget">
			<div class="pageflip_preview" style="margin:0.5em 0;">'; echo $this->html->popupLink($book, $a); ;echo '</div>
			<div class="pageflip_text" style="margin:0.5em 0;">'; echo $text; ;echo '</div>
		</div></div>
';
		echo $after_widget;
	}

	
	function pageFlipWidgetControl($widget_args) {
		global $wp_registered_widgets, $wpdb;
		static $updated = false;

		if ( is_numeric($widget_args) )
			$widget_args = array( 'number' => $widget_args );
		$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );
		extract( $widget_args, EXTR_SKIP );

		$options = get_option('widget_pageflip');
		if ( !is_array($options) )
			$options = array();

		if ( !$updated && !empty($_POST['sidebar']) ) {
			$sidebar = (string) $_POST['sidebar'];

			$sidebars_widgets = wp_get_sidebars_widgets();
			if ( isset($sidebars_widgets[$sidebar]) )
				$this_sidebar =& $sidebars_widgets[$sidebar];
			else
				$this_sidebar = array();

			foreach ( (array) $this_sidebar as $_widget_id ) {
				if ( array($this, 'pageFlipWidget') === $wp_registered_widgets[$_widget_id]['callback'] && isset($wp_registered_widgets[$_widget_id]['params'][0]['number']) ) {
					$widget_number = $wp_registered_widgets[$_widget_id]['params'][0]['number'];
					if ( !in_array( "pageflip-$widget_number", $_POST['widget-id'] ) ) 
						unset($options[$widget_number]);
				}
			}

			foreach ( (array) $_POST['widget-pageflip'] as $widget_number => $widget_pageflip ) {
				if ( !isset($widget_pageflip['book_id']) && isset($options[$widget_number]) ) 
					continue;

				$title = strip_tags(stripslashes($widget_pageflip['title']));

				$link_type = strip_tags(stripslashes($widget_pageflip['link_type']));
				$link_text = strip_tags(stripslashes($widget_pageflip['link_text']));

				$from = trim(strip_tags(stripslashes($widget_pageflip['from'])));
				$to = trim(strip_tags(stripslashes($widget_pageflip['to'])));

				$preview_width = trim(strip_tags(stripslashes($widget_pageflip['preview_width'])));
				$preview_height = trim(strip_tags(stripslashes($widget_pageflip['preview_height'])));

				if ($link_type == 'preview')
				{
					if ( empty($from) && empty($to) )
					{
						$from = '1';
						$to = '1';
					}

					if ( empty($preview_width) )
						$preview_width = 70;
					if ( empty($preview_height) )
						$preview_height = 90;
				}

				if ( current_user_can('unfiltered_html') )
					$text = stripslashes( $widget_pageflip['text'] );

				$book_id = stripslashes(wp_filter_post_kses( $widget_pageflip['book_id'] ));
				$options[$widget_number] = compact( 'book_id', 'title', 'link_type', 'link_text', 'from', 'to', 'text', 'preview_width', 'preview_height' );
			}

			update_option('widget_pageflip', $options);
			$updated = true;
		}

		if ( -1 == $number ) {
			$book_id = '';
			$title = '';
			$link_type = 'preview';
			$link_text = '';
			$from = '1';
			$to = '1';
			$preview_width = $this->thumbWidth;
			$preview_height = $this->thumbHeight;
			$text = '';
			$number = '%i%';
		} else {
			$book_id = attribute_escape($options[$number]['book_id']);
			$title = attribute_escape($options[$number]['title']);
			$link_type = attribute_escape($options[$number]['link_type']);
			$link_text = attribute_escape($options[$number]['link_text']);
			$from = attribute_escape($options[$number]['from']);
			$to = attribute_escape($options[$number]['to']);
			$preview_width = attribute_escape($options[$number]['preview_width']);
			$preview_height = attribute_escape($options[$number]['preview_height']);
			$text = format_to_edit($options[$number]['text']);
		}

	    $books = $wpdb->get_results("SELECT `id`, `name` FROM `{$this->table_name}` ORDER BY `id`");
;echo '			<p>
				<label for="pageflip-title-'; echo $number; ;echo '">Title</label>
				<input class="widefat" id="pageflip-title-'; echo $number; ;echo '" name="widget-pageflip['; echo $number; ;echo '][title]" type="text" value="'; echo $title; ;echo '" />
			</p>
			<p>
				<label for="pageflip-book_id-'; echo $number; ;echo '" style="width:20%; display:block; float:left; padding-top:0.33em;">Book</label>
				<select name="widget-pageflip['; echo $number; ;echo '][book_id]" style="width:70%;">
					<option value=""></option>
'; foreach ($books as $book) : ;echo '					<option value="'; echo $book->id; ;echo '"'; echo $book->id == $book_id ? ' selected="selected"' : ''; ;echo '>'; echo $book->id; ;echo ' - '; echo $book->name; ;echo '</option>
'; endforeach; ;echo '				</select>
			</p>
			<p style="margin:2em 0 0 0;">
				<label style="margin-right:1.5em;">Link type</label>
				<input id="pageflip-link_type-text-'; echo $number; ;echo '" type="radio" name="widget-pageflip['; echo $number; ;echo '][link_type]" value="text"'; echo $link_type=='text' ? ' checked="checked"' : ''; ;echo ' onclick="pageflip_link_type(\'text\');" />
				<label for="pageflip-link_type-text-'; echo $number; ;echo '">'; _e('Text', 'pageFlip'); ;echo '</label>
				<input id="pageflip-link_type-preview-'; echo $number; ;echo '" type="radio" name="widget-pageflip['; echo $number; ;echo '][link_type]" value="preview"'; echo $link_type=='preview' ? ' checked="checked"' : ''; ;echo ' onclick="pageflip_link_type(\'preview\');" style="margin-left:1.5em;" />
				<label for="pageflip-link_type-preview-'; echo $number; ;echo '">'; _e('Page preview', 'pageFlip'); ;echo '</label>
			</p>
			<p id="pageflip-link-'; echo $number; ;echo '" style="margin:1em 0 0 0;">
				<label for="pageflip-link_text-'; echo $number; ;echo '"></label>
				<input id="pageflip-link_text-'; echo $number; ;echo '" name="widget-pageflip['; echo $number; ;echo '][link_text]" value="'; echo $link_text; ;echo '" style="width:21.5em; margin-left:6.5em;" />
			</p>
			<div id="pageflip-preview-'; echo $number; ;echo '">
				<p style="float:left; height:6em; margin:1em 0 0 0;">
					<label for="pageflip-from-'; echo $number; ;echo '" style="display:block; margin:0 0 0.5em;">'; _e('Preview pages'); ;echo '</label>
					<label for="pageflip-from-'; echo $number; ;echo '" style="width:4em; height:3em; display:block; float:left; padding-top:0.33em;">'; _e('from', 'pageFlip'); ;echo '</label>
					<input id="pageflip-from-'; echo $number; ;echo '" type="text" class="widefat" name="widget-pageflip['; echo $number; ;echo '][from]" value="'; echo $from; ;echo '" style="width:4em;" />
					<label for="pageflip-to-'; echo $number; ;echo '">'; _e('to', 'pageFlip'); ;echo '</label>
					<input id="pageflip-to-'; echo $number; ;echo '" type="text" class="widefat" name="widget-pageflip['; echo $number; ;echo '][to]" value="'; echo $to; ;echo '" style="width:4em;" />
				</p>
				<p style="float:left; height:6em; margin:1em 0 0 3em;">
					<label for="pageflip-preview_width-'; echo $number; ;echo '" style="display:block; margin:0 0 0.5em;">'; _e('Max. preview size'); ;echo '</label>
					<input id="pageflip-preview_width-'; echo $number; ;echo '" type="text" class="widefat" name="widget-pageflip['; echo $number; ;echo '][preview_width]" value="'; echo $preview_width; ;echo '" title="'; _e('Width', 'pageFlip'); ;echo '" style="width:4em;" />
					&times;
					<input id="pageflip-preview_height-'; echo $number; ;echo '" type="text" class="widefat" name="widget-pageflip['; echo $number; ;echo '][preview_height]" value="'; echo $preview_height; ;echo '" title="'; _e('Height', 'pageFlip'); ;echo '" style="width:4em;" />
					px
				</p>
			</div>
			<script type="text/javascript">//<![CDATA[
				function pageflip_link_type(type)
				{
					switch (type)
					{
						case \'text\':
							document.getElementById(\'pageflip-preview-'; echo $number; ;echo '\').style.display = \'none\';
							document.getElementById(\'pageflip-link-'; echo $number; ;echo '\').style.display = \'block\';
							document.getElementById(\'pageflip-link_text-'; echo $number; ;echo '\').focus();
							break;
						case \'preview\':
							document.getElementById(\'pageflip-link-'; echo $number; ;echo '\').style.display = \'none\';
							document.getElementById(\'pageflip-preview-'; echo $number; ;echo '\').style.display = \'block\';
							break;
					}
				}
'; if ($link_type == 'text') : ;echo '				document.getElementById(\'pageflip-preview-'; echo $number; ;echo '\').style.display = \'none\';
'; else : ;echo '				document.getElementById(\'pageflip-link-'; echo $number; ;echo '\').style.display = \'none\';
'; endif; ;echo '			//]]>
			</script>
			<p style="clear:left; margin-top:2em;">
				<label for="pageflip-text-'; echo $number; ;echo '">'; _e('Text', 'pageFlip'); ;echo '</label>
				<textarea id="pageflip-text-'; echo $number; ;echo '" class="widefat" name="widget-pageflip['; echo $number; ;echo '][text]" cols="30" rows="5" style="display:block;">'; echo $text; ;echo '</textarea>
			</p>
			<input type="hidden" name="widget-text['; echo $number; ;echo '][submit]" value="1" />
			<!--<div style="clear:left; height:1px; overflow:hidden;">&nbsp;</div>-->
';
	}

	
	function pageFlipWidgetRegister() {
		if ( !$options = get_option('widget_pageflip') )
			$options = array();
		$widget_ops = array('classname' => 'widget_pageflip', 'description' => __('PageFlip'));
		$control_ops = array('width' => 380, 'height' => 350, 'id_base' => 'pageflip');
		$name = __('FlippingBook');

		$id = false;
		foreach ( (array) array_keys($options) as $o ) {
			
			if ( !isset($options[$o]['title']) || !isset($options[$o]['book_id']) )
				continue;
			$id = "pageflip-$o"; 
			wp_register_sidebar_widget($id, $name, array($this, 'pageFlipWidget'), $widget_ops, array( 'number' => $o ));
			wp_register_widget_control($id, $name, array($this, 'pageFlipWidgetControl'), $control_ops, array( 'number' => $o ));
		}

		
		if ( !$id ) {
			wp_register_sidebar_widget( 'pageflip-1', $name, array($this, 'pageFlipWidget'), $widget_ops, array( 'number' => -1 ) );
			wp_register_widget_control( 'pageflip-1', $name, array($this, 'pageFlipWidgetControl'), $control_ops, array( 'number' => -1 ) );
		}
	}

}

?>