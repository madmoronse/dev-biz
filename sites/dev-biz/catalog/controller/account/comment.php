<?php 
class ControllerAccountComment extends Controller {
	private $error = array();

	public function index () {

		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/comment', '', 'SSL');
			$this->redirect($this->url->link('account/login', '', 'SSL')); 
		}
	
		$this->language->load('account/comment');
		$this->load->model('account/comment');
		$this->document->setTitle($this->language->get('heading_title'));

		$this->getList();
	}

	public function insert() {

		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/comment', '', 'SSL');
			$this->redirect($this->url->link('account/login', '', 'SSL')); 
		} 

		$this->language->load('account/comment');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('account/comment');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_account_comment->addComment($this->request->post);
			$this->session->data['success'] = $this->language->get('text_insert');
			$this->redirect($this->url->link('account/comment', '', 'SSL'));
		}

		$this->getForm();
	}

	public function update() {

		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/comment', '', 'SSL');
			$this->redirect($this->url->link('account/login', '', 'SSL')); 
		} 
		
		$this->language->load('account/comment');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('account/comment');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm()) && ($this->request->get['comment_id'])) {
			$this->model_account_comment->editComment($this->request->get['comment_id'], $this->request->post);
			$this->session->data['success'] = $this->language->get('text_update');
			$this->redirect($this->url->link('account/comment', '', 'SSL'));
		} 
	  	
		$this->getForm();
  	}


  	public function delete() {

		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/comment', '', 'SSL');
			$this->redirect($this->url->link('account/login', '', 'SSL')); 
		} 
			
		$this->language->load('account/comment');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('account/comment');
		
		if ($this->request->get['comment_id']) {
			$this->model_account_comment->deleteComment($this->request->get['comment_id']);	
			$this->session->data['success'] = $this->language->get('text_delete');
			$this->redirect($this->url->link('account/comment', '', 'SSL'));
		} else {
			$this->error['warning'] = $this->language->get('error_delete');
		}

		$this->getList();
  	}
  	
	protected function getForm() {

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),       	
			'separator' => false
		); 

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/comment', '', 'SSL'),
			'separator' => $this->language->get('text_separator')
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('account/comment', '', 'SSL'),        	
			'separator' => $this->language->get('text_separator')
		);
		
		if (!isset($this->request->get['comment_id'])) {
			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_add_comment'),
				'href'      => $this->url->link('account/comment/insert', '', 'SSL'),       		
				'separator' => $this->language->get('text_separator')
			);
		} else {
			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_edit_comment'),
				'href'      => $this->url->link('account/comment/update', 'comment_id=' . $this->request->get['comment_id'], 'SSL'),       		
				'separator' => $this->language->get('text_separator')
			);
		}
						
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_edit_comment'] = $this->language->get('text_edit_comment');
		$this->data['text_comment_list'] = $this->language->get('text_comment_list');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');

		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_comment'] = $this->language->get('entry_comment');

		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_back'] = $this->language->get('button_back');
		
		if (!isset($this->request->get['comment_id'])) {
			$this->data['action'] = $this->url->link('account/comment/insert', '', 'SSL');
		} else {
			$this->data['action'] = $this->url->link('account/comment/update', 'comment_id=' . $this->request->get['comment_id'], 'SSL');
		}
		
		if (isset($this->request->get['comment_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$comment_info = $this->model_account_comment->getComment($this->request->get['comment_id']);
		}

		
		if (isset($this->request->post['title'])) {
			$this->data['title'] = $this->request->post['title'];
		} elseif (!empty($comment_info)) {
			$this->data['title'] = $comment_info['title'];
		} else {
			$this->data['title'] = '';
		}

		if (isset($this->request->post['content'])) {
			$this->data['content'] = $this->request->post['content'];
		} elseif (!empty($comment_info)) {
			$this->data['content'] = $comment_info['content'];
		} else {
			$this->data['content'] = '';
		}
		
		if (isset($this->request->post['default'])) {
			$this->data['default'] = $this->request->post['default'];
		} else {
			$this->data['default'] = false;
		}

		if (isset($this->error['title'])) {
			$this->data['error_title'] = $this->error['title'];
		} else {
			$this->data['error_title'] = '';
		}

		if (isset($this->error['content'])) {
			$this->data['error_content'] = $this->error['content'];
		} else {
			$this->data['error_content'] = '';
		}

		$this->data['back'] = $this->url->link('account/comment', '', 'SSL');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/comment_form.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/comment_form.tpl';
		} else {
			$this->template = 'default/template/account/comment_form.tpl';
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

  	protected function getList() {

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

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('account/comment', '', 'SSL'),
			'separator' => $this->language->get('text_separator')
		);

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_comment_list'] = $this->language->get('text_comment_list');

		$this->data['button_new_comment'] = $this->language->get('button_new_comment');
		$this->data['button_edit'] = $this->language->get('button_edit');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_back'] = $this->language->get('button_back');

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
		
		$this->data['comments'] = array();

		$results = $this->model_account_comment->getComments();
		
		foreach ($results as $result) {
			$this->data['comments'][] = array(
				'comment_id' => $result['comment_id'],
				'content'    => $result['content'],
				'title'    	 => $result['title'],
				'update'     => $this->url->link('account/comment/update', 'comment_id=' . $result['comment_id'], 'SSL'),
				'delete'     => $this->url->link('account/comment/delete', 'comment_id=' . $result['comment_id'], 'SSL')
			);
		}
		
		$this->data['insert'] = $this->url->link('account/comment/insert', '', 'SSL');
		$this->data['back'] = $this->url->link('account/account', '', 'SSL');
				
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/comment_list.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/comment_list.tpl';
		} else {
			$this->template = 'default/template/account/comment_list.tpl';
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

  	public function getComment() {

		$json = array();

		if (isset($this->request->get['comment_id'])) {
			$comment_id = $this->request->get['comment_id'];
		} else {
			$comment_id = 0;
		}

		$this->load->model('account/comment');

		$comment = $this->model_account_comment->getComment($comment_id);

		if ($comment) {
			$json['success'] = $comment;            
		} else {
			$json['error'] = $this->language->get('error_loading');
		}
		
		$this->response->setOutput(json_encode($json));
    }

	protected function validateForm() {

		if (!isset($this->request->post['title']) || trim($this->request->post['title']) == '') {
			$this->error['title'] = $this->language->get('error_title');
		}
		if (!isset($this->request->post['content']) || trim($this->request->post['content']) == '') {
			$this->error['content'] = $this->language->get('error_content');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}