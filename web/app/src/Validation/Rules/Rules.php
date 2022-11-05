<?php

namespace App\Validation\Rules;

class Rules
{
    /**
     * Validate input is a string value.
     * @param string $key
     * @param string|null $value
     * @return array
     */
    public function string($key, $value): array
    {
        if (!is_string($value)) {
            return [true, sprintf('The %s should be a string. %s provided', $key, $value)];
        }
        return [false, null];
    }


    /**
     * Validate input is a numeric value.
     *
     * @param string $key
     * @param string|null $value
     * @return array
     */
    public function numeric($key, $value): array
    {
        if (!is_numeric($value)) {
            return [true, sprintf('The %s should be numeric. %s provided', $key, $value)];
        }
        return [false, null];
    }

    /**
     * Validate required input.
     *
     * @param $key
     * @param $value
     * @return array
     */
    public function required($key, $value)
    {
        if (is_null($value)) {
            return [true, sprintf('The %s is required. null provided', $key)];
        }
        if (is_string($value) && trim($value) === '') {
            return [true, sprintf('The %s is required. empty value provided', $key)];
        }
        if ((is_array($value) || $value instanceof \Countable) && count($value) < 1) {
            return [true, sprintf('The %s is required. empty array provided', $key)];
        }
        return [false, null];
    }
}


