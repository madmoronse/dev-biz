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


	
	//переназначаем размер скидки в таблице oc_product
	$SKUS2=$db->getAll('SELECT * FROM `om_discounts`');
	foreach($SKUS2 as $rowSKU2=>$SKU2) {
		$db->query('UPDATE `oc_product` SET `discount` = ?i  WHERE `product_id` = ?i',$SKU2[current_discount],$SKU2['product_id']);
	}








