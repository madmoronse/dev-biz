<?php

namespace Neos\classes\Export;

use DateTime;
use Neos\classes\Export\Writer\Writer;
use Neos\libraries\SafeMySQL;
use Psr\Log\LoggerInterface;

abstract class AbstractExport
{
    /**
     * @var SafeMySQL
     */
    protected $db;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var string
     */
    protected $table;

    /**
     * @var string
     */
    protected $primary_key;

    /**
     * @var string
     */
    protected $date_added_field = 'date_added';

    /**
     * @var string
     */
    protected $date_modified_field = 'date_modified';

    /**
     * @var integer
     */
    protected $offset;
    
    /**
     * @var integer
     */
    protected $limit;


    /**
     * @param SafeMySQL $db
     * @param LoggerInterface $logger
     * @param array $options
     */
    public function __construct(SafeMySQL $db, LoggerInterface $logger, array $options = [])
    {
        $this->db = $db;
        $this->logger = $logger;
        $this->offset = $options['offset'] ?? 0;
        $this->limit = $options['limit'] ?? 100;
    }

    /**
     * @param XMLWriter $writer
     * @param DateTime $previous
     * @param DateTime $current
     * @return integer
     */
    public function export(Writer $writer, DateTime $current, DateTime $previous = null)
    {
        $entities = $this->fetch($current, $previous);
        foreach ($entities as $entity) {
            $writer->write($this->prepareEntityForWriter($writer, $entity));
        }
        return count($entities);
    }

    /**
     * @param Writer $writer
     * @param array $ids
     * @return void
     */
    public function exportUsingIds(Writer $writer, array $ids)
    {
        $entities = $this->fetchUsingIds($ids);
        foreach ($entities as $entity) {
            $writer->write($this->prepareEntityForWriter($writer, $entity));
        }
        return count($entities);
    }

    /**
     * @param DateTime $current
     * @param DateTime|null $previous
     * @return array
     */
    public function fetch(DateTime $current, DateTime $previous = null)
    {
        $where = [];
        $previous_sql = static::dateTimeToSql($previous);
        $current_sql = static::dateTimeToSql($current);
        if (!is_null($previous)) {
            $where[] = $this->db->parse(
                '((?n >= ?s AND ?n < ?s) OR (?n >= ?s AND ?n < ?s))',
                $this->date_added_field,
                $previous_sql,
                $this->date_added_field,
                $current_sql,
                $this->date_modified_field,
                $previous_sql,
                $this->date_modified_field,
                $current_sql
            );
        } else {
            $where[] = $this->db->parse(
                '(?n < ?s OR ?n < ?s)',
                $this->date_added_field,
                $current_sql,
                $this->date_modified_field,
                $current_sql
            );
        }
        $items = $this->db->getAll(
            'SELECT * FROM ?n WHERE ?p LIMIT ?i, ?i',
            DB_PREFIX . $this->table,
            implode(' AND ', $where),
            $this->offset,
            $this->limit
        );
        $this->logger->debug($this->db->lastQuery());
        $this->offset += count($items);
        return $items;
    }

    /**
     * @param array $ids
     * @return array
     */
    public function fetchUsingIds(array $ids)
    {
        if (count($ids) === 0) {
            return [];
        }
        $items = $this->db->getAll(
            'SELECT * FROM ?n WHERE ?n IN (?a) LIMIT ?i, ?i',
            DB_PREFIX . $this->table,
            $this->primary_key,
            $ids,
            $this->offset,
            $this->limit
        );
        $this->logger->debug($this->db->lastQuery());
        $this->offset += count($items);
        return $items;
    }

    abstract public function prepareEntityForWriter(Writer $writer, array $data);

    /**
     * @param DateTime $date
     * @return string
     */
    public static function dateTimeToSql(DateTime $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
