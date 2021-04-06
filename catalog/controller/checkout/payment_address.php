<?php
class ControllerCheckoutPaymentAddress extends Controller {
	public function index() {
		$this->language->load('checkout/checkout');
		$this->data['text_address_existing'] = $this->language->get('text_address_existing');
		$this->data['text_address_new'] = $this->language->get('text_address_new');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');
    $this->data['text_fio'] = $this->language->get('text_fio');
    $this->data['text_email'] = $this->language->get('text_email');
    $this->data['text_telephone'] = $this->language->get('text_telephone');

		$this->data['entry_firstname'] = $this->language->get('entry_firstname');
		$this->data['entry_middlename'] = $this->language->get('entry_middlename');
		$this->data['entry_lastname'] = $this->language->get('entry_lastname');
    $this->data['entry_email'] = $this->language->get('entry_email');
    $this->data['entry_telephone'] = $this->language->get('entry_telephone');
		$this->data['entry_company'] = $this->language->get('entry_company');
		$this->data['entry_company_id'] = $this->language->get('entry_company_id');
		$this->data['entry_tax_id'] = $this->language->get('entry_tax_id');
		$this->data['entry_address_1'] = $this->language->get('entry_address_1');
		$this->data['entry_address_2'] = $this->language->get('entry_address_2');
		$this->data['entry_address_3'] = $this->language->get('entry_address_3');
		$this->data['entry_address_4'] = $this->language->get('entry_address_4');
		$this->data['entry_naselenniy_punkt'] = $this->language->get('entry_naselenniy_punkt');
		$this->data['entry_postcode'] = $this->language->get('entry_postcode');
		$this->data['entry_city'] = $this->language->get('entry_city');
		$this->data['entry_country'] = $this->language->get('entry_country');
		$this->data['entry_zone'] = $this->language->get('entry_zone');
    $this->data['entry_area'] = $this->language->get('entry_area');
    $this->data['entry_comments'] = $this->language->get('text_comments');
    $this->data['entry_np'] = $this->language->get('entry_np');


		$this->data['button_continue'] = $this->language->get('button_continue');

		$this->data['init_geo_ip'] = false;
		if (!isset($this->session->data['payment_country_id']) && !isset($this->session->data['payment_zone_id'])) {
			$google_api_key = $this->config->get('config_google_api_key');
			if ($google_api_key) {
				$this->data['init_geo_ip'] = true;
				$this->data['google_api_key'] = $google_api_key;
			}
		}

		if (isset($this->session->data['payment_address_id'])) {
			$this->data['address_id'] = $this->session->data['payment_address_id'];
		} else {
			$this->data['address_id'] = $this->customer->getAddressId();
		}

		$this->data['addresses'] = array();

		$this->load->model('account/address');

    $total_data = array();
    $total = 0;
    $taxes = $this->cart->getTaxes();

    $this->load->model('setting/extension');

    $sort_order = array();

    $results = $this->model_setting_extension->getExtensions('total');

    foreach ($results as $key => $value) {
      $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
    }

    array_multisort($sort_order, SORT_ASC, $results);

    foreach ($results as $result) {
      if ($this->config->get($result['code'] . '_status')) {
        $this->load->model('total/' . $result['code']);

        $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
      }
    }

    $sort_order = array();

    foreach ($total_data as $key => $value) {
      $sort_order[$key] = $value['sort_order'];
    }

    array_multisort($sort_order, SORT_ASC, $total_data);

    $this->data['total'] = $total_data[0];


		//$this->data['addresses'] = $this->model_account_address->getAddresses();
    if($this->customer->isLogged()){
      $this->data['addresses'] = $this->model_account_address->getPaymentAddresses();
      // разрешенные ключи для адреса
      $address_keys = array_keys(reset($this->data['addresses']));
      // Ключ первого адреса
      $first_address = key($this->data['addresses']);

      foreach ($address_keys as $key) {
        if(isset($this->session->data[$key])){
          // Пропускаем city, потому что нам надо, чтобы он подставился из адореса в БД
          // иначе мы получим только название населенного пункта, а не полное название с областью и р-н
          if ($key == 'city') continue;
          $this->data['addresses'][$first_address][$key] = $this->session->data[$key];
        }
      }
      if($this->data['addresses'][$first_address]['firstname'] == ""){
        $this->data['addresses'][$first_address]['firstname'] = $this->customer->getFirstName();
      }
      if($this->data['addresses'][$first_address]['middlename'] == ""){
        $this->data['addresses'][$first_address]['middlename'] = $this->customer->getMiddleName();
      }
      if($this->data['addresses'][$first_address]['lastname'] == ""){
        $this->data['addresses'][$first_address]['lastname'] = $this->customer->getLastName();
      }
      if($this->data['addresses'][$first_address]['email'] == ""){
        $this->data['addresses'][$first_address]['email'] = $this->customer->getEmail();
      }
      if($this->data['addresses'][$first_address]['telephone'] == ""){
        $this->data['addresses'][$first_address]['telephone'] = $this->customer->getTelephone();
      }
    }else{
      $this->data['addresses'] = array(0 => $this->session->data);
    }
    $this->data['comment'] = $this->session->data['comment'];
    $this->load->model('tool/image');
    $this->load->model('catalog/product');
    foreach ($this->cart->getProducts() as $product) {

    $current_product_info = $this->model_catalog_product->getProduct($product['product_id']);

      if ($product['image']) {
        $image = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height'));
      } else {
        $image = '';
      }
      $option_data = array();

      foreach ($product['option'] as $option) {
        if ($option['type'] != 'file') {
          $value = $option['option_value'];
        } else {
          $filename = $this->encryption->decrypt($option['option_value']);

          $value = utf8_substr($filename, 0, utf8_strrpos($filename, '.'));
        }

        $option_data[] = array(
          'name'  => $option['name'],
          'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
        );
      }
      /** By Neos - Set price for gift - Start */
      $this->load->model('checkout/gifts');
      if ($this->model_checkout_gifts->inGifts($product['key'])) {
        $product['price'] = 0;
      }
      /** By Neos - Set price for gift - End */
      //BMV begin

      if (isset($this->session->data['price_drop'])){

        $this->data['products'][] = array(
            'product_id' => $product['product_id'],
            'name'       => $current_product_info['manufacturer'] . ' ' . $product['name'],

            'thumb'    	=> $image,
            'model'      => $product['model'],
            'option'     => $option_data,
            'quantity'   => $product['quantity'],
            'subtract'   => $product['subtract'],
            'price'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))),
            'total'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']),
            'href'       => $this->url->link('product/product', 'product_id=' . $product['product_id']),
            'price_drop' => $this->session->data['price_drop'][$pcount]
        );

