<?php

include '../app/vendor/autoload.php';

use App\Lib\Router;
use App\Lib\Request;
use App\Lib\Response;

$request  = new Request();
$response  = new Response();

$router = new Router($request, $response);


$router->get('/', function (Request $request){
    echo (new \App\Services\VehicleImportService())->readFiles('')->toJson();
});

// Create a Vehicle.
$router->get('/car', function($request, $response) {
      (new App\Http\Controller\CarController())->index($request, $response);
});
$router->post('/car', function(Request $request, Response $response) {
      (new App\Http\Controller\CarController())->save($request, $response);
});

