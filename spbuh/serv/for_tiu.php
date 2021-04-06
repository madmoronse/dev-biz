<?php

$DELTA=1018124;
// /var/www/www-root/data/www/outmaxshop.ru
// /var/www/u0040607/data/www/outmaxshop.ru



//$SKUS=$DB->select('SELECT * FROM `oc_product` WHERE `quantity`>? ORDER BY `product_id` DESC LIMIT 0,150',0);// выгребаем все артикулы

$SKUS=$DB->select('SELECT * FROM `oc_product` WHERE `quantity`>? ORDER BY `product_id` DESC',0);// выгребаем все артикулы

function replace_links_from_string ($STR) {

	$RESULT=$STR;

	$POS=strpos($STR,'<a href=');

	
	if ($POS===false) {} else {
	
		$POS_END=strpos($STR,'</a>');
		$B=substr($STR,$POS,$POS_END-$POS);
		$POS3=strpos($B,'>');
		$TXT=substr($B,$POS3+1);
		$RESULT=str_replace('</a>','',$STR);
		$RESULT=str_replace($B,$TXT,$RESULT);
	
	} 

	return $RESULT;

}

foreach ($SKUS as $numSKU=>$SKU) {

	

	$CAT_ID=$DB->selectCell('SELECT `category_id` FROM `oc_product_to_category` WHERE `product_id`=? AND `main_category`=?',$SKU['product_id'],1);// получаем родительский каталог
	$SEX_ID=$DB->selectCell('SELECT `category_id` FROM `oc_product_to_category` WHERE `product_id`=? AND `main_category`=? AND (`category_id`=? OR `category_id`=? OR `category_id`=? OR `category_id`=? OR `category_id`=? OR `category_id`=?)',$SKU['product_id'],0,1,97,2558,3332,2780,5075);// получаем пол
	$DESCR=$DB->selectRow('SELECT * FROM `oc_product_description` WHERE `product_id`=?',$SKU['product_id']);// получаем описания
	$VENDOR=$DB->selectCell('SELECT `name` FROM `oc_manufacturer` WHERE `manufacturer_id`=?',$SKU['manufacturer_id']);// получаем vendora




	//формирование атрибутов, формируется один раз, для разновидностей можно не переверстывать
	//oc_attribute_description это названия атрибутов
	//oc_product_attribute это значения атрибутов
	
	//пол
	//oc_category_description
	//1 - мужские, 97 - женские
	//2558 мужская зимняя обувь, 3332 мужские зимние куртки
	//2780 женская зимняя обувь, 5075 женские куртки зима
	//2830 непонятный пол одежда с начесом
	//это можно выцепить из oc_product_to_category
	//нужна восходящая цепочка до oc_category


	$ATTR_LIST='';
	
	$SEX = array(
	
		'1'=>'Мужской',
		'2558'=>'Мужской',
		'3332'=>'Мужской',
	
		'97'=>'Женский',
		'2780'=>'Женский',
		'5075'=>'Женский',
	
	);



	if ($ATTRIBS=$DB->select('SELECT * FROM `oc_product_attribute` WHERE `product_id`=?',$SKU['product_id']) ) {
	
		foreach($ATTRIBS as $rowATTR=>$ATTR) {
	
		$ATTR_NAME=$DB->selectCell('SELECT `name` FROM `oc_attribute_description` WHERE `attribute_id`=?',$ATTR['attribute_id']);
	
		$ATTR_LIST.='
			<param name="'.$ATTR_NAME.'">'.str_replace('&','&amp;',$ATTR['text']).'</param>';
	
		}
	
	
	}


	if ($SEX_ID) {
	
		$ATTR_LIST.='
			<param name="Пол">'.$SEX[$SEX_ID].'</param>';
	
	}


	$IMG_LIST='<picture>http://bizoutmax.ru/image/'.$SKU['image'].'</picture>
	';
	
	if ( $IMAGES=$DB->select('SELECT `image` FROM `oc_product_image` WHERE `product_id`=?',$SKU['product_id']) ) {
	
		// получаем список фоток
	
		foreach($IMAGES as $rowIMG=>$IMG) {
	
			if (file_exists('/var/www/www-root/data/www/outmaxshop.ru/image/'.$IMG['image'])) {
	
			$IMG_LIST.='		<picture>http://bizoutmax.ru/image/'.$IMG['image'].'</picture>
	';
	
			}
	
		}
		
	
	}


	//добавляем размер
	if ( $RAZMERS=$DB->select('SELECT * FROM `oc_product_option_value` WHERE `product_id`=? AND `quantity`>? AND (`option_id`=? OR `option_id`=?) ORDER BY `option_value_id` ASC',$SKU['product_id'],0,3,4) ) {
	
		//если есть размеры больше нуля, то срабатывает это правило
	
		$i=0;$postfix_id='';
		foreach ($RAZMERS as $rowRAZM=>$RAZM){
	
			//option_id=82 - размер обуви
			//option_id=83 - размер одежды
	
			//option_id=3 - размер обуви
			//option_id=4 - размер одежды
		
			$RAZM_NAME=$DB->selectCell('SELECT `ext_id` FROM `oc_option_value` WHERE `option_value_id`=?',$RAZM['option_value_id']);
	
	//$SKU['product_id'] первая разновидность всегда должна быть под артикулом
	// остальное тоже должно быть фиксой и к тому же уникальной
	// как это сделать на основе $SKU['product_id']?
	// какой алгоритм должен быть?
	
	
	$LIST.='
		<offer available="true" group_id="'.($DELTA+$SKU['product_id']).'" id="' . $SKU['product_id'].$RAZM['option_value_id'] . '">
			<price>'.(int)$SKU['price'].'</price>
			<currencyId>RUR</currencyId>
			<categoryId>'.$CAT_ID.'</categoryId>
			'.$IMG_LIST.'		<pickup>true</pickup>
			<delivery>true</delivery>
			<quantity>'.$RAZM['quantity'].'</quantity>
			<barcode>'.$SKU['product_id'].'</barcode>
			<name>'.str_replace('&','&amp;',$SKU['fullname']).'</name>
			<vendor>'.str_replace('&','&amp;',$VENDOR).'</vendor>
			<sales_notes>предоплата</sales_notes>'.$ATTR_LIST.'
			<description>'.str_replace('&','&amp;',strip_tags($DESCR['description'])).'</description>
			<param name="Размер">'.$RAZM_NAME.'</param>

		</offer>';	
	
	
	
		}
	
	
	
	
	}


	if ( !$RAZMERS=$DB->select('SELECT * FROM `oc_product_option_value` WHERE `product_id`=? AND (`option_id`=? OR `option_id`=?)',$SKU['product_id'],3,4) ) {
	
	
	//если нет размеров, то вот это правило
	
	$LIST.='
		<offer available="true" group_id="'.($DELTA+$SKU['product_id']).'" id="' . $SKU['product_id'] . '">
			<price>'.(int)$SKU['price'].'</price>
			<currencyId>RUR</currencyId>
			<categoryId>'.$CAT_ID.'</categoryId>
			'.$IMG_LIST.'		<pickup>true</pickup>
			<delivery>true</delivery>
			<quantity>'.$SKU['quantity'].'</quantity>
			<barcode>'.$SKU['product_id'].'</barcode>
			<name>'.str_replace('&','&amp;',$SKU[fullname]).'</name>
			<vendor>'.str_replace('&','&amp;',$VENDOR).'</vendor>
			<sales_notes>предоплата</sales_notes>'.$ATTR_LIST.'
			<description>'.str_replace('&','&amp;',strip_tags($DESCR[description])).'</description>
		</offer>';
	
	}
	
	
}

/*
print '<offers>'.$LIST.'
</offers>
</shop></yml_catalog>';
*/

$FCONT='<offers>'.$LIST.'
</offers>
</shop></yml_catalog>';

	$file = 'tiu.xml';
	//$file1= $ROOT_PATH.$SRV_NAME.'/robots.txt';
	
	file_put_contents($file, $FCONT);



?>