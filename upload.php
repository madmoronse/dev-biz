<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
session_start();
header('Content-Type: text/html; charset=utf-8');
require_once("config.php");

$mysqli = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$mysqli->set_charset("utf8");
if ($mysqli->connect_errno) {
    return;
}

if($_REQUEST["act"]=="delete") {
	$image_id = (int) $_REQUEST["image_id"];

	$res = $mysqli->query("SELECT * FROM `oc_pavblog_images` WHERE `id` = '{$image_id}'");
	if($res->num_rows > 0){
      	$arImage = $res->fetch_assoc();

      	unlink($_SERVER["DOCUMENT_ROOT"]."/image/data/Blog/pavimages/".$arImage['image']);
      	$mysqli->query("DELETE FROM `oc_pavblog_images` WHERE `id` = {$image_id}");

      	echo json_encode(array('success' => true));
    }
    else {
    	echo json_encode(array('success' => false, 'error' => 'Image not found, id: ' . $image_id));
    }
}
else {
	$uploaddir = $_SERVER["DOCUMENT_ROOT"].'/image/data/Blog/pavimages/';
	$error_keys = array();
	foreach ($_FILES['pav_images']['tmp_name'] as $key => $file) {
		$name = preg_replace("/([^\w\s\d\-_~,;:\[\]\(\).]|[\.]{2,})/", "", $_FILES['pav_images']['name'][$key]);
		if (!preg_match('/.jpg$|.jpeg$|.png$/', $name)) {
			echo json_encode(
				array(
					'success' => false,
					'error' => 'Wrong File!'
				)
			);
			exit;
		}
		$uploadfilename = basename(time().'_'.rand(100, 999).'_'.$name);
		$uploadfile = $uploaddir . $uploadfilename;
		if(!move_uploaded_file($file, $uploadfile)) {
		    $error_keys[$key] = 'File can\'t be moved to a upload directory';
		} else {
			$ctime = time();
			$name = $mysqli->real_escape_string($name);
			$uploadfilename = $mysqli->real_escape_string($uploadfilename);
			$blog_id = (int) $_REQUEST["blog_id"];
			$mysqli->query("INSERT INTO `oc_pavblog_images` (news_id, image, original_name, ctime) 
							VALUES ('$blog_id', '$uploadfilename', '$name', '$ctime')");
			$image_ids[$key] = $mysqli->insert_id;
		}
	}
	if (!count($error_keys)) {
		echo json_encode(array('success' => true, 'image_ids' => $image_ids));
	} else {
		echo json_encode(array('success' => false, 'error' => 'There was error during upload', 'errorkeys' => $error_keys));
	}
}