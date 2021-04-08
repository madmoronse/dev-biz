<?php

namespace Neos\classes\Import\Helpers;

use Neos\classes\Import\AbstractImport;
use Neos\Import1C\Dictionaries\OrderStatusDictionary;
use Neos\Import1C\Entities\OrderStatus as ImportOrderStatus;
use Neos\libraries\SafeMySQL;

class OrderStatus
{
    /**
     * @param OrderStatusDictionary $dictionary
     * @param SafeMySQL $db
     * @return void
     */
    public static function fillOrderStatusDictionary(OrderStatusDictionary $dictionary, SafeMySQL $db)
    {
        $data = $db->getAll(
            'SELECT * FROM ?n WHERE language_id = ?i',
            DB_PREFIX . 'order_status',
            AbstractImport::DEFAULT_LANGUAGE
        );
        foreach ($data as $item) {
            $status = new ImportOrderStatus();
            $status->id = $item['order_status_id'];
            $status->name = $item['name'];
            $dictionary->addOrderStatus($status);
        }
    }
}
