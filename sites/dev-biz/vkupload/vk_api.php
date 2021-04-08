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





}



		require 'addons.php';
		require 'sql_connect.php';



$album_id = 236165820;
// грузить фотки лучше по 40 шт
// косяк с загрузкой - ВК брыкатеся
// лучше работать с утра


$i=1;


$ALBUM=$DB->selectRow('SELECT * FROM `main_total_vk_groups_albums` WHERE `album_id`=?',$album_id);//получаем альбом, можно проверку сделать, есть ли такой альбом



$GROUP=$DB->selectRow('SELECT * FROM `main_total_vk_groups` WHERE `group_id`=?',$ALBUM[group_id]);//получаем группу, в которой альбом




$vk = new Model_Vk($GROUP[admin_token]);//подключаемся по токену админа группы




//выбираем нужный каталог по типу album_type


if ($ALBUM[album_type] == 'krossovki_sex_brandname') {


	$FULL_CATALOG=$DB->select('SELECT * FROM `main_total` WHERE `artikul`>? AND `cat_id`=? AND `brand_id`=? AND `sex_id`=? ORDER BY `artikul` ASC LIMIT 41',0,$ALBUM[album_catalog_id],$ALBUM[album_brand_id],$ALBUM[album_sex_id]);

	//аццкей сотона, а если сделать запрос артикулов, которые не пересекаются в двух таблицах?
	//$VK_PHOTO=$DB->select('SELECT * FROM `main_total_vk_groups_album_photos` WHERE `group_id`=? AND `album_id`=?',$ALBUM[group_id],$ALBUM[album_id]);

	

}



foreach ($FULL_CATALOG as $numCat=>$SKU) {


	//пытаемся выбрать артикул из уже загруженных, если совпадений нет, то загружаем и пишем в базу уже записанные артикулы

	if ( !$VK_PHOTO=$DB->selectRow('SELECT * FROM `main_total_vk_groups_album_photos` WHERE `artikul`=? AND `group_id`=? AND `album_id`=?',$SKU[artikul],$ALBUM[group_id],$ALBUM[album_id]) ) {
	

		//формируем заголовок для фотки
		$DESCR=ucwords($ALBUM[album_catalog_name])." ".ucwords($BRANDS[$SKU[brand_id]]).' '.strtoupper($SKU[full_model_name])." ".$ALBUM[album_sex_name]."\r\n";

		$DESCR.="Артикул: ".$SKU[artikul]."\r\n";//артикул 
		$DESCR.=$ALBUM[album_sizes]."\r\n";//размерныей ряд
		$DESCR.="По вопросам - пишите https://vk.com/id".$GROUP[admin_id]."\r\n";//адрес для вопросов
		
		
		$image_path=$_SERVER['DOCUMENT_ROOT'].'/image/data/products/'.$SKU[artikul].'/'.$SKU[image];
		
		
		
		//Загружаем изображение
		$upload_img = $vk->uploadImage($image_path,$ALBUM[group_id],$ALBUM[album_id],$DESCR);
	
		if ( $upload_img == false ) {

			//тут надо чота сделать, можно просто указать, какие артикулы не загрузились
			print '<p style="color:red;">'.$i.' | '.$image_path.'</p>';
			$i++;


		} else {
	
			//Надо помнить, какие артикулы уже были загружены, чтобы не плодить дубли

			print '<p style="color:green;">'.$i.' | '.$image_path.'</p>';
			$i++;


				$DB->query('INSERT INTO `main_total_vk_groups_album_photos` (
	
						`group_id`,
						`album_id`,
						`artikul`) 
	
	
					VALUES (?,?,?)',

						$ALBUM[group_id],
						$ALBUM[album_id],
						$SKU[artikul]
	
				);

			
	
		}


	}
	


}



?>