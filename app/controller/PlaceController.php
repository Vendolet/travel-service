<?php

namespace app\controller;

use app\model\Place;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PlaceController extends Controller
{
    public function showAll(Request $request, Response $response)
    {
        $place = new Place($this->conn);
        $data = $place->getAll();

        $dataJson = json_encode($data);

        $response->getBody()->write($dataJson);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
