<?php

// uninstall radslide
function radslide_ajax_uninstall() {
	radslide_uninstall();
	?>
	<p>radSLIDE has been completely uninstalled. Now go de-activate and delete it.</p>
	<p>If you want to use it again, you have to de-activate and then re-activate it to setup the database again.</p>
	<?php
	exit();
}

add_action('wp_ajax_radslide_uninstall', 'radslide_ajax_uninstall');

?>
