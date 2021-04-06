<div class="registration" id="contact_block">
  <h2><?php echo $text_your_details; ?></h2>
	<div style="display:none">
		<b><span class="required">*</span><?php echo $entry_lastname; ?></b>
		<input type="text" name="lastname" placeholder="Иванов Иван Иванович" value="" class="large-field" />
	</div>
	<div style="margin-top: 30px;">
		<b><span class="required">*</span>Ваше Ф.И.О.</b>
		<input type="text" name="firstname" placeholder="Иванов Иван Иванович" value="" class="large-field" />
  </div>

  	<div style="display:none">
		<b><span class="required">*</span> <?php echo  $entry_middlename; ?></b>
		<input type="text" name="middlename" value="" class="large-field" />
  </div>

	<div>
		<b><span class="required">*</span> <?php echo $entry_email; ?></b>
		<input type="text" name="email" id="email" value="" placeholder="mail@xxxx.xx" class="large-field" />
	</div>
	<div style="margin-bottom: 30px">
		<b><span class="required">*</span> <?php echo $entry_telephone; ?></b>
		<input type="text" name="telephone" placeholder="+7-xxx-xxx-xx-xx" value="" class="large-field" />
	</div>
	<div style="margin-bottom: 30px; display:none">
		<b><?php echo $entry_fax; ?></b>
		<input type="text" name="fax" value="" class="large-field" />
	</div>

	<div id="register_next">
		<label class="button" id="address_button">Далее</label>
		<label class="error_registration_label" id="address_error"></label>
	</div>
</div>

<div class="registration registration-big" id="address_block">
  <h2><?php echo $text_your_address; ?></h2>

	<div style="margin-top: 30px;display:none;">
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

	 <div id="company-id-display" style="display:none;" >
		<b ><span id="company-id-required" class="required">*</span> <?php echo $entry_company_id; ?></b>
		<input type="text" name="company_id" value="" class="large-field" />
	</div>


	<div id="tax-id-display">
		<b><span id="tax-id-required" class="required">*</span> <?php echo $entry_tax_id; ?></b>
		<input type="text" name="tax_id" value="" class="large-field" />
	</div>



	<div style="margin-top: 30px;">
		<b><span class="required" >*</span> <?php echo $entry_country; ?></b>
		<select name="country_id" class="large-field" onchange="CountryChange(this)">
			<option value=""><?php echo $text_select; ?></option>
			<?php foreach ($countries as $country) { ?>
			<?php if ($country['name'] == "Российская Федерация") { ?>
			<option value="<?php echo $country['country_id']; ?>" data-iso2="<?php echo $country['iso_code_2']; ?>" selected="selected"><?php echo $country['name']; ?></option>
			<?php } else if ($country['country_id'] == '220' or $country['country_id'] == '109' or $country['country_id'] == '140' or $country['country_id'] == '20')  { ?>
			<option value="<?php echo $country['country_id']; ?>" data-iso2="<?php echo $country['iso_code_2']; ?>" ><?php echo $country['name']; ?></option>
			<?php } ?>
			<?php } ?>
		</select>
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
	</div>

	<div>
		<b><span class="required">*</span> <?php echo $entry_zone; ?></b>
		<select name="zone_id" class="large-field">
		</select>
	</div>

	<div>
        <b><span class="required">*</span> <?php echo $entry_naselenniy_punkt; ?></b>
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

	<div>
		<b><span class="required">*</span> <span id='punkt'><?php echo $entry_city; ?></span></b>
		<input type="text" name="city" value="" class="large-field-big" />
	</div>

	<div>
		<b><label style="display:none"><input type="checkbox" id="no_street"/>Нет улицы   </label><span class="required">*</span> Адрес доставки:</b>
		<input type="text" name="address_1" value="" class="large-field-big" />
	</div>

	<div style="display:none">
		<b><span class="required">*</span> <?php echo $entry_address_2; ?></b>
		<input type="text" name="address_2" value="" class="large-field" />
	</div>

	 <div style="display:none">
      <b> <?php echo $entry_address_4; ?></b>
      <input type="text" name="address_4" value="" class="large-field" />
    </div>

	<div style="display:none">
		<b><label><input type="checkbox" id="no_flat"/>Частный дом</label><span class="required">*</span> <?php echo $entry_address_3; ?></b>
		<input type="text" name="address_3" value="" class="large-field" />
	</div>


	<div style="display:none">
		<b><span id="payment-postcode-required" class="required">*</span> <?php echo $entry_postcode; ?></b>
		<input type="text" name="postcode" value="<?php echo $postcode; ?>" class="large-field" />
	</div>

	<div id="register_next">
		<label class="button" id="pass_button">Далее</label>
		<label class="error_registration_label" id="pass_error"></label>
	</div>

</div>


