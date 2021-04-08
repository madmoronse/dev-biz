<?php
class ModelToolPinger extends Model {

	public function getlink($data,$class) {
$router='';
$param='';
$output='';

if($class=='ModelCatalogProduct') {
	$router='product/product';
	$param='product_id='.$data['product_id'];	
}
if($class=='ModelCatalogCategory') {
	$router='product/category';
	$param='category_id='.$data['category_id'];
	}
if($class=='ModelCatalogInformation') {
	$router='information/information';
	$param='information_id='.$data['information_id'];	
}
if($class=='ModelCatalogManufacturer') {
	$router='product/manufacturer/info';
	$param='manufacturer_id=' . $data['manufacturer_id'];
}




	if(!empty($router)&&!empty($param)) {
		
		$url = new Url(HTTP_CATALOG, $this->config->get('config_secure') ? HTTPS_CATALOG : HTTP_CATALOG);		
		$rewrite=$url->link($router, $param);
			if (!$seo_type = $this->config->get('config_seo_url_type')) {
				$seo_type = 'seo_url';
			}
		$action=new Action('common/'.$seo_type.'/rewrite',$rewrite);
		
				if (file_exists($action->getFile())) {
				require_once($action->getFile());
				$class = $action->getClass();
				$controller = new $class($this->registry);
				$output=$controller->{$action->getMethod()}($action->getArgs());		
				}
		
			if($seo_type=='seo_url') {
			$trueurl=$output;
				}else {
			$trueurl=HTTP_CATALOG.$output;
			}
		$this->pings($trueurl);
	}




}



	public function pings($url) {	

	$config=$this->config->get('yandexpds_config');	
	$key=(!empty($config['key']))?$config['key']:'';
	$yalogin=(!empty($config['login']))?$config['login']:'';
	$searchId=(!empty($config['searchid']))?$config['searchid']:'';
	$message='';

		
			$postdata = http_build_query(array(
				'key' => urlencode($key),
				'login' => urlencode($yalogin),
				'search_id' => urlencode($searchId),
				'urls' => $url
				));

		$host = 'site.yandex.ru';
		$length = strlen($postdata);		   
		
		
		$out = "POST /ping.xml HTTP/1.0\r\n";
		$out.= "HOST: ".$host."\r\n";
		$out.= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out.= "Content-Length: ".$length."\r\n\r\n";
		$out.= $postdata."\r\n\r\n";
		try{
		
			$errno='';
			$errstr = '';
			$result = '';
			$socket = @fsockopen($host, 443, $errno, $errstr, 30);
			if($socket){
				if(!fwrite($socket, $out)){
					throw new Exception("unable to write");
				} else {
					while ($in = @fgets ($socket, 1024)){
	            		$result.=$in;
	           		} 
				}
				
			} else {
				throw new Exception("unable to create socket");
			}
			fclose($socket);
			
			$result_xml = array();		
			preg_match('/(<.*>)/u', $result, $result_xml);
			if(count($result_xml) && function_exists('simplexml_load_string')) {
				$result = array_pop($result_xml);
				$xml = simplexml_load_string($result);
				
				if(isset( $xml -> error ) && isset( $xml -> error -> code)) {
					if($xml -> error -> code){
						$errorcode = (string)$xml -> error -> code;

						if (($errorcode=="ILLEGAL_VALUE_TYPE")||($errorcode=="SEARCH_NOT_OWNED_BY_USER")||($errorcode=="NO_SUCH_USER_IN_PASSPORT"))
							$message = "Один или несколько параметров в настройках плагина указаны неверно - ключ (key), логин (login) или ID поиска (searchid).";
						elseif ($errorcode == "TOO_DELAYED_PUBLISH")
							$message = "Максимальный срок отложенной публикации - 6 месяцев";
						elseif ($errorcode=="USER_NOT_PERMITTED")
						{
							$errorparam = (string)$xml -> error -> param;
							$errorvalue = (string)$xml -> error -> value;
							if ($errorparam=="key")
								$message = "Неверный ключ (key) ".$errorvalue.". Проверьте настройки плагина.";
							elseif ($errorparam=="ip")
								$message = "Запрос приходит с IP адреса ".$errorvalue.", который не указан в списке адресов в настройках вашего поиска";
							else
								$message = "Запрос приходит с IP адреса, который не указан в списке адресов в настройках вашего поиска, либо Вы указали неправильный ключ (key) в настройках плагина.";

						}
						else $message=$errorcode;
					}
				}
				elseif(isset($xml -> invalid)) {
					$invalidurl = $xml->invalid->url;
					$errorcode = $xml->invalid["reason"];
					if ($errorcode=="NOT_CONFIRMED_IN_WMC")
						$message = "Сайт не подтвержден в сервисе Яндекс.Вебмастер для указанного имени пользователя.";

					elseif ($errorcode=="OUT_OF_SEARCH_AREA")
						$message = "Адрес ".$invalidurl." не принадлежит области поиска вашей поисковой площадки.";

					elseif ($errorcode=="MALFORMED_URLS")
						$message = "Невозможно принять некорректный адрес: ".$invalidurl;
					
					else $message=$errorcode;
					
					} elseif( isset($xml -> added) 
					&& isset($xml -> added['count']) 
					&& $xml -> added['count'] >0) {
						$addedaddress = $xml->added->url;
						$message = "Плагин работает корректно. Последний принятый адрес: ".$addedaddress;
				}
				
				if(isset($message) && $message) {
					$this->cache->set('yandexpds_log' ,$message);	
				}
				return true;
			}
		} catch(exception $e) {
			return false;
		}	
		
	}


}