<?php echo $header; ?>
<div id="content">
  <div class="box">
	
		<div class="breadcrumb">
			<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
			<?php } ?>
		</div>
	
		<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
		<?php } ?>
	
		<div class="heading">
      <h1><img src="view/image/product.png" alt="" /> <?php echo preg_replace('(<.*?>)', '', $heading_title); ?></h1>
			<div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
		<div class="content">
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<div id="tabs" class="htabs">
				<a href="#tab-general"><?php echo $heading_main_title; ?></a>
				<a href="#tab-shem"><?php echo $heading_shem_title; ?></a>
			</div>
			<div id="tab-general">
				
				<div id="tabs-in" class="vtabs">
					<a href="#tab-contact-head"><?php echo $vtab_head_title; ?></a>
					<a href="#tab-details-foot"><?php echo $vtab_foot_title; ?></a>
					<a href="#tab-social-foot"><?php echo $vtab_cap_title; ?></a>
				</div>
				<div id="tab-contact-head" class="vtabs-content">
					<table class="list">
						<tr>
							<td class="left" ><b><?php echo $phone_number_title; ?></b></td>
							<td class="left"><input type="text" name="phone_number_text" value="<?php echo $phone_number_text; ?>"></td>
						</tr>
						<tr>
							<td class="left"><b><?php echo $email_text_title; ?></b></td>
							<td class="left"><input type="text" name="email_text" value="<?php echo $email_text; ?>"></td>
						</tr>
						<tr>
							<td class="left"><b><?php echo $skype_text_title; ?></b></td>
							<td class="left"><input type="text" name="skype_text" value="<?php echo $skype_text; ?>"></td>
						</tr>
					</table>
				</div>
				<div id="tab-details-foot" class="vtabs-content">
					<h2><?php echo $details_text_title; ?></h2>
					<table class="form">
						<tr>
							<td class="left">
								<div id="languages-d" class="htabs">
									<?php foreach ($languages as $language) { ?>
									<a href="#language-d<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
									<?php } ?>
								</div>
								<?php foreach ($languages as $language) { ?>
								<div id="language-d<?php echo $language['language_id']; ?>">
									<textarea name="details_text[<?php echo $language['language_id']; ?>][name]" id="details<?php echo $language['language_id']; ?>"><?php echo isset($details_text[$language['language_id']]) ? $details_text[$language['language_id']]['name'] : ''; ?></textarea>
								</div>
								<?php } ?>
							</td>
						</tr>
					</table>
				</div>
				<div id="tab-social-foot" class="vtabs-content">
					<h2><?php echo $cap_text_title; ?></h2>
					<table class="form">
						<tr>
							<td class="left">
								<div id="languages-c" class="htabs">
									<?php foreach ($languages as $language) { ?>
									<a href="#language-c<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
									<?php } ?>
								</div>
								<?php foreach ($languages as $language) { ?>
								<div id="language-c<?php echo $language['language_id']; ?>">
									<textarea name="cap_text[<?php echo $language['language_id']; ?>][name]" id="cap<?php echo $language['language_id']; ?>"><?php echo isset($cap_text[$language['language_id']]) ? $cap_text[$language['language_id']]['name'] : ''; ?></textarea>
								</div>
								<?php } ?>
							</td>
						</tr>
					</table>
				</div>
				
			</div>
			<div id="tab-shem">
				<h2><?php echo $heading_shem_title; ?></h2>
				<table id="module" class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $entry_layout; ?></td>
              <td class="left"><?php echo $entry_position; ?></td>
              <td class="left"><?php echo $entry_status; ?></td>
              <td class="right"><?php echo $entry_sort_order; ?></td>
              <td></td>
            </tr>
          </thead>
          <?php $module_row = 0; ?>
          <?php foreach ($modules as $module) { ?>
          <tbody id="module-row<?php echo $module_row; ?>">
            <tr>
              <td class="left"><select name="xds_default2_control_module[<?php echo $module_row; ?>][layout_id]">
                  <?php foreach ($layouts as $layout) { ?>
                  <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                  <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
              <td class="left"><select name="xds_default2_control_module[<?php echo $module_row; ?>][position]">
                  <?php if ($module['position'] == 'content_top') { ?>
                  <option value="content_top" selected="selected"><?php echo $text_content_top; ?></option>
                  <?php } else { ?>
                  <option value="content_top"><?php echo $text_content_top; ?></option>
                  <?php } ?>
                  <?php if ($module['position'] == 'content_bottom') { ?>
                  <option value="content_bottom" selected="selected"><?php echo $text_content_bottom; ?></option>
                  <?php } else { ?>
                  <option value="content_bottom"><?php echo $text_content_bottom; ?></option>
                  <?php } ?>
                  <?php if ($module['position'] == 'column_left') { ?>
                  <option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
                  <?php } else { ?>
                  <option value="column_left"><?php echo $text_column_left; ?></option>
                  <?php } ?>
                  <?php if ($module['position'] == 'column_right') { ?>
                  <option value="column_right" selected="selected"><?php echo $text_column_right; ?></option>
                  <?php } else { ?>
                  <option value="column_right"><?php echo $text_column_right; ?></option>
                  <?php } ?>
                </select></td>
              <td class="left"><select name="xds_default2_control_module[<?php echo $module_row; ?>][status]">
                  <?php if ($module['status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
              <td class="right"><input type="text" name="xds_default2_control_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
              <td class="left"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
            </tr>
          </tbody>
          <?php $module_row++; ?>
          <?php } ?>
          <tfoot>
            <tr>
              <td colspan="4"></td>
              <td class="left"><a onclick="addModule();" class="button"><?php echo $button_add_module; ?></a></td>
            </tr>
          </tfoot>
        </table>
			</div>
			
			</form>
		</div>
	</div>
</div>
<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;

function addModule() {	
	html  = '<tbody id="module-row' + module_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><select name="xds_default2_control_module[' + module_row + '][layout_id]">';
	<?php foreach ($layouts as $layout) { ?>
	html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
	<?php } ?>
	html += '    </select></td>';
	html += '    <td class="left"><select name="xds_default2_control_module[' + module_row + '][position]">';
	html += '      <option value="content_top"><?php echo $text_content_top; ?></option>';
	html += '      <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
	html += '      <option value="column_left"><?php echo $text_column_left; ?></option>';
	html += '      <option value="column_right"><?php echo $text_column_right; ?></option>';
	html += '    </select></td>';
	html += '    <td class="left"><select name="xds_default2_control_module[' + module_row + '][status]">';
  html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
  html += '      <option value="0"><?php echo $text_disabled; ?></option>';
  html += '    </select></td>';
	html += '    <td class="right"><input type="text" name="xds_default2_control_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
	html += '    <td class="left"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#module tfoot').before(html);
	
	module_row++;
}
//--></script>
<script type="text/javascript"><!--
$('#tabs a').tabs();
$('#tabs-in a').tabs();
$('#languages-d a').tabs();
$('#languages-c a').tabs();
//--></script>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
<script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
CKEDITOR.replace('cap<?php echo $language['language_id']; ?>', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
CKEDITOR.replace('details<?php echo $language['language_id']; ?>', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
<?php } ?>
//--></script> 
<?php echo $footer; ?>