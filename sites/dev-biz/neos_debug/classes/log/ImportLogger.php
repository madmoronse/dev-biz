<?php

namespace Neos\classes\log;

class ImportLogger extends PsrLogger
{
    /**
     * @param string $destination
     */
    public function __construct($destination = '/import.log')
    {
        if (defined('IMPORT_LOG_LEVEL')) {
            $this->log_level = static::toLevel(IMPORT_LOG_LEVEL);
        }
        if (defined('IMPORT_LOG_STDOUT')) {
            $this->log_stdout = true;
        }
        parent::__construct($destination);
    }
}
