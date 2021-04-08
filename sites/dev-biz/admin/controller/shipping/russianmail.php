<?php
class ControllerShippingRussianMail extends Controller
{
 
    private $error = array(); 
    
    public function index()
    {  
        $this->language->load('shipping/russianmail');

        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->load->model('setting/setting');
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('russianmail', $this->request->post);        

            $this->session->data['success'] = $this->language->get('text_success');
                                    
            $this->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
        }
        $this->load->model('sale/customer_group');
        $this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
        $this->data['entry_russianmail_online'] = $this->language->get('entry_russianmail_online');
        $this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['entry_partbuy'] = $this->language->get('entry_partbuy');
        $this->data['entry_cost'] = $this->language->get('entry_cost');
        $this->data['entry_status'] = $this->language->get('entry_status');
        $this->data['entry_russianmail_use_fallback'] = $this->language->get('entry_russianmail_use_fallback');
        $this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');
        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');
        $this->data['tab_main'] = $this->language->get('tab_main');
        $this->data['tab_markup'] = $this->language->get('tab_markup');
        $this->data['text_markup_full'] = $this->language->get('text_markup_full');
        $this->data['text_markup_part'] = $this->language->get('text_markup_part');
        $this->data['entry_markup_online'] = $this->language->get('entry_markup_online');
        $this->data['entry_markup_avia'] = $this->language->get('entry_markup_avia');
        $this->data['entry_markup_ordinary'] = $this->language->get('entry_markup_ordinary');
        $this->data['tab_auth'] = $this->language->get('tab_auth');
        $this->data['entry_login'] = $this->language->get('entry_login');
        $this->data['entry_password'] = $this->language->get('entry_password');
        $this->data['tab_data'] = $this->language->get('tab_data');
        $this->data['entry_postalcode'] = $this->language->get('entry_postalcode');
        $this->data['entry_size'] = $this->language->get('entry_size');
        $this->data['entry_default_weight'] = $this->language->get('entry_default_weight');
        $this->data['entry_timeout'] = $this->language->get('entry_timeout');
        $this->data['entry_log'] = $this->language->get('entry_log');
        $this->data['boolean_variables'] = array($this->language->get('text_no'), $this->language->get('text_yes'));
        $this->data['text_data_category_help'] = $this->language->get('text_data_category_help');
        $this->data['text_markup_declared_value_help'] = $this->language->get('text_markup_declared_value_help');
        $this->data['column_category'] = $this->language->get('column_category');
        $this->data['column_size'] = $this->language->get('column_size');
        $this->data['column_weight'] = $this->language->get('column_weight');
        $this->data['column_customer_group'] = $this->language->get('column_customer_group');
        $this->data['column_markup'] = $this->language->get('column_markup');

