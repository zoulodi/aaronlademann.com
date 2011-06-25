<?php
/*
collapsing categories version: 1.2.2
copyright 2007-2010 robert felty

this file is part of collapsing categories

		collapsing categories is free software; you can redistribute it and/or
    modify it under the terms of the gnu general public license as published by 
    the free software foundation; either version 2 of the license, or (at your
    option) any later version.

    collapsing categories is distributed in the hope that it will be useful,
    but without any warranty; without even the implied warranty of
    merchantability or fitness for a particular purpose.  see the
    gnu general public license for more details.

    you should have received a copy of the gnu general public license
    along with collapsing categories; if not, write to the free software
    foundation, inc., 51 franklin st, fifth floor, boston, ma  02110-1301  usa
*/
global $collapsCatItems;
$collapsCatItems = array();


function add_to_includes($cat, $inexclusionarray) {
  /* add all parents to include list */
  if (in_array($cat->slug, $inexclusionarray) ||
      in_array($cat->term_id, $inexclusionarray)) {
    $includes[]= $cat->term_id;
    if ($cat->parent!=0) 
      $inexclusionarray[]= $cat->parent;
      $cat2 = get_category($cat->parent);
      $moreincludes = add_to_includes($cat2,$inexclusionarray);
      if (!empty($moreincludes)) {
        foreach ($moreincludes as $include) {
          $includes[] =  $include;
        }
      }
    $children = get_categories('child_of=' . $cat->term_id);
    foreach ($children as $child) {
      $includes[]= $child->term_id;
    }
  }
  return($includes);
}

function getCollapsCatLink($cat,$catlink,$self) {
  /* returns link to category. we use the id of the category if possible,
  because it is faster. otherwise we pass the whole category object */
  if (empty($catlink)) {
    if ($cat->taxonomy=='post_tag') {
      $link = "<a $self href='".get_tag_link($cat->term_id)."' ";
    } else {
      $link = "<a $self href='".get_category_link($cat->term_id)."' ";
    }
  } else {
    if ($cat->taxonomy=='post_tag') {
      $link = "<a $self href='".get_tag_link($cat)."' ";
    } else {
      $link = "<a $self href='".get_category_link($cat)."' ";
    }
  }
  return($link);
}

function miscPosts($cat,$catlink,$subcatpostcount2, $posttext) {
  /* this function will group posts into a miscellaneous sub-category */
  global $options, $collapsCatItems, $cur_categories;
  extract($options);
  $showHide='expand';
  $symbol=$expandSym;
  $expanded='none';
  $theID='collapsCat-' . $cat->term_id . ":$number-misc";

  if ((in_array($cat->term_id, $cur_categories) && $expandCatPost) ||
      ($useCookies && $_COOKIE[$theID]==1)) {
    $expanded='block';
  }
  if ($expanded=='block') {
    $showHide='collapse';
    $symbol=$collapseSym;
  }
  $miscposts="      <li class='collapsing categories'>".
      "<span class='collapsing categories $showHide' ".
      "onclick='expandCollapse(event, \"$expandSymJS\", \"$collapseSymJS\", $animate, " .
      "\"collapsing categories\"); return false'>".
      "<span class='sym'>$symbol</span>";
  if ($linktocat=='yes') {
    $thislink=getCollapsCatLink($cat,$catlink,$self);
    $miscposts.="</span>$thislink>$addMiscTitle</a>";
  } else {
    $miscposts.="$addMiscTitle</span>";
  }
  if( $showPostCount=='yes') {
    $miscposts.=' (' . $subcatpostcount2.')';
  }
  $miscposts.= "\n     <ul id='$theID' style=\"display:$expanded\">\n" ;
  $miscposts.=$posttext;
  $miscposts.="    </ul></li>\n";
  if ($theID!='' && !$collapsCatItems[$theID]) {
    $collapsCatItems[$theID] = $posttext;
  }
  return($miscposts);
}

function checkCurrentCat($cat, $categories) {
 /* this function checks whether the post being displayed belongs to a given
 category, * or if that category's page itself is displayed.  * If so, it adds
 all parent categories to the autoExpand array, so * that it is automatically
 expanded 
 */
  global $autoExpand;
	array_push($autoExpand, $cat->slug);
	if ($cat->parent!=0) {
		foreach ($categories as $cat2) {
		  if ($cat2->term_id == $cat->parent) {
			  checkCurrentCat($cat2,$categories);
		  }
		}
	}
}

