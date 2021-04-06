<?php
class ControllerModuleProductSizes extends Controller
{
    private $error = array();
    
    public function index()
    {
        $this->language->load('module/productsizes');

        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->load->model('setting/setting');
        $this->load->model('tool/image');
                
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('productsizes', $this->request->post);
            $this->data['success'] = $this->language->get('text_success');
        }

        $language_keys = array(
            'heading_title',
            'text_enabled',
            'text_disabled',
            'entry_type',
            'entry_image',
            'entry_product',
            'entry_name',
            'entry_sex',
            'entry_category',
            'entry_caption',
            'entry_text',
            'button_save',
            'button_add_size',
            'button_cancel',
            'button_remove',
            'text_browse',
            'text_clear',
        );

        foreach ($language_keys as $key) {
            $this->data[$key] = $this->language->get($key);
        }

        $this->data['text_help'] = str_replace("\n", '', nl2br($this->language->get('text_help')));
        
        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }
        $this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
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
            'href'      => $this->url->link('module/productsizes', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );
        
        $this->data['action'] = $this->url->link('module/productsizes', 'token=' . $this->session->data['token'], 'SSL');
        
        $this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['productsizes'] = array();
        if (isset($this->request->post['productsizes'])) {
            $this->data['productsizes'] = $this->request->post['productsizes'];
        } elseif ($this->config->get('productsizes')) {
            $this->data['productsizes'] = $this->config->get('productsizes');
        }

        foreach ($this->data['productsizes'] as $key => $size) {
            if (!empty($size['image'])) {
                $this->data['productsizes'][$key]['thumb'] = $this->model_tool_image->resize($size['image'], 100, 100);
                $this->data['productsizes'][$key]['title'] = basename($size['image']);
            }
            $this->data['productsizes'][$key]['product_id'] = implode("\n", is_array($size['product_id']) ? $size['product_id'] : array());
        }

        $this->data['size_types'] = array(
            array(
                'value' => 'shoes',
                'text' => $this->language->get('text_size_type_shoes')
            ),
            array(
                'value' => 'clothes',
                'text' => $this->language->get('text_size_type_clothes')
            )
        );

        $this->data['size_sex'] = array(
            array(
                'value' => '',
                'text' => '---'
            ),
            array(
                'value' => 'man',
                'text' => $this->language->get('text_size_sex_man')
            ),
            array(
                'value' => 'woman',
                'text' => $this->language->get('text_size_sex_woman')
            )

        );
        $this->template = 'module/productsizes.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );
                
        $this->response->setOutput($this->render());
    }
    
    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'module/productsizes')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (isset($this->request->post['productsizes']) && is_array($this->request->post['productsizes'])) {
            foreach ($this->request->post['productsizes'] as $key => $size) {
                $all_empty = empty($size['product_id'])
                    && empty($size['name'])
                    && empty($size['sex'])
                    && empty($size['category_id']);
                if (!$all_empty && !(empty($size['product_id'])
                    xor empty($size['name'])
                    xor empty($size['sex'])
                    xor empty($size['category_id'])
                )) {
                    $this->error['productsizes'][$key]['general'] = $this->language->get('error_single_parameter');
                }
                if (!empty($size['category_id']) && !is_numeric($size['category_id'])) {
                    $this->error['productsizes'][$key]['category_id'] = $this->language->get('error_numeric');
                }
                if (empty($size['image'])) {
                    $this->error['productsizes'][$key]['image'] = $this->language->get('error_required');
                }
                foreach ($size as $k => $v) {
                    switch ($k) {
                        default:
                            $this->request->post['productsizes'][$key][$k] = trim($v);
                            break;
                        case 'product_id':
                            $delimeter = strpos($v, "\r\n") !== false ? "\r\n" : "\n";
                            $this->request->post['productsizes'][$key][$k] = array_filter(array_map(
                                'intval',
                                explode($delimeter, trim($v))
                            ));
                            break;
                    }
                }
            }
        } else {
            $this->request->post['productsizes'] = array();
        }
        
        if (!$this->error) {
            return true;
        } else {
            $this->error['warning'] = $this->language->get('error_warning');
            return false;
        }
    }
}
