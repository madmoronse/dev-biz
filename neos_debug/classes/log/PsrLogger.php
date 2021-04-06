<?php

namespace Neos\classes\log;

use Psr\Log\AbstractLogger;
use Psr\Log\InvalidArgumentException;

class PsrLogger extends AbstractLogger
{
    const DEBUG = 100;
    const INFO = 200;
    const NOTICE = 250;
    const WARNING = 300;
    const ERROR = 400;
    const CRITICAL = 500;
    const ALERT = 550;
    const EMERGENCY = 600;

    /**
     * @var integer
     */
    protected $log_level = 200;

    /**
     * @var boolean
     */
    protected $log_stdout = false;

    /**
     * @var string
     */
    protected $destination;

    /**
     * This is a static variable and not a constant to serve as an extension point for custom levels
     *
     * @var string[] $levels Logging levels with the levels as key
     */
    protected static $levels = [
        self::DEBUG     => 'DEBUG',
        self::INFO      => 'INFO',
        self::NOTICE    => 'NOTICE',
        self::WARNING   => 'WARNING',
        self::ERROR     => 'ERROR',
        self::CRITICAL  => 'CRITICAL',
        self::ALERT     => 'ALERT',
        self::EMERGENCY => 'EMERGENCY',
    ];

    /**
     * @var integer
     */
    protected $profiler_start;

    /**
     * @var integer
     */
    protected $profiler_prev;

    /**
     * @var array
     */
    protected $profilers = [];

    /**
     * @param string $destination
     */
    public function __construct($destination)
    {
        $this->destination = $destination;
    }

    public function setLogLevel($level)
    {
        $this->log_level = static::toLevel($level);
    }

    public function setLogStdout($stdout)
    {
        $this->log_stdout = (bool) $stdout;
    }

    /**
     * @inheritDoc
     */
    public function log($level, $message, array $context = array())
    {
        if (!$this->shouldLog($level)) {
            return;
        }
        $entry = sprintf(
            "[%s] %s: %s%s\n",
            date('Y-m-d H:i:s'),
            strtoupper($level),
            $message,
            empty($context) ? '' : ' ' . json_encode($context)
        );
        if ($this->log_stdout) {
            echo $entry;
        }
        file_put_contents(DIR_LOGS . $this->destination, $entry, FILE_APPEND);
    }

    /**
     * @param string $message
     * @param string $level
     * @return void
     */
    public function profile($message, $level = 'debug')
    {
        $current = microtime(true);
        if (is_null($this->profiler_start)) {
            $this->profiler_start = $current;
            $this->profiler_prev = $current;
        }
        $this->log(
            $level,
            sprintf(
                'Profile: %u ms %u ms %s',
                round(($current - $this->profiler_prev) * 1000),
                round(($current - $this->profiler_start) * 1000),
                $message
            )
        );
        $this->profiler_prev = $current;
    }

    /**
     * @param string $key
     * @return void
     */
    public function measureStart($key)
    {
        $this->profilers[$key] = microtime(true);
    }

    /**
     * @param string $key
     * @param string $level
     * @return void
     */
    public function measureEnd($key, $level = 'debug')
    {
        if (!isset($this->profilers[$key])) {
            return;
        }
        $this->log(
            $level,
            sprintf(
                'Measure: %u ms Memory: %s Peak: %s. %s',
                round((microtime(true) - $this->profilers[$key]) * 1000),
                $this->formatBytes(memory_get_usage()),
                $this->formatBytes(memory_get_peak_usage()),
                $key
            )
        );
        unset($this->profilers[$key]);
    }

    /**
     * @param integer $bytes
     * @param integer $precision
     * @return void
     */
    protected function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * @param  string
     */
    public function shouldLog($level)
    {
        return static::toLevel($level) >= $this->log_level;
    }

    /**
     * @param  string|int
     * @throws \Psr\Log\InvalidArgumentException If level is not defined
     */
    public static function toLevel($level): int
    {
        if (is_string($level)) {
            if (defined(__CLASS__.'::'.strtoupper($level))) {
                return constant(__CLASS__.'::'.strtoupper($level));
            }
            throw new InvalidArgumentException('Level "'.$level.'" is not defined, use one of: '.implode(', ', array_keys(static::$levels)));
        }
        if (!is_int($level)) {
            throw new InvalidArgumentException('Level "'.var_export($level, true).'" is not defined, use one of: '.implode(', ', array_keys(static::$levels)));
        }
        return $level;
    }
}
