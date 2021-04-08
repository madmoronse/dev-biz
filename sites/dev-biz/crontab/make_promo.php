<?php

	// http://bizoutmax.ru/crontab/make_promo.php

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
	


	// вначале добавить файл в БД

	$filename=$_SERVER['DOCUMENT_ROOT'].'/exchange/promo.csv';

	$afields = array('product_id','category_id',);



	$line_end='\\r\\n';
	$delim=';';
	$hasheader=TRUE;		//игнорировать 1-ю строку, т.к. там хранятся названия полей



	if($hasheader) {$IGNORE_1_LINES = "IGNORE 1 LINES";} else {$IGNORE_1_LINES = "";}

	$LOAD_QUERY="LOAD DATA LOCAL INFILE '" . $filename . "' INTO TABLE `om_bannercat` FIELDS TERMINATED BY '" . $delim . "' LINES TERMINATED BY '" . $line_end . "' " . $IGNORE_1_LINES;

	print $LOAD_QUERY;



	$db->query($LOAD_QUERY);





?>