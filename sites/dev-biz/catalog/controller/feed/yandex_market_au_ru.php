<?php
/**
 * Класс YML экспорта
 * YML (Yandex Market Language) - стандарт, разработанный "Яндексом"
 * для принятия и публикации информации в базе данных Яндекс.Маркет
 * YML основан на стандарте XML (Extensible Markup Language)
 * описание формата YML http://partner.market.yandex.ru/legal/tt/
 */
class ControllerFeedYandexMarketAuRu extends Controller
{

    public function index()
    {
        // Получаем группу пользователей
        $customer_group = 1;
        $export_categories = $this->getExportCategories('yml');
        $filename = DIR_APPLICATION . '../price/au_ru_1.yml';
        $options['show_description'] = true;

        $this->exportYML($filename, $customer_group, $export_categories, 'yml', $options);
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
        $this->load->model('catalog/category');
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

        /* BMV
         foreach ($categories as $category) {
            $this->model_export_yandex_market_helper
                ->setCategory($category['name'], $category['category_id'], $category['parent_id']);
        }
        */

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
        /*BMV
        $this->model_export_yandex_market_writer->addCategories(
            $this->model_export_yandex_market_helper->getCategories()
        );
        */

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
        

        // добавляем нужные категории
        while ($product = $this->model_export_yandex_market_data->fetchResource($product_resource)) {
             
            $attribute_groups = $this->model_catalog_product->getProductAttributes($product['product_id']);
            if (!empty($attribute_groups)) {
                $product['param'] = array();
                foreach ($attribute_groups as $attribute_group) {
                    foreach ($attribute_group['attribute'] as $attribute) {
                        $product['param'][] = array(
                            'name'  => $attribute['name'],
                            'value' => $attribute['text']
                        );
                    }
                }
            }


            //Оптимизиуем категории для аукциона
            $product_categories = $this->model_catalog_product->getCategories($product['product_id']);
            


            $currentCategory = $this->model_catalog_category->getCategory($product['category_id']);

            

                foreach ($product_categories as $product_category){

                        if ($product_category['category_id'] != $product['category_id']){
                            $currentCategory = $this->model_catalog_category->getCategory($product_category['category_id']);
                            if ($currentCategory['parent_id'] != "7607" and $currentCategory['parent_id'] != "7609" and $currentCategory['parent_id'] != "11970")
                                if (!$newCurCategoryName){
                                    $newCurCategoryName = $currentCategory['name'];
                                } else {
                                    $newCurCategoryName .= ' - ' . $currentCategory['name'];
                                }
                        }

                }

                if ($currentCategory['parent_id'] != "3"){
                    $changeCategory = $this->model_catalog_category->getCategory( $product['category_id']);
                    if($changeCategory['parent_id'] != "0"){
                        $product['category_id'] = $changeCategory['parent_id'];
                    }
                }   
                




                foreach ($categories as $key => $curCat){
                    if ($curCat['category_id'] == $product['category_id']){
                        if (isset($newCurCategoryName)){
                            $categories[$key]['name'] = $newCurCategoryName;
                        }

                        break;
                    }

                }

                unset($newCurCategoryName);
            



        }

        foreach ($categories as $category) {
            $this->model_export_yandex_market_helper
                ->setCategory($category['name'], $category['category_id'], $category['parent_id']);
        }

        $this->model_export_yandex_market_writer->addCategories(
            $this->model_export_yandex_market_helper->getCategories()
        );

        $product_resource = $this->model_export_yandex_market_data->getProduct(
            $export_categories,
            $out_of_stock_id,
            $vendor_required
        );

        $this->model_export_yandex_market_writer->startOffers();
        while ($product = $this->model_export_yandex_market_data->fetchResource($product_resource)) {
            
            $data = array();

            // Атрибуты товарного предложения
            $data['id'] = $product['product_id'];
            $data['type'] = 'vendor.model';
            $data['available'] = ($product['quantity'] > 0 || $product['stock_status_id'] == $in_stock_id);
            $data['longitude'] = '55.99087690514633';
            $data['latitude'] = '92.88780214369098';
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


            
                $changeCategory = $this->model_catalog_category->getCategory( $product['category_id']);
                if($changeCategory['parent_id'] != "0" and $changeCategory['parent_id'] != "3"){
                    $data['categoryId'] = $changeCategory['parent_id'];
                } else {
                    $data['categoryId'] = $product['category_id'];
                }
            

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
                $data['description'] = preg_replace("/<([a-z][a-z0-9]*)[^>]*?(\/?)>/i",'<$1$2>', $product['description']);
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

            //Добавляем параметры
            $data['param'][] = array(
                'name'  => 'Дополнительно',
                'value' => 'Возможна примерка'
            );

            $data['param'][] = array(
                'name'  => 'Состояние',
                'value' => 'Новое'
            );


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
            $offers_with_options = $this->multipleOfferByOptions($offer, $options, $product, $scheme);
            foreach ($offers_with_options as $item) {
                $this->model_export_yandex_market_writer->addOffer($item);
            }
        }





        $this->model_export_yandex_market_writer->endOffers();
        // заголовки документа
        $this->model_export_yandex_market_writer->addFooter();
        $this->model_export_yandex_market_writer->flushToFile();

        printf('File created in: %F', microtime(true) - $start);
    }

