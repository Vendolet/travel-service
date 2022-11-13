<?php

namespace app\controller;

use app\model\Place;
use app\model\Score;
use app\tools\Tools;
use Valitron\Validator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;

class ScoreController extends Controller
{
    /**
     * Возвращает список оценок по запросу
     * @return ResponseInterface
     */
    public function findAll(Request $request, Response $response): Response
    {
        $model = new Score($this->conn);
        $data = $model->getAll();

        return Tools::getResponseJSON($response, $data);
    }

    /**
     * Возвращает данные об путешественнике по GET запросу
     * @return ResponseInterface
     */
    public function findOne(Request $request, Response $response): Response
    {
        $model = new Score($this->conn);

        $route = RouteContext::fromRequest($request)->getRoute();
        $modelID = $route->getArgument('id');
        //TODO валидация данных

        $data = $model->getByID($modelID);
        //TODO список посещенных городов и оценок достопримечательностей
        return Tools::getResponseJSON($response, $data);
    }

    /**
     * Создание записи новой оценки
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function create(Request $request, Response $response): Response
    {
        if (!$this->isAuth){
            return $response->withStatus(403);
            //TODO вынести проверку прав в другой обработчик
        }

        $modelScore = new Score($this->conn);
        $modelPlace = new Place($this->conn);

        $data = Tools::getRequestContentBody($request);
        $data['traveler_id'] = $_SESSION['user']['id'];

        $validator = new Validator($data);
        $modelScore->getRulesCreate($validator);

        if ($validator->validate()){
            $score = $modelScore->isExist($data['place_id'], $data['traveler_id']);
            if (!$score){
                if ($modelPlace->getByID($data['place_id']))
                {
                    $modelScore->create($data);
                    $modelPlace->updateRank($data['place_id']);
                    //TODO перенести действие получения данных для страницы
                    $placeData = $modelPlace->getByID($data['place_id']);
                    $scorePlaceData = $modelScore->getByPlaceID($data['place_id']);

                    $data = ['place' => $placeData, 'score' => $scorePlaceData];
                    return Tools::getResponseJSON($response, $data);
                }else{
                    $errors['place_id'] = 'This place is not exist';
                    return Tools::getResponseJSON($response, $errors)->withStatus(400);
                }
            }else{
                $errors['scoreIsExist'] = 'This place already has scored of this user';
                return Tools::getResponseJSON($response, $errors)->withStatus(400);
            }
        }
        return Tools::getResponseJSON($response, $validator->errors())->withStatus(400);
    }

    /**
     * Изменение выставленной оценки
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function update(Request $request, Response $response): Response
    {
        if (!$this->isAuth){
            return $response->withStatus(403);
            //TODO вынести проверку прав в другой обработчик
        }

        $modelPlace = new Place($this->conn);
        $modelScore = new Score($this->conn);
        $data = Tools::getRequestContentBody($request);

        $validator = new Validator($data);
        $modelScore->getRulesUpdate($validator);

        if ($validator->validate()){
            $score = $modelScore->getByID($data['id']);

            if ($score){
                if ($score['traveler_id'] !== $_SESSION['user']['id'])
                {
                    $errors['traveler_id'] = 'This traveler is not creater';
                    return Tools::getResponseJSON($response, $errors)->withStatus(403);
                }

                $modelScore->update($score['id'], $data['score']);
                $modelPlace->updateRank($score['place_id']);

                //TODO перенести действие получения данных для страницы
                $scoreOfTraveler = $modelScore->getByTravelerID($_SESSION['user']['id']);

                $data = ['scoreOfTraveler' => $scoreOfTraveler];
                return Tools::getResponseJSON($response, $data);
            }else{
                $errors['score_id'] = 'This score is not exist';
                return Tools::getResponseJSON($response, $errors)->withStatus(400);
            }
        }
        return Tools::getResponseJSON($response, $validator->errors())->withStatus(400);
    }

    public function delete(Request $request, Response $response): Response
    {
        if (!$this->isAuth){
            return $response->withStatus(403);
            //TODO вынести проверку прав в другой обработчик
        }

        $modelPlace = new Place($this->conn);
        $modelScore = new Score($this->conn);
        $data = Tools::getRequestContentBody($request);

        $validator = new Validator($data);
        $modelScore->getRulesDelete($validator);

        if ($validator->validate()){
            $score = $modelScore->getByID($data['id']);

            if ($score){
                if ($score['traveler_id'] !== $_SESSION['user']['id'])
                {
                    $errors['traveler_id'] = 'This traveler is not creater';
                    return Tools::getResponseJSON($response, $errors)->withStatus(403);
                }

                $modelScore->delete($score['id']);
                $modelPlace->updateRank($score['place_id']);

                //TODO перенести действие получения данных для страницы
                $scoreOfTraveler = $modelScore->getByTravelerID($_SESSION['user']['id']);

                return Tools::getResponseJSON($response, ['scoreOfTraveler' => $scoreOfTraveler]);
            }else{
                $errors['score_id'] = 'This score is not exist';
                return Tools::getResponseJSON($response, $errors)->withStatus(400);
            }
        }
        return Tools::getResponseJSON($response, $validator->errors())->withStatus(400);
    }
}
