<?php
    class GeoIP_Driver_IPGeoBase extends GeoIP_Driver {

        protected function initGeoFilter() {

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'http://ipgeobase.ru:7020/geo?ip=' . $this->ip);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_TIMEOUT, 3);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 3);
            $content = curl_exec($curl);

            if ($content) {

                $content = iconv('windows-1251', 'utf-8', $content);

                $patterns = array(
                    'iso_code_2' => '#<country>(.*)</country>#is',
                    'zone_name'  => '#<region>(.*)</region>#is',
                    'city_name'  => '#<city>(.*)</city>#is',
                );

                foreach ($patterns as $key => $pattern) {

                    preg_match($pattern, $content, $out);

                    if (isset($out[1]) && $out[1]) {
                        $this->geo_filter[$key] = $this->translate(trim($out[1]));
                    }
                }
            }
        }

        protected function translate($name) {

            $dict = array(
                'Крым'                                => 'Крым АР',
                'Республика Чечня'                    => 'Чеченская Республика',
                'Республика Удмуртия'                 => 'Удмуртская Республика',
                'Республика Северная Осетия (Алания)' => 'Республика Северная Осетия',
                'Еврейская автономная область'        => 'Еврейская АО',
                'Республика Саха (Якутия)'            => 'Республика Саха',
                'Чукотский автономный округ'          => 'Чукотский АО',
                'Республика Чувашия'                  => 'Чувашская Республика',
                'Ханты-Мансийский автономный округ'   => 'Ханты-Мансийский АО - Югра',
                'Ямало-Ненецкий автономный округ'     => 'Ямало-Ненецкий АО',
                'Республика Тыва (Тува)'              => 'Республика Тыва',
                'Ненецкий автономный округ'           => 'Ненецкий АО',
                'Республика Карачаево-Черкессия'      => 'Карачаево-Черкеcсия');

            return isset($dict[$name]) ? $dict[$name] : $name;
        }
    }