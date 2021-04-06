<?php  
class ControllerModuleXDSCustomMenu extends Controller {
	protected function index() {
		$this->language->load('module/xds_custom_menu');

    $this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['lang_id'] = $this->config->get('config_language_id');
		
		$this->data['item1'] = $this->config->get('item1');
		$this->data['item1_href'] = $this->config->get('item1_href');
		$this->data['item2'] = $this->config->get('item2');
		$this->data['item2_href'] = $this->config->get('item2_href');
		$this->data['item3'] = $this->config->get('item3');
		$this->data['item3_href'] = $this->config->get('item3_href');
		$this->data['item4'] = $this->config->get('item4');
		$this->data['item4_href'] = $this->config->get('item4_href');
		$this->data['item5'] = $this->config->get('item5');
		$this->data['item5_href'] = $this->config->get('item5_href');
		
		$this->data['odnoklassniki_href'] = $this->config->get('odnoklassniki_href');
		$this->data['odnoklassniki_title'] = $this->language->get('odnoklassniki_title');
		$this->data['vkontakte_href'] = $this->config->get('vkontakte_href');
		$this->data['vkontakte_title'] = $this->language->get('vkontakte_title');
		$this->data['facebook_href'] = $this->config->get('facebook_href');
		$this->data['facebook_title'] = $this->language->get('facebook_title');
		$this->data['twitter_href'] = $this->config->get('twitter_href');
		$this->data['twitter_title'] = $this->language->get('twitter_title');
		$this->data['googleplus_href'] = $this->config->get('googleplus_href');
		$this->data['googleplus_title'] = $this->language->get('googleplus_title');
		
		
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/xds_custom_menu.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/xds_custom_menu.tpl';
		} else {
			$this->template = 'default/template/module/xds_custom_menu.tpl';
		}
		
		$this->render();
	}
}
?>