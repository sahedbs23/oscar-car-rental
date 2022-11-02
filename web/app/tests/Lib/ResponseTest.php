<?php

namespace Lib;

use App\Lib\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function listOfHeader(): array
    {
        return [
            [['Content-type' => 'application/json', 'Pragma' => 'no-cache'], true, 'Content-type'],
            [['Cache-Control' => 'no-cache, no-store, max-age=0, must-revalidate'], false, '0']
        ];
    }

    public function headerProvider():array
    {
        return[
            ['Content-type' , 'text/html', "Content-type: text/html"],
            ['Pragma' , 'no-cache', "Pragma: no-cache"],
            ['Cache-Control' , 'no-cache, no-store, max-age=0, must-revalidate', "Cache-Control: no-cache, no-store, max-age=0, must-revalidate"]
        ];
    }

    public function statusTextProvider() :array
    {
        return[
            [1.1 , Response::HTTP_OK, "HTTP/1.1 200 OK"],
            [2.1 , Response::HTTP_CREATED, "HTTP/2.1 201 Created"],
            [3.1 , Response::HTTP_NOT_FOUND, "HTTP/3.1 404 Not Found"],
        ];
    }

    /**
     * @return void
     */
    public function test__construct(): void
    {
        $response = new Response('Hello', 200, [
            'Cache-Control' => 'no-cache, no-store, max-age=0, must-revalidate',
            'Pragma' => 'no-cache',
        ]);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @dataProvider headerProvider
     * @param string $key
     * @param mixed $value
     * @param $expected
     * @return void
     */
    public function test_stringifyHeader(string $key, mixed $value, $expected)
    {
        $response = new Response();
        $this->assertEquals($expected, $response->stringifyHeader($key, $value));
    }


    /**
     * @dataProvider statusTextProvider
     * @param float $version
     * @param int $statusCode
     * @param string $expected
     * @return void
     */
    public function test_stringifyStatus(float $version,int $statusCode, string $expected) :void
    {
        $response = (new Response())->setProtocolVersion($version)->setStatusCode($statusCode);
        $this->assertEquals($expected, $response->stringifyStatus());
    }

    /**
     * @return void
     */
    public function test__toString() :void
    {
        $content = 'Hello';
        $response = new Response($content);
        $this->assertEquals($content, $response->__toString());
    }


    /**
     * @dataProvider listOfHeader
     *
     * @param array $headers
     * @param bool $replace
     * @param mixed $key
     * @return void
     */
    public function testSetHeaders(array $headers, bool $replace, mixed $key): void
    {
        $response = (new Response())->setHeaders($headers, $replace);
        $headerLists = $response->getHeaders();
        $this->assertIsArray($headerLists);
        $this->assertArrayHasKey($key, $headerLists);
    }

    public function testSetStatusCode(): void
    {
        $response = new Response();
        $response->setStatusCode(500);
        $this->assertEquals(Response::HTTP_SERVER_ERROR, $response->getStatusCode());

        $response->setStatusCode(404);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testProtocolVersion():void
    {
        $response = new Response();
        $response->setProtocolVersion(1.1);
        $this->assertEquals(1.1, $response->getProtocolVersion());
    }

    public function testContent()
    {
        $response = new Response();
        $this->assertNull($response->getContent());
    }

}
