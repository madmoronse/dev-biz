<?php
/**
 * Класс YML экспорта
 * YML (Yandex Market Language) - стандарт, разработанный "Яндексом"
 * для принятия и публикации информации в базе данных Яндекс.Маркет
 * YML основан на стандарте XML (Extensible Markup Language)
 * описание формата YML http://partner.market.yandex.ru/legal/tt/
 */
class ControllerFeedXmlDrop extends Controller {
	private $shop = array();
	private $currencies = array();
	private $categories = array();
	private $offers = array();
	private $from_charset = 'utf-8';
	private $eol = "\n";

	public function index() {
		if ($this->config->get('yandex_market_status')) {

			$allowed_categories = '';
			
			//$this->response->addHeader('Content-Type: application/xml');

			//$filename = DIR_DOWNLOAD . 'offers.yml';
			$filename = str_replace('/catalog/', '', DIR_APPLICATION) . '/price/xml/drop_82093ru23ru28f323fh.xml';

			if (!is_file($filename) || time() - @filemtime($filename) > 60*30) { // one time per half of hour
				$fd = fopen($filename, 'w');

				$this->load->model('export/yandex_market');
				$this->load->model('localisation/currency');
				$this->load->model('tool/image');
				$this->load->model('catalog/product');
				$this->load->model('catalog/category');

				// Магазин
				//$this->setShop('name', $this->config->get('yandex_market_shopname'));
				//$this->setShop('company', $this->config->get('yandex_market_company'));
				//$this->setShop('url', HTTP_SERVER);
				//$this->setShop('url', '');
				//$this->setShop('phone', $this->config->get('config_telephone'));
				//$this->setShop('phone', '');
				//$this->setShop('platform', 'ocStore');
				//$this->setShop('platform', '');
				//$this->setShop('version', VERSION);
				//$this->setShop('version', '');

				// Валюты
				// TODO: Добавить возможность настраивать проценты в админке.
				$offers_currency = $this->config->get('yandex_market_currency');
				if (!$this->currency->has($offers_currency)) exit();

				$decimal_place = $this->currency->getDecimalPlace($offers_currency);

				if (!$decimal_place) {
					$decimal_place = 2;
				}

				$shop_currency = $this->config->get('config_currency');

				//$this->setCurrency($offers_currency, 1);

				$currencies = $this->model_localisation_currency->getCurrencies();

				$supported_currencies = array('RUR', 'RUB', 'USD', 'BYR', 'KZT', 'EUR', 'UAH');

				$currencies = array_intersect_key($currencies, array_flip($supported_currencies));

				foreach ($currencies as $currency) {
					if ($currency['code'] != $offers_currency && $currency['status'] == 1) {
						//$this->setCurrency($currency['code'], number_format(1/$this->currency->convert($currency['value'], $offers_currency, $shop_currency), 4, '.', ''));
					}
				}

				// Категории
				$categories = $this->model_export_yandex_market->getCategory();

				foreach ($categories as $category) {
					$this->setCategory($category['name'], $category['category_id'], $category['parent_id']);
				}

				// Товарные предложения
				$in_stock_id = $this->config->get('yandex_market_in_stock'); // id статуса товара "В наличии"
				$out_of_stock_id = $this->config->get('yandex_market_out_of_stock'); // id статуса товара "Нет на складе"
				$vendor_required = false; // true - только товары у которых задан производитель, необходимо для 'vendor.model'
				$products = $this->model_export_yandex_market->getProduct($allowed_categories, $out_of_stock_id, $vendor_required);


				$yml  = '<?xml version="1.0" encoding="utf-8"?>' . $this->eol;
//				$yml .= '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">' . $this->eol;
				$yml .= '<catalog date="' . date('Y-m-d H:i') . '">' . $this->eol;
/*				$yml .= '<shop>' . $this->eol;

				// информация о магазине
				$yml .= $this->array2Tag($this->shop);

				// валюты
				$yml .= '<currencies>' . $this->eol;
				foreach ($this->currencies as $currency) {
					$yml .= $this->getElement($currency, 'currency');
				}
				$yml .= '</currencies>' . $this->eol;
*/
				// категории
				$yml .= '<categories>' . $this->eol;
				foreach ($this->categories as $category) {
					$category_name = $category['name'];
					unset($category['name'], $category['export']);
					$yml .= $this->getElement($category, 'category', $category_name);
				}
				$yml .= '</categories>' . $this->eol;

				$yml .= '<offers>' . $this->eol;

				fwrite($fd, $yml);

				$buffer = '';
				$buffer_size = 128*1024; // 128Kb

				foreach ($products as $product) {

					$data = array();

					// Атрибуты товарного предложения
					$data['id'] = $product['product_id'];
					$data['type'] = 'vendor.model';
					$data['available'] = ($product['quantity'] > 0 || $product['stock_status_id'] == $in_stock_id);


					// Параметры товарного предложения
					$data['url'] = $this->url->link('product/product', 'path=' . $this->getPath($product['category_id']) . '&product_id=' . $product['product_id']);

					$product['discount'] = $this->model_export_yandex_market->getProductDiscounts($product['product_id']);
					if (count($product['discount']) > 0){
						foreach ($product['discount'] as $discount) {
							if ($discount['price'] > 0) {
								$data['price'] = number_format($this->currency->convert($this->tax->calculate($discount['price'], $product['tax_class_id']), $shop_currency, $offers_currency), $decimal_place, '.', '');
							} else {
								$data['price'] = number_format($this->currency->convert($this->tax->calculate($product['price'], $product['tax_class_id']), $shop_currency, $offers_currency), $decimal_place, '.', '');
							}
						}
					} else {
						$data['price'] = number_format($this->currency->convert($this->tax->calculate($product['price'], $product['tax_class_id']), $shop_currency, $offers_currency), $decimal_place, '.', '');
					
					}
					$data['currencyId'] = $offers_currency;
					$data['categoryId'] = $product['category_id'];
					$data['delivery'] = 'true';
					$data['name'] = $product['name'];
					if ($product['quantity'] > 15) {$product['quantity'] = 15;}
					$data['quantity'] = $product['quantity'];
					$data['vendor'] = $product['manufacturer'];
					$data['vendorCode'] = $product['product_id'];
					$data['model'] = $product['name'];

					$data['description'] = $product['description'];
					

					$attribute_groups = $this->model_catalog_product->getProductAttributes($product['product_id']);

					if (!empty($attribute_groups)) {
						$data['param'] = array();
						foreach ($attribute_groups as $attribute_group) {
							foreach ($attribute_group['attribute'] as $attribute) {
								$data['param'][] = array (
										'name'  => $attribute['name'],
										'value' => $attribute['text'],
								);
							}
						}
					}

					if ($product['image']) {
						$image_path = 'image/' . $product['image'];
						$data['param'][] = array (
							'name'  => 'picture',
							'value' => HTTP_SERVER . $image_path,
						);
					}
					
					$images = $this->model_catalog_product->getProductImages($product['product_id']);

					foreach ($images as $image) {
						$image_path = 'image/' . $image['image'];
						$data['param'][] = array (
							'name'  => 'picture',
							'value' => HTTP_SERVER . $image_path,
						);						
					}


					
					$categories = $this->getPath($product['category_id']);
					$categories = explode("_", $categories);
					
					$categoryStr = '';

					foreach ($categories as $category) {
						
						$currrentCategory = $this->model_catalog_category->getCategory($category);
						
						if ( $currrentCategory['parent_id'] != 7607 AND $category['category_id'] != 7607 AND $currrentCategory['parent_id'] != 7608 AND $category['category_id'] != 7608 AND $currrentCategory['parent_id'] != 7609 AND $category['category_id'] != 7609 ) { // Исключаем категории Промо и Новинки и их субкатегории
							
							if ($currrentCategory['name'] != ''){
								$categoryStr .= $currrentCategory['name'] . " / ";
							}

						}				
					}	

					$data['param'][] = array (
						'name'  => 'categoryTree',
						'value' => substr($categoryStr,0,-3),
					);									
									
					
					$options = $this->model_catalog_product->getProductOptions($product['product_id']);
					if ($options){						
						foreach ($options[0]['option_value'] as $cur_option) {
							//echo "<script>alert('".var_dump($options)."')</script>"; 
							$data['group_id'] = $product['product_id'];					
							$data['id'] = $product['product_id'].'O'.$cur_option['name'];
							
							if ($cur_option['quantity'] > 5) {
								$data['quantity'] = 5;
							} else {
								$data['quantity'] = $cur_option['quantity'];
							}


							$offer = $this->setOffer($data);


							$tags = $this->array2Tag($offer['data']);
							unset($offer['data']);
							if (isset($offer['param'])) {
								$tags .= $this->array2Param($offer['param']);
								unset($offer['param']);
							}


							
							
							$offer['options'][] = array (
								'name'  => $options[0]['name'],
								'value' => $cur_option['name'],
							);
														
							$tags .= $this->array2Param($offer['options']);
							unset($offer['options']);
							

							$buffer .= $this->getElement($offer, 'offer', $tags);
							
						}
						

						
					} else {

						$offer = $this->setOffer($data);

						$tags = $this->array2Tag($offer['data']);
						unset($offer['data']);
						if (isset($offer['param'])) {
							$tags .= $this->array2Param($offer['param']);
							unset($offer['param']);
						}
						$buffer .= $this->getElement($offer, 'offer', $tags);
					}


					if (strlen($buffer) >= $buffer_size) {
						fwrite($fd, $buffer);
						$buffer = '';
					}
				}

				if ($buffer != '') {
					fwrite($fd, $buffer);
				}

				$yml = '';
				$yml .= '</offers>' . $this->eol;

//				$yml .= '</shop>';
				$yml .= '</catalog>';

				fwrite($fd, $yml);
				fclose($fd);
			}

			//readfile($filename);

		}
	}

