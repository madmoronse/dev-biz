<?php
class ModelLocalisationDostavka extends Model
{

    public function getZones($zone = null)
    {
        $json = array();
        if ($zone !== null) {
            $query = $this->db->query("SELECT * FROM `delivery_regions` WHERE `name` LIKE '%{$zone}%' ORDER BY `name` ASC");
            $zone_data = $query->rows;
            foreach ($zone_data as $key => $zone) {
                array_push($json, $zone['name']);
            }
        }
        return $json;
    }

    public function getAreas($area = null, $zone = '')
    {
        $json = array();
        if ($area !== null) {
            $zonequery = '';
            if (($zone != '')&&($zoneid = $this->getZoneId($zone))) {
                $zonequery = " AND `region_id` = '{$zoneid}' ";
            }
            $query = $this->db->query("SELECT * FROM `delivery_area` WHERE `name` LIKE '%{$area}%' {$zonequery} ORDER BY `name` ASC");
            $area_data = $query->rows;
            foreach ($area_data as $key => $areat) {
                array_push($json, $areat['name']);
            }
        }
        return $json;
    }

    
    public function getShippingCity($name)
    {

        $name = $this->db->escape($name);
        $name = preg_replace('/%_|/', '', $name);
        /*if (false !== $searchHelper = \Neos\NeosFactory::getHelper('SearchHelper')) {
            $search = $searchHelper->prepareAddress($name);
        } elseif (!empty($name)) {
            $search = "`offname` LIKE '%{$name}%'";
        }*/
        $json = array();

        //if (!empty($search)) {
            $query = $this->db->query("SELECT DISTINCT offname  FROM `fias` WHERE `offname` LIKE '%{$name}%' AND `Level` in (4,6) ORDER BY shortname, CHAR_LENGTH(offname) ASC LIMIT 20");
            $area_data = $query->rows;
            foreach ($area_data as $key => $areat) {
                $json[] = $areat['offname'];
            }
        //}
        return $json;
    }

    
    public function getNp($name)
    {
        $name = $this->db->escape($name);
        $name = preg_replace('/%_|/', '', $name);
        if (false !== $searchHelper = \Neos\NeosFactory::getHelper('SearchHelper')) {
            $search = $searchHelper->prepareAddress($name);
        } elseif (!empty($name)) {
            $search = "`name` LIKE '%{$name}%'";
        }
        $json = array();
        if (!empty($search)) {
            $query = $this->db->query("SELECT * FROM `delivery_cities` WHERE $search ORDER BY `name` ASC");
            $area_data = $query->rows;
            foreach ($area_data as $key => $areat) {
                $json[] = $areat['np_name'];
            }
        }
        return $json;
    }

    public function getNp1($name)
    {
        $cachehash = null;
        $cache = false;
        if (false === $suggestionsApi = \Neos\NeosFactory::getHelper('AddressSuggestions')) {
            return array('error' => NEOS_ERROR_YANDEX_API);
        }
        if (false === $data = $suggestionsApi->get($name, $cachehash, $cache)) {
            return array('error' => NEOS_ERROR_YANDEX_API);
        }
        $items = array();
        foreach ($data as $address) {
            if ($address->data->postal_code === null) {
                continue;
            }
            $items[] = $address->value;
        }
        if (!count($items)) {
            return array('error' => array('city_not_found' => NEOS_WARNING_YANDEX_API_CITY_NOT_FOUND));
        }
        return  array('count' => count($items), 'items' => $items, 'cache' => $cache, 'cachehash' => $cachehash);
    }

