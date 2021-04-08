<?php

	require_once 'sql_connect.php';

	//устанавливаем нужное время для нужных новинок, которые всегда надо держать наверху в определенном порядке
	//список в таблице om_datemod


	$SKUS=$DB->select('SELECT * FROM `om_datemod` ORDER BY `id` ASC LIMIT 1000');

	foreach($SKUS as $rowSKU=>$SKU) {


		//надо чекнуть, есть ли этот арт в суперакции и есть ли он в принципе в базе. 
		//Если есть в скидках, то дату не менять. 
		//Если нет в базе - дату не менять


		//$CD=$DB->selectRow("SELECT * FROM `oc_product` WHERE `product_id`=?",$SKU['product_id']);//ищем арт, чтобы извлечь текущий статус

		//$UPD=$DB->query('UPDATE `om_datemod` SET `status` = ? WHERE `product_id` = ?',$CD['status'],$SKU['product_id']); //ставим статус


		$DM=$SKU['date_modified'];

		$UPD2=$DB->query('UPDATE `oc_product` SET `date_modified` = ? WHERE `product_id` = ?',$DM,$SKU['product_id']);// ставим время





	}



/*

REPLACE INTO `oc_product` (`product_id`, `model`, `sku`, `upc`, `ean`, `jan`, `isbn`, `mpn`, `fullname`, `location`, `quantity`, `stock_status_id`, `image`, `manufacturer_id`, `shipping`, `price`, `points`, `tax_class_id`, `date_available`, `weight`, `weight_class_id`, `length`, `width`, `height`, `length_class_id`, `subtract`, `minimum`, `sort_order`, `status`, `date_added`, `date_modified`, `viewed`, `discount`) VALUES
(6925, '6925', '6925', '', '', 'Кроссовки Adidas Yeezy 350 Boost v2', '', '', 'Кроссовки Adidas Yeezy 350 Boost v2', '', 24, 7, 'data/products/6925/Adidas-yeezy-boost-350-v2-1.jpg', 2, 1, '3090.0000', 3090, 0, '2018-04-02', '0.00000000', 0, '0.00000000', '0.00000000', '0.00000000', 1, 1, 1, 6925, 1, '2018-04-02 00:00:00', '2018-04-03 00:00:00', 63, 0);

2018-04-04 00:15:00

*/
