<?php

require_once __DIR__ . '/CustomShipping.php';

/**
 *
 */
class ModelShippingRussianMail extends CustomShipping
{
    protected $shipping_code = 'russianmail';

    public function getQuote($address)
    {
        if (!$this->config->get('russianmail_status')) {
            return array();
        }
        if (!in_array($this->customer->getCustomerGroupId(), array(1, 4))) {
            return array();
        }
        if (empty($address['postcode'])) {
            return array();
        }
        $quote_data = $this->calculate(
            $address['postcode'],
            $this->cart->getSubTotal(),
            $this->getProducts()
        );
        return $this->prepareQuoteData($quote_data);
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
            'code'       => 'russianmail',
            'title'      => 'Почта России',
            'quote'      => $quote_data,
            'sort_order' => (int) $this->config->get('russianmail_sort_order'),
            'error'      => false
        );
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
        if ($this->config->get('russianmail_status')) {
            $sum_order = $this->getSumOrderFromTotals($totals);
            $products = $this->getProducts();
            $quote_data = $this->calculate(
                $postcode,
                $sum_order,
                ($is_calculator === false || count($products) > 0) ? $products : $this->getDummyProducts(),
                $is_calculator
            );
            $should_use_fallback = count($quote_data) === 0 && $this->config->get('russianmail_use_fallback');
            // Fallback
            if ($should_use_fallback
                && isset($fallback['city'])
                && isset($fallback['zone'])
                && $this->customer->getCustomerGroupId() != 4
            ) {
                $quote_data = $this->fallback($fallback['city'], $fallback['zone'], $sum_order);
            }
        }
        return $this->prepareQuoteData($quote_data);
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
		//bmv NON-FREE AKCIA SHIPPING begin
        $this->load->model('setting/setting');
        $promo_settings = $this->model_setting_setting->getSetting('promo_settings');

        $promo_settings['total_discount_products'] = str_replace(" ", "", $promo_settings['total_discount_products']);
        $total_discount_products = array_diff(explode("\r\n", $promo_settings['total_discount_products']), array(''));
        //bmv NON-FREE AKCIA SHIPPING End

