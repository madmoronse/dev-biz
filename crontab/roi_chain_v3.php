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

	if (!$BOTS_DETECTED) {


			if( !isset($_COOKIE['outmax_chain']) and !empty($_SERVER['HTTP_REFERER']) and stripos($_SERVER['HTTP_REFERER'],'outmaxshop') === false ) {
				
				//do something
			
			}

			//если есть кука цепочки - записать в лог

			if( isset($_COOKIE['outmax_chain']) and !empty($_SERVER['HTTP_REFERER']) and stripos($_SERVER['HTTP_REFERER'],'outmaxshop') === false ) {

				//do something else
			
			}


	}


?>