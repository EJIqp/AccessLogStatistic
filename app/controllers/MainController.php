<?php

namespace App\Controllers;

use App\Kernel\Controller;
use App\Helpers\FileService;
use App\Models\AccessLogFile;

class MainController extends Controller
{	
	public function __construct()
    {
        parent::__construct();
        $fileService = new FileService();
        $this->accessLogFile = new AccessLogFile($fileService);
    }

    public  function index(){ 
    	$this->accessLogFile->openFile('./access.log');

    	$tmp = '';
    	$currentLogLine = $this->accessLogFile->readLine();
    	while ( $currentLogLine !== null ) {
    		$tmp .= $currentLogLine;
    		$currentLogLine = $this->accessLogFile->readLine();
    	}

    	

      $this->response->json([$tmp]);
    }
}