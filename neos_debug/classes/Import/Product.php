<?php

namespace Neos\classes\Import;

use DateTime;
use Neos\libraries\SafeMySQL;
use Neos\classes\helpers\StringHelper;
use Neos\classes\Import\Dictionaries\DatabaseCategoryTree;
use Neos\Import1C\Dictionaries\CategoryTree;
use Neos\Import1C\Dictionaries\SerializableDictionary;
use Neos\Import1C\Entities\Product as ImportProduct;
use Neos\Import1C\Helpers\ImageConverter;
use Neos\Import1C\Helpers\Str;
use Psr\Log\LoggerInterface;
use stdClass;

class Product extends AbstractImport
{
    const MAP_PRODUCT_TO_IMPORT = [
        'model' => 'vendor_code',
        'sku' => 'vendor_code',
        'jan' => 'name',
        'fullname' => 'name',
        'weight' => 'weight',
        'length' => 'length',
        'width' => 'width',
        'height' => 'height',
    ];

    /**
     * @var CategoryTree
     */
    protected $category_tree;

    /**
     * @var DatabaseCategoryTree
     */
    protected $db_category_tree;

    /**
     * @var SerializableDictionary
     */
    protected $images;

    /**
     * @var SerializableDictionary
     */
    protected $offers;

    /**
     * @var string
     */
    protected $import_path;

    /**
     * @var string
     */
    protected $sitename;

    /**
     * @var array
     */
    protected $images_to_remove = [];

    /**
     * @var boolean
     */
    protected $clean_image_directory = false;

    /**
     * @var DateTime
     */
    protected $recreate_product_categories_added_after;

    /**
     * @param SafeMySQL $db
     */
    public function __construct(
        SafeMySQL $db,
        LoggerInterface $logger,
        CategoryTree $category_tree,
        DatabaseCategoryTree $db_category_tree,
        SerializableDictionary $images,
        SerializableDictionary $offers,
        array $options
    ) {
        parent::__construct($db, $logger);
        $this->category_tree = $category_tree;
        $this->db_category_tree = $db_category_tree;
        $this->images = $images;
        $this->offers = $offers;
        if (!is_dir($options['import_path'])) {
            throw new \InvalidArgumentException('Expected \'import_path\' to be a directory');
        }
        if (empty($options['sitename'])) {
            throw new \InvalidArgumentException('Expected \'sitename\' params');
        }
        $this->import_path = rtrim($options['import_path'], '/') . '/';
        $this->sitename = trim($options['sitename']);
        $this->clean_image_directory = !empty($options['clean_image_directory']);
        $this->recreate_product_categories_added_after = !empty(
            $options['recreate_product_categories_added_after']
        )
            ? DateTime::createFromFormat('Y-m-d', $options['recreate_product_categories_added_after'])
            : null;
        if ($this->recreate_product_categories_added_after) {
            $this->recreate_product_categories_added_after->setTime(0, 0, 0);
        }
    }

    /**
     * @inheritDoc
     */
    public function import($import)
    {
        $this->images_to_remove = [];
        $context = $this->getContext($import);
        $product = $this->atomicImport(function () use ($import, $context) {
            $product = parent::import($import);
            $this->logger->debug(
                'New attributes: ' . $this->importProductAttributes($product, $import),
                $context
            );
            $this->importProductDescription($product, $import);
            $this->logger->debug(
                'New images: ' . $this->importProductImages($product, $import),
                $context
            );
            $this->importProductLabels($product, $import);
            return $product;
        });
        // After database operations are complete perform changes to filesystem
        foreach ($this->images_to_remove as $image) {
            $this->removeImage($image);
        }
        return $product;
    }

    /**
     * @param ImportProduct $import
     * @return stdClass|null
     */
    public function fetch($import)
    {
        $product = $this->db->getRow(
            'SELECT * FROM ?n WHERE product_id = ?i',
            $this->getTable('product'),
            $import->vendor_code
        );
        if (!$product) {
            return null;
        }
        return (object) $product;
    }

