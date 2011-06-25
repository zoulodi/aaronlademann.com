<?php
/*
Plugin Name: Collapsing Categories
Plugin URI: http://blog.robfelty.com/plugins
Description: Uses javascript to expand and collapse categories to show the posts that belong to the category 
Author: Robert Felty
Version: 1.2.2
Author URI: http://robfelty.com
Tags: sidebar, widget, categories, menu, navigation, posts

Copyright 2007-2010 Robert Felty

This file is part of Collapsing Categories

		Collapsing Categories is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License as published by 
    the Free Software Foundation; either version 2 of the License, or (at your
    option) any later version.

    Collapsing Categories is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Collapsing Categories; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/ 

$url = get_settings('siteurl');
global $collapsCatVersion;
$collapsCatVersion = '1.2.2';

if (!is_admin()) {
  $inFooter = get_option('collapsCatInFooter');
  wp_enqueue_script('collapsFunctions',
      "$url/wp-content/plugins/collapsing-categories/collapsFunctions.js",
      array('jquery'), '1.7', $inFooter);
  add_action( 'wp_head', array('collapsCat','get_head'));
} else {
  // call upgrade function if current version is lower than actual version
  $dbversion = get_option('collapsCatVersion');
  if (!$dbversion || $collapsCatVersion != $dbversion)
    collapscat::init();
}
add_action('admin_menu', array('collapsCat','setup'));
add_action('init', array('collapsCat','init_textdomain'));
register_activation_hook(__FILE__, array('collapsCat','init'));

class collapsCat {
	function init_textdomain() {
	  $plugin_dir = basename(dirname(__FILE__)) . '/languages/';
	  load_plugin_textdomain( 'collapsing-categories', WP_PLUGIN_DIR . $plugin_dir, $plugin_dir );
	}

	function init() {
    global $collapsCatVersion;
	  include('collapsCatStyles.php');
		$defaultStyles=compact('selected','default','block','noArrows','custom');
    $dbversion = get_option('collapsCatVersion');
    if ($collapsCatVersion != $dbversion && $selected!='custom') {
      $style = $defaultStyles[$selected];
      update_option( 'collapsCatStyle', $style);
      update_option( 'collapsCatVersion', $collapsCatVersion);
    }
    if( function_exists('add_option') ) {
      update_option( 'collapsCatOrigStyle', $style);
      update_option( 'collapsCatDefaultStyles', $defaultStyles);
    }
    if (!get_option('collapsCatOptions')) {
      include('defaults.php');
      update_option('collapsCatOptions', $defaults);
    }
    if (!get_option('collapsCatStyle')) {
      add_option( 'collapsCatStyle', $style);
		}
    if (!get_option('collapsCatSidebarId')) {
      add_option( 'collapsCatSidebarId', 'sidebar');
		}
    if (!get_option('collapsCatVersion')) {
      add_option( 'collapsCatVersion', $collapsCatVersion);
		}

	}

	function setup() {
		if( function_exists('add_options_page') ) {
      if (current_user_can('manage_options')) {
				add_options_page(__('Collapsing Categories'),
            __('Collapsing Categories'),1,
            basename(__FILE__),array('collapscat','ui'));
			}
		}
	}
	function ui() {
		include_once( 'collapscatUI.php' );
	}

	function get_head() {
    $style=stripslashes(get_option('collapsCatStyle'));
    echo "<style type='text/css'>
    $style
    </style>\n";
	}
  function phpArrayToJS($array,$name) {
    /* generates javscript code to create an array from a php array */
    $script = "try { $name" . 
        "['catTest'] = 'test'; } catch (err) { $name = new Object(); }\n";
    foreach ($array as $key => $value){
      $script .= $name . "['$key'] = '" . 
          addslashes(str_replace("\n", '', $value)) . "';\n";
    }
    return($script);
  }
}


include_once( 'collapscatlist.php' );
function collapsCat($args='', $print=true) {
  global $collapsCatItems; 
  if (!is_admin()) {
    list($posts, $categories, $parents, $options) = 
        get_collapscat_fromdb($args);
    list($collapsCatText, $postsInCat) = list_categories($posts, $categories,
        $parents, $options);
    $url = get_settings('siteurl');
    if ($print) {
      print($collapsCatText);
      echo "<li style='display:none'><script type=\"text/javascript\">\n";
      echo "// <![CDATA[\n";
      echo '/* These variables are part of the Collapsing Categories Plugin 
      *  Version: 1.2.2
      *  $Id: collapscat.php 272205 2010-08-06 02:36:07Z robfelty $
      * Copyright 2007 Robert Felty (robfelty.com)
      */' . "\n";
      $expandSym="<img src='". $url .
           "/wp-content/plugins/collapsing-categories/" . 
           "img/expand.gif' alt='expand' />";
      $collapseSym="<img src='". $url .
           "/wp-content/plugins/collapsing-categories/" . 
           "img/collapse.gif' alt='collapse' />";
      echo "var expandSym=\"$expandSym\";\n";
      echo "var collapseSym=\"$collapseSym\";\n";
      if ($useCookies) {
        echo"
        collapsAddLoadEvent(function() {
          autoExpandCollapse('collapCat');
        });
        ";
      }
      // now we create an array indexed by the id of the ul for posts
      echo collapsCat::phpArrayToJS($collapsCatItems, 'collapsItems');

      echo "// ]]>\n</script></li>\n";
    } else {
      return(array($collapsCatText, $postsInCat));
    }
  }
}
$version = get_bloginfo('version');
if (preg_match('/^(2\.[8-9]|3\..*)/', $version)) 
  include('collapscatwidget.php');
?>
