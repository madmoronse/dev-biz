<?php
class ControllerModuleXDSCustomMenu extends Controller {
	private $error = array(); 
	public function index() {  
	
// Подключаем модель языков
		$this->load->model('localisation/language');
		
// Получаем включенные языки
		$this->data['languages'] = $this->model_localisation_language->getLanguages(); 
		
// Подключаем ЯП для модуля
		$this->load->language('module/xds_custom_menu');
		
// Подключаем значение заголовка из ЯП
		$this->document->setTitle(preg_replace('(<.*?>)', '', $this->language->get('heading_title')));
		
// Подключаем модель настроек
		$this->load->model('setting/setting');

// Действие кнопок (отпавка формы и отмена)
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('xds_custom_menu', $this->request->post);
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$this->data['action'] = $this->url->link('module/xds_custom_menu', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

// Получаем значения переменных их языкового пакета
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['heading_items_title'] = $this->language->get('heading_items_title');
		$this->data['heading_social_title'] = $this->language->get('heading_social_title');
		$this->data['heading_shem_title'] = $this->language->get('heading_shem_title');
		$this->data['table_pos_title'] = $this->language->get('table_pos_title');
		$this->data['table_name_title'] = $this->language->get('table_name_title');
		$this->data['table_href_title'] = $this->language->get('table_href_title');
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
		'href'      => $this->url->link('module/xds_custom_menu', 'token=' . $this->session->data['token'], 'SSL'),
    'separator' => ' :: '
   	);
		

// Таблица схымы отображения
		$this->data['modules'] = array();
		
		if (isset($this->request->post['xds_custom_menu_module'])) {
			$this->data['modules'] = $this->request->post['xds_custom_menu_module'];
		} elseif ($this->config->get('xds_custom_menu_module')) { 
			$this->data['modules'] = $this->config->get('xds_custom_menu_module');
		}		
		
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		
// Пункты меню
// --- 1
		$this->data['item1_title'] = $this->language->get('item1_title');
		if (isset($this->request->post['item1'])) {
				$this->data['item1'] = $this->request->post['item1'];
			} else {
				$this->data['item1'] = $this->config->get('item1');
			}
		if (isset($this->request->post['item1_href'])) {
				$this->data['item1_href'] = $this->request->post['item1_href'];
			} else {
				$this->data['item1_href'] = $this->config->get('item1_href');
			}
			
// --- 2
		$this->data['item2_title'] = $this->language->get('item2_title');
		if (isset($this->request->post['item2'])) {
				$this->data['item2'] = $this->request->post['item2'];
			} else {
				$this->data['item2'] = $this->config->get('item2');
			}
		if (isset($this->request->post['item2_href'])) {
				$this->data['item2_href'] = $this->request->post['item2_href'];
			} else {
				$this->data['item2_href'] = $this->config->get('item2_href');
			}

// --- 3			
		$this->data['item3_title'] = $this->language->get('item3_title');
		if (isset($this->request->post['item3'])) {
				$this->data['item3'] = $this->request->post['item3'];
			} else {
				$this->data['item3'] = $this->config->get('item3');
			}
		if (isset($this->request->post['item3_href'])) {
				$this->data['item3_href'] = $this->request->post['item3_href'];
			} else {
				$this->data['item3_href'] = $this->config->get('item3_href');
			}

// --- 4
		$this->data['item4_title'] = $this->language->get('item4_title');
		if (isset($this->request->post['item4'])) {
				$this->data['item4'] = $this->request->post['item4'];
			} else {
				$this->data['item4'] = $this->config->get('item4');
			}
		if (isset($this->request->post['item4_href'])) {
				$this->data['item4_href'] = $this->request->post['item4_href'];
			} else {
				$this->data['item4_href'] = $this->config->get('item4_href');
			}

// --- 5
		$this->data['item5_title'] = $this->language->get('item5_title');
		if (isset($this->request->post['item5'])) {
				$this->data['item5'] = $this->request->post['item5'];
			} else {
				$this->data['item5'] = $this->config->get('item5');
			}
		if (isset($this->request->post['item5_href'])) {
				$this->data['item5_href'] = $this->request->post['item5_href'];
			} else {
				$this->data['item5_href'] = $this->config->get('item5_href');
			}
			
// Социальные сети
		$this->data['odnoklassniki_title'] = $this->language->get('odnoklassniki_title');
		if (isset($this->request->post['odnoklassniki_href'])) {
				$this->data['odnoklassniki_href'] = $this->request->post['odnoklassniki_href'];
			} else {
				$this->data['odnoklassniki_href'] = $this->config->get('odnoklassniki_href');
			}
		
		$this->data['vkontakte_title'] = $this->language->get('vkontakte_title');
		if (isset($this->request->post['vkontakte_href'])) {
				$this->data['vkontakte_href'] = $this->request->post['vkontakte_href'];
			} else {
				$this->data['vkontakte_href'] = $this->config->get('vkontakte_href');
			}
		
		$this->data['facebook_title'] = $this->language->get('facebook_title');
		if (isset($this->request->post['facebook_href'])) {
				$this->data['facebook_href'] = $this->request->post['facebook_href'];
			} else {
				$this->data['facebook_href'] = $this->config->get('facebook_href');
			}
		
		$this->data['twitter_title'] = $this->language->get('twitter_title');
		if (isset($this->request->post['twitter_href'])) {
				$this->data['twitter_href'] = $this->request->post['twitter_href'];
			} else {
				$this->data['twitter_href'] = $this->config->get('twitter_href');
			}
		
		$this->data['googleplus_title'] = $this->language->get('googleplus_title');
		if (isset($this->request->post['googleplus_href'])) {
				$this->data['googleplus_href'] = $this->request->post['googleplus_href'];
			} else {
				$this->data['googleplus_href'] = $this->config->get('googleplus_href');
			}

// Вывод в шаблон
		$this->template = 'module/xds_custom_menu.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render());
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/xds_custom_menu')) {
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