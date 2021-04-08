<?php
Class Registry {
    private $vars = array();
     
    // запись данных
     function set($key, $var) {
        $this->vars[$key] = $var;
    }
 
    // получение данных
    function get($key) {
        if (isset($this->vars[$key]) == false) {
            return null;
        }
        return $this->vars[$key];
    }
 
    // удаление данных
    function remove($var) {
        unset($this->vars[$key]);
    }
}