<?php

namespace app\model;

class Traveler extends Model
{
    /**
     * Выполняет настройку валидации объекта библиотеки Valitron\Validator создания новой сущности
     * @param Valitron\Validator $validator объект с данными валидации
     */
    public function getRulesCreate($validator): void
    {
        $validator->rule('required', 'phone');
        $validator->rule('regex', 'phone', '/^[\d]{11}/i');
        $validator->rule('regex', 'name_traveler', '/^[A-Za-zА-Яа-я]+$/iu');
        $validator->rule('required', 'password');
    }
    /**
     * Выполняет настройку валидации объекта библиотеки Valitron\Validator авторизации пользователя
     * @param Valitron\Validator $validator объект с данными валидации
     */
    public function getRulesLogin($validator): void
    {
        $validator->rule('required', 'phone');
        $validator->rule('regex', 'phone', '/^[\d]{11}/i');
        $validator->rule('required', 'password');
    }
    /**
     * Получение всех записей (без телефонов)
     * @return array|null найденные записи
     */
    public function getAll(): array|null
    {
       $result = $this->db->query('SELECT `id`, `name_traveler` FROM traveler');
       return $result->fetchAssocArray();
    }
    /**
     * Получить запись по ID (без телефона)
     * @param int $id ID путешественника
     * @return array найденная запись
     */
    public function getByID(int $id): array|null
    {
        $result = $this->db->query("SELECT `id`, `name_traveler` FROM traveler WHERE `id` =  ?i", $id);
        return $result->fetchAssoc();
    }

    /**
     * Возвращает данные пользователя по номеру телефона (в том числе телефон)
     * @param string $phone телефон пользователя
     * @return array|null возвращает массив данных путешественника или Null, если не найден
     */
    public function getTravelerByPhone(string $phone): array|null
    {
        $result = $this->db->query('SELECT `id`, `name_traveler`, `phone` FROM traveler WHERE `phone` = "?s"', $phone);
        return $result->fetchAssoc();
    }

    /**
     * Создать новую запись
     * @param array $data именованный массив c данными 'name', 'phone', 'password'
     * @return array созданный пользователь
     */
    public function create(array $data)
    {
        $this->db->query('INSERT INTO `traveler` SET ?A["?s", "?s", "?s"];', $data);
        $result = $this->db->query('SELECT `id`, `name_traveler`, `phone` FROM `traveler` WHERE `id` =  LAST_INSERT_ID()');
        //TODO объединить запрос
        return $result->fetchAssoc();
    }

    /**
     * Проверяет правильность переданного пароля
     * @param string $phone телефон пользователя
     * @return bool возвращает true, если пароль верный. Иначе - false
     */
    public function isVerifyPassword(string $phone, string $password): bool
    {
        $result = $this->db->query('SELECT `password` FROM traveler WHERE `phone` = "?s"', $phone);
        $passwordHash = $result->getOne();
        return password_verify($password, $passwordHash);
    }
}
