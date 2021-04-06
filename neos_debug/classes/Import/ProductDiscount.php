<?php

namespace Neos\classes\Import;

use Neos\Import1C\Entities\Offer;
use Neos\Import1C\Entities\OfferGroup;
use stdClass;

class ProductDiscount extends AbstractImport
{
    /**
     * @param OfferGroup $import
     * @return stdClass|null
     */
    public function fetch($import)
    {
        $offer = $import->getDefaultOffer();
        $prices = $this->preparePrices($offer);
        if (count($prices) > 0) {
            $discounts = $this->db->getInd(
                'customer_group_id',
                'SELECT * FROM ?n
                WHERE customer_group_id IN (?a) AND product_id = ?i',
                $this->getTable('product_discount'),
                array_keys($prices),
                $import->product_id
            );
        } else {
            $discounts = [];
        }
        $object = new stdClass();
        $object->discounts = array_map(function ($discount) {
            return (object) $discount;
        }, $discounts);
        $object->prices = $prices;
        return $object;
    }

    /**
     * Dummy method
     * @param OfferGroup $import
     * @return stdClass
     */
    public function create($import)
    {
        return new stdClass();
    }

    /**
     * @param stdClass $object
     * @param OfferGroup $import
     * @param boolean $updated
     * @return stdClass
     */
    public function update(stdClass $object, $import, &$updated)
    {
        $context = $this->getContext($import);
        $default_discount = [
            'product_id' => $import->product_id,
            'quantity' => 1,
            'priority' => 1,
            'date_start' => date('Y-m-d'),
            'date_end' => '2101-01-01'
        ];
        $table = $this->getTable('product_discount');
        $preserve_ids = [];
        foreach ($object->prices as $customer_group_id => $price) {
            $discount = $object->discounts[$customer_group_id] ?? null;
            // Create new discount
            if (is_null($discount)) {
                $discount = array_merge(
                    $default_discount,
                    [
                        'customer_group_id' => $customer_group_id,
                        'price' => $price->value
                    ]
                );
                $this->db->query(
                    'INSERT INTO ?n SET ?u',
                    $table,
                    $discount
                );
                $discount = (object) $discount;
                $discount->product_discount_id = $this->db->insertId();
                $object->discounts[$customer_group_id] = $discount;
                $this->logger->info('New price: ' . $discount->product_discount_id . ', customer_group_id: ' . $customer_group_id, $context);
            // Update existing discount
            } else {
                // Update if price has changed
                if ($price->value != $discount->price) {
                    $this->db->query(
                        'UPDATE ?n SET price = ?s WHERE product_discount_id = ?i',
                        $table,
                        $price->value,
                        $discount->product_discount_id
                    );
                } else {
                    $this->logger->debug('Price has not changed, customer_group_id: ' . $customer_group_id, $context);
                }
            }
            $preserve_ids[] = $discount->product_discount_id;
        }
        $updated = count($preserve_ids) > 0;
        // Delete other discounts
        $this->db->query(
            'DELETE FROM ?n WHERE product_id = ?i ?p',
            $table,
            $import->product_id,
            (count($preserve_ids) > 0 ? $this->db->parse('AND product_discount_id NOT IN (?a)', $preserve_ids) : '')
        );
        $removed = $this->db->affectedRows();
        if ($removed > 0) {
            $this->logger->notice('Removed discounts from database: ' . $removed, $context);
        }
        return $object;
    }

    /**
     * @param Offer $offer
     * @return array
     */
    protected function preparePrices(Offer $offer)
    {
        $prices = [];
        foreach ($offer->prices as $price) {
            if (!is_null($price->price_type->id) && $price->price_type->id !== static::DEFAULT_CUSTOMER_GROUP) {
                $prices[$price->price_type->id] = $price;
            }
        }
        return $prices;
    }

    /**
     * @param OfferGroup $import
     * @return array
     */
    public function getContext($import)
    {
        return [
            'offer_product_id' => $import->product_id
        ];
    }
}
