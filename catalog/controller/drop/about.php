<?php  
class ControllerDropAbout extends Controller {
	public function index() {
		$this->document->setTitle($this->config->get('config_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->data['heading_title'] = "О нас";
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/drop/about.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/drop/about.tpl';
			$this->data['template'] = $this->config->get('config_template');
		} else {
			$this->template = 'default/template/drop/about.tpl';
		}

		$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'href'      => $this->url->link('common/home'),
        	'text'      => $this->language->get('text_home'),
        	'separator' => false
      	);

      	$this->data['breadcrumbs'][] = array(
        	'href'      => $this->url->link('drop/about'),
        	'text'      => "О нас",
        	'separator' => $this->language->get('text_separator')
      	);

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

	public function send() {
		$type = $this->request->post['type'];
		$type_name = $type == 1 ? 'Дропшиппинг' : 'Опт';
		$name = $this->request->post['name'];
		$tel = $this->request->post['tel'];
		$email = $this->request->post['email'];
		$text = $this->request->post['text'];

		if($type && $name && $tel && $email && $text) {
			$subject = 'Бизнес с Outmax';
			$message = <<<HTML
				Заполнена форма Бизнес с Outmax.

				Вид сотрудничества: {$type_name}
				Имя: {$name}
				Телефон: {$tel}
				E-mail: {$email}
				Сообщение: {$text}
HTML;

			$emailTo = $type == 1 ? 'outmaxdrop@mail.ru' : 'outmaxshop.ru@gmail.com';

			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');
			$mail->setTo($emailTo);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($this->config->get('config_name'));
			$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();

			$this->response->setOutput(json_encode(array('error' => 0, 'mess' => 'Заявка успешно отправлена.')));
		}
		else {
			$this->response->setOutput(json_encode(array('error' => 1, 'mess' => 'Ошибка, не все обязательные поля заполнены верно.')));	
		}
	}
}
?>