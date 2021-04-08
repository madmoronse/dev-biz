<?php
use Neos\util\cache;
IF (PHP_SAPI !== 'cli') {
    die('Restricted Access');
}
define('_NEXEC', 1);
define('NPATH_CACHE', __DIR__ . '/../filter_cache');
require_once __DIR__ . '/classes/CacheGarbageCollector.php';
echo "Filter Cache\n";
\Neos\util\cache\CacheGarbageCollector::setDefaultRootpath(__DIR__ . '/../filter_cache');

$date = new DateTime();
echo "Date: " . $date->format("Y-m-d H:i:s") . "\n";
$total_removed = 0;

$gb = new \Neos\util\cache\CacheGarbageCollector('', 3600);
$gb->iterate();
echo "Removed: " . $gb->getRemovedFileCount() ."\n\n";
$total_removed += $gb->getRemovedFileCount();

echo "Yandex Cache\n";
\Neos\util\cache\CacheGarbageCollector::setDefaultRootpath(__DIR__ . '/../cache/geocoder');

$date = new DateTime();
echo "Date: " . $date->format("Y-m-d H:i:s") . "\n";

$gb = new \Neos\util\cache\CacheGarbageCollector('', 2592000);
$gb->iterate();
echo "Removed: " . $gb->getRemovedFileCount() ."\n\n";
$total_removed += $gb->getRemovedFileCount();

echo "TMP Photos\n";
\Neos\util\cache\CacheGarbageCollector::setDefaultRootpath(__DIR__ . '/../uploads/tmp_dir');

$date = new DateTime();
echo "Date: " . $date->format("Y-m-d H:i:s") . "\n";

$gb = new \Neos\util\cache\CacheGarbageCollector('', 3600, 'all');
$gb->iterate();
echo "Removed: " . $gb->getRemovedFileCount() ."\n";
$total_removed += $gb->getRemovedFileCount();

echo "YML Exports\n";
\Neos\util\cache\CacheGarbageCollector::setDefaultRootpath(__DIR__ . '/../price/export');
$date = new DateTime();
echo "Date: " . $date->format("Y-m-d H:i:s") . "\n";

$gb = new \Neos\util\cache\CacheGarbageCollector('', 259200, 'yml');
$gb->iterate();
echo "Removed: " . $gb->getRemovedFileCount() ."\n";
$total_removed += $gb->getRemovedFileCount();

echo "Categories Cache\n";
\Neos\util\cache\CacheGarbageCollector::setDefaultRootpath(__DIR__ . '/../cache/categories');

$date = new DateTime();
echo "Date: " . $date->format("Y-m-d H:i:s") . "\n";

$gb = new \Neos\util\cache\CacheGarbageCollector('', 3600, 'all');
$gb->iterate();
echo "Removed: " . $gb->getRemovedFileCount() ."\n\n";
$total_removed += $gb->getRemovedFileCount();

echo "Total: " . $total_removed . "\n\n";
