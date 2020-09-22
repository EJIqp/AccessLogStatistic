<?php

namespace App\Controllers;

use App\Kernel\Controller;
use App\Helpers;
use App\Models\AccessLogFile;

class MainController extends Controller
{    
    public function __construct()
    {
        parent::__construct();

        $fileService = new Helpers\FileService();
        $this->accessLogFile = new AccessLogFile($fileService);

        $this->statisticsCollector = new Helpers\StatisticsCollector();
    }

    public  function index(){

        if( array_key_exists('argv',$_SERVER) && array_key_exists(1,$_SERVER['argv'])){
            $accessLogFilePath = $_SERVER['argv'][1];
        }else{
            return $this->response->json(['Error' => 'Invalid file path']);
        }   


        if($this->accessLogFile->fileExists($accessLogFilePath)){
            $this->accessLogFile->openFile($accessLogFilePath);
        }else{
            return $this->response->json(['Error' => 'File not found']);
        }

        while ( $this->accessLogFile->canBeRead() ) {
        	$currentLogEntry = $this->accessLogFile->readNextLine()->logInArray();
           
            if(count($currentLogEntry) > 0){
                $this->statisticsCollector
                    ->addView()
                    ->addUrl($currentLogEntry['path'])
                    ->addTraffic($currentLogEntry['bytes'])
                    ->addCrawler($currentLogEntry['agent'])
                    ->addStatusCode($currentLogEntry['status']);
            }
        }
        
        $statistics = $this->statisticsCollector->statistics();

        return $this->response->json($statistics);
    }
}