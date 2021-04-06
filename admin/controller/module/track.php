<?php
class ControllerModuleTrack extends Controller {

	private $error = array(); 
	
	const VERSION = '1.5.5';
	
	public function index() { 
		$this->load->language('module/track'); 
		 
		$this->document->setTitle($this->language->get('heading_title')); 
 
		$this->load->model('setting/setting'); 


		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('track', $this->request->post);		 
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}


		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_success'] = $this->language->get('text_success');
		$this->data['text_help_auth'] = $this->language->get('text_help_auth');
		$this->data['entry_login'] = $this->language->get('entry_login');
		$this->data['entry_password'] = $this->language->get('entry_password');
		$this->data['error_permission'] = $this->language->get('error_permission');
		$this->data['error_warning'] = $this->language->get('error_warning');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');


		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

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
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/track', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->data['action'] = $this->url->link('module/track', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->request->post['cdek_auth_login'])) {
			$this->data['cdek_auth_login'] = trim($this->request->post['cdek_auth_login']);
		} else {
			$this->data['cdek_auth_login'] = trim($this->config->get('cdek_auth_login'));
		}
		
		if (isset($this->request->post['cdek_auth_password'])) {
			$this->data['cdek_auth_password'] = trim($this->request->post['cdek_auth_password']);
		} else {
			$this->data['cdek_auth_password'] = trim($this->config->get('cdek_auth_password'));
		}

		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
						
		$this->template = 'module/track.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/track')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}

	public function uninstall() {
		
		$this->load->model('setting/setting');
		$this->model_setting_setting->deleteSetting('track');
	}
}
