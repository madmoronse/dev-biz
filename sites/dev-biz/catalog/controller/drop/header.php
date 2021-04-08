<?php  

require_once __DIR__ . '/../common/header.php';

class ControllerDropHeader extends ControllerCommonHeader {
	protected function render() {
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/drop/header.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/drop/header.tpl';
		} else {
			$this->template = 'default/template/drop/header.tpl';
		}
		return parent::render();
	}
}
?>