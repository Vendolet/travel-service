<?php

namespace app\tools;

use Psr\Http\Message\ResponseInterface as Response;

class Tools
{
    /**
     * Возвращает ассоциативный массив
     * @param Psr\Http\Message\ServerRequestInterface объект запроса callback функции маршрута Slim4
     * @return array|null возвращает результат работы функции json_decode(?, true)
     */
    public static function getRequestContentBody($request): array|null
    {
        $inputJSON = $request->getBody();
        return json_decode($inputJSON, true);
    }

    /**
     * Возвращает объект Psr\Http\Message\ResponseInterface со статусом 200 по умолчанию,
     * заголовком "Content-Type" = "application/json", и телом $data в формате JSON.
     * @param Psr\Http\Message\ResponseInterface интерфейс объекта ответа callback функции маршрута Slim4
     * @param array $data именованный массив, который необходимо передать
     * @return \Psr\Http\Message\ResponseInterface
     */
    public static function getResponseJSON($response, $data): Response
    {
        $outputJSON = json_encode($data);
        $response->getBody()->write($outputJSON);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
