<?php

function radslide_helper_ajax_loader($id) {
  $image_url = get_option('siteurl').'/wp-content/plugins/radslide/images/ajax-loader.gif';
  echo '<img src="'.$image_url.'" id="'.$id.'" style="display:none" />';
}

function radslide_helper_db_slideshow() {
	global $wpdb;
  return $wpdb->prefix.'radslide_slideshow';
}

function radslide_helper_db_slide() {
	global $wpdb;
  return $wpdb->prefix.'radslide_slide';
}

// add jquery to head, if needed
function radslide_head() {
	global $wpdb;
	?><script type="text/javascript">jQuery(function(){<?php
	$table_name = radslide_helper_db_slideshow();
	$slideshow_rows = $wpdb->get_results("SELECT * FROM $table_name");
	foreach($slideshow_rows as $slideshow_row) {
		?>jQuery("#radslide-<?php echo($slideshow_row->id) ?>").cycle(<?php echo(stripslashes($slideshow_row->cycle_options)); ?>); <?php
	}
	?>});</script>	<?php
}

// media api scripts and styles
function radslide_media_api_scripts() {
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_register_script('my-upload', WP_PLUGIN_URL.'/radslide/image_selector.js', array('jquery','media-upload','thickbox'));
	wp_enqueue_script('my-upload');
}
function radslide_media_api_styles() {
	wp_enqueue_style('thickbox');
}

function radslide_rd_credit() {
	?><p style="margin-top:100px">&copy; radSLIDE is developed and maintained by <a href="http://github.com/micahflee/" target="_blank">Micah Lee</a> at <a href="http://radicaldesigns.org/" target="_blank">Radical Designs</a> and is happily released under the <a href="http://www.gnu.org/licenses/gpl-2.0.html" target="_blank">GLP2 License</a>.</p><?php
}

?>
