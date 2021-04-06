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


