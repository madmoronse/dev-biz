<?php
/**
 * Класс YML экспорта
 * YML (Yandex Market Language) - стандарт, разработанный "Яндексом"
 * для принятия и публикации информации в базе данных Яндекс.Маркет
 * YML основан на стандарте XML (Extensible Markup Language)
 * описание формата YML http://partner.market.yandex.ru/legal/tt/
 */
class ControllerFeedYandexMarketByNeos extends Controller
{

    public function index()
    {
        // Получаем группу пользователей
        $customer_group = (isset($this->request->get['customer_group']))
            ? (int) $this->request->get['customer_group'] : $this->config->get('config_customer_group_id');
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
        $filenames = explode(
            "\n",
            str_replace("\r", "", $this->config->get('yandex_market_file_list'))
        );
        $filekeys = array();
        for ($i = 1; $i < count($filenames) + 1; $i++) {
            $filekeys[] = $i;
        }
        $filenames = array_combine($filekeys, $filenames);
        foreach ($filenames as $key => $filename) {
            $filenames[$key] = trim(basename($filename));
        }

        // Если нету файла для такой группы пользователей
        if (empty($filenames[$customer_group])) {
            return false;
        }

        // Market Status
        if (!$this->config->get('yandex_market_status')) {
            return false;
        }
        $export_categories = $this->getExportCategories('yml');
        $filename = DIR_APPLICATION . '../price/export/' . basename($filenames[$customer_group]);
        $this->exportYML($filename, $customer_group, $export_categories, 'yml');
    }

    /**
     * Export other file types
     *
     * @return void
     */
    public function export()
    {
        $target = $this->request->get['target'];
        $options = array();
        switch ($target) {
            case 'freemoda':
                $scheme = $target;
                $customer_group = 4;
                $export_categories = $this->getExportCategories($target);
                $filename = DIR_APPLICATION . '../price/freemoda_28ed3917a1c098627df00c5d3c8a48a3.yml';
                break;
            case 'dropshippers':
                $scheme = 'yml';
                $customer_group = 4;
                $export_categories = $this->getExportCategories($target);
                $filename = DIR_APPLICATION . '../price/full_drop_7812ee680e5448beda137a34dc8d8b8a.yml';
                $options['show_description'] = true;
                break;
                break;
            case 'opt':
                $scheme = 'yml';
                $customer_group = 3;
                $export_categories = $this->getExportCategories($target);
                $filename = DIR_APPLICATION . '../price/export/3.yml';
                $options['show_description'] = false;
                $options['skip_zero_quantyty_products'] = true;
                break;
            case 'sneakerswearfeed':
                $scheme = 'yml';
                $customer_group = 1;
                $export_categories = $this->getExportCategories($target);
                $filename = DIR_APPLICATION . '../price/sneakerswearfeed.yml';
                $options['sneakerswearfeed'] = true;
                break;
            case 'tiu_ru':
                $scheme = 'yml';
                $customer_group = 1;
                $export_categories = $this->getExportCategories($target);
                $filename = DIR_APPLICATION . '../price/tiu_ru.yml';
                $options['skip_zero_quantyty_products'] = true;
                $options['show_description'] = true;
                break;
            case 'cdek_market':
                $scheme = 'yml';
                $customer_group = 1;
                $export_categories = $this->getExportCategories($target);
                $filename = DIR_APPLICATION . '../price/cdek_market.yml';
                $options['skip_zero_quantyty_products'] = true;
                $options['show_description'] = true;
                break;
            case 'cdek_market_only_clothes':
                $scheme = 'yml';
                $customer_group = 1;
                $export_categories = $this->getExportCategories($target);
                $filename = DIR_APPLICATION . '../price/cdek_market_only_clothes.yml';
                $options['skip_zero_quantyty_products'] = true;
                $options['show_description'] = true;
                $options['only_clothes'] = true;
                break;
            case 'cdek_market_without_clothes':
                $scheme = 'yml';
                $customer_group = 1;
                $export_categories = $this->getExportCategories($target);
                $filename = DIR_APPLICATION . '../price/cdek_market_without_clothes.yml';
                $options['skip_zero_quantyty_products'] = true;
                $options['show_description'] = true;
                $options['without_clothes'] = true;
                break;
            case 'default_with_description':
                $scheme = 'yml';
                $customer_group = 1;
                $export_categories = $this->getExportCategories($target);
                $filename = DIR_APPLICATION . '../price/1_with_description.yml';
                $options['show_description'] = true;
                break;
            default:
                exit('Not allowed');
        }
        $this->exportYML($filename, $customer_group, $export_categories, $scheme, $options);
    }

