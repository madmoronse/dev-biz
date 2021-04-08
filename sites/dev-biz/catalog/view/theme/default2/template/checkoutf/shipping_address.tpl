<div class="wrapper">

<?php if ($addresses) { ?>
<input type="radio" name="shipping_address" value="existing" id="shipping-address-existing" checked="checked" />
<label for="shipping-address-existing"><?php echo $text_address_existing; ?></label>
<div id="shipping-existing">
  <p style="font-size: 12px; margin: 0; margin-top: 10px;">Начните вводить адрес...</p>    
  <input type="text" name="autocomplete" id="input-autocomplete" style="width: 100%; margin-bottom: 10px;" />
  <select name="address_id" style="width: 100%; margin-bottom: 15px;height:300px;" size="5">
    <?php foreach ($addresses as $address) {

	if ($customer_group_id ==2 or $customer_group_id == 4){
	if ($address['address_id'] != $address['main_address_id']) {?>

    <?php if ($address['address_id'] == $address_id) { ?>
    <option value="<?php echo $address['address_id']; ?>" selected="selected"><?php echo $address['lastname']; ?> <?php echo $address['firstname']; ?> <?php echo $address['middlename']; ?>, <?php echo $address['country']; ?>, <?php echo $address['zone']; ?>, <?php echo $address['postcode']; ?>, <?php echo $address['naselenniy_punkt']; ?> <?php echo $address['city']; ?>, <?php if ($address['address_1'] == "нет улицы") {echo $address['address_1'];} else{ echo "ул. " . $address['address_1']; }?>, дом <?php echo $address['address_2']; ?>, <?php if ($address['address_4'] != '') {echo "корп. " . $address['address_4']; } ?>, <?php if ($address['address_3'] == "частный дом") {echo $address['address_3'];} else {echo "кв." . $address['address_3'];} ?><?php if ($address['telephone']) {echo ", (т. " . $address['telephone'] . ")";} ?></option>
    <?php } else { ?>
    <option value="<?php echo $address['address_id']; ?>" selected="selected"><?php echo $address['lastname']; ?> <?php echo $address['firstname']; ?> <?php echo $address['middlename']; ?>, <?php echo $address['country']; ?>, <?php echo $address['zone']; ?>, <?php echo $address['postcode']; ?>, <?php echo $address['naselenniy_punkt']; ?> <?php echo $address['city']; ?>, <?php if ($address['address_1'] == "нет улицы") {echo $address['address_1'];} else{ echo "ул. " . $address['address_1']; }?>, дом <?php echo $address['address_2']; ?>, <?php if ($address['address_4'] != '') {echo "корп. " . $address['address_4']; } ?>, <?php if ($address['address_3'] == "частный дом") {echo $address['address_3'];} else {echo "кв." . $address['address_3'];} ?><?php if ($address['telephone']) {echo ", (т. " . $address['telephone'] . ")";} ?></option>
    <?php } ?>
	<?php } ?>
	<?php } else {?>

	<?php if ($address['address_id'] == $address_id) { ?>
    <option value="<?php echo $address['address_id']; ?>" selected="selected"><?php echo $address['lastname']; ?> <?php echo $address['firstname']; ?> <?php echo $address['middlename']; ?>, <?php echo $address['country']; ?>, <?php echo $address['zone']; ?>, <?php echo $address['postcode']; ?>, <?php echo $address['naselenniy_punkt']; ?> <?php echo $address['city']; ?>, <?php if ($address['address_1'] == "нет улицы") {echo $address['address_1'];} else{ echo "ул. " . $address['address_1']; }?>, дом <?php echo $address['address_2']; ?>, <?php if ($address['address_4'] != '') {echo "корп. " . $address['address_4']; } ?>, <?php if ($address['address_3'] == "частный дом") {echo $address['address_3'];} else {echo "кв." . $address['address_3'];} ?><?php if ($address['telephone']) {echo ", (т. " . $address['telephone'] . ")";} ?></option>
    <?php } else { ?>
    <option value="<?php echo $address['address_id']; ?>" selected="selected"><?php echo $address['lastname']; ?> <?php echo $address['firstname']; ?> <?php echo $address['middlename']; ?>, <?php echo $address['country']; ?>, <?php echo $address['zone']; ?>, <?php echo $address['postcode']; ?>, <?php echo $address['naselenniy_punkt']; ?> <?php echo $address['city']; ?>, <?php if ($address['address_1'] == "нет улицы") {echo $address['address_1'];} else{ echo "ул. " . $address['address_1']; }?>, дом <?php echo $address['address_2']; ?>, <?php if ($address['address_4'] != '') {echo "корп. " . $address['address_4']; } ?>, <?php if ($address['address_3'] == "частный дом") {echo $address['address_3'];} else {echo "кв." . $address['address_3'];} ?><?php if ($address['telephone']) {echo ", (т. " . $address['telephone'] . ")";} ?></option>
    <?php } ?>

    <?php } ?>
    <?php } ?>
  </select>
</div>
<script>
  $('#input-autocomplete').autocompleteAnywhere({
    "source": "#shipping-existing option",
    "hideCallback": function (item) {
      $(item).prop('selected', false);
    }
  });
</script>
<p>
  <input type="radio" name="shipping_address" value="new" id="shipping-address-new" />
  <label for="shipping-address-new"><?php echo $text_address_new; ?></label>
</p>
<?php } ?>
<div id="shipping-new" class="customer_<?php echo $customer_group_id; ?>" style="display: <?php echo ($addresses ? 'none' : 'block'); ?>;">
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
      <td><?php echo $entry_company; ?></td>
      <td><input type="text" name="company" value="" class="large-field" /></td>
    </tr>
    <tr>
	<tr>
      <td><span class="required">*</span> <?php echo $entry_country; ?></td>
      <td><select name="country_id" class="large-field" style = "width:255px;" onchange="CountryChange(this)">
          <option value=""><?php echo $text_select; ?></option>
          <?php foreach ($countries as $country) { ?>
          <?php if ($country['name'] == "Российская Федерация") { ?>
          <option value="<?php echo $country['country_id']; ?>" data-iso2="<?php echo $country['iso_code_2']; ?>" selected="selected"><?php echo $country['name']; ?></option>
          <?php } else if ($country['country_id'] == '220' or $country['country_id'] == '109' or $country['country_id'] == '140' or $country['country_id'] == '20') { ?>
          <option value="<?php echo $country['country_id']; ?>" data-iso2="<?php echo $country['iso_code_2']; ?>" ><?php echo $country['name']; ?></option>
          <?php } ?>
          <?php } ?>
        </select></td>

