<?php

class ModelExportYandexMarketWriter extends Model {
    /**
     * @var object
     */
    protected $writer;

    /**
     * @var string
     */
    protected $file;

    /**
     * @var string
     */
    protected $scheme = 'yml';

    public function __construct($registry) 
    {
        parent::__construct($registry);
        
        $this->writer = new \XmlWriter();
        $this->writer->openMemory();
        $this->writer->setIndent(true);
    }

    public function setFile($file) 
    {
        $this->file = $file;
    }

    public function setScheme($scheme)
    {
        $this->scheme = $scheme;
    }

    public function applyScheme()
    {
        switch ($this->scheme) {
            case 'freemoda':
                $this->writer->setIndentString('');
                break;
        }
    }

    public function checkFile()
    {
        if (empty($this->file)) {
            throw new \LogicException('Please setup export file'); 
        }
        if (file_exists($this->file)) {
            $file = new stdClass;
            $file->mtime = filemtime($this->file);
            $file->is_file = is_file($this->file);
            return $file;
        }
        return false;
    }

    /**
     * Начало документа
     */
    public function addHeader()
    {
        $this->writer->startDocument('1.0', 'UTF-8');
        if ($this->scheme === 'yml') {
            $this->writer->startDtd('yml_catalog', null, 'shops.dtd');
            $this->writer->endDtd();
        } elseif ($this->scheme === 'freemoda') {
            $this->writer->writeRaw("<!DOCTYPE yml_catalog SYSTEM \"shops.dtd\">\n");
        }
        $this->writer->startElement('yml_catalog');
        $this->writer->writeAttribute('date', date('Y-m-d H:i'));
        $this->writer->startElement('shop');
    }

    /**
     * Конец документа
     */
    public function addFooter()
    {
        $this->writer->fullEndElement();
        $this->writer->fullEndElement();
        $this->writer->endDocument();
    }

    /**
     * Добавляем инфу о магазине
     */
    public function addShopInfo($shopInfo)
    {
        foreach ($shopInfo as $name => $value) {
            $this->writer->writeElement($name, $value);
        }
    }
    /**
     * Добавляем валюты
     */
    public function addCurrencies($currencies)
    {
        $this->writer->startElement('currencies');
        foreach ($currencies as $currency) {
            $this->writer->startElement('currency');
            $this->writer->writeAttribute('id', $currency['id']);
            $this->writer->writeAttribute('rate', $currency['rate']);
            $this->writer->endElement();
        }
        $this->writer->fullEndElement();
    }
    /**
     * Добавляем категории
     */
    public function addCategories($categories)
    {
        $this->writer->startElement('categories');
        foreach ($categories as $category) {
            $this->writer->startElement('category');
            $this->writer->writeAttribute('id', $category['id']);
            if (isset($category['parentId'])) {
                $this->writer->writeAttribute('parentId', $category['parentId']);
            }
            $this->writer->text($category['name']);
            $this->writer->fullEndElement();
        }
        $this->writer->fullEndElement();
    }
    /**
     * Начинаем раздел предложений
     */
    public function startOffers()
    {
        $this->writer->startElement('offers');
    }
    /**
     * Заканчиваем раздел предложений
     */
    public function endOffers()
    {
        $this->writer->fullEndElement();
    }
    /**
     * Добавляем оффер
     */
    public function addOffer($offer)
    {
        switch ($this->scheme) {
            default:
            case 'yml':
                $this->addOfferYml($offer);
                break;
            case 'freemoda':
                $this->addOfferFreemoda($offer);
                break;
        }
    }

    /**
     * @param array $offer
     * @return void
     */
    protected function addOfferYml($offer)
    {
        $this->writer->startElement('offer');
        $this->addOfferAttributes($offer);
        $this->addOfferData($offer);
        $this->addOfferParams($offer);
        $this->writer->fullEndElement();
    }

    /**
     * @param array $offer
     * @return void
     */
    protected function addOfferFreemoda($offer)
    {
        $this->writer->startElement('offer');
        // Prepare offer data
        $data = $offer['data'];
        $options = isset($data['options']) ? $data['options'] : null;
        unset($data['options']);
        $key = 'name';
        $offset = array_search($key, array_keys($data));
        $offer['data'] = array_merge(
            array_slice($data, 0, $offset),
            array('quantity' => $data['quantity']),
            array_slice($data, $offset, null)
        );
        // Write offer body
        $this->addOfferAttributes($offer);
        $this->addOfferData($offer);
        $this->addOfferParams($offer);
        if ($options !== null) {
            $this->writer->writeElement('options', $options);
        }
        $this->writer->fullEndElement();
    }

    protected function addOfferAttributes($offer)
    {
        if (isset($offer['group_id'])) $this->writer->writeAttribute('group_id', $offer['group_id']);
        if (isset($offer['id'])) $this->writer->writeAttribute('id', $offer['id']);
        if (isset($offer['bid'])) $this->writer->writeAttribute('bid', $offer['bid']);
        if (isset($offer['cbid'])) $this->writer->writeAttribute('cbid', $offer['cbid']);
        if (isset($offer['fee'])) $this->writer->writeAttribute('fee', $offer['fee']);
        if (isset($offer['available'])) $this->writer->writeAttribute('available', $offer['available']);
        if (isset($offer['type'])) $this->writer->writeAttribute('type', $offer['type']);
    }


    protected function addOfferData($offer)
    {
        foreach ($offer['data'] as $name => $value) {
            if (!is_array($value)) {
                switch ($name) {
                    default:
                        $this->writer->writeElement($name, $value);
                        break;
                    case 'description':
                        $this->writer->startElement($name);
                        $this->writer->writeCData($value);
                        $this->writer->endElement();
                        break;
                }
            } elseif ($name !== 'outlets') {
                foreach ($value as $value2) {
                    $this->writer->writeElement($name, $value2);
                }
            } elseif ($name == 'outlets' && $this->config->get('yandex_market_show_outlets')) {
                $this->writer->startElement('outlets');
                foreach ($value as $outlet) {
                    $this->writer->startElement('outlet');
                    $this->writer->writeAttribute('id', $outlet['id']);
                    $this->writer->writeAttribute('instock', $outlet['instock']);
                    $this->writer->endElement();
                }
                $this->writer->endElement();
            }
        }
    }

    protected function addOfferParams($offer)
    {
        
        if (isset($offer['param'])) {
            foreach ($offer['param'] as $param) {
                $this->addParam($param);
            }
        }
    }

    /**
     * @param array $param
     */
    public function addParam($param) 
    {
        $this->writer->startElement('param');
        $this->writer->writeAttribute('name', $param['name']);
        if (isset($param['unit'])) {
            $this->writer->writeAttribute('unit', $param['unit']);
        }
        $this->writer->text($param['value']);
        $this->writer->endElement();
    }

    /**
     * Flush XML memory to file
     *
     * @param void
     * @return void
     */
    public function flushToFile()
    {
        file_put_contents($this->file, $this->writer->outputMemory(true), FILE_APPEND);
    }
}