<?php 
class ControllerPaymentCodCdek extends Controller {
	private $error = array();
	 
	public function index() { 
		$this->load->language('payment/cod_cdek');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
			$this->model_setting_setting->editSetting('cod_cdek', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$this->load->model('setting/store');
		$this->load->model('sale/customer_group');
		$this->load->model('localisation/language');
		
		$this->document->addStyle('view/stylesheet/cdek.css');
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
				
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');
		$this->data['entry_city_ignore'] = $this->language->get('entry_city_ignore');
		$this->data['entry_view_mode'] = $this->language->get('entry_view_mode');
		$this->data['entry_view_mode_cdek'] = $this->language->get('entry_view_mode_cdek');
		$this->data['entry_active'] = $this->language->get('entry_active');
		$this->data['entry_min_total'] = $this->language->get('entry_min_total');
		$this->data['entry_max_total'] = $this->language->get('entry_max_total');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_cache_on_delivery'] = $this->language->get('entry_cache_on_delivery');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_price'] = $this->language->get('entry_price');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_additional'] = $this->language->get('tab_additional');
		
		$this->data['boolean_variables'] = array($this->language->get('text_no'), $this->language->get('text_yes'));
		$this->data['status_variables'] = array($this->language->get('text_disabled'), $this->language->get('text_enabled'));

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		$this->data['error'] = $this->error;

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/cod_cdek', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('payment/cod_cdek', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');	
		
		$this->data['view_mode'] = array(
			'all'	=> $this->language->get('text_all'),
			'cdek'	=> $this->language->get('text_cdek')
		);
		
		$this->data['view_mode_cdek'] = array(
			'all'		=> $this->language->get('text_all_tariff'),
			'courier'	=> $this->language->get('text_tariff_courier'),
			'pvz'		=> $this->language->get('text_tariff_pvz'),
		);
		
		if (isset($this->request->post['cod_cdek_mode'])) {
			$this->data['cod_cdek_mode'] = $this->request->post['cod_cdek_mode'];
		} elseif ($this->config->get('cod_cdek_mode')) {
			$this->data['cod_cdek_mode'] = $this->config->get('cod_cdek_mode');
		} else {
			$this->data['cod_cdek_mode'] = 'cdek';
		}
		
		if (isset($this->request->post['cod_cdek_mode_cdek'])) {
			$this->data['cod_cdek_mode_cdek'] = $this->request->post['cod_cdek_mode_cdek'];
		} elseif ($this->config->get('cod_cdek_mode_cdek')) {
			$this->data['cod_cdek_mode_cdek'] = $this->config->get('cod_cdek_mode_cdek');
		} else {
			$this->data['cod_cdek_mode_cdek'] = 'all';
		}
		
		$this->data['discount_type'] = array(
			'fixed'				=> $this->language->get('text_fixed'),
			'percent'			=> $this->language->get('text_percent_product'),
			'percent_total'		=> $this->language->get('text_percent_total')
		);
		
		if (isset($this->request->post['cod_cdek_title'])) {
			$this->data['cod_cdek_title'] = $this->request->post['cod_cdek_title'];
		} else {
			$this->data['cod_cdek_title'] = $this->config->get('cod_cdek_title');
		}
		
		if (isset($this->request->post['cod_cdek_price'])) {
			$this->data['cod_cdek_price'] = $this->request->post['cod_cdek_price'];
		} else {
			$this->data['cod_cdek_price'] = $this->config->get('cod_cdek_price');
		}
		
		if (isset($this->request->post['cod_cdek_active'])) {
			$this->data['cod_cdek_active'] = $this->request->post['cod_cdek_active'];
		} else {
			$this->data['cod_cdek_active'] = $this->config->get('cod_cdek_active');
		}
		
		if (isset($this->request->post['cod_cdek_cache_on_delivery'])) {
			$this->data['cod_cdek_cache_on_delivery'] = $this->request->post['cod_cdek_cache_on_delivery'];
		} elseif ($this->config->get('cod_cdek_cache_on_delivery')) {
			$this->data['cod_cdek_cache_on_delivery'] = $this->config->get('cod_cdek_cache_on_delivery');
		} else {
			$this->data['cod_cdek_cache_on_delivery'] = 1;
		}
		
		if (isset($this->request->post['cod_cdek_min_total'])) {
			$this->data['cod_cdek_min_total'] = $this->request->post['cod_cdek_min_total'];
		} else {
			$this->data['cod_cdek_min_total'] = $this->config->get('cod_cdek_min_total');
		}
		