<SCRIPT LANGUAGE="JavaScript">
<!--
 function CountryChange(combo){
   if(combo.value == '176'){
   }else{
alert('Доставка товара в любые страны, кроме Российской федерации осуществляются только после ПОЛНОЙ предоплаты');
   }
 }
//-->
</SCRIPT>

    </tr>
    <tr>
      <td><span class="required">*</span> <?php echo $entry_zone; ?></td>
      <td><select name="zone_id" class="large-field" style = "width:255px;">
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
      <td><input type="text" id="shippingCity" name="city" value="" class="large-field" /></td>
      <script type="text/javascript">
        // Neos - Disabled by request 31.07.2019
        // $(document).ready(function() {
        //     $( "#shippingCity" ).autocomplete({
        //         source: function( request, response ) {
        //             $.ajax( {
        //                 url: "index.php?route=checkout/payment_address/address",
        //                 dataType: "json",
        //                 data: {
        //                     q: request.term,
        //                     thing: 'shippingCity'
        //                 },
        //                 success: function( data ) {
        //                     //console.log(data);
        //                     // Handle 'no match' indicated by [ "" ] response
        //                     response( data.length === 1 && data[ 0 ].length === 0 ? [] : data );
        //                 }
        //             } );
        //         },
        //         minLength: 2
        //         // select: function( event, ui ) {
        //         //   log( "Selected: " + ui.item.label );
        //         // }
        //     });
        // });
      </script>

    </tr>
      <td><span class="required">*</span> <?php echo $entry_address_1; ?></td>
      <td><input type="text" name="address_1" value="" class="large-field" /><label><input type="checkbox" id="no_street"/>Нет улицы</label></td>
    </tr>

	</tr>
      <td><span class="required">*</span> <?php echo $entry_address_2; ?></td>
      <td><input type="text" name="address_2" value="" class="large-field" /></td>
    </tr>

    </tr>
      <td><?php echo $entry_address_4; ?></td>
      <td><input type="text" name="address_4" value="" class="large-field" /></td>
    </tr>

	</tr>
      <td><span class="required">*</span> <?php echo $entry_address_3; ?></td>
      <td><input type="text" name="address_3" value="" class="large-field" /><label><input type="checkbox" id="no_flat"/>Частный дом</label></td>
    </tr>

    <tr>
      <td><span id="shipping-postcode-required" class="required">*</span> <?php echo $entry_postcode; ?></td>
      <td><input type="text" name="postcode" value="<?php if ($customer_group_id ==1) { echo $postcode;} ?>" class="large-field" /></td>
    </tr>

	<tr>
        <td><span class="required">*</span> <?php echo $entry_telephone; ?></td>
        <td><input type="text" placeholder="7-987-654-32-10"  name="telephone" value="<?php if ($customer_group_id ==1 and isset($telephone)) { echo $telephone;} ?>" class="large-field" /></td>
    </tr>

      <?php if ($this->customer->getCustomerGroupId() == 2){ ?>
      <tr>
          <td> <?php echo $entry_social; ?></td>
      <td><input type="text" name="social" value="<?php echo $social; ?>" class="large-field" /></td>
      </tr>

	<?php } ?>

  </table>
