jQuery(document).ready(function()
{
	jQuery('#addimageinfo').click(function()
	{
		var str = '<div class="portfolio_admin_subbox">'+
			'<p><label for="'+profile_images_key+'_'+profile_images_count+'"><strong>'+profile_images_label+'</strong></label></p>'+
			'<p><input type="file" id="'+profile_images_key+'_'+profile_images_count+'" name="'+profile_images_key+'_'+profile_images_count+'"></p>'+
			'<p><em></em></p>'+
			'<p><label for="'+profile_images_key+'_title_'+profile_images_count+'"><strong>'+profile_images_title_label+'</strong></label></p>'+
			'<p><input style="width: 80%;" type="text" name="'+profile_images_key+'_title_'+profile_images_count+'" id="'+profile_images_key+'_title_'+profile_images_count+'" value="" /></p>'+
			'<p><em>'+profile_images_title_text+'</em></p>'+
			'<p><label for="'+profile_images_key+'_description_'+profile_images_count+'"><strong>'+profile_images_descr_label+'</strong></label></p>'+
			'<p><input style="width: 80%;" type="text" name="'+profile_images_key+'_description_'+profile_images_count+'" id="'+profile_images_key+'_description_'+profile_images_count+'" value="" /></p>'+
			'<p><em>'+profile_images_descr_text+'</em></p>'+
			'</div>';
		jQuery('#addimageinfo').before(str);
		//profile_images_count++;
	});	
});