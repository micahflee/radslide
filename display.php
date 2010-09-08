<?php

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


?>
