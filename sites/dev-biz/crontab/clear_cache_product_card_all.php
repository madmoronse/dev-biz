<?php
define('_NEXEC', 1);
define('NPATH_BASE', __DIR__ . '/../neos_debug');

require_once NPATH_BASE . '/_includes/constants.php';

system('rm -rf ' . NPATH_CACHE . '/product');
mkdir(NPATH_CACHE . '/product', 0777);
chown(NPATH_CACHE . '/product', 'www-root');
