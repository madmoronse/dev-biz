<?php
class ControllerModuleCategoryMenu extends Controller {
	private $error = array();

	public function index() {

		$query = $this->db->query("DESC ". DB_PREFIX ."category menu_image");

		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "category` ADD `menu_image` tinyint(1) NOT NULL default '1' AFTER image;");
		}

		$this->language->load('module/category_menu');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('category_menu', $this->request->post);

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

		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_popup'] = $this->language->get('text_popup');
		$this->data['text_accordion'] = $this->language->get('text_accordion');
		$this->data['text_collapsible'] = $this->language->get('text_collapsible');
		$this->data['text_name'] = $this->language->get('text_name');
		$this->data['text_button'] = $this->language->get('text_button');
		$this->data['text_custom_style'] = $this->language->get('text_custom_style');
		$this->data['text_example'] = $this->language->get('text_example');
		$this->data['text_select_all'] = $this->language->get('text_select_all');
		$this->data['text_unselect_all'] = $this->language->get('text_unselect_all');

		$this->data['entry_module_name'] = $this->language->get('entry_module_name');
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_title_style'] = $this->language->get('entry_title_style');
		$this->data['entry_style'] = $this->language->get('entry_style');
		$this->data['entry_toggle'] = $this->language->get('entry_toggle');
		$this->data['entry_items'] = $this->language->get('entry_items');
		$this->data['entry_image'] = $this->language->get('entry_image');
		$this->data['entry_link'] = $this->language->get('entry_link');
		$this->data['entry_link_title'] = $this->language->get('entry_link_title');

		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		$this->data['button_add_link'] = $this->language->get('button_add_link');

		$this->data['tab_module'] = $this->language->get('tab_module');
		$this->data['tab_categories'] = $this->language->get('tab_categories');
		$this->data['tab_information'] = $this->language->get('tab_information');
		$this->data['tab_manufacturers'] = $this->language->get('tab_manufacturers');
		$this->data['tab_links'] = $this->language->get('tab_links');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

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
			'href'      => $this->url->link('module/category_menu', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['action'] = $this->url->link('module/category_menu', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['modules'] = array();

		if (isset($this->request->post['category_menu_module'])) {
			$this->data['modules'] = $this->request->post['category_menu_module'];
		} elseif ($this->config->get('category_menu_module')) { 
			$this->data['modules'] = $this->config->get('category_menu_module');
		}

		$this->load->model('design/layout');

		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->load->model('localisation/language');

		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		$this->load->model('catalog/category');

		$this->data['categories'] = $this->model_catalog_category->getCategories(0);

		$this->load->model('catalog/manufacturer');

		$this->data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();

		$this->load->model('catalog/information');

		$this->data['informations'] = $this->model_catalog_information->getInformations();

		$this->template = 'module/category_menu.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/category_menu')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>