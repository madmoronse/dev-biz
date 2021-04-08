<?php
class ModelAccountSubscriber extends Model {

    public function isCustomerSubsribed() 
    {	
        if (!$this->customer->isLogged()) return false;
        $result = $this->db->query(
            "SELECT 1 FROM " . DB_PREFIX . "email_subscribers WHERE `email` = '" . $this->db->escape($this->customer->getEmail()) . "'"
        );
        if ($result->num_rows != 0) {
            return true;
        }
        return false;
    }
    public function cookiePopupShown()
    {
        setcookie('subscribe_popup_shown', 1, time()+60*60*24*10, '/');
    }
    
    public function shouldShowPopup($ip)
    {
        $query = $this->db->query(
            "SELECT 1 FROM " . DB_PREFIX . "email_subscribers WHERE ip = '" . $this->db->escape($ip) . "' LIMIT 1"
        );

        if ($query->num_rows > 0) {
            return false;
        }
        return true;
    }
    public function addNewSubscriber($email, $ip, $answer = 'json')
    {
        // Валидируем почту
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
           if ($answer == 'json') {
               return array('error' => 'Неправильно указан адрес электронной почты!');
           } else {
               return false;
           }
        }

        $query = $this->db->query(
            "SELECT 1 FROM " . DB_PREFIX . "email_subscribers WHERE email = '" . $this->db->escape($email) . "' LIMIT 1"
        );

        if ($query->num_rows > 0) {
            if ($answer == 'json') {
                return array('warning' => 'Указанный email уже зарегистрирован!');
            } else {
                return false;
            }
        }

        $this->db->query(
            "INSERT INTO " . DB_PREFIX . "email_subscribers 
            SET `email` = '" . $this->db->escape($email) . "', `ip` = '" . $this->db->escape($ip) . "', 
            `datetime` = NOW()"
        );
        if ($this->db->countAffected()) {
            if ($answer == 'json') {
                return array('success' => 'Подписка оформлена!');
            } else {
                return true;
            }
        } else {
            if ($answer == 'json') {
                return array('error' => 'Произошла ошибка, попробуйте позже!');
            } else {
                return false;
            }
        }
    }
    public function DEPRECATED_addNewSubscriber($email, $ip, $answer = 'json')
    {
        $file = DIR_UPLOADS."subscribers.csv";
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $list = array (
            array($email, $ip, date("d:m:Y H:i:s")."(UTC+0)"),
        );

        $fp = fopen($file, 'a');

        $row = 1;
        $emailis = false;
        if (($handle = fopen($file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
                $num = count($data);
                for ($c=0; $c < $num; $c++) {
                    if($data[$c] == $_REQUEST['email']){
                        $emailis = true;
                        break;
                    }
                }
            }
            fclose($handle);
        }
        if($emailis == false){
            foreach ($list as $fields) {
                fputcsv($fp, $fields);
            }
            fclose($fp);
            $json['success'] = "Подписка оформлена!";
        }else{
            $json['error'] = "Указанный email уже зарегистрирован!";
        }
        }else{
        $json['error'] = "Неправильно указан адрес электронной почты!";
        }
        if($answer == 'json'){
        return $json;
        }elseif($answer == 'bool'){
        if(isset($json['success'])){
            return true;
        }else{
            return false;
        }
        }else{
        return $json;
        }
    }

  public function updateCustomersInSubscription($customers)
  {
    $file = DIR_UPLOADS."subscribers.csv";
    $cust = array();
    foreach ($customers->rows as $key => $value) {
      array_push($cust, array($value['email'],$value['ip'],$value['date_added']));
    }
    $fp = fopen($file, 'a');
    $json['added'] = 0;
    $json['missed'] = 0;
    $newCustomers = array();
    $subscribers = array();
    $fr = fopen($file, "r");
    while (($data = fgetcsv($fr, 0, ",")) !== FALSE) {
        $num = count($data);
        array_push($subscribers,$data);
    }
    fclose($fr);
    foreach ($cust as $fields) {
      $emailis = false;
      foreach ($subscribers as $subscriber) {
        if($subscriber != null){
          if($fields[0] == $subscriber[0]){
            $emailis = true;
            break;
          }
        }
      }
      if($emailis == false){
        array_push($newCustomers, $fields);
        $json['added']++;
      }else{
        $json['missed']++;
      }
    }
    foreach ($newCustomers as $key => $value) {
      fputcsv($fp, $value);
    }
    fclose($fp);
    return $json;
  }

}
?>
