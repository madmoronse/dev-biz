<?php
class ModelDesignBanner extends Model {
	public function addBanner($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "banner SET name = '" . $this->db->escape($data['name']) . "', status = '" . (int)$data['status'] . "'");
	
		$banner_id = $this->db->getLastId();
	
		if (isset($data['banner_image'])) {
			foreach ($data['banner_image'] as $banner_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "banner_image SET banner_id = '" . (int)$banner_id . "', link = '" .  $this->db->escape($banner_image['link']) . "', image = '" .  $this->db->escape($banner_image['image']) . "'");
				
				$banner_image_id = $this->db->getLastId();
				
				foreach ($banner_image['banner_image_description'] as $language_id => $banner_image_description) {				
					$this->db->query("INSERT INTO " . DB_PREFIX . "banner_image_description SET banner_image_id = '" . (int)$banner_image_id . "', language_id = '" . (int)$language_id . "', banner_id = '" . (int)$banner_id . "', title = '" .  $this->db->escape($banner_image_description['title']) . "'");
				}
			}
		}		
	}
	
	public function editBanner($banner_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "banner SET name = '" . $this->db->escape($data['name']) . "', status = '" . (int)$data['status'] . "' WHERE banner_id = '" . (int)$banner_id . "'");
		/** By Neos - Update, not delete - Start */
		$current_images = $this->db->query(
			"SELECT `banner_image_id` as `id` FROM " . DB_PREFIX . "banner_image 
			WHERE banner_id = " . (int) $banner_id
		);

		$ids = array();

		foreach ($current_images->rows as $row) {
			$ids[] = $row['id'];
		}

		$new_ids = array();

		if (isset($data['banner_image'])) foreach ($data['banner_image'] as $banner_image) {
			if (!isset($banner_image['banner_image_id'])) continue;
			$new_ids[] = (int) $banner_image['banner_image_id'];
		}

		$save_ids = array_intersect($ids, $new_ids);

		if (count($save_ids)) {
			$where =  " AND `banner_image_id` NOT IN(" . implode(',', $save_ids) . ")";
		} else {
			$where = '';
		}
		$remove_ids = array_diff($ids, $new_ids);
		$this->deleteFollows($remove_ids);

		$this->db->query("DELETE FROM " . DB_PREFIX . "banner_image WHERE banner_id = '" . (int)$banner_id . "'" . $where);
		$this->db->query("DELETE FROM " . DB_PREFIX . "banner_image_description WHERE banner_id = '" . (int)$banner_id . "'" . $where);
		
		/** By Neos - Update, not delete - End */
		
		if (isset($data['banner_image'])) {
			$i = 0;
			foreach ($data['banner_image'] as $banner_image) {
				$banner_image_id = null;
				$query = "INSERT INTO " . DB_PREFIX . "banner_image SET banner_id = '" . (int)$banner_id . "', link = '" .  $this->db->escape($banner_image['link']) . "', image = '" .  $this->db->escape($banner_image['image']) . "', video = '" . $this->db->escape($banner_image['video']) . "'";
				if (isset($banner_image['banner_image_id']) && is_numeric($banner_image['banner_image_id'])) {
					$banner_image_id = (int) $banner_image['banner_image_id'];
					$set = ', banner_image_id = ' . $banner_image_id;
				} else {
					$set = '';
				}
				$this->db->query(
					"INSERT INTO " . DB_PREFIX . "banner_image 
					SET 
					banner_id = '" . (int)$banner_id . "', 
					link = '" .  $this->db->escape($banner_image['link']) . "', 
					image = '" .  $this->db->escape($banner_image['image']) . "', 
					video = '" . $this->db->escape($banner_image['video']) . "',
					sort = $i 
					$set
					ON DUPLICATE KEY UPDATE 
					`link` = VALUES(`link`),
					`image` = VALUES(`image`),
					`video` = VALUES(`video`),
					`sort` = VALUES(`sort`)"
				);
				$i++;
				if (empty($banner_image_id)) $banner_image_id = $this->db->getLastId();
				foreach ($banner_image['banner_image_description'] as $language_id => $banner_image_description) {				
					$this->db->query(
						"INSERT INTO " . DB_PREFIX . "banner_image_description 
						SET banner_image_id = '" . (int)$banner_image_id . "', 
						language_id = '" . (int)$language_id . "', 
						banner_id = '" . (int)$banner_id . "', 
						title = '" .  $this->db->escape($banner_image_description['title']) . "'
						ON DUPLICATE KEY UPDATE 
						`language_id` = VALUES(`language_id`),
						`banner_id` = VALUES(`banner_id`),
						`title` = VALUES(`title`)"
					);
				}
			}
		}			
	}

	public function deleteFollows($ids) {
		$sql = ''; $c = '';
		foreach ($ids as $id) {
			$sql .= $c . "'banner_image-$id'"; $c = ',';
		}
		if (!$sql) return false;

		$this->db->query("DELETE FROM `hits` WHERE `pageid` IN ($sql)");
	}
	
	public function deleteBanner($banner_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "banner WHERE banner_id = '" . (int)$banner_id . "'");
		$images = $this->db->query("SELECT `banner_image_id` as `id` FROM " . DB_PREFIX . "banner_image WHERE banner_id = '" . (int)$banner_id . "'");
		$ids = array();
		foreach ($images->rows as $row) {
			$ids[] = $row['id'];
		}
		$this->deleteFollows($ids);
		$this->db->query("DELETE FROM " . DB_PREFIX . "banner_image WHERE banner_id = '" . (int)$banner_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "banner_image_description WHERE banner_id = '" . (int)$banner_id . "'");
	}
	
	public function getBanner($banner_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "banner WHERE banner_id = '" . (int)$banner_id . "'");
		
		return $query->row;
	}
		
	public function getBanners($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "banner";
		
		$sort_data = array(
			'name',
			'status'
		);	
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY name";	
		}
		
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
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
		
	public function getBannerImages($banner_id) {
		$banner_image_data = array();
		
		$banner_image_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner_image WHERE banner_id = '" . (int)$banner_id . "' ORDER BY `sort` ASC, `banner_image_id` DESC");
		// By neos
		$follows = $this->getBannerImagesFollows($banner_image_query->rows);
		foreach ($banner_image_query->rows as $banner_image) {
			$banner_image_description_data = array();
			 
			$banner_image_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner_image_description WHERE banner_image_id = '" . (int)$banner_image['banner_image_id'] . "' AND banner_id = '" . (int)$banner_id . "'");
			
			foreach ($banner_image_description_query->rows as $banner_image_description) {			
				$banner_image_description_data[$banner_image_description['language_id']] = array('title' => $banner_image_description['title']);
			}
			
			$follow = (isset($follows[$banner_image['banner_image_id']])) ? $follows[$banner_image['banner_image_id']] : 0;
			$banner_image_data[] = array(
				'banner_image_id'		   => $banner_image['banner_image_id'],
				'follows'				   => $follow, // By Neos
				'banner_image_description' => $banner_image_description_data,
				'link'                     => $banner_image['link'],
				'image'                    => $banner_image['image'],
				'video'					   => $banner_image['video']
			);
		}
		
		return $banner_image_data;
	}

	/**
	 * 
	 * @param array $rows
	 */
	public function getBannerImagesFollows($rows)
	{
		$data = array();
		$list = ''; $c = '';
		foreach ($rows as $banner_image) {
			$list .= $c . "'banner_image-{$banner_image['banner_image_id']}'"; $c = ',';
		}
		if (!$list) return $data;

		$hits_query = $this->db->query("SELECT * FROM `hits` WHERE `isunique` = 1 AND `pageid` IN ($list)");
		foreach ($hits_query->rows as $hit) {
			$data[preg_replace('/[^\d]/', '', $hit['pageid'])] = $hit['hitcount'];
		}

		return $data;
	}
		
	public function getTotalBanners() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "banner");
		
		return $query->row['total'];
	}	
}
?>