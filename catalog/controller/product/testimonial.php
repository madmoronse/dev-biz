<?php
class ControllerProducttestimonial extends Controller {
	
	public function index() {  
    	$this->language->load('product/testimonial');
		
		$this->load->model('catalog/testimonial');

		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', '', 'SSL'),
      		'separator' => false
   		);

		
		$testimonial_total = $this->model_catalog_testimonial->getTotalTestimonials();
			
		//if ($testimonial_total) {

	  		$this->document->SetTitle ($this->language->get('heading_title'));
			$this->document->addScript('catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js');
			$this->document->addStyle('catalog/view/javascript/jquery/colorbox/colorbox.css');

	   		$this->data['breadcrumbs'][] = array(
	       		'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('product/testimonial', '', 'SSL'),
	      		'separator' => $this->language->get('text_separator')
	   		);

						
      		$this->data['heading_title'] = $this->language->get('heading_title');
      		$this->data['text_auteur'] = $this->language->get('text_auteur');
      		$this->data['text_city'] = $this->language->get('text_city');
      		$this->data['button_continue'] = $this->language->get('button_continue');
      		$this->data['showall'] = $this->language->get('text_showall');
      		$this->data['write'] = $this->language->get('text_write');
      		$this->data['text_average'] = $this->language->get('text_average');
      		$this->data['text_stars'] = $this->language->get('text_stars');
      		$this->data['text_no_rating'] = $this->language->get('text_no_rating');
			
			$this->data['continue'] = $this->url->link('common/home', '', 'SSL');

			$this->page_limit = $this->config->get('testimonial_all_page_limit');
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			} else { 
				$page = 1;
			}	

			$this->data['testimonials'] = array();
			
			if ( isset($this->request->get['testimonial_id']) ){
				$results = $this->model_catalog_testimonial->getTestimonial($this->request->get['testimonial_id']);
			}
			else{
				$results = $this->model_catalog_testimonial->getTestimonials(($page - 1) * $this->page_limit, $this->page_limit);
			}
			
			foreach ($results as $result) {
				
				$this->data['testimonials'][] = array(
					'name'		=> $result['name'],
					'title'    		=> $result['title'],
					'rating'		=> $result['rating'],
					'description'	=> $result['description'],
					'city'		=> $result['city'],
					'image1'		=> $result['image1'],
					'image2'		=> $result['image2'],
					'image3'		=> $result['image3'],
					'date_added'	=> date("d-m-Y", strtotime($result['date_added'])) //$result['date_added']



				);
			}
			
			$url = '';
	
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
				$this->data['write_url'] = $this->url->link('product/isitestimonial', '', 'SSL'); 	
			
			if ( isset($this->request->get['testimonial_id']) ){
				$this->data['showall_url'] = $this->url->link('product/testimonial', '', 'SSL'); 	
			}
			else{
				$pagination = new Pagination();
				$pagination->total = $testimonial_total;
				$pagination->page = $page;
				$pagination->limit = $this->page_limit; 
				$pagination->text = $this->language->get('text_pagination');
				$pagination->url = $this->url->link('product/testimonial', '&page={page}', 'SSL');
				$this->data['pagination'] = $pagination->render();				

			}


			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/testimonial.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/product/testimonial.tpl';
			} else {
				$this->template = 'default/template/product/testimonial.tpl';
			}
			
			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);		
			
	  		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
/*
    	} else {

				
	  		$this->document->SetTitle ( $this->language->get('text_error'));
			
      		$this->data['heading_title'] = $this->language->get('text_error');

      		$this->data['text_error'] = $this->language->get('text_error');

      		$this->data['button_continue'] = $this->language->get('button_continue');

	    		$this->data['continue'] = $this->url->link('common/home', '', 'SSL');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
			} else {
				$this->template = 'default/template/error/not_found.tpl';
			}
			
			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);
		
	  		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
    	}
*/
  	}
	
	public function add_image() {
		$size_img = getimagesize($_FILES['uploadfile']['tmp_name']); 
		$dest_img = imagecreatetruecolor($size_img[0], $size_img[1]); 
		 
		
		if ($size_img[2]==2) $src_img = imagecreatefromjpeg($_FILES['uploadfile']['tmp_name']); 
		else if ($size_img[2]==1) $src_img = imagecreatefromgif($_FILES['uploadfile']['tmp_name']); 
		else if ($size_img[2]==3) $src_img = imagecreatefrompng($_FILES['uploadfile']['tmp_name']); 
		
		imagecopyresampled($dest_img, $src_img, 0, 0, 0, 0, $size_img[0], $size_img[1], $size_img[0], $size_img[1]); 
		
			$uploaddir = './image/testimonials/'; 
			$tmp_name = explode(".", $_FILES['uploadfile']['name']);

		$extension = end($tmp_name);
		
		$file_name = uniqid() . "-" .date("m.d.y-H:i:s") . "." .  $extension;
				
		$file = $uploaddir . basename($file_name);
		
				
		
			if ($size_img[2]==2) imagejpeg($dest_img, $file); 
			else if ($size_img[2]==1) imagegif($dest_img, $file); 
			else if ($size_img[2]==3) imagepng($dest_img, $file); 
			imagedestroy($dest_img); 
			imagedestroy($src_img);		
		
		if (file_exists($file)){
			echo $file_name;	
	
		} else {
			echo "error";
		}
	}
	
	public function delete_image() {	
		unlink($_POST["src_image"]);		
	}
}
?>