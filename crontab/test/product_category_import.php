<?php

// File is created for manual testing
use Neos\classes\Import\Product as ProductImporter;
use Neos\classes\Import\Category as CategoryImporter;
use Neos\classes\log\ImportLogger;
use Neos\Import1C\Dictionaries\SerializableDictionary;
use Neos\Import1C\Entities\Product;
use Neos\Import1C\Parser\ClassifierParser;
use Neos\Import1C\Validation\Validator;

define('_NEXEC', 1);
define('NPATH_BASE', __DIR__ . '/../../neos_debug');

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
$options = getopt('', ['import-dir:']);
if (!isset($options['import-dir'])) {
    print('Please set --import-dir');
    exit;
}
if (!is_dir($options['import-dir'])) {
    print('Import directory must be a directory');
    exit;
}
define('IMPORT_LOG_STDOUT', 1);
define('IMPORT_LOG_LEVEL', 'debug');
$classifier_filename = $options['import-dir'] . '/import.xml';
$logger = new ImportLogger();
$logger->measureStart('total time');
$logger->measureStart('catalog validation');
// Validate catalog feed
$validator = new Validator(NPATH_DATA . '/classifier.xsd');
if (!$validator->validateFeed($classifier_filename)) {
    foreach ($validator->getErrors() as $error) {
        $logger->error($error);
    }
    return;
}
$logger->measureEnd('catalog validation', 'info');
$offers = new SerializableDictionary();
$image_checksums = new SerializableDictionary();
// Parse catalog feed
$parser = new ClassifierParser($logger, $classifier_filename);
$category_importer = new CategoryImporter($db, $logger, $parser->category_tree);
$db_category_tree = $category_importer->fetchDatabaseCategoryTree();
$product_importer = new ProductImporter(
    $db,
    $logger,
    $parser->category_tree,
    $db_category_tree,
    $image_checksums,
    $offers,
    [
        'import_path' => pathinfo($classifier_filename)['dirname'],
    ]
);
foreach ($parser->parse() as $entity) {
    try {
        $class = get_class($entity);
        switch ($class) {
            case Product::class:
                $logger->measureStart('product time');
                $logger->info(
                    $entity->name . ', id: ' . $entity->vendor_code
                );
                $parents = $parser->category_tree->getParentCategories($entity->categories[0]->import_id);
                // Get categories twice - cache should be used
                for ($i = 1; $i <= 2; $i++) {
                    $category = $product_importer->getProductCategoryByProperty(
                        $entity,
                        array_merge([$entity->categories[0]], $parents)
                    );
                }
                $logger->measureEnd('product time', 'info');
                if (is_null($category)) {
                    $logger->info(
                        'Category not found'
                    );
                } else {
                    $logger->info(
                        'Category found, name: ' . $category->name . ', id: ' . $category->id
                    );
                }
                break;
        }
    } catch (\Exception $e) {
        $logger->alert(
            "Failure, entity: $class, reason: {$e->getMessage()}",
            ['stackTrace' => $e->getTraceAsString()]
        );
    }
}
$logger->measureEnd('total time', 'info');
