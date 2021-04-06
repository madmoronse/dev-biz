<?php
class ControllerModuleCategoryMenu extends Controller {
	protected function index($setting) {
		$this->language->load('module/category_menu');

		$this->document->addScript('catalog/view/javascript/jquery/category-menu/category-menu.js');
		$this->document->addStyle('catalog/view/javascript/jquery/category-menu/category-menu.css');

		if (!empty($setting['menu_title'][$this->config->get('config_language_id')])) {
			$this->data['heading_title'] = $setting['menu_title'][$this->config->get('config_language_id')];
		} else {
			$this->data['heading_title'] = $this->language->get('heading_title');
		}

		if (isset($setting['title_status'][$this->config->get('config_language_id')])) {
			$this->data['title_status'] = $setting['title_status'][$this->config->get('config_language_id')];
		} else {
			$this->data['title_status'] = '1';
		}

		if (isset($setting['title_style'])) {
			$this->data['title_style'] = $setting['title_style'];
		} else {
			$this->data['title_style'] = '';
		}

		if (isset($setting['style'])) {
			$this->data['style'] = $setting['style'];
		} else {
			$this->data['style'] = 'accordion';
		}

		if (isset($setting['image'])) {
			$this->data['image'] = $setting['image'];
		} else {
			$this->data['image'] = 0;
		}

		if (!empty($setting['width'])) {
			$this->data['width'] = $setting['width'];
		} else {
			$this->data['width'] = '172';
		}

		if (!empty($setting['height'])) {
			$this->data['height'] = $setting['height'];
		} else {
			$this->data['height'] = '90';
		}

		if (isset($setting['toggle'])) {
			$this->data['toggle'] = $setting['toggle'];
		} else {
			$this->data['toggle'] = 'btn';
		}

		if ($setting['position'] == 'column_right') {
			$this->data['position'] = $setting['position'];
		} else {
			$this->data['position'] = '';
		}

		if (isset($setting['category_selected'])) {
			$categories = $setting['category_selected'];
		} else {
			$categories = '';
		}

		if (isset($setting['manufacturer_selected'])) {
			$manufacturers = $setting['manufacturer_selected'];
		} else {
			$manufacturers = '';
		}

		if (isset($setting['information_selected'])) {
			$informations = $setting['information_selected'];
		} else {
			$informations = '';
		}

		if (isset($setting['custom_link'])) {
			$custom_links = $setting['custom_link'];
		} else {
			$custom_links = '';
		}

		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = array();
		}

		if (isset($parts[0])) {
			$this->data['category_id'] = $parts[0];
		} else {
			$this->data['category_id'] = 0;
		}

		if (isset($parts[1])) {
			$this->data['child_id'] = $parts[1];
		} else {
			$this->data['child_id'] = 0;
		}

		if (isset($parts[2])) {
			$this->data['child2_id'] = $parts[2];
		} else {
			$this->data['child2_id'] = 0;
		}

		if (isset($parts[3])) {
			$this->data['child3_id'] = $parts[3];
		} else {
			$this->data['child3_id'] = 0;
		}

		$this->load->model('catalog/category');

		$this->load->model('catalog/manufacturer');

		$this->load->model('catalog/information');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		if (isset($this->request->get['information_id'])) {
			$this->data['information_id'] = (int)$this->request->get['information_id'];
		} else {
			$this->data['information_id'] = 0;
		}

		$this->data['informations'] = array();

		if($informations) {
			foreach ($informations as $information_id) {
				$information_info = $this->model_catalog_information->getInformation($information_id);

				if($information_info) {
					$this->data['informations'][] = array(
						'information_id' => $information_info['information_id'],
						'title' => $information_info['title'],
						'href'  => $this->url->link('information/information', 'information_id=' .  $information_info['information_id'])
						);
				}
			}
		}

		if (isset($this->request->get['manufacturer_id'])) {
			$this->data['manufacturer_id'] = (int)$this->request->get['manufacturer_id'];
		} else {
			$this->data['manufacturer_id'] = 0;
		}

		$this->data['manufacturers'] = array();

