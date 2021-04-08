<?php
class ControllerProductCategory extends Controller {
	public function index() {
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header',
			'common/category_filter'
		);
		/** By Neos - Cache Page - Start */
		\Neos\NeosFactory::setupEnviroment();
		$cacheView = new \Neos\classes\Cache\Category($this, $this->children);
		// Cache found
		if ($cacheView->load()) {
			foreach ($this->children as $child) {
				$childrenData[basename($child)] = $this->getChild($child);
			}
			$cacheView->setChildrenData($childrenData);
			$cacheView->renderView();
			return;
		}
		/** By Neos - Cache Page - End */
		$this->language->load('product/category');

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		if (isset($this->request->get['filter'])) {
			$filter = $this->request->get['filter'];
		} else {
			$filter = '';
		}


		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
			//$limit = 60;
		} else {
			$limit = $this->config->get('config_catalog_limit');
			//$limit = 60;
		}


		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
       		'separator' => false
   		);

		if (isset($this->request->get['path'])) {
			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}


			$path = '';

			$parts = explode('_', (string)$this->request->get['path']);

			$category_id = (int)array_pop($parts);

				foreach ($parts as $path_id) {
					if (!$path) {
						$path = (int)$path_id;
					} else {
						$path .= '_' . (int)$path_id;
					}

					$category_info = $this->model_catalog_category->getCategory($path_id);

					if ($category_info) {
						$this->data['breadcrumbs'][] = array(
							'text'      => $category_info['name'],
							'href'      => $this->url->link('product/category', 'path=' . $path . $url),
							'separator' => $this->language->get('text_separator')
						);
					}

				}


		} else {
			$category_id = 0;
		}


		$category_info = $this->model_catalog_category->getCategory($category_id);


		if ($category_id == "1163") { $eto_akcia = 1; $sort = 'p.date_modified'; $order = 'DESC';}
		//if ($category_id == "7608") { $sort = 'p.product_id'; $order = 'DESC';}
		if ($category_id == "7608") { $sort = 'p.date_modified'; $order = 'DESC';}
		//if ($category_id == "7608") { $sort = 'p.price'; $order = 'DESC';}

		if ($category_id == "7611") { $sort = 'p.date_modified'; $order = 'DESC';}

		if ($category_id == "10951") { $sort = 'p.price'; $order = 'DESC';}
		if ($category_id == "10013408" or $category_id == "10013409" or $category_id == "10013410" or $category_id == "10013411") { $sort = 'p.sku'; $order = 'ASC';}




		    if(isset($this->request->get['novently'])) {
		
			    $category_id = 99999999999999999;
			    $eto_novinki = true;
			    $sort = 'p.date_modified';
			    $order = 'DESC';
			    $category_info['seo_title'] = 'Новинки';
			    $category_info['seo_h1'] = 'Новинки';
			    $category_info['name'] = 'Новинки';
			    $category_info['meta_keyword'] = 'Новинки';
			    $category_info['meta_description'] = 'Новинки';
		
		     }

