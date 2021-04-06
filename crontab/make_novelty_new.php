<?php

	// http://bizoutmax.ru/crontab/make_novelty_new.php

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
	

	$START_TIME = time() + (60*60*24);
	$START_TIME = date('Y-m-d',$START_TIME).' 00:15:00';
	$unix_START_TIME = strtotime($START_TIME);




	// вначале добавить файл в БД

	$TABLE_MAIN='om_datemod1';

	$TABLE_NEW='om_datemod_new';

	$filename=$_SERVER['DOCUMENT_ROOT'].'/exchange/novinki.csv';

	$afields = array('id','product_id','date_modified','status','date_available',);


	$db->query('TRUNCATE TABLE `om_datemod_new`');					// вначале нужно очистить таблицу, эта операция возможна под root?




	// потом загрузить в нее данные

	$line_end='\\r\\n';
	$delim=';';
	$hasheader=TRUE;		//игнорировать 1-ю строку, т.к. там хранятся названия полей



	if($hasheader) {$IGNORE_1_LINES = "IGNORE 1 LINES";} else {$IGNORE_1_LINES = "";}

	$LOAD_QUERY="LOAD DATA LOCAL INFILE '" . $filename . "' INTO TABLE `om_datemod_new` FIELDS TERMINATED BY '" . $delim . "' LINES TERMINATED BY '" . $line_end . "' " . $IGNORE_1_LINES;

	print $LOAD_QUERY;



	$db->query($LOAD_QUERY);




	$NOVELTY_NEW = $db->getAll('SELECT * FROM `om_datemod_new` ORDER BY `id` ASC');

	foreach ($NOVELTY_NEW as $numNEWSKU=>$NEWSKU) {

			$CHECK_SKU = $db->getRow('SELECT * FROM `om_datemod` WHERE `product_id`=?i',$NEWSKU['product_id']);
			
			if ( $CHECK_SKU ) {

				// есть такое в старой таблице?
				$DEL = $db->query('DELETE FROM `om_datemod` WHERE `product_id`=?i',$NEWSKU['product_id']);


			}

			$db->query('UPDATE `om_datemod_new` SET
	
					`date_modified`=?s,
					`status`=?i
	
				WHERE `product_id`=?i',
	
					date('Y-m-d H:i:s',$unix_START_TIME),
					1,
					$NEWSKU['product_id']
	
					
	
			);			

			$unix_START_TIME--;


	}


	//вставит как есть из om_datemod в om_datemod_new

	$NOVELTY_OLD = $db->getAll('SELECT * FROM `om_datemod` ORDER BY `date_modified` DESC');

	foreach ($NOVELTY_OLD as $numOLDSKU=>$OLDSKU) {

		//$db->query('INSERT INTO `om_datemod_new` SET `product_id`=?i, `date_modified`=?s, `status`=?i',$OLDSKU['product_id'],$OLDSKU['date_modified'],1); BMV закомментировал, чтобы старые товары не оставались в новинках

	}


	$db->query('TRUNCATE TABLE `om_datemod`');					// очищаем старую таблицу

	// загружаем новые данные

	$NOVELTY_ALL = $db->getAll('SELECT * FROM `om_datemod_new` ORDER BY `id` ASC');

	foreach ($NOVELTY_ALL as $numSKU=>$SKU) {

		$db->query('INSERT INTO `om_datemod` SET `product_id`=?i, `date_modified`=?s, `status`=?i',$SKU['product_id'],$SKU['date_modified'],1);


	}




?>