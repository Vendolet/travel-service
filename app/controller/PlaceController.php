<?php

namespace app\controller;

use app\model\City;
use app\model\Place;
use app\model\Score;
use app\tools\Tools;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteContext;
use Valitron\Validator;

class PlaceController extends Controller
{
    /**
     * Возвращает список путешественников по запросу
     * @return ResponseInterface
     */
    public function findAll(Request $request, Response $response): Response
    {
        $modelPlace = new Place($this->conn);
        $modelCity = new City($this->conn);

        if ($request->getMethod() == 'POST'){
            $filters = Tools::getRequestContentBody($request);

            $validator = new Validator($filters);
            $modelPlace->getRulesFilter($validator);

            if (!$validator->validate()){
                return Tools::getResponseJSON($response, $validator->errors())->withStatus(400);
            }

            //TODO перенести проверку массива ниже в пользовательский валидатор модели
            if (!$this->validateFilterCityArray($filters['city'])){
                return Tools::getResponseJSON($response, ['city' => 'Array contains not integer'])->withStatus(400);
            }

            $places = $modelPlace->getFilterAll($filters);
        }else{
            $places = $modelPlace->getAll();
        }

        $cities = $modelCity->getAll();

        $data = ['places' => $places, 'cities' => $cities];
        return Tools::getResponseJSON($response, $data);
    }

    /**
     * Возвращает данные об достопримечательности по GET запросу
     * @return ResponseInterface
     */
    public function findOne(Request $request, Response $response): Response
    {
        $modelPlace = new Place($this->conn);
        $modelScore = new Score($this->conn);
        $route = RouteContext::fromRequest($request)->getRoute();

        $modelID = $route->getArgument('id');
        //TODO валидатор в middleware
        if (!is_numeric($modelID)){
            throw new HttpNotFoundException($request);
        }

        $place = $modelPlace->getByID($modelID);
        if (!$place){
            throw new HttpNotFoundException($request);
        }
        $scores = $modelScore->getByPlaceID($modelID);

        return Tools::getResponseJSON($response, ['scores' => $scores, 'place' => $place]);
    }

    /**
     * Валидация значений массива городов фильтра
     * @param mixed $data данные на проверку
     * @return bool возвращает true если массив пустой или содержит только целые числа. Иначе False.
     */
    private function validateFilterCityArray(mixed $data): bool
    {
        if(empty($data)){
            return true;
        }else{
            // TODO refactor на тернарных операторах
            foreach ($data as $value)
            {
                if (!is_int($value)){
                    return false;
                }
            }
            return true;
        }
    }
}
