<?php
class ModelAccountAddress extends Model {
	public function addAddress($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" 
						. (int)$this->customer->getId() . "', firstname = '" 
						. $this->db->escape($data['firstname']) . "', middlename = '" 
						. $this->db->escape($data['middlename']) . "', lastname = '" 
						. $this->db->escape($data['lastname']) . "', company = '" 
						. $this->db->escape($data['company']) . "', company_id = '" 
						. $this->db->escape(isset($data['company_id']) ? $data['company_id'] : '') 
						. "', tax_id = '" . $this->db->escape(isset($data['tax_id']) ? $data['tax_id'] : '') 
						. "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" 
						. $this->db->escape($data['address_2']) . "', address_3 = '" 
						. $this->db->escape($data['address_3']) . "', address_4 = '" 
						. $this->db->escape($data['address_4']) . "', telephone = '" 
						. $this->db->escape($data['telephone']) . "', social = '" 
						. $this->db->escape($data['social']) . "', naselenniy_punkt_id = '" 
						. $this->db->escape($data['naselenniy_punkt_id']) . "', postcode = '" 
						. $this->db->escape($data['postcode']) . "', city = '" 
						. $this->db->escape($data['city']) . "', zone_id = '" 
						. (int)$data['zone_id'] . "', country_id = '" 
						. (int)$data['country_id'] . "', delivery_cities_id = " 
						. (int)$data['delivery_cities_id']);

		$address_id = $this->db->getLastId();

		if (!empty($data['default'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
		}

		return $address_id;
	}

	public function editAddress($address_id, $data) {


		$this->db->query("UPDATE " . DB_PREFIX . "address SET firstname = '" 
						 . $this->db->escape($data['firstname']) . "', middlename = '" 
						 . $this->db->escape($data['middlename']) . "', lastname = '" 
						 . $this->db->escape($data['lastname']) . "', company = '" 
						 . $this->db->escape($data['company']) . "', company_id = '" 
						 . $this->db->escape(isset($data['company_id']) ? $data['company_id'] : '') 
						 . "', tax_id = '" . $this->db->escape(isset($data['tax_id']) ? $data['tax_id'] : '') 
						 . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" 
						 . $this->db->escape($data['address_2']) . "', address_3 = '" 
						 . $this->db->escape($data['address_3']) . "', address_4 = '" 
						 . $this->db->escape($data['address_4']) . "', telephone = '" 
						 . $this->db->escape($data['telephone']) . "', social = '" 
						 . $this->db->escape($data['social']) . "', naselenniy_punkt_id = '" 
						 . (int)$data['naselenniy_punkt_id'] . "', postcode = '" 
						 . $this->db->escape($data['postcode']) . "', city = '" . $this->db->escape($data['city']) 
						 . "', zone_id = '" . (int)$data['zone_id'] . "', country_id = '" 
						 . (int)$data['country_id'] 
						 . "', delivery_cities_id = " 
						 . (int)$data['delivery_cities_id']
						 . " WHERE address_id  = '" 
						 . (int)$address_id . "' AND customer_id = '" 
						 . (int)$this->customer->getId() . "'");

		if (!empty($data['default'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
		}
	}

	public function deleteAddress($address_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE address_id = '" . (int)$address_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");
	}

	public function getAddress($address_id) {
		$address_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "address WHERE address_id = '" . (int)$address_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");

		if ($address_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$address_query->row['country_id'] . "'");
			$naselenniy_punkt_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "naselenniy_punkt` WHERE naselenniy_punkt_id = '" . (int)$address_query->row['naselenniy_punkt_id'] . "'");

			if ($country_query->num_rows) {
				$country = $country_query->row['name'];
				$iso_code_2 = $country_query->row['iso_code_2'];
				$iso_code_3 = $country_query->row['iso_code_3'];
				$address_format = $country_query->row['address_format'];
			} else {
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';
				$address_format = '';
			}


			if ($naselenniy_punkt_query->num_rows) {
				$naselenniy_punkt = $naselenniy_punkt_query->row['code'];
			} else {
				$naselenniy_punkt = '';
			}



			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$address_query->row['zone_id'] . "'");

			if ($zone_query->num_rows) {
				$zone = $zone_query->row['name'];
				$zone_code = $zone_query->row['code'];
			} else {
				$zone = '';
				$zone_code = '';
			}

			$address_data = array(
				'firstname'      => $address_query->row['firstname'],
				'middlename'     => $address_query->row['middlename'],
				'lastname'       => $address_query->row['lastname'],
				'company'        => $address_query->row['company'],
				'company_id'     => $address_query->row['company_id'],
				'tax_id'         => $address_query->row['tax_id'],
				'address_1'      => $address_query->row['address_1'],
				'address_2'      => $address_query->row['address_2'],
				'address_3'      => $address_query->row['address_3'],
				'address_4'      => $address_query->row['address_4'],
				'telephone'      => $address_query->row['telephone'],
				'social'      => $address_query->row['social'],
				'naselenniy_punkt_id'      => $address_query->row['naselenniy_punkt_id'],
				'naselenniy_punkt'      => $naselenniy_punkt,
				'postcode'       => $address_query->row['postcode'],
				'city'           => $address_query->row['city'],
				'zone_id'        => $address_query->row['zone_id'],
				'zone'           => $zone,
				'zone_code'      => $zone_code,
				'country_id'     => $address_query->row['country_id'],
				'country'        => $country,
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format,
				'delivery_cities_id' => $address_query->row['delivery_cities_id']
			);

			return $address_data;
		} else {
			return false;
		}
	}