/* TODO 
* add depth option
* add option to display number of comments
*/
function getSubPosts($posts, $cat2, $showPosts, $theID) {
  /* returns all the posts for a given category */
  global $postsToExclude, $options, $thisPost, $collapsCatItems;
  extract($options);
  $posttext2='';
  if ($excludeAll==0 && !$showPosts) {
    $subCatPostCount2=$cat2->count;
  } else { 
    $subCatPostCount2=0;
    if (count($posts)==0) {
      return array(0,'');
    }
    foreach ($posts as $post2) {
      if ($post2->term_id != $cat2->term_id)
        continue;
      if (!in_array($post2->ID, $postsToExclude)) {
        $subCatPostCount2++;
        if (!$showPosts) {
          continue;
        }
        if (is_single() && $post2->ID == $thisPost)
          $self="class='self'";
        else
          $self="";
        $date=preg_replace("/-/", '/', $post2->date);
        $name=$post2->post_name;
        $title_text = htmlspecialchars(strip_tags(__($post2->post_title),
        'collapsing-categories'), 
            ENT_QUOTES);
        $tmp_text = '';
        if ($postTitleLength> 0 && strlen($title_text) > $postTitleLength ) {
          $tmp_text = substr($title_text, 0, $postTitleLength );
            $tmp_text .= ' &hellip;';
        }
        $linktext = $tmp_text == '' ? $title_text : $tmp_text;
        if ($showPostDate) {
          $theDate = mysql2date($postDateFormat, $post2->post_date );
          if ($postDateAppend=='before') {
            $linktext = "$theDate $linktext";
          } else {
            $linktext = "$linktext $theDate";
          }
        }
        $posttext2.= "<li class='collapsing categories item'><a $self " . 
            "href='".get_permalink($post2).
            "' title='$title_text'>$linktext</a></li>\n";
      }
    }
  }
  return array($subCatPostCount2, $posttext2);
}

function addFeedLink($feed,$cat) {
  /* returns a link to the rss feed for a given category */
  if ($feed=='text') {
    $rssLink= '<a href="' . get_category_feed_link($cat->term_id) .
        '">&nbsp;(RSS)</a>';
  } elseif ($feed=='image') {
    $rssLink= '<a href="' . get_category_feed_link($cat->term_id) .
        '">&nbsp;<img src="' .get_settings(siteurl) .
        '/wp-includes/images/rss.png" /></a>';
  } else {
    $rssLink='';
  }
  return $rssLink;
}

