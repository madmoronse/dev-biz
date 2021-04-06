<?php
// STEBLYUK ALEKSEY
// 89233530280
// 16:02 20.06.2017
// Поиск расхождений картинок в mysql и ftp для РАБОЧЕГО !!!! сайта omxtest.ru. Работает как в GET так и в POST.
// Возвращает json_decode массив. Предпоследий элемент - количество элементов. Последний элемент - время выполнения.Должен передаваться параметр password =1234567890
// NB1: в таблице oc_product храниться одна основная картинка, если она есть в 1с.
// NB2: в таблице oc_product_image храняться остальные картинки, если они есть.
// Вариант Работы 1
// http://omxtest.ru/admin/t1.php?password=1234567890&data={%220%22:5794,%221%22:1276}
// Через json комплектуется массив и передается в переменной data, если массив содержит ошибки, то будет сообщение data_corrupted
//
// Вариант Работы 2
// http://bizoutmax.ru/admin/t1.php?password=1234567890&data=
// Если дата - пустая, то проверяются все артикулы у кого количество > 0.
//
// Для рабочего сайта НАдо
// WORK1. Заменить параметры подключения к mySQL
// WORK2. Заменить имя БД
// WORK3. Заменить полный путь к катлогу где лежат картинки от корня.
// WORK4. Каталог где искать фотки.
// WORK5. Поидеи должно быть так же. как и в тестовой. ничего не меняем.
error_reporting (0);
$startTime = microtime(true);

//phpinfo();
//
//echo $_POST['password']."<br>\n";
//echo $_REQUEST['password']."<br>\n";
//echo $_REQUEST['data']."<br>\n";
//echo $_GET['password']."<br>\n";

if ( ( $_REQUEST['password'] == '1234567890' ) && isset($_REQUEST['data']) ) {
	//$addFullPath = "/home/users/t/testoutmax/domains/omxtest.ru/image/data/products/";
	//$addFullPath = "/var/www/u0040607/data/www/outmaxshop.ru/image/data/products/"; // WORK3
	$addFullPath = "/var/www/www-root/data/www/outmaxshop.ru/image/data/products/"; // WORK3 // /var/www/www-root/data/www/outmaxshop.ru
	
	if (!is_dir($addFullPath)){
		die($addFullPath ." notExist!!!");
	}

//echo "passwodISPASSED";
	// Outmax
	$link = mysql_connect('localhost', 'u0040607_admin', '0O8d8P5jT4o4U6b0') or die('ne udalos soedinitsya: ' . mysql_error());
	mysql_select_db('u0040607_outmax') or die('ne udalos vibrat bazu dannih');  
	
	// Outmax_BIZ
	//$link = mysql_connect('localhost', 'u0040607_admin', 'dE40oJBhdE40oJBh') or die('ne udalos soedinitsya: ' . mysql_error());
	//mysql_select_db('u0040607_bizoutmax') or die('ne udalos vibrat bazu dannih');
	

	$arr_data = json_decode($_REQUEST['data']);
//echo "asdf<br><pre>";
//print_r($arr_data);
//echo "sizeof=".count($arr_data);
	if  ( count($arr_data) <= 0 ) {
		IF ( strlen($_REQUEST['data'])<=0 ) {
			$sql = "SELECT product_id FROM oc_product WHERE quantity > 0";
			$res = mysql_query($sql);
			$arr_data = array();
			While ( $value = mysql_fetch_array($res , MYSQL_ASSOC) ) {
			$arr_data[] = $value['product_id'];
			}
		} else { die("data-corrupted");}
//echo "asdf<br><pre>";
//print_r($arr_data);			
	}
	
	
	$arrRepairImages = array(); //suda zapisivaem vozvrashennie artikuli
	
    
	foreach ($arr_data as $key => $value) {
		// Proverka, est' li kartinki na saite
//echo $addFullPath."domains/omxtest.ru/image/data/products/".$value;
		 //$scanFiles = scandir ($addFullPath.$value,1); 
 		 $scanFiles = scandir ($addFullPath.$value,1); // WORK4 
//print_r($scanFiles);
		if ( !$scanFiles ) {
			array_push($arrRepairImages,$value);
			continue;
		}

		// sveryaem po kolichestvu sql i ftp, a takzhe poluchaem puti k failam
		$ftpCountImages = count($scanFiles)-2 ;
//echo "<br>ftpCountImages=".$ftpCountImages;
		$sql_select = "SELECT DISTINCT product_id,image FROM(".
			"SELECT product_id,image FROM oc_product ".
			"WHERE product_id = ".$value." ".
			"UNION ".
			"SELECT product_id,image FROM oc_product_image ".
			"WHERE product_id = ".$value ." ".
			") as images ".
			"ORDER BY product_id,image ";
//echo "<br>".$sql_select;
		$res = mysql_query($sql_select) or die('Zapros ne udalsya: ' . mysql_error());
		//$images4mSql = mysql_fetch_array($res , MYSQL_ASSOC);
		$sqlCountImages = mysql_num_rows($res);
//echo "<br>sqlCountImages=".$sqlCountImages ;		
		If ( $ftpCountImages != $sqlCountImages) {
			array_push($arrRepairImages,$value);
			continue;
		} 
		
		//proveryaem faili po puti
		//foreach ($images4mSql as $imgValue) {
		WHILE ( $imgValue = mysql_fetch_array($res , MYSQL_ASSOC) ) {
			// if ( !file_exists ( $imgValue)) { // staraya tema
//echo "<pre><br>\n".print_r($imgValue)."<br>\n".print_r($scanFiles);

			 $imageWithoutPath = explode("/", $imgValue['image']);	
//echo "<br>\n===".$imageWithoutPath[3];
			// Ishem imya v massive. Bistree (vozmozhno), chem iskat' fail.
			//if ( !(in_array($imageWithoutPath[3], $scanFiles)) ) { 
			if ( !(in_array($imageWithoutPath[3], $scanFiles)) ) { // WORK5
				array_push($arrRepairImages,$value);
				break;
			}
			
		}

		
	}
	mysql_close($link);
//array_push($arrRepairImages, count($arrRepairImages));	
//array_push($arrRepairImages,microtime(true) - $startTime );
//printf('script vipolnyalsya %.4F sec.', microtime(true) - $startTime);
	//If ($arrRepairImages  > 0) {
	echo json_encode($arrRepairImages);
	return 0;
//	return(json_encode($arrRepairImages)); 				
//	} else 
//	return(json_encode(0));
} 	



?>