		if($manufacturers) {
			foreach ($manufacturers as $manufacturer_id) {
				$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);

				if($manufacturer_info) {
					$this->data['manufacturers'][] = array(
						'manufacturer_id' => $manufacturer_info['manufacturer_id'],
						'name' => $manufacturer_info['name'],
						'href' => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer_info['manufacturer_id'])
						);
				}
			}
		}

		$this->data['custom_links'] = array();

		if($custom_links) {
			foreach ($custom_links as $custom_link) {

				$this->data['custom_links'][] = array(
					'link_title' => $custom_link['link_title'][$this->config->get('config_language_id')],
					'href'       => $custom_link['href'][$this->config->get('config_language_id')]
					);
			}
		}

		$this->data['categories'] = array();

		if($categories) {
            foreach ($categories as $category_id) {
                $category = $this->model_catalog_category->getCategory($category_id);
                if ($category) {

				if ($this->config->get('config_product_count')) {
					$total = $this->model_catalog_product->getTotalProducts(array('filter_category_id' => $category['category_id']));
				}

				$children_data = array();

				$children = $this->model_catalog_category->getCategories($category['category_id']);

				foreach ($children as $child) {
					$data = array(
						'filter_category_id'  => $child['category_id'],
						'filter_sub_category' => true
					);

					if ($this->config->get('config_product_count')) {
						$product_total = $this->model_catalog_product->getTotalProducts($data);
						$total += $product_total;
					}

					$children2_data = array();
					$children2 = $this->model_catalog_category->getCategories($child['category_id']);

					foreach ($children2 as $child2) {

						$data2 = array(
							'filter_category_id'  => $child2['category_id'],
							'filter_sub_category' => true
						);

						if ($this->config->get('config_product_count')) {
							$product_total2 = $this->model_catalog_product->getTotalProducts($data2);
							$total += $product_total2;
						}

						$children3_data = array();
						$children3 = $this->model_catalog_category->getCategories($child2['category_id']);

						foreach ($children3 as $child3) {

							$data3 = array(
								'filter_category_id'  => $child3['category_id'],
								'filter_sub_category' => true
							);

							if ($this->config->get('config_product_count')) {
								$product_total3 = $this->model_catalog_product->getTotalProducts($data3);
								$total += $product_total3;
							}

							$children3_data[] = array(
								'category_id' => $child3['category_id'],
								'name'        => $child3['name'] . ($this->config->get('config_product_count') ? ' (' . $product_total3 . ')' : ''),
								'href'        => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'] . '_' . $child2['category_id'] . '_' . $child3['category_id'])
							);
						}

						$children2_data[] = array(
							'category_id' => $child2['category_id'],
							'child3_id'   => $children3_data,
							'name'        => $child2['name'] . ($this->config->get('config_product_count') ? ' (' . $product_total2 . ')' : ''),
							'href'        => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'] . '_' . $child2['category_id']),
							'thumb'       => $this->model_tool_image->resize($child2['image'], $this->data['width'], $this->data['height']),
							'menu_image'  => $child2['menu_image']
						);
					}

					$children_data[] = array(
						'category_id' => $child['category_id'],
						'child2_id'   => $children2_data,
						'name'        => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $product_total . ')' : ''),
						'href'        => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id']),
						'thumb'       => $this->model_tool_image->resize($child['image'], $this->data['width'], $this->data['height']),
						'menu_image'  => $child['menu_image']
					);
				}

				$this->data['categories'][] = array(
					'category_id' => $category['category_id'],
					'children'    => $children_data,
					'name'        => $category['name'] . ($this->config->get('config_product_count') ? ' (' . $total . ')' : ''),
					'href'        => $this->url->link('product/category', 'path=' . $category['category_id']),
					'thumb'       => $this->model_tool_image->resize($category['image'], $this->data['width'], $this->data['height']),
					'menu_image'  => $category['menu_image']
				);
				}
			}
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/category_menu.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/category_menu.tpl';
		} else {
			$this->template = 'default/template/module/category_menu.tpl';
		}

		$this->render();
	}
}
?>