<?php

namespace app\controller;

use app\model\Traveler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;

class AuthController extends Controller
{
    /**
     * Аутентификация пользователя
     */
    public function login(Request $request, Response $response): Response
    {
        $model = new Traveler($this->conn);
        $inputJSON = $request->getBody();
        $data = json_decode($inputJSON, true);

        $errors = []; //TODO валидация данных

        // if (empty($errors)){
        //     $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT );

        //     if (!$model->isExistTraveler($data['phone']))
        //     {
        //         $newUser = $model->create($data);

        //         $_SESSION['user'] = $newUser;

        //         $outputJSON = json_encode($newUser);

        //         $response->getBody()->write($outputJSON);
        //         return $response->withHeader('Content-Type', 'application/json');
        //     }else{
        //          $errors['travelerIsExist'] = true;
        //     }
        // }

        $outputJSON = json_encode($errors);

        $response->getBody()->write($outputJSON);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    /**
     *
     */

    public function logout(): void
    {
        $_SESSION = [];
    }
}
