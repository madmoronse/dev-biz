<?php
class ModelDropProxy extends Model
{
    /**
     * @param array $items
     * @param integer $customer_group_id
     * @return void
     */
    public function getProductsFromRequest(array $items, $customer_group_id)
    {
        $products = array();
        foreach ($items as $item) {
            $product_id = $item['product_id'];
            $quantity = $item['quantity'];
            $product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.date_available <= NOW() AND p.status = '1'");    
            if ($product_query->num_rows) {
                $price = $product_query->row['price'];
                // Product Discounts
                $discount_quantity = 0;
                foreach ($items as $item_2) {
                    if ($item_2['product_id'] == $product_id) {
                        $discount_quantity += $item_2['quantity'];
                    }
                }
                $product_discount_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND quantity <= '" . (int)$discount_quantity . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1");
                if ($product_discount_query->num_rows > 0) {
                    $price = $product_discount_query->row['price'];
                }
                $products[] = array(
                    'product_id' => $product_query->row['product_id'],
                    'name' => $product_query->row['name'],
                    'model' => $product_query->row['model'],
                    'shipping' => $product_query->row['shipping'],
                    'image' => $product_query->row['image'],
                    'quantity' => $quantity,
                    'minimum' => $product_query->row['minimum'],
                    'subtract' => $product_query->row['subtract'],
                    'price' => $price,
                    'total' => $price * $quantity,
                    'tax_class_id' => $product_query->row['tax_class_id'],
                    'weight' => $product_query->row['weight'] * $quantity,
                    'weight_class_id' => $product_query->row['weight_class_id'],
                    'length' => $product_query->row['length'],
                    'width' => $product_query->row['width'],
                    'height' => $product_query->row['height'],
                    'length_class_id' => $product_query->row['length_class_id']
                );
            }
        }
        return $products;
    }
}
