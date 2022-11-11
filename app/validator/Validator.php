<?php

namespace app\validator;

class Validator
{
    private $errors = [];

    /**
     * Принимает именованный массив данных на валидацию и имена обязательных полей.
     */
    public function __construct(public array $inputData,
                                public array $requiredFields){}

    /**
     * Получение массива ошибок после валидации
     * @return array массив ошибок после валидации
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Проверка формы
     * @return bool возвращает true, если валидация прошла успешно. Иначе false
     */
    public function validate(): bool
    {
        foreach ($this->requiredFields as $field){
            $errors[$field] = $this->checkField($field);
        }

        $this->errors = array_filter($errors);

        return empty($this->errors);
    }

    /**
     * Проверка поля
     * @return null|string возвращает null при отсутствии ошибок либо строку с описанием ошибки
     */

    private function checkField($field): null|string
    {
        if (array_key_exists($field, $this->inputData)){
            if ($field === 'phone'){ return $this->validatePhone(); }
            if ($field === 'name_traveler'){ return $this->validateTravelerName(); }
            return null;
        }

        return 'This field is required';
    }

    //TODO создать конфиг правил валидации в модели

    private function validatePhone(): null|string
    {
        return preg_match('/^[\d]{11}/i', $this->inputData['phone']) ? null : 'No validate phone. Only 11 digits';
    }

    private function validateTravelerName(): null|string
    {
        if (preg_match('/^[A-Za-zА-Яа-я]/iu', $this->inputData['name_traveler']))
        {
            return $this->checkStringLength($this->inputData['name_traveler'], 2, 15) ? null : 'Diapason between 2 and 15 symbols';
        }
        return 'No validate traveler name. Only alphabet.';
    }

    private function checkStringLength(string $str, int $min, int $max): bool
    {
        if(strlen($str) >= $min && strlen($str) <= $max){
            return true;
        }
        return false;
    }
}
