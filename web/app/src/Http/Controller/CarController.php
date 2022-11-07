<?php

namespace App\Http\Controller;

use App\Exceptions\ValidationExceptions;
use App\Lib\Request;
use App\Lib\Response;
use App\Services\VehicleService;
use App\Validation\Validator;
use JsonException;
use App\Exceptions\InvalidRuleException;
use App\Exceptions\RecordNotFoundException;

class CarController
{
    /**
     * @var VehicleService
     */
    public VehicleService $service;


    public function __construct()
    {
        $this->service = new VehicleService();
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function index(Request $request, Response $response): Response
    {
        $res = $this->service->searchCar($request->getParams());
        $response->setContent(json_encode($res))
            ->setHeaders([
                'Content-type' => 'application/json'
            ]);
        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param mixed $id
     * @return Response
     * @throws RecordNotFoundException
     */
    public function view(Request $request, Response $response, mixed $id): Response
    {
        $res = $this->service->find($id);
        return $response->setContent(json_encode($res))
            ->setHeaders([
                'Content-type' => 'application/json'
            ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws ValidationExceptions
     * @throws InvalidRuleException
     * @throws RecordNotFoundException
     */
    public function save(Request $request, Response $response): Response
    {
        $input = $request->getBody();

        $rules = [
            'location' => ['required', 'string'],
            'car_brand' => ['required', 'string'],
            'car_model' => ['required', 'string'],
            'license_plate' => ['required', 'string'],
            'car_year' => ['required', 'numeric'],
            'number_of_doors' => ['required', 'numeric'],
            'number_of_seats' => ['required', 'numeric'],
            'car_km' => ['required', 'numeric'],
            'fuel_type' => ['required', 'string'],
            'transmission' => ['required', 'string'],
            'car_type_group' => ['required', 'string'],
            'car_type' => ['required', 'string'],
        ];

        if (array_key_exists('inside_height', $input) && !empty($input['inside_height'])) {
            $rules['inside_height'] = ['numeric'];
        }

        if (array_key_exists('inside_length', $input) && !empty($input['inside_length'])) {
            $rules['inside_length'] = ['numeric'];
        }
        if (array_key_exists('inside_width', $input) && !empty($input['inside_width'])) {
            $rules['inside_width'] = ['numeric'];
        }

        $validator = Validator::vlaidate($input, $rules);

        if ($validator->fails()) {
            throw new ValidationExceptions($validator->message(), Response::BAD_REQUEST);
        }

        if ($this->service->checkDuplicateLicense($input['license_plate'])) {
            throw new ValidationExceptions(['license_plate' => 'Record Exists. Vehicle license should be unique.'],
                Response::BAD_REQUEST);
        }

        $carId = $this->service->storeCar($input);
        $content = $this->service->find($carId);

        return $response
            ->setStatusCode(Response::HTTP_CREATED)
            ->setHeaders([
                'Content-type' => 'application/json'
            ])
            ->setContent(json_encode($content));
    }
}