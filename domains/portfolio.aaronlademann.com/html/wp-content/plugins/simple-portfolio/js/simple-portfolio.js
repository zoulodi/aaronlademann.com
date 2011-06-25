

/**
* Add Youtube ID
*/
function add_youtube() {
	jQuery("div#portfolio-media-add").slideUp('fast', function(){
		jQuery("div#portfolio-media-add").html( get_add_html('Youtube ID', 'Youtube','textinput') );
		jQuery("div#portfolio-media-add").slideDown('fast', function() {
			jQuery(this).find("#add-field").focus();
		});
	});
}

/**
* Add Html Snippet
*/
function add_snippet() {
	jQuery("div#portfolio-media-add").slideUp('fast', function(){
		jQuery("div#portfolio-media-add").html( get_add_html('Snippet', 'Snippet','textarea') );
		jQuery("div#portfolio-media-add").slideDown('fast', function() {
			jQuery(this).find("#add-field").focus();
		});
	});
}

/**
* Add Text Paragraph
*/
function add_text() {
	jQuery("div#portfolio-media-add").slideUp('fast', function(){
		jQuery("div#portfolio-media-add").html( get_add_html('Text', 'Text', 'textarea') );
		jQuery("div#portfolio-media-add").slideDown('fast', function() {
			jQuery(this).find("#add-field").focus();
		});
	});
}

function get_add_html(add_title, title, type) {
	html =  "<div class=\"single-media-item\">";
	html += "	<div class=\"title\">" + add_title + "</div>";
	html += "	<div class=\"container\">";
	
	html += type == 'textarea' ? "<textarea id=\"add-field\"></textarea>": "<input type=\"text\" id=\"add-field\" size=\"20\" maxlength=\"20\" value=\"\"/>";
	
	html += "		<input type=\"button\" class=\"button tagadd\" value=\"Add\" onclick=\"add_li('" + title + "');\" />";
	html += "		<input type=\"button\" class=\"button tagadd\" value=\"Cancel\" onclick=\"cancel_media_add();\" />";
	html += "	</div>";
	html += "</div>";
	
	return html;
}

function delete_media( btn_clicked ) {
	var item_to_delete = jQuery(btn_clicked).parents('.single-media-item');
	item_to_delete.slideUp(200, function(){
		var li = item_to_delete.parents('li');
		li.detach();
		reorder_li_ids();
	});
}

function add_li( type, value ) {
	
	value = (value == null) ? jQuery('div#portfolio-media-add #add-field').attr('value') : value;
	if (jQuery.trim( value ) == '') return;
	
	var argv = add_li.arguments;
	var argc = argv.length;
	
	jQuery("div#portfolio-media-add").slideUp('fast', function() {
		jQuery(this).hide();
		container = jQuery("div#portfolio-media-items ul");
		
		jQuery.ajax({
			url : media_html_path + type.toLowerCase() + '.html',
			success : function (data) {
				data = data.replace(/{title}/g, type);
				data = data.replace(/{index}/g, 0);
				
				if (argc > 2) {
					for (var i = 1; i < argc; i++) {
						var s = "{value\\[" + (i-1) + "\\]}";
						data = data.replace(new RegExp(s, "g"), argv[i]);
					}
				}else{
					data = data.replace(/{value}/g, html_entities(value));
				}
				
				container.prepend("<li>" + data + "</li>");
				first = container.find('*').first();
				first.hide();
				first.slideDown('normal');
				first.find('textarea').attr('value', value);
				
				first.find('.html-in-snippet').each(function(index, val){
					jQuery(val).css('display','none');
					jQuery(val).prev().attr('value','Show');
				});
				
				reorder_li_ids();
			}
		});
	});
}

/**
* Cancel add field
*/
function cancel_media_add() {
	jQuery("div#portfolio-media-add").slideUp('fast', function() {
		jQuery(this).hide();
	});
}

