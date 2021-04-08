<?php

use Neos\classes\Import\Option as OptionImporter;
use Neos\classes\Import\OptionValue as OptionValueImporter;
use Neos\classes\Import\PriceType as PriceTypeImporter;
use Neos\classes\Import\Product as ProductImporter;
use Neos\classes\Import\Attribute as AttributeImporter;
use Neos\classes\Import\Manufacturer as ManufacturerImporter;
use Neos\classes\Import\Category as CategoryImporter;
use Neos\classes\Import\Exceptions\ImportIgnoreException;
use Neos\classes\Import\ProductDiscount as ProductDiscountImporter;
use Neos\classes\Import\ProductOption as ProductOptionImporter;
use Neos\classes\Import\ProductOptionValue as ProductOptionValueImporter;
use Neos\classes\log\ImportLogger;
use Neos\Import1C\Dictionaries\SerializableDictionary;
use Neos\Import1C\Entities\Category;
use Neos\Import1C\Entities\Characteristic;
use Neos\Import1C\Entities\CharacteristicValue;
use Neos\Import1C\Entities\Manufacturer;
use Neos\Import1C\Entities\OfferGroup;
use Neos\Import1C\Entities\PriceType;
use Neos\Import1C\Entities\Product;
use Neos\Import1C\Entities\Property;
use Neos\Import1C\Parser\ClassifierParser;
use Neos\Import1C\Parser\OffersParser;
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
        'import-dir:',
        'save-checksums-count:',
        'clean-image-directory::',
        'recreate-product-categories-added-after:'
    ]
);
if (!isset($options['import-dir'])) {
    print('Please set --import-dir');
    exit;
}
if (!is_dir($options['import-dir'])) {
    print('Import directory must be a directory');
    exit;
}
if (isset($options['v'])) {
    define('IMPORT_LOG_STDOUT', 1);
}
if (isset($options['loglevel'])) {
    define('IMPORT_LOG_LEVEL', $options['loglevel']);
}
if (isset($options['save-checksums-count']) && $options['save-checksums-count'] >= 100) {
    $save_checksums_count = (int) $options['save-checksums-count'];
}
if (!isset($save_checksums_count)) {
    $save_checksums_count = 1000;
}
$classifier_filename = $options['import-dir'] . '/import.xml';
$offers_filename = $options['import-dir'] . '/offers.xml';
$lockfile = __DIR__ . '/src/.import-products-lock';
if (file_exists($lockfile)) {
    $logger->alert('Import is already in progress');
    return;
}
try {
    file_put_contents($lockfile, '');
    $logger = new ImportLogger();
    $logger->measureStart('total time');
    $logger->measureStart('catalog validation');
    // Validate catalog feed
    $validator = new Validator(NPATH_DATA . '/classifier.xsd');
    if (!$validator->validateFeed($classifier_filename)) {
        $logger->error('Classifier validation failed');
        foreach ($validator->getErrors() as $error) {
            $logger->error($error);
        }
        return;
    }
    $logger->measureEnd('catalog validation', 'info');
    // Validate offers feed
    $logger->measureStart('offers validation');
    // Validate offers feed
    $validator = new Validator(NPATH_DATA . '/offers.xsd');
    if (!$validator->validateFeed($offers_filename)) {
        $logger->error('Offers validation failed');
        foreach ($validator->getErrors() as $error) {
            $logger->error($error);
        }
        return;
    }
    $logger->measureEnd('offers validation', 'info');
    $logger->measureStart('import time');
    $offers = new SerializableDictionary();
    // Parse offers feed
    $logger->measureStart('offers import time');
    $parser = new OffersParser($logger, $offers_filename);
    $option_importer = new OptionImporter($db, $logger);
    $option_value_importer = new OptionValueImporter($db, $logger);
    $price_type_importer = new PriceTypeImporter($db, $logger);
    $product_discount_importer = new ProductDiscountImporter($db, $logger);
    $product_option_importer = new ProductOptionImporter($db, $logger);
    $product_option_value_importer = new ProductOptionValueImporter($db, $logger);
    $offer_counter = 0;
    foreach ($parser->parse() as $entity) {
        $class = get_class($entity);
        $entity_context = [];
        $handled = true;
        try {
            switch ($class) {
                case Characteristic::class:
                    $option_importer->import($entity);
                    $entity_context = $option_importer->getContext($entity);
                    break;
                case CharacteristicValue::class:
                    $option_value_importer->import($entity);
                    $entity_context = $option_value_importer->getContext($entity);
                    break;
                case PriceType::class:
                    $price_type_importer->import($entity);
                    $entity_context = $price_type_importer->getContext($entity);
                    break;
                case OfferGroup::class:
                    $offer_counter++;
                    $product_discount_importer->atomicImport(function () use (
                        $entity,
                        $product_discount_importer,
                        $product_option_importer,
                        $product_option_value_importer
                    ) {
                        $product_discount_importer->import($entity);
                        $imported = $product_option_importer->import($entity);
                        $product_option_value_importer->setProductOptions($entity->product_id, $imported->options);
                        $product_option_value_importer->import($entity);
                    });
                    $entity_context = $product_discount_importer->getContext($entity);
                    $offer = $entity->getDefaultOffer();
                    $default_price = 0;
                    $discount = 0;
                    foreach ($offer->prices as $price) {
                        if ($price->price_type->id === ProductImporter::DEFAULT_CUSTOMER_GROUP) {
                            $default_price = $price->value;
                            $discount = $price->discount_percent;
                        }
                    }
                    $offers->set(
                        $entity->product_id,
                        (object) [
                            'price' => $default_price,
                            'discount' => $discount,
                            'quantity' => $entity->getTotalQuantity()
                        ]
                    );
                    break;
                default:
                    $handled = false;
                    break;
            }
            if ($handled) {
                $logger->info("Import done, entity: $class", $entity_context);
            } else {
                $logger->debug("Ignoring, entity: $class", $entity_context);
            }
        } catch (\Throwable $e) {
            $logger->alert(
                "Import failed, entity: $class, reason: {$e->getMessage()}",
                array_merge($entity_context, ['stackTrace' => $e->getTraceAsString()])
            );
        }
    }
    $logger->measureEnd('offers import time', 'info');
    $logger->measureStart('image checksums');
    // Prepare dictionaries
    $image_checksums_filename = __DIR__ . '/src/.product-images';
    if (file_exists($image_checksums_filename)) {
        $image_checksums = unserialize(file_get_contents($image_checksums_filename));
    } else {
        $image_checksums = new SerializableDictionary();
    }
    $store_checksums = function () use ($image_checksums_filename, $image_checksums) {
        file_put_contents($image_checksums_filename, serialize($image_checksums));
    };
    $logger->measureEnd('image checksums', 'info');
    // Parse catalog feed
    $logger->measureStart('classifier import time');
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
            'clean_image_directory' => isset($options['clean-image-directory']),
            'recreate_product_categories_added_after' => $options['recreate-product-categories-added-after'] ?? null,
            'sitename' => parse_url(HTTP_SERVER)['host']
        ]
    );
    $attribute_importer = new AttributeImporter($db, $logger);
    $manufacturer_importer = new ManufacturerImporter($db, $logger);
    $product_counter = 0;
    foreach ($parser->parse() as $entity) {
        $class = get_class($entity);
        $entity_context = [];
        $handled = true;
        try {
            switch ($class) {
                case Manufacturer::class:
                    $manufacturer_importer->import($entity);
                    $entity_context = $manufacturer_importer->getContext($entity);
                    break;
                case Property::class:
                    $attribute_importer->import($entity);
                    $entity_context = $attribute_importer->getContext($entity);
                    break;
                case Product::class:
                    $product_counter++;
                    $product_importer->import($entity);
                    $entity_context = $product_importer->getContext($entity);
                    break;
                case Category::class:
                    $category_importer->import($entity);
                    $entity_context = $category_importer->getContext($entity);
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
        } catch (\Throwable $e) {
            $logger->alert(
                "Import failed, entity: $class, reason: {$e->getMessage()}",
                array_merge($entity_context, ['stackTrace' => $e->getTraceAsString()])
            );
        }
        // Store image checksums once every X products
        if ($product_counter > 0 && $product_counter % $save_checksums_count === 0) {
            call_user_func($store_checksums);
            $logger->info('Images checksums stored');
        }
    }
    $logger->measureEnd('classifier import time', 'info');
    $logger->measureEnd('import time', 'info');
    call_user_func($store_checksums);
    $db->query(
        'INSERT INTO ?n (`created`, `updated`, `finished`) VALUES(?i, ?i, UTC_TIMESTAMP())',
        'oc_import_data',
        $product_importer->getCreated(),
        $product_importer->getUpdated()
    );
    $logger->measureEnd('total time', 'info');
} catch (\Throwable $e) {
    $logger->error('Error during order export: ' . $e->getMessage());
} finally {
    unlink($lockfile);
}