    public function getNp2($name)
    {
        $cachehash = preg_replace("/[^a-f0-9]/", "", $_REQUEST['cachehash']);
        $cache = false;
        $is_new = false;
        if (false === $suggestionsApi = \Neos\NeosFactory::getHelper('AddressSuggestions')) {
            return array('error' => NEOS_ERROR_YANDEX_API);
        }
        if (false === $data = $suggestionsApi->get($name, $cachehash, $cache)) {
            return array('error' => NEOS_ERROR_YANDEX_API);
        }
        $items = array();
        $search_name = mb_strtolower(trim($name), 'UTF-8');
        foreach ($data as $address) {
            if (mb_strtolower(trim($address->value), 'UTF-8') === $search_name) {
                $new_item = $address;
                break;
            }
        }
        $error_response = array('error' => array('city_not_found' => NEOS_WARNING_YANDEX_API_CITY_NOT_FOUND));
        // Return error if city not found
        if (!isset($new_item)) {
            return $error_response;
        }
        $full_name = $new_item->value;
        $this->session->data['normalized_address_confirm'] = (object) array(
            'value' => $new_item->value,
            'unrestricted_value' => $new_item->unrestricted_value,
            'postcode' => $new_item->data->postal_code
        );
        // Parsed data from suggestions
        $parsed_data = array();
        // Response data from suggestions
        $destruct_full_name = explode(",", $full_name);
        $parsed_data['area_name'] = $parsed_data['region_name'] = $parsed_data['city_name'] = '';
        $parsed_data['area_full'] = $parsed_data['region_full'] = $parsed_data['city_full'] = '';
        if (!empty($new_item->data->region)) {
            $parsed_data['region_name'] = $new_item->data->region;
            $parsed_data['region_full'] = $new_item->data->region_with_type;
        }
        if (!empty($new_item->data->area)) {
            $parsed_data['area_name'] = $new_item->data->area;
            $parsed_data['area_full'] = $new_item->data->area_with_type;
        }
        if (!empty($new_item->data->city)) {
            $parsed_data['city_name'] = $new_item->data->city;
            $parsed_data['city_full'] = $new_item->data->city_with_type;
        }
        if (!empty($new_item->data->settlement)) {
            $parsed_data['city_name'] = $new_item->data->settlement;
            $parsed_data['city_full'] = $new_item->data->settlement_with_type;
        }
        if ($parsed_data['city_full'] !== '') {
            $arNp[] = $this->db->escape($parsed_data['city_full']);
        }
        if ($parsed_data['area_full'] !== '') {
            $arNp[] = $this->db->escape($parsed_data['area_full']);
        }
        if ($parsed_data['region_full'] !== '') {
            $arNp[] = $this->db->escape($parsed_data['region_full']);
        }
        $np_name = implode(", ", $arNp);
        
        // Adjusments for local db
        if ($parsed_data['city_name'] === 'Москва' || $parsed_data['region_name'] === 'Москва') {
            $parsed_data['region_name'] = 'Московская Область';
        }
        if ($parsed_data['city_name'] === 'Севастополь' || $parsed_data['region_name'] === 'Севастополь') {
            $parsed_data['region_name'] = 'Крым Республика';
        }
        // Get region from db
        $region_name = $this->db->escape(trim($parsed_data['region_name']));
        $query = $this->db->query(
            "SELECT * FROM `delivery_regions` WHERE `name` LIKE '%{$region_name}%' ORDER BY `name` ASC"
        );
        $region_data = $query->rows;
        if (count($region_data)) {
            $region_id = (int) $region_data[0]['id'];
        } else {
            return $error_response;
        }
        $area_id = 0;
        $area_sql = "";
        // Get area from DB
        if ($parsed_data['area_name'] !== '') {
            $area_name = $this->db->escape(trim($parsed_data['area_name']));
            $query = $this->db->query(
                "SELECT * FROM `delivery_area`
                WHERE `name` LIKE '%{$area_name}%' AND `region_id` = $region_id ORDER BY `name` ASC"
            );
            $area_data = $query->rows;
            if (count($area_data)) {
                $area_id = (int) $area_data[0]['id'];
                $area_sql = "AND `area_id` = $area_id";
            }
        }
        // Get city from DB
        $city_name = $this->db->escape($parsed_data['city_name']);
        $query = $this->db->query(
            "SELECT * FROM `delivery_cities`
            WHERE `region_id` = {$region_id} {$area_sql} AND `name` LIKE '%{$city_name}%' ORDER BY `name` ASC"
        );
        $city_data = $query->rows;
        // If city not found get first city
        if (count($city_data) == 0) {
            $query2 = $this->db->query(
                "SELECT * FROM `delivery_cities`
                WHERE `region_id` = {$region_id} {$area_sql} ORDER BY `name` ASC LIMIT 1"
            );
            $city_data_first = $query2->rows;
            // Insert new record
            if (count($city_data_first) > 0) {
                $arDataDB = $city_data_first[0];
                $city_full = $this->db->escape($parsed_data['city_full']);
                $this->db->query(
                    "INSERT INTO `delivery_cities`
                    (region_id, area_id, name, np_name, availability, post_delivery, sdek_sklad) 
                    VALUES (
                        {$arDataDB["region_id"]},
                        {$arDataDB["area_id"]},
                        '$city_full',
                        '{$np_name}',
                        '{$arDataDB["availability"]}',
                        '{$arDataDB["post_delivery"]}',
                        '0'
                    )"
                );
                $is_new = true;
                $query = $this->db->query(
                    "SELECT * FROM `delivery_cities`
                    WHERE `region_id` = {$region_id} {$area_sql}
                    AND `name` = '$city_full' ORDER BY `name` ASC"
                );
                $city_data = $query->rows;
            }
        }
      
        if (count($city_data) == 0) {
            return $error_response;
        } else {
            $max = 0;
            $mark = 0;
            foreach ($city_data as $city) {
                similar_text($city['np_name'], $np_name, $mark);
                if ($mark > $max) {
                    $max = $mark;
                    $result_name = $city['np_name'];
                    $is_db = $city['id'];
                }
            }
        }
        return array(
            'count' => 1,
            'items' => $result_name,
            'items_cnt' => count($city_data),
            'is_db' => $is_db,
            'cache' => $cache,
            'is_new' => $is_new
        );
    }

