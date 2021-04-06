<div class="wrapper">
<?php if ($addresses) { ?>
<input type="radio" name="payment_address" value="existing" id="payment-address-existing" checked="checked" />
<label for="payment-address-existing"><?php echo $text_address_existing; ?></label>
<div id="payment-existing">
  <select name="address_id" style="width: 100%; margin-bottom: 15px;" size="5">
    <?php foreach ($addresses as $address) { ?>
    <?php if ($address['address_id'] == $address_id) { ?>
    <option value="<?php echo $address['address_id']; ?>" selected="selected"><?php echo $address['lastname']; ?> <?php echo $address['firstname']; ?> <?php echo $address['middlename']; ?>, <?php echo $address['country']; ?>, <?php echo $address['zone']; ?>, <?php echo $address['postcode']; ?>, <?php echo $address['naselenniy_punkt']; ?> <?php echo $address['city']; ?>, <?php if ($address['address_1'] == "нет улицы") {echo $address['address_1'];} else{ echo "ул. " . $address['address_1']; }?>, дом <?php echo $address['address_2']; ?>, <?php if ($address['address_4'] != '') {echo "корп. " . $address['address_2']; } ?>, <?php if ($address['address_3'] == "частный дом") {echo $address['address_3'];} else {echo "кв." . $address['address_3'];} ?></option>
    <?php } else { ?>
    <option value="<?php echo $address['address_id']; ?>" selected="selected"><?php echo $address['lastname']; ?> <?php echo $address['firstname']; ?> <?php echo $address['middlename']; ?>, <?php echo $address['country']; ?>, <?php echo $address['zone']; ?>, <?php echo $address['postcode']; ?>, <?php echo $address['naselenniy_punkt']; ?> <?php echo $address['city']; ?>, <?php if ($address['address_1'] == "нет улицы") {echo $address['address_1'];} else{ echo "ул. " . $address['address_1']; }?>, дом <?php echo $address['address_2']; ?>, <?php if ($address['address_4'] != '') {echo "корп. " . $address['address_2']; } ?>, <?php if ($address['address_3'] == "частный дом") {echo $address['address_3'];} else {echo "кв." . $address['address_3'];} ?></option>
    <?php } ?>
    <?php } ?>
  </select>
</div>
<p>
  <input type="radio" name="payment_address" value="new" id="payment-address-new" />
  <label for="payment-address-new"><?php echo $text_address_new; ?></label>
</p>
<?php } ?>
<div id="payment-new" style="display: <?php echo ($addresses ? 'none' : 'block'); ?>;">
  <table class="form">
    <tr>
      <td><span class="required">*</span> <?php echo $entry_lastname; ?></td>
      <td><input type="text" name="lastname" value="" class="large-field" /></td>
    </tr>
	<tr>
      <td><span class="required">*</span> <?php echo $entry_firstname; ?></td>
      <td><input type="text" name="firstname" value="" class="large-field" /></td>
    </tr>
	<tr>
      <td><span class="required">*</span> <?php echo $entry_middlename; ?></td>
      <td><input type="text" name="middlename" value="" class="large-field" /></td>
    </tr>

    <tr style="display:none;">
      <td style=";"><?php echo $entry_company; ?></td>
      <td><input type="text" name="company" value="" class="large-field" /></td>
    </tr>
    <?php if ($company_id_display) { ?>
    <tr style="display:none;">
      <td><?php if ($company_id_required) { ?>
        <span class="required">*</span>
        <?php } ?>
        <?php echo $entry_company_id; ?></td>
      <td><input type="text" name="company_id" value="" class="large-field" /></td>
    </tr>
    <?php } ?>
    <?php if ($tax_id_display) { ?>
    <tr>
      <td><?php if ($tax_id_required) { ?>
        <span class="required">*</span>
        <?php } ?>
        <?php echo $entry_tax_id; ?></td>
      <td><input type="text" name="tax_id" value="" class="large-field" /></td>
    </tr>
    <?php } ?>

	<tr>
      <td><span class="required">*</span> <?php echo $entry_country; ?></td>
      <td><select name="country_id" class="large-field">
          <option value=""><?php echo $text_select; ?></option>
          <?php foreach ($countries as $country) { ?>
          <?php if ($country['name'] == "Российская Федерация") { ?>
          <option value="<?php echo $country['country_id']; ?>" data-iso2="<?php echo $country['iso_code_2']; ?>" selected="selected"><?php echo $country['name']; ?></option>
          <?php } else { ?>
          <option value="<?php echo $country['country_id']; ?>" data-iso2="<?php echo $country['iso_code_2']; ?>" ><?php echo $country['name']; ?></option>
          <?php } ?>
          <?php } ?>
        </select></td>
    </tr>
    <tr>
      <td><span class="required">*</span> <?php echo $entry_zone; ?></td>
      <td><select name="zone_id" class="large-field">
        </select></td>
    </tr>

	<tr>
          <td><span class="required">*</span> <?php echo $entry_naselenniy_punkt; ?></td>
          <td><select name="naselenniy_punkt_id">
              <?php foreach ($naselenniy_punkts as $naselenniy_punkt) { ?>
				<?php if (isset($naselenniy_punkt_id)) { ?>
					<?php if ($naselenniy_punkt['naselenniy_punkt_id'] == $naselenniy_punkt_id) { ?>
						<option value="<?php echo $naselenniy_punkt['naselenniy_punkt_id']; ?>" selected="selected"><?php echo $naselenniy_punkt['name']; ?></option>
					<?php } else { ?>
						<option value="<?php echo $naselenniy_punkt['naselenniy_punkt_id']; ?>"><?php echo $naselenniy_punkt['name']; ?></option>
					<?php } ?>
				<?php } else { ?>
					<?php if ($naselenniy_punkt['naselenniy_punkt_id'] == "2") { ?>
						<option value="<?php echo $naselenniy_punkt['naselenniy_punkt_id']; ?>" selected="selected"><?php echo $naselenniy_punkt['name']; ?></option>
					<?php } else { ?>
						<option value="<?php echo $naselenniy_punkt['naselenniy_punkt_id']; ?>"><?php echo $naselenniy_punkt['name']; ?></option>
					<?php } ?>
				 <?php }?>
			 <?php }?>
            </select>
            </td>
        </tr>

    <tr>
      <td><span class="required">*</span> <span id='punkt'><?php echo "Населенный пункт:"//$entry_city; ?></span></td>
      <td><input type="text" name="city" value="" class="large-field" /></td>
    </tr>
	<tr>
      <td><span class="required">*</span> <?php echo $entry_address_1; ?></td>
      <td><input type="text" name="address_1" value="" class="large-field" /></td>
    </tr>
	<tr>
      <td><span class="required">*</span> <?php echo $entry_address_2; ?></td>
      <td><input type="text" name="address_2" value="" class="large-field" /></td>
    </tr>
	<tr>
      <td><span class="required">*</span> <?php echo $entry_address_3; ?></td>
      <td><input type="text" name="address_3" value="" class="large-field" /></td>
    </tr>

    <tr>
      <td><span id="payment-postcode-required" class="required">*</span> <?php echo $entry_postcode; ?></td>
      <td><input type="text" name="postcode" value="" class="large-field" /></td>
    </tr>

  </table>
</div>
</div>
<div class="buttons">
  <div class="right">
    <input type="button" value="<?php echo $button_continue; ?>" id="button-payment-address" class="button" />
  </div>
</div>
<script type="text/javascript"><!--
$('#payment-address input[name=\'payment_address\']').live('change', function() {
	if (this.value == 'new') {
		$('#payment-existing').hide();
		$('#payment-new').show();
	} else {
		$('#payment-existing').show();
		$('#payment-new').hide();
	}
});
//--></script>
<script type="text/javascript"><!--
$('#payment-address select[name=\'country_id\']').bind('change', function() {
	if (this.value == '') return;
	$.ajax({
		url: 'index.php?route=checkoutf/checkout/country&country_id=' + this.value,
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
