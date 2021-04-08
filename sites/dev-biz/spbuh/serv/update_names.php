<?php

$SKU_LIST = array(
	'8903',
	'8904',
	'8907',
	'8908',
	'8913',
	'8914',
	'8915',
	'8916',
	'8917',
	'8918',
	'8919',
	'8920',
	'8905',
	'8906',
	'8921',
	'8922',
	'8923',
	'8924',
	'8911',
	'8912',
	'8910',
);

/*

oc_product
manufacturer_id

oc_manufacturer_description 
manufacturer_id
language_id=1
description

oc_product_description
product_id
name


*/

	foreach($SKU_LIST as $SKU) {

		$SKU_DATA=$DB->selectRow('SELECT * FROM `oc_product` WHERE `product_id`=?',$SKU);
		$BRAND_DATA=$DB->selectRow('SELECT * FROM `oc_manufacturer_description` WHERE `manufacturer_id`=? AND `language_id`=?',$SKU_DATA[manufacturer_id],1);
		$SKU_DESCR=$DB->selectRow('SELECT * FROM `oc_product_description` WHERE `product_id`=?',$SKU);		

		$NEW_NAME=trim($SKU_DESCR[name].' '.$BRAND_DATA[description]);
		print $SKU.' | '.$NEW_NAME.'<br>';

		$UPD=$DB->query('UPDATE `oc_product` SET `fullname`=?, `jan`=? WHERE `product_id`=?',$NEW_NAME,$NEW_NAME,$SKU);

	}


?>