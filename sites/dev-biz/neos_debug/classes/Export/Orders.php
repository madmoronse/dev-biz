<?php

namespace Neos\classes\Export;

use Neos\classes\Export\Writer\Writer;
use Neos\classes\Export\Writer\XML\Children;
use Neos\classes\Export\Writer\XML\Entity;
use Neos\classes\Export\Writer\XMLWriter;
use DateTime;

class Orders extends AbstractExport
{
    /**
     * @var string
     */
    protected $table = 'order';

    /**
     * @var string
     */
    protected $primary_key = 'order_id';

    /**
     * @param integer $order_id
     * @return array
     */
    public function fetchHistory(int $order_id): array
    {
        return $this->db->getAll('SELECT * FROM ?n WHERE order_id = ?i', DB_PREFIX . 'order_history', $order_id);
    }

    /**
     * @param integer $order_id
     * @return array
     */
    public function fetchProducts(int $order_id): array
    {
        $products = $this->db->getAll('SELECT * FROM ?n WHERE order_id = ?i', DB_PREFIX . 'order_product', $order_id);
        $resource = $this->db->query('SELECT * FROM ?n WHERE order_id = ?i', DB_PREFIX . 'order_option', $order_id);
        $options = [];
        while ($option = $this->db->fetch($resource)) {
            $options[$option['order_product_id']][] = $option;
        }
        foreach ($products as $key => $product) {
            if (isset($options[$product['order_product_id']])) {
                $products[$key]['options'] = $options[$product['order_product_id']];
            } else {
                $products[$key]['options'] = [];
            }
        }
        return $products;
    }

    /**
     * @param integer $order_id
     * @return array
     */
    public function fetchTotals(int $order_id): array
    {
        $resource = $this->db->query(
            'SELECT * FROM ?n WHERE order_id = ?i ORDER BY order_total_id ASC',
            DB_PREFIX . 'order_total',
            $order_id
        );
        $totals = [];
        while ($item = $this->db->fetch($resource)) {
            if (isset($totals[$item['code']])) {
                $this->logger->error("Duplicate totals for order: $order_id, code: {$item['code']}");
            }
            $totals[$item['code']] = $item['value'];
        }
        return $totals;
    }

    /**
     * @param integer $customer_id
     * @return array|false
     */
    public function fetchCustomer(int $customer_id)
    {
        return $this->db->getRow('SELECT * FROM ?n WHERE customer_id = ?i', DB_PREFIX . 'customer', $customer_id);
    }

    /**
     * @param integer $order_id
     * @return array
     */
    public function fetchImages(int $order_id): array
    {
        return $this->db->getAll('SELECT * FROM ?n WHERE order_id = ?i', DB_PREFIX . 'order_image', $order_id);
    }

    /**
     * @param integer $order_id
     * @return array
     */
    public function fetchComments(int $order_id): array
    {
        return $this->db->getAll('SELECT * FROM ?n WHERE order_id = ?i', DB_PREFIX . 'order_comments', $order_id);
    }

    /**
     * @param integer $order_id
     * @return array
     */
    public function fetchPrepays(int $order_id): array
    {
        return $this->db->getAll('SELECT * FROM ?n WHERE buyer_order_id = ?i', DB_PREFIX . 'prepay_orders', $order_id);
    }

    /**
     * @param integer $order_id
     * @return array
     */
    public function fetchShippingParams(int $order_id)
    {
        return $this->db->getRow('SELECT * FROM ?n WHERE order_id = ?i', DB_PREFIX . 'order_shipping_params', $order_id);
    }

    /**
     * @param Writer $writer
     * @param array $data
     * @return mixed
     */
    public function prepareEntityForWriter(Writer $writer, array $data)
    {
        switch (get_class($writer)) {
            default:
                return null;
            case XMLWriter::class:
                return $this->prepareXMLEntity($data);
        }
    }

    /**
     * @param integer $status_id
     * @return string
     */
    protected function getStatusName(int $status_id)
    {
        static $statuses;
        if (is_null($statuses)) {
            $statuses = $this->db->getIndCol(
                'id',
                'SELECT order_status_id as id, name FROM ?n WHERE language_id = ?i',
                DB_PREFIX . 'order_status',
                1
            );
        }
        return $statuses[$status_id] ?? '';
    }

