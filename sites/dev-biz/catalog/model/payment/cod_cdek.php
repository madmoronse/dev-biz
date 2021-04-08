<?php 
class ModelPaymentCodCdek extends Model {
	
  	public function getMethod($address, $total) {
		
		$method_data = array();
		
		if (!empty($this->session->data['payment_method']['code']) && $this->session->data['payment_method']['code'] == 'cod_cdek') {
			unset($this->session->data['payment_method']['code']);
		}
		
		if (!is_array($this->config->get('cod_cdek_store')) || !in_array($this->config->get('config_store_id'), $this->config->get('cod_cdek_store'))) {
			return $method_data;
		}
		
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}
		
		if ($this->config->get('cod_cdek_customer_group_id') && !in_array($customer_group_id, $this->config->get('cod_cdek_customer_group_id'))) {
			return $method_data;
		}
		
		if (!empty($address['city'])) {
			$city = $this->clearText($address['city']);
		} else {
			$city = '';
		}
		
		$city_ignore = array();
				
		if ($this->config->get('cod_cdek_city_ignore')) {
			
			$city_ignore = explode(', ', $this->config->get('cod_cdek_city_ignore'));
			$city_ignore = array_map('trim', $city_ignore);
			$city_ignore = array_filter($city_ignore);
			$city_ignore = array_map(array($this, 'clearText'), $city_ignore);
			
		}
		
		if (in_array($city, $city_ignore)) {
			return $method_data;
		}
		
		if ($this->config->get('cod_cdek_geo_zone_id')) {
			
			$cod_cdek_geo_zone_id = $this->config->get('cod_cdek_geo_zone_id');
			
			if (!is_array($cod_cdek_geo_zone_id)) {
				$cod_cdek_geo_zone_id = array($cod_cdek_geo_zone_id);
			}
		
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id IN(" . implode(',', $cod_cdek_geo_zone_id) . ") AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
			
			if (!$query->num_rows) {
				return $method_data;
			}
		
		}
		
		$total = $this->cart->getTotal();
	
		$min_total = (float)$this->config->get('cod_cdek_min_total');
		$max_total = (float)$this->config->get('cod_cdek_max_total');
		
		if (($min_total > 0 && $total < $min_total) || ($max_total > 0 && $total > $max_total)) {
			return $method_data;
		}
		
		if (isset($this->session->data['shipping_method']['code'])) {
			list($shipping_method, $code) = explode('.', $this->session->data['shipping_method']['code']);
		} else {
			$shipping_method = '';
		}
		
		// Ограничение наложки только для СДЭКа
		if ($this->config->get('cod_cdek_cache_on_delivery') && $shipping_method == 'cdek') {
			
			if (empty($this->session->data['shipping_method']['cod'])) {
				return $method_data;
			}
			
		}
		
		if ($this->config->get('cod_cdek_mode') == 'cdek') {
			
			if ($shipping_method != 'cdek') {
				return $method_data;
			}
			
			if ($this->config->get('cod_cdek_mode_cdek') != 'all') {
			
				$status = FALSE;
				
				$tariff_parts = explode('_', $code);
								
				if (count($tariff_parts) == 3) {
					
					list(,$tariff_id) = $tariff_parts;
					
					$tariff_info = $this->getTariffInfo($tariff_id);
					
					if ($tariff_info) {
				
						switch ($this->config->get('cod_cdek_mode_cdek')) {
							case 'courier':
							
								if (in_array($tariff_info['mode_id'], array(1, 3))) {
									$status = TRUE;
								}
								
								break;
							case 'pvz':
							
								if (in_array($tariff_info['mode_id'], array(2, 4))) {
									$status = TRUE;
								}
								
								break;
								
						}
							
					}
					
				}
				
				if (!$status) {
					return $method_data;
				}
				
			}
		}
		
		if ($this->config->get('cod_cdek_active')) {
			$this->session->data['payment_method']['code'] = 'cod_cdek';
		}
		
		$title_info = $this->config->get('cod_cdek_title');
							
		if (!empty($title_info[$this->config->get('config_language_id')])) {
			$title = $title_info[$this->config->get('config_language_id')];
		} else {
			$this->load->language('payment/cod_cdek');
			$title = $this->language->get('text_title');
		}
	
		$method_data = array( 
			'code'       => 'cod_cdek',
			'title'      => $title,
			'sort_order' => $this->config->get('cod_sort_order')
		);
   
