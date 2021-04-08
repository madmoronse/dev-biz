<?php

namespace Neos\classes\Export\Writer\XML;

class Entity extends Base
{
    /**
     * @var array
     */
    protected $tags = [];

    /**
     * @var array
     */
    protected $attributes = [];


    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function addAttribute(string $key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function addTag(string $key, $value)
    {
        $this->tags[$key] = $value;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }
}
