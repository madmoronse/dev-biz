<?php
class ModelShippingSng extends Model {
	function getQuote($address) {
		$this->language->load('shipping/sng');
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('sng_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
		
		if (!$this->config->get('sng_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}
		
		$method_data = array();
		
		$cost = $this->cart->getSubTotal();
	
		if ($status) {
			
			if ($this->cart->getSubTotal() >= 550){
			
			$items = 0;
			
			foreach ($this->cart->getProducts() as $product) {
				if ($product['shipping']) $items += $product['quantity'];
			}			
			
			if ($items == 1) { $total_cost = 800;}
			if ($items == 2) { $total_cost = 1400;}
			if ($items == 3) { $total_cost = 1800;}
			if ($items == 4) { $total_cost = 2200;}
			if ($items == 5) { $total_cost = 2400;}
			if ($items > 5) {
				$items_s = $items - 5;
				$total_cost  = 2400 + $items_s * 200;				
			}
			
			
			$quote_data = array();
			
      		$quote_data['sng'] = array(
        		'code'         => 'sng.sng',
        		'title'        => $this->language->get('text_description'),
        		'cost'         => $total_cost,
         		'tax_class_id' => $this->config->get('sng_tax_class_id'),
				'text'         => $this->currency->format($this->tax->calculate($total_cost, $this->config->get('sng_tax_class_id'), $this->config->get('config_tax')))
      		);

      		$method_data = array(
        		'code'       => 'sng',
        		'title'      => $this->language->get('text_title'),
        		'quote'      => $quote_data,
				'sort_order' => $this->config->get('sng_sort_order'),
        		'error'      => false
      		);
		}
		
		else
		{
		$quote_data = array();
			
      		$quote_data['sng'] = array(
        		'code'         => 'sng.sng',
        		'title'        => 'Минимальная сумма заказа для отправки 1300р.',
        		'cost'         => '-',
         		'tax_class_id' => $this->config->get('sng_tax_class_id'),
				'text'         => '-'
      		);
			
			
			$method_data = array(
        		'code'       => 'sng',
        		'title'      => $this->language->get('text_title'),
        		'quote'      => $quote_data,
				'sort_order' => $this->config->get('sng_sort_order'),
        		'error'      => false
      		);
		}
	}
		return $method_data;
	}
}
?>