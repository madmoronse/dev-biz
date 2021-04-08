<?php 
if (!isset($text_image_manager)) $text_image_manager = '';
if (!isset($success)) $success = '';
?>
<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
    <div class="success"><?php echo $success ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons">
        <a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a>
        <a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a>
      </div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table id="sizes" class="list">
          <thead>
            <tr>
              <td class="left"></td>
              <td class="left"><?php echo $entry_type; ?></td>
              <td class="left"><?php echo $entry_image; ?></td>
              <td class="left"><?php echo $entry_product; ?></td>
              <td class="left"><?php echo $entry_name; ?></td>
              <td class="left"><?php echo $entry_sex; ?></td>
              <td class="left"><?php echo $entry_category; ?></td>
              <td></td>
            </tr>
          </thead>
          <?php $size_row = 0; ?>
          <tbody>
          <?php foreach ($productsizes as $size) { ?>
            <tr id="size-row<?php echo $size_row; ?>">
              <td class="left">
                <strong><?php echo $entry_caption ?></strong>
                <input
                  type="text"
                  name="productsizes[<?php echo $size_row; ?>][caption]"
                  style="display:block"
                  value="<?php echo $size['caption']; ?>"
                  size="32"
                />
                <br />
                <strong><?php echo $entry_text ?></strong>
                <input
                  type="text"
                  name="productsizes[<?php echo $size_row; ?>][text]"
                  style="display:block"
                  value="<?php echo $size['text']; ?>"
                  size="32"
                />
                <p class="help"><?php echo $text_help ?></p>
                <?php if (isset($error['productsizes'][$size_row]['general'])) { ?>
                  <span class="error"><?php echo $error['productsizes'][$size_row]['general']; ?></span>
                <?php } ?>
              </td>
              <td class="left">
                <select name="productsizes[<?php echo $size_row; ?>][type]">
                  <?php foreach ($size_types as $type) { ?>
                    <?php if ($type['value'] == $size['type']) { ?>
                    <option value="<?php echo $type['value']; ?>" selected="selected"><?php echo $type['text']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $type['value']; ?>"><?php echo $type['text']; ?></option>
                    <?php } ?>
                  <?php } ?>
                </select>
              </td>
              <td class="left">
                <div class="image">
                  <img src="<?php echo $size['thumb']; ?>" title="<?php echo $size['title']; ?>" alt="" id="thumb<?php echo $size_row; ?>" />
                  <input
                    type="hidden"
                    name="productsizes[<?php echo $size_row; ?>][image]"
                    value="<?php echo $size['image']; ?>" id="image<?php echo $size_row; ?>" 
                  />
                  <br />
                  <a onclick="image_upload('image<?php echo $size_row; ?>', 'thumb<?php echo $size_row; ?>');">
                    <?php echo $text_browse; ?>
                  </a>
                    &nbsp;&nbsp;|&nbsp;&nbsp;
                  <a onclick="$('#thumb<?php echo $size_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image<?php echo $size_row; ?>').attr('value', '');">
                    <?php echo $text_clear; ?>
                  </a>
                  <?php if (isset($error['productsizes'][$size_row]['image'])) { ?>
                  <span class="error"><?php echo $error['productsizes'][$size_row]['image']; ?></span>
                <?php } ?>
                </div>
              </td>
              <td class="left">
                <textarea
                  name="productsizes[<?php echo $size_row; ?>][product_id]"
                ><?php echo $size['product_id']; ?></textarea>
                <?php if (isset($error['productsizes'][$size_row]['product_id'])) { ?>
                  <span class="error"><?php echo $error['productsizes'][$size_row]['product_id']; ?></span>
                <?php } ?>
              </td>
              <td class="left">
                <input
                  type="text"
                  name="productsizes[<?php echo $size_row; ?>][name]"
                  value="<?php echo $size['name']; ?>"
                  size="32"
                />
              </td>
              <td class="left">
                <select name="productsizes[<?php echo $size_row; ?>][sex]">
                <?php foreach ($size_sex as $sex) { ?>
                    <?php if ($sex['value'] == $size['sex']) { ?>
                    <option value="<?php echo $sex['value']; ?>" selected="selected"><?php echo $sex['text']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $sex['value']; ?>"><?php echo $sex['text']; ?></option>
                    <?php } ?>
                  <?php } ?>
                  </select>
              </td>
              <td class="left">
                <input
                  type="text"
                  name="productsizes[<?php echo $size_row; ?>][category_id]"
                  value="<?php echo $size['category_id']; ?>"
                  size="11"
                />
                <?php if (isset($error['productsizes'][$size_row]['category_id'])) { ?>
                  <span class="error"><?php echo $error['productsizes'][$size_row]['category_id']; ?></span>
                <?php } ?>
              </td>
              <td class="left">
                <a onclick="$('#size-row<?php echo $size_row; ?>').remove();" class="button">
                  <?php echo $button_remove; ?>
                </a>
              </td>
            </tr>
          <?php $size_row++; ?>
          <?php } ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="7"></td>
              <td class="left"><a onclick="addSize();" class="button"><?php echo $button_add_size; ?></a></td>
            </tr>
          </tfoot>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
