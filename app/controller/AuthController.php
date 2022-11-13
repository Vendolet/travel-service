<?php

namespace app\controller;

use app\model\Traveler;
use app\tools\Tools;
use Valitron\Validator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpUnauthorizedException;

class AuthController extends Controller
{
    /**
     * Аутентификация пользователя
     * @return ResponseInterface
     */
    public function login(Request $request, Response $response): Response
    {
        if ($this->isAuth){
            //TODO вынести проверку прав в другой обработчик (Middleware)
            throw new HttpForbiddenException($request);
        }

        $errors = [];
        $model = new Traveler($this->conn);
        $data = Tools::getRequestContentBody($request);
        $validator = new Validator($data);

        $model->getRulesLogin($validator);

        if (!$validator->validate()){
            $errors = $validator->errors();
        }

        if (empty($errors)){
            $user = $model->getTravelerByPhone($data['phone']);

            if ($user){
                if ($model->isVerifyPassword($user['phone'], $data['password']))
                {
                    $_SESSION['user'] = $user;
                    return Tools::getResponseJSON($response, $user);
                }else{
                    $errors['password'] = ['Wrong password'];
                }
            }else{
                $errors['phone'] = ['This user is not exist'];
            }
        }

        return Tools::getResponseJSON($response, $errors)->withStatus(400);
    }

    /**
     * Регистрация нового пользователя (путешественника)
     * @return ResponseInterface JSON ответ со списком ошибок в случае неудачи
     * или JSON ответ с новым пользователем
     */
    public function signup(Request $request, Response $response): Response
    {
        if ($this->isAuth){
            //TODO вынести проверку прав в другой обработчик (Middleware)
            throw new HttpForbiddenException($request);
        }

        $errors = [];
        $model = new Traveler($this->conn);
        $data = Tools::getRequestContentBody($request);
        $validator = new Validator($data);

        $model->getRulesCreate($validator);

        if (!$validator->validate()){
            $errors = $validator->errors();
        }

        if (empty($errors)){
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT );

            if (!$model->getTravelerByPhone($data['phone']))
            {
                $newUser = $model->create($data);
                $_SESSION['user'] = $newUser;

                return Tools::getResponseJSON($response, $newUser);
            }else{
                 $errors['phone'] = ['Traveler is exist'];
            }
        }

        return Tools::getResponseJSON($response, $errors)->withStatus(400);
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
            //TODO вынести проверку прав в другой обработчик (Middleware)
        }
        // return $response->withStatus(401);
        throw new HttpUnauthorizedException($request);
    }
}
