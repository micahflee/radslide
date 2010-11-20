<?php

// create menu
function radslide_create_menu() {
	$menu_slug = 'radslide_slideshows';
  add_menu_page('radSLIDE Slideshows', 'radSLIDE', 'administrator', $menu_slug, 'radslide_page_slideshow');
  add_submenu_page($menu_slug, 'radSLIDE Slideshows', 'Slideshows', 'administrator', $menu_slug, 'radslide_page_slideshow');
  add_submenu_page($menu_slug, 'radSLIDE Help', 'Help', 'administrator', 'radslide_help', 'radslide_page_help');
  add_submenu_page($menu_slug, 'radSLIDE Uninstall', 'Uninstall', 'administrator', 'radslide_uninstall', 'radslide_page_uninstall');
}

// menu register settings
function radslide_register_settings() {
  register_setting('radslide_settings','radslide_db_version');
}

// enqueue stuff
function radslide_admin_scripts() {
	wp_enqueue_script('jquery');
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_register_script('image-picker', get_option('siteurl').'/wp-content/plugins/radslide/js/image_picker.js"', array('jquery','media-upload','thickbox'));
	wp_enqueue_script('image-picker');
	wp_register_script('codemirror', get_option('siteurl').'/wp-content/plugins/radslide/vendor/codemirror/js/codemirror.js');
	wp_enqueue_script('codemirror');
}
function radslide_admin_styles() {
	wp_enqueue_style('thickbox');
}
function radslide_scripts() {
	wp_enqueue_script('jquery');
	wp_register_script('jquery.cycle', get_option('siteurl').'/wp-content/plugins/radslide/vendor/jquery.cycle.all.min.js"', array('jquery'));
	wp_enqueue_script('jquery.cycle');
}

// hooks
add_action('wp_head', 'radslide_head');
add_action('wp_print_scripts', 'radslide_scripts');

if(is_admin()) {
  add_action('admin_menu', 'radslide_create_menu');
	add_action('admin_init', 'radslide_register_settings');
	
	// admin enqueues
	if(isset($_GET['page']) && $_GET['page'] == 'radslide_slideshows') {
		add_action('admin_print_scripts', 'radslide_admin_scripts');
		add_action('admin_print_styles', 'radslide_admin_styles');
	}
}

?>
