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
      <h1><img src="view/image/shipping.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons">
      <a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a>
      <a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a>
      </div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <!-- новый интерфейс -->
        <div id="tabs" class="htabs">
					<a href="#tab-main"><?php echo $tab_main; ?></a>
          <a href="#tab-data"><?php echo $tab_data; ?></a>
					<a href="#tab-markup"><?php echo $tab_markup; ?></a>
          <a href="#tab-auth"><?php echo $tab_auth; ?></a>
				</div>
        <div id="tab-data">
          <table class="form">
						<tbody>
              <tr class="customcdek-default-size size<?php if (empty($customcdek_default_size['type']) || $customcdek_default_size['type'] == 'volume') echo ' hidden'; ?>">
                <td><label for="customcdek-default-size-type-size-a"><span class="required">*</span> <?php echo $entry_size; ?></label></td>
                <td>
                  <input id="customcdek-default-size-type-size-a" type="text" name="customcdek_default_size[size_a]" value="<?php if (!empty($customcdek_default_size['size_a'])) echo $customcdek_default_size['size_a']; ?>" size="2" /> x 
                  <input type="text" name="customcdek_default_size[size_b]" value="<?php if (!empty($customcdek_default_size['size_b'])) echo $customcdek_default_size['size_b']; ?>" size="2" /> x 
                  <input type="text" name="customcdek_default_size[size_c]" value="<?php if (!empty($customcdek_default_size['size_c'])) echo $customcdek_default_size['size_c']; ?>" size="2" />
                  <?php if (isset($error['customcdek_default_size']['size'])) { ?>
                  <span class="error"><?php echo $error['customcdek_default_size']['size']; ?></span>
                  <?php } ?>
                </td>
              </tr>
              <tr>
                <td><label for="customcdek-default-weight"><span class="required">*</span> <?php echo $entry_default_weight; ?></label></td>
                <td>
                  <input id="customcdek-default-weight" type="text" name="customcdek_default_weight" value="<?php if (!empty($customcdek_default_weight)) echo $customcdek_default_weight; ?>" size="1" /> кг.
                  <?php if (isset($error['customcdek_default_weight'])) { ?>
                  <span class="error"><?php echo $error['customcdek_default_weight']; ?></span>
                  <?php } ?>
                </td>
              </tr>
              <tr>
								<td><label for="customcdek-postalcode"><?php echo $entry_postalcode; ?></label></td>
								<td><input id="customcdek-postalcode" type="text" name="customcdek_postalcode" value="<?php echo $customcdek_postalcode; ?>" />
                <?php if (isset($error['customcdek_postalcode'])) { ?>
                  <span class="error"><?php echo $error['customcdek_postalcode']; ?></span>
                  <?php } ?></td>
                
							</tr>
						</tbody>
          </table>
          <p class="help"><?php echo $text_data_category_help; ?></p>
					<table class="list" id="category_data">
						<thead>
							<tr>
								<td class="left"><?php echo $column_category; ?></td>
								<td class="left"><?php echo $column_size; ?></td>
								<td class="left"><?php echo $column_weight; ?></td>
								<td></td>
							</tr>
						</thead>
						<tbody>
							<?php if ($customcdek_category_data) { ?>
							<?php foreach ($customcdek_category_data as $category_data_row => $category_data) { ?>
							<tr id="category-row<?php echo $category_data_row; ?>">
								<td class="left">
									<input type="text" name="customcdek_category_data[<?php echo $category_data_row; ?>][category_id]" value="<?php echo $category_data['category_id']; ?>" size="3" />
									<?php if (isset($error['customcdek_category_data'][$category_data_row]['category_id'])) { ?>
									<span class="error"><?php echo $error['customcdek_category_data'][$category_data_row]['category_id']; ?></span>
									<?php } ?>
                </td>
								<td class="left">
                    <input type="text" name="customcdek_category_data[<?php echo $category_data_row; ?>][size_a]" value="<?php echo $category_data['size_a']; ?>" size="2" /> x 
                    <input type="text" name="customcdek_category_data[<?php echo $category_data_row; ?>][size_b]" value="<?php echo $category_data['size_b']; ?>" size="2" /> x 
                    <input type="text" name="customcdek_category_data[<?php echo $category_data_row; ?>][size_c]" value="<?php echo $category_data['size_c']; ?>" size="2" />
                    <?php if (isset($error['customcdek_category_data'][$category_data_row]['size'])) { ?>
                    <span class="error"><?php echo $error['customcdek_category_data'][$category_data_row]['size']; ?></span>
                    <?php } ?>
								</td>
								<td class="left">
                  <input type="text" name="customcdek_category_data[<?php echo $category_data_row; ?>][weight]" value="<?php echo $category_data['weight']; ?>" size="3" />
									<?php if (isset($error['customcdek_category_data'][$category_data_row]['weight'])) { ?>
									<span class="error"><?php echo $error['customcdek_category_data'][$category_data_row]['weight']; ?></span>
									<?php } ?>
								</td>
								<td class="left"><a onclick="$('#category-row<?php echo $category_data_row; ?>').remove();return FALSE;" class="button"><?php echo $button_remove; ?></a></td>
							</tr>
							<?php } ?>
							<?php } ?>
						</tbody>
					</table>
					<a class="button" onclick="addCategoryData()"><?php echo $button_insert; ?></a>
        </div>
        <div id="tab-auth">
					<table class="form">
						<tbody>
							<tr>
								<td><label for="customcdek-login"><?php echo $entry_login; ?></label></td>
								<td><input id="customcdek-login" type="text" name="customcdek_login" value="<?php echo $customcdek_login; ?>" /></td>
							</tr>
							<tr>
								<td><label for="customcdek-password"><?php echo $entry_password; ?></label></td>
								<td><input id="customcdek-password" type="text" name="customcdek_password" value="<?php echo $customcdek_password; ?>" />
								</td>
							</tr>
						</tbody>
					</table>
				</div>
        <div id="tab-main">
					<table class="form">
          <tr>
								<td><label for="customcdek-cdek-log"><?php echo $entry_log; ?></label></td>
								<td>
									<select id="customcdek-cdek-log" name="customcdek_log">
										<?php foreach($boolean_variables as $key => $variable) { ?>
										<option <?php if ($customcdek_log == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
										<?php } ?>
									</select>
								</td>
							</tr>
          <tr>
            <td><?php echo $customcdek_status_text; ?></td>
            <td><select name="customcdek_status">
                <?php if ($customcdek_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_customcdek_use_fallback; ?></td>
            <td><select name="customcdek_use_fallback">
                <?php if ($customcdek_use_fallback) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </td>
          </tr>
          <tr>
              <td><?php echo $customcdek_pvz; ?></td>
              <td><select name="customcdek_pvz_activation">
                <?php if ($customcdek_pvz_activation) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
              <td><?php echo $customcdek_door; ?></td>
              <td><select name="customcdek_door_activation">
                <?php if ($customcdek_door_activation) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $customcdek_dressingroom; ?></td>
            <td><select name="customcdek_dressingroom_activation">
              <?php if ($customcdek_dressingroom_activation) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></td>
          </tr>
          <tr>
            <td><label for="customcdek-timeout"><?php echo $entry_timeout; ?></label></td>
            <td>
              <input id="customcdek-timeout" type="text" name="customcdek_timeout" value="<?php echo $customcdek_timeout; ?>" size="1" />									
              <?php if (isset($error['customcdek_timeout'])) { ?>
              <span class="error"><?php echo $error['customcdek_timeout']; ?></span>
              <?php } ?>
            </td>
          </tr>
          <tr>
            <td><label for="customcdek-sort-order"><?php echo $entry_sort_order; ?></label></td>
            <td>
              <input id="customcdek-sort-order" type="text" name="customcdek_sort_order" value="<?php echo $customcdek_sort_order; ?>" size="1" />									
              <?php if (isset($error['customcdek_sort_order'])) { ?>
              <span class="error"><?php echo $error['customcdek_sort_order']; ?></span>
              <?php } ?>
            </td>
          </tr>
          </table>
        </div>
        <div id="tab-markup">
        <table class="form">
        <tr>
            <td>
          <text>
            <font size="3">
            <b><?php echo $text_markup_full; ?></b>
            </font>
          </text>
          </td>
            </tr>
          <tr>
            <td><?php echo $entry_markup_door; ?></td>
            <td>
              <input type="text" name="customcdek_markup_full_door" value="<?php echo $customcdek_markup_full_door; ?>" />
              <?php if (isset($error['customcdek_markup_full_door'])) { ?>
                <span class="error"><?php echo $error['customcdek_markup_full_door']; ?></span>
              <?php } ?>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_markup_pvz; ?></td>
            <td>
              <input type="text" name="customcdek_markup_full_pvz" value="<?php echo $customcdek_markup_full_pvz; ?>" />
              <?php if (isset($error['customcdek_markup_full_pvz'])) { ?>
                <span class="error"><?php echo $error['customcdek_markup_full_pvz']; ?></span>
              <?php } ?>
            </td>
          </tr>
          <tr>
            <td>
          <text>
            <font size="3">
            <b><?php echo $text_markup_part; ?></b>
            </font>
          </text>
          </td>
            </tr>
          <tr>
            <td><?php echo $entry_markup_door; ?></td>
            <td>
              <input type="text" name="customcdek_markup_part_door" value="<?php echo $customcdek_markup_part_door; ?>" />
              <?php if (isset($error['customcdek_markup_part_door'])) { ?>
                <span class="error"><?php echo $error['customcdek_markup_part_door']; ?></span>
              <?php } ?>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_markup_pvz; ?></td>
            <td>
              <input type="text" name="customcdek_markup_part_pvz" value="<?php echo $customcdek_markup_part_pvz; ?>" />
              <?php if (isset($error['customcdek_markup_part_pvz'])) { ?>
                <span class="error"><?php echo $error['customcdek_markup_part_pvz']; ?></span>
              <?php } ?>
            </td>
          </tr>
          <tr>
          <td>
          <text>
            <font size="3">
          <b><?php echo $text_markup_option; ?></b>
            </font>
          </text>
          </td>
            </tr>
          <tr>
            <td><?php echo $entry_markup_dressingroom; ?></td>
            <td>
              <input type="text" name="customcdek_markup_dressingroom" value="<?php echo $customcdek_markup_dressingroom; ?>" />
              <?php if (isset($error['customcdek_markup_dressingroom'])) { ?>
                <span class="error"><?php echo $error['customcdek_markup_dressingroom']; ?></span>
              <?php } ?>
            </td>
          </tr>

        </table>
        <hr>
        <p><strong><?php echo $text_markup_declared_value_help; ?></strong></p>
        <table class="list" id="markup_declared_value">
          <thead>
            <tr>
              <td class="left"><?php echo $column_customer_group; ?></td>
              <td class="left"><?php echo $column_markup; ?></td>
              <td></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($customcdek_markup_declared_value) { ?>
            <?php foreach ($customcdek_markup_declared_value as $row => $data) { ?>
            <tr id="markup-declared-value-row<?php echo $row; ?>">
              <td class="left">
                <select name="customcdek_markup_declared_value[<?php echo $row; ?>][customer_group_id]">
                  <?php foreach($customer_groups as $customer_group) { ?>
                  <option <?php if ($data['customer_group_id'] == $customer_group['customer_group_id']) echo 'selected="selected"'; ?> value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                  <?php } ?>
                </select>
                <?php if (isset($error['customcdek_markup_declared_value'][$row]['customer_group_id'])) { ?>
                <span class="error"><?php echo $error['customcdek_markup_declared_value'][$row]['customer_group_id']; ?></span>
                <?php } ?>
              </td>
              <td class="left">
                <input type="text" name="customcdek_markup_declared_value[<?php echo $row; ?>][value]" value="<?php echo $data['value']; ?>" size="3" />
                <?php if (isset($error['customcdek_markup_declared_value'][$row]['value'])) { ?>
                <span class="error"><?php echo $error['customcdek_markup_declared_value'][$row]['value']; ?></span>
                <?php } ?>
              </td>
              <td class="left"><a onclick="$('#markup-declared-value-row<?php echo $row; ?>').remove();return FALSE;" class="button"><?php echo $button_remove; ?></a></td>
            </tr>
            <?php } ?>
            <?php } ?>
          </tbody>
        </table>
        <a class="button" onclick="addDeclaredValueMarkup()"><?php echo $button_insert; ?></a>
        </div>
      <!-- новый интерфейс -->

        
        
      </form>
    </div>
  </div>
</div>
<script>
  (function() {
    $('#tabs a').tabs();
  })();
  function addCategoryData() {
    var category_data_row = $('#category_data').find('tbody tr').length;
    var html = [
      '<tr id="category-row' + category_data_row + '">',
        '<td class="left">',
          '<input type="text" name="customcdek_category_data[' + category_data_row + '][category_id]" size="3" />',
        '</td>',
        '<td class="left">',
          '<input type="text" name="customcdek_category_data[' + category_data_row + '][size_a]" size="2" /> x',
          '<input type="text" name="customcdek_category_data[' + category_data_row + '][size_b]" size="2" /> x',
          '<input type="text" name="customcdek_category_data[' + category_data_row + '][size_c]" size="2" />',
        '<td class="left">',
          '<input type="text" name="customcdek_category_data[' + category_data_row + '][weight]" size="3" />',
        '</td>',
        '<td class="left"><a onclick="$(\'#category-row' + category_data_row + '\').remove();return FALSE;" class="button"><?php echo $button_remove; ?></a></td>',
      '</tr>'
    ].join('\n');
    $('#category_data').find('tbody').append(html);
  }
  function addDeclaredValueMarkup() {
    var customer_groups = JSON.parse('<?php echo json_encode($customer_groups); ?>');
    var row = $('#markup_declared_value').find('tbody tr').length;
    var html = [
      '<tr id="markup-declared-value-row' + row + '">',
        '<td class="left">',
          '<select name="customcdek_markup_declared_value[' + row + '][customer_group_id]">',
    ];
    var options = customer_groups.reduce(function(carry, item) {
      carry.push(
        [
          '<option value="' + item.customer_group_id + '">',
          item.name,
          '</option>'
        ].join('')
      );
      return carry;
    }, []).join('\n');
    html = html.concat([
            options,
          '</select>',
        '</td>',
        '<td class="left">',
          '<input type="text" name="customcdek_markup_declared_value[' + row + '][value]" size="3" />',
        '</td>',
        '<td class="left"><a onclick="$(\'#markup-declared-value-row' + row + '\').remove();return FALSE;" class="button"><?php echo $button_remove; ?></a></td>',
      '</tr>'
    ]);
    var html = html.join('\n');
    $('#markup_declared_value').find('tbody').append(html);
  }
</script>
<?php echo $footer; ?> 