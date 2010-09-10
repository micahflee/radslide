// add a slide to a slideshow
function radslide_slides_add() {
	$.ajax({
		url: siteurl+"/wp-admin/admin-ajax.php",
		data: {
			action: 'radslide_slides_add',
			cookie: encodeURIComponent(document.cookie),
			radslide_slideshow_id: $("#radslide_slideshow_id").val(),
			radslide_title: $("#radslide_add-title").val(),
			radslide_description: $("#radslide_add-description").val(),
			radslide_image_url: $("#radslide_add-image_url").val(),
			radslide_link_url: $("#radslide_add-link_url").val(),
			radslide_sort: $("#radslide_add-sort").val()
		},
		type: "POST",
		success: function(){
			radslide_slides_populate($("#radslide_slideshow_id").val());
		},
		error: radslide_ajax_error
	});
}

// update all the slides
function radslide_slides_update() {
	// radslide_data is an array of all the rows
	var radslide_data = new Array();
	$(".radslide_row").each(function(index){
		parts = $(this).attr('id').split('-');
		var row_id = parts[1];

		// row is a js object of a single row
		var row = {};
		row.id = row_id;
		$("#radslide_row-"+row_id+" .radslide_field").each(function(index){
			parts = $(this).attr('id').split('-');
			var field = parts[1];
			var value = $(this).val();
			eval("row."+field+"=value;");
		});
		radslide_data.push(row);
	});
	$.ajax({
		url: siteurl+"/wp-admin/admin-ajax.php",
		data: {
			action: 'radslide_slides_update',
			cookie: encodeURIComponent(document.cookie),
			radslide_slideshow_id: $("#radslide_slideshow_id").val(),
			radslide_data: radslide_data
		},
		type: "POST",
		success: function(){
			radslide_slides_populate($("#radslide_slideshow_id").val());
		},
		error: radslide_ajax_error
	});
}

// delete a slide
function radslide_slides_delete(id) {
	$.ajax({
		url: siteurl+"/wp-admin/admin-ajax.php",
		data: {
			action: 'radslide_slides_delete',
			cookie: encodeURIComponent(document.cookie),
			radslide_slideshow_id: $("#radslide_slideshow_id").val(),
			radslide_id: id
		},
		type: "POST",
		success: function(){
			radslide_slides_populate($("#radslide_slideshow_id").val());
		},
		error: radslide_ajax_error
	});
}

// populate slides for a slideshow
function radslide_slides_populate(id) {
	$.ajax({
		url: siteurl+"/wp-admin/admin-ajax.php",
		data: {
			action: 'radslide_slides_populate',
			cookie: encodeURIComponent(document.cookie),
			radslide_slideshow_id: id
		},
		type: "POST",
		success: function(data) {
			$("#radslide").html(data);

			// in the add new slide row, make input text disappear on focus
			$("#radslide_add_row input[type='text']").click(function(){
				var id = $(this).attr('id');
				if(id != 'radslide_add' && id != 'radslide_update')
					$(this).val('');
			});
 
			// intercept button clicks
			$(".button-primary").click(function(){
				var id = $(this).attr('id');

				// back to slideshow button
				if(id == 'radslide_back_to_slideshows') {
					$('#radslide_back_to_slideshows_loading').show();
					radslide_slideshows_populate();
				}
				// add new slide
				else if(id == 'radslide_add') {
					$("#radslide_loading").show();
					radslide_slides_add();
				}
				// update all rows
				else if(id == 'radslide_update') {
					$("#radslide_loading").show();
					radslide_slides_update();
				}
				// delete a row
				else {
					var parts = id.split('-');
					id = parts[0];
					var row_id = parts[1];
					
					if(parts[0] == 'radslide_delete') {
						$("#radslide_loading-"+row_id).show();
						radslide_slides_delete(row_id);
					}
				}
			});
		},
		error: radslide_ajax_error
	});
}

