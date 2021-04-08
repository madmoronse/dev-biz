<?php
class ControllerModuleNewsblock extends Controller {
	private $error = array();
 
	public function index() {

        $this->getForm();
	}


    public function getForm(){
        $this->language->load('module/newsblock');

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('module/newsblock', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->document->setTitle($this->language->get('heading_title'));

        $this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['entry_status'] = $this->language->get('entry_status');
        $this->data['entry_block_name'] = $this->language->get('entry_block_name');
        $this->data['entry_blog_name'] = $this->language->get('entry_blog_name');
        $this->data['entry_added_blogs'] = $this->language->get('entry_added_blogs');

        $this->data['entry_layout'] = $this->language->get('entry_layout');
        $this->data['entry_position'] = $this->language->get('entry_position');
        $this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');

        $this->data['text_content_top'] = $this->language->get('text_content_top');
        $this->data['text_content_bottom'] = $this->language->get('text_content_bottom');
        $this->data['text_column_left'] = $this->language->get('text_column_left');
        $this->data['text_column_right'] = $this->language->get('text_column_right');

        $this->data['action'] = $this->url->link('module/newsblock/insert', 'token=' . $this->session->data['token'], 'SSL');

        $this->load->model('tool/image');
        $this->load->model('setting/setting');
        $this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

        $this->load->model('design/layout');

        $this->data['layouts'] = $this->model_design_layout->getLayouts();

        $this->data['token'] = $this->session->data['token'];



        if (isset($this->request->post['newsblock_module'])) {
            $this->data['newsblock_module'] = $this->request->post['newsblock_module'];
        } else if ($this->config->get('newsblock_module')) {
            $this->data['newsblock_module'] = $this->config->get('newsblock_module');
        }else{
            $this->data['newsblock_module'][1]['block_name']='';
            $this->data['newsblock_module'][1]['selectedNews']='';
            $this->data['newsblock_module'][1]['position']='';
            $this->data['newsblock_module'][1]['sort_order']='1';
        }

        if($this->data['newsblock_module'][1]['selectedNews']) {
            $this->load->model('pavblog/blog');
            $blogs = explode(',', $this->data['newsblock_module'][1]['selectedNews']);
            foreach ($blogs as $blog) {
                $this->data['newsblock_module'][1]['news'][] = $this->model_pavblog_blog->getBlog($blog);
            }
        }

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        $this->template = 'module/newsblock.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }
    public function insert(){
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateUpdate()) {

            $this->load->model('setting/setting');

            $this->model_setting_setting->editSetting('newsblock_module', $this->request->post);

            $this->redirect($this->url->link('module/newsblock', 'token=' . $this->session->data['token'] , 'SSL'));
        }
        $this->getForm();

    }

	protected function validateUpdate() {
		if (!$this->user->hasPermission('modify', 'module/newsblock')) {
            $this->language->load('module/newsblock');
			$this->error['warning'] = $this->language->get('error_permission');
		}
	
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

    public function getblogs() {
        $json = array();

        if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model']) || isset($this->request->get['filter_category_id'])) {
            $this->load->model('pavblog/blog');

            if (isset($this->request->get['filter_name'])) {
            $filter = array('title'=>$this->request->get['filter_name'],'category_id'=> '');
            } else {
                $filter = array('title'=>'','category_id'=> '');
            }


            $data = array(
                'start' => 0,
                'limit' => 999999
            );

            $blogs = $this->model_pavblog_blog->getList( $data, $filter );

            foreach ($blogs as $blog) {
                $json[] = array(
                    'blog_id' => $blog['blog_id'],
                    'name' => strip_tags(html_entity_decode($blog['title'], ENT_QUOTES, 'UTF-8')),

                );
            }
        }


        $this->response->setOutput(json_encode($json));
    }
}
?>