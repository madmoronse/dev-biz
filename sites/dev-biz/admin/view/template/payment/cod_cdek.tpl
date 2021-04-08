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
			<h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
		</div>
		<div class="content">
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<div id="tabs" class="htabs">
					<a href="#tab-general"><?php echo $tab_general; ?></a>
					<a href="#tab-additional"><?php echo $tab_additional; ?></a>
				</div>
				<div id="tab-general">
					<table class="form">
						<tr>
							<td><?php echo $entry_title; ?></td>
							<td>
								<?php foreach ($languages as $language) { ?>
								<input type="text" name="cod_cdek_title[<?php echo $language['language_id']; ?>]" value="<?php echo isset($cod_cdek_title[$language['language_id']]) ? $cod_cdek_title[$language['language_id']] : ''; ?>" />
								<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td><label for="cdek-cache-on-delivery"><?php echo $entry_cache_on_delivery; ?></label></td>
							<td>
								<select id="cdek-cache-on-delivery" name="cod_cdek_cache_on_delivery">
									<?php foreach($boolean_variables as $key => $variable) { ?>
									<option <?php if ($cod_cdek_cache_on_delivery == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $variable; ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<tr class="parent">
							<td><label for="cod-cdek-view-mode"><?php echo $entry_view_mode; ?></label></td>
							<td>
								<select id="cod-cdek-view-mode" name="cod_cdek_mode">
									<?php foreach ($view_mode as $key => $value) { ?>
									<option value="<?php echo $key; ?>" <?php if ($cod_cdek_mode == $key) echo 'selected="selected"'; ?>><?php echo $value; ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<tr class="children">
							<td colspan="2" class="include">
								<div class="slider-content"<?php if ($cod_cdek_mode != 'cdek') echo ' style="display:none"'; ?>>
									<table class="form">
										<tr>
											<td><label for="cod-cdek-view-mode-cdek"><?php echo $entry_view_mode_cdek; ?></label></td>
											<td>
												<select id="cod-cdek-view-mode-cdek" name="cod_cdek_mode_cdek">
													<?php foreach ($view_mode_cdek as $key => $value) { ?>
													<option value="<?php echo $key; ?>" <?php if ($cod_cdek_mode_cdek == $key) echo 'selected="selected"'; ?>><?php echo $value; ?></option>
													<?php } ?>
												</select>
											</td>
										</tr>
									</table>
								</div>
							</td>
						</tr>
						<tr>
							<td><label for="order-status-id"><?php echo $entry_order_status; ?></label></td>
							<td><select id="order-status-id" name="cod_cdek_order_status_id">
									<?php foreach ($order_statuses as $order_status) { ?>
									<option value="<?php echo $order_status['order_status_id']; ?>" <?php if ($order_status['order_status_id'] == $cod_cdek_order_status_id) echo 'selected="selected"'; ?>><?php echo $order_status['name']; ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<tr>
							<td><label for="status"><?php echo $entry_status; ?></label></td>
							<td>
								<select id="status" name="cod_cdek_status">
									<?php foreach ($status_variables as $key => $text) { ?>
									<option value="<?php echo $key; ?>" <?php if ($cod_cdek_status == $key) echo 'selected="selected"'; ?>><?php echo $text; ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<tr>
							<td><label for="sort-order"><?php echo $entry_sort_order; ?></label></td>
							<td>
								<input id="sort-order" type="text" name="cod_cdek_sort_order" value="<?php echo $cod_cdek_sort_order; ?>" size="1" />
								<?php if (isset($error['cod_cdek_sort_order'])) { ?>
								<span class="error"><?php echo $error['cod_cdek_sort_order']; ?></span>
								<?php } ?>
							</td>
						</tr>
					</table>
				</div>
				<div id="tab-additional">
					<table class="form">
						<tr>
							<td><label for="active"><?php echo $entry_active; ?></label></td>
							<td>
								<select id="active" name="cod_cdek_active">
									<?php foreach ($boolean_variables as $key => $text) { ?>
									<option value="<?php echo $key; ?>" <?php if ($cod_cdek_active == $key) echo 'selected="selected"'; ?>><?php echo $text; ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<tr>
							<td><label for="cod-cdek-min-total"><?php echo $entry_min_total; ?></label></td>
							<td>
								<input id="cod-cdek-min-total" type="text" name="cod_cdek_min_total" value="<?php echo $cod_cdek_min_total; ?>" />
								<?php if (isset($error['cod_cdek_min_total'])) { ?>
								<span class="error"><?php echo $error['cod_cdek_min_total']; ?></span>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td><label for="cod-cdek-max-total"><?php echo $entry_max_total; ?></label></td>
							<td>
								<input id="cod-cdek-max-total" type="text" name="cod_cdek_max_total" value="<?php echo $cod_cdek_max_total; ?>" />
								<?php if (isset($error['cod_cdek_max_total'])) { ?>
								<span class="error"><?php echo $error['cod_cdek_max_total']; ?></span>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td><label for="cod-cdek-price-value"><?php echo $entry_price; ?></label></td>
							<td>
								<select name="cod_cdek_price[prefix]">
									<?php foreach (array('-', '+') as $prefix) { ?>
									<option <?php if (!empty($cod_cdek_price) && $cod_cdek_price['prefix'] == $prefix) echo 'selected="selected"'; ?> value="<?php echo $prefix; ?>"><?php echo $prefix; ?></option>
									<?php } ?>
								</select>
								<input id="cod-cdek-price-value" type="text" name="cod_cdek_price[value]" value="<?php if (!empty($cod_cdek_price)) echo $cod_cdek_price['value']; ?>" size="3" />
								<select name="cod_cdek_price[mode]">
									<?php foreach ($discount_type as $type => $name) { ?>
									<option <?php if (!empty($cod_cdek_price) && $cod_cdek_price['mode'] == $type) echo 'selected="selected"'; ?> value="<?php echo $type; ?>"><?php echo $name; ?></option>
									<?php } ?>
								</select>
								<?php if (isset($error['cod_cdek_price']['value'])) { ?>
								<span class="error"><?php echo $error['cod_cdek_price']['value']; ?></span>
								<?php } ?>
							</td>							
						</tr>
						<tr>
							<td><?php echo $entry_geo_zone; ?></td>
							<td>
								<div class="scrollbox">
									<?php $class = 'even'; ?>
									<?php foreach ($geo_zones as $geo_zone) { ?>
									<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
									<div class="<?php echo $class; ?>">
									<input type="checkbox" name="cod_cdek_geo_zone_id[]" value="<?php echo $geo_zone['geo_zone_id']; ?>" <?php if (!empty($cod_cdek_geo_zone_id) && in_array($geo_zone['geo_zone_id'], $cod_cdek_geo_zone_id)) echo 'checked="checked"'; ?> />
									<?php echo $geo_zone['name']; ?>
									</div>
									<?php } ?>
								</div>
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
									<input type="checkbox" name="cod_cdek_store[]" value="<?php echo $store['store_id']; ?>" <?php  if (isset($cod_cdek_store) && in_array($store['store_id'], $cod_cdek_store)) echo 'checked="checked"'; ?> />
									<?php echo $store['name']; ?>
									
									</div>
									<?php } ?>
								</div>
							</td>
						</tr>
						<tr>
							<td><?php echo $entry_customer_group; ?></td>
							<td class="left">
								<div class="scrollbox">
									<?php $class = 'even'; ?>
									<?php foreach ($customer_groups as $customer_group) { ?>
									<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
									<div class="<?php echo $class; ?>">
									<input type="checkbox" name="cod_cdek_customer_group_id[]" value="<?php echo $customer_group['customer_group_id']; ?>" <?php  if (!empty($cod_cdek_customer_group_id) && in_array($customer_group['customer_group_id'], $cod_cdek_customer_group_id)) echo 'checked="checked"'; ?> />
									<?php echo $customer_group['name']; ?>
									</div>
									<?php } ?>
								</div>
							</td>
						</tr>
						
						<tr>
							<td><label for="cod-cdek-city-ignore"><?php echo $entry_city_ignore; ?></label></td>
							<td><textarea id="cod-cdek-city-ignore" name="cod_cdek_city_ignore" rows="4" cols="50"><?php echo $cod_cdek_city_ignore; ?></textarea></td>
						</tr>
					</table>
				</div>
				
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
	
	$('.parent select').live('change', (function(event){
		$(this).closest('tr').next('tr').find('.slider-content:first').slideToggle('fast');
	}));
	
	$('#tabs a').tabs(); 
	
</script>
<?php echo $footer; ?> 