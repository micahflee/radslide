<?php

// list of slideshows
function radslide_ajax_slideshows_populate() {
	global $wpdb;

	$default_template = '<a href="[[LINK_URL]]"><img src="[[IMAGE_URL]]" alt="[[TITLE]]" /></a>
<h3><a href="[[LINK_URL]]">[[TITLE]]</a></h3>
<div class="blurb">[[DESCRIPTION]]</div>';
	$default_cycle_options = '{ timeout:2000, speed:500 }';
	
	?>
	<h2>Slideshows</h2>
	<table>
		<tr>
			<th>ID</th>
			<th style="width:20px;"></th>
			<th>Name</th>
			<th style="width:20px;"></th>
			<th>Action</th>
			<th style="width:20px;"></th>
			<th>Embed Code</th>
			<th style="width:20px;"></th>
			<th>PHP Theme Code</th>
			<td></td>
		</tr>
    <?php
    $table_name = radslide_helper_db_slideshow();
    $rows = $wpdb->get_results("SELECT id,name FROM $table_name ORDER BY name,id");
    foreach($rows as $row) {
			?>
			<tr>
				<td><?php echo($row->id); ?></td>
				<td></td>
				<td><?php echo($row->name); ?></td>
				<td></td>
				<td>
					<input type="button" class="button-primary" id="radslide_manage-<?php echo($row->id); ?>" value="Manage" />
					<input type="button" class="button-primary" id="radslide_settings-<?php echo($row->id); ?>" value="Settings" />
					<input type="button" class="button-primary" id="radslide_delete-<?php echo($row->id); ?>" value="Delete" />
				</td>
				<td></td>
				<td style="font-family:courier; font-size:11px;"> [[radslide <?php echo($row->id); ?>]] </td>
				<td></td>
				<td style="font-family:courier; font-size:11px;"> &lt;?php radslide(<?php echo($row->id); ?>); ?&gt; </td>
        <td><?php radslide_helper_ajax_loader("radslide_loading-".$row->id); ?></td>
			</tr>
			<?php
		}
		?>
	</table>

	<hr/>

	<h2 id="radslide_add_toggle">New Slideshow <input type="button" class="button-primary" value="+" style="vertical-align:middle" /></h2>
	<div id="radslide_add_form" style="visibility:hidden">
		<table>
			<tr>
				<td>Name</td>
				<td><input type="text" id="radslide_add-name" value="" /></td>
			</tr>
			<tr>
				<td style="width:120px;">Template<br/><span style="font-size:.8em; font-style:italic;">Note: Use [[TITLE]], [[DESCRIPTION]], [[LINK_URL]], [[IMAGE_URL]], [[SLIDE_ID]]</span></th>
				<td><textarea style="width:650px;height:150px;" id="radslide_add-template"><?php echo($default_template); ?></textarea></td>
			</tr>
			<tr>
				<td><a href="http://jquery.malsup.com/cycle/options.html" target="_blank">jQuery Cycle Options</a></td>
				<td><textarea style="width:500px;height:100px;" id="radslide_add-cycle_options"><?php echo($default_cycle_options); ?></textarea></td>
			</tr>
		</table>
		<p class="submit">
			<input type="submit" class="button-primary" id="radslide_add" value="<?php _e('Add Slideshow') ?>" />
			<?php radslide_helper_ajax_loader("radslide_loading"); ?>
		</p>
	</div>
	<?php
	exit();
}

// add a new slideshow
function radslide_ajax_slideshows_add() {
  global $wpdb;
  $row = array(
    'name' => $_POST['radslide_name'],
    'template' => $_POST['radslide_template'],
    'cycle_options' => $_POST['radslide_cycle_options'],
  );
  $wpdb->insert(radslide_helper_db_slideshow(), $row);
  exit();
}

// edit a slideshow's settings
function radslide_ajax_slideshows_settings() {
	global $wpdb;
	$slideshow_row = $wpdb->get_row("SELECT * FROM ".radslide_helper_db_slideshow()." WHERE id=".(int)($_POST['radslide_slideshow_id']));
	?>
	<input type="hidden" id="radslide_slideshow_id" value="<?php echo($slideshow_row->id); ?>" />
	<h2>Slideshow Settings: <?php echo($slideshow_row->name); ?></h2>
	<input type="button" id="radslide_back_to_slideshows" class="button-primary" value="Back to Slideshows" style="margin-bottom:10px;" />
	<?php radslide_helper_ajax_loader("radslide_back_to_slideshows_loading"); ?>
	<table>
		<tr>
			<td>Name</td>
			<td><input type="text" id="radslide-name" value="<?php echo(stripslashes($slideshow_row->name)); ?>" /></td>
		</tr>
		<tr>
			<td style="width:120px;">Template<br/><span style="font-size:.8em; font-style:italic;">Note: Use [[TITLE]], [[DESCRIPTION]], [[LINK_URL]], [[IMAGE_URL]], [[SLIDE_ID]]</span></th>
			<td><textarea style="width:650px;height:150px;" id="radslide-template"><?php echo(stripslashes($slideshow_row->template)); ?></textarea></td>
		</tr>
		<tr>
			<td><a href="http://jquery.malsup.com/cycle/options.html" target="_blank">jQuery Cycle Options</a></td>
			<td><textarea style="width:500px;height:100px;" id="radslide-cycle_options"><?php echo(stripslashes($slideshow_row->cycle_options)); ?></textarea></td>
		</tr>
	</table>
	<p class="submit">
		<input type="submit" class="button-primary" id="radslide_edit" value="<?php _e('Edit Slideshow') ?>" />
		<?php radslide_helper_ajax_loader("radslide_loading"); ?>
	</p>
	<?php
  exit();
}

// update a slideshow's settings
function radslide_ajax_slideshows_settings_edit() {
  global $wpdb;
  $row = array(
    'name' => $_POST['radslide_name'],
    'template' => $_POST['radslide_template'],
    'cycle_options' => $_POST['radslide_cycle_options'],
  );
  $wpdb->update(radslide_helper_db_slideshow(), $row, array('id'=>(int)($_POST['radslide_slideshow_id'])));
  exit();
}

// delete a slideshow
function radslide_ajax_slideshows_delete() {
  global $wpdb;
  $wpdb->query("DELETE FROM ".radslide_helper_db_slideshow()." WHERE id='".(int)($_POST['radslide_slideshow_id'])."'");
  $wpdb->query("DELETE FROM ".radslide_helper_db_slide()." WHERE slideshow_id='".(int)($_POST['radslide_slideshow_id'])."'");
  exit();
}

// add ajax actions
add_action('wp_ajax_radslide_slideshows_populate', 'radslide_ajax_slideshows_populate');
add_action('wp_ajax_radslide_slideshows_add', 'radslide_ajax_slideshows_add');
add_action('wp_ajax_radslide_slideshows_settings', 'radslide_ajax_slideshows_settings');
add_action('wp_ajax_radslide_slideshows_settings_edit', 'radslide_ajax_slideshows_settings_edit');
add_action('wp_ajax_radslide_slideshows_delete', 'radslide_ajax_slideshows_delete');

?>