    /**
     * @param integer $settlement_id
     * @return array
     */
    protected function getCityType(int $settlement_id)
    {
        static $settlements;
        if (is_null($settlements)) {
            $settlements = $this->db->getInd(
                'id',
                'SELECT naselenniy_punkt_id as id, `name`, `code` FROM ?n',
                DB_PREFIX . 'naselenniy_punkt'
            );
        }
        $city_type = 'Город';
        $prefix = 'г. ';
        if (isset($settlements[$settlement_id]) && $settlements[$settlement_id]['name'] !== 'Город') {
            $city_type = 'Населенный пункт';
            $prefix = $settlements[$settlement_id]['code'] . ' ';
        }
        return [$city_type, $prefix];
    }

    /**
     * @param array $data
     * @return Entity
     */
    protected function prepareXMLEntity(array $data): Entity
    {
        $order_id = $data['order_id'];
        $shipping_params = $this->fetchShippingParams($order_id);
        $entity = new Entity('Документ');
        $entity->addTag('Ид', $order_id);
        $entity->addTag('Номер', $order_id);
        $entity->addTag('Дата', $data['date_added']);
        $entity->addTag('ДатаИзменения', $data['date_modified']); // не по стандарту
        $entity->addTag('ХозОперация', 'Заказ товара');
        $entity->addTag('Контрагенты', $this->prepareXMLContragents($data));
        // начало - не по стандарту (кастомные параметры доставки)
        if ($shipping_params) {
            if ($shipping_params['inventory_inspection']) {
                $entity->addTag('ОписьВложения', $shipping_params['inventory_inspection']);
            }
            if ($shipping_params['try_on']) {
                $entity->addTag('ВозможностьПримерки', $shipping_params['try_on']);
            }
            if ($shipping_params['warehouse_code']) {
                $entity->addTag('КодПунктаВыдачи', $shipping_params['warehouse_code']);
            }
            if ($shipping_params['cdek_city_id']) {
                $entity->addTag('КодГородаСДЭК', $shipping_params['cdek_city_id']);
            }
            if ($shipping_params['partial_delivery']) {
                $entity->addTag('ЧастичнаяДоставка', $shipping_params['partial_delivery']);
            }
        }
        if ($data['tracking_number']) {
            $entity->addTag('ТрекНомер', $data['tracking_number']);
        }
        // конец - не по стандарту
        if (!empty($data['comment'])) {
            $entity->addTag('cdata:Комментарий', $data['comment']);
        }
        $entity->addTag('Итого', $this->preparePrice($data['total']));
        $totals = $this->fetchTotals($order_id);
        // начало - не по стандарту
        $entity->addTag('СуммаБезДоставки', $this->preparePrice($totals['sub_total'] ?? 0));
        $entity->addTag('Доставка', $this->preparePrice($totals['shipping'] ?? 0));
        $entity->addTag('Предоплата', $this->preparePrice($data['prepayment']));
        $entity->addTag('НаложенныйПлатеж', $this->preparePrice($data['cash_on_delivery']));
        if (isset($totals['markupdropshipping'])) {
            $entity->addTag('НаценкаДропшиппераНаЗаказ', $this->preparePrice($totals['markupdropshipping']));
        }
        $entity->addTag('Валюта', $data['currency_code']);
        $entity->addTag('СпособДоставки', $data['shipping_code']);
        $entity->addTag('ДополнительнаяИнформацияОДоставке', $data['shipping_method']);
        $entity->addTag('СпособОплаты', $data['payment_method']);
        $entity->addTag('ИсторияЗаказа', $this->prepareXMLHistory($order_id));
        $entity->addTag('СтатусЗаказа', $this->getStatusName($data['order_status_id']));
        if (!empty($data['replacement_for'])) {
            $entity->addTag('ОбменПоЗаказу', $data['replacement_for']);
        }
        $checks = $this->prepareXMLReceiptImages($order_id);
        if ($checks->hasChildren()) {
            $entity->addTag('Чеки', $checks);
        }
        $manager_comments = $this->prepareXMLManagerComments($order_id);
        if ($manager_comments->hasChildren()) {
            $entity->addTag('КомментарииМенеджера', $manager_comments);
        }
        $order_prepays = $this->prepareXMLOrderPrepays($order_id);
        if ($order_prepays->hasChildren()) {
            $entity->addTag('ДанныеПоПредоплате', $order_prepays);
        }
        // конец - не по стандарту
        $entity->addTag('Товары', $this->prepareXMLProducts($order_id));

        return $entity;
    }

