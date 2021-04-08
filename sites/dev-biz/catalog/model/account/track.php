<?php
class ModelAccountTrack extends Model {
    private $cdek_url = 'https://integration.cdek.ru/';

	public function makeRequest ($method, $xml)
	{
	    $url = $this->cdek_url . $method;
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, "xml_request=" . $xml);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		$response = curl_exec($ch);

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpcode != 200) {
            $error_msg =  "HTTP request failed with status code " . $httpcode;
            error_log($error_msg);

            return $error_msg;
        }

		$data = simplexml_load_string($response);

		curl_close($ch);

		return $data;
	}


	// Получаем данные для авторизации
	public function getAuth () 
	{
		return array(
		    'account' => $this->config->get('cdek_auth_login'),
            'date' => date('Y-m-d'),
            'secure' => md5(date('Y-m-d') . '&' . $this->config->get('cdek_auth_password'))
        );
	}


	// Создаем текст XML-запроса, вызываем функцию отправки запроса, обрабатываем ответ, возвращаем массив 
	public function getTrackWithCityAndStatus ($trackNumber) 
	{
	    $data = $this->getAuth();

	    $account = $data['account'];
	    $date = $data['date'];
	    $secure = $data['secure'];


		$xml 	= '';

		$xml.='<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
		$xml.='<StatusReport Date="'.$date.'" Account="'.$account.'" Secure="'.$secure.'" ShowHistory="1" ShowReturnOrder="0" ShowReturnOrderHistory="0">'.PHP_EOL;	
		$xml.='  <Order DispatchNumber="'.$trackNumber.'" />'.PHP_EOL;
		$xml.='</StatusReport>';

		$method = 'status_report_h.php';

		$data = $this->makeRequest($method, $xml);

		if (is_string($data)) {
		    return $data;
        }

		foreach ($data->Order->Status->State as $item) {
		    $res = json_decode(json_encode($item), true);
		    foreach ($res as $item) {
		        $result[] = $item;
		    }
		}
		return $result;
	}
}

