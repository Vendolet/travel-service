<?php

namespace app\model;

class Place extends Model
{
    /**
     * Выполняет настройку валидации объекта библиотеки Valitron\Validator создания новой сущности
     * @param Valitron\Validator $validator объект с данными валидации
     */
    public function getRulesFilter($validator)
    {
        $validator->rule('required', 'city');
        $validator->rule('array', 'city');

        $validator->rule('required', 'distance');
        $validator->rule('in', 'distance', [ 'max', 'min', '0']);

        $validator->rule('required', 'rank');
        $validator->rule('in', 'rank', [ 'max', 'min', '0']);
    }

    /**
     * Получение всех записей
     * @return array найденные записи
     */
    public function getAll(): array
    {
       $result = $this->db->query("SELECT p.*, c.name_city FROM `place` as p
                                        JOIN `city` as c ON p.city_id = c.id");
       return $result->fetchAssocArray();
    }

    /**
     * Получение всех записей по переданному фильтру, поддерживаемому моделью
     * @param array $filter
     * @return array найденные записи
     */
    public function getFilterAll($filter): array
    {
        $strRequest = '';
        $orderRank = '';
        $orderDistance = '';

        if(empty($filter['city'])){
            $strRequest = "SELECT p.*, c.name_city FROM `place` as p
                            JOIN `city` as c ON p.city_id = c.id";
        }else{
            $strRequest = "SELECT p.*, c.name_city FROM `place` as p
                                JOIN `city` as c ON p.city_id = c.id
                                    WHERE p.city_id IN (?ai)";
        }
        //TODO подумать над циклом для перебора полей сортировки
        if ($filter['rank'] || $filter['distance']){
            $strRequest = $strRequest . ' ORDER BY';
        }

        if ($filter['rank']){
            $orderRank = $filter['rank'] === 'max' ? 'DESC' : 'ASC';
            $strRequest = $strRequest . " p.rank $orderRank";
        }

        if ($filter['distance']){
            if ($filter['rank']){
                $strRequest = $strRequest . ",";
            }
            $orderDistance = $filter['distance'] === 'max' ? 'DESC' : 'ASC';
            $strRequest = $strRequest . " p.distance $orderDistance";
        }

        $result = $this->db->query($strRequest, $filter['city']);
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
     * Возвращает данные достопримечательностей нужного города
     * @param int $id ID города
     * @return array возвращает массив данных достопримечательностей
     */
    public function getPlaceOfCity(int $id): array
    {
        $result = $this->db->query("SELECT * FROM `place`
                                        WHERE city_id =  ?i", $id);
        return $result->fetchAssocArray();
    }

    /**
     * Обновить поле рейтинга записи
     * @param int $id ID достопримечательности
     */
    public function updateRank(int $id):void
    {
        $this->db->query("UPDATE `place`
                            SET `rank` = IFNULL((SELECT AVG(score) FROM `score` WHERE `place_id` = ?i), 0)
                                WHERE `id` = ?i", $id, $id);
    }
}
