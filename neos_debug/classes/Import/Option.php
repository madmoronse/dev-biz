<?php

namespace Neos\classes\Import;

use Neos\Import1C\Entities\Characteristic;
use stdClass;

class Option extends AbstractImport
{
    /**
     * @param Characteristic $import
     * @return stdClass|null
     */
    public function fetch($import)
    {
        $option = $this->db->getRow(
            'SELECT o.* FROM ?n as o INNER JOIN ?n as od USING (option_id)
            WHERE od.name = ?s AND od.language_id = ?i LIMIT 1',
            $this->getTable('option'),
            $this->getTable('option_description'),
            $import->name,
            static::DEFAULT_LANGUAGE
        );
        if (!$option) {
            return null;
        }
        return (object) $option;
    }

    /**
     * @param Characteristic $import
     * @return stdClass
     */
    public function create($import)
    {
        $option = (object) [
            'type' => 'radio',
            'sort_order' => 1
        ];
        $this->setOptionData($option, $import);
        $this->atomicImport(function () use ($option, $import) {
            $this->db->query('INSERT INTO ?n SET ?u', $this->getTable('option'), (array) $option);
            $option->option_id = $import->id = $this->db->insertId();
            $this->db->query(
                'INSERT INTO ?n VALUES (?i, ?i, ?s)',
                $this->getTable('option_description'),
                $option->option_id,
                static::DEFAULT_LANGUAGE,
                $import->name
            );
        });
        return $option;
    }

    /**
     * @param stdClass $option
     * @param Characteristic $import
     * @param boolean $updated
     * @return stdClass
     */
    public function update(stdClass $option, $import, &$updated)
    {
        $import->id = $option->option_id;
        $updated = false;
        // Nothing to update here
        return $option;
    }

    /**
     * @param stdClass $option
     * @param Characteristic $import
     * @return void
     */
    protected function setOptionData(stdClass $option, Characteristic $import)
    {
        $option->ext_id = $import->name;
    }

    /**
     * @param Characteristic $import
     * @return array
     */
    public function getContext($import)
    {
        $context = [];
        if (!is_null($import->id)) {
            $context['option_id'] = $import->id;
        }
        $context['option_name'] = $import->name;
        return $context;
    }
}
