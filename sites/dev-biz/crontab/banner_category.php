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


	//перенастройка суперраспродажb
	$db->query("DELETE FROM `om_bannercat` WHERE `category_id` = ?i",7608);//чистим текущие данные по акции
	$SKUS=$db->getAll("SELECT * FROM `oc_product_attribute` WHERE `attribute_id` = ?i",23);//получаем все актуальные арты, которые заехали после выгрузки
	foreach($SKUS as $rowSKU=>$SKU) {
		$db->query("INSERT INTO `om_bannercat` (`product_id`, `category_id`) VALUES (?i,?i)",$SKU['product_id'],7608);//вставляем новые значения в базу
	}



//Далее - все значения INSERT в product_to_category

$db->query("INSERT INTO `" . DB_PREFIX . "product_to_category`
SELECT `banner`.`product_id`, `banner`.`category_id`, 0 
FROM `om_bannercat` as `banner` 
LEFT JOIN  `" . DB_PREFIX . "product_to_category` as `cat` 
ON `cat`.`product_id` = `banner`.`product_id` AND `cat`.`category_id` = `banner`.`category_id`
WHERE `cat`.`product_id` IS NULL");

echo "Inserted rows: " . $db->affectedRows() . "\n"; 
