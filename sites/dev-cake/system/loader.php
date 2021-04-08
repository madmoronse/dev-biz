<?php
final class Loader {
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
	public function model($model)
   	{
   		$file = 'models/model_'.$model.'.php';

   		if (file_exists($file)) {
	    	require_once('models/model_'.$model.'.php');

		    $class_name = ucfirst('model_') . ucfirst($model);

		    $this->registry->set('model_' . $model, new $class_name($this->registry));
	    } else {
			trigger_error('Error: Could not load model ' . $model . '!');
			exit();
		}
	}
}