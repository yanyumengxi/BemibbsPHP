<?php

require_once __DIR__.'/vendor/autoload.php';

use app\controllers\IndexController;
use bemibbs\Application;
use bemibbs\validator\Validator;

$app = new Application(__DIR__);

//$app->router->get('/', function () {
//    return "Welcome to the bemibbs framework！！！";
//});
$app->router->get('/', [IndexController::class, 'index']);
$app->run();