<?php
class ControllerModuleFilterPro extends Controller {
	private $k = 1;

	protected function index($setting) {
		if($setting['type'] == 1) {
			$this->language->load('product/filter');
			$this->data['text_display'] = $this->language->get('text_display');
			$this->data['text_list'] = $this->language->get('text_list');
			$this->data['text_grid'] = $this->language->get('text_grid');
			$this->data['text_sort'] = $this->language->get('text_sort');
			$this->data['text_limit'] = $this->language->get('text_limit');

			$sort = 'p.sort_order';
			$order = 'DESC';



			$limit = $this->config->get('config_catalog_limit');



			$url = '';

			if(isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$this->data['sorts'] = array();

			$this->data['sorts'][] = array(
				'text' => $this->language->get('text_default'),
				'value' => 'p.sort_order-DESC',
				'href' => $this->url->link('product/filter', 'sort=p.sort_order&order=DESC' . $url)
			);

			$this->data['sorts'][] = array(
				'text' => $this->language->get('text_name_asc'),
				'value' => 'pd.name-ASC',
				'href' => $this->url->link('product/filter', 'sort=pd.name&order=ASC' . $url)
			);

			$this->data['sorts'][] = array(
				'text' => $this->language->get('text_name_desc'),
				'value' => 'pd.name-DESC',
				'href' => $this->url->link('product/filter', 'sort=pd.name&order=DESC' . $url)
			);

			$this->data['sorts'][] = array(
				'text' => $this->language->get('text_price_asc'),
				'value' => 'p.price-ASC',
				'href' => $this->url->link('product/filter', 'sort=p.price&order=ASC' . $url)
			);

			$this->data['sorts'][] = array(
				'text' => $this->language->get('text_price_desc'),
				'value' => 'p.price-DESC',
				'href' => $this->url->link('product/filter', 'sort=p.price&order=DESC' . $url)
			);

			if($this->config->get('config_review_status')) {
				$this->data['sorts'][] = array(
					'text' => $this->language->get('text_rating_desc'),
					'value' => 'rating-DESC',
					'href' => $this->url->link('product/filter', 'sort=rating&order=DESC' . $url)
				);

				$this->data['sorts'][] = array(
					'text' => $this->language->get('text_rating_asc'),
					'value' => 'rating-ASC',
					'href' => $this->url->link('product/filter', 'sort=rating&order=ASC' . $url)
				);
			}

			$this->data['sorts'][] = array(
				'text' => $this->language->get('text_model_asc'),
				'value' => 'p.model-ASC',
				'href' => $this->url->link('product/filter', 'sort=p.model&order=ASC' . $url)
			);

			$this->data['sorts'][] = array(
				'text' => $this->language->get('text_model_desc'),
				'value' => 'p.model-DESC',
				'href' => $this->url->link('product/filter', 'sort=p.model&order=DESC' . $url)
			);

			$url = '';

			if(isset($this->request->get['sort'])) {
				$url .= 'sort=' . $this->request->get['sort'];
			}

			if(isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$this->data['limits'] = array();

			$this->data['limits'][] = array(
				'text' => $this->config->get('config_catalog_limit'),
				'value' => $this->config->get('config_catalog_limit'),
				'href' => $this->url->link('product/filter', $url . '&limit=' . $this->config->get('config_catalog_limit'))
			);

			$this->data['limits'][] = array(
				'text' => 25,
				'value' => 25,
				'href' => $this->url->link('product/filter', $url . '&limit=25')
			);

			$this->data['limits'][] = array(
				'text' => 50,
				'value' => 50,
				'href' => $this->url->link('product/filter', $url . '&limit=50')
			);

			$this->data['limits'][] = array(
				'text' => 75,
				'value' => 75,
				'href' => $this->url->link('product/filter', $url . '&limit=75')
			);

			$this->data['limits'][] = array(
				'text' => 100,
				'value' => 100,
				'href' => $this->url->link('product/filter', $url . '&limit=100')
			);


			$this->data['sort'] = $sort;
			$this->data['order'] = $order;
			$this->data['limit'] = $limit;

			if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/filterpro_container.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/module/filterpro_container.tpl';
			} else {
				$this->template = 'default/template/module/filterpro_container.tpl';
			}
		} else {
			$this->language->load('module/filterpro');

			$this->data['text_tax'] = $this->language->get('text_tax');
			$this->data['button_cart'] = $this->language->get('button_cart');
			$this->data['button_wishlist'] = $this->language->get('button_wishlist');
			$this->data['button_compare'] = $this->language->get('button_compare');
			$this->data['text_price_range'] = $this->language->get('text_price_range');
			$this->data['text_manufacturers'] = $this->language->get('text_manufacturers');
			$this->data['text_tags'] = $this->language->get('text_tags');
			$this->data['text_categories'] = $this->language->get('text_categories');
			$this->data['text_attributes'] = $this->language->get('text_attributes');
			$this->data['text_all'] = $this->language->get('text_all');
			$this->data['clear_filter'] = $this->language->get('clear_filter');
			$this->data['text_instock'] = $this->language->get('text_instock');

			$this->data['heading_title'] = $this->language->get('heading_title');

			$this->data['pds_sku'] = $this->language->get('pds_sku');
			$this->data['pds_upc'] = $this->language->get('pds_upc');
			$this->data['pds_location'] = $this->language->get('pds_location');
			$this->data['pds_model'] = $this->language->get('pds_model');
			$this->data['pds_brand'] = $this->language->get('pds_brand');
			$this->data['pds_stock'] = $this->language->get('pds_stock');
			$this->data['symbol_right'] = $this->currency->getSymbolRight();
			$this->data['symbol_left'] = $this->currency->getSymbolLeft();

			$this->data['setting'] = $setting;

			if(VERSION == '1.5.0') {
				$filterpro_setting = unserialize($this->config->get('filterpro_setting'));
			} else {
				$filterpro_setting = $this->config->get('filterpro_setting');
			}

			$category_id = false;
			$this->data['path'] = "";
			if(isset($this->request->get['path'])) {
				$this->data['path'] = $this->request->get['path'];
				$parts = explode('_', (string)$this->request->get['path']);
				$category_id = array_pop($parts);
			}

			$manufacturer_id = false;
			if(isset($this->request->get['manufacturer_id'])) {
				$manufacturer_id = $this->request->get['manufacturer_id'];
				$data = array(
					'filter_manufacturer_id' => $this->request->get['manufacturer_id']
				);
			} else {
				$data = array(
					'filter_category_id' => $category_id,
					'filter_sub_category' => false
				);
			}



            $this->data['category_id'] = $category_id;

			if ($category_id == "1163") { $sort = 'p.price'; $order = 'DESC';}
			if ($category_id == "10013408" or $category_id == "10013409" or $category_id == "10013410" or $category_id == "10013411") { $sort = 'p.sku'; $order = 'ASC';}
			
            //BMV additional products BEGIN

            $this->load->model('catalog/category');

            $this->load->model('catalog/product');

            $additionalProductsForCategory = $this->model_catalog_category->getAdditionalProductsForCategory($category_id);
            $additionalProductsForCategory['add_products_from_category'] = explode(',', $additionalProductsForCategory['add_products_from_category']);
            $additionalProductsForCategory['substract_products_from_category'] = explode(',', $additionalProductsForCategory['substract_products_from_category']);
            $additionalProductsForCategory['substract_products'] = explode(',', $additionalProductsForCategory['substract_products']);
            $additionalProductsForCategory['add_products'] = array_diff(explode("\r\n", $additionalProductsForCategory['add_products']), array(''));

            $total_add_products_from_category = array(); // Итоговый массив дополнительных товаров, который прибавится к основным товарам
            //Прибавляем все товары из дополнительных категорий НАЧАЛО

            foreach ($additionalProductsForCategory['add_products_from_category'] as $add_products_from_category) {
                if ($add_products_from_category != ''){
                    $data_add_products_from_category = array(
                        'filter_category_id' => intval($add_products_from_category),
                        'sort' => $sort,
                        'order' => $order,
                        //'start'              => ($page - 1) * $limit,
                        //'limit'              => $limit,
                        //'date_filter'        => $days->format('Y-m-d H:i:s')
                    );
                    $result_add_products_from_category = $this->model_catalog_product->getProducts($data_add_products_from_category);
                    $total_add_products_from_category = $total_add_products_from_category + $result_add_products_from_category;
                }
            }
            //Прибавляем все товары из дополнительных категорий КОНЕЦ
            //
            //Отнимаем все товары из дополнительных категорий НАЧАЛО
            foreach ($additionalProductsForCategory['substract_products_from_category'] as $substract_products_from_category) {
                if ($substract_products_from_category != '') {
                    $data_substract_products_from_category = array(
                        'filter_category_id' => intval($substract_products_from_category),
                        'sort' => $sort,
                        'order' => $order,
                        //'start'              => ($page - 1) * $limit,
                        //'limit'              => $limit,
                        //'date_filter'        => $days->format('Y-m-d H:i:s')
                    );
                    $result_substract_products_from_category = $this->model_catalog_product->getProducts($data_substract_products_from_category);
                    $total_add_products_from_category = array_diff_key($total_add_products_from_category, $result_substract_products_from_category);
                }
            }

            //Отнимаем все товары из дополнительных категорий КОНЕЦ
            //
            //Отнимаем все товары НАЧАЛО
            foreach ($additionalProductsForCategory['substract_products'] as $substract_products) {
                if ($substract_products != '') {
                    $result_substract_products[intval($substract_products)] = $this->model_catalog_product->getProduct(intval($substract_products));
                    $total_add_products_from_category = array_diff_key($total_add_products_from_category, $result_substract_products);
                }
            }
            //Отнимаем все товары КОНЕЦ
            // //
            //Прибавляем все товары НАЧАЛО
            foreach ($additionalProductsForCategory['add_products'] as $add_products) {
                if ($add_products != '') {
                    $result = $this->model_catalog_product->getProduct(intval($add_products));
                    if($result){
                        $result_add_products[intval($add_products)] = $result;
                        $total_add_products_from_category = $total_add_products_from_category + $result_add_products;
                    }
                }
            }
            //Прибавляем все товары КОНЕЦ



            $this->load->model('catalog/product');
            $product_total = $this->model_catalog_product->getTotalProducts($data);
            //Returns no filter if products are less than 2
            if($product_total < 1 and count($total_add_products_from_category) < 1) {
                return;
            }


            $data = array('category_id' => $category_id, 'manufacturer_id' => $manufacturer_id);

            $this->load->model('module/filterpro');

            $this->data['manufacturers'] = false;
            if(isset($this->request->get['manufacturer_id'])) {
                $this->data['manufacturer_id'] = $this->request->get['manufacturer_id'];
            } else {
                if($filterpro_setting['display_manufacturer'] != 'none') {
                    $this->data['manufacturers'] = $this->model_module_filterpro->getManufacturers($data);

                    //bmv begin
                    $result_total_add_products = array();
                    if (count($total_add_products_from_category) > 0 ) {
                        foreach ($total_add_products_from_category as $add_products) {

                            $total_add_products_ids[] = $add_products['product_id'];
                        }

                        $additionalData = array('additionalProducts' => $total_add_products_ids, 'manufacturer_id' => $manufacturer_id);
                        unset($total_add_products_ids);
                        $totals_additional_manufacturers = $this->model_module_filterpro->getManufacturers($additionalData);

                        foreach ($this->data['manufacturers'] as $manufacturers) {
                            foreach ($totals_additional_manufacturers as $key=>$additional_manufacturers) {
                            	if ($manufacturers['manufacturer_id'] == $additional_manufacturers['manufacturer_id']){
                            		unset($totals_additional_manufacturers[$key]);
								}
                            }
                        }
                        foreach ($totals_additional_manufacturers as $additional_manufacturers) {
                            $this->data['manufacturers'][count($this->data['manufacturers'])]['manufacturer_id'] = $additional_manufacturers['manufacturer_id'];
                            $this->data['manufacturers'][count($this->data['manufacturers']) - 1]['name'] = $additional_manufacturers['name'];
                        }
                    }

                    //bmv end

                    $this->data['display_manufacturer'] = $filterpro_setting['display_manufacturer'];
                    $this->data['expanded_manufacturer'] = isset($filterpro_setting['expanded_manufacturer']) ? 1 : 0;
                }
            }
            $this->data['options'] = $this->model_module_filterpro->getOptions($data);

            //bmv добавляем опции дополнительных товаров к фильтру начало

            if (count($total_add_products_from_category) > 0 ) {
                foreach ($total_add_products_from_category as $add_products) {
                    if ($add_products['product_id'] != false) {
                        $total_add_products_ids[] = $add_products['product_id'];
                    }
                }
                $additionalData = array('additionalProducts' => $total_add_products_ids, 'manufacturer_id' => $manufacturer_id);
                unset($total_add_products_ids);
                $totals_additional_options = $this->model_module_filterpro->getOptions($additionalData);

                foreach ($this->data['options'] as $key => $options) {
                    foreach ($totals_additional_options as $key2 => $additional_options) {
                        if ($options['option_id'] == $additional_options['option_id']) {
                            foreach ($options['option_values'] as $key3 => $option_values) {
                                foreach ($additional_options['option_values'] as $key4 => $additional_option_values) {
                                    if ($option_values['option_value_id'] == $additional_option_values['option_value_id']) {
                                        unset($totals_additional_options[$key2]['option_values'][$key4]);
                                    }
                                }
                            }
                        }
                        $totals_additional_options[$key2]['option_values']=array_values($totals_additional_options[$key2]['option_values']);
                    }
                }

                foreach ($this->data['options'] as $key => $options) {
                    foreach ($totals_additional_options as $key2 => $additional_options) {
                        if ($options['option_id'] == $additional_options['option_id']) {
                            foreach ($totals_additional_options[$key2]['option_values'] as $additional_option_values) {
                                $this->data['options'][$key]['option_values'][count($this->data['options'][$key]['option_values'])] = $additional_option_values;
                            }
                            unset($totals_additional_options[$key2]);
                        }
                    }
                }
                $this->data['options'] =  $this->data['options'] + $totals_additional_options;
                $this->data['options'] =array_values($this->data['options']);

            }
            //bmv добавляем опции дополнительных товаров к фильтру конец

            $this->load->model('tool/image');
            foreach($this->data['options'] as $i => $option) {
                if(!isset($filterpro_setting['display_option_' . $option['option_id']])) {
                    $filterpro_setting['display_option_' . $option['option_id']] = 'none';
                }
                $display_option = $filterpro_setting['display_option_' . $option['option_id']];
                if($display_option != 'none') {
                    $this->data['options'][$i]['display'] = $display_option;
                    $this->data['options'][$i]['expanded'] = isset($filterpro_setting['expanded_option_' . $option['option_id']]) ? 1 : 0;
                    foreach($this->data['options'][$i]['option_values'] as $j => $option_value) {
                        $this->data['options'][$i]['option_values'][$j]['thumb'] = $this->model_tool_image->resize($this->data['options'][$i]['option_values'][$j]['image'], 20, 20);
                    }
                } else {
                    unset($this->data['options'][$i]);
                }
            }
            $this->data['tags'] = array();
            $version = array_map("intVal", explode(".", VERSION));
            if($version[2] < 4 && $filterpro_setting['display_tags'] != 'none') {
                $this->data['tags'] = $this->model_module_filterpro->getTags($data);
                $this->data['expanded_tags'] = isset($filterpro_setting['expanded_tags']) ? 1 : 0;
            }
            // hide categories
            $this->data['categories'] = false;
            if($filterpro_setting['display_categories'] != 'none') {
                $this->data['categories'] = $this->model_module_filterpro->getSubCategories($data);
                $this->data['expanded_categories'] = isset($filterpro_setting['expanded_categories']) ? 1 : 0;
            }

            $this->data['attributes'] = $this->model_module_filterpro->getAttributes($data);

            //bmv добавляем атрибуты дополнительных товаров к фильтру начало

            if (count($total_add_products_from_category) > 0 ) {
                foreach ($total_add_products_from_category as $add_products) {
                    $total_add_products_ids[] = $add_products['product_id'];
                }
                $additionalData = array('additionalProducts' => $total_add_products_ids, 'manufacturer_id' => $manufacturer_id);
                unset($total_add_products_ids);
                $totals_additional_attributes = $this->model_module_filterpro->getAttributes($additionalData);

                if(isset($this->data['attributes']['1']['attribute_values'])){
                    foreach ($this->data['attributes']['1']['attribute_values'] as $key => $attributes) {
                        foreach ($totals_additional_attributes['1']['attribute_values'] as $key2 => $additional_attributes) {
                            if ($attributes['name'] == $additional_attributes['name']) {
                                foreach ($additional_attributes['values'] as $values) {
                                    $this->data['attributes']['1']['attribute_values'][$key]['values'][count($this->data['attributes']['1']['attribute_values'][$key]['values'])] = $values;
                                }
                                unset($totals_additional_attributes['1']['attribute_values'][$key2]);

                                $this->data['attributes']['1']['attribute_values'][$key]['values'] = array_unique($this->data['attributes']['1']['attribute_values'][$key]['values']);

                            }
                        }
                    }
                }
                if(isset($this->data['attributes']['1']['attribute_values'])) {
                    $this->data['attributes']['1']['attribute_values'] = $this->data['attributes']['1']['attribute_values'] + $totals_additional_attributes['1']['attribute_values'];
                } else {
                    $this->data['attributes'] = $totals_additional_attributes;
                }


            }
            //bmv добавляем атрибуты дополнительных товаров к фильтру конец


            foreach($this->data['attributes'] as $j => $attribute_group) {
                foreach($attribute_group['attribute_values'] as $attribute_id => $attribute) {
                    if(!isset($filterpro_setting['display_attribute_' . $attribute_id])) {
                        $filterpro_setting['display_attribute_' . $attribute_id] = 'none';
                    }
                    $display_attribute = $filterpro_setting['display_attribute_' . $attribute_id];
                    if($display_attribute != 'none') {
                        if($display_attribute == 'slider') {
                            $values = $this->data['attributes'][$j]['attribute_values'][$attribute_id]['values'];
                            $first = $values[0];
                            $this->data['attributes'][$j]['attribute_values'][$attribute_id]['suffix'] = preg_replace("/^[0-9]*/", '', $first);

                            $values = array_map('intVal', $values);
                            $values = array_unique($values);
                            sort($values);
                            $this->data['attributes'][$j]['attribute_values'][$attribute_id]['values'] = $values;
                        }
                        $this->data['attributes'][$j]['attribute_values'][$attribute_id]['display'] = $display_attribute;
                        $this->data['attributes'][$j]['attribute_values'][$attribute_id]['expanded'] = isset($filterpro_setting['expanded_attribute_' . $attribute_id]) ? 1 : 0;
                    } else {
                        unset($this->data['attributes'][$j]['attribute_values'][$attribute_id]);
                        if(!$this->data['attributes'][$j]['attribute_values']) {
                            unset($this->data['attributes'][$j]);
                        }
                    }
                }
            }

            $this->data['price_slider'] = $filterpro_setting['price_slider'];
            $this->data['attr_group'] = $filterpro_setting['attr_group'];

            $this->data['instock_checked'] = isset($filterpro_setting['instock_checked']) ? 1 : 0;
            $this->data['instock_visible'] = isset($filterpro_setting['instock_visible']) ? 1 : 0;

            if($this->data['options'] || $this->data['manufacturers'] || $this->data['attributes'] || $this->data['price_slider']) {
                $this->document->addScript('catalog/view/javascript/jquery/jquery.tmpl.min.js');
                $this->document->addScript('catalog/view/javascript/jquery/jquery.deserialize.min.js');
                $this->document->addScript('catalog/view/javascript/jquery/jquery.loadmask.min.js');
                $this->document->addScript('catalog/view/javascript/filterpro.min.js');
                if (isset($filterpro_setting['theme_mega'])){
                    $this->document->addStyle('catalog/view/theme/default/stylesheet/filterpro-mega.css');
                } else{
                    $this->document->addStyle('catalog/view/theme/default/stylesheet/filterpro.css');
                }
                $this->document->addStyle('catalog/view/theme/default/stylesheet/jquery.loadmask.css');
                if($this->config->get('config_template') == 'shoppica2') {
                    $this->document->addStyle('catalog/view/javascript/jquery/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css');
                }
            }
            $tmp_discounts = explode(',', $filterpro_setting['filterpro_discounts']);
            if ($tmp_discounts && isset($this->request->get['withdiscount'])) {
                foreach ($tmp_discounts as $discount) {
                    if (!is_numeric($discount)) continue;
                    $this->data['discounts'][] = array('value' => (int) $discount);
                }
            } else {
                $this->data['discounts'] = array();
            }
            unset($tmp_discounts);
            $this->data['filterpro_container'] = $filterpro_setting['filterpro_container'];
            $this->data['filterpro_afterload'] = html_entity_decode($filterpro_setting['filterpro_afterload'], ENT_QUOTES, 'UTF-8');

            if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/filterpro.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/module/filterpro.tpl';
            } else {
                $this->template = 'default/template/module/filterpro.tpl';
            }
        }
        $this->render();
    }

