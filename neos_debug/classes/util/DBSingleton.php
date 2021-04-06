<?php
namespace Neos\classes\util;
use Neos\libraries as Lib;

defined('_NEXEC') or die('Restricted Access');

class DBSingleton
{
    private static $instance = null;
    private static $defaults = array (
                    'host'      => 'localhost',
                    'user'      => '',
                    'pass'      => ''
            );
    public static function getInstance($opts = null, $new = false)
    {
        if (self::$instance == null || $new) {
            if (is_array($opts)) {
                $opts = array_merge(self::$defaults, $opts);
            } elseif ($opts == null) {
                $opts = self::$defaults;
            }
            self::$instance = new Lib\SafeMySQL($opts);
        } 
        return self::$instance;
    }
    private function __construct() {}
    private function __clone() {}

}