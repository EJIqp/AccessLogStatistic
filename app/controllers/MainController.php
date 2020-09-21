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
        $this->statisticsCollector = new Helpers\StatisticsCollector();
        $this->accessLogFile = new AccessLogFile($fileService);
    }

    public  function index(){ 
        $this->accessLogFile->openFile('./access.log');
        
        
        while ( $this->accessLogFile->canBeRead() ) {
        	$currentLogEntry = $this->accessLogFile->readNextLine()->logInArray();
            $this->statisticsCollector->add($currentLogEntry);
        }

        
        $statistics = $this->statisticsCollector->statistics();
        $this->response->json($statistics);
    }
}