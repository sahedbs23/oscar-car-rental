<?php

namespace App\Http\Controller;

use App\Exceptions\ValidationExceptions;
use App\Lib\Request;
use App\Lib\Response;
use App\Validation\Validator;

class CarController
{
    public function index(Request $request, Response $response)
    {
        $content = json_encode($request->getParams(), JSON_THROW_ON_ERROR);
        $response->setContent($content)->send(true);
    }

    public function view(Request $request, Response $response, mixed $id)
    {
        $response->setContent($id)->setProtocolVersion(2.1)->send(true);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     * @throws ValidationExceptions
     * @throws \JsonException
     */
    public function save(Request $request, Response $response)
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

        if (array_key_exists('inside_height', $input) && !empty($input['inside_height'])){
            $rules['inside_height'] = ['numeric'];
        }

        if (array_key_exists('inside_length', $input) && !empty($input['inside_length'])){
            $rules['inside_length'] = ['numeric'];
        }
        if (array_key_exists('inside_width', $input) && !empty($input['inside_width'])){
            $rules['inside_width'] = ['numeric'];
        }

        $validator = Validator::vlaidate($input, $rules);

        if ($validator->fails()) {
            throw new ValidationExceptions($validator->message(), Response::BAD_REQUEST);
        }

        $this->sendResponse(
            $response,
            Response::HTTP_CREATED,
            json_encode($request->getBody(), JSON_THROW_ON_ERROR),
            [
                'Content-type' => 'application/json'
            ]
        );
    }

    /**
     * @param Response $response
     * @param int $code
     * @param string $content
     * @param array $headers
     * @return void
     */
    public function sendResponse(Response $response, int $code, string $content, array $headers=[])
    {
        $response
            ->setStatusCode($code)
            ->setHeaders($headers)
            ->setContent($content)
            ->send(true);
    }

}