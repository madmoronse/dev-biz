<?php

	// http://new.outmaxshop.ru/crontab/om_au_kopylova_yml_v3.php

	// нужно иметь статус магазина
	// тут мануал https://help.au.ru/help/158/#199
	// тут загрузчик https://au.ru/my/import/
	// лучше подготовить под ау.ру отдельный файл, специально заточенный под него
	// надо будет первый раз сопоставить категории в файле YML с категориями ау.ру через их интерфейс


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

	function mb_ucfirst($str, $encoding='UTF-8') {
	        $str = mb_ereg_replace('^[\ ]+', '', $str);
	        $str = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding).
	               mb_substr($str, 1, mb_strlen($str), $encoding);
	        return $str;
	}


	$IMG_FOLDER='http://bizoutmax.ru/image/';

$YML_FILE='';
$YML_FILE.='<?xml version="1.0" encoding="UTF-8"?>'."\r\n";
$YML_FILE.='<!DOCTYPE yml_catalog SYSTEM "shops.dtd">'."\r\n";
$YML_FILE.='<yml_catalog date="'.date("Y-m-d H:i").'">'."\r\n";
$YML_FILE.=' <shop>'."\r\n";
$YML_FILE.='  <name></name>'."\r\n";
$YML_FILE.='  <company></company>'."\r\n";
$YML_FILE.='  <url>http://bizoutmax.ru/</url>'."\r\n";
$YML_FILE.='  <phone></phone>'."\r\n";
$YML_FILE.='  <platform>ocStore</platform>'."\r\n";
$YML_FILE.='  <version>1.5.5.1.2</version>'."\r\n";
$YML_FILE.='  <currencies>'."\r\n";
$YML_FILE.='   <currency id="RUB" rate="1"/>'."\r\n";
$YML_FILE.='  </currencies>'."\r\n";
$YML_FILE.='  <categories>'."\r\n";


	$CATS_24AU=$DB->select('SELECT * FROM `om_24au_categories` ORDER BY `cat_id` ASC');// получаем все категории
	$CAT_LIST='';
	foreach($CATS_24AU as $rowCAT_24AU=>$CAT_24AU) {

		$YML_FILE.='   <category id="'.$CAT_24AU['cat_id'].'">'.$CAT_24AU['cat_name'].'</category>'."\r\n";

	}

$YML_FILE.='  </categories>'."\r\n";
$YML_FILE.='  <offers>'."\r\n";


	$SKUS=$DB->select('SELECT * FROM `om_24au_kopylova` ORDER BY `product_id` ASC');// получаем все арты 


	foreach($SKUS as $rowSKU=>$SKU) {


		@$SKU_DATAS=$DB->selectRow('SELECT * FROM `oc_product` WHERE `product_id`=?',$SKU['product_id']);// выгребаем данные артикула

		if(@$SKU_DATAS['quantity']>0) {

	
			$SKU_IMG_LIST='';
	
			$SKU_IMG_LIST.='    <picture>'.$IMG_FOLDER.$SKU_DATAS['image'].'</picture>'."\r\n";
	
	
			//формирование фото
			if ($IMAGES=$DB->select('SELECT * FROM `oc_product_image` WHERE `product_id`=? ORDER BY `image` ASC',$SKU['product_id'])) {
	
				foreach($IMAGES as $rowIMG=>$IMG) {
		
					$SKU_IMG_LIST.='    <picture>'.$IMG_FOLDER.$IMG['image'].'</picture>'."\r\n";
		
				}
	
			}



			//формирование сиска атрибутов
			$ATTR_LIST='';
			if ($ATTRIBUTES=$DB->select('SELECT * FROM `oc_product_attribute` WHERE `product_id`=? ORDER BY `attribute_id` ASC',$SKU['product_id'])) {
	
				foreach($ATTRIBUTES as $rowATTR=>$ATTR) {
		
					$ATTR_DESCR=$DB->selectRow('SELECT * FROM `oc_attribute_description` WHERE `attribute_id`=?',$ATTR['attribute_id']);// выгребаем название атрибута
		
					$ATTR_LIST.='    <param name="'.$ATTR_DESCR['name'].'">'.$ATTR['text'].'</param>'."\r\n";
		
				}
	
			}


			//формирование сиска размеров
			$SIZES_LIST='';
			$QNT=0;
			if ($SIZES=$DB->select('SELECT * FROM `oc_product_option_value` WHERE `product_id`=? AND `quantity`>? ORDER BY `option_value_id` ASC',$SKU['product_id'],0)) {
	
				foreach($SIZES as $rowSIZE=>$SIZE) {
		
					$SIZE_DESCR=$DB->selectRow('SELECT * FROM `oc_option_value_description` WHERE `option_value_id`=?',$SIZE['option_value_id']);// выгребаем название размера
					$SIZES_LIST.=$SIZE_DESCR['name'].', ';
					if($SIZE['quantity']>=1) $QNT++;
		
				}
		
				$SIZES_LIST=substr($SIZES_LIST,0,-2);
	
				$SIZES_LIST='    <param name="Размеры">'.$SIZES_LIST.'</param>'."\r\n";
	
			} else {$QNT=1;}
	
	
			if(!$SKU_MANUFACTURER=$DB->selectRow('SELECT * FROM `oc_manufacturer` WHERE `manufacturer_id`=?',$SKU_DATAS['manufacturer_id'])) {  // выгребаем данные производятла
	
				$SKU_MANUFACTURER['name']='outmax';
	
			}

			$SKU_DESCR=$DB->selectRow('SELECT * FROM `oc_product_description` WHERE `product_id`=?',$SKU['product_id']);// выгребаем описание арта
	
			//$SKU_DATAS['fullname'] нужны маленькие буквы, а все слова - с заглавной
			$SKU_DATAS['fullname']=strtolower($SKU_DATAS['fullname']);
			$SKU_DATAS['fullname']=ucwords($SKU_DATAS['fullname']);
	
			$OFFER='';
			$OFFER.='   <offer id="'.$SKU['product_id'].'" available="true" type="vendor.model">'."\r\n";
			$OFFER.='    <url>http://bizoutmax.ru/index.php?route=product/product&amp;product_id='.$SKU['product_id'].'</url>'."\r\n";
			$OFFER.='    <price>'.$SKU_DATAS['price'].'</price>'."\r\n";
			$OFFER.='    <currencyId>RUB</currencyId>'."\r\n";
			$OFFER.='    <categoryId>'.$SKU['cat_id'].'</categoryId>'."\r\n";
	
			$OFFER.=$SKU_IMG_LIST;
	
			$OFFER.='    <delivery>true</delivery>'."\r\n";
			$OFFER.='    <name>'.$SKU_DATAS['fullname'].' (арт. '.$SKU_DATAS['product_id'].')</name>'."\r\n";
			$OFFER.='    <vendor>'.$SKU_MANUFACTURER['name'].'</vendor>'."\r\n";
			$OFFER.='    <model>'.$SKU_DESCR['name'].'</model>'."\r\n";
			$OFFER.='    <outlets>'."\r\n";
			$OFFER.='     <outlet id="0" instock="'.$QNT.'"/>'."\r\n";
			$OFFER.='    </outlets>'."\r\n";
	
			$OFFER.=$ATTR_LIST;
	
			$OFFER.=$SIZES_LIST;
	
			$OFFER.='   </offer>'."\r\n";
	
			//print $OFFER;
			$YML_FILE.=$OFFER;

		}

	}


$YML_FILE.='  </offers>'."\r\n";
$YML_FILE.=' </shop>'."\r\n";
$YML_FILE.='</yml_catalog>'."\r\n";

print $YML_FILE;


?>