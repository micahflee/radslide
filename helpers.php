<?php

function radslide_helper_include_jquery() {
  $jquery_url = get_option('siteurl').'/wp-content/plugins/radslide/vendor/jquery-1.4.2.min.js';
  echo '<script type="text/javascript" src="'.$jquery_url.'"></script>';
}

function radslide_helper_include_bespin() {
	$bespin_base = get_option('siteurl').'/wp-content/plugins/radslide/vendor/bespin';
	$css_url = get_option('siteurl').'/wp-content/plugins/radslide/vendor/bespin/BespinEmbedded.css';
	$js_url = get_option('siteurl').'/wp-content/plugins/radslide/vendor/bespin/BespinEmbedded.js';
	echo '<link id="bespin_base" href="'.$bespin_base.'">';
	echo '<link rel="stylesheet" type="text/css" href="'.$css_url.'">';
	echo '<script type="text/javascript" src="'.$js_url.'"></script>';
}

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
  if(get_option('radslide_enabled') == 'true') {
?>
<script type="text/javascript">
$(document).ready(function(){ $("#radslide").cycle(<?php echo(get_option('radslide_cycle_options')); ?>); });
</script>
<?php
  }
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

?>
