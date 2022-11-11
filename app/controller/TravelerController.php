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

    /**
     * Регистрация нового пользователя (путешественника)
     * @return ResponseInterface JSON ответ со списком ошибок в случае неудачи
     * или JSON ответ с новым пользователем
     */
    public function signup(Request $request, Response $response): Response
    {
        if ($this->isAuth){
            return $response->withStatus(403);
            //TODO вынести проверку прав в другой обработчик
        }
        $errors = [];

        $model = new Traveler($this->conn);
        $data = Tools::getRequestContentBody($request);

        $validator = new Validator($data, $model->getRequiredFieldsCreate());

        if (!$validator->validate()){
            $errors = $validator->getErrors();
        }

        if (empty($errors)){
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT );

            if (!$model->getTravelerByPhone($data['phone']))
            {
                $newUser = $model->create($data);
                $_SESSION['user'] = $newUser;

                return Tools::getResponseJSON($response, $newUser);
            }else{
                 $errors['travelerIsExist'] = true;
            }
        }

        return Tools::getResponseJSON($response, $errors)->withStatus(400);
    }
}
