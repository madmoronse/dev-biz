<?php
$BYPASS_DATE	= date('Y-m-d',time());							// текущая дата обхода

$QUERY		= '';

$track = '1206037889';

$info = array(
'004100',
'00087564',
'29952',
'24768',
'29943',
'29940',
'29885',
'29823',
'29818',
'00086836',
'29609',
'29601',
'00086741',
'29459',
'00107902'
);

$ACCOUNT	= 'PaUmnSqLrNkfWqStciyXfzggbTrYlySO';					// боевой
$SECURE		= md5($BYPASS_DATE.'&dNM0Bu9NCHWWOnbVsU5cotaf9pbWbPi9');		// боевой

foreach ($info as $number) {
$QUERY.='<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.PHP_EOL;
$QUERY.='<deleterequest number="123" ordercount="1" account="'.$ACCOUNT.'" date="'.$BYPASS_DATE.'" secure="'.$SECURE.'">'.PHP_EOL;
$QUERY.='  <order number="'.$number.'"/>'.PHP_EOL;
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
}