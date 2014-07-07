<?php

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

$loader = require_once dirname(__DIR__) . DS . 'vendor' . DS . 'autoload.php';
$loader->add('Vnn', __DIR__);
