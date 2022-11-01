<?php

include '../app/vendor/autoload.php';

use App\Lib\Router;
use App\Lib\Request;


$router = new Router(new Request());


$router->get('/', function (){
    header('Content-type:Application/json');
    echo (new \App\Services\VehicleImportService())->readFiles('')->toJson();
});

// Create a Vehicle.
$router->post('/car', function($request) {
    return  (new App\Http\Controller\CarController())->save($request);
});