        $pcount=$pcount+1;
      } else{

        $this->data['products'][] = array(
            'product_id' => $product['product_id'],
            'name'       => $current_product_info['manufacturer'] . ' ' . $product['name'],
            'thumb'    	=> $image,
            'model'      => $product['model'],
            'option'     => $option_data,
            'quantity'   => $product['quantity'],
            'subtract'   => $product['subtract'],
            'price'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))),
            'total'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']),
            'href'       => $this->url->link('product/product', 'product_id=' . $product['product_id'])
        );

      }
      //BMV end

    }


		$this->load->model('account/customer_group');

		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($this->customer->getCustomerGroupId());

		if ($customer_group_info) {
			$this->data['company_id_display'] = $customer_group_info['company_id_display'];
		} else {
			$this->data['company_id_display'] = '';
		}

		if ($customer_group_info) {
			$this->data['company_id_required'] = $customer_group_info['company_id_required'];
		} else {
			$this->data['company_id_required'] = '';
		}

		if ($customer_group_info) {
			$this->data['tax_id_display'] = $customer_group_info['tax_id_display'];
		} else {
			$this->data['tax_id_display'] = '';
		}

		if ($customer_group_info) {
			$this->data['tax_id_required'] = $customer_group_info['tax_id_required'];
		} else {
			$this->data['tax_id_required'] = '';
		}

		if (isset($this->session->data['payment_country_id'])) {
			$this->data['country_id'] = $this->session->data['payment_country_id'];
		} else {
			$this->data['country_id'] = $this->config->get('config_country_id');
		}

		if (isset($this->session->data['payment_zone_id'])) {
			$this->data['zone_id'] = $this->session->data['payment_zone_id'];
		} else {
			$this->data['zone_id'] = '';
		}

		$this->load->model('localisation/country');

		$this->data['countries'] = $this->model_localisation_country->getCountries();

    $this->load->model('localisation/zone');
    foreach ($this->data['countries'] as $country) {
      if ($country['name'] == "Российская Федерация"){
        $this->data['zones'] = $this->model_localisation_zone->getZonesByCountryId($country['country_id']);
      }
    }

		$this->data['naselenniy_punkts'] = $this->model_localisation_country->getNaselenniyPunkts();

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/payment_address.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/payment_address.tpl';
		} else {
			$this->template = 'default/template/checkout/payment_address.tpl';
		}
    if($_REQUEST['step'] == 2){
    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/payment_address_2.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/payment_address_2.tpl';
		} else {
			$this->template = 'default/template/checkout/payment_address_2.tpl';
		}}

		$this->response->setOutput($this->render());
	}

  public function address()
  {
     $this->load->model('localisation/dostavka');

    // //подгружаем район, город
    // $json['success'] = 1;
    switch ($_REQUEST['thing']) {
      case 'city':
        $json = $this->model_localisation_dostavka->getCities($_REQUEST['q'], $_REQUEST['area'], $_REQUEST['zone']);
        break;
      case 'area':
        $json = $this->model_localisation_dostavka->getAreas($_REQUEST['q'], $_REQUEST['zone']);
        break;
      case 'zone':
        $json = $this->model_localisation_dostavka->getZones($_REQUEST['q']);
        break;
      case 'shippingCity':
        $json = $this->model_localisation_dostavka->getShippingCity($_REQUEST['q']);
        break;
      case 'np':
        $json = $this->model_localisation_dostavka->getNp($_REQUEST['q']);
        break;
      case 'np1':
        $json = $this->model_localisation_dostavka->getNp1($_REQUEST['q']);
        break;
      case 'np2':
        $json = $this->model_localisation_dostavka->getNp2($_REQUEST['q']);
        break;
      default:
        break;
    }
    $this->response->setOutput(json_encode($json));
  }

  public function addresscalc()
  {
    $this->load->model('localisation/dostavka');
    $html = $this->model_localisation_dostavka->getNpCalc($_REQUEST['q']);
    $this->response->setOutput($html);
  }

  public function testcheckout()
  {

    return;
    $json = array();

    $cities_q = $this->db->query("SELECT * FROM `delivery_cities` LIMIT 5000, 1000");
    $cities = $cities_q->rows;
    $data_result = array();
    foreach ($cities as $key => $city) {
      unset($dr);
      $dr = array();
      $np = $city['np_name'];
      $querycity = $this->db->query("SELECT * FROM `delivery_cities` WHERE `np_name` LIKE '%{$np}%' LIMIT 1");
      $queryregion = $this->db->query("SELECT * FROM `delivery_regions` WHERE `id` LIKE '{$querycity->row['region_id']}' LIMIT 1");
      $queryarea = $this->db->query("SELECT * FROM `delivery_area` WHERE `id` LIKE '{$querycity->row['area_id']}' AND `region_id` = '{$querycity->row['region_id']}' LIMIT 1");
      $region_data = $queryregion->row;
      $city_data = $querycity->row;
      $area_data = $queryarea->row;
      //print_r($querycity->num_rows);
      if($querycity->num_rows > 0){
          //$city_name =  str_replace('г. ','',$city_data['name']);
          $city_name =  explode(' ',$city_data['name']);
          $cname = "";
          foreach ($city_name as $key => $value) {
            if($key > 0){
              $cname .= " ".$value;
            }
          }
          $cname = trim($cname);
        //  print_r($cname);
          $fias_cities = $this->db->query("SELECT *,np.* FROM `fias_new` LEFT JOIN `oc_naselenniy_punkt` as `np` ON(`np`.`code` = `fias_new`.`shortname`) WHERE `offname` LIKE '%{$cname}%' OR '{$cname}' LIKE CONCAT('%', offname, '%')");
          $fias_trees = array();
          //print_r($fias_cities->row);
            if(count($fias_cities->rows) > 0){
              foreach ($fias_cities->rows as $fias) {
                $lvl = $fias['level'];
                $aoguid = $fias['parentguid'];
                $fiases = array();
                array_push($fiases, $fias);
                $k = 0;
                while(($lvl != 0)){
                  $k++;
                  $fias_q = $this->db->query("SELECT * FROM `fias_new` WHERE `aoguid` LIKE '{$aoguid}'");
                  $aoguid = $fias_q->row['parentguid'];
                  $lvl = $fias_q->row['level'];
                  array_push($fiases, $fias_q->row);
                }
                array_push($fias_trees, $fiases);
              }
            }
            // print_r($fias_trees);
            foreach ($fias_trees as $fiastr) {
              $result = (object) array();
              $result->region = false;
              $result->city = false;
              foreach ($fiastr as $fias) {
                if((strripos($region_data['name'],$fias['offname']) !== false)&&($fias['level'] == 1)){
                  $result->region = true;
                  $result->region_data = $fias;
                }
                if((strripos(str_replace('ё','е',$city_data['name']),str_replace('ё','е',$fias['offname'])) !== false)&&($fias['level'] >= 3)){
                  $result->city = true;
                  $result->city_data = $fias;
                }
                if(($result->region === true)&&($result->city === true)&&($fias['level'] == 0)){
                  $result->country_data = $fias;
                }
              }
              if(($result->region === true)&&($result->city === true)){
                $dr['dev_array'] = $result;
                $dr['dev_array']->found = true;
                break;
              }
            }
            //$dr['dev_array'] = $fias_trees;
            if(isset($dr['dev_array']->found)){
              $dr['payment_city'] = $dr['dev_array']->city_data['offname'];
              $dr['shipping_city'] = $dr['dev_array']->city_data['offname'];
              $c_f_id = $dr['dev_array']->country_data['fias_id'];
              $country_q = $this->db->query("SELECT * FROM `oc_country` as `c` INNER JOIN `country_to_fias` as `ctf` ON (`c`.`country_id` = `ctf`.`country_id`) WHERE `ctf`.`fias_id` = '{$c_f_id}'");
              $dr['payment_country'] = $country_q->row['name'];
              $dr['payment_country_id'] = $country_q->row['country_id'];
              $dr['shipping_country'] = $country_q->row['name'];
              $dr['shipping_country_id'] = $country_q->row['country_id'];
              $z_f_id = $dr['dev_array']->region_data['fias_id'];
              $zone_q = $this->db->query("SELECT * FROM `oc_zone` as `z` INNER JOIN `zone_to_fias` as `ztf` ON (`z`.`zone_id` = `ztf`.`zone_id`) WHERE `ztf`.`fias_id` = '{$z_f_id}'");
              $dr['payment_zone'] = $zone_q->row['name'];
              $dr['payment_zone_id'] = $zone_q->row['zone_id'];
              $dr['shipping_zone'] = $zone_q->row['name'];
              $dr['shipping_zone_id'] = $zone_q->row['zone_id'];
              $dr['shipping_naselenniy_punkt'] = ($dr['dev_array']->city_data['code']) ? $dr['dev_array']->city_data['code'] : $dr['dev_array']->city_data['shortname'];
              $dr['payment_naselenniy_punkt'] = ($dr['dev_array']->city_data['code']) ? $dr['dev_array']->city_data['code'] : $dr['dev_array']->city_data['shortname'];
              $dr['shipping_naselenniy_punkt_id'] = ($dr['dev_array']->city_data['naselenniy_punkt_id']) ? $dr['dev_array']->city_data['naselenniy_punkt_id'] : 0;
              $dr['payment_naselenniy_punkt_id'] = ($dr['dev_array']->city_data['naselenniy_punkt_id']) ? $dr['dev_array']->city_data['naselenniy_punkt_id'] : 0;

            }
          // $dr['city'] = $c['name'];
          // $zone_id = $c['region_id'];
          // $area_id = ($c['area_id'] !== null) ? $c['area_id'] : 0;
          // $queryzone = $this->db->query("SELECT * FROM `delivery_regions` WHERE `id` = '{$zone_id}' LIMIT 1");
          // $zone_data = $queryzone->rows;
          // foreach ($zone_data as $key => $value) {
          //   $dr['zone'] = $value['name'];
          // }
          // if ($area_id) {
          //   $queryarea = $this->db->query("SELECT * FROM `delivery_area` WHERE `id` = '{$area_id}' LIMIT 1");
          //   $area_data = $queryarea->rows;
          //   foreach ($area_data as $key => $value) {
          //     $dr['area'] = $value['name'];
          //   }
          // }
      }
      if(!isset($dr['dev_array']->found)){
        $json[] = array('city_not_found' => $this->language->get('city_not_found'), 'np' => $np, $city['id'], 'city' => $city['name'], 'zone_id' => $city['area_id'], 'region_id' => $city['region_id']);
      }
    }
    $resarr = array();
    foreach ($json as $key => $res) {
      $resarr[$res['region_id']][$res['zone_id']][] = array('np' => $res['np']);
    }
    foreach ($resarr as $reg => $value1) {
      $queryreg = $this->db->query("SELECT * FROM `delivery_regions` WHERE `id` = '{$reg}' LIMIT 1");
      echo "<br>".$queryreg->row['name']."<br>";
      foreach ($value1 as $zone => $value2) {
        $queryzone = $this->db->query("SELECT * FROM `delivery_area` WHERE `id` = '{$zone}' LIMIT 1");
        echo "<br>".$queryzone->row['name']."<br>";
        foreach ($value2 as $key => $value3) {
          print_r($value3);
        }
      }
    }
    //print_r($resarr);
    $this->response->setOutput(json_encode($jsons));
  }



	public function validate() {
		$this->language->load('checkout/checkout');

		$json = array();

		// Validate if customer is logged in.
		// if (!$this->customer->isLogged()) {
		// 	$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
		// }

		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');
		}

		// Validate minimum quantity requirments.
		$products = $this->cart->getProducts();

		foreach ($products as $product) {
			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}

			if ($product['minimum'] > $product_total) {
				$json['redirect'] = $this->url->link('checkout/cart');

				break;
			}
		}

		if (!$json) {
			if (isset($this->request->post['payment_address']) && $this->request->post['payment_address'] == 'existing') {
				$this->load->model('account/address');

				if (empty($this->request->post['address_id'])) {
					$json['error']['warning'] = $this->language->get('error_address');
				} elseif (!in_array($this->request->post['address_id'], array_keys($this->model_account_address->getAddresses()))) {
					$json['error']['warning'] = $this->language->get('error_address');
				} else {
					// Default Payment Address
					$this->load->model('account/address');

					$address_info = $this->model_account_address->getAddress($this->request->post['address_id']);

					if ($address_info) {
						$this->load->model('account/customer_group');

						$customer_group_info = $this->model_account_customer_group->getCustomerGroup($this->customer->getCustomerGroupId());

						// Company ID
						if ($customer_group_info['company_id_display'] && $customer_group_info['company_id_required'] && !$address_info['company_id']) {
							$json['error']['warning'] = $this->language->get('error_company_id');
						}

						// Tax ID
						if ($customer_group_info['tax_id_display'] && $customer_group_info['tax_id_required'] && !$address_info['tax_id']) {
							$json['error']['warning'] = $this->language->get('error_tax_id');
						}
					}
				}
				if (!$json) {
					$this->session->data['payment_address_id'] = $this->request->post['address_id'];

					if ($address_info) {
						// $this->session->data['payment_country_id'] = $address_info['country_id'];
						// $this->session->data['payment_zone_id'] = $address_info['zone_id'];
					} else {
						// unset($this->session->data['payment_country_id']);
						// unset($this->session->data['payment_zone_id']);
					}

					unset($this->session->data['payment_method']);
					unset($this->session->data['payment_methods']);
				}
			} else {
			    if($_REQUEST['step'] == 2){
            $err = false;
            $this->request->post['firstname'] = trim($this->request->post['firstname']);
            $this->request->post['firstname'] =  preg_replace('/ {2,}/',' ',$this->request->post['firstname']);
            if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 80)) {
    					$json['error']['firstname'] = $this->language->get('error_firstname');
              $err = true;
    				}else{
              $fio = explode(" ", $this->request->post['firstname']);
              if(count($fio) == 1){
                $this->session->data['firstname'] = $fio[0];
                unset($this->session->data['middlename']);
                unset($this->session->data['lastname']);
              }elseif(count($fio) == 2){
                $this->session->data['firstname'] = $fio[0];
                $this->session->data['lastname'] = $fio[1];
                unset($this->session->data['middlename']);
              }elseif(count($fio) >= 3){
                $this->session->data['lastname'] = $fio[0];
                $this->session->data['firstname'] = $fio[1];
                $this->session->data['middlename'] = $fio[2];
              }else{
                $this->session->data['firstname'] = $fio[0];
                unset($this->session->data['middlename']);
                unset($this->session->data['lastname']);
              }
              $this->session->data['fullname'] = "";
              foreach ($fio as $key => $value) {
                $this->session->data['fullname'] .= $value." ";
              }
              if((utf8_strlen($this->session->data['firstname']) < 1) || (utf8_strlen($this->session->data['firstname']) > 32)){
                $json['error']['firstname'] = $this->language->get('error_firstname');
                $err = true;
              }
            }
            if ((utf8_strlen($this->request->post['telephone']) < 1) || (utf8_strlen($this->request->post['telephone']) > 32)) {
    					$json['error']['telephone'] = $this->language->get('error_telephone');
              $err = true;
    				}else{
              $this->session->data['telephone'] = $this->request->post['telephone'];
            }
            if ((utf8_strlen($this->request->post['email']) < 1) || 
                (utf8_strlen($this->request->post['email']) > 96) || 
                !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
    					$json['error']['email'] = $this->language->get('error_email');
              $err = true;
    				}else{
              $this->session->data['email'] = $this->request->post['email'];
            }
            if(!$err){

              if(!$this->customer->isLogged()){
                $password = substr(sha1(uniqid(mt_rand(), true)), 0, 10);
                $email = $this->session->data['email'];
                $this->load->model('account/customer');
                if($this->model_account_customer->getCustomerByEmail($email)){
                  $json['error']['yrregistered'] = "Для продолжения - введите свой пароль";
                  $json['email'] = $email;
                }else{
                  $data = array('email' => $email, 'password' => $password, 'firstname' => $this->session->data['firstname'], 'middlename' => $this->session->data['middlename'], 'lastname' => $this->session->data['lastname'], 'telephone' => $this->request->post['telephone']);
                  $this->model_account_customer->addCustomer($data);
                  $this->customer->login($email, $password);
                  unset($this->session->data['guest']);

                  $subject = <<<HTML
                  Добро пожаловать в на OUTMAXSHOP.RU!
HTML;
                  $message = <<<HTML
                  Здравствуйте, уважаемый клиент!
                  Вы совершили свой первый заказ на OUTMAXSHOP.RU
                  Теперь у вас есть свой личный кабинет, в котором вы сможете отслеживать свои заказы
                  Для входа - используйте следующие данные:
                  Логин: {$email}
                  Пароль: {$password}
                  Вход по ссылке http://bizoutmax.ru/
                  С уважением, OUTMAXSHOP.RU
HTML;
                  $mail = new Mail();
            			$mail->protocol = $this->config->get('config_mail_protocol');
            			$mail->parameter = $this->config->get('config_mail_parameter');
            			$mail->hostname = $this->config->get('config_smtp_host');
            			$mail->username = $this->config->get('config_smtp_username');
            			$mail->password = $this->config->get('config_smtp_password');
            			$mail->port = $this->config->get('config_smtp_port');
            			$mail->timeout = $this->config->get('config_smtp_timeout');
            			$mail->setTo($email);
            			$mail->setFrom($this->config->get('config_email'));
            			$mail->setSender($this->config->get('config_name'));
            			$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
            			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
            			$mail->send();
                }

              }
              //редактируем адресс клиента в таблице адресов
              $this->load->model('account/address');
              $addressData = $this->getCustomerMainAddress();
              if(isset($this->session->data['firstname'])){
                $addressData['firstname'] = $this->session->data['firstname'];
              }
              if(isset($this->session->data['middlename'])){
                $addressData['middlename'] = $this->session->data['middlename'];
              }
              if(isset($this->session->data['lastname'])){
                $addressData['lastname'] = $this->session->data['lastname'];
              }
              if(isset($this->session->data['telephone'])){
                $addressData['telephone'] = $this->session->data['telephone'];
              }
              if(!empty($addressData)){
                $this->model_account_address->editAddress($addressData['address_id'], $addressData);
              }
              //конец редактируем адресс клиента в таблице адресов
            }
          } elseif ($_REQUEST['step'] == 3) {
            if ((utf8_strlen($this->request->post['np']) < 1) || (utf8_strlen($this->request->post['np']) > 128)) {
    					$json['error']['np'] = $this->language->get('error_np');
    				} else {
              $this->session->data['np'] = $this->request->post['np'];
              $np = $this->request->post['np'];
              $querycity = $this->db->query("SELECT * FROM `delivery_cities` WHERE `np_name` LIKE '{$np}' LIMIT 1");
              $city_data = array();
              if ($querycity) {
                $city_data = $querycity->rows;
              }
              if (count($city_data) > 0) {
                foreach ($city_data as $key => $c) {
                  $this->session->data['city'] = $c['name'];
                  $zone_id = $c['region_id'];
                  $area_id = ($c['area_id'] !== null) ? $c['area_id'] : 0;
                  $queryzone = $this->db->query("SELECT * FROM `delivery_regions` WHERE `id` = '{$zone_id}' LIMIT 1");
                  $zone_data = $queryzone->rows;
                  foreach ($zone_data as $key => $value) {
                    $this->session->data['zone'] = $value['name'];
                  }
                  if ($area_id) {
                    $queryarea = $this->db->query("SELECT * FROM `delivery_area` WHERE `id` = '{$area_id}' LIMIT 1");
                    $area_data = $queryarea->rows;
                    foreach ($area_data as $key => $value) {
                      $this->session->data['area'] = $value['name'];
                    }
                  }
                }
              }
            }
            // if ((utf8_strlen($this->request->post['zone']) < 1) || (utf8_strlen($this->request->post['zone']) > 128)) {
    				// 	$json['error']['zone'] = $this->language->get('error_zone');
    				// }else{
            //   $this->session->data['zone'] = $this->request->post['zone'];
            // }
            // if ((utf8_strlen($this->request->post['area']) > 0)) {
    				// 	$this->session->data['area'] = $this->request->post['area'];
    				// }
            // if ((utf8_strlen($this->request->post['city']) < 1) || (utf8_strlen($this->request->post['city']) > 128)) {
    				// 	$json['error']['city'] = $this->language->get('error_city');
    				// }else{
            //   $this->session->data['city'] = $this->request->post['city'];
            // }
            if ((utf8_strlen($this->request->post['address_1']) < 1) || (utf8_strlen($this->request->post['address_1']) > 128)) {
    					$json['error']['address_1'] = $this->language->get('error_address_1');
    				}else{
              $this->session->data['address_1'] = $this->request->post['address_1'];
            }

    				if ((utf8_strlen($this->request->post['address_2']) < 1) || (utf8_strlen($this->request->post['address_2']) > 128)) {
    					$json['error']['address_2'] = $this->language->get('error_address_2');
    				}else{
              $this->session->data['address_2'] = $this->request->post['address_2'];
            }
    				if (isset($this->request->post['address_3'])) {
    					$this->session->data['address_3'] = $this->request->post['address_3'];
    				}

            if (isset($this->request->post['address_4'])) {
    					$this->session->data['address_4'] = $this->request->post['address_4'];
    				}
            if (isset($this->request->post['comment'])) {
    					$this->session->data['comment'] = $this->request->post['comment'];
            }
            // TODO: remove duplicate code
            if ((utf8_strlen($this->request->post['np']) < 1) || (utf8_strlen($this->request->post['np']) > 128)) {
    					$json['error']['np'] = $this->language->get('error_np');
    				} else {
              unset($this->session->data['payment_city']);
              unset($this->session->data['shipping_city']);
              unset($this->session->data['payment_country']);
              unset($this->session->data['payment_country_id']);
              unset($this->session->data['shipping_country']);
              unset($this->session->data['shipping_country_id']);
              unset($this->session->data['payment_zone']);
              unset($this->session->data['payment_zone_id']);
              unset($this->session->data['shipping_zone']);
              unset($this->session->data['shipping_zone_id']);

              $this->session->data['np'] = $np = $this->request->post['np'];
              $querycity = $this->db->query("SELECT * FROM `delivery_cities` WHERE `np_name` LIKE '{$np}' LIMIT 1");
              $queryregion = $this->db->query("SELECT * FROM `delivery_regions` WHERE `id` LIKE '{$querycity->row['region_id']}' LIMIT 1");
              $queryarea = $this->db->query("SELECT * FROM `delivery_area` WHERE `id` LIKE '{$querycity->row['area_id']}' AND `region_id` = '{$querycity->row['region_id']}' LIMIT 1");
              $region_data = $queryregion->row;
              $city_data = $querycity->row;
              $area_data = $queryarea->row;
              $this->load->model('checkout/shipping');
              $normalize_data = array(
                $np,
                $this->session->data['address_1'],
                $this->session->data['address_2']
              );
              if (isset($this->session->data['address_4']) && utf8_strlen($this->session->data['address_4']) < 32) {
                $normalize_data[] = $this->session->data['address_4'];
              }
              $normalize_from = trim(implode(',', $normalize_data));
              $normalized = $this->model_checkout_shipping->normalizeAddress($normalize_from);
              if (($querycity->num_rows > 0) && ($queryregion->num_rows > 0) && count($normalized) === 1) {
                // Очищаем выбранный метод доставки и доступные способы доставок из сессии
                // т.к. адрес меняется
                if ($this->session->data['postcode'] !== $normalized[0]->postcode) {
                  unset($this->session->data['shipping_method']);
                  unset($this->session->data['shipping_methods']);
                }
                $this->session->data['postcode'] = $normalized[0]->postcode;
                $this->session->data['payment_city'] = $city_data['name'];
                $this->session->data['shipping_city'] = $city_data['name'];
                $c_id = 176;
                $country_q = $this->db->query("SELECT * FROM `oc_country` as `c` WHERE `c`.`country_id` = '{$c_id}'");
                $this->session->data['payment_country'] = $country_q->row['name'];
                $this->session->data['payment_country_id'] = $country_q->row['country_id'];
                $this->session->data['shipping_country'] = $country_q->row['name'];
                $this->session->data['shipping_country_id'] = $country_q->row['country_id'];
                $this->session->data['payment_zone'] = $region_data['name'];
                //$this->session->data['payment_zone_id'] = $zone_q->row['zone_id'];
                $this->session->data['shipping_zone'] = $region_data['name'];
                //$this->session->data['shipping_zone_id'] = $zone_q->row['zone_id'];
                $city_name =  explode(' ', $city_data['name']);
                $nasp_q = $this->db->query("SELECT * FROM `oc_naselenniy_punkt` as `np` WHERE `code` LIKE '{$city_name[0]}' OR `name` LIKE '{$city_name[0]}'");
                if($nasp_q->num_rows > 0){
                  $np = $nasp_q->row;
                  $this->session->data['shipping_naselenniy_punkt'] = $np['code'];
                  $this->session->data['payment_naselenniy_punkt'] = $np['code'];
                  $this->session->data['shipping_naselenniy_punkt_id'] = $np['naselenniy_punkt_id'];
                  $this->session->data['payment_naselenniy_punkt_id'] = $np['naselenniy_punkt_id'];
                }
              } else {
                $json['error']['city_not_found'] = $this->language->get('city_not_found');
              }
            }
            if (!$json) {
              $this->load->model('account/address');
              $addressData = $this->getCustomerMainAddress();
              $this->session->data['shipping_address_id'] = $addressData['address_id'];
              if(isset($this->session->data['address_1'])){
                $addressData['address_1'] = $this->session->data['address_1'];
              }
              if(isset($this->session->data['address_2'])){
                $addressData['address_2'] = $this->session->data['address_2'];
              }
              if(isset($this->session->data['address_3'])){
                $addressData['address_3'] = $this->session->data['address_3'];
              }
              if(isset($this->session->data['address_4'])){
                $addressData['address_4'] = $this->session->data['address_4'];
              }
              if(isset($this->session->data['np'])){
                $addressData['city'] = $this->session->data['np'];
              }
              if(isset($this->session->data['postcode'])){
                $addressData['postcode'] = $this->session->data['postcode'];
              }
              if(!empty($addressData)){
                $this->model_account_address->editAddress($addressData['address_id'], $addressData);
              }
            }
          }

				// Customer Group
				$this->load->model('account/customer_group');

				$customer_group_info = $this->model_account_customer_group->getCustomerGroup($this->customer->getCustomerGroupId());

				if ($customer_group_info) {
					// Company ID
					if ($customer_group_info['company_id_display'] && $customer_group_info['company_id_required'] && empty($this->request->post['company_id'])) {
						$json['error']['company_id'] = $this->language->get('error_company_id');
					}

					// Tax ID
					if ($customer_group_info['tax_id_display'] && $customer_group_info['tax_id_required'] && empty($this->request->post['tax_id'])) {
						$json['error']['tax_id'] = $this->language->get('error_tax_id');
					}
				}

				// if ((utf8_strlen($this->request->post['address_1']) < 1) || (utf8_strlen($this->request->post['address_1']) > 128)) {
				// 	$json['error']['address_1'] = $this->language->get('error_address_1');
				// }
        //
				// if ((utf8_strlen($this->request->post['address_2']) < 1) || (utf8_strlen($this->request->post['address_2']) > 128)) {
				// 	$json['error']['address_2'] = $this->language->get('error_address_2');
				// }
        //
				// if ((utf8_strlen($this->request->post['address_3']) < 1) || (utf8_strlen($this->request->post['address_3']) > 128)) {
				// 	$json['error']['address_3'] = $this->language->get('error_address_3');
				// }

				// if ((utf8_strlen($this->request->post['city']) < 2) || (utf8_strlen($this->request->post['city']) > 32)) {
				// 	$json['error']['city'] = $this->language->get('error_city');
				// }

				$this->load->model('localisation/country');

				$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

				if ($country_info) {
					if ($country_info['postcode_required'] && (utf8_strlen($this->request->post['postcode']) < 2) || (utf8_strlen($this->request->post['postcode']) > 10)) {
						$json['error']['postcode'] = $this->language->get('error_postcode');
					}

					// VAT Validation
					$this->load->helper('vat');

					if ($this->config->get('config_vat') && !empty($this->request->post['tax_id']) && (vat_validation($country_info['iso_code_2'], $this->request->post['tax_id']) == 'invalid')) {
						$json['error']['tax_id'] = $this->language->get('error_vat');
					}
				}

				// if ($this->request->post['country_id'] == '') {
				// 	$json['error']['country'] = $this->language->get('error_country');
				// }
        //
				// if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '') {
				// 	$json['error']['zone'] = $this->language->get('error_zone');
				// }

				if (!$json) {
					// Default Payment Address
					$this->load->model('account/address');

					// $this->session->data['payment_address_id'] = $this->model_account_address->addAddress($this->request->post);
					// $this->session->data['payment_country_id'] = $this->request->post['country_id'];
					// $this->session->data['payment_zone_id'] = $this->request->post['zone_id'];

					unset($this->session->data['payment_method']);
					unset($this->session->data['payment_methods']);
				}
			}
		}
		$this->response->setOutput(json_encode($json));
  }
  
  /**
   * Возвращает основной адрес текущего пользователя
   *
   * @return array
   */
  protected function getCustomerMainAddress()
  {
    $addresses = $this->db->query(
      "SELECT * FROM `oc_address`
      WHERE `customer_id` = '{$this->customer->getId()}'
      AND `address_id` = '{$this->customer->getAddressId()}' LIMIT 1"
    );
    return $addresses->row;
  }
}
?>
