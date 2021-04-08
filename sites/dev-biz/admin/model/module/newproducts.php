<?php

class ModelModuleNewProducts extends Model
{
    /**
     * @param array $post
     * @return array (added, removed)
     */
    public function store(array $post)
    {
        $categories = isset($post['categories']) ? $post['categories'] : array();
        $this->load->model('setting/setting');
        $this->model_setting_setting->editSetting('newproducts', array(
            'newproducts_lifetime_in_weeks' => $post['lifetime_in_weeks'],
        ));
        $removed = 0;
        $added = 0;
        $updated = 0;
        $existing_categories = $this->getCategoriesWithProducts();
        $date = date('Y-m-d H:i:s');
        foreach ($categories as $category) {
            $category_id = $category['category_id'];
            $categories_ids = $this->getCategoryParentIds($category_id);
            array_unshift($categories_ids, $category_id);
            $products = array_unique(array_map('intval', $category['products']));
            $sort = array_flip($products);
            $existing_products = isset($existing_categories[$category_id])
                ? $existing_categories[$category_id]['products']
                : array();
            $existing_sort = array_combine(
                $existing_products,
                count($existing_products) > 0 ? $existing_categories[$category_id]['sort'] : array()
            );
            // Perform update, insert, delete
            $matching = array_intersect($products, $existing_products);
            $to_update = array();
            foreach ($matching as $product_id) {
                if ($sort[$product_id] !== $existing_sort[$product_id]) {
                    $to_update[] = $product_id;
                }
            }
            if (count($to_update) > 0) {
                $this->db->query(sprintf(
                    'INSERT INTO `%smodule_newproducts` (`product_id`, `category_id`, `sort`)
                    VALUES %s ON DUPLICATE KEY UPDATE `sort` = VALUES(`sort`)',
                    DB_PREFIX,
                    implode(',', array_map(function ($product_id) use ($category_id, $sort) {
                        return sprintf('(%u, %u, %u)', $product_id, $category_id, $sort[$product_id]);
                    }, $to_update))
                ));
                $updated += count($to_update);
            }
            $to_add = array_diff($products, $existing_products);
            if (count($to_add) > 0) {
                $this->db->query(sprintf(
                    'INSERT INTO `%smodule_newproducts` VALUES %s',
                    DB_PREFIX,
                    implode(',', array_map(function ($product_id) use ($category_id, $sort, $date) {
                        return sprintf('(%u, %u, %u, \'%s\')', $product_id, $category_id, $sort[$product_id], $date);
                    }, $to_add))
                ));
                $this->setProductsToCategories($to_add, $categories_ids);
                $added += count($to_add);
            }
            $to_remove = array_diff($existing_products, $products);
            if (count($to_remove) > 0) {
                $this->db->query(sprintf(
                    'DELETE FROM `%smodule_newproducts` WHERE category_id = %u AND product_id IN (%s)',
                    DB_PREFIX,
                    $category_id,
                    implode(',', $to_remove)
                ));
                // NOTE: it might do not remove all previous relations
                // due to that given category parents may change,
                // but this event is very unlikely to happen
                $this->unsetProductsFromCategories($to_remove, $categories_ids);
                $removed += count($to_remove);
            }
        }
        return array($added, $updated, $removed);
    }

    /**
     * @return array
     */
    public function getCategoriesWithProducts()
    {
        $categories = $this->getCategories();
        $result = array();
        $category_ids = array();
        foreach ($categories as $category) {
            $category_id = (int) $category['category_id'];
            $result[$category_id] = $category;
            $result[$category_id]['products'] = array();
            $category_ids[] = (int) $category_id;
        }
        $products_query = $this->db->query(sprintf(
            "SELECT * FROM `%smodule_newproducts` WHERE category_id IN (%s) ORDER BY `category_id`, `sort`",
            DB_PREFIX,
            implode(',', $category_ids)
        ));
        if ($products_query->num_rows > 0) {
            foreach ($products_query->rows as $product) {
                $category_id = (int) $product['category_id'];
                $result[$category_id]['products'][] = (int) $product['product_id'];
                $result[$category_id]['sort'][] = (int) $product['sort'];
            }
        }
        return $result;
    }

    /**
     * @return array
     */
    public function getCategories()
    {
        $category_id = $this->getMainCategoryId();
        $query = $this->db->query(sprintf(
            "SELECT c.category_id, cd.name FROM `%scategory` as c
            INNER JOIN `%scategory_description` as cd ON cd.category_id = c.category_id AND language_id = 1
            WHERE `parent_id` = %u",
            DB_PREFIX,
            DB_PREFIX,
            $category_id
        ));
        return $query->num_rows === 0 ? array() : $query->rows;
    }

    /**
     * @return integer
     */
    public function getMainCategoryId()
    {
        $parent_query = $this->db->query(sprintf(
            "SELECT category_id FROM `%scategory_description` WHERE `name` = 'новинки'",
            DB_PREFIX
        ));
        if ($parent_query->num_rows === 0) {
            return 0;
        }
        return (int) $parent_query->row['category_id'];
    }

    /**
     * @param integer $category_id
     * @return array
     */
    public function getCategoryParentIds($category_id)
    {
        $ids = array();
        $parent_query = $this->db->query(sprintf(
            "SELECT parent_id FROM `%scategory` WHERE category_id = %u",
            DB_PREFIX,
            $category_id
        ));
        if (!empty($parent_query->row['parent_id'])) {
            $ids[] = $parent_query->row['parent_id'];
            $ids = array_merge($ids, $this->getCategoryParentIds($parent_query->row['parent_id']));
        }
        return $ids;
    }

    /**
     * @param array $products_ids
     * @param array $categories_ids
     * @return void
     */
    public function setProductsToCategories(array $products_ids, array $categories_ids)
    {
        $this->db->query(sprintf(
            'INSERT INTO `%sproduct_to_category` (`product_id`, `category_id`)
            VALUES %s ON DUPLICATE KEY UPDATE `product_id` = VALUES(`product_id`)',
            DB_PREFIX,
            implode(',', array_map(function ($product_id) use ($categories_ids) {
                return implode(',', array_map(function ($category_id) use ($product_id) {
                    return sprintf('(%u, %u)', $product_id, $category_id);
                }, $categories_ids));
            }, $products_ids))
        ));
    }

    /**
     * @param array $products_ids
     * @param array $categories_ids
     * @return void
     */
    public function unsetProductsFromCategories(array $products_ids, array $categories_ids)
    {
        $this->db->query(sprintf(
            'DELETE FROM `%sproduct_to_category`
            WHERE `product_id` IN (%s) AND `category_id` IN (%s) AND `main_category` = 0',
            DB_PREFIX,
            implode(',', $products_ids),
            implode(',', $categories_ids)
        ));
    }

    /**
     * @return void
     */
    public function createDatabaseTables()
    {
        $this->db->query(
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "module_newproducts` (
                `product_id` INT(11) NOT NULL,
                `category_id` INT(11) NOT NULL,
                `sort` INT(11) NOT NULL,
                `date_added` DATETIME NOT NULL,
                PRIMARY KEY (`product_id`, `category_id`),
                INDEX `sort` (`sort`)
            )
            COLLATE='utf8_general_ci'
            ENGINE=InnoDB"
        );
    }

    /**
     * @return void
     */
    public function dropDatabaseTables()
    {
        $this->db->query(
            "DROP TABLE IF EXISTS `" . DB_PREFIX . "module_newproducts`"
        );
    }
}
