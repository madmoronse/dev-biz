<?php

	// http://new.outmaxshop.ru/crontab/om_au_import.php

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

	$MAIN_CATS=$DB->select('SELECT `product_id` FROM `oc_product_to_category` WHERE `category_id`=? ORDER BY `product_id` DESC',1);// получаем родительский каталог с мужской обувью

	foreach($MAIN_CATS as $rowMAIN_CAT=>$MAIN_CAT) {


		$PRODUCT_ID=$DB->selectCell('SELECT `product_id` FROM `oc_product_to_category` WHERE `category_id`=? AND `product_id`=?',1789,$MAIN_CAT['product_id']);// получаем артикул

		//print $PRODUCT_ID.'<br>';


		$SKU=$DB->selectRow('SELECT * FROM `oc_product` WHERE `quantity`>? AND `product_id`=?',0,$PRODUCT_ID);// выгребаем все артикулы где остатки больше 0


		if (@$SKU['quantity']>0) {


			print $SKU['fullname'].'<br>';



			$ATTRIB_14=$DB->selectRow('SELECT * FROM `oc_product_attribute` WHERE `product_id`=? AND `attribute_id`=?',$SKU['product_id'],14);//сезон
			$ATTRIB_16=$DB->selectRow('SELECT * FROM `oc_product_attribute` WHERE `product_id`=? AND `attribute_id`=?',$SKU['product_id'],16);//мат-л подошвы
			$ATTRIB_17=$DB->selectRow('SELECT * FROM `oc_product_attribute` WHERE `product_id`=? AND `attribute_id`=?',$SKU['product_id'],17);//мат-л верха

			if(strlen($ATTRIB_16['text'])>0) $MATERIAL=$ATTRIB_16['text'];

			if(strlen($MATERIAL)>0) {

				if(strlen($ATTRIB_17['text'])>0) $MATERIAL=$MATERIAL.', '.$ATTRIB_17['text'];

			} else {

				$MATERIAL=$ATTRIB_17['text'];

			}


			$IMAGES=$DB->select('SELECT * FROM `oc_product_image` WHERE `product_id`=?',$SKU['product_id']);//фотки

			foreach($IMAGES as $rowIMAGE=>$IMAGE) {

				$IMAGES_ARRAY[]=$IMAGE['image'];

			}

			sort($IMAGES_ARRAY);

			if(count($IMAGES_ARRAY<7)) {

				for($i=count($IMAGES_ARRAY);$i<8;$i++) {

					$IMAGES_ARRAY[$i]='';

				}

			}

		/*
			$SKUS[] = array (
				'product_id'	=> $PRODUCT_ID,
				'fullname'	=> $SKU['fullname'],
				'start_price'	=> $SKU['price'],
				'blitz_price'	=> $SKU['price']+100,
				`photo_1`	=> $SKU['image'],
				`photo_2`	=> $IMAGES_ARRAY[0],
				`photo_3`	=> $IMAGES_ARRAY[1],
				`photo_4`	=> $IMAGES_ARRAY[2],
				`photo_5`	=> $IMAGES_ARRAY[3],
				`photo_6`	=> $IMAGES_ARRAY[4],
				`photo_7`	=> $IMAGES_ARRAY[5],
				'quantity'	=> 1,
				'size'		=> 41,
				'season'	=> $ATTRIB_14['text'],
				'material'	=> $MATERIAL,
			);
		*/

		$DB->query('INSERT INTO `om_au_import` (
		
			`product_id`,
			`fullname`,
			`delyvery_terms`,
			`start_price`,
			`lot_durability`,
			`blitz_price`,
			`photo_1`,
			`photo_2`,
			`photo_3`,
			`photo_4`,
			`photo_5`,
			`photo_6`,
			`photo_7`,
			`lot_description`,
			`lot_category`,
			`longitude`,
			`latitude`,
			`quantity`,
			`property_1`,
			`property_value_1`,
			`property_2`,
			`property_value_2`,
			`property_3`,
			`property_value_3`,
			`property_4`,
			`property_value_4`,
			`property_5`,
			`property_value_5`)
		
			VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',
		
			$SKU['product_id'],
			$SKU['fullname'],
			'Самовывоз, Возможна доставка по Красноярску',
			$SKU['price'],
			7,
			$SKU['price']+100,
			$SKU['image'],
			$IMAGES_ARRAY[0],
			$IMAGES_ARRAY[1],
			$IMAGES_ARRAY[2],
			$IMAGES_ARRAY[3],
			$IMAGES_ARRAY[4],
			$IMAGES_ARRAY[5],
			'Наш магазин гарантирует отличное качество продукции, прекрасный сервис и максимально доступные цены.',
			'Одежда, обувь, галантерея > Мужская обувь > Кеды, кроссовки, слипоны',
			'92.822550',
			'56.013341',
			1,
			'Состояние',
			'Новое',
			'Размер обуви',
			41,
			'Сезон',
			$ATTRIB_14['text'],
			'Материал обуви',
			$MATERIAL,
			'Дополнительно',
			'Возможна примерка'
		
		
		);


			unset($MATERIAL,$IMAGES_ARRAY);




		}



	}


	//print_r ($SKUS);

/*
	foreach($SKUS as $rowSKU=>$SKU) {
	
	
		$DB->query('INSERT INTO `om_au_import` (
		
			`product_id`,
			`fullname`,
			`delyvery_terms`,
			`start_price`,
			`lot_durability`,
			`blitz_price`,
			`photo_1`,
			`photo_2`,
			`photo_3`,
			`photo_4`,
			`photo_5`,
			`photo_6`,
			`photo_7`,
			`lot_description`,
			`lot_category`,
			`longitude`,
			`latitude`,
			`quantity`,
			`property_1`,
			`property_value_1`,
			`property_2`,
			`property_value_2`,
			`property_3`,
			`property_value_3`,
			`property_4`,
			`property_value_4`,
			`property_5`,
			`property_value_5`)
		
			VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',
		
			$SKU['product_id'],
			$SKU['fullname'],
			'Самовывоз, Возможна доставка по Красноярску',
			$SKU['start_price'],
			7,
			$SKU['blitz_price'],
			$SKU['photo_1'],
			$SKU['photo_2'],
			$SKU['photo_3'],
			$SKU['photo_4'],
			$SKU['photo_5'],
			$SKU['photo_6'],
			$SKU['photo_7'],
			'Наш магазин гарантирует отличное качество продукции, прекрасный сервис и максимально доступные цены.',
			'Одежда, обувь, галантерея > Мужская обувь > Кеды, кроссовки, слипоны',
			'92.822550',
			'56.013341',
			$SKU['quantity'],
			'Состояние',
			'Новое',
			'Размер обуви',
			$SKU['size'],
			'Сезон',
			$SKU['season'],
			'Материал обуви',
			$SKU['material'],
			'Дополнительно',
			'Возможна примерка'
		
		
		);
	
	
	}


*/

?>