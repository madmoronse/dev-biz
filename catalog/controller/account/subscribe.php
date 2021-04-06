<?php
class ControllerAccountSubscribe extends Controller {
	private $error = array();

  	public function index() {
      $this->load->model('account/subscriber');
      $json = $this->model_account_subscriber->addNewSubscriber($_REQUEST['email'], $_SERVER['REMOTE_ADDR']);
      $this->response->setOutput(json_encode($json));
  	}
    public function addallusers()
    {
      exit('Deprecated method');
      $this->load->model('account/subscriber');
      $customers = $this->db->query("SELECT * FROM `oc_customer`");
      $json = $this->model_account_subscriber->updateCustomersInSubscription($customers);

      $this->response->setOutput(json_encode($json));
    }
    public function shouldShowPopup()
    {
      $this->load->model('account/subscriber');
      $result = $this->model_account_subscriber->shouldShowPopup($_SERVER['REMOTE_ADDR']);
      $this->response->setOutput(json_encode(array('shouldShow' => $result)));
    }
}
?>
