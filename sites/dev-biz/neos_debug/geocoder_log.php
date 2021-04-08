<?php
use Neos\classes\util as U;
use Neos\classes\log as Log;
define('_NEXEC', 1);
define('NPATH_BASE', __DIR__);

require_once NPATH_BASE . '/_includes/constants.php';
require_once NPATH_INCLUDES . '/neosfactory.php';
require_once NPATH_INCLUDES . '/loader.php';
require_once NPATH_BASE . '/../config.php';

NeosLoader::setup();

$logger = new Log\Logger(array('db_instance' => \Neos\NeosFactory::getDb()));
$data['type'] = 'geocoder';
$data['query'] = $_POST['query'];
$data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
$data['ip'] = $_SERVER['REMOTE_ADDR'];
$data['context'] = $_POST['context'];

$logger->write($data);