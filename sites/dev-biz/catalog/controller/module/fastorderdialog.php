<?php 
class ControllerModuleFastorderdialog extends Controller {
	private $error = array(); 
	    
  	public function index() {
	
	
		$fastorderdialog_module_cfg = $this->config->get('fastorderdialog_setting');		
		$this->data['height'] = '500';
		
		if ($fastorderdialog_module_cfg['showfieldtime'] == '1') $this->data['height'] = '575'; 
		if ($fastorderdialog_module_cfg['capcha'] == '1') $this->data['height'] = '600'; 
		if ($fastorderdialog_module_cfg['showfieldtime'] == '1' && $fastorderdialog_module_cfg['capcha'] == '1') $this->data['height'] = '625'; 
		
			
			
	
	}
	
	//open form	for fastorderdialog
	public function open() {
		
		if (isset($this->request->get['remove'])) {
          	$this->cart->remove($this->request->get['remove']);
			
			unset($this->session->data['vouchers'][$this->request->get['remove']]);
      	}
		
		$this->data['text_items'] = "Товаров в корзине: " . sprintf($this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total));
		
		// Totals
		$this->load->model('setting/extension');
		
		$total_data = array();					
		$total = 0;
		$taxes = $this->cart->getTaxes();
		
		// Display prices
		if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
			$sort_order = array(); 
			
