<?php

namespace app\model;

class Traveler extends Model
{
    /**
     * Возвращает обязательные поля для создания записи
     * @return array наименование столбцов таблицы
     */
    public function getRequiredFieldsCreate()
    {
        return ['name_traveler', 'phone', 'password'];
    }
    /**
     * Возвращает обязательные поля для авторизации
     * @return array наименование столбцов таблицы
     */
    public function getRequiredFieldsLogin()
    {
        return ['phone', 'password'];
    }
    /**
     * Получение всех записей
     * @return array найденные записи
     */
    public function getAll()
    {
       $result = $this->db->query('SELECT `id`, `name_traveler` FROM traveler');
       $data = $result->fetchAssocArray();

       return $data;
    }
    /**
     * Получить запись по ID
     * @return array найденная запись
     */
    public function getByID($id)
    {
        $result = $this->db->query('SELECT `id`, `name_traveler` FROM traveler WHERE `id` =  ?i', $id);

        return $result->fetchAssoc();
    }

    /**
     * Создать новую запись
     * @param array $data именованный массив c данными 'name', 'phone', 'password'
     * @return array созданный пользователь
     */
    public function create($data)
    {
        $this->db->query('INSERT INTO `traveler` SET ?A["?s", "?s", "?s"];', $data);
        $result = $this->db->query('SELECT `id`, `name_traveler` FROM `traveler` WHERE `id` =  LAST_INSERT_ID()');
        //TODO объединить запрос
        return $result->fetchAssoc();
    }

    /**
     * Возвращает данные пользователя по номеру телефона
     * @param string $phone телефон пользователя
     * @return array|null возвращает массив данных путешественника или Null, если не найден
     */
    public function getTravelerByPhone($phone): array|null
    {
        $result = $this->db->query('SELECT `id`, `name_traveler`, `phone` FROM traveler WHERE `phone` = "?s"', $phone);
        return $result->fetchAssoc();
    }

    /**
     * Проверяет правильность переданного пароля
     * @param string $phone телефон пользователя
     * @return bool возвращает true, если пароль верный. Иначе - false
     */
    public function isVerifyPassword($phone, $password): bool
    {
        $result = $this->db->query('SELECT `password` FROM traveler WHERE `phone` = "?s"', $phone);
        $passwordHash = $result->getOne();
        return password_verify($password, $passwordHash);
    }
}
