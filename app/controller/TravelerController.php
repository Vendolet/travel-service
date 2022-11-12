<?php

namespace app\controller;

use app\model\Traveler;
use app\tools\Tools;
use app\validator\Validator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;

class TravelerController extends Controller
{
    /**
     * Возвращает список путешественников по GET запросу
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
        $model = new Traveler($this->conn);

        $route = RouteContext::fromRequest($request)->getRoute();
        $modelID = $route->getArgument('id');
        //TODO валидация данных

        $data = $model->getByID($modelID);
        //TODO список посещенных городов и оценок достопримечательностей
        return Tools::getResponseJSON($response, $data);
    }
}
