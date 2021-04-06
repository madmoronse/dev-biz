<?php
class ControllerModuleCatalogGifts extends Controller {
    public function index() {		
        $this->data = $this->language->load('module/catalog_gifts');
        $this->load->model('setting/setting');

        $this->data['breadcrumbs'] = array();
        $this->document->setTitle($this->language->get('heading_title'));

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),       		
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('tool/upload', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );
        $this->data['token'] = $this->session->data['token'];
        // Update
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
            $error = false;
            // Проверяем JSON на корректность
            if (isset($this->request->post['catalog_gifts_rules'])) {
                $catalog_gifts_rules = html_entity_decode($this->request->post['catalog_gifts_rules']);
                json_decode($catalog_gifts_rules);
                if (json_last_error()) {
                    $this->session->data['error'] = $this->language->get('error_wrong_json');
                    $error = true;
                }
            }

            if (!$error) {
                $this->model_setting_setting->editSetting('catalog_gifts', $this->request->post);
                
                $this->session->data['success'] = $this->language->get('text_success');
            }
            $this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
        }

        if (isset($this->request->post['catalog_gifts_rules'])) {
			$this->data['catalog_gifts_rules'] = $this->request->post['catalog_gifts_rules'];
		} else {
			$this->data['catalog_gifts_rules'] = $this->config->get('catalog_gifts_rules');
		}
		if (isset($this->request->post['catalog_gifts_list'])) {
			$this->data['catalog_gifts_list'] = $this->request->post['catalog_gifts_list'];
		} else {
			$this->data['catalog_gifts_list'] = $this->config->get('catalog_gifts_list');
        }
		if (isset($this->request->post['catalog_gifts_show_info'])) {
			$this->data['catalog_gifts_show_info'] = $this->request->post['catalog_gifts_show_info'];
		} else {
			$this->data['catalog_gifts_show_info'] = $this->config->get('catalog_gifts_show_info');
		}

        $this->template = 'module/catalog_gifts.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
    }

    private function validate() {
		if (!$this->user->hasPermission('modify', 'module/catalog_gifts')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
    }
}