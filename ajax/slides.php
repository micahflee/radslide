<?php

// list of slides
function radslide_ajax_slides_populate() {
	global $wpdb;
	$slideshow_row = $wpdb->get_row("SELECT * FROM ".radslide_helper_db_slideshow()." WHERE id=".(int)($_POST['radslide_slideshow_id']));
	?>
	<input type="hidden" id="radslide_slideshow_id" value="<?php echo($slideshow_row->id); ?>" />
	<h2>Managing Slideshow: <?php echo($slideshow_row->name); ?></h2>
	<input type="button" id="radslide_back_to_slideshows" class="button-primary" value="Back to Slideshows" style="margin-bottom:10px;" />
	<?php radslide_helper_ajax_loader("radslide_back_to_slideshows_loading"); ?>

  <table>
    <tr>
			<th>ID</th>
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
	$rows = $wpdb->get_results("SELECT * FROM $table_name WHERE slideshow_id='".$slideshow_row->id."' ORDER BY sort,id");
    foreach($rows as $row) {
      ?>
			<tr class="radslide_row" id="radslide_row-<?php echo($row->id); ?>">
				<td><?php echo($row->id); ?></td>
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
        </td>
        <td><?php radslide_helper_ajax_loader("radslide_loading-".$row->id); ?></td>
      </tr>
      <?php
    }
    ?>
    <tr><td colspan="8"><div style="background-color:#999999;height:1px;margin:8px 0;"></div></td></tr>
		<tr id="radslide_add_row">
			<td></td>
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
        <input type="button" class="button-primary" value="Add Slide" id="radslide_add" />
      </td>
			<td><?php radslide_helper_ajax_loader("radslide_loading"); ?></td>
    </tr>
	</table>
  <p><input type="button" class="button-primary" value="Save Changes" id="radslide_update" /></p>
	<script type="text/javascript">radslide_setup_image_pickers();</script>
  <?php
  exit();
}

// add a new slide
function radslide_ajax_slides_add() {
  global $wpdb;
  $row = array(
    'slideshow_id' => $_POST['radslide_slideshow_id'],
    'title' => $_POST['radslide_title'],
    'description' => $_POST['radslide_description'],
    'image_url' => $_POST['radslide_image_url'],
    'link_url' => $_POST['radslide_link_url'],
    'sort' => $_POST['radslide_sort']
  );
  $wpdb->insert(radslide_helper_db_slide(), $row);
  exit();
}

// update all the slides
function radslide_ajax_slides_update() {
  global $wpdb;
  foreach($_POST['radslide_data'] as $row)
    $wpdb->update(radslide_helper_db_slide(), $row, array('id'=>$row['id']));
  exit();
}

// delete a slide
function radslide_ajax_slides_delete() {
  global $wpdb;
  $wpdb->query("DELETE FROM ".radslide_helper_db_slide()." WHERE id='".(int)($_POST['radslide_id'])."'");
}

// add ajax actions
add_action('wp_ajax_radslide_slides_populate', 'radslide_ajax_slides_populate');
add_action('wp_ajax_radslide_slides_add', 'radslide_ajax_slides_add');
add_action('wp_ajax_radslide_slides_update', 'radslide_ajax_slides_update');
add_action('wp_ajax_radslide_slides_delete', 'radslide_ajax_slides_delete');

?>
