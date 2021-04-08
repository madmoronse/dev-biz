<?php

define ('DS', DIRECTORY_SEPARATOR); // разделитель для путей к файлам
$sitePath = realpath(dirname(__FILE__) . DS) . DS;
define ('SITE_PATH', $sitePath); // путь к корневой папке сайта
define(DIR_CONTROLLER, 'controllers/');
define(DIR_MODEL, 'models/');


define(DB_HOST, 'localhost');
define(DB_NAME, 'cakes');
define(DB_USER, 'root');
define(DB_PASS, '');