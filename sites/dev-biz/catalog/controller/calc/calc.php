<?php  
class ControllerCalcCalc extends Controller {
	public function index() {
		$this->document->setTitle($this->config->get('config_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->data['heading_title'] = "Расчет доставки";
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/calc/calc.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/calc/calc.tpl';
			$this->data['template'] = $this->config->get('config_template');
		} else {
			$this->template = 'default/template/calc/calc.tpl';
		}

		$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'href'      => $this->url->link('common/home'),
        	'text'      => $this->language->get('text_home'),
        	'separator' => false
      	);

      	$this->data['breadcrumbs'][] = array(
        	'href'      => $this->url->link('calc/calc'),
        	'text'      => "Расчет доставки",
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
}
?>