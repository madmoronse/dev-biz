<?php
class ControllerCommonUtm extends Controller {
	public function index() {
        $utm = array();
        foreach ($this->request->get as $key => $param) {
            if (strpos($key, 'utm') === 0) {
                $utm[$key] = $param;
            }
        }
        if (count($utm)) {
            $this->session->data['amo_utm'] = $utm;
        }
    }
}
