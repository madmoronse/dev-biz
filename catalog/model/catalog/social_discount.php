<?php
class ModelCatalogSocialDiscount extends Model {
	protected $secret = null;
	private $storage_version = 2;
	private $discount = null;
	
	public function __construct($registry) {
		parent::__construct($registry);
		
		$this->secret = $this->config->get('social_discount_secret');
		
		if (!$this->secret) {
			$this->secret = md5(uniqid());
			
			// set group to empty to protect it from delete when module settings changed in admin panel
			$this->db->query('INSERT INTO ' . DB_PREFIX . 'setting(`store_id`, `group`, `key`, `value`, `serialized`) VALUES(0,"","social_discount_secret","' . $this->secret . '",0)');
		}
	}
	
	public function doAction($social, $product_id, $action) {
		$discount = $this->readCookie();
		
		if ($action === 'like' || $action === 'share') {
			$discount[$product_id][$action][$social] = time();
		} else {
			if ($action === 'unlike') {
				// clear like for this social
				unset($discount[$product_id]['like'][$social]);
				
				// clear like array in case of no like inside
				if (count($discount[$product_id]['like']) === 0) {
					unset($discount[$product_id]['like']);
				}
			} else
			if ($action == 'unpublish') {
				// clear share for this social
				unset($discount[$product_id]['share'][$social]);
				
				// clear share array in case of no share inside
				if (count($discount[$product_id]['share']) === 0) {
					unset($discount[$product_id]['share']);
				}
			} else {
				return false;
			}
			
			// clear product from cookie is last like was deleted
			if (count($discount[$product_id]) === 0) {
				unset($discount[$product_id]);
			}
		}
		
		return $this->writeCookie($discount);
	}
	
	public function isLiked($product_id) {
		$discount = $this->readCookie();

		return isset($discount[$product_id]['like']);
	}

	public function getDiscountPercentForProduct($product_id) {
		//if (!$this->discount) {
			$this->discount = $this->readCookie();
		//}
		
		return $this->_getDiscountPercentForProduct($this->discount, $product_id);
	}
	
	private function _getDiscountPercentForProduct($discount, $product_id) {
		$percent = 0;
		
		//$sd_custom = $this->config->get('social_discount_custom');
		$sd_custom = $this->getCustomSocialDiscount($product_id);
		
		if ($sd_custom && $sd_custom['social_discount_custom_enabled']) {
			$sd_params = $sd_custom;
		} else {
			$sd_params = array();
		}
		
		$lifetime = (int)$this->config->get('social_discount_lifetime');
		
		// find maximum discount
		if (isset($discount[$product_id]['like']) === true) {
			foreach ($discount[$product_id]['like'] as $social => $action_time) {
				if ($lifetime == 0 || (time() - $action_time <= $lifetime)) {
					$action_enabled = $this->config->get('social_discount_' . $social . '_like_enabled');
					
					if (isset($sd_params['social_discount_' . $social . '_like_enabled']) === true) {
						$action_enabled = $sd_params['social_discount_' . $social . '_like_enabled'];
					}
					
					if ($action_enabled) {
						if (isset($sd_params['social_discount_' . $social . '_like_value']) === true) {
							$v = $sd_params['social_discount_' . $social . '_like_value'] / 100;
						} else {
							$v = $this->config->get('social_discount_' . $social . '_like_value') / 100;
						}
						
						if ($v > $percent) {
							$percent = $v;
						}
					}
				}
			}
		}
		
		if (isset($discount[$product_id]['share']) === true) {
			foreach ($discount[$product_id]['share'] as $social => $action_time) {
				if ($lifetime == 0 || (time() - $action_time <= $lifetime)) {
					$action_enabled = $this->config->get('social_discount_' . $social . '_share_enabled');
					
					if (isset($sd_params['social_discount_' . $social . '_share_enabled']) === true) {
						$action_enabled = $sd_params['social_discount_' . $social . '_share_enabled'];
					}
					
					if ($action_enabled) {
						if (isset($sd_params['social_discount_' . $social . '_share_value']) === true) {
							$v = $sd_params['social_discount_' . $social . '_share_value'] / 100;
						} else {
							$v = $this->config->get('social_discount_' . $social . '_share_value') / 100;
						}
						
						if ($v > $percent) {
							$percent = $v;
						}
					}
				}
			}
		}
		
		return $percent;
	}
	
	protected function _getDiscountForProduct($discount, $product) {
		$discount_method = $this->config->get('social_discount_discount_method');
		$discount_type = $this->config->get('social_discount_discount_type');
		
		$discount_value = 0;
		
		if (isset($discount[ $product['product_id'] ]) === true) {
			$discount_percent = $this->_getDiscountPercentForProduct($discount, $product['product_id']);
				
			$product['social_discount'] = ($discount_percent > 0);
				
			if ($product['price'] > 0 && isset($product['special'])) {
				$special_percent = ($product['price'] - $product['special']) * 1.0 / $product['price'];
			} else {
				$special_percent = 0;
			}
			
			if ($discount_percent > 0) {
				if ($discount_type == 1) {
					$discount_value = $discount_percent*100;
				} else {
					switch ($discount_method) {
					case 1: // special price
						if (isset($product['special'])) {
							$discount_value = $product['special'] * $discount_percent;
							
							break;
						}
					case 0: // base price
						$product['social_discount_percent'] = $discount_percent;
						
						$discount_value = $product['price'] * $discount_percent;
						
						break;
					}
				}
			}
		}
		
		return $discount_value;
	}
	
