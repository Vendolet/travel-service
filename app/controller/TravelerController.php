<?php

namespace app\controller;

use app\model\Traveler;
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

        $dataJson = json_encode($data);

        $response->getBody()->write($dataJson);
        return $response->withHeader('Content-Type', 'application/json');
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
        $dataJson = json_encode($data);

        //TODO список посещенных городов и оценок достопримечательностей

        $response->getBody()->write($dataJson);
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * Регистрация нового пользователя (путешественника)
     * @return ResponseInterface JSON ответ со списком ошибок в случае неудачи
     * или JSON ответ с новым пользователем
     */
    public function signup(Request $request, Response $response): Response
    {
        $model = new Traveler($this->conn);

        $inputJSON = $request->getBody();
        $data = json_decode($inputJSON, true);
        $validator = new Validator($data, $model->getRequiredFieldsCreate());

        $errors = [];

        if (!$validator->validate()){
            $errors = $validator->getErrors();
        }

        if (empty($errors)){
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT );

            if (!$model->isExistTraveler($data['phone']))
            {
                $newUser = $model->create($data);

                $_SESSION['user'] = $newUser;

                $outputJSON = json_encode($newUser);

                $response->getBody()->write($outputJSON);
                return $response->withHeader('Content-Type', 'application/json');
            }else{
                 $errors['travelerIsExist'] = true;
            }
        }

        $outputJSON = json_encode($errors);

        $response->getBody()->write($outputJSON);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
}
