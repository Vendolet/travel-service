<?php

namespace app\controller;

use app\model\Place;
use app\tools\Tools;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;

class PlaceController extends Controller
{
    public function findAll(Request $request, Response $response): Response
    {
        $place = new Place($this->conn);
        $data = $place->getAll();

        return Tools::getResponseJSON($response, $data);
    }

    public function findOne(Request $request, Response $response): Response
    {
        $model = new Place($this->conn);
        $route = RouteContext::fromRequest($request)->getRoute();

        $modelID = $route->getArgument('id');

        $data = $model->getByID($modelID);

        //TODO список оценок достопримечательности

        return Tools::getResponseJSON($response, $data);
    }

    /**
     * Добавить оценку достопримечательности
     */
    public function addScore(Request $request, Response $response)
    {

    }
}
