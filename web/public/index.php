<?php

include '../app/vendor/autoload.php';

use App\App;
use App\Http\Controller\CarController;
use App\Exceptions\MethodNotAllowedException;
use App\Exceptions\RouteNotFoundException;
use App\Exceptions\ValidationExceptions ;
use App\Lib\Response;

// Write Dedicated Error Handler.
//TODO:: Register Global Error Handler
//TODO:: Register Global Exception Handler
//TODO:: Register Global Depreciation Handler
//TODO:: Register Global Connection close Handler

try {
    $app = App::run();

    $app->get('/', function ($request, $response) {
        $cars = (new \App\Services\VehicleImportService())->readFiles('')->toJson();
        $response->setContent($cars)
            ->setHeaders([
                'Content-type' => 'application/json',
                'Additional-header' => 'Bla-bla'
            ])
            ->send(true);
    });

    $app->get('/cars', function ($request, $response) {
        (new CarController())->index($request, $response);
    });

// Create a Vehicle.
    $app->get('/cars/:num', function ($request, $response, $vehicleId) {
        (new CarController())->view($request, $response, $vehicleId);
    });

    $app->post('/cars', function ($request, $response) {
        (new CarController())->save($request, $response);
    });

    $app->resolve();
} catch (ValidationExceptions $validationExceptions) {
    (new Response())
        ->setContent(
            json_encode([
                'message' => $validationExceptions->getMessage(),
                'code' => $validationExceptions->getCode(),
            ],)
        )
        ->setHeaders([
            'Content-type' => 'application/json',
        ])
        ->setStatusCode($validationExceptions->getCode())
        ->send(true);
} catch (MethodNotAllowedException $methodNotAllowedException) {
    (new Response())
        ->setContent(
            json_encode([
                'message' => $methodNotAllowedException->getMessage(),
                'code' => $methodNotAllowedException->getCode(),
            ],)
        )
        ->setHeaders([
            'Content-type' => 'application/json',
        ])
        ->setStatusCode(\App\Lib\Response::METHOD_NOT_ALLOWED)
        ->send(true);
} catch (RouteNotFoundException $routeNotFoundException) {
    (new Response())
        ->setContent(
            json_encode([
                'message' => $routeNotFoundException->getMessage(),
                'code' => $routeNotFoundException->getCode(),
            ],)
        )
        ->setHeaders([
            'Content-type' => 'application/json',
        ])
        ->setStatusCode(Response::HTTP_NOT_FOUND)
        ->send(true);
} catch (\Exception $exception) {
    (new Response())
        ->setContent(
            json_encode([
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
            ],)
        )
        ->setHeaders([
            'Content-type' => 'application/json',
        ])
        ->setStatusCode(Response::HTTP_SERVER_ERROR)
        ->send(true);
}



