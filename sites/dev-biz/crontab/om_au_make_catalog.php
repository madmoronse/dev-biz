<?php

	// http://new.outmaxshop.ru/crontab/om_au_make_catalog.php


	require_once 'sql_connect.php';

	$SKUS=$DB->select('SELECT * FROM `om_24au_kopylova` ORDER BY `product_id` DESC');// получаем все арты


	foreach($SKUS as $rowSKU=>$SKU) {


		// это кроссовки?
		$SKU_CATS=$DB->select('SELECT * FROM `oc_product_to_category` WHERE `product_id`=? ORDER BY `category_id` ASC',$SKU['product_id']);

		if($SKU_CATS) {

			foreach($SKU_CATS as $rowSKU_CAT=>$SKU_CAT) {

				if ($SKU_CAT['category_id']==1 or $SKU_CAT['category_id']==97) {

					$SKU_UPD=$DB->query('UPDATE `om_24au_kopylova` SET `cat_id`=?  WHERE `product_id`=?',$SKU_CAT['category_id'],$SKU['product_id']);//присваиваем категорию

				}

			}
			

		} else {

			// это предмет одежды?
			$SKU_ATTR=$DB->selectRow('SELECT * FROM `oc_product_attribute` WHERE `product_id`=? AND `attribute_id`=?',$SKU['product_id'],20);

			//category_id=1597 мужская одежда
			//category_id=1596 женская одежда

			if ($SKU_ATTR) { 

				//это одежда
				//это мужская одежда?
				$SEX=$DB->selectRow('SELECT * FROM `oc_product_to_category` WHERE `product_id`=? AND `category_id`=?',$SKU['product_id'],1597);
				
				//может быть это женская одежда?
				if (!$SEX) $SEX=$DB->selectRow('SELECT * FROM `oc_product_to_category` WHERE `product_id`=? AND `category_id`=?',$SKU['product_id'],1596);


			} else {

				// значит это вид аксессуаров
				$SKU_ATTR=$DB->selectRow('SELECT * FROM `oc_product_attribute` WHERE `product_id`=? AND `attribute_id`=?',$SKU['product_id'],21);

			}


			if ($SKU_ATTR) {


				if ($SEX) {

					$AU_CAT=$DB->selectRow('SELECT * FROM `om_24au_categories` WHERE `serv_cat_name`=? AND `parent_id`=?',$SKU_ATTR['text'],$SEX['category_id']);// получаем категорию

				} else {

					$AU_CAT=$DB->selectRow('SELECT * FROM `om_24au_categories` WHERE `serv_cat_name`=?',$SKU_ATTR['text']);// получаем категорию
				}
	
				$SKU_UPD=$DB->query('UPDATE `om_24au_kopylova` SET `cat_id`=?  WHERE `product_id`=?',$AU_CAT['cat_id'],$SKU['product_id']);//присваиваем категорию

			}

		}


	}



?>