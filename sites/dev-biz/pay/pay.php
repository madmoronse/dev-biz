<?php





	require 'MySimplePayMerchant.class.php';



	
	/**
	 * Здесь представлен вариант с переадресацией сразу на страницу платежной системы
	 * Установите payment_system = SP для переадресации на страницу выбора способа платежа
	 * В случае если не будут указаны обязательные данные о плательщике, 
	 * будет произведена переадресация на платежную страницу SimplePay для уточнения деталей
	 */





	$ORDER_DESCR='Оплата заказа '.$_REQUEST[order_id];


	
	$payment_data = new SimplePay_Payment;//переменная-идентификатор платежа


	
	$payment_data->amount = $_REQUEST[order_summ];// сумма платежа
	$payment_data->order_id = $_REQUEST[order_id];//id заказа в система
	$payment_data->client_name = $_REQUEST[fullname];//ФИО клиента
	$payment_data->client_email = $_REQUEST[email];//мыло клиента
	$payment_data->client_phone = $_REQUEST[phone];//телефон клиента
	$payment_data->description = $ORDER_DESCR;//за что прилетел платеж
	$payment_data->payment_system = 'SP';//система оплаты
	$payment_data->client_ip = $_SERVER['REMOTE_ADDR'];
	$payment_data->result_url = 'http://bizoutmax.ru/pay/result.php';
	$payment_data->success_url = 'http://bizoutmax.ru/pay/success.php';
	$payment_data->fail_url =  'http://bizoutmax.ru/pay/fail.php';
	
	
	
	
	
	// Создаем объект мерчант-класса SP
	$sp = new MySimplePayMerchant();
	
	
	
	$out = $sp->get_ps_list(100);
	
	
	
	
	// Запрос на создание платежа
	$out = $sp->direct_payment($payment_data);
	
	
	
	
	// Получаем ссылку на платежную страницу
	$payment_link = $out['sp_redirect_url'];
	

	
	
	// Запрос данных о созданном платеже
	$out = $sp->get_order_status_by_order_id($_REQUEST[order_id]);



	header('Location: '.$payment_link);//перенаправляем на страницу оплаты в SP


	


	
	
	


?>