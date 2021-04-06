<?php
require_once __DIR__ . '/CustomShipping.php';

class ModelShippingCustomCdek extends CustomShipping {
    protected $shipping_code = 'customcdek';

    public function getQuote($address)
    {
        if (!in_array($this->customer->getCustomerGroupId(), array(1))) {
            return array();
        }
        if (empty($address['postcode'])) {
            return array();
        }
        return $this->prepareQuoteData(array());
    }

    /**
     * Get delivery methods for default customer
     *
     * @param string $postcode
     * @param array $totals
     * @param array $fallback
     * @param boolean $is_calculator Расчёт доставки для калькулятора
     * @return array
     */
    public function getDefaultCustomerDelivery(
        $postcode,
        array $totals,
        array $fallback = array(),
        $is_calculator = false
    ) {
        $quote_data = array();
        if ($this->config->get('customcdek_status')) {
            $sum_order = $this->getSumOrderFromTotals($totals);
            $products = $this->getProducts();
            $quote_data = $this->calculate(
                $postcode,
                $sum_order,
                ($is_calculator === false || count($products) > 0) ? $products : $this->getDummyProducts(),
                $is_calculator
            );
            // Fallback
            if ($this->config->get('customcdek_use_fallback')
                && count($quote_data) === 0
                && isset($fallback['city'])
                && isset($fallback['zone'])
            ) {
                $quote_data = $this->fallback($fallback['city'], $fallback['zone'], $sum_order);
            }
        }
        $cdek_city_id = $this->getCdekCityId($postcode);
        foreach ($quote_data as $code => $quote) {
            if (!is_null($cdek_city_id)) {
                $quote_data[$code]['cdek_city_id'] = $cdek_city_id;
            }
            // To warehouse
            if (strpos($code, '136')) {
                $quote_data[$code]['default_customer']['options'] = htmlspecialchars(json_encode(array(
                    'isCdek' => true,
                    'methods' => array_values(
                        array_filter(
                            array_map(function ($value) use ($quote) {
                                $item = $value['default_customer'];
                                if ($quote['payment_type'] === $value['payment_type']) {
                                    return array(
                                        'name' => $item['place'],
                                        'with_warehouse' => strpos($value['code'], '136') !== false,
                                        'markup_dressingroom' => isset($value['cost_components']['markup_dressingroom'])
                                            ? $value['cost_components']['markup_dressingroom'] : 0,
                                        'code' => $value['code']
                                    );
                                }
                                return null;
                            }, $quote_data)
                        )
                    )
                )));
            // To Door - hide that option if it is not a calculator
            } elseif ($is_calculator === false) {
                $quote_data[$code]['default_customer']['hidden'] = true;
            }
        }
        return $this->prepareQuoteData($quote_data);
    }

