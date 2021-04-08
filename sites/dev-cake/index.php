<?php
ini_set('display_error', 1);
require_once('system/startup.php');

$router = new Router($registry);
$registry->set('router', $router);

$loader = new Loader($registry);
$registry->set('load', $loader);

$request = new Request();
$registry->set('request', $request);

$response = new Response();
$registry->set('response', $response);

$cart = new Cart();
$registry->set('cart', $cart);

$telegram = new Telegram();
$registry->set('telegram', $telegram);


$db = new DB(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$registry->set('db', $db);

$router->setPath(SITE_PATH . 'controllers');
$router->start();

// @$content = file_get_contents('http://localhost:4040/status');
// $pos = strpos($content, 'command_line\":{\"URL\":\"');
// $content = substr($content, $pos);
// $pos = strpos($content, '\",\"Proto\":\"https');
// $content = substr($content, 0, $pos);
// $content = str_replace('command_line\":{\"URL\":\"', '', $content);

//  $token = '1597848789:AAHFO8wmND7Cia6DS9UvcEnFsPd9I_8W9Wo';
//  $chat_id = '-554802612';
//  $headers = [];
//  $message;

// $message = "https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat_id}&text=123qwe22123";

// 		echo $message;
// 		$curl = curl_init();
// 		curl_setopt_array($curl, [
// 			CURLOPT_POST => 1,
// 			CURLOPT_HEADER => 0,
// 			CURLOPT_RETURNTRANSFER => 1,
// 			CURLOPT_URL => $message,
// 			CURLOPT_HTTPHEADER => array_merge(array("Content-Type: application/json"), $headers)
// 		]);

// 		$result = curl_exec($curl);
// 		curl_close($curl);