<?php

use app\controller\PlaceController;
use app\controller\TravelerController;
use Slim\Factory\AppFactory;

require BASE_DIR . '/vendor/autoload.php';

$app = AppFactory::create();

$app->get('/', PlaceController::class . ':findAll'); // получить все достопримечательности
$app->get('/place/{id}', PlaceController::class . ':findOne'); // получить информацию об достопримечательности
$app->post('/place/{id}', PlaceController::class . ':findOne'); // добавить оценку достопримечательности

$app->get('/traveler', TravelerController::class . ':findAll'); // получить всех пользователей
$app->post('/traveler/signup', TravelerController::class . ':signup'); // получить информацию об путешественнике
$app->get('/traveler/{id}', TravelerController::class . ':findOne'); // получить информацию об путешественнике

$app->run();
