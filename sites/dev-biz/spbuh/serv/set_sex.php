<?php



	$SKUS=$DB->select('SELECT * FROM `oc_product` WHERE `quantity`>?',0);

	foreach($SKUS as $rowSKU=>$SKU) {

		$SEX=$DB->selectRow('SELECT * FROM `oc_product_to_category` WHERE `product_id`=? AND (`category_id`=? OR `category_id`=?)',$SKU[product_id],1,97);

		if ($SEX[category_id]==1) $SEX_TXT='Мужской';
		if ($SEX[category_id]==97) $SEX_TXT='Женский';

		if (strlen($SEX_TXT)>0) {

			$INS=$DB->query('INSERT INTO `oc_product_attribute` (
			
								`product_id`,
								`attribute_id`,
								`language_id`,
								`text`) 
	
							VALUES (?,?,?,?)',
			
								$SKU[product_id],
								24,
								1,
								$SEX_TXT
	
			);
		}

	}



?>