function get_sub_cat($cat, $categories, $parents, $posts,
  $subCatCount,$subCatPostCount,$expanded, $depth) {
  /* returns all the subcategories for a given category */
  global $options, $collapsCatItems, $autoExpand, $postsToExclude, 
      $totalCatPostCount, $catlink, $postsInCat, $cur_categories, $thisCatID;
  $subCatLinks='';
  $postself='';
  extract($options);
  $link2='';
  $depth++;
  if (in_array($cat->term_id, $parents)) {
    foreach ($categories as $cat2) {
      $subCatLink2=''; // clear info from subCatLink2
      if ((is_category() || is_tag()) && ($cat2->term_id==$thisCatID)) {
        $self="class='self'";
      } else {
        $self="";
      }
      if ($cat->term_id==$cat2->parent) {
        $theID='collapsCat-' . $cat2->term_id . ":$number";
        list($subCatPostCount2, $posttext2) = 
            getSubPosts($postsInCat[$cat2->term_id],$cat2, $showPosts, $theID);
        $totalCatPostCount+=$subCatPostCount2;
        $subCatPostCount+=$subCatPostCount2;
        $expanded='none';
        if (((in_array($cat2->name, $autoExpand) ||
            in_array($cat2->slug, $autoExpand)) && $expandCatPost) ||
            ($useCookies && $_COOKIE[$theID]==1)) {
          $expanded='block';
        }
        if (!in_array($cat2->term_id, $parents)) {
					// check to see if there are more subcategories under this one
          if ($theID!='' && !$collapsCatItems[$theID]) {
            $collapsCatItems[$theID] = $posttext2;
          }
          $subCatCount=0;
          if ($subCatPostCount2<1) {
            continue;
          }
          if ($showPosts) {
            if ($expanded=='block') {
              $showHide='collapse';
              $symbol=$collapseSym;
            } else {
              $showHide='expand';
              $symbol=$expandSym;
            }
            $subCatLinks.=( "<li class='collapsing categories'>".
                "<span class='collapsing categories $showHide' ".
                "onclick='expandCollapse(event, \"$expandSymJS\",".
                "\"$collapseSymJS\", $animate, \"collapsing categories\"); return false'>" . 
                "<span class='sym'>$symbol</span>" );
          } else {
            $subCatLinks.=( "<li class='collapsing categories item'>" );
          }
          $link2= getCollapsCatLink($cat2,$catlink,$self);
          if ( empty($cat2->description) ) {
            $link2 .= 'title="'. 
                sprintf(__("View all posts filed under %s",
                'collapsing-categories'), 
                wp_specialchars(apply_filters('single_cat_title',$cat2->name))) . '"';
          } else {
            $link2 .= 'title="' . 
                wp_specialchars(apply_filters('description', 
                $cat2->description,$cat2)) . '"';
          }
          $link2 .= '>';
          if ($linkToCat=='yes') {
            if ($showPosts) {
              $subCatLinks.='</span>';
            }
            $link2 .= apply_filters('single_cat_title', $cat2->name).
                '</a>';
          } else {
            $link2 .= apply_filters('single_cat_title', $cat2->name).  '</a>';
            if ($showPosts) {
              $link2 .= "</a></span>";
            }
          }
        } else {
          list ($subCatLink2, $subCatCount,$subCatPostCount2)= 
              get_sub_cat($cat2, $categories, $parents, $posts, $subCatCount,
              $subCatPostCount2,$expanded, $depth);
          $subCatCount=1;
          $subCatPostCount+=$subCatPostCount2;
          if ($subCatPostCount2<1) {
            continue;
          }
          if ($expanded=='block') {
            $showHide='collapse';
            $symbol=$collapseSym;
          } else {
            $showHide='expand';
            $symbol=$expandSym;
          }
          $subCatLinks.=( "<li class='collapsing categories'>".
              "<span class='collapsing categories $showHide' ".
              "onclick='expandCollapse(event, \"$expandSymJS\",".
              "\"$collapseSymJS\", $animate, \"collapsing categories\"); return false'>" . 
              "<span class='sym'>$symbol</span>" );
          $link2=getCollapsCatLink($cat2,$catlink,$self);
          if ( empty($cat2->description) ) {
            $link2 .= 'title="'. 
                sprintf(__("View all posts filed under %s"), 
                wp_specialchars(apply_filters('single_cat_title',$cat2->name))) . '"';
          } else {
            $link2 .= 'title="' . 
                wp_specialchars(apply_filters('description', 
                $cat2->description,$cat2)) . '"';
          }
          $link2 .= '>';
          if ($linkToCat=='yes') {
            $subCatLinks.='</span>';
            $link2 .= apply_filters('single_cat_title', $cat2->name).'</a>';
          } else {
            if ($showPosts || $subCatPostCount2>0) {
              $link2 .= apply_filters('single_cat_title',$cat2->name) . '</a></span>';
            } else {
              // don't include the triangles if posts are not shown and there
              // are no more subcategories
                $link2 .= apply_filters('single_cat_title',$cat2->name).'</a>';
                $subCatLinks = "      <li class='collapsing categories item'>";
            }
          }
        }
        if( $showPostCount=='yes') {
          $theCount=$subCatPostCount2;
          $link2 .= ' ('.$theCount.')';
        }
        $subCatLinks.= $link2 ;
        $rssLink=addFeedLink($catfeed,$cat2);
        $subCatLinks.=$rssLink;
        if (($subCatCount>0) || ($showPosts)) {
          $subCatLinks.="\n<ul id='$theID' style=\"display:$expanded\">\n";
          if ($subCatCount>0 && $posttext2!='' && $addMisc) {
            $posttext2=miscPosts($cat2,$catlink,$subCatPostCount2,
                $posttext2);
          }
          if ($expanded=='block') {
            $subCatLinks.=$posttext2;
          } else {
            $subCatLinks.='<li></li>';
          }
        }
        // add in additional subcategory information
        $subCatLinks.="$subCatLink2";
        if ($theID!='' && !$collapsCatItems[$theID]) {
          $collapsCatItems[$theID] =  $posttext2 . $subCatLink2;
        }
        // close <ul> and <li> before starting a new category
        if (($subCatCount>0) || ($showPosts)) {
          $subCatLinks.= "          </ul>\n";
        }
        $subCatLinks.= "         </li> <!-- ending subcategory -->\n";
      }
    }
  }
  return(array($subCatLinks,$subCatCount,$subCatPostCount));
}

