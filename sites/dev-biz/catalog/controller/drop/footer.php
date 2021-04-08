<?php  

require_once __DIR__ . '/../common/footer.php';

class ControllerDropFooter extends ControllerCommonFooter {
	protected function render() {
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/drop/footer.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/drop/footer.tpl';
		} else {
			$this->template = 'default/template/drop/footer.tpl';
		}
		return parent::render();
	}
}
?>