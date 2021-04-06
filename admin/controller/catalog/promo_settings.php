<?php 
class ControllerCatalogPromoSettings extends Controller {
	private $error = array();
 
	public function index() {

        $this->getForm();
	}


    public function getForm(){
        $this->language->load('catalog/promo_settings');

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('catalog/promo_settings', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->document->setTitle($this->language->get('heading_title'));

        $this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['entry_timer'] = $this->language->get('entry_timer');
        $this->data['entry_status'] = $this->language->get('entry_status');
        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');
        $this->data['entry_common_settings'] = $this->language->get('entry_common_settings');
        $this->data['entry_test_user'] = $this->language->get('entry_test_user');
        $this->data['entry_promo_banner'] = $this->language->get('entry_promo_banner');
        $this->data['entry_promo_banner_url'] = $this->language->get('entry_promo_banner_url');
        $this->data['text_browse'] = $this->language->get('text_browse');
        $this->data['text_clear'] = $this->language->get('text_clear');
        $this->data['text_image_manager'] = $this->language->get('text_image_manager');

        $this->data['entry_one_plus_one_is_three'] = $this->language->get('entry_one_plus_one_is_three');


        $this->data['entry_shapka_v_podarok'] = $this->language->get('entry_shapka_v_podarok');
        $this->data['entry_shapka_v_podarok_conditions'] = $this->language->get('entry_shapka_v_podarok_conditions');
        $this->data['entry_shapka_v_podarok_free_products'] = $this->language->get('entry_shapka_v_podarok_free_products');

        $this->data['entry_total_discount'] = $this->language->get('entry_total_discount');
        $this->data['entry_total_discount_conditions'] = $this->language->get('entry_total_discount_conditions');
        $this->data['entry_total_discount_products'] = $this->language->get('entry_total_discount_products');

        $this->data['action'] = $this->url->link('catalog/promo_settings/update', 'token=' . $this->session->data['token'], 'SSL');


        $this->load->model('tool/image');
        $this->load->model('setting/setting');
        $this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);


        $promo_settings = $this->model_setting_setting->getSetting('promo_settings');

        if ((isset($this->request->post['promo_banner'])) and file_exists(DIR_IMAGE . $this->request->post['promo_banner'])) {
            $this->data['promo_banner'] = $this->request->post['promo_banner'];
        } elseif ((isset($promo_settings['promo_banner'])) and file_exists(DIR_IMAGE . $promo_settings['promo_banner'])) {
            $this->data['promo_banner'] = $promo_settings['promo_banner'];
        } else {
            $this->data['promo_banner'] = '';
        }

        $this->data['promo_banner_thumb'] = $this->model_tool_image->resize($this->data['promo_banner'], 100, 100);


        if (isset($this->request->post['promo_banner_url'])) {
            $this->data['promo_banner_url'] = $this->request->post['promo_banner_url'];
        } elseif (isset($promo_settings['promo_banner_url'])) {
            $this->data['promo_banner_url'] = $promo_settings['promo_banner_url'];
        } else {
            $this->data['promo_banner_url'] = '';
        }


        if (isset($this->request->post['promo_banner_status'])) {
            $this->data['promo_banner_status'] = $this->request->post['promo_banner_status'];
        } elseif (isset($promo_settings['promo_banner_status'])) {
            $this->data['promo_banner_status'] = $promo_settings['promo_banner_status'];
        } else {
            $this->data['promo_banner_status'] = '';
        }


        if (isset($this->request->post['one_plus_one_status'])) {
            $this->data['one_plus_one_status'] = $this->request->post['one_plus_one_status'];
        } elseif (isset($promo_settings['one_plus_one_status'])) {
            $this->data['one_plus_one_status'] = $promo_settings['one_plus_one_status'];
        } else {
            $this->data['one_plus_one_status'] = '';
        }

        if (isset($this->request->post['one_plus_one_test_user'])) {
            $this->data['one_plus_one_test_user'] = $this->request->post['one_plus_one_test_user'];
        } elseif (isset($promo_settings['one_plus_one_test_user'])) {
            $this->data['one_plus_one_test_user'] = $promo_settings['one_plus_one_test_user'];
        } else {
            $this->data['one_plus_one_test_user'] = '';
        }

        if (isset($this->request->post['shapka_v_podarok_test_user'])) {
            $this->data['shapka_v_podarok_test_user'] = $this->request->post['shapka_v_podarok_test_user'];
        } elseif (isset($promo_settings['shapka_v_podarok_test_user'])) {
            $this->data['shapka_v_podarok_test_user'] = $promo_settings['shapka_v_podarok_test_user'];
        } else {
            $this->data['shapka_v_podarok_test_user'] = '';
        }

        if (isset($this->request->post['shapka_v_podarok_status'])) {
            $this->data['shapka_v_podarok_status'] = $this->request->post['shapka_v_podarok_status'];
        } elseif (isset($promo_settings['shapka_v_podarok_status'])) {
            $this->data['shapka_v_podarok_status'] = $promo_settings['shapka_v_podarok_status'];
        } else {
            $this->data['shapka_v_podarok_status'] = '';
        }

        if (isset($this->request->post['shapka_v_podarok_conditions'])) {
            $this->data['shapka_v_podarok_conditions'] = $this->request->post['shapka_v_podarok_conditions'];
        } elseif (isset($promo_settings['shapka_v_podarok_conditions'])) {
            $this->data['shapka_v_podarok_conditions'] = $promo_settings['shapka_v_podarok_conditions'];
        } else {
            $this->data['shapka_v_podarok_conditions'] = '';
        }

        if (isset($this->request->post['shapka_v_podarok_free_products'])) {
            $this->data['shapka_v_podarok_free_products'] = $this->request->post['shapka_v_podarok_free_products'];
        } elseif (isset($promo_settings['shapka_v_podarok_free_products'])) {
            $this->data['shapka_v_podarok_free_products'] = $promo_settings['shapka_v_podarok_free_products'];
        } else {
            $this->data['shapka_v_podarok_free_products'] = '';
        }


        if (isset($this->request->post['total_discount_test_user'])) {
            $this->data['total_discount_test_user'] = $this->request->post['total_discount_test_user'];
        } elseif (isset($promo_settings['total_discount_test_user'])) {
            $this->data['total_discount_test_user'] = $promo_settings['total_discount_test_user'];
        } else {
            $this->data['total_discount_test_user'] = '';
        }

        if (isset($this->request->post['total_discount_status'])) {
            $this->data['total_discount_status'] = $this->request->post['total_discount_status'];
        } elseif (isset($promo_settings['total_discount_status'])) {
            $this->data['total_discount_status'] = $promo_settings['total_discount_status'];
        } else {
            $this->data['total_discount_status'] = '';
        }

        if (isset($this->request->post['total_discount_conditions'])) {
            $this->data['total_discount_conditions'] = $this->request->post['total_discount_conditions'];
        } elseif (isset($promo_settings['total_discount_conditions'])) {
            $this->data['total_discount_conditions'] = $promo_settings['total_discount_conditions'];
        } else {
            $this->data['total_discount_conditions'] = '';
        }

        if (isset($this->request->post['total_discount_products'])) {
            $this->data['total_discount_products'] = $this->request->post['total_discount_products'];
        } elseif (isset($promo_settings['total_discount_products'])) {
            $this->data['total_discount_products'] = $promo_settings['total_discount_products'];
        } else {
            $this->data['total_discount_products'] = '';
        }



        $this->data['token'] = $this->session->data['token'];


        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        $this->template = 'catalog/promo_settings.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }


    public function update(){
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateUpdate()) {

            $this->load->model('setting/setting');

            $this->model_setting_setting->editSetting('promo_settings', $this->request->post);

        }
        $this->getForm();
    }

	protected function validateUpdate() {
		if (!$this->user->hasPermission('modify', 'catalog/promo_settings')) {
            $this->language->load('catalog/promo_settings');
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