<?php
namespace Neos\classes\log;
use Neos\libraries\SafeMySQL;

defined('_NEXEC') or die('Restricted Access');

class LoggerDB implements LoggerInterface
{
    private $db;
    public function __construct(\Neos\libraries\SafeMySQL $db)
    {
        $this->db = $db;
    }
    public function write($data) 
    {
        switch ($data['type']) {
            case 'geocoder':
                $this->db->query("INSERT INTO `neos_geocoder_log` (`query`, `user_agent`, `ip`, `context`, `createdate`) VALUES 
                                  (?s, ?s, ?s, ?s, NOW())", $data['query'], $data['user_agent'], $data['ip'], $data['context']);
            break;
        }
    }
}