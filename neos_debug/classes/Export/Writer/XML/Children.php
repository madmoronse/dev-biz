<?php

namespace Neos\classes\Export\Writer\XML;

class Children extends Base
{
    /**
     * @var Entity[]
     */
    protected $children = [];

    /**
     * @param Entity $entity
     * @return void
     */
    public function addChild(Entity $entity)
    {
        $this->children[] = $entity;
    }

    /**
     * @return Entity[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return boolean
     */
    public function hasChildren()
    {
        return count($this->children) > 0;
    }
}