		if (isset($this->request->post['cod_cdek_max_total'])) {
			$this->data['cod_cdek_max_total'] = $this->request->post['cod_cdek_max_total'];
		} else {
			$this->data['cod_cdek_max_total'] = $this->config->get('cod_cdek_max_total');
		}
		
		if (isset($this->request->post['cod_cdek_store'])) {
			$this->data['cod_cdek_store'] = $this->request->post['cod_cdek_store'];
		} elseif ($this->config->get('cod_cdek_store')) {
			$this->data['cod_cdek_store'] = $this->config->get('cod_cdek_store');
		} else {
			$this->data['cod_cdek_store'] = array();
		}
		
		if (isset($this->request->post['cod_cdek_customer_group_id'])) {
			$this->data['cod_cdek_customer_group_id'] = $this->request->post['cod_cdek_customer_group_id'];
		} else {
			$this->data['cod_cdek_customer_group_id'] = $this->config->get('cod_cdek_customer_group_id');
		}
				
		if (isset($this->request->post['cod_cdek_order_status_id'])) {
			$this->data['cod_cdek_order_status_id'] = $this->request->post['cod_cdek_order_status_id'];
		} else {
			$this->data['cod_cdek_order_status_id'] = $this->config->get('cod_cdek_order_status_id');
		}
		
		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['cod_cdek_geo_zone_id'])) {
			$this->data['cod_cdek_geo_zone_id'] = $this->request->post['cod_cdek_geo_zone_id'];
		} else {
			$this->data['cod_cdek_geo_zone_id'] = $this->config->get('cod_cdek_geo_zone_id');
		}
		
		$this->load->model('localisation/geo_zone');						
		
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['cod_cdek_city_ignore'])) {
			$this->data['cod_cdek_city_ignore'] = $this->request->post['cod_cdek_city_ignore'];
		} else {
			$this->data['cod_cdek_city_ignore'] = $this->config->get('cod_cdek_city_ignore');
		}
		
		if (isset($this->request->post['cod_cdek_status'])) {
			$this->data['cod_cdek_status'] = $this->request->post['cod_cdek_status'];
		} else {
			$this->data['cod_cdek_status'] = $this->config->get('cod_cdek_status');
		}
		
		if (isset($this->request->post['cod_cdek_sort_order'])) {
			$this->data['cod_cdek_sort_order'] = $this->request->post['cod_cdek_sort_order'];
		} else {
			$this->data['cod_cdek_sort_order'] = $this->config->get('cod_cdek_sort_order');
		}
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
		
		$this->data['stores'] = array();
		$this->data['stores'][] = array(
			'store_id' => 0,
			'name'	   => $this->config->get('config_name')
		);
		
		$this->data['stores'] = array_merge($this->data['stores'], $this->model_setting_store->getStores());

		$this->template = 'payment/cod_cdek.tpl';
		
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	
	private function validate() {
		
		if (!$this->user->hasPermission('modify', 'payment/cod_cdek')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		foreach (array('cod_cdek_min_total', 'cod_cdek_max_total', 'cod_cdek_sort_order') as $item) {
			
			if ($this->request->post[$item] != "" && !is_numeric($this->request->post[$item])) {
				$this->error[$item] = $this->language->get('error_numeric');
			}
			
		}
		
		if ($this->request->post['cod_cdek_price']['value'] != '' && !is_numeric($this->request->post['cod_cdek_price']['value'])) {
			$this->error['cod_cdek_price']['value'] = $this->language->get('error_numeric');
		}
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
		
		return !$this->error;
	}
	
	public function install() {
		
		$this->load->model('setting/setting');
		$this->load->model('setting/extension');
		
		$totals = $this->model_setting_extension->getInstalled('total');
		
		if (!in_array('cod_cdek_total', $totals)) {
		
			$this->model_setting_extension->install('total', 'cod_cdek_total');
			$this->model_setting_setting->editSetting('cod_cdek_total', array('cod_cdek_total_status' => 1, 'cod_cdek_total_sort_order' => 2));
		
		}
	}
	
	public function uninstall() {
		
		$this->load->model('setting/setting');
		$this->load->model('setting/extension');
			
		$this->model_setting_extension->uninstall('payment', 'cod_cdek_total');
		$this->model_setting_setting->deleteSetting('cod_cdek_total');
	}
}
?>