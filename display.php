<?php

// display the slideshow, this gets called in the theme
function radslide($id) {
	global $wpdb;
	$id = (int)$id;

	// get the slideshow
	$table_name = radslide_helper_db_slideshow();
	$slideshow_row = $wpdb->get_row("SELECT * FROM $table_name WHERE id='$id'");
	echo '<pre>'; print_r($slideshow_row); echo '</pre>';
	return;
	
	// display all its slides
  ?>
	<div id="radslide-<?php echo($id); ?>">
    <?php
    $table_name = radslide_helper_db_slide();
    $rows = $wpdb->get_results("SELECT id,title,description,image_url,link_url,sort FROM $table_name WHERE slideshow_id='$id' ORDER BY sort,id");
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


?>
