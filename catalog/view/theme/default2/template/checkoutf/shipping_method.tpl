<?php
				$akcia_in_cart = false;
				$this->load->model('catalog/product');
				$this->load->model('catalog/category');
				foreach ($this->cart->getProducts() as $product) {
					$categories  = $this->model_catalog_product->getCategories($product['product_id']);
					if ($categories){
						foreach ($categories as $category) {
							if($category['category_id'] == 1163) {
								$akcia_in_cart = true;
							}
						}
					}
				}
				$show_russianpost = true;
				if ($customer_group_id == 4 && isset($shipping_methods['russianmail'])) {
					$show_russianpost = false;
				}
				$russianpost_list = array(
					'ruspostfull.ruspostfull',
					'item.item',
					'ruspostpart.ruspostpart',
					'firstclasspart.firstclasspart',
					'farfull.farfull',
					'farpart.farpart',
					'sng.sng',
					'firstclassfull.firstclassfull'
				);
?>

<div class="wrapper">
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>

<?php if ($_SESSION['customer_id'] == 3589)  { 
		echo '<div id="buybuysu_bc_block"><input id="buybuysu_bc" name="buybuysu_bc" type="checkbox" onclick="blockmargin()"><label for="buybuysu_bc">Отправить заказ без наложенного платежа</label></input></div><hr/>';
	} ?>

		<script>
			function blockmargin() {
				var counts = $('.price_drop_row').length;
				if($('#buybuysu_bc').attr("checked") != 'checked'){
					for (i = 1; i <= counts; i++) {
						$('#total_drop_'+i).text(Number ($('input[name=\'price_drop_'+i+'\']').val()) * Number ($('#quantity_'+i).text()));
						$('#margin_drop_'+i).text(Number ($('input[name=\'price_drop_'+i+'\']').val()) - Number ($('#price_'+i).text()));
						$('#total_margin_drop_'+i).text((Number ($('input[name=\'price_drop_'+i+'\']').val()) - Number ($('#price_'+i).text())) * Number ($('#quantity_'+i).text()));
					}
				} else {
					for (i = 1; i <= counts; i++) {
						$('input[name=\'price_drop_'+i+'\']').val(Number ($('#price_'+i).text()));
						$('#total_drop_'+i).text(Number ($('input[name=\'price_drop_'+i+'\']').val()) * Number ($('#quantity_'+i).text()));
						$('#margin_drop_'+i).text(Number ($('input[name=\'price_drop_'+i+'\']').val()) - Number ($('#price_'+i).text()));
						$('#total_margin_drop_'+i).text(0);
					}
					$('#md').val('0');
				}
			}
		</script>

	<?php if ($customer_group_id == 2 or $customer_group_id == 4) { ?>

		<div id="ReplacementBlock">
			<input id="ReplacementCheckbox" type="checkbox" onclick="hideOrShowReplacementTable()">
			<label for="ReplacementCheckbox">Обмен по прошлому заказу</label>
			</input>
		</div>

		<script>
			function hideOrShowReplacementTable() {
				if($('#replacement_for').length > 0){
					$("#replacement_for").remove();
					$("#addition_text").remove();

				} else{
					$( "#ReplacementBlock" ).append( $( '<label id="addition_text"> № </label><input name="replacement_for" id="replacement_for"  placeholder="Номер заказа по которому обмен"></input>' ) );

					$('#replacement_for').bind("change keyup input click", function() {
							if (this.value.match(/[^0-9]/g)) {
							this.value = this.value.replace(/[^0-9]/g, '');
						}
					});
				}
			}
		</script>
	<?php }?>

	<?php  if ($customer_group_id == 4) { ?>
		<table class="radio_makup">
			<thead>
			<tr>
				<td class="name"><?php echo $column_name; ?></td>
				<td class="model"><?php echo $column_sku; ?></td>
				<td class="quantity"><?php echo $column_quantity; ?></td>
				<td class="price"><?php echo /*$column_price*/ 'Цена без наценки за 1 шт.'; ?></td>
				<td class="price_drop"><?php echo $column_price_drop; ?></td>
				<td class="total"><?php echo /*$column_total*/ 'Итого без наценки'; ?></td>
				<td class="total_drop"><?php echo $column_total_drop; ?></td>
				<td class="margin_drop"><?php echo $column_margin_drop; ?></td>
				<td class="total_margin_drop"><?php echo $column_total_margin_drop; ?></td>
			</tr>
			</thead>
			<tbody>
			<?php $product_num = 0;
			foreach ($products as $product) {
				$product_num = $product_num + 1;?>
				<tr>
					<td class="name"><a tabindex="-1" target="_blank" href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
						<?php foreach ($product['option'] as $option) { ?>
							<br />
							&nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
						<?php } ?></td>
					<td class="model"><?php echo $product['product_id']; ?></td>
					<td class="quantity" id="quantity_<?php echo $product_num; ?>"><?php echo $product['quantity']; ?></td>
					<td class="price"  id="price_<?php echo $product_num; ?>"> <?php echo $product['price']; ?></td>
					<td class="price_drop"><input class="price_drop_row" name="price_drop_<?php echo $product_num; ?>"></input></td>
					<td class="total"><?php echo $product['total']; ?></td>
					<td class="total_drop" id="total_drop_<?php echo $product_num; ?>">0</td>
					<td class="margin_drop" id="margin_drop_<?php echo $product_num; ?>">0</td>
					<td class="total_margin_drop" id="total_margin_drop_<?php echo $product_num; ?>">0</td>
				</tr>
			<?php
                $quantity_total += $product['quantity'];
                $price_total += $product['total'];
			} ?>
            <tr style="font-weight:bold">
                <td id="name_total" colspan="2" style="text-align:right">Итого: </td>
                <td id="quantity_total" ><?php echo $quantity_total; ?></td>
                <td id="price_total" ></td>
                <td id="price_drop_total" ></td>
                <td id="total_total" ><?php echo $price_total; ?></td>
                <td id="total_drop_total" ></td>
                <td id="margin_drop_total" ></td>
                <td id="total_margin_total" ></td>

            </tr>
			</tbody>
		</table>

	<?php	echo '<table class="radio" style="display: inline-block;"><tr style="border-bottom:1px solid #BEDAFD;"><td style="border-left:1px solid #BEDAFD;background:#daeaff;" ><b>Итого ВАША наценка на текущий заказ:</b></td></tr><tr><td><input name="markupdropshipping" id="md" onkeydown="return false;" tabindex="9999" value="Заполните цены с ВАШЕЙ наценкой"></input> руб.</td></tr></table>';

	if ($_SESSION['customer_id'] != 3589){
		echo '<table class="radio" style="display: none;width: 280px;"><tr style="border-bottom:1px solid #BEDAFD;"><td style="border-left:1px solid #BEDAFD;background:#daeaff;" ><b>Укажите сумму предоплаты, которую клиент уже внёс за заказ:</b></td></tr><tr><td><input name="prepayment" id="prepayment"></input> руб.</td></tr></table>';
	}

	echo '<span style="display: inline-block;margin-top: 0px;position: absolute; font-size:24px;line-height:60px;"><b><span>Доставка: </span><span id="shipping_total">0</span></b><br /><b><span>Итого: </span><span id="order_total">0</span></b></span>';

	?>
	<?php if ($shipping_methods) { ?>
		<?php foreach ($shipping_methods as $shipping_method_code => $shipping_method) { ?>
			<?php if ($shipping_method_code === 'cdek') { ?>
				<form id="cdek_extra_options_form">
					<table class="radio">
						<thead>
							<tr>
								<td style="border-left: 1px solid #BEDAFD;background:#daeaff;" colspan="2">
									<b>Дополнительные опции по доставке СДЭК</b>
								</td>
							</tr>	
						<thead>
						<tbody>
							<tr>
								<td><label for="extra_options_try_on">Возможность примерки</label></td>
								<td><input type="checkbox" name="try_on" id="extra_options_try_on"></td>
							</tr>
							<tr>
								<td><label for="extra_options_partial_delivery">Частичная доставка</label></td>
								<td><input type="checkbox" name="partial_delivery" id="extra_options_partial_delivery"></td>
							</tr>					
							<tr>
								<td><label for="extra_options_inventory_inspection">Осмотр вложения</label></td>
								<td><input type="checkbox" name="inventory_inspection" id="extra_options_inventory_inspection"></td>
							</tr>	
						</tbody>
					</table>
				</form>
			<?php } ?>
		<?php } ?>
	<?php } ?>
<script type='text/javascript' ><!--
	$('.price_drop_row').keyup(function calculate_margin() {
	var counts = $('.price_drop_row').length;
	if($('#buybuysu_bc').attr("checked") != 'checked'){
		totalvalue = 0;
		for (i = 1; i <= counts; i++) {
			totalvalue = Number (totalvalue) +  Number ($('input[name=\'price_drop_'+i+'\']').val()) * Number ($('#quantity_'+i).text()) - Number ($('#price_'+i).text()) * Number ($('#quantity_'+i).text());
			$('#total_drop_'+i).text(Number ($('input[name=\'price_drop_'+i+'\']').val()) * Number ($('#quantity_'+i).text()));
			$('#margin_drop_'+i).text(Number ($('input[name=\'price_drop_'+i+'\']').val()) - Number ($('#price_'+i).text()));
			$('#total_margin_drop_'+i).text((Number ($('input[name=\'price_drop_'+i+'\']').val()) - Number ($('#price_'+i).text())) * Number ($('#quantity_'+i).text()));

		}

        $('#total_margin_total').text(totalvalue);
        $('#total_drop_total').text(totalvalue+<?php if(isset($price_total)) echo $price_total;?>);
        var order_total = parseFloat($('#shipping_total').text()) + totalvalue+<?php if(isset($price_total)) echo $price_total;?>;
        $('#order_total').text(order_total);

		if (totalvalue >= 0) {
			if ($('input:radio[name=shipping_method]:checked').length==0){
				$('#md').val(totalvalue);
			} else {
				postcost = $('input:radio[name=shipping_method]:checked').attr('class');
				$('#md').val(totalvalue);
			}

		} else{
			$('#md').val('Слишком маленькая наценка');
		}
		} else {
			for (i = 1; i <= counts; i++) {
				//$('input[name=\'price_drop_'+i+'\']').val(Number ($('#price_'+i).text()));
				$('#total_drop_'+i).text(Number ($('input[name=\'price_drop_'+i+'\']').val()) * Number ($('#quantity_'+i).text()));
				$('#margin_drop_'+i).text(Number ($('input[name=\'price_drop_'+i+'\']').val()) - Number ($('#price_'+i).text()));
				$('#total_margin_drop_'+i).text((Number ($('input[name=\'price_drop_'+i+'\']').val()) - Number ($('#price_'+i).text())) * Number ($('#quantity_'+i).text()));
			}
			$('#md').val('0');
		}
	});
	//--></script>



	<script type='text/javascript' ><!--
	function calculate_margin() {
	var counts = $('.price_drop_row').length;
	if($('#buybuysu_bc').attr("checked") != 'checked'){
		totalvalue = 0;
		for (i = 1; i <= counts; i++) {
			totalvalue = Number (totalvalue) +  Number ($('input[name=\'price_drop_'+i+'\']').val()) * Number ($('#quantity_'+i).text()) - Number ($('#price_'+i).text()) * Number ($('#quantity_'+i).text());
			$('#total_drop_'+i).text(Number ($('input[name=\'price_drop_'+i+'\']').val()) * Number ($('#quantity_'+i).text()));
			$('#margin_drop_'+i).text(Number ($('input[name=\'price_drop_'+i+'\']').val()) - Number ($('#price_'+i).text()));
			$('#total_margin_drop_'+i).text((Number ($('input[name=\'price_drop_'+i+'\']').val()) - Number ($('#price_'+i).text())) * Number ($('#quantity_'+i).text()));

		}

        $('#total_margin_total').text(totalvalue);
        $('#total_drop_total').text(totalvalue+<?php if(isset($price_total)) echo $price_total;?>);
        var order_total = parseFloat($('#shipping_total').text()) + totalvalue+<?php if(isset($price_total)) echo $price_total;?>;
        $('#order_total').text(order_total);

		if (totalvalue >= 0) {
			if ($('input:radio[name=shipping_method]:checked').length==0){
				$('#md').val(totalvalue);
			} else {
				postcost = $('input:radio[name=shipping_method]:checked').attr('class');
				$('#md').val(totalvalue);
			}

		} else{
			$('#md').val('Слишком маленькая наценка');
		}
		} else {
			for (i = 1; i <= counts; i++) {
				//$('input[name=\'price_drop_'+i+'\']').val(Number ($('#price_'+i).text()));
				$('#total_drop_'+i).text(Number ($('input[name=\'price_drop_'+i+'\']').val()) * Number ($('#quantity_'+i).text()));
				$('#margin_drop_'+i).text(Number ($('input[name=\'price_drop_'+i+'\']').val()) - Number ($('#price_'+i).text()));
				$('#total_margin_drop_'+i).text((Number ($('input[name=\'price_drop_'+i+'\']').val()) - Number ($('#price_'+i).text())) * Number ($('#quantity_'+i).text()));
			}
			$('#md').val('0');
		}
	}

	function calculateOrderTotal(elem) {
        var shippingPriceForTotal = $(elem).attr("class");
        $('#shipping_total').text(shippingPriceForTotal);
        calculate_margin();

    }
	//--></script>
	
	<?php }?>

