<?php

class ControllerModuleRedirects extends Controller
{
    public function index()
    {
        $this->data = $this->language->load('module/redirects');
        $this->load->model('setting/setting');

        $this->data['breadcrumbs'] = array();
        $this->document->setTitle($this->language->get('heading_title'));

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['token'] = $this->session->data['token'];
        // Update
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
            $this->update();
        }

        if (isset($this->request->post['redirects_yandex_direct'])) {
            $this->data['redirects_yandex_direct'] = $this->request->post['redirects_yandex_direct'];
        } else {
            $this->data['redirects_yandex_direct'] = $this->config->get('redirects_yandex_direct');
        }
        if (!is_array($this->data['redirects_yandex_direct'])) {
            $this->data['redirects_yandex_direct'] = array();
        }
        if (isset($this->request->post['redirects_users'])) {
            $this->data['redirects_users'] = $this->request->post['redirects_users'];
        } else {
            $this->data['redirects_users'] = $this->config->get('redirects_users');
        }
        if (!is_array($this->data['redirects_users'])) {
            $this->data['redirects_users'] = array();
        }
        $query = $this->db->query('SELECT customer_group_id as id FROM ' . DB_PREFIX . 'customer_group');
        $this->data['user_groups'] = array_map(function ($item) {
            return $item['id'];
        }, $query->rows);
        $this->template = 'module/redirects.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );
        $this->response->setOutput($this->render());
    }

    private function validate() {
        if (!$this->user->hasPermission('modify', 'module/redirects')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    protected function update()
    {
        $error = false;
        $keys = array('redirects_yandex_direct' , 'redirects_users');
        foreach ($keys as $global) {
            if (isset($this->request->post[$global])) {
                $redirects = $this->request->post[$global];
                if (!is_array($redirects)) {
                    unset($this->request->post[$global]);
                } else {
                    foreach ($redirects as $key => $redirect) {
                        $redirect = (object) $redirect;
                        switch ($global) {
                            case 'redirects_users':
                                $name = 'users';
                                $users = array_map(function ($item) {
                                    return (int) trim($item);
                                }, explode(',', $redirect->users));
                                $this->request->post[$global][$key][$name] = $redirect->users = $users;
                                break;
                            case 'redirects_yandex_direct':
                                $name = 'groups';
                                break;
                        }
                        if (false === $this->validateRedirectGroup((object) $redirect, $name)) {
                            $error = true;
                            unset($this->request->post[$global][$key]);
                        }
                    }
                    if (empty($this->request->post[$global])) {
                        unset($this->request->post[$global]);
                    }
                }
            }
        }
        if (!$error) {
            $this->model_setting_setting->editSetting('redirects', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
        }
        $this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
    }

    /**
     * Validate redirect groups
     *
     * @param object $redirect
     * @param string $gname
     * @return boolean
     */
    private function validateRedirectGroup($redirect, $gname = 'groups')
    {
        if (!isset($redirect->link) ||
            !isset($redirect->{$gname}) ||
            (isset($redirect->{$gname}) && (!is_array($redirect->{$gname}) || empty($redirect->{$gname})))
        ) {
            $this->session->data['error'] = $this->language->get('error_wrong_input');
            return false;
        }
        foreach ($redirect->{$gname} as $data) {
            if (!is_numeric($data)) {
                $this->session->data['error'] = $this->language->get('error_wrong_input');
                return false;
            }
        }
        if (!filter_var($redirect->link, FILTER_VALIDATE_URL)) {
            $this->session->data['error'] = $this->language->get('error_wrong_url');
            return false;
        }
        return true;
    }
}