<?php
    require_once 'geoip/driver.php';
    require_once 'geoip/driver/sypexgeo.php';

    /**
     * Определение региона по IP-адресу пользователя.
     * @author Roman Shipilov r.shipilov@gmail.com
     */

    class GeoIP {

        /**
         * @var Registry;
         */
        private $registry;

        /**
         * @var Config
         */
        private $config;

        private $country_id;

        private $zone_id;

        private $country_name;

        private $zone_name;

        private $postcode;

        // Название населенного пункта + район, если он есть
        private $city_name;

        // Название населенного пункта
        private $short_city_name;

        private $fias_country_id;

        private $fias_zone_id;

        private $fias_id;

        private $fias_id_cookie_key = 'geoip_fias_id';

        private $session_key = 'geoip';

        private $settings;

        private $rules;

        /**
         * @var ModelPrFias
         */
        private $model_pr_fias;

        //<editor-fold desc="Public methods">

        /**
         * @param Registry $registry
         */
        public function __construct($registry) {

            $this->registry = $registry;
            $this->config = $registry->get('config');

            $registry->get('load')->model('pr/fias');
            $this->model_pr_fias = $this->registry->get('model_pr_fias');

            $this->settings = $this->config->get('geoip_setting');

            $this->setFields($this->getFields());
            $this->setCurrency();
            $this->setRules();
            $this->geoRedirect();
        }

        public function search($term) {

            return $this->model_pr_fias->findFiasByName($term);
        }

        public function save($fias_id) {

            $data = $this->getAllFields(array('fias_id' => $fias_id));

            if ($data) {

                $this->setFields($data);

                return true;
            }

            return false;
        }

        public function getZoneName() {

            return $this->zone_name;
        }

        public function getZoneId() {

            return $this->zone_id;
        }

        public function getCountryId() {

            return $this->country_id;
        }

        public function getCountryName() {

            return $this->country_name;
        }

        public function getPostcode() {

            return $this->postcode;
        }

        public function getCityName() {

            return $this->city_name;
        }

        public function getShortCityName() {

            return $this->short_city_name;
        }

        public function setCurrency($force = false) {

            $request = $this->registry->get('request');

            if ($force || !isset($request->cookie['geoip_currency'])) {

                $currency = $this->registry->get('currency');
                $currency_code = $currency->getCode();
                $currency_ru = $this->setting('currency_for_ru');
                $currency_ua = $this->setting('currency_for_ua');
                $geoip_currency = '';

                if ($this->country_id == $this->model_pr_fias->getCountryByIsoCode('RUS')) {

                    if ($currency_ru && $currency_code != $currency_ru) {
                        $currency->set($currency_ru);
                        $geoip_currency = $currency_ru;
                    }
                }
                elseif ($this->country_id == $this->model_pr_fias->getCountryByIsoCode('UKR')) {

                    if ($currency_ua && $currency_code != $currency_ua) {
                        $currency->set($currency_ua);
                        $geoip_currency = $currency_ua;
                    }
                }

                setcookie('geoip_currency', $geoip_currency, time() + 60 * 60 * 24 * 30, '/', $request->server['HTTP_HOST']);
            }
        }

        public function getRule($key, $default = '') {

            return isset($this->rules[$key]) ? $this->rules[$key] : $default;
        }

        //</editor-fold>

        //<editor-fold desc="Private methods">

        /**
         * Инициализация полей.
         * @param $data Значения для инициализации.
         */
        private function setFields($data = array()) {

            if (!$data) {
                return;
            }

            $session = $this->registry->get('session');

            $fields = array('country_id', 'country_name', 'zone_id', 'zone_name', 'postcode', 'city_name',
                            'short_city_name', 'fias_country_id', 'fias_zone_id',  'fias_id');

            foreach ($fields as $field) {

                $value = isset($data[$field]) && $data[$field] ? $data[$field] : null;

                $this->$field = $session->data[$this->session_key][$field] = trim($value);
            }

            if ($this->fias_id) {
                setcookie($this->fias_id_cookie_key, $this->fias_id, time() + 3600 * 24 * 30);
            }
        }

        private function getFields() {

            $sources = array('Session', 'Cookie', 'Drivers', 'Config');

            foreach ($sources as $source) {

                $data = call_user_func(array($this, 'getFrom' . $source));

                if ($data) {

                    return $data;
                }
            }

            return false;
        }

        private function getFromSession() {

            $session = $this->registry->get('session');

            if (isset($session->data[$this->session_key]) && !empty($session->data[$this->session_key])) {

                return $session->data[$this->session_key];
            }

            return false;
        }

        private function getFromCookie() {

            $cookie = $this->registry->get('request')->cookie;

            if (!empty($cookie[$this->fias_id_cookie_key])) {

                $data = $this->getAllFields(array('fias_id' => $cookie[$this->fias_id_cookie_key]));

                if ($data) {

                    return $data;
                }
            }

            return false;
        }

        private function getFromDrivers() {

            foreach ($this->getDrivers() as $driver) {

                $data = $driver->getGeoFilter();

                if ($data) {

                    return $this->getAllFields($data);
                }
            }

            return false;
        }

        private function getFromConfig() {

            if (!$this->setting('set_zone')) {
                return false;
            }

            $zone_id = $this->config->get('config_zone_id');

            if (!$zone_id) {
                return false;
            }

            $country_id = $this->config->get('config_country_id');

            return $this->getAllFields(array('zone_id' => $zone_id, 'country_id' => $country_id));
        }

        /**
         * @return GeoIP_Driver[]
         */
        private function getDrivers() {

            $drivers = array();

            $drivers[] = new GeoIP_Driver_SypexGeo($this->registry);

            return $drivers;
        }

        /**
         * По имеющимся полям вычисляет недостающие
         * @param $fields
         * @return array|bool
         */
        private function getAllFields($fields) {

            $fias = false;

            if (!empty($fields['fias_id'])) {
                $fias = $this->model_pr_fias->getFiasById($fields['fias_id']);
            }
            elseif (!empty($fields['country_id'])) {

                $fields['fias_country_id'] = $this->model_pr_fias->getFiasCountryIdByCountryId($fields['country_id']);

                if (!empty($fields['zone_id'])) {
                    $fields['fias_zone_id'] = $this->model_pr_fias->getFiasZoneIdByZoneId($fields['zone_id']);
                    $countryAndZone = $this->model_pr_fias->findCountryAndZone(array('zone_id' => $fields['zone_id']));
                }
                else {
                    $countryAndZone = $this->model_pr_fias->getCountryById($fields['country_id']);
                }

                $fields = array_merge($fields, $countryAndZone);
            }
            else {
                $fias = $this->model_pr_fias->getFias($fields);
            }

            if ($fias) {

                if ($fias['f1_level'] == 0) {

                    $fiasData = array('fias_country_id' => $fias['f1_fias_id']);
                    $countryAndZone = $this->model_pr_fias->findCountryAndZone(array('country_id' => $this->model_pr_fias->getCountryIdByFiasId($fiasData['fias_country_id'])));
                }
                else {
                    $fiasData = array('fias_id' => $fias['f1_fias_id']);
                    $fiasData['postcode'] = $fias['f1_postalcode'] ? $fias['f1_postalcode'] : '';

                    // Город-регион (Москва или СПб)
                    if ($fias['f1_level'] == 1) {

                        $fiasData['fias_zone_id'] = $fias['f1_fias_id'];
                        $fiasData['fias_country_id'] = $fias['f2_fias_id'];
                        $fiasData['city_name'] = $fias['f1_name'];
                        $fiasData['short_city_name'] = '';
                    }
                    // Города
                    elseif ($fias['f2_level'] == 1) {

                        $fiasData['fias_zone_id'] = $fias['f2_fias_id'];
                        $fiasData['fias_country_id'] = $fias['f3_fias_id'];
                        $fiasData['city_name'] = $fiasData['short_city_name'] = $fias['f1_name'];
                    }
                    // Населенные пункты в районах
                    elseif ($fias['f3_level'] == 1) {

                        $fiasData['fias_zone_id'] = $fias['f3_fias_id'];
                        $fiasData['fias_country_id'] = $fias['f4_fias_id'];
                        $fiasData['city_name'] = $fias['f2_name'] . ', ' . $fias['f1_name'];
                        $fiasData['short_city_name'] = $fias['f1_name'];
                    }
                    else {
                        return false;
                    }

                    $countryAndZone = $this->model_pr_fias->findCountryAndZone(array('zone_id' => $this->model_pr_fias->getZoneIdByFiasId($fiasData['fias_zone_id'])));
                }

                $fields = array_merge($countryAndZone, $fiasData);
            }

            return $fields;
        }

        private function setting($key) {

            return (isset($this->settings[$key]) ? $this->settings[$key] : null);
        }

        private function setRules() {

            $rules = $this->config->get('geoip_rule');

            if (is_array($rules)) {

                $group_rules = array();

                foreach ($rules as $rule) {
                    $group_rules[$rule['key']][] = $rule;
                }

                foreach ($group_rules as $key => $group) {

                    foreach ($group as $rule) {

                        // Для города имеет приоритет над остальными
                        if ($rule['fias_id'] == $this->fias_id) {
                            $this->rules[$key] = $rule['value'];
                            break;
                        }

                        // Для региона может быть переписан
                        if ($rule['fias_id'] == $this->fias_zone_id) {
                            $this->rules[$key] = $rule['value'];
                        }

                        // Для страны устанавливаем, если не было определено ранее
                        else if ($rule['fias_id'] == $this->fias_country_id && !isset($this->rules[$key])) {
                            $this->rules[$key] = $rule['value'];
                        }
                    }
                }
            }
        }

        private function geoRedirect() {

            if (empty($_SERVER['HTTP_HOST'])) {
                return;
            }

            $redirects = $this->config->get('geoip_redirect');

            if (is_array($redirects)) {

                $redirect_url = false;

                foreach ($redirects as $redirect) {

                    // Для города имеет приоритет над остальными
                    if ($redirect['fias_id'] == $this->fias_id) {
                        $redirect_url = $redirect['value'];
                        break;
                    }

                    // Для региона может быть переписан
                    if ($redirect['fias_id'] == $this->fias_zone_id) {
                        $redirect_url = $redirect['value'];
                    }

                    // Для страны устанавливаем, если не было определено ранее
                    else if (!$redirect_url && $redirect['fias_id'] == $this->fias_country_id) {
                        $redirect_url = $redirect['value'];
                    }
                }

                $http_host = 'http://' . rtrim($_SERVER['HTTP_HOST'], '/') . '/';

                if ($redirect_url && $redirect_url != $http_host) {

                    $request_uri = ltrim($_SERVER['REQUEST_URI'], '/');

                    header('Status: 302');
                    header('Location: ' . $redirect_url . $request_uri);
                    exit();
                }
            }
        }
        //</editor-fold>
    }