function get_collapscat_fromdb($args='') {
  global $expandSym,$collapseSym,$expandSymJS, $collapseSymJS, 
      $wpdb,$options,$wp_query, $autoExpand, $postsToExclude, 
      $postsInCat;
  include('defaults.php');
  $options=wp_parse_args($args, $defaults);
  extract($options);
  if ($expand==1) {
    $expandSym='+';
    $collapseSym='—';
  } elseif ($expand==2) {
    $expandSym='[+]';
    $collapseSym='[—]';
  } elseif ($expand==3) {
    $expandSym="<img src='". get_settings('siteurl') .
         "/wp-content/plugins/collapsing-categories/" . 
         "img/expand.gif' alt='expand' />";
    $collapseSym="<img src='". get_settings('siteurl') .
         "/wp-content/plugins/collapsing-categories/" . 
         "img/collapse.gif' alt='collapse' />";
  } elseif ($expand==4) {
    $expandSym=$customExpand;
    $collapseSym=$customCollapse;
  } else {
    $expandSym='▶';
    $collapseSym='▼';
  }
  if ($expand==3) {
    $expandSymJS='expandImg';
    $collapseSymJS='collapseImg';
  } else {
    $expandSymJS=$expandSym;
    $collapseSymJS=$collapseSym;
  }
	$inExclusionArray = array();
	if ( !empty($inExcludeCats )) {
		$exterms = preg_split('/[,]+/',$inExcludeCats);
		if ( count($exterms) ) {
			foreach ( $exterms as $exterm ) {
					$sanitizedTitle = sanitize_title($exterm);
			  $inExclusionArray[] = $sanitizedTitle;
				if (empty($inExclusions))
					$inExclusions = "'$sanitizedTitle'";
				else
					$inExclusions .= ", '$sanitizedTitle'";
			}
		}
	}
	if ( empty($inExclusions) || $inExclude=='include' ) {
		$inExcludeQuery = "";
  } else {
    $inExcludeQuery ="AND t.slug NOT IN ($inExclusions)";
  }

  $isPage='';
  if (!$showPages) {
    $isPage="AND p.post_type='post'";
  }
  if ($catSort!='') {
    if ($catSort=='catName') {
      $catSortColumn="ORDER BY t.name";
    } elseif ($catSort=='catId') {
      $catSortColumn="ORDER BY t.term_id";
    } elseif ($catSort=='catSlug') {
      $catSortColumn="ORDER BY t.slug";
    } elseif ($catSort=='catOrder') {
      $catSortColumn="ORDER BY t.term_order";
    } elseif ($catSort=='catCount') {
      $catSortColumn="ORDER BY tt.count";
    }
  } 
  if ($postSort!='') {
    if ($postSort=='postDate') {
      $postSortColumn="ORDER BY p.post_date";
    } elseif ($postSort=='postId') {
      $postSortColumn="ORDER BY p.id";
    } elseif ($postSort=='postTitle') {
      $postSortColumn="ORDER BY p.post_title";
    } elseif ($postSort=='postComment') {
      $postSortColumn="ORDER BY p.comment_count";
    }
  } 
	if ($defaultExpand!='') {
		$autoExpand = preg_split('/,\s*/',$defaultExpand);
  } else {
	  $autoExpand = array();
  }

	if ($catTag == 'tag') {
	  $catTagQuery= "'post_tag'";
	} elseif ($catTag == 'both') {
	  $catTagQuery= "'category','post_tag'";
	} else {
	  $catTagQuery= "'category'";
	}
	if ($olderThan > 0) {
		$now = date('U');
		$olderThanQuery= "AND  date(post_date) > '" . 
			date('Y-m-d', $now-date('U',$olderThan*60*60*24)) . "'";
	}


  $catquery = "SELECT t.*, tt.* FROM $wpdb->terms AS t INNER JOIN
      $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy IN
      ($catTagQuery) $inExcludeQuery AND t.slug!='blogroll' 
      $catSortColumn $catSortOrder ";
  $posts = NULL;
  if ($showPosts) {
    $postsInCat=array();
    $postquery= "select ID, slug, date(post_date) as date, post_status,
         post_date, post_author, post_title, post_name, name, object_id,
         t.term_id from $wpdb->term_relationships AS tr, $wpdb->posts AS p,
         $wpdb->terms AS t, $wpdb->term_taxonomy AS tt
         WHERE tt.term_id = t.term_id 
         AND object_id=ID 
         $olderThanQuery
         AND post_status='publish'
         AND tr.term_taxonomy_id = tt.term_taxonomy_id 
         AND tt.taxonomy IN ($catTagQuery) $isPage $postSortColumn $postSortOrder";
    $posts= $wpdb->get_results($postquery); 
    foreach ($posts as $post) {
      if (!$postsInCat[$post->term_id]) {
        $postsInCat[$post->term_id]=array();
      }
      array_push($postsInCat[$post->term_id], $post);
    }
  }
  $categories = $wpdb->get_results($catquery);
  $totalPostCount=count($posts);
  if ($totalPostCount>5000) {
    $options['showPosts']=false;
    $showPosts=false;
  }
  $includeCatArray=array();
  $parents=array();
  foreach ($categories as $cat) {
    // if only including certain categories, we build an array of those
    // category ids 
    if ($inExclude=='include' && $inExclusionArray!='') {
      $includes = add_to_includes($cat, $inExclusionArray);
      if (!empty($includes)) {
        $includeCatArray = array_merge($includeCatArray, $includes);
      }
    }
    if ($cat->parent!=0) {
      array_push($parents, $cat->parent);
    }
  }
  $includeCatArray = array_unique($includeCatArray);
	$postsToExclude=array();
	if ($excludeAll==1) {
		foreach ($posts as $post) {
			if (in_array($post->slug, $inExclusionArray)) {
				array_push($postsToExclude, $post->ID);
			}
		}
	}
  // add in computed options to options array
  $computedOptions = compact('includeCatArray', 'expandSym', 'expandSymJS', 
      'collapseSym', 'collapseSymJS');
  $options = array_merge($options, $computedOptions);
  if ($debug==1) {
    echo "<li style='display:none' >";
    printf ("MySQL server version: %s\n", mysql_get_server_info());
    echo "\ncollapsCat options:\n";
    print_r($options);
    echo "\npostsToExclude:\n";
    print_r($postsToExclude);
    echo "CATEGORY QUERY: \n $catquery\n";
    echo "\nCATEGORY QUERY RESULTS\n";
    print_r($categories);
    echo "POST QUERY:\n $postquery\n";
    echo "\nPOST QUERY RESULTS\n";
    print_r($posts);
    echo "</li>";
  }
  return(array($posts, $categories, $parents, $options));
}