	/**
	 * Методы формирования YML
	 */

	/**
	 * Формирование массива для элемента shop описывающего магазин
	 *
	 * @param string $name - Название элемента
	 * @param string $value - Значение элемента
	 */
	private function setShop($name, $value) {
		$allowed = array('name', 'company', 'url', 'phone', 'platform', 'version', 'agency', 'email');
		if (in_array($name, $allowed)) {
			$this->shop[$name] = $this->prepareField($value);
		}
	}

	/**
	 * Валюты
	 *
	 * @param string $id - код валюты (RUR, RUB, USD, BYR, KZT, EUR, UAH)
	 * @param float|string $rate - курс этой валюты к валюте, взятой за единицу.
	 *	Параметр rate может иметь так же следующие значения:
	 *		CBRF - курс по Центральному банку РФ.
	 *		NBU - курс по Национальному банку Украины.
	 *		NBK - курс по Национальному банку Казахстана.
	 *		СВ - курс по банку той страны, к которой относится интернет-магазин
	 * 		по Своему региону, указанному в Партнерском интерфейсе Яндекс.Маркета.
	 * @param float $plus - используется только в случае rate = CBRF, NBU, NBK или СВ
	 *		и означает на сколько увеличить курс в процентах от курса выбранного банка
	 * @return bool
	 */
	private function setCurrency($id, $rate = 'CBRF', $plus = 0) {
		$allow_id = array('RUR', 'RUB', 'USD', 'BYR', 'KZT', 'EUR', 'UAH');
		if (!in_array($id, $allow_id)) {
			return false;
		}
		$allow_rate = array('CBRF', 'NBU', 'NBK', 'CB');
		if (in_array($rate, $allow_rate)) {
			$plus = str_replace(',', '.', $plus);
			if (is_numeric($plus) && $plus > 0) {
				$this->currencies[] = array(
						'id'=>$this->prepareField(strtoupper($id)),
						'rate'=>$rate,
						'plus'=>(float)$plus
				);
			} else {
				$this->currencies[] = array(
						'id'=>$this->prepareField(strtoupper($id)),
						'rate'=>$rate
				);
			}
		} else {
			$rate = str_replace(',', '.', $rate);
			if (!(is_numeric($rate) && $rate > 0)) {
				return false;
			}
			$this->currencies[] = array(
					'id'=>$this->prepareField(strtoupper($id)),
					'rate'=>(float)$rate
			);
		}

		return true;
	}

