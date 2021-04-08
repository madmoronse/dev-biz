<?php

use Neos\classes\util as U;
use Neos\classes\log as Log;

define('_NEXEC', 1);
define('NPATH_BASE', __DIR__ . '/../neos_debug');

require_once NPATH_BASE . '/_includes/constants.php';
require_once NPATH_INCLUDES . '/loader.php';
require_once NPATH_BASE . '/../config.php';

if (php_sapi_name() !== 'cli') {
    exit('Forbidden');
}
NeosLoader::setup();

$db = U\DBSingleton::getInstance(array(
    'host' => DB_HOSTNAME,
    'user' => DB_USERNAME,
    'pass' => DB_PASSWORD,
    'db' => DB_DATABASE
));

$category_id = $db->getOne(
    "SELECT `cat`.`category_id` FROM `" . DB_PREFIX . "category` as `cat` 
    INNER JOIN `" . DB_PREFIX . "category_description`  as `cat_ds` 
    ON `cat_ds`.`category_id` = `cat`.`category_id`
    WHERE `cat_ds`.`language_id` = 1 AND `cat_ds`.`name` = 'новинки' AND `cat`.`parent_id` = 0"
);
if (empty($category_id)) {
    exit;
}

$products = $db->getAll(
    'SELECT p.product_id, p.date_modified FROM ?n as p 
    INNER JOIN ?n as pr_cat ON pr_cat.product_id = p.product_id 
    WHERE pr_cat.category_id = ?i',
    DB_PREFIX . 'product',
    DB_PREFIX . 'product_to_category',
    $category_id
);

$file = __DIR__ . '/../price/export/new_products.json';
file_put_contents($file, json_encode($products));
