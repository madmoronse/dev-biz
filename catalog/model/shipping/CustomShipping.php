<?php

class CustomShipping extends Model
{
    const WEIGHT_KG = 1;
    const WEIGHT_G = 2;

    const LENGTH_CM = 1;
    const LENGTH_MM = 2;

    const DIMENSION_BY_MAX = 'max';
    const DIMENSION_SUM = 'sum';

    protected $requestCache = array();

    protected $shipping_code = '';

    protected $productsCache = array();

    /**
     * Calculate product weight
     *
     * @param array $products
     * @param integer $default_weight
     * @param integer $weight_class_id
     * @return float
     */
    public function getProductsWeight(array $products, $default_weight, $weight_class_id = 2)
    {
        $weight = 0;
        foreach ($products as $product) {
            $quantity = $product['quantity'] > 0 ? $product['quantity'] : 1;
            if ((float) $product['weight'] > 0 && $product['weight_class_id'] !== 0) {
                $weight += $this->weight->convert(
                    $product['weight'],
                    $product['weight_class_id'],
                    $weight_class_id
                );
            } else {
                $category_default = $this->getProductDefaultWeightByCategory(
                    isset($product['category_id']) ? (int) $product['category_id'] : 0
                );
                $weight += $quantity * ($category_default !== 0 ? $category_default : $default_weight);
            }
        }
        return $weight;
    }

    /**
     * Calculate products volume
     *
     * @param array $products
     * @param integer $default_dimensions
     * @param integer $length_class_id
     * @return float
     */
    public function getProductsTotalVolume(
        array $products,
        $default_dimensions = array(
            'height' => 15,
            'length' => 20,
            'width' => 30
        ),
        $length_class_id = 1
    ) {
        $total = 0;
        $dimensions = array('height', 'length', 'width');
        foreach ($products as $product) {
            $volume = 1;
            $quantity = $product['quantity'] > 0 ? $product['quantity'] : 1;
            $category_default = $this->getProductDefaultDimensionsByCategory(
                isset($product['category_id']) ? (int) $product['category_id'] : 0
            );
            foreach ($dimensions as $dimension) {
                $default_dimension_value = isset($category_default[$dimension])
                    ? $category_default[$dimension]
                    : $default_dimensions[$dimension];
                $volume *= $this->length->convert(
                    $product[$dimension] > 0 ? $product[$dimension] : $default_dimension_value,
                    $product['length_class_id'],
                    $length_class_id
                );
            }
            $total += $volume / 1000000 * $quantity;
        }

        return $total;
    }

    /**
     * @param array $products
     * @param array $default
     * @param string $alg
     * @param integer $length_class_id
     * @return array
     */
    public function getProductsTotalDimensions(
        array $products,
        $default = array(
            'height' => 15,
            'length' => 20,
            'width' => 30
        ),
        $alg = 'max',
        $length_class_id = 1
    ) {
        $dimensions = array(
            'height' => 0,
            'length' => 0,
            'width' => 0
        );
        if ($alg === 'sum') {
            $method = 'getDimensionBySum';
        } else {
            $method = 'getDimensionByMax';
        }
        foreach ($products as $product) {
            $category_default = $this->getProductDefaultDimensionsByCategory(
                isset($product['category_id']) ? (int) $product['category_id'] : 0
            );
            foreach ($dimensions as $dimension => $value) {
                if (isset($category_default[$dimension])) {
                    $default_dimension_value = $category_default[$dimension];
                } elseif (isset($default[$dimension])) {
                    $default_dimension_value = $default[$dimension];
                } else {
                    $default_dimension_value = 0;
                }
                $params = array(
                    $value,
                    $product[$dimension],
                    $default_dimension_value,
                    $product['length_class_id'],
                    $length_class_id
                );
                $dimensions[$dimension] = call_user_func_array(array($this, $method), $params);
            }
        }
        return $dimensions;
    }

    /**
     * @param array $products
     * @param array $default
     * @param integer $length_class_id
     * @return array
     */
    public function getParcelDimensions(
        array $products,
        $default = array(
            'height' => 15,
            'length' => 20,
            'width' => 30
        ),
        $length_class_id = 1
    ) {
        $volume = $this->getProductsTotalVolume($products, $default, $length_class_id);
        $dimensions = $this->getProductsTotalDimensions(
            $products,
            $default,
            static::DIMENSION_BY_MAX,
            $length_class_id
        );
        $largest_product_volume = array_reduce($dimensions, function ($carry, $dimension) {
            return (float) $carry * $dimension;
        }, 1);
        if ($volume > 0 && $largest_product_volume > 0) {
            // Коэффициент
            $k = pow($volume / ($largest_product_volume / 1000000), 1/3);
            foreach ($dimensions as $key => $dimension) {
                $dimensions[$key] = round($dimension * $k, 2);
            }
        }
        return $dimensions;
    }

