<?php
namespace Neos\classes\log;
use Neos\classes\util as U;
class Logger
{
    /**
     * @implements LoggerInterface
     */
    private $logger;
    public function __construct($opts, $log_type = 'db')
    {
        switch ($log_type) {
            case 'db':
                if (isset($opts['db_instance']) && is_a($opts['db_instance'], '\Neos\libraries\SafeMySQL')) {
                    $this->logger = new LoggerDB($opts['db_instance']);
                } else {
                    $this->logger = new LoggerDB(U\DBSingleton::getInstance($opts));
                }
            break;
            default:
                throw new \Exception('Please define correct log type');
            break;
        }
    }
    public function write($data) 
    {
        $this->logger->write($data);
    }
}