<?php if ($shipping_methods) { ?>
<table class="radio" style="display: table">
	<tr style="border-bottom:1px solid #BEDAFD;">
		<td style="background:#daeaff;" colspan="2"><b><?php echo $text_shipping_method; ?></b></td>
        <?php if ($customer_group_id != 3) { ?> <td style="border-left:1px solid #BEDAFD;background:#daeaff;" ><b>Стоимость доставки</b></td> <?php } ?>
	</tr>

  <?php foreach ($shipping_methods as $shipping_method_code => $shipping_method) { ?>
			<?php if ($customer_group_id == 1) {
					echo '<tr><td colspan="2"><b>' .  $shipping_method["title"] . '</b></td><td style="border-left:1px solid #BEDAFD;"></td></tr>';
				} else {
				$cdek=false;
				if ($shipping_method_code === 'russianmail') {
					echo '<tr><td colspan="2"><b>Доставка Почтой России </b></td><td style="border-left:1px solid #BEDAFD;"></td></tr>';
				}
				foreach ($shipping_method['quote'] as $quote1) {
				if ($customer_group_id == 4 or $customer_group_id == 2) {
					if ($shipping_method["title"] != "400 рублей (При заказе одной единицы товара стоимостью от 700 до 3500 рублей)"){
						//echo '<tr><td colspan="2"><b>' .  $shipping_method["title"] . '</b></td><td style="border-left:1px solid #BEDAFD;"></td></tr>';

						if(in_array($quote1['code'], $russianpost_list) && $show_russianpost){echo '<tr><td colspan="2"><b>Доставка Почтой России </b></td><td style="border-left:1px solid #BEDAFD;"></td></tr>';}
						if($quote1['code']=='ems.ems' or $quote1['code']=='emssng.emssng'){echo '<tr><td colspan="2"><b>Доставка службой EMS </b></td><td style="border-left:1px solid #BEDAFD;"></td></tr>';}
						if (stripos($quote1['code'], "cdek") !== false and $cdek == false) {echo '<tr><td colspan="2"><b>Доставка службой СДЭК </b></td><td style="border-left:1px solid #BEDAFD;"></td></tr>';$cdek = true;}
						if($quote1['code']=='tkkrsk.tkkrsk'){echo '<tr><td colspan="2"><b>Доставка по Красноярску </b></td><td style="border-left:1px solid #BEDAFD;"></td></tr>';}


					}
				}
			}
			}?>
			
  <?php if (!$shipping_method['error']) { ?>
  <?php foreach ($shipping_method['quote'] as $quote) { ?>
  <?php if ($customer_group_id == 3 or $customer_group_id == 4) { ?>
	<?php	if ($quote['code'] != "item.item") { ?>
	<?php 	$allowed_shipping_3 = array('ruspostfull.ruspostfull', 'tkpek.tkpek', 'tkcdekopt.tkcdekopt', 'tkenergia.tkenergia', 'tkanother.tkanother', 'tkkrsk.tkkrsk', 'tkpost.tkpost'); ?>	
	<?php 	if ($customer_group_id == 3 and !in_array($quote['code'], $allowed_shipping_3)) { continue; } ?>
	<?php 	if (in_array($quote['code'], $russianpost_list) && !$show_russianpost) { continue; } ?>

			<tr class="highlight">
				
				<td>
				<input type="radio" class="<?php echo round($quote['cost']); ?>" name="shipping_method" value="<?php echo $quote['code']; ?>" <?php if (stripos($quote['code'], "cdek") !== false AND $akcia_in_cart == true) {echo 'onchange="akciaattention()"';} ?> id="<?php echo $quote['code']; ?>"  onclick="calculate_margin(); calculateOrderTotal(this)"/>
				</td>

				<td >
					<label for="<?php echo $quote['code']; ?>" >
						<?php
							if($quote['code']=='tkanother.tkanother'){echo 'Доставка Другой транспортной компанией';}
							elseif($quote['code']=='tkenergia.tkenergia'){echo 'Доставка транспортной компанией Энергия';}

							//elseif($quote['code']=='tkcdekopt.tkcdekopt'){echo 'Доставка курьерской службой СДЭК';}

							elseif($quote['code']=='tkpek.tkpek'){echo 'Доставка транспортной компанией ПЭК';}
							elseif($quote['code']=='ruspostfull.ruspostfull'){if ($customer_group_id == 3) {echo 'Доставка Почтой России';}else echo 'Доставка Почтой России. Полная предоплата';}
							elseif($quote['code']=='ruspostpart.ruspostpart'){echo 'Доставка Почтой России. Частичная предоплата';}
							elseif($quote['code']=='firstclasspart.firstclasspart'){echo 'Доставка 1 классом по России. Частичная предоплата';}
							elseif($quote['code']=='firstclassfull.firstclassfull'){echo 'Доставка 1 классом по России. Полная предоплата';}
							elseif($quote['code']=='farpart.farpart'){echo 'Доставка Почтой России в удаленный регион. Частичная предоплата';}
							elseif($quote['code']=='farfull.farfull'){echo 'Доставка Почтой России в удаленный регион. Полная предоплата';}
							elseif($quote['code']=='sng.sng'){echo 'Доставка Почтой России в СНГ. Полная предоплата';}
							elseif($quote['code']=='ems.ems'){echo 'Доставка службой EMS. Полная предоплата';}
							elseif($quote['code']=='emssng.emssng'){echo 'Доставка службой EMS в СНГ. Полная предоплата';}
							elseif(stripos($quote['code'], "cdek") !== false || stripos($quote['code'], "russianmail") !== false) {echo $quote['title'];}
							else {echo $quote['title'];}
							
						?>
					</label>
				</td>
          <?php if ($customer_group_id != 2 and $customer_group_id != 3) { echo '<td style="text-align: right;border-left:1px solid #BEDAFD;"><label for="'. $quote["code"]. '">'. $quote["text"] . '</label></td>';}  ?>

  </tr>
  <?php 	} ?>
  <?php } else { ?> 
  <tr class="highlight">
    <td>
      <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" id="<?php echo $quote['code']; ?>" <?php if (stripos($quote['code'], "cdek") !== false AND $akcia_in_cart == true) {echo 'onchange="akciaattention()"';} ?> <?php if ($customer_group_id != 2) {if ($quote['title'] == 'Заказы на сумму от 3500р и выше отправляются только по предоплате.') {echo 'disabled = "disabled"';}} ?> />
    </td>
    <td >
        <label for="<?php echo $quote['code']; ?>" >
            <?php if ($customer_group_id != 2) {echo $quote['title'];}
            if ($customer_group_id == 2) {
                if($quote['code']=='ruspostfull.ruspostfull'){echo 'Доставка Почтой России. Полная предоплата.';}
                if($quote['code']=='item.item'){echo 'Доставка Почтой России. Один товар БЕЗ предоплаты.';}
                if($quote['code']=='ruspostpart.ruspostpart'){echo 'Доставка Почтой России. Частичная предоплата.';}
                if($quote['code']=='firstclasspart.firstclasspart'){echo 'Доставка 1 классом по России. Частичная предоплата.';}
				if($quote['code']=='firstclassfull.firstclassfull'){echo 'Доставка 1 классом по России. Полная предоплата.';}
                if($quote['code']=='farpart.farpart'){echo 'Доставка Почтой России в удаленный регион. Частичная предоплата.';}
				if($quote['code']=='farfull.farfull'){echo 'Доставка Почтой России в удаленный регион. Полная предоплата.';}
                if($quote['code']=='sng.sng'){echo 'Доставка Почтой России в СНГ. Полная предоплата';}
				if($quote['code']=='ems.ems'){echo 'Доставка службой EMS. Полная предоплата.';}
				if($quote['code']=='emssng.emssng'){echo 'Доставка службой EMS. Полная предоплата.';}
				if (stripos($quote['code'], "cdek") !== false || stripos($quote['code'], "russianmail") !== false) {echo $quote['title'];}
            }
            ?>
        </label>
    </td>

		<?php if ($customer_group_id == 2) {
			if (stripos($quote['code'], "cdek") !== false ) {
				echo '<td style="text-align: right;border-left:1px solid #BEDAFD;"><label for="'. $quote["code"]. '">'. $quote["text"] . '</label></td>';
			} else {
				echo '<td style="text-align: right;border-left:1px solid #BEDAFD;"><label for="'. $quote["code"]. '">Вручную</label></td>';
			}
		}?>

		<?php if ($customer_group_id != 2) {
			echo '<td style="text-align: right;border-left:1px solid #BEDAFD;"><label for="'. $quote["code"]. '">'. $quote["text"] . '</label></td>';
		  } ?>

  </tr>

  <?php } ?>
  <?php }} else { ?>
  <tr>
    <td colspan="2"><div class="error"><?php echo $shipping_method['error']; ?></div></td>
  </tr>
  <?php } ?>
  <?php } ?>
</table>
    <?php  if ($customer_group_id == 2) {
        echo '<table class="radio" style="display: inline-block;" id="cstable"><tr style="border-bottom:1px solid #BEDAFD;"><td style="border-left:1px solid #BEDAFD;background:#daeaff;" ><b>Укажите стоимость доставки текущего заказа:</b></td></tr><tr><td><input name="shipping_cost" id="cs"></input> руб.</td></tr></table>';

		echo '<table class="radio" style="display: inline-block;width: 280px;"><tr style="border-bottom:1px solid #BEDAFD;"><td style="border-left:1px solid #BEDAFD;background:#daeaff;" ><b>Укажите сумму предоплаты, которую клиент уже внёс за заказ:</b></td></tr><tr><td><input name="prepayment" id="prepayment"></input> руб.</td></tr></table>';}
    ?>

<br />
<?php } ?>
    <?php /*$_SESSION['shipping_methods']['item2']['quote']['item2']['cost']= 1;*/?>


	<?php if ($customer_group_id == 3) { ?>
		<b>Паспорт:</b>
		<span style="display:inline-block; margin: 10px">
			<span><label>Серия</label><input type="text" maxlength="4" id="ps" name="passport-seria" class="large-field" style="width:50px;margin:10px;"></input></span>
			<span><label>Номер</label><input type="text" maxlength="6" id="pn" name="passport-number" class="large-field" style="width:50px;margin:10px;"></input></span>
		</span>
		<br style="margin:10px;">
	<?php } echo $text_comments; ?><b style="margin-left:20px;"></b><br style="margin:20px;">

