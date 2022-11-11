<?php

namespace app\model;

class Place extends Model
{
    /**
     * Получение всех записей
     * @return array найденные записи
     */
    public function getAll(): array|null
    {
       $result = $this->db->query('SELECT p.*, c.name_city FROM `place` as p JOIN `city` as c ON p.city_id = c.id');
       $data = $result->fetchAssocArray();

       return $data;
    }

    /**
     * Получить запись по ID
     * @param int $id ID достопримечательности
     * @return array найденная запись
     */
    public function getByID(int $id): array|null
    {
        $result = $this->db->query('SELECT p.*, c.name_city FROM `place` as p JOIN `city` as c ON p.city_id = c.id WHERE p.id =  ?i ', $id);
        $data = $result->fetchAssoc();

        return $data;
    }
}
