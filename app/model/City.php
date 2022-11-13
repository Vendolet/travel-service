<?php

namespace app\model;

class City extends Model
{
    /**
     * Выполняет настройку валидации объекта библиотеки Valitron\Validator создания новой сущности
     * @param Valitron\Validator $validator объект с данными валидации
     */
    public function getRulesCreate($validator): void
    {
        $validator->rule('required', 'name_city');
        $validator->rule('regex', 'name_city', '/^[A-Za-zА-Яа-я]+$/iu');
    }
    /**
     * Выполняет настройку валидации объекта библиотеки Valitron\Validator
     * @param Valitron\Validator $validator объект с данными валидации
     */
    public function getRules($validator): void
    {
        $validator->rule('required', 'id');
        $validator->rule('integer', 'id');
        $validator->rule('min', 'id', 1);
    }
    /**
     * Получение всех записей (без телефонов)
     * @return array|null найденные записи
     */
    public function getAll(): array|null
    {
       $result = $this->db->query('SELECT * FROM city');
       return $result->fetchAssocArray();
    }
    /**
     * Получить запись по ID (без телефона)
     * @param int $id ID города
     * @return array найденная запись
     */
    public function getByID(int $id): array|null
    {
        $result = $this->db->query("SELECT * FROM city WHERE `id` =  ?i", $id);
        return $result->fetchAssoc();
    }

    /**
     * Получение всех городов, посещённых пользователем
     * @param int $id ID путешественника
     * @return array|null найденные записи
     */
    public function getAllByTravelerID(int $id): array
    {
        $result = $this->db->query("SELECT DISTINCT c.* FROM city as c
                                        JOIN place as p ON p.city_id = c.id
                                        JOIN score as s ON s.place_id = p.id
                                            WHERE s.traveler_id =  ?i", $id);
        return $result->fetchAssocArray();
    }
}
