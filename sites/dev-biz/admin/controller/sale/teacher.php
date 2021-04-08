<?php
class ControllerSaleTeacher extends Controller
{
    private $error = array();

    public function index()
    {
        $this->language->load('sale/teacher');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('sale/teacher');

        $this->getList();

        
    }

    public function insert()
    {
        $this->language->load('sale/teacher');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('sale/teacher');
        $this->data['token'] = $this->session->data['token'];

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateForm()) {
            $this->load->model('sale/customer');
            
            $customer = $this->model_sale_customer->getCustomerByEmail($this->request->post['filter_email']);
            
            $customer_id = $customer['customer_id'];
       
            $this->model_sale_teacher->addTeacher($customer_id);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->redirect($this->url->link('sale/teacher', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function update()
    {
        $this->language->load('sale/teacher');
        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/teacher');
        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateForm()) {
            $this->request->post['teacher_id'] = $this->request->get['teacher_id'];
            $customer = $this->model_sale_customer->getCustomerByEmail($this->request->post['filter_email']);
            
            $this->request->post['customer_id'] = $customer['customer_id'];

            $this->model_sale_teacher->editTeacher($this->request->post);

            $this->session->data['success'] = $this->language->get('text_update');

            $url = '';
            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->redirect($this->url->link('sale/teacher', 'token=' .$this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function delete()
    {
        $this->language->load('sale/teacher');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/teacher');

        if (isset($this->request->post['selected'])) {
            foreach ($this->request->post['selected'] as $teacher_id) {
                $this->model_sale_teacher->deleteTeacher($teacher_id);
            }
            $this->session->data['success'] = $this->language->get('text_delete');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->redirect($this->url->link('sale/teacher', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    public function getList()
    {
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'cgd.name';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('sale/teacher', 'token=' . $this->session->data['token'] . $url, 'SSL'),
            'separator' => ' :: '
        );

        $this->data['insert'] = $this->url->link('sale/teacher/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $this->data['delete'] = $this->url->link('sale/teacher/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $this->data['teachers'] = array();

        $teacher_total = $this->model_sale_teacher->getTotalTeachers();

        $results = $this->model_sale_teacher->getTeachers();
        
        foreach ($results as $result) {
            $action = array();

            $action[] = array(
                'text' => $this->language->get('text_edit'),
                'href' => $this->url->link('sale/teacher/update', 'token=' . $this->session->data['token'] . '&teacher_id=' . $result['teacher_id'] . $url, 'SSL')
            );

            $data = $this->model_sale_teacher->getTeacherInfo($result['customer_id']);

            $this->data['teachers'][] = array(
                'teacher_id'        => $result['teacher_id'],
                'email'             => $data['email'],
                'name'              => $data['lastname'] . ' ' . $data['firstname'] . ' ' . $data['middlename'],
                'customer_id'       => $result['customer_id'],
                'selected'          => isset($this->request->post['selected']) && in_array($result['teacher_id'], $this->request->post['selected']),
                'action'            => $action
            );
        }


        
        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_no_results'] = $this->language->get('text_no_results');
        $this->data['text_code'] = $this->language->get('text_code');
        $this->data['text_name'] = $this->language->get('text_name');
        $this->data['text_email'] = $this->language->get('text_email');

        $this->data['column_sort_order'] = $this->language->get('column_sort_order');
        $this->data['column_action'] = $this->language->get('column_action');

        $this->data['button_insert'] = $this->language->get('button_insert');
        $this->data['button_delete'] = $this->language->get('button_delete');

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }

        $url = '';

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $url = '';

        $pagination = new Pagination();
        $pagination->total = $teacher_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_admin_limit');
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('sale/teacher', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $this->data['pagination'] = $pagination->render();

        $this->template = 'sale/teacher_list.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }

    public function getForm()
    {
        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_yes'] = $this->language->get('text_yes');
        $this->data['text_no'] = $this->language->get('text_no');
        $this->data['text_no_results'] = $this->language->get('text_no_results');


        $this->data['entry_email'] = $this->language->get('entry_email');
        $this->data['entry_name'] = $this->language->get('entry_name');
        $this->data['entry_code'] = $this->language->get('entry_code');

        $this->data['student_email'] = $this->language->get('student_email');
        $this->data['student_name'] = $this->language->get('student_name');
        $this->data['student_phone'] = $this->language->get('student_phone');

        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $this->data['error_name'] = $this->error['name'];
        } else {
            $this->data['error_name'] = array();
        }

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('sale/teacher', 'token=' . $this->session->data['token'] . $url, 'SSL'),
            'separator' => ' :: '
        );

        if (!isset($this->request->get['teacher_id'])) {
            $this->data['action'] = $this->url->link('sale/teacher/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
        } else {
            $this->data['action'] = $this->url->link('sale/teacher/update', 'token=' . $this->session->data['token'] . '&teacher_id=' . $this->request->get['teacher_id'] . $url, 'SSL');
        }

        $this->data['cancel'] = $this->url->link('sale/teacher', 'token=' . $this->session->data['token'] . $url, 'SSL');

        if (isset($this->request->get['teacher_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $teacher_info = $this->model_sale_teacher->getTeacher($this->request->get['teacher_id']);

            $data = $this->model_sale_teacher->getTeacherInfo($teacher_info['customer_id']);

            if($teacher_info == false) {
              $this->redirect($this->url->link('sale/teacher', 'token=' . $this->session->data['token'] . $url, 'SSL'));
     
            } else {
                $students = $this->model_sale_teacher->getStudents($this->request->get['teacher_id']);
            }

            foreach ($students as $student) {
                $this->data['students'][] = array(
                    'customer_id'        => $student['customer_id'],
                    'email'             => $student['email'],
                    'firstname'              => $student['firstname'],
                    'middlename'              => $student['middlename'],
                    'lastname'              => $student['lastname'],
                    'phone'              => $student['phone']
                );
            }
        }


        if (isset($this->request->post['filter_email'])) {
            $this->data['email'] = $this->request->post['filter_email'];
        } elseif (!empty($teacher_info)) {
            $this->data['email'] = $data['email'];
        } else {
            $this->data['email'] = '';
        }



        if (isset($this->request->post['name'])) {
            $this->data['name'] = $this->request->post['name'];
        } elseif (!empty($teacher_info)) {
            $this->data['name'] = $data['lastname'] . $data['middlename'] . $data['lastname'];
        } else {
            $this->data['name'] = '';
        }

        if (isset($this->request->post['code'])) {
            $this->data['code'] = $this->request->post['code'];
        } elseif (!empty($teacher_info)) {
            $this->data['code'] = $teacher_info['code'];
        } else {
            $this->data['code'] = '';
        }

        $this->template = 'sale/teacher_form.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }

    protected function validateForm() {

        $this->load->model('sale/customer');
        $customer = $this->model_sale_customer->getCustomerByEmail($this->request->post['filter_email']);

        if (empty($customer)) {
            $this->error['warning'] = $this->language->get('error_warning');
        }
        if (!$this->request->post['name']) {
            $this->error['warning'] = $this->language->get('error_name');
        }
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
