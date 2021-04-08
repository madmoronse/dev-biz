<?php

namespace Neos\classes\Export\Writer;

interface Writer
{
    /**
     * write writes entity to destination
     * @param mixed $entity
     * @return void
     */
    public function write($entity);

    /**
     * open starts writing
     * @param mixed $target
     * @return void
     */
    public function open($target);

    /**
     * close stops writing
     * @return void
     */
    public function close();

    /**
     * getHandle returns resource which is used to write data
     * @return mixed
     */
    public function getHandle();
}
