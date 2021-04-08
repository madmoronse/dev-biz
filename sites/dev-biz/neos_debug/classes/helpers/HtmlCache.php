<?php

namespace Neos\classes\helpers;

class HtmlCache
{
    public $cache_path;
    public $format = 'html';
    public function get($cachehash)
    {
        $filename = $this->getPath() . $cachehash . '.' . $this->format;
        if (file_exists($filename)) {
            return file_get_contents($filename);
        }
        return false;
    }
    public function set($content, $cachehash, $mkdir = false)
    {
        $filename = $this->getPath() . $cachehash . '.' . $this->format;
        if ($this->checkPath($mkdir)) {
            return file_put_contents($filename, $content);
        }
        return false;
    }
    public function expire($cachehash, $expire)
    {
        if ($expire == 0) return false;
        $filename = $this->getPath() . $cachehash . '.' . $this->format;
        if (file_exists($filename) && (\time() - filemtime($filename) > $expire)) {
            unlink($filename);
            return true;
        }
        return false;
    }
    protected function getPath()
    {
        if (empty($this->cache_path)) {
            return NPATH_ROOT . '../cache/product/'; 
        }
        return $this->cache_path; 
    }

    protected function checkPath($mkdir = false)
    {
        if (is_writable($this->getPath())) {
            return true;
        }
        if ($mkdir) {
            return mkdir($this->getPath(), 0755, true);
        }
        return false;
    }
}