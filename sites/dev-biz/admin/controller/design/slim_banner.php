<?php 
class ControllerDesignSlimBanner extends Controller {
	private $error = array();
 
	public function index() {

        $this->getForm();
	}


    public function getForm(){
        $this->language->load('design/slim_banner');

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('design/slim_banner', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->document->setTitle($this->language->get('heading_title'));

        $this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['entry_link'] = $this->language->get('entry_link');
        $this->data['entry_image'] = $this->language->get('entry_image');
        $this->data['entry_header'] = $this->language->get('entry_header');
        $this->data['entry_timer'] = $this->language->get('entry_timer');
        $this->data['entry_status'] = $this->language->get('entry_status');
        $this->data['entry_hits'] = $this->language->get('entry_hits');
        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');
        $this->data['text_image_manager'] = $this->language->get('text_image_manager');
        $this->data['text_browse'] = $this->language->get('text_browse');
        $this->data['text_clear'] = $this->language->get('text_clear');
        $this->data['action'] = $this->url->link('design/slim_banner/insert', 'token=' . $this->session->data['token'], 'SSL');

        $this->load->model('tool/image');
        $this->load->model('setting/setting');
        $this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);


        $slim_banner = $this->model_setting_setting->getSetting('slim_banner');
        if (isset($this->request->post['slim_banner_image']) and file_exists(DIR_IMAGE . $this->request->post['slim_banner_image'])) {
            $this->data['slim_banner_image'] = $this->request->post['slim_banner_image'];
        } elseif ($slim_banner['slim_banner_image'] and file_exists(DIR_IMAGE . $slim_banner['slim_banner_image'])) {
            $this->data['slim_banner_image'] = $slim_banner['slim_banner_image'];
        } else {
            $this->data['slim_banner_image'] = 'no_image.jpg';
        }

        if (isset($this->request->post['slim_banner_url'])) {
            $this->data['slim_banner_url'] = $this->request->post['slim_banner_url'];
        } elseif ($slim_banner['slim_banner_url']) {
            $this->data['slim_banner_url'] = $slim_banner['slim_banner_url'];
        } else {
            $this->data['slim_banner_url'] = '';
        }
        $this->data['slim_banner_thumb'] = $this->model_tool_image->resize($this->data['slim_banner_image'], 100, 100);

        if (isset($this->request->post['slim_banner_status'])) {
            $this->data['slim_banner_status'] = $this->request->post['slim_banner_status'];
        } elseif ($slim_banner['slim_banner_status']) {
            $this->data['slim_banner_status'] = $slim_banner['slim_banner_status'];
        } else {
            $this->data['slim_banner_status'] = false;
        }

        if (isset($this->request->post['slim_banner_timer_header'])) {
            $this->data['slim_banner_timer_header'] = $this->request->post['slim_banner_timer_header'];
        } elseif ($slim_banner['slim_banner_timer_date']) {
            $this->data['slim_banner_timer_header'] = $slim_banner['slim_banner_timer_header'];
        } else {
            $this->data['slim_banner_timer_header'] = false;
        }

        if (isset($this->request->post['slim_banner_timer_date'])) {
            $this->data['slim_banner_timer_date'] = $this->request->post['slim_banner_timer_date'];
        } elseif ($slim_banner['slim_banner_timer_date']) {
            $this->data['slim_banner_timer_date'] = $slim_banner['slim_banner_timer_date'];
        } else {
            $this->data['slim_banner_timer_date'] = false;
        }

        if (isset($this->request->post['slim_banner_timer_time'])) {
            $this->data['slim_banner_timer_time'] = $this->request->post['slim_banner_timer_time'];
        } elseif ($slim_banner['slim_banner_timer_time']) {
            $this->data['slim_banner_timer_time'] = $slim_banner['slim_banner_timer_time'];
        } else {
            $this->data['slim_banner_timer_time'] = false;
        }

        if (isset($this->request->post['slim_banner_hits']) and file_exists(DIR_IMAGE . $this->request->post['slim_banner_hits'])) {
            $this->data['slim_banner_hits'] = $this->request->post['slim_banner_hits'];
        } elseif ($slim_banner['slim_banner_hits']) {
            $this->data['slim_banner_hits'] = $slim_banner['slim_banner_hits'];
        } else {
            $this->data['slim_banner_hits'] = 0;
        }


        $this->data['token'] = $this->session->data['token'];


        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        $this->template = 'design/slim_banner.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }
    public function insert(){
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateUpdate()) {

            $this->load->model('setting/setting');

            $this->model_setting_setting->editSetting('slim_banner', $this->request->post);

            //$this->redirect($this->url->link('design/slim_banner', 'token=' . $this->session->data['token'] , 'SSL'));
        }
        $this->getForm();

    }

	protected function validateUpdate() {
		if (!$this->user->hasPermission('modify', 'design/slim_banner')) {
            $this->language->load('design/slim_banner');
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