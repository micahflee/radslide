// add a slide to a slideshow
function radslide_slides_add() {
	jQuery.ajax({
		url: siteurl+"/wp-admin/admin-ajax.php",
		data: {
			action: 'radslide_slides_add',
			cookie: encodeURIComponent(document.cookie),
			radslide_slideshow_id: jQuery("#radslide_slideshow_id").val(),
			radslide_title: jQuery("#radslide_add-title").val(),
			radslide_description: jQuery("#radslide_add-description").val(),
			radslide_image_url: jQuery("#radslide_add-image_url").val(),
			radslide_link_url: jQuery("#radslide_add-link_url").val(),
			radslide_sort: jQuery("#radslide_add-sort").val()
		},
		type: "POST",
		success: function(){
			radslide_slides_populate(jQuery("#radslide_slideshow_id").val());
		},
		error: radslide_ajax_error
	});
}

// update all the slides
function radslide_slides_update() {
	// radslide_data is an array of all the rows
	var radslide_data = new Array();
	jQuery(".radslide_row").each(function(index){
		parts = jQuery(this).attr('id').split('-');
		var row_id = parts[1];

		// row is a js object of a single row
		var row = {};
		row.id = row_id;
		jQuery("#radslide_row-"+row_id+" .radslide_field").each(function(index){
			parts = jQuery(this).attr('id').split('-');
			var field = parts[1];
			var value = jQuery(this).val();
			eval("row."+field+"=value;");
		});
		radslide_data.push(row);
	});
	jQuery.ajax({
		url: siteurl+"/wp-admin/admin-ajax.php",
		data: {
			action: 'radslide_slides_update',
			cookie: encodeURIComponent(document.cookie),
			radslide_slideshow_id: jQuery("#radslide_slideshow_id").val(),
			radslide_data: radslide_data
		},
		type: "POST",
		success: function(){
			radslide_slides_populate(jQuery("#radslide_slideshow_id").val());
		},
		error: radslide_ajax_error
	});
}

// delete a slide
function radslide_slides_delete(id) {
	jQuery.ajax({
		url: siteurl+"/wp-admin/admin-ajax.php",
		data: {
			action: 'radslide_slides_delete',
			cookie: encodeURIComponent(document.cookie),
			radslide_slideshow_id: jQuery("#radslide_slideshow_id").val(),
			radslide_id: id
		},
		type: "POST",
		success: function(){
			radslide_slides_populate(jQuery("#radslide_slideshow_id").val());
		},
		error: radslide_ajax_error
	});
}

// populate slides for a slideshow
function radslide_slides_populate(id) {
	jQuery.ajax({
		url: siteurl+"/wp-admin/admin-ajax.php",
		data: {
			action: 'radslide_slides_populate',
			cookie: encodeURIComponent(document.cookie),
			radslide_slideshow_id: id
		},
		type: "POST",
		success: function(data) {
			jQuery("#radslide").html(data);

			// in the add new slide row, make input text disappear on focus
			jQuery("#radslide_add_row input[type='text']").click(function(){
				var id = jQuery(this).attr('id');
				if(id != 'radslide_add' && id != 'radslide_update')
					jQuery(this).val('');
			});
 
			// intercept button clicks
			jQuery(".button-primary").click(function(){
				var id = jQuery(this).attr('id');

				// back to slideshow button
				if(id == 'radslide_back_to_slideshows') {
					jQuery('#radslide_back_to_slideshows_loading').show();
					radslide_slideshows_populate();
				}
				// add new slide
				else if(id == 'radslide_add') {
					jQuery("#radslide_loading").show();
					radslide_slides_add();
				}
				// update all rows
				else if(id == 'radslide_update') {
					jQuery("#radslide_loading").show();
					radslide_slides_update();
				}
				// delete a row
				else {
					var parts = id.split('-');
					id = parts[0];
					var row_id = parts[1];
					
					if(parts[0] == 'radslide_delete') {
						jQuery("#radslide_loading-"+row_id).show();
						radslide_slides_delete(row_id);
					}
				}
			});
		},
		error: radslide_ajax_error
	});
}

