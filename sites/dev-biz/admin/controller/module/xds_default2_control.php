<?php
class ControllerModuleXDSDefault2Control extends Controller {
	private $error = array(); 
	public function index() {  
	
// Подключаем модель языков
		$this->load->model('localisation/language');
		
// Получаем включенные языки
		$this->data['languages'] = $this->model_localisation_language->getLanguages(); 
		
// Подключаем ЯП для модуля
		$this->load->language('module/xds_default2_control');
		
// Подключаем значение заголовка из ЯП
		$this->document->setTitle(preg_replace('(<.*?>)', '', $this->language->get('heading_title')));
		
// Подключаем модель настроек
		$this->load->model('setting/setting');

// Действие кнопок (отпавка формы и отмена)
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('xds_default2_control', $this->request->post);
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$this->data['action'] = $this->url->link('module/xds_default2_control', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

// Получаем значения переменных их языкового пакета
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['heading_main_title'] = $this->language->get('heading_main_title');
		$this->data['heading_shem_title'] = $this->language->get('heading_shem_title');
		
		$this->data['vtab_head_title'] = $this->language->get('vtab_head_title');
		$this->data['vtab_foot_title'] = $this->language->get('vtab_foot_title');
		$this->data['vtab_cap_title'] = $this->language->get('vtab_cap_title');
		
		$this->data['phone_number_title'] = $this->language->get('phone_number_title');
		$this->data['email_text_title'] = $this->language->get('email_text_title');
		$this->data['skype_text_title'] = $this->language->get('skype_text_title');
		
		$this->data['details_text_title'] = $this->language->get('details_text_title');
		$this->data['cap_text_title'] = $this->language->get('cap_text_title');

		
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');


// Хлебные крошки
  	$this->data['breadcrumbs'] = array();

   	$this->data['breadcrumbs'][] = array(
    'text'      => $this->language->get('text_home'),
		'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
    'separator' => false
   	);

   	$this->data['breadcrumbs'][] = array(
    'text'      => $this->language->get('text_module'),
		'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
    'separator' => ' :: '
   	);
		
   	$this->data['breadcrumbs'][] = array(
    'text'      => preg_replace('(<.*?>)', '', $this->language->get('heading_title')),
		'href'      => $this->url->link('module/xds_default2_control', 'token=' . $this->session->data['token'], 'SSL'),
    'separator' => ' :: '
   	);
		

// Таблица схымы отображения
		$this->data['modules'] = array();
		
		if (isset($this->request->post['xds_default2_control_module'])) {
			$this->data['modules'] = $this->request->post['xds_default2_control_module'];
		} elseif ($this->config->get('xds_default2_control_module')) { 
			$this->data['modules'] = $this->config->get('xds_default2_control_module');
		}		
		
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		
// Переменные из формы	
		
		if (isset($this->request->post['phone_number_text'])) {
				$this->data['phone_number_text'] = $this->request->post['phone_number_text'];
			} else {
				$this->data['phone_number_text'] = $this->config->get('phone_number_text');
			}
			
		if (isset($this->request->post['email_text'])) {
				$this->data['email_text'] = $this->request->post['email_text'];
			} else {
				$this->data['email_text'] = $this->config->get('email_text');
			}
		if (isset($this->request->post['skype_text'])) {
				$this->data['skype_text'] = $this->request->post['skype_text'];
			} else {
				$this->data['skype_text'] = $this->config->get('skype_text');
			}		
		
		if (isset($this->request->post['details_text'])) {
				$this->data['details_text'] = $this->request->post['details_text'];
			} else {
				$this->data['details_text'] = $this->config->get('details_text');
			}
			
		if (isset($this->request->post['cap_text'])) {
				$this->data['cap_text'] = $this->request->post['cap_text'];
			} else {
				$this->data['cap_text'] = $this->config->get('cap_text');
			}
			
// Вывод в шаблон
		$this->template = 'module/xds_default2_control.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render());
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/xds_default2_control')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>