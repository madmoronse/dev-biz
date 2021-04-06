<?php
namespace Neos\debug;

class Debug 
{
    public static $allow_debug = true;
    public static $allow_timing = true;
    private static $start;
    private $breakpoints;
    private static $_debug;
    public function __construct()
    {
        self::$start = microtime(true);
    }
    public function addBreakPoint($message = 1)
    {
        if (self::$allow_timing == false) return false;
        $this->breakpoints[] = array(sprintf('%F', microtime(true) - self::$start) => $message);
    }
    public function getTiming()
    {
        return $this->breakpoints;
    }
    public static function debugInfo($message, $profiler = false)
    {
        if (self::$allow_debug == false) return false;
        $info = '';
        if ($profiler == true) {
            $info = sprintf('%F', microtime(true) - self::$start) . ": ";
        }
        $info .= $message;
        self::$_debug[] = $info;
    }
    public function getDebug()
    {
        return self::$_debug;
    }
}