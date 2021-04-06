<?php
require_once 'PayU.php';
$payu = new PayU('', 'gehyytht', 'C2c55^9#+r90T6#h8|Z7');
/**
 * В $_POST будут содержаться параметры, выбранные в панеле управления настройками IPN.
 */
// Обработка IPN запроса
$result = $payu->handleIpnRequest();


//echo $result;

print '<H1 style="text-align:center;">Операция проведена.</H1><H4 style="text-align:center;">Вернуться на <a href="http://bizoutmax.ru/">главную страницу OUTMAX</a></H4>';

?>