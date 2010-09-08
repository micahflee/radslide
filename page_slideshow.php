<?php

// slideshow page
function radslide_page_slideshow() {
  global $wpdb;
  
  radslide_helper_include_jquery();
  ?>
  <script type="text/javascript">
    function radslide_ajax_error(response) {
      alert("radSLIDE ajax error:\n\n"+JSON.stringify(response));
    }
    
    function radslide_populate() {
      $.ajax({
        url: "<?php echo(get_option('siteurl')); ?>/wp-admin/admin-ajax.php",
        data: {
          action: 'radslide_populate',
          cookie: encodeURIComponent(document.cookie)
        },
        type: "POST",
        success: function(data) {
          // populate the table
          $("#radslide_table").html(data);

          // in the add new slide row, make input text disappear on focus
          $("#radslide_add_row input[type='text']").click(function(){
            var id = $(this).attr('id');
            if(id != 'radslide_add' && id != 'radslide_update')
              $(this).val('');
          });
     
          // intercept button clicks
          $(".button-primary").click(function(){
            var id = $(this).attr('id');
            
            // add new row
            if(id == 'radslide_add') {
              $("#radslide_loading").show();
              $.ajax({
                url: "<?php echo(get_option('siteurl')); ?>/wp-admin/admin-ajax.php",
                data: {
                  action: 'radslide_add',
                  cookie: encodeURIComponent(document.cookie),
                  radslide_title: $("#radslide_add-title").val(),
                  radslide_description: $("#radslide_add-description").val(),
                  radslide_image_url: $("#radslide_add-image_url").val(),
                  radslide_link_url: $("#radslide_add-link_url").val(),
                  radslide_sort: $("#radslide_add-sort").val()
                },
                type: "POST",
                success: radslide_populate,
                error: radslide_ajax_error
              });
            }
            // update all rows
            else if(id == 'radslide_update') {
              $("#radslide_loading").show();

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
                  action: 'radslide_update',
                  cookie: encodeURIComponent(document.cookie),
                  radslide_data: radslide_data
                },
                type: "POST",
                success: radslide_populate,
                error: radslide_ajax_error
              });
            }
            // delete a row
            else {
              var parts = id.split('-');
              id = parts[0];
              var row_id = parts[1];
              
              if(parts[0] == 'radslide_delete') {
                $("#radslide_loading-"+row_id).show();
                 $.ajax({
                  url: "<?php echo(get_option('siteurl')); ?>/wp-admin/admin-ajax.php",
                  data: {
                    action: 'radslide_delete',
                    cookie: encodeURIComponent(document.cookie),
                    radslide_id: row_id
                  },
                  type: "POST",
                  success: radslide_populate,
                  error: radslide_ajax_error
                });
              }
            }
          });
        },
        error: radslide_ajax_error
      });
    }

    $(document).ready(function() {
      // populate the table of slides
      radslide_populate();
    });
  </script>

  <h2>Manage Slideshow</h2>
  <div id="radslide_table">Loading slides...</div>
	<pre id="test_data"></pre>
  <?php
}


?>
