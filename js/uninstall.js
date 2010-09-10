jQuery(function(){
	jQuery('#radslide_uninstall').click(function(){
		jQuery('#radslide_loading').show();
		jQuery.ajax({
			url: siteurl+"/wp-admin/admin-ajax.php",
			data: {
				action: 'radslide_uninstall',
				cookie: encodeURIComponent(document.cookie)
			},
			type: "POST",
			success: function(data){
				jQuery('#radslide_uninstall_container').html(data);
			}
		});
	});
});
