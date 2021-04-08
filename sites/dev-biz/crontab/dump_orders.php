<?php
use Neos\classes\util as U;
use Neos\classes\log as Log;

define('_NEXEC', 1);
define('NPATH_BASE', __DIR__ . '/../neos_debug');
define('LAST_ORDER_FILE', __DIR__ . '/src/.last_order');
define('BACKUP_DIR', realpath(__DIR__ . '/../../../backup/orders'));

if (PHP_SAPI !== 'cli') {
    die('Restricted Access');
}
require_once NPATH_BASE . '/_includes/constants.php';
require_once NPATH_INCLUDES . '/loader.php';
require_once NPATH_BASE . '/../config.php';

function resource_to_sql($db, $result, $table)
{
    $sql = $db->parse('INSERT INTO ?n VALUES ', $table);
    $count = $db->numRows($result);
    echo "`$table` extracted rows: $count\n";
    $i = 0;
    if ($count === 0) {
        return '';
    }
    while ($row = $db->fetch($result)) {
        $i++;
        $sql .=  $db->parse('(?a)?p', $row, ($i === $count ? ";\n" : ",\n"));
    }
    return $sql;
}

NeosLoader::setup();

$db = U\DBSingleton::getInstance(array(
    'host' => DB_HOSTNAME,
    'user' => DB_USERNAME,
    'pass' => DB_PASSWORD,
    'db' => DB_DATABASE
));

$start = microtime(true);
$last_order_id = (int) file_get_contents(LAST_ORDER_FILE);
echo "Extracting from id: $last_order_id\n";
$ids = array();
$sql = array();
// Export order
$table = DB_PREFIX . 'order';
$result = $db->query('SELECT * FROM ?n WHERE `order_id` > ?i LIMIT 1000', $table, $last_order_id);
$sql['order'] = $db->parse('INSERT INTO ?n VALUES ', $table);
$count = $db->numRows($result);
echo "`$table` extracted rows: $count\n";
$i = 0;
while ($row = $db->fetch($result)) {
    $i++;
    $ids[] = $row['order_id'];
    $sql['order'] .=  $db->parse('(?a)?p', $row, ($i === $count ? ";\n" : ",\n"));
}
if (count($ids) === 0) {
    exit("Nothing to export\n");
}
// Export order_history
$table = DB_PREFIX . 'order_history';
$result = $db->query('SELECT * FROM ?n WHERE `order_id` IN (?a)', $table, $ids);
$sql['order_history'] = resource_to_sql($db, $result, $table);

// Export order_image
$table = DB_PREFIX . 'order_image';
$result = $db->query('SELECT * FROM ?n WHERE `order_id` IN (?a)', $table, $ids);
$sql['order_image'] = resource_to_sql($db, $result, $table);

// Export order_option
$table = DB_PREFIX . 'order_option';
$result = $db->query('SELECT * FROM ?n WHERE `order_id` IN (?a)', $table, $ids);
$sql['order_option'] = resource_to_sql($db, $result, $table);

// Export order_product
$table = DB_PREFIX . 'order_product';
$result = $db->query('SELECT * FROM ?n WHERE `order_id` IN (?a)', $table, $ids);
$sql['order_product'] = resource_to_sql($db, $result, $table);

// Export order_total
$table = DB_PREFIX . 'order_total';
$result = $db->query('SELECT * FROM ?n WHERE `order_id` IN (?a)', $table, $ids);
$sql['order_total'] = resource_to_sql($db, $result, $table);

printf("Execution time: %F\n", microtime(true) - $start);
$filename = BACKUP_DIR . '/orders' . date('Ymd-Hi');
$fullname = $filename . '.sql';
if (is_dir(BACKUP_DIR)) {
    file_put_contents(LAST_ORDER_FILE, max($ids));
    file_put_contents($fullname, implode("\n-- DUMP\n", array_filter($sql)));
    system('cd ' . BACKUP_DIR . ' && tar -zcvf ' . basename($filename) . '.tar.gz ' . basename($fullname));
    unlink($fullname);
} else {
    echo "Backup directory is not available\n";
}
