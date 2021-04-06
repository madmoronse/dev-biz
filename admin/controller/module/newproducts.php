<?php
class ControllerModuleNewProducts extends Controller
{
    private $error = array();
    
    public function index()
    {
        $this->language->load('module/newproducts');

        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->load->model('setting/setting');
        $this->load->model('module/newproducts');
        $this->load->model('tool/image');
                
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            list($added, $updated, $removed) = $this->model_module_newproducts->store($this->request->post);

            $this->session->data['success'] = sprintf($this->language->get('text_success'), $added, $removed, $updated);

            $this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $language_keys = array(
            'heading_title',
            'text_enabled',
            'text_disabled',
            'entry_category',
            'entry_product',
            'entry_lifetime_in_weeks',
            'button_save',
            'button_cancel',
            'button_remove',
        );

        foreach ($language_keys as $key) {
            $this->data[$key] = $this->language->get($key);
        }

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }
        $this->data['token'] = $this->session->data['token'];

        $this->data['error'] = $this->error;

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
            'href'      => $this->url->link('module/newproducts', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );
        
        $this->data['action'] = $this->url->link('module/newproducts', 'token=' . $this->session->data['token'], 'SSL');
        
        $this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
        if (isset($this->request->post['categories'])) {
            $this->data['categories'] = $this->request->post['categories'];
        } else {
            $this->data['categories'] = array_values($this->model_module_newproducts->getCategoriesWithProducts());
        }

        if (isset($this->request->post['lifetime_in_weeks'])) {
            $this->data['lifetime_in_weeks'] = $this->request->post['lifetime_in_weeks'];
        } else {
            $this->data['lifetime_in_weeks'] = $this->config->get('newproducts_lifetime_in_weeks');
        }

        foreach ($this->data['categories'] as $key => $category) {
            $this->data['categories'][$key]['products'] = implode(
                "\n",
                is_array($category['products']) ? $category['products'] : array()
            );
        }

        $this->template = 'module/newproducts.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );
                
        $this->response->setOutput($this->render());
    }
    
    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'module/newproducts')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        if (empty($this->request->post['lifetime_in_weeks'])) {
            $this->error['lifetime_in_weeks'] = $this->language->get('error_required');
        }
        if (!empty($this->request->post['lifetime_in_weeks'])
            && !is_numeric($this->request->post['lifetime_in_weeks'])
        ) {
            $this->error['lifetime_in_weeks'] = $this->language->get('error_numeric');
        }
        if (isset($this->request->post['categories']) && is_array($this->request->post['categories'])) {
            foreach ($this->request->post['categories'] as $key => $category) {
                $delimeter = strpos($category['products'], "\r\n") !== false ? "\r\n" : "\n";
                $this->request->post['categories'][$key]['products'] = array_filter(array_map(
                    'intval',
                    explode($delimeter, trim($category['products']))
                ));
            }
        } else {
            $this->request->post['categories'] = array();
        }
        
        if (!$this->error) {
            return true;
        } else {
            $this->error['warning'] = $this->language->get('error_warning');
            return false;
        }
    }

    public function install()
    {
        $this->load->model('module/newproducts');
        $this->model_module_newproducts->createDatabaseTables();
    }

    public function uninstall()
    {
        $this->load->model('module/newproducts');
        $this->model_module_newproducts->dropDatabaseTables();
    }
}
