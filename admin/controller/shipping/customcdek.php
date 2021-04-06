<?php
class ControllerShippingCustomCdek extends Controller
{
 
    private $error = array();
    
    public function index()
    {
        $this->language->load('shipping/customcdek');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('setting/setting');
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('customcdek', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
        }
        $this->load->model('sale/customer_group');
        $this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
        $this->data['entry_customcdek_use_fallback'] = $this->language->get('entry_customcdek_use_fallback');
        $this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['customcdek_dressingroom'] = $this->language->get('customcdek_dressingroom');
        $this->data['customcdek_pvz'] = $this->language->get('customcdek_pvz_activation_text');
        $this->data['customcdek_door'] = $this->language->get('customcdek_door_activation_text');
        $this->data['customcdek_status_text'] = $this->language->get('customcdek_status_text');
        $this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');
        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');
        $this->data['tab_main'] = $this->language->get('tab_main');
        $this->data['tab_markup'] = $this->language->get('tab_markup');
        $this->data['text_markup_full'] = $this->language->get('text_markup_full');
        $this->data['text_markup_part'] = $this->language->get('text_markup_part');
        $this->data['entry_markup_door'] = $this->language->get('entry_markup_door');
        $this->data['entry_markup_pvz'] = $this->language->get('entry_markup_pvz');
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
        $this->data['text_markup_option'] = $this->language->get('text_markup_option');
        $this->data['entry_markup_dressingroom'] = $this->language->get('entry_markup_dressingroom');
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
        'href'      => $this->url->link('shipping/customcdek', 'token=' . $this->session->data['token'], 'SSL'),
              'separator' => ' :: '
        );
        
        $this->data['action'] = $this->url->link('shipping/customcdek', 'token=' . $this->session->data['token'], 'SSL');
        
        $this->data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');

        if (isset($this->request->post['customcdek_use_fallback'])) {
            $this->data['customcdek_use_fallback'] = $this->request->post['customcdek_use_fallback'];
        } else {
            $this->data['customcdek_use_fallback'] = $this->config->get('customcdek_use_fallback');
        }

        if (isset($this->request->post['customcdek_pvz_activation'])) {
            $this->data['customcdek_pvz_activation'] = $this->request->post['customcdek_pvz_activation'];
        } else {
            $this->data['customcdek_pvz_activation'] = $this->config->get('customcdek_pvz_activation');
        }

        if (isset($this->request->post['customcdek_door_activation'])) {
            $this->data['customcdek_door_activation'] = $this->request->post['customcdek_door_activation'];
        } else {
            $this->data['customcdek_door_activation'] = $this->config->get('customcdek_door_activation');
        }

        if (isset($this->request->post['customcdek_status'])) {
            $this->data['customcdek_status'] = $this->request->post['customcdek_status'];
        } else {
            $this->data['customcdek_status'] = $this->config->get('customcdek_status');
        }

        if (isset($this->request->post['customcdek_dressingroom_activation'])) {
            $this->data['customcdek_dressingroom_activation'] = $this->request->post['customcdek_dressingroom_activation'];
        } else {
            $this->data['customcdek_dressingroom_activation'] = $this->config->get('customcdek_dressingroom_activation');
        }

        if (isset($this->request->post['customcdek_markup_full_door'])) {
            $this->data['customcdek_markup_full_door'] = $this->request->post['customcdek_markup_full_door'];
        } else {
            $this->data['customcdek_markup_full_door'] = $this->config->get('customcdek_markup_full_door');
        }

        if (isset($this->request->post['customcdek_markup_full_pvz'])) {
            $this->data['customcdek_markup_full_pvz'] = $this->request->post['customcdek_markup_full_pvz'];
        } else {
            $this->data['customcdek_markup_full_pvz'] = $this->config->get('customcdek_markup_full_pvz');
        }

