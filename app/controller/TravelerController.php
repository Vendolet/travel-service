<?php

namespace app\controller;

use app\model\City;
use app\model\Score;
use app\model\Traveler;
use app\tools\Tools;
use app\validator\Validator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteContext;

class TravelerController extends Controller
{
    /**
     * Возвращает список путешественников по запросу
     * @return ResponseInterface
     */
    public function findAll(Request $request, Response $response): Response
    {
        $model = new Traveler($this->conn);
        $data = $model->getAll();

        return Tools::getResponseJSON($response, $data);
    }

    /**
     * Возвращает данные об путешественнике по GET запросу
     * @return ResponseInterface
     */
    public function findOne(Request $request, Response $response): Response
    {
        $modelTraveler = new Traveler($this->conn);
        $modelCity = new City($this->conn);
        $modelScore = new Score($this->conn);

        $route = RouteContext::fromRequest($request)->getRoute();
        $travelerID = $route->getArgument('id');

        if (!is_numeric($travelerID)){
            throw new HttpNotFoundException($request);
        }

        $traveler = $modelTraveler->getByID($travelerID);
        if (!$traveler){
            throw new HttpNotFoundException($request);
        }
        $cities = $modelCity->getAllByTravelerID($travelerID);
        $scores = $modelScore->getByTravelerID($travelerID);

        $data = ['traveler' => $traveler, 'cities' => $cities, 'scores' => $scores];
        return Tools::getResponseJSON($response, $data);
    }
}
