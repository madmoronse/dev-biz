<?php
/**
 * ������ ��� ������� ����� cron �������� ������� � .yml 
 */
    require_once('basic_cron_script.php');
    make_request('index.php?route=feed/yandex_market');
?>
