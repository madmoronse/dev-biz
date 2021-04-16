<?php



class Model_Vk {

    private $access_token;
    private $url = "https://api.vk.com/method/";

    /**
     * Конструктор
     */
    public function __construct($access_token) {

        $this->access_token = $access_token;
    }

    /**
     * Делает запрос к Api VK
     * @param $method
     * @param $params
     */
    public function method($method, $params = null) {

        $p = "";
        if( $params && is_array($params) ) {
            foreach($params as $key => $param) {
                $p .= ($p == "" ? "" : "&") . $key . "=" . urlencode($param);
            }
        }
        $response = file_get_contents($this->url . $method . "?" . ($p ? $p . "&" : "") . "access_token=" . $this->access_token);

        if( $response ) {
            return json_decode($response);
        }
        return false;
    }




	public function uploadImage($file, $group_id = null, $album_id = null, $descr = null) {
	
		$params = array();
		if( $group_id ) {
		  $params['group_id'] = $group_id;
		}
		if( $album_id ) {
		  $params['album_id'] = $album_id;
		}

		$params['v'] = '3.0';
		
		//Получаем сервер для загрузки изображения
		$response = $this->method("photos.getUploadServer", $params);
		
		
		if( isset($response) == false ) {
		  print_r($response);
		  exit;
		}

		if ($response->error) {
			echo $response->error->error_code . ":";
		  	echo $response->error->error_msg . "<br/>";
			return false;
		}

		$server = $response->response->upload_url;//вот тут вернул урл, по которому можно загружать
		
		$postparam=array("file1"=>"@".$file);
		//Отправляем файл на сервер
		$ch = curl_init($server);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$postparam);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data; charset=UTF-8'));
		$json = json_decode(curl_exec($ch));
		curl_close($ch);
		
		
		
		
		
		//Сохраняем файл в альбом
		$photo = $this->method("photos.save", array(
		  "server" => $json->server,
		  "photos_list" => $json->photos_list,
		  "album_id" => $album_id,
		  "hash" => $json->hash,
		  'gid' => $group_id,
		  'caption' => $descr,
		  'v' => '3.0',
		));
		
		
		if( isset($photo->response[0]->id) ) {
		  return $photo->response[0]->id;
		} else {
		  return false;
		}
		
	
	}



	public function uploadImageToWall($file, $group_id, $descr) {
	
		$params = array();
		if( $group_id ) {
		  $params['group_id'] = $group_id;
		}


		
		//Получаем сервер для загрузки изображения photos.getWallUploadServer
		$response = $this->method("photos.getWallUploadServer", $params);
		
		
		if( isset($response) == false ) {
		  print_r($response);
		  exit;
		}



		$server = $response->response->upload_url;//вот тут вернул урл, по которому можно загружать
		
		$postparam=array("file1"=>"@".$file);
		//Отправляем файл на сервер
		$ch = curl_init($server);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$postparam);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data; charset=UTF-8'));
		$json = json_decode(curl_exec($ch));
		curl_close($ch);
		
		
		
		
		
		//Сохраняем файл на стену
		$photo = $this->method("photos.saveWallPhoto", array(

			  "server" => $json->server,
			  "photo" => $json->photo,
			  "hash" => $json->hash,
			  'gid' => $group_id,
			  'caption' => $descr

		));
		
		
		if( isset($photo->response[0]->id) ) {

			  return $photo->response[0]->id;

		} else {

			  return false;

		}

	
	}


	public function postImgToWall($files, $group_id, $descr) {

		$params = array();

		if( $group_id ) {
		  $params['owner_id'] = '-' . $group_id;
		}

		if( $descr ) {
		  $params['message'] = $descr;
		}

		if($files) {
	            $params['attachments'] = $files;
	        }

		$params['from_group'] = 1;

		//wall.post
		$wallpost = $this->method("wall.post", $params);




	}





}




// https://vk.com/club152251201 группа аутмакс под акцию
// мой id336967229
// ID приложения 6157243 
// сервисный ключ доступа 0d32a9af0d32a9af0d32a9af060d6f5a1400d320d32a9af54a962a80f8d15fb719e0b7a
// https://oauth.vk.com/authorize?client_id=6157243&scope=groups,wall,offline,photos&redirect_uri=https://oauth.vk.com/blank.html&display=page&v=5.73&response_type=token
// access_token=b5ebe34d8a38a576a4f1fc9ed669b2e19d24a54f4fc759afe77ab399440472b6743cca035131aedbea4a3

//BMV BEGIN
// https://vk.com/club152251201 группа аутмакс под акцию
// мой id 462996045
// ID приложения 6681371 
// сервисный ключ доступа 83b56d0883b56d0883b56d08fb83d09e13883b583b56d08d8385bc9a48f4919528f2bcd
// https://oauth.vk.com/authorize?client_id=6681409&scope=groups,wall,offline,photos&redirect_uri=https://oauth.vk.com/blank.html&display=page&v=5.73&response_type=token
//https://oauth.vk.com/blank.html#access_token=9357b96b40bf7dbb452a61bcdfe23a21fc5a555040192d2d0456c37269086cfa32367d4a4c15d24a5c4df&expires_in=0&user_id=462996045
// access_token=9357b96b40bf7dbb452a61bcdfe23a21fc5a555040192d2d0456c37269086cfa32367d4a4c15d24a5c4df
//BMV END

