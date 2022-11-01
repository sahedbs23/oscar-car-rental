<?php

namespace App\Lib;

use App\Contracts\RequestInterface;

class Request implements RequestInterface
{
    public function __construct()
    {
        $this->bootstrapSelf();
    }

    /**
     * set all keys in the global $_SERVER array as properties of the Request class
     * and assigns their values as well
     *
     * @return void
     */
    private function bootstrapSelf() : void
    {
        foreach ($_SERVER as $key => $value):
            $this->{$this->toCamelCase($key)} = $value;
        endforeach;
    }

    /**
     * converts a string from snake case to camel case.
     *
     * @param string $key
     * @return string
     */
    private function toCamelCase(string $key)
    {
        $result = strtolower($key);
        preg_match_all('/_[a-z]/', $result, $matches);

        foreach ($matches[0] as $match):
            $c = str_replace('_', '', strtoupper($match));
            $result = str_replace($match, $c, $result);
        endforeach;

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function getBody():array
    {
        if ($this->requestMethod !== 'POST' && $this->requestMethod !== 'PATCH') {
            return [];
        }

        $input = [];
        foreach ($_POST as $key => $value):
            $input[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        endforeach;
        return $input;
    }
}