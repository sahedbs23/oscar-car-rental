<?php

namespace Lib;

use App\Contracts\RequestInterface;
use App\Contracts\ResponseInterface;
use App\Exceptions\MethodNotAllowedException;
use App\Exceptions\RouteNotFoundException;
use App\Lib\Response;
use App\Lib\Router;
use App\Lib\Request;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    public ?Request $request;
    public ?Response $response;

    protected function setUp(): void
    {
        $this->request = $this->createMock(Request::class);
        $this->response = $this->createMock(Response::class);
    }

    protected function tearDown(): void
    {
        $this->request = null;
        $this->response = null;
    }

    public function test__construct():void
    {
        $router = new Router($this->request, $this->response);
        $this->assertInstanceOf(Router::class, $router);
    }

    /**
     * test that the call mehtod called. and appropriate properties set.
     *
     * @return void
     */
    public function test__call():void
    {
        $router = new Router($this->request, $this->response);
        $router->get('/welcome', static function (){});
        $this->assertObjectHasAttribute('get', $router);
        $this->assertIsArray($router->get);
        $this->expectException(MethodNotAllowedException::class);
        $router->PATCH('/welcome', static function (){});

    }

    /**
     * @return void
     * @throws RouteNotFoundException
     */
    public function test_resolve_throw_exception()
    {
        $this->request->requestMethod = 'GET';
        $this->request->requestUri = '/';
        $router = new Router($this->request, $this->response);
        $router->get('/welcome', static function (){});
        $this->expectException(RouteNotFoundException::class);
        $router->resolve();
    }

    /**
     * @return void
     * @throws RouteNotFoundException
     */
    public function test_resolve()
    {
        $this->request->requestMethod = 'GET';
        $this->request->requestUri = '/welcome';
        $router = new Router($this->request, $this->response);
        $res = $router->get('/welcome', function ($request, $response){
            $this->assertInstanceOf(RequestInterface::class, $request);
            $this->assertInstanceOf(ResponseInterface::class, $response);
        });
        $router->resolve();
        $this->assertNull($res);
    }


    /**
     * @return void
     * @throws RouteNotFoundException
     */
    public function test_match()
    {
        $this->request->requestMethod = 'GET';
        $this->request->requestUri = '/welcome/123';
        $router = new Router($this->request, $this->response);
        $res = $router->get('/welcome/:num', function ($request, $response, $id){
            $this->assertInstanceOf(RequestInterface::class, $request);
            $this->assertInstanceOf(ResponseInterface::class, $response);
            $this->assertEquals(123, $id);
        });
        $router->resolve();
        $this->assertNull($res);
    }
}
