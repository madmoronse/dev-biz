<?php

	$START_TIME = '2018-07-26 00:15:00';
	$unix_START_TIME = strtotime($START_TIME);

	require_once 'sql_connect.php';//подключение к библиотеке

	// нужна табличка om_datemod_new
	// в нее загружается список новинок из csv файла и запускается этот скрит
	// запуск http://bizoutmax.ru/crontab/make_novelty.php
	// после испольнения - скопировать одну таблицу в другу таблицу

	$NOVELTY_NEW=$DB->select('SELECT * FROM `om_datemod_new` ORDER BY `id` ASC');

	foreach ($NOVELTY_NEW as $numNEWSKU=>$NEWSKU) {

			
			if ( $CHECK_SKU = $DB->selectRow('SELECT * FROM `om_datemod` WHERE `product_id`=?',$NEWSKU['product_id']) ) {

				// есть такое в старой таблице?
				$DEL = $DB->query('DELETE FROM `om_datemod` WHERE `product_id`=?',$NEWSKU['product_id']);


			}

			$UPD=$DB->query('UPDATE `om_datemod_new` SET
	
					`date_modified`=?,
					`status`=?
	
				WHERE `product_id`=?',
	
					date('Y-m-d H:i:s',$unix_START_TIME),
					1,
					$NEWSKU['product_id']
	
					
	
			);			

			$unix_START_TIME--;


	}


	//вставить как есть из om_datemod в om_datemod_new

	$UPD=$DB->query('INSERT INTO `om_datemod_new` ( 
				      `product_id`, 
				      `date_modified`, 
				      `status` ) 
				SELECT `product_id`, 
				       `date_modified`, 
				       `status`
				FROM `om_datemod` ORDER BY `date_modified` DESC'
	);




	$UPD=$DB->query('UPDATE `om_datemod_new` SET `status`=?',1);// поставить всем статус 1

	//надо дописать копирование одной талицы в другую


?>