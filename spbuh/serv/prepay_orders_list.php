<?php

//как навесить обработчик ошибок и сделать проверку id заказа и его суммы


foreach ($LIST_ORDERS as $listOrder=>$ORDER) {

	$COPYBTN='';$BG='';$BG_ZAKAZ='';

	if (strlen($ORDER[buyer_order_id]) == 0 or $ORDER[buyer_order_id]=='не прикреплен') {

			$ORDER[buyer_order_id]='<a href="/spbuh/attach_order_form/?pay_id='.$ORDER[prepay_order_id].'" class="copy_btn modalwin">Прикрепить счет к заказу</a>';
			$BG_ZAKAZ=' style="background:#faeaec;"';
			$EDIT_ORD='';

	} else {


		$ORDER[buyer_order_id]='<a href="/index.php?route=account/order/info&order_id='.$ORDER[buyer_order_id].'" target="_blank">'.$ORDER[buyer_order_id].'</a>';

		$BG_ZAKAZ=' style="background:#c8eac5;font-weight:600;"';

		$EDIT_ORD='<a href="/spbuh/attach_order_form/?pay_id='.$ORDER[prepay_order_id].'" title="Изменить" class="modalwin"><i class="fa fa-pencil" aria-hidden="true" style="float:right;color:#333;"></i></a>';


	}


	if ($ORDER[order_status]=='новый') {

		$COPYBTN='<span class="copy_btn" data-clipboard-target="#order'.$ORDER[prepay_order_id].'" style="margin-top:3px;">Скопировать</span>';
		$BG=' style="background:#faeaec;"';

	}

	if ($ORDER[order_status]=='оплата поступила') {
		$BG=' style="background:#c8eac5;"';
	}



	$LIST.='
		<tr>
			<td>'.$ORDER[prepay_order_id].'</td>
			<td'.$BG_ZAKAZ.' id="'.$ORDER[prepay_order_id].'">'.$ORDER[buyer_order_id].$EDIT_ORD.'</td>
			<td>'.$ORDER[buyer_fio].'</td>
			<td>'.$ORDER[buyer_email].'</td>
			<td>'.$ORDER[buyer_phone].'</td>
			<td>'.number_format($ORDER[order_summ], 0, '.', ' ').' руб.</td>
			<td'.$BG.'>'.$ORDER[order_status].'</td>
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

	<link rel="stylesheet" type="text/css" href="/fancybox/my_jquery.fancybox.css" media="screen" />
	<script type="text/javascript" src="/fancybox/jquery.easing.1.3.js"></script>
	<script type="text/javascript" src="/fancybox/jquery.fancybox-1.2.1.pack.js"></script>

	<script src="/js/clipboard.min.js"></script>

	<script src="/js/js_handler.js"></script>


	<script type="text/javascript">
		$(document).ready(function() {

			$(".modalwin").fancybox({
				"padding" : 0, // отступ контента от краев окна
				"overlayShow" : true, // если true затеняят страницу под всплывающим окном. (по умолчанию true). Цвет задается в jquery.fancybox.css - div#fancy_overlay 
				"hideOnContentClick" :false, // Если TRUE  закрывает окно по клику по любой его точке (кроме элементов навигации). Поумолчанию TRUE		
				"centerOnScroll" : true, // Если TRUE окно центрируется на экране, когда пользователь прокручивает страницу
				"autoSize" : false,
				"frameWidth": 390,
				"frameHeight": 690,
			});


			    var clipboard = new Clipboard(\'.copy_btn\');
			    clipboard.on(\'success\', function(e) {
			        console.log(e);
			    });
			    clipboard.on(\'error\', function(e) {
			        console.log(e);
			    });
		

		});
	</script>






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
		<p><b>Оплаченные счета DROP/VK</b></p>


		<table>

			<tr id="table_header"><th>Номер счета</th><th>Номер заказа</th><th>ФИО плательщика</th><th>Email плательщика</th><th>Телефон плательщика</th><th>Сумма счета</th><th>Статус счета</th></tr>

			'.$LIST.'


		</table>

	</div>

	<div class="paging">'.$PAGES.'</div>



</body>
</html>
';


?>