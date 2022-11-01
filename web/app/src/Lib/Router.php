<?php
namespace App\Lib;

use App\Contracts\RequestInterface;

class Router
{
    private RequestInterface $request;

    private array $supportedHttpMethods = array(
        "GET",
        "POST"
    );

    /**
     * @param RequestInterface $request
     */
    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @param $name
     * @param $args
     * @return void
     */
    public function __call($name, $args)
    {
        [$route, $method] = $args;

        if(!in_array(strtoupper($name), $this->supportedHttpMethods, true))
        {
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
        if ($result === '')
        {
            return '/';
        }
        return $result;
    }

    private function invalidMethodHandler()
    {
        header("{$this->request->serverProtocol} 405 Method Not Allowed");
    }

    private function defaultRequestHandler()
    {
        header("{$this->request->serverProtocol} 404 Not Found");
    }

    /**
     * Resolves a route
     */
    function resolve()
    {
        $methodDictionary = $this->{strtolower($this->request->requestMethod)};
        $formatedRoute = $this->formatRoute($this->request->requestUri);
        $method = $methodDictionary[strtok($formatedRoute, '?')];

        if(is_null($method))
        {
            $this->defaultRequestHandler();
            return;
        }
        call_user_func_array($method, array($this->request));
    }

    public function __destruct()
    {
        $this->resolve();
    }
}