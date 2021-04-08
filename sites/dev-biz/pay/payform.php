<?php

//как навесить обработчик ошибок и сделать проверку id заказа и его суммы

if (preg_match("/^[0-9]+$/i", $_REQUEST['order_id'])) {

	require 'sql_connect.php';
	
	$ORDER_DATA=$DB->selectRow('SELECT * FROM `oc_prepay_orders` WHERE `prepay_order_id`=?',$_REQUEST['order_id']);// выгребаем данные заказа
	
	if ($ORDER_DATA) {

		$USER=$DB->selectRow('SELECT * FROM `oc_customer` WHERE `customer_id`=?',$ORDER_DATA['partner_id']);//выгребаем данные партнера

		if ($ORDER_DATA['order_status']=='новый') {


print '<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>MyRupay предоплата заказа</title>
	<meta name="description" content="MyRupay предоплата заказа"/>

	<link href="/css/animate.css" rel="stylesheet" />
	<link href="/css/payment_style.css" rel="stylesheet" />

	<script src="/js/jquery-1.8.2.min.js"></script> 
	<script src="/js/js_handler.js"></script> 


</head>
<body>
	<header>
		<div class="mainmenu">
		
		</div>

	</header>

	<form action="/sp/pay.php" method="post" enctype="multipart/form-data">
	
		<div class="signform">
			<H2>Предоплата заказа</H2>
			<div class="form_block" style="margin-top:50px;">
				<p class="payinfo">Номер счета: <b>'.$ORDER_DATA['prepay_order_id'].'</b></p>
				<p class="payinfo">Сумма к оплате: '.number_format($ORDER_DATA['order_summ'], 0, '.', ' ').' руб.</p>
				<p class="payinfo">ФИО плательщика: '.$ORDER_DATA['buyer_fio'].'</p>
				<p class="payinfo">Email плательщика: '.$ORDER_DATA['buyer_email'].'</p>
				<p class="payinfo">Телефон плательщика: '.$ORDER_DATA['buyer_phone'].'</p>

				<input type="hidden" value="'.$ORDER_DATA['buyer_phone'].'" name="phone">
				<input type="hidden" value="'.$ORDER_DATA['buyer_email'].'" name="email">
				<input type="hidden" value="'.$ORDER_DATA['prepay_order_id'].'" name="order_id">
				<input type="hidden" value="'.$ORDER_DATA['partnet_type'].'" name="partner_type">
				<input type="hidden" value="'.(int) $ORDER_DATA['order_summ'].'" name="order_summ">
				<input type="hidden" value="'.$ORDER_DATA['buyer_fio'].'" name="fullname">

				<input type="submit" class="myButton" value="Оплатить" style="margin:50px auto 20px;">
				<p class="help" style="text-align:center;margin-bottom:30px;">Для оплаты нажмите кнопку "Оплатить"</p>
				<p class="help" style="text-align:center;margin-top:20px;">Данные защищены по международному стандарту PCI DSS</p>
				<p style="text-align:center;"><img src="/sp/Visa_mastercard_secure.png"><img src="/sp/cps_pci.png" style="margin-left:20px;"></p>

				<p style="text-align:center;">Сервис предоставлен платежной системой <a href="https://simplepay.pro/" target="_blank" style="color:blue;">SimplePay</a></p>
		
			</div>
		</div>
	
	
		<div style="height:100px;" id="debug"></div>


	</form>


</body>
</html>
';
		} else {


print '<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>MyRupay счет обработан</title>
	<meta name="description" content="MyRupay счет обработан"/>

	<link href="/css/animate.css" rel="stylesheet" />
	<link href="/css/payment_style.css" rel="stylesheet" />

	<script src="/js/jquery-1.8.2.min.js"></script> 
	<script src="/js/js_handler.js"></script> 


</head>
<body>
	<header>
		<div class="mainmenu">
		
		</div>

	</header>


	
	<div class="signform">
		<H2>Счет обработан</H2>
		<div class="form_block" style="margin-top:50px;">
			<p class="payinfo">Статус счета: <b>'.$ORDER_DATA['order_status'].'</b></p>
			<p class="payinfo">Номер счета: <b>'.$ORDER_DATA['prepay_order_id'].'</b></p>
			<p class="payinfo">Сумма к оплате: '.number_format($ORDER_DATA['order_summ'], 0, '.', ' ').' руб.</p>
			<p class="payinfo">ФИО плательщика: '.$ORDER_DATA['buyer_fio'].'</p>
			<p class="payinfo">Email плательщика: '.$ORDER_DATA['buyer_email'].'</p>
			<p class="payinfo">Телефон плательщика: '.$ORDER_DATA['buyer_phone'].'</p>

			<p class="help" style="text-align:center;margin-top:50px;">Данные защищены по международному стандарту PCI DSS</p>
			<p style="text-align:center;"><img src="/sp/Visa_mastercard_secure.png"><img src="/sp/cps_pci.png" style="margin-left:20px;"></p>

			<p style="text-align:center;">Сервис предоставлен платежной системой <a href="https://simplepay.pro/" target="_blank" style="color:blue;">SimplePay</a></p>
	
		</div>
	</div>


	<div style="height:100px;" id="debug"></div>


</body>
</html>
';




		}




	} else {
	
		print 'Сервис временно не доступен. Повторите попытку позже.<br><a href="/">Перейти на главную страницу</a>';
	
	}
	

}
?>