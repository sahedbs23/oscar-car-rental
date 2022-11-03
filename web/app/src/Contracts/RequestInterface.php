<?php

namespace App\Contracts;

interface RequestInterface
{
    /**
     * get request query params for the http request.
     * @return array
     */
    public function getParams() :array;

    /**
     * get request body for the http request.
     * @return array
     */
    public function getBody() :array;
}