<?php
Class Model_Products Extends Model
{
    public function getCategories()
    {
        $category = $this->db->query("SELECT * FROM category");

        return $category->rows;
    }

    public function getCategory($id)
    {
        $category = $this->db->query("SELECT * FROM category WHERE id = '" . $id . "'");

        return $category->row;
    }

    public function getProducts($category_id)
    {
        $products = $this->db->query("SELECT * FROM products WHERE category_id = '" . $category_id . "'");

        return $products->rows;
    }

    public function getProduct($id)
    {
        $product = $this->db->query("SELECT * FROM products WHERE id = '" . $id . "'");

        return $product->rows;
    }
}