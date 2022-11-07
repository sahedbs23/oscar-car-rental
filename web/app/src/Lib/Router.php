<?php

namespace App\Lib;

use App\Contracts\RequestInterface;
use App\Contracts\ResponseInterface;
use App\Exceptions\MethodNotAllowedException;
use App\Exceptions\RouteNotFoundException;

class Router
{
    /**
     * @var RequestInterface
     */
    private RequestInterface $request;
    /**
     * @var ResponseInterface
     */
    private ResponseInterface $response;

    /**
     * @var array|string[]
     */
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
     * This function will be called when client call undefined method from this class.
     * Route->GET get methodnot exists in class. call __call method.
     * Router->POST POST method not defined in class. will call __call.
     *
     * @param $name
     * @param $args
     * @return void
     * @throws MethodNotAllowedException
     */
    public function __call($name, $args)
    {
        [$route, $method] = $args;

        if (!in_array(strtoupper($name), $this->supportedHttpMethods, true)) {
            throw new MethodNotAllowedException('Method Not Allowed', Response::METHOD_NOT_ALLOWED);
        }
        $this->{strtolower($name)}[$this->formatRoute($route)] = $method;
    }

    /**
     * Removes trailing forward slashes from the right of the route.
     * @param $route (string)
     */
    private function formatRoute($route): string
    {
        $result = rtrim($route, '/');
        if ($result === '') {
            return '/';
        }
        return $result;
    }

    /**
     * @return void
     * @throws RouteNotFoundException
     */
    public function resolve(): void
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
            throw new RouteNotFoundException('Route Not Found', Response::HTTP_NOT_FOUND);
        }

        $method($this->request, $this->response);
    }

    /**
     * find the slug route
     *
     * @param string $url
     * @param $routeLists
     * @return string|null
     */
    public function match(string $url, $routeLists): string|null
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
}