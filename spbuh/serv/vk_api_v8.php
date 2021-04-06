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
		
		//Получаем сервер для загрузки изображения
		$response = $this->method("photos.getUploadServer", $params);
		
		
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
		
		
		
		
		
		//Сохраняем файл в альбом
		$photo = $this->method("photos.save", array(
		  "server" => $json->server,
		  "photos_list" => $json->photos_list,
		  "album_id" => $album_id,
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
// https://oauth.vk.com/authorize?client_id=6157243&scope=groups,wall,offline,photos&redirect_uri=https://oauth.vk.com/blank.html&display=page&v=5.53&response_type=token
// access_token=725b7eaf97d77916e50b8129b6307c0d63113bc9027be1548a978e825ecf68973a27537cf7e10eaa7303b&expires_in=0&user_id=336967229

print $_SERVER['DOCUMENT_ROOT'];

$user_id = 152251201;// это ID круппа, чтобы фотки были подписаны от имени сообщества
$access_token='725b7eaf97d77916e50b8129b6307c0d63113bc9027be1548a978e825ecf68973a27537cf7e10eaa7303b';//это токен Федор Сорокин

$group_id = 49059617;// группа https://vk.com/album-49059617_248731349
$album_id = 248731349;// альбом группы c распродажей


$vk = new Model_Vk($access_token);




//если делаю по DESC, то старье будет сверху, если сделать по ASC - старье будет внизу
$FULL_CATALOG=$DB->select('SELECT * FROM `om_bannercat` WHERE `category_id`=? ORDER BY `product_id` ASC LIMIT 0,40',7608);//получаем данные по супер распродаже
$ATTRDESCRS=$DB->select('SELECT * FROM `oc_attribute_description` ORDER BY `attribute_id` ASC');//выбираем все названия атрибутов
$SIZENAMES=$DB->select('SELECT * FROM `oc_option_value_description` ORDER BY `option_value_id` ASC');//выбираем все названия размеров

foreach ($FULL_CATALOG as $rowSKU=>$SKU) {


	$PRODUCT=$DB->selectRow('SELECT * FROM `oc_product` WHERE `product_id`=?',$SKU[product_id]);//выбрали артикул

	if ($PRODUCT[quantity]>0) {

		//грузим только те, которые больше 0

		$IMAGES=$DB->select('SELECT * FROM `oc_product_image` WHERE `product_id`=?',$SKU[product_id]);//выбрали фотки к нему
		$ATTRS=$DB->select('SELECT * FROM `oc_product_attribute` WHERE `product_id`=?',$SKU[product_id]);//выбрали атрибуты


		$descr=$PRODUCT[fullname]."\r\n";
		$descr.="Артикул: ".$PRODUCT[product_id]."\r\n";	
		$descr.="Цена: ".(int) $PRODUCT[price]." руб. \r\n";
		$descr.="Размеры в наличии: ";

		$SIZES=$DB->select('SELECT * FROM `oc_product_option_value` WHERE `product_id`=? AND `quantity`>?',$SKU[product_id],0);//выбрали размеры которые больше нуля

		foreach($SIZES as $rowSIZE=>$SIZE) {

			foreach($SIZENAMES as $rowSIZENAME=>$SIZENAME) {

				if ($SIZENAME[option_value_id]==$SIZE[option_value_id] and $SIZE[option_id]<5) {

					$descr.=$SIZENAME[name].", ";

				}
	
			}

		}

		$descr=substr($descr,0,-2);
		$descr=$descr." \r\n";

		foreach($ATTRS as $rowATTR=>$ATTR) {

			foreach($ATTRDESCRS as $rowATTRDESCR=>$ATTRDESCR) {

				if ($ATTRDESCR[attribute_id]==$ATTR[attribute_id] and $ATTR[attribute_id]!=23) {

					$descr.=$ATTRDESCR[name].": ".$ATTR[text]." \r\n";

				}
	
			}

		}





		
		
		$main_image_path=$_SERVER['DOCUMENT_ROOT'].'/image/'.$PRODUCT[image];//главное фото
		//http://bizoutmax.ru/image/data/products/4931/Lacoste-lacoste-1.jpg
	
		
		//Загружаем изображение в альбом группы
		$upload_img_for_album = $vk->uploadImage($main_image_path,$group_id,$album_id,$descr);
		// https://vk.com/dev/wall.post?params[owner_id]=-152251201&params[friends_only]=0&params[from_group]=1&params[message]=New%20post%20on%20group%20wall%20via%20API.console.&params[services]=twitter&params[signed]=0&params[mark_as_ads]=0&params[v]=5.68


	}





}



?>