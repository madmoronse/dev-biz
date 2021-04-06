<?php

use Neos\classes\util as U;
use Neos\classes\log as Log;
define('_NEXEC', 1);
define('NPATH_BASE', __DIR__ . '/../neos_debug');

require_once NPATH_BASE . '/_includes/constants.php';
require_once NPATH_INCLUDES . '/loader.php';
require_once NPATH_BASE . '/../config.php';

define('SUBSCRIBERS', NPATH_BASE . "/../uploads/subscribers.csv");
define('LAST_LINE_FILE', __DIR__ . '/src/.last_line_file_to_bd_email_sync');

NeosLoader::setup();

$db = U\DBSingleton::getInstance(array(
    'host' => DB_HOSTNAME,
    'user' => DB_USERNAME,
    'pass' => DB_PASSWORD,
    'db' => DB_DATABASE
));

@$last_line = (int) file_get_contents(LAST_LINE_FILE);
if (empty($last_line)) $last_line = 0;

out('Обрабатываем файл с строки: ' . $last_line);
if ($fp = fopen(SUBSCRIBERS, 'rb')) {
    // $count - count lines, $bulk_insert - string to insert, $c - comma, $inserted - inserted count
    $count = 0; $bulk_insert = ''; $c = ''; $inserted = 0;
    while ($row = fgetcsv($fp)) {
        $count++;
        if ($last_line >= $count) continue;
        try {
            $date = \DateTime::createFromFormat('d:m:Y H:i:s', str_replace('(UTC+0)', '', $row[2]));
            if ($date) {
                $date = $date->format('Y-m-d H:i:s');
            } else {
                $date = null;
            }
        } catch (\Exception $e) {
            $date = null;
        } 
        $bulk_insert .= $c . $db->parse("(?s, ?s, ?s)", $row[0], $row[1], $date); $c = ',';
        if ($count % 1000 == 0) {
            insertIntoTable();
        }
    }

    file_put_contents(LAST_LINE_FILE, $count);

    if (!empty($bulk_insert)) {
        insertIntoTable();
    }
    out('Новых подписчиков добавлено в базу: ' . $inserted);
} else {
    out('Не удалось прочитать файл подписчиков');
}

function insertIntoTable() {
    global $db, $bulk_insert, $c, $inserted;
    $db->query("INSERT INTO " . DB_PREFIX . "email_subscribers (`email`, `ip`, `datetime`) VALUES " . $bulk_insert . "ON DUPLICATE KEY UPDATE `datetime` = VALUES(`datetime`), `ip` = VALUES(`ip`)");
    $c = ''; $bulk_insert = '';
    $inserted += $db->affectedRows();
}

function out($message) {
    echo $message . "\n";
}