<?php 
class ControllerAccountTrackingNumber extends Controller {
	private $error = array();
		
	public function index() {
    	if (!$this->customer->isLogged()) {
      		$this->session->data['redirect'] = $this->url->link('account/tracking_number', '', 'SSL');

	  		$this->redirect($this->url->link('account/login', '', 'SSL'));
    	}
		
		$this->language->load('account/order');
		
		$this->load->model('account/order');


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
			'href'      => $this->url->link('account/tracking_number', $url, 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_order_id'] = $this->language->get('text_order_id');
		$this->data['text_status'] = $this->language->get('text_status');
		$this->data['text_date_added'] = $this->language->get('text_date_added');
		$this->data['text_customer'] = $this->language->get('text_customer');
		$this->data['text_total'] = $this->language->get('text_total');
		$this->data['text_tracks_empty'] = $this->language->get('text_tracks_empty');

		$this->data['button_view'] = $this->language->get('button_view');
		$this->data['button_reorder'] = $this->language->get('button_reorder');
		$this->data['button_continue'] = $this->language->get('button_continue');
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		$this->data['orders'] = array();
		
		$order_total = $this->model_account_order->getTotalTracks();

        $this->data['filters'] = array();
        if (isset($_GET['order_id']) ){
            $this->data['filters']['order_id'] = $_GET['order_id'];
        } else{
            $this->data['filters']['order_id'] = '';
        }

        if (isset($_GET['date_start']) ){
            $this->data['filters']['date_start'] = $_GET['date_start'];
        } else{
            $this->data['filters']['date_start'] = '1900-00-00';
        }

        if (isset($_GET['date_end']) ){
            $this->data['filters']['date_end'] = $_GET['date_end'];
        } else{
            $this->data['filters']['date_end'] = '2900-00-00';
        }

		$results = $this->model_account_order->getTrackOrders(($page - 1) * 10, 10,  $this->data['filters']);
		
		foreach ($results as $result) {
			$product_total = $this->model_account_order->getTotalOrderProductsByOrderId($result['order_id']);
			$voucher_total = $this->model_account_order->getTotalOrderVouchersByOrderId($result['order_id']);
			$product_count = $this->model_account_order->getTotalOrderProductsCountByOrderId($result['order_id']);

			$this->data['orders'][] = array(
				'order_id'   => $result['order_id'],
				'name'       => $result['shipping_lastname'] . ' ' . $result['shipping_firstname'] . ' ' . $result['shipping_middlename'],
				'status'     => $result['status'],
				'tracking_number'     => $result['tracking_number'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'track_date_added' => date($this->language->get('date_format_short'), strtotime($result['track_date_added'])),
				'products'   => ($product_total + $voucher_total),
				'products_count'   => ($product_count + $voucher_total),
				'total'      => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'href'       => $this->url->link('account/order/info', 'order_id=' . $result['order_id'], 'SSL'),
			);
		}

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/tracking_number', 'page={page}', 'SSL');
		
		$this->data['pagination'] = $pagination->render();

		$this->data['continue'] = $this->url->link('account/account', '', 'SSL');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/tracking_number.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/tracking_number.tpl';
		} else {
			$this->template = 'default/template/account/tracking_number.tpl';
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
    public function filter() {
        $addUrl = '';
	    if ($_POST['order_id'] != ''){
	        $addUrl .= '&order_id='.$_POST['order_id'];
        }

	    if ($_POST['date_start'] != ''){
	        $addUrl .= '&date_start='.$_POST['date_start'];
        }

	    if ($_POST['date_end'] != ''){
	        $addUrl .= '&date_end='.$_POST['date_end'];
        }

        header('Location: /index.php?route=account/tracking_number'.$addUrl);
    }
}
?>