	private function array_clean(array $array) {
		foreach($array as $key => $value) {
			if(is_array($value)) {
				$array[$key] = $this->array_clean($value);
				if(!count($array[$key])) {
					unset($array[$key]);
				}
			} elseif(is_string($value)) {
				$value = trim($value);
				if(!$value) {
					unset($array[$key]);
				}
			}
		}
		return $array;
	}

	public function getProducts() {
		$this->language->load('module/filterpro');
		if(VERSION == '1.5.0') {
			$filterpro_setting = unserialize($this->config->get('filterpro_setting'));
		} else {
			$filterpro_setting = $this->config->get('filterpro_setting');
		}

        $neosDebug = new \Neos\debug\Debug;
		if((float)$filterpro_setting['tax'] > 0) {
			$this->k = 1 + (float)$filterpro_setting['tax'] / 100;
		}

		$page = 1;
		if(isset($this->request->post['page'])) {
			$page = (int)$this->request->post['page'];
		}

		if(isset($this->request->post['sort'])) {
			$sort = $this->request->post['sort'];
		} else {
			$sort = 'p.sort_order';
		}

		if(isset($this->request->post['order'])) {
			$order = $this->request->post['order'];
		} else {
			$order = 'DESC';
		}

		if(isset($this->request->post['limit'])) {
			$limit = $this->request->post['limit'];
		} else {
			$limit = $this->config->get('config_catalog_limit');
		}

		$this->load->model('module/filterpro');
		$this->load->model('catalog/product');
		$this->load->model('tool/image');

		$manufacturer = false;
		if(isset($this->request->post['manufacturer'])) {
			$manufacturer = $this->array_clean($this->request->post['manufacturer']);
			if(!count($manufacturer)) {
				$manufacturer = false;
			}
		}
		$manufacturer_id = false;
		if(isset($this->request->post['manufacturer_id'])) {
			$manufacturer_id = $this->request->post['manufacturer_id'];
			$manufacturer = array($manufacturer_id);
		}

		$request_discount = false;
		if(isset($this->request->post['discount'])) {
			$request_discount = (int) $this->request->post['discount'];
		}

		$option_value = false;
		if(isset($this->request->post['option_value'])) {
			$option_value = $this->array_clean($this->request->post['option_value']);
			if(!count($option_value)) {
				$option_value = false;
			}
		}

		$attribute_value = array();
		if(isset($this->request->post['attribute_value'])) {
			$attribute_value = $this->array_clean($this->request->post['attribute_value']);
			if(!count($attribute_value)) {
				$attribute_value = array();
			}
		}

		$instock = false;
		if(isset($this->request->post['instock'])) {
			$instock = true;
		}

		$tags = false;
		if(isset($this->request->post['tags'])) {
			$tags = $this->array_clean($this->request->post['tags']);
			if(!count($tags)) {
				$tags = false;
			}
		}

		$categories = false;
		if(isset($this->request->post['categories'])) {
			$categories = $this->array_clean($this->request->post['categories']);
			if(!count($categories)) {
				$categories = false;
			}
		}

		$category_id = 0;
		if(isset($this->request->post['category_id'])) {
			$category_id = $this->request->post['category_id'];
		}
		$request_discount = 0;
		if(isset($this->request->post['discount'])) {
			$request_discount = (int) $this->request->post['discount'];
		}

		if(!$categories && $category_id) {
			$categories = array($category_id);
		}

		$attr_slider = isset($this->request->post['attr_slider']) ? $this->request->post['attr_slider'] : false;
	//	if ($category_id==0) {$limit=9999999999;}

		//if ($category_id==7608) {$sort='p.product_id';$order='ASC';}
		if ($category_id == "7608") { $sort = 'p.date_modified'; $order = 'DESC';}
		//if ($category_id == "7608") { $sort = 'p.price'; $order = 'DESC';}
		//if ($category_id == "10951") { $sort = 'p.price'; $order = 'DESC';}
		if ($category_id == "7611") { $sort = 'p.date_modified'; $order = 'DESC';}
		if ($category_id == "1163") { $sort = 'p.price'; $order = 'DESC';}
		//if ($category_id == "3") { $sort = 'p.sku'; $order = 'ASC';}
		if ($category_id == "10013408" or $category_id == "10013409" or $category_id == "10013410" or $category_id == "10013411") { $sort = 'p.sku'; $order = 'ASC';}


        //BMV additional products BEGIN

        $this->load->model('catalog/category');

        $this->load->model('catalog/product');
		
		$сategoryDesign = $this->model_catalog_category->getCategoryDesign($category_id);
        if (isset($сategoryDesign['category_sticker_image']) and file_exists(DIR_IMAGE . $сategoryDesign['category_sticker_image']) and $сategoryDesign['category_sticker_image'] != "") {
            $this->data['category_sticker_image'] = $сategoryDesign['category_sticker_image'];
        }

        $additionalProductsForCategory = $this->model_catalog_category->getAdditionalProductsForCategory($category_id);
        $additionalProductsForCategory['add_products_from_category'] = explode(',', $additionalProductsForCategory['add_products_from_category']);
        $additionalProductsForCategory['substract_products_from_category'] = explode(',', $additionalProductsForCategory['substract_products_from_category']);
        $additionalProductsForCategory['substract_products'] = explode(',', $additionalProductsForCategory['substract_products']);
        $additionalProductsForCategory['add_products'] = array_diff(explode("\r\n", $additionalProductsForCategory['add_products']), array(''));

        $total_add_products_from_category = array(); // Итоговый массив дополнительных товаров, который прибавится к основным товарам
        //Прибавляем все товары из дополнительных категорий НАЧАЛО

        foreach ($additionalProductsForCategory['add_products_from_category'] as $add_products_from_category) {
            if ($add_products_from_category != ''){
                $data_add_products_from_category = array(
                    'filter_category_id' => intval($add_products_from_category),
                    'sort' => $sort,
                    'order' => $order,
                    //'start'              => ($page - 1) * $limit,
                    //'limit'              => $limit,
                    //'date_filter'        => $days->format('Y-m-d H:i:s')
                );
                $result_add_products_from_category = $this->model_catalog_product->getProducts($data_add_products_from_category);
                $total_add_products_from_category = $total_add_products_from_category + $result_add_products_from_category;
            }
        }
        //Прибавляем все товары из дополнительных категорий КОНЕЦ
        //
        //Отнимаем все товары из дополнительных категорий НАЧАЛО
        foreach ($additionalProductsForCategory['substract_products_from_category'] as $substract_products_from_category) {
            if ($substract_products_from_category != '') {
                $data_substract_products_from_category = array(
                    'filter_category_id' => intval($substract_products_from_category),
                    'sort' => $sort,
                    'order' => $order,
                    //'start'              => ($page - 1) * $limit,
                    //'limit'              => $limit,
                    //'date_filter'        => $days->format('Y-m-d H:i:s')
                );
                $result_substract_products_from_category = $this->model_catalog_product->getProducts($data_substract_products_from_category);
                $total_add_products_from_category = array_diff_key($total_add_products_from_category, $result_substract_products_from_category);
            }
        }

        //Отнимаем все товары из дополнительных категорий КОНЕЦ
        //
        //Отнимаем все товары НАЧАЛО
        foreach ($additionalProductsForCategory['substract_products'] as $substract_products) {
            if ($substract_products != '') {
                $result_substract_products[intval($substract_products)] = $this->model_catalog_product->getProduct(intval($substract_products));
                $total_add_products_from_category = array_diff_key($total_add_products_from_category, $result_substract_products);
            }
        }
        //Отнимаем все товары КОНЕЦ
        // //
        //Прибавляем все товары НАЧАЛО
        foreach ($additionalProductsForCategory['add_products'] as $add_products) {
            if ($add_products != '') {
                $result = $this->model_catalog_product->getProduct(intval($add_products));
                if ($result){
                    $result_add_products[intval($add_products)]  = $result;
                    $total_add_products_from_category = $total_add_products_from_category + $result_add_products;
                }
            }
        }
        //Прибавляем все товары КОНЕЦ
        //

        //Дополнительные товары фильтруем через FilterPro НАЧАЛО
        $result_total_add_products = array();
        if (count($total_add_products_from_category) > 0 ) {
            foreach ($total_add_products_from_category as $add_products) {
                $total_add_products_ids[] = $add_products['product_id'];
            }
		} else {
            $total_add_products_ids[] = 0;
		}

			$datalimit = $limit;
            $data_total_add_products = array(
                'instock' => $instock,
                'option_value' => $option_value,
                'manufacturer' => $manufacturer,
                'attribute_value' => $attribute_value,
                'tags' => $tags,
                'additionalProducts' => $total_add_products_ids,
                'attr_slider' => $attr_slider,
                'min_price' => $this->request->post['min_price'] / $this->k,
                'max_price' => $this->request->post['max_price'] / $this->k,
                'start' => ($page - 1) * $limit,
                'limit' => $datalimit,
                'sort' => $sort,
                'order' => $order,
                'f_category_id' => $category_id,
                'discount' => $request_discount,
                'withdiscount' => isset($this->request->get['withdiscount']),
            );


            $result_total_add_products = $this->model_module_filterpro->getAdditionalProducts($data_total_add_products);
			
			$result_total_add_products_tmp = array();
			foreach ($total_add_products_ids as $key){
                if (array_key_exists($key, $result_total_add_products)) {
                    $result_total_add_products_tmp[$key] = $result_total_add_products[$key];
                }
			}
			$result_total_add_products = $result_total_add_products_tmp;

            //Дополнительные товары фильтруем через FilterPro КОНЕЦ

            
            $total_add_products = array();
            if ($page == 0) {
                $pg = 1;
            } else {
                $pg = $page;
            }
            if (count($result_total_add_products) > ($pg) * $limit) {
                $datalimit = 0;
                $i = ($pg - 1) * $limit;
                foreach ($result_total_add_products as $key => $total_add_product) {
                    $total_add_products[$key] = $total_add_product;
                    $i++;
                    if ($i == $pg * $limit) {
                        break;
                    }
                }
            } else if ((count($result_total_add_products) + $limit) - ($pg * $limit) > 0) {
                $i = ($pg - 1) * $limit;
                $j = 0;
                foreach ($result_total_add_products as $key => $total_add_product) {
                    if ($j == $i) {
                        $total_add_products[$key] = $total_add_product;
                        $i++;
                    }
                    $j++;
                    if ($j == $pg * $limit or $j == count($result_total_add_products)) {
                        $datalimit = $pg * $limit - $j;
                        break;
                    }
                }
            }


            //BMV additional products END

            //рассчитываем c какого основного товара делать выборку в зависимости от дополнительных
            if (count($result_total_add_products) > 0) {
                $curStart = ($page - 1) * $limit - count($result_total_add_products);
            } else {
                $data_curStart_total_add_products = array(
                    'instock' => $instock,
                    'option_value' => $option_value,
                    'manufacturer' => $manufacturer,
                    'attribute_value' => $attribute_value,
                    'tags' => $tags,
                    'additionalProducts' => $total_add_products_ids,
                    'attr_slider' => $attr_slider,
                    'min_price' => $this->request->post['min_price'] / $this->k,
                    'max_price' => $this->request->post['max_price'] / $this->k,
                    'start' => 0,
                    'limit' => 99999999999999,
                    'sort' => $sort,
                    'order' => $order,
                    'f_category_id' => $category_id,
                    'discount' => $request_discount,
                    'withdiscount' => isset($this->request->get['withdiscount']),
                );

                $curStart = ($page - 1) * $limit - bcmod($this->model_module_filterpro->getCountAllAdditionalProducts($data_curStart_total_add_products), $limit);
            }
        /*} else {
            $curStart = ($page - 1) * $limit;
            $datalimit = $limit;
        }*/



        $data = array(
            'instock' => $instock,
            'option_value' => $option_value,
            'manufacturer' => $manufacturer,
            'attribute_value' => $attribute_value,
            'tags' => $tags,
			'categories' => $categories,
			'filter' => isset($this->request->post['filter']) ? array_map('intval', explode(',', $this->request->post['filter'])) : false,
            'attr_slider' => $attr_slider,
            'min_price' => $this->request->post['min_price'] / $this->k,
            'max_price' => $this->request->post['max_price'] / $this->k,
            'start' => $curStart ,
            'limit' => $datalimit,
            'sort' => $sort,
            'order' => $order,
            'f_category_id' => $category_id,
            'discount' => $request_discount,
            'withdiscount' => isset($this->request->get['withdiscount']),
        );

        //Cache <!-- start -- >
        if (isset($this->request->post['getPriceLimits'])) {
            $price_limit = '1';
        } else {
            $price_limit = '';
        }
        if (isset($this->request->get['novently'])) {
            $novently = '1';
        } else {
            $novently = '';
        }
        $mc_request = __DIR__.'/../../../filter_cache/'; 
        $mc_request .= md5($price_limit . $novently . $_SERVER['QUERY_STRING'] . $this->customer->getCustomerGroupId() . json_encode($data)) . '.json';

        if (file_exists($mc_request) && (filemtime($mc_request) > (time() - 60 * 60 ))) {
            $mc_response = file_get_contents($mc_request);
            $mc_response = json_decode($mc_response, true);
            $neosDebug->addBreakPoint('After Cache Load');
            $mc_response['timing'] = $neosDebug->getTiming();
            $mc_response = json_encode($mc_response);
            $this->response->setOutput($mc_response);
            return;
        } elseif(file_exists($mc_request)) {
            unlink($mc_request);
        }
        //Cache <!-- end -- >
        
		if(isset($this->request->post['manufacturer_id']) || ($filterpro_setting['display_manufacturer'] == 'none')) {
			$totals_manufacturers = false;
		} else {
            $totals_manufacturers = $this->model_module_filterpro->getTotalManufacturers($data);
            $totals_additional_manufacturers = $this->model_module_filterpro->getTotalManufacturers($data_total_add_products);

            foreach ($totals_manufacturers as $key=>$manufacturers){
                foreach($totals_additional_manufacturers as $key2=>$additional_manufacturers){
                    if ($manufacturers['id'] == $additional_manufacturers['id']){
                        $totals_manufacturers[$key]['t'] = $totals_manufacturers[$key]['t'] +$additional_manufacturers['t'];
                        unset($totals_additional_manufacturers[$key2]);
                    }
                }
            }
            foreach ($totals_additional_manufacturers as $additional_manufacturers) {
                $totals_manufacturers[count($totals_manufacturers)]['id'] = $additional_manufacturers['id'];
                $totals_manufacturers[count($totals_manufacturers)-1]['t'] = $additional_manufacturers['t'];
            }
        }
        $neosDebug->addBreakPoint('Before Total Options Load');
		$totals_options = $this->model_module_filterpro->getTotalOptions($data);
        $totals_additional_options = $this->model_module_filterpro->getTotalOptions($data_total_add_products);
        foreach ($totals_options as $key=>$options){
            foreach($totals_additional_options as $key2=>$additional_options){
                if ($options['id'] == $additional_options['id']){
                    $totals_options[$key]['t'] = $totals_options[$key]['t'] +$additional_options['t'];
                    unset($totals_additional_options[$key2]);
                }
            }
        }
        foreach ($totals_additional_options as $additional_options) {
            $totals_options[count($totals_options)]['id'] = $additional_options['id'];
            $totals_options[count($totals_options)-1]['t'] = $additional_options['t'];
        }

        $neosDebug->addBreakPoint('After Total Options Load');
        $neosDebug->addBreakPoint('Before Total Attr Load');
		$totals_attributes = $this->model_module_filterpro->getTotalAttributes($data);
        $totals_additional_attributes = $this->model_module_filterpro->getTotalAttributes($data_total_add_products);
        foreach ($totals_attributes as $key=>$attributes){
            foreach($totals_additional_attributes as $key2=>$additional_attributes){
                if (($attributes['id'] == $additional_attributes['id']) and ($attributes['text'] == $additional_attributes['text'])){
                    $totals_attributes[$key]['t'] = $totals_attributes[$key]['t'] +$additional_attributes['t'];
                    unset($totals_additional_attributes[$key2]);
                }
            }
        }
        foreach ($totals_additional_attributes as $additional_attributes) {
            $totals_attributes[count($totals_attributes)]['id'] = $additional_attributes['id'];
            $totals_attributes[count($totals_attributes)-1]['t'] = $additional_attributes['t'];
            $totals_attributes[count($totals_attributes)-1]['text'] = $additional_attributes['text'];
        }

        foreach($attribute_value as $attribute_id => $values) {
            foreach($totals_attributes as $i => $attribute) {
                if($attribute['id'] == $attribute_id) {
                    unset($totals_attributes[$i]);
                }
            }

            $temp_data = $data;
            unset($temp_data['attribute_value'][$attribute_id]);
            foreach($this->model_module_filterpro->getTotalAttributes($temp_data) as $attribute){
                if($attribute['id'] == $attribute_id) {
                    $totals_attributes[] = $attribute;
                }
            }
        }
        $neosDebug->addBreakPoint('After Total Attr Load');

		$version = array_map("intVal", explode(".", VERSION));
		if($version[2] >= 4) {
			$totals_tags = array();
		} else {
            $totals_tags = $this->model_module_filterpro->getTotalTags($data);
            $totals_additional_tags = $this->model_module_filterpro->getTotalTags($data_total_add_products);
            foreach ($totals_tags as $key=>$tags){
                foreach($totals_additional_tags as $key2=>$additional_tags){
                    if ($tags['id'] == $additional_tags['id']){
                        $totals_tags[$key]['t'] = $totals_tags[$key]['t'] +$additional_tags['t'];
                        unset($totals_additional_tags[$key2]);
                    }
                }
            }
            foreach ($totals_additional_tags as $additional_tags) {
                $totals_tags[count($totals_tags)]['id'] = $additional_tags['id'];
                $totals_tags[count($totals_tags)-1]['t'] = $additional_tags['t'];
            }

            //	$totals_tags = $this->model_module_filterpro->getTags($data);
        }

		$totals_categories = $this->model_module_filterpro->getTotalCategories($data, $category_id);
	//	$totals_categories = $this->model_module_filterpro->getSubCategories($data, $category_id);

        $neosDebug->addBreakPoint('Before Get Products Load');
		//Return array where 0 - products, 1 - sql



        //Дополняем обычные товары добавленными через админку в категории

        $results =  $this->model_module_filterpro->getProducts($data);

        $resultsTmp = $results[0];
        $results[0] = $result_total_add_products + $resultsTmp ;


        //$results = $this->model_module_filterpro->getProducts($data);

        $neosDebug->addBreakPoint('After Get Products Load');

		$min_price = false;
		$max_price = false;
        
        $neosDebug->addBreakPoint('Before Price Limits Load');
		if(isset($this->request->post['getPriceLimits']) && $this->request->post['getPriceLimits']) {
			$priceLimits = $this->model_module_filterpro->getPriceLimits(array('category_id' => $category_id, 'manufacturer_id' => $manufacturer_id));
			$priceLimitsAdditionalProducts = $this->model_module_filterpro->getPriceLimits(array('additionalProducts' => $total_add_products_ids));

			$min_price = min($priceLimits['min_price'], $priceLimitsAdditionalProducts['min_price']);
			$max_price = max($priceLimits['max_price'], $priceLimitsAdditionalProducts['max_price']);
			
		}
        $neosDebug->addBreakPoint('After Price Limits Load');
		$this->request->get['path'] = isset($this->request->post['path']) ? $this->request->post['path'] : '';

		//$product_total = $this->model_module_filterpro->getTotalProducts($data);
        $neosDebug->addBreakPoint('Before Count Poducts Load');
		//К количеству товаров добавленных обычным способом плюсуем дополнительные товары указанные в админке у категории
        if(count($total_add_products_ids)>0) {
            $product_total = $this->model_module_filterpro->getCountAllAdditionalProducts($data_total_add_products) + $this->model_module_filterpro->getCountAllProducts($data);
        }else{
            $product_total = $this->model_module_filterpro->getCountAllProducts($data);
        }
        //$product_total = $this->model_module_filterpro->getCountAllProducts($data);
        $neosDebug->addBreakPoint('After Count Poducts Load');

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->text = $this->language->get('text_pagination');
		if ($this->request->get['novently']) {
			$pag_insert = '&noventy=1';
		} elseif ($this->request->get['withdiscount']) {
			$pag_insert = '&withdiscount=1';
		} else {
			$pag_insert = "";
		}
		$pagination->url = $this->url->link('product/category' . $pag_insert, 'path=' . $this->request->get['path'] . '&page={page}');


		$min_price = $this->currency->convert($min_price * $this->k, $this->config->get('config_currency'), $this->currency->getCode());
		$max_price = $this->currency->convert($max_price * $this->k, $this->config->get('config_currency'), $this->currency->getCode());


		$result_html = $this->getHtmlProducts($results[0], $product_total);

		$json = json_encode(array('result_html' => $result_html, 'min_price' => $min_price, 'max_price' => $max_price, 'pagination' => $pagination->render(),
								 'totals_data' => array('manufacturers' => $totals_manufacturers,
														'options' => $totals_options,
														'attributes' => $totals_attributes,
														'categories' => $totals_categories,
														'tags' => $totals_tags),
                                   'timing' => $neosDebug->getTiming(),
                                   'debug' => $neosDebug->getDebug()));												
   		file_put_contents($mc_request, $json, LOCK_EX);
		$this->response->setOutput($json);
	}

