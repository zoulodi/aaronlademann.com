<?php
 if ( -1 == $number ) {
    /* default options go here */
    $title = 'Categories';
    $showPostCount = 'yes';
    $catSort = 'catName';
    $catSortOrder = 'ASC';
    $postSort = 'postTitle';
    $postSortOrder = 'ASC';
    $defaultExpand='';
    $number = '%i%';
    $expand='1';
    $customExpand='';
    $customCollapse='';
    $postTitleLength='0';
    $inExclude='include';
    $inExcludeCats='';
    $showPosts='yes';
    $linkToCat='yes';
    $showPages='no';
    $animate='1';
    $debug='0';
    $catfeed='none';
    $catTag='cat';
    $olderThan=0;
    $excludeAll='0';
    $addMisc=false;
    $showTopLevel=true;
    $postsBeforeCats=false;
    $addMiscTitle='Miscellaneous';
  } else {
    $title = attribute_escape($options[$number]['title']);
    $showPostCount = $options[$number]['showPostCount'];
    $expand = $options[$number]['expand'];
    $customExpand = $options[$number]['customExpand'];
    $customCollapse = $options[$number]['customCollapse'];
    $postTitleLength = $options[$number]['postTitleLength'];
    $inExcludeCats = $options[$number]['inExcludeCats'];
    $inExclude = $options[$number]['inExclude'];
    $catSort = $options[$number]['catSort'];
    $catSortOrder = $options[$number]['catSortOrder'];
    $postSort = $options[$number]['postSort'];
    $postSortOrder = $options[$number]['postSortOrder'];
    $defaultExpand = $options[$number]['defaultExpand'];
    $showPosts = $options[$number]['showPosts'];
    $showPages = $options[$number]['showPages'];
    $linkToCat = $options[$number]['linkToCat'];
    $animate = $options[$number]['animate'];
    $debug = $options[$number]['debug'];
    $showTopLevel = $options[$number]['showTopLevel'];
    $postsBeforeCats = $options[$number]['postsBeforeCats'];
    $catfeed = $options[$number]['catfeed'];
    $catTag = $options[$number]['catTag'];
    $olderThan = $options[$number]['olderThan'];
    $excludeAll = $options[$number]['excludeAll'];
    $addMisc = $options[$number]['addMisc'];
    $addMiscTitle = $options[$number]['addMiscTitle'];
  }
?>
