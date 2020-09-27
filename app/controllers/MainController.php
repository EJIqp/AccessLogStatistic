<?php

namespace App\Controllers;

use App\Kernel\Controller;
use App\Helpers\StatisticsCollector;
use App\Models\AccessLogFile;

class MainController extends Controller
{
    /**
     * @var AccessLogFile Объект, содержащий свойства и методы по работе с файлом access.log
     */
    private AccessLogFile $accessLogFile;
    /**
     * @var StatisticsCollector Сборщик статистики
     */
    private StatisticsCollector $statisticsCollector;

    public function __construct()
    {
        parent::__construct();

        $this->accessLogFile = new AccessLogFile();
        $this->statisticsCollector = new StatisticsCollector();
    }

    public  function index(){

        if( array_key_exists('argv',$_SERVER) && array_key_exists(1,$_SERVER['argv'])){
            $accessLogFilePath = $_SERVER['argv'][1];
        }else{
            return $this->response->json(['Error' => 'Invalid file path']);
        }   


        if($this->accessLogFile->isFileExists($accessLogFilePath)){
            $this->accessLogFile->openFile($accessLogFilePath);
        }else{
            return $this->response->json(['Error' => 'File not found']);
        }

        if(!$this->accessLogFile->isFileOpen()){
            return $this->response->json(['Error' => 'File did not open']);
        }

        while ( $this->accessLogFile->canBeRead() ) {
        	$currentLogEntry = $this->accessLogFile->readNextLine()->logLineToArray();
           
            if(count($currentLogEntry) > 0){
                $this->statisticsCollector
                    ->addView()
                    ->addUrl($currentLogEntry['path'])
                    ->addCrawler($currentLogEntry['agent'])
                    ->addStatusCode($currentLogEntry['status']);

                if( (int)$currentLogEntry['status'] < 300 || (int)$currentLogEntry['status'] >= 400 ){
                    $this->statisticsCollector->addTraffic($currentLogEntry['bytes']);
                }
            }
        }
        
        $statistics = $this->statisticsCollector->statistics();

        return $this->response->json($statistics);
    }
}