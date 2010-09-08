<?php

// list of slideshows
function radslide_ajax_populate_slideshows() {
	global $wpdb;

	$default_template = '<a href="[[LINK_URL]]"><img src="[[IMAGE_URL]]" alt="[[TITLE]]" /></a>
<h3><a href="[[LINK_URL]]">[[TITLE]]</a></h3>
<div class="blurb">[[DESCRIPTION]]</div>';
	$default_cycle_options = '{
  delay:2000,
  speed:500
}';

	?>
	<h2>Slideshows</h2>
	<table>
		<tr>
			<th>Name</th>
			<th>Action</th>
		</tr>
    <?php
    $table_name = radslide_helper_db_slideshow();
    $rows = $wpdb->get_results("SELECT id,name FROM $table_name ORDER BY name,id");
    foreach($rows as $row) {
			?>
			<tr>
				<td><?php echo($row->name); ?></td>
				<td>
					<input type="button" class="button-primary" id="radslide_manage-<?php echo($row->id); ?>" value="Manage" />
					<input type="button" class="button-primary" id="radslide_edit-<?php echo($row->id); ?>" value="Edit" />
					<input type="button" class="button-primary" id="radslide_delete-<?php echo($row->id); ?>" value="Delete" />
				</td>
			</tr>
			<?php
		}
		?>
	</table>

	<hr/>

	<h2>New Slideshow</h2>
	<table>
		<tr>
			<td>Name</td>
			<td><input type="text" id="radslide_add-name" value="" /></td>
		</tr>
		<tr>
			<td style="width:120px;">Template<br/><span style="font-size:.8em; font-style:italic;">Note: Use [[TITLE]], [[DESCRIPTION]], [[LINK_URL]], [[IMAGE_URL]]</span></th>
			<td><textarea style="width:500px;height:200px;" id="radslide_add-template"><?php echo($default_template); ?></textarea></td>
		</tr>
		<tr>
			<td>jQuery Cycle Options</td>
			<td><textarea style="width:500px;height:100px;" id="radslide_add-cycle_options"><?php echo($default_cycle_options); ?></textarea></td>
		</tr>
	</table>
	<p class="submit"><input type="submit" class="button-primary" id="radslide_add" value="<?php _e('Add Slideshow') ?>" /> <span id="radslide_loading"></span></p>
	<?php
}

// add a new slideshow
function radslide_ajax_add_slideshow() {
  global $wpdb;
  $row = array(
    'name' => $_POST['radslide_name'],
    'template' => $_POST['radslide_template'],
    'cycle_options' => $_POST['radslide_cycle_options'],
  );
  $wpdb->insert(radslide_helper_db_slideshow(), $row);
  exit();
}




// list of slides
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
    $table_name = radslide_helper_db_slide();
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

// add a new slide
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

// update all the slides
function radslide_ajax_update() {
  global $wpdb;
  foreach($_POST['radslide_data'] as $row)
    $wpdb->update(radslide_helper_db_table_name(), $row, array('id'=>$row['id']));
  exit();
}

// delete a slide
function radslide_ajax_delete() {
  global $wpdb;
  $wpdb->query("DELETE FROM ".radslide_helper_db_table_name()." WHERE id='".$_POST['radslide_id']."'");
}

// add ajax actions
add_action('wp_ajax_radslide_populate_slideshows', 'radslide_ajax_populate_slideshows');
add_action('wp_ajax_radslide_add_slideshow', 'radslide_ajax_add_slideshow');
add_action('wp_ajax_radslide_populate', 'radslide_ajax_populate');
add_action('wp_ajax_radslide_add', 'radslide_ajax_add');
add_action('wp_ajax_radslide_update', 'radslide_ajax_update');
add_action('wp_ajax_radslide_delete', 'radslide_ajax_delete');

?>