    /**
     * @param array $offer
     * @param array $options
     * @param array $product
     * @param string $scheme
     * @return array
     */
    protected function multipleOfferByOptions(array $offer, array $options, array $product, $scheme = 'yml')
    {
        $offers_with_options = array();
        switch ($scheme) {
            case 'yml':

                if (count($options) > 0) {
                    $editedFirstOfferDescription = "<p>Размеры в наличии: ";
                    foreach ($options as $option_group) {
                        foreach ($option_group['option_value'] as $option_value) {
                            if ($option_value['quantity'] > 0) {
                                if ($editedFirstOfferDescription != "<p>Размеры в наличии: ") {
                                    $editedFirstOfferDescription .= ", ";
                                }                            
                                $editedFirstOfferDescription .= $option_value['name'];
                            }
                        }
                    }
                    $editedFirstOfferDescription .= "</p>";
                }
                $editedFirstOfferDescription .= $offer['data']['description'];
                $editedFirstOfferDescription .= '<p>Наш интернет-магазин гарантирует <i><b>отличное качество</b></i> продукции, прекрасный сервис и <i><b>максимально доступные цены</b></i>. <br>Данный товар в наличии в Красноярске. Также имеются и другие модели, полная размерная сетка.  <br>О наличии в магазине всегда уточняйте, так как модели могут быть на складе.<br><i><b>Возможность примерки товара перед покупкой!</b></i><br>Мы регулярно проводим <i><b>выгодные акции</b></i> и <i><b>сезонные распродажи</b></i>, а также постоянно расширяем ассортимент нашего магазина, поэтому выбрав нас, Вы, безусловно, останетесь довольны!<br>Отправляем Почтой России в другие регионы. 	<br><div><a href="https://sun9-6.userapi.com/c855124/v855124790/1c6784/WZVat0OsZWg.jpg" class="text-image-wrp" target="_blank" title="нажмите для просмотра в новом окне"><img src="https://sun9-6.userapi.com/c855124/v855124790/1c6784/WZVat0OsZWg.jpg" class="text-image"></a></div><br></p>';

                $offers_with_options[] = $offer;
                $offers_with_options[0]['data']['description'] = $editedFirstOfferDescription;
                $offers_with_options[0]['group_id'] = $product['product_id'];

                $offers_with_options[0]['data']['name'] = $offers_with_options[0]['data']['name'] . ' (' . $offers_with_options[0]['id'] . ')';

                $editedOtherOfferDescription = $offer['data']['description'] . '<p>Наш интернет-магазин гарантирует <i><b>отличное качество</b></i> продукции, прекрасный сервис и <i><b>максимально доступные цены</b></i>. <br>Данный товар в наличии в Красноярске. Также имеются и другие модели, полная размерная сетка.  <br>О наличии в магазине всегда уточняйте, так как модели могут быть на складе.<br><i><b>Возможность примерки товара перед покупкой!</b></i><br>Мы регулярно проводим <i><b>выгодные акции</b></i> и <i><b>сезонные распродажи</b></i>, а также постоянно расширяем ассортимент нашего магазина, поэтому выбрав нас, Вы, безусловно, останетесь довольны!<br>Отправляем Почтой России в другие регионы. 	<br><div><a href="https://sun9-6.userapi.com/c855124/v855124790/1c6784/WZVat0OsZWg.jpg" class="text-image-wrp" target="_blank" title="нажмите для просмотра в новом окне"><img src="https://sun9-6.userapi.com/c855124/v855124790/1c6784/WZVat0OsZWg.jpg" class="text-image"></a></div><br></p>';



                $offer['data']['description'] = $editedOtherOfferDescription;

                foreach ($options as $option_group) {
                    foreach ($option_group['option_value'] as $option_value) {
                        $current_offer = $offer;
                        if ($option_value['quantity'] <= 0) {
                            $current_offer['available'] = 'false';
                        }

                        if ($current_offer['available'] == 'false' ) {continue;}

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

                        $current_offer['data']['name'] = $current_offer['data']['name'] . ' (' . $current_offer['group_id'] . ') ' . $option_group['name'] . ': ' . $option_value['name'];
                            
                        
                        $offers_with_options[] = $current_offer;
                        
                    }
                }
                break;            
        }
        

        return $offers_with_options;
    }
}
