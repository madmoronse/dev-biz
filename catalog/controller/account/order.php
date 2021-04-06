<?php
class ControllerAccountOrder extends Controller {
    private $error = array();
    public function index() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/order', '', 'SSL');

            $this->redirect($this->url->link('account/login', '', 'SSL'));
        }

        $this->language->load('account/order');

        $this->load->model('account/order');
        $this->load->model('account/track');


        if (isset($this->request->get['order_id'])) {
            $order_info = $this->model_account_order->getOrder($this->request->get['order_id']);

            if ($order_info) {
                $order_products = $this->model_account_order->getOrderProducts($this->request->get['order_id']);

                foreach ($order_products as $order_product) {
                    $option_data = array();

                    $order_options = $this->model_account_order->getOrderOptions($this->request->get['order_id'], $order_product['order_product_id']);

                    foreach ($order_options as $order_option) {
                        if ($order_option['type'] == 'select' || $order_option['type'] == 'radio') {
                            $option_data[$order_option['product_option_id']] = $order_option['product_option_value_id'];
                        } elseif ($order_option['type'] == 'checkbox') {
                            $option_data[$order_option['product_option_id']][] = $order_option['product_option_value_id'];
                        } elseif ($order_option['type'] == 'text' || $order_option['type'] == 'textarea' || $order_option['type'] == 'date' || $order_option['type'] == 'datetime' || $order_option['type'] == 'time') {
                            $option_data[$order_option['product_option_id']] = $order_option['value'];
                        } elseif ($order_option['type'] == 'file') {
                            $option_data[$order_option['product_option_id']] = $this->encryption->encrypt($order_option['value']);
                        }
                    }

                    $this->session->data['success'] = sprintf($this->language->get('text_success'), $this->request->get['order_id']);

                    $this->cart->add($order_product['product_id'], $order_product['quantity'], $option_data);
                }

                $this->redirect($this->url->link('checkout/cart'));
            }
        }

        $this->document->setTitle($this->language->get('heading_title'));

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_account'),
            'href'      => $this->url->link('account/account', '', 'SSL'),
            'separator' => $this->language->get('text_separator')
        );

        $url = '';

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('account/order', $url, 'SSL'),
            'separator' => $this->language->get('text_separator')
        );

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_order_id'] = $this->language->get('text_order_id');
        $this->data['text_status'] = $this->language->get('text_status');
        $this->data['text_date_added'] = $this->language->get('text_date_added');
        $this->data['text_customer'] = $this->language->get('text_customer');
        $this->data['text_products'] = $this->language->get('text_products');
        $this->data['text_products_count'] = $this->language->get('text_products_count');
        $this->data['text_total'] = $this->language->get('text_total');
        $this->data['text_empty'] = $this->language->get('text_empty');
        $this->data['text_replacement_for'] = $this->language->get('text_replacement_for');

        $this->data['button_view'] = $this->language->get('button_view');
        $this->data['button_reorder'] = $this->language->get('button_reorder');
        $this->data['button_continue'] = $this->language->get('button_continue');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $this->data['orders'] = array();

        $ordersStatuses = $this->model_account_order->getFilterStatuses();
        $this->data['ordersStatuses'] = $ordersStatuses;
		
		if (isset($_POST['statusFilter']) AND $_POST['statusFilter'] != ""){
			$StatusFilter = $_POST['statusFilter'];
			$this->data['statusFilter'] = $_POST['statusFilter'];
			$this->session->data['StatusFilter'] = $_POST['statusFilter'];
		} else if (isset($this->session->data['StatusFilter']) AND $this->session->data['StatusFilter'] != ""){
			$this->data['statusFilter'] = $this->session->data['StatusFilter'];
			$StatusFilter = $this->session->data['StatusFilter'];
		} else {
			$StatusFilter = "%%";
		}
		
		if (isset($_POST['customer-f-i-o'])) { $page = 1; }
        $NameFilter = $_POST['customer-f-i-o'];        
        $this->data['NameFilter'] = $_POST['customer-f-i-o'];
        $order_total = $this->model_account_order->getTotalOrders($NameFilter, $StatusFilter);

        $results = $this->model_account_order->getOrders(($page - 1) * 10, 10,$NameFilter, $StatusFilter);

        $customerGroupId = $this->customer->getCustomerGroupId();
		
		if ($customerGroupId == 4) {
			$this->data['TotalNotPayedMarkup'] = round($this->model_account_order->getTotalNotPayedMarkup(),2);			
		}
        
    

        
        foreach ($results as $result) {
            $product_total = $this->model_account_order->getTotalOrderProductsByOrderId($result['order_id']);
            $voucher_total = $this->model_account_order->getTotalOrderVouchersByOrderId($result['order_id']);
            $product_count = $this->model_account_order->getTotalOrderProductsCountByOrderId($result['order_id']);




            $this->data['orders'][] = array(
                'order_id'   => $result['order_id'],
                'name'       => $result['shipping_lastname'] . ' ' . $result['shipping_firstname'] . ' ' . $result['shipping_middlename'],
                'status'     => $result['status'],
                'tracking_number'     => $result['tracking_number'],
                'replacement_for' => $result['replacement_for'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'products'   => ($product_total + $voucher_total),
                'products_count'   => ($product_count + $voucher_total),
                'total'      => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
                'href'       => $this->url->link('account/order/info', 'order_id=' . $result['order_id'], 'SSL'),
                'reorder'    => $this->url->link('account/order', 'order_id=' . $result['order_id'], 'SSL')
            );


            if ($customerGroupId == 4) {
                $this->data['orders'][count($this->data['orders'])-1]['orderMarkupDropshipping'] =$this->model_account_order->getOrderMarkupByOrderId($result['order_id']);
				
				$this->data['orders'][count($this->data['orders'])-1]['PayoutDate'] = date($this->language->get('date_format_short'), strtotime($this->model_account_order->getDropPayoutDate($result['order_id'])));
            }
        }


        $pagination = new Pagination();
        $pagination->total = $order_total;
        $pagination->page = $page;
        $pagination->limit = 10;
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('account/order', 'page={page}', 'SSL');

        $this->data['pagination'] = $pagination->render();

        $this->data['continue'] = $this->url->link('account/account', '', 'SSL');
        $this->data['track'] = $this->url->link('account/track', '', 'SSL');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/order_list.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/account/order_list.tpl';
        } else {
            $this->template = 'default/template/account/order_list.tpl';
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

    public function info() {
        $this->language->load('account/order');

        if (isset($this->request->get['order_id'])) {
            $order_id = $this->request->get['order_id'];
        } else {
            $order_id = 0;
        }

        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/order/info', 'order_id=' . $order_id, 'SSL');

            $this->redirect($this->url->link('account/login', '', 'SSL'));
        }

        $this->load->model('account/order');

        $order_info = $this->model_account_order->getOrder($order_id);

        if ($order_info) {
            $this->document->setTitle($this->language->get('text_order'));

            $this->data['breadcrumbs'] = array();

            $this->data['breadcrumbs'][] = array(
                'text'      => $this->language->get('text_home'),
                'href'      => $this->url->link('common/home'),
                'separator' => false
            );

            $this->data['breadcrumbs'][] = array(
                'text'      => $this->language->get('text_account'),
                'href'      => $this->url->link('account/account', '', 'SSL'),
                'separator' => $this->language->get('text_separator')
            );

            $url = '';

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->data['breadcrumbs'][] = array(
                'text'      => $this->language->get('heading_title'),
                'href'      => $this->url->link('account/order', $url, 'SSL'),
                'separator' => $this->language->get('text_separator')
            );

            $this->data['breadcrumbs'][] = array(
                'text'      => $this->language->get('text_order'),
                'href'      => $this->url->link('account/order/info', 'order_id=' . $this->request->get['order_id'] . $url, 'SSL'),
                'separator' => $this->language->get('text_separator')
            );

            $this->data['heading_title'] = $this->language->get('text_order');

            $this->data['text_order_detail'] = $this->language->get('text_order_detail');
            $this->data['text_invoice_no'] = $this->language->get('text_invoice_no');
            $this->data['text_order_id'] = $this->language->get('text_order_id');
            $this->data['text_date_added'] = $this->language->get('text_date_added');
            $this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
            $this->data['text_shipping_address'] = $this->language->get('text_shipping_address');
            $this->data['text_payment_method'] = $this->language->get('text_payment_method');
            $this->data['text_payment_address'] = $this->language->get('text_payment_address');
            $this->data['text_history'] = $this->language->get('text_history');
            $this->data['text_comment'] = $this->language->get('text_comment');
            $this->data['text_replacement_for'] = $this->language->get('text_replacement_for');

            $this->data['column_name'] = $this->language->get('column_name');
            $this->data['column_sku'] = $this->language->get('column_sku');
            $this->data['column_quantity'] = $this->language->get('column_quantity');
            $this->data['column_price'] = $this->language->get('column_price');
            $this->data['column_total'] = $this->language->get('column_total');
            $this->data['column_action'] = $this->language->get('column_action');
            $this->data['column_date_added'] = $this->language->get('column_date_added');
            $this->data['column_status'] = $this->language->get('column_status');
            $this->data['column_comment'] = $this->language->get('column_comment');

            $this->data['button_return'] = $this->language->get('button_return');
            $this->data['button_continue'] = $this->language->get('button_continue');

            if ($order_info['invoice_no']) {
                $this->data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
            } else {
                $this->data['invoice_no'] = '';
            }

            if ($order_info['replacement_for']) {
                $this->data['replacement_for'] = $order_info['replacement_for'];
            } else {
                $this->data['replacement_for'] = '';
            }

            $this->data['order_id'] = $this->request->get['order_id'];
            $this->data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));

            if ($order_info['payment_address_format']) {
                $format = $order_info['payment_address_format'];
            } else {

                $format = '{lastname} {firstname} {middlename}' . "\n" . '{country}' . ", " . '{zone}' . "\n" .'{postcode} {address_5} {city}' . ", ул. " . '{address_1}' . ", дом " . '{address_2}' . ", корп " . '{address_4}' . ", кв. " . '{address_3}';
            }

            $find = array(
                '{firstname}',
                '{middlename}',
                '{lastname}',
                '{company}',
                '{address_1}',
                '{address_2}',
                '{address_3}',
                '{address_4}',
                '{address_5}',
                '{city}',
                '{postcode}',
                '{zone}',
                '{zone_code}',
                '{country}'
            );

            $replace = array(
                'firstname' => $order_info['payment_firstname'],
                'middlename'=> $order_info['payment_middlename'],
                'lastname'  => $order_info['payment_lastname'],
                'company'   => $order_info['payment_company'],
                'address_1' => $order_info['payment_address_1'],
                'address_2' => $order_info['payment_address_2'],
                'address_3' => $order_info['payment_address_3'],
                'address_4' => $order_info['payment_address_4'],
                'address_5' => $order_info['payment_address_5'],
                'city'      => $order_info['payment_city'],
                'postcode'  => $order_info['payment_postcode'],
                'zone'      => $order_info['payment_zone'],
                'zone_code' => $order_info['payment_zone_code'],
                'country'   => $order_info['payment_country']
            );

            $this->data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

            $this->data['payment_method'] = $order_info['payment_method'];

            if ($order_info['shipping_address_format']) {
                $format = $order_info['shipping_address_format'];
            } else {
                if ($order_info['payment_address_4']!=''){
                    $format = '{lastname} {firstname} {middlename}' . "\n" . '{country}' . ", " . '{zone}' . "\n" .'{postcode} {address_5} {city}' . ", ул. " . '{address_1}' . ", дом " . '{address_2}' . ", корп " . '{address_4}' . ", кв. " . '{address_3}';
                } else {
                    $format = '{lastname} {firstname} {middlename}' . "\n" . '{country}' . ", " . '{zone}' . "\n" .'{postcode} {address_5} {city}' . ", ул. " . '{address_1}' . ", дом " . '{address_2}' . ", кв. " . '{address_3}';
                }
            }

            $find = array(
                '{firstname}',
                '{middlename}',
                '{lastname}',
                '{company}',
                '{address_1}',
                '{address_2}',
                '{address_3}',
                '{address_4}',
                '{address_5}',
                '{city}',
                '{postcode}',
                '{zone}',
                '{zone_code}',
                '{country}'
            );

            $replace = array(
                'firstname' => $order_info['shipping_firstname'],
                'middlename'=> $order_info['shipping_middlename'],
                'lastname'  => $order_info['shipping_lastname'],
                'company'   => $order_info['shipping_company'],
                'address_1' => $order_info['shipping_address_1'],
                'address_2' => $order_info['shipping_address_2'],
                'address_3' => $order_info['shipping_address_3'],
                'address_4' => $order_info['shipping_address_4'],
                'address_5' => $order_info['shipping_address_5'],
                'city'      => $order_info['shipping_city'],
                'postcode'  => $order_info['shipping_postcode'],
                'zone'      => $order_info['shipping_zone'],
                'zone_code' => $order_info['shipping_zone_code'],
                'country'   => $order_info['shipping_country']
            );

            $this->data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

            $this->data['shipping_method'] = $order_info['shipping_method'];

            $this->data['products'] = array();

            $products = $this->model_account_order->getOrderProducts($this->request->get['order_id']);

            foreach ($products as $product) {
                $option_data = array();

                $options = $this->model_account_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);

                foreach ($options as $option) {
                    if ($option['type'] != 'file') {
                        $value = $option['value'];
                    } else {
                        $value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
                    }

                    $option_data[] = array(
                        'name'  => $option['name'],
                        'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
                    );
                }

                $this->data['products'][] = array(
                    'name'     => $product['name'],
                    //'model'    => $product['model'],
                    'product_id'    => $product['product_id'],
                    'option'   => $option_data,
                    'quantity' => $product['quantity'],
                    'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                    'drop_saller_price'    => $this->currency->format($product['drop_saller_price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                    'markup'    => $this->currency->format($product['drop_saller_price'] - ($product['total'] + ($this->config->get('config_tax') ? $product['tax'] : 0)), $order_info['currency_code'], $order_info['currency_value']),
                    'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
                    'return'   => $this->url->link('account/return/insert', 'order_id=' . $order_info['order_id'] . '&product_id=' . $product['product_id'], 'SSL')
                );
            }

            // Voucher
            $this->data['vouchers'] = array();

            $vouchers = $this->model_account_order->getOrderVouchers($this->request->get['order_id']);

            foreach ($vouchers as $voucher) {
                $this->data['vouchers'][] = array(
                    'description' => $voucher['description'],
                    'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
                );
            }

            $this->data['totals'] = $this->model_account_order->getOrderTotals($this->request->get['order_id']);

            $this->data['comment'] = nl2br($order_info['comment']);

            $this->data['histories'] = array();

            $results = $this->model_account_order->getOrderHistories($this->request->get['order_id']);

            foreach ($results as $result) {
                $this->data['histories'][] = array(
                    'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                    'status'     => $result['status'],
                    'comment'    => nl2br($result['comment'])
                );
            }

            // BMV Begin
            $this->data['comments'] = array();

            $results = $this->model_account_order->getOrderComments($this->request->get['order_id']);

            foreach ($results as $result) {
                $this->data['comments'][] = array(
                    'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                    'comment'    => nl2br($result['comment'])
                );
            }
            // BMV End

            $this->data['continue'] = $this->url->link('account/order', '', 'SSL');			
			// NEOS - payments
            if ($this->customer->getCustomerGroupId() == 1) {
                $this->load->model('payment/walletone');
                $this->data['walletone_form'] = $this->model_payment_walletone->getForm($order_info);
            }
            // NEOS - payments end
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/order_info.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/account/order_info.tpl';
            } else {
                $this->template = 'default/template/account/order_info.tpl';
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
        } else {
            $this->document->setTitle($this->language->get('text_order'));

            $this->data['heading_title'] = $this->language->get('text_order');

            $this->data['text_error'] = $this->language->get('text_error');

            $this->data['button_continue'] = $this->language->get('button_continue');

            $this->data['breadcrumbs'] = array();

            $this->data['breadcrumbs'][] = array(
                'text'      => $this->language->get('text_home'),
                'href'      => $this->url->link('common/home'),
                'separator' => false
            );

            $this->data['breadcrumbs'][] = array(
                'text'      => $this->language->get('text_account'),
                'href'      => $this->url->link('account/account', '', 'SSL'),
                'separator' => $this->language->get('text_separator')
            );

            $this->data['breadcrumbs'][] = array(
                'text'      => $this->language->get('heading_title'),
                'href'      => $this->url->link('account/order', '', 'SSL'),
                'separator' => $this->language->get('text_separator')
            );

            $this->data['breadcrumbs'][] = array(
                'text'      => $this->language->get('text_order'),
                'href'      => $this->url->link('account/order/info', 'order_id=' . $order_id, 'SSL'),
                'separator' => $this->language->get('text_separator')
            );

            $this->data['continue'] = $this->url->link('account/order', '', 'SSL');

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
            } else {
                $this->template = 'default/template/error/not_found.tpl';
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

    


    public function comments() {

        $this->load->model('account/order');

                $this->model_account_order->addOrderComments($this->request->get['order_id'], $this->request->post);

        echo '<meta http-equiv="refresh" content="0">';


    }

} 
?>