    /**
     * @param array $data
     * @return Children
     */
    protected function prepareXMLContragents(array $data): Children
    {
        $contragents = new Children('Контрагенты');
        $customer = $this->fetchCustomer((int) $data['customer_id']);
        if ($customer) {
            $payer = $this->createContragent($customer, '', [
                'Ид' => $customer['customer_id'],
                'Группа' => $this->getCustomerGroup($customer['customer_group_id']),
                'Роль' => 'Плательщик'
            ]);
            $payer->addTag('Адрес', $this->prepareXMLAddress($data, 'payment', 'Адрес'));
            $contragents->addChild($payer);
        } else {
            $this->logger->warning('Customer not found: ' . $data['customer_id']);
        }
        $receiver = $this->createContragent($data, 'shipping_', [
            'Роль' => 'Получатель'
        ], ['email', 'telephone']);
        $this->setContactsForContragent($receiver, ['telephone' => $data['telephone']]);
        $receiver->addTag('Адрес', $this->prepareXMLAddress($data, 'shipping', 'Адрес'));
        $contragents->addChild($receiver);
        return $contragents;
    }

    /**
     * @param array $data
     * @param string $prefix
     * @param array $attributes
     * @return Entity
     */
    protected function createContragent(
        array $data,
        string $prefix = '',
        array $attributes = [],
        array $excludes = []
    ) {
        if (count($excludes) > 0) {
            $data = array_diff_key($data, array_flip($excludes));
        }
        $contragent = new Entity('Контрагент');
        if (isset($attributes['Ид'])) {
            $contragent->addTag('Ид', $attributes['Ид']);
        }
        if (isset($attributes['Группа'])) {
            $contragent->addTag('Группа', $attributes['Группа']);
        }
        $contragent->addTag('ПолноеНаименование', $this->prepareContragentFullname([
            $data[$prefix . 'lastname'],
            $data[$prefix . 'firstname'],
            $data[$prefix . 'middlename']
        ]));
        if (isset($attributes['Роль'])) {
            $contragent->addTag('Роль', $attributes['Роль']);
        }
        if (!empty($data[$prefix . 'lastname'])) {
            $contragent->addTag('Фамилия', $data[$prefix . 'lastname']);
        }
        if (!empty($data[$prefix . 'firstname'])) {
            $contragent->addTag('Имя', $data[$prefix . 'firstname']);
        }
        if (!empty($data[$prefix . 'middlename'])) {
            $contragent->addTag('Отчество', $data[$prefix . 'middlename']);
        }
        $this->setContactsForContragent($contragent, $data, $prefix);
        return $contragent;
    }

    protected function setContactsForContragent(Entity $contragent, array $data, string $prefix = '')
    {
        $contacts = new Children('Контакты');
        if (!empty($data[$prefix . 'telephone'])) {
            $phone = new Entity('Контакт');
            $phone->addTag('Тип', 'Телефон мобильный');
            $phone->addTag('Значение', $data[$prefix . 'telephone']);
            $contacts->addChild($phone);
        }
        if (!empty($data[$prefix . 'email'])) {
            $email = new Entity('Контакт');
            $email->addTag('Тип', 'Почта');
            $email->addTag('Значение', $data[$prefix . 'email']);
            $contacts->addChild($email);
        }
        if ($contacts->hasChildren()) {
            $contragent->addTag('Контакты', $contacts);
        }
        return $contragent;
    }

    /**
     * @param string[] $parts
     * @return string
     */
    protected function prepareContragentFullname(array $parts)
    {
        $fullname = [];
        foreach ($parts as $part) {
            if (!empty($part)) {
                $fullname[] = trim($part);
            }
        }
        return implode(' ', $fullname);
    }

