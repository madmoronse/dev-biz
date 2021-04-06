<?php
use Neos\classes\util as U;
use Neos\classes\log as Log;
define('_NEXEC', 1);
define('NPATH_BASE', __DIR__ . '/../neos_debug');

require_once NPATH_BASE . '/_includes/constants.php';
require_once NPATH_INCLUDES . '/loader.php';
require_once NPATH_BASE . '/../config.php';

NeosLoader::setup();

	$db = U\DBSingleton::getInstance(array(
            'host' => DB_HOSTNAME,
            'user' => DB_USERNAME,
            'pass' => DB_PASSWORD,
            'db' => DB_DATABASE
        ));



	//устанавливаем нужное время для нужных новинок, которые всегда надо держать наверху в определенном порядке
	//список в таблице om_datemod

$LST = '';
	$ORDERS=$db->getAll('SELECT * FROM `oc_order` WHERE `customer_id`=?i ORDER BY `order_id` DESC LIMIT 3000',20430);

	foreach($ORDERS as $rowORDERS=>$ORDER) {

		$withTracks=$db->getAll("SELECT * FROM `oc_order_history` WHERE `order_id`=?i AND `order_status_id`=?i ORDER BY order_history_id DESC LIMIT 1",$ORDER['order_id'],16);//ищем order

        foreach($withTracks as $withTrack) {
            //$LST .= '<tr><td>'.$ORDER['order_id'].'</td><td>'.$withTrack['comment'].'</td></tr>';
            if ($withTrack['comment']) {
                $COMM = str_replace("\r", " ", str_replace("\n", " ", $withTrack['comment']));
                $COMM = preg_replace("/\s{2,}/", " ", $COMM);
                $COMM = htmlspecialchars_decode($COMM, ENT_QUOTES);

                $LST .= $ORDER['order_id'] . ';Заказ отправлен;' . $COMM . "\r\n";
            }
        }
        if (!$withTracks) {
            $withOutTracks = $db->getAll("SELECT * FROM `oc_order_history` WHERE `order_id`=?i AND `order_status_id`=?i", $ORDER['order_id'], 15);//ищем order
            foreach ($withOutTracks as $withOutTrack) {
                if ($withOutTrack['comment']) {
                    $COMM = str_replace("\r", " ", str_replace("\n", " ", $withOutTrack['comment']));
                    $COMM = preg_replace("/\s{2,}/", " ", $COMM);
                    $COMM = htmlspecialchars_decode($COMM, ENT_QUOTES);

                    $LST .= $ORDER['order_id'] . ';Заказ отгружается;' . "\r\n";
                }
            }
        }

	}




	$file = __DIR__ . '/../tracks/track_20430.csv';

	
	file_put_contents($file, $LST);

