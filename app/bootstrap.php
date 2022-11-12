<?php

use app\controller\PlaceController;
use app\controller\TravelerController;
use app\controller\AuthController;
use Slim\Factory\AppFactory;

require BASE_DIR . '/vendor/autoload.php';

session_start();

$app = AppFactory::create();

$app->get('/', PlaceController::class . ':findAll'); // получить все достопримечательности
$app->get('/place/{id}', PlaceController::class . ':findOne'); // получить информацию об достопримечательности
$app->post('/place/{id}', ScoreController::class . ':findOne'); // добавить оценку достопримечательности

$app->get('/traveler', TravelerController::class . ':findAll'); // получить всех путешественников
$app->get('/traveler/{id}', TravelerController::class . ':findOne'); // получить информацию об путешественнике

$app->post('/signup', AuthController::class . ':signup'); // создать нового пользователя
$app->post('/login', AuthController::class . ':login'); // авторизация путешественника
$app->get('/logout', AuthController::class . ':logout'); // закрытие сессии путешественника

$app->run();