/**
* Toggle a single snippet item (show/hide)
* @param Div container
*/
function toggle_snippet_html( container ) {
	snippet_html = jQuery(".single-media-item #" + jQuery(container).attr('snippet_id'));
	
	if (snippet_html.attr('state') == 'hidden') {
		
		snippet_html.attr('state', 'visible');
		snippet_html.slideDown('fast');
		jQuery(container).attr('value', 'Hide');
	}else{
		snippet_html.css('display', 'block');
		snippet_html.attr('state', 'hidden');
		snippet_html.slideUp('fast');
		jQuery(container).attr('value', 'Show');
	}
}

/**
* Convert all applicable characters to HTML entities
* @param String str
* @return String
*/
function html_entities(s) {
	encoded = jQuery('<div/>').text(s).html();
	encoded = encoded.replace(/\n/g, "<br />");
	encoded = encoded.replace(/\t/g, "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
	
	return encoded;
}

/**
* On document load.
*/
jQuery(document).ready(function() {
	// check browser version
	if (!jQuery.browser.msie) {
		if (jQuery('div#portfolio-media-items ul').length)
			init_media_sortable();
	}
	
	//init_debugger();
});

function init_debugger() {
	var debugtool = "<div id=\"debugger\" style=\"background-color:#fffdc4; display:block; width:100%; height:200px; position:absolute; left:0;z-index:100; overflow:scroll;\"><pre>Debugger initialized.. waiting for input</pre></div>";
	jQuery("body").prepend(debugtool);
}
/**
* Close all open media snippets
*/
function close_all_media_snippets() {
	jQuery('.single-media-item .html-in-snippet').each(function(index, val){
		jQuery(val).css('display','none');
		jQuery(val).prev().attr('value','Show');
	});
}

/**
* Make media items sortable.
* On update apply ordered indexes on each item
*/
function init_media_sortable() {
	jQuery('div#portfolio-media-items ul').sortable({
		containment: 'parent',
		tolerance: 'pointer',
		handle: '.title',
		opacity: 0.6,
		stop: function(e, ui) {
			reorder_li_ids();
		}
	});
}

/**
* Reorder all id's in media items
*/
function reorder_li_ids() {
	jQuery('div#portfolio-media-items ul li').each(function(i, id) {
		// loop through each hidden element
		jQuery(id).find(".item-data input[type='hidden']").each(function(i2, id2) {
			iname = jQuery(id2).attr('name').split('_');
			iname.pop();
			iname = iname.join('_') + '_' + (i+1);
			jQuery(id2).attr('name', iname);
			jQuery(id2).attr('id', iname);
		});
		
		// loop through snippet html
		jQuery(id).find(".html-in-snippet").each(function(i2, id2) {
			iname = jQuery(id2).attr('id').split('_');
			iname.pop();
			iname = iname.join('_') + '_' + (i+1);
			jQuery(id2).attr('id', iname);
		});
		
		// loop through textarea
		jQuery(id).find(".item-data textarea").each(function(i2, id2) {
			iname = jQuery(id2).attr('name').split('_');
			iname.pop();
			iname = iname.join('_') + '_' + (i+1);
			jQuery(id2).attr('name', iname);
		});
		
		// loop through 'show/hide button' 
		jQuery(this).find("#show_hide_button").each(function(i2, id2) {
			iname = jQuery(id2).attr('snippet_id').split('_');
			iname.pop();
			iname = iname.join('_') + '_' + (i+1);
			jQuery(id2).attr('snippet_id', iname);
		});
	});
}

/**
* Will be triggered from media library when insert into portfolio is clicked..
* @param images array with image urls
*/
function send_media_to_metabox( images ) {
	tb_remove();
	
	jQuery.each( images, function( index, value ){
		jQuery.ajax({
			url : media_html_path + 'media_attachment.php?id=' + value,
			success : function (data) {
				add_li('Image', data, value);
			}
		});
	});
}
