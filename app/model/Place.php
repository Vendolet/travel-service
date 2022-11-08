<?php

namespace app\model;

class Place extends Model
{
    /**
     * Получение всех записей
     * @return array найденные записи
     */
    public function getAll()
    {
       $result = $this->db->query('SELECT * FROM place');
       $data = $result->fetchAssocArray();

       return $data;
    }
}
