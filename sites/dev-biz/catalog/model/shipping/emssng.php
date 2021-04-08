<?php
class ModelShippingEmssng extends Model {
	function getQuote($address) {
		$this->language->load('shipping/emssng');
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('emssng_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
		
		if (!$this->config->get('emssng_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}
		
		$method_data = array();
		
		$cost = $this->cart->getSubTotal();
	
		if ($status) {			
			
			
			$shop_cost_items = 0;
			
				$this->load->model('catalog/product');
				$this->load->model('catalog/category');				
				foreach ($this->cart->getProducts() as $product) {
					if ($product['shipping']) $shop_cost_items += $product['quantity'];
					$categories  = $this->model_catalog_product->getCategories($product['product_id']);		
						if ($categories){
							foreach ($categories as $category) {
								if($category['category_id'] == 1898 or $category['category_id'] == 1899) { //Носки
										$shop_cost_items = $shop_cost_items - $product['quantity'];
								}								
							}
						}
				}				
			
			$add_items = $shop_cost_items-1;
			
				$total_cost  = 1500 + $add_items*500;				
			
			
			
			$quote_data = array();
			
      		$quote_data['emssng'] = array(
        		'code'         => 'emssng.emssng',
        		'title'        => $this->language->get('text_description'),
        		'cost'         => $total_cost,
         		'tax_class_id' => $this->config->get('emssng_tax_class_id'),
				'text'         => $this->currency->format($this->tax->calculate($total_cost, $this->config->get('emssng_tax_class_id'), $this->config->get('config_tax')))
      		);

      		$method_data = array(
        		'code'       => 'emssng',
        		'title'      => $this->language->get('text_title'),
        		'quote'      => $quote_data,
				'sort_order' => $this->config->get('emssng_sort_order'),
        		'error'      => false
      		);
				
		
	}
		return $method_data;
	}
}
?>