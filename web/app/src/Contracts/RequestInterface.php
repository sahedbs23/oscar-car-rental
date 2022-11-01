<?php

namespace App\Contracts;

interface RequestInterface
{
    /**
     * get request body for the http request.
     * @return array
     */
    public function getBody() :array;
}