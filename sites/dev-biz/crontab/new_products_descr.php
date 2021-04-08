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



	//допил косяков с выгрузкой разных товаров
	$db->query("UPDATE `oc_product` SET `jan`=`fullname`");//корректировка для работы поиска
	
	$db->query("UPDATE `oc_product_option` SET `required`=0 WHERE `product_id` = ?i",2671);//безразмерные леггинсы
	$db->query("UPDATE `oc_product_option` SET `required`=0 WHERE `product_id` = ?i",2672);//безразмерные леггинсы
	$db->query("UPDATE `oc_product_option` SET `required`=0 WHERE `product_id` = ?i",2872);//безразмерные леггинсы
	$db->query("UPDATE `oc_product_option` SET `required`=0 WHERE `product_id` = ?i",4028);//безразмерные леггинсы
	$db->query("UPDATE `oc_product_option` SET `required`=0 WHERE `product_id` = ?i",4514);//безразмерные леггинсы

	//устанавливаем нужное время для нужных новинок, которые всегда надо держать на верху в определенном порядке
	//список в таблице om_datemod





	//переназначаем метадескрипшн в таблице oc_product_description
	$SKUS1=$db->getAll('SELECT * FROM `oc_product`');
	foreach($SKUS1 as $rowSKU1=>$SKU1) {
		$db->query('UPDATE `oc_product_description` SET `meta_description` = ?s, `meta_keyword` = ?s, `seo_title` = ?s, `seo_h1` = ?s  WHERE `product_id` = ?i',$SKU1[fullname],$SKU1[fullname],$SKU1[fullname],$SKU1[fullname],$SKU1['product_id']);
	}
	

	//переназначаем размер скидки в таблице oc_product
	$SKUS2=$db->getAll('SELECT * FROM `om_discounts`');
	foreach($SKUS2 as $rowSKU2=>$SKU2) {
		$db->query('UPDATE `oc_product` SET `discount` = ?i  WHERE `product_id` = ?i',$SKU2[current_discount],$SKU2['product_id']);
	}