    /**
     * @param integer $customer_group_id
     * @return string
     */
    protected function getCustomerGroup(int $customer_group_id)
    {
        static $groups;
        if (is_null($groups)) {
            $groups = $this->db->getIndCol(
                'id',
                'SELECT customer_group_id as id, `description` FROM ?n WHERE language_id = ?i',
                DB_PREFIX . 'customer_group_description',
                1
            );
        }
        return $groups[$customer_group_id] ?? '';
    }

    /**
     * @param array $data
     * @param string $type
     * @param string $name
     * @return Entity
     */
    protected function prepareXMLAddress(array $data, string $type, string $name): Entity
    {
        $address = new Entity($name);
        $fields = [];
        $presentation = [];
        // Postcode
        if (!empty($data[$type . '_postcode'])) {
            $presentation[] = $data[$type . '_postcode'];
            $postcode = new Entity('АдресноеПоле');
            $postcode->addTag('Тип', 'Почтовый индекс');
            $postcode->addTag('Значение', $data[$type . '_postcode']);
            $fields[] = $postcode;
        }
        // Country
        $presentation[] = $data[$type . '_country'];
        $country = new Entity('АдресноеПоле');
        $country->addTag('Тип', 'Страна');
        $country->addTag('Значение', $data[$type. '_country']);
        $fields[] = $country;
        // City
        list($city_type, $prefix) = $this->getCityType(
            $type === 'shipping' && !empty($data['shipping_naselenniy_punkt_id']) ? $data['shipping_naselenniy_punkt_id'] : 0
        );
        $presentation[] = (preg_match('/^(г|гор|д)[\s.]/ui', $data[$type . '_city']) ? '' : $prefix) . $data[$type . '_city'];
        $city = new Entity('АдресноеПоле');
        $city->addTag('Тип', $city_type);
        $city->addTag('Значение', $data[$type. '_city']);
        $fields[] = $city;
        // Street
        $presentation[] = (preg_match('/^(пр-кт|ул|улица|проспект)[\s.]|[\s.](пр-кт|ул|улица|проспект)$/ui', $data[$type . '_address_1']) ? '' : 'ул. ') . $data[$type . '_address_1'];
        $street = new Entity('АдресноеПоле');
        $street->addTag('Тип', 'Улица');
        $street->addTag('Значение', $data[$type . '_address_1']);
        $fields[] = $street;
        // House
        if (!empty($data['shipping_address_2'])) {
            $data[$type . '_address_2'] = trim($data[$type . '_address_2']);
            $presentation[] = (preg_match('/^\D/', $data[$type . '_address_2']) ? '' : 'дом ')
                . $data[$type . '_address_2'];
            $house = new Entity('АдресноеПоле');
            $house->addTag('Тип', 'Дом');
            $house->addTag('Значение', $data[$type . '_address_2']);
            $fields[] = $house;
        }
        // Appartment
        if (!empty($data['shipping_address_3'])) {
            $data[$type . '_address_3'] = trim($data[$type . '_address_3']);
            $presentation[] = (mb_strtolower($data[$type . '_address_3']) === 'частный дом'
                || preg_match('/^\D/', $data[$type . '_address_3']) ? '' : 'кв. ')
                . $data[$type . '_address_3'];
            $appartment = new Entity('АдресноеПоле');
            $appartment->addTag('Тип', 'Квартира');
            $appartment->addTag('Значение', $data[$type . '_address_3']);
            $fields[] = $appartment;
        }
        // Building
        if (!empty($data[$type . '_address_4'])) {
            $presentation[] = (preg_match('/^\D/', $data[$type . '_address_4']) ? '' : 'корпус')
                . $data[$type . '_address_4'];
            $building = new Entity('АдресноеПоле');
            $building->addTag('Тип', 'Корпус');
            $building->addTag('Значение', $data[$type . '_address_4']);
            $fields[] = $building;
        }
        $address->addTag('Представление', implode(', ', $presentation));
        $address->addTag('АдресноеПоле', $fields);
        return $address;
    }

