function init() {
	tinyMCEPopup.resizeToInnerSize();
}

function getCheckedValue(radioObj) {
	if(!radioObj)
		return "";
	var radioLength = radioObj.length;
	if(radioLength == undefined)
		if(radioObj.checked)
			return radioObj.value;
		else
			return "";
	for(var i = 0; i < radioLength; i++) {
		if(radioObj[i].checked) {
			return radioObj[i].value;
		}
	}
	return "";
}

function tzshortcodesubmit() {
	
	var tagtext;
	
	var tz_shortcode = document.getElementById('tzshortcode_panel');
	
	// who is active ?
	if (tz_shortcode.className.indexOf('current') != -1) {
		var tz_shortcodeid = document.getElementById('tzshortcode_tag').value;
		switch(tz_shortcodeid)
{
case 0:
 	tinyMCEPopup.close();
  break;

case "button":
	tagtext = "["+ tz_shortcodeid + "  url=\"#\" style=\"white\" size=\"small\"] Button text [/" + tz_shortcodeid + "]";
break;

case "alert":
	tagtext = "["+ tz_shortcodeid + " style=\"white\"] Alert text [/" + tz_shortcodeid + "]";
break;

case "toggle":
	tagtext = "["+ tz_shortcodeid + " title=\"Title goes here\"] Content here [/" + tz_shortcodeid + "]";
break;

case "tabs":
	tagtext="["+tz_shortcodeid + " tab1=\"Tab 1 Title\" tab2=\"Tab 2 Title\" tab3=\"Tab 3 Title\"] [tab]Insert tab 1 content here[/tab] [tab]Insert tab 2 content here[/tab] [tab]Insert tab 3 content here[/tab] [/" + tz_shortcodeid + "]";
break;

default:
tagtext="["+tz_shortcodeid + "] Insert you content here [/" + tz_shortcodeid + "]";
}
}

if(window.tinyMCE) {
		//TODO: For QTranslate we should use here 'qtrans_textarea_content' instead 'content'
		window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, tagtext);
		//Peforms a clean up of the current editor HTML. 
		//tinyMCEPopup.editor.execCommand('mceCleanup');
		//Repaints the editor. Sometimes the browser has graphic glitches. 
		tinyMCEPopup.editor.execCommand('mceRepaint');
		tinyMCEPopup.close();
	}
	return;
}