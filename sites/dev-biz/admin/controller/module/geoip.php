<?php
    class ControllerModuleGeoIP extends Controller {

        private $error = array();

        public function index() {

            $this->load->language('module/geoip');

            $this->document->setTitle($this->language->get('heading_title'));

            $this->load->model('setting/setting');

            if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
                $this->model_setting_setting->editSetting('geoip', $this->request->post);

                $this->session->data['success'] = $this->language->get('text_success');

                $this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
            }

            $this->data['heading_title'] = $this->language->get('heading_title');

            $this->data['text_yes'] = $this->language->get('text_yes');
            $this->data['text_no'] = $this->language->get('text_no');
            $this->data['text_none'] = $this->language->get('text_none');

            $this->data['button_save'] = $this->language->get('button_save');
            $this->data['button_cancel'] = $this->language->get('button_cancel');
            $this->data['button_remove'] = $this->language->get('button_remove');
            $this->data['button_add_rule'] = $this->language->get('button_add_rule');

            $this->data['entry_set_zone'] = $this->language->get('entry_set_zone');
            $this->data['entry_currency_for_ru'] = $this->language->get('entry_currency_for_ru');
            $this->data['entry_currency_for_ua'] = $this->language->get('entry_currency_for_ua');
            $this->data['entry_key'] = $this->language->get('entry_key');
            $this->data['entry_zone'] = $this->language->get('entry_zone');
            $this->data['entry_value'] = $this->language->get('entry_value');
            $this->data['entry_subdomain'] = $this->language->get('entry_subdomain');

            $this->data['text_geo_messages'] = $this->language->get('text_geo_messages');
            $this->data['text_geo_redirects'] = $this->language->get('text_geo_redirects');

            $this->load->model('localisation/currency');
            $this->data['currencies'] = $this->model_localisation_currency->getCurrencies();

            if (isset($this->error['warning'])) {
                $this->data['error_warning'] = $this->error['warning'];
            }
            else {
                $this->data['error_warning'] = '';
            }

            $this->data['error_key'] = isset($this->error['key']) ? $this->error['key'] : array();
            $this->data['error_fias'] = isset($this->error['fias']) ? $this->error['fias'] : array();
            $this->data['error_redirect_fias'] = isset($this->error['redirect_fias']) ? $this->error['redirect_fias'] : array();
            $this->data['error_subdomain'] = isset($this->error['subdomain']) ? $this->error['subdomain'] : array();

            $this->data['breadcrumbs'] = array();

            $this->data['breadcrumbs'][] = array('text'      => $this->language->get('text_home'),
                                                 'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
                                                 'separator' => false);

            $this->data['breadcrumbs'][] = array('text'      => $this->language->get('text_module'),
                                                 'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
                                                 'separator' => ' :: ');

            $this->data['breadcrumbs'][] = array('text'      => $this->language->get('heading_title'),
                                                 'href'      => $this->url->link('module/geoip', 'token=' . $this->session->data['token'], 'SSL'),
                                                 'separator' => ' :: ');

            $this->data['action'] = $this->url->link('module/geoip', 'token=' . $this->session->data['token'], 'SSL');

            $this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

            $this->data['token'] = $this->session->data['token'];

            if (isset($this->request->post['geoip_setting'])) {
                $this->data['geoip_setting'] = $this->request->post['geoip_setting'];
            }
            elseif ($this->config->get('geoip_setting')) {
                $this->data['geoip_setting'] = $this->config->get('geoip_setting');
            }

            $this->data['rules'] = array();

            if (isset($this->request->post['geoip_rule'])) {
                $this->data['rules'] = $this->request->post['geoip_rule'];
            }
            elseif ($this->config->get('geoip_rule')) {
                $this->data['rules'] = $this->config->get('geoip_rule');
            }

            foreach ($this->data['rules'] as & $rule) {
                $rule['fias_name'] = $this->getFiasName($rule['fias_id']);
            }

            $this->data['redirects'] = array();

            if (isset($this->request->post['geoip_redirect'])) {
                $this->data['redirects'] = $this->request->post['geoip_redirect'];
            }
            elseif ($this->config->get('geoip_redirect')) {
                $this->data['redirects'] = $this->config->get('geoip_redirect');
            }

            foreach ($this->data['redirects'] as & $redirect) {
                $redirect['fias_name'] = $this->getFiasName($redirect['fias_id']);
            }

            $this->load->model('design/layout');

            $this->data['layouts'] = $this->model_design_layout->getLayouts();

            $this->load->model('localisation/language');

            $this->data['languages'] = $this->model_localisation_language->getLanguages();

            $this->template = 'module/geoip.tpl';
            $this->children = array('common/header', 'common/footer');

            $this->response->setOutput($this->render());
        }

        public function search() {

            $json = array();

            if (isset($this->request->get['term'])) {

                $term = $this->request->get['term'];
                $parts = explode(' ', $term, 2);
                $where = '';

                if (isset($parts[1])) {
                    $where .= "(f1.offname LIKE '%" . $this->db->escape(utf8_strtolower($parts[0])) . "%'
                        AND (f2.offname LIKE '%" . $this->db->escape(utf8_strtolower($parts[1])) . "%' OR f3.offname LIKE '%" . $this->db->escape(utf8_strtolower($parts[1])) . "%')) OR ";
                }

                $where .= "(f1.offname LIKE '%" . $this->db->escape(utf8_strtolower($term)) . "%')";

                $json = $this->db->query("SELECT CONCAT_WS(', ',
                                                    CONCAT(f1.shortname, ' ', f1.offname),
                                                    CONCAT(f2.offname, ' ', f2.shortname),
                                                    CONCAT(f3.offname, ' ', f3.shortname)) label,
                                            CONCAT(f1.shortname, ' ', f1.offname) value,
                                            f1.fias_id
                                        FROM fias f1
                                            LEFT JOIN fias f2 ON f2.aoguid = f1.parentguid
                                            LEFT JOIN fias f3 ON f3.aoguid = f2.parentguid
                                        WHERE (" . $where . ")
                                            AND f1.level IN (0, 1, 4)
                                        ORDER BY f1.level, f2.level, f3.level
                                        LIMIT 10")->rows;
            }

            echo json_encode($json);
            die;
        }

        private function validate() {

            if (!$this->user->hasPermission('modify', 'module/geoip')) {
                $this->error['warning'] = $this->language->get('error_permission');
            }

            if (isset($this->request->post['geoip_rule'])) {

                foreach ($this->request->post['geoip_rule'] as $key => $value) {

                    if (!$value['key'] || !preg_match('#^[a-zA-Z0-9_-]*$#', $value['key'])) {
                        $this->error['key'][$key] = $this->language->get('error_key');
                    }

                    if (!(int)$value['fias_id']) {
                        $this->error['fias'][$key] = $this->language->get('error_fias');
                    }
                }
            }

            if (isset($this->request->post['geoip_redirect'])) {

                foreach ($this->request->post['geoip_redirect'] as $key => $value) {

                    if (!$value['value'] || !preg_match('#^http://([a-z0-9]+([\-a-z0-9]*[a-z0-9]+)?\.){0,}([a-z0-9]+([\-a-z0-9]*[a-z0-9]+)?){1,63}(\.[a-z0-9]{2,7})+/$#', $value['value'])) {
                        $this->error['subdomain'][$key] = $this->language->get('error_subdomain');
                    }

                    if (!(int)$value['fias_id']) {
                        $this->error['redirect_fias'][$key] = $this->language->get('error_fias');
                    }
                }
            }

            return !$this->error;
        }

        private function getFiasName($fiasId) {

            $row = $this->db->query("SELECT CONCAT(shortname, ' ', offname) name FROM fias WHERE fias_id = " . (int)$fiasId)->row;

            return $row ? $row['name'] : null;
        }
    }

?>