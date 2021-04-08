 <?php if ($customer_group_id == 2 or $customer_group_id == 4) {echo '<script type="text/javascript" src="./catalog/view/javascript/jquery/ajaxupload/ajaxupload.3.5.js"></script>';}?>


<div class="wrapper">
<?php if (isset($this->session->data['replacement_for'])) {
	if ($this->session->data['replacement_for'] > 0) {
		echo '<div style="color:#F00;margin:10px 20px 10px 20px;font-size:18px;font-weight:600;">Обмен по заказу:' . $this->session->data['replacement_for'] . '</div>';
	}
} ?>

<?php if (isset($this->session->data['buybuysu_bc'])) {
	if ($this->session->data['buybuysu_bc'] > 0) {
		echo '<div style="color:#F00;margin:10px 20px 10px 20px;font-size:18px;font-weight:600;">Отправить заказ БЕЗ наложенного платежа.</div>';
	}
} ?>

<div style="margin-left:20px;font-size:20px;">Получатель:<br/>
        <div style="font-size:16px;"><?php echo $shipping_address['lastname'] . " " . $shipping_address['firstname'] . " " .  $shipping_address['middlename'];?></div>
        <div style="font-size:16px;"><?php echo $shipping_address['country'] . ", " . $shipping_address['zone'];?></div>
        <div style="font-size:16px;"><?php if ($shipping_address['address_4'] != ""){
            echo $shipping_address['postcode'] . " " . $shipping_address['naselenniy_punkt'] . " " . $shipping_address['city'] . ", ул. " . $shipping_address['address_1'] . ", дом " . $shipping_address['address_2'] . ", корп. " . $shipping_address['address_4'] . ", кв. " . $shipping_address['address_3'] . " (тел. " . $shipping_address['telephone']. ")";} else {
            echo $shipping_address['postcode'] . " " . $shipping_address['naselenniy_punkt'] . " " . $shipping_address['city'] . ", ул. " . $shipping_address['address_1'] . ", дом " . $shipping_address['address_2'] . ", кв. " . $shipping_address['address_3'] . " (тел. " . $shipping_address['telephone']. ")";}?></div> <br />
    </div>

