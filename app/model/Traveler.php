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
     * Проверить существование записи
     * @param string $phone телефон пользователя
     * @return int|bool true, если найдено
     */
    public function isExistTraveler($phone): bool
    {
        $result = $this->db->query('SELECT EXISTS (SELECT * FROM traveler WHERE `phone` = "?s")', $phone);

        return $result->getOne();
    }
}
