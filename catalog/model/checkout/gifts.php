<?php

class ModelCheckoutGifts extends Model 
{
    /** @var array Models */
    protected $models;
    /** @var array ids of products */
    protected $allowedGifts;
    /** @var array rules */
    protected $rules;
    /**
     * Set model
     * @param string $name
     * @param object $model
     */
    public function setModel($name, $model) 
    {
        $this->models[$name] = $model;
    }

    /**
     * Get model
     * @param string $name
     */
    public function getModel($name) 
    {
        if (!isset($this->models[$name])) 
            throw new \InvalidArgumentException("Model $name is not set");
        return $this->models[$name];
    }

    /**
     * Get available gifts
     */
    public function getGifts() 
    {
        if (false === $this->giftsAllowed()) {
            return false;
        }
        
        $product_ids = $this->getGiftsAllowed(); 
        $rules = $this->getRules();

        $sum = $this->cart->getSubTotal() - $this->getGiftsPrice();
        if (count($product_ids) == 0) {
            return false;
        }
        if (false === $current_gift_status = $this->giftsStatusConfirmed($sum)) {
            // Не отдаем подарки если не надо показывать инфу по акции
            if (!$this->config->get('catalog_gifts_show_info')) return false;
            $available = 0;
        } else {
            $available = $rules[$current_gift_status];
        }
        if (isset($this->session->data['cart.gifts']) && $available !== 0) {
            $selected = array();
            foreach ($this->session->data['cart.gifts'] as $gift) {
                $selected[] = $gift['product_id'];
            }
            // Если все подарки для текущего статуса выбраны не даем подарки на выбор
            if ($rules[$current_gift_status] <= count($selected)) {
                return false;
            }
            $available = $rules[$current_gift_status] - count($selected);
            // Remove already selected
            $product_ids = array_diff($product_ids, $selected);
        }
        $count = count($product_ids);
        if (!$count) return false;
        // Get random gifts
        $gifts = array();
        for ($i = 0; $i < 3 || ($count < 3 && $i < $count); $i++) {
            $index = rand(0, count($product_ids) - 1);
            $gifts[] = $product_ids[$index];
            unset($product_ids[$index]);
            $product_ids = array_values($product_ids);
        }

        // Prepare gifts
        foreach ($gifts as $key => $gift) {
            $tmp = $this->prepareGift($gift);
            if ($tmp) {
                $gifts[$key] = $tmp;
            } else {
                unset($gifts[$key]);
            } 
        }
        if (!count($gifts)) return false;

        return array(
            'items' => $gifts, 
            'rules' => $rules, 
            'available' => $available, 
            'minimal_sum' => min(array_keys($rules))
        );
    }

    /**
     * 
     * @param bool $add
     */
    public function checkGifts($add = false) 
    {
        $rules = $this->getRules();
        // Check by user group
        if (false === $this->giftsAllowed()) {
            if (isset($this->session->data['cart.gifts'])) {
                foreach ($this->session->data['cart.gifts'] as $cart_key => $gift) {
                    $this->cart->remove($cart_key);
                }
                unset($this->session->data['cart.gifts']);
            }
            return false;
        }
        $sum = $this->cart->getSubTotal() - $this->getGiftsPrice();
        $this->checkCartItems();
        // Check by count
        if (false === $gift_status = $this->giftsStatusConfirmed($sum)) {
            // Удаляем все подарки если пользователь не достиг 
            // минимального статуса для получения подарков
            if (isset($this->session->data['cart.gifts'])) {
                foreach ($this->session->data['cart.gifts'] as $cart_key => $gift) {
                    $this->cart->remove($cart_key);
                }
                unset($this->session->data['cart.gifts']);
            }
            return false;
        }
        // Удаляем лишние подарки
        // которые по статусу не доступны пользователю
        // если например пользователь уменьшил сумму товаров в корзине
        $allowed = ($gift_status) ? $rules[$gift_status] : 0;
        if (isset($this->session->data['cart.gifts']) &&
            count($this->session->data['cart.gifts']) >= $allowed) {
            $count = count($this->session->data['cart.gifts']);
            $gifts = array_values($this->session->data['cart.gifts']);
            // Если добавляем товар в корзину, и количество выбранных подарков уже равно доступным, 
            // то запрещаем добавление подарка
            if ($count == $allowed && $add) {
                return false;
            }
            for ($i = $allowed; $i < $count; $i++) {
                $this->cart->remove($gifts[$i]['cart_key']);
                $this->removeGiftByCartKey($gifts[$i]['cart_key']);
            }
        }
        return true;
    }
    /**
     * @param array $product_info
     * @param string $cart_key
     */
    public function addGift($product_info, $cart_key)
    {
        if (!isset($this->session->data['cart.gifts'])) $this->session->data['cart.gifts'] = array();
        $this->session->data['cart.gifts'][$cart_key] = array(
            'product_id' => $product_info['product_id'], 
            'price' => $product_info['price'],
            'cart_key' => $cart_key
        );
    }
    /**
     * @param string $cart_key
     */
    public function inGifts($cart_key) 
    {
        $inGifts = isset($this->session->data['cart.gifts'][$cart_key]);
        // Проверяем на всякий случай, чтобыв корзине был максимум один подарок по артикулу
        if ($inGifts) {
            // Если вдруг это уже не подарок
            if (!$this->isGift($this->session->data['cart.gifts'][$cart_key]['product_id'])) {
                $this->removeGiftByCartKey($cart_key);
                return false;
            }
            $this->cart->update($cart_key, 1);
        }
        return $inGifts;
    }
    /**
     * @param string $cart_key
     */
    public function removeGiftByCartKey($cart_key)
    {
        if (!isset($this->session->data['cart.gifts'][$cart_key])) return false;
        unset($this->session->data['cart.gifts'][$cart_key]);
    }

