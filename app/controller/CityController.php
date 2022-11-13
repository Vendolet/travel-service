<?php

namespace app\controller;

use app\model\Place;
use app\model\City;
use app\model\Traveler;
use app\tools\Tools;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteContext;

class CityController extends Controller
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
        if (!is_numeric($cityID)){
            throw new HttpNotFoundException($request);
        }

        $city = $modelCity->getByID($cityID);
        if(!$city){
            throw new HttpNotFoundException($request);
        }
        $places = $modelPlace->getPlaceOfCity($cityID);
        $travelers = $modelTraveler->getTravelerOfCity($cityID);

        $data = ['city' => $city, 'places' => $places, 'travelers' => $travelers];
        return Tools::getResponseJSON($response, $data);
    }
}
