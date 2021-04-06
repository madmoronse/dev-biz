<?php

namespace Neos\classes\Cache;

use \Neos\classes\Engine\Templator;

abstract class CacheView
{
    /**
     * @var object Category controller
     */
    protected $controller;

    /**
     * @var array Controller children
     */
    protected $controllerChildren;

    /**
     * @var array Children data
     */
    protected $childrenData = array();

    /**
     * @var string Cache key
     */
    protected $cache_key;


    /**
     * @var object Html cache instance
     */
    protected $htmlCache;

    /**
     * @var object Json cache instance
     */
    protected $jsonCache;

    /**
     * @var string Output
     */
    protected $output = '';

    public function __construct($controller, $controllerChildren)
    {
        $this->controller = $controller;
        $this->controllerChildren = $controllerChildren;
        $this->htmlCache = \Neos\NeosFactory::getHelper('HtmlCache');
        $this->jsonCache = \Neos\NeosFactory::getHelper('Cache');

        $this->prepareCacheKey();

    }

    /**
     * Load page from cache
     */
    abstract public function load();

    /**
     * Prepare cache key
     */
    abstract protected function prepareCacheKey();

    /**
     * Determine if should cache
     */
    abstract protected function shouldCache();

    /**
     * Set cache
     * @param string $output
     */
    public function setCache($output)
    {
        if (false === $this->shouldCache()) {
            return false;
        }
        return $this->htmlCache->set($output, $this->cache_key, true);
    }

    /**
     * Set json cache
     * @param array|object $data
     */
    public function setJsonCache($data)
    {
        if (false === $this->shouldCache()) {
            return false;
        }
        return $this->jsonCache->set(json_encode($data), $this->cache_key, true);
    }

    /**
     * Get json cache
     */
    public function getJsonCache()
    {
        if (false === $this->shouldCache()) {
            return null;
        }
        return json_decode($this->jsonCache->get($this->cache_key), true);
    }

    /**
     * Set children data
     * @param array $data
     * 
     * @return string
     */
    public function setChildrenData($data) 
    {
		$this->childrenData = $data;
    }

    /**
     * Render cached view
     */
    public function renderView()
    {
        $this->prepareOutput($this->output, $this->childrenData);
    }

    /**
     * Prepare output
     * @param string $output
     * @param arraay $data
     */
    public function prepareOutput($output, $data)
    {
        $render_keys = array_flip(array_map(function($value) { return basename($value); }, $this->controllerChildren));
        $templator = new Templator(array_intersect_key((array) $data, $render_keys));
        $templator->setTemplate($output);
        $this->controller->response->setOutput($templator->render());
    }
}