$user_id = 49059617;// это ID круппа, чтобы фотки были подписаны от имени сообщества
$access_token='cd9b4164fd9b823a20f7e663804ef887d67e071578c22774f2bb899324d09ebee92da81ff19ed82eeae19';//это токен BMV

//https://vk.com/album-49059617_248731349

$group_id = 49059617;// группа https://vk.com/album-49059617_248731349
$album_id = 257774175;// альбом группы c распродажей

//https://vk.com/album-49059617_252499891 - альбом с очками
//$album_id = 252499891;// альбом группы c распродажей


$vk = new Model_Vk($access_token);


require_once('sql_connect.php');//подключение к библиотеке


$total_num = 0;
if(!empty($_GET["num"])){
	$total_num = $_GET["num"];
}
//если делаю по DESC, то старье будет сверху, если сделать по ASC - старье будет внизу

$FULL_CATALOG=$DB->select('SELECT * FROM `oc_product_to_category` WHERE `category_id`=? ORDER BY `product_id` ASC LIMIT ' .$total_num . ',4',1163);//получаем данные по супер распродаже
//$FULL_CATALOG=$DB->select('SELECT * FROM `om_bannercat` WHERE `category_id`=? ORDER BY `product_id` ASC LIMIT ' .$total_num . ',5',7608);//получаем данные по супер распродаже
//$FULL_CATALOG=$DB->select('SELECT * FROM `om_bannercat` WHERE `category_id`=? ORDER BY `product_id` ASC LIMIT 160,40',10250);//получаем данные по бесплатным очкам
$ATTRDESCRS=$DB->select('SELECT * FROM `oc_attribute_description` ORDER BY `attribute_id` ASC');//выбираем все названия атрибутов
$SIZENAMES=$DB->select('SELECT * FROM `oc_option_value_description` ORDER BY `option_value_id` ASC');//выбираем все названия размеров
print "Выгружено начиная с позиции: ". $total_num;

print "<br/>";

$i=0;

foreach ($FULL_CATALOG as $rowSKU=>$SKU) {		

	

	$PRODUCT=$DB->selectRow('SELECT * FROM `oc_product` WHERE `product_id`=?',$SKU['product_id']);//выбрали артикул
	
	if ($PRODUCT['quantity']>0) {

		//грузим только те, которые больше 0

		$IMAGES=$DB->select('SELECT * FROM `oc_product_image` WHERE `product_id`=?',$SKU['product_id']);//выбрали фотки к нему
		$ATTRS=$DB->select('SELECT * FROM `oc_product_attribute` WHERE `product_id`=?',$SKU['product_id']);//выбрали атрибуты


		$descr=$PRODUCT['fullname']."\r\n";
		$descr.="Артикул: ".$PRODUCT['product_id']."\r\n";	
		$descr.="Цена: ".(int) $PRODUCT['price']." руб. \r\n";
		$descr.="Размеры в наличии: ";

		$SIZES=$DB->select('SELECT * FROM `oc_product_option_value` WHERE `product_id`=? AND `quantity`>?',$SKU['product_id'],0);//выбрали размеры которые больше нуля

		foreach($SIZES as $rowSIZE=>$SIZE) {

			foreach($SIZENAMES as $rowSIZENAME=>$SIZENAME) {

				if ($SIZENAME['option_value_id']==$SIZE['option_value_id'] and $SIZE['option_id']<5) {

					$descr.=$SIZENAME['name'].", ";

				}
	
			}

		}

		$descr=substr($descr,0,-2);
		$descr=$descr." \r\n";

		foreach($ATTRS as $rowATTR=>$ATTR) {

			foreach($ATTRDESCRS as $rowATTRDESCR=>$ATTRDESCR) {

				if ($ATTRDESCR['attribute_id']==$ATTR['attribute_id'] and $ATTR['attribute_id']!=23) {

					$descr.=$ATTRDESCR['name'].": ".$ATTR['text']." \r\n";

				}
	
			}

		}





		
		
		$main_image_path=$_SERVER['DOCUMENT_ROOT'].'/image/'.$PRODUCT['image'];//главное фото
		//http://bizoutmax.ru/image/data/products/4931/Lacoste-lacoste-1.jpg
		//$main_image_path='http://bizoutmax.ru/image/'.$PRODUCT['image'];//главное фото
		print $main_image_path.'<br>';
	
		
		//Загружаем изображение в альбом группы
		$upload_img_for_album = $vk->uploadImage($main_image_path,$group_id,$album_id,$descr);
		// https://vk.com/dev/wall.post?params[owner_id]=-152251201&params[friends_only]=0&params[from_group]=1&params[message]=New%20post%20on%20group%20wall%20via%20API.console.&params[services]=twitter&params[signed]=0&params[mark_as_ads]=0&params[v]=5.68


	}





}
		//Ждем минуту и выгружаем следующие 5 товаров
		/*sleep(60);
		$total_num = $total_num + 5;
		if(count($FOR_ECHO_FULL_CATALOG) > $total_num) {
			header('Location: http://bizoutmax.ru/crontab/vk_api_v8.php?num='.$total_num);
		}*/

?>