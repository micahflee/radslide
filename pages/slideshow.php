<?php

// slideshow page
function radslide_page_slideshow() {
  global $wpdb;
  
	radslide_helper_include_jquery();
	radslide_helper_include_bespin();
  ?>
	<script type="text/javascript">
		// if there's an ajax error, alert it
    function radslide_ajax_error(response) {
      alert("radSLIDE ajax error:\n\n"+JSON.stringify(response));
		}
		
		// add a new slideshow
		function radslide_slideshows_add() {
      $("#radslide_loading").show();
			$.ajax({
				url: "<?php echo(get_option('siteurl')); ?>/wp-admin/admin-ajax.php",
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
				url: "<?php echo(get_option('siteurl')); ?>/wp-admin/admin-ajax.php",
				data: {
					action: 'radslide_slideshows_settings',
					cookie: encodeURIComponent(document.cookie),
					radslide_slideshow_id: id
				},
				type: "POST",
				success: function(data) {
					$("#radslide").html(data);
				},
				error: radslide_ajax_error
			});
		}

		// slideshow delete
		function radslide_slideshows_delete(id) {
      $("#radslide_loading").show();
			$.ajax({
				url: "<?php echo(get_option('siteurl')); ?>/wp-admin/admin-ajax.php",
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
        url: "<?php echo(get_option('siteurl')); ?>/wp-admin/admin-ajax.php",
        data: {
          action: 'radslide_slideshows_populate',
          cookie: encodeURIComponent(document.cookie)
        },
        type: "POST",
				success: function(data) {
					// have slideshow index, display it
					$('#radslide').html(data);

					// make the template textarea use bespin
					var node = document.getElementById("radslide_add-template");
					bespin.useBespin(node, { "stealFocus": true, "syntax": "html" });

					// intercept button clicks
          $(".button-primary").click(function(){
						var id = $(this).attr('id');

						// add slideshow button
						if(id == 'radslide_add') {
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

		// add a slide to a slideshow
		function radslide_slides_add() {
      $.ajax({
				url: "<?php echo(get_option('siteurl')); ?>/wp-admin/admin-ajax.php",
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
				url: "<?php echo(get_option('siteurl')); ?>/wp-admin/admin-ajax.php",
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
				url: "<?php echo(get_option('siteurl')); ?>/wp-admin/admin-ajax.php",
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
        url: "<?php echo(get_option('siteurl')); ?>/wp-admin/admin-ajax.php",
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

    $(document).ready(function() {
      // populate the table of slideshows
      radslide_slideshows_populate();
    });
  </script>

  <div id="radslide">Loading...</div>
  <?php
}


?>
