<?php

// uninstall page
function radslide_page_uninstall() {
	?>
	<script type="text/javascript"> siteurl = '<?php echo(get_option('siteurl')); ?>'; </script>
	<script src="<?php echo(get_option('siteurl')); ?>/wp-content/plugins/radslide/js/uninstall.js" type="text/javascript"></script>
	<h2>Uninstall radSLIDE</h2>
	<div id="radslide_uninstall_container">
		<p>When you deactive radSLIDE, all of the information it stores (slideshows and their slides) stays in your website's database. This is so you can temporarly disable the plugin without losing all your data. If you want to get rid of all traces of radSLIDE for good, click the following button.</p>
		<p style="color:red">WARNING: You cannot undo this. Only click the button if you're sure you want to.</p>
		<input type="button" id="radslide_uninstall" class="button-primary" value="Uninstall radSLIDE" />
	<?php radslide_helper_ajax_loader("radslide_loading"); ?>
	</div>
	<?php
	radslide_rd_credit();
}


?>
