<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
	<div class="wrapper">

		<?php

			//если активирован я, то разрешить, иначе пока не давать
			//запилить свой запрос

			if (preg_match("/^[0-9]+$/i", $order_id) and preg_match("/^[0-9]+$/i", $_SESSION['customer_id']) and isset($_SESSION['customer_id'])) {

				//если это цифры и сессия установлена, то подключаемся к БД и делаем пару запросов
				require_once 'mylib/sql_connect.php';
	
				$CURRENT_ORDER=$DB->selectRow('SELECT * FROM `oc_order` WHERE `order_id`=?',$order_id);
				$CURRENT_USER=$DB->selectRow('SELECT * FROM `oc_customer` WHERE `customer_id`=?',$_SESSION['customer_id']);//нахуя?

			}
			

			if ( $CURRENT_USER['customer_group_id']<2 and ($CURRENT_ORDER['order_status_id']==2 or $CURRENT_ORDER['order_status_id']==15 or $CURRENT_ORDER['order_status_id']==17)) {

				// тут косяк возможен


				if ($CURRENT_ORDER['prepayment']>0) {

					//$product['price'] если сдек и меньше 6 то +150 cdekpart.cdekpart
					//$product['price'] если сдек и больше 6 то +250
					$AMOUNT=(int) $CURRENT_ORDER['prepayment'];
					/*
					if ($CURRENT_ORDER['shipping_code']=='cdekpart.cdekpart') {
	
								if ($CURRENT_ORDER['cash_on_delivery']<=6000) $AMOUNT=$AMOUNT+150;
								if ($CURRENT_ORDER['cash_on_delivery']>6000) $AMOUNT=$AMOUNT+250;
					}
					*/


				} else {

					$AMOUNT=$CURRENT_ORDER['total']-$CURRENT_ORDER['cash_on_delivery'];

					/*
					if ($CURRENT_ORDER['shipping_code']=='cdekpart.cdekpart') {
	
								if ($CURRENT_ORDER['cash_on_delivery']<=6000) $AMOUNT=$AMOUNT+150;
								if ($CURRENT_ORDER['cash_on_delivery']>6000) $AMOUNT=$AMOUNT+250;
					}
					*/

				}


				$FULL_NAME=$CURRENT_ORDER['firstname'].' '.$CURRENT_ORDER['middlename'].' '.$CURRENT_ORDER['lastname'];


					//формирование формы для PayU

						$SECRET_KEY = 'C2c55^9#+r90T6#h8|Z7';
					
					
					
						$BACK_REF ='http://bizoutmax.ru/crontab/order_done.php';
					
					
						//учитывается при рассчете контрольной суммы
						$BASE_DATA = array (
					
							'MERCHANT'	=>	'gehyytht',
							'ORDER_REF'	=>	$order_id,
							'ORDER_DATE'	=>	date('Y-m-d H:i:s'),
					
						);
						$payu_test_order = false;
					
					
					
						//учитывается при рассчете контрольной суммы
						//по товару надо как-то иначе
						//товар выгружается строкой: product_id, name, price, qnt и до кучи подключаем vat - налоги 
						$PRODUCT_DATA = array (
					
							'0' =>	array(
								'ORDER_PNAME'	=> 'Заказ #'.$order_id,
								'ORDER_PCODE'	=> $order_id,
								'ORDER_PRICE'	=> $AMOUNT,
								'ORDER_QTY'	=> 1,
								'ORDER_VAT'	=> 0,
					
							),
					
				
						);
					
					
					
						//учитывается при рассчете контрольной суммы
						$SERV_DATE = array (
					
							'PRICES_CURRENCY' => 'RUB',
							'PAY_METHOD' => 'CCVISAMC',
							'TESTORDER' => 'TRUE',
					
						);
					
					
					
						foreach ($PRODUCT_DATA as $prodROW => $PRODUCT) {
					
							//формируем поля
							$ORDER_PNAMES.='<input type="hidden" name="ORDER_PNAME[]" value="'.$PRODUCT[ORDER_PNAME].'">';
							$ORDER_PCODES.='<input type="hidden" name="ORDER_PCODE[]" value="'.$PRODUCT[ORDER_PCODE].'">';
							$ORDER_PRICES.='<input type="hidden" name="ORDER_PRICE[]" value="'.$PRODUCT[ORDER_PRICE].'">';
							$ORDER_QTYS.='<input type="hidden" name="ORDER_QTY[]" value="'.$PRODUCT[ORDER_QTY].'">';
							$ORDER_VATS.='<input type="hidden" name="ORDER_VAT[]" value="'.$PRODUCT[ORDER_VAT].'">';
					
							//формируем базовую строку для рассчета хэша
							$ORDER_PNAMES_HASH.=strlen($PRODUCT[ORDER_PNAME]).$PRODUCT[ORDER_PNAME];
							$ORDER_PCODES_HASH.=strlen($PRODUCT[ORDER_PCODE]).$PRODUCT[ORDER_PCODE];
							$ORDER_PRICES_HASH.=strlen($PRODUCT[ORDER_PRICE]).$PRODUCT[ORDER_PRICE];
							$ORDER_QTYS_HASH.=strlen($PRODUCT[ORDER_QTY]).$PRODUCT[ORDER_QTY];
							$ORDER_VATS_HASH.=strlen($PRODUCT[ORDER_VAT]).$PRODUCT[ORDER_VAT];
					
						}
					
					
						$HASH_STR=strlen($BASE_DATA[MERCHANT]).$BASE_DATA[MERCHANT] . strlen($BASE_DATA[ORDER_REF]).$BASE_DATA[ORDER_REF] . '19'.$BASE_DATA[ORDER_DATE] . $ORDER_PNAMES_HASH . $ORDER_PCODES_HASH . $ORDER_PRICES_HASH . $ORDER_QTYS_HASH . $ORDER_VATS_HASH . strlen($SERV_DATE[PRICES_CURRENCY]).$SERV_DATE[PRICES_CURRENCY];
						if ($payu_test_order) {
							$HASH_STR .= strlen($SERV_DATE[TESTORDER]).$SERV_DATE[TESTORDER];
						}
						//все поля, что выше поля ORDER_HASH, участвуют в рассчете контрольной суммы
						$HASH=hash_hmac('md5', $HASH_STR, $SECRET_KEY);
					
					
					
					
					

					
					
					
					
					$PAYU_FORM_CONTENT='
					
					
						<input name="MERCHANT" type="hidden" value="'.$BASE_DATA[MERCHANT].'">
						<input name="ORDER_REF" type="hidden" value="'.$BASE_DATA[ORDER_REF].'">
						<input name="ORDER_DATE" type="hidden" value="'.$BASE_DATA[ORDER_DATE].'">
					
						'.$ORDER_PNAMES.'
						'.$ORDER_PCODES.'
						'.$ORDER_PRICES.'
						'.$ORDER_QTYS.'
						'.$ORDER_VATS.'
					
						<input name="PRICES_CURRENCY" type="hidden" value="'.$SERV_DATE[PRICES_CURRENCY].'">

						<!-- <input name="PAY_METHOD" type="hidden" value="'.$SERV_DATE[PAY_METHOD].'"> -->
						'. (($payu_test_order) ? '<input name="TESTORDER" type="hidden" value="'.$SERV_DATE[TESTORDER].'">' : '') . '
						
					
						<input name="ORDER_HASH" type="hidden" value="'.$HASH.'" id="ORDER_HASH">
					
					
						<input name="BILL_FNAME" type="hidden" value="'.$CURRENT_ORDER['firstname'].'">
						<input name="BILL_LNAME" type="hidden" value="'.$CURRENT_ORDER['lastname'].'">
						<input name="BILL_EMAIL" type="hidden" value="'.$CURRENT_ORDER['email'].'">
						<input name="BILL_PHONE" type="hidden" value="'.$CURRENT_ORDER[telephone].'">
					
						<input name="BILL_COUNTRYCODE" type="hidden" value="ru">
						<input name="LANGUAGE" type="hidden" value="ru">
						<!-- <input name="BACK_REF" type="hidden" value="'.BACK_REF.'"> -->
					
					
					';





		 ?>



		<div class="pay_from_order">
			<H1>Оплата заказа</H1>
			<div class="process_form">
				<!-- PayU.ru -->
				<form method="POST" action="https://secure.payu.ru/order/lu.php" id="form_payu" target="_blank">
					<?php echo $PAYU_FORM_CONTENT; ?>
				</form>
				<!-- YandexMoney -->
				<form method="POST" action="https://money.yandex.ru/quickpay/confirm.xml" id="form_yandex_money" target="_blank">
					<input type="hidden" name="receiver" value="410015835086371"> 
					<input type="hidden" name="formcomment" value="Дисконт-центр OUTMAX заказ #<?php echo $order_id; ?>"> 
					<input type="hidden" name="short-dest" value="Предоплата по заказу OUTMAX #<?php echo $order_id; ?>"> 
					<input type="hidden" name="label" value="<?php echo $order_id; ?>"> 
					<input type="hidden" name="quickpay-form" value="shop"> 
					<input type="hidden" name="targets" value="Предоплата по заказу OUTMAX #<?php echo $order_id; ?>"> 
					<input type="hidden" name="sum" value="<?php echo $AMOUNT; ?>" data-type="number"> 
					<input type="hidden" name="comment" value="Предоплата по заказу OUTMAX #<?php echo $order_id; ?>"> 
					<input type="hidden" name="need-fio" value="false"> 
					<input type="hidden" name="need-email" value="false"> 
					<input type="hidden" name="need-phone" value="false"> 
					<input type="hidden" name="need-address" value="false"> 
				</form>
				<!-- SimplePay -->
				<form method="POST" action="pay/pay.php" id="form_simple_pay" target="_blank">
						<input type="hidden" name="fullname" value="<?php echo $FULL_NAME;?>"> 
						<input type="hidden" name="phone" value="<?php echo $CURRENT_ORDER['telephone'];?>"> 
						<input type="hidden" name="email" value="<?php echo $CURRENT_ORDER['email'];?>"> 
						<input type="hidden" name="order_summ" value="<?php echo (int) $AMOUNT;?>" data-type="number"> 
						<input type="hidden" name="order_id" value="<?php echo $order_id;?>"> 
				</form>
				<form method="POST" action="/index.php?route=checkout/success" id="form_credentials" target="_blank">
					<input type="hidden" name="paymentType" value="PR"/>
					<input type="hidden" name="order_id" value="<?php echo $order_id;?>" /> 
				</form>
				<!-- WalletOne -->
				<?php echo $walletone_form ?>

				<div class="pay_chooser_block">
					<h3>Сумма предоплаты: <?php echo number_format($AMOUNT, 0, '.', ' ')." руб."; ?></h3>
					<?php /* <div class="chooser">	
						<input type="radio" name="paymentType" value="form_payu" id="pay_pu">
						<label for="pay_pu">PayU <span>(Банковские карты, QIWI, Евросеть, Связной, Альфа-клик)</span></label> 
					</div> */?>
					<div class="chooser">	
						<input type="radio" name="paymentType" value="walletone_payment" id="pay_walletone" checked>
						<label for="pay_walletone"><strong>Единая касса</strong> (банковские карты, альфа-клик, евросеть, связной, платежные терминалы)</label> 
					</div>
					<div class="chooser">	
						<input type="radio" name="paymentType" value="form_credentials" id="pay_pr">
						<label for="pay_pr">Через платежные реквизиты</label> 
					</div>
					<div class="chooser">	
						<input type="radio" name="paymentType" value="form_yandex_money" id="pay_pc">
						<label for="pay_pc">Кошелек Яндекс.Деньги</label> 
					</div>

					<?php /* <div class="chooser">	
						<input type="radio" name="paymentType" value="form_simple_pay" id="pay_sp">
						<label for="pay_sp">SimplePay <span>(Банковские карты, QIWI, Евросеть, терминалы и т.д.)</span></label> 
					</div> */?>
					<input type="button" class="myButton" id="pay_button" value="Оплатить"> 
				</div>
				<div class="pay_info_block">
				</div>
				<div class="clear"></div>
		</div>
		<script>
				$('#pay_button').on('click', function() {
						var paymentFormId = $('.chooser input[name="paymentType"]:checked').val();
						var form = document.getElementById(paymentFormId);
						if (form) {
							form.submit();
						}
				});
		</script>

		<?php 

			}

		 ?>



  <table class="list">
    <thead>
      <tr>
        <td class="left" ><?php echo $text_order_detail; ?></td>
		<td class="left"><?php echo $text_shipping_address; ?></td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="left" style="width: 50%;"><?php if ($invoice_no) { ?>
          <b><?php echo $text_invoice_no; ?></b> <?php echo $invoice_no; ?><br />
          <?php } ?>
          <b><?php echo $text_order_id; ?></b> #<?php echo $order_id; ?>
		  <?php if ($replacement_for) { ?>
          <b><?php echo $text_replacement_for ?></b> <?php echo $replacement_for; ?>
          <?php } ?>
		  <br />
          <b><?php echo $text_date_added; ?></b> <?php echo $date_added; ?>
		  <?php if ($shipping_method) { ?>
          <b><?php echo $text_shipping_method; ?></b> <?php echo $shipping_method; ?>
          <?php } ?></td>
		  <?php if ($shipping_address) { ?>
        <td class="left"><?php echo $shipping_address; ?></td>
        <?php } ?>
         <!--<td class="left" style="width: 50%;"><?php //if ($payment_method) { ?>
         <b><?php //echo $text_payment_method; ?></b> <?php //echo $payment_method; ?><br />
          <?php //} ?>
          </td>-->
      </tr>
    </tbody>
  </table>

  <table class="list">
    <thead>
      <tr>
        <td class="left"><?php echo $column_name; ?></td>
        <td class="left"><?php echo $column_sku; ?></td>
        <td class="right"><?php echo $column_quantity; ?></td>
        <td class="right"><?php echo $column_price; ?></td>
        <td class="right">Наценка</td>
        <td class="right">Итого без наценки</td>
        <td class="right">Итого с наценкой</td>
        <?php if ($products) { ?>
        
        <?php } ?>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($products as $product) { ?>
      <tr>
        <td class="left"><?php echo $product['name']; ?>
          <?php foreach ($product['option'] as $option) { ?>
          <br />
          &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
          <?php } ?></td>
        <td class="left"><?php echo $product['product_id']; ?></td>
        <td class="right"><?php echo $product['quantity']; ?></td>
        <td class="right"><?php echo $product['price']; ?></td>
        <td class="right"><?php echo $product['markup']; ?></td>
        <td class="right"><?php echo $product['total']; ?></td>
        <td class="right"><?php echo $product['drop_saller_price']; ?></td>
        
      </tr>
      <?php } ?>
      <?php foreach ($vouchers as $voucher) { ?>
      <tr>
        <td class="left"><?php echo $voucher['description']; ?></td>
        <td class="left"></td>
        <td class="right">1</td>
        <td class="right"><?php echo $voucher['amount']; ?></td>
        <td class="right"><?php echo $voucher['amount']; ?></td>
        <?php if ($products) { ?>
        
        <?php } ?>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <?php foreach ($totals as $total) { ?>
      <tr>
        
        <td colspan="6" class="right"><b><?php echo $total['title']; ?>:</b></td>
        <td class="right"><?php echo $total['text']; ?></td>
        <?php if ($products) { ?>
        
        <?php } ?>
      </tr>
      <?php } ?>
    </tfoot>
  </table>




  <?php if ($comment) { ?>
  <table class="list">
    <thead>
      <tr>
        <td class="left"><?php echo $text_comment; ?></td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="left"><?php echo $comment; ?></td>
      </tr>
    </tbody>
  </table>
	<?php } ?>
	


	<?php if ($comments) { // BMV Begin ?>
                <h2>Заметки к заказу</h2>
                <table class="list">
                    <thead>
                    <tr>
                        <td class="left"><?php echo $column_date_added; ?></td>
                        <td class="left">Заметка</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($comments as $currentComment) { ?>
                        <tr>
                            <td class="left"><?php echo $currentComment['date_added']; ?></td>
                            <td class="left"><?php echo $currentComment['comment']; ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            <?php } // BMV END ?>


            <div id="comments"></div>
            <table class="form">
                <tr colspan="2">
                    <th colspan="2">
                        <h2 style="text-align: center">Добавить заметку к заказу:</h2>
                    </th>
                </tr>
                <tr>
                    <td style="width:10%">Заметка: </td>
                    <td><textarea name="comment_comment" cols="40" rows="8" style="width: 99%"></textarea>
                        <div style="margin-top: 10px; text-align: right;"><a id="button-comment" class="button">Добавить</a></div></td>
                </tr>
            </table>

            <script>
                $('#button-comment').live('click', function() {
                    if ($('textarea[name=\'comment_comment\']').val().length >0 )
                        $.ajax({
                            url: 'index.php?route=account/order/comments&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
                            type: 'post',
                            dataType: 'html',
                            data: 'comment=' + encodeURIComponent($('textarea[name=\'comment_comment\']').val()),
                            beforeSend: function() {
                                $('.success, .warning').remove();
                                $('#button-comment').attr('disabled', true);
                                $('#comments').prepend('<div class="attention"><img src="view/image/loading.gif" alt="" /> Добавляется заметка</div>');
                            },
                            complete: function() {
                                $('#button-comment').attr('disabled', false);
                                $('.attention').remove();
                            },
                            success: function(html) {
                                $('#comments').html(html);
                                $('textarea[name=\'comment_comment\']').val('');
                            }
                        });
                });
            </script>





  <?php if ($histories) { ?>
  <h2><?php echo $text_history; ?></h2>
  <table class="list">
    <thead>
      <tr>
        <td class="left"><?php echo $column_date_added; ?></td>
        <td class="left"><?php echo $column_status; ?></td>
        <td class="left"><?php echo $column_comment; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($histories as $history) { ?>
      <tr>
        <td class="left"><?php echo $history['date_added']; ?></td>
        <td class="left"><?php echo $history['status']; ?></td>
        <td class="left"><?php echo $history['comment']; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
	</div>
  <?php } ?>
  <div class="buttons">
    <div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
  </div>
  <?php echo $content_bottom; ?></div>

<?php echo $footer; ?> 