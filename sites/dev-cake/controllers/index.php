<?php
Class Controller_Index Extends Controller
{
	public function index()
	{

		$this->data['title'] = 'Главная страница';
		
	 	$this->load->model('products');
		$this->data['categories'] = $this->model_products->getCategories();

		$this->children = array (
			'header',
			'footer'
		);

		$this->render('common/home', $this->data);	
	}
}