    /**
     * Get export categories
     * @param string $target
     * @return array
     */
    protected function getExportCategories($target)
    {
        // TODO: add categories for different targets
        return array_filter(
            explode(',', $this->config->get('yandex_market_categories')),
            function ($item) {
                return is_numeric($item);
            }
        );
    }

    /**
     * @param string $filename
     * @param integer $customer_group
     * @param array $export_categories
     * @param string $scheme
     * @param array $export_options
     * @return array
     */
    protected function exportYML(
        $filename,
        $customer_group,
        array $export_categories,
        $scheme = 'yml',
        $export_options = array()
    ) {
        $start = microtime(true);
        $this->load->model('export/yandex_market_data');
        $this->load->model('export/yandex_market_writer');
        $this->load->model('export/yandex_market_helper');
        $this->load->model('localisation/currency');
        $this->load->model('catalog/product');
        $this->model_export_yandex_market_writer->setScheme($scheme);
        $this->model_export_yandex_market_writer->applyScheme();
        $this->model_export_yandex_market_writer->setFile($filename);
        $fileinfo = $this->model_export_yandex_market_writer->checkFile();
        if ($fileinfo) {
            $timeout = ((int) $this->config->get('yandex_market_file_timeout')) ?
            (int) $this->config->get('yandex_market_file_timeout') : 3600;
            // Разрешаем обновлять файл раз в X секунд
            if (\time() - $fileinfo->mtime < $timeout) {
                exit('Not allowed');
            } else {
                unlink($filename);
            }
        }
        // Задаем группу пользователей
        $this->model_export_yandex_market_data->setCustomerGroup($customer_group);
        // Магазин
        $this->model_export_yandex_market_helper
             ->setShop('name', $this->config->get('yandex_market_shopname'))
             ->setShop('company', $this->config->get('yandex_market_company'))
             ->setShop('url', HTTP_SERVER)
             ->setShop('phone', $this->config->get('config_telephone'))
             ->setShop('phone', '')
             ->setShop('platform', 'ocStore')
             ->setShop('version', VERSION);


        // Валюты
        // TODO: Добавить возможность настраивать проценты в админке.
        $offers_currency = $this->config->get('yandex_market_currency');
        // нужно для number_format, потому что для выгрузки в YML
        // стандартый currency->format не подходит
        $decimal_place = $this->currency->getDecimalPlace($offers_currency);
        
        if (!$decimal_place) {
            $decimal_place = 2;
        }

        if (!$this->currency->has($offers_currency)) {
            return false;
        }

        $shop_currency = $this->config->get('config_currency');

        $this->model_export_yandex_market_helper->setCurrency($offers_currency, 1);

        $currencies = $this->model_localisation_currency->getCurrencies();

        $this->model_export_yandex_market_helper->prepareCurrencies($currencies, $offers_currency, $shop_currency);

        // Категории
        $categories = $this->model_export_yandex_market_data->getCategory();

        foreach ($categories as $category) {
            $this->model_export_yandex_market_helper
                 ->setCategory($category['name'], $category['category_id'], $category['parent_id']);
        }
        
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
        // категории
        $this->model_export_yandex_market_writer->addCategories(
            $this->model_export_yandex_market_helper->getCategories()
        );

        // офферы
        // id статуса товара "В наличии"
        $in_stock_id = $this->config->get('yandex_market_in_stock');
        // id статуса товара "Нет на складе"
        $out_of_stock_id = $this->config->get('yandex_market_out_of_stock');
        // акционные категории
        $sale_categories = explode(',', $this->config->get('yandex_market_sale_categories'));
        // true - только товары у которых задан производитель, необходимо для 'vendor.model'
        $vendor_required = $this->config->get('yandex_market_vendor_required');
        $product_resource = $this->model_export_yandex_market_data->getProduct(
            $export_categories,
            $out_of_stock_id,
            $vendor_required
        );
        $this->model_export_yandex_market_writer->startOffers();
        while ($product = $this->model_export_yandex_market_data->fetchResource($product_resource)) {
            if ($export_options['sneakerswearfeed']) { // Им очки не экспортируем
                if (strpos($product['fullname'], 'очки') !== false) {
                    continue;
                }

                if (strpos($product['fullname'], 'Очки') !== false) {
                    continue;
                }
            }
            
            // Check isClothes ?
            if ($export_options['only_clothes']) {
                if (substr($this->model_export_yandex_market_helper->getPath($product['category_id']), 0, 2) !== "4_") {
                    continue;
                }
            }
            
            if ($export_options['without_clothes']) {
                if (substr($this->model_export_yandex_market_helper->getPath($product['category_id']), 0, 2) === "4_") {
                    continue;
                }
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
                
            $discounts = array_filter(explode(',', (string) $product['customer_discount']));
    
            if (count($discounts) > 0 && (int) $discounts[0] !== 0) {
                $product['price'] = (int) $discounts[0];
            }
            $data['price'] = number_format(
                $this->currency->convert(
                    $this->tax->calculate(
                        $product['price'],
                        $product['tax_class_id']
                    ),
                    $shop_currency,
                    $offers_currency
                ),
                $decimal_place,
                '.',
                ''
            );

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
            if (($this->config->get('yandex_market_show_description') || $export_options['show_description'])
                && !empty($product['description'])
            ) {
                $data['description'] = $product['description'];
            }

            if ($product['image']) {
                $image_path = 'image/' . $product['image'];
                $data['picture'][] = HTTP_SERVER . $image_path;
            }
            // Get Images
            $images = $this->model_catalog_product->getProductImages($product['product_id']);

            foreach ($images as $object) {
                $data['picture'][]  = HTTP_SERVER . 'image/' . $object['image'];
            }
            // Get Attributes
            $attribute_groups = $this->model_catalog_product->getProductAttributes($product['product_id']);

            if (!empty($attribute_groups)) {
                $data['param'] = array();
                foreach ($attribute_groups as $attribute_group) {
                    foreach ($attribute_group['attribute'] as $attribute) {
                        $data['param'][] = array(
                            'name'  => $attribute['name'],
                            'value' => $attribute['text']
                        );
                    }
                }
            }
            // Get categories
            $product_categories = $this->model_catalog_product->getCategories($product['product_id']);
            foreach ($product_categories as $category) {
                if (in_array($category['category_id'], $sale_categories)) {
                    $data['param'][] = array(
                        'name'  => 'Акционный товар',
                        'value' => 'Да'
                    );
                    break;
                }
            }
            if (false === $offer = $this->model_export_yandex_market_helper->prepareOffer($data)) {
                continue;
            }
            $options = $this->model_catalog_product->getProductOptions($product['product_id']);
            $offers_with_options = $this->multipleOfferByOptions($offer, $options, $product, $scheme, $export_options);
            foreach ($offers_with_options as $item) {
                $this->model_export_yandex_market_writer->addOffer($item);
            }
        }
        $this->model_export_yandex_market_writer->endOffers();
        // заголовки документа
        $this->model_export_yandex_market_writer->addFooter();
        $this->model_export_yandex_market_writer->flushToFile();

        printf('File created in: %F <br/>', microtime(true) - $start);
    }

    /**
     * @param array $offer
     * @param array $options
     * @param array $product
     * @param string $scheme
     * @param array $export_options
     * @return array
     */
    protected function multipleOfferByOptions(array $offer, array $options, array $product, $scheme = 'yml', $export_options = array())
    {
        $offers_with_options = array();
        switch ($scheme) {
            case 'yml':
                foreach ($options as $option_group) {
                    foreach ($option_group['option_value'] as $option_value) {
                        $current_offer = $offer;
                        if ($option_value['quantity'] <= 0) {
                            $current_offer['available'] = 'false';
                        }
                        $current_offer['param'] = $offer['param'];
                        array_unshift(
                            $current_offer['param'],
                            array(
                                'name' => $option_group['name'],
                                'unit' => 'RU',
                                'value' => $option_value['name']
                            )
                        );
                        $current_offer['data']['outlets'] = array(
                            array(
                                'id' => $this->config->get('config_store_id'),
                                'instock' => ($option_value['quantity'] > 5) ? 5 : $option_value['quantity']
                            )
                        );
                        $current_offer['group_id'] = $product['product_id'];
                        $current_offer['id'] = substr($product['product_id'] . 'O' .
                            $this->model_export_yandex_market_helper->prepareId($option_value['name']), 0, 20);
                        if ($export_options['skip_zero_quantyty_products'] && $current_offer['available'] == 'false') {
                            continue;
                        }
                        $offers_with_options[] = $current_offer;
                    }
                }
                if (count($options) === 0) {
                    $offers_with_options[] = $offer;
                }
                break;
            case 'freemoda':
                $offer_options = array();
                foreach ($options as $option_group) {
                    foreach ($option_group['option_value'] as $option_value) {
                        if ($option_value['quantity'] > 0) {
                            $offer_options[] = $option_value['name'] . '-' . $option_value['quantity'];
                        }
                    }
                }
                if (count($offer_options) > 0) {
                    $offer['data']['options'] = implode(';', $offer_options) . ';';
                }
                $offer['data']['quantity'] = $product['quantity'];
                unset(
                    $offer['type'],
                    $offer['data']['outlets'],
                    $offer['data']['url'],
                    $offer['data']['vendorCode']
                );
                $offer['data']['price'] = (int) $offer['data']['price'];
                $offers_with_options[] = $offer;
                break;
        }

        return $offers_with_options;
    }
}
