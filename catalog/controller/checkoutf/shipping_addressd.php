<?php
class ControllerCheckoutfShippingAddressd extends Controller {
	public function index() {
		$this->language->load('checkoutf/checkout');

		$this->data['text_address_existing'] = $this->language->get('text_address_existing');
		$this->data['text_address_new'] = $this->language->get('text_address_new');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['replacement_for_order'] = $this->language->get('replacement_for_order');

		$this->data['entry_firstname'] = $this->language->get('entry_firstname');
		$this->data['entry_middlename'] = $this->language->get('entry_middlename');
		$this->data['entry_lastname'] = $this->language->get('entry_lastname');
		$this->data['entry_company'] = $this->language->get('entry_company');
		$this->data['entry_address_1'] = $this->language->get('entry_address_1');
		$this->data['entry_address_2'] = $this->language->get('entry_address_2');
		$this->data['entry_address_3'] = $this->language->get('entry_address_3');
		$this->data['entry_address_4'] = $this->language->get('entry_address_4');
		$this->data['entry_telephone'] = $this->language->get('entry_telephone');
		$this->data['entry_social'] = $this->language->get('entry_social');
		$this->data['entry_naselenniy_punkt'] = $this->language->get('entry_naselenniy_punkt');
		$this->data['entry_postcode'] = $this->language->get('entry_postcode');
		$this->data['entry_city'] = $this->language->get('entry_city');
		$this->data['entry_country'] = $this->language->get('entry_country');
		$this->data['entry_zone'] = $this->language->get('entry_zone');

		$this->data['customer_group_id'] = $this->customer->getCustomerGroupId();

		$this->data['button_continue'] = $this->language->get('button_continue');


		$this->data['init_geo_ip'] = false;
		if (!isset($this->session->data['shipping_country_id']) && !isset($this->session->data['shipping_zone_id'])) {
			$google_api_key = $this->config->get('config_google_api_key');
			if ($google_api_key) {
				$this->data['init_geo_ip'] = true;
				$this->data['google_api_key'] = $google_api_key;
			}
		}

		if (isset($this->session->data['shipping_address_id'])) {
			$this->data['address_id'] = $this->session->data['shipping_address_id'];
		} else {
			$this->data['address_id'] = $this->customer->getAddressId();
		}

		$this->load->model('account/address');

		$this->data['addresses'] = $this->model_account_address->getAddresses();

		if (isset($this->session->data['shipping_postcode'])) {
			$this->data['shipping_postcode'] = $this->session->data['shipping_postcode'];
		} else {
			$this->data['shipping_postcode'] = '';
		}

		if (isset($this->session->data['shipping_country_id'])) {
			$this->data['country_id'] = $this->session->data['shipping_country_id'];
		} else {
			$this->data['country_id'] = $this->config->get('config_country_id');
		}

		if (isset($this->session->data['shipping_zone_id'])) {
			$this->data['zone_id'] = $this->session->data['shipping_zone_id'];
		} else {
			$this->data['zone_id'] = '';
		}

		if (isset($this->session->data['shipping_lastname'])) {
			$this->data['shipping_lastname'] = $this->session->data['shipping_lastname'];
		} else {
			$this->data['shipping_lastname'] = '';
		}

		if (isset($this->session->data['shipping_firstname'])) {
			$this->data['shipping_firstname'] = $this->session->data['shipping_firstname'];
		} else {
			$this->data['shipping_firstname'] = '';
		}

		if (isset($this->session->data['shipping_middlename'])) {
			$this->data['shipping_middlename'] = $this->session->data['shipping_middlename'];
		} else {
			$this->data['shipping_middlename'] = '';
		}

		if (isset($this->session->data['shipping_telephone'])) {
			$this->data['shipping_telephone'] = $this->session->data['shipping_telephone'];
		} else {
			$this->data['shipping_telephone'] = '';
		}

		if (isset($this->session->data['shipping_city'])) {
			$this->data['shipping_city'] = $this->session->data['shipping_city'];
		} else {
			$this->data['shipping_city'] = '';
		}

		if (isset($this->session->data['shipping_address_1'])) {
			$this->data['shipping_address_1'] = $this->session->data['shipping_address_1'];
		} else {
			$this->data['shipping_address_1'] = '';
		}

		if (isset($this->session->data['shipping_address_2'])) {
			$this->data['shipping_address_2'] = $this->session->data['shipping_address_2'];
		} else {
			$this->data['shipping_address_2'] = '';
		}

		if (isset($this->session->data['shipping_address_3'])) {
			$this->data['shipping_address_3'] = $this->session->data['shipping_address_3'];
		} else {
			$this->data['shipping_address_3'] = '';
		}

		if (isset($this->session->data['shipping_address_4'])) {
			$this->data['shipping_address_4'] = $this->session->data['shipping_address_4'];
		} else {
			$this->data['shipping_address_4'] = '';
		}

		$this->load->model('localisation/country');

		$this->data['countries'] = $this->model_localisation_country->getCountries();

		$this->data['naselenniy_punkts'] = $this->model_localisation_country->getNaselenniyPunkts();

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkoutf/shipping_addressd.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkoutf/shipping_addressd.tpl';
		} else {
			$this->template = 'default/template/checkoutf/shipping_addressd.tpl';
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

		// Validate if shipping is required. If not the customer should not have reached this page.
		if (!$this->cart->hasShipping()) {
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

		if (!$json) {
			// clear for hybrid cart
			if (NEOS_CART_HYBRID) unset($this->session->data['city'], $this->session->data['zone']);
			if (isset($this->request->post['shipping_address']) && ($this->request->post['shipping_address'] == 'existing') ) {
				$this->load->model('account/address');

				if (empty($this->request->post['address_id'])) {
					$json['error']['warning'] = $this->language->get('error_address');
				} elseif (!in_array($this->request->post['address_id'], array_keys($this->model_account_address->getAddresses()))) {
					$json['error']['warning'] = $this->language->get('error_address');
				}

				if (!$json) {
					$this->session->data['shipping_address_id'] = $this->request->post['address_id'];

					// Default Shipping Address
					$this->load->model('account/address');

					$address_info = $this->model_account_address->getAddress($this->request->post['address_id']);

					if ($address_info) {
						$this->session->data['shipping_country_id'] = $address_info['country_id'];
						$this->session->data['shipping_zone_id'] = $address_info['zone_id'];
						$this->session->data['shipping_postcode'] = $address_info['postcode'];
					} else {
						unset($this->session->data['shipping_country_id']);
						unset($this->session->data['shipping_zone_id']);
						unset($this->session->data['shipping_postcode']);
					}

					if (!isset($this->request->post['shipping_method_confirmation'])){
						unset($this->session->data['shipping_method']);
						unset($this->session->data['shipping_methods']);
					}

				}
			}

			if ($this->request->post['shipping_address'] == 'new' || $this->request->post['shipping_address'] == 'replacement') {
				if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
					$json['error']['firstname'] = $this->language->get('error_firstname');
				}
				if ((utf8_strlen($this->request->post['middlename']) < 1) || (utf8_strlen($this->request->post['middlename']) > 32)) {
					$json['error']['middlename'] = $this->language->get('error_middlename');
				}
				if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
					$json['error']['lastname'] = $this->language->get('error_lastname');
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

				$this->request->post['telephone'] = preg_replace('/[^0-9]/', '', $this->request->post['telephone']);
				if ($this->request->post['country_id'] == 176 && strlen($this->request->post['telephone']) > 0 ) {
					if ( ($this->request->post['telephone'][0] != "7") || ((utf8_strlen($this->request->post['telephone']) != 11 ) && (utf8_strlen($this->request->post['telephone']) != 14))) {
						$json['error']['telephone'] = $this->language->get('error_telephone');
					}
				}


				if ((utf8_strlen($this->request->post['city']) < 2) || (utf8_strlen($this->request->post['city']) > 128)) {
					$json['error']['city'] = $this->language->get('error_city');
				}

				$this->load->model('localisation/country');

				$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

				if ($country_info && $country_info['postcode_required'] && (utf8_strlen($this->request->post['postcode']) < 2) || (utf8_strlen($this->request->post['postcode']) > 10)) {
					$json['error']['postcode'] = $this->language->get('error_postcode');
				}

				if ($this->request->post['country_id'] == '') {
					$json['error']['country'] = $this->language->get('error_country');
				}

				if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '') {
					$json['error']['zone'] = $this->language->get('error_zone');
				}

				if (!$json) {
					// Default Shipping Address
					$this->load->model('account/address');

					$this->session->data['shipping_address_id'] = $this->model_account_address->addAddress($this->request->post);
					if (!isset($this->request->post['shipping_method_confirmation'])) {
						unset($this->session->data['shipping_method']);
						unset($this->session->data['shipping_methods']);
					}
				} else {
					$this->session->data['shipping_country_id'] = $this->request->post['country_id'];
					$this->session->data['shipping_zone_id'] = $this->request->post['zone_id'];
					$this->session->data['shipping_postcode'] = $this->request->post['postcode'];
					$this->session->data['shipping_lastname'] = $this->request->post['lastname'];
					$this->session->data['shipping_firstname'] = $this->request->post['firstname'];
					$this->session->data['shipping_middlename'] = $this->request->post['middlename'];
					$this->session->data['shipping_telephone'] = $this->request->post['telephone'];
					$this->session->data['shipping_city'] = $this->request->post['city'];
					$this->session->data['shipping_address_1'] = $this->request->post['address_1'];
					$this->session->data['shipping_address_2'] = $this->request->post['address_2'];
					$this->session->data['shipping_address_3'] = $this->request->post['address_3'];
				}
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	public function getOrder(){
		if (isset($this->request->post['order_id'])) {
			$this->load->model('account/order');
			$order = $this->model_account_order->getOrder($this->request->post['order_id']);
			return $this->response->setOutput(json_encode($order));
		}
	}


	public function getAddressByPostcode() {
		if (isset($this->request->post['postcode'])) {
			$info = $this->db->query("SELECT * FROM `pr_indexes` WHERE `POSTCODE` = '{$this->request->post['postcode']}'");
			$result = $info->row;

			if ($result['REGION'] == 'КАРАЧАЕВО-ЧЕРКЕССКАЯ РЕСПУБЛИКА') {
				$data['zone_name'] = 'Карачаево-Черкеcсия';

			} else if ($result['REGION'] == 'АДЫГЕЯ РЕСПУБЛИКА') {
				$data['zone_name'] = 'Республика Адыгея';

			} else if ($result['REGION'] == 'АЛТАЙ РЕСПУБЛИКА') {
				$data['zone_name'] = 'Республика Алтай';

			} else if ($result['REGION'] == 'БУРЯТИЯ РЕСПУБЛИКА') {
				$data['zone_name'] = 'Республика Бурятия';

			} else if ($result['REGION'] == 'ДАГЕСТАН РЕСПУБЛИКА') {
				$data['zone_name'] = 'Республика Дагестан';

			} else if ($result['REGION'] == 'ИНГУШЕТИЯ РЕСПУБЛИКА') {
				$data['zone_name'] = 'Республика Ингушетия';

			} else if ($result['REGION'] == 'БАШКОРТОСТАН РЕСПУБЛИКА') {
				$data['zone_name'] = 'Республика Башкортостан';

			} else if ($result['REGION'] == 'КАЛМЫКИЯ РЕСПУБЛИКА') {
				$data['zone_name'] = 'Республика Калмыкия';

			} else if ($result['REGION'] == 'КАРЕЛИЯ РЕСПУБЛИКА') {
				$data['zone_name'] = 'Республика Карелия';

			} else if ($result['REGION'] == 'КОМИ РЕСПУБЛИКА') {
				$data['zone_name'] = 'Республика Коми';

			} else if ($result['REGION'] == 'КРЫМ РЕСПУБЛИКА') {
				$data['zone_name'] = 'Республика Крым';

			} else if ($result['REGION'] == 'МАРИЙ ЭЛ РЕСПУБЛИКА') {
				$data['zone_name'] = 'Республика Марий Эл';

			} else if ($result['REGION'] == 'МОРДОВИЯ РЕСПУБЛИКА') {
				$data['zone_name'] = 'Республика Мордовия';

			} else if ($result['REGION'] == 'САХА (ЯКУТИЯ) РЕСПУБЛИКА') {
				$data['zone_name'] = 'Республика Саха';

			} else if ($result['REGION'] == 'СЕВЕРНАЯ ОСЕТИЯ - АЛАНИЯ РЕСПУБЛИКА') {
				$data['zone_name'] = 'Республика Северная Осетия';

			} else if ($result['REGION'] == 'ТАТАРСТАН РЕСПУБЛИКА') {
				$data['zone_name'] = 'Республика Татарстан';

			} else if ($result['REGION'] == 'ТЫВА РЕСПУБЛИКА') {
				$data['zone_name'] = 'Республика Тыва';

			} else if ($result['REGION'] == 'ХАКАСИЯ РЕСПУБЛИКА') {
				$data['zone_name'] = 'Республика Хакасия';

			} else if ($result['AUTONOM'] == 'ХАНТЫ-МАНСИЙСКИЙ-ЮГРА АВТОНОМНЫЙ ОКРУГ') {
				$data['zone_name'] = 'Ханты-Мансийский АО - Югра';

			}  else if ($result['REGION'] == 'ЧУВАШИЯ РЕСПУБЛИКА') {
				$data['zone_name'] = 'Чувашская Республика';

			}  else if ($result['AUTONOM'] == 'ЧУКОТСКИЙ АВТОНОМНЫЙ ОКРУГ') {
				$data['zone_name'] = 'Чукотский АО';

			}  else if ($result['AUTONOM'] == 'ЯМАЛО-НЕНЕЦКИЙ АВТОНОМНЫЙ ОКРУГ') {
				$data['zone_name'] = 'Ямало-Ненецкий АО';

			} else if ($result['AUTONOM'] == 'ЕВРЕЙСКАЯ АВТОНОМНАЯ ОБЛАСТЬ') {
				$data['zone_name'] = 'Еврейская АО';

			} else if ($result['AUTONOM'] == 'НЕНЕЦКИЙ АВТОНОМНЫЙ ОКРУГ') {
				$data['zone_name'] = 'Ненецкий АО';

			} else if ($result['REGION']) {
				$data['zone_name'] = $result['REGION'];

			} else{
				$data['zone_name'] = $result['AUTONOM'];
			}

			$zone = $this->db->query("SELECT * FROM oc_zone WHERE name = '{$data['zone_name']}'");

			if ($zone->row){
				$data['zone_name'] = $zone->row['name'];
				$data['zone_id'] = $zone->row['zone_id'];
				$data['country_id'] = $zone->row['country_id'];
				$data['city'] =  $result['CITY'];
			}

			$this->response->setOutput(json_encode($data));
		}
	}
}
?>
