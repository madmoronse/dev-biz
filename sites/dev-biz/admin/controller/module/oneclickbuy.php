<?php
class ControllerModuleOneClickBuy extends Controller
{
    private $error = array();
    
    public function index()
    {
        $this->language->load('module/oneclickbuy');

        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->load->model('setting/setting');
                
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('oneclickbuy', $this->request->post);
                    
            $this->session->data['success'] = $this->language->get('text_success');
                        
            $this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
        }
                
        $this->data['heading_title'] = $this->language->get('heading_title');

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
		$this->data['entry_working_hours'] = $this->language->get('entry_working_hours');
		$this->data['entry_timezone'] = $this->language->get('entry_timezone');

        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');
        $this->data['button_add_module'] = $this->language->get('button_add_module');
        $this->data['button_remove'] = $this->language->get('button_remove');
        
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
            'text'      => $this->language->get('text_module'),
            'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );
        
        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('module/oneclickbuy', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );
        
        $this->data['action'] = $this->url->link('module/oneclickbuy', 'token=' . $this->session->data['token'], 'SSL');
        
        $this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['oneclickbuy'] = array();
		if (isset($this->request->post['oneclickbuy'])) {
            $this->data['oneclickbuy'] = $this->request->post['oneclickbuy'];
        } elseif ($this->config->get('oneclickbuy')) {
            $this->data['oneclickbuy'] = $this->config->get('oneclickbuy');
        }
        if (!empty($this->data['oneclickbuy']['timezone'])) {
            $zone = new DateTimeZone($this->data['oneclickbuy']['timezone']);
            $gmt_offset = $zone->getOffset(new DateTime('now', new DateTimeZone('UTC'))) / 3600;
            $sign = ($gmt_offset >= 0) ? '+' : '-';
			$this->data['oneclickbuy']['timezone'] =  $sign . str_pad(abs($gmt_offset), 2, '0', STR_PAD_LEFT) . '00';
        }
		$this->data['modules'] = array();
        if (isset($this->request->post['oneclickbuy_module'])) {
            $this->data['modules'] = $this->request->post['oneclickbuy_module'];
        } elseif ($this->config->get('oneclickbuy_module')) {
            $this->data['modules'] = $this->config->get('oneclickbuy_module');
		}
		$this->data['timezones'] = array();
		for ($i = -12; $i <= 12; $i++) {
			$sign = ($i >= 0) ? '+' : '-';
			$this->data['timezones'][] =  $sign . str_pad(abs($i), 2, '0', STR_PAD_LEFT) . '00';
		}
        
        $this->load->model('design/layout');
        
        $this->data['layouts'] = $this->model_design_layout->getLayouts();
                        
        $this->template = 'module/oneclickbuy.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );
                
        $this->response->setOutput($this->render());
    }
    
    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'module/oneclickbuy')) {
            $this->error['warning'] = $this->language->get('error_permission');
		}
		foreach (array('from', 'to') as $item) {
			if (empty($this->request->post['oneclickbuy']['working_hours'][$item])
				|| $this->request->post['oneclickbuy']['working_hours'][$item] < 0
				|| $this->request->post['oneclickbuy']['working_hours'][$item] > 24
			) {
				$this->error['error_working_hours'] = $this->language->get('error_working_hours');
			}
		}
		if (!isset($this->error['error_working_hours'])
			&& $this->request->post['oneclickbuy']['working_hours']['from'] > $this->request->post['oneclickbuy']['working_hours']['to']
		) {
			$this->error['error_working_hours'] = $this->language->get('error_working_hours');
		}
		preg_match('/^(\+|-)?(\d{2})00$/', $this->request->post['oneclickbuy']['timezone'], $matches);
		if (!isset($matches[2]) || $matches[2] < -12 || $matches[2] > 12) {
			$this->error['error_timezone'] = $this->language->get('error_timezone');
		} else {
            $this->request->post['oneclickbuy']['timezone'] = timezone_name_from_abbr(
                '',
                (($matches[1] === '-') ? -1 : 1) * $matches[2] * 3600,
                0
            );
        }
        if (!$this->error) {
            return true;
        } else {
			$this->error['warning'] = $this->language->get('error_warning');
            return false;
        }
    }
}
