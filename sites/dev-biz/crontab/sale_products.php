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




	$db->query("DELETE FROM `om_bannercat` WHERE `category_id` = ?i",7608);//чистим данные по акции


	$SKUS=$db->getAll("SELECT * FROM `oc_product_attribute` WHERE `attribute_id` = ?i",23);//получаем все арты, которые сейчас в акции


	foreach($SKUS as $rowSKU=>$SKU) {

		$db->query("INSERT INTO `om_bannercat` (`product_id`, `category_id`) VALUES (?i,?i)",$SKU[product_id],7608);//вставляем новые значения

	}


