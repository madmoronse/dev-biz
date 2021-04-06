<?php
class ControllerReportAbandonedCarts extends Controller {
    public function index() {
        $this->language->load('report/abandoned_carts');

        $this->document->setTitle($this->language->get('heading_title'));

        if (isset($this->request->get['filter_customer'])) {
            $filter_customer = $this->request->get['filter_customer'];
        } else {
            $filter_customer = NULL;
        }

        if (isset($this->request->get['filter_customer_group_id'])) {
			$filter_customer_group_id = $this->request->get['filter_customer_group_id'];
		} else {
			$filter_customer_group_id = null;
		}

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer=' . urlencode($this->request->get['filter_customer']);
        }

        if (isset($this->request->get['filter_customer_group_id'])) {
            $url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'text'      => $this->language->get('text_home'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'href'      => $this->url->link('report/abandoned_carts', 'token=' . $this->session->data['token'] . $url, 'SSL'),
            'text'      => $this->language->get('heading_title'),
            'separator' => ' :: '
        );

        $this->load->model('report/abandoned_carts');

        $this->load->model('sale/customer_group');
        $this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

        $this->load->model('sale/customer');

        $this->data['customers'] = array();

        $data = array(
            'filter_customer' => $filter_customer,
            'filter_customer_group_id' => $filter_customer_group_id, 
            'start'           => ($page - 1) * 20,
            'limit'           => 20
        );

        $customer_total = $this->model_report_abandoned_carts->getTotalCustomersCarts($data);
        $results = $this->model_report_abandoned_carts->getCustomersCarts($data);


        foreach ($results as $result) {
            $action = array();

            if ($result['customer_id']) {
                $action[] = array(
                    'text' => 'Edit',
                    'href' => $this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'], 'SSL')
                );
            }

            $customer_info = $this->model_sale_customer->getCustomer($result['customer_id']);

            if ($customer_info) {
                $customer = $customer_info['firstname'] . ' ' . $customer_info['lastname'];
                $customer_group = $customer_info['customer_group'];
            } else {
                $customer = $this->language->get('text_guest');
            }
            //$cart_products = $this->model_report_abandoned_carts->getCartProducts($result['cart']);
            $this->data['customers'][] = array(
                'customer'   => $customer,
                'customer_group' => $result['customer_group_name'],
                'url'        => $result['url'],
                'date_added' => date('Y-m-d H:i:s', strtotime($result['date_added'])),
                'cart_products' => $this->model_report_abandoned_carts->getCartProducts($result['cart']),
                'action'     => $action
            );
        }



        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_no_results'] = $this->language->get('text_no_results');

        $this->data['column_customer'] = $this->language->get('column_customer');
        $this->data['column_url'] = $this->language->get('column_url');
        $this->data['column_date_added'] = $this->language->get('column_date_added');
        $this->data['column_action'] = $this->language->get('column_action');

        $this->data['button_filter'] = $this->language->get('button_filter');

        $this->data['token'] = $this->session->data['token'];

        $url = '';

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer=' . urlencode($this->request->get['filter_customer']);
        }

        $pagination = new Pagination();
        $pagination->total = $customer_total;
        $pagination->page = $page;
        $pagination->limit = 20;
        $pagination->url = $this->url->link('report/abandoned_carts', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $this->data['pagination'] = $pagination->render();

        $this->data['filter_customer'] = $filter_customer;


        $this->template = 'report/abandoned_carts.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }
}
?>