<?php

namespace Lib;

use App\Lib\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{

    public function test__construct():void
    {
        $_SERVER['REQUEST_METHOD'] = 'PUT';
        $request = new Request();
        $this->assertObjectHasAttribute('requestMethod', $request);
        $this->assertEquals("PUT", $request->requestMethod);
    }

    public function testGetBody()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['location'] = 'Dhaka';
        $_POST['model'] = 'COROLLA-2007';
        $request = new Request();
        $body = $request->getBody();
        $this->assertIsArray($body);
        $this->assertArrayHasKey('location', $body);
        $this->assertArrayHasKey('model', $body);
    }


    public function testGetBodyWithGetRequest()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_POST['car_year'] = 2015;
        $_POST['fuel_type'] = 'Diesel';
        $request = new Request();
        $params = $request->getBody();
        $this->assertCount(0, $params);
        $this->assertArrayNotHasKey('car_year', $params);
        $this->assertArrayNotHasKey('fuel_type', $params);
    }

    public function testGetParams()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET['car_year'] = 2015;
        $_GET['fuel_type'] = 'Diesel';
        $request = new Request();
        $params = $request->getParams();
        $this->assertIsArray($params);
        $this->assertArrayHasKey('car_year', $params);
        $this->assertArrayHasKey('fuel_type', $params);
    }

    public function testGetParamsWithPost()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_GET['car_year'] = 2015;
        $_GET['fuel_type'] = 'Diesel';
        $request = new Request();
        $params = $request->getParams();
        $this->assertCount(0, $params);
        $this->assertArrayNotHasKey('car_year', $params);
        $this->assertArrayNotHasKey('fuel_type', $params);
    }
}
