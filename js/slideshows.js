// add a new slideshow
function radslide_slideshows_add() {
	$.ajax({
		url: siteurl+"/wp-admin/admin-ajax.php",
		data: {
			action: 'radslide_slideshows_add',
			cookie: encodeURIComponent(document.cookie),
			radslide_name: $("#radslide_add-name").val(),
			radslide_template: $("#radslide_add-template").val(),
			radslide_cycle_options: $("#radslide_add-cycle_options").val()
		},
		type: "POST",
		success: radslide_slideshows_populate,
		error: radslide_ajax_error
	});
}

// slideshow settings
function radslide_slideshows_settings(id) {
	$("#radslide_loading").show();
	$.ajax({
		url: siteurl+"/wp-admin/admin-ajax.php",
		data: {
			action: 'radslide_slideshows_settings',
			cookie: encodeURIComponent(document.cookie),
			radslide_slideshow_id: id
		},
		type: "POST",
		success: function(data) {
			$("#radslide").html(data);
			
			// make the template textarea use bespin
			var bespin_editor;
			bespin.useBespin(document.getElementById("radslide-template"), {
				"syntax": "html"
			}).then(function(env){
				bespin_editor = env.editor; 
			});

			// intercept button clicks
			$(".button-primary").click(function(){
				var id = $(this).attr('id');

				// back to slideshow button
				if(id == 'radslide_back_to_slideshows') {
					$('#radslide_back_to_slideshows_loading').show();
					radslide_slideshows_populate();
				}
				// edit slideshow button
				else if(id == 'radslide_edit') {
					$('#radslide-template').html(bespin_editor.value);
					$('#radslide_loading').show();
					radslide_slideshows_settings_edit();
				}
			});
		},
		error: radslide_ajax_error
	});
}

// actually update the slideshow settings
function radslide_slideshows_settings_edit() {
	$.ajax({
		url: siteurl+"/wp-admin/admin-ajax.php",
		data: {
			action: 'radslide_slideshows_settings_edit',
			cookie: encodeURIComponent(document.cookie),
			radslide_slideshow_id: $("#radslide_slideshow_id").val(),
			radslide_name: $("#radslide-name").val(),
			radslide_template: $("#radslide-template").val(),
			radslide_cycle_options: $("#radslide-cycle_options").val()
		},
		type: "POST",
		success: radslide_slideshows_populate,
		error: radslide_ajax_error
	});
}

// slideshow delete
function radslide_slideshows_delete(id) {
	$("#radslide_loading").show();
	$.ajax({
		url: siteurl+"/wp-admin/admin-ajax.php",
		data: {
			action: 'radslide_slideshows_delete',
			cookie: encodeURIComponent(document.cookie),
			radslide_slideshow_id: id
		},
		type: "POST",
		success: radslide_slideshows_populate,
		error: radslide_ajax_error
	});
}

// fill in the page with a list of slideshows
function radslide_slideshows_populate() {
	$.ajax({
		url: siteurl+"/wp-admin/admin-ajax.php",
		data: {
			action: 'radslide_slideshows_populate',
			cookie: encodeURIComponent(document.cookie)
		},
		type: "POST",
		success: function(data) {
			// have slideshow index, display it
			$('#radslide').html(data);

			// make the template textarea use bespin
			var bespin_editor;
			bespin.useBespin(document.getElementById("radslide_add-template"), {
				"syntax": "html"
			}).then(function(env){
				bespin_editor = env.editor; 
			});

			// intercept button clicks
			$(".button-primary").click(function(){
				var id = $(this).attr('id');

				// add slideshow button
				if(id == 'radslide_add') {
					$('#radslide_add-template').html(bespin_editor.value);
					$("#radslide_loading").show();
					radslide_slideshows_add();
				}
				// either manage, settings, or delete
				else {
					var parts = id.split('-');
					id = parts[0];
					var row_id = parts[1];
					$("#radslide_loading-"+row_id).show();

					if(id == 'radslide_manage') { radslide_slides_populate(row_id); }
					else if(id == 'radslide_settings') { radslide_slideshows_settings(row_id); }
					else if(id == 'radslide_delete') { radslide_slideshows_delete(row_id); }
				}
			});
		},
		error: radslide_ajax_error
	});
}


