<?php

namespace Neos\classes\helpers;

class AddressSuggestions
{
    /**
     * Get suggestions
     *
     * @param string $q
     * @param string|null $cachehash
     * @param boolean|null $cache
     * @param array $options
     * @return \stdClass
     */
    public function get($q, &$cachehash = null, &$cache = false, $options = array())
    {
        $cachehash = ($cachehash === null || strlen($cachehash) !== 32) ? md5($q) : $cachehash;
        $cachefile = 'suggestions_' . $cachehash;
        if (false !== $cacheManager = \Neos\NeosFactory::getHelper('Cache')) {
            $cacheManager->cache_path = NPATH_BASE . '/../cache/geocoder/';
            // 1 hour cache
            $cacheManager->expire($cachefile, 3600);
            $raw = $cacheManager->get($cachefile);
            if ($raw) {
                $cache = true;
                $data = json_decode($raw);
                if ($data === false) {
                    return false;
                }
            }
        }
        if (empty($data)) {
            $query = array_merge(
                array(
                    "query" => $q,
                    "count" => 10
                ),
                $options
            );
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Accept: application/json;charset=UTF-8';
            $headers[] = 'Authorization: Token ' . DADATA_TOKEN;
            $request = curl_init('https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address');
            curl_setopt($request, CURLOPT_POSTFIELDS, json_encode($query));
            curl_setopt($request, CURLOPT_TIMEOUT, 3);
            curl_setopt($request, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($request, CURLOPT_CUSTOMREQUEST, 'POST');
            $result = curl_exec($request);
            $info = curl_getinfo($request);
            if ((int) $info['http_code'] !== 200) {
                return false;
            }
            $decoded = json_decode($result);
            if (!isset($decoded->suggestions)) {
                return false;
            }
            $data = $decoded->suggestions;
            $cacheManager->set(json_encode($data), $cachefile);
        }
        return $data;
    }

    /**
     * Get bound to city or settlement
     *
     * @param string $q
     * @param integer $count
     * @return array|false
     */
    public function getBoundToCityOrSettlement($q, $count)
    {
        $cachehash = md5($q . '-city');
        $cache = false;
        $data = $this->get($q, $cachehash, $cache, array(
            'count' => $count,
            'to_bound' => array('value' => 'city')
        ));
        if ($data === false || is_array($data) && count($data) === 0) {
            $cachehash = md5($q . '-settlement');
            $data = $this->get($q, $cachehash, $cache, array(
                'count' => $count,
                'to_bound' => array('value' => 'settlement')
            ));
        }
        return $data;
    }
}