    /**
     * @param ImportProduct $import
     * @return stdClass
     */
    public function create($import)
    {
        $product = (object) [
            'stock_status_id' => 7,
            'manufacturer_id' => 0,
            'upc' => '',
            'ean' => '',
            'isbn' => '',
            'mpn' => '',
            'location' => '',
            'quantity' => 0,
            'shipping' => 1,
            'price' => 0,
            'discount' => 0,
            'points' => 0,
            'tax_class_id' => 0,
            'date_available' => date('Y-m-d'),
            'weight_class_id' => 1,
            'length_class_id' => 1,
            'subtract' => 1,
            'minimum' => 1,
        ];
        $this->setProductData($product, $import);
        $this->db->query('INSERT INTO ?n SET ?u', $this->getTable('product'), (array) $product);
        // Set to store
        $this->db->query(
            'INSERT INTO ?n SET product_id = ?i, store_id = ?i',
            $this->getTable('product_to_store'),
            $product->product_id,
            static::DEFAULT_STORE
        );
        // Import categories only on create!
        $this->logger->debug(
            'Imported categories: ' . $this->importProductCategories($product, $import),
            $this->getContext($import)
        );
        $alias_id = $this->createUrlAlias(
            'product_id="' . $product->product_id . '"',
            trim(Str::urlSafe($product->fullname) . '-' . $product->product_id, '-')
        );
        $this->logger->debug(
            'Created url alias, id: ' . $alias_id,
            $this->getContext($import)
        );
        return $product;
    }

    /**
     * @param stdClass $product
     * @param ImportProduct $import
     * @param boolean $updated
     * @return stdClass
     */
    public function update(stdClass $product, $import, &$updated)
    {
        $this->setProductData($product, $import);
        $this->db->query(
            'UPDATE ?n SET ?u WHERE product_id = ?i',
            $this->getTable('product'),
            (array) $product,
            $product->product_id
        );
        $date_added = DateTime::createFromFormat('Y-m-d H:i:s', $product->date_added);
        if (!is_null($this->recreate_product_categories_added_after)
            && $date_added >= $this->recreate_product_categories_added_after
        ) {
            $this->clearProductCategories($product);
            $this->logger->debug(
                'Recreated categories: ' . $this->importProductCategories($product, $import),
                $this->getContext($import)
            );
        }
        $updated = $this->db->affectedRows() > 0;
        return $product;
    }

    /**
     * @param stdClass $product
     * @param ImportProduct $import
     * @return array
     */
    protected function setProductData(stdClass $product, ImportProduct $import)
    {
        foreach (static::MAP_PRODUCT_TO_IMPORT as $dest => $source) {
            $product->$dest = $import->$source;
        }
        // Use vendor code as product id
        if (!isset($product->product_id)) {
            $product->product_id = (int) $import->vendor_code;
        }
        $product->status = $import->publish ? 1 : 0;
        if (in_array($this->sitename, $import->exclude_sitenames)) {
            $product->status = 0;
            $this->logger->debug('Excluding product by sitename', $this->getContext($import));
        }
        if (!is_null($import->manufacturer) && !is_null($import->manufacturer->id)) {
            $product->manufacturer_id = $import->manufacturer->id;
        } else {
            $this->logger->warning(
                'Manufacturer id not set, name: ' . ($import->manufacturer->name ?? 'none'),
                $this->getContext($import)
            );
        }
        if (!isset($product->date_added)) {
            $product->date_added = $import->modified_at->format('Y-m-d H:i:s');
        }
        $product->date_modified = $import->modified_at->format('Y-m-d H:i:s');
        foreach ($import->properties as $property_value) {
            $property_name = $this->prepareName($property_value->property->name);
            switch ($property_name) {
                case 'предмет одежды':
                    $product->mpn = $property_value->value;
                    break;
            }
        }
        $this->setProductPriceAndQuantity($product, $this->getContext($import));
        // Final modifications
        $product->points = (int) $product->price;
        $product->sort_order = $product->product_id;
    }

