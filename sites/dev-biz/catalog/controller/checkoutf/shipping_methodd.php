<?php
class ControllerCheckoutfShippingMethodd extends Controller {
  	public function index()
	{
		if (isset($this->session->data['markupdropshipping'])){
			$this->data['markupdropshipping'] = $this->session->data['markupdropshipping']['cost'];
		} else {
			$this->data['markupdropshipping'] = 0;
		}

		//BMV drop margin BEGIN
        $pdnum = 1;
        if (!empty($this->request->post['price_drop'])) {
            unset($this->session->data['price_drop']);
            foreach ($this->request->post['price_drop'] as $key=>$price_drop) {
                $this->session->data['price_drop'][] = $price_drop;
                $this->session->data['cart_price_drop'][$key] = $price_drop;
                $pdnum = $pdnum + 1;
            }
        }
        $this->data['price_drop'] = $this->session->data['cart_price_drop'];

        if (!empty($this->request->post['markupdropshipping'])) {
            unset($this->session->data['markupdropshipping']);
            $this->session->data['markupdropshipping']['cost'] = $this->request->post['markupdropshipping'];
        }

        //BMV drop margin END

		unset($this->session->data['passport-seria']);
		unset($this->session->data['passport-number']);
		$this->language->load('checkoutf/checkout');
		$this->data['customer_group_id'] = $this->customer->getCustomerGroupId();
		$this->load->model('account/address');
		$customer_group = $this->customer->getCustomerGroupId();

		$this->load->model('account/comment');

		$this->data['comments'] = $this->model_account_comment->getComments();
		
		// #carthybrid
		if (!NEOS_CART_OLD && $customer_group == 2) {
			if (NEOS_CART_HYBRID) {
				unset($this->session->data['deliverytype'],
					  $this->session->data['prepayment']);
			}
			$this->loadCustom($customer_group);
			return false;
		}
		if ($this->customer->isLogged() && isset($this->session->data['shipping_address_id'])) {
			$shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);
		} elseif (isset($this->session->data['guest'])) {
			$shipping_address = $this->session->data['guest']['shipping'];
		}

		if (!isset($this->session->data['shipping_address_id']) && $this->data['customer_group_id'] == 4) {

			$shipping_address['country_id'] = $this->session->data['shipping_country_id'];
			$shipping_address['zone_id'] = $this->session->data['shipping_zone_id'];
			$shipping_address['postcode'] = $this->session->data['shipping_postcode'];
			$shipping_address['lastname'] = $this->session->data['shipping_lastname'];
			$shipping_address['firstname'] = $this->session->data['shipping_firstname'];
			$shipping_address['middlename'] = $this->session->data['shipping_middlename'];
			$shipping_address['telephone'] = $this->session->data['shipping_telephone'];
			$shipping_address['city'] = $this->session->data['shipping_city'];
			$shipping_address['address_1'] = $this->session->data['shipping_address_1'];
			$shipping_address['address_2'] = $this->session->data['shipping_address_2'];
			$shipping_address['address_3'] = $this->session->data['shipping_address_3'];
		}

