<?php

class SimpleABTest
{

    protected static $instance;

    protected $tests = array();
    /**
     * @constructor
     */
    protected function __construct()
    {
        $tests = require __DIR__ . '/simple_ab_tests.php';
        if (is_array($tests)) {
            $this->tests = $tests;
        }
    }

    /**
     * Get instance
     *
     * @return self
     */
    public static function instance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * Check if tests is active
     *
     * @param string $name
     * @return boolean
     */
    public function isActive($name)
    {
        if (!isset($this->tests[$name])
            || (isset($this->tests[$name]) && $this->tests[$name]['active'] === false)
        ) {
            return false;
        }
        return true;
    }
}