    public function getNpCalc($name)
    {
        $tableHtml = '';
        if ($name != '') {
            $query = $this->db->query("SELECT * FROM `delivery_cities` WHERE `np_name` LIKE '{$name}' ORDER BY `np_name` ASC");
            $city_data = $query->rows;

            $query = $this->db->query("SELECT * FROM `delivery_regions` WHERE `id` = {$city_data[0]["region_id"]} ORDER BY `id` ASC");
            $region_data = $query->rows;

            $this->load->model('checkout/shipping');
            $is_calculator = true;
            $addresses = $this->model_checkout_shipping->normalizeAddress($name, $is_calculator);
            if (count($addresses) === 0) {
                return '<p>Не удалось определить почтовый индекс адреса для расчёта стоимости доставки</p>';
            }
            $sub_total = (int) $this->cart->getSubTotal();
            $shipping_methods = $this->model_checkout_shipping->getCustomDelivery(
				$sub_total !== 0 ? $sub_total : 5500,
				$addresses[0]->postcode,
				array(
					'city' => $city_data[0]["name"],
					'zone' => $region_data[0]["name"]
                ),
                $is_calculator
            );
            $tableHtml = <<<HTML
        <table class="calc-table">
          <tr>
            <th>Предоплата</th>
            <th>Доставка</th>
            <th>Стоимость доставки</th>
            <th>Место получения</th>
          </tr>
HTML;
            foreach ($shipping_methods as $key => $shipping_method) {
                switch ($shipping_method["delivery"]) {
                    case 'Почта России':
                        $img = '<img src="/catalog/view/theme/default2/image/rm.png" alt="">';
                        break;
                    case 'СДЭК':
                        $img = '<img src="/catalog/view/theme/default2/image/cdek.png" alt="">';
                        break;
                    default:
                        $img = '';
                        break;
                }

                $tableHtml .= <<<HTML
          <tr>
            <td>{$shipping_method["payment"]}</td>
            <td>{$img}</td>
            <td>{$shipping_method["dcost"]}</td>
            <td>{$shipping_method["place"]}</td>
          </tr>
HTML;
            }
            $tableHtml .= <<<HTML
        </table>
HTML;
        }
        return $tableHtml;
    }

    public function getCities($city = null, $area = '', $zone = '')
    {
        $json = array();
        $zonequery = '';
        if (($zone != '')&&($zoneid = $this->getZoneId($zone))) {
            $zonequery = " AND `region_id` = '{$zoneid}' ";
        }
        $areaquery = '';
        if (($area != '')&&($areaid = $this->getAreaId($area))) {
            $areaquery = " AND `area_id` = '{$areaid}' ";
        }
        if ($city !== null) {
            $query = $this->db->query("SELECT * FROM `delivery_cities` WHERE `name` LIKE '%{$city}%' {$zonequery} {$areaquery} ORDER BY `name` ASC");
            $city_data = $query->rows;
            foreach ($city_data as $key => $city) {
                array_push($json, $city['name']);
            }
        }
        return $json;
    }

    private function getZoneId($zone)
    {
        $qz = $this->db->query("SELECT * FROM `delivery_regions` WHERE `name` = '{$zone}' LIMIT 1");
        $z = $qz->rows;
        $zq = '';
        if (count($z) > 0) {
            $za = $z[0];
            $zid = $za['id'];
        }
        return ($zid) ? $zid : false;
    }

    private function getAreaId($area)
    {
        $q = $this->db->query("SELECT * FROM `delivery_area` WHERE `name` = '{$area}' LIMIT 1");
        $a = $q->rows;
        $aq = '';
        if (count($a) > 0) {
            $arr = $a[0];
            $aid = $arr['id'];
        }
        return ($aid) ? $aid : false;
    }
}
