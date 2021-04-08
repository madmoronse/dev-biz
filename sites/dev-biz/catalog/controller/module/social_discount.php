<?php  
class ControllerModuleSocialDiscount extends Controller {
	
	public function action() {
		$json = array();
		
		if (isset($this->request->post['product_id']) === false
			|| isset($this->request->post['social']) === false
			|| isset($this->request->post['action']) === false
		) {
			$json['error'] = true;
		}
		
		if (!in_array($this->request->post['social'], array('vk', 'fb', 'gp', 'mm', 'ok', 'tw'))) {
			$json['error'] = true;
		} else {
			$social = $this->request->post['social'];
		}
		
		if (!in_array($this->request->post['action'], array('like', 'unlike', 'share', 'unpublish'))) {
			$json['error'] = true;
		} else {
			$action = $this->request->post['action'];
		}
		
		if (!isset($json['error'])) {
			$this->load->model('catalog/social_discount');
			
			$product_id = (int)$this->request->post['product_id'];
			
			if ($this->model_catalog_social_discount->doAction($social, $product_id, $action)) {
				$json['success'] = true;
				
				// calculate and return new price with discount
				$this->load->model('catalog/product');
				$product = $this->model_catalog_product->getProduct($product_id);
				$this->model_catalog_social_discount->updateProductSpecial($product);
				
				$json['percent'] = sprintf("%.3f", $product['social_discount_percent'] * 100);
				if ($product['special']) {
					$json['discount_price'] = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$json['discount_price'] = '';
				}
				$json['social_discount'] = $product['social_discount']; // if discount is social or not
			} else {
				$json['error'] = false;
			}
		}
		
		$this->response->setOutput(json_encode($json));
	}
	
	/* todo:
	  1. � ������� ���������� �������� ������ ����� �� ������� (��������� � ����� span
	*/
	public function isliked() {
		$json = array();
		
		if (isset($this->request->post['product_id']) === false) {
			$json['error'] = true;
		} else {
			$this->load->model('catalog/social_discount');
			
			$product_id = (int)$this->request->post['product_id'];

			$json['result'] = $this->model_catalog_social_discount->isLiked($product_id);
		}
		
		$this->response->setOutput(json_encode($json));
	}
	
}
?>