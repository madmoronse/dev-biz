<div >
  <script type="text/javascript">
    var setGeoData = function() {
      var result = Array(),
      adress = {
          np:$('#np').val(),
          street:$('#address_1').val(),
          home:$('#address_2').val()
      };
      for (key in adress) {
          if (adress[key] != '') {
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
        // обработка ошибки
        function (err) {}
      );
    }
  </script>
<?php foreach($addresses as $address) { $addr = $address; break; } ?>
<div id="payment-new">
        <?php if ($addresses && $customer_group > 1) { ?>
        <div class="tab-control">
          <a class="btn btn--regular btn--sm is-active" href="#" data-tab="#checkout-new-address"><?php echo $text_address_new; ?></a>
          <a class="btn btn--regular btn--sm" href="#" data-tab="#checkout-existing-address"><?php echo $text_address_existing; ?></a>
        </div>
        <?php } ?>
        <div class="tab tab--is-active" id="checkout-new-address">  
        <form class="payment-form" id="checkout-form">        
          <div class="payment-form__item">
          <span class="required">*</span><label for="zone_id"><?php echo $entry_np; ?></label>
          <input type="text" id="np" name="np" value="<?= ($addresses) ? $addr['city'] :""?>" class="large-field" />
          <input type="hidden" id="is_db" name="is_db" value="" />
          <input type="hidden" name="payment_address" value="new" />
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
                      response( data.length === 1 && data[ 0 ].length === 0 ? [] : data );
                    }
                  } );
                },
                minLength: 2
              });
            });
          </script>
        </div>
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
          <textarea name="comment" id="comment" class="large-field"><?= $comment ?></textarea>
          <p class="help-block">Укажите дополнительную информацию, которую вы считаете важной</p>
        </div>
      </form>
      <div class="payment-information">
        <div id="ymap"></div>
      </div>
      </div>
      <?php if ($addresses && $customer_group > 1) { ?>
          <div class="tab" id="checkout-existing-address">
            <form>
            <input type="hidden" name="payment_address" value="existing" />
            <input type="hidden" name="address_id" id="address_id" value="<?= $address_id ?>" />
            <div id="payment-existing">
              <div class="payment-form__item">
                <input type="text" id="autocomplete-address" name="autocomplete-address" autocomplete="off" class="large-field" style="width: 50%"/>
                <p class="help-block">Начните вводить адрес...</p>
              </div>
              <div class="checkout-address__list" >
                <?php foreach ($addresses as $address) { ?>
                <?php $class = ($address['address_id'] == $address_id) ? "is-active" : ""; ?>
                <div data-value="<?php echo $address['address_id']; ?>" class="checkout-address__item <?= $class ?>">
                <?php  
                  echo $address['lastname'] . " "
                      . $address['firstname'] . " "
                      . $address['middlename'] . ", " 
                      . $address['country'] . ", "
                      . $address['zone'] . ", " 
                      . $address['postcode'] . ", "
                      . $address['naselenniy_punkt']
                      . $address['city'] . ", "; 
                  echo ($address['address_1'] == "нет улицы") ? $address['address_1'] : "ул. " . $address['address_1']; 
                  echo ", дом " . $address['address_2'] . ", ";
                  if ($address['address_4'] != '') {
                    echo "корп. " . $address['address_4'] . ","; 
                  }  
                  echo ($address['address_3'] == "частный дом") ? $address['address_3'] : "кв." . $address['address_3'];
                  ?>
                </div>
                <?php } ?>
              </div>
            </div>
            </form> 
        </div>

      <?php }  ?>
      <script type="text/javascript">
        $('#autocomplete-address').autocompleteAnywhere({
          "source": "#payment-existing .checkout-address__item"
        });
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
          });
          var drawTimeout;
          $("#np, #address_1, #address_2").on("change", function() {
            clearTimeout(drawTimeout);
            drawTimeout = setTimeout(function() { drawResult(); }, 2000);
          })
        });
        new Address().init();
      </script>
</div>
</div>



