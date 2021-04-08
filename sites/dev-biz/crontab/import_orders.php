<?php

use Neos\classes\Import\Exceptions\ImportIgnoreException;
use Neos\classes\Import\Helpers\OrderStatus as OrderStatusHelper;
use Neos\classes\Import\Order as OrderImporter;
use Neos\classes\log\ImportLogger;
use Neos\Import1C\Entities\Document;
use Neos\Import1C\Parser\DocumentsParser;
use Neos\Import1C\Validation\Validator;

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
    'v',
    [
        'loglevel:',
        'import-file:',
    ]
);
if (!isset($options['import-file'])) {
    print('Please set --import-file');
    exit(1);
}
if (!preg_match('/.xml$/', basename($options['import-file']))) {
    print('Bad import file extension');
    exit(1);
}
if (!file_exists($options['import-file'])) {
    print('Import file does not exist');
    exit(1);
}
if (isset($options['v'])) {
    define('IMPORT_LOG_STDOUT', 1);
}
if (isset($options['loglevel'])) {
    define('IMPORT_LOG_LEVEL', $options['loglevel']);
}

$logger = new ImportLogger('/import_orders.log');
$logger->measureStart('total time');
$logger->measureStart('documents validation');
// Validate catalog feed
$validator = new Validator(NPATH_DATA . '/documents_simplified.xsd');
if (!$validator->validateFeed($options['import-file'])) {
    foreach ($validator->getErrors() as $error) {
        $logger->error($error);
    }
    exit(1);
}
$logger->measureEnd('documents validation', 'info');
$logger->measureStart('import time');
$parser = new DocumentsParser($logger, $options['import-file']);
OrderStatusHelper::fillOrderStatusDictionary($parser->order_statuses, $db);
$order_importer = new OrderImporter(
    $db,
    $logger
);
$order_counter = 0;
foreach ($parser->parse() as $entity) {
    $class = get_class($entity);
    $entity_context = [];
    $handled = true;
    try {
        switch ($class) {
            case Document::class:
                $order_importer->import($entity);
                $entity_context = $order_importer->getContext($entity);
                $order_counter++;
                break;
            default:
                $handled = false;
                break;
        }
        if ($handled) {
            $logger->info("Import done, entity: $class", $entity_context);
        } else {
            $logger->debug("No handler, entity: $class", $entity_context);
        }
    } catch (ImportIgnoreException $e) {
        $logger->notice(
            "Ignoring, entity: $class, reason: {$e->getMessage()}",
            $entity_context
        );
    } catch (\Exception $e) {
        $logger->alert(
            "Import failed, entity: $class, reason: {$e->getMessage()}",
            array_merge($entity_context, ['stackTrace' => $e->getTraceAsString()])
        );
    }
}
$logger->info('Imported orders: ' . $order_counter);
$logger->measureEnd('import time', 'info');
$logger->measureEnd('total time', 'info');
