<?php  
class ControllerModuleXDSDefault2Control extends Controller {
	protected function index() {
		$this->language->load('module/xds_default2_control');

    $this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['lang_id'] = $this->config->get('config_language_id');
		
		$this->data['phone_number_text'] = $this->config->get('phone_number_text');
		$this->data['email_text'] = $this->config->get('email_text');
		$this->data['skype_text'] = $this->config->get('skype_text');
		$this->data['details_text'] = $this->config->get('details_text');
		$this->data['cap_text'] = $this->config->get('cap_text');
		
		$this->data['wishlist_text'] = $this->language->get('wishlist_text');
		$this->data['compare_text'] = $this->language->get('compare_text');
		
		$this->data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
		
		$this->data['designed_text'] = $this->language->get('designed_text');
		$this->data['mega_menu_text'] = $this->language->get('mega_menu_text');
		
		
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/xds_default2_control.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/xds_default2_control.tpl';
		} else {
			$this->template = 'default/template/module/xds_default2_control.tpl';
		}
		
		$this->render();
	}
}
?>