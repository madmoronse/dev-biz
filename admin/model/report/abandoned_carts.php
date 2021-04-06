<?php
class ModelReportAbandonedCarts extends Model {
	public function getCustomersCarts($data = array()) {
		$sql = "SELECT c.customer_id, c.cart, c.customer_group_id, cgd.name as customer_group_name, co.url, co.date_added FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (cgd.customer_group_id = c.customer_group_id) LEFT JOIN " . DB_PREFIX . "customer_online co ON (co.customer_id = c.customer_id)";

		$implode = array();


		if (isset($data['filter_customer']) && !is_null($data['filter_customer'])) {
			$implode[] = "co.customer_id > 0 AND CONCAT(c.lastname, ' ', c.firstname, ' ', c.middlename) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

        if ( !is_null($data['filter_customer_group_id']) ){
            $sql .= " WHERE c.cart <> 'a:0:{}' AND c.customer_group_id = '" . $data['filter_customer_group_id'] . "' ";        
        } else {
            $sql .= " WHERE c.cart <> 'a:0:{}' AND c.customer_group_id = '4' ";
        }

		if ($implode) {
			$sql .= "AND " . implode(" AND ", $implode);
		}

		$sql .= " ORDER BY co.date_added DESC";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
			
		$query = $this->db->query($sql);
	
		return $query->rows;
	}

    public function getTotalCustomersCarts($data = array()) {
        $sql = "SELECT COUNT(c.customer_id) AS total FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_online co ON (co.customer_id = c.customer_id)";

        $implode = array();

        if (isset($data['filter_customer']) && !is_null($data['filter_customer'])) {
            $implode[] = "co.customer_id > 0 AND CONCAT(c.lastname, ' ', c.firstname, ' ', c.middlename) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        $sql .= " WHERE c.cart <> 'a:0:{}' AND c.customer_group_id = '4' ";
        if ($implode) {
            $sql .= "AND " . implode(" AND ", $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }


    public function getCartProducts($customer_cart){
        $this->load->model('tool/image');
        $this->load->model('catalog/product');
        $cart_products = array();

        if ($customer_cart && is_string($customer_cart)) {
            $cart = unserialize($customer_cart);

            foreach ($cart as $key => $value) {
                $product = explode(':', $key);
                $product_id = $product[0];

                // Options
                if (isset($product[1])) {
                    $options = unserialize(base64_decode($product[1]));
                } else {
                    $options = array();
                }

                $product_info = $this->model_catalog_product->getProduct($product_id);

                if ($product_info){
                    if ($product_info['image']) {
                        $image = $this->model_tool_image->resize($product_info['image'], 30,30);
                    } else {
                        $image = $this->model_tool_image->resize('no_image.jpg', 30,30);
                    }

                    $option_data = array();

                    if ($options) {
                        foreach ($options as $product_option_id => $option_value) {
                            $option_query = $this->db->query("SELECT po.product_option_id, po.option_id, od.name, o.type FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_option_id = '" . (int)$product_option_id . "' AND po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

                            if ($option_query->num_rows) {
                                if ($option_query->row['type'] == 'select' || $option_query->row['type'] == 'radio' || $option_query->row['type'] == 'image') {
                                    $option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$option_value . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

                                    if ($option_value_query->num_rows) {

                                        $option_data[] = array(
                                            'name'                    => $option_query->row['name'],
                                            'option_value'            => $option_value_query->row['name']
                                        );
                                    }
                                } elseif ($option_query->row['type'] == 'checkbox' && is_array($option_value)) {
                                    foreach ($option_value as $product_option_value_id) {
                                        $option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$product_option_value_id . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

                                        if ($option_value_query->num_rows) {

                                            $option_data[] = array(
                                                'name'                    => $option_query->row['name'],
                                                'option_value'            => $option_value_query->row['name']
                                            );
                                        }
                                    }
                                } elseif ($option_query->row['type'] == 'text' || $option_query->row['type'] == 'textarea' || $option_query->row['type'] == 'file' || $option_query->row['type'] == 'date' || $option_query->row['type'] == 'datetime' || $option_query->row['type'] == 'time') {
                                    $option_data[] = array(
                                        'name'                    => $option_query->row['name'],
                                        'option_value'            => $option_value
                                    );
                                }
                            }
                        }
                    }

                    $cart_products[] = array(
                        'name'     => $product_info['name'],
                        'image'    => $image,
                        'quantity' => $value,
                        'options'  => $option_data,
                        'href'  => $this->url->link('product/product', 'product_id=' . $product_info['product_id'], 'SSL')
                    );
                }
            }
        }

        return $cart_products;
    }
}
?>