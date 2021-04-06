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


	if (!$BOTS_DETECTED and !empty($_SERVER['HTTP_USER_AGENT'])) {

		if( !isset($_COOKIE['outmax_chain']) and !empty($_SERVER['HTTP_REFERER']) and stripos($_SERVER['HTTP_REFERER'],'outmaxshop') === false ) {
			
			//do something
			$ROI_CHAIN->db->query("INSERT INTO om_statchain_starter SET http_referer = '".$_SERVER['HTTP_REFERER']."', request_uri = '".$_SERVER['REQUEST_URI']."', user_agent = '".$_SERVER['HTTP_USER_AGENT']."', start_time = '".time()."'");

			$STAT_CHAIN = mysql_insert_id();// последний вставленный ID - в таблице должно быть AUTO_INCREMENT поле


			$cookie_name='outmax_chain';//присваиваем имя куке
			$cookie_value = $STAT_CHAIN; // присваиваем куке значение STAT_CHAIN
			$cookie_time = time();// ставим текущее время сервера
	
			setcookie($cookie_name, $cookie_value, $cookie_time + (24 * 60 * 60 * 365), '/','.outmaxshop.ru');

		
		}

		//если есть кука цепочки - записать в лог

		if( isset($_COOKIE['outmax_chain']) and !empty($_SERVER['HTTP_REFERER']) and stripos($_SERVER['HTTP_REFERER'],'outmaxshop') === false ) {

			//do something else
			$ROI_VISIT->db->query("INSERT INTO om_statchain_log SET chain_id = '".$_COOKIE['outmax_chain']."', http_referer = '".$_SERVER['HTTP_REFERER']."', request_uri = '".$_SERVER['REQUEST_URI']."', visit_time = '".time()."'");
		
		}


	}


?>