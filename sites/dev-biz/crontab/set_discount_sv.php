<?php

	// http://bizoutmax.ru/crontab/set_discount_sv.php


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


	$DISCOUNT=12;//размер скидки

//	$db->query('UPDATE `oc_product_11_06_2018_exp` SET `discount` = ?i WHERE `status` = ?i',12,1); //скидку везде, где статус положительный, т.е. выводить на сайт

//	$db->query('UPDATE `oc_product_11_06_2018_exp` SET `price` = price*0.88 WHERE `status` = ?i',12,1); //скидку везде, где статус положительный, т.е. выводить на сайт


/*
	define('DB_USERNAME', 'u0040607_admin');
	define('DB_PASSWORD', '8LES9axUJKbbWZnF');

	как это сделать автоматом? а то придется вставать в 8 утра... бля

	UPDATE `oc_product` SET `discount`=12 
	UPDATE `oc_product_11_06_2018_exp` SET `price`=`price`*0.88
	UPDATE `oc_product_11_06_2018_exp` SET `points`=`price`

*/

	$SKUS=$db->getAll('SELECT * FROM `oc_product_11_06_2018_exp` WHERE `status`=?i ORDER BY `product_id` DESC',1);//для другой библиотеки

	foreach($SKUS as $rows=>$SKU) {



		$NEW_PRICE=floor(($SKU['price'])/10)*10;//вычисляется новая цена с учетом доп.скидки

		$db->query('UPDATE `oc_product_11_06_2018_exp` SET `price` = ?i,`points`=?i WHERE `product_id` = ?i',$NEW_PRICE,$NEW_PRICE,$SKU['product_id']);//для другой библиотеки




	}



?>
