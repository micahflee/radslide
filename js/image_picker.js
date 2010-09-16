function  radslide_setup_image_pickers() {
	var row_id;

	jQuery('.radslide_image_picker').click(function() {
		row_id = this.id.split('-')[1];
		tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
		return false;
	});

	window.send_to_editor = function(html) {
		var image_id, image_url_id;
		if(row_id == 'add') {
			image_id = '#radslide_add-image';
			image_url_id = '#radslide_add-image_url';
		} else {
			image_id = '#radslide_update-image-'+row_id;
			image_url_id = '#radslide_update-image_url-'+row_id;
		}

		imgurl = jQuery('img',html).attr('src');
		jQuery(image_id).attr('src', imgurl);
		jQuery(image_url_id).val(imgurl);
		tb_remove();
	}
}

