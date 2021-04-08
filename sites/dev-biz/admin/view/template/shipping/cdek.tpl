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
	<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading">
			<h1><img src="view/image/shipping-cdek.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons">
				<a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a>
				<a onclick="$('#form input[name=apply]').val(1); $('#form').submit();" class="button"><?php echo $button_apply; ?></a>
				<a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a>
			</div>
		</div>
		<div class="content">
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<div id="tabs" class="htabs">
					<a href="#tab-general"><?php echo $tab_general; ?></a>
					<a href="#tab-data"><?php echo $tab_data; ?></a>
					<a href="#tab-auth"><?php echo $tab_auth; ?></a>
					<a href="#tab-tariff"><?php echo $tab_tariff; ?></a>
					<a href="#tab-discount"><?php echo $tab_discount; ?></a>
					<a href="#tab-package"><?php echo $tab_package; ?></a>
					<a href="#tab-additional"><?php echo $tab_additional; ?></a>
					<a href="#tab-empty"><?php echo $tab_empty; ?></a>
				</div>
				<div id="tab-general">
					<table class="form">
						<tbody>
							<tr>
								<td><?php echo $entry_title; ?></td>
								<td>
									<?php foreach ($languages as $language) { ?>
									<input type="text" name="cdek_title[<?php echo $language['language_id']; ?>]" value="<?php echo isset($cdek_title[$language['language_id']]) ? $cdek_title[$language['language_id']] : ''; ?>" />
									<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
									<?php } ?>
								</td>
							</tr>
							<tr>
								<td><label for="cdek-tax-class-id"><?php echo $entry_tax_class; ?></label></td>
								<td>
									<select id="cdek-tax-class-id" name="cdek_tax_class_id">
										<option value="0"><?php echo $text_none; ?></option>
										<?php foreach ($tax_classes as $tax_class) { ?>
										<?php if ($tax_class['tax_class_id'] == $cdek_tax_class_id) { ?>
										<option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
										<?php } else { ?>
										<option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
										<?php } ?>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td><label for="cdek-cdek-log"><?php echo $entry_log; ?></label></td>
								<td>
									<select id="cdek-cdek-log" name="cdek_log">
										<?php foreach($boolean_variables as $key => $variable) { ?>
										<option <?php if ($cdek_log == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td><?php echo $entry_store; ?></td>
								<td>
									<div class="scrollbox">
										<?php $class = 'even'; ?>
										<?php foreach ($stores as $store) { ?>
										<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
										<div class="<?php echo $class; ?>">
										<input type="checkbox" name="cdek_store[]" value="<?php echo $store['store_id']; ?>" <?php  if (isset($cdek_store) && in_array($store['store_id'], $cdek_store)) echo 'checked="checked"'; ?> />
										<?php echo $store['name']; ?>
										
										</div>
										<?php } ?>
									</div>
								</td>
							</tr>
							<tr>
								<td><label for="cdek-status"><?php echo $entry_status; ?></label></td>
								<td>
									<select id="cdek-status" name="cdek_status">
										<?php foreach (array($text_disabled, $text_enabled) as $key => $value) { ?>
										<option value="<?php echo $key; ?>" <?php if ($cdek_status == $key) echo 'selected="selected"'; ?>><?php echo $value; ?></option>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td><label for="cdek-sort-order"><?php echo $entry_sort_order; ?></label></td>
								<td>
									<input id="cdek-sort-order" type="text" name="cdek_sort_order" value="<?php echo $cdek_sort_order; ?>" size="1" />									
									<?php if (isset($error['cdek_sort_order'])) { ?>
									<span class="error"><?php echo $error['cdek_sort_order']; ?></span>
									<?php } ?>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div id="tab-data">
					<table class="form">
						<tbody>
							<tr class="parent">
								<td><label for="cdek-default-size"><?php echo $entry_default_size; ?></label></td>
								<td colspan="2">
									<select class="slider" id="cdek-default-size" name="cdek_default_size[use]">
										<?php foreach($boolean_variables as $key => $variable) { ?>
										<option <?php if (!empty($cdek_default_size['use']) && $cdek_default_size['use'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr class="children">
								<td colspan="3" class="include">
									<div class="slider-content<?php if (empty($cdek_default_size['use']) || !$cdek_default_size['use']) echo " hidden"; ?>" >
										<table class="form">
											<tbody>
												<tr>
													<td><label for="cdek-default-size-type"><?php echo $entry_default_size_type; ?></label></td>
													<td>
														<select class="slider" id="cdek-default-size-type" name="cdek_default_size[type]">
															<?php foreach($size_types as $key => $size_type) { ?>
															<option <?php if (!empty($cdek_default_size['type']) && $cdek_default_size['type'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $size_type; ?></option>
															<?php } ?>
														</select>
													</td>
												</tr>
												<tr class="cdek-default-size volume<?php if (!empty($cdek_default_size['type']) && $cdek_default_size['type'] == 'size') echo ' hidden'; ?>">
													<td><label for="cdek-default-size-type-volume"><span class="required">*</span> <?php echo $entry_volume; ?></label></td>
													<td>
														<input id="cdek-default-size-type-volume" type="text" name="cdek_default_size[volume]" value="<?php if (!empty($cdek_default_size['volume'])) echo $cdek_default_size['volume']; ?>" size="1" /> м³
														<?php if (isset($error['cdek_default_size']['volume'])) { ?>
														<span class="error"><?php echo $error['cdek_default_size']['volume']; ?></span>
														<?php } ?>
													</td>
												</tr>
												<tr class="cdek-default-size size<?php if (empty($cdek_default_size['type']) || $cdek_default_size['type'] == 'volume') echo ' hidden'; ?>">
													<td><label for="cdek-default-size-type-size-a"><span class="required">*</span> <?php echo $entry_size; ?></label></td>
													<td>
														<input id="cdek-default-size-type-size-a" type="text" placeholder="<?php echo $text_short_length; ?>" name="cdek_default_size[size_a]" value="<?php if (!empty($cdek_default_size['size_a'])) echo $cdek_default_size['size_a']; ?>" size="2" /> x 
														<input type="text" placeholder="<?php echo $text_short_width; ?>" name="cdek_default_size[size_b]" value="<?php if (!empty($cdek_default_size['size_b'])) echo $cdek_default_size['size_b']; ?>" size="2" /> x 
														<input type="text" placeholder="<?php echo $text_short_height; ?>" name="cdek_default_size[size_c]" value="<?php if (!empty($cdek_default_size['size_c'])) echo $cdek_default_size['size_c']; ?>" size="2" />
														<?php if (isset($error['cdek_default_size']['size'])) { ?>
														<span class="error"><?php echo $error['cdek_default_size']['size']; ?></span>
														<?php } ?>
													</td>
												</tr>
												<tr>
													<td><label for="cdek-default-size-work-mode"><?php echo $entry_default_size_work_mode; ?></label></td>
													<td>
														<select class="slider" id="cdek-default-size-work-mode" name="cdek_default_size[work_mode]">
															<?php foreach($default_work_mode as $key => $mode) { ?>
															<option <?php if (!empty($cdek_default_size['work_mode']) && $cdek_default_size['work_mode'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $mode; ?></option>
															<?php } ?>
														</select>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</td>
							</tr>
							<tr class="parent">
								<td><label for="cdek-default-weight"><?php echo $entry_default_weight_use; ?></label></td>
								<td colspan="2">
									<select class="slider" id="cdek-default-weight" name="cdek_default_weight[use]">
										<?php foreach($boolean_variables as $key => $variable) { ?>
										<option <?php if (!empty($cdek_default_weight['use']) && $cdek_default_weight['use'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr class="children">
								<td colspan="3" class="include">
									<div class="slider-content<?php if (empty($cdek_default_weight['use']) || !$cdek_default_weight['use']) echo " hidden"; ?>" >
										<table class="form">
											<tbody>
												<tr>
													<td><label for="cdek-default-weight-value"><span class="required">*</span> <?php echo $entry_default_weight; ?></label></td>
													<td>
														<input id="cdek-default-weight-value" type="text" name="cdek_default_weight[value]" value="<?php if (!empty($cdek_default_weight['value'])) echo $cdek_default_weight['value']; ?>" size="1" /> кг.
														<?php if (isset($error['cdek_default_weight']['value'])) { ?>
														<span class="error"><?php echo $error['cdek_default_weight']['value']; ?></span>
														<?php } ?>
													</td>
												</tr>
												<tr>
													<td><label for="cdek-default-weight-work-mode"><?php echo $entry_default_weight_work_mode; ?></label></td>
													<td>
														<select class="slider" id="cdek-default-weight-work-mode" name="cdek_default_weight[work_mode]">
															<?php foreach($default_work_mode as $key => $mode) { ?>
															<option <?php if (!empty($cdek_default_weight['work_mode']) && $cdek_default_weight['work_mode'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $mode; ?></option>
															<?php } ?>
														</select>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</td>
							</tr>
							<tr>
								<td><label for="cdek-append-day"><?php echo $entry_date_execute; ?></label></td>
								<td>
									<?php echo $text_date_current; ?> + <input id="cdek-append-day" type="text" name="cdek_append_day" value="<?php echo $cdek_append_day; ?>" size="1" /> <?php echo $text_day; ?>
									<?php if (isset($error['cdek_append_day'])) { ?>
									<span class="error"><?php echo $error['cdek_append_day']; ?></span>
									<?php } ?>
								</td>
							</tr>
							<tr>
								<td><label for="cdek-cache-on-delivery"><?php echo $entry_cache_on_delivery; ?></label></td>
								<td>
									<select id="cdek-cache-on-delivery" name="cdek_cache_on_delivery">
										<?php foreach($boolean_variables as $key => $variable) { ?>
										<option <?php if ($cdek_cache_on_delivery == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td><label for="cdek-weight-limit"><?php echo $entry_weight_limit; ?></label></td>
								<td>
									<select id="cdek-weight-limit" name="cdek_weight_limit">
										<?php foreach($boolean_variables as $key => $variable) { ?>
										<option <?php if ($cdek_weight_limit == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td><label for="cdek-use-region-russia"><?php echo $entry_use_region_russia; ?></label></td>
								<td>
									<select id="cdek-use-region-russia" name="cdek_use_region_russia">
										<?php foreach($boolean_variables as $key => $variable) { ?>
										<option <?php if ($cdek_use_region_russia == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td><label for="cdek-use-region"><?php echo $entry_use_region; ?></label></td>
								<td>
									<select id="cdek-use-region" name="cdek_use_region">
										<?php foreach($boolean_variables as $key => $variable) { ?>
										<option <?php if ($cdek_use_region == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td><label for="cdek-city-from"><span class="required">*</span> <?php echo $entry_city_from; ?></label></td>
								<td>
									<input type="hidden" id="cdek-city-from-id" name="cdek_city_from_id" value="<?php echo $cdek_city_from_id; ?>"/>
									<a class="js city-from<?php if (!$cdek_city_from_id) echo ' hidden'; ?>"><?php echo $cdek_city_from; ?></a>
									<input type="text" id="cdek-city-from" name="cdek_city_from" value="<?php echo $cdek_city_from; ?>" class="<?php if ($cdek_city_from_id) echo ' hidden'; ?>" />
									<?php if (isset($error['cdek_city_from'])) { ?>
									<span class="error"><?php echo $error['cdek_city_from']; ?></span>
									<?php } ?>
								</td>
							</tr>
							<tr>
								<td><span class="required">*</span> <label for="cdek-length-class-id"><?php echo $entry_length_class; ?></label></td>
								<td>
									<select id="cdek-length-class-id" name="cdek_length_class_id">
										<?php foreach ($length_classes as $length_class) { ?>
										<option value="<?php echo $length_class['length_class_id']; ?>" <?php if ($length_class['length_class_id'] == $cdek_length_class_id) echo 'selected="selected"'; ?>><?php echo $length_class['title']; ?></option>
										<?php } ?>
									</select>
									<?php if (isset($error['length_class_id'])) { ?>
									<span class="error"><?php echo $error['length_class_id']; ?></span>
									<?php } ?>
								</td>
							</tr>
							<tr>
								<td><span class="required">*</span> <label for="cdek-weight-class-id"><?php echo $entry_weight_class; ?></label></td>
								<td>
									<select id="cdek-weight-class-id" name="cdek_weight_class_id">
										<?php foreach ($weight_classes as $weight_class) { ?>
										<option value="<?php echo $weight_class['weight_class_id']; ?>" <?php if ($weight_class['weight_class_id'] == $cdek_weight_class_id) echo 'selected="selected"'; ?>><?php echo $weight_class['title']; ?></option>
										<?php } ?>
									</select>
									<?php if (isset($error['cdek_weight_class_id'])) { ?>
									<span class="error"><?php echo $error['cdek_weight_class_id']; ?></span>
									<?php } ?>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div id="tab-auth">
					<table class="form">
						<tbody>
							<tr>
								<td colspan="2"><span class="help"><?php echo $text_help_auth; ?></span></td>
							</tr>
							<tr>
								<td><label for="cdek-login"><?php echo $entry_login; ?></label></td>
								<td><input id="cdek-login" type="text" name="cdek_login" value="<?php echo $cdek_login; ?>" /></td>
							</tr>
							<tr>
								<td><label for="cdek-password"><?php echo $entry_password; ?></label></td>
								<td><input id="cdek-password" type="text" name="cdek_password" value="<?php echo $cdek_password; ?>" />
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div id="tab-tariff">
					<table class="form">
						<tbody>
							<tr>
								<td><label for="cdek-work-mode"><?php echo $entry_work_mode; ?></label></td>
								<td>
									<select id="cdek-work-mode" class="work-mode" name="cdek_work_mode">
										<?php foreach ($work_mode as $mode_id => $mode_name) { ?>
										<option <?php if ($mode_id == $cdek_work_mode) echo 'selected="selected"'; ?> value="<?php echo $mode_id; ?>"><?php echo $mode_name; ?></option>
										<?php } ?>
									</select>
								</td>
								<td><div class="help attention<?php if ($cdek_work_mode != 'more') echo ' hidden' ?>"><?php echo $text_more_attention; ?></div></td>
							</tr>
							<tr class="parent">
								<td><label for="cdek-show-pvz"><?php echo $entry_show_pvz; ?></label></td>
								<td colspan="2">
									<select class="slider" id="cdek-show-pvz" name="cdek_show_pvz">
										<?php foreach($boolean_variables as $key => $variable) { ?>
										<option <?php if ($cdek_show_pvz == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr class="children">
								<td colspan="3" class="include">
									<div class="slider-content<?php if (!$cdek_show_pvz) echo " hidden"; ?>" >
										<table class="form">
											<tbody>
												<tr>
													<td><label for="cdek-pvz-more-one"><?php echo $entry_pvz_more_one; ?></label></td>
													<td>
														<select id="cdek-pvz-more-one" name="cdek_pvz_more_one">
															<?php foreach($pvz_more_one_action as $pkey => $variable) { ?>
															<option <?php if ($cdek_pvz_more_one == $pkey) echo 'selected="selected"'; ?> value="<?php echo $pkey; ?>"><?php echo $variable; ?></option>
															<?php } ?>
														</select>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
					<?php if (isset($error['tariff_list'])) { ?>
					<p class="error tariff_list"><?php echo $error['tariff_list']; ?></p>
					<?php } ?>
					<table class="list">
						<thead>
							<tr>
								<td class="left" colspan="2"><?php echo $column_tariff; ?></td>
								<td class="left" width="1"><?php echo $column_title; ?></td>
								<td class="left" width="1"><?php echo $column_customer_group; ?></td>
								<td class="left" width="1"><?php echo $column_geo_zone; ?></td>
								<td class="left"><?php echo $column_limit; ?></td>
								<td class="left"></td>
							</tr>
						</thead>
						<tbody>
							<?php $tariff_row = 0; ?>
							<?php foreach ($cdek_custmer_tariff_list as $tariff_row => $tariff_info) { ?>
							<tr id="tariff-<?php echo $tariff_row; ?>">
								<td class="left drag" width="1"><a title="<?php echo $text_drag; ?>">&nbsp;</a></td>
								<td class="left">
									<nobr><?php echo $tariff_info['tariff_name']; ?></nobr><span class="help"><b><?php echo $column_mode; ?></b>: <?php echo $tariff_info['mode_name']; ?></span>
									<?php if (isset($error['tariff_list_item'][$tariff_row]['exists'])) { ?>
									<span class="error"><?php echo $error['tariff_list_item'][$tariff_row]['exists']; ?></span>
									<?php } ?>
									<input type="hidden" name="cdek_custmer_tariff_list[<?php echo $tariff_row; ?>][sort_order]" value="<?php echo $tariff_info['sort_order']; ?>" class="sort_order" />
									<input type="hidden" name="cdek_custmer_tariff_list[<?php echo $tariff_row; ?>][tariff_id]" value="<?php echo $tariff_info['tariff_id']; ?>" />
									<input type="hidden" name="cdek_custmer_tariff_list[<?php echo $tariff_row; ?>][mode_id]" value="<?php echo $tariff_info['mode_id']; ?>" />
								</td>
								<td class="left">
									<?php foreach ($languages as $language) { ?>
										<nobr>
											<input type="text" name="cdek_custmer_tariff_list[<?php echo $tariff_row; ?>][title][<?php echo $language['language_id']; ?>]" value="<?php echo (isset($tariff_info['title'][$language['language_id']]) && is_array($tariff_info['title'])) ? $tariff_info['title'][$language['language_id']] : (($language['language_id'] == 1 && is_scalar($tariff_info['title'])) ? $tariff_info['title'] : ''); ?>" />
											<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
										</nobr><br />
									<?php } ?>
								</td>
								<td class="left">
									<div class="scrollbox">
										<?php $class = 'even'; ?>
										<?php foreach ($customer_groups as $customer_group) { ?>
										<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
										<div class="<?php echo $class; ?>">
										<input type="checkbox" name="cdek_custmer_tariff_list[<?php echo $tariff_row; ?>][customer_group_id][]" value="<?php echo $customer_group['customer_group_id']; ?>" <?php  if (!empty($tariff_info['customer_group_id']) && is_array($tariff_info['customer_group_id']) && in_array($customer_group['customer_group_id'], $tariff_info['customer_group_id'])) echo 'checked="checked"'; ?> />
										<?php echo $customer_group['name']; ?>
										</div>
										<?php } ?>
									</div>
								</td>
								<td class="left">
									<div class="scrollbox">
										<?php $class = 'even'; ?>
										<?php foreach ($geo_zones as $geo_zone) { ?>
										<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
										<div class="<?php echo $class; ?>">
										<input type="checkbox" name="cdek_custmer_tariff_list[<?php echo $tariff_row; ?>][geo_zone][]" value="<?php echo $geo_zone['geo_zone_id']; ?>" <?php  if (isset($tariff_info['geo_zone']) && in_array($geo_zone['geo_zone_id'], $tariff_info['geo_zone'])) echo 'checked="checked"'; ?> />
										<?php echo $geo_zone['name']; ?>
										</div>
										<?php } ?>
									</div>
								</td>
								<td class="left">
									<table class="form limit">
										<tbody>
											<tr>
												<td><label for="cdek-custmer-tariff-list-<?php echo $tariff_row; ?>-min-weight"><?php echo $entry_min_weight; ?></label></td>
												<td>
													<input id="cdek-custmer-tariff-list-<?php echo $tariff_row; ?>-min-weight" type="text" name="cdek_custmer_tariff_list[<?php echo $tariff_row; ?>][min_weight]" value="<?php if (isset($tariff_info['min_weight'])) echo $tariff_info['min_weight']; ?>" size="3" />
													<?php if (isset($error['tariff_list_item'][$tariff_row]['min_weight'])) { ?>
													<span class="error"><?php echo $error['tariff_list_item'][$tariff_row]['min_weight']; ?></span>
													<?php } ?>
												</td>
											</tr>
											<tr>
												<td><label for="cdek-custmer-tariff-list-<?php echo $tariff_row; ?>-max-weight"><?php echo $entry_max_weight; ?></label></td>
												<td>
													<input id="cdek-custmer-tariff-list-<?php echo $tariff_row; ?>-max-weight" type="text" name="cdek_custmer_tariff_list[<?php echo $tariff_row; ?>][max_weight]" value="<?php if (isset($tariff_info['max_weight'])) echo $tariff_info['max_weight']; ?>" size="3" />
													<?php if (isset($error['tariff_list_item'][$tariff_row]['max_weight'])) { ?>
													<span class="error"><?php echo $error['tariff_list_item'][$tariff_row]['max_weight']; ?></span>
													<?php } ?>
												</td>
											</tr>
											<tr>
												<td><label for="cdek-custmer-tariff-list-<?php echo $tariff_row; ?>-min-total"><?php echo $entry_min_total; ?></label></td>
												<td>
													<input id="cdek-custmer-tariff-list-<?php echo $tariff_row; ?>-min-total" type="text" name="cdek_custmer_tariff_list[<?php echo $tariff_row; ?>][min_total]" value="<?php if (isset($tariff_info['min_total'])) echo $tariff_info['min_total']; ?>" size="3" />
													<?php if (isset($error['tariff_list_item'][$tariff_row]['min_total'])) { ?>
													<span class="error"><?php echo $error['tariff_list_item'][$tariff_row]['min_total']; ?></span>
													<?php } ?>
												</td>
											</tr>
											<tr class="last">
												<td><label for="cdek-custmer-tariff-list-<?php echo $tariff_row; ?>-max-total"><?php echo $entry_max_total; ?></label></td>
												<td>
													<input id="cdek-custmer-tariff-list-<?php echo $tariff_row; ?>-max-total" type="text" name="cdek_custmer_tariff_list[<?php echo $tariff_row; ?>][max_total]" value="<?php if (isset($tariff_info['max_total'])) echo $tariff_info['max_total']; ?>" size="3" />
													<?php if (isset($error['tariff_list_item'][$tariff_row]['max_total'])) { ?>
													<span class="error"><?php echo $error['tariff_list_item'][$tariff_row]['max_total']; ?></span>
													<?php } ?>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
								<td class="left"><a onclick="removeTariff(<?php echo $tariff_row; ?>);" class="button"><?php echo $button_remove; ?></a></td>
							</tr>
							<?php } ?>
							<?php $tariff_row++; ?>
						</tbody>
					</table>
					<?php echo $text_tariff; ?>
					<select class="cdek-tariff">
						<option value="0"><?php echo $text_select; ?></option>
						<?php foreach ($tariff_list as $tariff_id => $tariff_info) { ?>
						<option rel="<?php echo $tariff_info['mode_id']; ?>" value="<?php echo $tariff_id; ?>"><?php echo $tariff_info['title'] . (isset($tariff_info['im']) ? ' ***' : ''); ?></option>
						<?php } ?>
					</select>
					<p class="help"><?php echo $text_help_im; ?></p>
				</div>
				<div id="tab-package">
					<table class="form">
						<tbody>
							<tr>
								<td><label for="cdek-packing-min-weight"><?php echo $entry_packing_min_weight; ?></label></td>
								<td>
									<input id="cdek-packing-min-weight" type="text" name="cdek_packing_min_weight" value="<?php echo $cdek_packing_min_weight; ?>" size="1" />
									<select name="cdek_packing_weight_class_id">
										<?php foreach ($weight_classes as $weight_class) { ?>
										<option value="<?php echo $weight_class['weight_class_id']; ?>" <?php if ($weight_class['weight_class_id'] == $cdek_packing_weight_class_id) echo 'selected="selected"'; ?>><?php echo $weight_class['title']; ?></option>
										<?php } ?>
									</select>
									<?php if (isset($error['cdek_packing_min_weight'])) { ?>
									<span class="error"><?php echo $error['cdek_packing_min_weight']; ?></span>
									<?php } ?>
								</td>
							</tr>
							<tr>
								<td><label for="cdek-packing-value"><?php echo $entry_packing_additional_weight; ?></label></td>
								<td>
									<select name="cdek_packing_prefix">
										<?php foreach (array('+', '-') as $prefix) { ?>
										<option <?php if ($prefix == $cdek_packing_prefix) echo 'selected="selected"'; ?> value="<?php echo $prefix; ?>"><?php echo $prefix; ?></option>
										<?php } ?>
									</select>
									<input id="cdek-packing-value" type="text" name="cdek_packing_value" value="<?php echo $cdek_packing_value; ?>" size="1" />
									<select name="cdek_packing_mode">
										<?php foreach($additional_weight_mode as $key => $value) { ?>
										<option <?php if ($cdek_packing_mode == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
										<?php } ?>
									</select>
									<?php if (isset($error['cdek_packing_value'])) { ?>
									<span class="error"><?php echo $error['cdek_packing_value']; ?></span>
									<?php } ?>
								</td>
							</tr>
							
						</tbody>
					</table>
				</div>
				<div id="tab-additional">
					<table class="form">
						<tbody>
							<tr>
								<td><label for="cdek-more-days"><?php echo $entry_more_days; ?></label></td>
								<td>
									 + <input id="cdek-more-days" type="text" name="cdek_more_days" value="<?php echo $cdek_more_days; ?>" size="1" /> <?php echo $text_day; ?>
									<?php if (isset($error['cdek_more_days'])) { ?>
									<span class="error"><?php echo $error['cdek_more_days']; ?></span>
									<?php } ?>
								</td>
							</tr>
							<tr>
								<td><label for="cdek-period"><?php echo $entry_period; ?></label></td>
								<td>
									<select id="cdek-period" name="cdek_period">
										<?php foreach($boolean_variables as $key => $variable) { ?>
										<option <?php if ($cdek_period == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td><label for="cdek-delivery-data"><?php echo $entry_delivery_data; ?></label></td>
								<td>
									<select id="cdek-delivery-data" name="cdek_delivery_data">
										<?php foreach($boolean_variables as $key => $variable) { ?>
										<option <?php if ($cdek_delivery_data == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td><label for="cdek-empty-address"><?php echo $entry_empty_address; ?></label></td>
								<td>
									<select id="cdek-empty-address" name="cdek_empty_address">
										<?php foreach($boolean_variables as $key => $variable) { ?>
										<option <?php if ($cdek_empty_address == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td><label for="cdek-min-weight"><?php echo $entry_min_weight; ?></label></td>
								<td>
									<input id="cdek-min-weight" type="text" name="cdek_min_weight" value="<?php echo $cdek_min_weight; ?>" />
									<?php if (isset($error['cdek_min_weight'])) { ?>
									<span class="error"><?php echo $error['cdek_min_weight']; ?></span>
									<?php } ?>
								</td>
							</tr>
							<tr>
								<td><label for="cdek-max-weight"><?php echo $entry_max_weight; ?></label></td>
								<td>
									<input id="cdek-max-weight" type="text" name="cdek_max_weight" value="<?php echo $cdek_max_weight; ?>" />
									<?php if (isset($error['cdek_max_weight'])) { ?>
									<span class="error"><?php echo $error['cdek_max_weight']; ?></span>
									<?php } ?>
								</td>
							</tr>
							<tr>
								<td><label for="cdek-min-total"><?php echo $entry_min_total; ?></label></td>
								<td>
									<input id="cdek-min-total" type="text" name="cdek_min_total" value="<?php echo $cdek_min_total; ?>" />
									<?php if (isset($error['cdek_min_total'])) { ?>
									<span class="error"><?php echo $error['cdek_min_total']; ?></span>
									<?php } ?>
								</td>
							</tr>
							<tr>
								<td><label for="cdek-max-total"><?php echo $entry_max_total; ?></label></td>
								<td>
									<input id="cdek-max-total" type="text" name="cdek_max_total" value="<?php echo $cdek_max_total; ?>" />
									<?php if (isset($error['cdek_max_total'])) { ?>
									<span class="error"><?php echo $error['cdek_max_total']; ?></span>
									<?php } ?>
								</td>
							</tr>
							<tr>
								<td><label for="cdek-city-ignore"><?php echo $entry_city_ignore; ?></label></td>
								<td><textarea id="cdek-city-ignore" name="cdek_city_ignore" rows="4" cols="50"><?php echo $cdek_city_ignore; ?></textarea></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div id="tab-discount">
					<p class="help"><?php echo $text_discount_help; ?></p>
					<table class="list" id="discount">
						<thead>
							<tr>
								<td class="left"><?php echo $column_total; ?></td>
								<td class="left"><?php echo $column_tariff; ?></td>	
								<td class="left"><?php echo $column_tax_class; ?></td>
								<td class="left"><?php echo $column_customer_group; ?></td>
								<td class="left"><?php echo $column_geo_zone; ?></td>
								<td class="left"><?php echo $column_discount_value; ?></td>
								<td></td>
							</tr>
						</thead>
						<tbody>
							<?php $discount_row = 0; ?>
							<?php if ($cdek_discounts) { ?>
							<?php foreach ($cdek_discounts as $discount_row => $discount) { ?>
							<tr id="discount-row<?php echo $discount_row; ?>">
								<td class="left">
									<input type="text" name="cdek_discounts[<?php echo $discount_row; ?>][total]" value="<?php echo $discount['total']; ?>" size="3" />
									<?php if (isset($error['cdek_discounts'][$discount_row]['total'])) { ?>
									<span class="error"><?php echo $error['cdek_discounts'][$discount_row]['total']; ?></span>
									<?php } ?>
								</td>
								<td class="left">
									<div class="scrollbox">
										<?php $class = 'even'; ?>
										<?php foreach ($tariff_list as $tariff_id => $tariff_info) { ?>
										<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
										<div class="<?php echo $class; ?>">
										<input type="checkbox" name="cdek_discounts[<?php echo $discount_row; ?>][tariff_id][]" value="<?php echo $tariff_id; ?>" <?php  if (!empty($discount['tariff_id']) && is_array($discount['tariff_id']) && in_array($tariff_id, $discount['tariff_id'])) echo 'checked="checked"'; ?> />
										<?php echo $tariff_info['title']; ?>
										</div>
										<?php } ?>
									</div>
								</td>
								<td class="left">
									<select name="cdek_discounts[<?php echo $discount_row; ?>][tax_class_id]">
										<option value="0"><?php echo $text_none; ?></option>
										<?php foreach ($tax_classes as $tax_class) { ?>
										<option <?php if ($tax_class['tax_class_id'] == $discount['tax_class_id']) echo 'selected="selected"'; ?> value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
										<?php } ?>
									</select>
								</td>
								<td class="left">
									<div class="scrollbox">
										<?php $class = 'even'; ?>
										<?php foreach ($customer_groups as $customer_group) { ?>
										<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
										<div class="<?php echo $class; ?>">
										<input type="checkbox" name="cdek_discounts[<?php echo $discount_row; ?>][customer_group_id][]" value="<?php echo $customer_group['customer_group_id']; ?>" <?php  if (!empty($discount['customer_group_id']) && is_array($discount['customer_group_id']) && in_array($customer_group['customer_group_id'], $discount['customer_group_id'])) echo 'checked="checked"'; ?> />
										<?php echo $customer_group['name']; ?>
										</div>
										<?php } ?>
									</div>
								</td>
								<td class="left">
									<div class="scrollbox">
										<?php $class = 'even'; ?>
										<?php foreach ($geo_zones as $geo_zone) { ?>
										<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
										<div class="<?php echo $class; ?>">
										<input type="checkbox" name="cdek_discounts[<?php echo $discount_row; ?>][geo_zone][]" value="<?php echo $geo_zone['geo_zone_id']; ?>" <?php  if (isset($discount['geo_zone']) && in_array($geo_zone['geo_zone_id'], $discount['geo_zone'])) echo 'checked="checked"'; ?> />
										<?php echo $geo_zone['name']; ?>
										</div>
										<?php } ?>
									</div>
								</td>
								<td class="left">
									<nobr>
										<select name="cdek_discounts[<?php echo $discount_row; ?>][prefix]">
											<?php foreach (array('-', '+') as $prefix) { ?>
											<option <?php if ($prefix == $discount['prefix']) echo 'selected="selected"'; ?> value="<?php echo $prefix; ?>"><?php echo $prefix; ?></option>
											<?php } ?>
										</select>
										<input type="text" name="cdek_discounts[<?php echo $discount_row; ?>][value]" value="<?php echo $discount['value']; ?>" size="3" />
										<select name="cdek_discounts[<?php echo $discount_row; ?>][mode]">
											<?php foreach ($discount_type as $type => $name) { ?>
											<option <?php if ($type == $discount['mode']) echo 'selected="selected"'; ?> value="<?php echo $type; ?>"><?php echo $name; ?></option>
											<?php } ?>
										</select>
									</nobr>
									<?php if (isset($error['cdek_discounts'][$discount_row]['value'])) { ?>
									<span class="error"><?php echo $error['cdek_discounts'][$discount_row]['value']; ?></span>
									<?php } ?>
								</td>
								<td class="left"><a onclick="$('#discount-row<?php echo $discount_row; ?>').remove();return FALSE;" class="button"><?php echo $button_remove; ?></a></td>
							</tr>
							<?php } ?>
							<?php $discount_row++; ?>
							<?php } ?>
						</tbody>
					</table>
					<a class="button" onclick="addDiscount();"><?php echo $button_add_discount; ?></a>
				</div>
				<div id="tab-empty">
					<table class="form">
						<tbody>
							<tr class="parent">
								<td><label for="cdek-empty"><?php echo $entry_empty; ?></label></td>
								<td colspan="2">
									<select class="slider" id="cdek-empty" name="cdek_empty[use]">
										<?php foreach($boolean_variables as $key => $variable) { ?>
										<option <?php if (!empty($cdek_empty['use']) && $cdek_empty['use'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr class="children">
								<td colspan="3" class="include">
									<div class="slider-content<?php if (empty($cdek_empty['use']) || !$cdek_empty['use']) echo " hidden"; ?>" >
										<table class="form">
											<tbody>
												<tr>
													<td><?php echo $entry_title; ?></td>
													<td>
														<?php foreach ($languages as $language) { ?>
														<input type="text" name="cdek_empty[title][<?php echo $language['language_id']; ?>]" value="<?php echo isset($cdek_empty['title'][$language['language_id']]) ? $cdek_empty['title'][$language['language_id']] : ''; ?>" />
														<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
														<?php } ?>
													</td>
												</tr>
												<tr>
													<td><label for="cdek-empty-cost"><?php echo $entry_empty_cost; ?></label></td>
													<td>
														<input id="cdek-empty-cost" type="text" name="cdek_empty[cost]" value="<?php if (!empty($cdek_empty['cost'])) echo $cdek_empty['cost']; ?>" />
														<?php if (isset($error['cdek_empty']['cost'])) { ?>
														<span class="error"><?php echo $error['cdek_empty']['cost']; ?></span>
														<?php } ?>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<input type="hidden" name="apply" value="0" />
			</form>
		</div>
	</div>
</div>
<script type="text/javascript"><!--

$('#cdek-default-size-type').change(function(event){
	
	var type = $(this).val();
	
	$('.cdek-default-size').hide();
	
	if (type == 'volume') {
		$('.cdek-default-size.volume').show();
	} else {
		$('.cdek-default-size.size').show();
	}
	
});

var mode_list = [];

<?php foreach ($tariff_mode as $mode_id => $mode_name) { ?>
	mode_list[<?php echo $mode_id; ?>] = '<?php echo $mode_name; ?>';
<?php } ?>

var tariff_row = <?php echo $tariff_row; ?>;

$('.cdek-tariff').live('change', function(event){
	event.preventDefault();
	
	var tariff_id = $(this).val();
	
	if (tariff_id == 0) return;
	
	var parent = $('#tab-tariff');
	
	var option = $('select.cdek-tariff option[value=' + tariff_id + ']', parent);
	var mode_id = $(option).attr('rel');
	
	var sort_orde = 0;
	
	$('table.list tr', parent).each(function(){
		
		var order = $('input.sort_order', this).val();
		
		if (order > sort_orde) {
			sort_orde = order;
		}
		
	});
	
	sort_orde++;
	
	var html = '<tr id="tariff-' + tariff_row + '">';
	html += '		<td class="left drag" width="1"><a title="<?php echo $text_drag; ?>">&nbsp;</a></td>';
	html += '		<td class="left"><nobr>' + $(option).text() + '</nobr><span class="help"><b><?php echo $column_mode; ?></b>: ' + mode_list[mode_id] + '</span><input class="sort_order" type="hidden" name="cdek_custmer_tariff_list[' + tariff_row + '][sort_order]" value="' + sort_orde + '" /><input type="hidden" name="cdek_custmer_tariff_list[' + tariff_row + '][tariff_id]" value="' + tariff_id + '" /><input type="hidden" name="cdek_custmer_tariff_list[' + tariff_row + '][mode_id]" value="' + mode_id + '" /></td>';
	html += '		<td class="left">';
	<?php foreach ($languages as $language) { ?>
	html += '			<nobr><input type="text" name="cdek_custmer_tariff_list[' + tariff_row + '][title][<?php echo $language['language_id']; ?>]" value="" />';
	html += '			<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></nobr><br />';
	<?php } ?>
	html += '		</td>';
	html += '		<td class="left">';
	html += '			<div class="scrollbox">';
	<?php $class = 'even'; ?>
	<?php foreach ($customer_groups as $customer_group) { ?>
	<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
	html += '				<div class="<?php echo $class; ?>">';
	html += '				<input type="checkbox" name="cdek_custmer_tariff_list[' + tariff_row + '][customer_group_id][]" value="<?php echo $customer_group['customer_group_id']; ?>" />';
	html += '				<?php echo $customer_group['name']; ?>';
	html += '				</div>';
	<?php } ?>
	html += '			</div>';
	html += '		</td>';
	html += '		<td class="left">';
	html += '			<div class="scrollbox">';
	<?php $class = 'even'; ?>
	<?php foreach ($geo_zones as $geo_zone) { ?>
	<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
	html += '				<div class="<?php echo $class; ?>">';
	html += '					<input type="checkbox" name="cdek_custmer_tariff_list[' + tariff_row + '][geo_zone][]" value="<?php echo $geo_zone['geo_zone_id']; ?>" />';
	html += '					<?php echo $geo_zone['name']; ?>';
	html += '				</div>';
	<?php } ?>
	html += '			</div>';
	html += '		</td>';
	html += '		<td class="left">';
	html += '			<table class="form limit">';
	html += '				<tbody>';
	html += '					<tr>';
	html += '						<td><label for="cdek-custmer-tariff-list-' + tariff_row + '-min-weight"><?php echo $entry_min_weight; ?></label></td>';
	html += '						<td><input id="cdek-custmer-tariff-list-' + tariff_row + '-min-weight" type="text" name="cdek_custmer_tariff_list[' + tariff_row + '][min_weight]" value="" size="3" /></td>';
	html += '					</tr>';
	html += '					<tr>';
	html += '						<td><label for="cdek-custmer-tariff-list-' + tariff_row + '-max-weight"><?php echo $entry_max_weight; ?></label></td>';
	html += '						<td><input id="cdek-custmer-tariff-list-' + tariff_row + '-max-weight" type="text" name="cdek_custmer_tariff_list[' + tariff_row + '][max_weight]" value="" size="3" /></td>';
	html += '					</tr>';
	html += '					<tr>';
	html += '						<td><label for="cdek-custmer-tariff-list-' + tariff_row + '-min-total"><?php echo $entry_min_total; ?></label></td>';
	html += '						<td><input id="cdek-custmer-tariff-list-' + tariff_row + '-min-total" type="text" name="cdek_custmer_tariff_list[' + tariff_row + '][min_total]" value="" size="3" /></td>';
	html += '					</tr>';
	html += '					<tr class="last">';
	html += '						<td><label for="cdek-custmer-tariff-list-' + tariff_row + '-max-total"><?php echo $entry_max_total; ?></label></td>';
	html += '						<td><input id="cdek-custmer-tariff-list-' + tariff_row + '-max-total" type="text" name="cdek_custmer_tariff_list[' + tariff_row + '][max_total]" value="" size="3" /></td>';
	html += '					</tr>';
	html += '				</tbody>';
	html += '			</table>';
	html += '		</td>';
	html += '		<td class="left"><a onclick="removeTariff(' + tariff_row + ');" class="button"><?php echo $button_remove; ?></a></td>';
	html += '</tr>';
	
	$('table.list tbody:first', parent).append(html);
	
	$('select.cdek-tariff option', parent).removeAttr('selected');
	
	addTableDnD($('.list', parent));
	
	tariff_row++;
});

function removeTariff(tariff_row) {
	$('#tariff-' + tariff_row).remove();
	$('select.cdek-tariff option[value=' + tariff_row + ']').show();
}

var discount_row = <?php echo $discount_row; ?>;

function addDiscount() {
	
	var html = '<tr id="discount-row' + discount_row + '">';
	html += '		<td class="left">';
	html += '			<input type="text" name="cdek_discounts[' + discount_row + '][total]" value="" size="3" />';
	html += '		</td>';
	html += '		<td class="left">';
	
	html += '			<div class="scrollbox">';
	<?php $class = 'even'; ?>
	<?php foreach ($tariff_list as $tariff_id => $tariff_info) { ?>
	<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
	html += '				<div class="<?php echo $class; ?>">';
	html += '				<input type="checkbox" name="cdek_discounts[' + discount_row + '][tariff_id][]" value="<?php echo $tariff_id; ?>" />';
	html += '				<?php echo $tariff_info['title']; ?>';
	html += '				</div>';
	<?php } ?>
	html += '			</div>';
	html += '		</td>';
	html += '		<td class="left">';
	html += '			<select name="cdek_discounts[' + discount_row + '][tax_class_id]">';
	html += '				<option value="0"><?php echo $text_none; ?></option>';
	<?php foreach ($tax_classes as $tax_class) { ?>
	html += '				<option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>';
	<?php } ?>
	html += '			</select>';
	html += '		</td>';
	html += '		<td class="left">';
	html += '			<div class="scrollbox">';
	<?php $class = 'even'; ?>
	<?php foreach ($customer_groups as $customer_group) { ?>
	<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
	html += '				<div class="<?php echo $class; ?>">';
	html += '				<input type="checkbox" name="cdek_discounts[' + discount_row + '][customer_group_id][]" value="<?php echo $customer_group['customer_group_id']; ?>" />';
	html += '				<?php echo $customer_group['name']; ?>';
	html += '				</div>';
	<?php } ?>
	html += '			</div>';
	html += '		</td>';
	html += '		<td class="left">';
	html += '			<div class="scrollbox">';
	<?php $class = 'even'; ?>
	<?php foreach ($geo_zones as $geo_zone) { ?>
	<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
	html += '				<div class="<?php echo $class; ?>">';
	html += '					<input type="checkbox" name="cdek_discounts[' + discount_row + '][geo_zone][]" value="<?php echo $geo_zone['geo_zone_id']; ?>" />';
	html += '					<?php echo $geo_zone['name']; ?>';
	html += '				</div>';
	<?php } ?>
	html += '			</div>';
	html += '		</td>';
	html += '		<td class="left">';
	html += '			<select name="cdek_discounts[' + discount_row + '][prefix]">';
	<?php foreach (array('-', '+') as $prefix) { ?>
	html += '				<option value="<?php echo $prefix; ?>"><?php echo $prefix; ?></option>';
	<?php } ?>
	html += '			</select>';
	html += '			<input type="text" name="cdek_discounts[' + discount_row + '][value]" value="" size="3" />';
	html += '			<select name="cdek_discounts[' + discount_row + '][mode]">';
	<?php foreach ($discount_type as $type => $name) { ?>
	html += '				<option value="<?php echo $type; ?>"><?php echo $name; ?></option>';
	<?php } ?>
	html += '			</select>';
	html += '		</td>';
	html += '		<td class="left"><a onclick="$(\'#discount-row' + discount_row + '\').remove();return FALSE;" class="button"><?php echo $button_remove; ?></a></td>';
	html += '</tr>';
	
	$('#discount tbody').append(html);
	
	discount_row++;
	
}

$('.js.city-from').click(function(){
	 $("#cdek-city-from").show().focus().trigger('keydown');
	 $(this).hide();
});

$("#cdek-city-from").blur(function(){
	if ($('#cdek-city-from-id').val() != '') {
		$('.js.city-from').show();
		$(this).hide();
	}
});

$("#cdek-city-from").change(function(){
	$('#cdek-city-from-id').val('');
});

$(function() {
  $("#cdek-city-from").autocomplete({
	source: function(request,response) {
	  $.ajax({
		url: "http://api.cdek.ru/city/getListByTerm/jsonp.php?callback=?",
		dataType: "jsonp",
		data: {
			q: function () { return $("#cdek-city-from").val() },
			name_startsWith: function () { return $("#cdek-city-from").val() }
		},
		success: function(data) {
		  response($.map(data.geonames, function(item) {
			return {
			  label: item.name,
			  value: item.name,
			  id: item.id
			}
		  }));
		}
	  });
	},
	minLength: 1,
	select: function(event,ui) {
		$('#cdek-city-from-id').parent().find('.error').remove();
		$('#cdek-city-from-id').val(ui.item.id);
		$('.js.city-from').text(ui.item.label).show();
		$("#cdek-city-from").hide();
	}
  });
  
});

$('.slider').live('change', (function(event){
	$(this).closest('tr').next('tr').find('.slider-content:first').slideToggle('fast');
}));

$('.slider-radio').change(function(event){
	$(this).closest('tr').find('.slider-content:first').toggle();
});

$('select.work-mode').live('change', function(){
	var attention = $(this).closest('tr').find('.attention').toggle();
});

function addTableDnD(el) {
	$(el).tableDnD({
		onDrop: function(table, row) {
			
			$('tbody tr', table).each(function(){
				$('td input.sort_order', this).val($(this).index());
			});
			
			$(row).addClass('changed').find('td:eq(1)');
			
			var change = $(row).find('td:eq(1)');
			
			if (!$('span.required', change).length) {
				$(change).append(' <span class="required">*</span>');
			}
			
		},
		onDragClass: 'draggable',
		dragHandle: ".drag"
	}).addClass('table-dnd');
}

addTableDnD($('#tab-tariff .list'));

$('#tabs a').tabs();
//--></script> 
<?php echo $footer; ?> 