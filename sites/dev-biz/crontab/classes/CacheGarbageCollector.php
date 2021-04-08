<?php
namespace Neos\util\cache;

defined('_NEXEC') or die('Restricted Access');

class CacheGarbageCollector
{
    /**
     * @var string $format cache format
     */
    public $format = 'json';
    /**
     * @var string $cachedir directory cache 
     * @var string $rootpath root of cache 
     * @var string $path full path to caching directory
     */
    private $cachedir;
    private $rootpath;
    private $path;
    private $expire;
    public static $default_rootpath;
    /**
     * @var string $error
     */
    private $error;
    private $counter = 0;

    public function __construct($directory = '', $expire = 0, $format = 'json') {
        $this->rootpath = static::$default_rootpath;
        if (empty($this->rootpath)) {
            throw new Exception('Wrong Path');
        }
        if (!empty($directory)) {
            $this->cachedir = trim($directory, '/');
            $this->path = $this->rootpath .'/'. $this->cachedir;
        } else {
            $this->cachedir = '';
            $this->path = $this->rootpath;
        }
        $this->format = $format;
        $this->expire = $expire;
        if (is_dir($this->path)) {
            $this->it = new \FileSystemIterator($this->path);
        } else {
            $this->error = $this->path . 'not found';
        }
    }
    public function getRemovedFileCount()
    {
        return $this->counter;
    }
    public function iterate($dir = null)
    {
        $it = ($dir) ? new \FileSystemIterator($dir) : $this->it;
        foreach ($it as $path => $fileInfo) {
            if ($fileInfo->isDir() && !$fileInfo->isLink()) {
                $this->iterate($path);
            }
            $finfo = pathinfo($fileInfo->getFilename());
            if ($this->format === 'all') {
                $this->collect($fileInfo);
            } else if ($this->format === $finfo['extension']) {
                $this->collect($fileInfo);
            }
        }   
    }
    protected function collect($fileInfo)
    {
        if ($this->expire < (\time() - $fileInfo->getMTime())) {
            unlink($fileInfo->getPathname());
            $this->counter++;
        }
    }
    public static function setDefaultRootpath($path)
    {
        static::$default_rootpath = $path;
    }
}
?>