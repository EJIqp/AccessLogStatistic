<?php

namespace App\Kernel;

class Controller 
{
    protected $model;
    protected $response;
    
    public function __construct()
    {
        $this->response = new Response();
    }
    
    public function index(){}
}