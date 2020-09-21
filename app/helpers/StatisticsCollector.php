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
        $this->addView();
        $this->addUrl($LogEntry['path']);
        $this->addTraffic((int)$LogEntry['bytes']);
        $this->addCrawler($LogEntry['agent']);
        $this->addStatusCode($LogEntry['status']);
        return true;
    }

    /**
     * добавляем новые просмотры
     */
    public function addView(): void{   
        $this->result['views']++;
    }

    /**
     * Сбор количества уникальных urls
     * @param string $url  Строка url
     */
    public function addUrl(string $url): void{   
        
        $startPosOfUrlQueryParams = StringService::strrpos($url,'?');
        $baseURL = StringService::substr($url,0,$startPosOfUrlQueryParams);

        if( !ArrayService::inArray($baseURL,$this->uniqueUrls) ){
            $this->uniqueUrls[] = $baseURL;
            $this->result['urls'] = count($this->uniqueUrls);
        }
    }

    /**
     * Накопление траффика
     * @param int $traffic  Количество переданных бит трафика
     */
    public function addTraffic(int $traffic): void{   
        $this->result['traffic'] += $traffic;
    }

    /**
     * Добавляем поисковые боты к статистике
     * @param string $agent  User agent
     */
    public function addCrawler(string $agent): void{   
        
        $botName = BotService::botName($agent);

        if( $botName !== null && isset($this->result['crawlers'][$botName])) {
            $this->result['crawlers'][$botName]++;
        }
    }

    /**
     * Добавляем коды статусов запросов
     * @param string $statusCode  Код статуса
     */
    public function addStatusCode(string $statusCode): void{   
        if( $statusCode !== ''){
            if( isset($this->result['statusCodes'][$statusCode]) ){
                $this->result['statusCodes'][$statusCode]++;
            }else{
                $this->result['statusCodes'][$statusCode] = 1;
            }
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