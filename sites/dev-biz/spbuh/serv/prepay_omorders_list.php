<?php

//как навесить обработчик ошибок и сделать проверку id заказа и его суммы


foreach ($LIST_ORDERS as $listOrder=>$ORDER) {


	$LIST.='
		<tr>
			<td>'.$ORDER[order_id].'</td>
			<td>'.$ORDER[comment].'</td>
			<td>'.$ORDER[date_added].'</td>
		</tr>
	';
}



print '<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Список предоплат</title>
	<meta name="description" content="Список предоплат"/>

	<link href="/css/animate.css" rel="stylesheet" />
	<link href="/css/font-awesome.css" rel="stylesheet" />
	<link href="/css/payment_style.css" rel="stylesheet" />

	<script src="/js/jquery-1.8.2.min.js"></script>





</head>
<body>
	<header>
		<div class="topline">
			<div class="topnav"> 
				<a href="/">Главная</a><a href="/spbuh/">Счета DROP/VK</a><a href="/spbuh/omorders/">Предоплаты от покупателей</a><a href="/my-account/">Личный кабинет</a>
			</div>
		</div>

	</header>


	<div class="table">
		<p><b>Предоплаты от покупателей</b></p>


		<table>

			<tr id="table_header"><th>Номер заказа</th><th>Оплата</th><th>Дата</th></tr>

			'.$LIST.'


		</table>

	</div>

	<div class="paging">'.$PAGES.'</div>



</body>
</html>
';


?>