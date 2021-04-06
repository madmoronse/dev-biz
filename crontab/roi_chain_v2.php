<?php
use Neos\classes\util as U;
use Neos\classes\log as Log;
define('_NEXEC', 1);
define('NPATH_BASE', __DIR__ . '/../neos_debug');

require_once NPATH_BASE . '/_includes/constants.php';
require_once NPATH_INCLUDES . '/loader.php';
require_once NPATH_BASE . '/../config.php';

NeosLoader::setup();



	//чекалка ботов, их нужно пополнять, т.к. есть реальные твари
	//заодно их отловим

	function isBot($user_agent)
	{
	    if (empty($user_agent)) {
	        return false;
	    }
	    
	    $bots = [
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



	    ];
	
	    foreach ($bots as $bot) {
	        if (stripos($user_agent, $bot) !== false) {
	            return true;
	        }
	    }
	
	    return false;
	}


	$BOTS_DETECTED = isBot($_SERVER['HTTP_USER_AGENT']);// false - не определился или не бот, иначе возвращает true


	if (!$BOTS_DETECTED) {

			// если нет куки цепочки, установить

			if( !isset($_COOKIE['outmax_chain']) and !empty($_SERVER['HTTP_REFERER']) and stripos($_SERVER['HTTP_REFERER'],'outmaxshop') === false ) {

				$db->query("INSERT INTO `om_statchain_starter` (`http_referer`,`request_uri`,`user_agent`,`start_time`) VALUES (?s,?s,?s,?s)",$_SERVER['HTTP_REFERER'],$_SERVER['REQUEST_URI'],$_SERVER['HTTP_USER_AGENT'],time());//вставляем новые значения в базу
				$STAT_CHAIN = mysql_insert_id();// последний вставленный ID - в таблице должно быть AUTO_INCREMENT поле
	

				$cookie_name='outmax_chain';//присваиваем имя куке
				$cookie_value = $STAT_CHAIN; // присваиваем куке значение STAT_CHAIN
				$cookie_time = time();// ставим текущее время сервера
		
				setcookie($cookie_name, $cookie_value, $cookie_time + (24 * 60 * 60 * 365), '/','.outmaxshop.ru');
			
			}

			//если есть кука цепочки - записать в лог

			if( isset($_COOKIE['outmax_chain']) and !empty($_SERVER['HTTP_REFERER']) and stripos($_SERVER['HTTP_REFERER'],'outmaxshop') === false ) {

				// учитываем только внешние источники и показываем это только груп_айди<2

				$db->query("INSERT INTO `om_statchain_log` (`chain_id`,`http_referer`,`request_uri`,`visit_time`) VALUES (?i,?s,?s,?s)",$_COOKIE['outmax_chain'],$_SERVER['HTTP_REFERER'],$_SERVER['REQUEST_URI'],time());//вставляем новые значения в базу


			
			}


	}


?>