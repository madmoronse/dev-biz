<?php

namespace Neos\classes\Import;

use Neos\Import1C\Entities\Property;
use stdClass;

class Attribute extends AbstractImport
{
    const IGNORE_ATTRIBUTES = [
        'полное наименование',
        'выводить на сайт',
        'размер обуви',
        'размер одежды',
        'размер аксессуаров',
        'полное наименование'
    ];

    /**
     * @param Property $import
     * @return stdClass|null
     */
    public function fetch($import)
    {
        if (in_array(mb_strtolower($import->name), static::IGNORE_ATTRIBUTES)) {
            throw new Exceptions\ImportIgnoreException('Ignoring attribute name: ' . $import->name);
        }
        $attribute = $this->db->getRow(
            'SELECT a.* FROM ?n as a INNER JOIN ?n as ad USING (attribute_id)
            WHERE ad.name = ?s AND ad.language_id = ?i LIMIT 1',
            $this->getTable('attribute'),
            $this->getTable('attribute_description'),
            $import->name,
            static::DEFAULT_LANGUAGE
        );
        if (!$attribute) {
            return null;
        }
        return (object) $attribute;
    }

    /**
     * @param Property $import
     * @return stdClass
     */
    public function create($import)
    {
        $attribute = (object) [
            'sort_order' => $this->db->getOne('SELECT count(*) FROM ?n', $this->getTable('attribute')),
            'ext_id' => null,
            'attribute_group_id' => static::DEFAULT_PRODUCT_ATTRIBUTE_GROUP
        ];
        $this->atomicImport(function () use ($attribute, $import) {
            $this->db->query('INSERT INTO ?n SET ?u', $this->getTable('attribute'), (array) $attribute);
            $attribute->attribute_id = $import->id = $this->db->insertId();
            $this->db->query(
                'INSERT INTO ?n VALUES (?i, ?i, ?s)',
                $this->getTable('attribute_description'),
                $attribute->attribute_id,
                static::DEFAULT_LANGUAGE,
                $import->name
            );
        });
        return $attribute;
    }

    /**
     * @param stdClass $attribute
     * @param Characteristic $import
     * @param boolean $updated
     * @return stdClass
     */
    public function update(stdClass $attribute, $import, &$updated)
    {
        $import->id = $attribute->attribute_id;
        $updated = false;
        // Nothing to update here
        return $attribute;
    }

    /**
     * @param Characteristic $import
     * @return array
     */
    public function getContext($import)
    {
        $context = [];
        if (!is_null($import->id)) {
            $context['attribute_id'] = $import->id;
        }
        $context['attribute_name'] = $import->name;
        return $context;
    }
}
