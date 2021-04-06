<?php
class ControllerAccountTrack extends Controller {
    private $error = array();

    public function track() {
        $this->language->load('account/track');
        $json = array();
                
        $track = $this->request->post['track'];
        if (empty($track)) {
            $this->response->setOutput(json_encode(array('error' => $this->language->get('error_empty'))));
            return;
        }

        $this->load->model('account/track');
        $order_track = $this->model_account_track->getTrackWithCityAndStatus($track);

        if (!is_array($order_track)) {
            $this->response->setOutput(json_encode(array('error' => $this->language->get('error_server'))));
            return;
        }

        if ($order_track) {
            $json['success'] = $order_track;
        }
        $this->response->setOutput(json_encode($json));
    } 
}