	public function getAddresses() {
		$address_data = array();


		$main_address = $this->db->query("SELECT address_id FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$this->customer->getId() . "'");

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$this->customer->getId() . "' ORDER BY lastname");

		foreach ($query->rows as $result) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$result['country_id'] . "'");
			$naselenniy_punkt_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "naselenniy_punkt` WHERE naselenniy_punkt_id = '" . (int)$result['naselenniy_punkt_id'] . "'");

			if ($country_query->num_rows) {
				$country = $country_query->row['name'];
				$naselenniy_punkt = $naselenniy_punkt_query->row['code'];
				$iso_code_2 = $country_query->row['iso_code_2'];
				$iso_code_3 = $country_query->row['iso_code_3'];
				$address_format = $country_query->row['address_format'];
			} else {
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';
				$address_format = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$result['zone_id'] . "'");

			if ($zone_query->num_rows) {
				$zone = $zone_query->row['name'];
				$zone_code = $zone_query->row['code'];
			} else {
				$zone = '';
				$zone_code = '';
			}

			$address_data[$result['address_id']] = array(
				'address_id'     => $result['address_id'],
				'firstname'      => $result['firstname'],
				'middlename'     => $result['middlename'],
				'lastname'       => $result['lastname'],
				'company'        => $result['company'],
				'company_id'     => $result['company_id'],
				'tax_id'         => $result['tax_id'],
				'address_1'      => $result['address_1'],
				'address_2'      => $result['address_2'],
				'address_3'      => $result['address_3'],
				'address_4'      => $result['address_4'],
				'naselenniy_punkt_id'      => $result['naselenniy_punkt_id'],
				'naselenniy_punkt'      => $naselenniy_punkt,
				'postcode'       => $result['postcode'],
				'city'           => $result['city'],
				'zone_id'        => $result['zone_id'],
				'zone'           => $zone,
				'zone_code'      => $zone_code,
				'country_id'     => $result['country_id'],
				'country'        => $country,
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format,
				'telephone'      => $result['telephone'],
                'social'         => $result['social'],
				'main_address_id'  => $main_address->row['address_id'],
				'delivery_cities_id' => $result['delivery_cities_id']

			);
		}

		return $address_data;
	}


	public function getPaymentAddresses() {
		$address_data = array();

		$querymainaddress = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$this->customer->getId() . "'" );

		foreach ($querymainaddress->rows as $resultmainaddress) {

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$this->customer->getId() . "' AND address_id = '" . (int)$resultmainaddress['address_id'] . "'");

		foreach ($query->rows as $result) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$result['country_id'] . "'");
			if ($result['naselenniy_punkt_id'] == '') {$result['naselenniy_punkt_id']='2';}
			$naselenniy_punkt_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "naselenniy_punkt` WHERE naselenniy_punkt_id = '" . (int)$result['naselenniy_punkt_id'] . "'");

			if ($country_query->num_rows) {
				$country = $country_query->row['name'];
				$naselenniy_punkt = $naselenniy_punkt_query->row['code'];
				$iso_code_2 = $country_query->row['iso_code_2'];
				$iso_code_3 = $country_query->row['iso_code_3'];
				$address_format = $country_query->row['address_format'];
			} else {
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';
				$address_format = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$result['zone_id'] . "'");

			if ($zone_query->num_rows) {
				$zone = $zone_query->row['name'];
				$zone_code = $zone_query->row['code'];
			} else {
				$zone = '';
				$zone_code = '';
			}

			$address_data[$result['address_id']] = array(
				'address_id'     => $result['address_id'],
				'firstname'      => $result['firstname'],
				'middlename'     => $result['middlename'],
				'lastname'       => $result['lastname'],
				'company'        => $result['company'],
        'email'           => $resultmainaddress['email'],
        'telephone'        => $result['telephone'],
				'company_id'     => $result['company_id'],
				'tax_id'         => $result['tax_id'],
				'address_1'      => $result['address_1'],
				'address_2'      => $result['address_2'],
				'address_3'      => $result['address_3'],
				'address_4'      => $result['address_4'],
				'naselenniy_punkt_id'      => $result['naselenniy_punkt_id'],
				'naselenniy_punkt'      => $naselenniy_punkt,
				'postcode'       => $result['postcode'],
				'city'           => $result['city'],
				'zone_id'        => $result['zone_id'],
				'zone'           => $zone,
				'zone_code'      => $zone_code,
				'country_id'     => $result['country_id'],
				'country'        => $country,
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format
			);
		}

		return $address_data;
	}
	}


	public function getTotalAddresses() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$this->customer->getId() . "'");

		return $query->row['total'];
	}
}
?>
