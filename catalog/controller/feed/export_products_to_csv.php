<?php
class ControllerFeedExportProductsToCsv extends Controller {

	
	public function index(){

		$this->load->model('tool/image');
		$this->load->model('catalog/product');
		$this->load->model('catalog/category');

		$customerGroups = array(1,3,4);
		foreach ($customerGroups as $customerGroup){

			$filename = DIR_APPLICATION . "../price/csv/".$customerGroup.".csv";
			if (file_exists($filename)) {
				if (time() - filemtime($filename) < 3600 ) { // разрешаем обновление файла раз в 1 час
					exit('Not allowed');
				} else {
					unlink($filename);
				}
			}

			$results = $this->model_catalog_product->getProducts();
			$this->data['products'] = array();
			$fields = ($this->data['products'][] = array ("product_id","product_name","price","category","manufacturer","options","attributes","description","sku","stock_status","weight","length","width","height","images"));
					
			foreach ($results as $result) {

				$imagesStr = "";
				if ($result['image']){
					$imagesStr .= HTTP_SERVER . 'image/' . $result['image'];
				}

				$images = $this->model_catalog_product->getProductImages($result['product_id']);
				foreach ($images as $image) {
					$imagesStr  .= "\r\n" . HTTP_SERVER . 'image/' . $image['image'];
				} 
				
				$categories = $this->model_catalog_product->getCategories($result['product_id']);
				$categoryStr = '';

				foreach ($categories as $category) {
					$currrentCategory = $this->model_catalog_category->getCategory($category['category_id']);
					if ( $currrentCategory['parent_id'] != 7607 AND $category['category_id'] != 7607 ) { // Исключаем категорию Промо и её субкатегории
						
						$sqlCategory = "SELECT cp.category_id AS category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR ' &gt; ') AS name, c.parent_id, c.sort_order FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category c ON (cp.category_id = c.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (cp.category_id = cd2.category_id) WHERE c.category_id  = " . $category['category_id'] . " AND cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";		
						
						$query = $this->db->query($sqlCategory);
						
						$catInfo = $query->rows;

						$categoryStr .= $catInfo[0]['name'] . " (" . $category['category_id'] . ")\r\n";
					}				
				}

				
				$sqlPrice = "SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = ". $result['product_id']. " AND `customer_group_id` = " . $customerGroup;
				
				$query = $this->db->query($sqlPrice);
				
				$prices = $query->rows;
				if ($prices[0]){
					$price = $prices[0]['price'];
				} else {
					$price = $result['price'];
				}
								

				$options = $this->model_catalog_product->getProductOptions($result['product_id']);
				$optionsStr = '';

				foreach ($options as $option_group) {
					foreach ($option_group['option_value'] as $option_value) {

						if ( $option_value['quantity'] > 0) {
							
							if ($option_value['quantity'] > 15) {
								$option_value['quantity'] = 15;
							}
							$optionsStr .= $option_group['name'] . ": ". $option_value['name'] . ": " . $option_value['quantity'] . "\r\n";
						}

					}
				}


				$attribute_groups = $this->model_catalog_product->getProductAttributes($result['product_id']);
				$attributesStr = "";
				if (!empty($attribute_groups)) {
					$data['param'] = array();
					foreach ($attribute_groups as $attribute_group) {
						foreach ($attribute_group['attribute'] as $attribute) {
							$attributesStr .= $attribute['name'] . ": " . $attribute['text'] . "\r\n";
						}
					}
				}

				$this->data['products'][] = array(
							'product_id'        => $result['product_id'],
							'product_name'         => $result['name'],
							'price'         => $price,							
							'category'         => rtrim($categoryStr,"\r\n"),
							'manufacturer'         => $result['manufacturer'],
							'options'         => rtrim($optionsStr,"\r\n"),
							'attributes'         => rtrim($attributesStr,"\r\n"),
							'description'      => strip_tags(htmlspecialchars_decode($result['description'])),
							'sku' 			    =>  $result['sku'],
							'stock_status' 		=> ($result['quantity'] > 15) ? 15 : $result['quantity'],							
							'weight' 			=> $result['weight'],
							'length'     		=> $result['length'],
							'width' 			=> $result['width'],
							'height'    		=> $result['height'],
							'images'            => $imagesStr
						);
		}
		

			$fp = fopen($filename, 'w');
			foreach ($this->data['products'] as $line) {
				$n++;
				if ( ! fputcsv($fp, $line)) {
					show_error("Can't write line $n: $line");
				}
			}
			fclose($fp);

		//echo "<h1>Ready ".$customerGroup.".csv</h1>";
		}
	}
	
	
}