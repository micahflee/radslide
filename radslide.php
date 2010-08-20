<?php
/*
 * Plugin Name: radSLIDE
 * Plugin URI: http://www.radicaldesigns.org/
 * Description: A slideshow plugin, made specifically for radicalDESIGN's clients
 * Version: 1.0
 * Author: Micah Lee <micah@radicaldesigns.org>
 * Author URI: http://www.radicaldesigns.org/
 * License: GPL2
 * */

define('RADSLIDE_DB_VERSION', 1);

// helpers
function radslide_helper_include_jquery() {
  $jquery_url = get_option('siteurl').'/wp-content/plugins/radslide/jquery-1.4.2.min.js';
  echo '<script type="text/javascript" src="'.$jquery_url.'"></script>';
}
function radslide_helper_ajax_loader($id) {
  $image_url = get_option('siteurl').'/wp-content/plugins/radslide/ajax-loader.gif';
  echo '<img src="'.$image_url.'" id="'.$id.'" style="display:none" />';
}
function radslide_helper_db_table_name() {
  return $wpdb->prefix.'radslide';
}

// display the slideshow, this gets called in the theme
function radslide() {
  global $wpdb;
  
  if(get_option('radslide_enabled') == 'false') {
    echo(get_option('radslide_disabled_html'));
    return; 
  }

  ?>
  <div id="radslide">
    <?php
    $table_name = radslide_helper_db_table_name();
    $rows = $wpdb->get_results("SELECT id,title,description,image_url,link_url,sort FROM $table_name ORDER BY sort,id");
    foreach($rows as $row) {
      $slide = get_option('radslide_template');
      $slide = str_replace("[[TITLE]]", stripslashes($row->title), $slide);
      $slide = str_replace("[[DESCRIPTION]]", stripslashes($row->description), $slide);
      $slide = str_replace("[[LINK_URL]]", stripslashes($row->link_url), $slide);
      $slide = str_replace("[[IMAGE_URL]]", stripslashes($row->image_url), $slide);
      echo('<div class="radslide">'.$slide.'</div>');
    }
    ?>
  </div>
  <?php
}

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
  add_menu_page('radSLIDE Slideshow', 'radSLIDE', 'administrator', 'radslide_slideshow', 'radslide_page_slideshow');
  add_submenu_page('radslide_slideshow', 'radSLIDE Settings', 'Settings', 'administrator', 'radslide_settings', 'radslide_page_settings');
}

// menu register settings
function radslide_register_settings() {
  register_setting('radslide_settings','radslide_template');
  register_setting('radslide_settings','radslide_cycle_options');
  register_setting('radslide_settings','radslide_inc_jquery');
  register_setting('radslide_settings','radslide_enabled');
  register_setting('radslide_settings','radslide_disabled_html');
}

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
function radslide_ajax_populate() {
  global $wpdb;
  ?>
  <table>
    <tr>
      <th>Image</th>
      <th>Title</th>
      <th>Description</th>
      <th>Link URL</th>
      <th>Order</th>
      <th>Action</th>
      <th></th>
    </tr>
    <?php
    $table_name = radslide_helper_db_table_name();
    $rows = $wpdb->get_results("SELECT id,title,description,image_url,link_url,sort FROM $table_name ORDER BY sort,id");
    foreach($rows as $row) {
      ?>
			<tr class="radslide_row" id="radslide_row-<?php echo($row->id); ?>">
				<td style="text-align:center">
					<input type="hidden" class="radslide_field" id="radslide_update-image_url-<?php echo($row->id); ?>" value="<?php echo(stripslashes($row->image_url)); ?>" />
					<?php if(!empty($row->image_url)) { ?><img src="<?php echo(stripslashes($row->image_url)); ?>" height="50" id="radslide_update-image-<?php echo($row->id); ?>" /><?php } ?>
				</td>
        <td><input type="text" class="radslide_field" id="radslide_update-title-<?php echo($row->id); ?>" value="<?php echo(stripslashes($row->title)); ?>" /></td>
        <td><input type="text" class="radslide_field" id="radslide_update-description-<?php echo($row->id); ?>" value="<?php echo(stripslashes($row->description)); ?>" /></td>
        <td><input type="text" class="radslide_field" id="radslide_update-link_url-<?php echo($row->id); ?>" value="<?php echo(stripslashes($row->link_url)); ?>" /></td>
        <td><input type="text" style="width:3em;" class="radslide_field" id="radslide_update-sort-<?php echo($row->id); ?>" value="<?php echo(stripslashes($row->sort)); ?>" /></td>
        <td style="text-align:center">
					<input type="button" class="button-primary radslide_image_picker" id="radslide_image_picker-<?php echo($row->id); ?>" value="Choose Image" />
          <input type="submit" class="button-primary" value="Delete" id="radslide_delete-<?php echo($row->id); ?>" />
        <td>
        <td><?php radslide_helper_ajax_loader("radslide_loading-".$row->id); ?></td>
      </tr>
      <?php
    }
    ?>
    <tr><td colspan="6"><div style="background-color:#999999;height:1px;margin:8px 0;"></div></td></tr>
    <tr id="radslide_add_row">
 			<td style="text-align:center">
				<input type="hidden" id="radslide_add-image_url" value="" />
				<img src="" width="100" id="radslide_add-image" />
			</td>
     <td><input type="text" id="radslide_add-title" value="Enter title" /></td>
      <td><input type="text" id="radslide_add-description" value="Enter description" /></td>
      <td><input type="text" id="radslide_add-link_url" value="http://" /></td>
      <td><input type="text" style="width:3em;" id="radslide_add-sort" value="0" /></td>
      <td style="text-align:center;">
				<input type="button" class="button-primary radslide_image_picker" id="radslide_image_picker-add" value="Choose Image" />
        <input type="submit" class="button-primary" value="Add Slide" id="radslide_add" />
        <input type="submit" class="button-primary" value="Update" id="radslide_update" />
      </td>
      <td><?php radslide_helper_ajax_loader("radslide_loading"); ?></td>
    </tr>
	</table>
	<script type="text/javascript">radslide_setup_image_pickers();</script>
  <?php
  exit();
}
function radslide_ajax_add() {
  global $wpdb;
  $row = array(
    'title' => $_POST['radslide_title'],
    'description' => $_POST['radslide_description'],
    'image_url' => $_POST['radslide_image_url'],
    'link_url' => $_POST['radslide_link_url'],
    'sort' => $_POST['radslide_sort']
  );
  $wpdb->insert(radslide_helper_db_table_name(), $row);
  exit();
}
function radslide_ajax_update() {
  global $wpdb;
  foreach($_POST['radslide_data'] as $row)
    $wpdb->update(radslide_helper_db_table_name(), $row, array('id'=>$row['id']));
  exit();
}
function radslide_ajax_delete() {
  global $wpdb;
  $wpdb->query("DELETE FROM ".radslide_helper_db_table_name()." WHERE id='".$_POST['radslide_id']."'");
}
add_action('wp_ajax_radslide_populate', 'radslide_ajax_populate');
add_action('wp_ajax_radslide_add', 'radslide_ajax_add');
add_action('wp_ajax_radslide_update', 'radslide_ajax_update');
add_action('wp_ajax_radslide_delete', 'radslide_ajax_delete');

