<?php
defined('_NEXEC') or die('Restricted Access');
// Global definitions
$parts = explode(DIRECTORY_SEPARATOR, NPATH_BASE);
define('NPATH_DS', DIRECTORY_SEPARATOR);
// Defines.
define('NPATH_ROOT',                  implode(NPATH_DS, $parts));
define('NPATH_INCLUDES',              NPATH_ROOT . NPATH_DS . '_includes');
define('NPATH_LIBRARIES',             NPATH_ROOT . NPATH_DS . 'libraries');
define('NPATH_CLASSES',               NPATH_ROOT . NPATH_DS . 'classes');
define('NPATH_CACHE',                 NPATH_ROOT . NPATH_DS . '..' . NPATH_DS . 'cache');
define('NPATH_DATA',                  NPATH_ROOT . NPATH_DS . 'data');

//Errors
define("NEOS_ERROR_YANDEX_API", "Возникла ошибка при обращении к сервису определния города, попробуйте позже или обратитесь в тех. поддержку");
define("NEOS_WARNING_YANDEX_API_CITY_NOT_FOUND", 'К сожалению, мы не смогли найти ваш город, обратитесь в тех. поддержку');