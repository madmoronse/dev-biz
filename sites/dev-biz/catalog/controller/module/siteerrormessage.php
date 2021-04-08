<?php 
class ControllerModuleSiteerrormessage extends Controller {
	private $error = array(); 
	    
  	public function index() {
	
	
		$siteerrormessage_module_cfg = $this->config->get('siteerrormessage_setting');		
		$this->data['height'] = '500';
		
		if ($siteerrormessage_module_cfg['showfieldtime'] == '1') $this->data['height'] = '575'; 
		if ($siteerrormessage_module_cfg['capcha'] == '1') $this->data['height'] = '600'; 
		if ($siteerrormessage_module_cfg['showfieldtime'] == '1' && $siteerrormessage_module_cfg['capcha'] == '1') $this->data['height'] = '625'; 
		
		if ($siteerrormessage_module_cfg['button_status'] == '1') {
						
			$css_color = $siteerrormessage_module_cfg['button_color'];
						
			if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/siteerrormessage/siteerrormessage_'.$css_color.'.css')) {
				$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/siteerrormessage/siteerrormessage_'.$css_color.'.css');
			} else {
				$this->document->addStyle('catalog/view/theme/default/stylesheet/siteerrormessage/siteerrormessage_'.$css_color.'.css');
			}
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/siteerrormessage_button.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/module/siteerrormessage_button.tpl';
			} else {
					$this->template = 'default/template/module/siteerrormessage_button.tpl';
			}
		
		$this->render();
		}
	
	}
	
	//open form	for siteerrormessage
	public function open() {
		
		$this->language->load('module/siteerrormessage');
		$this->document->setTitle($this->language->get('heading_title')); 
		$siteerrormessage_module_cfg = $this->config->get('siteerrormessage_setting');
		$this->data['link_page'] = $this->config->get('link_page');
	//  $this->data['showfieldtime'] = $siteerrormessage_module_cfg['showfieldtime'];
	//  $this->data['button_status'] = $siteerrormessage_module_cfg['button_status'];		
		$this->data['siteerrormessage_setting'] = $siteerrormessage_module_cfg;
					
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
				
				if ($siteerrormessage_module_cfg['link_page']=='1') {
				$link_page = 'Сообщение отправлено со страницы:' . "\n\n" . $this->request->post['link_page']. "\n\n" ;
				} else {
				$link_page = '';
				}
				
				if (isset($this->request->post['time1'])) {
				$time = $this->request->post['time1']. "--" .$this->request->post['time2'] . "\n\n"  ;
				} else {
				$time = '';
				}
				
			
				
		
			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');				
			$mail->setTo($this->config->get('config_email'));
	  		$mail->setFrom($this->config->get('config_email'));
	  		$mail->setSender('Сообщение об ошибке на сайте');
	  		$mail->setSubject(html_entity_decode(sprintf($this->language->get('email_subject'), ENT_QUOTES, 'UTF-8')));
	  		$mail->setText(html_entity_decode($this->request->post['name']
			.$time
			.$link_page 
			. 'Текст сообщения:' . "\n\n". $this->request->post['enquiry'], ENT_QUOTES, 'UTF-8'));
      		$mail->send();
	  				
			$this->data['success'] = $this->language->get('success');
    	
		}
			
			if (isset($this->error['captcha'])) {
$this->data['error_captcha'] = $this->error['captcha'];
} else {
$this->data['error_captcha'] = '';
}


		if (isset($this->request->post['captcha'])) {
$this->data['captcha'] = $this->request->post['captcha'];
} else {
$this->data['captcha'] = '';
}
			
    	$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['entry_enquiry'] = $this->language->get('entry_enquiry');
    	$this->data['entry_name'] = $this->language->get('entry_name');
    	$this->data['entry_tel'] = $this->language->get('entry_tel');
		$this->data['entry_city'] = $this->language->get('entry_city');
		$this->data['entry_time'] = $this->language->get('entry_time');
		$this->data['yes'] = $this->language->get('yes');
		$this->data['no'] = $this->language->get('no');
		$this->data['qs'] = $this->language->get('qs');
	$this->data['entry_captcha'] = $this->language->get('entry_captcha');

		if (isset($this->error['name'])) {
    		$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}
		
		if (isset($this->error['tel'])) {
			$this->data['error_tel'] = $this->error['tel'];
		} else {
			$this->data['error_tel'] = '';
		}	
		
		if (isset($this->error['city'])) {
			$this->data['error_city'] = $this->error['city'];
		} else {
			$this->data['error_city'] = '';
		}			
		
		if (isset($this->error['enquiry'])) {
			$this->data['error_enquiry'] = $this->error['enquiry'];
		} else {
			$this->data['error_enquiry'] = '';
		}		

		if (isset($this->error['capcha'])) {
			$this->data['error_capcha'] = $this->error['capcha'];
		} else {
			$this->data['error_capcha'] = '';
		}		
	

    	$this->data['button_send'] = $this->language->get('button_send');
    
		$this->data['action'] = $this->url->link('module/siteerrormessage/open');


    	
		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} else {
			$this->data['name'] = $this->customer->getFirstName();
		}

		if (isset($this->request->post['email'])) {
			$this->data['email'] = $this->request->post['email'];
		} else {
			$this->data['email'] = $this->customer->getEmail();
		}
		
		if (isset($this->request->post['tel'])) {
			$this->data['tel'] = $this->request->post['tel'];
		} else {
			$this->data['tel'] = '';
		}
		
		if (isset($this->request->post['city'])) {
			$this->data['city'] = $this->request->post['city'];
		} else {
			$this->data['city'] = '';
		}
		
			if (isset($this->request->post['time1'])) {
				$this->data['time1'] = $this->request->post['time1'];
			} else {
				$this->data['time1'] = $this->language->get('time1');
			}		
			if (isset($this->request->post['time2'])) {
				$this->data['time2'] = $this->request->post['time2'];
			} else {
				$this->data['time2'] = $this->language->get('time2');
			}
			
			if (isset($this->request->post['link_page'])) {
				$this->data['link_page'] = $this->request->post['link_page'];
			}

		
		
		if (isset($this->request->post['enquiry'])) {
			$this->data['enquiry'] = $this->request->post['enquiry'];
		} else {
			$this->data['enquiry'] = '';
		}
		
		

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/siteerrormessage.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/module/siteerrormessage.tpl';
			} else {
				$this->template = 'default/template/module/siteerrormessage.tpl';
			}
				
 		$this->response->setOutput($this->render());		
	}

	public function captcha() {
$this->load->library('captcha');
$captcha = new Captcha();
$this->session->data['captcha'] = $captcha->getCode();
$captcha->showImage();
}
	
  	private function validate() {
	$siteerrormessage_module_cfg = $this->config->get('siteerrormessage_setting');
	
    	if ($siteerrormessage_module_cfg['capcha']=='1') {
			if (isset($this->request->post['irobot_no']) || !isset($this->request->post['irobot_yes'])) {
				$this->error['capcha'] = $this->language->get('error_capcha');
			}
		}

		if (!isset($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
$this->error['captcha'] = $this->language->get('error_captcha');
}
		
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}  	  
  	}

	
}
?>
