<?php
class ControllerCheckoutShippingMethod extends Controller {
	public function index()
	{
		$this->language->load('checkout/checkout');
		$this->data['customer_group_id'] = $this->customer->getCustomerGroupId();
		$this->load->model('account/address');
		// NEOS - REMOVED DEFAULT OPENCART LOGIC FOR SHIPPING ADDRESS
		$this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
		$this->data['text_comments'] = $this->language->get('text_comments');
		$this->data['text_prepayment'] = $this->language->get('text_prepayment');
		$this->data['text_payment'] = $this->language->get('text_payment');
		$this->data['text_delivery'] = $this->language->get('text_delivery');
		$this->data['text_dcost'] = $this->language->get('text_dcost');
		$this->data['text_fullcost'] = $this->language->get('text_fullcost');
		$this->data['text_choose'] = $this->language->get('text_choose');

		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['error_warning'] = '';
		$this->data['shipping_methods'] = array();
		list($total, $total_data) = $this->getTotals();
		$this->load->model('checkout/shipping');
		if (isset($this->session->data['postcode'])) {
			$this->data['shipping_methods'] = $this->model_checkout_shipping->getCustomDelivery(
				$total_data,
				$this->session->data['postcode'],
				array(
					'city' => $this->session->data['city'],
					'zone' => $this->session->data['zone']
				)
			);
		}
		$this->data = array_merge($this->data, $this->model_checkout_shipping->templateData());
		if (count($this->data['shipping_methods']) === 0) {
			$this->data['error_warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
		}
		$this->data['cost_val'] = $this->currency->format(
			$this->model_checkout_shipping->getSumOrderFromTotals($total_data)
		);
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
		// bmv begin 
		// NEOS - пояснение к чужому коду
		// это не используется в текущем шаблоне
		// TODO: remove
		$products = $this->cart->getProducts();
		foreach ($products as $product) {
			$product_total = 0;
			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}
			if ($product['minimum'] > $product_total) {
				$json['redirect'] = $this->url->link('checkout/cart');
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
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/shipping_method.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/shipping_method.tpl';
		} else {
			$this->template = 'default/template/checkout/shipping_method.tpl';
		}
		$this->response->setOutput($this->render());
  	}

	public function validate() {
		$this->language->load('checkout/checkout');

		$json = array();

		// Validate if shipping is required. If not the customer should not have reached this page.
		if (!$this->cart->hasShipping()) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
		}

		// Validate if shipping address has been set.
		$this->load->model('account/address');

		if ($this->customer->isLogged() && isset($this->session->data['shipping_address_id'])) {
			$shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);
		} elseif (isset($this->session->data['guest'])) {
			$shipping_address = $this->session->data['guest']['shipping'];
		}

		if (empty($shipping_address)) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
		}

		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');
		}

		// Validate minimum quantity requirments.
		$products = $this->cart->getProducts();

		foreach ($products as $product) {
			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}

			if ($product['minimum'] > $product_total) {
				$json['redirect'] = $this->url->link('checkout/cart');

				break;
			}
		}

		if (!$json) {
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

				if(isset($_POST['prepayment'])) {
                    $this->session->data['prepayment'] = $_POST['prepayment'];
                }

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

				if(isset($_POST['shipping_cost'])) {
					$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
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

				} else {
					$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
				}
				$this->session->data['comment'] = strip_tags($this->request->post['comment']);
			}

		}
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Get delivery cost for default user
	 */
	public function deliveryCostTable()
	{
		$shipping_method = $this->request->post['shipping_method'];
		$options = array(
			'has_dressing_room' => $this->request->post['has_dressing_room'] === "true" ? true : false
		);
		$this->load->model('checkout/shipping');
		if (empty($shipping_method)) {
			unset($this->session->data['shipping_method']);
		}
		list($deliverycost, $fullcost) = $this->model_checkout_shipping->calculateDeliveryCostForDefaultCustomer(
			$shipping_method,
			true,
			$options
		);
		list($total, $total_data) = $this->getTotals();
		$this->data['cost_val'] = $this->currency->format(
			$this->model_checkout_shipping->getSumOrderFromTotals($total_data)
		);
		$this->data['deliverycost'] = $deliverycost;
		$this->data['fullcost'] = $fullcost;
		$this->template = $this->config->get('config_template') . '/template/checkout/delivery_cost_table.tpl';
		$this->response->setOutput($this->render());
	}

	/**
	 * Get cdek warehouses
	 */
	public function cdekWarehouses()
	{
		$this->load->model('shipping/customcdek');
		$list = array();
		$options = array(
			'has_dressing_room' => $this->request->get['has_dressing_room'] !== null ? true : false,
			'cash_on_delivery' => isset($this->session->data['shipping_method']['payment_type'])
				&& $this->session->data['shipping_method']['payment_type'] === 'part' ? true : false
		);
		if (isset($this->session->data['postcode'])) {
			$list = $this->model_shipping_customcdek->getWarehouseList($this->session->data['postcode'], $options);
		}
		$this->response->setOutput(json_encode($list));
	}

	/**
	 * Add warehouse to shipping method
	 *
	 * @return void
	 */
	public function addCdekWarehouseToShippingMethod()
	{
		$this->load->model('shipping/customcdek');
		$warehouse_code = $this->request->get['warehouse_code'];
		if (isset($this->session->data['postcode'])) {
			$list = $this->model_shipping_customcdek->getWarehouseList($this->session->data['postcode'], $options);
		}
		$success = false;
		$text = ' <b>Пункт выдачи: </b>';
		foreach ($list as $warehouse) {
			if ($warehouse['code'] === $warehouse_code && isset($this->session->data['shipping_method'])) {
				$title = $this->session->data['shipping_method']['title'];
				$position = mb_strpos($title, $text, 0, 'UTF-8');
				if ($position !== false) {
					$title = mb_substr($title, 0, $position);
				}
				$this->session->data['shipping_method']['title'] = $title . $text . $warehouse['address'];
				$this->session->data['shipping_method']['warehouse_code'] = $warehouse['code'];
				if (!empty($warehouse['city_id'])) {
					$this->session->data['shipping_method']['cdek_city_id'] = $warehouse['city_id'];
				}
				$success = true;
				break;
			}
		}
		$this->response->setOutput(json_encode(array('success' => $success)));
	}

	/**
	 * @return array $total, $total_data
	 */
	protected function getTotals()
	{
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

		return array($total, $total_data);
	}
}
