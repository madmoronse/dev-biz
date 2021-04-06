<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <p class="wrapper"><?php echo $text_account_already; ?></p>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
    <h2 class="h2-head"><?php echo $text_your_details; ?></h2>
    <div class="content">
      <table class="form">
        
      <tr>
          <td><span class="required">*</span> <?php echo $entry_type; ?></td>
          <td>
            <label for="register_type_opt">
              <input type="radio" name="type" value="opt" id="register_type_opt" <?php echo $type === 'opt' ? 'checked' : '' ?> />
              <?php echo $text_opt; ?>
            </label>
            &nbsp;&nbsp;            
            <label for="register_type_drop">
              <input type="radio" name="type" value="drop" id="register_type_drop" <?php echo $type === 'drop' || !$type ? 'checked' : '' ?> />
              <?php echo $text_drop; ?>
            </label>
          </td>
      </tr>

    <tr>
          <td><span class="required">*</span> <?php echo $entry_lastname; ?></td>
          <td><input type="text" name="lastname" value="<?php echo $lastname; ?>" />
            <?php if ($error_lastname) { ?>
            <span class="error"><?php echo $error_lastname; ?></span>
            <?php } ?></td>
        </tr>
		
		<tr>
          <td><span class="required">*</span> <?php echo "Имя" ?></td>
          <td><input type="text" name="firstname" value="<?php echo $firstname; ?>" />
            <?php if ($error_firstname) { ?>
            <span class="error"><?php echo $error_firstname; ?></span>
            <?php } ?></td>
        </tr>
		
		<tr>
          <td><span class="required">*</span> <?php echo "Отчество" ?></td>
          <td><input type="text" name="middlename" value="<?php echo $middlename; ?>" />
            <?php if ($error_middlename) { ?>
            <span class="error"><?php echo $error_middlename; ?></span>
            <?php } ?></td>
        </tr>
				
        
        <tr>
          <td><span class="required">*</span> <?php echo $entry_email; ?></td>
          <td><input type="text" name="email" value="<?php echo $email; ?>" />
            <?php if ($error_email) { ?>
            <span class="error"><?php echo $error_email; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_telephone; ?></td>
          <td><input type="text" name="telephone"  placeholder="7-987-654-32-10" value="<?php echo $telephone; ?>" />
            <?php if ($error_telephone) { ?>
            <span class="error"><?php echo $error_telephone; ?></span>
            <?php } ?></td>
        </tr>

        <tr <?php echo $type === 'opt' ? 'style="display: none;"' : '' ?>>
          <td><span class="required">*</span> <?php echo $entry_qiwi; ?></td>
          <td><input type="text" name="qiwi" value="<?php echo $qiwi; ?>" />
            <?php if ($error_qiwi) { ?>
            <span class="error"><?php echo $error_qiwi; ?></span>
            <?php } ?></td>
        </tr>

        <tr>
          <td><?php echo $entry_fax; ?></td>
          <td><input type="text" name="fax" value="<?php echo $fax; ?>" /></td>
        </tr>

        <tr <?php $type === 'opt' ? 'style="display: none;"' : '' ?>>
          <td><?php echo $entry_teacher; ?></td>
          <td><select name="teacher">
              <option value=""></option>
              <?php foreach ($teachers as $teacher) { ?>
              <option value="<?php echo $teacher['teacher_id']; ?>"><?php echo $teacher['name']; ?></option>
              <?php }?>
            </select>
          </td>
        </tr>

      </table>
    </div>
	
	

    <h2 class="h2-head"><?php echo $text_your_address; ?></h2>
    <div class="content">
      <table class="form">
	  
	  <tr>
          <td><span class="required">*</span> <?php echo $entry_country; ?></td>
          <td><select name="country_id"  onchange="CountryChange(this)" style = "width:255px;">
              <option value=""><?php echo $text_select; ?></option>
              <?php foreach ($countries as $country) { ?>
              <?php /*if ($country['country_id'] == $country_id) { */?>
			  <?php if ($country['country_id'] == '176') { ?>
              <option value="<?php echo $country['country_id']; ?>" data-iso2="<?php echo $country['iso_code_2']; ?>" selected="selected"><?php echo $country['name']; ?></option>
              <?php } else if ($country['country_id'] == '220' or $country['country_id'] == '109' or $country['country_id'] == '140' or $country['country_id'] == '20') { ?>
              <option value="<?php echo $country['country_id']; ?>" data-iso2="<?php echo $country['iso_code_2']; ?>"><?php echo $country['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
            <?php if ($error_country) { ?>
            <span class="error"><?php echo $error_country; ?></span>
            <?php } ?></td>
        </tr>
		
        <tr>
          <td><span class="required">*</span> <?php echo $entry_zone; ?></td>
          <td><select name="zone_id" style = "width:255px;">
            </select>
            <?php if ($error_zone) { ?>
            <span class="error"><?php echo $error_zone; ?></span>
            <?php } ?></td>
        </tr>
	  
	  
        <tr style="display:none;">
          <td style="display:none;"><?php /* echo $entry_company; */ ?></td>
          <td style="display:none;"><input type="text" name="company" value="<?php /* echo $company; */ ?>" style="display:none;" /></td>
        </tr>     
        <tr style="display: <?php echo (count($customer_groups) > 1 ? 'table-row' : 'none'); ?>;">
          <td><?php echo $entry_customer_group; ?></td>
          <td><?php foreach ($customer_groups as $customer_group) { ?>
            <?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
            <input type="radio" name="customer_group_id" value="<?php echo $customer_group['customer_group_id']; ?>" id="customer_group_id<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
            <label for="customer_group_id<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></label>
            <br />
            <?php } else { ?>
            <input type="radio" name="customer_group_id" value="<?php echo $customer_group['customer_group_id']; ?>" id="customer_group_id<?php echo $customer_group['customer_group_id']; ?>" />
            <label for="customer_group_id<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></label>
            <br />
            <?php } ?>
            <?php } ?></td>
        </tr>      
        <tr id="company-id-display" style="display:none;">
          <td style="display:none;"><span id="company-id-required" class="required" style="display:none;">*</span> <?php /* echo $entry_company_id; */ ?></td>
          <td><input type="text" name="company_id" value="<?php /*echo $company_id; */ ?>" style="display:none;"/>
            <?php if ($error_company_id) { ?>
            <span class="error"><?php echo $error_company_id; ?></span>
            <?php } ?></td>
        </tr>
        <tr id="tax-id-display">
          <td><span id="tax-id-required" class="required">*</span> <?php echo $entry_tax_id; ?></td>
          <td><input type="text" name="tax_id" value="<?php echo $tax_id; ?>" />
            <?php if ($error_tax_id) { ?>
            <span class="error"><?php echo $error_tax_id; ?></span>
            <?php } ?></td>
        </tr>
		
		
		<tr>
          <td><span class="required">*</span> <?php echo $entry_naselenniy_punkt; ?></td>
          <td><select name="naselenniy_punkt_id">              
              <?php foreach ($naselenniy_punkts as $naselenniy_punkt) { ?>
				<?php if ($naselenniy_punkt['naselenniy_punkt_id'] == 2) { ?>			  
					<option value="<?php echo $naselenniy_punkt['naselenniy_punkt_id']; ?>" selected="selected"><?php echo $naselenniy_punkt['name']; ?></option>
				<?php } else { ?>
				<option value="<?php echo $naselenniy_punkt['naselenniy_punkt_id']; ?>"><?php echo $naselenniy_punkt['name']; ?></option>
              <?php } }?>
            </select>
            </td>
        </tr>
			
        <tr>
          <td><span class="required">*</span> <span id='punkt'><?php echo $entry_city; ?></span></td>
          <td><input type="text" name="city" value="<?php echo $city; ?>" />
            <?php if ($error_city) { ?>
            <span class="error"><?php echo $error_city; ?></span>
            <?php } ?></td>
        </tr>
		<tr>
          <td><span class="required">*</span> <?php echo $entry_address_1; ?></td>
          <td><input type="text" name="address_1" value="<?php echo $address_1; ?>" /><label><input type="checkbox" id="no_street"/>Нет улицы</label>
            <?php if ($error_address_1) { ?>
            <span class="error"><?php echo $error_address_1; ?></span>
            <?php } ?></td>
        </tr>
		<tr>
          <td><span class="required">*</span> <?php echo $entry_address_2; ?></td>
          <td><input type="text" name="address_2" value="<?php echo $address_2; ?>" />
            <?php if ($error_address_2) { ?>
            <span class="error"><?php echo $error_address_2; ?></span>
            <?php } ?></td>
        </tr>
		<tr>
          <td><?php echo $entry_address_4; ?></td>
          <td><input type="text" name="address_4" value="<?php echo $address_4; ?>" /></td>
        </tr>		
		<tr>
          <td><span class="required">*</span> <?php echo $entry_address_3; ?></td>
          <td><input type="text" name="address_3" value="<?php echo $address_3; ?>" /><label><input type="checkbox" id="no_flat"/>Частный дом</label>
            <?php if ($error_address_3) { ?>
            <span class="error"><?php echo $error_address_3; ?></span>
            <?php } ?></td>
        </tr>

        
        <tr>
          <td><span id="postcode-required" class="required">*</span> <?php echo $entry_postcode; ?></td>
          <td><input type="text" name="postcode" value="<?php echo $postcode; ?>" />
            <?php if ($error_postcode) { ?>
            <span class="error"><?php echo $error_postcode; ?></span>
            <?php } ?></td>
        </tr>
        
      </table>
    </div>
    <h2 class="h2-head"><?php echo $text_your_password; ?></h2>
    <div class="content">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $entry_password; ?></td>
          <td><input type="password" name="password" value="<?php echo $password; ?>" />
            <?php if ($error_password) { ?>
            <span class="error"><?php echo $error_password; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_confirm; ?></td>
          <td><input type="password" name="confirm" value="<?php echo $confirm; ?>" />
            <?php if ($error_confirm) { ?>
            <span class="error"><?php echo $error_confirm; ?></span>
            <?php } ?></td>
        </tr>
      </table>
    </div>
      <h2 class="h2-head"><?php echo $text_first_contact_method; ?></h2>
      <div class="content">
          <table class="form">
              <tr>
                  <td></td>
                  <td>
                      <input type="radio" name="first_contact_method" value="телефон" <?php if ($first_contact_method == 'телефон' ) { echo 'checked="checked"'; }?> />
                      По телефону
                      <br/>
                      <input type="radio" name="first_contact_method" value="ВКонтакте или WhatsApp" <?php if ($first_contact_method == 'ВКонтакте или WhatsApp' ) { echo 'checked="checked"'; }?> />
                      написать во ВКонтакте или WhatsApp

                      <?php if ($error_first_contact_method) { ?>
                          <span class="error"><?php echo $error_first_contact_method; ?></span>
                      <?php } ?>
                  </td>
              </tr>
          </table>
      </div>
    <h2 class="h2-head"><?php echo $text_newsletter; ?></h2>
    <div class="content">
      <table class="form">
        <tr>
          <td><?php echo $entry_newsletter; ?></td>
          <td><?php if ($newsletter) { ?>
            <input type="radio" name="newsletter" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="newsletter" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="newsletter" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="newsletter" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
      </table>
    </div>
    <?php if ($text_agree) { ?>
    <div class="buttons">
      <div class="right"><?php echo $text_agree; ?>
        <?php if ($agree) { ?>
        <input type="checkbox" name="agree" value="1" checked="checked" />
        <?php } else { ?>
        <input type="checkbox" name="agree" value="1" />
        <?php } ?>
        <input type="submit" value="<?php echo $button_continue; ?>" class="button" />
      </div>
    </div>
    <?php } else { ?>
    <div class="buttons">
      <div class="right">
        <input type="submit" value="<?php echo $button_continue; ?>" class="button" />
      </div>
    </div>
    <?php } ?>
  </form>
  <?php echo $content_bottom; ?></div>
  
 <script type="text/javascript"><!--
$('#no_street').on( "click", function(){	
if ($('#no_street').is(':checked')) {
$('input[name=\'address_1\']').val('Нет улицы');
$('input[name=\'address_1\']').prop('readonly', true);
} else{
$('input[name=\'address_1\']').val('');
$('input[name=\'address_1\']').prop('readonly', false);
}
});

$('#no_flat').on( "click", function(){	
if ($('#no_flat').is(':checked')) {
$('input[name=\'address_3\']').val('Частный дом');
$('input[name=\'address_3\']').prop('readonly', true);
} else{
$('input[name=\'address_3\']').val('');
$('input[name=\'address_3\']').prop('readonly', false);
}
});
//--></script>
  
 <script type="text/javascript"><!-- 
 $(document).ready(function() {
    var text1 = $('input[name=\'address_1\']').val();
	if (text1 == "Нет улицы") {
	$('#no_street').click();
	$('input[name=\'address_1\']').val('Нет улицы');
	$('input[name=\'address_1\']').prop('readonly', true);
	}
	
	var text2 = $('input[name=\'address_3\']').val();
	if (text2 == "Частный дом") {
	$('#no_flat').click();
	$('input[name=\'address_3\']').val('Частный дом');
	$('input[name=\'address_3\']').prop('readonly', true);
	}
});

$('select[name=\'naselenniy_punkt_id\']').bind('change', function(){
text=$('select[name=\'naselenniy_punkt_id\']').find('option:selected').text();
$('#punkt').text(text);
});


//--></script>
 
<script type="text/javascript"><!--
$('input[name=\'customer_group_id\']:checked').live('change', function() {
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

$('input[name=\'customer_group_id\']:checked').trigger('change');
//--></script> 
<script type="text/javascript"><!--
$('select[name=\'country_id\']').bind('change', function() {
	$.ajax({
		url: 'index.php?route=account/register/country&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('.wait').remove();
		},			
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('#postcode-required').show();
			} else {
				$('#postcode-required').hide();
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
			
			$('select[name=\'zone_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('select[name=\'country_id\']').trigger('change');
//--></script> 
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.colorbox').colorbox({
		width: 640,
		height: 480
	});
});
//--></script> 

<script type="text/javascript"><!--
(function() {
  var $qiwi = $('[name="qiwi"]');
  var $qiwiParent = $('[name="qiwi"]').parents('tr');

  var $teacher = $('[name="teacher"]');
  var $teacherParent = $('[name="teacher"]').parents('tr');
  $('[name="type"]').on('change', function(e) {
    var type = $(this).val();
    if (type === 'opt') {
      $qiwiParent.css('display', 'none');
      $teacherParent.css('display', 'none');
    } else {
      $qiwiParent.removeAttr('style');
      $teacherParent.removeAttr('style');
    }
  });
})();




//--></script>

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

<?php echo $footer; ?>