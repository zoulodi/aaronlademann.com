$(window).load(function(){
												
	//---------------------- add borders to any images that are not 0x0 spacer.gif images
	fixBrokenImages("#bodyWrapper","border");
	
});	//-------------------------------------------------------------- END window.load()

$(document).ready(function(){

	//---------------------- handle gallery wordings	
		adjustGalleryHeadings();
	//---------------------- fix any broken images that return errors on DOM ready
		fixBrokenImages("#bodyWrapper","error");			
	//---------------------- handle login logout link in custom footer
		logInOrOut();
	
	//---------------------- load menu js
	$.ajax({
		type: "GET",
		url: "http://aaronlademann.com/_includes/_nav/menu.js",
		dataType: "script"
	});

}); //-------------------------------------------------------------- END document.ready();