<?php

namespace Neos\classes\Jobs;
use Neos\classes\util\DBSingleton;

abstract class BaseJob
{
    protected $db;

    protected $job_id;

    protected $params;

    public function __construct($id, $params)
    {
        $this->db = DBSingleton::getInstance(array(
            'host' => DB_HOSTNAME,
            'user' => DB_USERNAME,
            'pass' => DB_PASSWORD,
            'db' => DB_DATABASE
        ));

        $this->job_id = $id;

        $this->params = \json_decode($params, true);
        // Set error 
        if (json_last_error()) {
            $this->setStatus(4, 'JSON parse error');
            return false;
        }
        // Set in-progress
        $this->setStatus(2);
    }

    abstract public function execute();

    /**
     * 
     * @param int $status
     * @param string $result
     */
    protected function setStatus($status, $result = null)
    {
        if (in_array($status, array(3,4))) {
            $finishdate = 'NOW()';
        } else {
            $finishdate = 'NULL';
        }
        $this->db->query(
            "UPDATE jobs SET job_status = ?i, result = ?s, finishdate = ?p WHERE id = ?i", 
            $status, $result, $finishdate, $this->job_id
        );
    }
}