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
     * Class Destructor.
     */
    public function __destruct()
    {
        $this->resolve();
    }

    /**
     * This function will be called when client call undefined method from this class.
     * Route->GET get methodnot exists in class. call __call method.
     * Router->POST POST method not defined in class. will call __call.
     *
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

    /**
     * Resolves a route
     */
    private function resolve(): void
    {
        $requestMethod = strtolower($this->request->requestMethod);
        $methodDictionary = property_exists($this, $requestMethod) ? $this->{$requestMethod} : [];
        $formatedRoute = strtok($this->formatRoute($this->request->requestUri), '?');
        $method = $methodDictionary[$formatedRoute] ?? null;

        if (is_null($method)) {
            $key = $this->match($formatedRoute, array_keys($methodDictionary));

            if ($method = $methodDictionary[$key] ?? null) {
                $ex = explode('/', $formatedRoute);
                $method($this->request, $this->response, end($ex));
                return;
            }
        }

        if (is_null($method)) {
            $this->defaultRequestHandler();
            return;
        }

        $method($this->request, $this->response);
    }

    /**
     * find the slug route
     *
     * @param string $url
     * @param $routeLists
     * @return mixed|null
     */
    private function match(string $url, $routeLists)
    {
        foreach ($routeLists as $key => $route) :
            // deal with regex's groups
            $route = str_replace(array(
                ':alnum',
                ':num',
                ':alpha',
            ), array(
                '[a-zA-Z0-9]+',
                '[0-9]+',
                '[a-zA-Z]+'
            ), $route);
            $pattern = str_replace('/', '\/', $route);
            preg_match("/$pattern/", $url, $matched, PREG_UNMATCHED_AS_NULL);
            if (array_key_exists(0, $matched) && $matched[0] === strtok($url, '?')) {
                return $routeLists[$key];
            }
        endforeach;
        return null;
    }

    /**
     * Send invalid HTTP method error response to client.
     *
     * @return void
     */
    private function invalidMethodHandler()
    {
        $this->response->setStatusCode(Response::METHOD_NOT_ALLOWED)
            ->setContent('Method Not Allowed')
            ->send(true);
    }

    /**
     * Send Route not found error response to client.
     *
     * @return void
     */
    private function defaultRequestHandler(): void
    {
        $this->response->setStatusCode(Response::HTTP_NOT_FOUND)
            ->setContent('Route Not Found')
            ->send(true);
    }

}