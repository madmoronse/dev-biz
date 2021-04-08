<?php
use Neos\classes\util as U;
use Neos\classes\log as Log;
define('_NEXEC', 1);
define('NPATH_BASE', __DIR__ . '/../neos_debug');

require_once NPATH_BASE . '/_includes/constants.php';
require_once NPATH_INCLUDES . '/loader.php';
require_once NPATH_BASE . '/../config.php';

require_once 'lib_functions.php';

NeosLoader::setup();

$db = U\DBSingleton::getInstance(array(
            'host' => DB_HOSTNAME,
            'user' => DB_USERNAME,
            'pass' => DB_PASSWORD,
            'db' => DB_DATABASE
        ));


	$FIX_SKU_IMG = array(
11019,


	);//арты которые надо пофиксить

	// читаем директорию по артикулу и формируем галерею

	$dir='/var/www/www-root/data/www/outmaxshop.ru/image/data/products/';//физ.папка

	foreach($FIX_SKU_IMG as $SKU) {


		$dir='/var/www/www-root/data/www/outmaxshop.ru/image/data/products/'.$SKU;//физ папка

		$DB_MAINIMG=$db->getRow("SELECT * FROM `oc_product` WHERE `product_id`=?i",$SKU);//ищем арт в товарах
		$DB_IMAGES=$db->getAll("SELECT * FROM `oc_product_image` WHERE `product_id`=?i ORDER BY `image` ASC",$SKU);//ищем арт в картинках

		$DBMIGM = array(

			'0' =>	Array(
					'image' => $DB_MAINIMG['image'],
				),

		);

		$ALL_DB_IMAGES = array_merge($DBMIGM,$DB_IMAGES);//объединяем 2 массива

		print_r ($ALL_DB_IMAGES);

		$IMAGES=myscandir($dir, 0);
		asort($IMAGES);//сортировка по возрастанию

		$i=0;
		if (count($IMAGES)>0) {
	
			foreach ($IMAGES as $IMG) {


					$IMG_NAME=str_replace('data/products/'.$SKU.'/','',$ALL_DB_IMAGES[$i]['image']);
				
					$IMG_GAL.='<div><img src="/image/data/products/'.$SKU.'/'.$IMG.'" alt="'.$IMG_NAME.' => '.$IMG.'" style="width:200px;margin:10px;"></div>';

					//rename ( string $oldname , string $newname [, resource $context ] )

					rename ( $dir.'/'.$IMG,$dir.'/'.$IMG_NAME);	//ренейм файлов

					$i++;


	
			}
	
		}

		unset($DB_MAINIMG,$DBMIGM,$DB_IMAGES,$ALL_DB_IMAGES,$IMAGES,$IMG_GAL,$IMG);//убиваем все переменные от греха подальше

	}


	print $IMG_GAL;


?>





