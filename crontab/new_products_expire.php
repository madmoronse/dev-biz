<?php

use Neos\classes\util as U;

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

// Functions
function unset_products_from_categories(array $products_ids, array $categories_ids)
{
    global $db;
    $db->query(
        'DELETE FROM ?n
        WHERE `product_id` IN (?a) AND `category_id` IN (?a) AND `main_category` = 0',
        DB_PREFIX . 'product_to_category',
        $products_ids,
        $categories_ids
    );
    return $db->affectedRows();
}


function get_category_parent_ids($category_id)
{
    global $db;
    $ids = array();
    $parent_id = $db->getOne(
        "SELECT parent_id FROM ?n WHERE category_id = ?i",
        DB_PREFIX . 'category',
        $category_id
    );
    if ($parent_id) {
        $ids[] = $parent_id;
        $ids = array_merge($ids, get_category_parent_ids($parent_id));
    }
    return $ids;
}

// Script
$lifetime_in_weeks = (int) $db->getOne(
    'SELECT `value` FROM ?n WHERE `key` = ?s',
    DB_PREFIX . 'setting',
    'newproducts_lifetime_in_weeks'
);

$date = new DateTime();
$date->modify('-' . $lifetime_in_weeks . ' week');
$date->setTime(0, 0, 0);

$result = $db->query(
    'SELECT * FROM ?n WHERE date_added <= ?s',
    DB_PREFIX . 'module_newproducts',
    $date->format('Y-m-d H:i:s')
);

$categories = array();
while ($row = $db->fetch($result)) {
    if (!isset($categories[$row['category_id']])) {
        $categories[$row['category_id']] = array();
    }
    $categories[$row['category_id']][] = $row['product_id'];
}
$removed = 0;
$removed_relations = 0;
foreach ($categories as $category_id => $products_ids) {
    $categories_ids = get_category_parent_ids($category_id);
    array_unshift($categories_ids, $category_id);
    $removed_relations += unset_products_from_categories($products_ids, $categories_ids);
    $db->query(
        'DELETE FROM ?n WHERE `product_id` IN (?a) AND `category_id` = ?i',
        DB_PREFIX . 'module_newproducts',
        $products_ids,
        $category_id
    );
    $removed += $db->affectedRows();
}

echo "Removed $removed product(s), product to category relations: $removed_relations\n";
