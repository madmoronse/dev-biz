<?php
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


	$db->query('UPDATE `oc_product` SET `status` = ?i  WHERE `quantity` = ?i',0,0);
	
	//переназначаем размер скидки в таблице oc_product
	$SKUS2=$db->getAll('SELECT * FROM `om_discounts`');
	foreach($SKUS2 as $rowSKU2=>$SKU2) {
		$db->query('UPDATE `oc_product` SET `discount` = ?i  WHERE `product_id` = ?i',$SKU2['current_discount'],$SKU2['product_id']);
	}

	//data/products/9997/Asics-sportivnyy-kostum-2.html

	$db->query('UPDATE `oc_product_option_value` SET `price` = ?i  WHERE `price` > ?i',0,0);//в этой таблице должны быть цены=0

	$db->query('UPDATE `oc_product` SET `quantity` = ?i,`price`=?i  WHERE `product_id` = ?i',1000,0,1240);//на остатка 1936 на 8.02.3018 это подарочный артикул
	$db->query('UPDATE `oc_product` SET `quantity` = ?i,`price`=?i  WHERE `product_id` = ?i',1000,0,8290);//на остатка 2261 на 8.02.3018 это подарочный артикул




