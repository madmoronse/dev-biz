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
				<td class="price"><?php echo $column_price; ?></td>
				<td class="price_drop"><?php echo $column_price_drop; ?></td>
				<td class="total"><?php echo $column_total; ?></td>
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
					<td id="quantity_<?php echo $product_num; ?>"><?php echo $product['quantity']; ?></td>
					<td id="price_<?php echo $product_num; ?>"> <?php echo $product['price']; ?></td>
					<td><input class="price_drop_row" name="price_drop_<?php echo $product_num; ?>"></input></td>
					<td class="total"><?php echo $product['total']; ?></td>
					<td id="total_drop_<?php echo $product_num; ?>">0</td>
					<td id="margin_drop_<?php echo $product_num; ?>">0</td>
					<td id="total_margin_drop_<?php echo $product_num; ?>">0</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>

	<?php	echo '<table class="radio" style="display: inline-block;"><tr style="border-bottom:1px solid #BEDAFD;"><td style="border-left:1px solid #BEDAFD;background:#daeaff;" ><b>Итого ВАША наценка на текущий заказ:</b></td></tr><tr><td><input name="markupdropshipping" id="md" onkeydown="return false;" tabindex="9999" value="Заполните цены с ВАШЕЙ наценкой"></input> руб.</td></tr></table>';

	if ($_SESSION['customer_id'] != 3589){
		echo '<table class="radio" style="display: inline-block;width: 280px;"><tr style="border-bottom:1px solid #BEDAFD;"><td style="border-left:1px solid #BEDAFD;background:#daeaff;" ><b>Укажите сумму предоплаты, которую клиент уже внёс за заказ:</b></td></tr><tr><td><input name="prepayment" id="prepayment"></input> руб.</td></tr></table>';
	}
	}?>

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
		if (totalvalue >= 0) {
			if ($('input:radio[name=shipping_method]:checked').length==0){
				$('#md').val(totalvalue);
			} else {
				postcost = $('input:radio[name=shipping_method]:checked').attr('class');
				$('#md').val(totalvalue - postcost);
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
		if (totalvalue >= 0) {
			if ($('input:radio[name=shipping_method]:checked').length==0){
				$('#md').val(totalvalue);
			} else {
				postcost = $('input:radio[name=shipping_method]:checked').attr('class');
				$('#md').val(totalvalue - postcost);
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
	//--></script>

<?php if ($shipping_methods) { ?>
<table class="radio" style="display: inline-block">
	<tr style="border-bottom:1px solid #BEDAFD;">
		<td style="background:#daeaff;" colspan="2"><b><?php echo $text_shipping_method; ?></b></td>
        <td style="border-left:1px solid #BEDAFD;background:#daeaff;" ><b>Стоимость доставки</b></td>
	</tr>

  <?php foreach ($shipping_methods as $shipping_method) { ?>
			<?php if ($customer_group_id == 1) {
					echo '<tr><td colspan="2"><b>' .  $shipping_method["title"] . '</b></td><td style="border-left:1px solid #BEDAFD;"></td></tr>';
				} else {
				$cdek=false;
				foreach ($shipping_method['quote'] as $quote1) {
				if ($customer_group_id == 4 or $customer_group_id == 2) {
					if ($shipping_method["title"] != "400 рублей (При заказе одной единицы товара стоимостью от 700 до 3500 рублей)"){
						//echo '<tr><td colspan="2"><b>' .  $shipping_method["title"] . '</b></td><td style="border-left:1px solid #BEDAFD;"></td></tr>';

						if($quote1['code']=='ruspostfull.ruspostfull' or $quote1['code']=='item.item'  or $quote1['code']=='ruspostpart.ruspostpart' or $quote1['code']=='firstclasspart.firstclasspart' or $quote1['code']=='farpart.farpart' or $quote1['code']=='sng.sng' or $quote1['code']=='firstclassfull.firstclassfull' or $quote1['code']=='farfull.farfull' ){echo '<tr><td colspan="2"><b>Доставка Почтой России </b></td><td style="border-left:1px solid #BEDAFD;"></td></tr>';}
						if($quote1['code']=='ems.ems' or $quote1['code']=='emssng.emssng'){echo '<tr><td colspan="2"><b>Доставка службой EMS </b></td><td style="border-left:1px solid #BEDAFD;"></td></tr>';}
						if (stripos($quote1['code'], "cdek") !== false and $cdek == false) {echo '<tr><td colspan="2"><b>Доставка службой СДЭК </b></td><td style="border-left:1px solid #BEDAFD;"></td></tr>';$cdek = true;}

					}
				}
			}
			}?>

  <?php if (!$shipping_method['error']) { ?>
  <?php foreach ($shipping_method['quote'] as $quote) { if ($customer_group_id == 3 or $customer_group_id == 4) { if ($quote['code'] != "item.item"){?>
  <?php if ($quote['code'] != "ruspostfull.ruspostfull" and $customer_group_id == 3) {continue;} ?>
			<tr class="highlight">
				<td>
				<input type="radio" class="<?php if (stripos($quote['code'], "cdek") === false and $_SESSION['customer_id'] == 3589){ echo ceil($quote['cost']);} ?>" name="shipping_method" value="<?php echo $quote['code']; ?>" <?php if (stripos($quote['code'], "cdek") !== false AND $akcia_in_cart == true) {echo 'onchange="akciaattention()"';} ?> id="<?php echo $quote['code']; ?>"  onclick="calculate_margin()"/>
				</td>
				<td >
					<label for="<?php echo $quote['code']; ?>" >
						<?php
							if($quote['code']=='ruspostfull.ruspostfull'){echo 'Доставка Почтой России. Полная предоплата.';}
							if($quote['code']=='ruspostpart.ruspostpart'){echo 'Доставка Почтой России. Частичная предоплата.';}
							if($quote['code']=='firstclasspart.firstclasspart'){echo 'Доставка 1 классом по России. Частичная предоплата.';}
							if($quote['code']=='firstclassfull.firstclassfull'){echo 'Доставка 1 классом по России. Полная предоплата.';}
							if($quote['code']=='farpart.farpart'){echo 'Доставка Почтой России в удаленный регион. Частичная предоплата.';}
							if($quote['code']=='farfull.farfull'){echo 'Доставка Почтой России в удаленный регион. Полная предоплата.';}
							if($quote['code']=='sng.sng'){echo 'Доставка Почтой России в СНГ. Полная предоплата.';}
							if($quote['code']=='ems.ems'){echo 'Доставка службой EMS. Полная предоплата.';}
							if($quote['code']=='emssng.emssng'){echo 'Доставка службой EMS в СНГ. Полная предоплата.';}
							if (stripos($quote['code'], "cdek") !== false ) {echo $quote['title'];}
						?>
					</label>
				</td>
          <?php if ($customer_group_id != 2) { echo '<td style="text-align: right;border-left:1px solid #BEDAFD;"><label for="'. $quote["code"]. '">'. $quote["text"] . '</label></td>';} ?>

  </tr>
  <?php }} else {?>
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
				if (stripos($quote['code'], "cdek") !== false ) {echo $quote['title'];}
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
    <b style="margin-left:20px;"><?php echo $text_comments; ?></b><br style="margin:10px;">
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


$('#prepayment').bind("change keyup input click", function() {
         if (this.value.match(/[^0-9]/g)) {
             this.value = this.value.replace(/[^0-9]/g, '');
         }
     });

$('.price_drop_row').bind("change keyup input click", function() {
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

alert('Товары по акции 1+1=3 транспортной компанией СДЭК отправляются только по полной предоплате.');

 }
//-->
</SCRIPT>