	/**
	 * Категории товаров
	 *
	 * @param string $name - название рубрики
	 * @param int $id - id рубрики
	 * @param int $parent_id - id родительской рубрики
	 * @return bool
	 */
	private function setCategory($name, $id, $parent_id = 0) {
		$id = (int)$id;
		if ($id < 1 || trim($name) == '') {
			return false;
		}
		if ((int)$parent_id > 0) {
			$this->categories[$id] = array(
					'id'=>$id,
					'parentId'=>(int)$parent_id,
					'name'=>$this->prepareField($name)
			);
		} else {
			$this->categories[$id] = array(
					'id'=>$id,
					'name'=>$this->prepareField($name)
			);
		}

		return true;
	}

	/**
	 * Товарные предложения
	 *
	 * @param array $data - массив параметров товарного предложения
	 */
	private function setOffer($data) {
		$offer = array();
		
		$attributes = array('id', 'type', 'available', 'bid', 'cbid', 'param','group_id','quantity');
		$attributes = array_intersect_key($data, array_flip($attributes));

		foreach ($attributes as $key => $value) {
			switch ($key)
			{
				case 'group_id':
				case 'bid':
				case 'cbid':
					$value = (int)$value;
					if ($value > 0) {
						$offer[$key] = $value;
					}
					break;
				case 'id':
					$value = $value;					
						$offer[$key] = $value;					
					break;

				case 'type':
					if (in_array($value, array('vendor.model', 'book', 'audiobook', 'artist.title', 'tour', 'ticket', 'event-ticket'))) {
						$offer['type'] = $value;
					}
					break;

				case 'available':
					$offer['available'] = ($value ? 'true' : 'false');
					break;

				case 'param':
					if (is_array($value)) {
						$offer['param'] = $value;
					}
					break;

				default:
					break;
			}
		}

		$type = isset($offer['type']) ? $offer['type'] : '';

		$allowed_tags = array('url'=>0, 'buyurl'=>0, 'price'=>1, 'wprice'=>0, 'currencyId'=>1, 'xCategory'=>0, 'categoryId'=>1, 'picture'=>0, 'store'=>0, 'pickup'=>0, 'delivery'=>0, 'deliveryIncluded'=>0, 'local_delivery_cost'=>0, 'orderingTime'=>0, 'quantity'=>1);

		switch ($type) {
			case 'vendor.model':
				$allowed_tags = array_merge($allowed_tags, array('typePrefix'=>0, 'vendor'=>1, 'vendorCode'=>0, 'model'=>1, 'provider'=>0, 'tarifplan'=>0));
				break;

			case 'book':
				$allowed_tags = array_merge($allowed_tags, array('author'=>0, 'name'=>1, 'publisher'=>0, 'series'=>0, 'year'=>0, 'ISBN'=>0, 'volume'=>0, 'part'=>0, 'language'=>0, 'binding'=>0, 'page_extent'=>0, 'table_of_contents'=>0));
				break;

			case 'audiobook':
				$allowed_tags = array_merge($allowed_tags, array('author'=>0, 'name'=>1, 'publisher'=>0, 'series'=>0, 'year'=>0, 'ISBN'=>0, 'volume'=>0, 'part'=>0, 'language'=>0, 'table_of_contents'=>0, 'performed_by'=>0, 'performance_type'=>0, 'storage'=>0, 'format'=>0, 'recording_length'=>0));
				break;

			case 'artist.title':
				$allowed_tags = array_merge($allowed_tags, array('artist'=>0, 'title'=>1, 'year'=>0, 'media'=>0, 'starring'=>0, 'director'=>0, 'originalName'=>0, 'country'=>0));
				break;

			case 'tour':
				$allowed_tags = array_merge($allowed_tags, array('worldRegion'=>0, 'country'=>0, 'region'=>0, 'days'=>1, 'dataTour'=>0, 'name'=>1, 'hotel_stars'=>0, 'room'=>0, 'meal'=>0, 'included'=>1, 'transport'=>1, 'price_min'=>0, 'price_max'=>0, 'options'=>0));
				break;

			case 'event-ticket':
				$allowed_tags = array_merge($allowed_tags, array('name'=>1, 'place'=>1, 'hall'=>0, 'hall_part'=>0, 'date'=>1, 'is_premiere'=>0, 'is_kids'=>0));
				break;

			default:
				$allowed_tags = array_merge($allowed_tags, array('name'=>1, 'vendor'=>0, 'vendorCode'=>0));
				break;
		}

		$allowed_tags = array_merge($allowed_tags, array('aliases'=>0, 'additional'=>0, 'description'=>0, 'sales_notes'=>0, 'promo'=>0, 'manufacturer_warranty'=>0, 'country_of_origin'=>0, 'downloadable'=>0, 'adult'=>0, 'barcode'=>0));

		$required_tags = array_filter($allowed_tags);

		if (sizeof(array_intersect_key($data, $required_tags)) != sizeof($required_tags)) {
			return;
		}

		$data = array_intersect_key($data, $allowed_tags);
//		if (isset($data['tarifplan']) && !isset($data['provider'])) {
//			unset($data['tarifplan']);
//		}

		$allowed_tags = array_intersect_key($allowed_tags, $data);

		// Стандарт XML учитывает порядок следования элементов,
		// поэтому важно соблюдать его в соответствии с порядком описанным в DTD
		$offer['data'] = array();
		foreach ($allowed_tags as $key => $value) {
			$offer['data'][$key] = $this->prepareField($data[$key]);
		}

		//$this->offers[] = $offer;
		return $offer;
	}

