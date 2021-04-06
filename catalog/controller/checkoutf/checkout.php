<?php
class ControllerCheckoutfCheckout extends Controller {
	public function index() {

		if ($_SERVER[HTTP_HOST] == "opt.bizoutmax.ru" ) {
			$this->session->data['redirect'] = $this->url->link('common/home', '', 'SSL');
			$this->redirect($this->url->link('common/home', '', 'SSL'));
		}

		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
	  		$this->redirect($this->url->link('checkoutf/cart'));
    	}
		if(($this->customer->getCustomerGroupId() == 1)||(!$this->customer->isLogged())){
			$this->redirect($this->url->link('checkout/checkout'));
		} 
		else if ($this->customer->getCustomerGroupId() == 4  && $this->customer->getId() != 2734) {
			$this->redirect($this->url->link('checkoutf/checkoutd'));
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
				$this->redirect($this->url->link('checkoutf/cart'));
			}
		}

		$this->language->load('checkoutf/checkout');

		$this->initGeoIp();

		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addScript('catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js');
		$this->document->addScript('js/neos_checkout.min.js?20190312');
		$this->document->addStyle('catalog/view/javascript/jquery/colorbox/colorbox.css');
		$this->document->addStyle('css/neos_checkout.css');


		$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
        	'separator' => false
      	);

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_cart'),
			'href'      => $this->url->link('checkoutf/cart'),
        	'separator' => $this->language->get('text_separator')
      	);

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('checkoutf/checkout', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);

	    $this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_checkout_option'] = $this->language->get('text_checkout_option');
		$this->data['text_checkout_account'] = $this->language->get('text_checkout_account');
		$this->data['text_checkout_payment_address'] = $this->language->get('text_checkout_payment_address');
		$this->data['text_checkout_shipping_address'] = $this->language->get('text_checkout_shipping_address');
		$this->data['text_checkout_shipping_method'] = $this->language->get('text_checkout_shipping_method');
		$this->data['text_checkout_payment_method'] = $this->language->get('text_checkout_payment_method');
		$this->data['text_checkout_confirm'] = $this->language->get('text_checkout_confirm');
		$this->data['text_modify'] = $this->language->get('text_modify');

		$this->data['logged'] = $this->customer->isLogged();
		$this->data['shipping_required'] = $this->cart->hasShipping();
		$this->data['customer_group_id'] = $this->customer->getCustomerGroupId();

		$this->frontedOptions();
		// #carthybrid
		if (!NEOS_CART_HYBRID && $this->customer->getCustomerGroupId() == 2 && file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkoutf/checkout_new.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkoutf/checkout_new.tpl';
		} else if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkoutf/checkout.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkoutf/checkout.tpl';
		} else {
			$this->template = 'default/template/checkoutf/checkout.tpl';
		}

		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);

		$this->response->setOutput($this->render());
  	}

	public function country() {
		$json = array();

		$this->load->model('localisation/country');

    	$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);

		if ($country_info) {
			$this->load->model('localisation/zone');

			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']
			);
		}

		$this->response->setOutput(json_encode($json));
	}

	private function initGeoIp() {
		$google_api_key = $this->config->get('config_google_api_key');
		$is_init = (($this->request->server['REQUEST_METHOD'] != 'POST') && $google_api_key);
		if ($is_init) {
			$this->document->addScript('http://maps.google.com/maps/api/js?key='.$google_api_key.'&sensor=false&language=ru');
			//$this->document->addScript('catalog/view/javascript/jquery/geoip.ru.js');
		}
		return $is_init;
	}

	private function frontedOptions() {
		$this->data['frontend_options'] = array();
		$this->data['frontend_options']['customer_group'] = $this->customer->getCustomerGroupId();
		if ($this->customer->getCustomerGroupId() == 2) {
			$this->data['frontend_options']['links']['step1'] = $this->url->link('checkoutf/payment_address') . '&step=1';
			$this->data['frontend_options']['links']['step2'] = $this->url->link('checkoutf/payment_address') . '&step=2';
			$this->data['frontend_options']['links']['step3'] = $this->url->link('checkoutf/shipping_method');
			$this->data['frontend_options']['links']['validate_step2'] = $this->url->link('checkoutf/payment_address/validate') . '&step=2';
			$this->data['frontend_options']['links']['validate_step3'] = $this->url->link('checkoutf/payment_address/validate') . '&step=3';
			$this->data['frontend_options']['links']['validate_step4'] = $this->url->link('checkoutf/shipping_method/validate');
			$this->data['frontend_options']['links']['success'] = $this->url->link('checkout/success');
			$this->data['frontend_options']['links']['confirm'] = $this->url->link('checkoutf/confirm');
			$this->data['frontend_options']['links']['final_confirm'] = $this->url->link('checkoutf/confirm') . '/confirm/';
			$this->data['frontend_options']['language']['error'] = $this->language->get('error_unexpected');
			$this->data['frontend_options']['max_step'] = $this->data['max_step'] = 4;
			$this->data['steps'] = array(1 => 'Контактные данные', 'Адрес доставки', 'Варианты доставки', 'Подтверждение');
		} else {
			$this->data['frontend_options']['max_step'] = $this->data['max_step'] = 3;
			$this->data['steps'] = array(1 => 'Контактные данные', 'Адрес доставки', 'Варианты доставки');
		}
	}	
}
?>
