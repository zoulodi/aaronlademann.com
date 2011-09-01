<!DOCTYPE html>
<html><head>
<!-- force latest IE rendering || force Chrome Frame if installed -->
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta charset="utf-8" />
<!-- TemplateBeginEditable name='docmeta' -->
<title>Aaron Lademann { Web Designer | Innovator | Web Developer }</title>
<meta name="description" content="Aaron Lademann provides innovative business solutions for companies seeking creativity and leadership." />
<meta name="keywords" content="Aaron Lademann, Lademann, alademann, trueson82" />
<!-- TemplateEndEditable -->
<meta name="author" content="Aaron Lademann" />
<meta name="copyright" content="Copyright Aaron Lademann. All Rights Reserved." />

<!-- mobile viewport optimized -->
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="shortcut icon" href="/favicon.ico" />
<link rel="stylesheet" type="text/css" href="http://aaronlademann.com/_includes/_css/base.css" media="all" />
<!-- TemplateBeginEditable name='head_before_scripts' -->
<!-- TemplateEndEditable -->
<script src="/_includes/_js/head.min.js"></script>
<!-- TemplateBeginEditable name='head_after_scripts' -->
<!-- TemplateEndEditable -->
</head>
<body>
<div id="masthead">
	<a id="mastlogo" href="/"><img src="http://aaronlademann.com/_images/_template/masthead-aaronlademann.com-logo.png" alt="aaronlademann.com" width="294" height="52" align="left" /></a>
	<!-- TemplateBeginEditable name='masthead' -->
    <h1>Aaron Lademann - Web Designer, Innovator, Web Developer</h1>
  	<h2>aaronlademann.com</h2>
    <div class="nav" id="topNav">
    	<ul class="home">
    	<!--#include virtual='/_includes/_content/nav.asp' -->
        </ul>
    </div>
    <!-- TemplateEndEditable -->
</div>
<div id="contentWrap">
<!-- TemplateBeginEditable name='body' -->

<!-- TemplateEndEditable -->
</div>
<div id="footer">
	<div class="nav" id="footerNav">
    
	<!-- TemplateBeginEditable name='footnav' -->
    	<ul class="home">
			<!--#include virtual='/_includes/_content/nav.asp' -->
	<!-- TemplateEndEditable -->      
    		<li><a href="http://www.linkedin.com/in/aaronlademann" target="_blank">LinkedIn</a></li>
    		<li><a href="http://twitter.com/alademann" target="_blank">twitter</a></li>    
    	</ul> 
    </div>
    <p id="copyright"><small>&copy; <% Response.Write Year(now) %></small></p>
</div>
<script> 
		head.js("/_includes/_js/jquery-1.6.2.min.js").js("/_includes/_js/analytics.js", 
		function() {
			// inline scripts here			
			var nav = $(".nav");
			$.each(nav,function(){
				var define = $(this).find("ul").attr("class");
				$(this).find("ul > li[id='" + define + "']").addClass("active");
			});
		});
</script>
<!-- TemplateBeginEditable name='end_body_scripts' -->

<!-- TemplateEndEditable-->
</body>
</html>
