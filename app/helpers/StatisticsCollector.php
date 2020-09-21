<?php

namespace App\Helpers;

class StatisticsCollector
{   
    /**
     * Статистика по файлу логов
     * @var array
     */
    private $result = [
        'views' => 0,
        'urls' => 0,
        'traffic' => 0,
        'crawlers' => [
            'Google' => 0,
            'Bing' => 0,
            'Baidu' => 0,
            'Yandex' => 0,
        ],
        'statusCodes' => [],
    ];

    /**
     * Коллекция уникальных url из лога запросов
     * @var array
     */
    private $uniqueUrls = [];

    /**
     * Добавление строки лога к статистике
     * @param array $logLine Строка лога
     */
    public function add(array $LogEntry): bool{   
        
        $this->result['views']++;
        $this->setUrls($LogEntry['path']);
        return true;
    }

    /**
     * Сбор количества уникальных urls
     * @param string $url  Строка url
     */
    public function setUrls(string $url): void{   
        
        $startPosOfUrlQueryParams = StringService::strrpos($url,'?');
        $baseURL = StringService::substr($url,0,$startPosOfUrlQueryParams);

        if( !ArrayService::inArray($baseURL,$this->uniqueUrls) ){
            $this->uniqueUrls[] = $baseURL;
            $this->result['urls'] = count($this->uniqueUrls);
        }
    }


    /**
     * Метод возвращает собранную статистику
     * @return array накопленная статистика
     */
    public function statistics(): array{
        return $this->result;
    }
}