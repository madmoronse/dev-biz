<?php
class ControllerCheckoutConfirm extends Controller {
	public function index() {
		$redirect = '';
		if ($this->cart->hasShipping()) {
			// Validate if shipping address has been set.
			$this->load->model('account/address');
			
			// Validate if shipping method has been set.
			if (!isset($this->session->data['shipping_method'])) {
				$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
			}
		} else {
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
		}

		// Validate if payment address has been set.
		$this->load->model('account/address');

		if (!$this->customer->isLogged()) {
			$json['redirect'] = $this->url->link('checkout/cart');
		}

		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');
		}

		if ($json['redirect']) {
			$this->response->setOutput(json_encode($json));
			return;
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
				$redirect = $this->url->link('checkout/cart');
				break;
			}
		}
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

			$this->language->load('checkout/checkout');

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
			} else {
				$data['customer_id'] = 0;
				$data['customer_group_id'] = 1;
				$data['firstname'] = $this->session->data['firstname'];
				$data['middlename'] = $this->session->data['middlename'];
				$data['lastname'] = $this->session->data['lastname'];
				$data['email'] = $this->session->data['email'];
				$data['telephone'] = $this->session->data['telephone'];
				$data['fax'] = $this->session->data['guest']['fax'];

				$payment_address = $this->session->data;
			}
			$payment_address = $this->session->data;
			$data['firstname'] = ($data['firstname'] == "") ? $this->session->data['firstname'] : $data['firstname'];
			$data['middlename'] = ($data['middlename'] == "") ? $this->session->data['middlename'] : $data['middlename'];
			$data['lastname'] = ($data['lastname'] == "") ? $this->session->data['lastname'] : $data['lastname'];
			$data['payment_firstname'] = $data['firstname'];
			$data['payment_middlename'] = $data['middlename'];
			$data['payment_lastname'] = $data['lastname'];
			$data['payment_company'] = "";//$payment_address['company'];
			$data['payment_company_id'] = "";//$payment_address['company_id'];
			$data['payment_tax_id'] = "";// $payment_address['tax_id'];
			$data['payment_address_1'] = $payment_address['address_1'];
			$data['payment_address_2'] = $payment_address['address_2'];
			$data['payment_address_3'] = $payment_address['address_3'];
			$data['payment_address_4'] = $payment_address['address_4'];
			$data['payment_naselenniy_punkt'] = $payment_address['payment_naselenniy_punkt'];
			$data['payment_naselenniy_punkt_id'] = $payment_address['payment_naselenniy_punkt_id'];
			$data['payment_city'] = $payment_address['payment_city'];
			$data['payment_postcode'] = $payment_address['postcode'];
			$data['payment_zone'] = $payment_address['payment_zone'];
			$data['payment_zone_id'] = $payment_address['payment_zone_id'];
			$data['payment_country'] = $payment_address['payment_country'];
			$data['payment_country_id'] = $payment_address['payment_country_id'];
			$data['payment_address_format'] = "";//$payment_address['address_format'];
			// Раньше эти значения (payment_method, payment_code) были захардкожены в модель доставки
			// так как доставка считается через API - прописали эти значения тут
			$data['payment_method'] = 'Стандартная оплата';
			$data['payment_code'] = 'cod';
        	$shipping_address = $this->session->data;
			$data['shipping_address_id'] = $this->session->data['shipping_address_id'];
			$data['shipping_firstname'] = $data['firstname'];
			$data['shipping_middlename'] = $data['middlename'];
			$data['shipping_lastname'] = $data['lastname'];
			$data['shipping_company'] = $shipping_address['company'];
			$data['shipping_address_1'] = $shipping_address['address_1'];
			$data['shipping_address_2'] = $shipping_address['address_2'];
			$data['shipping_address_3'] = $shipping_address['address_3'];
			$data['shipping_address_4'] = $shipping_address['address_4'];
			$data['shipping_naselenniy_punkt'] = $shipping_address['shipping_naselenniy_punkt'];
			$data['shipping_naselenniy_punkt_id'] = $shipping_address['shipping_naselenniy_punkt_id'];
			$data['shipping_city'] = $shipping_address['shipping_city'];
			$data['shipping_postcode'] = $shipping_address['postcode'];
			$data['shipping_zone'] = $shipping_address['shipping_zone'];
			$data['shipping_zone_id'] = $shipping_address['shipping_zone_id'];
			$data['shipping_country'] = $shipping_address['shipping_country'];
			$data['shipping_country_id'] = $shipping_address['shipping_country_id'];
			$data['shipping_address_format'] = $shipping_address['address_format'];
        	$data['telephone'] = $shipping_address['telephone'];
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
			if (isset($this->session->data['shipping_method']['try_on'])) {
				$data['try_on'] = $this->session->data['shipping_method']['try_on'];
			}
			if (isset($this->session->data['shipping_method']['cdek_city_id'])) {
				$data['cdek_city_id'] = $this->session->data['shipping_method']['cdek_city_id'];
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

				/** By Neos - Set price for gift - Start */
				$this->load->model('checkout/gifts');
				if ($this->model_checkout_gifts->inGifts($product['key'])) {
					$product['price'] = 0;
					$product['total'] = 0;
				}
				/** By Neos - Set price for gift - End */
				if (isset($this->session->data['price_drop'])) {
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
					$pcount = $pcount + 1;
				} else {
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
			} elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
				$data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
			} else {
				$data['forwarded_ip'] = $this->request->server['REMOTE_ADDR'];
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
			$this->load->model('checkout/shipping');
			$shipping_method = $this->model_checkout_shipping->getSavedShippingMethodForDefaultCustomer();
			if (is_array($shipping_method)) {
				$data['prepayment'] = $shipping_method['prepayment'];

			}
			if ($data['prepayment']) {
				if ($data['prepayment'] < $total) {
					$data['cash_on_delivery'] = round($total - $data['prepayment']);
				} else {
					$data['cash_on_delivery'] = 0;
				}
				$this->session->data['prepayment'] = $data['prepayment'];
				$prepay =  "Предоплата ".$data['prepayment']." рублей.";
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

			/** By Neos - Clear User Credentials From session - Start */
			unset(
				$this->session->data['firstname'],
				$this->session->data['lastname'],
				$this->session->data['middlename'],
				$this->session->data['email'],
				$this->session->data['telephone'],
				$this->session->data['postcode']
			);
			/** By Neos - Clear User Credentials From session - End */
      		$this->model_checkout_order->confirm(
				  $this->session->data['order_id'],
				  $this->config->get('cod_order_status_id')
			);
		
			// By Neos
			$this->model_checkout_gifts->clearGifts();
			$this->session->data['payment_method']['code'] = $data['payment_code'];

		} else {
			$json['redirect'] = $redirect;
		}
		$this->response->setOutput($json);
  	}

	public function add_image() {
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

	public function rem_image() {
		exit('Disallowed');
		unlink($_POST["src_image"]);
	}
}
?>
