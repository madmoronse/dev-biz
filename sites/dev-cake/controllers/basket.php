<?php 

Class Controller_Basket Extends Controller
{
	public $totalCart;
	public function getCart()
	{

		$json = array();
		$products_id = $this->cart->get();

		$this->load->model('products');

		foreach ($products_id as $value) {
			$product = $this->model_products->getProduct($value);
			foreach ($product as $result) {
				$json['success'][] = array(
				'id' 	=> $result['id'],
				'title' => $result['title'],
				'price' => $result['price'],
				'image' => $result['image'],
				);
			}
		}

		$json['totalCart'] = $this->getSumCart();

		if (!empty($json)) {
			$json['totalSum'] = $this->getSumCart();
			echo json_encode($json, JSON_UNESCAPED_UNICODE);
		} else {
			$json['error'] = "В корзине нет товаров";
			echo json_encode($json, JSON_UNESCAPED_UNICODE);
		}
	}

	public function addToCart()
	{
		$json = array();

		$this->data['basket'] = $this->cart->get();

		if ($this->cart->add($this->request->post['id'])) {
			$this->cart->save();
			$json['total'] = count($this->cart->get());
		} else {
			$json['error'] = "Товар уже в корзине!";
		}

		header('Content-type: application/json');
		echo json_encode($json);
	}

	public function deleteToCart()
	{
		$json = array();

		if ($this->cart->delete($this->request->post['id'])) {
			$this->cart->save();
			$json['total'] = count($this->cart->get());
			$json['totalCart'] = $this->getSumCart();
		} else {
			$json['error'] = 'Нет в корзине';
		}
		if (count($this->cart->get()) == 0) {
			$json['empty'] = 'В корзине нет товаров';
		}

		header('Content-type: application/json');
		echo json_encode($json, JSON_UNESCAPED_UNICODE);
	}

	protected function getSumCart()
	{
		$products_id = $this->cart->get();
		$this->load->model('products');
		$this->totalCart = 0;
		foreach ($products_id as $value) {
			$product = $this->model_products->getProduct($value);
			foreach ($product as $result) {
				$this->totalCart += $result['price'];
			}
		}

		return $this->totalCart;
	}
}