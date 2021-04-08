<?php
class ControllerCheckoutSuccess extends Controller {
	public function index() {

	$this->language->load('checkout/success');

		if (isset($this->session->data['order_id'])) {
			$this->cart->clear();

			if ($this->customer->isLogged()) {
                if ($this->customer->getCustomerGroupId() == 1) {
                    if ($this->session->data['shipping_method'][code] != "item.item") {
                        $this->data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('account/account', '', 'SSL'), $this->url->link('account/order', '', 'SSL'), /*$this->url->link('account/download', '', 'SSL'), */
                            $this->url->link('information/contact'), "https://vk.com/club110664758");
                    } else {
                        $this->data['text_message'] = sprintf($this->language->get('text_customer_no_prepayment'), $this->url->link('account/account', '', 'SSL'), $this->url->link('account/order', '', 'SSL'), /*$this->url->link('account/download', '', 'SSL'), */
                            $this->url->link('information/contact'), "https://vk.com/club110664758");
                    }
                }
             else {
                $this->data['text_message'] = sprintf($this->language->get('text_customer_no_prepayment'), $this->url->link('account/account', '', 'SSL'), $this->url->link('account/order', '', 'SSL'), /*$this->url->link('account/download', '', 'SSL'), */
                    $this->url->link('information/contact'), "https://vk.com/club110664758");
            }
        }
            else {
                $this->data['text_message'] = sprintf($this->language->get('text_guest'), $this->url->link('information/contact'));
            }

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['guest']);
			unset($this->session->data['comment']);
			unset($this->session->data['order_id']);
			unset($this->session->data['coupon']);
			unset($this->session->data['reward']);
			unset($this->session->data['voucher']);
			unset($this->session->data['vouchers']);
			unset($this->session->data['replacement_for']);
			unset($this->session->data['buybuysu_bc']);
			unset($this->session->data['prepayment']);
			unset($this->session->data['markupdropshipping']);

			unset($this->session->data['shipping_country_id']);
			unset($this->session->data['shipping_zone_id']);
			unset($this->session->data['shipping_postcode']);
			unset($this->session->data['shipping_lastname']);
			unset($this->session->data['shipping_firstname']);
			unset($this->session->data['shipping_middlename']);
			unset($this->session->data['shipping_telephone']);
			unset($this->session->data['shipping_city']);
			unset($this->session->data['shipping_address_1']);
			unset($this->session->data['shipping_address_2']);
			unset($this->session->data['shipping_address_3']);
			unset($this->session->data['cart_price_drop']);
		}



		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'href'      => $this->url->link('common/home'),
        	'text'      => $this->language->get('text_home'),
        	'separator' => false
      	);

      	$this->data['breadcrumbs'][] = array(
        	'href'      => $this->url->link('checkout/cart'),
        	'text'      => $this->language->get('text_basket'),
        	'separator' => $this->language->get('text_separator')
      	);

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('checkout/checkout', '', 'SSL'),
			'text'      => $this->language->get('text_checkout'),
			'separator' => $this->language->get('text_separator')
		);

      	$this->data['breadcrumbs'][] = array(
        	'href'      => $this->url->link('checkout/success'),
        	'text'      => $this->language->get('text_success'),
        	'separator' => $this->language->get('text_separator')
      	);

		$this->data['heading_title'] = $this->language->get('heading_title');



    	$this->data['button_continue'] = $this->language->get('button_continue');

    	$this->data['continue'] = $this->url->link('common/home');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/success.tpl';
		} else {
			$this->template = 'default/template/common/success.tpl';
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
}
?>
