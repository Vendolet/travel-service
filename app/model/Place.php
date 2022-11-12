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
       return $result->fetchAssocArray();
    }

    /**
     * Получить запись по ID
     * @param int $id ID достопримечательности
     * @return array найденная запись
     */
    public function getByID(int $id): array|null
    {
        $result = $this->db->query("SELECT p.*, c.name_city FROM `place` as p
                                        JOIN `city` as c ON p.city_id = c.id
                                            WHERE p.id =  ?i ", $id);
        return $result->fetchAssoc();
    }

    /**
     * Обновить поле рейтинга записи
     * @param int $id ID достопримечательности
     */
    public function updateRank(int $id){
        $this->db->query("UPDATE `place`
                            SET `rank` = (SELECT AVG(score) FROM `score` WHERE `place_id` = ?i)
                                WHERE `id` = ?i", $id, $id);
    }
}
