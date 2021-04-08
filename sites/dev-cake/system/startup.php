<?php
// Загрузка классов "на лету"
function __autoload($className) {
	$filename = strtolower($className) . '.php';
	// определяем класс и находим для него путь
	$expArr = explode('_', $className);
	if(empty($expArr[1]) OR $expArr[1] == 'Base'){
		$folder = 'system';
	}else{
		switch(strtolower($expArr[0])){
			case 'controller':
				$folder = 'controllers';
				break;

			case 'model':
				$folder = 'models';
				break;

			default:
				$folder = 'system';
				break;
		}
	}
	// путь до класса
	$file = SITE_PATH . $folder . DS . $filename;
	// проверяем наличие файла
	if (file_exists($file) == false) {
		return false;
	}
	// подключаем файл с классом
	include ($file);
}

require_once 'system/model.php';
require_once 'system/controller.php';
require_once 'system/router.php';
require_once 'system/registry.php';
require_once 'system/db.php';
require_once 'system/loader.php';
require_once 'system/request.php';
require_once 'system/cart.php';
require_once 'system/response.php';
require_once 'system/telegram.php';
require_once 'config.php';

$registry = new Registry;