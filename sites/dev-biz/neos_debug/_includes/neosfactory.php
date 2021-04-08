<?php 
namespace Neos;

class NeosFactory
{
    protected static $enviroment_ready = false;
    public static function setupEnviroment()
    {
        if (static::$enviroment_ready) return true;

        $functions = \spl_autoload_functions();
        if (is_array($functions)) {
            foreach ($functions as $function) {
                if (isset($function[0]) && $function[0] === 'NeosLoader') {
                    static::$enviroment_ready = true;
                    return true;
                }
            }
        }
        define('NPATH_BASE', __DIR__ . '/../'); 
        define('_NEXEC', 1);
        include NPATH_BASE . '/_includes/constants.php';
        include NPATH_INCLUDES . '/loader.php';
        \NeosLoader::setup();
        static::$enviroment_ready = true;
    }
    public static function getHelper($name)
    {
        static::setupEnviroment();
        $name = trim($name);
        $class = '\\Neos\\classes\\helpers\\' . $name;
        if (class_exists($class)) {
            return new $class;
        }
        
        return false;
    }

    public static function getDb()
    {
        static::setupEnviroment();
        $connect_options = array(
            'user' => DB_USERNAME,
            'pass' => DB_PASSWORD,
            'db' => DB_DATABASE
        );
        if (defined("DB_NEOSPORT") && defined("DB_NEOSHOST")) {
            $connect_options['host'] = DB_NEOSHOST;
            $connect_options['port'] = DB_NEOSPORT;
        }
        return \Neos\classes\util\DBSingleton::getInstance($connect_options);
    }
    
    /**
     * Create Job
     * @param string $job_class
     * @param array|object $params
     */
    public static function createJob($job_class, $params)
    {
        $db = static::getDB();

        $result = $db->query(
            "INSERT INTO `jobs` (`job_type`, `params`, `createdate`) 
            SELECT `id`, ?s, NOW() FROM `jobs_type` WHERE `job_class` = ?s",
            \json_encode($params), $job_class
        );

        if ($result) {
            return true;
        }

        return false;
    }
}