<div class="registration" id="pass_block" style="display:none">
		<h2 class="pass" ><?php echo $text_your_password; ?></h2>
		<div style="margin-top: 30px;">
			<b><span class="required">*</span> <?php echo $entry_password; ?></b>
			<input type="password" name="password" value="" class="large-field" />
		</div>
		<div style="margin-bottom: 30px;">
			<b><span class="required">*</span> <?php echo $entry_confirm; ?> </b>
			<input type="password" name="confirm" value="" class="large-field" />
		</div>

</div>

<div class="newsletter_block" style="display:none">
  <input type="checkbox" name="newsletter" value="1" id="newsletter" />
  <label for="newsletter"><?php echo $entry_newsletter; ?></label>
  <?php if ($shipping_required) { ?>
	<br />
  <input type="checkbox" name="shipping_address" value="1" id="shipping" checked="checked" style="display:none;"/>
  <label for="shipping" style="display:none;"><?php echo $entry_shipping; ?></label>
  <?php } ?>
</div>

<?php if ($text_agree) { ?>
<div class="buttons" id="register-finish" style="display:none">
  <div class="right"><?php echo $text_agree; ?>
    <input type="checkbox" name="agree" value="1" checked="checked" />
    <input type="button" value="<?php echo $button_continue; ?>" id="button-register" class="button" />
  </div>
</div>
<?php } else { ?>
<div class="buttons" id="register-finish" style="display:none">
  <div class="right">
    <input type="button" value="<?php echo $button_continue; ?>" id="button-register" class="button" />
  </div>
</div>
<?php } ?>



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
			/*$('#company-id-display').show();*/
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

<script type="text/javascript"><!--
$('.colorbox').colorbox({
	width: 640,
	height: 480
});
//--></script>
<?php if ($init_geo_ip) { ?>
<script type="text/javascript" src="catalog/view/javascript/jquery/geoip.ru.js"></script>
<?php } ?>


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


$(function(){

	$('#address_button').click(function(){
	$('.error').remove();
	var go_to_address = true;
		if ($('input[name=\'firstname\']').val() == ''){
			$('input[name=\'firstname\']').after("<span class='error'>Введите ваше Ф.И.О.</span>");
			go_to_address = false;
		}
			if ($('input[name=\'telephone\']').val().length < 4){
				$('input[name=\'telephone\']').after("<span class='error'>Слишком короткий телефон</span>");
				go_to_address = false;
			}
			var reg = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,6})+$/;
			if (!reg.test($('#email').val())) {
				$('#email').after("<span class='error'>E-mail введён некорректно</span>");
				go_to_address = false;
			} else {
				var newemail =$('#email').val();
				$.ajax({
					url: 'index.php?route=checkoutf/register/testemail/',
					type: 'post',
					data: {
						newemail : newemail
					},
					success: function(result){
						var data = $(result).filter('.error');
						$('#email').after(data);
						go_to_address = false;
					}
				});
			}

			if (go_to_address == true) {
				$('#address_block').slideDown('fast');
				$('#address_error').text('');
				$('#address_error').css( "background-color","#FFF");
				$('#contact_block').hide('slow');

				var newname =$('input[name=\'firstname\']').val();
				var newtelephone =$('input[name=\'telephone\']').val();
				$.ajax({
					url: 'index.php?route=checkoutf/register/addnewuser',
					type: 'post',
					data: {
						newname : newname,
						newemail : newemail,
						newtelephone : newtelephone
					}
				});

			}

			else {
				$('#address_error').text('Заполните все поля для продолжения');
				$('#address_error').css( "background-color","#F8DCDC");
			}
	});

	$('#pass_button').click(function(){
		$('.error').remove();
		var go_to_password = true;

		if ($('select[name=\'zone_id\'] option:selected').val() == ''){
		$('select[name=\'zone_id\']').after("<span class='error'>Выберите ваш регион</span>");
			go_to_password = false;
		}

		if ($('input[name=\'city\']').val() == ''){
		$('input[name=\'city\']').after("<span class='error'>Укажите название вашего населенного пункта</span>");
			go_to_password = false;
		}

		if ($('input[name=\'address_1\']').val() == ''){
		$('input[name=\'address_1\']').after("<span class='error'>Укажите адрес доставки</span>");
			go_to_password = false;
		}

		if (go_to_password == true) {
			$('#pass_error').text('');
			$('#pass_error').css( "background-color","#FFF");
			$('#address_block').hide('slow');
			$('#pass_block').slideDown();
			$('.newsletter_block').slideDown();
			$('#register-finish').slideDown();
		}
			else {
				$('#pass_error').text('Заполните все поля для продолжения');
				$('#pass_error').css( "background-color","#F8DCDC");
			}
	});


		$('#button-register').click(function(){
			var newemail =$('#email').val();
			$.ajax({
				url: 'index.php?route=checkoutf/register/remnewuser',
				type: 'post',
				data: {
					newemail : newemail
				}
			});
		});
});


//--></script>
