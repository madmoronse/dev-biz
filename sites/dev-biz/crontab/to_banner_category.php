<?php

	// http://new.outmaxshop.ru/crontab/to_banner_category.php


	require_once 'sql_connect.php';

	//баскетбол
	//промо баскетбол category_id=7614

	$SKUS=$DB->select('SELECT * FROM `oc_product_attribute` WHERE `text`=? ORDER BY `product_id` DESC','Баскетбол');// выгребаем все артикулы где есть слово

	foreach($SKUS as $rowSKU=>$SKU) {

		$DB->query('INSERT INTO `om_bannercat` (
		
			`product_id`,
			`category_id`)
		
			VALUES (?,?)',
		
			$SKU['product_id'],
			7614		
		
		);


	}



?>