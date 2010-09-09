<?php

// create menu
function radslide_create_menu() {
	$menu_slug = 'radslide_slideshows';
  add_menu_page('radSLIDE Slideshows', 'radSLIDE', 'administrator', $menu_slug, 'radslide_page_slideshow');
  add_submenu_page($menu_slug, 'radSLIDE Slideshows', 'Slideshows', 'administrator', $menu_slug, 'radslide_page_slideshow');
  add_submenu_page($menu_slug, 'radSLIDE Settings', 'Settings', 'administrator', 'radslide_settings', 'radslide_page_settings');
  add_submenu_page($menu_slug, 'radSLIDE Uninstall', 'Uninstall', 'administrator', 'radslide_uninstall', 'radslide_page_uninstall');
}

// menu register settings
function radslide_register_settings() {
  register_setting('radslide_settings','radslide_inc_jquery');
  register_setting('radslide_settings','radslide_db_version');
}

// hooks
add_action('wp_head', 'radslide_head');
if(is_admin()) {
  add_action('admin_menu', 'radslide_create_menu');
	add_action('admin_init', 'radslide_register_settings');

	if(isset($_GET['page']) && $_GET['page'] == 'radslide_slideshow') {
		add_action('admin_print_scripts', 'radslide_media_api_scripts');
		add_action('admin_print_styles', 'radslide_media_api_styles');
	}

	/*// load jquery
  wp_deregister_script( 'jquery' );
	wp_register_script( 'jquery', get_option('siteurl').'/wp-content/plugins/radslide/vendor/jquery-1.4.2.min.js');

	// load bespin
  wp_deregister_script( 'bespin' );
	wp_register_script( 'bespin', get_option('siteurl').'/wp-content/plugins/radslide/vendor/bespin/BespinEmbedded.js');*/
}


?>
