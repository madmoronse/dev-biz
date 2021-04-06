<?php

	//Структура папок Новинки и Промо
	//На случай, если 1С опять убьет всё
	
	// это объедить в один скрипт?
	
	$SQLS = array(
	
		"INSERT INTO `oc_category` (`category_id`, `image`, `menu_image`, `parent_id`, `top`, `linkto`, `column`, `sort_order`, `status`, `date_added`, `date_modified`, `ext_id`) VALUES (7607, '', 1, 0, 0, '', 1, 5, 1, '2017-10-16 15:43:03', '2017-10-16 16:08:03', NULL)",
		"INSERT INTO `oc_category` (`category_id`, `image`, `menu_image`, `parent_id`, `top`, `linkto`, `column`, `sort_order`, `status`, `date_added`, `date_modified`, `ext_id`) VALUES (7608, '', 1, 7607, 0, '', 1, 0, 1, '2017-10-16 15:43:27', '2017-10-16 15:43:27', NULL)",
		//"INSERT INTO `oc_category` (`category_id`, `image`, `menu_image`, `parent_id`, `top`, `linkto`, `column`, `sort_order`, `status`, `date_added`, `date_modified`, `ext_id`) VALUES (7609, '', 1, 0, 1, '', 0, 4, 1, '2017-10-16 15:44:15', '2017-10-16 16:34:40', NULL)",
		"INSERT INTO `oc_category` (`category_id`, `image`, `menu_image`, `parent_id`, `top`, `linkto`, `column`, `sort_order`, `status`, `date_added`, `date_modified`, `ext_id`) VALUES (7610, '', 1, 7607, 0, '', 1, 0, 1, '2017-10-18 13:46:50', '2017-10-18 13:47:01', NULL)",
		"INSERT INTO `oc_category` (`category_id`, `image`, `menu_image`, `parent_id`, `top`, `linkto`, `column`, `sort_order`, `status`, `date_added`, `date_modified`, `ext_id`) VALUES (7611, '', 1, 7607, 0, '', 1, 0, 1, '2017-11-23 13:14:39', '2017-11-23 13:25:43', NULL)",
		"INSERT INTO `oc_category` (`category_id`, `image`, `menu_image`, `parent_id`, `top`, `linkto`, `column`, `sort_order`, `status`, `date_added`, `date_modified`, `ext_id`) VALUES (7612, '', 1, 7607, 0, '', 1, 0, 1, '2017-12-06 10:12:02', '2017-12-06 10:12:02', NULL)",
		"INSERT INTO `oc_category` (`category_id`, `image`, `menu_image`, `parent_id`, `top`, `linkto`, `column`, `sort_order`, `status`, `date_added`, `date_modified`, `ext_id`) VALUES (7613, '', 1, 7607, 0, '', 1, 0, 1, '2017-12-28 07:04:50', '2017-12-28 07:04:50', NULL)",
		"INSERT INTO `oc_category` (`category_id`, `image`, `menu_image`, `parent_id`, `top`, `linkto`, `column`, `sort_order`, `status`, `date_added`, `date_modified`, `ext_id`) VALUES (7614, '', 1, 7607, 0, '', 1, 0, 1, '2018-03-08 10:13:26', '2018-03-08 10:17:53', NULL)",
		
		
		"INSERT INTO `oc_category_description` (`category_id`, `language_id`, `name`, `description`, `meta_description`, `meta_keyword`, `seo_title`, `seo_h1`) VALUES (7607, 1, 'Промо', '', '', '', '', '')",
		"INSERT INTO `oc_category_description` (`category_id`, `language_id`, `name`, `description`, `meta_description`, `meta_keyword`, `seo_title`, `seo_h1`) VALUES (7608, 1, 'Супер распродажа', '', '', '', '', '')",
		//"INSERT INTO `oc_category_description` (`category_id`, `language_id`, `name`, `description`, `meta_description`, `meta_keyword`, `seo_title`, `seo_h1`) VALUES (7609, 1, 'Новинки', '', 'Новинки', 'Новинки', 'Новинки', 'Новинки')",
		"INSERT INTO `oc_category_description` (`category_id`, `language_id`, `name`, `description`, `meta_description`, `meta_keyword`, `seo_title`, `seo_h1`) VALUES (7610, 1, 'Зимние кроссовки', '', 'Зимние кроссовки', 'Зимние кроссовки', 'Зимние кроссовки', 'Зимние кроссовки')",
		"INSERT INTO `oc_category_description` (`category_id`, `language_id`, `name`, `description`, `meta_description`, `meta_keyword`, `seo_title`, `seo_h1`) VALUES (7611, 1, 'Футбол', '', 'Футбол', 'Футбол', 'Футбол', 'Футбол')",
		"INSERT INTO `oc_category_description` (`category_id`, `language_id`, `name`, `description`, `meta_description`, `meta_keyword`, `seo_title`, `seo_h1`) VALUES (7612, 1, 'Зимние товары', '', '', 'Зимние товары', 'Зимние товары', 'Зимние товары')",
		"INSERT INTO `oc_category_description` (`category_id`, `language_id`, `name`, `description`, `meta_description`, `meta_keyword`, `seo_title`, `seo_h1`) VALUES (7613, 1, 'Tunuo', '', '', 'Tunuo', 'Tunuo', 'Tunuo')",
		"INSERT INTO `oc_category_description` (`category_id`, `language_id`, `name`, `description`, `meta_description`, `meta_keyword`, `seo_title`, `seo_h1`) VALUES (7614, 1, 'Баскетбол', '', 'Баскетбол', 'Баскетбол', 'Баскетбол', 'Баскетбол')",
		
		
		"INSERT INTO `oc_category_path` (`category_id`, `path_id`, `level`) VALUES (7607, 7607, 0)",
		"INSERT INTO `oc_category_path` (`category_id`, `path_id`, `level`) VALUES (7608, 7608, 1)",
		"INSERT INTO `oc_category_path` (`category_id`, `path_id`, `level`) VALUES (7608, 7607, 0)",
		//"INSERT INTO `oc_category_path` (`category_id`, `path_id`, `level`) VALUES (7609, 7609, 0)",
		"INSERT INTO `oc_category_path` (`category_id`, `path_id`, `level`) VALUES (7610, 7610, 1)",
		"INSERT INTO `oc_category_path` (`category_id`, `path_id`, `level`) VALUES (7610, 7607, 0)",
		"INSERT INTO `oc_category_path` (`category_id`, `path_id`, `level`) VALUES (7611, 7607, 0)",
		"INSERT INTO `oc_category_path` (`category_id`, `path_id`, `level`) VALUES (7611, 7611, 1)",
		"INSERT INTO `oc_category_path` (`category_id`, `path_id`, `level`) VALUES (7612, 7607, 0)",
		"INSERT INTO `oc_category_path` (`category_id`, `path_id`, `level`) VALUES (7612, 7612, 1)",
		"INSERT INTO `oc_category_path` (`category_id`, `path_id`, `level`) VALUES (7613, 7607, 0)",
		"INSERT INTO `oc_category_path` (`category_id`, `path_id`, `level`) VALUES (7613, 7613, 1)",
		"INSERT INTO `oc_category_path` (`category_id`, `path_id`, `level`) VALUES (7614, 7607, 0)",
		"INSERT INTO `oc_category_path` (`category_id`, `path_id`, `level`) VALUES (7614, 7614, 1)",
	
		
		
		"INSERT INTO `oc_category_to_store` (`category_id`, `store_id`) VALUES (7607, 0)",
		"INSERT INTO `oc_category_to_store` (`category_id`, `store_id`) VALUES (7608, 0)",
		//"INSERT INTO `oc_category_to_store` (`category_id`, `store_id`) VALUES (7609, 0)",
		"INSERT INTO `oc_category_to_store` (`category_id`, `store_id`) VALUES (7610, 0)",
		"INSERT INTO `oc_category_to_store` (`category_id`, `store_id`) VALUES (7611, 0)",
		"INSERT INTO `oc_category_to_store` (`category_id`, `store_id`) VALUES (7612, 0)",
		"INSERT INTO `oc_category_to_store` (`category_id`, `store_id`) VALUES (7613, 0)",
		"INSERT INTO `oc_category_to_store` (`category_id`, `store_id`) VALUES (7614, 0)",
	
	
	);
	
	require_once 'sql_connect.php';
	
	foreach($SQLS as $SQL) $CAT_INS=$DB->query($SQL);



?>