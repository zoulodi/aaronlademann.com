// --------------------------------------------------------------------------
//	functions called from bottomjs inline area on photos.aaronlademann.com
//  dependencies: jquery
//	copyright - Aaron Lademann, aaronlademann.com
// --------------------------------------------------------------------------

function adjustGalleryHeadings(){
	// remove "galleries" and "gallery" from all h3 tags
	var galleriesHeader = $("#bodyWrapper").find("h3.title");
	if(galleriesHeader){
		// cycle through each header found within #bodyWrapper
		$.each(galleriesHeader,function(){
		
		// set vars
			var hText = $(this).text();
			var isGalleries = hText.indexOf("Galleries");
			var isGallery = hText.indexOf("Gallery");
			var isSubCat  = hText.indexOf("Sub-Categories");
			var wordwerelookingfor = "";
			//	console.log("before if(isGalleries||isGallery): \n" + "isGalleries = " + isGalleries + "\n isGallery = " + isGallery);
		
		//  don't go through all this hassle unless we find the words we're interested in.
			if(isGalleries != -1 || isGallery != -1 || isSubCat != -1){
				if(isGalleries != -1){
					wordwerelookingfor = "Galleries";
				} 
				if(isGallery != -1){
					wordwerelookingfor = "Gallery";
				}
				if(isSubCat != -1){
					wordwerelookingfor = "Sub-Categories";
				}
				// now that we know what we're looking for... hunt it down in the header string contents
				huntdowntheword(wordwerelookingfor,hText,this);
			} // end if(isGalleries || isGallery)
			// show all h3s again
			$(this).css("visibility","visible");
		}); // end .each(galleriesHeader)
	} // end if(galleriesHeader)

} //--------------------------------------------------------------  end fn(adjustGalleryHeadings())

function huntdowntheword(word,header,elem){
	
	// set vars
	var ln = header.length; // length of entire string within the <h3> tag were checking
	var li = header.lastIndexOf(word); // index location of the first letter of the word we're hunting
	var end = word.length; // index length of the word we're hunting
	var newText; // empty string var for us to place the replacement text in
	var newText2; // need this if the word we are looking for ends up being in the middle of the string
	
	// make sure that the words we are looking for aren't the only word in the header
	// since the purpose of this is to remove Gallery of Galleries when the name of the gallery might sound funny (e.g. "Album Galleries")
	if(ln != end){
		
		// now that we caught what we're hunting, we need to decide how to skin this cat. 
		// if the word we found is at the beginning of the string, skin it one way, otherwise - skin it backwards.
		
		if(li == 0){								// its at the beginning
		
			newText = header.substring((li+end),ln);
			
		} else if(li+end == ln){		// its at the end
		
				if(word == "Sub-Categories"){
					
					newText = header.substring(0,li) + " By Category";
					
				} else {
					
					newText = header.substring(0,li);
					
				} // end if(word == "Sub-Categories")
				
		} else { 										// its somewhere in the middle
			
			newText = header.substring(0,li);						// find the stuff BEFORE we stumble upon the word we're looking for
			newText2 = header.substring((li+end),ln);		// find the stuff AFTER we stumble upon the word we're looking for
			// now that we have our two pieces... put em together
			newText = newText.concat(newText," ",newText2);
			
		} // end evaluation of where within the target string our hunted word exists
		
	} else {
		// the word we're hunting for is the ONLY word there... so there's nothing to do.
		
		newText = header;
		
	} // end if(li!=end)
	
	// insert our new header into the target <h3> tag
	$(elem).text(newText); 
		
} 
//-------------------------------------------------------------- end fn(huntdowntheword())

function fixBrokenImages(container,type){
	
	var theseImages = $(container).find('img');
	comingSoonImg = "http://aaronlademann.com/_images/_template/photos_thumbnail-coming-soon_100x100.png";
	
	if(type = "error"){
		
		$.each(theseImages, function(){
																 
			//replace the broken image with another image
			$(this).bind('error', function(){
					$(this).attr("src", "http://aaronlademann.com/_images/_template/photos_thumbnail-coming-soon_100x100.png");
					$(this).addClass("error");
			});
			
		});

	
	} // end if(type=error)
	
	if(type = "border"){
		
		$.each(theseImages, function(){
			
			if( $(this).width() > 0 ){
				$(this).addClass("imgBorderSuccess");
			}
			
		});
	
	} // end if(type=error)

} //--------------------------------------------------------------  end fn(fixBrokenImages())

function logInOrOut(){
	
	var bodyClass = $("body").attr("class");
	var loginlink = $("#custom_footer").find("#footerLogin");
	var linkloc;
	var newtext;
	var notLoggedIn = bodyClass.indexOf("notLoggedIn");
	if(notLoggedIn == "-1"){
		linkloc = "logout.mg?skipDomainCheck=1&goTo=" + document.location.protocol + "//" + document.location.host + document.location.pathname + document.location.hash;
		newtext = "Logout";
	} else {
		linkloc = "login.mg?skipDomainCheck=1?goto=" + document.location.protocol + "//" + document.location.host + document.location.pathname + document.location.hash;
		newtext = "Login";
	}	
	var newlink = "https://secure.smugmug.com/" + linkloc;
	$(loginlink).attr("href",newlink);
	$(loginlink).text(newtext);
	$(loginlink).css("visibility","visible");
	
} //--------------------------------------------------------------  end fn(logInOrOut)