        $quote_data = array();
        $calcs = $this->makeRequest($postcode, $sum_order, $products);
        $delivery = 'Почта России';
        //превести массив к кастомному виду
        foreach ($calcs as $code => $calc) {
            // Это означает, что доставка по выбранному способу недоступна
            if ($calc['total-rate'] === 0) {
                continue;
            }
            // Calculate declared value, multiple rubles by 100 to get kopeikas
            $type = (strpos($code, 'full') !== false) ? 'full' : 'part';
            $calc['total-rate'] += $calc['total-vat'];
            $calc['total-rate'] += $type === 'part' ? $this->getDeclaredValueMarkup($sum_order * 100) : 0;
            $place = 'Почта России';
            $payment = (strpos($code, 'class_1') !== false) ? '1 Класс. ' : '';
            if (strpos($code, 'online_parcel') !== false) {
                $rate = $this->config->get("russianmail_markup_{$type}_online");
                $place .= ' (Посылка онлайн)';
            } else {
                $rate = $this->config->get("russianmail_markup_{$type}_ordinary");
            }
            // Avia rate markup
            if (isset($calc['avia-rate'])) {
                $rate = ($type === 'full')
                    ? $this->config->get('russianmail_markup_full_avia')
                    : $this->config->get('russianmail_markup_part_avia');
            }
            $dcost = $cost = $this->calculateMarkup($calc['total-rate'], $rate);
            $fullcost = $sum_order + $cost;
						
            //bmv NON-FREE AKCIA SHIPPING begin
            $canBeFreeShipping = true;
            foreach ($products as $currentProduct){
                if (in_array($currentProduct['product_id'], $total_discount_products)) {
                    //$canBeFreeShipping = false;
                }
            }

            if ($type === 'full'
                && $sum_order >= $this->config->get('russianmail_sum_to_free')
                && $this->customer->getCustomerGroupId() != 4
				&& $canBeFreeShipping //added by BMV
            ) {
                $dcost = 'Бесплатно';
                $cost = 0;
                $fullcost = $sum_order;
            }
            $payment .= ($type === 'full') ? 'Предоплата 100%' : "Предоплата $dcost рублей";
            $quote_delivery = array(
                'code' => $code,
                'cost' => $cost,
                'cost_components' => array(
                    'original' => $calc['total-rate'],
                    'markup' => $cost - $calc['total-rate']
                ),
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
        $russianmail_default_size = $this->config->get('russianmail_default_size');
        $russianmail_default_weight = $this->config->get('russianmail_default_weight');
        $from = $this->config->get('russianmail_postalcode');
        $access_token = $this->config->get('russianmail_login');
        $user_token = $this->config->get('russianmail_password');
        $timeout = $this->config->get('russianmail_timeout');
        $weight = $this->getProductsWeight($products, $russianmail_default_weight, static::WEIGHT_G);
        $dimensions = $this->getParcelDimensions(
            $products,
            array(
                'height' => $russianmail_default_size['size_a'],
                'length' => $russianmail_default_size['size_b'],
                'width' => $russianmail_default_size['size_c']
            ),
            static::LENGTH_CM
        );
        $default = array(
            "courier" => false,
            "declared-value" => 0,
            "dimension" => $dimensions,
            "fragile" => false,
            "index-from" => trim($from),
            "index-to" => trim($postcode),
            "mail-category" => "ORDINARY",
            "mail-type" => "POSTAL_PARCEL",
            "mass" => $weight,
            "payment-method" => "CASHLESS",
            "with-order-of-notice" =>  false,
            "with-simple-notice" => false
        );
        if (count($options) === 0) {
            $options = array_flip($this->getAvailablePaymentsType());
            foreach ($options as $payment => $mail_type) {
                $options[$payment] = $this->getAvailableMailType();
            }
        }
        $options = array_intersect_key($options, array_flip($this->getAvailablePaymentsType()));
        $request_params = array();
        foreach ($options as $payment => $mail_type) {
            foreach ($mail_type as $type) {
                if (!in_array($type, $this->getAvailableMailType())
                    || !$this->isSendable($type, $dimensions, $weight)
                ) {
                    continue;
                }
                $code = strtolower("{$payment}_{$type}");
                $current_params = $default;
                $current_params['mail-type'] = $type;
                $codes[] = $code;
                $request_params[$type] = $current_params;
            }
        }
        $result = array();
        foreach ($request_params as $type => $value) {
            $headers = array();
            $request = curl_init('https://otpravka-api.pochta.ru/1.0/tariff');
            $json = json_encode($value);
            $headers[] = 'Content-Length: ' . strlen($json);
            $headers[] = 'Authorization: AccessToken ' . $access_token;
            $headers[] = 'X-User-Authorization: Basic ' . $user_token;
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Accept: application/json;charset=UTF-8';
            curl_setopt($request, CURLOPT_POSTFIELDS, $json);
            curl_setopt($request, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($request, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($request, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($request, CURLOPT_CONNECTTIMEOUT, $timeout);
            $return = curl_exec($request);
            $info = curl_getinfo($request);
            curl_close($request);
            if ($this->config->get('russianmail_log')) {
                $this->log->write('Почта Росии: отладка API, запрос - ' . $json . ', тело ответа: ' . $return);
            }
            $decoded = json_decode($return, true);
            if ($decoded && $info['http_code'] === 200) {
                foreach ($codes as $code) {
                    if (stripos($code, $type) !== false) {
                        $result[$code] = $decoded;
                    }
                }
            // On fail return empty result - to use fallback
            // TODO: think about that
            } elseif ($this->config->get('russianmail_log')) {
                $this->log->write('Почта Росии: код ответа от API: ' . $info['http_code'] . ', тело ответа: ' . $return);
                return array();
            }
        }
        return $result;
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
    public function getAvailableMailType()
    {
        $list = array('POSTAL_PARCEL');
        if ($this->config->get('russianmail_use_online')) {
            $list[] = 'ONLINE_PARCEL';
        }
        return $list;
    }

    /**
     * Is sendable
     *
     * @param string $mail_type
     * @param array $dimensions
     * @param int $weight
     * @return boolean
     */
    public function isSendable($mail_type, array $dimensions, $weight)
    {
        $dimension_sum = array_reduce($dimensions, function ($carry, $item) {
            return $carry + $item;
        }, 0);
        $dimension_max = max($dimensions);
        switch ($mail_type) {
            default:
                return true;
            case 'ONLINE_PARCEL':
                return ($weight <= 5000);
            case 'PARCEL_CLASS_1':
            case 'BANDEROL_CLASS_1':
                return ($weight <= 2500 && $dimension_sum <= 70 && $dimension_max <= 36);
        }
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
            if ($delivery['delivery'] !== $model::DELIVERY_RM) {
                continue;
            }
            $tariff = $key;
            $type = ($delivery['payment'] === $model::PAYMENT_100) ? 'full' : 'part';
            $code = "{$type}_{$tariff}";
            $cost = is_numeric($delivery['dcost']) ? $delivery['dcost'] : 0;
            $quote_delivery = $delivery;
            $quote_delivery = array_merge(
                $delivery,
                array(
                    'code' => $code,
                    'cost' => $cost,
                    'cost_components' => array('original' => $cost),
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
     * Расчёт наценки
     *
     * @param integer $cost
     * @param integer $rate
     * @return integer
     */
    private function calculateMarkup($cost, $rate)
    {
        // $cost в копейках - делим на 100, считаем наценку округляем до 10
        return ceil(($cost / 100) * (1 + $rate / 100) / 10) * 10;
    }

    /**
     * @inheritDoc
     */
    protected function getConfigCategoryData()
    {
        return $this->config->get('russianmail_category_data');
    }

    /**
     * @param integer $declared_value
     * @return float
     */
    protected function getDeclaredValueMarkup($declared_value)
    {
        $percents = $this->config->get('russianmail_markup_declared_value');
        $customer_group = !$this->customer->getCustomerGroupId() ? $this->config->get('config_customer_group_id') : $this->customer->getCustomerGroupId();
        foreach ($percents as $data) {
            if ($data['customer_group_id'] == $customer_group) {
                $percent = (float) $data['value'];
                break;
            }
        }
        if (!isset($percent)) {
            $percent = 6;
        }
        return $declared_value / 100 * $percent;
    }
}
