<?php

namespace Lib;

use App\Lib\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function listOfHeader(): array
    {
        return [
            [
                [
                    'Content-type' => 'application/json',
                    'Pragma' => 'no-cache'
                ]
            ]
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
     * @return void
     */
    public function test__toString() :void
    {
        $content = 'Hello';
        $response = new Response($content);
        $this->assertEquals($content, $response->__toString());
    }

    public function testSend(): void
    {
        $response = $this->getMockBuilder(Response::class)
            ->setMethods(['sendHeaders'])
            ->getMock();

        $response->expects($this->once())
            ->method('sendHeaders')
            ->with(true)
            ->willReturn(true);

//        $response = new Response('Hello', 200, [
//            'Cache-Control' => 'no-cache, no-store, max-age=0, must-revalidate',
//            'Pragma' => 'no-cache',
//        ]);
//        $response->send();
//        $this->assertTrue(headers_sent());
    }

    /**
     * @dataProvider listOfHeader
     * @param array $headers
     * @return void
     */
    public function testSetHeader(array $headers): void
    {
        $response = (new Response())->setHeaders($headers);
        $this->assertIsArray($response->getHeaders());
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
