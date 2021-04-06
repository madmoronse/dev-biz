<?php
class ModelShippingTkkrsk extends Model
{
    function getQuote($address)
    {
        $code = 'tkkrsk';
        $this->language->load("shipping/$code");

        $status = isset($address['city']) && mb_stripos($address['city'], "Красноярск", 0, "utf-8") === 0;

        if ($this->customer->getCustomerGroupId() != 4 && $this->customer->getCustomerGroupId() != 3) {
            $status = false;
        }
        
        $method_data = array();

        if ($status) {
            $quote_data = array();

            if ($this->customer->getCustomerGroupId() == 4) {
                $quote_data[$code] = array(
                    'code'         => $code . '.' . $code,
                    'title'        => $this->language->get('text_description'),
                    'cost'         => 350.00,
                    'tax_class_id' => 0,
                    'text'         => $this->currency->format(350)
                );
            }

            if ($this->customer->getCustomerGroupId() == 3) {
                $quote_data[$code] = array(
                    'code'         => $code . '.' . $code,
                    'title'        => $this->language->get('text_description'),
                    'cost'         => 0.00,
                    'tax_class_id' => 0,
                    'text'         => $this->currency->format(0)
                );
            }


            $method_data = array(
                'code'       => $code,
                'title'      => $this->language->get('text_title'),
                'quote'      => $quote_data,
                'sort_order' => $this->config->get($code . '_sort_order'),
                'error'      => false
            );
        }
        return $method_data;
    }
}
