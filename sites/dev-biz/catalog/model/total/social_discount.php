<?php
class ModelTotalSocialDiscount extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		$this->load->language('total/social_discount');
		$this->load->model('catalog/social_discount');
		
		$discount = $this->model_catalog_social_discount->getDiscount( $this->cart->getProducts());
		
		if ($discount > 0) {
			$total_data[] = array( 
				'code'       => 'social_discount',
				'title'      => $this->language->get('text_social_discount'),
				'text'       => $this->currency->format($discount),
				'value'      => (-$discount),
				'sort_order' => $this->config->get('social_discount_sort_order')
			);
			
			$total -= $discount;
		}
	}
}
?>