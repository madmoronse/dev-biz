<?php
Class Controller_Footer Extends Controller
{
	public function index($data_child)
	{
		$this->render('common/footer', $data_child);
	}
}