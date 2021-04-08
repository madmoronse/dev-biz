<?php
Class Controller_Order Extends Controller
{
	public function newOrder()
	{
		$json = array();

		$data = array(
			'email' => $this->request->post['email'],
			'name' 	=> $this->request->post['name'],
			'phone' => $this->request->post['phone']
		);

		$this->load->model('customers');

		if ($this->model_customers->add($data)) {
			$json['success'] = 'Спасибо! <br> Ваш заказ оформлен. <br> Мы свяжемся с Вами в ближайшее время!';
			$this->cart->clear();
			$json['total'] = 0;
		} else {
			$json['error'] = 'Что-то пошло не так... :(';
 		}

 		echo json_encode($json, JSON_UNESCAPED_UNICODE);
	}
}


//$this->telegram->send($data)