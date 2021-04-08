<?php
class ControllerCheckoutfConfirm extends Controller {
	public function index() {
		$redirect = $this->validateConfirm();
		$this->data['customer_group_id'] = $this->customer->getCustomerGroupId();

		if (!$redirect) {
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
	
			$this->language->load('checkoutf/checkout');
			if ($this->cart->hasShipping()) {
				if ($this->customer->isLogged()) {
					$this->load->model('account/address');
	
					$shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);
				} elseif (isset($this->session->data['guest'])) {
					$shipping_address = $this->session->data['guest']['shipping'];
				}
				//для вывода адреса в подтверждения
				$this->data['shipping_address'] = $shipping_address;
			}

			$this->data['column_name'] = $this->language->get('column_name');
			$this->data['column_sku'] = $this->language->get('column_sku');
			$this->data['column_quantity'] = $this->language->get('column_quantity');
			$this->data['column_price'] = $this->language->get('column_price');
			$this->data['column_total_drop'] = $this->language->get('column_total_drop');
			$this->data['column_margin_drop'] = $this->language->get('column_margin_drop');
			$this->data['column_total'] = $this->language->get('column_total');

			$this->data['products'] = array();
			$pcount=0;

			$this->load->model('tool/image');
			$this->load->model('catalog/product');
			foreach ($this->cart->getProducts() as $product) {

			$current_product_info = $this->model_catalog_product->getProduct($product['product_id']);

				if ($product['image']) {
					$image = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height'));
				} else {
					$image = '';
				}

				$option_data = array();

				foreach ($product['option'] as $option) {
					if ($option['type'] != 'file') {
						$value = $option['option_value'];
					} else {
						$filename = $this->encryption->decrypt($option['option_value']);

						$value = utf8_substr($filename, 0, utf8_strrpos($filename, '.'));
					}

					$option_data[] = array(
						'name'  => $option['name'],
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
					);
				}
	 			//BMV begin

				if (isset($this->session->data['price_drop'])){

					$this->data['products'][] = array(
							'product_id' => $product['product_id'],
							'name'       => $current_product_info['manufacturer'] . ' ' . $product['name'],

							'thumb'    	=> $image,
							'model'      => $product['model'],
							'option'     => $option_data,
							'quantity'   => $product['quantity'],
							'subtract'   => $product['subtract'],
							'price'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))),
							'total'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']),
							'href'       => $this->url->link('product/product', 'product_id=' . $product['product_id']),
							'price_drop' => $this->session->data['price_drop'][$pcount],
							'price_markup' => $this->session->data['price_drop'][$pcount] - $product['price']
					);

