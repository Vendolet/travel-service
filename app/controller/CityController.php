<?php

namespace app\controller;

use app\model\Place;
use app\model\Score;
use app\model\City;
use app\model\Traveler;
use app\tools\Tools;
use Valitron\Validator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;

class ScoreController extends Controller
{
    /**
     * Возвращает список путешественников по GET запросу
     * @return ResponseInterface
     */
    public function findAll(Request $request, Response $response): Response
    {
        $model = new City($this->conn);
        $data = $model->getAll();

        return Tools::getResponseJSON($response, $data);
    }

    /**
     * Возвращает данные об путешественнике по GET запросу
     * @return ResponseInterface
     */
    public function findOne(Request $request, Response $response): Response
    {
        $modelCity = new City($this->conn);
        $modelPlace = new Place($this->conn);
        $modelTraveler = new Traveler($this->conn);

        $route = RouteContext::fromRequest($request)->getRoute();
        $cityID = $route->getArgument('id');
        //TODO валидация данных в Middleware

        $city = $modelCity->getByID($cityID);
        $places = $modelPlace->getPlaceOfCity($cityID);
        $travelers = $modelTraveler->getTravelerOfCity($cityID);

        $data = ['city' => $city, 'places' => $places, 'travelers' => $travelers];
        return Tools::getResponseJSON($response, $data);
    }
}
