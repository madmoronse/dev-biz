<?php
class Cart
{
	private $time;
	private $data = [];
	private $name = 'CART';


	public function __construct()
	{
		$this->time = 30 * 86400;
		$this->decode();
	}

	protected function decode()
	{
		$cdata = filter_input(INPUT_COOKIE, $this->name);
		$cdata = json_decode($cdata);

		if (is_array($cdata)) {
			$this->data = $cdata;
		} else {
			$this->data = [];
		}
	}

	public function add($id)
	{
		if (!in_array($id, $this->data)) {
			$this->data[] = (int) $id;
			return true;
		}

		return false;
	}

	public function delete($id)
	{
		if (array_search($id, $this->data) !== false) {
			unset($this->data[array_search($id, $this->data)]);
			$this->data = array_values($this->data);
			return true;
		}

		return false;
	}

	public function save()
	{
		setcookie($this->name, json_encode($this->data), time() + $this->time, '/');
	}

	public function get()
	{
		return $this->data;
	}

	public function clear()
	{
		unset($_COOKIE[$this->name]);
		setcookie($this->name, null, time() - 86400, '/');
	}

}