		if (!empty($shipping_address)) {
			// Shipping Methods
			$quote_data = array();

			$this->load->model('setting/extension');

			$results = $this->model_setting_extension->getExtensions('shipping');

			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('shipping/' . $result['code']);

					$quote = $this->{'model_shipping_' . $result['code']}->getQuote($shipping_address);

					if ($quote) {
						$quote_data[$result['code']] = array(
								'title' => $quote['title'],
								'quote' => $quote['quote'],
								'sort_order' => $quote['sort_order'],
								'error' => $quote['error']
						);
					}
				}
			}

			$sort_order = array();

			foreach ($quote_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $quote_data);

			$this->session->data['shipping_methods'] = $quote_data;
		}

		$this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
		$this->data['text_comments'] = $this->language->get('text_comments');

		$this->data['button_continue'] = $this->language->get('button_continue');

		if (empty($this->session->data['shipping_methods'])) {
			$this->data['error_warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['shipping_methods'])) {
			$this->data['shipping_methods'] = $this->session->data['shipping_methods'];
		} else {
			$this->data['shipping_methods'] = array();
		}

		if (isset($this->session->data['shipping_method']['code'])) {
			$this->data['code'] = $this->session->data['shipping_method']['code'];
		} else {
			$this->data['code'] = '';
		}

		if (isset($this->session->data['comment'])) {
			$this->data['comment'] = $this->session->data['comment'];
		} else {
			$this->data['comment'] = '';
		}

		//bmv begin
		$products = $this->cart->getProducts();

		foreach ($products as $product) {
			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}

			if ($product['minimum'] > $product_total) {
				$json['redirect'] = $this->url->link('checkoutf/cart');

				break;
			}
		}

		if (!$json) {

		$product_data = array();

		foreach ($this->cart->getProducts() as $product) {
			$option_data = array();

			foreach ($product['option'] as $option) {
				if ($option['type'] != 'file') {
					$value = $option['option_value'];
				} else {
					$value = $this->encryption->decrypt($option['option_value']);
				}

				$option_data[] = array(
						'product_option_id' => $option['product_option_id'],
						'product_option_value_id' => $option['product_option_value_id'],
						'option_id' => $option['option_id'],
						'option_value_id' => $option['option_value_id'],
						'name' => $option['name'],
						'value' => $value,
						'type' => $option['type']
				);
			}

			$product_data[] = array(
					'key'      => $product['key'],
					'product_id' => $product['product_id'],
					'name' => $product['name'],
					'model' => $product['model'],
					'option' => $option_data,
					'download' => $product['download'],
					'quantity' => $product['quantity'],
					'subtract' => $product['subtract'],
					'price' => $product['price'],
					'total' => $product['total'],
					'href'       => $this->url->link('product/product', 'product_id=' . $product['product_id']),
					'tax' => $this->tax->getTax($product['price'], $product['tax_class_id']),
					'reward' => $product['reward']
			);
		}
			$this->data['products'] = $product_data;
			$this->data['column_name'] = $this->language->get('column_name');
			$this->data['column_sku'] = $this->language->get('column_sku');
			$this->data['column_quantity'] = $this->language->get('column_quantity');
			$this->data['column_price'] = $this->language->get('column_price');
			$this->data['column_price_drop'] = $this->language->get('column_price_drop');
			$this->data['column_total_drop'] = $this->language->get('column_total_drop');
			$this->data['column_margin_drop'] = $this->language->get('column_margin_drop');
			$this->data['column_total_margin_drop'] = $this->language->get('column_total_margin_drop');
			$this->data['column_total'] = $this->language->get('column_total');

	}
		//bmv end



		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkoutf/shipping_methodd.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkoutf/shipping_methodd.tpl';
		} else {
			$this->template = 'default/template/checkoutf/shipping_methodd.tpl';
		}

		$this->response->setOutput($this->render());
  	}

	public function validate() {
		$this->language->load('checkoutf/checkout');

		$json = array();

		// Validate if shipping is required. If not the customer should not have reached this page.
		if (!$this->cart->hasShipping()) {
			$json['redirect'] = $this->url->link('checkoutf/checkout', '', 'SSL');
		}

		// Validate if shipping address has been set.
		$this->load->model('account/address');

		if ($this->customer->isLogged() && isset($this->session->data['shipping_address_id'])) {
			$shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);
		} elseif (isset($this->session->data['guest'])) {
			$shipping_address = $this->session->data['guest']['shipping'];
		}

		if (empty($shipping_address)) {
			$json['redirect'] = $this->url->link('checkoutf/checkout', '', 'SSL');
		}

		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkoutf/cart');
		}

		// Validate minimum quantity requirments.
		$products = $this->cart->getProducts();
		$customer_group = $this->customer->getCustomerGroupId();

		$product_iterator = 0;
		foreach ($products as $product) {
			$product_total = 0;
			$product_iterator += 1;
			if ($customer_group === 4 && $product['price'] > ((int) $_POST['price_drop_'. $product_iterator])) {
				$json['error']['warning'] = $this->language->get('error_markupdropshipping');
				break;
			}
			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}

			if ($product['minimum'] > $product_total) {
				$json['redirect'] = $this->url->link('checkoutf/cart');
				break;
			}
		}

		if (!empty($json)) {
			$this->response->setOutput(json_encode($json));
			return false;
		}

		// #carthybrid
		if (!NEOS_CART_OLD && $customer_group == 2) {
			$this->validateCustom($customer_group, $shipping_address);
			return false;
		}
		if (!isset($this->request->post['shipping_method'])) {
			$json['error']['warning'] = $this->language->get('error_shipping');
		} else {
			$shipping = explode('.', $this->request->post['shipping_method']);

			if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
				$json['error']['warning'] = $this->language->get('error_shipping');
			}
		}

		if (!$json) {
			$shipping = explode('.', $this->request->post['shipping_method']);

			if(isset($_POST['markupdropshipping'])) {
				if ($_POST['markupdropshipping'] == 'Слишком маленькая наценка' or $_POST['markupdropshipping'] == 'Заполните цены с ВАШЕЙ наценкой'){
					$json['error']['warning'] = $this->language->get('error_markupdropshipping');
				} else {
					$this->session->data['markupdropshipping']['cost'] = $_POST['markupdropshipping'];
				}
			}
			
			if(isset($_POST['passport-number'])) {
				if (strlen($_POST['passport-number']) < 6){
					$json['error']['warning'] = $this->language->get('error_passport-number');
				} 	
				$this->session->data['passport-number'] = $_POST['passport-number'];				
			}
			
			if(isset($_POST['passport-seria'])) {
				if (strlen($_POST['passport-seria']) < 4){
					$json['error']['warning'] = $this->language->get('error_passport-seria');
				} 	
				$this->session->data['passport-seria'] = $_POST['passport-seria'];
			}

			

			if(isset($_POST['prepayment'])) {
				$this->session->data['prepayment'] = $_POST['prepayment'];
			}
//			Можно закомментировать, если расчет наценки перенесен в корзину
			$pdnum = 1;
			unset($this->session->data['price_drop']);
			while (isset($_POST['price_drop_'.$pdnum])) {
				$this->session->data['price_drop'][] = $_POST['price_drop_'.$pdnum];
				$pdnum = $pdnum + 1;
			}

			if(isset($_POST['replacement_for'])) {
				$this->session->data['replacement_for'] = $_POST['replacement_for'];
			} else{
				$this->session->data['replacement_for'] = '';
			}

			if(isset($_POST['buybuysu_bc'])) {
				$this->session->data['buybuysu_bc'] = 1;
			} else{
				$this->session->data['buybuysu_bc'] = 0;
			}
			$quote = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
			if ($shipping[0] === 'cdek') {
				foreach (array('try_on', 'partial_delivery', 'inventory_inspection') as $key) {
					if (isset($this->request->post[$key])) {
						$quote[$key] = true;
					}
				}
			}
			$this->session->data['shipping_method'] = $quote;
			if (isset($_POST['shipping_cost'])) {
				$this->session->data['shipping_method']['cost'] = $_POST['shipping_cost'];
				$this->session->data['shipping_method']['text'] = $_POST['shipping_cost'] . " ք";
				//$this->session->data['prepayment'] = strip_tags($this->request->post['prepayment']);
				//$this->session->data['shipping_method']['prepayment'] = $_POST['prepayment'];
				if($shipping[0] == 'free'){$this->session->data['shipping_method']['title'] ='Полная предоплата';}
				if($shipping[0] == 'item'){$this->session->data['shipping_method']['title'] ='Один товар БЕЗ предоплаты';}
				if($shipping[0] == 'item2'){$this->session->data['shipping_method']['title'] ='Частичная предоплата';}
				if($shipping[0] == 'item3'){$this->session->data['shipping_method']['title'] ='Полная предоплата (первый класс)';}
				if($shipping[0] == 'item4'){$this->session->data['shipping_method']['title'] ='Частичная предоплата (первый класс)';}
				if($shipping[0] == 'item5'){$this->session->data['shipping_method']['title'] ='Полная предоплата';}
				if($shipping[0] == 'emssng'){$this->session->data['shipping_method']['title'] ='Доставка службой EMS';}
			}

			$this->session->data['comment'] = strip_tags($this->request->post['comment']);
		}
		$this->response->setOutput(json_encode($json));
	}

	private function loadCustom($customer_group) {
		switch($customer_group) {
			case 2:
				if ($this->customer->isLogged() && isset($this->session->data['shipping_address_id'])) {
					$shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);
				} elseif (isset($this->session->data['guest'])) {
					$shipping_address = $this->session->data['guest']['shipping'];
				}


				$this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
				$this->data['text_comments'] = $this->language->get('text_comments');
				$this->data['text_prepayment'] = $this->language->get('text_prepayment');
				$this->data['text_payment'] = $this->language->get('text_payment');
				$this->data['text_delivery'] = $this->language->get('text_delivery');
				$this->data['text_dcost'] = $this->language->get('text_dcost');
				$this->data['text_fullcost'] = $this->language->get('text_fullcost');
				$this->data['text_choose'] = $this->language->get('text_choose');

				$this->data['button_continue'] = $this->language->get('button_continue');


				$this->load->model('checkout/delivery');
				$total_data = array();
				$total = 0;
				$taxes = $this->cart->getTaxes();

				$this->load->model('setting/extension');

				$sort_order = array();

				$results = $this->model_setting_extension->getExtensions('total');

				foreach ($results as $key => $value) {
					$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
				}

				array_multisort($sort_order, SORT_ASC, $results);

				foreach ($results as $result) {
					if ($this->config->get($result['code'] . '_status')) {
						$this->load->model('total/' . $result['code']);

						$this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
					}
				}

				$sort_order = array();

				foreach ($total_data as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}

				array_multisort($sort_order, SORT_ASC, $total_data);
				//Set params for get delivery 
				$delivery_city = '';
				$delivery_zone = '';

				if (empty($shipping_address['delivery_cities_id']) && 
					(!isset($this->session->data['city']) || !isset($this->session->data['zone']))) {
					//get delivery id
					$shipping_address['delivery_cities_id'] = $this->model_checkout_delivery->getDeliveryCityId($shipping_address);
					//Обновляем адрес
					if ($shipping_address['delivery_cities_id'] != 0) {
						$this->model_account_address->editAddress(
								$shipping_address['address_id'], 
								$shipping_address
							);
					}
				} else if (isset($this->session->data['city']) && isset($this->session->data['zone'])) {
					$delivery_city = $this->session->data['city'];
					$delivery_zone = $this->session->data['zone'];
				}

				$this->data['shipping_methods'] = $this->model_checkout_delivery->getDelivery(
					$delivery_city, 
					$delivery_zone, 
					$total_data[0]['value'], 
					(isset($shipping_address['delivery_cities_id'])) ? $shipping_address['delivery_cities_id'] : 0
				);
				$this->data['cost_val'] = $total_data[0]['value'];
				$delivery_way = $this->request->get['delivery-way'];
				if ($delivery_way !== null) {
					$this->data['delivery_way'] = (int) $delivery_way;
				} else if (isset($this->session->data['deliverytype'])) {
					$this->data['shipping_methods'][$this->session->data['deliverytype']]['selected'] = true;
					$this->data['delivery_way'] = $this->session->data['deliverytype'];
				}

				if (isset($this->session->data['prepayment'])) {
					$this->data['prepayment'] = $this->session->data['prepayment'];
				} else {
					$this->data['prepayment'] = '';
				}

				if (isset($this->session->data['passport-seria'])) {
					$this->data['passport-seria'] = $this->session->data['passport-seria'];
				} else {
					$this->data['passport-seria'] = '';
				}

				if (isset($this->session->data['passport-number'])) {
					$this->data['passport-number'] = $this->session->data['passport-number'];
				} else {
					$this->data['passport-number'] = '';
				}

				if (isset($this->session->data['replacement_for'])) {					
					$this->data['replacement_for'] = $this->session->data['replacement_for'];
				} else{
					$this->data['replacement_for'] = '';
				}

				if (isset($this->session->data['shipping_method']['code'])) {
					$this->data['code'] = $this->session->data['shipping_method']['code'];
				} else {
					$this->data['code'] = '';
				}

				if (isset($this->session->data['comment'])) {
					$this->data['comment'] = $this->session->data['comment'];
				} else {
					$this->data['comment'] = '';
				}


				if (!$json) {
					$this->data['column_name'] = $this->language->get('column_name');
					$this->data['column_sku'] = $this->language->get('column_sku');
					$this->data['column_quantity'] = $this->language->get('column_quantity');
					$this->data['column_price'] = $this->language->get('column_price');
					$this->data['column_price_drop'] = $this->language->get('column_price_drop');
					$this->data['column_total_drop'] = $this->language->get('column_total_drop');
					$this->data['column_margin_drop'] = $this->language->get('column_margin_drop');
					$this->data['column_total_margin_drop'] = $this->language->get('column_total_margin_drop');
					$this->data['column_total'] = $this->language->get('column_total');

				}

				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkoutf/shipping_method_new.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/checkoutf/shipping_method_new.tpl';
				} else {
					$this->template = 'default/template/checkout/shipping_method.tpl';
				}
			break;
		}
		$this->response->setOutput($this->render());
	}

	private function validateCustom($customer_group, $shipping_address) {
		$json = array();
		switch($customer_group) {
			case 2:
			//Способ доставки
			if (NEOS_CART_HYBRID && isset($this->request->post['delivery-way'])) {
				$this->request->post['deliverytype'] = $this->request->post['delivery-way'];
			}
			if (!isset($this->request->post['deliverytype'])) {
				$json['error']['warning'] = $this->language->get('error_shipping');
				unset($this->session->data['deliverytype']);
			}
			// Предоплата
			if (!NEOS_CART_HYBRID && empty($this->request->post['prepayment'])) {
				$json['error']['warning'] = $this->language->get('error_prepayment');
			}
			// Обмен
			if (!empty($this->request->post['replacement_for'])) {
				$result = $this->db->query(
					"SELECT `order_id` FROM `oc_order` WHERE `order_id` = " . (int) $this->request->post['replacement_for']
				);
				if ($result->num_rows == 0) {
					$json['error']['warning'] = $this->language->get('error_replacement_for_order_404');
				} else {
					$this->session->data['replacement_for'] = (int) $this->request->post['replacement_for'];					
				}
			} else{
				$this->session->data['replacement_for'] = '';
			}
			$deliverytype = $this->session->data['deliverytype'] = (int) $this->request->post['deliverytype'];
			$this->load->model('checkout/delivery');
			$total_data = array();
			$total = 0;
			$taxes = $this->cart->getTaxes();

			$this->load->model('setting/extension');

			$sort_order = array();

			$results = $this->model_setting_extension->getExtensions('total');

			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
			}

			array_multisort($sort_order, SORT_ASC, $results);

			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('total/' . $result['code']);

					$this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
				}
			}

			$sort_order = array();

			foreach ($total_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $total_data);
			//Set params for get delivery 
			$delivery_city = '';
			$delivery_zone = '';

			if (empty($shipping_address['delivery_cities_id']) && 
				(!isset($this->session->data['city']) || !isset($this->session->data['zone']))) {
				//get delivery id
				$shipping_address['delivery_cities_id'] = $this->model_checkout_delivery->getDeliveryCityId($shipping_address);
				//Обновляем адрес
				if ($shipping_address['delivery_cities_id'] != 0) {
					$this->model_account_address->editAddress(
							$shipping_address['address_id'], 
							$shipping_address
						);
				}
			} else if (isset($this->session->data['city']) && isset($this->session->data['zone'])) {
				$delivery_city = $this->session->data['city'];
				$delivery_zone = $this->session->data['zone'];
			}
			$delivery_city_id = (isset($shipping_address['delivery_cities_id'])) ? $shipping_address['delivery_cities_id'] : 0;
			$shipping_methods = $this->model_checkout_delivery->getDelivery(
					$delivery_city, 
					$delivery_zone, 
					$total_data[0]['value'], 
					$delivery_city_id 
				);
			$prepayment = $this->model_checkout_delivery->getPrepayment(
				$delivery_city, 
				$delivery_zone, 
				$total_data[0]['value'], 
				$deliverytype,
				$shipping_methods[$deliverytype]['fullcost'],
				$delivery_city_id 
			);

			if (!NEOS_CART_HYBRID && (int) $prepayment > (int) $this->request->post['prepayment']) {
				$json['error']['warning'] = $this->language->get('error_prepayment') 
											. " Предоплата должна быть не менее: $prepayment руб.";
			}
			
			if (NEOS_CART_HYBRID && isset($this->request->post['comment'])) {
				$this->session->data['comment'] = strip_tags($this->request->post['comment']);
			}

			if (!$json) {
				$this->session->data['shipping_method']['cost'] = $shipping_methods[$deliverytype]['dcost'];
				$this->session->data['prepayment'] = $this->request->post['prepayment'];
          		$this->session->data['shipping_method']['title'] = $this->model_checkout_delivery->getShippingMethod(
					  $delivery_city, 
					  $delivery_zone, 
					  $total,
					  $deliverytype,
					  $delivery_city_id);
          		$this->session->data['shipping_method']['code'] = $this->model_checkout_delivery->getShippingCode(
					  $delivery_city, 
					  $delivery_zone, 
					  $total,
					  $deliverytype,
					  $delivery_city_id);
				$this->session->data['payment_method']['title'] = $this->model_checkout_delivery->getPaymentMethod(
					  $delivery_city, 
					  $delivery_zone, 
					  $total,
					  $deliverytype,
					  $delivery_city_id);
			    $this->session->data['payment_method']['code'] = $this->model_checkout_delivery->getPaymentCode(
					  $delivery_city, 
					  $delivery_zone, 
					  $total,
					  $deliverytype,
					  $delivery_city_id);
       		}
			
			break;
		}
		$this->response->setOutput(json_encode($json));
	}
} 
?>