        if (isset($this->request->post['customcdek_markup_part_door'])) {
            $this->data['customcdek_markup_part_door'] = $this->request->post['customcdek_markup_part_door'];
        } else {
            $this->data['customcdek_markup_part_door'] = $this->config->get('customcdek_markup_part_door');
        }
        if (isset($this->request->post['customcdek_markup_part_pvz'])) {
            $this->data['customcdek_markup_part_pvz'] = $this->request->post['customcdek_markup_part_pvz'];
        } else {
            $this->data['customcdek_markup_part_pvz'] = $this->config->get('customcdek_markup_part_pvz');
        }
        if (isset($this->request->post['customcdek_markup_dressingroom'])) {
            $this->data['customcdek_markup_dressingroom'] = $this->request->post['customcdek_markup_dressingroom'];
        } else {
            $this->data['customcdek_markup_dressingroom'] = $this->config->get('customcdek_markup_dressingroom');
        }
        if (isset($this->request->post['customcdek_login'])) {
            $this->data['customcdek_login'] = $this->request->post['customcdek_login'];
        } else {
            $this->data['customcdek_login'] = $this->config->get('customcdek_login');
        }
        if (isset($this->request->post['customcdek_postalcode'])) {
            $this->data['customcdek_postalcode'] = $this->request->post['customcdek_postalcode'];
        } else {
            $this->data['customcdek_postalcode'] = $this->config->get('customcdek_postalcode');
        }
        if (isset($this->request->post['customcdek_password'])) {
            $this->data['customcdek_password'] = $this->request->post['customcdek_password'];
        } else {
            $this->data['customcdek_password'] = $this->config->get('customcdek_password');
        }
        if (isset($this->request->post['customcdek_default_size'])) {
            $this->data['customcdek_default_size'] = $this->request->post['customcdek_default_size'];
        } else {
            $this->data['customcdek_default_size'] = $this->config->get('customcdek_default_size');
        }
        if (isset($this->request->post['customcdek_default_weight'])) {
            $this->data['customcdek_default_weight'] = $this->request->post['customcdek_default_weight'];
        } else {
            $this->data['customcdek_default_weight'] = $this->config->get('customcdek_default_weight');
        }
        if (isset($this->request->post['customcdek_log'])) {
            $this->data['customcdek_log'] = $this->request->post['customcdek_log'];
        } else {
            $this->data['customcdek_log'] = $this->config->get('customcdek_log');
        }
        if (isset($this->request->post['customcdek_sort_order'])) {
            $this->data['customcdek_sort_order'] = $this->request->post['customcdek_sort_order'];
        } else {
            $this->data['customcdek_sort_order'] = $this->config->get('customcdek_sort_order');
        }
        if (isset($this->request->post['customcdek_timeout'])) {
            $this->data['customcdek_timeout'] = $this->request->post['customcdek_timeout'];
        } else {
            $this->data['customcdek_timeout'] = $this->config->get('customcdek_timeout');
        }
		if (isset($this->request->post['customcdek_category_data'])) {
			$this->data['customcdek_category_data'] = $this->request->post['customcdek_category_data'];
		} elseif ($this->config->get('customcdek_category_data')) {
			$this->data['customcdek_category_data'] = $this->config->get('customcdek_category_data');
		} else {
			$this->data['customcdek_category_data'] = array();
        }
        if (isset($this->request->post['customcdek_markup_declared_value'])) {
            $this->data['customcdek_markup_declared_value'] = $this->request->post['customcdek_markup_declared_value'];
        } elseif ($this->config->get('customcdek_markup_declared_value')) {
            $this->data['customcdek_markup_declared_value'] = $this->config->get('customcdek_markup_declared_value');
        } else {
            $this->data['customcdek_markup_declared_value'] = array();
        }
        $this->template = 'shipping/customcdek.tpl';
        $this->children = array(
        'common/header',
        'common/footer'
        );
                
        $this->response->setOutput($this->render());
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'shipping/customcdek')) {
            $this->error['warning'] = $this->language->get('error_permission');
        } else {
            $default_size = $this->request->post['customcdek_default_size'];
            if (!isset($default_size['type'])) {
                $default_size['type'] = 'size';
            }
            switch ($default_size['type']) {
                case 'volume':
                    if (!is_numeric($default_size['volume'])) {
                        $this->error['customcdek_default_size']['volume'] = $this->language->get('error_numeric');
                    } elseif ($default_size['volume'] <= 0) {
                        $this->error['customcdek_default_size']['volume'] = $this->language->get('error_positive_numeric');
                    }
                    break;
                case 'size':
                    foreach (array('size_a', 'size_b', 'size_c') as $item) {
                                
                        if (!is_numeric($default_size[$item])) {
                            $this->error['customcdek_default_size']['size'] = $this->language->get('error_numeric');
                            break;
                        } elseif ($default_size[$item] <= 0) {
                            $this->error['customcdek_default_size']['size'] = $this->language->get('error_positive_numeric');
                            break;
                        }
                    }
                    break;
            }
            $default_weight = $this->request->post['customcdek_default_weight'];
            if (!is_numeric($default_weight)) {
                $this->error['customcdek_default_weight'] = $this->language->get('error_numeric');
            } elseif ($default_weight <= 0) {
                $this->error['customcdek_default_weight'] = $this->language->get('error_positive_numeric');
            }
                
            $default_postal = $this->request->post['customcdek_postalcode'];
                
            if (!is_numeric($default_postal)) {
                $this->error['customcdek_postalcode'] = $this->language->get('error_numeric');
            } elseif ($default_postal <= 0) {
                $this->error['customcdek_postalcode'] = $this->language->get('error_positive_numeric');
            }

            if (!empty($this->request->post['customcdek_category_data'])) {
                foreach ($this->request->post['customcdek_category_data'] as $category_data_row => $category_data) {
                    if ($category_data['category_id'] == '' || !is_numeric($category_data['category_id'])) {
                        $this->error['customcdek_category_data'][$category_data_row]['category_id'] = $this->language->get('error_numeric');
                    }
                    if ($category_data['weight'] == '' || !is_numeric($category_data['weight'])) {
                        $this->error['customcdek_category_data'][$category_data_row]['weight'] = $this->language->get('error_numeric');
                    }
                    foreach (array('size_a', 'size_b', 'size_c') as $item) {
                        if (!is_numeric($category_data[$item])) {
                            $this->error['customcdek_category_data'][$category_data_row]['size'] = $this->language->get('error_numeric');
                            break;
                        } elseif ($category_data[$item] <= 0) {
                            $this->error['customcdek_category_data'][$category_data_row]['size'] = $this->language->get('error_positive_numeric');
                            break;
                        }
                    }
                }
            }

            if (!empty($this->request->post['customcdek_markup_declared_value'])) {
                foreach ($this->request->post['customcdek_markup_declared_value'] as $row => $data) {
                    foreach (array('customer_group_id', 'value') as $key) {
                        if ($data[$key] == '' || !is_numeric($data[$key])) {
                            $this->error['customcdek_markup_declared_value'][$row][$key] = $this->language->get('error_numeric');
                        }
                    }
                }
            }
            
            foreach (array('customcdek_sort_order', 'customcdek_timeout', 'customcdek_markup_full_door', 'customcdek_markup_full_pvz', 'customcdek_markup_part_door', 'customcdek_markup_part_pvz', 'customcdek_markup_dressingroom') as $item) {
                if ($this->request->post[$item] == "" || !is_numeric($this->request->post[$item])) {
                    $this->error[$item] = $this->language->get('error_numeric');
                }
                elseif ($this->request->post[$item] < 0) {
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
