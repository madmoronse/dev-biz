<?php
class ModelShippingFarpart extends Model {
	function getQuote($address) {
		$this->language->load('shipping/farpart');
		
		$customer_group_id = $this->customer->getCustomerGroupId();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('farpart_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
		
		if (!$this->config->get('farpart_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}
					
		$method_data = array();
		
		if ($status and $this->cart->getSubTotal() >= 550) {
			
			
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

			if ($shop_cost_items == 1) {$shop_cost=700;}
			if ($shop_cost_items >= 2) {
				$shop_cost=700+($shop_cost_items-1)*500;		
			}
			
			if ($shop_cost_items != 0) {
			$quote_data = array();
			
      		$quote_data['farpart'] = array(
        		'code'         => 'farpart.farpart',
        		'title'        => $this->language->get('text_description'),
        		'cost'         => $shop_cost,
         		'tax_class_id' => $this->config->get('farpart_tax_class_id'),
				'text'         => $this->currency->format($shop_cost)
      		);

      		$method_data = array(
        		'code'       => 'farpart',
        		'title'      => $this->language->get('text_title'),
        		'quote'      => $quote_data,
				'sort_order' => $this->config->get('farpart_sort_order'),
        		'error'      => false
      		);
			}
		}
	
		return $method_data;
	}
}
?>