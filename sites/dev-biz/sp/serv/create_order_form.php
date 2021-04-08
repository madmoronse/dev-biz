<?php


print '
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Восстановление пароля</title>
	<meta name="description" content="Восстановление пароля"/>
	<meta name="keywords" content=""/>

	<link href="/css/basketstyle.css" rel="stylesheet" />
	<link href="/css/animate.css" rel="stylesheet" />

	<script src="/js/js_handler.js"></script>

</head>
<body>


	<form id="createprepayorder" onsubmit="return true;">
	
		<div class="signform modal">
			<H3>Создать счет на предоплату</H3>
			<div class="form_block">
				<input type="text" value="" name="order_summ" placeholder="Введите сумму оплаты*" required>
				<p class="help">Вводите только цифры</p>
				<input type="text" value="" name="buyer_fio" placeholder="Введите ФИО плательщика*" required>
				<p class="help">Вводите ФИО полностью</p>
				<input type="text" value="" name="buyer_email" placeholder="Введите email плательщика*" required>
				<p class="help">Обязательно, для извещения плательщика об операциях</p>
				<input type="text" value="" name="buyer_phone" placeholder="Введите телефон плательщика*" required>
				<p class="help">Обязательно, для извещения плательщика об операциях</p>
				<span class="myButton createprepayorder">Создать счет</span>
				<div id="errorTarget" class="error_message">Заполните все поля</div>
			</div>
		</div>
	
	
	</form>




</body>
</html>
';



?>