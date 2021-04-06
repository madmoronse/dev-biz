<?php
require_once __DIR__ . '/CustomShipping.php';

class ModelShippingKrskDelivery extends CustomShipping
{
    protected $shipping_code = 'krskdelivery';

    public function getQuote($address)
    {
        return array();
    }

    /**
     * Get delivery methods for default customer
     *
     * @param array $totals
     * @param array $fallback
     * @return array
     */
    public function getDefaultCustomerDelivery(array $totals, array $fallback)
    {
        $sum_order = $this->getSumOrderFromTotals($totals);
        $quote_data = array();
        if (isset($fallback['city']) && isset($fallback['zone'])) {
            $city = $fallback['city'];
            $zone = $fallback['zone'];
            $this->load->model('checkout/delivery');
            $model = $this->model_checkout_delivery;
            $deliveries = $model->getDelivery($city, $zone, $sum_order, 0);
            foreach ($deliveries as $key => $delivery) {
                $code = "{$key}";
                $quote_delivery = $delivery;
                $quote_delivery = array_merge(
                    $delivery,
                    array(
                        'code' => $code,
                        'cost' => is_numeric($delivery['dcost']) ? $delivery['dcost'] : 0,
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
        }
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
            'code'       => $this->shipping_code,
            'title'      => 'Доставка по Красноярску',
            'quote'      => $quote_data,
            'sort_order' => 0,
            'error'      => false
        );
    }
}