    /**
     * @param string $postcode
     * @return integer|null
     */
    protected function getCdekCityId($postcode)
    {
        $ch = curl_init(
            'https://integration.cdek.ru/v1/location/cities/json?size=1&page=0&countryCode=RU&postcode='
            . urlencode(trim($postcode))
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        if ((int) $info['http_code'] !== 200) {
            return null;
        }
        $data = json_decode($result);
        return isset($data[0]->cityCode) ? $data[0]->cityCode : null;
    }

    /**
     * @param string $postcode
     * @param float $sum_order
     * @param array $products
     * @param boolean $is_calculator Расчёт доставки для калькулятора
     * @return array
     */
    protected function calculate($postcode, $sum_order, array $products, $is_calculator = false)
    {
        $quote_data = array();
        $calcs = $this->makeRequest($postcode, $sum_order, $products);
        $delivery = 'СДЭК';
        //превести массив к кастомному виду
        foreach ($calcs as $code => $calc) {
            if ($calc === 0) {
                continue;
            }
            switch ($code) {
                case 'full_136':
                    $rate = $this->config->get('customcdek_markup_full_pvz');
                    $type = 'full';
                    break;
                case 'part_136':
                    $rate = $this->config->get('customcdek_markup_part_pvz');
                    $type = 'part';
                    break;
                case 'full_137':
                    $rate = $this->config->get('customcdek_markup_full_door');
                    $type = 'full';
                    break;
                case 'part_137':
                    $rate = $this->config->get('customcdek_markup_part_door');
                    $type = 'part';
                    break;
            }
            $declared_value_markup = ceil($this->getDeclaredValueMarkup($sum_order) / 10) * 10;
            $dcost = $cost = $this->calculateMarkup((float) $calc, (float) $rate) + $declared_value_markup;
            $cost_components = array(
                'original' => $calc,
                'markup' => $cost - $calc
            );
            // To door
            if (strpos($code, '137') !== false) {
                $place = $delivery . ' (до двери)';
            // To warehouse
            } else {
                $place = $delivery . ' (пункт выдачи)';
            }
            if ((float) $this->config->get('customcdek_markup_dressingroom') != 0) {
                $cost_components['markup_dressingroom'] = ceil(($this->calculateMarkup(
                    (float) $calc,
                    (float) $this->config->get('customcdek_markup_dressingroom')
                ) - $calc) / 10) * 10;
            } else {
                $cost_components['markup_dressingroom'] = 0;
            }
            $payment = ($type === 'full') ? 'Предоплата 100%' : "Предоплата $dcost рублей";
            $fullcost = $sum_order + $cost;
            $quote_delivery = array(
                'code' => $code,
                'cost' => $cost,
                'cost_components' => $cost_components,
                'dcost' => $dcost,
                'place' => $place,
                'payment' => $payment,
                'delivery' => $delivery,
                'fullcost' => $fullcost,
                'prepayment' => $type === 'full' ? $fullcost : $cost,
                'payment_type' => $type
            );
            $quote_data[$code] = $this->quoteData($quote_delivery);
        }
        return $quote_data;
    }

    /**
     * @param string $postcode
     * @param float $sum_order
     * @param array $products
     * @param array $options Варианты доставки
     * @return array
     */
    protected function makeRequest($postcode, $sum_order, array $products, $options = array())
    {
        $timeout = $this->config->get('customcdek_timeout');
        $cdek_default_size = $this->config->get('customcdek_default_size');
        $customcdek_default_weight = $this->config->get('customcdek_default_weight');
        $from =  $this->config->get('customcdek_postalcode');
        $weight = $this->getProductsWeight($products, $customcdek_default_weight, static::WEIGHT_KG);
        $volume = $this->getProductsTotalVolume(
            $products,
            array(
                'height' => $cdek_default_size['size_a'],
                'length' => $cdek_default_size['size_b'],
                'width' => $cdek_default_size['size_c']
            ),
            static::LENGTH_CM
        );
        $date = date('Y-m-d');
        $default = array(
            'version' => '1.0',
            'dateExecute' => $date,
            'authLogin' => $this->config->get('customcdek_login'),
            'secure' => md5($date . '&' . $this->config->get('customcdek_password')),
            'senderCityPostCode' => $from,
            'receiverCityPostCode' => $postcode,
            'goods' => array(
                array(
                'weight' => $weight,
                'volume' => $volume
            ))
        );
        if (count($options) === 0) {
            $tariff_list = array(136);
            if ($this->config->get('customcdek_door_activation')) {
                $tariff_list[] = 137;
            }
            $options = array_flip($this->getAvailablePaymentsType());
            foreach ($options as $payment => $tariffs) {
                $options[$payment] = $tariff_list;
            }
        }
        $headers = array(
            'Content-Type: application/json',
        );
        $response = array();
        $options = array_intersect_key($options, array_flip($this->getAvailablePaymentsType()));
        $tariff_list = $this->getTariffListFromOptions($options);
        foreach ($tariff_list as $tariff) {
            $current_params = $default;
            $current_params['tariffId'] = $tariff;
            $json = json_encode($current_params);
            $url = "http://api.cdek.ru/calculator/calculate_price_by_json.php";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 6);
            $raw = curl_exec($ch);
            $result = json_decode($raw, true);
            $info = curl_getinfo($ch);
            curl_close($ch);
            if ($this->config->get('customcdek_log')) {
                unset($current_params['authLogin'], $current_params['secure']);
                $this->log->write('Custom СДЭК: отладка API, запрос - ' . json_encode($current_params) . ', тело ответа - ' . $raw);
            }
            if ($this->config->get('customcdek_log') && ($info['http_code'] !== 200 || isset($result['error']))) {
                $this->log->write('Custom СДЭК: код ответа от API: ' . $info['http_code'] . ', тело ответа: ' . $raw);
            }
            if (isset($result['result']['price'])) {
                $calculate_results[$tariff] = array(
                    'price' => isset($result['result']['priceByCurrency']) ? $result['result']['priceByCurrency'] : $result['result']['price'],
                    'cash_on_delivery_limit' => isset($result['result']['cashOnDelivery']) ? (float) $result['result']['cashOnDelivery'] : 0
                );
            // On fail return empty result - to use fallback
            // TODO: think about that
            } else {
                return array();
            }
        }
        foreach ($options as $payment => $tariffs) {
            foreach ($tariffs as $tariff) {
                if (!isset($calculate_results[$tariff])) {
                    continue;
                }
                $result = $calculate_results[$tariff];
                $code = strtolower("{$payment}_{$tariff}");
                if ($payment === 'part') {
                    // Order price is greater than allowed limit
                    if ($result['cash_on_delivery_limit'] !== 0
                        && $sum_order > $result['cash_on_delivery_limit']
                    ) {
                        continue;
                    }
                    $response[$code] = $result['price'];
                } else {
                    $response[$code] = $result['price'];
                }
            }
        }
        return $response;
    }

