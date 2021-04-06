<?php

namespace Neos\classes\Import\Dictionaries;

use Neos\Import1C\Dictionaries\CategoryTree as ImportCategoryTree;
use Neos\Import1C\Entities\Category;
use Psr\Log\LoggerInterface;

class DatabaseCategoryTree extends ImportCategoryTree
{
    /**
     * Map of category name to array of categories ids
     * @var array
     */
    protected $category_map_by_name = [];

    /**
     * Map of category md5(name + parents) to category id
     * @var array
     */
    protected $category_map_by_name_cache = [];

    /**
     * Map of category id to it's childs ids
     * @var array
     */
    protected $child_categories = [];

    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param Category $category
     * @return void
     */
    public function addCategory(Category $category)
    {
        parent::addCategory($category);
        $name = trim(mb_strtolower($category->name));
        if (!isset($this->category_map_by_name[$name])) {
            $this->category_map_by_name[$name] = [];
        }
        $this->category_map_by_name[$name][] = $category->id;
        $this->child_categories[$category->parent_id][] = $category->id;
    }

    /**
     * @param string $name
     * @param Category[] $expected_parents
     * @return Category|null
     */
    public function getCategoryByName(string $name, array $expected_parents)
    {
        if (!isset($this->category_map_by_name[$name])) {
            return null;
        }
        // If there is only one category return it immediatly
        if (count($this->category_map_by_name[$name]) === 1) {
            return $this->getCategory($this->category_map_by_name[$name][0]);
        }
        // Try to get category from cache
        $hash = md5($name . '-' . implode('-', array_map(function ($parent) {
            return $parent->id;
        }, $expected_parents)));
        if (isset($this->category_map_by_name_cache[$hash])) {
            $this->logger->debug(
                "Found category using map cache, name: '$name'"
            );
            return $this->getCategory($this->category_map_by_name_cache[$hash]);
        }
        // Find best matching category using expected parents
        $this->logger->debug(
            "Found " . count($this->category_map_by_name[$name]) . " matching categories, name: '$name'"
        );
        $best_match = null;
        $best_match_count = 0;
        $best_match_parents = '';
        foreach ($this->category_map_by_name[$name] as $category_id) {
            $category = $this->getCategory($category_id);
            if (is_null($category)) {
                $this->logger->notice('Category not found, id: ' . $category_id);
                continue;
            }
            $parents_matching = 0;
            $parent_categories = $this->getParentCategories($category_id);
            $parent_path = implode(' > ', array_map(function ($parent) {
                return $parent->name;
            }, array_reverse($parent_categories)));
            foreach ($expected_parents as $expected_parent) {
                foreach ($parent_categories as $key => $parent) {
                    if (trim($expected_parent->name) === trim($parent->name)) {
                        $parents_matching += 1;
                        // Shorten parents array of tested category
                        $parent_categories = array_slice($parent_categories, $key + 1);
                        // Go to next expected parent category
                        break;
                    }
                }
            }
            if ($best_match_count < $parents_matching) {
                $best_match = $category;
                $best_match_count = $parents_matching;
                $best_match_parents = $parent_path;
            }
        }
        if (!is_null($best_match)) {
            $this->logger->debug(
                "Found best match category, id: '{$best_match->id}', name: '$name', parents: $best_match_parents"
            );
            $this->category_map_by_name_cache[$hash] = $best_match->id;
        }
        return $best_match;
    }

    /**
     * @param integer $parent_id
     * @param string $name
     * @return Category
     */
    public function getChildCategoryByName(int $parent_id, string $name)
    {
        if (isset($this->child_categories[$parent_id])) {
            foreach ($this->child_categories[$parent_id] as $category_id) {
                $category = $this->getCategory($category_id);
                if ($name === trim(mb_strtolower($category->name))) {
                    return $category;
                }
            }
        }
        return null;
    }
}
