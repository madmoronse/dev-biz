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


	$SKUS=$db->getAll('SELECT * FROM `oc_product_attribute` WHERE `attribute_id`=?i',23);


	foreach($SKUS as $rowSKU=>$SKU) {

		$CD=$db->getRow("SELECT * FROM `oc_product` WHERE `product_id`=?i",$SKU['product_id']);//ищем арт в oc_product

		if ($CD['quantity']>0) {
	
			print $SKU['product_id'].'<br>';
			


		}


	}