<textarea name="comment" rows="8" style="width:85%; max-width: 90%; margin-left:20px;background:#f9f9f9 "><?php echo $comment; ?></textarea>
</div>
<div class="buttons">
  <div class="right">
    <input type="button" value="<?php echo $button_continue; ?>" id="button-shipping-method" class="button" />
  </div>
</div>

 <script type="text/javascript" >
     <!--
$('#cs').bind("change keyup input click", function() {
    if (this.value.match(/[^0-9]/g)) {
        this.value = this.value.replace(/[^0-9]/g, '');
    }
});

$('#ps').bind("change keyup input click", function() {
    if (this.value.match(/[^0-9]/g)) {
        this.value = this.value.replace(/[^0-9]/g, '');
    }
});

$('#pn').bind("change keyup input click", function() {
    if (this.value.match(/[^0-9]/g)) {
        this.value = this.value.replace(/[^0-9]/g, '');
    }
});


$('#prepayment').bind("change keyup input click", function() {
         if (this.value.match(/[^0-9]/g)) {
             this.value = this.value.replace(/[^0-9]/g, '');
         }
     });

$('.price_drop_row').bind("change keyup input click", function() {
		$(this).removeAttr('style');
         if (this.value.match(/[^0-9]/g)) {
             this.value = this.value.replace(/[^0-9]/g, '');
         }
     });

$('input[name=\'shipping_method\']').live('change', function(){
	$('#sdekcs').attr("id", "cs");
	$('#cstable').show();
});
$('[value ^= "cdek"]').live('change', function(){
	$('#cs').attr("id", "sdekcs");
	$('#cstable').hide();
});
//-->

 </script>

  <SCRIPT LANGUAGE="JavaScript">
<!--
 function akciaattention(){

//alert('Товары по акции 1+1=3 транспортной компанией СДЭК отправляются только по полной предоплате.');

 }
//-->
</SCRIPT>