    /**
     * @param stdClass $product
     * @param ImportProduct $import
     * @return integer Number of attributes imported
     */
    protected function importProductAttributes(stdClass $product, ImportProduct $import)
    {
        $attributes = array_reduce($this->db->getAll(
            'SELECT * FROM ?n WHERE product_id = ?i AND language_id = ?i',
            $this->getTable('product_attribute'),
            $product->product_id,
            static::DEFAULT_LANGUAGE
        ), function ($carry, $attribute) {
            $attribute_id = $attribute['attribute_id'];
            if (isset($carry[$attribute_id])) {
                $carry[$attribute_id]['values'][] = $attribute['text'];
            } else {
                $carry[$attribute_id] = [
                    'attribute_id' => $attribute_id,
                    'values' => [$attribute['text']]
                ];
            }
            return $carry;
        }, []);
        $create = [];
        $current_attributes_ids = [];
        $previous_attributes_ids = array_keys($attributes);
        foreach ($import->properties as $property_value) {
            // Ignore properties without id
            if (is_null($property_value->property->id)) {
                continue;
            }
            $attribute_id = $property_value->property->id;
            $current_attributes_ids[] = $attribute_id;
            $is_new = true;
            foreach ($attributes as $attribute) {
                // Check if property matches existing attribute
                if ($attribute['attribute_id'] === $attribute_id) {
                    // Check values if there is only one attribute
                    if (count($attribute['values']) === 1
                        && StringHelper::equal($attribute['values'][0], $property_value->value)
                    ) {
                        $is_new = false;
                    // Remove previous attributes if value differs
                    // or we have more than 1 value of the attribute (ignore sex)
                    } elseif ($this->prepareName($property_value->property->name) !== 'пол') {
                        $this->db->query(
                            'DELETE FROM ?n WHERE product_id = ?i AND language_id = ?i AND attribute_id = ?i',
                            $this->getTable('product_attribute'),
                            $product->product_id,
                            static::DEFAULT_LANGUAGE,
                            $attribute_id
                        );
                        if ($this->db->affectedRows() > 0) {
                            $this->logger->debug(
                                'Removed previous product attribute: ' . $attribute_id,
                                $this->getContext($import)
                            );
                        } else {
                            $this->logger->alert(
                                'Failed to delete previous product attribute: ' . $attribute_id,
                                $this->getContext($import)
                            );
                            $is_new = false;
                        }
                    }
                    break;
                }
            }
            if ($is_new) {
                $create[] = $this->db->parse(
                    "(?i, ?i, ?i, ?s)",
                    $product->product_id,
                    $attribute_id,
                    static::DEFAULT_LANGUAGE,
                    StringHelper::ucfirst($property_value->value)
                );
            }
        }
        $obsolete_attributes_ids = array_diff($previous_attributes_ids, $current_attributes_ids);
        if (count($obsolete_attributes_ids) > 0) {
            $this->db->query(
                'DELETE FROM ?n WHERE product_id = ?i AND language_id = ?i AND attribute_id IN (?a)',
                $this->getTable('product_attribute'),
                $product->product_id,
                static::DEFAULT_LANGUAGE,
                $obsolete_attributes_ids
            );
            $this->logger->info(
                'Removed obsolete product attributes: ' . implode(', ', $obsolete_attributes_ids),
                $this->getContext($import)
            );
        }

        if (count($create) > 0) {
            $this->db->query(
                'INSERT INTO ?n (product_id, attribute_id, language_id, `text`) VALUES ?p',
                $this->getTable('product_attribute'),
                implode(',', $create)
            );
            return $this->db->affectedRows();
        }
        return 0;
    }

