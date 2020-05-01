<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__.'/../vendor/autoload.php';

$config = require __DIR__.'/config.php';
$client = new Yess\Client($config['addr'], $config['port']);

function dd($var)
{
        echo '<pre>';
        var_dump($var);
        die();
}