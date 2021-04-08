<?php

namespace Neos\classes\Import;

use Neos\Import1C\Entities\OfferGroup;
use stdClass;

class ProductOptionValue extends AbstractImport
{

    /**
     * Current product options
     * @var array
     */
    protected $product_options = [];

    protected $current_product_id;

    /**
     * @param OfferGroup $import
     * @return stdClass
     */
    public function import($import)
    {
        if ($this->current_product_id !== (int) $import->product_id) {
            throw new \InvalidArgumentException('You forgot to set product options');
        }
        return parent::import($import);
    }

    /**
     * @param integer $product_id
     * @param array $product_options
     * @return void
     */
    public function setProductOptions(int $product_id, array $product_options)
    {
        $this->current_product_id = $product_id;
        $this->product_options = $product_options;
    }

    /**
     * @param OfferGroup $import
     * @return stdClass|null
     */
    public function fetch($import)
    {
        // Get offers
        $offers = array_reduce($import->offers, function ($carry, $offer) {
            if (count($offer->characteristics) === 0) {
                return $carry;
            }
            // There should be only one characteristic value
            $characteristic_value = $offer->characteristics[0];
            if (is_null($characteristic_value->id) || is_null($characteristic_value->characteristic->id)) {
                $this->logger->warning(
                    'Characteristic or characteristic_value id is null, characteristic value: '
                    . $characteristic_value->value
                );
            } else {
                if (!isset($carry[$characteristic_value->id])) {
                    $carry[$characteristic_value->id] = (object) [
                        'quantity' => $offer->quantity,
                        'option_id' => $characteristic_value->characteristic->id
                    ];
                } else {
                    $carry[$characteristic_value->id]->quantity += $offer->quantity;
                }
            }
            return $carry;
        }, []);
        if (count($offers) > 0) {
            $option_values = $this->db->getInd(
                'option_value_id',
                'SELECT product_option_value_id, product_option_id, option_id, option_value_id, quantity FROM ?n
                WHERE option_value_id IN (?a) AND product_id = ?i',
                $this->getTable('product_option_value'),
                array_keys($offers),
                $import->product_id
            );
        } else {
            $option_values = [];
        }
        $object = new stdClass();
        $object->option_values = array_map(function ($option_value) {
            return (object) $option_value;
        }, $option_values);
        $object->offers = $offers;
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
            'subtract' => 1,
            'price' => 0,
            'price_prefix' => '-',
            'points' => 0,
            'points_prefix' => '+',
            'weight' => 0,
            'weight_prefix' => '+'
            
        ];
        $table = $this->getTable('product_option_value');
        $preserve_ids = [];
        foreach ($object->offers as $option_value_id => $offer) {
            $product_option_value = $object->option_values[$option_value_id] ?? null;
            $product_option = $this->product_options[$offer->option_id] ?? null;
            if (is_null($product_option)) {
                $this->logger->alert('Product option not found for option: ' . $offer->option_id, $context);
                continue;
            }
            // Create new option
            if (is_null($product_option_value)) {
                $product_option_value = array_merge(
                    $default_option,
                    [
                        'product_option_id' => $product_option->product_option_id,
                        'option_id' => $offer->option_id,
                        'option_value_id' => $option_value_id,
                        'quantity' => $offer->quantity,
                    ]
                );
                $this->db->query(
                    'INSERT INTO ?n SET ?u',
                    $table,
                    $product_option_value
                );
                $product_option_value = (object) $product_option_value;
                $product_option_value->product_option_value_id = $this->db->insertId();
                $object->option_values[$option_value_id] = $product_option_value;
                $this->logger->info('New product option value: ' . $product_option_value->product_option_value_id . ', option_value_id: ' . $option_value_id, $context);
            // Update existing option
            } else {
                $should_update = false;
                // Log if product option id has changed
                if ($product_option_value->product_option_id != $product_option->product_option_id) {
                    $this->logger->alert(
                        "Product option id dismatch, prev: {$product_option_value->product_option_id}, new: {$product_option->product_option_id}",
                        $context
                    );
                    $should_update = true;
                }
                if ($product_option_value->option_id != $offer->option_id) {
                    $this->logger->alert(
                        "Option id dismatch, prev: {$product_option_value->option_id}, new: {$offer->option_id}",
                        $context
                    );
                    $should_update = true;
                }
                $should_update = $should_update || $offer->quantity != $product_option_value->quantity;
                if ($should_update) {
                    $this->db->query(
                        'UPDATE ?n SET product_option_id = ?i, option_id = ?i, quantity = ?i, points_prefix = \'+\' WHERE product_option_value_id = ?i',
                        $table,
                        $product_option->product_option_id,
                        $offer->option_id,
                        $offer->quantity,
                        $product_option_value->product_option_value_id
                    );
                } else {
                    $this->logger->debug('Nothing changed, option_value_id: ' . $option_value_id, $context);
                }
            }
            $preserve_ids[] = $product_option_value->product_option_value_id;
        }
        $updated = count($preserve_ids) > 0;
        // Set quantity equal to zero (also change points prefix so that affected rows will be correct)
        $this->db->query(
            'UPDATE ?n SET quantity = 0, points_prefix = \'-\' WHERE product_id = ?i ?p',
            $table,
            $import->product_id,
            (count($preserve_ids) > 0 ? $this->db->parse('AND product_option_value_id NOT IN (?a)', $preserve_ids) : '')
        );
        $affected = $this->db->affectedRows();
        if ($affected > 0) {
            $this->logger->notice('Set quantity to zero for product option values in database: ' . $affected, $context);
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
