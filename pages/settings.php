<?php

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


?>
