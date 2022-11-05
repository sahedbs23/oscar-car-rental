<?php

include '../app/vendor/autoload.php';

use App\App;
use App\Http\Controller\CarController;
use App\Exceptions\MethodNotAllowedException;
use App\Exceptions\RouteNotFoundException;
use App\Exceptions\ValidationExceptions;
use App\Exceptions\RecordNotFoundException;
use App\Lib\Response;
use App\Services\VehicleImportService;
use App\Services\VehicleService;

// Write Dedicated Error Handler.
//TODO:: Register Global Error Handler
//TODO:: Register Global Exception Handler
//TODO:: Register Global Depreciation Handler
//TODO:: Register Global Connection close Handler

try {
    $app = App::run();

    // Read cars from Data source
    $app->get('/read-files', function ($request, $response) {
        $cars = (new VehicleImportService())->readFiles('')->toJson();
        $response->setContent($cars)
            ->setHeaders([
                'Content-type' => 'application/json',
                'Additional-header' => 'Bla-bla'
            ])
            ->send(true);
    });

    // Import cars from data source.
    $app->post('/write-files', function ($request, $response) {
        (new VehicleImportService())->importDatFromSource('');
        $cars = (new VehicleService())->searchCar([], 30);
        $response->setContent(json_encode($cars, JSON_THROW_ON_ERROR))
            ->setHeaders([
                'Content-type' => 'application/json'
            ])
            ->send(true);
    });

    // Search Cars
    $app->get('/cars', function ($request, $response) {
        (new CarController())->index($request, $response);
    });

    // Read a specific Car
    $app->get('/cars/:num', function ($request, $response, $vehicleId) {
        (new CarController())->view($request, $response, $vehicleId);
    });

    // Create a Vehicle.
    $app->post('/cars', function ($request, $response) {
        (new CarController())->save($request, $response);
    });

    $app->resolve();
} catch (RecordNotFoundException $recordNotFoundException) {
    (new Response())
        ->setContent(
            json_encode([
                'message' => $recordNotFoundException->getMessage(),
                'code' => Response::HTTP_NOT_FOUND,
            ],)
        )
        ->setHeaders([
            'Content-type' => 'application/json',
        ])
        ->setStatusCode(Response::HTTP_NOT_FOUND)
        ->send(true);
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



