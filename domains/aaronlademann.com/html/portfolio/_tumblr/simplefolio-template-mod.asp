<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=8" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=905, user-scalable=yes" />
        <meta name="tumblr-theme" content="8955" />
	
	<meta name="text:First Nav Link" content="Work">
	<meta name="text:Slogan" content=""/>
	<meta name="text:Contact Email" content=""/>
	<meta name="image:Sidebar" content=""/>
	
	<meta name="if:Show Address" content="0"/>
	<meta name="text:Office Location" content=""/>
	<meta name="text:Office Street" content=""/>
	<meta name="text:Office City" content=""/>
	<meta name="text:Office Postal" content=""/>
	
	{block:Description}
	<meta name="description" content="{MetaDescription}" />
  {/block:Description}

  {block:PermalinkPage}
  	<title>{Title} of Aaron Lademann - aaronlademann.com{block:PostTitle} - {PostTitle}{/block:PostTitle}</title>
  {/block:PermalinkPage}
  
  {block:IndexPage} 
	<title>{block:TagPage}{Tag} projects - {/block:TagPage}{Title} of Aaron Lademann - aaronlademann.com{block:PostTitle} - {PostTitle}{/block:PostTitle}</title>
  {/block:IndexPage}
  
	<link rel="alternate" type="application/rss+xml" href="{RSS}" />
	<link rel="shortcut icon" href="http://aaronlademann.com/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="http://aaronlademann.com/_includes/_css/base.css" media="all" />
	<link rel="stylesheet" type="text/css" href="http://aaronlademann.com/_includes/_css/portfolio.css" media="all" />
	
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.min.js"></script>
	<script type="text/javascript" charset="utf-8">
		$(function(){
			var url = window.location.pathname;
			var windowURL = window.location;
			var navitems = $('#nav ul li a');
			var crumbitems = $('.crumbTitles .link');
			var pathArray = url.split('/').pop();
			
			$.each(navitems, function(i, e) {
				if($(this).attr('href') == url) {
					$(this).parent().addClass('active');
				}
			});
			
			var crumbTitles = $('.crumbTitles');
			
				if(windowURL == 'http://portfolio.aaronlademann.com/') {
					$(crumbTitles).find('#section').addClass('active').removeClass('link');
					$(crumbTitles).find('#page-title').remove();
					$(crumbTitles).find('#section + .divider').remove();
				} else {
					$(crumbTitles).find('#page-title').addClass('active').removeClass('link');
					var section = $(crumbTitles).find('#section');
					var secText = $(section).text();
					$(section).before('<a href="http://portfolio.aaronlademann.com" class="h1">' + secText + '</a>');
						$(section).remove();
				}
			
			// take all of the <h1> elements from each post caption and convert it into the label for the
			// list of projects.
			{block:IndexPage}
			var projects = $('#projects ul li');
			$.each(projects, function(i, e){
				var postID = $(this).attr('id');
				if ($('#' + postID + ' .source h1').length > 0) {
					var pageTitle = $('#' + postID + ' .source h1').text();
					$('li#' + postID + ' span#' + postID + '-link-title').text(pageTitle);
					// remove the dupe content
					$('#' + postID + ' .source').remove();
				}
			});
			{/block:IndexPage}
			
			// move the <h1> element from the caption area up to the main title area of the page.
			{block:PermalinkPage}
			if ($('#description h1').length > 0) {
				var pageTitle = $('#description h1').text();
	    		$('h1#page-title').text(pageTitle);
				// remove the dupe content
				$('#description h1').remove();
			}
			
			{/block:PermalinkPage}
			
			
		});
	</script>
    <script type="text/javascript">

	  var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-3765006-1']);
		_gaq.push(['_setDomainName', '.aaronlademann.com']);
		_gaq.push(['_setAllowHash', false]);
		_gaq.push(['_trackPageview']); 
	
	
	  (function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
	
	</script>
</head>  		 	
<body>
<div id="masthead">
	<a id="mastlogo" href="http://aaronlademann.com/"><img src="http://aaronlademann.com/_images/_template/masthead-aaronlademann.com-logo.png" alt="www.aaronlademann.com" width="294" height="52" align="left" /></a>
	
    <p class="h1">{Title}</p>
    <div class="nav" id="topNav">
    	<ul class="portfolio">
    		<li id="home"><a href="http://aaronlademann.com">Home</a></li>
            <li id="portfolio"><a href="http://portfolio.aaronlademann.com">Portfolio</a></li>
            <li id="about"><a href="http://aaronlademann.com/about/">About</a></li>
            <li id="contact"><a href="mailto:{text:Contact Email}" rel="nofollow">Contact</a></li>
            <li id="resume"><a href="http://aaronlademann.com/files/Lademann_Resume_0409.pdf">Resume</a></li>
        </ul>
    </div>
    
</div>
<div id="contentWrap">
<div id="wrapper">
		<div class="container">
			<div id="header">
            <!--BREADCRUMB NAV TITLES-->
				<div id="title" class="crumbTitles">
					
                    <a class="link" id="home" title="Home" href="http://aaronlademann.com">aaronlademann.com</a> 
                    	<span class="divider">&nbsp;</span>
                    <h1 id="section" class="link">{Title}</h1>
                    	<span class="divider">&nbsp;</span>
                    <h1 id="page-title" class="link">{block:TagPage}{Tag} Projects{/block:TagPage}</h1>
				</div>
            <!--/ BREADCRUMB NAV TITLES-->
				{block:HasPages}
				<div id="nav">
					<ul>
						{block:IfFirstNavLink}
						<li{block:IndexPage} class="active"{/block:IndexPage}>
							<a href="http://portfolio.aaronlademann.com/">
								<span>{text:First Nav Link}</span>
							</a>
						</li>
						{/block:IfFirstNavLink}
						{block:Pages}
						<li>
							<a href="{URL}">
								<span>{Label}</span>
							</a>
						</li>
						{/block:Pages}
					</ul>
				</div>
				<!-- / div#nav -->
				{/block:HasPages}
				
				{block:IfContactEmail}
				<div class="button right">
					<a href="mailto:{text:Contact Email}">
						<span>Get In Touch</span>
					</a>
				</div>
				{/block:IfContactEmail}
			</div>
			<!-- / div#header -->
		</div>
		<!-- / div.container -->
		
		{block:TagPage}
		<div class="container">
			<div id="tag-header">
				<!--<h2>{Tag} Projects</h2>-->
			</div>
		</div>
		<!-- /div.container -->
		{/block:TagPage}
		
		{block:IndexPage}
		
		{block:Description} 
		<div class="container"{block:TagPage} style="display:none;"{/block:TagPage}>
			<div id="intro">
				<h2>{text:Slogan}</h2>
				<p>{Description}</p>
			</div>
		</div>
		<!-- /div.container -->
		{/block:Description}
			<div id="projects-container">
				<div id="projects">
					<ul>
						{block:Posts}
						
						{block:Photo}
						<li id="{PostID}">
							<a href="{Permalink}" id="{PostID}-permalink">
								<span class="img-wrap" style="background-image: url({PhotoURL-HighRes});"></span>
								<span id="{PostID}-link-title" class="project-title"></span>
                                <span id="{PostID}" class="source">{Caption}</span>
							</a>
							
						</li>
						{/block:Photo}
						
						{block:Video}
						<li id="{PostID}">
							<a href="#" onclick="javascript:return false;" id="{PostID}-permalink">
								<span class="img-wrap">
									<img src="http://aaronlademann.com/_images/_template/_portfolio/photo_only.gif" alt="Photo posts only"/>
								</span>
								<span class="project-title">This template uses photos only</span>
							</a>
						</li>
						{/block:Video}
						
						{block:Text}
						<li id="{PostID}">
							<a href="#" onclick="javascript:return false;" id="{PostID}-permalink">
								<span class="img-wrap">
									<img src="http://aaronlademann.com/_images/_template/_portfolio/photo_only.gif" alt="Photo posts only"/>
								</span>
								<span class="project-title">This template uses photos only</span>
							</a>
						</li>
						{/block:Text}
						
						{block:Quote}
						<li id="{PostID}">
							<a href="#" onclick="javascript:return false;" id="{PostID}-permalink">
								<span class="img-wrap">
									<img src="http://aaronlademann.com/_images/_template/_portfolio/photo_only.gif" alt="Photo posts only"/>
								</span>
								<span class="project-title">This template uses photos only</span>
							</a>
						</li>
						{/block:Quote}
						
						{block:Link}
						<li id="{PostID}">
							<a href="#" onclick="javascript:return false;" id="{PostID}-permalink">
								<span class="img-wrap">
									<img src="http://aaronlademann.com/_images/_template/_portfolio/photo_only.gif" alt="Photo posts only"/>
								</span>
								<span class="project-title">This template uses photos only</span>
							</a>
						</li>
						{/block:Link}
						
						{block:Chat}
						<li id="{PostID}">
							<a href="#" onclick="javascript:return false;" id="{PostID}-permalink">
								<span class="img-wrap">
									<img src="http://aaronlademann.com/_images/_template/_portfolio/photo_only.gif" alt="Photo posts only"/>
								</span>
								<span class="project-title">This template uses photos only</span>
							</a>
						</li>
						{/block:Chat}
						
						{block:Audio}
						<li id="{PostID}">
							<a href="#" onclick="javascript:return false;" id="{PostID}-permalink">
								<span class="img-wrap">
									<img src="http://aaronlademann.com/_images/_template/_portfolio/photo_only.gif" alt="Photo posts only"/>
								</span>
								<span class="project-title">This template uses photos only</span>
							</a>
						</li>
						{/block:Audio}
						
						{/block:Posts}
					</ul>

				</div>

			</div>



		<!-- / div.container -->
		{/block:IndexPage}


	{block:Posts}
		
		{block:PermalinkPage}
		
		{block:Photo}
		<div class="container">
			<div id="content">
				<div id="banner">
                	
                    <div class="viewopt" style="float: left;">
                    	<ul class="tags">
                        	<li><a href="#"><span><img src="http://aaronlademann.com/_images/_template/_portfolio/ico_view-description.png" /></span></a></li>
                        	<li><a href="#"><span><img src="http://aaronlademann.com/_images/_template/_portfolio/ico_view-image.png" /></span></a></li>
                        </ul>
                    </div>
                
                	<div class="meta">
                        {block:HasTags}
                        <p class="h2">META: </p>
                        <ul class="tags" style="float: left;">
                            {block:Tags}
                            <li>
                                <a href="{TagURL}">
                                    <span>{Tag}</span>
                                </a>
                            </li>
                            {block:Tags}
                            
                        </ul>
                        {/block:HasTags}
                    </div>
					<div class="img-wrap-fullsize">
						{LinkOpenTag}<img src="{PhotoURL-HighRes}" alt="{PhotoAlt}"/>{LinkCloseTag}
					</div>
				</div>
				<div style="clear:both;"></div>
				<div id="description" class="left-column">
					{Caption}
					<div class="back button">
						<a href="http://portfolio.aaronlademann.com/">
							<span>Portfolio home</span>
						</a>
					</div>
				</div>
				<!-- / div.left-column -->
				
				<div id="sidebar" class="right-column">
					

                    
				</div>
				<!-- / div.right-column -->
			</div>
			<!-- / div#content -->
		</div>
		{/block:Photo}
		{/block:PermalinkPage}
		
		{block:Text}
		<div class="container">
			<div id="content">
				<div class="left-column">
					{Body}
				</div>
				<!-- / div.left-column -->
			
				<div id="sidebar" class="right-column">
					{block:IfSidebarImage}
					<div class="img-wrap-360">
						<img src="{image:Sidebar}" alt=""/>
					</div>
					{/block:IfSidebarImage}
					{block:IfShowAddress}
					<div class="hr">
						<hr />
					</div>
					<h4>{text:Office Location}</h4>
					<address>
						{text:Office Street}<br />
						{text:Office City}<br />
						{text:Office Postal}
					</address>
					{/block:IfShowAddress}
				</div>
				<!-- / div.right-column -->
			</div>
			<!-- / div#content -->
		</div>
		<!-- /div.container -->
		{/block:Text}
		
		
		{/block:Posts}
	

		{block:IfContactEmail}
		<div class="container">
			
            {block:Pagination} 
			<div id="pagination">
				{block:PreviousPage}
				<div class="button">
					<a href="{PreviousPage}"><span>{lang:Older}</span></a>
				</div>
				{/block:PreviousPage}
				{block:NextPage}
				<div class="button">
					<a href="{NextPage}"><span>{lang:Newer}</span></a>
				</div>
				{/block:NextPage}
			</div>
			<!-- / div#pagination -->
			{/block:Pagination}


			<!--<div id="footer2">
				<p class="h2">Interested in my work?</p>
				<div class="button">
					<a href="mailto:{text:Contact Email}" rel="nofollow">
						<span>Get In Touch</span>
					</a>
				</div>
			</div>-->
			<!-- / div#footer -->
		</div>
		<!-- /div.container -->
		{/block:IfContactEmail}
	</div>  		 	
	<!-- / div#wrapper -->


</div>
<div id="footer">
	<div class="nav" id="footerNav">
    
	
    	<ul class="portfolio">
			<li id="home"><a href="http://aaronlademann.com">Home</a></li>
            <li id="portfolio"><a href="http://portfolio.aaronlademann.com">Portfolio</a></li>
            <li id="about"><a href="http://aaronlademann.com/about/">About</a></li>
            <li id="contact"><a href="mailto:{text:Contact Email}" rel="nofollow">Contact</a></li>
            <li id="resume"><a href="http://aaronlademann.com/files/Lademann_Resume_0409.pdf">Resume</a></li>
    		<li><a href="http://www.linkedin.com/in/aaronlademann" target="_blank">LinkedIn</a></li>
    		<li><a href="http://twitter.com/alademann" target="_blank">twitter</a></li>    
    	</ul> 
    </div>
    <p id="copyright"><small>&copy; 2010</small></p>
</div>
<script type="text/javascript">
$(document).ready(function(){
	var nav = $(".nav");
	$.each(nav,function(){
		var define = $(this).find("ul").attr("class");
		$(this).find("ul > li[id='" + define + "']").addClass("active");
	});
	
});
</script>    
    <!-- Tumblr Theme #8955 -->
	
</body>
<!--
    This Tumblr Theme and all of its CSS, Javascript,
    and media assets are subject to Tumblr's Terms of Service:

    http://www.tumblr.com/terms_of_service
-->
</html>