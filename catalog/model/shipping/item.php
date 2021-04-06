<?php
class ModelShippingItem extends Model {
	function getQuote($address) {
		$this->language->load('shipping/item');
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('item_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
		
		if (!$this->config->get('item_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}
		
		if ($this->customer->getCustomerGroupId() == 4) {$status = false;}
		
		$items = 0;
			
			foreach ($this->cart->getProducts() as $product) {
				if ($product['shipping']) $items += $product['quantity'];
            $this->load->model('catalog/product');
            $this->load->model('catalog/category');
            $categories  = $this->model_catalog_product->getCategories($product['product_id']);

            if ($categories){
                foreach ($categories as $category) {
					if($category['category_id'] == 1163 or $category['category_id'] == 2988 or $category['category_id'] == 3319) {$status = false;}
                    /*$categories_info = $this->model_catalog_category->getCategory($category['category_id']);
                    if ($categories_info['name'] == "Супер-цены!") {
                        $status = false;
                    }*/
                }
            }
        }
		
		if ($status) {
		
		
		
		
		if ($this->cart->getSubTotal() >= 700 and $this->cart->getSubTotal() < $this->config->get('item_total') and $items <2) {
		
		$method_data = array();
				
			$quote_data = array();
			
      		$quote_data['item'] = array(
        		'code'         => 'item.item',
        		'title'        => $this->language->get('text_description'),
        		'cost'         => $this->config->get('item_cost') * $items,
         		'tax_class_id' => $this->config->get('item_tax_class_id'),
				'text'         => $this->currency->format($this->tax->calculate($this->config->get('item_cost') * $items, $this->config->get('item_tax_class_id'), $this->config->get('config_tax')))
      		);

      		$method_data = array(
        		'code'       => 'item',
        		'title'      => $this->language->get('text_title'),
        		'quote'      => $quote_data,
				'sort_order' => $this->config->get('item_sort_order'),
        		'error'      => false
      		);
		}
			
		else
		{
		$quote_data = array();
			
      		$quote_data['item'] = array(
        		'code'         => 'item.item',
        		'title'        => 'Заказы на сумму от 3500р и выше отправляются только по предоплате.',
        		'cost'         => '-',
         		'tax_class_id' => $this->config->get('item_tax_class_id'),
				'text'         => '-'
      		);
			
			
			$method_data = array(
        		'code'       => 'item',
        		'title'      => $this->language->get('text_title'),
        		'quote'      => $quote_data,
				'sort_order' => $this->config->get('item_sort_order'),
        		'error'      => false
      		);
		}
		
		
	
		return $method_data;
		}
	}
}
?>