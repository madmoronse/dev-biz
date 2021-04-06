<?php

namespace Neos\classes\Import;

use Neos\Import1C\Entities\PriceType as ImportPriceType;
use stdClass;

class PriceType extends AbstractImport
{
    /**
     * Dummy method
     * @param ImportPriceType $import
     * @return stdClass|null
     */
    public function fetch($import)
    {
        return new stdClass();
    }

    /**
     * Dummy method
     * @param ImportPriceType $import
     * @return stdClass
     */
    public function create($import)
    {
        return new stdClass();
    }

    /**
     * @param stdClass $price_type
     * @param ImportPriceType $import
     * @param boolean $updated
     * @return stdClass
     */
    public function update(stdClass $price_type, $import, &$updated)
    {
        // Here we set custom group for price type
        switch ($import->name) {
            case 'Типовые правила продаж (Безпредоплаты розница)':
                $import->id = static::DEFAULT_CUSTOMER_GROUP;
                break;
            case 'опт':
                $import->id = 3;
                break;
            case 'дроп':
                $import->id = 4;
                break;
            default:
                $import->id = null;
        }
        $updated = false;
        // Nothing to update here
        return $price_type;
    }

    /**
     * @param ImportPriceType $import
     * @return array
     */
    public function getContext($import)
    {
        return [
            'price_type' => $import->name
        ];
    }
}
