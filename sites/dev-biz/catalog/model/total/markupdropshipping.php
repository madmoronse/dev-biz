<?php
class ModelTotalMarkupdropshipping extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		if (($this->cart->getSubTotal() < $this->config->get('markupdropshipping_total')) && ($this->cart->getSubTotal() > 0)) {
            $customer_group_id = $this->customer->getCustomerGroupId();
            if ($customer_group_id==4) { if(!isset($this->session->data['markupdropshipping']['cost'])){$this->session->data['markupdropshipping']['cost'] = 0;}
                $this->language->load('total/markupdropshipping');


                $total_data[] = array(
                    'code' => 'markupdropshipping',
                    'title' => $this->language->get('text_markupdropshipping'),
                    'text' => $this->currency->format($this->session->data['markupdropshipping']['cost']),
                    'value' => $this->session->data['markupdropshipping']['cost'],
                    'sort_order' => $this->config->get('markupdropshipping_sort_order')
                );

                if ($this->config->get('markupdropshipping_tax_class_id')) {
                    $tax_rates = $this->tax->getRates($this->session->data['markupdropshipping']['cost'], $this->config->get('markupdropshipping_tax_class_id'));

                    foreach ($tax_rates as $tax_rate) {
                        if (!isset($taxes[$tax_rate['tax_rate_id']])) {
                            $taxes[$tax_rate['tax_rate_id']] = $tax_rate['amount'];
                        } else {
                            $taxes[$tax_rate['tax_rate_id']] += $tax_rate['amount'];
                        }
                    }
                }
                $total += $this->session->data['markupdropshipping']['cost'];
                //$total += $this->config->get('markupdropshipping_fee');
            }
		}
	}
}
?>