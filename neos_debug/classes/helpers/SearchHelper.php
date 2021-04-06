<?php

namespace Neos\classes\helpers;

class SearchHelper
{
    public function prepareCityname($q) {
        $words = explode(" ", trim($q));
        foreach ($words as $key => $word) {
            if (false === $this->checkLength($word)) {
                unset($words[$key]);
                continue;
            }
            if (false === $this->excludeAddressList($word, true)) {
                unset($words[$key]);
                continue;
            }
        }
        if (!count($words)) return $q;
        return implode(" ", $words);
    }
    public function prepareAddress($q, $type = 1)
    {
        $words = explode(" ", trim($q));
        foreach ($words as $key => $word) {
            if (false === $this->checkLength($word)) {
                unset($words[$key]);
                continue;
            }
            if (false === $this->excludeAddressList($word)) {
                unset($words[$key]);
                continue;
            }

        }
        $count = count($words);
        if (!$count) return false;
        switch ($type) {
            default:
            case 1:
                $search_field = ($count > 1) ? '`np_name`' : '`name`';
                $clause = ($count > 1) ? ' AND ' : ' OR ';
                $symbol = ($count > 1) ? '%' :  '%';
                $sql = array();
                foreach ($words as $word) {
                    $sql[] = "$search_field LIKE '$symbol{$word}$symbol'";
                }
                return implode($clause, $sql);
            break;
            case 2:
                return implode(', ', $words);
            break;
        }
    }   
    public function checkLength($word)
    {
        $word = trim($word, '.,?!/\\:-_');
        if (function_exists('mb_strlen')) {
            if (\mb_strlen($word, "UTF-8") < 3) {
                return false;
            }
        } else {
            if (\strlen($word) < 3) {
                return false;
            } 
        }
        return true;
    }
    public function excludeAddressList($word, $extend = false)
    {
        $list = array(
            "дер",
            "село",
            "пос",
            "поселок",
            "посёлок",
            "хутор",
            "улица",
            "дом",
            "р-он",
            "обл",
            "район",
            "область",
            "край",
            "город",
            "гор",
            "г",
            "снт",
            "пгт"
        );
        if ($extend) {
            $extended_list = array(
                "сельское",
                "поселение",
                "городское",
                "типа",
                "городского",
                "городок",
                "слобода",
                "деревня"
            );
            $list = array_merge($list, $extended_list);
        }
        $entries = implode('|', $list);
        if (preg_match("/^({$entries})[.,?!\/\\:-_]{0,3}$/ui", $word)) {
            return false;
        }
        
    }
}