	private function getHtmlProducts($results, $product_total) {
		//return '';
		$this->language->load('product/category');
		$this->data['text_refine'] = $this->language->get('text_refine');
		$this->data['text_empty'] = $this->language->get('text_empty');
		$this->data['text_quantity'] = $this->language->get('text_quantity');
		$this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$this->data['text_model'] = $this->language->get('text_model');
		$this->data['text_price'] = $this->language->get('text_price');
		$this->data['text_tax'] = $this->language->get('text_tax');
		$this->data['text_points'] = $this->language->get('text_points');
		$this->data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
		$this->data['text_display'] = $this->language->get('text_display');
		$this->data['text_list'] = $this->language->get('text_list');
		$this->data['text_grid'] = $this->language->get('text_grid');
		$this->data['text_sort'] = $this->language->get('text_sort');
		$this->data['text_limit'] = $this->language->get('text_limit');

		$this->data['button_cart'] = $this->language->get('button_cart');
		$this->data['button_wishlist'] = $this->language->get('button_wishlist');
		$this->data['button_compare'] = $this->language->get('button_compare');
		$this->data['button_continue'] = $this->language->get('button_continue');

		$this->data['products'] = array();

		$this->load->model('setting/setting');
        $promo_settings = $this->model_setting_setting->getSetting('promo_settings');
		
		$promo_settings['total_discount_products'] = str_replace(" ", "", $promo_settings['total_discount_products']);
        $total_discount_products = array_diff(explode("\r\n", $promo_settings['total_discount_products']), array(''));
		
		foreach ($results as $result) {
			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
			} else {
				$image = false;
			}

			//Дополнительное фото

				$show_additional_images = 0; if ($show_additional_images == 1) {
					$additional_photos = $this->model_catalog_product->getProductImages($result['product_id']);
					$additional_photos[] = "";

					foreach ($additional_photos as $additional_photo) {
						if(isset($additional_photo['image'])){
							$image2 = $this->model_tool_image->resize($additional_photo['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
								break;
						} //else {
							//$image2 = $image;
						//}
					}
				} else {
							$image2 = $image;
						}

				//Дополнительное фото Конец


			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$price = false;
			}

			if ((float)$result['special']) {
				$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$special = false; 
			}
			
			if (($promo_settings['total_discount_status'] && $this->customer->getCustomerGroupId() < 2) || ($this->customer->getId() == $promo_settings['total_discount_test_user'] && $this->customer->getId() != "")) {
				
				$category_sticker_image = '';
				
                if (!empty($total_discount_products)) {
                    if (in_array($result['product_id'], $total_discount_products)) {
						$special = $this->currency->format(round(($result['price'] - $result['price']/100*$promo_settings['total_discount_conditions'])/10)*10);
						
						$сategoryDesign = $this->model_catalog_category->getCategoryDesign('1163');
                        if (isset($сategoryDesign['category_sticker_image']) and file_exists(DIR_IMAGE . $сategoryDesign['category_sticker_image']) and $сategoryDesign['category_sticker_image'] != "") {
                            $category_sticker_image = $сategoryDesign['category_sticker_image'];
                        }
                    }
                } else{
                    $special = $this->currency->format(round(($result['price'] - $result['price']/100*$promo_settings['total_discount_conditions'])/10)*10);
					
					$сategoryDesign = $this->model_catalog_category->getCategoryDesign('1163');
                        if (isset($сategoryDesign['category_sticker_image']) and file_exists(DIR_IMAGE . $сategoryDesign['category_sticker_image']) and $сategoryDesign['category_sticker_image'] != "") {
                            $category_sticker_image = $сategoryDesign['category_sticker_image'];
                        }
                }
            }
			
			
			//BMV "Настоящие" цены очков Begin
            $glasses_price = array(
                '13043' =>	11500,
                '13037' =>	35000,
                '13036' =>	35000,
                '13035' =>	31000,
                '13034' =>	32000,
                '13038' =>	20500,
                '13033' =>	25000,
                '13032' =>	25000,
                '13031' =>	25000,
                '13030' =>	25000,
                '13008' =>	15600,
                '13005' =>	15600,
                '13001' =>	15600,
                '12999' =>	15600,
                '12998' =>	15600,
                '13026' =>	67000,
                '12978' =>	16000,
                '12995' =>	15000,
                '12983' =>	19000,
                '13045' =>	10500,
                '13057' =>	12000,
                '13056' =>	12000,
                '13055' =>	12000,
                '13044' =>	11000,
                '13039' =>	29000,
                '13049' =>	11000,
                '13048' =>	11000,
                '13046' =>	11000,
                '13047' =>	11000,
                '12985' =>	23500,
                '12988' =>	23500,
                '12992' =>	23500,
                '12994' =>	23500,
                '13042' =>	11000,
                '13029' =>	19000,
                '13027' =>	15000,
                '13028' =>	10000,
                '13011' =>	13000,
                '13009' =>	23000,
                '12968' =>	16000,
                '12997' =>	18000,
                '12973' =>	19000,
                '12976' =>	19000,
                '12981' =>	19000,
                '12980' =>	19000,
                '13007' =>	16000,
                '12982' =>	19000
            );

            if ( isset($glasses_price[$result['product_id']])){

                //$price = $this->currency->format($glasses_price[$result['product_id']]);

                //$special = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));

            }

            //BMV "Настоящие" цены очков End
			
			//BMV акция ZX 750 Begin
                /*$ZX750NewPrice = array(
					'13017' =>	1600, 
					'13023' =>	1600, 
					'13018' =>	1600, 
					'13021' =>	1600, 
					'13025' =>	1600, 
					'13024' =>	1600, 
					'13022' =>	1600, 
					'13020' =>	1600, 
					'13019' =>	1600 
                   
				);

				if ( isset($ZX750NewPrice[$result['product_id']])){

                    $price =$this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));					

                    $special = $this->currency->format($ZX750NewPrice[$result['product_id']]);

				}*/

                //BMV акция ZX 750 End
				
				//BMV "20% fake discount" Begin
            $fakeProcentsDiscounts = array(
				'1861' => 20,
				'3372' => 20,
				'3908' => 20,
				'4567' => 20,
				'4920' => 20,
				'5611' => 20,
				'6028' => 20,
				'6118' => 20,
				'6120' => 20,
				'6833' => 20,
				'8225' => 20,
				'8250' => 20,
				'8251' => 20,
				'8252' => 20,
				'8283' => 20,
				'8497' => 20,
				'8625' => 20,
				'8630' => 20,
				'8635' => 20,
				'8710' => 20,
				'8719' => 20,
				'8727' => 20,
				'8734' => 20,
				'8800' => 20,
				'8895' => 20,
				'8925' => 20,
				'9062' => 20,
				'9068' => 20,
				'9069' => 20,
				'9146' => 20,
				'9156' => 20,
				'9158' => 20,
				'9159' => 20,
				'9162' => 20,
				'9219' => 20,
				'9319' => 20,
				'9325' => 20,
				'9419' => 20,
				'9537' => 20,
				'9538' => 20,
				'9610' => 20,
				'9667' => 20,
				'9669' => 20,
				'9682' => 20,
				'9685' => 20,
				'9687' => 20,
				'9876' => 20,
				'9891' => 20,
				'9961' => 20,
				'10696' => 20,
				'10697' => 20,
				'11283' => 20,
				'11346' => 20,
				'11465' => 20,
				'11466' => 20,
				'11467' => 20,
				'11468' => 20,
				'11469' => 20,
				'11470' => 20,
				'11471' => 20,
				'11617' => 20,
				'11691' => 20,
				'11697' => 20,
				'11755' => 20,
				'11845' => 20,
				'11866' => 20,
				'11873' => 20,
				'11874' => 20,
				'11877' => 20,
				'11881' => 20,
				'12061' => 20,
				'12069' => 20,
				'12071' => 20,
				'12121' => 20,
				'12164' => 20,
				'12381' => 20,
				'12382' => 20,
				'12441' => 20,
				'12463' => 20,
				'12465' => 20,
				'12956' => 20,
				
				'288'	=> 20,
				'312'	=> 20,
				'448'	=> 20,
				'591'	=> 20,
				'682'	=> 20,
				'769'	=> 20,
				'915'	=> 20,
				'1084'	=> 20,
				'1103'	=> 20,
				'1160'	=> 20,
				'1513'	=> 20,
				'2124'	=> 20,
				'2156'	=> 20,
				'2208'	=> 20,
				'2293'	=> 20,
				'2573'	=> 20,
				'2675'	=> 20,
				'2689'	=> 20,
				'2761'	=> 20,
				'2765'	=> 20,
				'3020'	=> 20,
				'3025'	=> 20,
				'3028'	=> 20,
				'3030'	=> 20,
				'3141'	=> 20,
				'3150'	=> 20,
				'3305'	=> 20,
				'3346'	=> 20,
				'3609'	=> 20,
				'3995'	=> 20,
				'4109'	=> 20,
				'4151'	=> 20,
				'4182'	=> 20,
				'4200'	=> 20,
				'4232'	=> 20,
				'4240'	=> 20,
				'4293'	=> 20,
				'4325'	=> 20,
				'4469'	=> 20,
				'4526'	=> 20,
				'4657'	=> 20,
				'4677'	=> 20,
				'4740'	=> 20,
				'4804'	=> 20,
				'4816'	=> 20,
				'4888'	=> 20,
				'5064'	=> 20,
				'5104'	=> 20,
				'5188'	=> 20,
				'5204'	=> 20,
				'5205'	=> 20,
				'5272'	=> 20,
				'5284'	=> 20,
				'5307'	=> 20,
				'5364'	=> 20,
				'5386'	=> 20,
				'5543'	=> 20,
				'5675'	=> 20,
				'5684'	=> 20,
				'5943'	=> 20,
				'5960'	=> 20,
				'6056'	=> 20,
				'6057'	=> 20,
				'6064'	=> 20,
				'6065'	=> 20,
				'6091'	=> 20,
				'6092'	=> 20,
				'6116'	=> 20,
				'6139'	=> 20,
				'6140'	=> 20,
				'6144'	=> 20,
				'6159'	=> 20,
				'6163'	=> 20,
				'6171'	=> 20,
				'6331'	=> 20,
				'6344'	=> 20,
				'6432'	=> 20,
				'6434'	=> 20,
				'6437'	=> 20,
				'6490'	=> 20,
				'6528'	=> 20,
				'6536'	=> 20,
				'6552'	=> 20,
				'6583'	=> 20,
				'6591'	=> 20,
				'6592'	=> 20,
				'6618'	=> 20,
				'6624'	=> 20,
				'6627'	=> 20,
				'6636'	=> 20,
				'6641'	=> 20,
				'6647'	=> 20,
				'6649'	=> 20,
				'6650'	=> 20,
				'6680'	=> 20,
				'6683'	=> 20,
				'6694'	=> 20,
				'6706'	=> 20,
				'6871'	=> 20,
				'6872'	=> 20,
				'6886'	=> 20,
				'6926'	=> 20,
				'6927'	=> 20,
				'6989'	=> 20,
				'6996'	=> 20,
				'6998'	=> 20,
				'7003'	=> 20,
				'7008'	=> 20,
				'7009'	=> 20,
				'7011'	=> 20,
				'7012'	=> 20,
				'7013'	=> 20,
				'7070'	=> 20,
				'7071'	=> 20,
				'7078'	=> 20,
				'7089'	=> 20,
				'7107'	=> 20,
				'7111'	=> 20,
				'7120'	=> 20,
				'7121'	=> 20,
				'7168'	=> 20,
				'7251'	=> 20,
				'7256'	=> 20,
				'7260'	=> 20,
				'7265'	=> 20,
				'7266'	=> 20,
				'7267'	=> 20,
				'7271'	=> 20,
				'7456'	=> 20,
				'7587'	=> 20,
				'7664'	=> 20,
				'7665'	=> 20,
				'7672'	=> 20,
				'7676'	=> 20,
				'7760'	=> 20,
				'7763'	=> 20,
				'7770'	=> 20,
				'7772'	=> 20,
				'7776'	=> 20,
				'7791'	=> 20,
				'7804'	=> 20,
				'7816'	=> 20,
				'7845'	=> 20,
				'7854'	=> 20,
				'7954'	=> 20,
				'7975'	=> 20,
				'7992'	=> 20,
				'8063'	=> 20,
				'8105'	=> 20,
				'8109'	=> 20,
				'8112'	=> 20,
				'8272'	=> 20,
				'8293'	=> 20,
				'8294'	=> 20,
				'8295'	=> 20,
				'8303'	=> 20,
				'8309'	=> 20,
				'8326'	=> 20,
				'8337'	=> 20,
				'8345'	=> 20,
				'8348'	=> 20,
				'8360'	=> 20,
				'8361'	=> 20,
				'8362'	=> 20,
				'8364'	=> 20,
				'8367'	=> 20,
				'8432'	=> 20,
				'8433'	=> 20,
				'8451'	=> 20,
				'8453'	=> 20,
				'8534'	=> 20,
				'8545'	=> 20,
				'8546'	=> 20,
				'8549'	=> 20,
				'8550'	=> 20,
				'8559'	=> 20,
				'8560'	=> 20,
				'8561'	=> 20,
				'8566'	=> 20,
				'8567'	=> 20,
				'8572'	=> 20,
				'8695'	=> 20,
				'8699'	=> 20,
				'8766'	=> 20,
				'8771'	=> 20,
				'8790'	=> 20,
				'8793'	=> 20,
				'8834'	=> 20,
				'8881'	=> 20,
				'8883'	=> 20,
				'8884'	=> 20,
				'9043'	=> 20,
				'9045'	=> 20,
				'9257'	=> 20,
				'9260'	=> 20,
				'9270'	=> 20,
				'9280'	=> 20,
				'9289'	=> 20,
				'9304'	=> 20,
				'9307'	=> 20,
				'9308'	=> 20,
				'9352'	=> 20,
				'9353'	=> 20,
				'9357'	=> 20,
				'9358'	=> 20,
				'9361'	=> 20,
				'9377'	=> 20,
				'9424'	=> 20,
				'9426'	=> 20,
				'9454'	=> 20,
				'9463'	=> 20,
				'9465'	=> 20,
				'9479'	=> 20,
				'9488'	=> 20,
				'9570'	=> 20,
				'9703'	=> 20,
				'9713'	=> 20,
				'9727'	=> 20,
				'9729'	=> 20,
				'9816'	=> 20,
				'9819'	=> 20,
				'9826'	=> 20,
				'9828'	=> 20,
				'9836'	=> 20,
				'9840'	=> 20,
				'9896'	=> 20,
				'9898'	=> 20,
				'9924'	=> 20,
				'9990'	=> 20,
				'9995'	=> 20,
				'10200'	=> 20,
				'10204'	=> 20,
				'10251'	=> 20,
				'10260'	=> 20,
				'10308'	=> 20,
				'10321'	=> 20,
				'10333'	=> 20,
				'10342'	=> 20,
				'10351'	=> 20,
				'10356'	=> 20,
				'10372'	=> 20,
				'10452'	=> 20,
				'10455'	=> 20,
				'10473'	=> 20,
				'10480'	=> 20,
				'10481'	=> 20,
				'10485'	=> 20,
				'10492'	=> 20,
				'10497'	=> 20,
				'10499'	=> 20,
				'10500'	=> 20,
				'10510'	=> 20,
				'10513'	=> 20,
				'10520'	=> 20,
				'10537'	=> 20,
				'10595'	=> 20,
				'10596'	=> 20,
				'10661'	=> 20,
				'10662'	=> 20,
				'10699'	=> 20,
				'10757'	=> 20,
				'10770'	=> 20,
				'10777'	=> 20,
				'10816'	=> 20,
				'10994'	=> 20,
				'11008'	=> 20,
				'11025'	=> 20,
				'11062'	=> 20,
				'11224'	=> 20,
				'11439'	=> 20,
				'11457'	=> 20,
				'11458'	=> 20,
				'11494'	=> 20,
				'11589'	=> 20,
				'11599'	=> 20,
				'11703'	=> 20,
				'11781'	=> 20,
				'11870'	=> 20,
				'12062'	=> 20,
				'12087'	=> 20,
				'12100'	=> 20,
				'12405'	=> 20,
				'12537'	=> 20,
				'12538'	=> 20,
				'12828'	=> 20,
				'8802' => 30,
				'3451' => 30,
				'3525' => 30,
				'4567' => 30,
				'4920' => 30,
				'5375' => 30,
				'5594' => 30,
				'5708' => 30,
				'6321' => 30,
				'7020' => 30,
				'7022' => 30,
				'8250' => 30,
				'8301' => 30,
				'8401' => 30,
				'8416' => 30,
				'8628' => 30,
				'8629' => 30,
				'8693' => 30,
				'8713' => 30,
				'8801' => 30,
				'8803' => 30,
				'8804' => 30,
				'8887' => 30,
				'8890' => 30,
				'9052' => 30,
				'9066' => 30,
				'9071' => 30,
				'9146' => 30,
				'9163' => 30,
				'9218' => 30,
				'9247' => 30,
				'9251' => 30,
				'9290' => 30,
				'9296' => 30,
				'9297' => 30,
				'9300' => 30,
				'9322' => 30,
				'9440' => 30,
				'9560' => 30,
				'9681' => 30,
				'9686' => 30,
				'9880' => 30,
				'11341' => 30,
				'11342' => 30,
				'11343' => 30,
				'11345' => 30,
				'11562' => 30,
				'11711' => 30,
				'11714' => 30,
				'11756' => 30,
				'11758' => 30,
				'11845' => 30,
				'11846' => 30,
				'11875' => 30,
				'11876' => 30,
				'12063' => 30,
				'12070' => 30,
				'12076' => 30,
				'12106' => 30,
				'12122' => 30,
				'12123' => 30,
				'12165' => 30,
				'12247' => 30,
				'12253' => 30,
				'12287' => 30,
				'12361' => 30,
				'12383' => 30,
				'12399' => 30,
				'12468' => 30,
				'12471' => 30,
				'12560' => 30,
				
				'297'	 => 30,
				'398'	 => 30,
				'548'	 => 30,
				'780'	 => 30,
				'1366'	 => 30,
				'2769'	 => 30,
				'2840'	 => 30,
				'3574'	 => 30,
				'4060'	 => 30,
				'4092'	 => 30,
				'4147'	 => 30,
				'4187'	 => 30,
				'4279'	 => 30,
				'4280'	 => 30,
				'4474'	 => 30,
				'4532'	 => 30,
				'4702'	 => 30,
				'4724'	 => 30,
				'4727'	 => 30,
				'4731'	 => 30,
				'4733'	 => 30,
				'4790'	 => 30,
				'4997'	 => 30,
				'5069'	 => 30,
				'5189'	 => 30,
				'5228'	 => 30,
				'5239'	 => 30,
				'5456'	 => 30,
				'5610'	 => 30,
				'5835'	 => 30,
				'5933'	 => 30,
				'5935'	 => 30,
				'5966'	 => 30,
				'6053'	 => 30,
				'6059'	 => 30,
				'6141'	 => 30,
				'6143'	 => 30,
				'6148'	 => 30,
				'6149'	 => 30,
				'6158'	 => 30,
				'6160'	 => 30,
				'6168'	 => 30,
				'6172'	 => 30,
				'6327'	 => 30,
				'6334'	 => 30,
				'6407'	 => 30,
				'6482'	 => 30,
				'6561'	 => 30,
				'6562'	 => 30,
				'6584'	 => 30,
				'6644'	 => 30,
				'6672'	 => 30,
				'6879'	 => 30,
				'6905'	 => 30,
				'6979'	 => 30,
				'6980'	 => 30,
				'7001'	 => 30,
				'7085'	 => 30,
				'7108'	 => 30,
				'7110'	 => 30,
				'7258'	 => 30,
				'7259'	 => 30,
				'7455'	 => 30,
				'7505'	 => 30,
				'7659'	 => 30,
				'7663'	 => 30,
				'7667'	 => 30,
				'7674'	 => 30,
				'7799'	 => 30,
				'7819'	 => 30,
				'7960'	 => 30,
				'8113'	 => 30,
				'8292'	 => 30,
				'8305'	 => 30,
				'8333'	 => 30,
				'8336'	 => 30,
				'8344'	 => 30,
				'8346'	 => 30,
				'8355'	 => 30,
				'8357'	 => 30,
				'8430'	 => 30,
				'8472'	 => 30,
				'8535'	 => 30,
				'8563'	 => 30,
				'8719'	 => 30,
				'8772'	 => 30,
				'8832'	 => 30,
				'8875'	 => 30,
				'9256'	 => 30,
				'9278'	 => 30,
				'9302'	 => 30,
				'9365'	 => 30,
				'9376'	 => 30,
				'9378'	 => 30,
				'9435'	 => 30,
				'9448'	 => 30,
				'9451'	 => 30,
				'9459'	 => 30,
				'9698'	 => 30,
				'9984'	 => 30,
				'9991'	 => 30,
				'10160'	 => 30,
				'10175'	 => 30,
				'10323'	 => 30,
				'10325'	 => 30,
				'10327'	 => 30,
				'10336'	 => 30,
				'10378'	 => 30,
				'10493'	 => 30,
				'10496'	 => 30,
				'10505'	 => 30,
				'10591'	 => 30,
				'10612'	 => 30,
				'10772'	 => 30,
				'10814'	 => 30,
				'10843'	 => 30,
				'11222'	 => 30,
				'11223'	 => 30,
				'11291'	 => 30,
				'11292'	 => 30,
				'12057'	 => 30,
				'12829'	 => 30,
				'9320' => 40,
				'1728' => 40,
				'1730' => 40,
				'1847' => 40,
				'3389' => 40,
				'3396' => 40,
				'3546' => 40,
				'3645' => 40,
				'3888' => 40,
				'5623' => 40,
				'8457' => 40,
				'8729' => 40,
				'8732' => 40,
				'8733' => 40,
				'8739' => 40,
				'9054' => 40,
				'9157' => 40,
				'9165' => 40,
				'9221' => 40,
				'9244' => 40,
				'9246' => 40,
				'9288' => 40,
				'9292' => 40,
				'9533' => 40,
				'9540' => 40,
				'9871' => 40,
				'11347' => 40,
				'11709' => 40,
				'11710' => 40,
				'11713' => 40,
				'11844' => 40,
				'11864' => 40,
				'11865' => 40,
				'12072' => 40,
				'12386' => 40,
				'12406' => 40,
				'12462' => 40,
				'12464' => 40,
				'12466' => 40,
				'12467' => 40,
				'12469' => 40,
				'12470' => 40,
				'12472' => 40,
				'12473' => 40,
				
				'538' => 40,
				'695' => 40,
				'729' => 40,
				'826' => 40,
				'847' => 40,
				'1340'	=> 40,
				'2127'	=> 40,
				'2164'	=> 40,
				'2168'	=> 40,
				'2205'	=> 40,
				'2688'	=> 40,
				'2926'	=> 40,
				'2931'	=> 40,
				'3033'	=> 40,
				'3095'	=> 40,
				'3099'	=> 40,
				'3187'	=> 40,
				'3217'	=> 40,
				'3440'	=> 40,
				'3573'	=> 40,
				'3578'	=> 40,
				'3986'	=> 40,
				'3987'	=> 40,
				'3991'	=> 40,
				'3994'	=> 40,
				'4061'	=> 40,
				'4191'	=> 40,
				'4201'	=> 40,
				'4209'	=> 40,
				'4225'	=> 40,
				'4235'	=> 40,
				'4253'	=> 40,
				'4316'	=> 40,
				'4520'	=> 40,
				'4524'	=> 40,
				'4655'	=> 40,
				'4680'	=> 40,
				'4689'	=> 40,
				'4691'	=> 40,
				'4694'	=> 40,
				'4788'	=> 40,
				'4811'	=> 40,
				'4812'	=> 40,
				'4981'	=> 40,
				'5060'	=> 40,
				'5119'	=> 40,
				'5122'	=> 40,
				'5200'	=> 40,
				'5249'	=> 40,
				'5274'	=> 40,
				'5275'	=> 40,
				'5308'	=> 40,
				'5351'	=> 40,
				'5356'	=> 40,
				'5387'	=> 40,
				'5451'	=> 40,
				'5454'	=> 40,
				'5459'	=> 40,
				'5658'	=> 40,
				'5680'	=> 40,
				'5701'	=> 40,
				'5702'	=> 40,
				'5719'	=> 40,
				'5774'	=> 40,
				'5836'	=> 40,
				'5837'	=> 40,
				'5919'	=> 40,
				'5934'	=> 40,
				'5944'	=> 40,
				'5954'	=> 40,
				'5964'	=> 40,
				'6050'	=> 40,
				'6067'	=> 40,
				'6086'	=> 40,
				'6095'	=> 40,
				'6101'	=> 40,
				'6155'	=> 40,
				'6156'	=> 40,
				'6162'	=> 40,
				'6166'	=> 40,
				'6308'	=> 40,
				'6381'	=> 40,
				'6426'	=> 40,
				'6428'	=> 40,
				'6438'	=> 40,
				'6442'	=> 40,
				'6443'	=> 40,
				'6485'	=> 40,
				'6533'	=> 40,
				'6535'	=> 40,
				'6558'	=> 40,
				'6563'	=> 40,
				'6567'	=> 40,
				'6569'	=> 40,
				'6597'	=> 40,
				'6635'	=> 40,
				'6645'	=> 40,
				'6678'	=> 40,
				'6682'	=> 40,
				'6749'	=> 40,
				'6868'	=> 40,
				'6918'	=> 40,
				'6928'	=> 40,
				'7004'	=> 40,
				'7073'	=> 40,
				'7113'	=> 40,
				'7268'	=> 40,
				'7502'	=> 40,
				'7503'	=> 40,
				'7508'	=> 40,
				'7512'	=> 40,
				'7514'	=> 40,
				'7515'	=> 40,
				'7519'	=> 40,
				'7520'	=> 40,
				'7525'	=> 40,
				'7526'	=> 40,
				'7531'	=> 40,
				'7532'	=> 40,
				'7538'	=> 40,
				'7544'	=> 40,
				'7545'	=> 40,
				'7644'	=> 40,
				'7647'	=> 40,
				'7654'	=> 40,
				'7657'	=> 40,
				'7683'	=> 40,
				'7688'	=> 40,
				'7693'	=> 40,
				'7700'	=> 40,
				'7792'	=> 40,
				'7796'	=> 40,
				'7797'	=> 40,
				'7800'	=> 40,
				'7808'	=> 40,
				'7813'	=> 40,
				'7821'	=> 40,
				'7837'	=> 40,
				'7840'	=> 40,
				'7846'	=> 40,
				'7943'	=> 40,
				'7945'	=> 40,
				'7952'	=> 40,
				'7955'	=> 40,
				'7956'	=> 40,
				'7958'	=> 40,
				'7981'	=> 40,
				'7998'	=> 40,
				'8051'	=> 40,
				'8062'	=> 40,
				'8117'	=> 40,
				'8306'	=> 40,
				'8325'	=> 40,
				'8327'	=> 40,
				'8340'	=> 40,
				'8347'	=> 40,
				'8352'	=> 40,
				'8356'	=> 40,
				'8434'	=> 40,
				'8452'	=> 40,
				'8459'	=> 40,
				'8536'	=> 40,
				'8538'	=> 40,
				'8540'	=> 40,
				'8543'	=> 40,
				'8547'	=> 40,
				'8554'	=> 40,
				'8555'	=> 40,
				'8562'	=> 40,
				'8569'	=> 40,
				'8575'	=> 40,
				'8710'	=> 40,
				'8783'	=> 40,
				'8786'	=> 40,
				'8792'	=> 40,
				'8794'	=> 40,
				'8876'	=> 40,
				'8877'	=> 40,
				'9266'	=> 40,
				'9271'	=> 40,
				'9284'	=> 40,
				'9380'	=> 40,
				'9422'	=> 40,
				'9431'	=> 40,
				'9450'	=> 40,
				'9452'	=> 40,
				'9477'	=> 40,
				'9567'	=> 40,
				'9700'	=> 40,
				'9824'	=> 40,
				'9989'	=> 40,
				'10163'	=> 40,
				'10174'	=> 40,
				'10190'	=> 40,
				'10191'	=> 40,
				'10262'	=> 40,
				'10265'	=> 40,
				'10266'	=> 40,
				'10273'	=> 40,
				'10305'	=> 40,
				'10312'	=> 40,
				'10345'	=> 40,
				'10348'	=> 40,
				'10506'	=> 40,
				'10592'	=> 40,
				'10593'	=> 40,
				'10619'	=> 40,
				'10621'	=> 40,
				'10653'	=> 40,
				'10775'	=> 40,
				'10815'	=> 40,
				'10863'	=> 40,
				'11584'	=> 40,
				'11590'	=> 40,
				'11704'	=> 40,
				'11780'	=> 40,
				'1846' => 50,
				'5010' => 50,
				'5617' => 50,
				'6507' => 50,
				'8647' => 50,
				'9611' => 50,
				'9803' => 50,
				'11405' => 50,
				'11406' => 50,
				'11407' => 50,
				'11919' => 50,
				'11920' => 50,
				'11921' => 50,
				'11982' => 50 
            );

            if ( isset($fakeProcentsDiscounts[$result['product_id']])){

				//$price = $this->currency->format(round(($result['price'] + (($result['price']/100) * $fakeProcentsDiscounts[$result['product_id']]))/100)*100-10);

				//$special = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));

            }

            //BMV "20% fake discount" End
				 
				
				
			
			
			if (!$special){
                $testCategories  = $this->model_catalog_product->getCategories($result['product_id']);
                foreach ($testCategories as $curCategory){
                    if ($curCategory['category_id'] == '1163'){
                        //$special = $price;
                        //$price = $this->currency->format(round(($result['price'] + $result['price']/100*20)/100)*100-10);
                        break;
                    }
                }
			}

			if ($this->config->get('config_tax')) {
				$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
			} else {
				$tax = false;
			}

			if ($this->config->get('config_review_status')) {
				$rating = (int)$result['rating'];
			} else {
				$rating = false;
			}

			$options = $this->model_catalog_product->getProductOptions($result['product_id']);


				$attribute_groups = $this->model_catalog_product->getProductAttributes($result['product_id']);

				foreach ($attribute_groups as $attribute_group) {

					foreach ($attribute_group['attribute'] as $attribute) {

						if ($attribute['text']=='Акция') {
							$PR_PR=str_replace(' ','',str_replace('ք','',$price));
							$new_old_price=ceil($PR_PR*1.2/100)*100;
							$new_old_price=number_format($new_old_price, 0, '.', ' ');
						}

					}


				}


				$customer_group_id = $this->customer->getCustomerGroupId();
				if ((empty($customer_group_id) || $customer_group_id == 1 || $customer_group_id == 2) && $result['discount'] > 0) {

					$dsc_price = $result['price'] * 100 / (100 - $result['discount']);
					$dsc_price = ceil($dsc_price/10) * 10;
					$new_old_price = number_format($this->tax->calculate(($dsc_price), $result['tax_class_id'], $this->config->get('config_tax')), 0, ',', ' ');


				} else if ($customer_group_id > 2) {
					$result['discount'] = 0;
				}

				unset($new_old_price);


				//проверка, а не входит ли данный арт в категорию новинки
				$PRODUCT_LABEL='';
				$this->load->model('catalog/category');
				$categories  = $this->model_catalog_product->getCategories($result['product_id']);
		                if ($categories){
		
					foreach ($categories as $category) {
		
						if($category['category_id'] == "7609") {
		
							$SKU_TIME_DELTA=(time()-strtotime($result['date_modified']))/(60*60*24);
							if ($SKU_TIME_DELTA<11) {$PRODUCT_LABEL='novinka';}
			
						}

						if($category['category_id'] == "7608") {
		
							//$PRODUCT_LABEL='all1090';
			
						}




					}
				}


	
				$this->data['products'][] = array(
					'product_id'		=> $result['product_id'],
					'thumb'			=> $image,
					'thumb2'		=> $image2,
					'options'		=> $options,
					'name'			=> $result['name'],
					'manufacturer'		=> $result['manufacturer'],
					'fullname'		=> $result['fullname'],
					'sku'			=> (empty($result['sku'])) ? '' : $this->language->get('text_sku') .' '. $result['sku'],
					'description'		=> utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 100) . '..',
					'price'			=> $price,
					'new_old_price'		=> $new_old_price,
					'special'		=> $special,
					'category_sticker_image'		=> $category_sticker_image,
					'tax'			=> $tax,
					'rating'		=> $result['rating'],
					'reviews'		=> sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
					'href'			=> $this->url->link('product/product', 'path=' . $this->request->get['path'] . '&product_id=' . $result['product_id']),
					'discount'		=> $result['discount'],
					'date_modified'		=> $result['date_modified'],
					'product_label'		=> $PRODUCT_LABEL

				);

				if (isset($new_old_price)) unset($new_old_price,$dsc_price);
				unset($PRODUCT_LABEL);

		}

		if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/filterpro_products.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/filterpro_products.tpl';
		} else {
			$this->template = 'default/template/module/filterpro_products.tpl';
		}
		return $this->render();
	}
}

?>
