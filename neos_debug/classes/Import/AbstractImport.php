<?php

namespace Neos\classes\Import;

use Neos\Import1C\Entities\Entity;
use Neos\libraries\SafeMySQL;
use Psr\Log\LoggerInterface;
use stdClass;

abstract class AbstractImport
{

    const DEFAULT_CUSTOMER_GROUP = 1;
    const DEFAULT_LANGUAGE = 1;
    const DEFAULT_PRODUCT_ATTRIBUTE_GROUP = 1;
    const DEFAULT_STORE = 0;

    /**
     * @var SafeMySQL
     */
    protected $db;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var int updated counter
     */
    protected $updated = 0;

    /**
     * @var int created counter
     */
    protected $created = 0;

    /**
     * @var array
     */
    protected $tables = [
        'product' => 'oc_product',
        'product_discount' => 'oc_product_discount',
        'product_option' => 'oc_product_option',
        'product_option_value' => 'oc_product_option_value',
        'product_attribute' => 'oc_product_attribute',
        'product_description' => 'oc_product_description',
        'product_image' => 'oc_product_image',
        'product_to_store' => 'oc_product_to_store',
        'product_to_category' => 'oc_product_to_category',
        'product_filter' => 'oc_product_filter',
        'option' => 'oc_option',
        'option_description' => 'oc_option_description',
        'option_value' => 'oc_option_value',
        'option_value_description' => 'oc_option_value_description',
        'attribute' => 'oc_attribute',
        'attribute_description' => 'oc_attribute_description',
        'manufacturer' => 'oc_manufacturer',
        'manufacturer_description' => 'oc_manufacturer_description',
        'manufacturer_to_store' => 'oc_manufacturer_to_store',
        'category' => 'oc_category',
        'category_description' => 'oc_category_description',
        'category_path' => 'oc_category_path',
        'category_to_store' => 'oc_category_to_store',
        'url_alias' => 'oc_url_alias',
        'order' => 'oc_order',
        'order_history' => 'oc_order_history'
    ];

    /**
     * @param SafeMySQL $db
     * @param LoggerInterface $logger
     */
    public function __construct(SafeMySQL $db, LoggerInterface $logger)
    {
        $this->db = $db;
        $this->logger = $logger;
    }

    public function getCreated() {
        return $this->created;
    }
    
    public function getUpdated() {
        return $this->updated;
    }

    /**
     * @param callable $callable
     * @return stdClass
     * @throws Exception If any error happens during import
     */
    public function atomicImport(callable $callable)
    {
        $this->db->query('START TRANSACTION');
        try {
            $result = call_user_func($callable);
            $this->db->query('COMMIT');
            return $result;
        } catch (\Exception $e) {
            $this->db->query('ROLLBACK');
            throw $e;
        }
    }

    /**
     * @param Entity
     * @return stdClass
     */
    public function import($import)
    {
        $class_name = get_class($this);
        $entity = $this->fetch($import);
        if (is_null($entity)) {
            $entity = $this->create($import);
            $this->created += 1;
            $this->logger->info('Created entity: ' . $class_name, $this->getContext($import));
        } else {
            $updated = false;
            $entity = $this->update($entity, $import, $updated);
            if ($updated) {
                $this->updated += 1;
                $this->logger->info('Updated entity: ' . $class_name, $this->getContext($import));
            } else {
                $this->logger->debug('Nothing changed, entity: ' . $class_name, $this->getContext($import));
            }
        }
        return $entity;
    }

    /**
     * @param string $query
     * @param string $alias
     * @return integer
     */
    public function createUrlAlias(string $query, string $alias)
    {
        $this->db->query('INSERT INTO ?n SET query = ?s, keyword = ?s', $this->getTable('url_alias'), $query, $alias);
        return $this->db->affectedRows() > 0 ? $this->db->insertId() : 0;
    }

    /**
     * @param Entity $import
     * @return stdClass
     */
    abstract public function create($import);

    /**
     * @param Entity $import
     * @return stdClass
     */
    abstract public function fetch($import);

    /**
     * @param stdClass $entity
     * @param Entity $import
     * @param boolean $updated
     * @return stdClass
     */
    abstract public function update(stdClass $entity, $import, &$updated);

    /**
     * @param Entity $entity
     * @return array
     */
    public function getContext($entity)
    {
        return [];
    }

    /**
     * @param string $name
     * @return string
     */
    public function getTable($name)
    {
        if (isset($this->tables[$name])) {
            return $this->tables[$name];
        }
        throw new \InvalidArgumentException("Table with name $name not found");
    }
}