    /**
     * @param stdClass $product
     * @param ImportProduct $import
     * @return stdClass
     */
    protected function importProductDescription(stdClass $product, ImportProduct $import)
    {
        $description = $this->db->getRow(
            'SELECT * FROM ?n WHERE product_id = ?i AND language_id = ?i',
            $this->getTable('product_description'),
            $product->product_id,
            static::DEFAULT_LANGUAGE
        );
        $is_new = false;
        if (!$description) {
            $description = [
                'language_id' => static::DEFAULT_LANGUAGE
            ];
            $description['product_id'] = $product->product_id;
            $is_new = true;
        }
        $name_changed = false;
        if (!isset($description['name']) || !StringHelper::equal($description['name'], $import->name)) {
            $name_changed = true;
            $description['name'] = $import->name;
        }
        $description['description'] = $import->description ?? '';
        $color = '';
        $tag = '';
        foreach ($import->properties as $property_value) {
            $property_name = $this->prepareName($property_value->property->name);
            switch ($property_name) {
                case 'цвет':
                    $color = "цвет: {$property_value->value}";
                    break;
                case 'модель':
                    $tag = $property_value->value;
                    break;
            }
        }
        // Set meta from import or use default meta if name changed or meta is not set
        $default_meta = implode(' ', array_filter([
            $import->name,
            $color
        ]));
        if ($import->meta->description) {
            $description['meta_description'] = $import->meta->description;
        } elseif (!isset($description['meta_description']) || $name_changed) {
            $description['meta_description'] = $default_meta;
        }
        if ($import->meta->keywords) {
            $description['meta_keyword'] = $import->meta->keywords;
        } elseif (!isset($description['meta_keyword']) || $name_changed) {
            $description['meta_keyword'] = $default_meta;
        }
        if ($import->meta->title) {
            $description['seo_title'] = $import->meta->title;
        } elseif (!isset($description['seo_title']) || $name_changed) {
            $description['seo_title'] = $default_meta;
        }
        if (!isset($description['seo_h1']) || $name_changed) {
            $description['seo_h1'] = $description['seo_title'];
        }
        if (!isset($description['tag'])) {
            $description['tag'] = $tag;
        }
        if ($is_new) {
            $this->db->query(
                'INSERT INTO ?n SET ?u',
                $this->getTable('product_description'),
                $description
            );
        } else {
            $this->db->query(
                'UPDATE ?n SET ?u WHERE product_id = ?i AND language_id = ?i',
                $this->getTable('product_description'),
                $description,
                $product->product_id,
                static::DEFAULT_LANGUAGE
            );
        }
        return (object) $description;
    }

    /**
     * @param stdClass $product
     * @param ImportProduct $import
     * @return integer
     */
    public function importProductImages(stdClass $product, ImportProduct $import)
    {
        $context = $this->getContext($import);
        // Do nothing with images during leftovers update
        if ($import->leftovers_update) {
            if (empty($product->image) && $product->status == 1) {
                $product->status = 0;
                $this->logger->notice('Unpublishing product due to no image', $context);
                $this->db->query(
                    'UPDATE ?n SET `status` = ?i WHERE `product_id` = ?i',
                    $this->getTable('product'),
                    $product->status,
                    $product->product_id
                );
            }
            return 0;
        }
        $images = $this->db->getAll(
            'SELECT * FROM ?n WHERE product_id = ?i',
            $this->getTable('product_image'),
            $product->product_id
        );
        $delete_ids = [];
        $do_not_import = [];
        // Unset image if no images were imported
        if (count($import->images) === 0) {
            if (isset($product->image) && $product->image !== '') {
                $this->images_to_remove[] = $product->image;
            }
            $product->image = '';
            $product->status = 0;
            $this->logger->notice('Unpublishing product due to no image', $context);
        }
        $import_images = $this->copyImages($product, $import);
        // Update main image
        if (count($import_images) > 0) {
            $main_image = array_shift($import_images);
            if ($main_image !== $product->image && !empty($product->image)) {
                $this->images_to_remove[] = $product->image;
            }
            $product->image = $main_image;
        }
        // Update product main image
        $this->db->query(
            'UPDATE ?n SET `image` = ?s, `status` = ?i WHERE `product_id` = ?i',
            $this->getTable('product'),
            $product->image,
            $product->status,
            $product->product_id
        );
        // Set other images
        foreach ($images as $image) {
            $image_name = $image['image'];
            $index = array_search($image_name, $import_images);
            if ($index === false) {
                $delete_ids[] = $image['product_image_id'];
                $this->logger->notice("Removing image: {$image_name}", $context);
                $this->images_to_remove[] = $image_name;
            } else {
                $do_not_import[] = $import_images[$index];
                $this->logger->debug("Image exists: {$image_name}", $context);
            }
        }
        if (count($delete_ids) > 0) {
            $this->db->query(
                'DELETE FROM ?n WHERE product_image_id IN (?a)',
                $this->getTable('product_image'),
                $delete_ids
            );
            $removed = $this->db->affectedRows();
            if ($removed > 0) {
                $this->logger->notice('Removed images from database: ' . $removed, $context);
            }
        }
        if ($this->clean_image_directory) {
            $this->cleanImageDirectory(
                $product,
                isset($main_image) ? array_merge([$main_image], $import_images) : $import_images,
                $context
            );
        }
        $create = array_diff($import_images, $do_not_import);
        if (count($create) > 0) {
            $this->db->query(
                'INSERT INTO ?n (product_id, image) VALUES ?p',
                $this->getTable('product_image'),
                implode(',', array_map(
                    function ($image) use ($product) {
                        return $this->db->parse('(?i, ?s)', $product->product_id, $image);
                    },
                    $import_images
                ))
            );
            return $this->db->affectedRows();
        }
        return 0;
    }

