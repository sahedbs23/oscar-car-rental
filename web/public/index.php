<?php

include '../app/vendor/autoload.php';

use App\App;

$app = App::run();

//$app->get('/', function ($request){
//    echo (new \App\Services\VehicleImportService())->readFiles('')->toJson();
//});

$app->get('/cars', function ($request, $response){
    (new \App\Http\Controller\CarController())->index($request, $response);
});

// Create a Vehicle.
$app->get('/cars/:num', function( $request,  $response, $vehicleId) {
      (new \App\Http\Controller\CarController())->view($request, $response, $vehicleId);
});

$app->post('/cars', function( $request,  $response) {
      (new \App\Http\Controller\CarController())->save($request, $response);
});

