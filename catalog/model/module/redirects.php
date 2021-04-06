<?php

class ModelModuleRedirects extends Model
{
    /**
     * Try redirects
     *
     * @return void
     */
    public function tryRedirects()
    {
        $this->redirectUsers();
        $this->redirectYandexDirect();
    }

    /**
     * Redirects users
     *
     * @return void
     */
    protected function redirectUsers()
    {
        $redirects = $this->config->get('redirects_users');
        if (!is_array($redirects)) {
            return false;
        }
        foreach ($redirects as $redirect) {
            $redirect = (object) $redirect;
            if ((!isset($redirect->link)) ||
                (!isset($redirect->users)) ||
                (isset($redirect->users) && !is_array($redirect->users)) ||
                (isset($redirect->link) && !filter_var($redirect->link, FILTER_VALIDATE_URL))
            ) {
                continue;
            }
        }
        $users = array_filter($redirect->users, function ($item) {
            return is_numeric($item);
        });
        if (in_array($this->customer->getId(), $users)) {
            $this->response->redirect($redirect->link);
        }
    }

    /**
     * Redirect yandex direct
     *
     * @return void
     */
    protected function redirectYandexDirect()
    {
        $redirects = $this->config->get('redirects_yandex_direct');
        if (!is_array($redirects)) {
            return false;
        }
        foreach ($redirects as $redirect) {
            $redirect = (object) $redirect;
            if ((!isset($redirect->link)) ||
                (!isset($redirect->groups)) ||
                (isset($redirect->groups) && !is_array($redirect->groups)) ||
                (isset($redirect->groups) && !filter_var($redirect->link, FILTER_VALIDATE_URL))
            ) {
                continue;
            }
            $groups = array_filter($redirect->groups, function ($item) {
                return is_numeric($item);
            });
            if (isset($this->request->get['yclid']) &&
                $this->request->get['yclid'] > 0 &&
                in_array($this->customer->getCustomerGroupId(), $groups)
            ) {
                $this->db->query(sprintf(
                    "INSERT INTO `yandex_direct_log` SET `customer_id` = %d, `visit_date` = NOW(), `yclid` = '%s'",
                    (int) $this->customer->getId(),
                    $this->db->escape($this->request->get['yclid'])
                ));
                $this->response->redirect($redirect->link);
            }
        }
    }
}