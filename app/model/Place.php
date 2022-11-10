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

    /**
     * Получить запись по ID
     * @return array найденная запись
     */
    public function getByID($id)
    {
        $result = $this->db->query('SELECT * FROM place JOIN `city` WHERE `place`.`id` =  ?i ', $id);
        $data = $result->fetchAssoc();

        return $data;
    }
}
