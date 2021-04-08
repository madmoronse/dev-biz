<?php
class ModelLocalisationCountry extends Model {
	public function getCountry($country_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE country_id = '" . (int)$country_id . "' AND status = '1'");
		
		return $query->row;
	}


	//public function getNaselenniyPunkt($getNaselenniyPunkt_id) {
	//	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "naselenniy_punkt WHERE naselenniy_punkt_id = '" . (int)$naselenniy_punkt_id . "'");
		
	//	return $query->row;
	//}	
	
	public function getCountries() {
		$country_data = $this->cache->get('country.status');
		
		if (!$country_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE status = '1' ORDER BY name ASC");
	
			$country_data = $query->rows;
		
			$this->cache->set('country.status', $country_data);
		}

		return $country_data;
	}
	
	
	public function getNaselenniyPunkts() {
		
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "naselenniy_punkt ORDER BY name ASC");
	
			$naselenniy_punkt_data = $query->rows;
		
		return $naselenniy_punkt_data;
	}
	
}
?>