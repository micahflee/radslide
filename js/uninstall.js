$(function(){
	$('#radslide_uninstall').click(function(){
		$('#radslide_loading').show();
		$.ajax({
			url: siteurl+"/wp-admin/admin-ajax.php",
			data: {
				action: 'radslide_uninstall',
				cookie: encodeURIComponent(document.cookie)
			},
			type: "POST",
			success: function(data){
				$('#radslide_uninstall_container').html(data);
			}
		});
	});
});
