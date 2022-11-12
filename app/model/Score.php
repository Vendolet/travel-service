<?php

namespace app\model;

class Score extends Model
{
    /**
     * Выполняет настройку валидации объекта библиотеки Valitron\Validator создания новой сущности
     * @param Valitron\Validator $validator объект с данными валидации
     */
    public function getRulesCreate($validator): void
    {
        $validator->rule('required', 'score');
        $validator->rule('integer', 'score');
        $validator->rule('min', 'score', 1);
        $validator->rule('max', 'score', 10);

        $validator->rule('required', 'place_id');
        $validator->rule('integer', 'place_id');
        $validator->rule('min', 'place_id', 1);

        $validator->rule('required', 'traveler_id');
        $validator->rule('integer', 'traveler_id');
        $validator->rule('min', 'traveler_id', 1);
    }

    /**
     *Выполняет настройку валидации объекта библиотеки Valitron\Validator обновления сущности
     * @param Valitron\Validator $validator объект с данными валидации
     */
    public function getRulesUpdate($validator): void
    {
        $validator->rule('required', 'score');
        $validator->rule('min', 'score', 1);
        $validator->rule('max', 'score', 10);

        $validator->rule('required', 'id');
        $validator->rule('integer', 'id');
        $validator->rule('min', 'id', 1);
    }

    /**
     *Выполняет настройку валидации объекта библиотеки Valitron\Validator обновления сущности
     * @param Valitron\Validator $validator объект с данными валидации
     */
    public function getRulesDelete($validator): void
    {
        $validator->rule('required', 'id');
        $validator->rule('integer', 'id');
        $validator->rule('min', 'id', 1);
    }

    /**
     * Получить все записи оценок
     * @return array|null найденные записи
     */
    public function getAll(): array|null
    {
       $result = $this->db->query("SELECT `id`, `score`, `traveler_id`, `place_id`
                                      FROM score as s
                                        JOIN place as p ON p.id = s.place_id
                                        JOIN traveler as t ON t.id = s.traveler_id");
       return $result->fetchAssocArray();
    }

    /**
     * Получить запись по ID
     * @param int $id ID оценки
     * @return array найденная запись
     */
    public function getByID(int $id): array|null
    {
        $result = $this->db->query("SELECT `id`, `score`, `traveler_id`, `place_id`
                                        FROM score
                                            WHERE `id` =  ?i", $id);
        return $result->fetchAssoc();
    }

    /**
     * Получение всех оценок достопримечательности
     * @param int $id ID достопримечательности
     * @return array|null найденные записи
     */
    public function getByPlaceID(int $id): array
    {
        $result = $this->db->query("SELECT s.*, t.name_traveler
                                    FROM score as s
                                        JOIN traveler as t ON t.id = s.traveler_id
                                            WHERE `place_id` =  ?i", $id);
        return $result->fetchAssocArray();
    }

    /**
     * Получение всех оценок пользователя
     * @param int $id ID путешественника
     * @return array|null найденные записи
     */
    public function getByTravelerID(int $id): array
    {
        $result = $this->db->query("SELECT s.*, p.name_place, p.rank, p.city_id, c.name_city
                                    FROM score as s
                                        JOIN place as p ON p.id = s.place_id
                                        JOIN city as c ON p.city_id = c.id
                                            WHERE `traveler_id` =  ?i", $id);
        return $result->fetchAssocArray();
    }

    /**
     * Проверить, что оценка существует
     * @param int $placeID ID достопримечательности
     * @param int $travelerID ID путешественника
     * @return array|null найденная запись или null
     */
    public function isExist(int $placeID, int $travelerID): array|null
    {
        $result = $this->db->query("SELECT * FROM score
                                    WHERE place_id = ?i AND traveler_id = ?i",  $placeID, $travelerID);
        return $result->fetchAssoc();
    }

    /**
     * Создать новую запись оценки
     * @param array $data именованный массив c обязательными данными для записи
     */
    public function create(array $data): void
    {
        $this->db->query('INSERT INTO `score` SET ?A["?s", "?s", "?s"];', $data);
    }

    /**
     * Обновить оценку записи
     * @param int $id ID записи
     * @param int $score новая оценка
     */
    public function update(int $id, int $score): void
    {
        $this->db->query('UPDATE `score` SET score = ?i WHERE id = ?i', $score, $id);
    }

    /**
     * Удалить оценку записи
     * @param int $id ID записи
     * @param int $score новая оценка
     */
    public function delete(int $id): void
    {
        $this->db->query('DELETE FROM `score` WHERE id = ?i', $id);
    }
}
