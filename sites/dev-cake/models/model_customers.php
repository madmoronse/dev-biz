<?php
Class Model_Customers Extends Model
{
	public function add($data)
	{
		$this->db->query("INSERT INTO  customers SET phone = '" . $data['phone'] .
                        "', name = '" . $data['name'] . "'");

		return true;
	}
}