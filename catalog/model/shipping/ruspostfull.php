<?php
class ModelShippingRuspostfull extends Model {
	function getQuote($address) {
		$this->language->load('shipping/ruspostfull');
		$customer_group_id = $this->customer->getCustomerGroupId();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('ruspostfull_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
	
		if (!$this->config->get('ruspostfull_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			if ($customer_group_id != 3){
				$status = false;
			} else {
				$status = true;
			}
		}

		if ($this->cart->getSubTotal() < $this->config->get('ruspostfull_total')) {
			$status = false;
		}
		
		$method_data = array();
		
		
		$customer_city = $this->db->query("SELECT city FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$this->customer->getId() . "' AND address_id = '" . (int)$_SESSION['shipping_address_id'] . "'");

		if ($customer_group_id != 3){
		
			if ($customer_city->row['city'] == "Норильск" or $customer_city->row['city'] == "норильск" or $customer_city->row['city'] == "Норильск " or $customer_city->row['city'] == "норильск " or 
			$customer_city->row['city'] == "Кайеркан" or $customer_city->row['city'] == "кайеркан" or $customer_city->row['city'] == "Кайеркан " or $customer_city->row['city'] == "кайеркан " or
			$customer_city->row['city'] == "Талнах" or $customer_city->row['city'] == "талнах" or $customer_city->row['city'] == "Талнах " or $customer_city->row['city'] == "талнах " or 
			$customer_city->row['city'] == "Дудинка" or $customer_city->row['city'] == "дудинка" or $customer_city->row['city'] == "Дудинка " or $customer_city->row['city'] == "дудинка " or 
			$customer_city->row['city'] == "Игарка" or $customer_city->row['city'] == "игарка" or $customer_city->row['city'] == "Игарка " or $customer_city->row['city'] == "игарка ") {
				$status = false;
			}
		}
	
		if ($status) {
			if ($customer_group_id==4){ //стоимость доставки для дропшипперов
				$shop_cost_items = 0;		
				$opoQNT = 0;
				
				$this->load->model('catalog/product');
				$this->load->model('catalog/category');
				
				foreach ($this->cart->getProducts() as $product) {

					if ($product['shipping']) $shop_cost_items += $product['quantity'];//общее количество товара в корзине
					
						$categories  = $this->model_catalog_product->getCategories($product['product_id']);		
						if ($categories){
							foreach ($categories as $category) {

								if($category['category_id'] == 1898 or $category['category_id'] == 1899) { //Носки
										$shop_cost_items = $shop_cost_items - $product['quantity'];
								}								

								if($category['category_id'] == 7608) { //1+1=3

									//посчитать количество товара
									//$opoQNT += $product['quantity'];

									//$shop_cost_items = $shop_cost_items - $product['quantity'];
								}								



							}
						}
				}
				if ($shop_cost_items != 0) {

					if ($opoQNT<3) {
						$shop_cost=300+($shop_cost_items-1)*200;
					}

					if ($opoQNT>2) {

						$shop_cost=390*floor($opoQNT/3)+($shop_cost_items-(floor($opoQNT/3)*3))*200;
					}


				} else {
					$shop_cost = 150;
				}
					$quote_data = array();
					
					$quote_data['ruspostfull'] = array(
						'code'         => 'ruspostfull.ruspostfull',
						'title'        => $this->language->get('text_description'),
						'cost'         => $shop_cost,
						'tax_class_id' => 0,
						'text'         => $this->currency->format($shop_cost)
					);
		
					$method_data = array(
						'code'       => 'ruspostfull',
						'title'      => $this->language->get('text_title'),
						'quote'      => $quote_data,
						'sort_order' => $this->config->get('ruspostfull_sort_order'),
						'error'      => false
					);
					
			} else {
				if ($customer_group_id==3){
			
					$quote_data = array();
					
					$quote_data['ruspostfull'] = array(
						'code'         => 'ruspostfull.ruspostfull',
						'title'        => $this->language->get('text_description'),
						'cost'         => 0,
						'tax_class_id' => 0,
						'text'         => $this->currency->format(0)
					);
		
					$method_data = array(
						'code'       => 'ruspostfull',
						'title'      => $this->language->get('text_title'),
						'quote'      => $quote_data,
						'sort_order' => $this->config->get('ruspostfull_sort_order'),
						'error'      => false
					);
				
				}else {
					$quote_data = array();
					
					$shop_cost_items = 0;
					$shop_cost=0;
					$oneplusonecost = 0;
					foreach ($this->cart->getProducts() as $product) {
						if ($product['shipping']) $shop_cost_items += $product['quantity'];
						$this->load->model('catalog/product');
						$this->load->model('catalog/category');
						$categories  = $this->model_catalog_product->getCategories($product['product_id']);
		
						if ($categories){
							foreach ($categories as $category) {
								if($category['category_id'] == 1163) {
									if ($oneplusonecost == 0 and $product['quantity'] == 1) {
										$shop_cost=$shop_cost+$product['quantity']*250;
									} else {
										//$shop_cost=$shop_cost+$product['quantity']*350; //1+1=3
										$shop_cost=$shop_cost+$product['quantity']*250;
									}							
									$oneplusonecost = $oneplusonecost +$product['quantity'];
									if ($oneplusonecost == 2) {
										//$shop_cost=$shop_cost+50; //1+1=3
										
									}
									if ($oneplusonecost == 3) {
										//$shop_cost=$shop_cost-350;
									}
								}
								if($category['category_id'] == 1898 or $category['category_id'] == 1899) {  //Носки
										$shop_cost_items = $shop_cost_items - $product['quantity'];
								}
								
								/*$categories_info = $this->model_catalog_category->getCategory($category['category_id']);
								if ($categories_info['name'] == "Супер-цены!") {
									$status = false;
								}*/
							}
						}
		
					}
					if ($shop_cost_items != 0) {
					if ($oneplusonecost == 0){
						if ($this->cart->getSubTotal() > 6000) {
							$shop_cost = 0;
						} else {
							$shop_cost = 250;
						}
					}					
					
				} else {
					$shop_cost = 150;
				}
					
					$quote_data['ruspostfull'] = array(
						'code'         => 'ruspostfull.ruspostfull',
						'title'        => $this->language->get('text_description'),
						'cost'         => $shop_cost,
						'tax_class_id' => 0,
						'text'         => $this->currency->format($shop_cost)
					);
		
					$method_data = array(
						'code'       => 'ruspostfull',
						'title'      => $this->language->get('text_title'),
						'quote'      => $quote_data,
						'sort_order' => $this->config->get('ruspostfull_sort_order'),
						'error'      => false
					);
				}
			}
		}
	
		return $method_data;
	}
}
?>