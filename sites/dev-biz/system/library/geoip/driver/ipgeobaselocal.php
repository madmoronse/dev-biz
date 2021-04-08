<?php
    class GeoIP_Driver_IPGeoBaseLocal extends GeoIP_Driver_IPGeoBase {

        protected function initGeoFilter() {

            if (!$this->ip) {
                return;
            }

            $db = $this->registry->get('db');

            $result = $db->query('SELECT country.iso_code iso_code_2, zone.name zone_name, city.name city_name
                                                FROM geobase_base base
                                                LEFT JOIN geobase_city city using (geobase_city_id)
                                                LEFT JOIN geobase_country country using (geobase_country_id)
                                                LEFT JOIN geobase_zone zone using (geobase_zone_id)
                                                 WHERE INET_ATON("' . $db->escape($this->ip) . '")
                                                    BETWEEN base.long_ip_begin AND base.long_ip_end
                                                  LIMIT 1');

            foreach ($result->row as $key => $value) {
                $this->geo_filter[$key] = $this->translate($value);
            }
        }
    }
