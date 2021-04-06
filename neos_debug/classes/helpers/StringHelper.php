<?php

namespace Neos\classes\helpers;

class StringHelper
{
    /**
     * @param string $str1
     * @param string $str2
     * @return boolean
     */
    public static function equal($str1, $str2)
    {
        return strcmp(mb_strtoupper($str1), mb_strtoupper($str2)) === 0;
    }

    /**
     * @param string $str
     * @return void
     */
    public static function ucfirst($str)
    {
        return mb_strtoupper(mb_substr($str, 0, 1)) . mb_substr($str, 1);
    }
}
