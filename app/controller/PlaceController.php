<?php

namespace app\controller;

use app\model\Place;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;

class PlaceController extends Controller
{
    public function findAll(Request $request, Response $response)
    {
        $place = new Place($this->conn);
        $data = $place->getAll();

        $dataJson = json_encode($data);

        $response->getBody()->write($dataJson);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function findOne(Request $request, Response $response): Response
    {
        $model = new Place($this->conn);
        $route = RouteContext::fromRequest($request)->getRoute();

        $modelID = $route->getArgument('id');

        $data = $model->getByID($modelID);
        $dataJson = json_encode($data);

        //TODO список оценок достопримечательности

        $response->getBody()->write($dataJson);
        return $response->withHeader('Content-Type', 'application/json');
    }
    /**
     * Добавить оценку достопримечательности
     */
    public function addScore(Request $request, Response $response)
    {

    }
}
