<?php
class ControllerShippingCdek extends Controller {
	
	private $error = array(); 
	
	const VERSION = '1.5.5';
	
	public function index() {   
	
		$this->load->language('shipping/cdek');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		if (!extension_loaded('curl')) {
			$this->error['warning'] = $this->language->get('error_curl');
		}
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate() && !isset($this->error['warning'])) {
			
			if ($this->request->post['apply']) {
				$url = $this->url->link('shipping/cdek', 'token=' . $this->session->data['token'], 'SSL');
			} else {
				$url = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');
			}
			
			unset($this->request->post['apply']);
			
			$this->model_setting_setting->editSetting('cdek', $this->request->post);
			
			$this->saveTariffList();
			
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($url);
		}
		
		$this->load->model('sale/customer_group');
		$this->load->model('localisation/geo_zone');
		$this->load->model('localisation/tax_class');
		$this->load->model('localisation/language');
		$this->load->model('localisation/length_class');
		$this->load->model('localisation/weight_class');
		
		$this->document->addStyle('view/stylesheet/cdek.css');
		$this->document->addScript('view/javascript/jquery/jquery.tablednd.0.7.min.js');
				
		$this->data['heading_title'] = $this->language->get('heading_title') . ' v' . self::VERSION;

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_date_current'] = $this->language->get('text_date_current');
		$this->data['text_date_append'] = $this->language->get('text_date_append');
		$this->data['entry_more_days'] = $this->language->get('entry_more_days');
		$this->data['text_day'] = $this->language->get('text_day');
		$this->data['text_insurance_cost'] = $this->language->get('text_insurance_cost');
		$this->data['text_loading'] = $this->language->get('text_loading');
		$this->data['text_help_auth'] = $this->language->get('text_help_auth');
		$this->data['text_drag'] = $this->language->get('text_drag');
		$this->data['text_geo_zone'] = $this->language->get('text_geo_zone');
		$this->data['text_tariff'] = $this->language->get('text_tariff');
		$this->data['text_help_im'] = $this->language->get('text_help_im');
		$this->data['text_show_password'] = $this->language->get('text_show_password');
		$this->data['text_hide_password'] = $this->language->get('text_hide_password');
		$this->data['text_more_attention'] = $this->language->get('text_more_attention');
		$this->data['text_from'] = $this->language->get('text_from');
		$this->data['text_discount_help'] = $this->language->get('text_discount_help');
		$this->data['text_short_length'] = $this->language->get('text_short_length');
		$this->data['text_short_width'] = $this->language->get('text_short_width');
		$this->data['text_short_height'] = $this->language->get('text_short_height');
		$this->data['text_all_tariff'] = $this->language->get('text_all_tariff');
		
		$this->data['entry_log'] = $this->language->get('entry_log');
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_period'] = $this->language->get('entry_period');
		$this->data['entry_delivery_data'] = $this->language->get('entry_delivery_data');
		$this->data['entry_empty_address'] = $this->language->get('entry_empty_address');
		$this->data['entry_show_pvz'] = $this->language->get('entry_show_pvz');
		$this->data['entry_work_mode'] = $this->language->get('entry_work_mode');
		$this->data['entry_max_weight'] = $this->language->get('entry_max_weight');
		$this->data['entry_cache_on_delivery'] = $this->language->get('entry_cache_on_delivery');
		$this->data['entry_city_from'] = $this->language->get('entry_city_from');
		$this->data['entry_length_class'] = $this->language->get('entry_length_class');
		$this->data['entry_weight_class'] = $this->language->get('entry_weight_class');
		$this->data['entry_default_size'] = $this->language->get('entry_default_size');
		$this->data['entry_volume'] = $this->language->get('entry_volume');
		$this->data['entry_default_weight_use'] = $this->language->get('entry_default_weight_use');
		$this->data['entry_default_weight'] = $this->language->get('entry_default_weight');
		$this->data['entry_default_weight_work_mode'] = $this->language->get('entry_default_weight_work_mode');
		$this->data['entry_size'] = $this->language->get('entry_size');
		$this->data['entry_login'] = $this->language->get('entry_login');
		$this->data['entry_password'] = $this->language->get('entry_password');
		$this->data['entry_cost'] = $this->language->get('entry_cost');
		$this->data['entry_tax_class'] = $this->language->get('entry_tax_class');
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_additional_weight'] = $this->language->get('entry_additional_weight');
		$this->data['entry_min_weight'] = $this->language->get('entry_min_weight');
		$this->data['entry_max_weight'] = $this->language->get('entry_max_weight');
		$this->data['entry_min_total'] = $this->language->get('entry_min_total');
		$this->data['entry_max_total'] = $this->language->get('entry_max_total');
		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_date_execute'] = $this->language->get('entry_date_execute');
		$this->data['entry_pvz_more_one'] = $this->language->get('entry_pvz_more_one');
		$this->data['entry_weight_limit'] = $this->language->get('entry_weight_limit');
		$this->data['entry_use_region'] = $this->language->get('entry_use_region');
		$this->data['entry_use_region_russia'] = $this->language->get('entry_use_region_russia');
		$this->data['entry_default_size_type'] = $this->language->get('entry_default_size_type');
		$this->data['entry_default_size_work_mode'] = $this->language->get('entry_default_size_work_mode');
		$this->data['entry_packing_min_weight'] = $this->language->get('entry_packing_min_weight');
		$this->data['entry_packing_additional_weight'] = $this->language->get('entry_packing_additional_weight');
		$this->data['entry_city_ignore'] = $this->language->get('entry_city_ignore');
		$this->data['entry_empty'] = $this->language->get('entry_empty');
		$this->data['entry_empty_cost'] = $this->language->get('entry_empty_cost');
		