    /** 
     * Remove gifts from session
     */
    public function clearGifts()
    {
        unset($this->session->data['cart.gifts']);
    }


    /**
     * @return int
     */
    public function getGiftsPrice()
    {
        $sum = 0;
        if (isset($this->session->data['cart.gifts'])) {
            foreach ($this->session->data['cart.gifts'] as $cart_key => $gift) {
                // Если товар есть в подарках, но нету в корзине надо его удалить из подарков
                if (!isset($this->session->data['cart'][$cart_key])) {
                    $this->removeGiftByCartKey($cart_key);
                    continue;
                }
                if (!$this->inGifts($cart_key)) {
                    continue;
                }
                $sum += $gift['price'];
            }
        }
        return $sum;
    }

    /**
     * @param int $sum
     */
    protected function giftsStatusConfirmed($sum)
    {
        $totals = array_keys($this->getRules());
        // Текущий статус подарков (какая сумма открыта)
        $current_gift_status = 0;
        foreach ($totals as $total) {
            if ((int) $sum > (int) $total) {
                $current_gift_status = $total;
            }
        }
        
        // Если нужная сумма не набралась не даем подарки
        if ($current_gift_status == 0) return false;

        return $current_gift_status;
    }

    /**
     * Check if gifts are allowed for user
     * @return bool
     */
    protected function giftsAllowed() 
    {
        if ($this->customer->isLogged()) {
            $customer_group_id = (int) $this->customer->getCustomerGroupId();
        } else {
            $customer_group_id = (int) $this->config->get('config_customer_group_id');
        }
        
        if ($customer_group_id === 1) return true;

        return false;
    }

    /**
     * @param int $id
     */
    protected function prepareGift($id) 
    {
        $product = $this->getModel('product')->getProduct($id);
        if (!$product || $product['quantity'] == 0) return false;
        // Prepare Image
        if ($product['image']) {
            $image = $this->getModel('image')->resize(
                $product['image'], 
                100, 
                100
            );
        } else {
            $image = '';
        }
        
        // Display prices
        if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || 
            !$this->config->get('config_customer_price')) {
            $price = $this->currency->format(
                $this->tax->calculate(
                    $product['price'], 
                    $product['tax_class_id'], 
                    $this->config->get('config_tax')
                )
            );
        } else {
            $price = false;
        }

        return array(
                'thumb'    => $image,
                'name'     => $product['manufacturer'] . ' ' . $product['name'],
                'product_id'     => $product['product_id'],
                'model'    => $product['model'],
                'option'   => $option_data,
                'price'    => $price,
                'href'     => $this->url->link('product/product', 'product_id=' . $product['product_id'])
        );
    }

    /**
     * Check if product is a gift
     */
    public function isGift($id) {
        if (in_array($id, $this->getGiftsAllowed())) {
            return true;
        }
        return false;
    }

    /**
     * Return allowed for gifts
     */
    protected function getGiftsAllowed() 
    {
        if ($this->allowedGifts) return $this->allowedGifts;
        $ids = explode(',', $this->config->get('catalog_gifts_list'));
        foreach ($ids as $key => $id) {
            $ids[$key] = (int) $id;
            if (empty($ids[$key])) unset($ids[$key]);
        }
        $this->allowedGifts = $ids;
        return $ids;
    }

    /**
     * Get Rules
     */
    protected function getRules() 
    {
        if ($this->rules) return $this->rules;
        $rules = json_decode(html_entity_decode($this->config->get('catalog_gifts_rules')), true);
        if (json_last_error()) {
            $rules = array(
                3000 => 1,
                6000 => 2,
                9000 => 3
            );
        }
        $this->rules = $rules;
        return $rules;
    }
    /**
     * Check if cart items are gifts
     */
    protected function checkCartItems()
    {
        if (!isset($this->session->data['cart'])) return false;

        foreach ($this->session->data['cart'] as $key => $item) {
            $product = explode(':', $key);
            $product_id = $product[0];
            if ($this->isGift($product_id) && !$this->inGifts($key)) {
                $price = $this->db->query("SELECT `price` FROM " . DB_PREFIX . "product WHERE `product_id` = " . (int) $product_id);
                $this->addGift(array('product_id' => $product_id, 'price' => $price->row['price']), $key);
            } 
        }
    }
}