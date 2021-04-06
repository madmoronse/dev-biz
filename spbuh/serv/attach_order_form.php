<?php


print '
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Прикрепить заказ к счету</title>
	<meta name="description" content="Прикрепить заказ к счету"/>
	<meta name="keywords" content=""/>

	<link href="/css/basketstyle.css" rel="stylesheet" />
	<link href="/css/animate.css" rel="stylesheet" />

	<script src="/js/js_handler.js"></script>

</head>
<body>


	<form id="attachorder" onsubmit="return true;">
	
		<div class="signform modal">
			<H3>Прикрепить счет #'.$_REQUEST[pay_id].' к заказу</H3>
			<div class="form_block">
				<input type="text" value="" name="order_id" placeholder="Введите номер заказа*" required>
				<p class="help">Вводите только цифры</p>
				<span class="myButton attachorder">Прикрепить</span>
				<input type="hidden" value="'.$_REQUEST[pay_id].'" name="pay_order_id">
				<p style="text-align:center;visibility:hidden;" id="realy_yes">Всё-равно сохранить? <span class="forceattach">Да</span></p>
				<div id="errorTarget" class="error_message">Заполните поле</div>
			</div>
		</div>
	
	
	</form>




</body>
</html>
';



?>