    /**
     * @param integer $order_id
     * @return Children
     */
    protected function prepareXMLHistory(int $order_id): Children
    {
        $items = $this->fetchHistory($order_id);
        $history = new Children('ИсторияЗаказа');
        foreach ($items as $item) {
            $entry = new Entity('ЗначениеРеквизита');
            $entry->addTag('Статус', $this->getStatusName($item['order_status_id']));
            $entry->addTag('Дата', $item['date_added']);
            if (!empty($item['comment'])) {
                $entry->addTag('cdata:Комментарий', $item['comment']);
            }
            $history->addChild($entry);
        }
        return $history;
    }
    
    protected function prepareXMLOrderPrepays(int $order_id): Children
    {
        $order_prepays = $this->fetchPrepays($order_id);
        $prepays = new Children('ДанныеПоПредоплате');
        foreach ($order_prepays as $order_prepay) {
            $prepay = new Entity('Предоплата');
            $prepay->addTag('ИдПартнера', $order_prepay['partner_id']);
            $prepay->addTag('ТипПартнера', $this->getCustomerGroup($order_prepay['partnet_type'])); 
            $prepay->addTag('Сумма', $order_prepay['order_summ']);
            $prepay->addTag('СтатусЗаказа', $order_prepay['order_status']);
            $prepay->addTag('ФиоПокупателя', $order_prepay['buyer_fio']);
            $prepay->addTag('ПочтаПокупателя', $order_prepay['buyer_email']);
            $prepay->addTag('ТелефонПокупателя', $order_prepay['buyer_phone']);
            $date = new DateTime();
            $date->setTimestamp($order_prepay['date_create']);
            $prepay->addTag('ДатаСоздания', $date->format('Y-m-d H:i:s'));
            if (!empty($order_prepay['date_pay'])) {
                $date->setTimestamp($order_prepay['date_pay']);
                $prepay->addTag('ДатаОплаты', $date->format('Y-m-d H:i:s'));
            }
            if (!empty($order_prepay['date_order_attach'])) {
                $date->setTimestamp($order_prepay['date_order_attach']);
                $prepay->addTag('ДатаПрикрепленияКОрдеру', $date->format('Y-m-d H:i:s'));
            }
            $prepays->addChild($prepay);
        }
        return $prepays;
    }

    protected function prepareXMLManagerComments(int $order_id): Children
    {
        $manager_comments = $this->fetchComments($order_id);
        $comments = new Children('КомментарииМенеджера');
        foreach ($manager_comments as $manager_comment) {
            $comment = new Entity('Комментарий');
            $comment->addTag('Дата', $manager_comment['date_added']);
            $comment->addTag('Текст', $manager_comment['comment']);
            $comments->addChild($comment);
        }
        return $comments;
    }

    protected function prepareXMLReceiptImages(int $order_id): Children
    {
        $images = $this->fetchImages($order_id);
        $receipts = new Children('Чеки');
        foreach ($images as $image) {
            $receipt = new Entity('Чек');
            $receipt->addTag('Ссылка', $image['image']);
            $receipts->addChild($receipt);
        }
        return $receipts;
    }

    protected function prepareXMLProducts(int $order_id): Children
    {
        $items = $this->fetchProducts($order_id);
        $products = new Children('Товары');
        foreach ($items as $item) {
            $product = new Entity('Товар');
            $product->addTag('Ид', $item['product_id']);
            $product->addTag('Артикул', $item['product_id']);
            $product->addTag('Наименование', $item['name']);
            $product->addTag('Количество', $item['quantity']);
            $product->addTag('ЦенаЗаЕдиницу', $this->preparePrice($item['price']));
            if ($item['drop_saller_price'] > 0) {
                $product->addTag('ЦенаДропшиппераЗаЕдиницу', $this->preparePrice($item['drop_saller_price']));
            }
            $product->addTag('Сумма', $this->preparePrice($item['total']));
            $characteristics = new Children('ХарактеристикиТовара');
            foreach ($item['options'] as $option) {
                $char = new Entity('ХарактеристикаТовара');
                $char->addTag('Наименование', $option['name']);
                $char->addTag('Значение', $option['value']);
                $characteristics->addChild($char);
            }
            if ($characteristics->hasChildren()) {
                $product->addTag('ХарактеристикиТовара', $characteristics);
            }
            $products->addChild($product);
        }
        return $products;
    }

    protected function preparePrice($price)
    {
        return number_format($price, 2, '.', '');
    }
}
