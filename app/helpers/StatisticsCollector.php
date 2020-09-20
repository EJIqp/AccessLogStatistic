<?php

namespace App\Helpers;

class StatisticsCollector
{   
    /**
     * Статистика по файлу логов
     * @var array
     */
    private $result = [];

    /**
     * Добавление строки лога к статистике
     * @param string $logLine Строка лога
     */
    public function add(string $logLine): bool{   
        
        $this->result[] = $logLine;
        
        return true;
    }


    /**
     * Метод возвращает собранную статистику
     * @return array накопленная статистика
     */
    public function statistics(): array{   
        return $this->result;
    }
}