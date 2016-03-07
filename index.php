<?php

use ruddy\Base\App\application;
use ruddy\Base\Server as server;

require_once 'autoloader.php';

$GLOBALS['_PATH'] = new server\Path('INDEX', __FILE__);
$GLOBALS['_HTTP'] = new server\HTTP();

try {
    $app = new application('App1');
    $app->run();
} catch (\Exception $e){
    echo 'Error!: '. $e->getMessage() .', file: '. $e->getFile() .', line: '. $e->getLine();
}