<?php

namespace Neos\classes\Import;

use Neos\classes\Import\Dictionaries\DatabaseCategoryTree;
use Neos\Import1C\Dictionaries\CategoryTree;
use Neos\Import1C\Entities\Category as ImportCategory;
use Neos\libraries\SafeMySQL;
use Neos\Import1C\Helpers\Str;
use Psr\Log\LoggerInterface;
use stdClass;

class Category extends AbstractImport
{
    /**
     * @var CategoryTree
     */
    protected $category_tree;

    /**
     * @param SafeMySQL $db
     */
    public function __construct(
        SafeMySQL $db,
        LoggerInterface $logger,
        CategoryTree $category_tree
    ) {
        parent::__construct($db, $logger);
        $this->category_tree = $category_tree;
    }

    /**
     * @param ImportCategory $import
     * @return stdClass|null
     */
    public function fetch($import)
    {
        $category = $this->db->getRow(
            'SELECT c.* FROM ?n as c INNER JOIN ?n as cd USING (category_id)
            WHERE cd.name = ?s AND cd.language_id = ?i LIMIT 1',
            $this->getTable('category'),
            $this->getTable('category_description'),
            $import->name,
            static::DEFAULT_LANGUAGE
        );
        if (!$category) {
            return null;
        }
        return (object) $category;
    }

    /**
     * @return DatabaseCategoryTree
     */
    public function fetchDatabaseCategoryTree(): DatabaseCategoryTree
    {
        $categories = $this->db->getAll(
            'SELECT c.category_id as id, cd.name, c.parent_id FROM ?n as c
            INNER JOIN ?n as cd USING (category_id)
            WHERE cd.language_id = ?i',
            $this->getTable('category'),
            $this->getTable('category_description'),
            static::DEFAULT_LANGUAGE
        );
        $tree = new DatabaseCategoryTree($this->logger);
        foreach ($categories as $category_data) {
            $category = new ImportCategory();
            $category->id = $category_data['id'];
            $category->import_id = $category_data['id'];
            $category->name = $category_data['name'];
            $category->parent_id = $category_data['parent_id'];
            $category->parent_import_id = $category_data['parent_id'];
            $tree->addCategory($category);
        }
        return $tree;
    }


    /**
     * @param ImportCategory $import
     * @return stdClass
     */
    public function create($import)
    {
        $category = (object) [
            'image' => '',
            'parent_id' => 0,
            'top' => 0,
            'linkto' => '',
            'column' => 0,
            'sort_order' => 0,
            'status' => 1,
            'date_added' => date('Y-m-d H:i:s'),
            'date_modified' => date('Y-m-d H:i:s'),
            'ext_id' => null
        ];
        $this->atomicImport(function () use ($category, $import) {
            $parents = $this->category_tree->getParentCategories($import->import_id);
            $context = $this->getContext($import);
            if (isset($parents[0]) && !is_null($parents[0]->id)) {
                $category->parent_id = $parents[0]->id;
            } elseif (isset($parents[0])) {
                $this->logger->alert('Parent id not found, parent: ' . $parents[0]->name, $context);
            }
            $this->db->query('INSERT INTO ?n SET ?u', $this->getTable('category'), (array) $category);
            $category->category_id = $import->id = $this->db->insertId();
            $this->db->query(
                "INSERT INTO ?n VALUES (?i, ?i, ?s, '', ?s, ?s, ?s, ?s)",
                $this->getTable('category_description'),
                $category->category_id,
                static::DEFAULT_LANGUAGE,
                $import->name,
                $import->name,
                $import->name,
                $import->name,
                $import->name
            );
            $this->db->query(
                'INSERT INTO ?n VALUES (?i, ?i)',
                $this->getTable('category_to_store'),
                $category->category_id,
                static::DEFAULT_STORE
            );
            $level = count($parents);
            $path_error = false;
            foreach ($parents as $parent) {
                $level--;
                if (is_null($parent->id)) {
                    $this->logger->alert('Parent id not found, parent: ' . $parents[0]->name, $context);
                    $path_error = true;
                } else {
                    $paths[] = $this->db->parse('(?i, ?i, ?i)', $category->category_id, $parent->id, $level);
                }
            }
            // If there is an error - insert category path as root
            if ($path_error) {
                $paths = [$this->db->parse('(?i, ?i, ?i)', $category->category_id, $category->category_id, 0)];
            } else {
                array_unshift(
                    $paths,
                    $this->db->parse('(?i, ?i, ?i)', $category->category_id, $category->category_id, count($parents))
                );
            }
            $this->db->query(
                'INSERT INTO ?n VALUES ?p',
                $this->getTable('category_path'),
                implode(',', $paths)
            );
            $alias_id = $this->createUrlAlias(
                'category_id="' . $category->category_id . '"',
                Str::urlSafe($import->name)
            );
            $this->logger->debug(
                'Created url alias, id: ' . $alias_id,
                $this->getContext($import)
            );
        });
        return $category;
    }

    /**
     * @param stdClass $category
     * @param ImportCategory $import
     * @param boolean $updated
     * @return stdClass
     */
    public function update(stdClass $category, $import, &$updated)
    {
        $import->id = $category->category_id;
        $updated = false;
        $parents = $this->category_tree->getParentCategories($import->import_id);
        $paths = $this->db->getAll(
            'SELECT * FROM ?n WHERE category_id = ?i ORDER BY `level` DESC',
            $this->getTable('category_path'),
            $category->category_id
        );
        // First path is current category
        array_shift($paths);
        $max_level = count($parents) - 1;
        $parent_changed = false;
        foreach ($parents as $key => $parent) {
            if (!isset($paths[$key]) || $parent->id != $paths[$key]['path_id']) {
                $parent_changed = true;
                $this->logger->warning(
                    sprintf(
                        'Category parents has changed, previous parent: %s, current parent: %s, level: %s',
                        $paths[$key]['path_id'] ?? 'none',
                        $parent->id,
                        $paths[$key]['level'] ?? $max_level - $key
                    ),
                    $this->getContext($import)
                );
            }
        }
        // TODO: if parent has changed - rebuild paths for all child categories, change parent id
        return $category;
    }

    /**
     * @param ImportCategory $import
     * @return array
     */
    public function getContext($import)
    {
        $context = [];
        if (!is_null($import->id)) {
            $context['category_id'] = $import->id;
        }
        $context['category_name'] = $import->name;
        return $context;
    }
}
