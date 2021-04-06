<?php 
class ControllerAccountInvoice extends Controller {
	private $error = array();
		
	public function index() {
    	if (!$this->customer->isLogged()) {
      		$this->session->data['redirect'] = $this->url->link('account/invoice', '', 'SSL');

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
			'href'      => $this->url->link('account/invoice', $url, 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['button_continue'] = $this->language->get('button_continue');



		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/invoice.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/invoice.tpl';
		} else {
			$this->template = 'default/template/account/invoice.tpl';
		}
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'	
		);
		
		$customer_id = $this->customer->getId();
		$files = scandir('/var/www/www-root/data/invoice/'.$customer_id, 1);
		
		foreach($files as $key=>$file){
			if (strpos($file, '.xls') !== false){
				
				$this->data['files'][] = str_replace('invoice_','',$file);

			}
		}
		
		
						
		$this->response->setOutput($this->render());				
	}
	
	function downloadInvoice() {
		
		if (isset($this->request->get['file'])) {
			
			$customer_id = $this->customer->getId();
			$file = '/var/www/www-root/data/invoice/'.$customer_id . '/invoice_' . $this->request->get['file'];
		}
		
			if (file_exists($file)) {
				// сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
				// если этого не сделать файл будет читаться в память полностью!
				if (ob_get_level()) {
				ob_end_clean();
				}
				// заставляем браузер показать окно сохранения файла
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename=' . basename($file));
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				header('Content-Length: ' . filesize($file));
				// читаем файл и отправляем его пользователю
				if ($fd = fopen($file, 'rb')) {
				while (!feof($fd)) {
					print fread($fd, 1024);
				}
				fclose($fd);
				}
				exit;
			} else {
				//echo "<h1>ERROR</h1>";
			}
		}

}
?>