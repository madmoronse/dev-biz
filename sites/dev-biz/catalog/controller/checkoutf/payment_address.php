<?php
class ControllerCheckoutfPaymentAddress extends Controller {
	public function index() {
		$this->language->load('checkoutf/checkout');
		$step = $this->request->get['step'];
		$customer_group = $this->customer->getCustomerGroupId();
		if ($customer_group == 2 && $step) {
			$this->loadStep($step, $customer_group);
			return false;
		}
		$this->data['text_address_existing'] = $this->language->get('text_address_existing');
		$this->data['text_address_new'] = $this->language->get('text_address_new');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');

		$this->data['entry_firstname'] = $this->language->get('entry_firstname');
		$this->data['entry_middlename'] = $this->language->get('entry_middlename');
		$this->data['entry_lastname'] = $this->language->get('entry_lastname');
		$this->data['entry_company'] = $this->language->get('entry_company');
		$this->data['entry_company_id'] = $this->language->get('entry_company_id');
		$this->data['entry_tax_id'] = $this->language->get('entry_tax_id');
		$this->data['entry_address_1'] = $this->language->get('entry_address_1');
		$this->data['entry_address_2'] = $this->language->get('entry_address_2');
		$this->data['entry_address_3'] = $this->language->get('entry_address_3');
		$this->data['entry_address_4'] = $this->language->get('entry_address_4');
		$this->data['entry_naselenniy_punkt'] = $this->language->get('entry_naselenniy_punkt');
		$this->data['entry_postcode'] = $this->language->get('entry_postcode');
		$this->data['entry_city'] = $this->language->get('entry_city');
		$this->data['entry_country'] = $this->language->get('entry_country');
		$this->data['entry_zone'] = $this->language->get('entry_zone');

		$this->data['button_continue'] = $this->language->get('button_continue');

		$this->data['init_geo_ip'] = false;
		if (!isset($this->session->data['payment_country_id']) && !isset($this->session->data['payment_zone_id'])) {
			$google_api_key = $this->config->get('config_google_api_key');
			if ($google_api_key) {
				$this->data['init_geo_ip'] = true;
				$this->data['google_api_key'] = $google_api_key;
			}
		}

		if (isset($this->session->data['payment_address_id'])) {
			$this->data['address_id'] = $this->session->data['payment_address_id'];
		} else {
			$this->data['address_id'] = $this->customer->getAddressId();
		}

		$this->data['addresses'] = array();

		$this->load->model('account/address');

		//$this->data['addresses'] = $this->model_account_address->getAddresses();
		$this->data['addresses'] = $this->model_account_address->getPaymentAddresses();

		$this->load->model('account/customer_group');

		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($this->customer->getCustomerGroupId());

		if ($customer_group_info) {
			$this->data['company_id_display'] = $customer_group_info['company_id_display'];
		} else {
			$this->data['company_id_display'] = '';
		}

		if ($customer_group_info) {
			$this->data['company_id_required'] = $customer_group_info['company_id_required'];
		} else {
			$this->data['company_id_required'] = '';
		}

		if ($customer_group_info) {
			$this->data['tax_id_display'] = $customer_group_info['tax_id_display'];
		} else {
			$this->data['tax_id_display'] = '';
		}

		if ($customer_group_info) {
			$this->data['tax_id_required'] = $customer_group_info['tax_id_required'];
		} else {
			$this->data['tax_id_required'] = '';
		}

		if (isset($this->session->data['payment_country_id'])) {
			$this->data['country_id'] = $this->session->data['payment_country_id'];
		} else {
			$this->data['country_id'] = $this->config->get('config_country_id');
		}

		if (isset($this->session->data['payment_zone_id'])) {
			$this->data['zone_id'] = $this->session->data['payment_zone_id'];
		} else {
			$this->data['zone_id'] = '';
		}

		$this->load->model('localisation/country');

		$this->data['countries'] = $this->model_localisation_country->getCountries();

		$this->data['naselenniy_punkts'] = $this->model_localisation_country->getNaselenniyPunkts();

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkoutf/payment_address.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkoutf/payment_address.tpl';
		} else {
			$this->template = 'default/template/checkoutf/payment_address.tpl';
		}