					$pcount=$pcount+1;
				} else{

					$this->data['products'][] = array(
							'product_id' => $product['product_id'],
							'name'       => $current_product_info['manufacturer'] . ' ' . $product['name'],
							'thumb'    	=> $image,
							'model'      => $product['model'],
							'option'     => $option_data,
							'quantity'   => $product['quantity'],
							'subtract'   => $product['subtract'],
							'price'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))),
							'total'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']),
							'href'       => $this->url->link('product/product', 'product_id=' . $product['product_id'])
					);

				}

				//BMV end

			}
			// Gift Voucher
			$this->data['vouchers'] = array();

			if (!empty($this->session->data['vouchers'])) {
				foreach ($this->session->data['vouchers'] as $voucher) {
					$this->data['vouchers'][] = array(
						'description' => $voucher['description'],
						'amount'      => $this->currency->format($voucher['amount'])
					);
				}
			}
			if (isset($this->session->data['prepayment'])) {
				$data['cash_on_delivery'] = round($total - $this->session->data['prepayment']);
				$data['prepayment'] = (int)$this->session->data['prepayment'];
			} else {
				$data['cash_on_delivery'] = $total;
			}
	
			$this->session->data['cash_on_delivery'] = $data['cash_on_delivery'];

			$this->data['totals'] = $total_data;
			$this->data['payment'] = $this->getChild('payment/' . $this->session->data['payment_method']['code']);
			unset($this->session->data['order_cheque_images']);
		} else {
			$this->data['redirect'] = $redirect;
		}
		$this->chooseTemplate();
		$this->response->setOutput($this->render());
  	}

	public function DEPRECATED_add_image() {
		exit('Disallowed');
		$uploaddir = './uploads/uploads/' . $_POST['order_id'] . '/';
		if(!is_dir($uploaddir)) {mkdir($uploaddir, 0755, true) ;}

		$tmp_name = explode(".", $_FILES['uploadfile']['name']);

		$extension = end($tmp_name);

		$file_name = uniqid() . "." .  $extension;

		$file = $uploaddir . basename($file_name);

		if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $file)) {
			echo $file_name;

			$this->db->query("INSERT INTO " . DB_PREFIX . "order_image SET order_id = '" . $_POST['order_id'] . "',  image = '" . $this->config->get('config_url') . 'uploads/uploads/' . $_POST['order_id'] . '/' . $file_name . "'");

		} else {
			echo "error";
		}

	}

	public function add_image() {
		$uploaddir = './uploads/tmp_dir/';
		if(!is_dir($uploaddir)) {mkdir($uploaddir, 0755, true) ;}

		$tmp_name = explode(".", $_FILES['uploadfile']['name']);
		$extension = end($tmp_name);
		if (!preg_match('/jpg$|jpeg$|png$|pdf$/', $extension)) {
			exit('error');
		}
		$file_name = uniqid() . "." .  $extension;

		$file = $uploaddir . basename($file_name);

		if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $file)) {
			echo $file_name;
			$this->session->data['order_cheque_images'][] = $file;
		} else {
			echo "error";
		}
	}

	public function rem_image() {
		$basename = basename($_POST["src_image"]);
		$file = './uploads/tmp_dir/' . $basename;
		unlink($file);
		$index = array_search($file, $this->session->data['order_cheque_images']);
		if ($index !== false) {
			unset($this->session->data['order_cheque_images'][$index]);
		}
	}

	public function confirm()
	{
		$redirect = $this->validateConfirm();
		if ($redirect) {
			$this->response->setOutput(json_encode(array('success' => true, 'redirect' => $redirect)));
			return false;
		}

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

		$this->language->load('checkoutf/checkout');

		$data = array();

		$data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
		$data['store_id'] = $this->config->get('config_store_id');
		$data['store_name'] = $this->config->get('config_name');

		if ($data['store_id']) {
			$data['store_url'] = $this->config->get('config_url');
		} else {
			$data['store_url'] = HTTP_SERVER;
		}

		if ($this->customer->isLogged()) {
			$data['customer_id'] = $this->customer->getId();
			$data['customer_group_id'] = $this->customer->getCustomerGroupId();
			$data['firstname'] = $this->customer->getFirstName();
			$data['middlename'] = $this->customer->getMiddleName();
			$data['lastname'] = $this->customer->getLastName();
			$data['email'] = $this->customer->getEmail();
			$data['telephone'] = $this->customer->getTelephone();
			$data['fax'] = $this->customer->getFax();

			$this->load->model('account/address');

			$payment_address = $this->model_account_address->getAddress($this->session->data['payment_address_id']);
		} elseif (isset($this->session->data['guest'])) {
			$data['customer_id'] = 0;
			$data['customer_group_id'] = $this->session->data['guest']['customer_group_id'];
			$data['firstname'] = $this->session->data['guest']['firstname'];
			$data['middlename'] = $this->session->data['guest']['middlename'];
			$data['lastname'] = $this->session->data['guest']['lastname'];
			$data['email'] = $this->session->data['guest']['email'];
			$data['telephone'] = $this->session->data['guest']['telephone'];
			$data['fax'] = $this->session->data['guest']['fax'];

			$payment_address = $this->session->data['guest']['payment'];
		}

		$data['payment_firstname'] = $payment_address['firstname'];
		$data['payment_middlename'] = $payment_address['middlename'];
		$data['payment_lastname'] = $payment_address['lastname'];
		$data['payment_company'] = $payment_address['company'];
		$data['payment_company_id'] = $payment_address['company_id'];
		$data['payment_tax_id'] = $payment_address['tax_id'];
		$data['payment_address_1'] = $payment_address['address_1'];
		$data['payment_address_2'] = $payment_address['address_2'];
		$data['payment_address_3'] = $payment_address['address_3'];
		$data['payment_address_4'] = $payment_address['address_4'];
		$data['payment_naselenniy_punkt'] = $payment_address['naselenniy_punkt'];
		$data['payment_naselenniy_punkt_id'] = $payment_address['naselenniy_punkt_id'];
		$data['payment_city'] = $payment_address['city'];
		$data['payment_postcode'] = $payment_address['postcode'];
		$data['payment_zone'] = $payment_address['zone'];
		$data['payment_zone_id'] = $payment_address['zone_id'];
		$data['payment_country'] = $payment_address['country'];
		$data['payment_country_id'] = $payment_address['country_id'];
		$data['payment_address_format'] = $payment_address['address_format'];

		if (isset($this->session->data['payment_method']['title'])) {
			$data['payment_method'] = $this->session->data['payment_method']['title'];
		} else {
			$data['payment_method'] = '';
		}

		if (isset($this->session->data['payment_method']['code'])) {
			$data['payment_code'] = $this->session->data['payment_method']['code'];
		} else {
			$data['payment_code'] = '';
		}

		if ($this->cart->hasShipping()) {
			if ($this->customer->isLogged()) {
				$this->load->model('account/address');

				$shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);
			} elseif (isset($this->session->data['guest'])) {
				$shipping_address = $this->session->data['guest']['shipping'];
			}

			$data['shipping_address_id'] = $this->session->data['shipping_address_id'];
			$data['shipping_firstname'] = $shipping_address['firstname'];
			$data['shipping_middlename'] = $shipping_address['middlename'];
			$data['shipping_lastname'] = $shipping_address['lastname'];
			$data['shipping_company'] = $shipping_address['company'];
			$data['shipping_address_1'] = $shipping_address['address_1'];
			$data['shipping_address_2'] = $shipping_address['address_2'];
			$data['shipping_address_3'] = $shipping_address['address_3'];
			$data['shipping_address_4'] = $shipping_address['address_4'];
			$data['shipping_naselenniy_punkt'] = $shipping_address['naselenniy_punkt'];
			$data['shipping_naselenniy_punkt_id'] = $shipping_address['naselenniy_punkt_id'];
			$data['shipping_city'] = $shipping_address['city'];
			$data['shipping_postcode'] = $shipping_address['postcode'];
			$data['shipping_zone'] = $shipping_address['zone'];
			$data['shipping_zone_id'] = $shipping_address['zone_id'];
			$data['shipping_country'] = $shipping_address['country'];
			$data['shipping_country_id'] = $shipping_address['country_id'];
			$data['shipping_address_format'] = $shipping_address['address_format'];

			//для вывода адреса в подтверждения
			$this->data['shipping_address'] = $shipping_address;


			if ($data['customer_group_id'] == 2) {
				$data['telephone'] = $shipping_address['telephone'];
				$data['fax'] = $shipping_address['social'];
			} else
			{
			if ($shipping_address['telephone'] != '') {$data['telephone'] = $shipping_address['telephone'];}
			}

			if (isset($this->session->data['shipping_method']['title'])) {
				$data['shipping_method'] = $this->session->data['shipping_method']['title'];
			} else {
				$data['shipping_method'] = '';
			}

			if (isset($this->session->data['shipping_method']['code'])) {
				$data['shipping_code'] = $this->session->data['shipping_method']['code'];
			} else {
				$data['shipping_code'] = '';
			}

			if (isset($this->session->data['shipping_method']['warehouse_code'])) {
				$data['warehouse_code'] = $this->session->data['shipping_method']['warehouse_code'];
			} else {
				$data['warehouse_code'] = '';
			}
			foreach (array('try_on', 'partial_delivery', 'inventory_inspection', 'cdek_city_id') as $key) {
				if (isset($this->session->data['shipping_method'][$key])) {
					$data[$key] = $this->session->data['shipping_method'][$key];
				}
			}
		} else {
			$data['shipping_firstname'] = '';
			$data['shipping_middlename'] = '';
			$data['shipping_lastname'] = '';
			$data['shipping_company'] = '';
			$data['shipping_address_1'] = '';
			$data['shipping_address_2'] = '';
			$data['shipping_address_3'] = '';
			$data['shipping_address_4'] = '';
			$data['shipping_naselenniy_punkt'] = '';
			$data['shipping_naselenniy_punkt_id'] = '';
			$data['shipping_city'] = '';
			$data['shipping_postcode'] = '';
			$data['shipping_zone'] = '';
			$data['shipping_zone_id'] = '';
			$data['shipping_country'] = '';
			$data['shipping_country_id'] = '';
			$data['shipping_address_format'] = '';
			$data['shipping_method'] = '';
			$data['shipping_code'] = '';
			$data['warehouse_code'] = '';
		}

		$product_data = array();

		$pcount = 0;
		foreach ($this->cart->getProducts() as $product) {
			$option_data = array();

			foreach ($product['option'] as $option) {
				if ($option['type'] != 'file') {
					$value = $option['option_value'];
				} else {
					$value = $this->encryption->decrypt($option['option_value']);
				}

				$option_data[] = array(
					'product_option_id'       => $option['product_option_id'],
					'product_option_value_id' => $option['product_option_value_id'],
					'option_id'               => $option['option_id'],
					'option_value_id'         => $option['option_value_id'],
					'name'                    => $option['name'],
					'value'                   => $value,
					'type'                    => $option['type']
				);
			}


			if (isset($this->session->data['price_drop'])){


				$product_data[] = array(
					'product_id' => $product['product_id'],
					'name'       => $product['name'],
					'model'      => $product['model'],
					'option'     => $option_data,
					'download'   => $product['download'],
					'quantity'   => $product['quantity'],
					'subtract'   => $product['subtract'],
					'price'      => $product['price'],
					'total'      => $product['total'],
					'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
					'reward'     => $product['reward'],
					'price_drop' => $this->session->data['price_drop'][$pcount]
				);

				$pcount=$pcount+1;
			} else{

				$product_data[] = array(
						'product_id' => $product['product_id'],
						'name'       => $product['name'],
						'model'      => $product['model'],
						'option'     => $option_data,
						'download'   => $product['download'],
						'quantity'   => $product['quantity'],
						'subtract'   => $product['subtract'],
						'price'      => $product['price'],
						'total'      => $product['total'],
						'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
						'reward'     => $product['reward'],
						'price_drop' => 0
				);

			}

		}

		// Gift Voucher
		$voucher_data = array();

		if (!empty($this->session->data['vouchers'])) {
			foreach ($this->session->data['vouchers'] as $voucher) {
				$voucher_data[] = array(
					'description'      => $voucher['description'],
					'code'             => substr(md5(mt_rand()), 0, 10),
					'to_name'          => $voucher['to_name'],
					'to_email'         => $voucher['to_email'],
					'from_name'        => $voucher['from_name'],
					'from_email'       => $voucher['from_email'],
					'voucher_theme_id' => $voucher['voucher_theme_id'],
					'message'          => $voucher['message'],
					'amount'           => $voucher['amount']
				);
			}
		}
		$data['products'] = $product_data;
		$data['vouchers'] = $voucher_data;
		$data['totals'] = $total_data;
		
		if (isset($this->session->data['passport-seria']) and isset($this->session->data['passport-number'])){
			$comment = $this->session->data['comment'];
            $this->session->data['comment'] = 'Паспорт: ' . $this->session->data['passport-seria'] . ' ' . $this->session->data['passport-number'] . '
' . $comment;
		}

		$data['comment'] = $this->session->data['comment'];
		$data['total'] = $total;

		if (isset($this->request->cookie['tracking'])) {
			$this->load->model('affiliate/affiliate');

			$affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->cookie['tracking']);
			$subtotal = $this->cart->getSubTotal();

			if ($affiliate_info) {
				$data['affiliate_id'] = $affiliate_info['affiliate_id'];
				$data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
			} else {
				$data['affiliate_id'] = 0;
				$data['commission'] = 0;
			}
		} else {
			$data['affiliate_id'] = 0;
			$data['commission'] = 0;
		}

		$data['language_id'] = $this->config->get('config_language_id');
		$data['currency_id'] = $this->currency->getId();
		$data['currency_code'] = $this->currency->getCode();
		$data['currency_value'] = $this->currency->getValue($this->currency->getCode());
		$data['ip'] = $this->request->server['REMOTE_ADDR'];

		if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
			$data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
		} elseif(!empty($this->request->server['HTTP_CLIENT_IP'])) {
			$data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
		} else {
			$data['forwarded_ip'] = '';
		}

		if (isset($this->request->server['HTTP_USER_AGENT'])) {
			$data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
		} else {
			$data['user_agent'] = '';
		}

		if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
			$data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
		} else {
			$data['accept_language'] = '';
		}


		if (isset($this->session->data['prepayment'])) {
			$data['cash_on_delivery'] = round($total - $this->session->data['prepayment']);
			$data['prepayment'] = (int)$this->session->data['prepayment'];
		} else {
			$data['cash_on_delivery'] = $total;
		}

		if (isset($this->session->data['replacement_for'])) {
			$data['replacement_for'] = (int)$this->session->data['replacement_for'];
		} else {
			$data['replacement_for'] = 0;
		}


		if (isset($this->session->data['buybuysu_bc'])) {
			$data['buybuysu_bc'] = (int)$this->session->data['buybuysu_bc'];
		} else {
			$data['buybuysu_bc'] = 0;
		}

		$this->session->data['cash_on_delivery'] = $data['cash_on_delivery'];

		$this->load->model('checkout/order');

		$this->session->data['order_id'] = $this->model_checkout_order->addOrder($data);
		if ($this->session->data['order_id']) {
			$this->response->setOutput(json_encode(array('success' => true)));
			$this->saveImages();
		} else {
			$this->response->setOutput(json_encode(array('success' => false)));
		}
	}
	protected function saveImages()
	{
		if (!isset($this->session->data['order_cheque_images'])) {
			return false;
		}
		$filepath = './uploads/uploads/' . (int) $this->session->data['order_id'] . '/';
		if (!is_dir($filepath)) {
			mkdir($filepath, 0755, true);
		}
		foreach ($this->session->data['order_cheque_images'] as $file) {
			if (!file_exists($file)) {
				continue;
			}
			if (rename($file, $filepath . basename($file))) {
				$url = $this->config->get('config_url') . preg_replace('/^\.\//', '', $filepath) . basename($file);
				$query[]= "(" . (int) $this->session->data['order_id'] . ",'" . $url  . "')"; 
			}
		}

		if (count($query)) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_image (`order_id`, `image`) VALUES " . implode(',', $query));
		}

	}
	public function validateConfirm()
	{
		$redirect = '';
		if ($this->cart->hasShipping()) {
			// Validate if shipping address has been set.
			$this->load->model('account/address');

			if ($this->customer->isLogged() && isset($this->session->data['shipping_address_id'])) {
				$shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);
			} elseif (isset($this->session->data['guest'])) {
				$shipping_address = $this->session->data['guest']['shipping'];
			}

			if (empty($shipping_address)) {
				$redirect = $this->url->link('checkoutf/checkout', '', 'SSL');
			}

			// Validate if shipping method has been set.
			if (!isset($this->session->data['shipping_method'])) {
				$redirect = $this->url->link('checkoutf/checkout', '', 'SSL');
			}
		} else {
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
		}

		// Validate if payment address has been set.
		$this->load->model('account/address');

		if ($this->customer->isLogged() && isset($this->session->data['payment_address_id'])) {
			$payment_address = $this->model_account_address->getAddress($this->session->data['payment_address_id']);
		} elseif (isset($this->session->data['guest'])) {
			$payment_address = $this->session->data['guest']['payment'];
		}

		if (empty($payment_address)) {
			$redirect = $this->url->link('checkoutf/checkout', '', 'SSL');
		}

		// Validate if payment method has been set.
		if (!isset($this->session->data['payment_method'])) {
			$redirect = $this->url->link('checkoutf/checkout', '', 'SSL');
		}

		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$redirect = $this->url->link('checkoutf/cart');
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
				$redirect = $this->url->link('checkoutf/cart');

				break;
			}
		}
		
		return $redirect;
	}
	private function chooseTemplate() {
		$customer_group = $this->customer->getCustomerGroupId();
		if (NEOS_CART_HYBRID) $customer_group = 'default';
		$found = false;
		switch($customer_group) {
			case 2:
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkoutf/confirm_new.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/checkoutf/confirm_new.tpl';
					$found = true;
				} 
			break;
			default:
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkoutf/confirm.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/checkoutf/confirm.tpl';
					$found = true;
				} 
			break;
		}
		if (!$found) {
			$this->template = 'default/template/checkoutf/confirm.tpl';
		}

	}
}
?>