    /**
     * @param stdClass $product
     * @return void
     */
    public function clearProductCategories(stdClass $product)
    {
        $this->db->query(
            'DELETE FROM ?n WHERE product_id = ?i',
            $this->getTable('product_to_category'),
            $product->product_id
        );
    }

    /**
     * @param stdClass $product
     * @param ImportProduct $import
     * @return integer
     */
    public function importProductCategories(stdClass $product, ImportProduct $import)
    {
        if (count($import->categories) === 0) {
            return 0;
        }
        $main_category = $import->categories[0];
        $parent_categories = $this->category_tree->getParentCategories($main_category->import_id);
        // Try to get category by property
        $category_by_property = $this->getProductCategoryByProperty(
            $import,
            array_merge([$main_category], $parent_categories)
        );
        $update_parent_categories = false;
        if (!is_null($category_by_property)) {
            $main_category = $category_by_property;
            $update_parent_categories = true;
        }
        // Get manufacturer category
        if (!empty($import->manufacturer)) {
            $manufacturer_category = $this->db_category_tree->getChildCategoryByName(
                $main_category->id,
                $this->prepareName($import->manufacturer->name)
            );
            if (!is_null($manufacturer_category)) {
                $main_category = $manufacturer_category;
                $update_parent_categories = true;
            }
        }
        if ($update_parent_categories) {
            $parent_categories = $this->db_category_tree->getParentCategories($main_category->id);
        }
        $context = $this->getContext($import);
        $create = [
            $this->db->parse('(?i, ?i, ?i)', $product->product_id, $main_category->id ?? 1, 1)
        ];
        foreach ($parent_categories as $category) {
            if (is_null($category->id)) {
                $this->logger->warning('Ignoring category without id. Category name: ' . $category->name, $context);
                continue;
            }
            $create[] = $this->db->parse('(?i, ?i, ?i)', $product->product_id, $category->id, 0);
        }
        $this->db->query(
            'INSERT INTO ?n (product_id, category_id, main_category) VALUES ?p',
            $this->getTable('product_to_category'),
            implode(',', $create)
        );
        return $this->db->affectedRows();
    }

    /**
     * @param stdClass $product
     * @param ImportProduct $import
     * @return void
     */
    public function importProductLabels(stdClass $product, ImportProduct $import)
    {
        $category = $this->db_category_tree->getCategoryByName('акция', []);
        $context = $this->getContext($import);
        if (is_null($category)) {
            $this->logger->warning('Sale category not found', $context);
            return;
        }
        $product_is_in_sale_category = $this->db->getOne(
            'SELECT count(*) FROM ?n WHERE product_id = ?i AND category_id = ?i',
            $this->getTable('product_to_category'),
            $product->product_id,
            $category->id
        ) > 0;
        $product_has_sale_filter = $this->db->getOne(
            'SELECT count(*) FROM ?n WHERE product_id = ?i AND filter_id = ?i',
            $this->getTable('product_filter'),
            $product->product_id,
            $category->id
        ) > 0;
        $product_should_be_in_sale_category = array_reduce($import->labels, function ($carry, $label) {
            if ($label->type === 'Акция' && $label->name === 'SALE') {
                return true;
            }
            return $carry;
        }, false);
        if ($product_should_be_in_sale_category) {
            if (!$product_is_in_sale_category) {
                $this->db->query(
                    'INSERT INTO ?n (product_id, category_id, main_category) VALUES (?p)',
                    $this->getTable('product_to_category'),
                    implode(',', [$product->product_id, $category->id, 0])
                );
                $this->logger->debug(
                    'Adding product to sale category',
                    $context
                );  
            }
            // NOTE: here we are using category id as sale filter - it is a hardcoded value
            if (!$product_has_sale_filter) {
                $this->db->query(
                    'INSERT INTO ?n (product_id, filter_id) VALUES (?p)',
                    $this->getTable('product_filter'),
                    implode(',', [$product->product_id, $category->id])
                );
                $this->logger->debug(
                    'Creating sale filter for product',
                    $context
                );  
            }

        } else {
            if ($product_is_in_sale_category) {
                $this->db->query(
                    'DELETE FROM ?n WHERE product_id = ?i AND category_id = ?i',
                    $this->getTable('product_to_category'),
                    $product->product_id,
                    $category->id
                );
                $this->logger->notice(
                    'Removing product from sale category',
                    $context
                );
            }
            if ($product_has_sale_filter) {
                $this->db->query(
                    'DELETE FROM ?n WHERE product_id = ?i AND filter_id = ?i',
                    $this->getTable('product_filter'),
                    $product->product_id,
                    $category->id
                );
                $this->logger->notice(
                    'Removing sale filter for product',
                    $context
                );
            }
        }
    }