			$results = $this->model_setting_extension->getExtensions('total');
			
			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
			}
			
			array_multisort($sort_order, SORT_ASC, $results);
			
			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('total/' . $result['code']);
		
					$this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
				}
				
				$sort_order = array(); 
			  
				foreach ($total_data as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}
	
				array_multisort($sort_order, SORT_ASC, $total_data);			
			}		
		}
		
		$this->data['totals'] = $total_data;
		
		$this->load->model('tool/image');
		
			$this->data['products'] = array();
			$text = "Товары в заказе:";	
			foreach ($this->cart->getProducts() as $product) {
				$text .=  "\n\n" . $product['quantity'] . ' шт. ' . $product['name'] . ' (Артикул: ' . $product['product_id'] .') ' . str_replace(" ","",html_entity_decode($this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']), ENT_NOQUOTES, 'UTF-8')) . " ";
				if ($product['image']) {
					$image = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height'));
				} else {
					$image = '';
				}
								
				$option_data = array();
				
				foreach ($product['option'] as $option) {
					if ($option['type'] != 'file') {
						$value = $option['option_value'];	
					} else {
						$filename = $this->encryption->decrypt($option['option_value']);
						
						$value = utf8_substr($filename, 0, utf8_strrpos($filename, '.'));
					}				
					
					$option_data[] = array(								   
						'name'  => $option['name'],
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value),
						'type'  => $option['type']
					);
					$text .= chr(9) . '-' . $option['name'] . ' ' . (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value) . "\n\n";
				}
				
				// Display prices
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}
				
				// Display prices
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$total = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']);
				} else {
					$total = false;
				}
														
				$this->data['products'][] = array(
					'key'      => $product['key'],
					'thumb'    => $image,
					'name'     => $product['name'],
					'product_id'     => $product['product_id'],
					'model'    => $product['model'], 
					'option'   => $option_data,
					'quantity' => $product['quantity'],
					'price'    => $price,	
					'total'    => $total,	
					'href'     => $this->url->link('product/product', 'product_id=' . $product['product_id'])		
				);
			}
		
		$this->language->load('module/fastorderdialog');
		$this->document->setTitle($this->language->get('heading_title')); 
		$fastorderdialog_module_cfg = $this->config->get('fastorderdialog_setting');
		$this->data['link_page'] = $this->config->get('link_page');
	//  $this->data['showfieldtime'] = $fastorderdialog_module_cfg['showfieldtime'];
	//  $this->data['button_status'] = $fastorderdialog_module_cfg['button_status'];		
		$this->data['fastorderdialog_setting'] = $fastorderdialog_module_cfg;
					
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
				
				if ($fastorderdialog_module_cfg['link_page']=='1') {
				$link_page = 'Сообщение отправлено со страницы:' . "\n\n" . $this->request->post['link_page']. "\n\n" ;
				} else {
				$link_page = '';
				}
				
				if (isset($this->request->post['time1'])) {
				$time = $this->request->post['time1']. "--" .$this->request->post['time2'] . "\n\n"  ;
				} else {
				$time = '';
				}
				
			$this->data['success'] = $this->language->get('success');	
		
			$mail_manager = new Mail();
			$mail_manager->protocol = $this->config->get('config_mail_protocol');
			$mail_manager->parameter = $this->config->get('config_mail_parameter');
			$mail_manager->hostname = $this->config->get('config_smtp_host');
			$mail_manager->username = $this->config->get('config_smtp_username');
			$mail_manager->password = $this->config->get('config_smtp_password');
			$mail_manager->port = $this->config->get('config_smtp_port');
			$mail_manager->timeout = $this->config->get('config_smtp_timeout');				
			$mail_manager->setTo($this->config->get('config_email'));
	  		$mail_manager->setFrom($this->config->get('config_email'));
	  		$mail_manager->setSender('Заказ Outmaxshop.ru');
	  		$mail_manager->setSubject(html_entity_decode(sprintf($this->language->get('email_subject'), ENT_QUOTES, 'UTF-8')));
	  		$mail_manager->setText(html_entity_decode("Вы получили заказ от "
			.$this->request->post['name'] . " " . $this->request->post['email'] . " " . $this->request->post['city'] . "\n\n"
			. "Телефон покупателя:" . $this->request->post['tel'] . "\n\n" 
			."Дата заказа: " .date('d.m.Y') . "\n\n" 
			//.$link_page 
			.$text. "\n\n"
			. 'Комментарий к заказу:' . "\n\n". $this->request->post['enquiry'] . "\n\n"
			, ENT_QUOTES, 'UTF-8'));
      		$mail_manager->send();
			
			if (isset($this->request->post['email']) && $this->request->post['email'] != '') {
			
				$mail_customer = new Mail();
				$mail_customer->protocol = $this->config->get('config_mail_protocol');
				$mail_customer->parameter = $this->config->get('config_mail_parameter');
				$mail_customer->hostname = $this->config->get('config_smtp_host');
				$mail_customer->username = $this->config->get('config_smtp_username');
				$mail_customer->password = $this->config->get('config_smtp_password');
				$mail_customer->port = $this->config->get('config_smtp_port');
				$mail_customer->timeout = $this->config->get('config_smtp_timeout');				
				$mail_customer->setTo($this->request->post['email']);
				$mail_customer->setFrom($this->config->get('config_email'));
				$mail_customer->setSender('Заказ Outmaxshop.ru');
				$mail_customer->setSubject(html_entity_decode(sprintf($this->language->get('email_subject'), ENT_QUOTES, 'UTF-8')));
				$mail_customer->setText(html_entity_decode("Спасибо за заказ. \n\n" .  "В ближайшее время c вами свяжется оператор для подтверждения заказа. \n\n"
				.$text. "\n\n"
				. 'Ваш комментарий к заказу:' . "\n\n" . $this->request->post['enquiry'] . "\n\n" 
				, ENT_QUOTES, 'UTF-8'));
				$mail_customer->send();
				
			}							
			
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
		
		
		
		if ($fastorderdialog_module_cfg['button_status'] == '1') {
						
			$css_color = $fastorderdialog_module_cfg['button_color'];
						
			if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/fastorderdialog/fastorderdialog_'.$css_color.'.css')) {
				$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/fastorderdialog/fastorderdialog_'.$css_color.'.css');
			} else {
				$this->document->addStyle('catalog/view/theme/default/stylesheet/fastorderdialog/fastorderdialog_'.$css_color.'.css');
			}
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/fastorderdialog_button.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/module/fastorderdialog_button.tpl';
			} else {
					$this->template = 'default/template/module/fastorderdialog_button.tpl';
			}
		
		$this->render();
		}

		$this->data['entry_enquiry'] = $this->language->get('entry_enquiry');
    	$this->data['entry_name'] = $this->language->get('entry_name');
    	$this->data['entry_tel'] = $this->language->get('entry_tel');
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_remove'] = $this->language->get('entry_remove');		
		$this->data['entry_city'] = $this->language->get('entry_city');
		$this->data['entry_time'] = $this->language->get('entry_time');
		$this->data['yes'] = $this->language->get('yes');
		$this->data['no'] = $this->language->get('no');
		$this->data['qs'] = $this->language->get('qs');
		$this->data['articul'] = "Артикул";
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
		
    	$this->data['text_shipping_and_payment'] = $this->language->get('text_shipping_and_payment');
    
		$this->data['action'] = $this->url->link('module/fastorderdialog/open');


    	
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
		
		

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/fastorderdialog.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/module/fastorderdialog.tpl';
			} else {
				$this->template = 'default/template/module/fastorderdialog.tpl';
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
	$fastorderdialog_module_cfg = $this->config->get('fastorderdialog_setting');
	
    	if ($fastorderdialog_module_cfg['capcha']=='1') {
			if (isset($this->request->post['irobot_no']) || !isset($this->request->post['irobot_yes'])) {
				$this->error['capcha'] = $this->language->get('error_capcha');
			}
		}

		if (!isset($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
$this->error['captcha'] = $this->language->get('error_captcha');
}


	if (!isset($this->request->post['tel']) or mb_strlen($this->request->post['tel']) <5) {
$this->error['tel'] = $this->language->get('error_tel');
}
		
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}  	  
  	}

	
}
?>