/*
		    if(isset($this->request->get['withdiscount'])) {
		
			    $category_id = 99999999999999998;
			    $withdiscount = true;
			    $sort = 'p.discount';
			    $order = 'DESC';
			    $category_info['seo_title'] = 'Скидки';
			    $category_info['seo_h1'] = 'Скидки';
			    $category_info['name'] = 'Скидки';
			    $category_info['meta_keyword'] = 'Скидки';
			    $category_info['meta_description'] = 'Скидки';
		
		     }
		
*/		
		    if(isset($this->request->get['supersale'])) {
		
			      $category_id = 99999999999999998;
			      $eto_supersale = true;
			      $sort = 'p.date_modified';
			      $order = 'DESC';
			      $category_info['seo_title'] = 'Распродажа';
			      $category_info['seo_h1'] = 'Распродажа';
			      $category_info['name'] = 'Распродажа';
			      $category_info['meta_keyword'] = 'Распродажа';
			      $category_info['meta_description'] = 'Распродажа';
		
		     }


		if ($category_info) {
		$seoData = array();
		if($category_id == 0){
			$this->document->setTitle('Последние поступления');
			$seoData['seo_title'] = 'Последние поступления';
		} else {
			if ($category_info['seo_title']) {
				$this->document->setTitle($category_info['seo_title']);
				$seoData['seo_title'] = $category_info['seo_title'];
			} else {
				  $this->document->setTitle($category_info['name']);
				  $seoData['seo_title'] = $category_info['name'];
			}
			}

			$this->document->setDescription($category_info['meta_description']);
			$seoData['meta_description'] = $category_info['meta_description'];
			$this->document->setKeywords($category_info['meta_keyword']);
			$seoData['meta_keyword'] = $category_info['meta_keyword'];
			$this->document->addScript('catalog/view/javascript/jquery/jquery.total-storage.min.js');

			if ($category_info['seo_h1']) {
				$this->data['heading_title'] = $category_info['seo_h1'];
			} else {
				$this->data['heading_title'] = $category_info['name'];
			}

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

			// Set the last category breadcrumb
			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}




			if ($category_id == 0) {
				$sort = 'p.date_available';
				$this->data['breadcrumbs'][] = array(
						'text'      => 'Последние поступления',
						'href'      => '/index.php?route=product/category&path=0',
						'separator' => $this->language->get('text_separator')
					);

			} elseif($eto_novinki) {

			        $this->data['breadcrumbs'][] = array(
			          'text'      => 'Новинки',
			          'href'      => $this->url->link('product/category', 'novently=' . 1),
			          'separator' => $this->language->get('text_separator')
			        );

			} elseif($eto_supersale) {

			        $this->data['breadcrumbs'][] = array(
			          'text'      => 'Распродажа',
			          'href'      => $this->url->link('product/category', 'supersale=' . 1),
			          'separator' => $this->language->get('text_separator')
				);
			} elseif($withdiscount) {
				$this->data['breadcrumbs'][] = array(
			          'text'      => 'Скидки',
			          'href'      => $this->url->link('product/category', 'withdiscount=' . 1),
			          'separator' => $this->language->get('text_separator')
				);
			}else {

				$this->data['breadcrumbs'][] = array(
					'text'      => $category_info['name'],
					'href'      => $this->url->link('product/category', 'path=' . $this->request->get['path']),
					'separator' => $this->language->get('text_separator')
				);
			}





			if ($category_info['image']) {
				$this->data['thumb'] = $this->model_tool_image->resize($category_info['image'], $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));
				$this->document->setOgImage($this->data['thumb']);
				$seoData['thumb'] = $this->data['thumb'];
			} else {
				$this->data['thumb'] = '';
			}
			/** By Neos - Cache Seo */
			$cacheView->setJsonCache($seoData);

			$this->data['description'] = str_replace('¶', ' ', html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8'));
			$this->data['compare'] = $this->url->link('product/compare');

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$this->data['categories'] = array();

			$results = $this->model_catalog_category->getCategories($category_id);

			foreach ($results as $result) {
				$data = array(
					'filter_category_id'  => $result['category_id'],
					'filter_sub_category' => true
				);

				$product_total = $this->model_catalog_product->getTotalProducts($data);

				$this->data['categories'][] = array(
					'name'  => $result['name'] . ($this->config->get('config_product_count') ? ' (' . $product_total . ')' : ''),
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '_' . $result['category_id'] . $url),
					'thumb' => $this->model_tool_image->resize(($result['image']=='' ? 'no_image.jpg' : $result['image']), $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'))
				);
			}

			$this->data['products'] = array();
			if ($category_id == 0) {$limit=100;}




		      if($eto_novinki){
		
			        $d1 = new DateTime();
			        $dt = $d1->format('U') - (60*60*24*15);
			        $days = DateTime::createFromFormat("U", $dt);
			        $data = array(
		  				'filter_filter'      => $filter,
		  				'sort'               => $sort,
		  				'order'              => $order,
		  				'start'              => ($page - 1) * $limit,
		  				'limit'              => $limit,
					        'date_filter'        => $days->format('Y-m-d H:i:s'),
		  		);
			  } elseif ($withdiscount) {
					$data = array(
		  				'filter_filter'      => $filter,
						'withdiscount'		 => true,
		  				'sort'               => $sort,
		  				'order'              => $order,
		  				'start'              => ($page - 1) * $limit,
		  				'limit'              => $limit
		  			);
		      } else {
		
					$data = array(
		  				'filter_category_id' => $category_id,
		  				'filter_filter'      => $filter,
		  				'sort'               => $sort,
		  				'order'              => $order,
		  				'start'              => ($page - 1) * $limit,
		  				'limit'              => $limit
		  			);
		      }


		      if($eto_supersale){
		
			        $d1 = new DateTime();
			        $dt = $d1->format('U') - (60*60*24*15);
			        $days = DateTime::createFromFormat("U", $dt);
			        $data = array(
		  				'filter_filter'      => $filter,
		  				'sort'               => $sort,
		  				'order'              => $order,
		  				'start'              => ($page - 1) * $limit,
		  				'limit'              => $limit,
						'date_filter'        => $days->format('Y-m-d H:i:s'),
		  		);
		
		      }		

//BMV additional products BEGIN

            $additionalProductsForCategory = $this->model_catalog_category->getAdditionalProductsForCategory($category_id);
            $additionalProductsForCategory['add_products_from_category'] = explode(',', $additionalProductsForCategory['add_products_from_category']);
            $additionalProductsForCategory['substract_products_from_category'] = explode(',', $additionalProductsForCategory['substract_products_from_category']);
            $additionalProductsForCategory['substract_products'] = explode(',', $additionalProductsForCategory['substract_products']);
            $additionalProductsForCategory['add_products'] = explode(',', $additionalProductsForCategory['add_products']);

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
                    $result_add_products[intval($add_products)] = $this->model_catalog_product->getProduct(intval($add_products));
                    $total_add_products_from_category = $total_add_products_from_category + $result_add_products;
                }
            }
            //Прибавляем все товары КОНЕЦ
            //
            //BMV additional products END

            $results = $total_add_products_from_category;

            //Дополняем обычные товары добавленными через админку в категории

            $results += $this->model_catalog_product->getProducts($data);
			//Вызов метода getFoundProducts должен проводится сразу же после getProducts
			//только тогда он выдает правильное значения количества товаров
			$product_total = $this->model_catalog_product->getFoundProducts();




			//артикулы под скидку
			$ACT_SKU = array ('1106','927','922','881','868','858','847','775','758','723','709','695','686','635','590','588','579','577','566','5267','5246','5245','5244','5240','5234','5231','5229','5226','5225','5223','5222','5209','5202','5205','5198','5197','5195','5193','5192','5124','5122','5120','5121','5107','5119','5116','5115','5110','5109','5105','5072','5062','4996','4931','4853','4846','4847','4848','4809','4803','4738','4732','4729','4659','1081','1308','1336','134','1359','1436','1448','172','1732','1777','155','1449','1513','147','1444','1376','1911','1941','2100','2103','2162','2165','2169','2212','2246','241','2466','2467','2484','2493','2515','2518','2523','2536','2567','2617','2634','2635','2678','2728','2801','2806','284','2843','2848','291','2919','2934','297','3001','3001','3021','307','317','3193','322','329','330','3308','3315','335','3441','345','351','3533','3534','3564','3752','3843','3850','390','3902','3933','394','3940','3946','395','398','3983','3986','3987','3988','3991','4000','401','4093','4109','4114','4116','4118','4130','4133','4138','4141','4153','4162','4198','4203','4210','4228','4230','4250','4253','4281','4285','4290','4319','4325','4326','4332','4350','4351','4361','4363','4364','4462','4524','4527','4530','4533','4536','4540','4541','457','4573','4579','4684','458','4580','4620','4655','4696','4701','4702','4791','4791');


			$FINAL_SKU = array (
				'557',
				'559',
				'573',
				'575',
				'718',
				'586',
				'634',
				'679',
				'1484',
				'1485',
				'589',
				'211',
				'157',
				'245',
				'182',
				'756',
				'1887',
				'1021',
				'1782',
				'3844',
				'140',
				'1765',
				'1715',
				'3398',
			
			);
	
			$FINAL_SKU_COEFF = array (

				'557'	=>	'3.32323232323232',
				'559'	=>	'3.32323232323232',			
				'573'	=>	'3.32323232323232',			
				'575'	=>	'3.32323232323232',
				'718'	=>	'3.32323232323232',
				'586'	=>	'3.32323232323232',
				'634'	=>	'3.32323232323232',
				'679'	=>	'3.32323232323232',
				'1484'	=>	'3.32323232323232',
				'1485'	=>	'3.32323232323232',
				'589'	=>	'3.32323232323232',
				'211'	=>	'2.00671140939597',
				'157'	=>	'1.80536912751678',
				'245'	=>	'2.20805369127517',
				'182'	=>	'2.20805369127517',
				'756'	=>	'2.20805369127517',
				'1887'	=>	'2.20805369127517',
				'1021'	=>	'2.20805369127517',
				'1782'	=>	'2.20805369127517',
				'3844'	=>	'2.20805369127517',
				'140'	=>	'2.20805369127517',
				'1765'	=>	'2.20805369127517',
				'1715'	=>	'2.20805369127517',
				'3398'	=>	'2.20805369127517',
			
			);




			$this->load->model('setting/setting');
            $promo_settings = $this->model_setting_setting->getSetting('promo_settings');
			
			$promo_settings['total_discount_products'] = str_replace(" ", "", $promo_settings['total_discount_products']);
            $total_discount_products = array_diff(explode("\r\n", $promo_settings['total_discount_products']), array(''));
			
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
				} else {
					$image = $this->model_tool_image->resize('no_image.jpg', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
				}


				//Дополнительное фото

				$show_additional_images = 0; if ($show_additional_images == 1) {
					$additional_photos = $this->model_catalog_product->getProductImages($result['product_id']);
					$additional_photos[] = "";

					foreach ($additional_photos as $additional_photo) {
						if(isset($additional_photo['image'])){
							$image2 = $this->model_tool_image->resize($additional_photo['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
								break;
						}
					}
				} else {
							$image2 = $image;
						}

				//Дополнительное фото Конец


				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
					if (isset($eto_akcia)){
							$new_old_price = $this->currency->format($this->tax->calculate(ceil($result['price']*2.3/100)*100, $result['tax_class_id'], $this->config->get('config_tax')));
					} else {
						$new_old_price = 0;




						if ( in_array($result['product_id'], $ACT_SKU, true) ) {
		
							if ($_SESSION[customer_id]<3) $new_old_price=$this->currency->format($this->tax->calculate(ceil($result['price']*1.2/100)*100, $result['tax_class_id'], $this->config->get('config_tax')));
							if ($_SESSION[customer_id]>2) $new_old_price=$this->currency->format($this->tax->calculate(ceil($result['price']*1.15/100)*100, $result['tax_class_id'], $this->config->get('config_tax')));
		
						}


						if ( in_array($result['product_id'], $FINAL_SKU, true) ) {
		
							$new_old_price=$this->currency->format($this->tax->calculate($result['price']*$FINAL_SKU_COEFF[$result['product_id']], $result['tax_class_id'], $this->config->get('config_tax')));
							
		
						}





					}
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}
				
				if (($promo_settings['total_discount_status'] && $this->customer->getCustomerGroupId() != 3) || ($this->customer->getId() == $promo_settings['total_discount_test_user'] && $this->customer->getId() != "")) {

                    if (!empty($total_discount_products)) {
                        if (in_array($result['product_id'], $total_discount_products)) {
                    		$special = $this->currency->format(round(($result['price'] - $result['price']/100*$promo_settings['total_discount_conditions'])/10)*10);
                        }
                    } else{
                        $special = $this->currency->format(round(($result['price'] - $result['price']/100*$promo_settings['total_discount_conditions'])/10)*10);
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

                   // $special = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));

				}

                //BMV "Настоящие" цены очков End
				
				//BMV акция ZX 750 Begin
               /* $ZX750NewPrice = array(
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
				'5611' => 20,
				'6028' => 20,
				'6118' => 20,
				'6120' => 20,
				'6833' => 20,
				'8225' => 20,
				'8251' => 20,
				'8252' => 20,
				'8283' => 20,
				'8497' => 20,
				'8625' => 20,
				'8630' => 20,
				'8635' => 20,
				'8727' => 20,
				'8734' => 20,
				'8800' => 20,
				'8895' => 20,
				'8925' => 20,
				'9062' => 20,
				'9068' => 20,
				'9069' => 20,
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
				'7110' => 30,
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
				$product_info = $this->model_catalog_product->getProduct($result['product_id']);


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
				//опробуем объявить цену для конкретного артикула


				//проверка, а не входит ли данный арт в категорию новинки
				$PRODUCT_LABEL='';
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
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'thumb2'       => $image2,
					'options' 	  => $options,
					'name'        => $result['name'],
					'fullname'    => $result['fullname'],
					'sku'         => (empty($result['sku'])) ? '' : $this->language->get('text_sku') .' '. $result['sku'],
					'manufacturer'=>  $product_info['manufacturer'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 300) . '..',
					'price'       => $price,
					'date_modified'       => $result['date_modified'],
					'new_old_price'  => $new_old_price,
					'special'     => $special,
					'tax'         => $tax,
					'rating'      => $result['rating'],
					'reviews'     => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
					'href'        => $this->url->link('product/product', 'path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'] . $url),
					'discount'    => $result['discount'],
					'date_modified'    => $result['date_modified'],
					'product_label'    => $PRODUCT_LABEL
				);

				if (isset($new_old_price)) unset($new_old_price,$dsc_price);
				unset($PRODUCT_LABEL);


			}

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}


			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$this->data['sorts'] = array();

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_default'),
				'value' => 'p.sort_order-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.sort_order&order=DESC' . $url)
			);

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_name_asc'),
				'value' => 'pd.name-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=ASC' . $url)
			);

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_name_desc'),
				'value' => 'pd.name-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=DESC' . $url)
			);

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_price_asc'),
				'value' => 'p.price-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=ASC' . $url)
			);

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_price_desc'),
				'value' => 'p.price-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=DESC' . $url)
			);

			if ($this->config->get('config_review_status')) {
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_desc'),
					'value' => 'rating-DESC',
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=DESC' . $url)
				);

				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_asc'),
					'value' => 'rating-ASC',
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=ASC' . $url)
				);
			}

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_model_asc'),
				'value' => 'p.model-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=ASC' . $url)
			);

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_model_desc'),
				'value' => 'p.model-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=DESC' . $url)
			);
			$this->data['sorts'][] = array(
				'text'  => "Дата изменения (по убыванию)",
				'value' => 'p.date_modified-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.date_modified&order=DESC' . $url)
			);

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$this->data['limits'] = array();

			$limits = array_unique(array($this->config->get('config_catalog_limit'), 20, 40, 60, 100));

			sort($limits);

			foreach($limits as $value){
				$this->data['limits'][] = array(
					'text'  => $value,
					'value' => $value,
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&limit=' . $value)
				);
			}

			$url = '';


			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&page={page}');

			$this->data['pagination'] = $pagination->render();

			$this->data['sort'] = $sort;
			$this->data['order'] = $order;
			$this->data['limit'] = $limit;

			$this->data['continue'] = $this->url->link('common/home');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/category.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/product/category.tpl';
			} else {
				$this->template = 'default/template/product/category.tpl';
			}
			/** By Neos - Cache Output - Start */
			$output = $this->render();
			$cacheView->setCache($output);
			$cacheView->prepareOutput($output, $this->data);
			/** By Neos - Cache Output - End */
    	} else {
			//BMV BEGIN если подкатегория в акции не существует, то перевести в корень акции
			if (substr_count($_SERVER['REQUEST_URI'], '1163_')){
				header("Location: outmaxshop.ru/index.php?route=product/category&path=1163");
			}
			//BMV END
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}


			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_error'),
				'href'      => $this->url->link('product/category', $url),
				'separator' => $this->language->get('text_separator')
			);

			$this->document->setTitle($this->language->get('text_error'));

      		$this->data['heading_title'] = $this->language->get('text_error');

      		$this->data['text_error'] = $this->language->get('text_error');

      		$this->data['button_continue'] = $this->language->get('button_continue');

      		$this->data['continue'] = $this->url->link('common/home');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
			} else {
				$this->template = 'default/template/error/not_found.tpl';
			}

			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);


			$this->response->setOutput($this->render());
		}
  	}
}
?>