    /**
     * @param array $totals
     * @return float
     */
    public function getSumOrderFromTotals(array $totals)
    {
        foreach ($totals as $data) {
            if ($data['code'] === 'sub_total') {
                return $data['value'];
            }
        }
        return 0;
    }

    /**
     * @param float $previous
     * @param float $current
     * @param float $default
     * @param integer $current_length_class_id
     * @param integer $length_class_id
     * @return float
     */
    protected function getDimensionByMax(
        $previous,
        $current,
        $default,
        $current_length_class_id,
        $length_class_id
    ) {
        if ($previous < $current && $current > 0 && $current_length_class_id !== 0) {
            return $this->length->convert(
                $current,
                $current_length_class_id,
                $length_class_id
            );
        }
        if ($previous < $default) {
            return $default;
        }
        return $previous;
    }

    /**
     * @param float $previous
     * @param float $current
     * @param float $default
     * @param integer $current_length_class_id
     * @param integer $length_class_id
     * @return float
     */
    protected function getDimensionBySum(
        $previous,
        $current,
        $default,
        $current_length_class_id,
        $length_class_id
    ) {
        if ($current > 0 && $current_length_class_id !== 0) {
            return $previous + $this->length->convert(
                $current,
                $current_length_class_id,
                $length_class_id
            );
        }
        return $previous + $default;
    }

    /**
     * @param array $delivery
     * @return array
     */
    protected function quoteData(array $delivery)
    {
        return array(
            // Opencart naming convention
            'code' => $this->shipping_code . '.' . $delivery['code'],
            'title' => $delivery['place']. '. ' . $delivery['payment'],
            'cost' => $delivery['cost'],
            'tax_class_id' => $this->config->get('config_tax'),
            'text' => $this->currency->format(
                $this->tax->calculate($delivery['cost'], $this->config->get('config_tax')),
                $this->config->get('config_tax')
            ),
            'cost_components' => (isset($delivery['cost_components']) && is_array($delivery['cost_components']))
                ? $delivery['cost_components'] : array(),
            'payment_type' => $delivery['payment_type'],
            'default_customer' => array(
                'payment' => $delivery['payment'],
                'delivery' => $delivery['delivery'],
                'place' => $delivery['place'],
                'dcost' => $delivery['dcost'],
                'available' => '',
                'fullcost' => $delivery['fullcost'],
                'prepayment' => $delivery['prepayment']
            )
        );
    }

    /**
     * Get dummy products
     *
     * @return array
     */
    protected function getDummyProducts()
    {
        return array(
            array(
                'quantity' => 1,
                'category_id' => 0,
                'weight' => 0,
                'weight_class_id' => static::WEIGHT_G,
                'height' => 0,
                'length' => 0,
                'width' => 0,
                'length_class_id' => static::LENGTH_CM
            )
        );
    }

    /**
     * @param array $products
     * @return void
     */
    public function setProducts(array $products)
    {
        $this->productsCache = $this->addCategoryInfoToProducts($products);
    }

    /**
     * Get products
     *
     * @return array
     */
    protected function getProducts()
    {
        if (empty($this->productsCache)) {
            $products = $this->cart->getProducts();
            if (count($products) > 0) {
                $this->setProducts($products);
            }
        }
        return $this->productsCache;
    }

    /**
     * @param array $products
     * @return void
     */
    protected function addCategoryInfoToProducts(array $products)
    {
        $query = $this->db->query(sprintf(
            'SELECT product_id, category_id FROM '
            . DB_PREFIX . 'product_to_category WHERE product_id IN (%s) AND main_category = 1',
            implode(',', array_map(function ($item) {
                return $item['product_id'];
            }, $products))
        ));
        foreach ($query->rows as $row) {
            foreach ($products as $key => $product) {
                if ($product['product_id'] === $row['product_id']) {
                    $products[$key]['category_id'] = $row['category_id'];
                    break;
                }
            }
        }
        return $products;
    }

    /**
     * @param integer $category_id
     * @return float
     */
    protected function getProductDefaultWeightByCategory($category_id)
    {
        $config = $this->getConfigCategoryData();
        foreach ($config as $data) {
            if ((int) $data['category_id'] === $category_id) {
                return (float) $data['weight'];
            }
        }
        return 0;
    }

    /**
     * @param integer $category_id
     * @return array
     */
    protected function getProductDefaultDimensionsByCategory($category_id)
    {
        $config = $this->getConfigCategoryData();
        foreach ($config as $data) {
            if ((int) $data['category_id'] === $category_id) {
                return array(
                    'height' => $data['size_a'],
                    'length' => $data['size_b'],
                    'width' => $data['size_c']
                );
            }
        }
        return array();
    }

    /**
     * @return array
     */
    protected function getConfigCategoryData()
    {
        return array();
    }
}
