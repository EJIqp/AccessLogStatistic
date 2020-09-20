<?php 
require_once './app/autoload.php';
require_once './app/route.php';

ini_set('display_errors', 1);
Autoload::run();
Route::run();