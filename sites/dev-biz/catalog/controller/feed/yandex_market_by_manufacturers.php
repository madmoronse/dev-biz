<?php
/**
 * Класс YML экспорта
 * YML (Yandex Market Language) - стандарт, разработанный "Яндексом"
 * для принятия и публикации информации в базе данных Яндекс.Маркет
 * YML основан на стандарте XML (Extensible Markup Language)
 * описание формата YML http://partner.market.yandex.ru/legal/tt/
 */
class ControllerFeedYandexMarketByManufacturers extends Controller {

	public function index() {
        $this->load->model('export/yandex_market_data');
	    //$manufacturers = $this->model_export_yandex_market_data->getManufacturers(); // Через эту функцию $manufacturers не перебираются
			$manufacturers = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer");			
	    foreach ($manufacturers->rows as $manufacturer) {			

            $start = microtime(true);
            // Получаем группу пользователей
            $customer_group = (isset($this->request->get['customer_group']))
                ? (int)$this->request->get['customer_group'] : $this->config->get('config_customer_group_id');
            // Проверяем есть ли такая группа пользователей
            if (isset($this->request->get['customer_group'])) {
                $query = $this->db->query(
                    "SELECT `customer_group_id` FROM "
                    . DB_PREFIX . "customer_group WHERE `customer_group_id` = "
                    . $customer_group
                );

                if ($query->num_rows == 0) {
                    exit('Not allowed');
                }
            }

            // Market Status
            if (!$this->config->get('yandex_market_status')) return false;

            $allowed_categories = array();

            $this->load->model('export/yandex_market_writer');
            $this->load->model('export/yandex_market_helper');
            $this->load->model('localisation/currency');
            $this->load->model('catalog/product');
            $filename = DIR_APPLICATION . '../yml/' . $manufacturer['name'] . '.yml';
            $this->model_export_yandex_market_writer->setFile($filename);
            $fileinfo = $this->model_export_yandex_market_writer->checkFile();
			echo '<br />' . $manufacturer['name']  . ' - ';
            if ($fileinfo) {
                $timeout = ((int)$this->config->get('yandex_market_file_timeout')) ?
                    (int)$this->config->get('yandex_market_file_timeout') : 3600;
                // Разрешаем обновлять файл раз в X секунд
                if (\time() - $fileinfo->mtime < $timeout) {
                    echo 'Not allowed';
                    return false;
                } else {
                    unlink($filename);
                }
            }
            // Задаем группу пользователей
            $this->model_export_yandex_market_data->setCustomerGroup($customer_group);
            // Магазин
            $this->model_export_yandex_market_helper
                ->setShop('name', 'outmaxshop.ru')
                ->setShop('company', 'outmaxshop.ru')
                ->setShop('url', HTTP_SERVER);
                //->setShop('phone', $this->config->get('config_telephone'))
                //->setShop('phone', '')
                //->setShop('platform', 'ocStore')
                //->setShop('version', VERSION);


            // Валюты
            // TODO: Добавить возможность настраивать проценты в админке.
            $offers_currency = $this->config->get('yandex_market_currency');
            // нужно для number_format, потому что для выгрузки в YML
            // стандартый currency->format не подходит
            $decimal_place = $this->currency->getDecimalPlace($offers_currency);

            if (!$decimal_place) {
                $decimal_place = 2;
            }

            if (!$this->currency->has($offers_currency)) return false;

            $shop_currency = $this->config->get('config_currency');

            $this->model_export_yandex_market_helper->setCurrency($offers_currency, 1);

            $currencies = $this->model_localisation_currency->getCurrencies();

            $this->model_export_yandex_market_helper->prepareCurrencies($currencies, $offers_currency, $shop_currency);

            /*// Категории
            $categories = $this->model_export_yandex_market_data->getCategory();

            foreach ($categories as $category) {
                $this->model_export_yandex_market_helper
                    ->setCategory($category['name'], $category['category_id'], $category['parent_id']);
            }*/

            // заголовки документа
            $this->model_export_yandex_market_writer->addHeader();
            // информация о магазине
            $this->model_export_yandex_market_writer->addShopInfo(
                $this->model_export_yandex_market_helper->getShop()
            );
            // валюты
            $this->model_export_yandex_market_writer->addCurrencies(
                $this->model_export_yandex_market_helper->getCurrencies()
            );

            // офферы
            // id статуса товара "В наличии"
            $in_stock_id = $this->config->get('yandex_market_in_stock');
            // id статуса товара "Нет на складе"
            $out_of_stock_id = $this->config->get('yandex_market_out_of_stock');
            // true - только товары у которых задан производитель, необходимо для 'vendor.model'
            $vendor_required = $this->config->get('yandex_market_vendor_required');
            $product_resource = $this->model_export_yandex_market_data->getProductByManufacturer(
                $allowed_categories,
                $out_of_stock_id,
                $vendor_required,
                $manufacturer['manufacturer_id']
            );

            // категории
            $categories = array();
            while ($product = $this->model_export_yandex_market_data->fetchResource($product_resource)) {

                // категории
                $category = $this->model_export_yandex_market_data->getCategoryByManufacturersProducts($product['category_id']);

                $catAllreadyAdded = false;

                foreach ($categories as $curCategory)
                if ($category['0']['category_id'] == $curCategory['category_id']) {
                    $catAllreadyAdded = true;
                    break;
                }
                if($catAllreadyAdded == false){
                    $categories[] = $category['0'];
                }

                $i = 0;
                do {
                    if ($categories[$i]['parent_id'] == '0'){
                        break;
                    }

                        $category = $this->model_export_yandex_market_data->getCategoryByManufacturersProducts($categories[$i]['parent_id']);

                        $catAllreadyAdded = false;

                        foreach ($categories as $curCategory)
                            if ($category['0']['category_id'] == $curCategory['category_id']) {
                                $catAllreadyAdded = true;
                                break;
                            }
                        if($catAllreadyAdded == false){
                            $categories[] = $category['0'];

                        }
                        $i++;


                } while ($categories[$i]['parent_id'] != '0' );

            }


            foreach ($categories as $category) {
                $this->model_export_yandex_market_helper
                    ->setCategory($category['name'], $category['category_id'], $category['parent_id']);
            }

            // категории
            $this->model_export_yandex_market_writer->addCategories(
                $this->model_export_yandex_market_helper->getCategories()
            );


            $product_resource = $this->model_export_yandex_market_data->getProductByManufacturer(
                $allowed_categories,
                $out_of_stock_id,
                $vendor_required,
                $manufacturer['manufacturer_id']
            );
            $this->model_export_yandex_market_writer->startOffers();
            while ($product = $this->model_export_yandex_market_data->fetchResource($product_resource)) {

                //Очки не экспортируем

                    if (strpos($product['fullname'], 'очки') !== false) {
                        continue;
                    }

                    if (strpos($product['fullname'], 'Очки') !== false) {
                        continue;
                    }


                $data = array();

                // Атрибуты товарного предложения
                $data['id'] = $product['product_id'];
                $data['type'] = 'vendor.model';
                $data['available'] = ($product['quantity'] > 0 || $product['stock_status_id'] == $in_stock_id);

                // Параметры товарного предложения
                $data['url'] = HTTP_SERVER . 'index.php?route=product/product' .
                    '&path=' . $this->model_export_yandex_market_helper->getPath($product['category_id'])
                    . '&product_id=' . $product['product_id'];

                $discounts = array_filter(explode(',', (string)$product['customer_discount']));

                if (count($discounts) > 0 && (int)$discounts[0] !== 0) {
                    $product['price'] = (int)$discounts[0];
                }
                $data['price'] = number_format(
                    $this->currency->convert(
                        $this->tax->calculate(
                            $product['price'], $product['tax_class_id']
                        ),
                        $shop_currency,
                        $offers_currency
                    ),
                    $decimal_place,
                    '.',
                    '');

                $data['vendorCode'] = $product['product_id'];
                $data['currencyId'] = $offers_currency;
                $data['categoryId'] = $product['category_id'];
                $data['delivery'] = ($product['shipping'] == 1) ? 'true' : 'false';
                $data['outlets'] = array(
                    array(
                        'id' => $this->config->get('config_store_id'),
                        'instock' => ($product['quantity'] > 15) ? 15 : $product['quantity']
                    )
                );
                $data['name'] = $product['fullname'];
                $data['vendor'] = $product['manufacturer'];
                $data['model'] = $product['name'];
                //if ($this->config->get('yandex_market_show_description')) {
                    $data['description'] = \strip_tags($product['description']);
                //$data['description'] = '<![CDATA[<p>' . \strip_tags($product['description']) .'</p>]]>';
                //}

                if ($product['image']) {
                    $image_path = 'image/' . $product['image'];
                    $data['picture'][] = HTTP_SERVER . $image_path;
                }
                // Get Images
                $images = $this->model_catalog_product->getProductImages($product['product_id']);

                foreach ($images as $object) {
                    $data['picture'][] = HTTP_SERVER . 'image/' . $object['image'];
                }
                // Get Attributes
                $attribute_groups = $this->model_catalog_product->getProductAttributes($product['product_id']);

                if (!empty($attribute_groups)) {
                    $data['param'] = array();
                    foreach ($attribute_groups as $attribute_group) {
                        foreach ($attribute_group['attribute'] as $attribute) {
                            $data['param'][] = array(
                                'name' => $attribute['name'],
                                'value' => $attribute['text']
                            );
                        }
                    }
                }

                if (false === $offer = $this->model_export_yandex_market_helper->prepareOffer($data)) {
                    continue;
                }

                $options = $this->model_catalog_product->getProductOptions($product['product_id']);

                if (count($options) > 0) {
                    $params = $offer['param'];
                    $initialAvailable = $offer['available'];
                    foreach ($options as $option_group) {
                        foreach ($option_group['option_value'] as $option_value) {
                            if ($option_value['quantity'] <= 0) {
                                $offer['available'] = 'false';
                            } else {
                                $offer['available'] = $initialAvailable;
                            }
                            $offer['param'] = $params;
                            array_unshift(
                                $offer['param'],
                                array(
                                    'name' => $option_group['name'],
                                    'unit' => 'RU',
                                    'value' => $option_value['name']
                                )
                            );
                            $offer['data']['outlets'] = array(
                                array(
                                    'id' => $this->config->get('config_store_id'),
                                    'instock' => ($option_value['quantity'] > 5) ? 5 : $option_value['quantity']
                                )
                            );
                            $offer['group_id'] = $product['product_id'];
                            $offer['id'] = substr($product['product_id'] . 'O' .
                                $this->model_export_yandex_market_helper->prepareId($option_value['name']), 0, 20);
                            $this->model_export_yandex_market_writer->addOffer($offer);
                        }
                    }
                } else {
                    $this->model_export_yandex_market_writer->addOffer($offer);
                }
            }
            $this->model_export_yandex_market_writer->endOffers();
            // заголовки документа
            $this->model_export_yandex_market_writer->addFooter();
            $this->model_export_yandex_market_writer->flushToFile();

            printf('File created in: %F', microtime(true) - $start);
        }
	}
}
?>