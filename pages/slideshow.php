<?php

// slideshow page
function radslide_page_slideshow() {
	?>
	<script type="text/javascript">
		// if there's an ajax error, alert it
    function radslide_ajax_error(response) {
      alert("radSLIDE ajax error:\n\n"+JSON.stringify(response));
		}

		// define the site url for javascript
		siteurl = '<?php echo(get_option('siteurl')); ?>';
	</script>
	<script src="<?php echo(get_option('siteurl')); ?>/wp-content/plugins/radslide/js/slideshows.js" type="text/javascript"></script>
	<script src="<?php echo(get_option('siteurl')); ?>/wp-content/plugins/radslide/js/slides.js" type="text/javascript"></script>
	<script src="<?php echo(get_option('siteurl')); ?>/wp-content/plugins/radslide/js/image_picker.js" type="text/javascript"></script>
	<script type="text/javascript">
    jQuery(function() {
      // populate the table of slideshows
      radslide_slideshows_populate();
    });
  </script>

  <div id="radslide">Loading...</div>
  <?php
	radslide_rd_credit();
}


?>