    /**
     * @param ImportProduct $import
     * @param Category[] $parent_categories
     * @return Category
     */
    public function getProductCategoryByProperty(ImportProduct $import, array $parent_categories)
    {
        // Detect product type
        $product_type = null;
        $i = 0;
        $n = count($parent_categories);
        while (is_null($product_type) && $i < $n) {
            $category = $parent_categories[$i];
            $category_name = $this->prepareName($category->name);
            switch ($category_name) {
                case 'одежда':
                case 'кроссовки':
                case 'аксессуары':
                    $product_type = $category_name;
                    break;
            }
            $i++;
        }
        // Detect expected category name
        foreach ($import->properties as $property) {
            $property_name = $this->prepareName($property->property->name);
            $property_value = $this->prepareName($property->value);
            if ($product_type === 'одежда'
                && $property_name === 'модель'
                && in_array($property_value, ['штаны', 'ветровка', 'спортивный костюм', 'шапка'])
            ) {
                $name = $property_value;
            } elseif ($product_type === 'аксессуары'
                && $property_name === 'модель'
                && in_array($property_value, ['шапка'])
            ) {
                $name = $property_value;
            } elseif (!isset($name) && ($property_name === 'категория' || $property_name === 'предмет одежды')) {
                $name = $property_value;
            }
        }
        // Custom matches
        $property_to_category = [
            'футбол' => 'футбольные',
            'кроссовки с мехом' => 'зимние кроссовки',
            'футбольные костюмы' => 'футбольная форма',
            'куртки демисезонные' => 'демисезонные куртки',
            'худи' => 'толстовки',
            'ветровка' => 'ветровки',
            'тренировочные костюмы' => 'спортивные костюмы',
            'спортивный костюм' => 'спортивные костюмы',
            'куртки зимние' => 'зимние куртки',
            'шапка' => 'шапки,шарфы',
            'худи' => 'толстовки',
            'парки зимние' => 'парки',
            'жилеты' => 'жилет'
        ];
        if (isset($name)) {
            $expected_category_name = $property_to_category[$name] ?? $name;
            return $this->db_category_tree->getCategoryByName($expected_category_name, $parent_categories);
        }
        return null;
    }

    /**
     * @param string $name
     * @return string
     */
    protected function prepareName(string $name)
    {
        return trim(mb_strtolower($name));
    }

