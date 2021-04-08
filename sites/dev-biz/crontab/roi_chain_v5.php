<?php

	function isBot($user_agent)
	{
	    if (empty($user_agent)) {
	        return false;
	    }
	    
	    $bots = array(

	        // Yandex
	        'Yandex',
	
	        // Google
	        'Google',
	
	        // Other
	        'MailRU','Mail.RU_Bot', 'bingbot', 'Accoona', 'ia_archiver', 'Ask Jeeves', 'OmniExplorer_Bot', 'W3C_Validator',
	        'WebAlta', 'YahooFeedSeeker', 'Yahoo!', 'Ezooms', 'Tourlentabot', 'MJ12bot', 'AhrefsBot',
	        'SearchBot', 'SiteStatus', 'Nigma.ru', 'Baiduspider', 'Statsbot', 'SISTRIX', 'AcoonBot', 'findlinks',
	        'proximic', 'OpenindexSpider', 'statdom.ru', 'Exabot', 'Spider', 'SeznamBot', 'oBot', 'C-T bot',
	        'Updownerbot', 'Snoopy', 'heritrix', 'Yeti', 'DomainVader', 'DCPbot', 'PaperLiBot', 'StackRambler',
	        'msnbot', 'msnbot-media', 'msnbot-news', 'RetailRocketBot', 'Semrush',



	    );
	
	    foreach ($bots as $bot) {
	        if (stripos($user_agent, $bot) !== false) {
	            return true;
	        }
	    }
	
	    return false;
	}


	$BOTS_DETECTED = isBot($_SERVER['HTTP_USER_AGENT']);// false - не определился или не бот, иначе возвращает true

	//будут писаться все цепочки, в том числе и дроперы, вк, оптовики. надо будет очищать


	if (!$BOTS_DETECTED and !empty($_SERVER['HTTP_USER_AGENT'])) {

		if( !isset($_COOKIE['outmax_chain']) and !empty($_SERVER['HTTP_REFERER']) and stripos($_SERVER['HTTP_REFERER'],'outmaxshop') === false ) {
			
			//do something
			$db->query("INSERT INTO om_statchain_starter SET http_referer = '".$_SERVER['HTTP_REFERER']."', request_uri = '".$_SERVER['REQUEST_URI']."', user_agent = '".$_SERVER['HTTP_USER_AGENT']."', start_time = '".time()."'");

			$STAT_CHAIN = mysqli_insert_id($db);// последний вставленный ID - в таблице должно быть AUTO_INCREMENT поле


			$cookie_name='outmax_chain';//присваиваем имя куке
			$cookie_value = $STAT_CHAIN; // присваиваем куке значение STAT_CHAIN
			$cookie_time = time();// ставим текущее время сервера
	
			setcookie($cookie_name, $cookie_value, $cookie_time + (24 * 60 * 60 * 365), '/','.outmaxshop.ru');


			$cookie_name1='outmax_chain_start';//присваиваем имя куке
			$cookie_value1 = time(); // присваиваем куке значение STAT_CHAIN
			$cookie_time1 = time();// ставим текущее время сервера
	
			setcookie($cookie_name1, $cookie_value1, $cookie_time1 + (24 * 60 * 60 * 365), '/','.outmaxshop.ru');

		
		}

		// если есть кука цепочки - записать в лог
		// если не установлен $STAT_CHAIN но установлена $_COOKIE['outmax_chain'] - то добавить запись в лог посещений
		// ну соответственно, если реферер не пуст и это не внутренний переход по сайту
		// нам интересны внешние источники

		$CHAIN_SAVE_DELTA = time() - (int) (isset($_COOKIE['outmax_chain_start']) ? $_COOKIE['outmax_chain_start'] : 0);

		if( isset($_COOKIE['outmax_chain']) and $CHAIN_SAVE_DELTA > 2 and !empty($_SERVER['HTTP_REFERER']) and stripos($_SERVER['HTTP_REFERER'],'outmaxshop') === false and !isset($STAT_CHAIN) ) {

			//do something else
			$db->query("INSERT INTO om_statchain_log SET chain_id = '".$_COOKIE['outmax_chain']."', http_referer = '".$_SERVER['HTTP_REFERER']."', request_uri = '".$_SERVER['REQUEST_URI']."', visit_time = '".time()."'");

			// уточнить, сколько времени прошло с предыдущей записи?
			// !!!! Двойной редирект сносит метки. Почему? 
			// Получается скрипт вызывается дважды за эту секунду!!! 
			// При этом первая запись - корректная (с метками), а вот вторая - без меток!, только нахер надо задвоение? 
			// надо избавиться

			$cookie_name1='outmax_chain_start';//присваиваем имя куке
			$cookie_value1 = time(); // присваиваем куке значение STAT_CHAIN
			$cookie_time1 = time();// ставим текущее время сервера
	
			setcookie($cookie_name1, $cookie_value1, $cookie_time1 + (24 * 60 * 60 * 365), '/','.outmaxshop.ru');

		
		}


	}
	// NEOS - log all files requests
	if (!empty($_FILES)) {
		file_put_contents(
			DIR_LOGS . '/files.log',
			"[" . date('Y-m-d H:i:s') . "] " . json_encode(array(
				'uri' => $_SERVER['REQUEST_URI'],
				'files' => $_FILES,
				'request' => $_REQUEST
			)) . "\n",
			FILE_APPEND
		);
	}

?>