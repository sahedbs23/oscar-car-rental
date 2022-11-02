<?php

namespace App\Lib;

use App\Contracts\RequestInterface;
use App\Contracts\ResponseInterface;

class Router
{
    private RequestInterface $request;
    private ResponseInterface $response;

    private array $supportedHttpMethods = array(
        "GET",
        "POST"
    );

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     */
    public function __construct(RequestInterface $request, ResponseInterface $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @param $name
     * @param $args
     * @return void
     */
    public function __call($name, $args)
    {
        [$route, $method] = $args;

        if (!in_array(strtoupper($name), $this->supportedHttpMethods, true)) {
            $this->invalidMethodHandler();
        }
        $this->{strtolower($name)}[$this->formatRoute($route)] = $method;
    }

    /**
     * Removes trailing forward slashes from the right of the route.
     * @param $route (string)
     */
    private function formatRoute($route)
    {
        $result = rtrim($route, '/');
        if ($result === '') {
            return '/';
        }
        return $result;
    }

    private function invalidMethodHandler()
    {
        $this->response->setStatusCode(Response::METHOD_NOT_ALLOWED)
            ->setContent('Method Not Allowed')
            ->send(true);
    }

    private function defaultRequestHandler(): void
    {
        $this->response->setStatusCode(Response::HTTP_NOT_FOUND)
            ->setContent('Route Not Found')
            ->send(true);
    }

    /**
     * Resolves a route
     */
    function resolve()
    {
        $requestMethod = strtolower($this->request->requestMethod);
        $methodDictionary = property_exists($this, $requestMethod) ? $this->{$requestMethod} : [];
        $formatedRoute = $this->formatRoute($this->request->requestUri);
        $method = $methodDictionary[strtok($formatedRoute, '?')] ?? null;

        if (is_null($method)) {
            $this->defaultRequestHandler();
            return;
        }

        $method($this->request, $this->response);
    }

    public function __destruct()
    {
        $this->resolve();
    }
}