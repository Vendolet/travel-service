<?php

use app\controller\PlaceController;
use app\controller\TravelerController;
use app\controller\CityController;
use app\controller\ScoreController;
use app\controller\AuthController;
use Slim\Factory\AppFactory;

require BASE_DIR . '/vendor/autoload.php';

session_start();

$app = AppFactory::create();
$app->addErrorMiddleware(false, false, false);

$app->get('/place', PlaceController::class . ':findAll'); // получить все достопримечательности
$app->post('/place', PlaceController::class . ':findAll'); // получить все достопримечательности по фильтру
$app->get('/place/{id}', PlaceController::class . ':findOne'); // получить информацию об достопримечательности

$app->get('/city', CityController::class . ':findAll'); // получить все города
$app->get('/city/{id}', CityController::class . ':findOne'); // получить информацию о городе

$app->get('/score', ScoreController::class . ':findAll'); // посмотреть все оценки
$app->post('/score', ScoreController::class . ':create'); // добавить оценку достопримечательности
$app->put('/score', ScoreController::class . ':update'); // изменить оценку достопримечательности
$app->delete('/score', ScoreController::class . ':delete'); // удалить оценку достопримечательности

$app->get('/traveler', TravelerController::class . ':findAll'); // получить всех путешественников
$app->get('/traveler/{id}', TravelerController::class . ':findOne'); // получить информацию об путешественнике

$app->post('/signup', AuthController::class . ':signup'); // создать нового пользователя
$app->post('/login', AuthController::class . ':login'); // авторизация путешественника
$app->get('/logout', AuthController::class . ':logout'); // закрытие сессии путешественника

$app->run();
