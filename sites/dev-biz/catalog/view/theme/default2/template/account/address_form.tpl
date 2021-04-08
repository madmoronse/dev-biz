<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
    <h2 class="h2-head"><?php echo $text_edit_address; ?></h2>
    <div class="content">
      <table class="form">
	    <tr>
          <td><span class="required">*</span> <?php echo $entry_lastname; ?></td>
          <td><input type="text" name="lastname" value="<?php echo $lastname; ?>" />
            <?php if ($error_lastname) { ?>
            <span class="error"><?php echo $error_lastname; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_firstname; ?></td>
          <td><input type="text" name="firstname" value="<?php echo $firstname; ?>" />
            <?php if ($error_firstname) { ?>
            <span class="error"><?php echo $error_firstname; ?></span>
            <?php } ?></td>
        </tr>
		<tr>
			<td><span class="required">*</span> <?php echo $entry_middlename; ?></td>
			<td><input type="text" name="middlename" value="<?php echo $middlename; ?>" />
			<?php if ($error_middlename) { ?>
			<span class="error"><?php echo $error_middlename; ?></span>
			<?php } ?></td>
		</tr>

        <tr style="display:none;">
          <td><?php echo $entry_company; ?></td>
          <td><input type="text" name="company" value="<?php echo $company; ?>" /></td>
        </tr>
        <?php if ($company_id_display) { ?>
        <tr style="display:none;">
          <td><?php echo $entry_company_id; ?></td>
          <td><input type="text" name="company_id" value="<?php echo $company_id; ?>" />
            <?php if ($error_company_id) { ?>
            <span class="error"><?php echo $error_company_id; ?></span>
            <?php } ?></td>
        </tr>
        <?php } ?>
        <?php if ($tax_id_display) { ?>
        <tr>
          <td><?php echo $entry_tax_id; ?></td>
          <td><input type="text" name="tax_id" value="<?php echo $tax_id; ?>" />
            <?php if ($error_tax_id) { ?>
            <span class="error"><?php echo $error_tax_id; ?></span>
            <?php } ?></td>
        </tr>
        <?php } ?>
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
          <td><span class="required">*</span> <?php echo $entry_naselenniy_punkt; ?></td>
          <td><select name="naselenniy_punkt_id">              
              <?php foreach ($naselenniy_punkts as $naselenniy_punkt) { ?>
				<?php if ($naselenniy_punkt_id != '' ) { ?>
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
          <td><span class="required">* </span> <span id='punkt'><?php echo "Населенный пункт:"//$entry_city; ?></span></td>
          <td><input type="text" name="city" value="<?php echo $city; ?>" />
            <?php if ($error_city) { ?>
            <span class="error"><?php echo $error_city; ?></span>
            <?php } ?></td>
        </tr>
       
        <tr>
          <td><span class="required">*</span> <?php echo $entry_country; ?></td>
          <td><select name="country_id" onchange="CountryChange(this)" style = "width:255px;">
              <option value=""><?php echo $text_select; ?></option>
              <?php foreach ($countries as $country) { ?>
              <?php if ($country['name'] == "Российская Федерация") { ?>
              <option value="<?php echo $country['country_id']; ?>" data-iso2="<?php echo $country['iso_code_2']; ?>" selected="selected"><?php echo $country['name']; ?></option>
              <?php } else if ($country['country_id'] == '220' or $country['country_id'] == '109' or $country['country_id'] == '20') { ?>
              <option value="<?php echo $country['country_id']; ?>" data-iso2="<?php echo $country['iso_code_2']; ?>" ><?php echo $country['name']; ?></option>
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
        <tr>
		 <tr>
          <td><span id="postcode-required" class="required">*</span> <?php echo $entry_postcode; ?></td>
          <td><input type="text" name="postcode" value="<?php echo $postcode; ?>" />
            <?php if ($error_postcode) { ?>
            <span class="error"><?php echo $error_postcode; ?></span>
            <?php } ?></td>
        </tr>
		
		 <tr>
          <td><?php echo $entry_telephone; ?></td>
          <td><input type="text" placeholder="7-987-654-32-10" name="telephone" value="<?php echo $telephone; ?>" />
          <?php if ($error_telephone) { ?>
            <span class="error"><?php echo $error_telephone; ?></span>
            <?php } ?></td>
        </tr>
		
		 <tr>
          <td><?php echo $entry_social; ?></td>
          <td><input type="text" name="social" value="<?php echo $social; ?>" /></td>
        </tr>
		
          <td><?php echo $entry_default; ?></td>
          <td><?php if ($default) { ?>
            <input type="radio" name="default" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="default" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="default" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="default" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
      </table>
    </div>
    <div class="buttons">
      <div class="left"><a href="<?php echo $back; ?>" class="button"><?php echo $button_back; ?></a></div>
      <div class="right">
        <input type="submit" value="<?php echo $button_continue; ?>" class="button" />
      </div>
    </div>
  </form>
  <?php echo $content_bottom; ?></div>
  
  <script type="text/javascript"><!--
$('#no_street').on( "click", function(){	
if ($('#no_street').is(':checked')) {
$('input[name=\'address_1\']').val('нет улицы');
$('input[name=\'address_1\']').prop('readonly', true);
} else{
$('input[name=\'address_1\']').val('');
$('input[name=\'address_1\']').prop('readonly', false);
}
});

$('#no_flat').on( "click", function(){	
if ($('#no_flat').is(':checked')) {
$('input[name=\'address_3\']').val('частный дом');
$('input[name=\'address_3\']').prop('readonly', true);
} else{
$('input[name=\'address_3\']').val('');
$('input[name=\'address_3\']').prop('readonly', false);
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
$('select[name=\'country_id\']').bind('change', function() {
	$.ajax({
		url: 'index.php?route=account/address/country&country_id=' + this.value,
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