<?php

class ModelModuleProductSizes extends Model
{
    public function getProductSize(
        array $product,
        array $categories,
        array $options,
        $sex = null
    ) {
        $sizes = $this->config->get('productsizes');
        $sizetype = $this->getSizeType($options);
        $category_map = $this->mapCategories($categories);
        $matches = array();
        $matches_subpriority = array();
        if (is_array($sizes)) {
            foreach ($sizes as $size) {
                if ($size['type'] === $sizetype) {
                    // Приоритет между правилами по параметру, чем НИЖЕ значение тем выше приоритет
                    $priority = empty($size['product_id'])
                        && empty($size['name'])
                        && empty($size['sex'])
                        && empty($size['category_id']) ? 5 : 0;
                    // Субприоритет между правилами совпадающими по приоритету,
                    // чем ВЫШЕ значение (т.к. количество уровней субприоритета может быть неограниченно)
                    // тем выше субприоритет
                    $subpriority = 0;
                    if (!empty($size['product_id']) && in_array($product['product_id'], $size['product_id'])) {
                        $priority = 1;
                    } elseif (!empty($size['name']) && mb_stripos($product['fullname'], $size['name'], 0, 'UTF-8') !== false) {
                        $priority = 2;
                    } elseif (!empty($size['sex']) && $size['sex'] === $sex) {
                        $priority = 3;
                    } elseif (!empty($size['category_id']) && isset($category_map[$size['category_id']])) {
                        $priority = 4;
                        $subpriority = $category_map[$size['category_id']];
                    }
                    if ($priority !== 0 &&
                        (
                            !isset($matches_subpriority[$priority])
                            || $matches_subpriority[$priority] < $subpriority
                        )
                    ) {
                        $matches[$priority] = array(
                            'image' => '/image/' . $size['image'],
                            'caption' => $size['caption'],
                            'text' => $size['text']
                        );
                        if ($subpriority > 0) {
                            $matches_subpriority[$priority] = $subpriority;
                        }
                    }
                }
            }
        }
        return count($matches) > 0 ? $matches[min(array_keys($matches))] : null;
    }

    /**
     * @param array $options
     * @return string
     */
    protected function getSizeType(array $options)
    {
        return array_reduce($options, function ($carry, $option) {
            if ($carry) {
                return $carry;
            }
            switch (mb_strtolower($option['name'], 'UTF-8')) {
                case 'размер обуви':
                    return 'shoes';
                case 'размер одежды':
                    return 'clothes';
            }
        }, '');
    }

    /**
     * @param array $categories
     * @return array
     */
    protected function mapCategories(array $categories)
    {
        $map = array();
        foreach ($categories as $level => $category) {
            $map[$category['category_id']] = $level + 1;
        }
        return $map;
    }
}
