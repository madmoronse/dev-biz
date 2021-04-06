<?php




			$i=1;//стартовая страница
			$pageSize=40;//количество позиций на странице
	
			if (!isset($AURL[3]) or empty($AURL[3]) or !preg_match("/^[0-9]+$/i", $AURL[3])) $AURL[3]=1;//нормализация УРЛа
			$from=($AURL[3]-1)*$pageSize;//вычисление текущей позиции выборки из БД


			//выгребаем все счета по которым поступила оплата, сортируем по дате оплаты

			$LIST_ORDERS=$DB->selectPage($totalRows,'SELECT * FROM `oc_order_history` WHERE `order_status_id`=? ORDER BY `date_added` DESC LIMIT ?d,?d',200,$from,$pageSize);

			require_once 'paging.php';// подключаем функцию формирования постраничной навигации
			$PAGES=@universal_link_bar($AURL[3],$totalRows,ceil($totalRows/$pageSize),$AURL[1].'/'.$AURL[2].'/',$RELP);// функция формирования HTML-кода постраничной навигации


			require 'prepay_omorders_list.php';


	
	


?>