</div>
</div>
<div class="buttons">
  <div class="right">
    <input type="button" value="<?php echo $button_continue; ?>" <?php /*запрещаем учетке для оптовиков покупать товар*/ if (2650 != $this->customer->getId()) echo 'id="button-shipping-address"';?> class="button" />
  </div>
</div>

<script type="text/javascript"><!--
$('#no_street').on( "click", function(){
if ($('#no_street').is(':checked')) {
$('input[name=\'address_1\']').val('нет улицы');
$('input[name=\'address_1\']').prop('disabled', true);
} else{
$('input[name=\'address_1\']').val('');
$('input[name=\'address_1\']').prop('disabled', false);
}
});

$('#no_flat').on( "click", function(){
if ($('#no_flat').is(':checked')) {
$('input[name=\'address_3\']').val('частный дом');
$('input[name=\'address_3\']').prop('disabled', true);
} else{
$('input[name=\'address_3\']').val('');
$('input[name=\'address_3\']').prop('disabled', false);
}
});

$('select[name=\'naselenniy_punkt_id\']').bind('change', function(){
text=$('select[name=\'naselenniy_punkt_id\']').find('option:selected').text();
$('#punkt').text(text);
});

$( document ).ready(function() {
    text=$('select[name=\'naselenniy_punkt_id\']').find('option:selected').text();
	$('#punkt').text(text);
});
//--></script>

<script type="text/javascript"><!--

if ($('#shipping-new').attr('class') == "customer_2" ) {
	$('#shipping-address-new').prop('checked', true);
	$('#shipping-existing').hide();
	$('#shipping-new').show();
}

if ($('#shipping-new').attr('class') == "customer_4" ) {
	$('#shipping-address-new').prop('checked', true);
	$('#shipping-existing').hide();
	$('#shipping-new').show();
}


//--></script>

<script type="text/javascript"><!--
$('#shipping-address input[name=\'shipping_address\']').live('change', function() {
	if (this.value == 'new') {
		$('#shipping-existing').hide();
		$('#shipping-new').show();
	} else {
		$('#shipping-existing').show();
		$('#shipping-new').hide();
	}
});
//--></script>
<script type="text/javascript"><!--
$('#shipping-address select[name=\'country_id\']').bind('change', function() {
	if (this.value == '') return;
	$.ajax({
		url: 'index.php?route=checkoutf/checkout/country&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('#shipping-address select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('.wait').remove();
		},
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('#shipping-postcode-required').show();
			} else {
				$('#shipping-postcode-required').hide();
			}

			html = '<option value=""><?php echo $text_select; ?></option>';

			if (json['zone'] != '') {
				for (i = 0; i < json['zone'].length; i++) {
        			html += '<option value="' + json['zone'][i]['zone_id'] + '"';
	    			if ($('#shipping-new').attr('class') != "customer_2" ) {
					if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
	      				html += ' selected="selected"';
	    			}
					}
	    			html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
			}

			$('#shipping-address select[name=\'zone_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#shipping-address select[name=\'country_id\']').trigger('change');
//--></script>
<?php if ($init_geo_ip) { ?>
<script type="text/javascript" src="catalog/view/javascript/jquery/geoip.ru.js"></script>
<?php } ?>