		$this->response->setOutput($this->render());
  	}

	public function validate() {
		$this->language->load('checkoutf/checkout');

		$json = array();
		
		// Validate if customer is logged in.
		if (!$this->customer->isLogged()) {
			$json['redirect'] = $this->url->link('checkoutf/checkout', '', 'SSL');
		}		
		
		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkoutf/cart');
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
				$json['redirect'] = $this->url->link('checkoutf/cart');

				break;
			}
		}
		if (!empty($json)) {
			$this->response->setOutput(json_encode($json));
			return false;
		}
		$step = $this->request->get['step'];
		$customer_group = $this->customer->getCustomerGroupId();
		if ($customer_group == 2 && $step) {
			$this->validateStep($step, $customer_group);
			return false;
		}
		if (isset($this->request->post['payment_address']) && $this->request->post['payment_address'] == 'existing') {
			$this->load->model('account/address');

			if (empty($this->request->post['address_id'])) {
				$json['error']['warning'] = $this->language->get('error_address');
			} elseif (!in_array($this->request->post['address_id'], array_keys($this->model_account_address->getAddresses()))) {
				$json['error']['warning'] = $this->language->get('error_address');
			} else {
				// Default Payment Address
				$this->load->model('account/address');

				$address_info = $this->model_account_address->getAddress($this->request->post['address_id']);

				if ($address_info) {
					$this->load->model('account/customer_group');

					$customer_group_info = $this->model_account_customer_group->getCustomerGroup($this->customer->getCustomerGroupId());

					// Company ID
					if ($customer_group_info['company_id_display'] && $customer_group_info['company_id_required'] && !$address_info['company_id']) {
						$json['error']['warning'] = $this->language->get('error_company_id');
					}

					// Tax ID
					if ($customer_group_info['tax_id_display'] && $customer_group_info['tax_id_required'] && !$address_info['tax_id']) {
						$json['error']['warning'] = $this->language->get('error_tax_id');
					}
				}
			}

			if (!$json) {
				$this->session->data['payment_address_id'] = $this->request->post['address_id'];

				if ($address_info) {
					$this->session->data['payment_country_id'] = $address_info['country_id'];
					$this->session->data['payment_zone_id'] = $address_info['zone_id'];
				} else {
					unset($this->session->data['payment_country_id']);
					unset($this->session->data['payment_zone_id']);
				}

				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);
			}
		} else {
			if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
				$json['error']['firstname'] = $this->language->get('error_firstname');
			}
			if ((utf8_strlen($this->request->post['middlename']) < 1) || (utf8_strlen($this->request->post['middlename']) > 32)) {
				$json['error']['middlename'] = $this->language->get('error_middlename');
			}
			if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
				$json['error']['lastname'] = $this->language->get('error_lastname');
			}

			// Customer Group
			$this->load->model('account/customer_group');

			$customer_group_info = $this->model_account_customer_group->getCustomerGroup($this->customer->getCustomerGroupId());

			if ($customer_group_info) {
				// Company ID
				if ($customer_group_info['company_id_display'] && $customer_group_info['company_id_required'] && empty($this->request->post['company_id'])) {
					$json['error']['company_id'] = $this->language->get('error_company_id');
				}

				// Tax ID
				if ($customer_group_info['tax_id_display'] && $customer_group_info['tax_id_required'] && empty($this->request->post['tax_id'])) {
					$json['error']['tax_id'] = $this->language->get('error_tax_id');
				}
			}

			if ((utf8_strlen($this->request->post['address_1']) < 1) || (utf8_strlen($this->request->post['address_1']) > 128)) {
				$json['error']['address_1'] = $this->language->get('error_address_1');
			}

			if ((utf8_strlen($this->request->post['address_2']) < 1) || (utf8_strlen($this->request->post['address_2']) > 128)) {
				$json['error']['address_2'] = $this->language->get('error_address_2');
			}

			if ((utf8_strlen($this->request->post['address_3']) < 1) || (utf8_strlen($this->request->post['address_3']) > 128)) {
				$json['error']['address_3'] = $this->language->get('error_address_3');
			}

			if ((utf8_strlen($this->request->post['city']) < 2) || (utf8_strlen($this->request->post['city']) > 32)) {
				$json['error']['city'] = $this->language->get('error_city');
			}

			$this->load->model('localisation/country');

			$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

			if ($country_info) {
				if ($country_info['postcode_required'] && (utf8_strlen($this->request->post['postcode']) < 2) || (utf8_strlen($this->request->post['postcode']) > 10)) {
					$json['error']['postcode'] = $this->language->get('error_postcode');
				}

				// VAT Validation
				$this->load->helper('vat');

				if ($this->config->get('config_vat') && !empty($this->request->post['tax_id']) && (vat_validation($country_info['iso_code_2'], $this->request->post['tax_id']) == 'invalid')) {
					$json['error']['tax_id'] = $this->language->get('error_vat');
				}
			}

			if ($this->request->post['country_id'] == '') {
				$json['error']['country'] = $this->language->get('error_country');
			}

			if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '') {
				$json['error']['zone'] = $this->language->get('error_zone');
			}

			if (!$json) {
				// Default Payment Address
				$this->load->model('account/address');

				$this->session->data['payment_address_id'] = $this->model_account_address->addAddress($this->request->post);
				$this->session->data['payment_country_id'] = $this->request->post['country_id'];
				$this->session->data['payment_zone_id'] = $this->request->post['zone_id'];

				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);
			}
		}
		

		$this->response->setOutput(json_encode($json));
	}

	private function loadStep($step, $customer_group) {
		$step = (int) $step;
		$customer_group = (int) $customer_group;

		switch($step) {
			case 1:
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkoutf/payment_address_new_1.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/checkoutf/payment_address_new_1.tpl';
				} else {
					$this->template = 'default/template/checkoutf/payment_address.tpl';
				}
				$this->data['text_fio'] = $this->language->get('text_fio');
				$this->data['text_email'] = $this->language->get('text_email');
				$this->data['text_telephone'] = $this->language->get('text_telephone');

				$this->data['entry_enter_fio'] = $this->language->get('entry_enter_fio');
				$this->data['entry_enter_email'] = $this->language->get('entry_enter_email');
				$this->data['entry_enter_telephone'] = $this->language->get('entry_enter_telephone');
				//Load Products
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

				$this->data['total'] = $total_data[0];
				
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
							'price_drop' => $this->session->data['price_drop'][$pcount]
						);
						$pcount=$pcount+1;
					} else {
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
				//Set Data
				$methods = array(
							  'firstname' => 'getFirstName', 
							  'middlename' => 'getMiddleName', 
							  'lastname' => 'getLastName', 
							  'email' => 'getEmail', 
							  'telephone' => 'getTelephone'
							);
				foreach (array_keys($methods) as $key) {
					if (isset($this->session->data[$key])) {
						$this->data[$key] = $this->session->data[$key];
					} else if ($this->customer->isLogged()) {
						$this->data[$key] = $this->customer->{$methods[$key]}();
					} else {
						$this->data[$key] = '';
					}
				}
			break;
			case 2:
				$this->data['text_address_existing'] = $this->language->get('text_address_existing');
				$this->data['text_address_new'] = $this->language->get('text_address_new');
				$this->data['entry_address_1'] = $this->language->get('entry_address_1');
				$this->data['entry_address_2'] = $this->language->get('entry_address_2');
				$this->data['entry_address_3'] = $this->language->get('entry_address_3');
				$this->data['entry_address_4'] = $this->language->get('entry_address_4');
				$this->data['entry_np'] = $this->language->get('entry_np');
				$this->data['entry_postcode'] = $this->language->get('entry_postcode');
				$this->data['entry_comments'] = $this->language->get('entry_comments');
				$this->data['addresses'] = array();
				$this->load->model('account/address');
				$this->data['addresses'] = $this->model_account_address->getAddresses();
				$this->data['customer_group'] = $this->customer->getCustomerGroupId();
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkoutf/payment_address_new_2.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/checkoutf/payment_address_new_2.tpl';
				} else {
					$this->template = 'default/template/checkout/payment_address_2.tpl';
				}
			break;
		}
		$this->response->setOutput($this->render());
	}

	private function validateStep($step, $customer_group) {
		$step = (int) $step;
		$customer_group = (int) $customer_group;
		$json = array();
		switch($step) {
			case 2:
				$err = false;
				$this->request->post['firstname'] = trim(preg_replace('/ {2,}/', ' ', $this->request->post['firstname']));
				if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 80)) {
					$json['error']['firstname'] = $this->language->get('error_fio');
					$err = true;
				}
				// Validate Fio
				if (!$err) {
					$no_middlename = preg_match("/Нет отчества/ui", $this->request->post['firstname']);
					$fio = explode(" ", $this->request->post['firstname']);
					if (count($fio) == 1) {
						$this->session->data['firstname'] = $fio[0];
						unset($this->session->data['middlename']);
						unset($this->session->data['lastname']);
					} elseif (count($fio) == 2){
						$this->session->data['firstname'] = $fio[0];
						$this->session->data['lastname'] = $fio[1];
						unset($this->session->data['middlename']);
					} elseif (count($fio) >= 3) {
						$this->session->data['lastname'] = $fio[0];
						$this->session->data['firstname'] = $fio[1];
						if ($no_middlename) {
							$this->session->data['middlename'] = 'Нет отчества';
						} else {
							$this->session->data['middlename'] = $fio[2];
						}
					} else {
						$this->session->data['firstname'] = $fio[0];
						unset($this->session->data['middlename']);
						unset($this->session->data['lastname']);
					}
					$this->session->data['fullname'] = "";
					foreach ($fio as $key => $value) {
						$this->session->data['fullname'] .= $value." ";
					}
					// Validate First Name
					if((utf8_strlen($this->session->data['firstname']) < 1) || (utf8_strlen($this->session->data['firstname']) > 32)) {
						$json['error']['firstname'] = $this->language->get('error_firstname');
						$err = true;
					}
				}
				// Validate Phone
				if ((utf8_strlen($this->request->post['telephone']) < 1) || (utf8_strlen($this->request->post['telephone']) > 32)) {
					$json['error']['telephone'] = $this->language->get('error_telephone');
					$err = true;
				} else {
					$this->session->data['telephone'] = $this->request->post['telephone'];
				}
				// Validate Email
				if ((utf8_strlen($this->request->post['email']) < 1) || (utf8_strlen($this->request->post['email']) > 32) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
					$json['error']['email'] = $this->language->get('error_email');
					$err = true;
				} else {
					$this->session->data['email'] = $this->request->post['email'];
				}
				if (!$err) {
					if (!$this->customer->isLogged()) {
						$password = substr(sha1(uniqid(mt_rand(), true)), 0, 10);
						$email = $this->session->data['email'];
						$this->load->model('account/customer');
						//Get User By Email
						if ($this->model_account_customer->getCustomerByEmail($email)) {
							$json['error']['yrregistered'] = "Для продолжения - введите свой пароль";
							$json['email'] = $email;
						//Пока отключим авторегистрацию
						//это задел на будущее
						} else if (false) {
							$data = array('email' => $email, 'password' => $password, 'firstname' => $this->session->data['firstname'], 'middlename' => $this->session->data['middlename'], 'lastname' => $this->session->data['lastname'], 'telephone' => $this->request->post['telephone']);
							$this->model_account_customer->addCustomer($data);
							$this->customer->login($email, $password);
							unset($this->session->data['guest']);
							$this->sendRegisterEmail($email, $password);
						}
					}
					// Для Default
					if ($customer_group == 1) {
						//редактируем адресс клиента в таблице адресов
						$this->load->model('account/address');
						$addressData = array();
						$addresses = $this->db->query("SELECT * FROM `oc_address` WHERE `customer_id` = '{$this->customer->getId()}' LIMIT 1");
						$addressData = $addresses->row;
						if(isset($this->session->data['firstname'])){
							$addressData['firstname'] = $this->session->data['firstname'];
						}
						if(isset($this->session->data['middlename'])){
							$addressData['middlename'] = $this->session->data['middlename'];
						}
						if(isset($this->session->data['lastname'])){
							$addressData['lastname'] = $this->session->data['lastname'];
						}
						if(isset($this->session->data['telephone'])){
							$addressData['telephone'] = $this->session->data['telephone'];
						}
						if(!empty($addressData)){
							$addresses = $this->db->query("SELECT * FROM `oc_address` WHERE `customer_id` = '{$this->customer->getId()}' LIMIT 1");
							$this->model_account_address->editAddress($addresses->row['address_id'], $addressData);
						}
					}
				}
			break;
			case 3:
				unset($this->session->data['deliverytype'],
					  $this->session->data['prepayment']);
				// Existing Address
				if (isset($this->request->post['payment_address']) && 
					$this->request->post['payment_address'] == 'existing') {
					$this->load->model('account/address');

					if (empty($this->request->post['address_id'])) {
						$json['error']['warning'] = $this->language->get('error_address');
					} elseif (!in_array($this->request->post['address_id'], array_keys($this->model_account_address->getAddresses()))) {
						$json['error']['warning'] = $this->language->get('error_address');
					} else {
						// Default Payment Address
						$this->load->model('account/address');

						$address_info = $this->model_account_address->getAddress($this->request->post['address_id']);

						if ($address_info) {
							$this->load->model('account/customer_group');

							$customer_group_info = $this->model_account_customer_group->getCustomerGroup($this->customer->getCustomerGroupId());

							// Company ID
							if ($customer_group_info['company_id_display'] && $customer_group_info['company_id_required'] && !$address_info['company_id']) {
								$json['error']['warning'] = $this->language->get('error_company_id');
							}

							// Tax ID
							if ($customer_group_info['tax_id_display'] && $customer_group_info['tax_id_required'] && !$address_info['tax_id']) {
								$json['error']['warning'] = $this->language->get('error_tax_id');
							}
						}
					}

					if (!$json) {
						// В качестве адреса платежа указываем основной адрес saler'a 
						if ($this->customer->getCustomerGroupId() == 2) {
							$this->session->data['payment_address_id'] = $this->customer->getAddressId();
						} else {
							$this->session->data['payment_address_id'] = $this->request->post['address_id'];
						}
						$this->session->data['shipping_address_id'] = $this->request->post['address_id'];

						if ($address_info) {
							$this->session->data['payment_country_id'] = $address_info['country_id'];
							$this->session->data['payment_zone_id'] = $address_info['zone_id'];

						} else {
							unset($this->session->data['payment_country_id']);
							unset($this->session->data['payment_zone_id']);
						}
						//Очищае данные из сессии, потому что в данном случае нам нужен только ID адреса
						//и также для того, чтобы избежать ошибок на следующем шаге
						unset($this->session->data['payment_city'],
								$this->session->data['shipping_city'],
								$this->session->data['payment_country'],
								$this->session->data['payment_country_id'],
								$this->session->data['shipping_country'],
								$this->session->data['shipping_country_id'],
								$this->session->data['payment_zone'],
								$this->session->data['payment_zone_id'],
								$this->session->data['shipping_zone'],
								$this->session->data['shipping_zone_id'],
								$this->session->data['zone'],
								$this->session->data['city'],
								$this->session->data['area'],
								$this->session->data['address_1'],
								$this->session->data['address_2'],
								$this->session->data['address_3'],
								$this->session->data['address_4'],
								$this->session->data['postcode']
							);
						unset($this->session->data['payment_method']);
						unset($this->session->data['payment_methods']);
					}
				// New address
				} else if ($this->request->post['payment_address'] == 'new') {
					//В начале проверям поля, которые требуют минимальной проверки
					if ((utf8_strlen($this->request->post['address_1']) < 1) || 
						(utf8_strlen($this->request->post['address_1']) > 128)) {
						$json['error']['address_1'] = $this->language->get('error_address_1');
					} else {
						$this->session->data['address_1'] = $this->request->post['address_1'];
					}

					if ((utf8_strlen($this->request->post['address_2']) < 1) || 
						(utf8_strlen($this->request->post['address_2']) > 128)) {
						$json['error']['address_2'] = $this->language->get('error_address_2');
					} else {
						$this->session->data['address_2'] = $this->request->post['address_2'];
					}

					if ((utf8_strlen($this->request->post['address_3']) < 1) || 
						(utf8_strlen($this->request->post['address_3']) > 128)) {
						$json['error']['address_3'] = $this->language->get('error_address_3');
					} else {
						$this->session->data['address_3'] = $this->request->post['address_3'];
					}

					if (utf8_strlen($this->request->post['address_4']) > 10) {
						$json['error']['address_4'] = $this->language->get('error_address_4');
					} else {
						$this->session->data['address_4'] = $this->request->post['address_4'];
					}

					if (!empty($this->request->post['postcode']) && 
						((utf8_strlen($this->request->post['postcode']) < 2) || 
						(utf8_strlen($this->request->post['postcode']) > 10))) {
						$json['error']['error_postcode'] = $this->language->get('error_postcode');
					} else {
						$this->session->data['postcode'] = $this->request->post['postcode'];
					}

					if (isset($this->request->post['comment'])) {
						$this->session->data['comment'] = strip_tags($this->request->post['comment']);
					}
					//Проверяем поле населенного пункта
					if ((utf8_strlen($this->request->post['np']) < 1) || (utf8_strlen($this->request->post['np']) > 180)) {
						$json['error']['np'] = $this->language->get('error_np');
					} else {
						$delivery_city_data = array();
						$this->session->data['np'] = $np = $this->request->post['np'];
						$delivery_city_id = (int) $this->request->post['is_db'];
						//Select by id or by np_name
						if ($delivery_city_id) {
							$query = "SELECT * FROM `delivery_cities` WHERE `id` = $delivery_city_id LIMIT 1";
						} else {
							$np = $this->db->escape($np);
							$query = "SELECT * FROM `delivery_cities` WHERE `np_name` LIKE '$np' LIMIT 1";
						}
						//Получаем населенный пункт для доставки
						$querycity = $this->db->query($query);
						if ($querycity) $delivery_city_data = $querycity->row;

						if ($querycity->num_rows > 0) {
							$c = $delivery_city_data;
							$this->session->data['city'] = $delivery_city_data['name'];
							$region_id = (int) $delivery_city_data['region_id'];
							$area_id = ($delivery_city_data['area_id'] !== null) ? (int) $delivery_city_data['area_id'] : 0;
							// Получаем регион
							$queryregion = $this->db->query("SELECT * FROM `delivery_regions` WHERE `id` = $region_id LIMIT 1");
							$region_data = $queryregion->row;
							$this->session->data['zone'] = $region_data['name'];
							// Получаем район
							if ($area_id) {
								$queryarea = $this->db->query("SELECT * FROM `delivery_area` WHERE `id` = $area_id LIMIT 1");
								$this->session->data['area'] = $queryarea->row['name'];
							}
							unset($this->session->data['payment_city'],
									$this->session->data['shipping_city'],
									$this->session->data['payment_country'],
									$this->session->data['payment_country_id'],
									$this->session->data['shipping_country'],
									$this->session->data['shipping_country_id'],
									$this->session->data['payment_zone'],
									$this->session->data['payment_zone_id'],
									$this->session->data['shipping_zone'],
									$this->session->data['shipping_zone_id']);

							$this->session->data['payment_city'] = $delivery_city_data['name'];
							$this->session->data['shipping_city'] = $delivery_city_data['name'];
							$c_id = 176;
							$querycountry = $this->db->query("SELECT * FROM `oc_country` as `c` WHERE `c`.`country_id` = '{$c_id}'");
							$this->session->data['payment_country'] = $querycountry->row['name'];
							$this->session->data['country_id'] = $querycountry->row['country_id'];
							$this->session->data['payment_country_id'] = $querycountry->row['country_id'];
							$this->session->data['shipping_country'] = $querycountry->row['name'];
							$this->session->data['shipping_country_id'] = $querycountry->row['country_id'];
							$queryzone = $this->db->query("SELECT * FROM `oc_zone` WHERE `country_id` = $c_id AND `name` LIKE '{$region_data['name']}' LIMIT 1");
							if ($queryzone->num_rows > 0) {
								$this->session->data['zone_id'] = $queryzone->row['zone_id'];
								$this->session->data['payment_zone_id'] = $queryzone->row['id'];
								$this->session->data['shipping_zone_id'] = $queryzone->row['id'];
							} else {
								$this->session->data['zone_id'] = $this->session->data['payment_zone_id'] =
								$this->session->data['shipping_zone_id'] = 0;

							}
							$this->session->data['shipping_zone'] = $region_data['name'];
							$this->session->data['payment_zone'] = $region_data['name'];
							$city_name = explode(' ', $delivery_city_data['name']);
							$npquery = $this->db->query("SELECT * FROM `oc_naselenniy_punkt` as `np` WHERE `code` LIKE '{$city_name[0]}' OR `name` LIKE '{$city_name[0]}'");
							if ($npquery->num_rows > 0){
								$np = $npquery->row;
								$this->session->data['shipping_naselenniy_punkt'] = $np['code'];
								$this->session->data['payment_naselenniy_punkt'] = $np['code'];
								$this->session->data['shipping_naselenniy_punkt_id'] = $np['naselenniy_punkt_id'];
								$this->session->data['payment_naselenniy_punkt_id'] = $np['naselenniy_punkt_id'];
							}
						} else {
							$json['error']['city_not_found'] = $this->language->get('city_not_found');
						}
					}
					if (!$json) {
						$this->load->model('account/address');
						$dataAdress = $this->session->data;
						$dataAdress['city'] = $delivery_city_data['np_name'];
						$dataAdress['delivery_cities_id'] = $delivery_city_data['id'];
						$this->session->data['shipping_address_id'] = $this->model_account_address->addAddress($dataAdress);
						// В качестве адреса платежа указываем основной адрес saler'a 
						if ($this->customer->getCustomerGroupId() == 2) {
							$this->session->data['payment_address_id'] = $this->customer->getAddressId();
						} else {
							$this->session->data['payment_address_id'] = $this->session->data['shipping_address_id'];
						}
					}
				} else {
					$json['error']['warning'] = $this->language->get('error_address');
				}
			break;
		}
		$this->response->setOutput(json_encode($json));
	}

	private function sendRegisterEmail($email, $password) {
		$subject = <<<HTML
			Добро пожаловать в на OUTMAXSHOP.RU!
HTML;
		$message = <<<HTML
			Здравствуйте, уважаемый клиент!
			Вы совершили свой первый заказ на OUTMAXSHOP.RU
			Теперь у вас есть свой личный кабинет, в котором вы сможете отслеживать свои заказы
			Для входа - используйте следующие данные:
			Логин: {$email}
			Пароль: {$password}
			Вход по ссылке http://bizoutmax.ru/
			С уважением, OUTMAXSHOP.RU
HTML;
		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->hostname = $this->config->get('config_smtp_host');
		$mail->username = $this->config->get('config_smtp_username');
		$mail->password = $this->config->get('config_smtp_password');
		$mail->port = $this->config->get('config_smtp_port');
		$mail->timeout = $this->config->get('config_smtp_timeout');
		$mail->setTo($email);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_name'));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
		$mail->send();
	}
}
?>
