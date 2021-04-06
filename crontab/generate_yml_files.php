<?php
define('_NEXEC', 1);
define('NPATH_BASE', __DIR__ . '/../neos_debug');

require_once NPATH_BASE . '/_includes/constants.php';
require_once NPATH_BASE . '/../config.php';

$ch = curl_init(HTTP_SERVER . 'index.php?route=feed/yandex_market_by_neos');
curl_setopt($ch, CURLOPT_VERBOSE, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
echo curl_exec($ch) . "\n";
curl_close($ch);

$ch = curl_init(HTTP_SERVER . 'index.php?route=feed/yandex_market_by_neos&customer_group=4');
curl_setopt($ch, CURLOPT_VERBOSE, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
echo curl_exec($ch) . "\n";
curl_close($ch);

$ch = curl_init(HTTP_SERVER . 'index.php?route=feed/yandex_market_by_neos/export&target=freemoda');
curl_setopt($ch, CURLOPT_VERBOSE, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
echo curl_exec($ch) . "\n";
curl_close($ch);


$ch = curl_init(HTTP_SERVER . 'index.php?route=feed/yandex_market_by_neos/export&target=sneakerswearfeed');
curl_setopt($ch, CURLOPT_VERBOSE, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
echo curl_exec($ch) . "\n";
curl_close($ch);

$ch = curl_init(HTTP_SERVER . 'index.php?route=feed/yandex_market_au_ru');
curl_setopt($ch, CURLOPT_VERBOSE, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
echo curl_exec($ch) . "\n";
curl_close($ch);

$ch = curl_init(HTTP_SERVER . 'index.php?route=feed/yandex_market_by_neos/export&target=dropshippers');
curl_setopt($ch, CURLOPT_VERBOSE, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
echo curl_exec($ch) . "\n";
curl_close($ch);

$ch = curl_init(HTTP_SERVER . 'index.php?route=feed/yandex_market_by_neos/export&target=opt');
curl_setopt($ch, CURLOPT_VERBOSE, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
echo curl_exec($ch) . "\n";
curl_close($ch);


$ch = curl_init(HTTP_SERVER . 'index.php?route=feed/yandex_market_by_neos/export&target=tiu_ru');
curl_setopt($ch, CURLOPT_VERBOSE, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
echo curl_exec($ch) . "\n";
curl_close($ch);

$ch = curl_init(HTTP_SERVER . 'index.php?route=feed/yandex_market_by_neos/export&target=cdek_market'); 
curl_setopt($ch, CURLOPT_VERBOSE, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
echo curl_exec($ch) . "\n";
curl_close($ch);
$ch = curl_init(HTTP_SERVER . 'index.php?route=feed/yandex_market_by_neos/export&target=cdek_market_only_clothes'); 
curl_setopt($ch, CURLOPT_VERBOSE, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
echo curl_exec($ch) . "\n";
curl_close($ch);

$ch = curl_init(HTTP_SERVER . 'index.php?route=feed/yandex_market_by_neos/export&target=cdek_market_without_clothes'); 
curl_setopt($ch, CURLOPT_VERBOSE, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
echo curl_exec($ch) . "\n";
curl_close($ch);

$ch = curl_init(HTTP_SERVER . 'index.php?route=feed/yandex_market_by_neos/export&target=default_with_description'); 
curl_setopt($ch, CURLOPT_VERBOSE, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
echo curl_exec($ch) . "\n";
curl_close($ch);

$ch = curl_init(HTTP_SERVER . 'index.php?route=feed/export_products_to_csv'); 
curl_setopt($ch, CURLOPT_VERBOSE, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
echo curl_exec($ch) . "\n";
curl_close($ch);

$ch = curl_init(HTTP_SERVER . 'index.php?route=feed/export_products_to_csv_yopt_org'); 
curl_setopt($ch, CURLOPT_VERBOSE, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
echo curl_exec($ch) . "\n";
curl_close($ch);


$ch = curl_init(HTTP_SERVER . '/index.php?route=feed/xml_drop'); 
curl_setopt($ch, CURLOPT_VERBOSE, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
echo curl_exec($ch) . "\n";
curl_close($ch);
