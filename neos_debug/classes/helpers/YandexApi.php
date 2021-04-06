<?php

namespace Neos\classes\helpers;

class YandexApi
{

    public function geocode($q, &$cachehash, &$cache, $context = 'backend_geocoder')
    {
        $q = trim($q);
        if (empty($cachehash)) {
            $cachehash = md5($q);
        }
        if (false !== $cacheManager = \Neos\NeosFactory::getHelper('Cache')) {
            $cacheManager->cache_path = NPATH_BASE . '/../cache/geocoder/';
            $cacheManager->expire('geocoder_' . $cachehash, 2592000);
            $objJson = $cacheManager->get('geocoder_' . $cachehash);
            if ($objJson) {
                $cache = true;
                $objJson = json_decode($objJson);
            }
        }   
        if (!$objJson) {
            $raw = file_get_contents("https://geocode-maps.yandex.ru/1.x/?geocode={$q}&format=json&lang=ru_RU");
            $objJson = json_decode($raw);
            if (json_last_error()) {
                return false;
            }
            if (!isset($objJson->response->GeoObjectCollection)) {
                return false;
            }
            //make log
            if (defined('DB_USERNAME')) {
                $db = \Neos\NeosFactory::getDb();

                $logger = new \Neos\classes\log\Logger(array('db_instance' => $db));
                $data['type'] = 'geocoder';
                $data['query'] = $q;
                $data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                $data['ip'] = $_SERVER['REMOTE_ADDR'];
                $data['context'] = $context;
                $logger->write($data);
                $cacheManager->set($raw, 'geocoder_' . $cachehash);
            }
        }
        return $objJson;


    }
    
}