<?php

use Neos\classes\Export\Orders;
use Neos\classes\Export\Writer\XMLWriter;
use Neos\classes\log\PsrLogger;

define('_NEXEC', 1);
define('NPATH_BASE', __DIR__ . '/../neos_debug');

require_once NPATH_BASE . '/_includes/constants.php';
require_once NPATH_INCLUDES . '/loader.php';
require_once NPATH_BASE . '/../config.php';
require_once NPATH_BASE . '/vendor/autoload.php';

NeosLoader::setup();
// Init
$db = Neos\classes\util\DBSingleton::getInstance(array(
    'host' => DB_HOSTNAME,
    'user' => DB_USERNAME,
    'pass' => DB_PASSWORD,
    'db' => DB_DATABASE
));
$db->query("SET SQL_MODE = ''");
$options = getopt(
    'vh',
    [
        'loglevel:',
        'export-dir:',
        'id:',
        'ignore-lock',
        'help'
    ]
);
if (isset($options['h']) || isset($options['help'])) {
    echo <<<EOD
Short commands:
-v - outputs all logs to stdout
-h - shows this reference
Available commands:
--loglevel - setup loglevel (PSR-3)
--export-dir - export directory (required)
--id - list of order ids to export, i.e.: 1,2,3. If present, previous export time will
       be ignored and new export history record won't be created.
       Should be used to export specific orders.
--ignore-lock - ignores any active export lock and overrides it (use with caution!)
--help - shows this reference\n
EOD;
    exit;
}
$logger = new PsrLogger('/export-orders.log');
if (isset($options['v'])) {
    $logger->setLogStdout(1);
}
if (isset($options['loglevel'])) {
    $logger->setLogLevel($options['loglevel']);
}
if (!isset($options['export-dir'])) {
    print('Please set --export-dir');
    exit;
}
if (!is_dir($options['export-dir'])) {
    print('Export directory must be a directory');
    exit;
}
if (isset($options['id'])) {
    $ids = array_map('intval', explode(',', $options['id']));
}
$export_table = DB_PREFIX . 'export_orders';
$lockfile = __DIR__ . '/src/.export-orders-lock';
if (!isset($options['ignore-lock']) && file_exists($lockfile)) {
    $logger->alert('Export is already in progress');
    return;
}
try {
    file_put_contents($lockfile, '');
    // Get current time from database
    $db->query('LOCK TABLES ?n READ', DB_PREFIX . 'order');
    $export_time = DateTime::createFromFormat('Y-m-d H:i:s', $db->getOne('SELECT NOW()'));
    $db->query('UNLOCK TABLES');
    if (!$export_time) {
        return;
    }
    // If it is not an export of specific orders get we should create new export history entry
    if (!isset($ids)) {
        // Extract previous export
        $previous_export = $db->getRow('SELECT * FROM ?n ORDER BY `id` DESC LIMIT 1', $export_table);
        $previous_export_time = $previous_export
            ? DateTime::createFromFormat('Y-m-d H:i:s', $previous_export['date_added'])
            : null;
        // Create new export entry
        $export = [
            'date_added' => $export_time->format('Y-m-d H:i:s')
        ];
        $db->query('INSERT INTO ?n SET ?u', $export_table, $export);
        $export['id'] = $db->insertId();
        $filename = 'export-orders-' . $export['id'] . '.xml';
    } else {
        $filename = 'export-orders-' . time() . '.xml';
    }
    // Create tmp directory
    $tmp_dir = __DIR__ . '/src/export-orders-tmp';
    if (!is_dir($tmp_dir)) {
        mkdir($tmp_dir);
    }
    $tmp_file = $tmp_dir . '/' . $filename;
    $export_file = $options['export-dir'] . '/' . $filename;
    // Start export
    $logger->info('Export time: ' . $export_time->format('Y-m-d H:i:s'));
    $logger->measureStart('total time');
    $writer = new XMLWriter($logger);
    $writer->open($tmp_file);
    $handle = $writer->getHandle();
    $handle->startElement('КоммерческаяИнформация');
    $handle->writeAttribute('ДатаФормирования', $export_time->format('Y-m-d H:i:s'));
    if (isset($export)) {
        $handle->writeAttribute('Ид', $export['id']);
    }
    $export_orders = new Orders($db, $logger);
    $total = 0;
    if (isset($ids)) {
        while ($exported = $export_orders->exportUsingIds($writer, $ids)) {
            $total += $exported;
        }
    } else {
        while ($exported = $export_orders->export($writer, $export_time, $previous_export_time)) {
            $total += $exported;
        }
    }
    $logger->info("Exported orders, total: $total");
    $handle->endElement();
    $logger->measureStart('write to file');
    $writer->close();
    unset($writer);
    $logger->measureEnd('write to file', 'info');
    // Update export history entry if it was created
    if (isset($export)) {
        $db->query(
            'UPDATE ?n SET `exported` = ?i, `date_modified` = NOW() WHERE `id` = ?i',
            $export_table,
            $total,
            $export['id']
        );
    }
    $logger->measureEnd('total time', 'info');
    // Move export file to destination only if there were orders exported
    if ($total > 0) {
        if (rename($tmp_file, $export_file)) {
            $logger->info('Moved tmp file to destination');
        } else {
            $logger->error('Failed to move tmp file to destination');
        }
    } else {
        unlink($tmp_file);
    }
} catch (\Throwable $e) {
    $logger->error('Error during order export: ' . $e->getMessage());
} finally {
    unlink($lockfile);
}
