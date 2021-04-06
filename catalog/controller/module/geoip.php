<?php

class ControllerModuleGeoip extends Controller {

    /**
     * @var GeoIP
     */
    private $geoip;

    public function __construct($registry) {
        parent::__construct($registry);

        $this->geoip = $registry->get('geoip');
    }

    protected function index() {

        $this->saveInSession();

		$this->language->load('module/geoip');

        $this->data['text_zone'] = $this->language->get('text_zone');
        $this->data['text_search_zone'] = $this->language->get('text_search_zone');
        $this->data['text_search_placeholder'] = $this->language->get('text_search_placeholder');

        $city_name = $this->geoip->getShortCityName();
        $zone_name = $this->geoip->getZoneName();

        $parts = array();

        if ($city_name) {
            $parts[] = $city_name;
        }

        if ($zone_name && $zone_name != $city_name) {
            $parts[] = $zone_name;
        }

        $zone = implode(', ', $parts);

        $this->data['zone'] = $zone ? $zone : $this->language->get('text_unknown');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/geoip.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/geoip.tpl';
		} else {
			$this->template = 'default/template/module/geoip.tpl';
		}

		$this->response->setOutput($this->render());		
	}

    public function search() {

        $json = array();

        if (isset($this->request->get['term'])) {

            $json = $this->geoip->search($this->request->get['term']);
        }

        echo json_encode($json);
        die;
    }

    public function save() {

        $json = array();
        $fias_id = isset($this->request->get['fias_id']) ? $this->request->get['fias_id'] : 0;

        if ($fias_id && $this->geoip->save($fias_id)) {

            $this->forceSaveInSession();
            $this->geoip->setCurrency(true);
        }

        $this->response->setOutput(json_encode($json));
    }

    /**
     * Записывает адреса доставки и оплаты в сессию,
     * только если эти значения не были установлены ранее.
     * Не перезаписывает уже установленных значений.
     */
    private function saveInSession() {

        $session = $this->registry->get('session');
        $zone_id = $this->geoip->getZoneId();
        $country_id = $this->geoip->getCountryId();
        $city_name = $this->geoip->getCityName();
        $postcode = $this->geoip->getPostcode();

        if ($this->customer->isLogged()) {

            if ($zone_id) {

                if (!isset($session->data['shipping_zone_id'])) {
                    $session->data['shipping_zone_id'] = $zone_id;
                }

                if (!isset($session->data['payment_zone_id'])) {
                    $session->data['payment_zone_id'] = $zone_id;
                }
            }

            if ($country_id) {

                if (!isset($session->data['shipping_country_id'])) {
                    $session->data['shipping_country_id'] = $country_id;
                }

                if (!isset($session->data['payment_country_id'])) {
                    $session->data['payment_country_id'] = $country_id;
                }
            }

            if ($postcode) {

                if (!isset($session->data['shipping_postcode'])) {
                    $session->data['shipping_postcode'] = $postcode;
                }
            }
        }
        else {
            if ($zone_id) {

                if (!isset($session->data['guest']['shipping']['zone_id'])) {
                    $session->data['guest']['shipping']['zone_id'] = $zone_id;
                }

                if (!isset($session->data['guest']['payment']['zone_id'])) {
                    $session->data['guest']['payment']['zone_id'] = $zone_id;
                }
            }

            if ($country_id) {

                if (!isset($session->data['guest']['shipping']['country_id'])) {
                    $session->data['guest']['shipping']['country_id'] = $country_id;
                }

                if (!isset($session->data['guest']['payment']['country_id'])) {
                    $session->data['guest']['payment']['country_id'] = $country_id;
                }
            }

            if ($city_name) {

                if (!isset($session->data['guest']['shipping']['city'])) {
                    $session->data['guest']['shipping']['city'] = $city_name;
                }

                if (!isset($session->data['guest']['payment']['city'])) {
                    $session->data['guest']['payment']['city'] = $city_name;
                }
            }

            if ($postcode) {

                if (!isset($session->data['guest']['shipping']['postcode'])) {
                    $session->data['guest']['shipping']['postcode'] = $postcode;
                }

                if (!isset($session->data['guest']['payment']['postcode'])) {
                    $session->data['guest']['payment']['postcode'] = $postcode;
                }
            }
        }
    }

    /**
     * Записывает адреса доставки и оплаты в сессию.
     * Используется, когда пользователь меняет регион вручную.
     */
    private function forceSaveInSession() {

        $session = $this->registry->get('session');
        $zone_id = $this->geoip->getZoneId();
        $country_id = $this->geoip->getCountryId();
        $city_name = $this->geoip->getCityName();
        $postcode = $this->geoip->getPostcode();

        if ($this->customer->isLogged()) {

            $session->data['shipping_zone_id'] = $session->data['payment_zone_id'] = $zone_id;
            $session->data['shipping_country_id'] = $session->data['payment_country_id'] = $country_id;
            $session->data['shipping_postcode'] = $postcode;
        }
        else {
            $session->data['guest']['shipping']['zone_id'] = $session->data['guest']['payment']['zone_id'] = $zone_id;
            $session->data['guest']['shipping']['country_id'] = $session->data['guest']['payment']['country_id'] = $country_id;
            $session->data['guest']['shipping']['city'] = $session->data['guest']['payment']['city'] = $city_name;
            $session->data['guest']['shipping']['postcode'] = $session->data['guest']['payment']['postcode'] = $postcode;
        }
    }
}