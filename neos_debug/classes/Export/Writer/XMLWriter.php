<?php

namespace Neos\classes\Export\Writer;

use Neos\classes\Export\Writer\XML\Children;
use Neos\classes\Export\Writer\XML\Entity;
use Psr\Log\LoggerInterface;
use XMLWriter as DomXMLWriter;

class XMLWriter implements Writer
{
    /**
     * @var DomXMLWriter
     */
    protected $handle;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->handle = new DomXMLWriter();
        $this->logger = $logger;
    }

    public function __destruct()
    {
        unset($this->handle);
    }

    public function open($target)
    {
        if ($this->handle->openUri($target)) {
            $this->handle->setIndent(true);
            $this->handle->startDocument("1.0");
            return true;
        }
        return false;
    }

    public function close()
    {
        $this->handle->endDocument();
        return $this->handle->flush();
    }

    /**
     * @param Entity $entity
     * @return void
     */
    public function write($entity)
    {
        $this->handle->startElement($entity->getName());
        foreach ($entity->getAttributes() as $name => $value) {
            $this->handle->writeAttribute($name, $value);
        }
        foreach ($entity->getTags() as $name => $value) {
            if ($value instanceof Children) {
                $this->writeChildren($value);
            } elseif ($value instanceof Entity) {
                $this->write($value);
            } elseif (is_scalar($value)) {
                if (strpos($name, 'cdata:') === 0) {
                    $tag_name = substr($name, strlen('cdata:'));
                    $this->handle->startElement($tag_name);
                    $this->handle->writeCdata($value);
                    $this->handle->endElement();
                } else {
                    $this->handle->writeElement($name, $value);
                }
            } elseif (is_array($value)) {
                foreach ($value as $subvalue) {
                    if (is_scalar($subvalue)) {
                        $this->handle->writeElement($name, $subvalue);
                    } elseif ($subvalue instanceof Entity) {
                        $this->write($subvalue);
                    } else {
                        $this->logger->alert('Unexpected subvalue type: ' . gettype($value) . ', name: ' . $name);
                    }
                }
            } else {
                $this->logger->alert('Unexpected value type: ' . gettype($value) . ', name: ' . $name);
            }
        }
        $this->handle->endElement();
    }

    /**
     * @return \XMLWriter
     */
    public function getHandle()
    {
        return $this->handle;
    }

    protected function writeChildren(Children $children)
    {
        $this->handle->startElement($children->getName());
        foreach ($children->getChildren() as $child) {
            $this->write($child);
        }
        $this->handle->endElement();
    }
}
