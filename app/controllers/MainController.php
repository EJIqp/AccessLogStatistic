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
        $this->accessLogFile->openFile('./access.log');
        
        
        while ( $this->accessLogFile->canBeRead() ) {
        	$currentLogEntry = $this->accessLogFile->readNextLine()->logInArray();
            
            $this->statisticsCollector
                ->addView()
                ->addUrl($currentLogEntry['path'])
                ->addTraffic($currentLogEntry['bytes'])
                ->addCrawler($currentLogEntry['agent'])
                ->addStatusCode($currentLogEntry['status']);
        }

        
        $statistics = $this->statisticsCollector->statistics();
        
        return $this->response->json($statistics);
    }
}