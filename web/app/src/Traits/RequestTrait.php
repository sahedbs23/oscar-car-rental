<?php

namespace App\Traits;

trait RequestTrait
{
    /**
     * converts a string from snake case to camel case.
     *
     * @param string $key
     * @return string
     */
    public function toCamelCase(string $key)
    {
        $result = strtolower($key);
        preg_match_all('/_[a-z]/', $result, $matches);

        foreach ($matches[0] as $match):
            $c = str_replace('_', '', strtoupper($match));
            $result = str_replace($match, $c, $result);
        endforeach;

        return $result;
    }
}