    	return $method_data;
  	}
	
	private function clearText($value) {
		return trim(mb_convert_case($value, MB_CASE_LOWER, "UTF-8"));
	}
	
	private function getTariffInfo($tariff_id) {
		
		$all = $this->getTariffList();
		
		return array_key_exists($tariff_id, $all) ? $all[$tariff_id] : FALSE;
	}
	
	private function getTariffList() {
		return array(
			'1'	=> array(
				'title'		=> 'Экспресс лайт (Д-Д)',
				'mode_id'	=> 1
			),
			'3' => array(
				'title'		=> 'Супер-экспресс до 18 (Д-Д)',
				'mode_id'	=> 1
			),
			'4' => array(
				'title'		=> 'Рассылка (Д-Д)',
				'mode_id'	=> 1
			),
			'5' => array(
				'title'		=> 'Экономичный экспресс (С-С)',
				'mode_id'	=> 4
			),
			'7' => array(
				'title'		=> 'Международный экспресс документы (Д-Д)',
				'mode_id'	=> 1
			),
			'8' => array(
				'title'		=> 'Международный экспресс грузы (Д-Д)',
				'mode_id'	=> 1
			),
			'10' => array(
				'title'		=> 'Экспресс лайт (С-С)',
				'mode_id'	=> 4
			), 
			'11' => array(
				'title'		=> 'Экспресс лайт (С-Д)',
				'mode_id'	=> 3
			), 
			'12' => array(
				'title'		=> 'Экспресс лайт (Д-С)',
				'mode_id'	=> 2
			), 
			'15' => array(
				'title'		=> 'Экспресс тяжеловесы (С-С)',
				'mode_id'	=> 4
			),
			'16' => array(
				'title'		=> 'Экспресс тяжеловесы (С-Д)',
				'mode_id'	=> 3
			), 
			'17' => array(
				'title'		=> 'Экспресс тяжеловесы (Д-С)',
				'mode_id'	=> 2
			), 
			'18' => array(
				'title'		=> 'Экспресс тяжеловесы (Д-Д)',
				'mode_id'	=> 1
			), 
			'57' => array(
				'title'		=> 'Супер-экспресс до 9 (Д-Д)',
				'mode_id'	=> 1
			),
			'58' => array(
				'title'		=> 'Супер-экспресс до 10 (Д-Д)',
				'mode_id'	=> 1
			), 
			'59' => array(
				'title'		=> 'Супер-экспресс до 12 (Д-Д)',
				'mode_id'	=> 1
			), 
			'60' => array(
				'title'		=> 'Супер-экспресс до 14 (Д-Д)',
				'mode_id'	=> 1
			),
			'61' => array(
				'title'		=> 'Супер-экспресс до 16 (Д-Д)',
				'mode_id'	=> 1
			),
			'62' => array(
				'title'		=> 'Магистральный экспресс (С-С)',
				'mode_id'	=> 4
			),
			'63' => array(
				'title'		=> 'Магистральный супер-экспресс (С-С)',
				'mode_id'	=> 4
			),
			'66' => array(
				'title'		=> 'Блиц-экспресс 01 (Д-Д)',
				'mode_id'	=> 1
			),
			'67' => array(
				'title'		=> 'Блиц-экспресс 02 (Д-Д)',
				'mode_id'	=> 1
			), 
			'68' => array(
				'title'		=> 'Блиц-экспресс 03 (Д-Д)',
				'mode_id'	=> 1
			), 
			'69' => array(
				'title'		=> 'Блиц-экспресс 04 (Д-Д)',
				'mode_id'	=> 1
			), 
			'70' => array(
				'title'		=> 'Блиц-экспресс 05 (Д-Д)',
				'mode_id'	=> 1
			), 
			'71' => array(
				'title'		=> 'Блиц-экспресс 06 (Д-Д)',
				'mode_id'	=> 1
			), 
			'72' => array(
				'title'		=> 'Блиц-экспресс 07 (Д-Д)',
				'mode_id'	=> 1
			), 
			'73' => array(
				'title'		=> 'Блиц-экспресс 08 (Д-Д)',
				'mode_id'	=> 1
			), 
			'74' => array(
				'title'		=> 'Блиц-экспресс 09 (Д-Д)',
				'mode_id'	=> 1
			), 
			'75' => array(
				'title'		=> 'Блиц-экспресс 10 (Д-Д)',
				'mode_id'	=> 1
			), 
			'76' => array(
				'title'		=> 'Блиц-экспресс 11 (Д-Д)',
				'mode_id'	=> 1
			), 
			'77' => array(
				'title'		=> 'Блиц-экспресс 12 (Д-Д)',
				'mode_id'	=> 1
			), 
			'78' => array(
				'title'		=> 'Блиц-экспресс 13 (Д-Д)',
				'mode_id'	=> 1
			), 
			'79' => array(
				'title'		=> 'Блиц-экспресс 14 (Д-Д)',
				'mode_id'	=> 1
			), 
			'80' => array(
				'title'		=> 'Блиц-экспресс 15 (Д-Д)',
				'mode_id'	=> 1
			), 
			'81' => array(
				'title'		=> 'Блиц-экспресс 16 (Д-Д)',
				'mode_id'	=> 1
			),
			'82' => array(
				'title'		=> 'Блиц-экспресс 17 (Д-Д)',
				'mode_id'	=> 1
			), 
			'83' => array(
				'title'		=> 'Блиц-экспресс 18 (Д-Д)',
				'mode_id'	=> 1
			), 
			'84' => array(
				'title'		=> 'Блиц-экспресс 19 (Д-Д)',
				'mode_id'	=> 1
			), 
			'85' => array(
				'title'		=> 'Блиц-экспресс 20 (Д-Д)',
				'mode_id'	=> 1
			), 
			'86' => array(
				'title'		=> 'Блиц-экспресс 21 (Д-Д)',
				'mode_id'	=> 1
			), 
			'87' => array(
				'title'		=> 'Блиц-экспресс 22 (Д-Д)',
				'mode_id'	=> 1
			), 
			'88' => array(
				'title'		=> 'Блиц-экспресс 23 (Д-Д)',
				'mode_id'	=> 1
			),
			'89' => array(
				'title'		=> 'Блиц-экспресс 24 (Д-Д)',
				'mode_id'	=> 1
			),
			'136' => array(
				'title'		=> 'Посылка (С-С)',
				'mode_id'	=> 4
			),
			'137' => array(
				'title'		=> 'Посылка (С-Д)',
				'mode_id'	=> 3
			),
			'138' => array(
				'title'		=> 'Посылка (Д-С)',
				'mode_id'	=> 2
			),
			'139' => array(
				'title'		=> 'Посылка (Д-Д)',
				'mode_id'	=> 1
			),
			'140' => array(
				'title'		=> 'Возврат (С-С)',
				'mode_id'	=> 4
			),
			'141' => array(
				'title'		=> 'Возврат (С-Д)',
				'mode_id'	=> 3
			),
			'142' => array(
				'title'		=> 'Возврат (Д-С)',
				'mode_id'	=> 2
			)
		);
	}
}
?>