// settings page
function radslide_page_settings() {
  $data_template = get_option('radslide_template');
  $data_cycle_options = get_option('radslide_cycle_options');
  $data_inc_jquery = get_option('radslide_inc_jquery');
  $data_enabled = get_option('radslide_enabled');
  $data_disabled_html = get_option('radslide_disabled_html');
?>
<div class="wrap">
  <h2>radSLIDE Slideshow</h2>
  <form method="post" action="options.php">
    <?php wp_nonce_field('update-options'); ?>
    <?php settings_fields('radslide_settings'); ?>
    <table class="form-table">
      <tr valign="top">
        <th scope="row">Enable radSLIDE?</th>
        <td>
          <input type="radio" name="radslide_enabled" value="true" <?php if($data_enabled == 'true') echo('checked="checked"'); ?>/> Yes
          <input type="radio" name="radslide_enabled" value="false" <?php if($data_enabled == 'false') echo('checked="checked"'); ?>/> No
        </td>
      </tr>
      <tr valign="top">
        <th scope="row">Should radSLIDE include jQuery?</th>
        <td>
          <input type="radio" name="radslide_inc_jquery" value="true" <?php if($data_inc_jquery == 'true') echo('checked="checked"'); ?>/> Yes
          <input type="radio" name="radslide_inc_jquery" value="false" <?php if($data_inc_jquery == 'false') echo('checked="checked"'); ?>/> No
        </td>
      </tr>
      <tr valign="top">
        <th scope="row">Slideshow Template<br/><em>Note: Use [[TITLE]], [[DESCRIPTION]], [[LINK_URL]], [[IMAGE_URL]]</em></th>
        <td><textarea style="width:500px;height:200px;" name="radslide_template"><?php echo($data_template); ?></textarea></td>
      </tr>
      <tr valign="top">
        <th scope="row">jQuery Cycle Options</th>
        <td><textarea style="width:500px;height:100px;" name="radslide_cycle_options"><?php echo($data_cycle_options); ?></textarea></td>
      </tr>
      <tr valign="top">
        <th scope="row">What should get displayed if radSLIDE is disabled?</th>
        <td><textarea style="width:500px;height:200px;" name="radslide_disabled_html"><?php echo($data_disabled_html); ?></textarea></td>
      </tr>
    </table>
    <p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
  </form>
</div>
<?php
}

// add jquery to head, if needed
function radslide_head() {
  if(get_option('radslide_enabled') == 'true') {
    if(get_option('radslide_inc_jquery') == 'true') {
      radslide_helper_include_jquery();
    }
    $jquery_cycle_url = get_option('siteurl').'/wp-content/plugins/radslide/jquery.cycle.lite.min.js';
    ?>
    <script type="text/javascript" src="<?php echo($jquery_cycle_url); ?>"></script>
    <script type="text/javascript">
    $(document).ready(function(){
      $("#radslide").cycle(<?php echo(get_option('radslide_cycle_options')); ?>);
    });
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

