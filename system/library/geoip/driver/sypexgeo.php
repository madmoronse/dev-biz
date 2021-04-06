<?php
    require_once 'SxGeo.php';

    class GeoIP_Driver_SypexGeo extends GeoIP_Driver {

        protected function initGeoFilter() {

            $sxGeoCity = new SxGeo(DIR_SYSTEM . 'library/geoip/driver/SxGeoCity.dat');
            $dataCity = $sxGeoCity->getCityFull($this->ip);

            $sxGeoIGB = new SxGeo(DIR_SYSTEM . 'library/geoip/driver/SxGeo_IGB.dat');
            $dataIGB = $sxGeoIGB->getCityFull($this->ip);

            $isoCode = empty($dataCity['country']) ? $dataCity['country'] : $dataIGB['country'];

            $this->geo_filter = array('city_name'  => empty($dataCity['city']) ? $dataCity['city'] : iconv('cp1251', 'utf-8', $dataIGB['city']),
                                      'zone_name'  => empty($dataCity['region_name']) ? $dataCity['region_name'] : iconv('cp1251', 'utf-8', $dataIGB['region_name']),
                                      'iso_code_2' => $isoCode, 'country_name' => $this->getCountryNameByIso($isoCode));
        }

        private function getCountryNameByIso($iso) {

            $codes = array('RU' => 'Россия', 'UA' => 'Украина', 'BY' => 'Белоруссия');

            return isset($codes[$iso]) ? $codes[$iso] : '';
        }
    }