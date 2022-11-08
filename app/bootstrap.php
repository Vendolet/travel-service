<?php

use app\controller\PlaceController;
use Slim\Factory\AppFactory;

require BASE_DIR . '/vendor/autoload.php';

$app = AppFactory::create();

$app->get('/', PlaceController::class . ':showAll');

$app->run();
