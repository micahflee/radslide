// toggle the add slideshow form
function radslide_add_toggle_setup() {
	jQuery('#radslide_add_form').hide();
	jQuery('#radslide_add_form').css('visibility', 'visible');
	jQuery('#radslide_add_toggle').toggle(function(){
		jQuery('#radslide_add_form').show();
	}, function(){
		jQuery('#radslide_add_form').hide();
	});
};

// add a new slideshow
function radslide_slideshows_add() {
	jQuery.ajax({
		url: siteurl+"/wp-admin/admin-ajax.php",
		data: {
			action: 'radslide_slideshows_add',
			cookie: encodeURIComponent(document.cookie),
			radslide_name: jQuery("#radslide_add-name").val(),
			radslide_template: jQuery("#radslide_add-template").val(),
			radslide_cycle_options: jQuery("#radslide_add-cycle_options").val()
		},
		type: "POST",
		success: radslide_slideshows_populate,
		error: radslide_ajax_error
	});
}

// slideshow settings
function radslide_slideshows_settings(id) {
	jQuery("#radslide_loading").show();
	jQuery.ajax({
		url: siteurl+"/wp-admin/admin-ajax.php",
		data: {
			action: 'radslide_slideshows_settings',
			cookie: encodeURIComponent(document.cookie),
			radslide_slideshow_id: id
		},
		type: "POST",
		success: function(data) {
			jQuery("#radslide").html(data);
			
			// make the template textarea use bespin
			var bespin_editor;
			bespin.useBespin(document.getElementById("radslide-template"), {
				"syntax": "html"
			}).then(function(env){
				bespin_editor = env.editor; 
			});

			// intercept button clicks
			jQuery(".button-primary").click(function(){
				var id = jQuery(this).attr('id');

				// back to slideshow button
				if(id == 'radslide_back_to_slideshows') {
					jQuery('#radslide_back_to_slideshows_loading').show();
					radslide_slideshows_populate();
				}
				// edit slideshow button
				else if(id == 'radslide_edit') {
					jQuery('#radslide-template').html(bespin_editor.value);
					jQuery('#radslide_loading').show();
					radslide_slideshows_settings_edit();
				}
			});
		},
		error: radslide_ajax_error
	});
}

// actually update the slideshow settings
function radslide_slideshows_settings_edit() {
	jQuery.ajax({
		url: siteurl+"/wp-admin/admin-ajax.php",
		data: {
			action: 'radslide_slideshows_settings_edit',
			cookie: encodeURIComponent(document.cookie),
			radslide_slideshow_id: jQuery("#radslide_slideshow_id").val(),
			radslide_name: jQuery("#radslide-name").val(),
			radslide_template: jQuery("#radslide-template").val(),
			radslide_cycle_options: jQuery("#radslide-cycle_options").val()
		},
		type: "POST",
		success: radslide_slideshows_populate,
		error: radslide_ajax_error
	});
}

// slideshow delete
function radslide_slideshows_delete(id) {
	jQuery("#radslide_loading").show();
	jQuery.ajax({
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
	jQuery.ajax({
		url: siteurl+"/wp-admin/admin-ajax.php",
		data: {
			action: 'radslide_slideshows_populate',
			cookie: encodeURIComponent(document.cookie)
		},
		type: "POST",
		success: function(data) {
			// have slideshow index, display it
			jQuery('#radslide').html(data);

			// make the template textarea use bespin
			var bespin_editor;
			bespin.useBespin(document.getElementById("radslide_add-template"), {
				"syntax": "html"
			}).then(function(env){
				bespin_editor = env.editor; 
				// set up the add form's toggler
				radslide_add_toggle_setup();
			});

			// intercept button clicks
			jQuery(".button-primary").click(function(){
				var id = jQuery(this).attr('id');

				// add slideshow button
				if(id == 'radslide_add') {
					jQuery('#radslide_add-template').html(bespin_editor.value);
					jQuery("#radslide_loading").show();
					radslide_slideshows_add();
				}
				// either manage, settings, or delete
				else {
					var parts = id.split('-');
					id = parts[0];
					var row_id = parts[1];
					jQuery("#radslide_loading-"+row_id).show();

					if(id == 'radslide_manage') { radslide_slides_populate(row_id); }
					else if(id == 'radslide_settings') { radslide_slideshows_settings(row_id); }
					else if(id == 'radslide_delete') { radslide_slideshows_delete(row_id); }
				}
			});
		},
		error: radslide_ajax_error
	});
}


