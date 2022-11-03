<?php

namespace App\Http\Controller;

use App\Lib\Request;
use App\Lib\Response;

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