<?php if (!isset($redirect)) { ?>
<div class="checkout-product">
  <table>
    <thead>
      <tr>
        <td class="name"><?php echo $column_name; ?></td>
        <td class="model"><?php echo $column_sku; ?></td>
        <td class="quantity"><?php echo $column_quantity; ?></td>
        <td class="price"><?php echo $column_price; ?></td>
		<?php if ($customer_group_id == 4) { ?><td class="total"><?php echo $column_total; ?></td><?php } ?>
		<?php if ($customer_group_id == 4) { ?><td class="total"><?php echo $column_margin_drop; ?></td><td class="total"><?php echo $column_total_drop; ?></td><?php } ?>
        <?php if ($customer_group_id != 4) { ?><td class="total"><?php echo $column_total; ?></td><?php } ?>
      </tr>
    </thead>
    <tbody>
		<?php $product_num = 0;
		foreach ($products as $product) { ?>

      <tr>
        <td class="name">
			<?php if ($product['thumb']) { ?>
                <a target="_blank" href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
            <?php } ?>
			<a target="_blank" href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
          <?php foreach ($product['option'] as $option) { ?>

          &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
          <?php } ?></td>
        <td class="model"><?php echo $product['product_id']; ?></td>
        <td class="quantity"><?php echo $product['quantity']; ?></td>
        <td class="price"><?php echo $product['price']; ?></td>
		<?php if ($customer_group_id == 4) { ?><td class="total"><?php echo $product['total']; ?></td><?php } ?>
		<?php if ($customer_group_id == 4) { ?><td class="total"><?php echo $product['price_markup'] . ' ք'; ?></td><td class="total"><?php echo $product['price_drop']*$product['quantity'] . ' ք'; ?></td><?php } ?>
        <?php if ($customer_group_id != 4) { ?><td class="total"><?php echo $product['total']; ?></td><?php } ?>
      </tr>
      <?php } ?>
      <?php foreach ($vouchers as $voucher) { ?>
      <tr>
        <td class="name"><?php echo $voucher['description']; ?></td>
        <td class="model"></td>
        <td class="quantity">1</td>
        <td class="price"><?php echo $voucher['amount']; ?></td>
        <td class="total"><?php echo $voucher['amount']; ?></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <?php $n = 0; foreach ($totals as $total) { $n++; ?>
	  <?php if ($customer_group_id == 3 and $n == 2 ) {if (stripos($total['title'], 'ПЭК') or stripos($total['title'], 'Энергия') or stripos($total['title'], 'Другой'))  {$total['title'] = 'Стоимость доставки Транспортной компанией оплачивается при получении';}} ?>
      <tr>
        <td colspan="<?php if ($customer_group_id == 3 and $n == 2 ) {echo '5';} else if ($customer_group_id == 4) {echo '6';}else{echo '4';}?>" class="price"><b><?php if ($customer_group_id == 4) {if ($total['title'] == "Предоплата 1000 рублей. Оплата оставшейся суммы при получении в почтовом отделении.") {$total['title'] = "Предоплата 450 рублей. Оплата оставшейся суммы при получении в почтовом отделении";} } echo $total['title']; if ($n==2 and $customer_group_id != 3) {echo ". Стоимость доставки";}?><?php if ($customer_group_id != 3){?>: <?php } ?></b></td>
        
		<?php if ($customer_group_id == 3){ if ($n != 2){ ?>
			<td <?php if ($n==3) {echo 'id="markuptotal"';}?> class="total"><?php echo $total['text']; ?></td>
		<?php }} else{?>
			<td <?php if ($n==3) {echo 'id="markuptotal"';}?> class="total" <?php if ($total['title'] == "Итого"){echo 'style="font-weight:bold;"';} ?>><?php echo $total['text']; ?></td>
		<?php } ?>
      </tr>
      <?php } ?>
	  <?php if ($customer_group_id == 2) {
		$result = count($totals)-1;
		echo '<tr><td colspan="4" class="price"><b>Клиент уже внёс предоплату:</b></td><td class="total"><label style="font-weight:bold;cursor:auto;">'. (float)$this->session->data['prepayment'] .' ք</label></td></tr>'.'<tr><td colspan="4" class="price"><b>Сумма наложенного платежа:</b></td><td class="total"><label id="nalojenniy_platej" style="font-weight:bold;cursor:auto;">'. (float)$this->session->data['cash_on_delivery'] .' ք</label></td></tr>';
	  } ?>
	  <?php if ($customer_group_id == 4 && null) {
		$result = count($totals)-1;
		echo '<tr><td colspan="6" class="price"><b>Клиент уже внёс предоплату:</b></td><td class="total"><label style="font-weight:bold;cursor:auto;">'. (float)$this->session->data['prepayment'] .' ք</label></td></tr>'.'<tr><td colspan="6" class="price"><b>Сумма наложенного платежа:</b></td><td class="total"><label id="nalojenniy_platej" style="font-weight:bold;cursor:auto;">'. (float)$this->session->data['cash_on_delivery'] .' ք</label></td></tr>';
	  } ?>
    </tfoot>
  </table>
</div>
</div>

<?php if ($customer_group_id == 2 or $customer_group_id == 4) {echo '<div class="upload_images"><div id="upload" style="float:right;">Загрузить фото чека</div><span id="status" ></span><ul id="files" ></ul></div>';	}?>

<div class="payment"><?php echo $payment; ?></div>
<?php } else { ?>
<script type="text/javascript"><!--
location = '<?php echo $redirect; ?>';
//--></script>
<?php } ?>
<script type="text/javascript" >
<!--

$(function(){
if ($('#upload').length>0) {
var btnUpload=$('#upload');
var status=$('#status');
new AjaxUpload(btnUpload, {
    action: 'index.php?route=checkoutf/confirm/add_image',
    //Name of the file input box
    name:  'uploadfile',
    onSubmit: function(file, ext){
      if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
        // check for valid file extension
        status.text('Загружать можно только изображения в формате JPG, PNG или GIF');
        return false;
      }
      status.text('Загружается...');
    },
    onComplete: function(file, response){
      //On completion clear the status
      status.text('');
      //Add uploaded file to list
      filename=response;
      if(response!="error"){
        $('<li class="check_photo"style="border:1px solid #333;"></li>').appendTo('#files').html('<img src="../uploads/tmp_dir/' +filename+'" alt="" /><br />'+filename+'<br /><br /><span class="rem_img">удалить фото</span>');
        $('#files').find('.rem_img').not('.activated').on('click', function(e) {
          e.stopPropagation();
          var sr_im = $(this).parent().find('img').attr("src");
          $(this).parent().remove();
            $.ajax({
                url: 'index.php?route=checkoutf/confirm/rem_image',
                type: 'post',
                data: {
                    src_image : sr_im
                }
            });
        });

        $('#files').find('.rem_img').not('.activated').addClass('activated');

        $('#upload').text('загрузить еще фото');
      } else{
        $('<li></li>').appendTo('#files').text(filename).addClass('error');
      }
    }
});
}
});
//-->
</script>