var size_row = <?php echo $size_row; ?>;
function image_upload(field, thumb) {
	$('#dialog').remove();
	
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: '<?php echo $text_image_manager; ?>',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).attr('value')),
					dataType: 'text',
					success: function(data) {
            var parts = data.split('/');
						$('#' + thumb).replaceWith('<img src="' + data + '" title="' + parts[parts.length - 1] + '" alt="" id="' + thumb + '" />');
					}
				});
			}
		},	
		bgiframe: false,
		width: 800,
		height: 400,
		resizable: false,
		modal: false
	});
}

function addSize() {	
  html = [
    '<tr id="size-row' + size_row + '">',
      '<td class="left">',
        '<strong><?php echo $entry_caption ?></strong>',
        '<input type="text" style="display:block" name="productsizes[' + size_row + '][caption]" value="" size="32" />',
        '<br />',
        '<strong><?php echo $entry_text ?></strong>',
        '<input type="text" style="display:block" name="productsizes[' + size_row + '][text]" value="" size="32" />',
        '<p class="help"><?php echo $text_help ?></p>',
      '</td>',
      '<td class="left">',
        '<select name="productsizes[' + size_row + '][type]">',
          <?php foreach ($size_types as $type) { ?>
            '<option value="<?php echo $type['value']; ?>"><?php echo $type['text']; ?></option>',
          <?php } ?>
        '</select>',
      '</td>',
      '<td class="left">',
        '<div class="image">',
          '<img src="" alt="" id="thumb' + size_row + '" />',
          '<input type="hidden" name="productsizes[' + size_row + '][image]" value="" id="image' + size_row + '" />',
          '<br />',
          '<a onclick="image_upload(\'image' + size_row + '\', \'thumb' + size_row + '\');"><?php echo $text_browse; ?></a>',
          '&nbsp;&nbsp;|&nbsp;&nbsp;',
          '<a onclick="$(\'#thumb' + size_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#image' + size_row + '\').attr(\'value\', \'\');"><?php echo $text_clear; ?></a>',
        '</div>',
      '</td>',
      '<td class="left">',
        '<textarea name="productsizes[' + size_row + '][product_id]"></textarea>',
      '</td>',
      '<td class="left">',
        '<input type="text" name="productsizes[' + size_row + '][name]" value="" size="32" />',
      '</td>',
      '<td class="left">',
        '<select name="productsizes[' + size_row + '][sex]">',
        <?php foreach ($size_sex as $sex) { ?>
          '<option value="<?php echo $sex['value']; ?>"><?php echo $sex['text']; ?></option>',
        <?php } ?>
        '</select>',
      '</td>',
      '<td class="left">',
        '<input type="text" name="productsizes[' + size_row + '][category_id]" value="" size="11" />',
      '</td>',
      '<td class="left">',
        '<a onclick="$(\'#size-row' + size_row + '\').remove();" class="button"><?php echo $button_remove; ?></a>',
      '</td>',
    '</tr>'
  ].join('');
	$('#sizes tbody').append(html);
	size_row++;
}
</script> 
<?php echo $footer; ?>