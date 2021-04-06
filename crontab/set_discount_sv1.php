<?php

	// http://bizoutmax.ru/crontab/set_discount_sv1.php


	//подключение другой библиотеки для работы с БД - раскомментировать и заработает

use Neos\classes\util as U;
use Neos\classes\log as Log;
define('_NEXEC', 1);
define('NPATH_BASE', __DIR__ . '/../neos_debug');

require_once NPATH_BASE . '/_includes/constants.php';
require_once NPATH_INCLUDES . '/loader.php';
require_once NPATH_BASE . '/../config.php';

NeosLoader::setup();

	$db = U\DBSingleton::getInstance(array(
            'host' => DB_HOSTNAME,
            'user' => DB_USERNAME,
            'pass' => DB_PASSWORD,
            'db' => DB_DATABASE
        ));




/*
	define('DB_USERNAME', 'u0040607_admin');
	define('DB_PASSWORD', '8LES9axUJKbbWZnF');

	как это сделать автоматом? а то придется вставать в 8 утра... бля

	/opt/php53/bin/php /var/www/www-root/data/www/outmaxshop.ru/crontab/new_products.php 500

	UPDATE `oc_product` SET `discount`=12 
	UPDATE `oc_product_11_06_2018_exp` SET `price`=`price`*0.88
	UPDATE `oc_product_11_06_2018_exp` SET `points`=`price`

*/



	//$db->query('UPDATE `oc_product` SET `discount` = ?i WHERE `status` = ?i',12,1);//для другой библиотеки
	//$db->query('UPDATE `oc_product` SET `price` = (price*0.88 DIV 10)*10 WHERE `status` = ?i',1);//для другой библиотеки


	$SKUS = array (10720,10721,10770,10771,10772,10773,10774,10775);

	foreach($SKUS as $SKU) {

		$db->query('UPDATE `oc_product` SET `discount` = ?i WHERE `product_id` = ?i',40,$SKU);//для другой библиотеки

	}




?>
