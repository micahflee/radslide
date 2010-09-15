<?php
/*
 * Plugin Name: radSLIDE
 * Plugin URI: http://radicaldesigns.org/wordpress-plugin-radslide
 * Description: A javascript slideshow plugin that supports multiple slideshows and uses jQuery Cycle
 * Version: 1.0
 * Author: Micah Lee <micah@radicaldesigns.org>
 * Author URI: http://github.com/micahflee
 * License: GPL2
 * */

/*  Copyright 2010  RadicalDesigns

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


require_once('display.php');
require_once('helpers.php');

// ajax responders
require_once('ajax/slideshows.php');
require_once('ajax/slides.php');
require_once('ajax/uninstall.php');

// pages
require_once('pages/slideshow.php');
require_once('pages/help.php');
require_once('pages/uninstall.php');

require_once('setup.php');

// activate radslide
function radslide_activate() {
	radslide_install();
}

// deactive radslide
function radslide_deactivate() {
	// do nothing for now, we don't delete the data until it's manually uninstalled
}

// install radslide
define('RADSLIDE_DB_VERSION', 1);
function radslide_install() {
  global $wpdb;

	// set the initial options
  add_option('radslide_db_version', RADSLIDE_DB_VERSION);

	// add slideshow table
  $slideshow_name = radslide_helper_db_slideshow();
  if($wpdb->get_var("SHOW TABLES LIKE '$slideshow_name'") != $slideshow_name) {
    $sql = "CREATE TABLE ".$slideshow_name." (
				id MEDIUMINT(9) NOT NULL AUTO_INCREMENT,
        name TEXT NOT NULL,
        template TEXT NOT NULL,
        cycle_options TEXT NOT NULL,
        UNIQUE KEY id (id)
      );";
		$wpdb->query($sql);
	}

	// add slide table
	$slide_name = radslide_helper_db_slide();
  if($wpdb->get_var("SHOW TABLES LIKE '$slide_name'") != $slide_name) {
    $sql = "CREATE TABLE ".$slide_name." (
				id MEDIUMINT(9) NOT NULL AUTO_INCREMENT,
				slideshow_id MEDIUMINT(0) NOT NULL,
        image_url TEXT NOT NULL,
        link_url TEXT NOT NULL,
        title TEXT NOT NULL,
        description TEXT NOT NULL,
        sort TINYINT NOT NULL,
        UNIQUE KEY id (id)
      );";
		$wpdb->query($sql);
  }
}

// uninstall
function radslide_uninstall() {
  global $wpdb;

  // delete the options
  delete_option('radslide_db_version');

  // delete the table
	$wpdb->query("DROP TABLE IF EXISTS ".radslide_helper_db_slideshow());
	$wpdb->query("DROP TABLE IF EXISTS ".radslide_helper_db_slide());
}

register_activation_hook(__FILE__, 'radslide_activate');
register_deactivation_hook(__FILE__, 'radslide_deactivate');

