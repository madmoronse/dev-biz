<?php
class ModelShippingRuspostpart extends Model {
	function getQuote($address) {
		$this->language->load('shipping/ruspostpart');
		
		$customer_group_id = $this->customer->getCustomerGroupId();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('ruspostpart_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
		
		if (!$this->config->get('ruspostpart_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}
					
		$method_data = array();
		
		$customer_city = $this->db->query("SELECT city FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$this->customer->getId() . "' AND address_id = '" . (int)$_SESSION['shipping_address_id'] . "'");

		if ($customer_city->row['city'] == "Норильск" or $customer_city->row['city'] == "норильск" or $customer_city->row['city'] == "Норильск " or $customer_city->row['city'] == "норильск " or 
		$customer_city->row['city'] == "Кайеркан" or $customer_city->row['city'] == "кайеркан" or $customer_city->row['city'] == "Кайеркан " or $customer_city->row['city'] == "кайеркан " or
		$customer_city->row['city'] == "Талнах" or $customer_city->row['city'] == "талнах" or $customer_city->row['city'] == "Талнах " or $customer_city->row['city'] == "талнах " or 
		$customer_city->row['city'] == "Дудинка" or $customer_city->row['city'] == "дудинка" or $customer_city->row['city'] == "Дудинка " or $customer_city->row['city'] == "дудинка " or 
		$customer_city->row['city'] == "Игарка" or $customer_city->row['city'] == "игарка" or $customer_city->row['city'] == "Игарка " or $customer_city->row['city'] == "игарка ") {
			$status = false;
		}
		
		
		if ($status and $this->cart->getSubTotal() >= 550) {
			
			if ($customer_group_id==4){
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

								if($category['category_id'] == 7608) { //1+1=3

									//посчитать количество товара
									//$opoQNT += $product['quantity'];


								}
								
							}
						}
				}
				if ($shop_cost_items != 0) {


					if ($opoQNT<3) {
						$shop_cost=450+($shop_cost_items-1)*250;
					}

					if ($opoQNT>2) {

						$shop_cost=390*floor($opoQNT/3)+($shop_cost_items-(floor($opoQNT/3)*3))*250;
					}
		
				} else {
					$shop_cost = 150;
				}
				
				$quote_data = array();
				
				$quote_data['ruspostpart'] = array(
					'code'         => 'ruspostpart.ruspostpart',
					'title'        => 'Доставка Почтой России. Частичная предоплата.',
					'cost'         => $shop_cost,
					//'cost'         => $costtotal,
					'tax_class_id' => $this->config->get('ruspostpart_tax_class_id'),
					'text'         => $this->currency->format($shop_cost)
					//'text'         => (string)$costtotal." ք"
				);
	
				$method_data = array(
					'code'       => 'ruspostpart',
					'title'      => $this->language->get('text_title'),
					'quote'      => $quote_data,
					'sort_order' => $this->config->get('ruspostpart_sort_order'),
					'error'      => false
				);
			
			
			}	else

			{

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	

			if ($customer_group_id==1){
				$shop_cost_items = 0;
				$all_items = 0;
				$this->load->model('catalog/product');
					$this->load->model('catalog/category');
				foreach ($this->cart->getProducts() as $product) {
					
					if ($product['shipping']) $all_items += $product['quantity'];
										
					
					$categories  = $this->model_catalog_product->getCategories($product['product_id']);					
					if ($categories){
						foreach ($categories as $category) {
							if($category['category_id'] == 1163) {
								if ($product['shipping']) $akcia_items += $product['quantity'];
							}	
						}
						foreach ($categories as $category) {
								if($category['category_id'] == 1898 or $category['category_id'] == 1899 ) { //Носки
										$all_items = $all_items - $product['quantity'];
								}								
							}
					}	
				}
				
				if ($all_items != 0) {
				
				if ($akcia_items == 0){
					$cost = $this->cart->getSubTotal();		
					if ($cost<1000) {$costtotal = 150;}
					if ($cost>=1000 and $cost<2000) {$costtotal = 250;} 
					if ($cost>=2000 and $cost<2300) {$costtotal = round( $cost/10*0.15)*10;}
					if ($cost>=2300 and $cost<2700) {$costtotal = round( $cost/10*0.14)*10;}
					if ($cost>=2700 and $cost<3000) {$costtotal = round( $cost/10*0.135)*10;}
					if ($cost>=3000) {$costtotal = round( $cost/10*0.125)*10;}

					if ($costtotal / $all_items >500 ) { $costtotal = $all_items*500;  } 
					if ($costtotal < 420 ) { $costtotal = 420;  }
					
				} else{
				
				$ne_akcia = $all_items - $akcia_items;
				if ($ne_akcia == 1){ $ne_akcia_cost = 450; }
				if ($ne_akcia == 2){ $ne_akcia_cost = 900; }
				if ($ne_akcia > 2){ $ne_akcia_cost = 900 + ($ne_akcia-2)*250; }
					if ($akcia_items<3){
						//$costtotal=$all_items*450; //1+1=3
						$costtotal=450 + ($akcia_items - 1) * 350 + $ne_akcia_cost; //Слив-3						
					} else {
						if ($akcia_items > 2){
							//$costtotal=900 + ($akcia_items - 2) * 150-50 + $ne_akcia_cost; //1+1=3
							$costtotal=800 + ($akcia_items - 2) * 250 + $ne_akcia_cost; //Слив-3
						} else {
							//$costtotal=900 + ($akcia_items - 2) * 250 + $ne_akcia_cost; //1+1=3
							$costtotal=450 + ($akcia_items - 1) * 350 + $ne_akcia_cost; //Слив-3
						}
					}
				}
				
				} else{
					$costtotal = 150;
				}
			}
		
		

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
				$quote_data = array();
			
			$text_description = $this->language->get('text_description');
			if ($costtotal > 1000) {
				$text_description = str_replace('1000', $costtotal, $text_description);
			}
			
			if ($customer_group_id==1){
				$quote_data['ruspostpart'] = array(
					'code'         => 'ruspostpart.ruspostpart',
					'title'        => $text_description,
					'cost'         => $costtotal,
					//'cost'         => $costtotal,
					'tax_class_id' => $this->config->get('ruspostpart_tax_class_id'),
					//'text'         => $this->currency->format($this->tax->calculate($this->config->get('ruspostpart_cost') * $ruspostparts, $this->config->get('ruspostpart_tax_class_id'), $this->config->get('config_tax')))
					'text'         => (string)$costtotal." ք"
				);
			} else {
				$quote_data['ruspostpart'] = array(
					'code'         => 'ruspostpart.ruspostpart',
					'title'        => 'Доставка Почтой России. Частичная предоплата.',
					'cost'         => $costtotal,
					//'cost'         => $costtotal,
					'tax_class_id' => $this->config->get('ruspostpart_tax_class_id'),
					//'text'         => $this->currency->format($this->tax->calculate($this->config->get('ruspostpart_cost') * $ruspostparts, $this->config->get('ruspostpart_tax_class_id'), $this->config->get('config_tax')))
					'text'         => (string)$costtotal." ք"
				);			
			}

      		$method_data = array(
        		'code'       => 'ruspostpart',
        		'title'      => $this->language->get('text_title'),
        		'quote'      => $quote_data,
				'sort_order' => $this->config->get('ruspostpart_sort_order'),
        		'error'      => false
      		);		
				
			}
			
		
		}
	
		return $method_data;
	}
}
?>