<?php

if (preg_match("/^[0-9]+$/i", $_SESSION['customer_id']) and isset($_SESSION['customer_id'])) {

	//если это цифры и сессия установлена, то подключаемся к БД и делаем пару запросов
	require_once 'mylib/sql_connect.php';

	$CURRENT_ORDER=$DB->selectRow('SELECT * FROM `oc_order` WHERE `customer_id`=? ORDER BY `order_id` DESC',$_SESSION['customer_id']);//получаем последний заказ
	$CURRENT_USER=$DB->selectRow('SELECT * FROM `oc_customer` WHERE `customer_id`=?',$_SESSION['customer_id']);//нахуя?				


}


if ($CURRENT_ORDER and $CURRENT_USER['customer_group_id']<2 and $_REQUEST['paymentType']!='PR') {


	header('Location: /index.php?route=account/order/info&order_id='.$CURRENT_ORDER['order_id']);


} else {


	if ($_REQUEST['paymentType'] == 'PR' and $CURRENT_USER['customer_group_id']<2 and isset($_SESSION['customer_id'])) {

		$text_message=$DB->selectCell('SELECT `comment` FROM `oc_order_history` WHERE `order_id`=?',$_REQUEST['order_id']);//получаем заказ
		$heading_title='Реквизиты для оплаты';
		$text_message='<p style="line-height:30px;">'.nl2br($text_message).'</p>';
		$FIND_TXT='В комментарии платежа укажите фамилию.';
		$REPL_TXT='';
		$text_message=str_replace($FIND_TXT,$REPL_TXT,$text_message);
		$text_message=str_replace('Номер киви кошелька(Visa QIWI Wallet): 89233706569.','',$text_message);
		$text_message=str_replace(' или на Киви кошелек(Visa QIWI Wallet)','',$text_message);
		$continue='order-history';

	?>

<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    
    <a href="/">Главная</a> / <a href="/my-account/">Личный Кабинет</a> / <a href="/order-history/">История заказов</a> / <a href="/index.php?route=account/order/info&order_id=<?php echo $_REQUEST['order_id']; ?>">Заказ #<?php echo $_REQUEST['order_id']; ?></a> / <a href="">Реквизиты для оплаты</a>
    
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <div class="wrapper" style="padding:20px;font-size:14px;line-height:1.2"><?php echo $text_message; ?></div>
  <div class="buttons">
    <div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
  </div>
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>


<?php

	} else {


?>

<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <div class="wrapper" style="padding:20px;font-size:14px;line-height:1.2"><?php echo $text_message; ?></div>
  <div class="buttons">
    <div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
  </div>
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>


<?php

	}

}

?>