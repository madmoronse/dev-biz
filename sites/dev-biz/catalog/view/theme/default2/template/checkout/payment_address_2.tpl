<div >
  <script type="text/javascript">
    var setGeoData = function(){
      var result = Array(),
      adress = {
        np:$('#np').val(),
        // zone:$('#zone').val(),
        // area:$('#area').val(),
        // city:$('#city').val(),
        street:$('#address_1').val(),
        home:$('#address_2').val()
      };
      for (key in adress) {
        if(adress[key] != ''){
          result.push(adress[key]);
        }
      }
      return result.join(',');
    }
    var drawResult = function() {
	  //Neos DEBUG
	  $.ajax({
		"url":"/neos_debug/geocoder_log.php",
		"type":"POST",
		"data": {"query": setGeoData(), "context":"checkout"}
	  });
	  // Neos DEBUG -- END
      var myGeocoder = ymaps.geocode(setGeoData(),{results: 1});
      myGeocoder.then(
        function (res) {
            map.geoObjects.removeAll();
            map.geoObjects.add(res.geoObjects);
            map.setBounds(map.geoObjects.getBounds());
            map.setZoom(15);
        },
        function (err) {
            // обработка ошибки
        }
      );
    }
  </script>
<?php /* if ($addresses) { ?>
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
<?php } */ ?>
<?php foreach($addresses as $address) { $addr = $address; break; } ?>
<div id="payment-new">

  <? /*  <tr>
      <td><span class="required">*</span> <?php echo $entry_lastname; ?></td>
      <td><input type="text" name="lastname" value="<?= ($addresses) ? $addr['lastname'] :""?>" class="large-field" /></td>
    </tr>
    new form
    */?>
      <div class="payment-form">
        <div class="payment-form__item">
          <span class="required">*</span><label for="zone_id"><?php echo $entry_np; ?></label>
          <input type="text" id="np" name="np" value="<?= ($addresses) ? $addr['city'] :""?>" class="large-field" />
          <input type="hidden" id="is_db" name="is_db" value="" />
          <script type="text/javascript">
            $(document).ready(function() {
              $( "#np" ).autocomplete({
                source: function( request, response ) {
                  $.ajax( {
                    url: "index.php?route=checkout/payment_address/address",
                    dataType: "json",
                    data: {
                      q: request.term,
                      thing: 'np'
                    },
                    success: function( data ) {
                      //console.log(data);
                      // Handle 'no match' indicated by [ "" ] response
                      response( data.length === 1 && data[ 0 ].length === 0 ? [] : data );
                    }
                  } );
                },
                minLength: 2
                // select: function( event, ui ) {
                //   log( "Selected: " + ui.item.label );
                // }
              } );
            });
          </script>
          <?php /* <select class="large-field"  name="zone_id" id="zone_id" class="large-field">

            foreach($zones as $zone){
              if($zone['zone_id'] == $addr['zone_id']){
                echo "<option value='{$zone["zone_id"]}' selected>{$zone['name']}</option>";
              }else{
                echo "<option value='{$zone["zone_id"]}'>{$zone['name']}</option>";
              }

            }

          </select>  */ ?>
        </div>
        <? /* ?>
        <div class="payment-form__item">
          <span class="required">*</span><label for="zone_id"><?php echo $entry_zone; ?></label>
          <input type="text" id="zone" name="zone" value="<?= ($addresses) ? $addr['zone'] :""?>" class="large-field" />
          <script type="text/javascript">
            $(document).ready(function() {
              $( "#zone" ).autocomplete({
                source: function( request, response ) {
                  $.ajax( {
                    url: "index.php?route=checkout/payment_address/address",
                    dataType: "json",
                    data: {
                      q: request.term,
                      thing: 'zone'
                    },
                    success: function( data ) {
                      //console.log(data);
                      // Handle 'no match' indicated by [ "" ] response
                      response( data.length === 1 && data[ 0 ].length === 0 ? [] : data );
                    }
                  } );
                },
                minLength: 2
                // select: function( event, ui ) {
                //   log( "Selected: " + ui.item.label );
                // }
              } );
            });
          </script>
          <?php
           <select class="large-field"  name="zone_id" id="zone_id" class="large-field">

            foreach($zones as $zone){
              if($zone['zone_id'] == $addr['zone_id']){
                echo "<option value='{$zone["zone_id"]}' selected>{$zone['name']}</option>";
              }else{
                echo "<option value='{$zone["zone_id"]}'>{$zone['name']}</option>";
              }

            }

          </select>
           ?>
        </div>
        <div class="payment-form__item">
          <label for="area"><?php echo $entry_area; ?></label>
          <input type="text" id="area" name="area" value="<?= ($addresses) ? $addr['area'] :""?>" class="large-field" />
          <script type="text/javascript">
            $(document).ready(function() {
              $( "#area" ).autocomplete({
                source: function( request, response ) {
                  $.ajax( {
                    url: "index.php?route=checkout/payment_address/address",
                    dataType: "json",
                    data: {
                      q: request.term,
                      thing: 'area',
                      zone: $('#zone').val()
                    },
                    success: function( data ) {
                      //console.log(data);
                      // Handle 'no match' indicated by [ "" ] response
                      response( data.length === 1 && data[ 0 ].length === 0 ? [] : data );
                    }
                  } );
                },
                minLength: 2
                // select: function( event, ui ) {
                //   log( "Selected: " + ui.item.label );
                // }
              } );
            });
          </script>
        </div>
        <div class="payment-form__item">
          <span class="required">*</span><label for="city"><?php echo $entry_city; ?></label>
          <input type="text" id="city" name="city" value="<?= ($addresses) ? $addr['city'] :""?>" class="large-field" />
          <script type="text/javascript">
            $(document).ready(function() {
              $( "#city" ).autocomplete({
                source: function( request, response ) {
                  $.ajax( {
                    url: "index.php?route=checkout/payment_address/address",
                    dataType: "json",
                    data: {
                      q: request.term,
                      thing: 'city',
                      area: $('#area').val(),
                      zone: $('#zone').val()
                    },
                    success: function( data ) {
                      //console.log(data);
                      // Handle 'no match' indicated by [ "" ] response
                      response( data.length === 1 && data[ 0 ].length === 0 ? [] : data );
                    }
                  } );
                },
                minLength: 2
                // select: function( event, ui ) {
                //   log( "Selected: " + ui.item.label );
                // }
              } );
            });
          </script>
        </div>
        <? */ ?>
        <div class="payment-form__item">
          <span class="required">*</span><label for="address_1"><?php echo $entry_address_1; ?></label>
          <input type="text" id="address_1" name="address_1" autocomplete="off" value="<?= ($addresses) ? $addr['address_1'] :""?>" class="large-field" />
        </div>
        <div class="payment-form__group">
          <div class="payment-form__item inline-item">
            <span class="required">*</span><label for="address_2"><?php echo $entry_address_2; ?></label>
            <input type="text" id="address_2" name="address_2" autocomplete="off" value="<?= ($addresses) ? $addr['address_2'] :""?>" class="large-field" />
          </div>
          <div class="payment-form__item inline-item">
            <label for="address_3"><?php echo $entry_address_3; ?></label>
            <input type="text" id="address_3" name="address_3" autocomplete="off" value="<?= ($addresses) ? $addr['address_3'] :""?>" class="large-field" />
          </div>
          <div class="payment-form__item inline-item">
            <label for="address_4"><?php echo $entry_address_4; ?></label>
            <input type="text" id="address_4" name="address_4" autocomplete="off" value="<?= ($addresses) ? $addr['address_4'] :""?>" class="large-field" />
          </div>
        </div>
        <div class="payment-form__item">
          <label for="postcode"><?php echo $entry_postcode; ?></label>
          <input type="text" id="postcode" name="postcode" autocomplete="off" value="<?= ($addresses) ? $addr['postcode'] :""?>" class="large-field" />
          <p class="help-block">Можно не заполнять, если не знаете</p>
        </div>
        <div class="payment-form__item">
          <label for="postcode"><?php echo $entry_comments; ?></label>
          <textarea name="comment" id="comment" class="large-field"><?=$comment ?></textarea>
          <p class="help-block">Укажите дополнительную информацию, которую вы считаете важной</p>
        </div>
      </div>
      <div class="payment-information">
        <div id="ymap">

        </div>
        <script type="text/javascript">
        var map;
        var address;
        $(document).ready(function() {
          ymaps.ready(function() {
            map = new ymaps.Map("ymap", {
                center: [55.76, 37.64],
                zoom: 3,
                maxZoom: 18,
                controls: []
            });
            // var myButton = new ymaps.control.Button(
            //     '<b>Указать адрес</b>'
            //   );
            // var myPlacemark;
            // myPlacemark = new ymaps.Placemark(map.getCenter(), {}, {
            //     preset: 'islands#redIcon',
            //     draggable: true
            // });
          //  map.geoObjects.add(myPlacemark);
            // myButton.events
            //   .add(
            //     'press',
            //     function () {
            //
            //     }
            //   )
            //   .add(
            //     'select',
            //     function () {
            //       myPlacemark.geometry.setCoordinates(map.getCenter());
            //       map.geoObjects.removeAll();
            //       map.geoObjects.add(myPlacemark);
            //       myPlacemark.options.set('draggable',true);
            //     }
            //   )
            //   .add(
            //     'deselect',
            //     function () {
            //       //var coords = myPlacemark.geometry;
            //       //console.log(coords);
            //       //console.log(ymaps.geocode(coords.bounds[0]));
            //         ymaps.geocode(myPlacemark.geometry._coordinates,{kind: 'house', results: 1}).then(function (res) {
            //         // Переберём все найденные результаты и
            //         // запишем имена найденный объектов в массив names.
            //         res.geoObjects.each(function (obj) {
            //             address = obj;
            //         });
            //         $('#zone').val(address.getAdministrativeAreas()[0]);
            //         $('#area').val(address.getAdministrativeAreas()[1]);
            //         $('#city').val(address.getLocalities()[0]);
            //         $('#address_1').val(address.getThoroughfare());
            //         $('#address_2').val(address.getPremiseNumber());
            //
            //     });
            //       myPlacemark.options.set('draggable',false);
            //
            //     }
            //   );
            // map.controls.add(myButton, {
            //   float: "left"
            // });
          })
          $("#np, #address_1, #address_2").on("change", function() {
            drawResult();
          })





        });

        </script>
      </div>

    <? /*
	  <tr>
      <td><span class="required">*</span> <?php echo $entry_middlename; ?></td>
      <td><input type="text" name="middlename" value="<?= ($addresses) ? $addr['middlename'] :""?>" class="large-field" /></td>
    </tr>

    <tr style="display:none;">
      <td style=";"><?php echo $entry_company; ?></td>
      <td><input type="text" name="company" value="<?= ($addresses) ? $addr['company'] :""?>" class="large-field" /></td>
    </tr>
    <?php if ($company_id_display) { ?>
    <tr style="display:none;">
      <td><?php if ($company_id_required) { ?>
        <span class="required">*</span>
        <?php } ?>
        <?php echo $entry_company_id; ?></td>
      <td><input type="text" name="company_id" value="<?= ($addresses) ? $addr['company_id'] :""?>" class="large-field" /></td>
    </tr>
    <?php } ?>
    <?php if ($tax_id_display) { ?>
    <tr>
      <td><?php if ($tax_id_required) { ?>
        <span class="required">*</span>
        <?php } ?>
        <?php echo $entry_tax_id; ?></td>
      <td><input type="text" name="tax_id" value="<?= ($addresses) ? $addr['tax_id'] :""?>" class="large-field" /></td>
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
				 <?php } ?>
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
    */ ?>

</div>
</div>
<? /*
<div class="buttons">
  <div class="right">
    <input type="button" value="<?php echo $button_continue; ?>" id="button-payment-address" class="button" />
  </div>
</div>
*/ ?>
<script type="text/javascript"><!--
$('.checkout-content input[name=\'payment_address\']').on('change', function() {
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
$('.checkout-content select[name=\'zone_id\']').bind('change', function() {
  return;
	if (this.value == '') return;
	$.ajax({
		url: 'index.php?route=checkout/checkout/zone&zone_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('#payment-address select[name=\'zone_id\']').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
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

			$('.checkout-content select[name=\'zone_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('.checkout-content select[name=\'zone_id\']').trigger('change');
//--></script>
<?php if ($init_geo_ip) { ?>
<script type="text/javascript" src="catalog/view/javascript/jquery/geoip.ru.js"></script>
<?php } ?>
