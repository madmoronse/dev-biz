<?php

namespace Neos\classes\Jobs;
use Neos\classes\util\DBSingleton;

class JobManager
{
    protected $db;

    public function __construct()
    {   
        $this->db = DBSingleton::getInstance(array(
            'host' => DB_HOSTNAME,
            'user' => DB_USERNAME,
            'pass' => DB_PASSWORD,
            'db' => DB_DATABASE
        ));
    }
    
    public function executeJobs()
    {
        $jobs = $this->db->getAll(
            "SELECT `j`.*, `t`.`job_class` FROM `jobs` as `j` 
            INNER JOIN `jobs_type` as `t` ON `t`.`id` = `j`.`job_type`  
            WHERE `j`.`job_status` = 0"
        );
        $ids = array_map(function($value) { return $value['id']; }, $jobs);
        $this->db->query("UPDATE `jobs` SET `job_status` = 1 WHERE `id` IN(?a)", $ids);

        foreach ($jobs as $row) {
            $row['job_class'] = "\\Neos\\classes\\Jobs\\" . $row['job_class'];
            if (class_exists($row['job_class'])) {
                $job = new $row['job_class']($row['id'], $row['params']);
                $job->execute();
            } else {
                $this->db->query(
                    "UPDATE `jobs` SET `job_status` = 4, `result` = 'Job class not found' WHERE `id` = ?i", 
                    $row['id']
                );
            }
        }
    }

    
}