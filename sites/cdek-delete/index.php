<?php
$BYPASS_DATE	= date('Y-m-d',time());							// текущая дата обхода

$QUERY		= '';

$track = '1205670567';
$numb = '85278';

$ACCOUNT	= 'PaUmnSqLrNkfWqStciyXfzggbTrYlySO';					// боевой
$SECURE		= md5($BYPASS_DATE.'&dNM0Bu9NCHWWOnbVsU5cotaf9pbWbPi9');		// боевой

$QUERY.='<?xml version="1.0" encoding="UTF-8"?> standalone="yes"'.PHP_EOL;
$QUERY.='<deleterequest number="'.$numb.'" ordercount="1" account="'.$ACCOUNT.'" date="'.$BYPASS_DATE.'" secure="'.$SECURE.'">'.PHP_EOL;
$QUERY.='  <Order DispatchNumber="'.$track.'" />'.PHP_EOL;
$QUERY.='</deleterequest>';


$REQUEST_URL='https://integration.cdek.ru/delete_orders.php';


$REQUEST_METHOD='POST';


$request = curl_init($REQUEST_URL);

curl_setopt($request, CURLOPT_POST, true);
curl_setopt($request, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
curl_setopt($request, CURLOPT_POSTFIELDS, "xml_request=" . $QUERY);
curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);

$data = curl_exec($request);

$array_data = simplexml_load_string($data);

curl_close($request);	// рвём соединение

echo "<pre>";
print_r($array_data);