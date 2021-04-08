<?php
class Controller
{
	public $model;
	public $view;
	protected $data = array();
	protected $children = array();

	protected $registry;

	public function __construct($registry) {
		$this->registry = $registry;
	}

	public function __get($key) {
		return $this->registry->get($key);
	}

	public function __set($key, $value) {
		$this->registry->set($key, $value);
	}


	public function render($view, $data = [])
	{
		foreach ($this->children as $child) {
			ob_start();
			$this->getChild($child);
			$data[$child] = ob_get_contents();
			ob_end_clean();
		}

		extract($data);

		include SITE_PATH . 'views/' . $view . '.php';
	}

	private function getChild($child)
	{
		$path = SITE_PATH . DIR_CONTROLLER . $child . '.php';
		$class = 'Controller_' . ucfirst($child);


		if (file_exists($path)) {
			require_once $path;
		}


		$controller = new $class($this->registry);

		return $controller->index($this->data);
	}
}
