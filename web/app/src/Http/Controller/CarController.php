<?php

namespace App\Http\Controller;

use App\Lib\Request;
use App\Lib\Response;

class CarController
{

    public function index(Request $request, Response $response)
    {
        $response->send(true);
    }

    public function create()
    {
    }


    public function view(int $id)
    {
    }


    /**
     * @param Request $request
     * @param Response $response
     * @return void
     * @throws \JsonException
     */
    public function save(Request $request, Response $response)
    {
        $response
            ->setHeaders([
                'Content-type' => 'application/json',
                'Additional-header' => 'Bla-bla'
            ])
            ->setContent(json_encode($request->getBody(), JSON_THROW_ON_ERROR))
            ->send(true);
    }

}