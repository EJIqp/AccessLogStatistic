<?php

namespace App\Helpers;

class ArrayService
{   
    /**
     * Проверка наличия строки в массиве
     * @param  string       $needle   искомое значение
     * @param  string       $haystack массив в котором ищем
     * @param  bool|boolean $strict   соответствие типов
     * @return bool                   true - если найден, false - если нет
     */
    public static function inArray(string $needle , string $haystack, bool $strict = FALSE): bool{   
        $result = in_array($needle,$haystack,$strict);
        
        return (bool)$result;
    }
}