<?php

namespace Neos\classes\Engine;

class Templator
{
    protected $data;
    protected $template;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function setTemplate($template)
    {
        $this->template = $template;
    }

    public function render()
    {
        if (empty($this->data)) return $this->template;
        $data_keys = array_keys($this->data);
        $search = array();
        foreach ($data_keys as $key) {
            $search[] = "[{" . $key . "}]";
        }
        return str_replace($search, $this->data, $this->template);
    }
}