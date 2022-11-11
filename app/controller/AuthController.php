<?php

namespace app\controller;

use app\model\Traveler;
use app\validator\Validator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController extends Controller
{
    /**
     * Аутентификация пользователя
     * @return ResponseInterface
     */
    public function login(Request $request, Response $response): Response
    {
        if ($this->isAuth){
            return $response->withStatus(403);
            //TODO вынести проверку прав в другой обработчик
        }

        $model = new Traveler($this->conn);
        $inputJSON = $request->getBody();
        $data = json_decode($inputJSON, true);

        $errors = [];

        $validator = new Validator($data, $model->getRequiredFieldsLogin());

        if (!$validator->validate()){
            $errors = $validator->getErrors();
        }

        if (empty($errors)){
            $user = $model->getTravelerByPhone($data['phone']);

            if ($user){
                if ($model->isVerifyPassword($user['phone'], $data['password'])){
                    $_SESSION['user'] = $user;

                    $outputJSON = json_encode($user);
                    $response->getBody()->write($outputJSON);
                    return $response->withHeader('Content-Type', 'application/json');

                }else{ $errors['password'] = 'Wrong password'; }
            }else{ $errors['phone'] = 'This user is not exist'; }
        }

        $outputJSON = json_encode($errors);
        $response->getBody()->write($outputJSON);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    /**
     * Закрытие сессии
     * @return ResponseInterface
     */

    public function logout(Request $request, Response $response): Response
    {
        if ($this->isAuth){
            $_SESSION = [];
            return $response->withStatus(200);
            //TODO вынести проверку прав в другой обработчик
        }
        return $response->withStatus(401);
    }
}
