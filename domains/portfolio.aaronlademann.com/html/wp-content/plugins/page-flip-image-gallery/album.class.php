<?php 
if ( preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF']) ) { die('You are not allowed to call this page directly.'); }

class Album
{
	var $id,
		$name,
		$type,
		$backgroundColor,
		$frameColor,
		$frameSize,
		$pageWidth,
		$pageHeight,
		$images = array(),
		$pages = array(),
		$load = false;

	function Album( $id, $pageWidth, $pageHeight, $name = '', $type = '', $backgroundColor = '', $frameColor = '', $frameSize = 0 )
	{
		$this->id = $id;
		$this->pageWidth = (int)$pageWidth;
		$this->pageHeight = (int)$pageHeight;
		$this->name = $name;
		$this->type = $type;
		$this->backgroundColor = $backgroundColor;
		$this->frameColor = $frameColor;
		$this->frameSize = $frameSize;
	}

	function addPage( $id, $template, $backgroundColor = '', $frameColor = '', $frameSize = '', $caption = '', $type = '' )
	{
		if( $frameColor == '' || $frameSize == '' || $backgroundColor == '' )
		{
			$frameColor = $this->frameColor;
			$frameSize = $this->frameSize;
			$backgroundColor = $this->backgroundColor;
			$default = true;
		}
		else $default = false;

		$this->pages[] = new AlbumPage( $id, $template, $backgroundColor, $frameColor, $frameSize, $caption, $type );
		$key = count( $this->pages ) - 1;
		$this->pages[$key]->default = $default;

		return $key;
	}

	function addImage( $id, $thumb, $width, $height )
	{
		$this->images[(int)$id] = new AlbumImage( $id, $thumb, $width, $height );
		
		
		return $id;
	}

	function asXML()
	{
		$xml = '<album id="' . $this->id . '" pageWidth="' . $this->pageWidth . '" pageHeight="' . $this->pageHeight . '"';
		$xml .= ( !empty( $this->name ) ) ? ' name="' . $this->name . '"' : '';
		$xml .= ( !empty( $this->type ) ) ? ' type="' . $this->type . '"' : '';
		$xml .= ( !empty( $this->backgroundColor ) ) ? ' backgroundColor="' . $this->backgroundColor . '"' : '';
		$xml .= ( !empty( $this->frameColor ) ) ? ' frameColor="' . $this->frameColor . '"' : '';
		$xml .= ' frameSize="' . $this->frameSize . '"';
	 	$xml .= ">\n";

	 	$xml .= "<images>\n";
		if ( count($this->images) )
			foreach ($this->images as $image) $xml .= $image->asXML();
		$xml .= "</images>\n";

		$xml .= "<pages>\n";
		if ( count($this->pages) )
			foreach ($this->pages as $page) $xml .= $page->asXML();
		$xml .= "</pages>\n";

		$xml .= "</album>\n";

  		return $xml;
	}

	function parseFromXML( $xml )
	{
		$xml = '<?xml version="1.0" encoding="utf-8" standalone="yes"?>' . $xml;

		if( PHP_VERSION >= "5" )
			$album = @simplexml_load_string( $xml );
        else
        {
        	
			$domXml = @domxml_open_mem( $xml );

			
			$album = $domXml->document_element();
        }

        $this->getInfo( $album );
	}

	function getInfo( $album )
	{
		unset( $this->pages );
		unset( $this->images );

		if( PHP_VERSION >= "5" ) $this->parseXMLphp5( $album );
        else $this->parseXMLphp4( $album );

		$this->load = true;
	}

	function parseXMLphp4( $node )
	{
		$album['name'] = trim( $node->get_attribute('name') );
		$album['type'] = trim( $node->get_attribute('type') );
		$album['backgroundColor'] = trim( $node->get_attribute('backgroundColor') );
		$album['frameColor'] = trim( $node->get_attribute('frameColor') );
		$album['frameSize'] = trim( $node->get_attribute('frameSize') );
		$album['pageWidth'] = trim( $node->get_attribute('pageWidth') );
		$album['pageHeight'] = trim( $node->get_attribute('pageHeight') );

		if( !empty( $album['name'] ) ) $this->name = $album['name'];
		if( !empty( $album['type'] ) ) $this->type = $album['type'];
		if( !empty( $album['backgroundColor'] ) ) $this->backgroundColor = $album['backgroundColor'];
		if( !empty( $album['frameColor'] ) ) $this->frameColor = $album['frameColor'];
		if( !empty( $album['frameSize'] ) ) $this->frameSize = $album['frameSize'];
		if( !empty( $album['pageWidth'] ) ) $this->pageWidth = $album['pageWidth'];
		if( !empty( $album['pageHeight'] ) ) $this->pageHeight = $album['pageHeight'];

		$subnodes = $node->child_nodes();

		foreach( $subnodes as $subnode )
		{
			switch( $subnode->node_name() )
			{
				case 'images' : {
					$images = $subnode->child_nodes();
					foreach( $images as $image )
					{
						if( substr( $image->node_name(), 0, 1 ) == "#" ) continue;

						$this->addImage( $image->get_attribute( 'id' ), $image->get_attribute( 'thumb' ), $image->get_attribute( 'width' ), $image->get_attribute( 'height' ) );
					}
				} break;
				case 'pages' : {
					$pages = $subnode->child_nodes();
					foreach( $pages as $page )
					{
						if( substr( $page->node_name(), 0, 1 ) == "#" ) continue;

						$pageId = $page->get_attribute( 'id' );
						if( $pageId === '') $pageId = count( $this->pages );

						$key = $this->addPage( $pageId, $page->get_attribute( 'template' ), $page->get_attribute( 'backgroundColor' ), $page->get_attribute( 'frameColor' ), $page->get_attribute( 'frameSize' ), $page->get_attribute( 'caption' ), $page->get_attribute( 'type' ) );
						$this->pages[$key]->modified = $page->get_attribute( 'modified' );

						$imgs = $page->child_nodes();
						foreach( $imgs as $img )
						{
							if( substr( $img->node_name(), 0, 1 ) == "#" ) continue;

							$this->pages[$key]->addImg( $img->get_attribute( 'id' ), $img->get_attribute( 'scaling' ), $img->get_attribute( 'x' ), $img->get_attribute( 'y' ), $img->get_attribute( 'description' ) );
						}
					}
				} break;
			}
		}
	}

	function parseXMLphp5( $album )
	{
		if( !empty( $album['name'] ) ) $this->name = $album['name'];
		if( !empty( $album['type'] ) ) $this->type = $album['type'];
		if( !empty( $album['backgroundColor'] ) ) $this->backgroundColor = $album['backgroundColor'];
		if( !empty( $album['frameColor'] ) ) $this->frameColor = $album['frameColor'];
		if( !empty( $album['frameSize'] ) ) $this->frameSize = $album['frameSize'];
		if( !empty( $album['pageWidth'] ) ) $this->pageWidth = $album['pageWidth'];
		if( !empty( $album['pageHeight'] ) ) $this->pageHeight = $album['pageHeight'];

		foreach( $album->images->img as $value )
				$this->addImage( $value['id'], $value['thumb'], $value['width'], $value['height'] );

		foreach( $album->pages->page as $value )
		{
			$pageId = $value['id'];
			if( trim( $pageId ) === '' ) $pageId = count( $this->pages );

			$key = $this->addPage( $pageId, $value['template'], $value['backgroundColor'], $value['frameColor'], $value['frameSize'], $value['caption'], $value['type'] );
			$this->pages[$key]->modified = $value['modified'];
			foreach( $value->img as $img )
				$this->pages[$key]->addImg( $img['id'], $img['scaling'], $img['x'], $img['y'], $img['description'] );
		}
	}
}

class AlbumPage
{
	var $id,
		$template = 0,
		$backgroundColor,
		$frameColor,
		$frameSize,
		$caption,
		$type = 'single',
		$modified = 'false',
		$default = false,
		$imgs = array();

	function AlbumPage( $id, $template, $backgroundColor = '', $frameColor = '', $frameSize = '', $caption = '', $type = '' )
	{
		$this->id = (int)$id;
		$this->template = (int)$template;
		$this->backgroundColor = $backgroundColor;
		$this->frameColor = $frameColor;
		$this->frameSize = (int)$frameSize;
		$this->caption = $caption;
		$this->type = $type;
	}

	function addImg( $id, $scaling, $x, $y, $description = '' )
	{
		$this->imgs[] = new AlbumImg( $id, $scaling, $x, $y, $description );
	}

	function asXML()
	{
		$xml = '<page id="' . $this->id . '" template="' . $this->template . '"';
		$xml .= ( !empty( $this->backgroundColor ) && !$this->default ) ? ' backgroundColor="' . $this->backgroundColor . '"' : '';
		$xml .= ( !empty( $this->frameColor ) && !$this->default ) ? ' frameColor="' . $this->frameColor . '"' : '';
		$xml .= ( !empty( $this->frameSize ) && !$this->default ) ? ' frameSize="' . $this->frameSize . '"' : '';
		$xml .= ( !empty( $this->caption ) ) ? ' caption="' . $this->caption . '"' : '';
		$xml .= ( !empty( $this->type ) ) ? ' type="' . $this->type . '"' : '';
		$xml .= '>' . "\n";

		foreach( $this->imgs as $img )
			$xml .= $img->asXML();

		$xml .= '</page>' . "\n";

		return $xml;
	}
}

class AlbumImage
{
	var $id,
		$thumb,
		$width,
		$height;

	function AlbumImage( $id, $thumb, $width, $height )
	{
		$this->id = (int)$id;
		$this->thumb = $thumb;
		$this->width = (int)$width;
		$this->height = (int)$height;
	}

	function asXML()
	{
		return '<img id="' . $this->id . '" thumb="' . $this->thumb . '" width="' . $this->width . '" height="' . $this->height . '" />' . "\n";
	}
}

class AlbumImg
{
	var $id,
		$scaling,
		$x,
		$y,
		$description;

	function AlbumImg( $id, $scaling, $x, $y, $description )
	{
		$this->id = (int)$id;
		$this->scaling = $scaling;
		$this->x = (int)$x;
		$this->y = (int)$y;
		$this->description = $description;
	}

	function asXML()
	{
		return '<img id="' . $this->id . '" scaling="' . $this->scaling . '" x="' . $this->x . '" y="' . $this->y . '" description="' . $this->description . '" />' . "\n";
	}
}

class Layout
{
	var $id,
		$areas = array();

	function Layout( $id )
	{
		$this->id = (int)$id;
	}

	function addArea( $id, $X = 0, $Y = 0, $W = 1, $H = 1 )
	{
		$this->areas[$id] = new Area( $id, $X, $Y, $W, $H );
	}

	function asXML()
	{
		$xml = '<Layout id="' . $this->id . '">';
		foreach( $this->areas as $area )
			$xml .= $area->asXML();
		$xml .= '</Layout>';

		return $xml;
	}
}

class Area
{
	var $id,
		$X,
		$Y,
		$W,
		$H;

	function Area( $id, $X, $Y, $W, $H )
	{
		$this->id = (int)$id;
		$this->X = round( $X, 2 );
		$this->Y = round( $Y, 2 );
		$this->W = round( $W, 2 );
		$this->H = round( $H, 2 );
	}

	function asXML()
	{
		$xml = '<Area id="' . $this->id . '" X="' . $this->X . '" Y="' . $this->Y . '" W="' . $this->W . '" H="' . $this->H . '" />';

		return $xml;
	}
}

?>