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



	//устанавливаем нужное время для нужных новинок, которые всегда надо держать наверху в определенном порядке
	//список в таблице om_datemod


	$SKUS=$db->getAll('SELECT * FROM `om_datemod` ORDER BY `id` ASC LIMIT 1000');

	foreach($SKUS as $rowSKU=>$SKU) {


		//надо чекнуть, есть ли этот арт в суперакции и есть ли он в принципе в базе. 
		//Если есть в скидках, то дату не менять. 
		//Если нет в базе - дату не менять


		$CD=$db->getRow("SELECT * FROM `oc_product` WHERE `product_id`=?i",$SKU['product_id']);//ищем арт, чтобы извлечь текущий статус

		$db->query('UPDATE `om_datemod` SET `status` = ?i WHERE `product_id` = ?i',$CD['status'],$SKU['product_id']); //ставим статус


		$DM=$SKU['date_modified'];

		$db->query('UPDATE `oc_product` SET `date_modified` = ?s WHERE `product_id` = ?i',$DM,$SKU['product_id']);// ставим время





	}



//вставка в новинки списка товаров с сортировкой по времени по убыванию от свежего к старому


if (!empty($argv[1]) && is_numeric($argv[1])) {
    $limit = $argv[1];
} else {
    $limit = 500;
}

$category_id = $db->getOne("SELECT `cat`.`category_id` 
             FROM `" . DB_PREFIX . "category` as `cat` 
             INNER JOIN `" . DB_PREFIX . "category_description`  as `cat_ds` 
             ON `cat_ds`.`category_id` = `cat`.`category_id`
             WHERE `cat_ds`.`language_id` = 1 AND `cat_ds`.`name` = 'новинки' AND `cat`.`parent_id` = 0");

if (empty($category_id)) {
    exit;
}

$db->query("DELETE FROM `" . DB_PREFIX . "product_to_category` WHERE `category_id` = ?i", $category_id);
echo "\nDeleted rows: " . $db->affectedRows() . "\n";

/*
$db->query("INSERT INTO `" . DB_PREFIX . "product_to_category` (SELECT `p`.`product_id`, ?p, ?p FROM `" . DB_PREFIX . "product` as `p` 
            LEFT JOIN `" . DB_PREFIX . "product_description` as  `pd` ON `pd`.`product_id` = `p`.`product_id` 
            LEFT JOIN `" . DB_PREFIX . "product_to_store` as  `p2s` ON `p2s`.`product_id` = `p`.`product_id`
            LEFT JOIN `" . DB_PREFIX . "product_to_category` as `p2c` ON `p2c`.`product_id` = `p`.`product_id` 
            WHERE `pd`.`language_id` = '1' AND `p`.`status` = '1' 
            AND `p`.`date_available` <= NOW() AND `p2s`.`store_id` = 0
            GROUP BY `p`.`product_id`
            ORDER BY `p`.`date_modified` DESC LIMIT ?i)", $category_id, 0, $limit);
*/

//обращаемся к исходной таблице новинок,чтобы на 100% управлять выдачей
$db->query("INSERT INTO `" . DB_PREFIX . "product_to_category` (SELECT `p`.`product_id`, ?p, ?p FROM `om_datemod` as `p` 
            LEFT JOIN `" . DB_PREFIX . "product_description` as  `pd` ON `pd`.`product_id` = `p`.`product_id` 
            LEFT JOIN `" . DB_PREFIX . "product_to_store` as  `p2s` ON `p2s`.`product_id` = `p`.`product_id`
            LEFT JOIN `" . DB_PREFIX . "product_to_category` as `p2c` ON `p2c`.`product_id` = `p`.`product_id` 
            WHERE `pd`.`language_id` = '1' AND `p`.`status` = '1' 
            AND `p`.`date_available` <= NOW() AND `p2s`.`store_id` = 0
            GROUP BY `p`.`product_id`
            ORDER BY `p`.`date_modified` DESC LIMIT ?i)", $category_id, 0, $limit);

echo "Inserted rows: " . $db->affectedRows() . "\n"; 


