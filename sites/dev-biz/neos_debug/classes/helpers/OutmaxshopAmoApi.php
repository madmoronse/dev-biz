<?php

namespace Neos\classes\helpers;

class OutmaxshopAmoApi
{
    // Подготавливаем данные и делаем запрос к АПИ на outmaxshop.ru
    public function lead($data, $utm = array())
    {
        $data_new['name'] = $data['firstname'];
        $data_new['phone'] = $data['telephone'];
        $data_new['email'] = $data['email'];
        $data_new['city'] = $data['city'];
        $data_new['tag'] = $data['type'];
        $data_new['registration'] = 'registration';
        // set POST variables
        $url 		= 'https://outmaxshop.ru/business-lead';
        $fields 	= $data_new;

        // set UTM
        if (is_array($utm) && count($utm)) {
            $fields['utm'] = http_build_query($utm, '', '&');
            if (isset($utm['utm_amotag'])) {
                $fields['sp_tag'] = $utm['utm_amotag'];
            }
        }

        // url-ify the data for the POST
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        $fields_string = rtrim($fields_string, '&');

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_REFERER, 'http://bizoutmax.ru');

        $result = curl_exec($ch);
        curl_close($ch);
    }
    
}