<?php

namespace Neos\classes\Import;

use Neos\Import1C\Entities\CharacteristicValue;
use stdClass;

class OptionValue extends AbstractImport
{
    /**
     * @param CharacteristicValue $import
     * @return stdClass|null
     */
    public function fetch($import)
    {
        if (is_null($import->characteristic->id)) {
            throw new \RuntimeException('Expected characteristic to have id, characteristic value: ' . $import->value);
        }
        $option_value = $this->db->getRow(
            'SELECT o.* FROM ?n as o INNER JOIN ?n as od USING (option_value_id)
            WHERE od.option_id = ?i AND od.name = ?s AND od.language_id = ?i LIMIT 1',
            $this->getTable('option_value'),
            $this->getTable('option_value_description'),
            $import->characteristic->id,
            $import->value,
            static::DEFAULT_LANGUAGE
        );
        if (!$option_value) {
            return null;
        }
        return (object) $option_value;
    }

    /**
     * @param CharacteristicValue $import
     * @return stdClass
     */
    public function create($import)
    {
        $option_value = (object) [
            'image' => ''
        ];
        $this->setOptionValueData($option_value, $import);
        $this->atomicImport(function () use ($option_value, $import) {
            $this->db->query('INSERT INTO ?n SET ?u', $this->getTable('option_value'), (array) $option_value);
            $option_value->option_value_id = $import->id = $this->db->insertId();
            $this->db->query(
                'INSERT INTO ?n VALUES (?i, ?i, ?i, ?s)',
                $this->getTable('option_value_description'),
                $option_value->option_value_id,
                static::DEFAULT_LANGUAGE,
                $import->characteristic->id,
                $import->value
            );
        });
        return $option_value;
    }

    /**
     * @param stdClass $option_value
     * @param CharacteristicValue $import
     * @param boolean $updated
     * @return stdClass
     */
    public function update(stdClass $option_value, $import, &$updated)
    {
        $import->id = $option_value->option_value_id;
        $updated = false;
        // Nothing to update here
        return $option_value;
    }

    /**
     * @param stdClass $option
     * @param CharacteristicValue $import
     * @return void
     */
    protected function setOptionValueData(stdClass $option_value, CharacteristicValue $import)
    {
        $option_value->ext_id = $import->value;
        $option_value->sort_order = (int) $import->value;
        $option_value->option_id = $import->characteristic->id;
    }

    /**
     * @param CharacteristicValue $import
     * @return array
     */
    public function getContext($import)
    {
        $context = [];
        if (!is_null($import->id)) {
            $context['option_value_id'] = $import->id;
        }
        $context['option_value'] = $import->value;
        return $context;
    }
}
