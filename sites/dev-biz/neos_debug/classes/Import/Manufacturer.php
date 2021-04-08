<?php

namespace Neos\classes\Import;

use Neos\Import1C\Entities\Manufacturer as ImportManufacturer;
use Neos\Import1C\Helpers\Str;
use stdClass;

class Manufacturer extends AbstractImport
{
    /**
     * @param ImportManufacturer $import
     * @return stdClass|null
     */
    public function fetch($import)
    {
        $manufacturer = $this->db->getRow(
            'SELECT * FROM ?n WHERE `name` = ?s LIMIT 1',
            $this->getTable('manufacturer'),
            $import->name
        );
        if (!$manufacturer) {
            return null;
        }
        return (object) $manufacturer;
    }

    /**
     * @param ImportManufacturer $import
     * @return stdClass
     */
    public function create($import)
    {
        $manufacturer = (object) [
            'name' => $import->name,
            'image' => '',
            'sort_order' => 1,
            'ext_id' => $import->name
        ];
        $this->atomicImport(function () use ($manufacturer, $import) {
            $this->db->query('INSERT INTO ?n SET ?u', $this->getTable('manufacturer'), (array) $manufacturer);
            $manufacturer->manufacturer_id = $import->id = $this->db->insertId();
            $this->db->query(
                "INSERT INTO ?n VALUES (?i, ?i, ?s, '', '', '', '')",
                $this->getTable('manufacturer_description'),
                $manufacturer->manufacturer_id,
                static::DEFAULT_LANGUAGE,
                $import->name
            );
            $this->db->query(
                'INSERT INTO ?n VALUES (?i, ?i)',
                $this->getTable('manufacturer_to_store'),
                $manufacturer->manufacturer_id,
                static::DEFAULT_STORE
            );
            $alias_id = $this->createUrlAlias(
                'manufacturer_id="' . $manufacturer->manufacturer_id . '"',
                Str::urlSafe($import->name)
            );
            $this->logger->debug(
                'Created url alias, id: ' . $alias_id,
                $this->getContext($import)
            );
        });
        return $manufacturer;
    }

    /**
     * @param stdClass $manufacturer
     * @param ImportManufacturer $import
     * @param boolean $updated
     * @return stdClass
     */
    public function update(stdClass $manufacturer, $import, &$updated)
    {
        $import->id = $manufacturer->manufacturer_id;
        $updated = false;
        // Nothing to update here
        return $manufacturer;
    }

    /**
     * @param ImportManufacturer $import
     * @return array
     */
    public function getContext($import)
    {
        $context = [];
        if (!is_null($import->id)) {
            $context['manufacturer_id'] = $import->id;
        }
        $context['manufacturer_name'] = $import->name;
        return $context;
    }
}
