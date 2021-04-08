<?php
class ModelCatalogSocialDiscount extends Model {
	public function getCustomSocialDiscount($product_id) {
		$this->checkTables();
		
		$results = $this->db->query('SELECT * FROM ' . DB_PREFIX . 'social_discount WHERE product_id = ' . (int)$product_id . ' LIMIT 1');
		if ($results->num_rows > 0) {
			$result = unserialize($results->row['value']);
			return $result;
		} else {
			return false;
		}
	}
	
	public function setCustomSocialDiscount($product_id, $data) {
		$this->checkTables();
		
		$value = serialize($data);
		$this->db->query('INSERT INTO ' . DB_PREFIX . 'social_discount(product_id, `value`) VALUES(' . (int)$product_id . ', "' . $this->db->escape($value) . '") ON DUPLICATE KEY UPDATE `value`="' . $this->db->escape($value) . '"');
	}
	
	private function checkTables() {
		$sql = 'CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'social_discount`('
			. '`product_id` int(11) NOT NULL,'
			. '`value` text NOT NULL,'
			. ' PRIMARY KEY (`product_id`) '
			. ') ENGINE=MyISAM DEFAULT CHARSET=utf8;';
			
		$this->db->query($sql);
	}
}
?>