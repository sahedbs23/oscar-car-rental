<?php

namespace App\Lib;

use App\Contracts\RequestInterface;
use App\Traits\RequestTrait;

class Request implements RequestInterface
{
    use RequestTrait;

    public function __construct()
    {
        $this->bootstrapSelf();
    }

    /**
     * @inheritDoc
     */
    public function getParams(): array
    {
        if ($this->requestMethod !== 'GET') {
            return [];
        }
        $input = [];
        foreach ($_GET as $key => $value):
            $input[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        endforeach;
        return $input;
    }

    /**
     * @inheritDoc
     */
    public function getBody(): array
    {
        if ($this->requestMethod !== 'POST') {
            return [];
        }

        $input = [];
        foreach ($_POST as $key => $value):
            $input[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        endforeach;
        return $input;
    }

    /**
     * set all keys in the global $_SERVER array as properties of the Request class
     * and assigns their values as well
     *
     * @return void
     */
    private function bootstrapSelf(): void
    {
        foreach ($_SERVER as $key => $value):
            $this->{$this->toCamelCase($key)} = $value;
        endforeach;
    }
}