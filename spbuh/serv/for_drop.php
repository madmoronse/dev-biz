<?php

$DELTA=1018124;

//$SKUS=$DB->select('SELECT * FROM `oc_product` WHERE `quantity`>? ORDER BY `product_id` DESC LIMIT 0,150',0);// выгребаем все артикулы

$SKUS=$DB->select('SELECT * FROM `oc_product` WHERE `quantity`>? ORDER BY `product_id` DESC',0);// выгребаем все артикулы где остатки больше 0

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

	$IMAGES_ARRAY = array();

	$CAT_ID=$DB->selectCell('SELECT `category_id` FROM `oc_product_to_category` WHERE `product_id`=? AND `main_category`=?',$SKU['product_id'],1);// получаем родительский каталог
	$SEX_ID=$DB->selectCell('SELECT `category_id` FROM `oc_product_to_category` WHERE `product_id`=? AND `main_category`=? AND (`category_id`=? OR `category_id`=? OR `category_id`=? OR `category_id`=? OR `category_id`=? OR `category_id`=?)',$SKU['product_id'],0,1,97,2558,3332,2780,5075);// получаем пол
	$DESCR=$DB->selectRow('SELECT * FROM `oc_product_description` WHERE `product_id`=?',$SKU['product_id']);// получаем описания
	$VENDOR=$DB->selectCell('SELECT `name` FROM `oc_manufacturer` WHERE `manufacturer_id`=?',$SKU['manufacturer_id']);// получаем vendora
	$OPT_PRICE=$DB->selectCell('SELECT `price` FROM `oc_product_discount` WHERE `product_id`=? AND `customer_group_id`=?',$SKU['product_id'],4);// получаем дроповую цену




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

		if ($ATTR_NAME!='Распродажа' and $sex_flag!=1) {

				$ATTR_LIST.='<param name="'.$ATTR_NAME.'">'.str_replace('&','&amp;',$ATTR['text']).'</param>'."\r\n";

				if ($ATTR_NAME=='Модель') $MODEL_TAG='<model>'.str_replace('&','&amp;',$ATTR['text']).'</model>'."\r\n";
		
			}
		}
	
	}




	//$IMG_LIST='<picture>http://bizoutmax.ru/image/'.$SKU['image'].'</picture>'."\r\n";

	$IMAGES_ARRAY[]=$SKU['image'];

	if ( $IMAGES=$DB->select('SELECT `image` FROM `oc_product_image` WHERE `product_id`=?',$SKU['product_id']) ) {
	
		// получаем список фоток
	
		foreach($IMAGES as $rowIMG=>$IMG) {
	
			if (file_exists('/var/www/www-root/data/www/outmaxshop.ru/image/'.$IMG['image'])) {
	
				$IMAGES_ARRAY[]=$IMG['image'];
				//$IMG_LIST.='<picture>http://bizoutmax.ru/image/'.$IMG['image'].'</picture>'."\r\n";
	
			}
	
		}
		
	
	}

	unset($rowIMG,$IMG);

	sort($IMAGES_ARRAY);

	$IMG_LIST='';

	foreach($IMAGES_ARRAY as $IMG) {
	
		$IMG_LIST.='<picture>http://bizoutmax.ru/image/'.$IMG.'</picture>'."\r\n";
	
	}

	unset($IMAGES_ARRAY,$IMG);


	//добавляем размер
	$RAZM_LIST='';
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

			$RAZM_LIST.=$RAZM_NAME.'-'.$RAZM[quantity].';';

			unset($RAZM_NAME);

		}

	}



	//if ($RAZM_LIST!='') {$RAZM_LIST='<param name="Размеры">'.substr($RAZM_LIST,0,-1).'</param>'."\r\n";}
	if ($RAZM_LIST!='') {$RAZM_LIST='<options>'.$RAZM_LIST.'</options>'."\r\n";}

	
	$LIST.='<offer id="' . $SKU['product_id']. '" available="true">'."\r\n";
	$LIST.='<price>'.(int)$OPT_PRICE.'</price>'."\r\n";
	$LIST.='<currencyId>RUB</currencyId>'."\r\n";
	$LIST.='<categoryId>'.$CAT_ID.'</categoryId>'."\r\n";
	$LIST.=$IMG_LIST;
	$LIST.='<delivery>true</delivery>'."\r\n";
	$LIST.='<quantity>'.$SKU['quantity'].'</quantity>'."\r\n";
	$LIST.='<name>'.str_replace('&','&amp;',$SKU['fullname']).'</name>'."\r\n";
	$LIST.='<vendor>'.str_replace('&','&amp;',$VENDOR).'</vendor>'."\r\n";
	$LIST.=$MODEL_TAG;
	$LIST.=$ATTR_LIST;
	$LIST.=$RAZM_LIST;
	$LIST.='</offer>'."\r\n";	
	
	
	

	
	
	
	




	
	
}



$FCONT='<offers>'.$LIST.'
</offers>
</shop></yml_catalog>';

	$file = 'volkov_drop.xml';
	//$file1= $ROOT_PATH.$SRV_NAME.'/robots.txt';
	
	file_put_contents($file, $FCONT);



?>