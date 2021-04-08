<?php

namespace Neos\classes\Cache;

class Category extends CacheView 
{
    /**
     * @inheritDoc
     */
    public function __construct($controller, $controllerChildren)
    {
        parent::__construct($controller, $controllerChildren);
        $this->htmlCache->cache_path = $this->jsonCache->cache_path = NPATH_BASE . '../cache/categories/';
    }

    /**
     * @inheritDoc
     */
    public function load()
    {
        if (false === $this->shouldCache()) {
            return false;
        }
        // Expire cache
        $this->htmlCache->expire($this->cache_key, 3600);
        // Get Output
		$output = $this->htmlCache->get($this->cache_key);
		if ($output) {		
			// Add Seo Data
			$seoData = $this->getJsonCache();
			if ($seoData) {
                $this->controller->document->setTitle($seoData['seo_title']);
                $this->controller->document->setDescription($seoData['meta_description']);
                $this->controller->document->setKeywords($seoData['meta_keyword']);
                $this->controller->document->addScript('catalog/view/javascript/jquery/jquery.total-storage.min.js');
				if (isset($seoData['thumb'])) {
					$this->document->setOgImage($seoData['thumb']);
				}
			}
            $this->output = $output;
			return true;	
        }
        return false;
    }


    /**
     * @inheritDoc
     */
    protected function prepareCacheKey()
    {
        if (false === $this->shouldCache()) {
            return false;
        }
        $controller = $this->controller;
        if (isset($controller->request->get['filter'])) {
			$filter = $controller->request->get['filter'];
		} else {
			$filter = '';
		}
		if (isset($controller->request->get['sort'])) {
			$sort = $controller->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
		}
		if (isset($controller->request->get['order'])) {
			$order = $controller->request->get['order'];
		} else {
			$order = 'DESC';
		}
		if (isset($controller->request->get['page'])) {
			$page = $controller->request->get['page'];
		} else {
			$page = 1;
		}
		if (isset($controller->request->get['limit'])) {
			$limit = $controller->request->get['limit'];
		} else {
			$limit = $controller->config->get('config_catalog_limit');
        }
        $customer = $this->controller->customer;
        // Customer type
		if ($customer->isLogged()) {
			$raw = $customer->getCustomerGroupId();
			$raw .= ($customer->getId() == 2650) ? 'opt' : '';
		} else {
			$raw = $this->controller->config->get('config_customer_group_id');
        }

        // Category path
        $path = (string) $controller->request->get['path'];
        
        $raw .= $path;

        if (isset($controller->request->get['novently'])) {
            $raw .= '-novently'; 
        }
        if (isset($this->request->get['withdiscount'])) {
            $raw .= '-withdiscount'; 
        }
        if (isset($this->request->get['supersale'])) {
            $raw .= '-supersale'; 
        }
        $data = array(
            'filter_filter'      => $filter,
            'sort'               => $sort,
            'order'              => $order,
            'start'              => ($page - 1) * $limit,
            'limit'              => $limit
        );

        $raw .= json_encode($data);
        $this->cache_key = md5($raw);
    }

    /**
     * Detect if should cache
     */
    protected function shouldCache()
    {
        if (!defined('NEOS_CACHE_CATEGORIES') || (defined('NEOS_CACHE_CATEGORIES') && !NEOS_CACHE_CATEGORIES)) {
            return false;
        }
        return true;
    }
}