    /**
     * @param array $options
     * @return array
     */
    protected function getTariffListFromOptions($options)
    {
        $tariff_list = array();
        foreach ($options as $tariffs) {
            foreach ($tariffs as $tariff) {
                if (!in_array($tariff, $this->getAvailableTariffs())) {
                    continue;
                }
                $tariff_list[] = $tariff;
            }
        }
        return array_unique($tariff_list);
    }

    /**
     * @return array
     */
    public function getAvailablePaymentsType()
    {
        return array('full', 'part');
    }

    /**
     * @return array
     */
    public function getAvailableTariffs()
    {
        return array(136, 137);
    }

    /**
     * @param string $city
     * @param string $zone
     * @param float $sum_order
     * @return array
     */
    protected function fallback($city, $zone, $sum_order)
    {
        $quote_data = array();
        $this->load->model('checkout/delivery');
        $model = $this->model_checkout_delivery;
        $deliveries = $model->getDelivery($city, $zone, $sum_order, 0);
        foreach ($deliveries as $key => $delivery) {
            if ($delivery['delivery'] !== $model::DELIVERY_SDEK) {
                continue;
            }
            $cost = is_numeric($delivery['dcost']) ? $delivery['dcost'] : 0;
            $cost_components = array('original' => $cost);
            if ($delivery['place'] === $model::PLACE_SDEK) {
                $tariff = 136;
            } else {
                $tariff = 137;
            }
            if ((float) $this->config->get('customcdek_markup_dressingroom') != 0) {
                $cost_components['markup_dressingroom'] = ceil(($this->calculateMarkup(
                    (float) $cost,
                    (float) $this->config->get('customcdek_markup_dressingroom')
                ) - $cost) / 10) * 10;
            } else {
                $cost_components['markup_dressingroom'] = 0;
            }
            $type = ($delivery['payment'] === $model::PAYMENT_100) ? 'full' : 'part';
            $code = "{$type}_{$tariff}";
            $quote_delivery = $delivery;
            $quote_delivery = array_merge(
                $delivery,
                array(
                    'code' => $code,
                    'cost' => $cost,
                    'cost_components' => $cost_components,
                    'payment_type' => $type,
                    'prepayment' => $model->getPrepayment(
                        $city,
                        $zone,
                        $sum_order,
                        $key,
                        $delivery['fullcost'],
                        0
                    )
                )
            );
            $quote_data[$code] = $this->quoteData($quote_delivery);
        }
        return $quote_data;
    }