	public function getDiscountForProduct($product) {
		$discount = $this->readCookie();
		
		return $this->_getDiscountForProduct($discount, $product);
	}
	
	public function updateProductSpecial(& $product) {
		$discount_method = $this->config->get('social_discount_discount_method');
		$discount_type = $this->config->get('social_discount_discount_type');
		
		if ($product) {
			$discount_value = $this->getDiscountForProduct($product);
			
			//die($discount_value);
			$product['social_discount'] = ($discount_value > 0);
			
			if ($product['price'] > 0 && isset($product['special'])) {
				$special_percent = ($product['price'] - $product['special']) * 1.0 / $product['price'];
			} else {
				$special_percent = 0;
			}
			
			if ($discount_value > 0) {
				if ($discount_type == 1) {
					if (isset($product['special']) && $discount_method == 1) {
						$special = $product['special'] - $discount_value;
					} else {
						$special = $product['price'] - $discount_value;
					}
					
					$product['social_discount_percent'] = $discount_value / $product['price'];
				} else {
					switch ($discount_method) {
					case 1: // special price
						if ($product['special']) {
							$special = $product['special'] - $discount_value;
							$discount_percent = $discount_value * 1.0 / $product['special'];
							
							$product['social_discount_percent'] = sprintf("%.2f", $special_percent + $discount_percent);
							
							break;
						}
					case 0: // base price
						$discount_percent = $discount_value *1.0 / $product['price'];
						$product['social_discount_percent'] = $discount_percent;
						
						if ($product['special']) {
							$special = $product['special'] - $discount_value;
						} else {
							$special = $product['price'] - $discount_value;
						}
						
						break;
					}
				}
			
				$product['special'] = $special;
			} else {
				$product['social_discount_percent'] = $special_percent;
			}
		}
		
		return $product;
	}
	
	public function getDiscount($products) {
		$discount = $this->readCookie();
		
		$result = 0;
		
		foreach ($products as $product) {
			$result += $this->_getDiscountForProduct($discount, $product) * $product['quantity'];
		}
		
		return $result;
	}

	public function getCustomSocialDiscount($product_id) {
		$this->checkTables();
		
		$results = $this->db->query('SELECT * FROM ' . DB_PREFIX . 'social_discount WHERE product_id = ' . (int)$product_id . ' LIMIT 1');
		if ($results->num_rows > 0) {
			return unserialize($results->row['value']);
		} else {
			return false;
		}
	}
	
	private function checkTables() {
		$sql = 'CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'social_discount`('
			. '`product_id` int(11) NOT NULL,'
			. '`value` text NOT NULL,'
			. ' PRIMARY KEY (`product_id`) '
			. ') ENGINE=MyISAM DEFAULT CHARSET=utf8;';
			
		$this->db->query($sql);
	}
	
	/* Internal functions */
	private function readCookie() {
		if (isset($_COOKIE['sd'])) {
			$content = $this->decrypt($_COOKIE['sd'], $this->secret);
			
			if ($content) {
				$result = @unserialize($content);
				
				if (!$result) {
					// invalid cookie or secret was changed
					// nulify cookie
					$result = array();
					$this->writeCookie($result);
				}
			} else {
				$result = array();
			}
		} else {
			$result = array();
		}
		
		if (!isset($result['v'])) {
			// No version field. Invalid format.
			return array(); 
		}
		
		if ($result['v'] != $this->storage_version) {
			// migrate cookie structure to newest version if needed
			$result['c'] = $this->migrate($result['c'], $result['v']);
			$this->writeCookie($result['c']);
		}
		
		if (isset($result['c'])) {
			// just return content of cookie
			$result = $result['c'];
		}
		
		return $result;
	}
	
	private function writeCookie($content) {
		$data = array (
			'v' => $this->storage_version, // storage version
			'c' => $content,
		);
		
		$value = $this->encrypt(serialize($data), $this->secret);
		
		$_COOKIE['sd'] = $value; // hack: update for future read in this thread
		
		return (setcookie('sd', $value, time() + 60*60*24*365*5) === true);
	}
	
	private function encrypt($text, $salt) { 
		return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
	}

	private function decrypt($text, $salt) { 
		return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $salt, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
	}
	
	private function migrate($content, $version) {
		for ($i = $version; $i < $this->storage_version; ++$i) {
			$func_name = 'migrate_' . $i . '_' . ($i + 1);
			
			$content = $this->{$func_name}($content);
		}
		
		return $content;
	}
	
	/* Version 1: Stored only time of likes in Vkontakte
	   Version 2: Stored time, social network and action for each product
	*/
	private function migrate_1_2($content) {
		$result = array();
		
		foreach ($content as $product_id => $vk_like_time) {
			$result[$product_id]['like']['vk'] = $vk_like_time;
		}
		
		return $result;
	}
}
?>