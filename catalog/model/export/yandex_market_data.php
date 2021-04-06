<?php
class ModelExportYandexMarketData extends Model {
    /**
     * @var object SafeMySQL instance
     */
    protected $db;

    /**
     * @var int customer group
     */
    protected $customer_group;

    public function __construct($registry) 
    {
        parent::__construct($registry);
        
        $this->db = \Neos\NeosFactory::getDb();
    }
    /**
     * Fetch mysql resource
     * @param resource $resource
     */
    public function fetchResource($resource) {
        return $this->db->fetch($resource);
    }
    /**
     * @param int $customer_group
     */
    public function setCustomerGroup($customer_group)
    {
        $this->customer_group = $customer_group;
    }
    /**
     * @return int customer group id
     */
    public function getCustomerGroup()
    {
        return ($this->customer_group !== null) ? $this->customer_group : $this->config->get('config_customer_group_id');
    }

    /**
     * Get all categories
     */
	public function getCategory() {
		return $this->db->getAll(
            "SELECT cd.name, c.category_id, c.parent_id FROM " . DB_PREFIX . "category c 
            LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) 
            LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) 
            WHERE cd.language_id = ?i AND c2s.store_id = ?i AND c.status = '1' AND c.sort_order <> '-1'",
            $this->config->get('config_language_id'),
            $this->config->get('config_store_id')
        );
    }
    
    /**
     * @param array $export_categories
     * @param integer $out_of_stock_id
     * @param boolean $vendor_required
     * @return resource
     */
    public function getProduct(array $export_categories, $out_of_stock_id, $vendor_required = true)
    {
        $sql_mode = $this->db->getOne("SELECT @@sql_mode;");
        $this->db->query("SET sql_mode=''");
        $result = $this->db->query(
            "SELECT p.*, pd.name, pd.description, m.name AS manufacturer, p2c.category_id, 
            IFNULL(ps.price, p.price) AS price, 
            GROUP_CONCAT(discount.price ORDER BY discount.priority ASC, discount.price ASC) AS customer_discount
            FROM " . DB_PREFIX . "product p 
            INNER JOIN " . DB_PREFIX . "product_to_category AS p2c ON (p.product_id = p2c.product_id AND p2c.main_category = '1') 
            ?p JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) 
            LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
            LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
            LEFT JOIN " . DB_PREFIX . "product_special ps 
            ON (p.product_id = ps.product_id) AND ps.customer_group_id = ?i 
            AND ps.date_start < NOW() AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()) 
            LEFT JOIN " . DB_PREFIX . "product_discount as discount ON discount.product_id = p.product_id 
            AND discount.customer_group_id = ?i 
            AND discount.date_start < NOW() AND (discount.date_end = '0000-00-00' OR discount.date_end > NOW())
            WHERE ?p p2s.store_id = ?i AND pd.language_id = ?i AND p.date_available <= NOW() AND p.status = '1' 
            AND (p.quantity > 0 AND p.stock_status_id != ?i) 
            GROUP BY p.product_id",
            ($vendor_required) ? 'INNER' : 'LEFT',
            $this->getCustomerGroup(),
            $this->getCustomerGroup(),
            $this->getCategoriesSql($export_categories),
            $this->config->get('config_store_id'),
            $this->config->get('config_language_id'),
            $out_of_stock_id
        );
        $this->db->query("SET sql_mode='?p'", $sql_mode);
        return $result;
    }

    /**
     * @param array $export_categories
     * @param integer $out_of_stock_id
     * @param boolean|true $vendor_required
     * @param integer|null $manufacturer
     * @return resource
     */
    public function getProductByManufacturer(
        array $export_categories,
        $out_of_stock_id,
        $vendor_required = true,
        $manufacturer = null
    ) {
        $sql_mode = $this->db->getOne("SELECT @@sql_mode;");
        $this->db->query("SET sql_mode=''");
        $result = $this->db->query(
            "SELECT p.*, pd.name, pd.description, m.name AS manufacturer, p2c.category_id, 
            IFNULL(ps.price, p.price) AS price, 
            GROUP_CONCAT(discount.price ORDER BY discount.priority ASC, discount.price ASC) AS customer_discount
            FROM " . DB_PREFIX . "product p 
            INNER JOIN " . DB_PREFIX . "product_to_category AS p2c ON (p.product_id = p2c.product_id AND p2c.main_category = '1') 
            ?p JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) 
            LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
            LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
            LEFT JOIN " . DB_PREFIX . "product_special ps 
            ON (p.product_id = ps.product_id) AND ps.customer_group_id = ?i 
            AND ps.date_start < NOW() AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()) 
            LEFT JOIN " . DB_PREFIX . "product_discount as discount ON discount.product_id = p.product_id 
            AND discount.customer_group_id = ?i 
            AND discount.date_start < NOW() AND (discount.date_end = '0000-00-00' OR discount.date_end > NOW())
			WHERE ?p p2s.store_id = ?i AND pd.language_id = ?i AND p.date_available <= NOW() AND p.status = '1' 
            AND (p.quantity > 0 AND p.stock_status_id != ?i) 
            AND p.manufacturer_id = ?i
            GROUP BY p.product_id",
            ($vendor_required) ? 'INNER' : 'LEFT',
            $this->getCustomerGroup(),
            $this->getCustomerGroup(),
            $this->getCategoriesSql($export_categories),
            $this->config->get('config_store_id'),
            $this->config->get('config_language_id'),
            $out_of_stock_id,
            $manufacturer
        );
        $this->db->query("SET sql_mode='?p'", $sql_mode);
        return $result;
    }

    /**
     * @param array $categories
     * @param string $alias Table alias
     * @return string
     */
    protected function getCategoriesSql(array $categories, $alias = 'p2c.')
    {
        $sql = '';
        if (count($categories) !== 0) {
            $sql = $this->db->parse($alias . 'category_id IN (?a) AND', $categories);
        }
        return $sql;
    }

    public function getManufacturers()
    {
        $result = $this->db->query(
            "SELECT * FROM " . DB_PREFIX . "manufacturer"
        );

        return $result;
    }


    public function getCategoryByManufacturersProducts($category_id) {
        return $this->db->getAll(
            "SELECT cd.name, c.category_id, c.parent_id FROM " . DB_PREFIX . "category c 
            LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) 
            LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) 
            WHERE cd.language_id = ?i AND c2s.store_id = ?i AND c.status = '1' AND c.sort_order <> '-1' AND cd.category_id = '" . $category_id . "'",
            $this->config->get('config_language_id'),
            $this->config->get('config_store_id')
        );
    }

}
?>