<?php

// install radslide
function radslide_install() {
  global $wpdb;

	// set the initial options
  add_option('radslide_template',
'<a href="[[LINK_URL]]"><img src="[[IMAGE_URL]]" alt="[[TITLE]]" /></a>
<h3><a href="[[LINK_URL]]">[[TITLE]]</a></h3>
<div class="blurb">[[DESCRIPTION]]</div>');
  add_option('radslide_cycle_options',
'{
  delay:2000,
  speed:500
}');
  add_option('radslide_inc_jquery', 'true');
  add_option('radslide_enabled', 'true');
  add_option('radslide_disabled_html', '<div><h1>radSLIDE is disabled</h1></div>');

  // set up the radslide mysql table
  $table_name = radslide_helper_db_table_name();
  if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
    $sql = "CREATE TABLE ".$table_name." (
        id MEDIUMINT(9) NOT NULL AUTO_INCREMENT,
        image_url TEXT NOT NULL,
        link_url TEXT NOT NULL,
        title TEXT NOT NULL,
        description TEXT NOT NULL,
        sort TINYINT NOT NULL,
        UNIQUE KEY id (id)
      );";
    require_once(ABSPATH.'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    add_option('radslide_db_version', RADSLIDE_DB_VERSION);
  }
}

// uninstall
function radslide_uninstall() {
  /*global $wpdb;

  // delete the options
  delete_option('radslide_template');
  delete_option('radslide_cycle_options');
  delete_option('radslide_inc_jquery');
  delete_option('radslide_enabled');
  delete_option('radslide_disabled_html');

  // delete the table
  $table_name = radslide_helper_db_table_name();
	$wpdb->query("DROP TABLE IF EXISTS $table_name");*/
}

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
  register_setting('radslide_settings','radslide_template');
  register_setting('radslide_settings','radslide_cycle_options');
  register_setting('radslide_settings','radslide_inc_jquery');
  register_setting('radslide_settings','radslide_enabled');
  register_setting('radslide_settings','radslide_disabled_html');
}

// hooks
register_activation_hook(__FILE__, 'radslide_install');
register_deactivation_hook(__FILE__, 'radslide_uninstall');
add_action('wp_head', 'radslide_head');
if(is_admin()) {
  add_action('admin_menu', 'radslide_create_menu');
	add_action('admin_init', 'radslide_register_settings');

	if(isset($_GET['page']) && $_GET['page'] == 'radslide_slideshow') {
		add_action('admin_print_scripts', 'radslide_media_api_scripts');
		add_action('admin_print_styles', 'radslide_media_api_styles');
	}
}


?>
