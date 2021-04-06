<?php

class ModelCheckoutShipping extends Model
{
    /**
     * @param array|float $totals
     * @param string $postcode
     * @param array $fallback
     * @param boolean $is_calculator Расчёт доставки для калькулятора
     * @return array
     */
    public function getCustomDelivery($totals, $postcode, $fallback = array(), $is_calculator = false)
    {
        if (is_numeric($totals)) {
            $totals = array(
                array(
                'code' => 'sub_total',
                'value' => $totals
                )
            );
        }
        if (!isset($this->session->data['shipping_methods']) || $is_calculator) {
            $shipping_methods = array();
            if (!preg_match('/^660/', $postcode) || isset($fallback['ignore_krsk'])) {
                $this->load->model('shipping/customcdek');
                $this->load->model('shipping/russianmail');
                if (isset($fallback['products'])) {
                    $this->model_shipping_customcdek->setProducts($fallback['products']);
                    $this->model_shipping_russianmail->setProducts($fallback['products']);
                }
                // Получаем данные от калькулятора СДЭК
                $shipping_methods[] = $this->model_shipping_customcdek->getDefaultCustomerDelivery(
                    $postcode,
                    $totals,
                    $fallback,
                    $is_calculator
                );
                // Получаем данные от почты россии
                $shipping_methods[] = $this->model_shipping_russianmail->getDefaultCustomerDelivery(
                    $postcode,
                    $totals,
                    $fallback,
                    $is_calculator
                );
            } else {
                $this->load->model('shipping/krskdelivery');
                if (isset($fallback['products'])) {
                    $this->model_shipping_krskdelivery->setProducts($fallback['products']);
                }
                $shipping_methods[] = $this->model_shipping_krskdelivery->getDefaultCustomerDelivery(
                    $totals,
                    $fallback,
                    $is_calculator
                );
            }
            $quote_data = array();
            foreach ($shipping_methods as $quote) {
                if (empty($quote)) {
                    continue;
                }
                $quote_data[$quote['code']] = array(
                    'title' => $quote['title'],
                    // В этом ключе будут сохранены дополнительные ключи для обычного пользователя
                    'quote' => $quote['quote'],
                    'sort_order' => $quote['sort_order'],
                    'error' => $quote['error']
                );
            }
            if ($is_calculator === false) {
                $this->session->data['shipping_methods'] = $quote_data;
            }
        }
        return $this->extractDefaultCustomerDelivery(
            $is_calculator ? $quote_data : $this->session->data['shipping_methods']
        );
    }

    /**
     * Get template data
     *
     * @return array
     */
    public function templateData()
    {
        $data = array();
        $data['show_has_dressing_room'] = $this->config->get('customcdek_dressingroom_activation');
        $data['show_partial_buypack'] = $this->config->get('customcdek_partbuy_activation');
        return $data;
    }

    /**
     * @param string $shipping_method
     * @param boolean $save_shipping_method
     * @param array $options
     * @return array $deliverycost, $fullcost
     */
    public function calculateDeliveryCostForDefaultCustomer(
        $shipping_method,
        $save_shipping_method = false,
        $options = array()
    ) {
        $response = array('', '');
        if (!isset($this->session->data['shipping_methods'])) {
            return $response;
        }
        $shipping = explode('.', $shipping_method);
        if (!isset($shipping[0])
            || !isset($shipping[1])
            || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]]['default_customer'])
        ) {
            return $response;
        }
        $quote = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
        $extra_cost = 0;
        if ($options['has_dressing_room'] && isset($quote['cost_components']['markup_dressingroom'])) {
            $text = ' <b>Возможность примерки перед покупкой.</b>';
            $quote['try_on'] = true;
            if (!preg_match("/$text/", $quote['title'])) {
                $quote['title'] .= $text;
            }
            $extra_cost += $quote['cost_components']['markup_dressingroom'];
        }
        $method = $quote['default_customer'];
        if (is_numeric($method['dcost'])) {
            $method['dcost'] += $extra_cost;
            $response[0] = $this->currency->format($method['dcost']);
        }
        if (is_numeric($method['fullcost'])) {
            $method['fullcost'] += $extra_cost;
            $response[1] = $this->currency->format($method['fullcost']);
        }
        if ($save_shipping_method) {
            if ($method['prepayment'] > 0) {
                $method['prepayment'] += $extra_cost;
                if (preg_match('/^Предоплата \d+ рублей$/', $method['payment'])) {
                    $method['payment'] = "Предоплата {$method['prepayment']} рублей";
                    $quote['title'] = preg_replace(
                        '/Предоплата \d+ рублей/',
                        $method['payment'],
                        $quote['title']
                    );
                }
            }
            $quote['default_customer'] = $method;
            $quote['cost'] += $extra_cost;
            $quote['text'] = $this->currency->format(
                $this->tax->calculate($quote['cost'], $this->config->get('config_tax')),
                $this->config->get('config_tax')
            );
            $this->session->data['shipping_method'] = $quote;
        }
        return $response;
    }

    /**
     * Get default customer choosen shipping method
     *
     * @return array|false
     */
    public function getSavedShippingMethodForDefaultCustomer()
    {
        if (!isset($this->session->data['shipping_method'])) {
            return false;
        }
        return $this->session->data['shipping_method']['default_customer'];
    }

    /**
     * @param array $totals
     * @return float
     */
    public function getSumOrderFromTotals(array $totals)
    {
        foreach ($totals as $data) {
            if ($data['code'] === 'sub_total') {
                return $data['value'];
            }
        }
        return 0;
    }

    /**
     * @param array $shipping_methods
     * @return array
     */
    protected function extractDefaultCustomerDelivery(array $shipping_methods)
    {
        $result = array();
        foreach ($shipping_methods as $shipping_method) {
            foreach ($shipping_method['quote'] as $quote) {
                if (isset($quote['default_customer']['hidden'])) {
                    continue;
                }
                $result[] = array_merge(
                    array(
                        'code' => $quote['code'],
                        'options' => '{}'
                    ),
                    $quote['default_customer']
                );
            }
        }
        return $result;
    }

    /**
     * @param string $name
     * @param boolean $is_calculator
     * @return array
     */
    public function normalizeAddress($name, $is_calculator = false)
    {
        // If user has confirmed addres in getNp2
        if (isset($this->session->data['normalized_address_confirm'])) {
            $item = $this->session->data['normalized_address_confirm'];
            unset($this->session->data['normalized_address_confirm']);
            return array($item);
        }
        $items = array();
        if (false === $suggestionsApi = \Neos\NeosFactory::getHelper('AddressSuggestions')) {
            return $items;
        }
        if ($is_calculator) {
            $data = $suggestionsApi->getBoundToCityOrSettlement($name, 1);
        } else {
            $data = $suggestionsApi->get($name);
        }
        if ($data === false) {
            return $items;
        }
        foreach ($data as $address) {
            if ($address->data->postal_code === null) {
                continue;
            }
            $postcode = $address->data->postal_code;
            $items[] = (object) array(
                'value' => $address->value,
                'unrestricted_value' => $address->unrestricted_value,
                'postcode' => $postcode
            );
        }
        return $items;
    }
}