        $this->data['button_insert'] = $this->language->get('button_insert');
        $this->data['button_remove'] = $this->language->get('button_remove');
        
        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }
        $this->data['error'] = $this->error;

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
               'text'      => $this->language->get('text_home'),
        'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
              'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
               'text'      => $this->language->get('text_shipping'),
        'href'      => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'),
              'separator' => ' :: '
        );
        
        $this->data['breadcrumbs'][] = array(
               'text'      => $this->language->get('heading_title'),
        'href'      => $this->url->link('shipping/russianmail', 'token=' . $this->session->data['token'], 'SSL'),
              'separator' => ' :: '
        );
        
        $this->data['action'] = $this->url->link('shipping/russianmail', 'token=' . $this->session->data['token'], 'SSL');
        
        $this->data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');


        if (isset($this->request->post['russianmail_sum_to_free'])) {
            $this->data['russianmail_sum_to_free'] = $this->request->post['russianmail_sum_to_free'];
        } else {
            $this->data['russianmail_sum_to_free'] = $this->config->get('russianmail_sum_to_free');
        }
        
        if (isset($this->request->post['russianmail_status'])) {
            $this->data['russianmail_status'] = $this->request->post['russianmail_status'];
        } else {
            $this->data['russianmail_status'] = $this->config->get('russianmail_status');
        }

        if (isset($this->request->post['russianmail_use_fallback'])) {
            $this->data['russianmail_use_fallback'] = $this->request->post['russianmail_use_fallback'];
        } else {
            $this->data['russianmail_use_fallback'] = $this->config->get('russianmail_use_fallback');
        }
        
        if (isset($this->request->post['russianmail_markup_full_ordinary'])) {
            $this->data['russianmail_markup_full_ordinary'] = $this->request->post['russianmail_markup_full_ordinary'];
        } else {
            $this->data['russianmail_markup_full_ordinary'] = $this->config->get('russianmail_markup_full_ordinary');
        }

        if (isset($this->request->post['russianmail_markup_full_online'])) {
            $this->data['russianmail_markup_full_online'] = $this->request->post['russianmail_markup_full_online'];
        } else {
            $this->data['russianmail_markup_full_online'] = $this->config->get('russianmail_markup_full_online');
        }

        if (isset($this->request->post['russianmail_markup_full_avia'])) {
            $this->data['russianmail_markup_full_avia'] = $this->request->post['russianmail_markup_full_avia'];
        } else {
            $this->data['russianmail_markup_full_avia'] = $this->config->get('russianmail_markup_full_avia');
        }

        if (isset($this->request->post['russianmail_markup_part_ordinary'])) {
            $this->data['russianmail_markup_part_ordinary'] = $this->request->post['russianmail_markup_part_ordinary'];
        } else {
            $this->data['russianmail_markup_part_ordinary'] = $this->config->get('russianmail_markup_part_ordinary');
        }

        if (isset($this->request->post['russianmail_markup_part_online'])) {
            $this->data['russianmail_markup_part_online'] = $this->request->post['russianmail_markup_part_online'];
        } else {
            $this->data['russianmail_markup_part_online'] = $this->config->get('russianmail_markup_part_online');
        }

        if (isset($this->request->post['russianmail_markup_part_avia'])) {
            $this->data['russianmail_markup_part_avia'] = $this->request->post['russianmail_markup_part_avia'];
        } else {
            $this->data['russianmail_markup_part_avia'] = $this->config->get('russianmail_markup_part_avia');
        }
        if (isset($this->request->post['russianmail_login'])) {
            $this->data['russianmail_login'] = $this->request->post['russianmail_login'];
        } else {
            $this->data['russianmail_login'] = $this->config->get('russianmail_login');
        }
        
        if (isset($this->request->post['russianmail_password'])) {
            $this->data['russianmail_password'] = $this->request->post['russianmail_password'];
        } else {
            $this->data['russianmail_password'] = $this->config->get('russianmail_password');
        }
        if (isset($this->request->post['russianmail_default_size'])) {
            $this->data['russianmail_default_size'] = $this->request->post['russianmail_default_size'];
        } else {
            $this->data['russianmail_default_size'] = $this->config->get('russianmail_default_size');
        }
        if (isset($this->request->post['russianmail_default_weight'])) {
            $this->data['russianmail_default_weight'] = $this->request->post['russianmail_default_weight'];
        } else {
            $this->data['russianmail_default_weight'] = $this->config->get('russianmail_default_weight');
        }
        if (isset($this->request->post['russianmail_postalcode'])) {
            $this->data['russianmail_postalcode'] = $this->request->post['russianmail_postalcode'];
        } else {
            $this->data['russianmail_postalcode'] = $this->config->get('russianmail_postalcode');
        }
        if (isset($this->request->post['russianmail_log'])) {
            $this->data['russianmail_log'] = $this->request->post['russianmail_log'];
        } else {
            $this->data['russianmail_log'] = $this->config->get('russianmail_log');
        }
        if (isset($this->request->post['russianmail_sort_order'])) {
            $this->data['russianmail_sort_order'] = $this->request->post['russianmail_sort_order'];
        } else {
            $this->data['russianmail_sort_order'] = $this->config->get('russianmail_sort_order');
        }
        if (isset($this->request->post['russianmail_timeout'])) {
            $this->data['russianmail_timeout'] = $this->request->post['russianmail_timeout'];
        } else {
            $this->data['russianmail_timeout'] = $this->config->get('russianmail_timeout');
        }
        if (isset($this->request->post['russianmail_use_online'])) {
            $this->data['russianmail_use_online'] = $this->request->post['russianmail_use_online'];
        } else {
            $this->data['russianmail_use_online'] = $this->config->get('russianmail_use_online');
        }
        if (isset($this->request->post['russianmail_category_data'])) {
            $this->data['russianmail_category_data'] = $this->request->post['russianmail_category_data'];
        } elseif ($this->config->get('russianmail_category_data')) {
            $this->data['russianmail_category_data'] = $this->config->get('russianmail_category_data');
        } else {
            $this->data['russianmail_category_data'] = array();
        }
        if (isset($this->request->post['russianmail_markup_declared_value'])) {
            $this->data['russianmail_markup_declared_value'] = $this->request->post['russianmail_markup_declared_value'];
        } elseif ($this->config->get('russianmail_markup_declared_value')) {
            $this->data['russianmail_markup_declared_value'] = $this->config->get('russianmail_markup_declared_value');
        } else {
            $this->data['russianmail_markup_declared_value'] = array();
        }

        $this->template = 'shipping/russianmail.tpl';
        $this->children = array(
        'common/header',
        'common/footer'
        );
                
        $this->response->setOutput($this->render());
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'shipping/russianmail')) {
            $this->error['warning'] = $this->language->get('error_permission');
        } else {
            $default_size = $this->request->post['russianmail_default_size'];
            if (!isset($default_size['type'])) {
                $default_size['type'] = 'size';
            }
            switch ($default_size['type']) {
                case 'volume':
                    if (!is_numeric($default_size['volume'])) {
                            $this->error['russianmail_default_size']['volume'] = $this->language->get('error_numeric');
                    } elseif ($default_size['volume'] <= 0) {
                            $this->error['russianmail_default_size']['volume'] = $this->language->get('error_positive_numeric');
                    }
                    
                    break;
                case 'size':
                    foreach (array('size_a', 'size_b', 'size_c') as $item) {
                            
                        if (!is_numeric($default_size[$item])) {
                            $this->error['russianmail_default_size']['size'] = $this->language->get('error_numeric');
                            break;
                        } elseif ($default_size[$item] <= 0) {
                            $this->error['russianmail_default_size']['size'] = $this->language->get('error_positive_numeric');
                            break;
                        }
                    }
                    break;
            }
        
            $default_weight = $this->request->post['russianmail_default_weight'];
            
            if (!is_numeric($default_weight)) {
                $this->error['russianmail_default_weight'] = $this->language->get('error_numeric');
            } elseif ($default_weight <= 0) {
                $this->error['russianmail_default_weight'] = $this->language->get('error_positive_numeric');
            }
                
            $default_postal = $this->request->post['russianmail_postalcode'];
            
            if (!is_numeric($default_postal)) {
                $this->error['russianmail_postalcode'] = $this->language->get('error_numeric');
            } elseif ($default_postal <= 0) {
                $this->error['russianmail_postalcode'] = $this->language->get('error_positive_numeric');
            }
                
            if (!empty($this->request->post['russianmail_category_data'])) {
                foreach ($this->request->post['russianmail_category_data'] as $category_data_row => $category_data) {
                    if ($category_data['category_id'] == '' || !is_numeric($category_data['category_id'])) {
                        $this->error['russianmail_category_data'][$category_data_row]['category_id'] = $this->language->get('error_numeric');
                    }
                    if ($category_data['weight'] == '' || !is_numeric($category_data['weight'])) {
                        $this->error['russianmail_category_data'][$category_data_row]['weight'] = $this->language->get('error_numeric');
                    }
                    foreach (array('size_a', 'size_b', 'size_c') as $item) {
                        if (!is_numeric($category_data[$item])) {
                            $this->error['russianmail_category_data'][$category_data_row]['size'] = $this->language->get('error_numeric');
                            break;
                        } elseif ($category_data[$item] <= 0) {
                            $this->error['russianmail_category_data'][$category_data_row]['size'] = $this->language->get('error_positive_numeric');
                            break;
                        }
                    }
                }
            }

            if (!empty($this->request->post['russianmail_markup_declared_value'])) {
                foreach ($this->request->post['russianmail_markup_declared_value'] as $row => $data) {
                    foreach (array('customer_group_id', 'value') as $key) {
                        if ($data[$key] == '' || !is_numeric($data[$key])) {
                            $this->error['russianmail_markup_declared_value'][$row][$key] = $this->language->get('error_numeric');
                        }
                    }
                }
            }

            foreach (array('russianmail_sort_order', 'russianmail_timeout', 'russianmail_sum_to_free', 'russianmail_markup_full_ordinary', 'russianmail_markup_full_online', 'russianmail_markup_full_avia', 'russianmail_markup_part_ordinary', 'russianmail_markup_part_online', 'russianmail_markup_part_avia') as $item) {
                if ($this->request->post[$item] == "" || !is_numeric($this->request->post[$item])) {
                    $this->error[$item] = $this->language->get('error_numeric');
                } elseif ($this->request->post[$item] < 0) {
                    $this->error[$item] = $this->language->get('error_positive_numeric');
                }
            }
        }
        
        if (!$this->error) {
            return true;
        } else {
            $this->error['warning'] = $this->language->get('error_warning');
            return false;
        }
    }
}
?>
