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
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div class="vtabs">
          <?php $module_row = 1; ?>
          <?php foreach ($modules as $module) { ?>
          <a href="#tab-module-<?php echo $module_row; ?>" id="module-<?php echo $module_row; ?>">
          <?php if (!empty($module['module_name'])) { ?>
          <?php echo $module['module_name']; ?>
          <?php } else { ?>
          <?php echo $tab_module . ' ' . $module_row; ?>
          <?php } ?>
          &nbsp;&nbsp;<img src="view/image/delete.png" alt="" onclick="$('.vtabs a:first').trigger('click'); $('#module-<?php echo $module_row; ?>').remove(); $('#tab-module-<?php echo $module_row; ?>').remove(); return false;" /></a>
          <?php $module_row++; ?>
          <?php } ?>
          <span id="module-add"><?php echo $button_add_module; ?>&nbsp;&nbsp;<img src="view/image/add.png" alt="" onclick="addModule();" /></span> </div>
        <?php $module_row = 1; ?>
        <?php $custom_link_row = 1; ?>
        <?php foreach ($modules as $module) { ?>
        <div id="tab-module-<?php echo $module_row; ?>" class="vtabs-content">
          <table class="form" style="border-collapse: separate; border-spacing: 0;">
            <tr>
              <td><?php echo $entry_module_name; ?></td>
              <td colspan="2"><input type="text" name="category_menu_module[<?php echo $module_row; ?>][module_name]; ?>]" value="<?php echo isset($module['module_name']) ? $module['module_name'] : ''; ?>" size="28" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_title; ?></td>
              <td style="width: 290px;"><?php foreach ($languages as $language) { ?>
                <input type="text" name="category_menu_module[<?php echo $module_row; ?>][menu_title][<?php echo $language['language_id']; ?>]" value="<?php echo isset($module['menu_title'][$language['language_id']]) ? $module['menu_title'][$language['language_id']] : ''; ?>" size="28"/><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="margin: 0 14px 0 -24px;" />
                <select name="category_menu_module[<?php echo $module_row; ?>][title_status][<?php echo $language['language_id']; ?>]">
                  <?php if (isset($module['title_status'][$language['language_id']]) ? $module['title_status'][$language['language_id']] : '1') { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select><br />
                <?php } ?></td>
              <td><a onclick="$('#custom-style-<?php echo $module_row; ?>').toggle();" style="color: #000; text-decoration: none; border-bottom: 1px dotted #FF802B;"><?php echo $text_custom_style; ?></a></td>
            </tr>
            <tr id="custom-style-<?php echo $module_row; ?>" style="display: none;">
              <td><?php echo $entry_title_style; ?><span class="help"><?php echo $text_example; ?></span></td>
              <td colspan="2"><textarea name="category_menu_module[<?php echo $module_row; ?>][title_style]" cols="49" rows="5" style="overflow:auto;"><?php echo isset($module['title_style']) ? $module['title_style'] : ''; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $entry_style; ?></td>
              <td colspan="2"><select name="category_menu_module[<?php echo $module_row; ?>][style]" class="test1">
                  <?php if (!empty($module['style']) && $module['style'] == 'accordion') { ?>
                  <option value="accordion" selected="selected"><?php echo $text_accordion; ?></option>
                  <?php } else { ?>
                  <option value="accordion"><?php echo $text_accordion; ?></option>
                  <?php } ?>
                  <?php if (!empty($module['style']) && $module['style'] == 'collapsible') { ?>
                  <option value="collapsible" selected="selected"><?php echo $text_collapsible; ?></option>
                  <?php } else { ?>
                  <option value="collapsible"><?php echo $text_collapsible; ?></option>
                  <?php } ?>
                  <?php if (!empty($module['style']) && $module['style'] == 'popup') { ?>
                  <option value="popup" selected="selected"><?php echo $text_popup; ?></option>
                  <?php } else { ?>
                  <option value="popup"><?php echo $text_popup; ?></option>
                  <?php } ?>
                  <?php if (!empty($module['style']) && $module['style'] == 'box') { ?>
                  <option value="box" selected="selected"><?php echo $text_default; ?></option>
                  <?php } else { ?>
                  <option value="box"><?php echo $text_default; ?></option>
                  <?php } ?>
                </select><span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $entry_toggle; ?>
                <select name="category_menu_module[<?php echo $module_row; ?>][toggle]">
                  <?php if (!empty($module['toggle']) && $module['toggle'] == 'btn') { ?>
                  <option value="btn" selected="selected"><?php echo $text_button; ?></option>
                  <?php } else { ?>
                  <option value="btn"><?php echo $text_button; ?></option>
                  <?php } ?>
                  <?php if (!empty($module['toggle']) && $module['toggle'] == 'link') { ?>
                  <option value="link" selected="selected"><?php echo $text_name; ?></option>
                  <?php } else { ?>
                  <option value="link"><?php echo $text_name; ?></option>
                  <?php } ?>
                </select>
                </span></td>
            </tr>
            <tr>
              <td><?php echo $entry_items; ?></td>
              <td colspan="2"><div id="tabs-<?php echo $module_row; ?>" class="htabs" style="width:auto; padding:0;"><a href="#tab-categories-<?php echo $module_row; ?>"><?php echo $tab_categories; ?></a><a href="#tab-manufacturers-<?php echo $module_row; ?>"><?php echo $tab_manufacturers; ?></a><a href="#tab-informations-<?php echo $module_row; ?>"><?php echo $tab_information; ?></a><a href="#tab-links-<?php echo $module_row; ?>"><?php echo $tab_links; ?></a></div>
                <div id="tab-categories-<?php echo $module_row; ?>">
                  <div class="scrollbox" style="width:auto; height: 132px; margin-bottom: 15px; overflow-y: auto; border: none;">
                    <?php $class = 'odd'; ?>
                    <?php foreach ($categories as $category) { ?>
                    <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                    <div class="<?php echo $class; ?>">
                      <?php if (!empty($module['category_selected']) && in_array($category['category_id'], $module['category_selected'])) { ?>
                      <input type="checkbox" name="category_menu_module[<?php echo $module_row; ?>][category_selected][]" value="<?php echo $category['category_id']; ?>" checked="checked" />
                      <?php echo $category['name']; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="category_menu_module[<?php echo $module_row; ?>][category_selected][]" value="<?php echo $category['category_id']; ?>" />
                      <?php echo $category['name']; ?>
                      <?php } ?>
                    </div>
                    <?php } ?>
                  </div>
                  <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a> </div>
                <div id="tab-manufacturers-<?php echo $module_row; ?>">
                  <div class="scrollbox" style="width:auto; height: 132px; margin-bottom: 15px; overflow-y: auto; border: none;">
                    <?php $class = 'odd'; ?>
                    <?php foreach ($manufacturers as $manufacturer) { ?>
                    <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                    <div class="<?php echo $class; ?>">
                      <?php if (!empty($module['manufacturer_selected']) && in_array($manufacturer['manufacturer_id'], $module['manufacturer_selected'])) { ?>
                      <input type="checkbox" name="category_menu_module[<?php echo $module_row; ?>][manufacturer_selected][]" value="<?php echo $manufacturer['manufacturer_id']; ?>" checked="checked" />
                      <?php echo $manufacturer['name']; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="category_menu_module[<?php echo $module_row; ?>][manufacturer_selected][]" value="<?php echo $manufacturer['manufacturer_id']; ?>" />
                      <?php echo $manufacturer['name']; ?>
                      <?php } ?>
                    </div>
                    <?php } ?>
                  </div>
                  <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a> </div>
                <div id="tab-informations-<?php echo $module_row; ?>">
                  <div class="scrollbox" style="width:auto; height: 132px; margin-bottom: 15px; overflow-y: auto; border: none;">
                    <?php $class = 'odd'; ?>
                    <?php foreach ($informations as $information) { ?>
                    <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                    <div class="<?php echo $class; ?>">
                      <?php if (!empty($module['information_selected']) && in_array($information['information_id'], $module['information_selected'])) { ?>
                      <input type="checkbox" name="category_menu_module[<?php echo $module_row; ?>][information_selected][]" value="<?php echo $information['information_id']; ?>" checked="checked" />
                      <?php echo $information['title']; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="category_menu_module[<?php echo $module_row; ?>][information_selected][]" value="<?php echo $information['information_id']; ?>" />
                      <?php echo $information['title']; ?>
                      <?php } ?>
                    </div>
                    <?php } ?>
                  </div>
                  <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a> </div>
                <div id="tab-links-<?php echo $module_row; ?>">
                  <table id="links-<?php echo $module_row; ?>" class="list">
                    <thead>
                      <tr>
                        <td class="left"><?php echo $entry_link_title; ?></td>
                        <td class="left"><?php echo $entry_link; ?></td>
                        <td></td>
                      </tr>
                    </thead>
                    <?php if (isset($module['custom_link'])) { ?>
                    <?php foreach ($module['custom_link'] as $custom_link) { ?>
                    <tbody id="link-row-<?php echo $module_row; ?>-<?php echo $custom_link_row; ?>">
                      <tr>
                        <td class="left"><?php foreach ($languages as $language) { ?>
                          <input type="text" name="category_menu_module[<?php echo $module_row; ?>][custom_link][<?php echo $custom_link_row; ?>][link_title][<?php echo $language['language_id']; ?>]" value="<?php echo isset($custom_link['link_title'][$language['language_id']]) ? $custom_link['link_title'][$language['language_id']] : ''; ?>" size="26" />
                          <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
                          <?php } ?></td>
                        <td class="left"><?php foreach ($languages as $language) { ?>
                          <input type="text" name="category_menu_module[<?php echo $module_row; ?>][custom_link][<?php echo $custom_link_row; ?>][href][<?php echo $language['language_id']; ?>]" value="<?php echo isset($custom_link['href'][$language['language_id']]) ? $custom_link['href'][$language['language_id']] : ''; ?>" size="26" />
                          <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
                          <?php } ?></td>
                        <td class="left"><a onclick="$('#link-row-<?php echo $module_row; ?>-<?php echo $custom_link_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
                      </tr>
                    </tbody>
                    <?php $custom_link_row++; ?>
                    <?php } ?>
                    <?php } ?>
                    <tfoot>
                      <tr>
                        <td colspan="2"></td>
                        <td class="left"><a onclick="addLink('<?php echo $module_row; ?>');" class="button"><?php echo $button_add_link; ?></a></td>
                      </tr>
                    </tfoot>
                  </table>
                </div></td>
            </tr>
            <tr>
              <td><?php echo $entry_image; ?></td>
              <td colspan="2"><input type="text" name="category_menu_module[<?php echo $module_row; ?>][width]" value="<?php echo isset($module['width']) ? $module['width'] : '172'; ?>" size="2"/>
                <span> x </span>
                <input type="text" name="category_menu_module[<?php echo $module_row; ?>][height]" value="<?php echo isset($module['height']) ? $module['height'] : '90'; ?>" size="1" />&nbsp;&nbsp;
                <select name="category_menu_module[<?php echo $module_row; ?>][image]">
                  <?php if (!empty($module['image'])) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_layout; ?></td>
              <td colspan="2"><select name="category_menu_module[<?php echo $module_row; ?>][layout_id]">
                  <?php foreach ($layouts as $layout) { ?>
                  <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                  <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_position; ?></td>
              <td colspan="2"><select name="category_menu_module[<?php echo $module_row; ?>][position]">
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
            </tr>
            <tr>
              <td><?php echo $entry_status; ?></td>
              <td colspan="2"><select name="category_menu_module[<?php echo $module_row; ?>][status]">
                  <?php if ($module['status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_sort_order; ?></td>
              <td colspan="2"><input type="text" name="category_menu_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
            </tr>
          </table>
        </div>
        <?php $module_row++; ?>
        <?php } ?>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;

function addModule() {  
  html  = '<div id="tab-module-' + module_row + '" class="vtabs-content">';
  html += '  <table class="form" style="border-collapse: separate; border-spacing: 0;">';
  html += '    <tr>';
  html += '      <td><?php echo $entry_module_name; ?></td>';
  html += '      <td colspan="2"><input type="text" name="category_menu_module[' + module_row + '][module_name]; ?>]" value="" size="28" /></td>';
  html += '    </tr>';
  html += '    <tr>';
  html += '      <td><?php echo $entry_title; ?></td>';
  html += '      <td style="width: 290px;">';
  <?php foreach ($languages as $language) { ?>
  html += '        <input type="text" name="category_menu_module[' + module_row + '][menu_title][<?php echo $language['language_id']; ?>]" value="" size="28" /><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="margin: 0 14px 0 -24px;" />';
  html += '        <select name="category_menu_module[' + module_row + '][title_status][<?php echo $language['language_id']; ?>]">';
  html += '        <option value="1"><?php echo $text_enabled; ?></option>';
  html += '        <option value="0"><?php echo $text_disabled; ?></option>';
  html += '      </select><br />';
  <?php } ?>
  html += '      </td>';
  html += '      <td><a onclick="$(\'#custom-style-' + module_row + '\').toggle();" style="color: #000; text-decoration: none; border-bottom: 1px dotted #FF802B;"><?php echo $text_custom_style; ?></a></td>';
  html += '    </tr>';
  html += '    <tr id="custom-style-' + module_row + '" style="display: none;">';
  html += '      <td><?php echo $entry_title_style; ?><span class="help"><?php echo $text_example; ?></span></td>';
  html += '      <td colspan="2"><textarea name="category_menu_module[' + module_row + '][title_style]" cols="49" rows="5" style="overflow:auto;"></textarea></td>';
  html += '    </tr>';
  html += '    <tr>';
  html += '      <td><?php echo $entry_style; ?></td>';
  html += '      <td colspan="2"><select name="category_menu_module[' + module_row + '][style]">';
  html += '        <option value="accordion"><?php echo $text_accordion; ?></option>';
  html += '        <option value="collapsible"><?php echo $text_collapsible; ?></option>';
  html += '        <option value="popup"><?php echo $text_popup; ?></option>';
  html += '        <option value="box"><?php echo $text_default; ?></option>';
  html += '      </select>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $entry_toggle; ?>';
  html += '      <select name="category_menu_module[' + module_row + '][toggle]">';
  html += '        <option value="btn"><?php echo $text_button; ?></option>';
  html += '        <option value="link"><?php echo $text_name; ?></option>';
  html += '      </select></td>';
  html += '    </tr>';
  html += '    <tr>';
  html += '      <td><?php echo $entry_items; ?></td>';
  html += '      <td colspan="2"><div id="tabs-' + module_row + '" class="htabs" style="width:auto; padding:0;">';
  html += '      <a href="#tab-categories-'+ module_row + '"><?php echo $tab_categories; ?></a><a href="#tab-manufacturers-' + module_row + '"><?php echo $tab_manufacturers; ?></a><a href="#tab-informations-' + module_row + '"><?php echo $tab_information; ?></a><a href="#tab-links-' + module_row + '"><?php echo $tab_links; ?></a>';
  html += '        </div>';
  html += '        <div id="tab-categories-' + module_row + '">';
  html += '          <div class="scrollbox" style="width:auto; height: 132px; margin-bottom: 15px; overflow-y: auto; border: none;">';
  <?php $class = 'odd'; ?>
  <?php foreach ($categories as $category) { ?>
  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
  html += '          <div class="<?php echo $class; ?>">';
  html += '          <input type="checkbox" name="category_menu_module[' + module_row + '][category_selected][]" value="<?php echo $category['category_id']; ?>" />';
  html += '          <?php echo addslashes($category['name']); ?>';
  html += '          </div>';
  <?php } ?>
  html += '        </div><a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', false);"><?php echo $text_unselect_all; ?></a></div>';
  html += '        <div id="tab-manufacturers-' + module_row + '">';
  html += '          <div class="scrollbox" style="width:auto; height: 132px; margin-bottom: 15px; overflow-y: auto; border: none;">';
  <?php $class = 'odd'; ?>
  <?php foreach ($manufacturers as $manufacturer) { ?>
  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
  html += '          <div class="<?php echo $class; ?>">';
  html += '          <input type="checkbox" name="category_menu_module[' + module_row + '][manufacturer_selected][]" value="<?php echo $manufacturer['manufacturer_id']; ?>" />';
  html += '          <?php echo addslashes($manufacturer['name']); ?>';
  html += '          </div>';
  <?php } ?>
  html += '        </div><a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', false);"><?php echo $text_unselect_all; ?></a></div>';
  html += '        <div id="tab-informations-' + module_row + '">';
  html += '          <div class="scrollbox" style="width:auto; height: 132px; margin-bottom: 15px; overflow-y: auto; border: none;">';
  <?php $class = 'odd'; ?>
  <?php foreach ($informations as $information) { ?>
  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
  html += '          <div class="<?php echo $class; ?>">';
  html += '          <input type="checkbox" name="category_menu_module[' + module_row + '][information_selected][]" value="<?php echo $information['information_id']; ?>" />';
  html += '          <?php echo addslashes($information['title']); ?>';
  html += '          </div>';
  <?php } ?>
  html += '        </div><a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', false);"><?php echo $text_unselect_all; ?></a></div>';
  html += '        <div id="tab-links-'+ module_row + '">';
  html += '          <table id="links-'+ module_row + '" class="list">';
  html += '            <thead>';
  html += '              <tr>';
  html += '                <td class="left"><?php echo $entry_link_title; ?></td>';
  html += '                <td class="left"><?php echo $entry_link; ?></td>';
  html += '                <td></td>';
  html += '              </tr>';
  html += '            </thead>';
  html += '            <tfoot>';
  html += '              <tr>';
  html += '                <td colspan="2"></td>';
  html += '                <td class="left"><a onclick="addLink('+ module_row +');" class="button"><?php echo $button_add_link; ?></a></td>';
  html += '              </tr>';
  html += '            </tfoot>';
  html += '          </table>';
  html += '        </div>';
  html += '      </td>';
  html += '    </tr>';
  html += '    <tr>';
  html += '      <td><?php echo $entry_image; ?></td>';
  html += '      <td colspan="2"><input type="text" name="category_menu_module[' + module_row + '][width]" value="172" size="2" /><span> x </span><input type="text" name="category_menu_module[' + module_row + '][height]" value="90" size="1" />&nbsp;&nbsp;';
  html += '      <select name="category_menu_module[' + module_row + '][image]">';
  html += '        <option value="1"><?php echo $text_enabled; ?></option>';
  html += '        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>';
  html += '      </select></td>';
  html += '    </tr>';
  html += '    <tr>';
  html += '      <td><?php echo $entry_layout; ?></td>';
  html += '      <td colspan="2"><select name="category_menu_module[' + module_row + '][layout_id]">';
  <?php foreach ($layouts as $layout) { ?>
  html += '           <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
  <?php } ?>
  html += '      </select></td>';
  html += '    </tr>';
  html += '    <tr>';
  html += '      <td><?php echo $entry_position; ?></td>';
  html += '      <td colspan="2"><select name="category_menu_module[' + module_row + '][position]">';
  html += '        <option value="content_top"><?php echo $text_content_top; ?></option>';
  html += '        <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
  html += '        <option value="column_left"><?php echo $text_column_left; ?></option>';
  html += '        <option value="column_right"><?php echo $text_column_right; ?></option>';
  html += '      </select></td>';
  html += '    </tr>';
  html += '    <tr>';
  html += '      <td><?php echo $entry_status; ?></td>';
  html += '      <td colspan="2"><select name="category_menu_module[' + module_row + '][status]">';
  html += '        <option value="1"><?php echo $text_enabled; ?></option>';
  html += '        <option value="0"><?php echo $text_disabled; ?></option>';
  html += '      </select></td>';
  html += '    </tr>';
  html += '    <tr>';
  html += '      <td><?php echo $entry_sort_order; ?></td>';
  html += '      <td colspan="2"><input type="text" name="category_menu_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
  html += '    </tr>';
  html += '  </table>';
  html += '</div>';

  $('#form').append(html);
  $('#tabs-' + module_row + ' a').tabs();

  $('#module-add').before('<a href="#tab-module-' + module_row + '" id="module-' + module_row + '"><?php echo $tab_module; ?> ' + module_row + '&nbsp;<img src="view/image/delete.png" alt="" onclick="$(\'.vtabs a:first\').trigger(\'click\'); $(\'#module-' + module_row + '\').remove(); $(\'#tab-module-' + module_row + '\').remove(); return false;" /></a>');

  $('.vtabs a').tabs();
  $('#module-' + module_row).trigger('click');

  module_row++;
}
//--></script> 
<script type="text/javascript"><!--
var custom_link_row = <?php echo $custom_link_row; ?>;

function addLink(module_row) {
  html  = '<tbody id="link-row-' + module_row + '-' + custom_link_row + '">';
  html += '  <tr>';
  html += '    <td class="left">';
  <?php foreach ($languages as $language) { ?>
  html += '      <input type="text" name="category_menu_module[' + module_row + '][custom_link][' + custom_link_row + '][link_title][<?php echo $language['language_id']; ?>]" value="" size="26" /> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />';
    <?php } ?>
  html += '    </td>';
  html += '    <td class="left">';
  <?php foreach ($languages as $language) { ?>
  html += '      <input type="text" name="category_menu_module[' + module_row + '][custom_link][' + custom_link_row + '][href][<?php echo $language['language_id']; ?>]" value="" size="26" /> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />';
    <?php } ?>
  html += '    </td>';
  html += '    <td class="left"><a onclick="$(\'#link-row-' + module_row + '-' + custom_link_row  + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
  html += '  </tr>';
  html += '</tbody>';

  $('#links-' + module_row + ' tfoot').before(html);

  custom_link_row++;
}
//--></script> 
<script type="text/javascript"><!--
$('.vtabs a').tabs();
//--></script> 
<script type="text/javascript"><!--
<?php $module_row = 1; ?>
<?php foreach ($modules as $module) { ?>
$('#tabs-<?php echo $module_row; ?> a').tabs();
<?php $module_row++; ?>
<?php } ?> 
//--></script> 
<?php echo $footer; ?>