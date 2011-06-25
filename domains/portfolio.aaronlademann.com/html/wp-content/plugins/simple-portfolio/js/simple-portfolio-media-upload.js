/**
* On document load.
*/
jQuery(document).ready(function() {
	jQuery('.media-title').before('<h2>Add Images from your computer</h2>');
	jQuery('.media-title').before('Select images you want to add to your project. When uploading is fisnished, you can insert them into you project media panel.');
	jQuery("form#file-form script[type='text/javascript']").each(function(index, val){
		// find the js script which supply the succeed callback
		if ((/var swfu;/).test(jQuery(val).text())) {
			new_script = jQuery(val).text();
			new_script = new_script.replace('uploadComplete', 'uploadCompletePortfolio');
			new_script = new_script.replace('file_types: \"*.*\"', 'file_types: \"*.jpg;*.jpeg;*.png;*.gif;*.JPG;*.JPEG;*.PNG;*.GIF;\"');
			jQuery(val).after("<script type=\"text/javascript\">" + new_script + "</script>");
		}
	});
});

function uploadCompletePortfolio(b) {
	if (swfu.getStats().files_queued == 0) {
		jQuery('.savebutton input[type="submit"]').after("<input type=\"button\" value=\"Add to Project\" class=\"button tagadd\" id=\"add_to_portfolio\" onclick=\"add_items_to_portfolio();\"/>");
		jQuery('#add_to_portfolio').hide();
		jQuery('#add_to_portfolio').fadeIn('slow');
	}
}

function add_items_to_portfolio() {
	// construct images array..
	var images = [];
	jQuery('.media-item input[type=hidden][id*=type-of-]').each(function(index, value){
		images.push(/[\d]+$/.exec(jQuery(value).attr('id')));
	});

	var win = window.dialogArguments || opener || parent || top;
	win.send_media_to_metabox(images);
}
