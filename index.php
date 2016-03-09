<?php

use ruddy\Base\App\application;
use ruddy\Base\Server as server;

require_once 'autoloader.php';

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

$GLOBALS['_PATH'] = new server\Path('INDEX', __FILE__);
$GLOBALS['_HTTP'] = new server\HTTP();

$app = new application('App1');
$app->run();