function list_categories($posts, $categories, $parents, $options) {
  /* returns a list of categories, and optionally subcategories and posts,
  which can be collapsed or expanded with javascript */
  global $collapsCatItems, $wpdb,$options,$wp_query, $autoExpand, 
      $postsToExclude, $totalCatPostCount, $thisCatID,
      $cur_categories, $thisPost, $wp_rewrite, $catlink, $postsInCat;
  extract($options);
  $cur_categories = array();
  if (is_single()) {
    $tmp_categories = get_the_category();
    foreach ($tmp_categories as $tmp_cat) {
      $cur_categories[] = $tmp_cat->term_id;
    }
    $thisPost = $wp_query->post->ID;
    foreach ($categories as $cat) {
      if (!empty($cur_categories) && (in_array($cat->term_id, $cur_categories))) {
        checkCurrentCat($cat,$categories);
      }
    }
  } 
  if (is_category() || is_tag()) {
    $thisCatID = get_query_var('cat');
    $thisCat = get_category($thisCatID);
    checkCurrentCat($thisCat,$categories);
  }
  $catlink = $wp_rewrite->get_category_permastruct();


  foreach( $categories as $cat ) {
    $totalCatPostCount=0;
    if ($inExclude=='include' && !empty($includeCatArray)) {
      if (!in_array($cat->term_id, $includeCatArray) &&
          !in_array($cat->post_parent, $includeCatArray)) {
        continue;
      } else {
      }
    }
    if ($cat->parent!=0 )
      continue;
    if ((is_category() || is_tag()) && ($cat->term_id==$thisCatID)) {
      $self="class='self'";
    } else {
      $self="";
    }
    $rssLink=addFeedLink($catfeed,$cat);
    $subCatPostCount=0;
    $subCatCount=0;
    list ($subCatLinks, $subCatCount,$subCatPostCount)=
        get_sub_cat($cat, $categories, $parents, $posts, 
        $subCatCount,$subCatPostCount,$expanded,0);
    list($subCatPostCount2, $posttext2) = 
        getSubPosts($postsInCat[$cat->term_id],$cat, $showPosts, $theID);
      
    $theCount=$subCatPostCount2 + $totalCatPostCount;
    if ($theCount>0) {
      $expanded='none';
      $theID='collapsCat-' . $cat->term_id . ":$number";
      if (((in_array($cat->name, $autoExpand) ||
          in_array($cat->slug, $autoExpand)) && $expandCatPost) ||
          ($useCookies && $_COOKIE[$theID]==1)) {
        $expanded='block';
      }

      if ($showPosts || $subCatPostCount>0) {
        if ($expanded=='block') {
          $showHide='collapse';
          $symbol=$collapseSym;
        } else {
          $showHide='expand';
          $symbol=$expandSym;
        }
        $span= "      <li class='collapsing categories'>".
            "<span class='collapsing categories $showHide' ".
            "onclick='expandCollapse(event, \"$expandSymJS\"," .
            "\"$collapseSymJS\", $animate, \"collapsing categories\"); return false'>".
            "<span class='sym'>$symbol</span>";
      } else {
        $span = "      <li class='collapsing categories item'>";
      }
      $link=getCollapsCatLink($cat,$catlink,$self);
      if ( empty($cat->description) ) {
        $link .= 'title="'. 
            sprintf(__("View all posts filed under %s",
            'collapsing-categories'),
            wp_specialchars(apply_filters('single_cat_title',$cat->name))) . '"';
      } else {
        $link .= 'title="' . wp_specialchars(apply_filters(
            'description',$cat->description,$cat)) . '"';
      }
      $link .= '>';
      if ($linkToCat=='yes') {
        $link .= apply_filters('single_cat_title', $cat->name).'</a>';
        if ($showPosts || $subCatPostCount>0) {
          $span.='</span>';
        }
      } else {
        if ($showPosts || $subCatPostCount>0) {
          $link .= apply_filters('single_cat_title',$cat->name) . '</a></span>';
        } else {
          // don't include the triangles if posts are not shown and there
          // are no more subcategories
            $link .= apply_filters('single_cat_title',$cat->name).'</a>';
            $span = "      <li class='collapsing categories item'>";
        }
      }
      // Now print out the post info
      $posttext='';
      if( ! empty($postsInCat[$cat->term_id]) ) {
        list ($subCatPostCount, $posttext) = getSubPosts($posts, $cat,
            $collapsCatItems, $showPosts);
      }
      if( $showPostCount=='yes') {
        $link .= ' (' . $theCount.')';
      }
      $link.=$rssLink;
      if ($theCount<1) {
        $link='';
        $span='';
      }
      if ($showTopLevel) {
        $collapsCatText.=$span . $link;
        if (($subCatPostCount>0) || ($showPosts)) {
          $collapsCatText .= "\n     <ul id='$theID'" . 
              " style=\"display:$expanded\">\n";
        }
      }
      if ($showPosts) {
        if ($subCatPostCount>0 && $subCatLinks!='' && $addMisc) {
          $posttext = (miscPosts($cat,$catlink,$subCatPostCount2,$posttext));
        }
      }
      /* we only actually add the posts if it is expanded. Otherwise we add
         the posts dynamically to the dom from a javascript array 
         However, we can't have an empty ul, so we create one emtpy li here */
      if ($postsBeforeCats) {
        $text =$posttext . $subCatLinks;
      } else {
        $text = $subCatLinks . $posttext;
      }
      if ($theID!='' && !$collapsCatItems[$theID]) {
        $collapsCatItems[$theID] = $text;
      }
      if ($expanded!='block' && $showTopLevel) {
        $posttext='<li></li>';
      } 
      if ($postsBeforeCats) {
        $text =$posttext . $subCatLinks;
      } else {
        $text = $subCatLinks . $posttext;
      }
      $collapsCatText .= $text;
      if ($showTopLevel) {
        if ($subCatPostCount>0 || $showPosts) {
          $collapsCatText .= "        </ul>\n";
        }
        $collapsCatText .= "      </li> <!-- ending category -->\n";
      }
    } // end if theCount>0
  }
  return(array($collapsCatText, $postsInCat));
}
?>
