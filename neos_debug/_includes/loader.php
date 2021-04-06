<?php

defined('_NEXEC') or die('Restricted Access');

final class NeosLoader
{
    /**
    * Содержит в себе импортированные классы 
    *
    * @var array
    */
    protected static $imported = array ();
    protected static $imported_libraries = array ();
	private static function loadClass($class)
	{
        $to_lower = false;
        //Replace if your project is in root
        $class = preg_replace('/^Neos/', '', $class);
        //Get array path
        $path = explode('\\', $class);
        //Class name
        $class_name = array_pop($path);

        //Make path from array
        $path = implode(DIRECTORY_SEPARATOR, $path);
        if (!isset(self::$imported[$class]) 
			&& file_exists(NPATH_ROOT . $path . DIRECTORY_SEPARATOR .  $class_name . '.php')) {
			if (!include NPATH_ROOT . $path . DIRECTORY_SEPARATOR . $class_name . '.php') {
                throw new \Exception('Could\'t Load Class: ' . $class);
            }
		} elseif (!isset(self::$imported[$class])) {
            throw new \Exception('Could\'t Load Class: ' . $class);
        }
		return;
		
	}
	public static function setup()
	{
		spl_autoload_register(array('\NeosLoader', 'loadClass'));	
	}
    public static function import($path)
    {
        $library_path = $path;
        $parts = explode('.', $path);
        $filename = array_pop($parts);
        $path = implode(DIRECTORY_SEPARATOR, $parts);
        $import_file = (empty($parts)) ? $filename . '.php' : $path . DIRECTORY_SEPARATOR . $filename . '.php';
        if (!file_exists(NPATH_LIBRARIES . DIRECTORY_SEPARATOR . $import_file)) throw new \Exception('File not found: ' . $import_file);

        if (!isset(self::$imported_libraries[$library_path])) {
            if (!include_once NPATH_LIBRARIES . DIRECTORY_SEPARATOR . $import_file) return false;
        } 
        return true;

    }
    private function __clone() {}
    private function __construct() {}
} 
