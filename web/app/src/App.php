<?php

namespace App;

use App\Lib\Request;
use App\Lib\Response;
use App\Lib\Router;

class App
{
    /**
     * @var Request
     */
    private Request $request;

    /**
     * @var Response
     */
    private Response $response;


    public function __construct()
    {
        $this->request  = new Request();
        $this->response  = new Response();
    }

    public static function run() :Router
    {
        $app = new self();
        return new Router($app->request, $app->response);
    }
}