    /**
     * @param stdClass $product
     * @param ImportProduct $import
     * @return array
     */
    public function copyImages(stdClass $product, ImportProduct $import)
    {
        $context = $this->getContext($import);
        $images_checksums = $this->images->get($product->product_id);
        $dir = $this->getPathToProductImages($product);
        $dir_fullpath = $this->getImagePath($dir);
        $import_images = [];
        if (!is_dir($dir_fullpath)) {
            // If it's a file - that's totally unexpected
            if (is_file($dir_fullpath)) {
                $this->logger->alert("Product image directory is a file: $dir", $context);
                return $import_images;
            }
            mkdir($dir_fullpath, 0755, true);
        }
        $image_index = 1;
        foreach ($import->images as $image) {
            $import_image_path = $this->import_path . ltrim($image, '/');
            if (!file_exists($import_image_path)) {
                $this->logger->warning("Import image did not exists: {$image}", $context);
                continue;
            }
            $image_parts = explode('.', basename($image));
            $extension = end($image_parts);
            if (!in_array($extension, ['jpg', 'jpeg', 'png'])) {
                $this->logger->warning("Wrong image $image extension: $extension", $context);
                continue;
            }
            $new_image = $dir . '/' . $this->buildImageName($product->fullname, $image_index, $extension);
            $should_move = true;
            $checksum = md5_file($import_image_path);
            // Compare checksum only if dest file exists, in other case - we will import it
            if (isset($images_checksums[$new_image]) && file_exists($this->getImagePath($new_image))) {
                $should_move = $checksum !== $images_checksums[$new_image];
            }
            if ($should_move) {
                // Add image to result only if it succesfully got copied
                if (ImageConverter::copyImage($import_image_path, $this->getImagePath($new_image))) {
                    $import_images[] = $new_image;
                    $images_checksums[$new_image] = $checksum;
                } else {
                    $this->logger->error("Failed to copy image: $new_image", $context);
                }
            } else {
                $this->logger->debug("Image has not changed: {$new_image}", $context);
                $import_images[] = $new_image;
            }
            $this->images->set($product->product_id, $images_checksums);
            // Always increment image index, if some images didn't get copied there will be a gap in images name
            // that's totally inteded because next time we may be able to copy the image
            // and we don't want all images to change their names
            $image_index++;
        }
        return $import_images;
    }

    /**
     * @param stdClass $product
     * @param array $context
     * @return void
     */
    protected function setProductPriceAndQuantity(stdClass $product, array $context = [])
    {
        $info = $this->offers->get($product->product_id);
        if (is_null($info)) {
            $product->status = 0;
        } else {
            $product->price = (float) $info->price;
            $product->discount = (int) $info->discount;
            $product->quantity = (int) $info->quantity;
        }
        if ($product->status == 1) {
            if ($product->price <= 0) {
                $this->logger->notice(
                    'Unpublishing product due to price equal to zero',
                    $context
                );
                $product->status = 0;
            } elseif ($product->quantity <= 0) {
                $this->logger->notice(
                    'Unpublishing product due to quantity equal to zero',
                    $context
                );
                $product->status = 0;
            }
        }
    }

    /**
     * @param string $image
     * @return string
     */
    public function getImagePath(string $image)
    {
        return DIR_IMAGE . $image;
    }

    /**
     * @param stdClass $product
     * @param array $import_images
     * @param array $context
     * @return void
     */
    public function cleanImageDirectory(stdClass $product, array $import_images, array $context = [])
    {
        $basepath = $this->getPathToProductImages($product);
        $existing = glob($this->getImagePath($basepath) . '/*.{png,jpg,jpeg,gif,PNG,JPG,JPEG,GIF}', GLOB_BRACE);
        $map = [];
        foreach ($import_images as $image) {
            $map[basename($image)] = 1;
        }
        foreach ($existing as $file) {
            $filename = basename($file);
            $filename_with_path = $basepath . '/' . $filename;
            if (!isset($map[$filename]) && !in_array($filename_with_path, $this->images_to_remove)) {
                $this->images_to_remove[] =  $filename_with_path;
                $this->logger->notice("Removing obsolete image: {$filename_with_path}", $context);
            }
        }
    }

    /**
     * @param stdClass $product
     * @return string
     */
    public function getPathToProductImages(stdClass $product)
    {
        return "data/products/{$product->product_id}";
    }

    /**
     * @param string $product_name
     * @param int $image_index
     * @param string $extension
     * @param string $revision
     * @return void
     */
    protected function buildImageName(
        string $product_name,
        int $image_index,
        string $extension,
        string $revision = null
    ) {
        return Str::urlSafe($product_name)
            . '-' . $image_index
            . ($revision !== null ? '-' . $revision : '' )
            . '.' . $extension;
    }

    /**
     * @param string $image
     * @param array $context
     * @return void
     */
    protected function removeImage(string $image, array $context = [])
    {
        $filename = $this->getImagePath($image);
        if (file_exists($filename)) {
            if (unlink($filename)) {
                $this->logger->notice("Removed image: {$image}", $context);
            } else {
                $this->logger->warning("Failed to remove image: {$image}", $context);
            }
        } else {
            $this->logger->notice("Image didn't exist: {$image}", $context);
        }
    }

    /**
     * @param ImportProduct $import
     * @return array
     */
    public function getContext($import)
    {
        return [
            'product_id' => (int) $import->vendor_code
        ];
    }
}
