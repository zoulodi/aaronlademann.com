<?php 
if ( preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF']) ) { die('You are not allowed to call this page directly.'); }

class Book
{
	var $id = 0,
		$name = '',
		$stageWidth = '100%',
		$stageHeight = 512,
		$width = 640,
		$height = 480,
		$firstPage = 0,
		$navigationBarPlacement = 'bottom',
		$pageBack = '0x99CCFF',
		$backgroundColor = '0xFFFFFF',
		$backgroundImage,
		$backgroundImagePlacement = 'fit',
		$staticShadowsType =  'Symmetric',
		$staticShadowsDepth = 1,
		$autoFlip = 50,
		$centerBook = 'true',
		$scaleContent = 'true',
		$alwaysOpened = 'false',
		$flipCornerStyle = 'manually',
		$hardcover = 'false',
		$downloadURL = '',
		$downloadTitle = 'Start Download',
		$downloadSize = '',
		$downloadComplete = 'Complete',
		$allowPagesUnload = 'false',
		$fullscreenEnabled = 'true',
		$fullscreenHint = '',
		$zoomEnabled = 'true',
		$zoomImageWidth = 800,
		$zoomImageHeight = 1200,
		$zoomUIColor = '0x8f9ea6',
		$slideshowButton = 'false',
		$slideshowAutoPlay = 'false',
		$slideshowDisplayDuration = '5000',
		$goToPageField = 'true',
		$firstLastButtons = 'true',
		$printEnabled = 'true',
		$zoomOnClick = 'true',
		$moveSpeed = 2,
		$closeSpeed = 3,
		$gotoSpeed = 3,
		$rigidPageSpeed = 5,
		$zoomHint = 'Double click for zooming',
		$printTitle = 'Print Pages',
		$dropShadowEnabled = 'true',
		$flipSound = '1.mp3',
		$hardcoverSound,
		$preloaderType = 'Progress Bar',
		$pages = array(),
		$album,
		$countPages = 0,
		$navigation = 'true',
		$navigationBarSkin = 'navigation',
		$popup = 'false',
		$autoReduce = 'false',
		$state = 1; 

	var $preserveProportions = 'false',
		$centerContent = 'true',
		$hardcoverThickness = 3,
		$hardcoverEdgeColor = '0xFFFFFF',
		$highlightHardcover = 'true',
		$frameWidth = 0,
		$frameColor = '0xFFFFFF',
		$frameAlpha = 100,
		$navigationFlipOffset = 30,
		$flipOnClick = 'true',
		$handOverCorner = 'true',
		$handOverPage = 'true',
		$staticShadowsLightColor = '0xFFFFFF',
		$staticShadowsDarkColor = '0x000000',
		$dynamicShadowsDepth = 1,
		$dynamicShadowsLightColor = '0xFFFFFF',
		$dynamicShadowsDarkColor = '0x000000',
		$loadOnDemand = 'true',
		$showUnderlyingPages = 'false',
		$playOnDemand = 'true',
		$freezeOnFlip = 'false',
		$darkPages = 'false',
		$smoothPages = 'true',
		$rigidPages = 'false',
		$flipCornerPosition = 'top-right',
		$flipCornerAmount = 70,
		$flipCornerAngle = 45,
		$flipCornerRelease = 'true',
		$flipCornerVibrate = 'true',
		$flipCornerPlaySound = 'false',
		$useCustomCursors = 'false',
		$dropShadowHideWhenFlipping = 'true';

	var $properties = array( 'stageWidth',
							 'stageHeight',
							 'width',
							 'height',
							 'scaleContent',
							 'centerContent',
							 'preserveProportions',
							 'hardcover',
							 'hardcoverThickness',
							 'frameWidth',
							 'frameColor',
							 'frameAlpha',
							 'firstPage',
							 'flipOnClick',
							 'handOverCorner',
							 'handOverPage',
							 'alwaysOpened',
							 'staticShadowsType',
							 'staticShadowsDepth',
							 'rigidPageSpeed',
							 'flipSound',
							 'preloaderType',
							 'rigidPages',
							 'zoomEnabled',
							 'zoomImageWidth',
							 'zoomImageHeight',
							 'zoomOnClick',
							 'zoomHint',
							 'centerBook',
							 'useCustomCursors',
							 'dropShadowEnabled',
							 'dropShadowHideWhenFlipping',
							 'backgroundColor',
							 'pageBack',
							 'backgroundImage',
							 'backgroundImagePlacement',
							 'printEnabled',
							 'printTitle',
							 'navigation',
						
							 'downloadURL',
							 'downloadTitle',
							 'downloadSize',
							 'downloadComplete',
						
							 'allowPagesUnload',
							 'autoFlip',
							 'closeSpeed',
							 'darkPages',
							 'dynamicShadowsDarkColor',
							 'dynamicShadowsDepth',
							 'dynamicShadowsLightColor',
							 'firstLastButtons',
							 'flipCornerAmount',
							 'flipCornerAngle',
							 'flipCornerPlaySound',
							 'flipCornerPosition',
							 'flipCornerRelease',
							 'flipCornerStyle',
							 'flipCornerVibrate',
							 'freezeOnFlip',
							 'fullscreenEnabled',
							 'fullscreenHint',
							 'goToPageField',
							 'gotoSpeed',
							 'hardcoverEdgeColor',
							 'hardcoverSound',
							 'highlightHardcover',
							 'loadOnDemand',
							 'moveSpeed',
							 'navigationBarSkin',
							 'navigationBarPlacement',
							 'navigationFlipOffset',
							 'playOnDemand',
							 'showUnderlyingPages',
							 'slideshowAutoPlay',
							 'slideshowButton',
							 'slideshowDisplayDuration',
							 'smoothPages',
							 'staticShadowsDarkColor',
							 'staticShadowsLightColor',
							 'zoomUIColor',
							 'popup',
							 'autoReduce'
							);

	function Book( $id = '' )
	{
		include_once( PAGEFLIP_DIR.'/page.class.php' );

		if ( !empty($id) )
		{
			$this->id = (int)$id;
			$this->load();
		}
	}

	
	function load()
	{
        global $pageFlip, $wpdb;

		$file = $pageFlip->plugin_path . $pageFlip->booksDir . '/' . $this->id . '.xml';

		$this->album = new Album( $this->id, ceil( $this->width / 2 ), $this->height );

        if( PHP_VERSION >= "5" )
        	$res = $this->get_xml_php5( $file );
        else
        	$res = $this->get_xml_php4( $file );

        if ($res === false)
        	return false;

        $this->name = stripslashes( $wpdb->get_var("SELECT `name` FROM `{$pageFlip->table_name}` WHERE `id`='{$this->id}'") );

        $this->fullscreenHint = empty($this->fullscreenHint) ? $this->name : $this->fullscreenHint;

        
		$this->countPages = count( $this->pages );

		
		
			unset($this->album->pages);
			unset($this->album->images);

			foreach( $this->pages as $id=>$page )
			{
				if ( $this->autoReduce=='true' && !defined('WP_ADMIN') && $pageFlip->functions->getExt($page->image) != 'swf' && $page->image == $page->zoomURL )
				{
					list($width, $height) = $pageFlip->functions->getImageSize($page->image);
					$scale1 = $width / $this->width;
					$scale2 = $height / $this->height;
					$scale = $scale1 > $scale2 ? $scale1 : $scale2;
					$f = $scale - intval($scale);
					if ($f > 0.15 || $scale >= 3)
					{
						$this->pages[$id]->image =
							$pageFlip->functions->getResized(
								$page->zoomURL,
								array(
									'max_width' => $this->width / 2,
									'max_height' => $this->height,
									'background' => $this->pageBack,
									'quality' => 90,
								)
							);
					}
				}
				if( $pageFlip->functions->getExt( $page->image ) == 'swf' )
				{
					$key = $this->album->addPage( $id, 1 );
					continue;
				}

				$size = $pageFlip->functions->getImageSize( $page->image );
				$thumb = $pageFlip->functions->getThumb( $page->image );

				$this->album->addImage( $id, $thumb, $size[0], $size[1] );
				$key = $this->album->addPage( $id, 1 );
				$this->album->pages[$key]->addImg( $id, 'scaleToFill', 0, 0 );
			}

			

		
		if( $this->state === 1 )
		{
			$stat = stat( WP_PLUGIN_DIR . '/' . $pageFlip->plugin_dir . '/' . basename( $pageFlip->component ) );
		    if($stat[7] != 123291) $this->state = 2;
		}
		

		return true;
	}

	
	function get_xml_php4( $file )
	{
		if ( file_exists($file) )
		{
			
			$xml = @domxml_open_file( $file );

			if( !$xml )
			{
				$this->state = 0;
				return false;
			}

			
			$root = $xml->document_element();
			
			$nodes = $root->child_nodes();

			foreach( $nodes as $node )
			{
				if ( substr( $node->node_name(), 0, 1 ) == "#" ) continue;

				if ( $node->node_name() == "pages" )
				{
					$pages = $node->child_nodes();
					$id = 0;

					foreach( $pages as $page )
					{
						if ( substr( $page->node_name(),0,1 ) == "#" ) continue;

						$this->pages[$id] = new Page( trim( $page->get_content() ), $id, trim( $page->get_attribute('name') ) );

						
						

						$id++;
					}
					continue;
				}

				if( $node->node_name() == "album" )
				{
					$this->album->getInfo( $node );
					continue;
				}

				$key = $node->node_name();

				$this->$key = trim( $node->get_content() );
				
			}
		}
		else
			return false;
	}

	
	function get_xml_php5( $file )
	{
		if ( file_exists($file) )
		{
			global $pageFlip;

			$config = @join( '', file( $file ) );
	        $xml = @simplexml_load_string( '<?xml version="1.0" encoding="utf-8" standalone="yes"?>' . $config );

	        if( !$xml )
			{
				$this->state = 0;
				return false;
			}

	        foreach( $xml as $key=>$value )
	        {
	        	if( $key == 'pages' || $key == 'album' ) continue;
	        	$this->$key = trim( $value );
				
	        }

	        $id = 0;
			foreach( $xml->pages->page as $value )
	        {
	        	$this->pages[$id] = new Page( trim( $value ), $id, htmlspecialchars(trim( $value['name'] )), trim($value['zoomURL']) );
				$id++;
				
	        	
	        }

			if( isset( $xml->album ) )
				$this->album->getInfo( $xml->album );
		}
		else
			return false;
	}

	function save( $albumXML = '' )
	{
		global $pageFlip;

		$xml = $this->create_xml( $albumXML );
        
        $xml_file = $pageFlip->plugin_path . $pageFlip->booksDir . '/' . $this->id . '.xml';

		$config_file = fopen( $xml_file, "w+" );

		if( !fwrite( $config_file, $xml ) ) return false;

		fclose( $config_file );

        return true;
	}

	
	function create_xml( $albumXML = '' )
	{
		global $pageFlip;

		

		$xml = '<FlippingBook>' . "\n";

        foreach( $this->properties as $property )
 			$xml .= '	<' . $property . '>' . $this->$property . '</' . $property . '>' . "\n";

		$xml .= '	<pages>' . "\n";

		foreach( $this->pages as $id => $page )
		  $xml .= '		<page name="' . htmlspecialchars(html_entity_decode( $page->name )) . '" ' .
			 					 'zoomURL="' . htmlspecialchars(html_entity_decode( $page->zoomURL )) . '" ' .
								 'zoomType="' . $page->zoomType . '" ' .
								 'target="' . $page->target . '" ' .
								 'zoomHeight="' . $page->zoomHeight . '" ' .
								 'zoomWidth="' . $page->zoomWidth . '"' .
								'>' . $page->image . '</page>' . "\n";

		$xml .= '	</pages>' . "\n";

		if ( empty($this->album) )
			$this->album = new Album( $this->id, ceil( $this->width / 2 ), $this->height );

		if( empty( $albumXML ) ) $xml .= $this->album->asXML();
		else $xml .= $albumXML;

		$xml .=	'</FlippingBook>';

        return $xml;
	}

	function refreshPages()
	{
		$this->countPages = count( $this->pages );

		$pages = array();
		$rec = 0;

		for( $i = 0; $i < $this->countPages; $i++ )
		{
		  foreach( $this->pages as $id => $page )
			 if( (int)$page->number - $rec === (int)$i )
			 {
			 	$pages[$i] = $page;
			 	unset( $this->pages[$id] );
				break;
			 }

		  if( empty( $pages[$i] ) )
		  {
			 	$rec++;
			 	$i--;
		  }
		}

	 	$this->pages = $pages;
	}

	function deletePage( $number )
	{
		global $pageFlip;

		unset( $this->pages[$number] );

		$this->refreshPages();
	}
}

?>