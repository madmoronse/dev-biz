<?php

namespace Neos\classes\Import;

use Neos\Import1C\Entities\OfferGroup;
use stdClass;

class ProductOption extends AbstractImport
{
    /**
     * @param OfferGroup $import
     * @return stdClass|null
     */
    public function fetch($import)
    {
        // Get unique characteristics
        $characteristics = array_reduce($import->offers, function ($carry, $offer) {
            foreach ($offer->characteristics as $characteristic_value) {
                if (is_null($characteristic_value->characteristic->id)) {
                    $this->logger->warning(
                        'Expected characteristic to have id, characteristic value: ' . $characteristic_value->value
                    );
                } else {
                    $carry[$characteristic_value->characteristic->id] = $characteristic_value->characteristic;
                }
            }
            return $carry;
        }, []);
        if (count($characteristics) > 0) {
            $options = $this->db->getInd(
                'option_id',
                'SELECT product_option_id, option_id FROM ?n
                WHERE option_id IN (?a) AND product_id = ?i',
                $this->getTable('product_option'),
                array_keys($characteristics),
                $import->product_id
            );
        } else {
            $options = [];
        }
        $object = new stdClass();
        $object->options = array_map(function ($option) {
            return (object) $option;
        }, $options);
        $object->characteristics = $characteristics;
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
        $default_option = [
            'product_id' => $import->product_id,
            'option_value' => '',
            'required' => 1
        ];
        $table = $this->getTable('product_option');
        $preserve_ids = [];
        foreach ($object->characteristics as $option_id => $characteristic) {
            $product_option = $object->options[$option_id] ?? null;
            // Create new option
            if (is_null($product_option)) {
                $product_option = array_merge(
                    $default_option,
                    [
                        'option_id' => $option_id
                    ]
                );
                $this->db->query(
                    'INSERT INTO ?n SET ?u',
                    $table,
                    $product_option
                );
                $product_option = (object) $product_option;
                $product_option->product_option_id = $this->db->insertId();
                $object->options[$option_id] = $product_option;
                $this->logger->info('New product option: ' . $product_option->product_option_id . ', option_id: ' . $option_id, $context);
            }
            $preserve_ids[] = $product_option->product_option_id;
        }
        // Set as not required (that's how we will now it is not used anymore)
        $this->db->query(
            'UPDATE ?n SET `required` = 0 WHERE product_id = ?i ?p',
            $table,
            $import->product_id,
            (count($preserve_ids) > 0 ? $this->db->parse('AND product_option_id NOT IN (?a)', $preserve_ids) : '')
        );
        $updated = count($preserve_ids) > 0;
        $affected = $this->db->affectedRows();
        if ($affected > 0) {
            $this->logger->notice('Marked product options in database as unused: ' . $affected, $context);
        }
        return $object;
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
