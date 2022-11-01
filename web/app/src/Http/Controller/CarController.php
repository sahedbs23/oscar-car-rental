<?php
namespace App\Http\Controller;

use App\Lib\Request;
use App\Lib\Response;

class CarController
{

    public function index()
    {
        
    }

    public function create()
    {
        
    }


    public function view(int $id)
    {
        
    }


    /**
     * @param Request $request
     * @throws \JsonException
     */
    public function save(Request $request)
    {
        (new Response())
            ->setHeaders([
            'Content-type' => 'application/json',
            'Additional-header' => 'Bla-bla'
        ])->setContent(json_encode($request->getBody(), JSON_THROW_ON_ERROR))
        ->send(true);
    }

}