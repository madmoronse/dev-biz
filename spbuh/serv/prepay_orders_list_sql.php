<?php




			$i=1;//стартовая страница
			$pageSize=40;//количество позиций на странице
	
			if (!isset($AURL[2]) or empty($AURL[2]) or !preg_match("/^[0-9]+$/i", $AURL[2])) $AURL[2]=1;//нормализация УРЛа
			$from=($AURL[2]-1)*$pageSize;//вычисление текущей позиции выборки из БД


			//выгребаем все счета по которым поступила оплата, сортируем по дате оплаты

			$LIST_ORDERS=$DB->selectPage($totalRows,'SELECT * FROM `oc_prepay_orders` WHERE `order_status`=? ORDER BY `date_pay` DESC LIMIT ?d,?d','оплата поступила',$from,$pageSize);

			require_once 'paging.php';// подключаем функцию формирования постраничной навигации
			$PAGES=@universal_link_bar($AURL[2],$totalRows,ceil($totalRows/$pageSize),$AURL[1].'/',$RELP);// функция формирования HTML-кода постраничной навигации


			require 'prepay_orders_list.php';


			






	




	
	
	


?>