	/**
	 * Фрмирование элемента
	 *
	 * @param array $attributes
	 * @param string $element_name
	 * @param string $element_value
	 * @return string
	 */
	private function getElement($attributes, $element_name, $element_value = '') { // categories and offers
		
		if ( $element_name == 'offer'  ) {
			$retval = '<' . $element_name . ' ';
		} else {
			$retval = ' <' . $element_name . ' ';
		}	

		foreach ($attributes as $key => $value) {
			$retval .= $key . '="' . $value . '" ';
		}

		if ( $element_name == 'offer'  ) {
			$delimiter = $this->eol;
		} else {
			$delimiter = '';
		}
		

		$retval .= $element_value ? '>' . $delimiter . $element_value . '</' . $element_name . '>' : '/>';
		$retval .= $this->eol;

		return $retval;
	}

	/**
	 * Преобразование массива в теги
	 *
	 * @param array $tags
	 * @return string
	 */
	private function array2Tag($tags) {
		$retval = '';
		foreach ($tags as $key => $value) {
			if ($key == 'description') {
				$retval .= ' <' . $key . '><![CDATA[' . $value . ']]></' . $key . '>' . $this->eol;
			} else if ( $key ==  'quantity') { 
				$retval .= ' <outlets>' . $this->eol . '  <outlet id="0" instock="' . $value . '"/>' . $this->eol . ' </outlets>'. $this->eol;
			} else {
				$retval .= ' <' . $key . '>' . $value . '</' . $key . '>' . $this->eol;
			}
			
		}
		
		return $retval;
	}

