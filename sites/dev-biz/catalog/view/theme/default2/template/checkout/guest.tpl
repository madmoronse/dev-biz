<div class="left">
  <h2><?php echo $text_your_details; ?></h2>
	<div style="margin-top: 30px;">
		<b><span class="required">*</span> <?php echo $entry_lastname; ?></b>
		<input type="text" name="lastname" value="" class="large-field" />
	</div>
	<div>
		<b><span class="required">*</span> <?php echo $entry_firstname; ?></b>
		<input type="text" name="firstname" value="" class="large-field" />
  </div>
	<div>
		<b><span class="required">*</span> <?php echo $entry_email; ?></b>
		<input type="text" name="email" value="" class="large-field" />
	</div>
	<div>
		<b><span class="required">*</span> <?php echo $entry_telephone; ?></b>
		<input type="text" name="telephone" value="" class="large-field" />
	</div>
	<div style="margin-bottom: 30px;">
		<b><?php echo $entry_fax; ?></b>
		<input type="text" name="fax" value="" class="large-field" />
	</div>
</div>
<div class="right">
  <h2><?php echo $text_your_address; ?></h2>
	
	<div style="margin-top: 30px;">
		<b><?php echo $entry_company; ?></b>
		<input type="text" name="company" value="" class="large-field" />
	</div>
	
  <div style="display: <?php echo (count($customer_groups) > 1 ? 'table-row' : 'none'); ?>;">
		<?php echo $entry_customer_group; ?><br />
		<?php foreach ($customer_groups as $customer_group) { ?>
		<?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
		<input type="radio" name="customer_group_id" value="<?php echo $customer_group['customer_group_id']; ?>" id="customer_group_id<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
		<label for="customer_group_id<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></label>
		<br />
		<?php } else { ?>
		<input type="radio" name="customer_group_id" value="<?php echo $customer_group['customer_group_id']; ?>" id="customer_group_id<?php echo $customer_group['customer_group_id']; ?>" />
		<label for="customer_group_id<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></label>
		<br />
		<?php } ?>
		<?php } ?>
		<br />
	</div>
	
	
	<div id="company-id-display" style="display:none;">
		<b><span id="company-id-required" class="required">*</span> <?php echo $entry_company_id; ?></b>
		<input type="text" name="company_id" value="" class="large-field" />
	</div>
	
	<div id="tax-id-display" style="display:none;">
		<b><span id="tax-id-required" class="required">*</span> <?php echo $entry_tax_id; ?></b>
		<input type="text" name="tax_id" value="" class="large-field" />
	</div>
	
	<div>
		<b><span class="required">*</span> <?php echo $entry_address_1; ?></b>
		<input type="text" name="address_1" value="" class="large-field" />
	</div><div>
		<b><span class="required">*</span> <?php echo $entry_address_2; ?></b>
		<input type="text" name="address_2" value="" class="large-field" />
	</div><div>
		<b><span class="required">*</span> <?php echo $entry_address_3; ?></b>
		<input type="text" name="address_3" value="" class="large-field" />
	</div>
	
	
	<div>
		<b><span class="required">*</span> <?php echo $entry_city; ?></b>
		<input type="text" name="city" value="" class="large-field" />
	</div>
	
	<div>
		<b><span id="payment-postcode-required" class="required">*</span> <?php echo $entry_postcode; ?></b>
		<input type="text" name="postcode" value="<?php echo $postcode; ?>" class="large-field" />
	</div>
	
	<div>
		<b><span class="required">*</span> <?php echo $entry_country; ?></b>
		<select name="country_id" class="large-field">
			<option value=""><?php echo $text_select; ?></option>
			<?php foreach ($countries as $country) { ?>
			<?php if ($country['country_id'] == $country_id) { ?>
			<option value="<?php echo $country['country_id']; ?>" data-iso2="<?php echo $country['iso_code_2']; ?>" selected="selected"><?php echo $country['name']; ?></option>
			<?php } else { ?>
			<option value="<?php echo $country['country_id']; ?>" data-iso2="<?php echo $country['iso_code_2']; ?>"><?php echo $country['name']; ?></option>
			<?php } ?>
			<?php } ?>
		</select>
	</div>
	
	<div>
		<b><span class="required">*</span> <?php echo $entry_zone; ?></b>
		<select name="zone_id" class="large-field">
		</select>
	</div>


</div>

<?php if ($shipping_required) { ?>
<div class="both-line">
  <?php if ($shipping_address) { ?>
  <input type="checkbox" name="shipping_address" value="1" id="shipping" checked="checked" />
  <?php } else { ?>
  <input type="checkbox" name="shipping_address" value="1" id="shipping" />
  <?php } ?>
  <label for="shipping"><?php echo $entry_shipping; ?></label>
</div>
<?php } ?>
<div class="buttons">
  <div class="right">
    <input type="button" value="<?php echo $button_continue; ?>" id="button-guest" class="button" />
  </div>
</div>
<script type="text/javascript"><!--
$('#payment-address input[name=\'customer_group_id\']:checked').live('change', function() {
	var customer_group = [];
	
<?php foreach ($customer_groups as $customer_group) { ?>
	customer_group[<?php echo $customer_group['customer_group_id']; ?>] = [];
	customer_group[<?php echo $customer_group['customer_group_id']; ?>]['company_id_display'] = '<?php echo $customer_group['company_id_display']; ?>';
	customer_group[<?php echo $customer_group['customer_group_id']; ?>]['company_id_required'] = '<?php echo $customer_group['company_id_required']; ?>';
	customer_group[<?php echo $customer_group['customer_group_id']; ?>]['tax_id_display'] = '<?php echo $customer_group['tax_id_display']; ?>';
	customer_group[<?php echo $customer_group['customer_group_id']; ?>]['tax_id_required'] = '<?php echo $customer_group['tax_id_required']; ?>';
<?php } ?>	

	if (customer_group[this.value]) {
		if (customer_group[this.value]['company_id_display'] == '1') {
			$('#company-id-display').show();
		} else {
			$('#company-id-display').hide();
		}
		
		if (customer_group[this.value]['company_id_required'] == '1') {
			$('#company-id-required').show();
		} else {
			$('#company-id-required').hide();
		}
		
		if (customer_group[this.value]['tax_id_display'] == '1') {
			$('#tax-id-display').show();
		} else {
			$('#tax-id-display').hide();
		}
		
		if (customer_group[this.value]['tax_id_required'] == '1') {
			$('#tax-id-required').show();
		} else {
			$('#tax-id-required').hide();
		}	
	}
});

$('#payment-address input[name=\'customer_group_id\']:checked').trigger('change');
//--></script> 
<script type="text/javascript"><!--
$('#payment-address select[name=\'country_id\']').bind('change', function() {
	if (this.value == '') return;
	$.ajax({
		url: 'index.php?route=checkout/checkout/country&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('#payment-address select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('.wait').remove();
		},			
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('#payment-postcode-required').show();
			} else {
				$('#payment-postcode-required').hide();
			}
			
			html = '<option value=""><?php echo $text_select; ?></option>';
			
			if (json['zone'] != '') {
				for (i = 0; i < json['zone'].length; i++) {
        			html += '<option value="' + json['zone'][i]['zone_id'] + '"';
	    			
					if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
	      				html += ' selected="selected"';
	    			}
	
	    			html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
			}
			
			$('#payment-address select[name=\'zone_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#payment-address select[name=\'country_id\']').trigger('change');
//--></script>
<?php if ($init_geo_ip) { ?>
<script type="text/javascript" src="catalog/view/javascript/jquery/geoip.ru.js"></script>
<?php } ?>