		$this->data['column_tariff'] = $this->language->get('column_tariff');
		$this->data['column_title'] = $this->language->get('column_title');
		$this->data['column_mode'] = $this->language->get('column_mode');
		$this->data['column_markup'] = $this->language->get('column_markup');
		$this->data['column_limit'] = $this->language->get('column_limit');
		$this->data['column_customer_group'] = $this->language->get('column_customer_group');
		$this->data['column_geo_zone'] = $this->language->get('column_geo_zone');
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_tax_class'] = $this->language->get('column_tax_class');
		$this->data['column_discount_type'] = $this->language->get('column_discount_type');
		$this->data['column_discount_value'] = $this->language->get('column_discount_value');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_apply'] = $this->language->get('button_apply');
		$this->data['button_remove'] = $this->language->get('button_remove');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_discount'] = $this->language->get('button_add_discount');
		
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_data'] = $this->language->get('tab_data');
		$this->data['tab_auth'] = $this->language->get('tab_auth');
		$this->data['tab_tariff'] = $this->language->get('tab_tariff');
		$this->data['tab_package'] = $this->language->get('tab_package');
		$this->data['tab_discount'] = $this->language->get('tab_discount');
		$this->data['tab_additional'] = $this->language->get('tab_additional');
		$this->data['tab_empty'] = $this->language->get('tab_empty');
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

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
       		'text'      => $this->language->get('text_shipping'),
			'href'      => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('shipping/cdek', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['boolean_variables'] = array($this->language->get('text_no'), $this->language->get('text_yes'));
		
		$this->data['size_types'] = array(
			'volume' => $this->language->get('text_size_type_volume'),
			'size'	 => $this->language->get('text_size_type_size'),
		);
		
		$this->data['default_work_mode'] = array(
			'order'		=> $this->language->get('text_mode_order'),
			'all'		=> $this->language->get('text_mode_product_all'),
			'optional'	=> $this->language->get('text_mode_product_optional')
		);
		
		$this->data['pvz_more_one_action'] = array(
			'first'  => $this->language->get('text_first'),
			'merge'  => $this->language->get('text_merge'),
			'split'  => $this->language->get('text_split')
		);
		
		$this->data['work_mode'] = array(
			'single' => $this->language->get('text_single'),
			'more'	 => $this->language->get('text_more')
		);
		
		$this->data['discount_type'] = array(
			'fixed'				=> $this->language->get('text_fixed'),
			'percent'			=> $this->language->get('text_percent_source_product'),
			'percent_shipping'	=> $this->language->get('text_percent_shipping'),
			'percent_cod'		=> $this->language->get('text_percent_source_cod')
		);
		
		$this->data['additional_weight_mode'] = array(
			'fixed'			=> $this->language->get('text_weight_fixed'),
			'all_percent'	=> $this->language->get('text_weight_all')
		);
		
		$this->data['action'] = $this->url->link('shipping/cdek', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['token'] = $this->session->data['token'];
		
		if (isset($this->request->post['cdek_title'])) {
			$this->data['cdek_title'] = $this->request->post['cdek_title'];
		} else {
			$this->data['cdek_title'] = $this->config->get('cdek_title');
		}
		
		if (isset($this->request->post['cdek_cache_on_delivery'])) {
			$this->data['cdek_cache_on_delivery'] = $this->request->post['cdek_cache_on_delivery'];
		} else {
			$this->data['cdek_cache_on_delivery'] = $this->config->get('cdek_cache_on_delivery');
		}
		
		if (isset($this->request->post['cdek_weight_limit'])) {
			$this->data['cdek_weight_limit'] = $this->request->post['cdek_weight_limit'];
		} else {
			$this->data['cdek_weight_limit'] = $this->config->get('cdek_weight_limit');
		}
		
		if (isset($this->request->post['cdek_use_region'])) {
			$this->data['cdek_use_region'] = $this->request->post['cdek_use_region'];
		} elseif (!is_null($this->config->get('cdek_use_region'))) {
			$this->data['cdek_use_region'] = $this->config->get('cdek_use_region');
		} else {
			$this->data['cdek_use_region'] = 1;
		}
		
		if (isset($this->request->post['cdek_use_region_russia'])) {
			$this->data['cdek_use_region_russia'] = $this->request->post['cdek_use_region_russia'];
		} elseif (!is_null($this->config->get('cdek_use_region_russia'))) {
			$this->data['cdek_use_region_russia'] = $this->config->get('cdek_use_region_russia');
		} else {
			$this->data['cdek_use_region_russia'] = 1;
		}
		
		if (isset($this->request->post['cdek_log'])) {
			$this->data['cdek_log'] = $this->request->post['cdek_log'];
		} else {
			$this->data['cdek_log'] = $this->config->get('cdek_log');
		}
		
		if (isset($this->request->post['cdek_custmer_tariff_list'])) {
			$this->data['cdek_custmer_tariff_list'] = $this->request->post['cdek_custmer_tariff_list'];
		} elseif ($this->config->get('cdek_custmer_tariff_list')) {
			$this->data['cdek_custmer_tariff_list'] = $this->config->get('cdek_custmer_tariff_list');
		} else {
			$this->data['cdek_custmer_tariff_list'] = array();
		}
		
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		$this->data['tariff_list'] = $this->_getTariffList();
		
		$this->data['tariff_mode'] = $this->getTariffMode();
		
		if (isset($this->data['cdek_custmer_tariff_list'])) {
		
			foreach ($this->data['cdek_custmer_tariff_list'] as $tariff_row => $tariff_info) {
				
				if (array_key_exists('tariff_id', $tariff_info) && array_key_exists($tariff_info['tariff_id'], $this->data['tariff_list'])) {
					
					$tariff_id = $tariff_info['tariff_id'];
				
					$title = $this->data['tariff_list'][$tariff_id]['title'];
					
					if (array_key_exists('im', $this->data['tariff_list'][$tariff_id])) {
						$title .= ' ***'; 
					}
					
					$this->data['cdek_custmer_tariff_list'][$tariff_row] += array(
						'tariff_name'	=> $title,
						'mode_name'		=> $this->data['tariff_mode'][$tariff_info['mode_id']]
					);
					
				} else {
					unset($this->data['cdek_tariff_list'][$tariff_row]);
				}
			}
		
		}
		
		if (isset($this->request->post['cdek_work_mode'])) {
			$this->data['cdek_work_mode'] = $this->request->post['cdek_work_mode'];
		} else {
			$this->data['cdek_work_mode'] = $this->config->get('cdek_work_mode');
		}
		
		if (isset($this->request->post['cdek_show_pvz'])) {
			$this->data['cdek_show_pvz'] = $this->request->post['cdek_show_pvz'];
		} else {
			$this->data['cdek_show_pvz'] = $this->config->get('cdek_show_pvz');
		}
		
		if (isset($this->request->post['cdek_pvz_more_one'])) {
			$this->data['cdek_pvz_more_one'] = $this->request->post['cdek_pvz_more_one'];
		} else {
			$this->data['cdek_pvz_more_one'] = $this->config->get('cdek_pvz_more_one');
		}
		
		if (isset($this->request->post['cdek_default_size'])) {
			$this->data['cdek_default_size'] = $this->request->post['cdek_default_size'];
		} else {
			$this->data['cdek_default_size'] = $this->config->get('cdek_default_size');
		}
		
		if (isset($this->request->post['cdek_default_weight'])) {
			$this->data['cdek_default_weight'] = $this->request->post['cdek_default_weight'];
		} else {
			$this->data['cdek_default_weight'] = $this->config->get('cdek_default_weight');
		}
		
		if (isset($this->request->post['cdek_tax_class_id'])) {
			$this->data['cdek_tax_class_id'] = $this->request->post['cdek_tax_class_id'];
		} else {
			$this->data['cdek_tax_class_id'] = $this->config->get('cdek_tax_class_id');
		}
		
		if (isset($this->request->post['cdek_geo_zone_id'])) {
			$this->data['cdek_geo_zone_id'] = $this->request->post['cdek_geo_zone_id'];
		} else {
			$this->data['cdek_geo_zone_id'] = $this->config->get('cdek_geo_zone_id');
		}
		
		if (isset($this->request->post['cdek_customer_group_id'])) {
			$this->data['cdek_customer_group_id'] = $this->request->post['cdek_customer_group_id'];
		} else {
			$this->data['cdek_customer_group_id'] = $this->config->get('cdek_customer_group_id');
		}
		
		if (isset($this->request->post['cdek_status'])) {
			$this->data['cdek_status'] = $this->request->post['cdek_status'];
		} else {
			$this->data['cdek_status'] = $this->config->get('cdek_status');
		}
		
		if (isset($this->request->post['cdek_period'])) {
			$this->data['cdek_period'] = $this->request->post['cdek_period'];
		} else {
			$this->data['cdek_period'] = $this->config->get('cdek_period');
		}
		
		if (isset($this->request->post['cdek_delivery_data'])) {
			$this->data['cdek_delivery_data'] = $this->request->post['cdek_delivery_data'];
		} else {
			$this->data['cdek_delivery_data'] = $this->config->get('cdek_delivery_data');
		}
		
		if (isset($this->request->post['cdek_empty_address'])) {
			$this->data['cdek_empty_address'] = $this->request->post['cdek_empty_address'];
		} else {
			$this->data['cdek_empty_address'] = $this->config->get('cdek_empty_address');
		}
		
		if (isset($this->request->post['cdek_min_weight'])) {
			$this->data['cdek_min_weight'] = $this->request->post['cdek_min_weight'];
		} else {
			$this->data['cdek_min_weight'] = $this->config->get('cdek_min_weight');
		}
		
		if (isset($this->request->post['cdek_max_weight'])) {
			$this->data['cdek_max_weight'] = $this->request->post['cdek_max_weight'];
		} else {
			$this->data['cdek_max_weight'] = $this->config->get('cdek_max_weight');
		}
		
		if (isset($this->request->post['cdek_min_total'])) {
			$this->data['cdek_min_total'] = $this->request->post['cdek_min_total'];
		} else {
			$this->data['cdek_min_total'] = $this->config->get('cdek_min_total');
		}
		
		if (isset($this->request->post['cdek_max_total'])) {
			$this->data['cdek_max_total'] = $this->request->post['cdek_max_total'];
		} else {
			$this->data['cdek_max_total'] = $this->config->get('cdek_max_total');
		}
		
		if (isset($this->request->post['cdek_city_from'])) {
			$this->data['cdek_city_from'] = $this->request->post['cdek_city_from'];
		} else {
			$this->data['cdek_city_from'] = $this->config->get('cdek_city_from');
		}
		
		if (isset($this->request->post['cdek_length_class_id'])) {
			$this->data['cdek_length_class_id'] = $this->request->post['cdek_length_class_id'];
		} elseif ($this->config->get('cdek_length_class_id')) {
			$this->data['cdek_length_class_id'] = $this->config->get('cdek_length_class_id');
		} else {
			$this->data['cdek_length_class_id'] = 1;
		}
		
		if (isset($this->request->post['cdek_weight_class_id'])) {
			$this->data['cdek_weight_class_id'] = $this->request->post['cdek_weight_class_id'];
		} elseif ($this->config->get('cdek_weight_class_id')) {
			$this->data['cdek_weight_class_id'] = $this->config->get('cdek_weight_class_id');
		} else {
			$this->data['cdek_weight_class_id'] = 1;
		}
		
		if (isset($this->request->post['cdek_city_from_id'])) {
			$this->data['cdek_city_from_id'] = $this->request->post['cdek_city_from_id'];
		} else {
			$this->data['cdek_city_from_id'] = $this->config->get('cdek_city_from_id');
		}
		
		if (isset($this->request->post['cdek_append_day'])) {
			$this->data['cdek_append_day'] = $this->request->post['cdek_append_day'];
		} else {
			$this->data['cdek_append_day'] = (int)$this->config->get('cdek_append_day');
		}
		
		if (isset($this->request->post['cdek_more_days'])) {
			$this->data['cdek_more_days'] = $this->request->post['cdek_more_days'];
		} else {
			$this->data['cdek_more_days'] = (int)$this->config->get('cdek_more_days');
		}
		
		if (isset($this->request->post['cdek_login'])) {
			$this->data['cdek_login'] = $this->request->post['cdek_login'];
		} else {
			$this->data['cdek_login'] = $this->config->get('cdek_login');
		}
		
		if (isset($this->request->post['cdek_password'])) {
			$this->data['cdek_password'] = $this->request->post['cdek_password'];
		} else {
			$this->data['cdek_password'] = $this->config->get('cdek_password');
		}
		
		if (isset($this->request->post['cdek_store'])) {
			$this->data['cdek_store'] = $this->request->post['cdek_store'];
		} elseif($this->config->get('cdek_store')) {
			$this->data['cdek_store'] = $this->config->get('cdek_store');
		} else {
			$this->data['cdek_store'] = array();
		}
		
		if (isset($this->request->post['cdek_sort_order'])) {
			$this->data['cdek_sort_order'] = $this->request->post['cdek_sort_order'];
		} else {
			$this->data['cdek_sort_order'] = $this->config->get('cdek_sort_order');
		}
		
		if (isset($this->request->post['cdek_packing_weight_class_id'])) {
			$this->data['cdek_packing_weight_class_id'] = $this->request->post['cdek_packing_weight_class_id'];
		} else {
			$this->data['cdek_packing_weight_class_id'] = $this->config->get('cdek_packing_weight_class_id');
		}
		
		if (isset($this->request->post['cdek_packing_prefix'])) {
			$this->data['cdek_packing_prefix'] = $this->request->post['cdek_packing_prefix'];
		} else {
			$this->data['cdek_packing_prefix'] = $this->config->get('cdek_packing_prefix');
		}
		
		if (isset($this->request->post['cdek_packing_mode'])) {
			$this->data['cdek_packing_mode'] = $this->request->post['cdek_packing_mode'];
		} else {
			$this->data['cdek_packing_mode'] = $this->config->get('cdek_packing_mode');
		}
		
		if (isset($this->request->post['cdek_packing_value'])) {
			$this->data['cdek_packing_value'] = $this->request->post['cdek_packing_value'];
		} else {
			$this->data['cdek_packing_value'] = $this->config->get('cdek_packing_value');
		}
		
		if (isset($this->request->post['cdek_packing_min_weight'])) {
			$this->data['cdek_packing_min_weight'] = $this->request->post['cdek_packing_min_weight'];
		} else {
			$this->data['cdek_packing_min_weight'] = $this->config->get('cdek_packing_min_weight');
		}
		
		if (isset($this->request->post['cdek_discounts'])) {
			$this->data['cdek_discounts'] = $this->request->post['cdek_discounts'];
		} elseif ($this->config->get('cdek_discounts')) {
			$this->data['cdek_discounts'] = $this->config->get('cdek_discounts');
		} else {
			$this->data['cdek_discounts'] = array();
		}
		
		if (isset($this->request->post['cdek_city_ignore'])) {
			$this->data['cdek_city_ignore'] = $this->request->post['cdek_city_ignore'];
		} else {
			$this->data['cdek_city_ignore'] = $this->config->get('cdek_city_ignore');
		}
		
		if (isset($this->request->post['cdek_empty'])) {
			$this->data['cdek_empty'] = $this->request->post['cdek_empty'];
		} else {
			$this->data['cdek_empty'] = $this->config->get('cdek_empty');
		}
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
		$this->data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();
		$this->data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();
		
		$this->load->model('setting/store');
		
		$this->data['stores'] = array();
		$this->data['stores'][] = array(
			'store_id' => 0,
			'name'	   => $this->language->get('text_store_default')
		);
		
		$this->data['stores'] = array_merge($this->data['stores'], $this->model_setting_store->getStores());
		
		$this->template = 'shipping/cdek.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	
	private function validate() {
		
		if (!$this->user->hasPermission('modify', 'shipping/cdek')) {
			$this->error['warning'] = $this->language->get('error_permission');
		} else {
			
			if (!isset($this->request->post['cdek_city_from_id']) || $this->request->post['cdek_city_from_id'] == 0) {
				$this->error['cdek_city_from'] = $this->language->get('error_cdek_city_from');
			}
			
			foreach (array('cdek_weight_class_id', 'cdek_length_class_id') as $item) {
				if (!$this->request->post[$item]) $this->error[$item] = $this->language->get('error_empty');
			}
			
			if ($this->request->post['cdek_default_size']['use']) {
				
				$default_size = $this->request->post['cdek_default_size'];
				
				switch ($default_size['type']) {
					case 'volume':
					
						if (!is_numeric($default_size['volume'])) {
							$this->error['cdek_default_size']['volume'] = $this->language->get('error_numeric');
						} elseif ($default_size['volume'] <= 0) {
							$this->error['cdek_default_size']['volume'] = $this->language->get('error_positive_numeric');
						}
					
						break;
					case 'size':
					
						foreach (array('size_a', 'size_b', 'size_c') as $item) {
							
							if (!is_numeric($default_size[$item])) {
								$this->error['cdek_default_size']['size'] = $this->language->get('error_numeric');
								break;
							} elseif ($default_size[$item] <= 0) {
								$this->error['cdek_default_size']['size'] = $this->language->get('error_positive_numeric');
								break;
							}
							
						}
					
						break;
				}
				
			}
			
			if ($this->request->post['cdek_default_weight']['use']) {
				
				$default_weight = $this->request->post['cdek_default_weight'];
				
				if (!is_numeric($default_weight['value'])) {
					$this->error['cdek_default_weight']['value'] = $this->language->get('error_numeric');
				} elseif ($default_weight['value'] <= 0) {
					$this->error['cdek_default_weight']['value'] = $this->language->get('error_positive_numeric');
				}
				
			}
			
			foreach (array('cdek_append_day', 'cdek_max_weight', 'cdek_min_weight', 'cdek_min_total', 'cdek_max_total', 'cdek_sort_order', 'cdek_packing_value', 'cdek_more_days') as $item) {
				if ($this->request->post[$item] != "" && !is_numeric($this->request->post[$item])) {
					$this->error[$item] = $this->language->get('error_numeric');
				}
			}
			
			if ($this->request->post['cdek_packing_min_weight'] != "") {
				
				if (!is_numeric($this->request->post['cdek_packing_min_weight'])) {
					$this->error['cdek_packing_min_weight'] = $this->language->get('error_numeric');
				} elseif ($this->request->post['cdek_packing_min_weight'] <= 0) {
					$this->error['cdek_packing_min_weight'] = $this->language->get('error_positive_numeric');
				}
				
			}
			
			if (!isset($this->request->post['cdek_custmer_tariff_list']) || empty($this->request->post['cdek_custmer_tariff_list'])) {
				$this->error['tariff_list'] = $this->language->get('error_tariff_list');
			} else {
				
				$geo_zones = $tariff_exists = array();
				
				$this->load->model('localisation/geo_zone');
				
				foreach ($this->model_localisation_geo_zone->getGeoZones() as $item) {
					$geo_zones[$item['geo_zone_id']] = '«' . $item['name'] . '»';
				}
				
				foreach ($this->request->post['cdek_custmer_tariff_list'] as $tariff_row => $tariff_info) {
					
					$tariff_id = $tariff_info['tariff_id'];
					
					foreach (array('max_weight', 'min_weight', 'min_total', 'max_total') as $item) {
						if ($tariff_info[$item] != "" && !is_numeric($tariff_info[$item])) {
							$this->error['tariff_list_item'][$tariff_row][$item] = $this->language->get('error_numeric');
						} elseif (is_numeric($tariff_info[$item]) && $tariff_info[$item] <= 0) {
							$this->error['tariff_list_item'][$tariff_row][$item] = $this->language->get('error_positive_numeric');
						}
					}
					
					$geo_zone = !empty($tariff_info['geo_zone']) ? array_flip($tariff_info['geo_zone']) : array('all' => 'all');
					
					if (array_key_exists($tariff_id, $tariff_exists)) {
						
						$exists = array_intersect_key($geo_zone, $tariff_exists[$tariff_id]);
						
						if (!empty($exists)) {
							
							$error_zones = array();
							
							foreach (array_keys($exists) as $zone_id) {
								if (array_key_exists($zone_id, $geo_zones)) {
									$error_zones[] = $geo_zones[$zone_id];
								} elseif ($zone_id == 'all')  {
									$error_zones[] = 'все регионы';
								}
							}
							
							if (!empty($error_zones)) {
								$this->error['tariff_list_item'][$tariff_row]['exists'] = sprintf($this->language->get('error_tariff_item_exists'), implode(', ', array_unique($error_zones)));
							}
						}
						
						$tariff_exists[$tariff_id] += $geo_zone;
						
					} else {
						$tariff_exists[$tariff_id] = $geo_zone;
					}
					
				}
				
			}
			
			if (!empty($this->request->post['cdek_discounts'])) {
				
				foreach ($this->request->post['cdek_discounts'] as $discount_row => $discount_data) {
					
					if ($discount_data['total'] == "" || !is_numeric($discount_data['total'])) {
						$this->error['cdek_discounts'][$discount_row]['total'] = $this->language->get('error_numeric');
					}
					
					if ($discount_data['value'] == '') {
						$this->error['cdek_discounts'][$discount_row]['value'] = $this->language->get('error_empty');
					} elseif (!is_numeric($discount_data['value'])) {
						$this->error['cdek_discounts'][$discount_row]['value'] = $this->language->get('error_numeric');
					}
					
				}
			}
			
			if (!empty($this->request->post['cdek_empty']['use'])) {
				
				if ($this->request->post['cdek_empty']['cost'] != "" && !is_numeric($this->request->post['cdek_empty']['cost'])) {
					$this->error['cdek_empty']['cost'] = $this->language->get('error_numeric');
				}
				
			}
			
			if ($this->error && !isset($this->error['warning'])) {
				$this->error['warning'] = $this->language->get('error_warning');
			}
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
	
	private function getTariffMode() {
		return array(
			1 => 'дверь-дверь (Д-Д)',
			2 => 'дверь-склад (Д-С)',
			3 => 'склад-дверь (С-Д)',
			4 => 'склад-склад (С-С)'
		);
	}
	
	private function _getTariffList() {
		return array(
			'1'	=> array(
				'title'		=> 'Экспресс лайт (Д-Д)',
				'mode_id'	=> 1
			),
			'3' => array(
				'title'		=> 'Супер-экспресс до 18 (Д-Д)',
				'mode_id'	=> 1
			),
			'4' => array(
				'title'		=> 'Рассылка (Д-Д)',
				'mode_id'	=> 1
			),
			'5' => array(
				'title'		=> 'Экономичный экспресс (С-С)',
				'mode_id'	=> 4
			),
			'7' => array(
				'title'		=> 'Международный экспресс документы (Д-Д)',
				'mode_id'	=> 1
			),
			'8' => array(
				'title'		=> 'Международный экспресс грузы (Д-Д)',
				'mode_id'	=> 1
			),
			'10' => array(
				'title'		=> 'Экспресс лайт (С-С)',
				'mode_id'	=> 4
			), 
			'11' => array(
				'title'		=> 'Экспресс лайт (С-Д)',
				'mode_id'	=> 3
			), 
			'12' => array(
				'title'		=> 'Экспресс лайт (Д-С)',
				'mode_id'	=> 2
			), 
			'15' => array(
				'title'		=> 'Экспресс тяжеловесы (С-С)',
				'mode_id'	=> 4
			),
			'16' => array(
				'title'		=> 'Экспресс тяжеловесы (С-Д)',
				'mode_id'	=> 3
			), 
			'17' => array(
				'title'		=> 'Экспресс тяжеловесы (Д-С)',
				'mode_id'	=> 2
			), 
			'18' => array(
				'title'		=> 'Экспресс тяжеловесы (Д-Д)',
				'mode_id'	=> 1
			), 
			'57' => array(
				'title'		=> 'Супер-экспресс до 9 (Д-Д)',
				'mode_id'	=> 1
			),
			'58' => array(
				'title'		=> 'Супер-экспресс до 10 (Д-Д)',
				'mode_id'	=> 1
			), 
			'59' => array(
				'title'		=> 'Супер-экспресс до 12 (Д-Д)',
				'mode_id'	=> 1
			), 
			'60' => array(
				'title'		=> 'Супер-экспресс до 14 (Д-Д)',
				'mode_id'	=> 1
			),
			'61' => array(
				'title'		=> 'Супер-экспресс до 16 (Д-Д)',
				'mode_id'	=> 1
			),
			'62' => array(
				'title'		=> 'Магистральный экспресс (С-С)',
				'mode_id'	=> 4
			),
			'63' => array(
				'title'		=> 'Магистральный супер-экспресс (С-С)',
				'mode_id'	=> 4
			),
			'66' => array(
				'title'		=> 'Блиц-экспресс 01 (Д-Д)',
				'mode_id'	=> 1
			),
			'67' => array(
				'title'		=> 'Блиц-экспресс 02 (Д-Д)',
				'mode_id'	=> 1
			), 
			'68' => array(
				'title'		=> 'Блиц-экспресс 03 (Д-Д)',
				'mode_id'	=> 1
			), 
			'69' => array(
				'title'		=> 'Блиц-экспресс 04 (Д-Д)',
				'mode_id'	=> 1
			), 
			'70' => array(
				'title'		=> 'Блиц-экспресс 05 (Д-Д)',
				'mode_id'	=> 1
			), 
			'71' => array(
				'title'		=> 'Блиц-экспресс 06 (Д-Д)',
				'mode_id'	=> 1
			), 
			'72' => array(
				'title'		=> 'Блиц-экспресс 07 (Д-Д)',
				'mode_id'	=> 1
			), 
			'73' => array(
				'title'		=> 'Блиц-экспресс 08 (Д-Д)',
				'mode_id'	=> 1
			), 
			'74' => array(
				'title'		=> 'Блиц-экспресс 09 (Д-Д)',
				'mode_id'	=> 1
			), 
			'75' => array(
				'title'		=> 'Блиц-экспресс 10 (Д-Д)',
				'mode_id'	=> 1
			), 
			'76' => array(
				'title'		=> 'Блиц-экспресс 11 (Д-Д)',
				'mode_id'	=> 1
			), 
			'77' => array(
				'title'		=> 'Блиц-экспресс 12 (Д-Д)',
				'mode_id'	=> 1
			), 
			'78' => array(
				'title'		=> 'Блиц-экспресс 13 (Д-Д)',
				'mode_id'	=> 1
			), 
			'79' => array(
				'title'		=> 'Блиц-экспресс 14 (Д-Д)',
				'mode_id'	=> 1
			), 
			'80' => array(
				'title'		=> 'Блиц-экспресс 15 (Д-Д)',
				'mode_id'	=> 1
			), 
			'81' => array(
				'title'		=> 'Блиц-экспресс 16 (Д-Д)',
				'mode_id'	=> 1
			),
			'82' => array(
				'title'		=> 'Блиц-экспресс 17 (Д-Д)',
				'mode_id'	=> 1
			), 
			'83' => array(
				'title'		=> 'Блиц-экспресс 18 (Д-Д)',
				'mode_id'	=> 1
			), 
			'84' => array(
				'title'		=> 'Блиц-экспресс 19 (Д-Д)',
				'mode_id'	=> 1
			), 
			'85' => array(
				'title'		=> 'Блиц-экспресс 20 (Д-Д)',
				'mode_id'	=> 1
			), 
			'86' => array(
				'title'		=> 'Блиц-экспресс 21 (Д-Д)',
				'mode_id'	=> 1
			), 
			'87' => array(
				'title'		=> 'Блиц-экспресс 22 (Д-Д)',
				'mode_id'	=> 1
			), 
			'88' => array(
				'title'		=> 'Блиц-экспресс 23 (Д-Д)',
				'mode_id'	=> 1
			),
			'89' => array(
				'title'		=> 'Блиц-экспресс 24 (Д-Д)',
				'mode_id'	=> 1
			),
			'136' => array(
				'title'		=> 'Посылка (С-С)',
				'mode_id'	=> 4,
				'im'		=> 1
			),
			'137' => array(
				'title'		=> 'Посылка (С-Д)',
				'mode_id'	=> 3,
				'im'		=> 1
			),
			'138' => array(
				'title'		=> 'Посылка (Д-С)',
				'mode_id'	=> 2,
				'im'		=> 1
			),
			'139' => array(
				'title'		=> 'Посылка (Д-Д)',
				'mode_id'	=> 1,
				'im'		=> 1
			),
			'140' => array(
				'title'		=> 'Возврат (С-С)',
				'mode_id'	=> 4,
				'im'		=> 1
			),
			'141' => array(
				'title'		=> 'Возврат (С-Д)',
				'mode_id'	=> 3,
				'im'		=> 1
			),
			'142' => array(
				'title'		=> 'Возврат (Д-С)',
				'mode_id'	=> 2,
				'im'		=> 1
			)
		);
	}
	
	private function getURL($url, $data = array()) {
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_TIMEOUT, 6);
		
		if (!empty($data)) {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
		
		$out = curl_exec($ch);
		
		return json_decode($out, TRUE);
	}
	
	private function getStores() {
		
		$this->load->model('setting/store');
		
		$stores = $this->model_setting_store->getStores();
        $stores[] = array('store_id' => 0, 'name' => $this->config->get('config_name'));
		
		return $stores;
	}
	
	private function saveTariffList() {
		
		$this->load->model('setting/setting');
		
		$tariff_list = array(
			'cdek_tariff_list' => $this->_getTariffList()
		);
		
		foreach ($this->getStores() as $key => $store_info) {
            $this->model_setting_setting->editSetting('cdek_tariff_list', $tariff_list, $store_info['store_id']);
        }
		
	}
	
	public function uninstall() {
		
		$this->load->model('setting/setting');
		
		foreach ($this->getStores() as $key => $store_info) {
			$this->model_setting_setting->deleteSetting('cdek_tariff_list', $store_info['store_id']);
		}
		
	}
}
?>