	/**
	 * Преобразование массива в теги параметров
	 *
	 * @param array $params
	 * @return string
	 */
	private function array2Param($params) { //attributes
		
		$retval = '';
		foreach ($params as $param) {
			 
			$transliteratedName = $this->transliterate($this->prepareField($param['name']));
			$transliteratedName = str_replace(' ', '', lcfirst(ucwords($transliteratedName)));

			$retval .= ' <' . $transliteratedName . '>'. $this->prepareField($param['value']) . '</' . $transliteratedName . '>' . $this->eol;
			
			/*
			$retval .= ' <param name="' . $this->prepareField($param['name']);
			if (isset($param['unit'])) {
				$retval .= '" unit="' . $this->prepareField($param['unit']);
			}
			$retval .= '">' . $this->prepareField($param['value']) . '</param>' . $this->eol;*/
			
		}

		return $retval;
	}

	/**
	 * Подготовка текстового поля в соответствии с требованиями Яндекса
	 * Запрещаем любые html-тэги, стандарт XML не допускает использования в текстовых данных
	 * непечатаемых символов с ASCII-кодами в диапазоне значений от 0 до 31 (за исключением
	 * символов с кодами 9, 10, 13 - табуляция, перевод строки, возврат каретки). Также этот
	 * стандарт требует обязательной замены некоторых символов на их символьные примитивы.
	 * @param string $text
	 * @return string
	 */
	private function prepareField($field) {
		$field = htmlspecialchars_decode($field);
		$field = strip_tags($field);
		$from = array('"', '&', '>', '<', '\'');
		$to = array('&quot;', '&amp;', '&gt;', '&lt;', '&apos;');
		$field = str_replace($from, $to, $field);
		//if ($this->from_charset != 'windows-1251') {
//			$field = iconv($this->from_charset, 'windows-1251//IGNORE', $field);
		//}
		$field = preg_replace('#[\x00-\x08\x0B-\x0C\x0E-\x1F]+#is', ' ', $field);

		return trim($field);
	}

	protected function getPath($category_id, $current_path = '') {
		if (isset($this->categories[$category_id])) {
			$this->categories[$category_id]['export'] = 1;

			if (!$current_path) {
				$new_path = $this->categories[$category_id]['id'];
			} else {
				$new_path = $this->categories[$category_id]['id'] . '_' . $current_path;
			}

			if (isset($this->categories[$category_id]['parentId'])) {
				return $this->getPath($this->categories[$category_id]['parentId'], $new_path);
			} else {
				return $new_path;
			}

		}
	}


	function filterCategory($category) {
		return isset($category['export']);
	}

	private function transliterate($textcyr = null, $textlat = null) {
		$cyr = array(
		'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я',
		'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я');
		$lat = array(
		'a', 'b', 'v', 'g', 'd', 'e', 'е', 'zh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', '', 'y', '', 'e', 'u', 'ya',
		'A', 'B', 'V', 'G', 'D', 'E', 'E', 'ZH', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'CH', 'SH', 'SCH', '', 'Y', '', 'E', 'U', 'YA');
		if($textcyr) return str_replace($cyr, $lat, $textcyr);
		else if($textlat) return str_replace($lat, $cyr, $textlat);
		else return null;
	}
}
?>