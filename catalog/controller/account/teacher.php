<?php
class ControllerAccountTeacher extends Controller {
    private $error = array();

    public function index() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/teacher', '', 'SSL');

            $this->redirect($this->url->link('account/login', '', 'SSL'));
        }

        $this->language->load('account/teacher');

        $this->load->model('account/teacher');
        $customer_id = $this->customer->getId();

        $teacher = $this->model_account_teacher->getTeacherForCustomerId($customer_id);


        if (isset($teacher) && $teacher['teacher_id'] != 0) {

            $students = $this->model_account_teacher->getStudents($teacher['teacher_id']);
            $this->load->model('account/order');

            foreach ($students as $student) {
                $this->data['students'][] = array(
                    'customer_id'       => $student['customer_id'],
                    'email'             => $student['email'],
                    'firstname'         => $student['firstname'],
                    'middlename'        => $student['middlename'],
                    'lastname'          => $student['lastname'],
                    'phone'             => $student['phone'],
                    'totalOrder'        => $student['total'],
                    'totalSum'          => $student['totalSum']
                );
            }

            $total_students = $this->model_account_teacher->getTotalStudents($teacher['teacher_id']);
        }


        $this->document->setTitle($this->language->get('heading_title'));

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_account'),
            'href'      => $this->url->link('account/account', '', 'SSL'),
            'separator' => $this->language->get('text_separator')
        );

        $url = '';

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('account/teacher', $url, 'SSL'),
            'separator' => $this->language->get('text_separator')
        );

        $this->data['heading_title'] = $this->language->get('heading_title');


       $this->data['text_customer'] = $this->language->get('text_customer');
       $this->data['text_email'] = $this->language->get('text_email');
       $this->data['text_phone'] = $this->language->get('text_phone');

       $this->data['button_continue'] = $this->language->get('button_continue');


        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $pagination = new Pagination();
        $pagination->total = $total_students;
        $pagination->page = $page;
        $pagination->limit = 10;
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('account/teacher', 'page={page}', 'SSL');

        $this->data['pagination'] = $pagination->render();


        $this->data['continue'] = $this->url->link('account/account', '', 'SSL');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/teacher.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/account/teacher.tpl';
        } else {
            $this->template = 'default/template/account/teacher.tpl';
        }

        $this->children = array(
            'common/column_left',
            'common/column_right',
            'common/content_top',
            'common/content_bottom',
            'common/footer',
            'common/header'
        );

        $this->response->setOutput($this->render());
    }
}
?>