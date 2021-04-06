<?php

	// http://new.outmaxshop.ru/crontab/om_au_kopylova.php

	//формирование атрибутов, формируется один раз, для разновидностей можно не переверстывать
	//oc_attribute_description это названия атрибутов
	//oc_product_attribute это значения атрибутов
	
	//пол
	//oc_category_description
	//1 - мужские, 97 - женские
	//2558 мужская зимняя обувь
	//2780 женская зимняя обувь
	//это можно выцепить из oc_product_to_category
	//нужна восходящая цепочка до oc_category

	require_once 'sql_connect.php';

	$SKUS=$DB->select('SELECT * FROM `om_24au_kopylova` ORDER BY `product_id` DESC');// получаем все арты


	foreach($SKUS as $rowSKU=>$SKU) {



		//$SKU_DATAS=$DB->selectRow('SELECT * FROM `oc_product` WHERE `product_id`=?',$SKU['product_id']);// выгребаем все данные артикула

		/*

		$SKU_CATS=$DB->select('SELECT * FROM `oc_product_to_category` WHERE `product_id`=? ORDER BY `category_id` ASC',$SKU['product_id']);// выгребаем все категории

		$i=1;
		foreach($SKU_CATS as $rowSKU_CAT=>$SKU_CAT) {

			$SQL='UPDATE `om_24au_kopylova` SET `category_'.$i.'`='.$SKU_CAT['category_id'].'  WHERE `product_id`='.$SKU['product_id'];

			$SKU_UPD=$DB->query($SQL);

			$i++;

		}
		*/


		//$SKU_UPD=$DB->query('UPDATE `om_24au_kopylova` SET `fullname`=?,`quantity`=?  WHERE `product_id`=?',$SKU_DATAS['fullname'],$SKU_DATAS['quantity'],$SKU['product_id']);

		if ($SKU['category_2']==1789 or $SKU['category_3']==1789) {
			
			$SKU_UPD=$DB->query('UPDATE `om_24au_kopylova` SET `cat_24au_name`=?,`cat_24au_1`=?,`cat_24au_2`=?  WHERE `product_id`=?','Кеды, кроссовки, слипоны',1789,$SKU['category_1'],$SKU['product_id']);
		}


	}



?>