    /**
     * @param array $quote_data
     * @return array
     */
    protected function prepareQuoteData(array $quote_data)
    {
        if (count($quote_data) === 0) {
            return array();
        }
        return array(
            'code'       => 'customcdek',
            'title'      => 'СДЭК',
            'quote'      => $quote_data,
            'sort_order' => (int) $this->config->get('customcdek_sort_order'),
            'error'      => false
        );
    }

    /**
     * Расчёт наценки
     *
     * @param float $cost
     * @param float $rate
     * @return integer
     */
    private function calculateMarkup($cost, $rate)
    {
        // наценку округляем до 10
        return ceil($cost * (1 + $rate / 100) / 10) * 10;
    }

    /**
     * @param string $postcode
     * @param array $options
     * @return array
     */
    public function getWarehouseList($postcode, $options = array())
    {
        if (!$this->config->get('customcdek_pvz_activation')) {
            return array();
        }
        $hash = md5($postcode);
        if (!isset($this->session->data['cdek_warehouse']['hash'])
            || $this->session->data['cdek_warehouse']['hash'] !== $hash) {
            $raw = $this->makeRequestWarehouse($postcode);
            if ($raw !== false) {
                $list = array();
                foreach ($raw as $warehouse) {
                    $attr = $warehouse->{'@attributes'};
                    $list[] = array(
                        'code' => $attr->Code,
                        'address' => $attr->FullAddress,
                        'work_time' => $attr->WorkTime,
                        'has_dressing_room' => $attr->IsDressingRoom === 'true' ? true : false,
                        'cash_on_delivery' => $attr->AllowedCod === 'true' ? true : false,
                        'city_id' => $attr->CityCode,
                    );
                }
                $this->session->data['cdek_warehouse']['hash'] = $hash;
                $this->session->data['cdek_warehouse']['list'] = $list;
            }
        } else {
            $list = $this->session->data['cdek_warehouse']['list'];
        }
        $filtered = array_values(array_filter(is_array($list) ? $list : array(), function ($item) use ($options) {
            if ($options['has_dressing_room'] && $item['has_dressing_room'] === false
                || $options['cash_on_delivery'] && $item['cash_on_delivery'] === false
            ) {
                return false;
            }
            return true;
        }));
        return $filtered;
    }

    /**
     * @param string $postcode
     * @return array|false
     */
    protected function makeRequestWarehouse($postcode)
    {
        $request_params = array(
            'citypostcode' =>  $postcode,
            'type' => 'ALL'
        );
        $headers = array('Content-Type: application/xml');
        $timeout = 2;
        $ch = curl_init("https://integration.cdek.ru/pvzlist/v1/xml?".http_build_query($request_params));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        $data = array();
        if ($info['http_code'] === 200) {
            $xml = simplexml_load_string($result);
            if ($xml) {
                $data = json_decode(json_encode($xml));
            }
        } elseif ($this->config->get('customcdek_log')) {
            $this->log->write('Custom СДЭК: пункты выдачи, код ответа от API:' . $info['http_code'] . ', тело ответа: ' . $result);
            return false;
        }
        if (!isset($data->Pvz) && $this->config->get('customcdek_log')) {
            $this->log->write('Custom СДЭК: получение пунктов выдачи - ошибка, почтовый индекс: ' . $postcode . ', тело ответа: ' . $result);
        }
        return isset($data->Pvz) ? $data->Pvz : array();
    }

    /**
     * @inheritDoc
     */
    protected function getConfigCategoryData()
    {
        return $this->config->get('customcdek_category_data');
    }

    /**
     * @param integer $declared_value
     * @return float
     */
    protected function getDeclaredValueMarkup($declared_value)
    {
        $percents = $this->config->get('customcdek_markup_declared_value');
        $customer_group = !$this->customer->getCustomerGroupId() ? $this->config->get('config_customer_group_id') : $this->customer->getCustomerGroupId();
        foreach ($percents as $data) {
            if ($data['customer_group_id'] == $customer_group) {
                $percent = (float) $data['value'];
                break;
            }
        }
        if (!isset($percent)) {
            $percent = 1;
        }
        return $declared_value / 100 * $percent;
    }
}
