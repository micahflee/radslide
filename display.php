<?php

// display the slideshow, this gets called in the theme
function radslide($id, $display=true) {
	global $wpdb;
	$id = (int)$id;
	$html = '';

	// get the slideshow
	$table_name = radslide_helper_db_slideshow();
	$slideshow_row = $wpdb->get_row("SELECT * FROM $table_name WHERE id='$id'");
	if($slideshow_row) {
		// display all its slides
		$html .= '<div id="radslide-'.$id.'">';
		$table_name = radslide_helper_db_slide();
		$rows = $wpdb->get_results("SELECT id,title,description,image_url,link_url,sort FROM $table_name WHERE slideshow_id='$id' ORDER BY sort,id");
		foreach($rows as $row) {
			$slide = stripslashes($slideshow_row->template);
			$slide = str_replace("[[TITLE]]", stripslashes($row->title), $slide);
			$slide = str_replace("[[DESCRIPTION]]", stripslashes($row->description), $slide);
			$slide = str_replace("[[LINK_URL]]", stripslashes($row->link_url), $slide);
			$slide = str_replace("[[IMAGE_URL]]", stripslashes($row->image_url), $slide);
			$slide = str_replace("[[SLIDE_ID]]", stripslashes($row->id), $slide);
			$html .= '<div class="radslide">'.$slide.'</div>';
		}
		$html .= '</div>';
	}

	if($display) {
		echo($html);
	} else {
		return $html;
	}
}

// insert slideshow into pages and posts
function radslide_insert_slideshow($content) {
	// looking for [[radslide #]], where # is an id
	$pattern = '/\[\[radslide\s*(\d+)\]\]/';
	$callback = create_function('$matches', 'return radslide($matches[1],false);');
	$content = preg_replace_callback($pattern, $callback, $content);
	return $content;
}
add_action('the_content', 'radslide_insert_slideshow');

?>
