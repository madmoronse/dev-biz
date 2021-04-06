<div class="wrapper">

<?php if ($addresses) { ?>

        <input type="radio" name="shipping_address" value="existing" id="shipping-address-existing" checked="checked" />
        <label for="shipping-address-existing"><?php echo $text_address_existing; ?></label>
        <div id="shipping-existing">
          <p>Начните вводить адрес...</p>
          <input type="text" name="autocomplete" id="input-autocomplete" style="width: 100%; margin-bottom: 10px;" />
          <select name="address_id" size="5">
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

          $('select[name=\'address_id\'] option').bind('click', function(){
              loadShippingMethods();
          })
        </script>


    <p class="shipping-address-list">
      <input type="radio" name="shipping_address" value="new" id="shipping-address-new" />
      <label for="shipping-address-new"><?php echo $text_address_new; ?></label>
    </p>
    <p class="shipping-address-list">
        <input type="radio" name="shipping_address" value="replacement" id="replacement_for_order" />
        <label for="replacement_for_order"><?php echo $replacement_for_order; ?></label>
    </p>
    <div id="shipping-replacement">
        <p>Начните вводить номер заказа...</p>
        <input disabled="disabled" type="text" name="replacement_for" id="replacement_for"/>
        <input type="hidden" id="order_for_replacement_exist"/>
    </div>
<?php } ?>
<div id="shipping-new" class="customer_<?php echo $customer_group_id; ?>" style="display: <?php echo ($addresses ? 'none' : 'block'); ?>;">
    <div class="shipping-address-left">
        <div>
            <div><span class="required">*</span> <?php echo $entry_lastname; ?></div>
            <div><input type="text" name="lastname" value="<?php if (isset($shipping_lastname)) {echo $shipping_lastname;}?>" class="large-field" /></div>
        </div>
        <div>
            <div><span class="required">*</span> <?php echo $entry_firstname; ?></div>
            <div><input type="text" name="firstname" value="<?php if (isset($shipping_firstname)) {echo $shipping_firstname;}?>" class="large-field" /></div>
        </div>
        <div>
            <div><span class="required">*</span> <?php echo $entry_middlename; ?></div>
            <div><input type="text" name="middlename" value="<?php if (isset($shipping_middlename)) {echo $shipping_middlename;}?>" class="large-field" /></div>
        </div>
        <div style="display:none;">
            <div><?php echo $entry_company; ?></div>
            <div><input type="text" name="company" value="" class="large-field" /></div>
        </div>
        <div>
            <div><span class="required">*</span> <?php echo $entry_telephone; ?></div>
            <div>
                <input type="text" placeholder="7-987-654-32-10"  name="telephone" value="<?php if (isset($shipping_telephone)) {echo $shipping_telephone;}?>" class="large-field" />
            </div>
        </div>
    </div>

    <div  class="shipping-address-right">
        <div>
            <div><span id="shipping-postcode-required" class="required">*</span> <?php echo $entry_postcode; ?></div>
            <div><input style="width:247px;" type="text" name="postcode" value="<?php if (isset($shipping_postcode)) {echo $shipping_postcode;}?>" class="large-field" /></div>
        </div>
        <div>
            <div><span class="required">*</span> <?php echo $entry_country; ?></div>
            <div>
                <input id="country" placeholder="Введите страну" autocomplete="off" style="width:247px;" type="text"  value="Российская Федерация" class="large-field" />
                <select name="country_id" class="large-field" style = "width:255px;display: none" onchange="CountryChange(this)">
                    <option value=""><?php echo $text_select; ?></option>
                    <?php foreach ($countries as $country) { ?>
                        <?php if ($country['name'] == "Российская Федерация") { ?>
                            <option value="<?php echo $country['country_id']; ?>" data-iso2="<?php echo $country['iso_code_2']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                        <?php } else /*if ($country['country_id'] == '220' or $country['country_id'] == '109' or $country['country_id'] == '140' or $country['country_id'] == '20')*/ { ?>
                            <option value="<?php echo $country['country_id']; ?>" data-iso2="<?php echo $country['iso_code_2']; ?>" ><?php echo $country['name']; ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
            </div>
        </div>

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

        <div>
            <div><span class="required">*</span> <?php echo $entry_zone; ?></div>
            <input id="zone" autocomplete="off" style="width:247px;" type="text"  value="" placeholder="Введите регион/область" class="large-field" />
            <div><select name="zone_id" class="large-field" style = "width:255px;display:none;"></select></div>
        </div>
        <div style="display:none">
            <div><span class="required">*</span> <?php echo $entry_naselenniy_punkt; ?></div>
            <div>
                <select name="naselenniy_punkt_id">
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
            </div>
        </div>
        <div>
            <div><span class="required">*</span> <span id='punkt'><?php echo "Населенный пункт:"//$entry_city; ?></span></div>
            <div><input type="text" id="shippingCity" name="city" value="<?php if (isset($shipping_city)) {echo $shipping_city;}?>" class="large-field" /></div>
        </div>
        <div>
            <div><span class="required">*</span> <?php echo $entry_address_1; ?></div>
            <div>
                <input type="text" name="address_1" value="<?php if (isset($shipping_address_1)) {echo $shipping_address_1;}?>" class="large-field" />
                <label style="display: none"><input type="checkbox" id="no_street"/>Нет улицы</label>
            </div>
        </div>
        <div  class="address_2">
            <div><span class="required">*</span> <?php echo $entry_address_2; ?></div>
            <div><input type="text" name="address_2" value="<?php if (isset($shipping_address_2)) {echo $shipping_address_2;}?>" class="large-field" /></div>
        </div>
        <div class="address_3">
            <div><span class="required">*</span> <?php echo $entry_address_3; ?></div>
            <div>
                <input type="text" name="address_3" value="<?php if (isset($shipping_address_3)) {echo $shipping_address_3;}?>" class="large-field" />
                <label style="display: none"><input type="checkbox" id="no_flat"/>Частный дом</label>
            </div>
        </div>        
        <div class="address_4">
            <div><?php echo $entry_address_4; ?></div>
            <div>
                <input type="text" name="address_4" value="<?php if (isset($shipping_address_4)) {echo $shipping_address_4;}?>" class="large-field" />
            </div>
        </div>
    </div>


</div>
</div>

<script>

    let replacementOrderData = new Object();
    $('#replacement_for').bind("change keyup input", function() {
        if (this.value.match(/[^0-9]/g)) {
            this.value = this.value.replace(/[^0-9]/g, '');
        }
    });

    $('#replacement_for').bind("keyup click", function() {
        if ($('#replacement_for').val() !== ''){
            $.ajax({
                url: 'index.php?route=checkoutf/shipping_addressd/getOrder',
                type: 'post',
                data: 'order_id=' + $('#replacement_for').val(),
                dataType: 'json',
                success: function (json) {
                    $('#replace_order').remove();
                    console.log(json);
                    replacementOrderData = json;
                    if (json){
                        $('#shipping-replacement').append(`<div id="replace_order" class="autocomplete_result_block" onclick="loadOrderData()"><div id="close_replace_order" class="close_autocomplete_result_block" onclick="close_replace_order()">X</div><b>Заказ №${json.order_id} от ${json.date_added}</b><div>${json.shipping_lastname} ${json.shipping_middlename} ${json.shipping_firstname}</div><div>${json.shipping_country} ${json.shipping_zone}, ${json.shipping_postcode}, ${json.shipping_city}, ${json.shipping_address_1}, ${json.shipping_address_2}, ${json.shipping_address_3}</div><div>${json.shipping_method}</div></div>`);
                        $('#order_for_replacement_exist').val('');
                    } else {
                        $('#shipping-replacement').append(`<div id="replace_order" class="autocomplete_result_block" onclick="loadOrderData()"><div id="close_replace_order" class="close_autocomplete_result_block" onclick="close_replace_order()">X</div><b>Заказ не найден</b></div>`);
                        $('#order_for_replacement_exist').val('false');
                    }
                }
            });
        }
    });

    function close_replace_order(){
        console.log('1');
        $('#replace_order').remove();
    }

    function loadOrderData() {
        if ($('#replace_order').length){
            $('#replace_order').hide();
            $('#shipping-new input[name=\'lastname\']').val(replacementOrderData.shipping_lastname);
            $('#shipping-new input[name=\'middlename\']').val(replacementOrderData.shipping_middlename);
            $('#shipping-new input[name=\'firstname\']').val(replacementOrderData.shipping_firstname);


            let countries = $('#shipping-address select[name=\'country_id\'] option');
            let countCountries = countries.length;
            $('#shipping-address select[name=\'country_id\'] option').each(function(){
                $(this).prop('selected', false);
                if (!--countCountries) {
                    $('select[name=\'country_id\'] option[value="'+replacementOrderData.shipping_country_id+'"]').prop('selected', true);
                    $('#shipping-address #country').val($('#shipping-address select[name=\'country_id\'] option:selected').text());
                }
            });

            let zones = $('#shipping-address select[name=\'zone_id\'] option');
            let countZone = zones.length;
            $('#shipping-address select[name=\'zone_id\'] option').each(function(){
                $(this).prop('selected', false);
                if (!--countZone) {
                    $('select[name=\'zone_id\'] option[value="'+replacementOrderData.shipping_zone_id+'"]').prop('selected', true);
                    $('#shipping-address #zone').val($('#shipping-address select[name=\'zone_id\'] option:selected').text());
                }
            });

            $('#shipping-new input[name=\'postcode\']').val(replacementOrderData.shipping_postcode);
            $('#shipping-new input[name=\'city\']').val(replacementOrderData.shipping_city);
            $('#shipping-new input[name=\'address_1\']').val(replacementOrderData.shipping_address_1);
            $('#shipping-new input[name=\'address_2\']').val(replacementOrderData.shipping_address_2);
            $('#shipping-new input[name=\'address_3\']').val(replacementOrderData.shipping_address_3);
            loadShippingMethods();
        }
    }

</script>

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

if ($('#shipping-new').attr('class') == "customer_2" || $('#shipping-new').attr('class') == "customer_4") {
	$('#shipping-address-new').prop('checked', true);
	$('#shipping-existing').hide();
	$('#shipping-replacement').hide();
	$('#shipping-new').show();
}


//--></script>

<script type="text/javascript"><!--
$('#shipping-address input[name=\'shipping_address\']').live('change', function() {


	if (this.value == 'new') {
        $('#shipping-existing').hide();
        $('#replacement_for').attr('disabled', true);
        $('#order_for_replacement_exist').attr('disabled', true);
        $('#shipping-replacement').hide();
        $('#shipping-new').show();

        $('#shipping-method .checkout-content').empty();
        let postcode = $('#shipping-address input[name=\'postcode\']').val();
        if (postcode.length == 6){
            loadShippingMethods();
        }

	} else if ( this.value == 'replacement'){
        $('#shipping-existing').hide();
        $('#replacement_for').attr('disabled', false);
        $('#order_for_replacement_exist').attr('disabled', false);
        $('#shipping-replacement').show();
        $('#shipping-new').show();

        $('#shipping-method .checkout-content').empty();
        let postcode = $('#shipping-address input[name=\'postcode\']').val();
        if (postcode.length == 6) {
            loadShippingMethods();
        }

	} else {
        $('#shipping-method .checkout-content').empty();
        $('#shipping-existing').show();
        $('#replacement_for').attr('disabled', true);
        $('#order_for_replacement_exist').attr('disabled', true);
        $('#shipping-replacement').hide();
        $('#shipping-new').hide();
	}
});

    let postcode = $('#shipping-address input[name=\'postcode\']').val();
    if (postcode.length == 6){
        loadShippingMethods();
    }
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
