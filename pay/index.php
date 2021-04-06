<?php


if ($_REQUEST['paymentType']=='PC' or $_REQUEST['paymentType']=='AC') {


	print 'Yandex';

	//передать весь массив
	//https://money.yandex.ru/quickpay/confirm.xml

}
	

if ($_REQUEST['paymentType']=='SP') {


	print 'SimplePay';

	//передать весь массив


}



?>